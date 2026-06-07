import { 
  ArrowLeft, MapPin, Phone, CheckCircle, 
  Clock, Map, User, Car, MoveRight,
  MoreVertical, CheckCircle2, Circle, 
  MessageCircle, ExternalLink, Users,
  Navigation, Calendar, MapPinned
} from "lucide-react";
import { useState } from "react";
import { Link } from "react-router";

// --- Types & Mock Data ---
interface PassengerManifest {
  id: string;
  name: string;
  phone: string;
  pickup: string;
  destination: string;
  paymentStatus: "DP Lunas" | "Lunas";
  status: "Waiting Pickup" | "Picked Up";
  pax: number;
}

interface TripHeader {
  id: string;
  route: string;
  date: string;
  shift: "Pagi" | "Malam";
  vehicle: { plate: string; model: string };
  totalPassengers: number;
}

const mockTripHeader: TripHeader = {
  id: "TRP-102",
  route: "Padang Panjang → Pekanbaru",
  date: "2026-05-26",
  shift: "Pagi",
  vehicle: { plate: "BA 1234 XY", model: "Toyota Avanza" },
  totalPassengers: 5
};

const initialPassengers: PassengerManifest[] = [
  { 
    id: "BKG-101", 
    name: "Budi Santoso", 
    phone: "0812-3456-7890", 
    pickup: "Jl. Sudirman No. 12, Padang Panjang", 
    destination: "Terminal AKAP, Pekanbaru", 
    paymentStatus: "DP Lunas",
    status: "Waiting Pickup",
    pax: 2
  },
  { 
    id: "BKG-102", 
    name: "Siti Rahma", 
    phone: "0821-9988-7766", 
    pickup: "Simpang PDG Panjang (Dekat SPBU)", 
    destination: "Mall SKA, Pekanbaru", 
    paymentStatus: "Lunas",
    status: "Waiting Pickup",
    pax: 1
  },
  { 
    id: "BKG-105", 
    name: "Reza Pratama", 
    phone: "0852-1122-3344", 
    pickup: "Loket Singgalang Padang Panjang", 
    destination: "Loket Singgalang Pekanbaru", 
    paymentStatus: "DP Lunas",
    status: "Picked Up",
    pax: 2
  },
];

export function DriverManifest() {
  const [passengers, setPassengers] = useState<PassengerManifest[]>(initialPassengers);
  const trip = mockTripHeader;

  const togglePickupStatus = (id: string) => {
    setPassengers(passengers.map(p => 
      p.id === id ? { ...p, status: p.status === "Waiting Pickup" ? "Picked Up" : "Waiting Pickup" } : p
    ));
  };

  const openInMaps = (address: string) => {
    const encodedAddress = encodeURIComponent(address);
    window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
  };

  const contactWhatsApp = (phone: string) => {
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    window.open(`https://wa.me/${cleanPhone}`, '_blank');
  };

  return (
    <div className="min-h-screen bg-slate-50 font-poppins pb-24">
      {/* Top Sticky Nav */}
      <div className="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div className="max-w-xl mx-auto px-6 py-4 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <Link to="/driver" className="p-2 -ml-2 hover:bg-slate-100 rounded-full transition-colors">
              <ArrowLeft className="w-5 h-5 text-slate-600" />
            </Link>
            <h1 className="text-sm font-black text-slate-900 uppercase tracking-widest">Manifest Trip</h1>
          </div>
          <div className="bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
            <p className="text-[10px] font-black text-blue-700 uppercase">{trip.id}</p>
          </div>
        </div>
      </div>

      <div className="max-w-xl mx-auto px-6 pt-6 space-y-8">
        
        {/* Trip Summary Card - Modern Focus */}
        <div className="bg-slate-900 text-white rounded-[2.5rem] p-8 shadow-2xl shadow-slate-900/20 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
          
          <div className="relative z-10">
            <div className="flex items-center gap-2 mb-4">
              <span className={`px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight border ${
                trip.shift === 'Pagi' ? 'bg-orange-500/20 text-orange-400 border-orange-500/20' : 'bg-indigo-500/20 text-indigo-400 border-indigo-500/20'
              }`}>
                Shift {trip.shift}
              </span>
              <span className="px-2.5 py-1 bg-white/10 text-white/60 rounded-lg text-[9px] font-black uppercase tracking-tight border border-white/5">
                {trip.date}
              </span>
            </div>

            <h2 className="text-xl font-black mb-6 leading-tight flex items-center gap-3">
              {trip.route}
            </h2>

            <div className="grid grid-cols-2 gap-4">
              <div className="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                <div className="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                  <Car className="w-4 h-4 text-blue-400" />
                </div>
                <div>
                  <p className="text-[9px] font-black text-white/40 uppercase tracking-tighter">Armada</p>
                  <p className="text-[10px] font-black text-white">{trip.vehicle.plate}</p>
                </div>
              </div>
              <div className="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                <div className="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                  <Users className="w-4 h-4 text-emerald-400" />
                </div>
                <div>
                  <p className="text-[9px] font-black text-white/40 uppercase tracking-tighter">Okupansi</p>
                  <p className="text-[10px] font-black text-white">{trip.totalPassengers}/5 PAX</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Section Divider / Label */}
        <div className="flex items-center justify-between px-2">
          <h3 className="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
            <Users className="w-4 h-4 text-blue-600" /> Daftar Penumpang
          </h3>
          <span className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            {passengers.length} Total
          </span>
        </div>

        {/* Passenger List - Direct Card Display */}
        <div className="space-y-6 pb-10">
          {passengers.map((p, index) => (
            <div 
              key={p.id} 
              className={`bg-white rounded-[2.5rem] border transition-all duration-500 group relative ${
                p.status === 'Picked Up' 
                  ? 'border-emerald-100 bg-slate-50/50' 
                  : 'border-slate-100 shadow-[0_15px_40px_rgba(0,0,0,0.04)]'
              }`}
            >
              {/* Card Header with Counter & Badge */}
              <div className="p-8">
                <div className="flex items-start justify-between mb-8">
                  <div className="flex items-center gap-4">
                    <div className={`w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-black transition-all ${
                      p.status === 'Picked Up' ? 'bg-emerald-100 text-emerald-700 shadow-lg shadow-emerald-100/50' : 'bg-blue-900 text-white shadow-lg shadow-blue-900/20'
                    }`}>
                      {index + 1}
                    </div>
                    <div>
                      <h4 className="text-base font-black text-slate-900 leading-tight">{p.name}</h4>
                      <div className="flex items-center gap-2 mt-1">
                        <span className="text-[10px] font-black text-blue-600 uppercase bg-blue-50 px-2 py-0.5 rounded-md">{p.pax} PAX</span>
                        <span className={`px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-tight border ${
                          p.paymentStatus === 'Lunas' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'
                        }`}>
                          {p.paymentStatus}
                        </span>
                      </div>
                    </div>
                  </div>
                  <button className="p-2 text-slate-300 hover:text-slate-900 transition-colors">
                    <MoreVertical className="w-5 h-5" />
                  </button>
                </div>

                {/* Vertical Step-style Locations */}
                <div className="space-y-8 relative mb-8 pl-4">
                  {/* Vertical Line */}
                  <div className="absolute left-0 top-1 bottom-1 w-0.5 bg-slate-100"></div>
                  
                  {/* Pickup Point */}
                  <div className="relative">
                    <div className="absolute -left-[20px] top-1 w-3 h-3 rounded-full border-2 border-blue-500 bg-white z-10"></div>
                    <div className="flex items-center justify-between mb-2">
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Titik Penjemputan</p>
                      <button 
                        onClick={() => openInMaps(p.pickup)}
                        className="text-[10px] font-black text-blue-600 uppercase flex items-center gap-1.5 hover:underline"
                      >
                        <Navigation className="w-3 h-3" /> Buka Peta
                      </button>
                    </div>
                    <p className="text-xs font-bold text-slate-600 leading-relaxed pr-6 italic">
                      "{p.pickup}"
                    </p>
                  </div>

                  {/* Destination Point */}
                  <div className="relative">
                    <div className="absolute -left-[20px] top-1 w-3 h-3 rounded-full bg-emerald-500 z-10"></div>
                    <p className="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-2">Titik Tujuan</p>
                    <p className="text-xs font-bold text-slate-600 leading-relaxed pr-6 italic">
                      "{p.destination}"
                    </p>
                  </div>
                </div>

                {/* Action Buttons - Large & Mobile Friendly */}
                <div className="grid grid-cols-2 gap-4">
                  <button 
                    onClick={() => contactWhatsApp(p.phone)}
                    className="flex items-center justify-center gap-3 py-4 bg-slate-50 hover:bg-slate-100 text-slate-900 rounded-2xl transition-all text-xs font-black border border-slate-200/50 shadow-sm"
                  >
                    <MessageCircle className="w-4 h-4 text-emerald-500" /> HUBUNGI
                  </button>
                  <button 
                    onClick={() => togglePickupStatus(p.id)}
                    className={`flex items-center justify-center gap-3 py-4 rounded-2xl transition-all text-xs font-black border shadow-lg ${
                      p.status === 'Picked Up' 
                        ? 'bg-emerald-600 text-white border-emerald-600 shadow-emerald-600/20' 
                        : 'bg-white border-blue-200 text-blue-600 hover:bg-blue-50 shadow-blue-200/20'
                    }`}
                  >
                    {p.status === 'Picked Up' ? (
                      <><CheckCircle2 className="w-4 h-4" /> DIJEMPUT</>
                    ) : (
                      <><Circle className="w-4 h-4" /> JEMPUT</>
                    )}
                  </button>
                </div>
              </div>

              {/* Status Indicator Bar at Bottom */}
              {p.status === 'Picked Up' && (
                <div className="bg-emerald-500/10 py-2.5 px-8 flex items-center justify-center gap-2 border-t border-emerald-100">
                  <CheckCircle className="w-3 h-3 text-emerald-600" />
                  <p className="text-[10px] font-black text-emerald-700 uppercase tracking-widest italic">Penumpang sudah dalam armada</p>
                </div>
              )}
            </div>
          ))}
        </div>

        {/* Floating Quick Stats - Bottom Navigation Area */}
        <div className="fixed bottom-6 left-6 right-6 max-w-lg mx-auto z-[60]">
           <div className="bg-slate-900 text-white p-4 rounded-3xl shadow-2xl flex items-center justify-between border border-white/10 backdrop-blur-md">
             <div className="flex items-center gap-4 px-2">
                <div className="flex -space-x-2">
                  {passengers.slice(0,3).map((_, i) => (
                    <div key={i} className={`w-8 h-8 rounded-full border-2 border-slate-900 flex items-center justify-center text-[10px] font-black ${i === 0 ? 'bg-blue-500' : i === 1 ? 'bg-indigo-500' : 'bg-emerald-500'}`}>
                      P
                    </div>
                  ))}
                </div>
                <div>
                  <p className="text-[10px] font-black uppercase text-white/40 tracking-tighter leading-none mb-1">Status Kehadiran</p>
                  <p className="text-xs font-black">
                    {passengers.filter(p => p.status === 'Picked Up').length} / {passengers.length} Penumpang
                  </p>
                </div>
             </div>
             <button className="bg-blue-600 px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
               Detail Trip
             </button>
           </div>
        </div>

      </div>
    </div>
  );
}