import {
  User,
  Phone,
  MapPin,
  Map,
  Calendar,
  Users,
  Car,
  ChevronRight,
  Sun,
  Moon,
  Info,
  Crosshair,
} from "lucide-react";
import { useState, useEffect, useRef } from "react";
import { useSearchParams, useNavigate } from "react-router";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

// --- Default coordinates per direction ---
const DEFAULTS: Record<string, { pickup: [number, number]; destination: [number, number] }> = {
  "Padang Panjang → Pekanbaru": {
    pickup: [-0.4635, 100.4040],       // Padang Panjang
    destination: [0.5071, 101.4478],   // Pekanbaru
  },
  "Pekanbaru → Padang Panjang": {
    pickup: [0.5071, 101.4478],
    destination: [-0.4635, 100.4040],
  },
};

export function BookingForm() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  // Form state
  const [route, setRoute] = useState("Padang Panjang → Pekanbaru");
  const [pickupAddress, setPickupAddress] = useState("");
  const [destinationAddress, setDestinationAddress] = useState("");
  const [pickupCoords, setPickupCoords] = useState<[number, number]>(DEFAULTS[route].pickup);
  const [destinationCoords, setDestinationCoords] = useState<[number, number]>(DEFAULTS[route].destination);
  const [shift, setShift] = useState(searchParams.get("shift") === "malam" ? "malam" : "pagi");
  const [passengers, setPassengers] = useState(1);

  // When route changes, snap default coords to that direction
  useEffect(() => {
    const d = DEFAULTS[route];
    if (d) {
      setPickupCoords(d.pickup);
      setDestinationCoords(d.destination);
    }
  }, [route]);

  // Derive summary values — tarif tetap Rp 150.000 / penumpang
  const basePerSeat = 150000;
  const totalPrice = passengers * basePerSeat;
  const dpAmount = 50000;
  const remaining = Math.max(totalPrice - dpAmount, 0);
  const shiftLabel = shift === "malam" ? "Malam (20.00 WIB)" : "Pagi (08.00 WIB)";

  const formatCurrency = (value: number) =>
    new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(value);

  const handleNext = (e: React.FormEvent) => {
    e.preventDefault();
    const encodedRoute = encodeURIComponent(route);
    const params = new URLSearchParams({
      shift,
      route,
      pickupLat: pickupCoords[0].toString(),
      pickupLng: pickupCoords[1].toString(),
      destLat: destinationCoords[0].toString(),
      destLng: destinationCoords[1].toString(),
    });
    navigate(`/booking/detail?${params.toString()}`);
  };

  return (
    <div className="py-12 md:py-20 bg-slate-50 min-h-screen">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Step Indicator */}
        <div className="flex items-center justify-center mb-12 overflow-x-auto pb-4">
          <div className="flex items-center gap-2 sm:gap-4 text-sm font-bold min-w-max">
            <div className="flex items-center gap-2 text-blue-600">
              <div className="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs shadow-sm shadow-blue-600/20">1</div>
              <span>Booking</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-slate-400">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">2</div>
              <span>Detail Review</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-slate-400">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">3</div>
              <span>Pembayaran DP</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-slate-400">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">4</div>
              <span>Verifikasi</span>
            </div>
          </div>
        </div>

        {/* Top Section */}
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h1 className="text-slate-900 font-extrabold text-3xl md:text-4xl tracking-tight mb-3">
            Form Pemesanan Travel
          </h1>
          <p className="text-slate-500 text-lg font-medium">
            Lengkapi data perjalanan Anda
          </p>
        </div>

        {/* Main Layout: Form and Summary */}
        <form onSubmit={handleNext} className="grid lg:grid-cols-12 gap-8 items-start">

          {/* LEFT SIDE - BOOKING FORM */}
          <div className="lg:col-span-8 space-y-6">

            {/* Customer Information Card */}
            <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
              <div className="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                  <User className="w-5 h-5" />
                </div>
                <h2 className="text-xl font-extrabold text-slate-900 tracking-tight">Informasi Pemesan</h2>
              </div>
              <div className="p-6 md:p-8 space-y-6">
                <div className="grid md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <div className="relative">
                      <User className="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                      <input
                        type="text"
                        required
                        placeholder="Contoh: Budi Santoso"
                        className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-slate-400"
                      />
                    </div>
                  </div>
                  <div className="space-y-2">
                    <label className="text-sm font-bold text-slate-700">Nomor HP / WhatsApp</label>
                    <div className="relative">
                      <Phone className="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                      <input
                        type="tel"
                        required
                        placeholder="Contoh: 081234567890"
                        className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-slate-400"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Trip Information Card */}
            <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
              <div className="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                  <Map className="w-5 h-5" />
                </div>
                <h2 className="text-xl font-extrabold text-slate-900 tracking-tight">Detail Perjalanan</h2>
              </div>
              <div className="p-6 md:p-8 space-y-6">

                <div className="space-y-2">
                  <label className="text-sm font-bold text-slate-700">Rute Perjalanan</label>
                  <div className="relative">
                    <Map className="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                    <select
                      value={route}
                      onChange={(e) => setRoute(e.target.value)}
                      className="w-full pl-11 pr-8 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all appearance-none cursor-pointer"
                    >
                      <option value="Padang Panjang → Pekanbaru">Padang Panjang → Pekanbaru</option>
                      <option value="Pekanbaru → Padang Panjang">Pekanbaru → Padang Panjang</option>
                    </select>
                  </div>
                </div>

                {/* Pickup Location Picker */}
                <LocationPicker
                  label="Lokasi Jemput"
                  accent="blue"
                  address={pickupAddress}
                  onAddressChange={setPickupAddress}
                  coords={pickupCoords}
                  onCoordsChange={setPickupCoords}
                  placeholder="Alamat lengkap penjemputan..."
                />

                {/* Destination Location Picker */}
                <LocationPicker
                  label="Lokasi Tujuan"
                  accent="emerald"
                  address={destinationAddress}
                  onAddressChange={setDestinationAddress}
                  coords={destinationCoords}
                  onCoordsChange={setDestinationCoords}
                  placeholder="Alamat lengkap tujuan..."
                />

                <div className="grid md:grid-cols-2 gap-6">
                  <div className="space-y-2">
                    <label className="text-sm font-bold text-slate-700">Tanggal Keberangkatan</label>
                    <div className="relative">
                      <Calendar className="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                      <input
                        type="date"
                        required
                        className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all"
                      />
                    </div>
                  </div>

                  <div className="space-y-2">
                    <label className="text-sm font-bold text-slate-700">Pilih Shift</label>
                    <div className="grid grid-cols-2 gap-3">
                      <button
                        type="button"
                        onClick={() => setShift("pagi")}
                        className={`flex items-center justify-center gap-2 py-3 rounded-xl border text-sm font-bold transition-all ${
                          shift === "pagi"
                            ? "bg-blue-50 border-blue-600 text-blue-700 ring-2 ring-blue-600/20"
                            : "bg-white border-slate-200 text-slate-500 hover:bg-slate-50"
                        }`}
                      >
                        <Sun className={`w-4 h-4 ${shift === "pagi" ? "text-blue-600" : "text-slate-400"}`} />
                        Pagi
                      </button>
                      <button
                        type="button"
                        onClick={() => setShift("malam")}
                        className={`flex items-center justify-center gap-2 py-3 rounded-xl border text-sm font-bold transition-all ${
                          shift === "malam"
                            ? "bg-slate-900 border-slate-900 text-white ring-2 ring-slate-900/20"
                            : "bg-white border-slate-200 text-slate-500 hover:bg-slate-50"
                        }`}
                      >
                        <Moon className={`w-4 h-4 ${shift === "malam" ? "text-white" : "text-slate-400"}`} />
                        Malam
                      </button>
                    </div>
                  </div>

                  <div className="space-y-2 md:col-span-2">
                    <label className="text-sm font-bold text-slate-700">Jumlah Penumpang</label>
                    <div className="relative">
                      <Users className="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" />
                      <select
                        value={passengers}
                        onChange={(e) => setPassengers(Number(e.target.value))}
                        className="w-full pl-11 pr-8 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all appearance-none cursor-pointer"
                      >
                        {[1, 2, 3, 4, 5].map((num) => (
                          <option key={num} value={num}>{num} Orang</option>
                        ))}
                      </select>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          {/* RIGHT SIDE - SUMMARY CARD */}
          <div className="lg:col-span-4 relative">
            <div className="sticky top-28 bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
              <div className="p-6 md:p-8">
                <h3 className="text-lg font-extrabold text-slate-900 tracking-tight mb-6">Ringkasan Perjalanan</h3>

                <div className="space-y-5">
                  <div>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Rute Perjalanan</p>
                    <p className="text-base font-bold text-slate-900">{route}</p>
                  </div>

                  <div className="space-y-4 border-t border-slate-100 pt-5">
                    <div>
                      <div className="flex items-center gap-2 mb-1">
                        <span className="w-2 h-2 rounded-full bg-blue-600"></span>
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Jemput</p>
                      </div>
                      <p className="text-sm font-semibold text-slate-900 line-clamp-2">{pickupAddress || "Belum diisi"}</p>
                      <p className="text-[10px] font-mono text-slate-400 mt-0.5">
                        {pickupCoords[0].toFixed(5)}, {pickupCoords[1].toFixed(5)}
                      </p>
                    </div>
                    <div>
                      <div className="flex items-center gap-2 mb-1">
                        <span className="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Tujuan</p>
                      </div>
                      <p className="text-sm font-semibold text-slate-900 line-clamp-2">{destinationAddress || "Belum diisi"}</p>
                      <p className="text-[10px] font-mono text-slate-400 mt-0.5">
                        {destinationCoords[0].toFixed(5)}, {destinationCoords[1].toFixed(5)}
                      </p>
                    </div>
                  </div>

                  <div className="flex justify-between items-center border-t border-slate-100 pt-5">
                    <div>
                      <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Shift</p>
                      <div className="flex items-center gap-1.5 text-slate-900 font-bold">
                        {shift === "pagi" ? <Sun className="w-4 h-4 text-blue-600" /> : <Moon className="w-4 h-4 text-slate-900" />}
                        {shiftLabel}
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Penumpang</p>
                      <p className="text-base font-bold text-slate-900">{passengers} Kursi</p>
                    </div>
                  </div>

                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center gap-4">
                    <div className="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100 shrink-0">
                      <Car className="w-6 h-6 text-slate-700" />
                    </div>
                    <div>
                      <p className="font-bold text-slate-900 text-sm mb-0.5">Toyota Avanza</p>
                      <p className="text-xs font-medium text-slate-500">Maksimal 5 Penumpang</p>
                    </div>
                  </div>
                </div>

                <div className="border-t border-dashed border-slate-200 my-6"></div>

                <div className="space-y-2 mb-6">
                  <div className="flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>{formatCurrency(basePerSeat)} × {passengers} kursi</span>
                    <span className="text-slate-900">{formatCurrency(totalPrice)}</span>
                  </div>
                  <div className="flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>DP (Down Payment)</span>
                    <span className="text-blue-600">{formatCurrency(dpAmount)}</span>
                  </div>
                  <div className="flex items-center justify-between text-xs font-bold text-slate-500">
                    <span>Sisa dibayar ke driver</span>
                    <span className="text-slate-900">{formatCurrency(remaining)}</span>
                  </div>
                </div>

                <div className="flex items-end justify-between mb-8 border-t border-slate-100 pt-4">
                  <div>
                    <p className="text-sm font-bold text-slate-500 mb-1">Total Tarif</p>
                  </div>
                  <div className="text-right">
                    <p className="text-2xl md:text-3xl font-extrabold text-blue-600 tracking-tight">{formatCurrency(totalPrice)}</p>
                  </div>
                </div>

                <button
                  type="submit"
                  className="w-full bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-xl font-bold text-base transition-all shadow-lg shadow-slate-900/15 flex items-center justify-center gap-2 group"
                >
                  Lanjut Booking
                  <ChevronRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </button>
                <div className="mt-4 flex items-start gap-2 text-xs font-medium text-slate-500">
                  <Info className="w-4 h-4 shrink-0 text-slate-400" />
                  <p>Anda belum dikenakan biaya. Pembayaran DP dilakukan pada halaman selanjutnya.</p>
                </div>
              </div>
            </div>
          </div>

        </form>

      </div>
    </div>
  );
}

// ============== Location Picker Component ==============
interface LocationPickerProps {
  label: string;
  accent: "blue" | "emerald";
  address: string;
  onAddressChange: (v: string) => void;
  coords: [number, number];
  onCoordsChange: (c: [number, number]) => void;
  placeholder?: string;
}

function LocationPicker({
  label, accent, address, onAddressChange, coords, onCoordsChange, placeholder,
}: LocationPickerProps) {
  const mapContainerRef = useRef<HTMLDivElement>(null);
  const mapRef = useRef<L.Map | null>(null);
  const markerRef = useRef<L.Marker | null>(null);

  const accentClass =
    accent === "blue"
      ? { dot: "bg-blue-600", text: "text-blue-600", border: "border-blue-200", ring: "ring-blue-600/10" }
      : { dot: "bg-emerald-500", text: "text-emerald-600", border: "border-emerald-200", ring: "ring-emerald-500/10" };

  // Initialize map
  useEffect(() => {
    if (!mapContainerRef.current || mapRef.current) return;

    const DefaultIcon = L.icon({
      iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png",
      iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
      shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
      iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
    });

    const map = L.map(mapContainerRef.current, { zoomControl: true, attributionControl: false }).setView(coords, 14);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

    const marker = L.marker(coords, { icon: DefaultIcon, draggable: true }).addTo(map);
    marker.on("dragend", () => {
      const ll = marker.getLatLng();
      onCoordsChange([ll.lat, ll.lng]);
    });

    map.on("click", (e: L.LeafletMouseEvent) => {
      marker.setLatLng(e.latlng);
      onCoordsChange([e.latlng.lat, e.latlng.lng]);
    });

    mapRef.current = map;
    markerRef.current = marker;

    return () => {
      map.remove();
      mapRef.current = null;
      markerRef.current = null;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Sync external coord changes (e.g., when route changes)
  useEffect(() => {
    if (markerRef.current && mapRef.current) {
      const cur = markerRef.current.getLatLng();
      if (Math.abs(cur.lat - coords[0]) > 1e-6 || Math.abs(cur.lng - coords[1]) > 1e-6) {
        markerRef.current.setLatLng(coords);
        mapRef.current.setView(coords, mapRef.current.getZoom());
      }
    }
  }, [coords]);

  const handleUseMyLocation = () => {
    if (!navigator.geolocation) return;
    navigator.geolocation.getCurrentPosition((pos) => {
      const c: [number, number] = [pos.coords.latitude, pos.coords.longitude];
      onCoordsChange(c);
      if (mapRef.current) mapRef.current.setView(c, 15);
    });
  };

  return (
    <div className="space-y-3">
      <div className="flex items-center justify-between">
        <label className="text-sm font-bold text-slate-700 flex items-center gap-2">
          <span className={`w-2.5 h-2.5 rounded-full ${accentClass.dot}`}></span>
          {label}
        </label>
        <button
          type="button"
          onClick={handleUseMyLocation}
          className="text-[11px] font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5 transition-colors"
        >
          <Crosshair className="w-3.5 h-3.5" /> Gunakan lokasi saya
        </button>
      </div>

      <div className="relative">
        <MapPin className={`absolute left-3.5 top-3.5 w-5 h-5 ${accentClass.text} pointer-events-none`} />
        <textarea
          required
          value={address}
          onChange={(e) => onAddressChange(e.target.value)}
          placeholder={placeholder}
          rows={2}
          className="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all placeholder:text-slate-400 resize-none"
        />
      </div>

      <div className={`rounded-2xl overflow-hidden border ${accentClass.border} bg-slate-50 ring-4 ${accentClass.ring}`}>
        <div
          ref={mapContainerRef}
          className="w-full h-56 z-0"
          style={{ touchAction: "manipulation" }}
        />
        <div className="flex items-center justify-between px-4 py-2.5 bg-white border-t border-slate-100">
          <div className="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <Info className="w-3 h-3" />
            Geser pin atau klik peta untuk memilih lokasi
          </div>
          <div className="flex items-center gap-3 text-[10px] font-mono">
            <span className="text-slate-400">LAT</span>
            <span className="text-slate-900 font-bold">{coords[0].toFixed(5)}</span>
            <span className="text-slate-400">LNG</span>
            <span className="text-slate-900 font-bold">{coords[1].toFixed(5)}</span>
          </div>
        </div>
      </div>
    </div>
  );
}
