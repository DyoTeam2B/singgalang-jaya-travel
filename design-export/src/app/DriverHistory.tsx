import { 
  Car, Calendar, Users, Wallet, 
  Search, Filter, ChevronRight, 
  Clock, MapPin, CheckCircle2,
  TrendingUp, Download, Eye
} from "lucide-react";
import { useState } from "react";

// --- Types ---
interface CompletedTrip {
  id: string;
  date: string;
  route: string;
  shift: "Pagi" | "Malam";
  vehicle: string;
  totalPassengers: number;
  revenueCollected: number;
  status: "Completed";
}

// --- Mock Data ---
const mockHistory: CompletedTrip[] = [
  { 
    id: "TRP-098", date: "2026-05-24", route: "Padang Panjang → Pekanbaru", 
    shift: "Pagi", vehicle: "BA 1234 XY (Avanza)", totalPassengers: 5, 
    revenueCollected: 1800000, status: "Completed" 
  },
  { 
    id: "TRP-095", date: "2026-05-23", route: "Pekanbaru → Padang Panjang", 
    shift: "Pagi", vehicle: "BA 1234 XY (Avanza)", totalPassengers: 4, 
    revenueCollected: 1400000, status: "Completed" 
  },
  { 
    id: "TRP-092", date: "2026-05-21", route: "Padang Panjang → Pekanbaru", 
    shift: "Pagi", vehicle: "BA 1234 XY (Avanza)", totalPassengers: 5, 
    revenueCollected: 1800000, status: "Completed" 
  },
  { 
    id: "TRP-089", date: "2026-05-20", route: "Pekanbaru → Padang Panjang", 
    shift: "Malam", vehicle: "BA 1234 XY (Avanza)", totalPassengers: 5, 
    revenueCollected: 1950000, status: "Completed" 
  },
];

export function DriverHistory() {
  const [searchTerm, setSearchTerm] = useState("");

  const formatCurrency = (amount: number) => 
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);

  return (
    <div className="flex flex-col space-y-10 animate-in fade-in duration-500 font-poppins">
      
      {/* Header Section */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-2">Riwayat Trip</h1>
          <p className="text-sm font-bold text-slate-400">Kelola dan pantau seluruh perjalanan yang telah selesai.</p>
        </div>
        <div className="flex items-center gap-3">
           <button className="flex items-center gap-2 px-6 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
              <Download className="w-4 h-4" /> Export Data
           </button>
           <button className="flex items-center gap-2 px-6 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10">
              <TrendingUp className="w-4 h-4 text-emerald-400" /> Insight Trip
           </button>
        </div>
      </div>

      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
         <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
               <Car className="w-6 h-6" />
            </div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Trip Selesai</p>
            <h3 className="text-2xl font-black text-slate-900">42 Perjalanan</h3>
         </div>
         <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div className="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
               <Users className="w-6 h-6" />
            </div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Penumpang</p>
            <h3 className="text-2xl font-black text-slate-900">186 Orang</h3>
         </div>
         <div className="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div className="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
               <Wallet className="w-6 h-6" />
            </div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Estimasi Pendapatan</p>
            <h3 className="text-2xl font-black text-slate-900">{formatCurrency(6550000)}</h3>
         </div>
      </div>

      {/* Filter & List Area */}
      <div className="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
         <div className="p-8 border-b border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div className="relative flex-1 w-full max-w-md">
               <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" />
               <input 
                 type="text" 
                 placeholder="Cari ID Trip atau Rute..."
                 className="w-full pl-12 pr-6 py-4 bg-slate-50 rounded-2xl text-xs font-bold border border-transparent focus:border-blue-500 focus:bg-white transition-all outline-none"
                 value={searchTerm}
                 onChange={(e) => setSearchTerm(e.target.value)}
               />
            </div>
            <button className="flex items-center gap-2 px-6 py-4 bg-slate-50 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
               <Filter className="w-4 h-4" /> Filter Periode
            </button>
         </div>

         <div className="overflow-x-auto no-scrollbar">
            <table className="w-full">
               <thead>
                  <tr className="bg-slate-50/50">
                     <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Trip</th>
                     <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Jadwal</th>
                     <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Okupansi</th>
                     <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendapatan</th>
                     <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                     <th className="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                  </tr>
               </thead>
               <tbody className="divide-y divide-slate-50">
                  {mockHistory.map((trip) => (
                     <tr key={trip.id} className="hover:bg-slate-50/30 transition-colors group">
                        <td className="px-8 py-6">
                           <div className="flex items-center gap-4">
                              <div className="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                 <Car className="w-5 h-5" />
                              </div>
                              <div>
                                 <p className="text-xs font-black text-slate-900 mb-0.5">{trip.route}</p>
                                 <p className="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{trip.id} • {trip.vehicle}</p>
                              </div>
                           </div>
                        </td>
                        <td className="px-8 py-6">
                           <p className="text-xs font-bold text-slate-700">{trip.date}</p>
                           <p className="text-[10px] font-black text-blue-600 uppercase tracking-tighter">{trip.shift}</p>
                        </td>
                        <td className="px-8 py-6">
                           <div className="flex items-center gap-2">
                              <Users className="w-3.5 h-3.5 text-slate-400" />
                              <span className="text-xs font-black text-slate-700">{trip.totalPassengers} PAX</span>
                           </div>
                        </td>
                        <td className="px-8 py-6">
                           <p className="text-xs font-black text-emerald-600">{formatCurrency(trip.revenueCollected)}</p>
                        </td>
                        <td className="px-8 py-6">
                           <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[9px] font-black uppercase">
                              <CheckCircle2 className="w-3 h-3" /> {trip.status}
                           </span>
                        </td>
                        <td className="px-8 py-6 text-right">
                           <button className="p-2 text-slate-300 hover:text-blue-600 transition-colors">
                              <Eye className="w-5 h-5" />
                           </button>
                        </td>
                     </tr>
                  ))}
               </tbody>
            </table>
         </div>

         <div className="p-8 bg-slate-50/30 border-t border-slate-50 flex items-center justify-between">
            <p className="text-[10px] font-bold text-slate-400">Menampilkan {mockHistory.length} dari 42 trip selesai</p>
            <div className="flex items-center gap-2">
               <button className="px-4 py-2 text-[10px] font-black uppercase text-slate-400 hover:text-slate-900 transition-colors">Prev</button>
               <button className="w-8 h-8 rounded-lg bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">1</button>
               <button className="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-600 text-[10px] font-black flex items-center justify-center hover:bg-slate-50">2</button>
               <button className="px-4 py-2 text-[10px] font-black uppercase text-slate-400 hover:text-slate-900 transition-colors">Next</button>
            </div>
         </div>
      </div>
    </div>
  );
}
