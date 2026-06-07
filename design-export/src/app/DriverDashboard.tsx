import {
  Car, MapPin, Users, Phone, Navigation,
  CheckCircle2, Play, CheckCircle,
  Clock, Calendar,
  Wallet, Banknote, AlertCircle,
  ArrowRight, PackageCheck, MapPinned
} from "lucide-react";
import { useState, useEffect, useRef, useMemo } from "react";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// --- Types ---
interface Passenger {
  id: string;
  name: string;
  phone: string;
  pickup: string;
  destination: string;
  pax: number;
  totalFare: number;
  dpPaid: number;
  remainingPayment: number;
  paymentStatus: "DP Lunas" | "Lunas";
  pickupStatus: "Waiting Pickup" | "Picked Up";
  dropoffStatus: "Waiting Dropoff" | "Dropped Off";
  pickupCoords: [number, number];
  destCoords: [number, number];
}

interface AssignedTrip {
  id: string;
  date: string;
  route: string;
  shift: "Pagi" | "Malam";
  time: string;
  vehicle: { plate: string; model: string };
  status: "Assigned" | "On Trip" | "Completed";
  passengers: Passenger[];
}

// --- Mock Data ---
const mockActiveTrip: AssignedTrip = {
  id: "TRP-102",
  date: "2026-05-26",
  route: "Padang Panjang → Pekanbaru",
  shift: "Pagi",
  time: "10:00 WIB",
  vehicle: { plate: "BA 1234 XY", model: "Toyota Avanza" },
  status: "Assigned",
  passengers: [
    {
      id: "BKG-101", name: "Budi Santoso", phone: "0812-3456-7890",
      pickup: "Jl. Sudirman No. 12, Padang Panjang", destination: "Terminal AKAP, Pekanbaru",
      pax: 2, totalFare: 400000, dpPaid: 50000, remainingPayment: 350000,
      paymentStatus: "DP Lunas",
      pickupStatus: "Waiting Pickup", dropoffStatus: "Waiting Dropoff",
      pickupCoords: [-0.4635, 100.3957], destCoords: [0.5071, 101.4478],
    },
    {
      id: "BKG-102", name: "Siti Rahma", phone: "0821-9988-7766",
      pickup: "Simpang PDG Panjang (Dekat SPBU)", destination: "Mall SKA, Pekanbaru",
      pax: 1, totalFare: 200000, dpPaid: 200000, remainingPayment: 0,
      paymentStatus: "Lunas",
      pickupStatus: "Waiting Pickup", dropoffStatus: "Waiting Dropoff",
      pickupCoords: [-0.4678, 100.3990], destCoords: [0.5180, 101.4400],
    },
    {
      id: "BKG-105", name: "Reza Pratama", phone: "0852-1122-3344",
      pickup: "Loket Singgalang Padang Panjang", destination: "Loket Singgalang Pekanbaru",
      pax: 2, totalFare: 400000, dpPaid: 50000, remainingPayment: 350000,
      paymentStatus: "DP Lunas",
      pickupStatus: "Waiting Pickup", dropoffStatus: "Waiting Dropoff",
      pickupCoords: [-0.4590, 100.4020], destCoords: [0.5230, 101.4550],
    },
  ]
};

const mockNextTrip = {
  id: "TRP-105",
  date: "2026-05-27",
  route: "Pekanbaru → Padang Panjang",
  shift: "Pagi" as const,
  time: "09:00 WIB",
};

type TripPhase = "ready" | "pickup" | "delivering" | "ready-to-complete";

export function DriverDashboard() {
  const [activeTrip, setActiveTrip] = useState<AssignedTrip>(mockActiveTrip);
  const [selectedPassengerId, setSelectedPassengerId] = useState<string>(mockActiveTrip.passengers[0].id);
  const [isCompleteModalOpen, setIsCompleteModalOpen] = useState(false);
  const [dropoffConfirm, setDropoffConfirm] = useState<Passenger | null>(null);
  const [toast, setToast] = useState<string | null>(null);

  const mapRef = useRef<L.Map | null>(null);
  const mapContainerRef = useRef<HTMLDivElement>(null);
  const markersRef = useRef<{ [key: string]: L.Marker }>({});

  const selectedPassenger = useMemo(() =>
    activeTrip.passengers.find(p => p.id === selectedPassengerId) || activeTrip.passengers[0]
  , [activeTrip.passengers, selectedPassengerId]);

  const totalPax = useMemo(() =>
    activeTrip.passengers.reduce((sum, p) => sum + p.pax, 0)
  , [activeTrip.passengers]);

  const pickedUpCount = activeTrip.passengers.filter(p => p.pickupStatus === "Picked Up").length;
  const droppedOffCount = activeTrip.passengers.filter(p => p.dropoffStatus === "Dropped Off").length;
  const totalPassengers = activeTrip.passengers.length;
  const allPickedUp = pickedUpCount === totalPassengers && totalPassengers > 0;
  const allDroppedOff = droppedOffCount === totalPassengers && totalPassengers > 0;

  const totalCollected = useMemo(() =>
    activeTrip.passengers.filter(p => p.dropoffStatus === 'Dropped Off').reduce((sum, p) => sum + p.remainingPayment, 0)
  , [activeTrip.passengers]);

  // Derive trip phase
  const phase: TripPhase = useMemo(() => {
    if (activeTrip.status === "Assigned") return "ready";
    if (!allPickedUp) return "pickup";
    if (!allDroppedOff) return "delivering";
    return "ready-to-complete";
  }, [activeTrip.status, allPickedUp, allDroppedOff]);

  const isDeliveringMap = phase === "delivering" || phase === "ready-to-complete";

  // Coords getter per phase
  const coordsFor = (p: Passenger): [number, number] => isDeliveringMap ? p.destCoords : p.pickupCoords;
  const addressFor = (p: Passenger): string => isDeliveringMap ? p.destination : p.pickup;

  // --- Map init ---
  useEffect(() => {
    if (!mapContainerRef.current || mapRef.current) return;

    const map = L.map(mapContainerRef.current, { zoomControl: false, attributionControl: false }).setView(coordsFor(selectedPassenger), 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    mapRef.current = map;

    return () => { mapRef.current?.remove(); mapRef.current = null; };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // --- Re-render markers when phase or passengers change ---
  useEffect(() => {
    if (!mapRef.current) return;
    // Clear old
    Object.values(markersRef.current).forEach(m => m.remove());
    markersRef.current = {};

    const IconBlue = L.icon({
      iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
      iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
      iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    activeTrip.passengers.forEach(p => {
      const c = coordsFor(p);
      const label = isDeliveringMap ? p.destination : p.pickup;
      const m = L.marker(c, { icon: IconBlue }).addTo(mapRef.current!).bindPopup(`<b>${p.name}</b><br>${label}`);
      markersRef.current[p.id] = m;
    });

    // Re-center on selected
    if (selectedPassenger) {
      mapRef.current.flyTo(coordsFor(selectedPassenger), 15, { animate: true });
      markersRef.current[selectedPassenger.id]?.openPopup();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isDeliveringMap, activeTrip.passengers.length]);

  // --- Re-center on selection change ---
  useEffect(() => {
    if (mapRef.current && selectedPassenger) {
      mapRef.current.flyTo(coordsFor(selectedPassenger), 15, { animate: true });
      markersRef.current[selectedPassenger.id]?.openPopup();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selectedPassengerId, isDeliveringMap]);

  // --- Actions ---
  const markPickedUp = (id: string) => {
    setActiveTrip(prev => ({
      ...prev,
      passengers: prev.passengers.map(p =>
        p.id === id ? { ...p, pickupStatus: "Picked Up" } : p
      )
    }));
    showToast("Penumpang ditandai dijemput.");
  };

  const markDroppedOff = (p: Passenger) => {
    setActiveTrip(prev => ({
      ...prev,
      passengers: prev.passengers.map(x =>
        x.id === p.id ? { ...x, dropoffStatus: "Dropped Off", remainingPayment: 0, paymentStatus: "Lunas" } : x
      )
    }));
    setDropoffConfirm(null);
    showToast(`Sisa pembayaran ${p.name} diterima. Penumpang diturunkan.`);
  };

  const startTrip = () => {
    setActiveTrip(v => ({ ...v, status: 'On Trip' }));
    showToast("Trip dimulai. Mode Penjemputan aktif.");
  };

  const completeTrip = () => {
    setIsCompleteModalOpen(false);
    setActiveTrip(v => ({ ...v, status: 'Completed' }));
    showToast("Trip selesai. Data dipindahkan ke Riwayat Trip.");
  };

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(null), 3000);
  };

  const formatCurrency = (amount: number) =>
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);

  // --- Phase chip ---
  const phaseLabel: Record<TripPhase, string> = {
    "ready": "Ready to Start",
    "pickup": "Pickup Mode",
    "delivering": "Delivering Mode",
    "ready-to-complete": "Siap Diselesaikan",
  };
  const phaseColor: Record<TripPhase, string> = {
    "ready": "bg-white/10",
    "pickup": "bg-blue-600",
    "delivering": "bg-amber-500",
    "ready-to-complete": "bg-emerald-500",
  };

  return (
    <div className="flex flex-col space-y-10 font-poppins relative">

      {/* Top Section: Active & Next Cards */}
      <div className="flex flex-col lg:flex-row gap-6">
        <div className="flex-1 bg-slate-900 text-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-900/20 relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full -mr-32 -mt-32 blur-3xl group-hover:bg-blue-600/20 transition-all duration-500"></div>

          <div className="relative z-10">
            <div className="flex items-center justify-between mb-8">
              <div className="flex items-center gap-4">
                <div className="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-600/30">
                  <Car className="w-7 h-7" />
                </div>
                <div>
                  <p className="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-1">Active Trip Now</p>
                  <h2 className="text-xl font-black">{activeTrip.id}</h2>
                </div>
              </div>
              <span className={`px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/10 ${phaseColor[phase]}`}>
                {phaseLabel[phase]}
              </span>
            </div>

            <h3 className="text-3xl font-black mb-6 leading-tight">{activeTrip.route}</h3>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div className="bg-white/5 border border-white/5 p-4 rounded-3xl">
                <Clock className="w-4 h-4 text-blue-400 mb-2" />
                <p className="text-[10px] font-black text-white/30 uppercase tracking-tighter">Waktu</p>
                <p className="text-xs font-black">{activeTrip.time}</p>
              </div>
              <div className="bg-white/5 border border-white/5 p-4 rounded-3xl">
                <Users className="w-4 h-4 text-blue-400 mb-2" />
                <p className="text-[10px] font-black text-white/30 uppercase tracking-tighter">Penumpang</p>
                <p className="text-xs font-black">{totalPax}/5 PAX</p>
              </div>
              <div className="bg-white/5 border border-white/5 p-4 rounded-3xl">
                <Wallet className="w-4 h-4 text-emerald-400 mb-2" />
                <p className="text-[10px] font-black text-white/30 uppercase tracking-tighter">Diterima</p>
                <p className="text-xs font-black text-emerald-400">{formatCurrency(totalCollected)}</p>
              </div>
              <div className="bg-white/5 border border-white/5 p-4 rounded-3xl">
                <Calendar className="w-4 h-4 text-amber-400 mb-2" />
                <p className="text-[10px] font-black text-white/30 uppercase tracking-tighter">Tanggal</p>
                <p className="text-xs font-black">{activeTrip.date}</p>
              </div>
            </div>

            {/* Trip Progress */}
            <div className="mt-8 bg-white/5 border border-white/10 rounded-3xl p-5">
              <div className="flex items-center justify-between mb-3">
                <p className="text-[10px] font-black text-white/50 uppercase tracking-[0.2em]">Progress Trip</p>
                <p className="text-[10px] font-black text-white/70 uppercase tracking-widest">{droppedOffCount}/{totalPassengers} Selesai</p>
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <div className="flex items-center justify-between mb-1.5">
                    <span className="text-[10px] font-bold text-white/40 uppercase tracking-widest">Dijemput</span>
                    <span className="text-[10px] font-black text-blue-300">{pickedUpCount}/{totalPassengers}</span>
                  </div>
                  <div className="h-2 bg-white/10 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-blue-500 transition-all duration-500"
                      style={{ width: `${(pickedUpCount / totalPassengers) * 100}%` }}
                    />
                  </div>
                </div>
                <div>
                  <div className="flex items-center justify-between mb-1.5">
                    <span className="text-[10px] font-bold text-white/40 uppercase tracking-widest">Diturunkan</span>
                    <span className="text-[10px] font-black text-emerald-300">{droppedOffCount}/{totalPassengers}</span>
                  </div>
                  <div className="h-2 bg-white/10 rounded-full overflow-hidden">
                    <div
                      className="h-full bg-emerald-500 transition-all duration-500"
                      style={{ width: `${(droppedOffCount / totalPassengers) * 100}%` }}
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Next Trip Card */}
        <div className="lg:w-96 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col justify-between">
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center">
                <ArrowRight className="w-5 h-5" />
              </div>
              <h3 className="text-xs font-black text-slate-400 uppercase tracking-widest">Next Assignment</h3>
            </div>
            <h4 className="text-lg font-black text-slate-900 mb-2">{mockNextTrip.route}</h4>
            <p className="text-xs font-bold text-slate-400 mb-6">{mockNextTrip.date} • {mockNextTrip.shift} ({mockNextTrip.time})</p>
          </div>
          <div className="bg-blue-50/50 p-4 rounded-2xl border border-blue-50 flex items-center gap-3">
             <AlertCircle className="w-4 h-4 text-blue-500" />
             <p className="text-[10px] font-bold text-blue-700 leading-tight">Pastikan istirahat cukup sebelum trip berikutnya dimulai.</p>
          </div>
        </div>
      </div>

      {/* Main Content Area: Manifest & Map */}
      <div className="flex flex-col xl:flex-row gap-8 min-h-[700px]">

        {/* Left: Passenger List */}
        <div className="flex-1 flex flex-col gap-6">
          <div className="flex items-center justify-between px-2">
            <h3 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
              {isDeliveringMap
                ? <><PackageCheck className="w-5 h-5 text-amber-600" /> Penurunan Penumpang</>
                : <><Users className="w-5 h-5 text-blue-600" /> Penjemputan Penumpang</>}
            </h3>
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{totalPassengers} Terdaftar</span>
          </div>

          <div className="grid md:grid-cols-2 gap-4">
            {activeTrip.passengers.map((p, index) => {
              const isPickedUp = p.pickupStatus === "Picked Up";
              const isDroppedOff = p.dropoffStatus === "Dropped Off";
              const isSelected = selectedPassengerId === p.id;
              return (
                <div
                  key={p.id}
                  onClick={() => setSelectedPassengerId(p.id)}
                  className={`p-6 rounded-[2.5rem] border transition-all cursor-pointer relative flex flex-col ${
                    isSelected
                      ? 'border-blue-500 bg-blue-50/30 ring-4 ring-blue-500/5'
                      : 'border-white bg-white hover:border-blue-200'
                  } ${isDroppedOff ? 'bg-emerald-50/40 border-emerald-100' : ''}`}
                >
                  <div className="flex items-start justify-between mb-5">
                    <div className="flex items-center gap-4">
                      <div className={`w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-black shadow-sm ${
                        isDroppedOff ? 'bg-emerald-500 text-white' : isPickedUp ? 'bg-blue-600 text-white' : 'bg-slate-900 text-white'
                      }`}>
                        {index + 1}
                      </div>
                      <div>
                        <h4 className="text-base font-black text-slate-900 leading-tight">{p.name}</h4>
                        <p className="text-[10px] font-bold text-slate-400 mt-1">{p.phone}</p>
                      </div>
                    </div>
                    <div className="flex flex-col items-end gap-1">
                      <span className={`px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight border ${
                        isPickedUp ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 text-slate-500 border-slate-200'
                      }`}>
                        {isPickedUp ? 'Jemput ✓' : 'Belum'}
                      </span>
                      <span className={`px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight border ${
                        isDroppedOff ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-slate-100 text-slate-500 border-slate-200'
                      }`}>
                        {isDroppedOff ? 'Turun ✓' : 'Belum'}
                      </span>
                    </div>
                  </div>

                  {/* Address rows */}
                  <div className="space-y-2 mb-4">
                    <div className="flex items-start gap-2 text-[10px] font-bold text-slate-600">
                      <span className="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1 shrink-0"></span>
                      <p className="truncate">{p.pickup}</p>
                    </div>
                    <div className="flex items-start gap-2 text-[10px] font-bold text-slate-600">
                      <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1 shrink-0"></span>
                      <p className="truncate">{p.destination}</p>
                    </div>
                  </div>

                  {/* Payment */}
                  <div className="bg-white/50 rounded-2xl border border-slate-100 p-4 grid grid-cols-2 gap-4 mb-5">
                    <div>
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Total</p>
                      <p className="text-xs font-black text-slate-900">{formatCurrency(p.totalFare)}</p>
                    </div>
                    <div className="text-right">
                      <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Sisa</p>
                      <p className={`text-xs font-black ${p.remainingPayment > 0 ? 'text-red-600' : 'text-emerald-600'}`}>
                        {p.remainingPayment === 0 ? 'LUNAS' : formatCurrency(p.remainingPayment)}
                      </p>
                    </div>
                  </div>

                  {/* Actions */}
                  <div className="flex gap-2 mt-auto pt-2">
                    {/* Pickup phase action */}
                    {!isPickedUp && (
                      <button
                        onClick={(e) => { e.stopPropagation(); markPickedUp(p.id); }}
                        className="flex-1 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all"
                      >
                        Mark Picked Up
                      </button>
                    )}

                    {/* Delivering phase action */}
                    {isPickedUp && !isDroppedOff && (
                      <button
                        onClick={(e) => { e.stopPropagation(); setDropoffConfirm(p); }}
                        disabled={activeTrip.status !== "On Trip"}
                        className="flex-1 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-amber-500 text-white shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none disabled:cursor-not-allowed"
                      >
                        Mark Dropped Off
                      </button>
                    )}

                    {/* Done state */}
                    {isDroppedOff && (
                      <div className="flex-1 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200 text-center">
                        Selesai
                      </div>
                    )}

                    <button
                      onClick={(e) => { e.stopPropagation(); window.open(`tel:${p.phone}`); }}
                      className="w-12 h-12 flex items-center justify-center bg-slate-50 text-emerald-600 border border-slate-100 rounded-2xl hover:bg-emerald-50 transition-colors"
                      title="Hubungi penumpang"
                    >
                      <Phone className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Right: Interactive Map */}
        <div className="xl:w-[500px] flex flex-col gap-6">
           <div className="flex items-center gap-3 px-2">
              {isDeliveringMap
                ? <MapPinned className="w-5 h-5 text-amber-600" />
                : <MapIcon className="w-5 h-5 text-blue-600" />}
              <h3 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">
                {isDeliveringMap ? "Lokasi Pengantaran" : "Lokasi Penjemputan"}
              </h3>
           </div>

           <div className="flex-1 min-h-[400px] bg-white rounded-[3rem] border border-slate-100 overflow-hidden relative shadow-xl shadow-slate-900/5">
              <div ref={mapContainerRef} className="w-full h-full z-10" />

              {/* Floating Map Info */}
              <div className="absolute bottom-6 left-6 right-6 z-[1000]">
                <div className="bg-white/95 backdrop-blur-md rounded-[2rem] p-6 shadow-2xl border border-white">
                  <div className="flex items-center gap-4 mb-4">
                    <div className={`w-10 h-10 rounded-xl flex items-center justify-center border ${
                      isDeliveringMap
                        ? "bg-amber-50 text-amber-600 border-amber-100"
                        : "bg-blue-50 text-blue-600 border-blue-100"
                    }`}>
                      <MapPin className="w-5 h-5" />
                    </div>
                    <div className="overflow-hidden">
                      <p className="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                        {isDeliveringMap ? "Fokus Pengantaran" : "Fokus Penjemputan"}
                      </p>
                      <p className="text-xs font-black text-slate-900 truncate">{selectedPassenger.name}</p>
                    </div>
                  </div>
                  <p className="text-[10px] text-slate-600 italic font-medium mb-6 line-clamp-2">"{addressFor(selectedPassenger)}"</p>
                  <button
                    onClick={() => window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(addressFor(selectedPassenger))}`, '_blank')}
                    className={`w-full py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all text-white ${
                      isDeliveringMap
                        ? "bg-amber-600 hover:bg-amber-700 shadow-lg shadow-amber-600/20"
                        : "bg-slate-900 hover:bg-slate-800"
                    }`}
                  >
                    <Navigation className="w-4 h-4" />
                    {isDeliveringMap ? "Navigate to Destination" : "Navigate to Pickup"}
                  </button>
                </div>
              </div>
           </div>
        </div>
      </div>

      {/* Global Action Bar */}
      <div className="sticky bottom-4 bg-white border border-slate-100 p-6 flex items-center justify-center gap-4 z-[50] rounded-[3rem] shadow-2xl">
        <div className="max-w-[1200px] w-full flex items-center justify-between gap-6 px-4">
           <div className="hidden md:flex items-center gap-6">
              <div>
                 <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Dijemput</p>
                 <p className="text-sm font-black text-blue-600">{pickedUpCount}/{totalPassengers}</p>
              </div>
              <div className="w-px h-8 bg-slate-100"></div>
              <div>
                 <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Diturunkan</p>
                 <p className="text-sm font-black text-emerald-600">{droppedOffCount}/{totalPassengers}</p>
              </div>
              <div className="w-px h-8 bg-slate-100"></div>
              <div>
                 <p className="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Diterima</p>
                 <p className="text-sm font-black text-slate-900">{formatCurrency(totalCollected)}</p>
              </div>
           </div>

           <div className="flex-1 md:flex-none flex gap-4">
             {activeTrip.status === "Assigned" ? (
               <button
                 onClick={startTrip}
                 className="w-full md:w-80 flex items-center justify-center gap-3 px-10 py-5 bg-blue-900 text-white rounded-[2rem] font-black text-xs hover:bg-blue-950 transition-all shadow-xl shadow-blue-900/20"
               >
                 <Play className="w-5 h-5 fill-current" /> MULAI TRIP SEKARANG
               </button>
             ) : (
               <button
                 onClick={() => setIsCompleteModalOpen(true)}
                 disabled={!allDroppedOff}
                 className="w-full md:w-80 flex items-center justify-center gap-3 px-10 py-5 bg-emerald-600 text-white rounded-[2rem] font-black text-xs hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none disabled:cursor-not-allowed"
                 title={allDroppedOff ? "Selesaikan perjalanan" : `Tandai semua penumpang turun dulu (${droppedOffCount}/${totalPassengers})`}
               >
                 <CheckCircle className="w-5 h-5" />
                 {allDroppedOff ? "SELESAIKAN PERJALANAN" : `TURUNKAN SEMUA DULU (${droppedOffCount}/${totalPassengers})`}
               </button>
             )}
           </div>
        </div>
      </div>

      {/* Dropoff + Collect Payment Confirmation */}
      {dropoffConfirm && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
            <div className="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mx-auto mb-5">
              <Banknote className="w-8 h-8" />
            </div>
            <h3 className="text-xl font-black text-slate-900 text-center mb-2">Tandai Diturunkan?</h3>
            <p className="text-sm font-bold text-slate-400 text-center mb-6 leading-relaxed">
              Pastikan sisa pembayaran <span className="font-black text-slate-900">{dropoffConfirm.name}</span> sudah diterima sebelum penumpang ditandai turun.
            </p>
            <div className="bg-slate-50 rounded-2xl p-4 mb-6 space-y-3">
              <div className="flex items-center justify-between">
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Tarif</span>
                <span className="text-sm font-black text-slate-900">{formatCurrency(dropoffConfirm.totalFare)}</span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">DP Sudah Dibayar</span>
                <span className="text-sm font-black text-slate-700">- {formatCurrency(dropoffConfirm.dpPaid)}</span>
              </div>
              <div className="flex items-center justify-between pt-3 border-t border-slate-200">
                <span className="text-[10px] font-black text-amber-700 uppercase tracking-widest">Tagih Sekarang</span>
                <span className="text-lg font-black text-amber-600">{formatCurrency(dropoffConfirm.remainingPayment)}</span>
              </div>
            </div>
            <div className="grid grid-cols-2 gap-3">
              <button
                onClick={() => setDropoffConfirm(null)}
                className="py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all"
              >
                Batal
              </button>
              <button
                onClick={() => markDroppedOff(dropoffConfirm)}
                className="py-3.5 bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-amber-600/20 hover:bg-amber-700 transition-all"
              >
                Sudah Terima
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Complete Trip Modal */}
      {isCompleteModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
            <div className="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-5">
              <CheckCircle className="w-8 h-8" />
            </div>
            <h3 className="text-xl font-black text-slate-900 text-center mb-2">Selesaikan Perjalanan?</h3>
            <p className="text-sm font-bold text-slate-500 text-center mb-6 leading-relaxed">
              Trip <span className="font-black text-slate-900">{activeTrip.id}</span> akan ditandai selesai dan dipindahkan ke Riwayat Trip.
            </p>
            <div className="bg-slate-50 rounded-2xl p-4 mb-6 grid grid-cols-2 gap-4">
              <div>
                <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Penumpang</p>
                <p className="text-sm font-black text-slate-900">{droppedOffCount}/{totalPassengers} diturunkan</p>
              </div>
              <div className="text-right">
                <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Diterima</p>
                <p className="text-sm font-black text-emerald-600">{formatCurrency(totalCollected)}</p>
              </div>
            </div>
            <div className="grid grid-cols-2 gap-3">
              <button
                onClick={() => setIsCompleteModalOpen(false)}
                className="py-3.5 bg-slate-100 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all"
              >
                Belum
              </button>
              <button
                onClick={completeTrip}
                className="py-3.5 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all"
              >
                Ya, Selesai
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Toast */}
      {toast && (
        <div className="fixed bottom-6 right-6 z-[400] px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 bg-emerald-600 text-white animate-in slide-in-from-bottom-4 fade-in duration-300">
          <CheckCircle2 className="w-5 h-5" />
          <p className="text-xs font-bold">{toast}</p>
        </div>
      )}
    </div>
  );
}

function MapIcon(props: any) {
  return (
    <svg {...props} xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21" /><line x1="9" y1="3" x2="9" y2="18" /><line x1="15" y1="6" x2="15" y2="21" /></svg>
  );
}
