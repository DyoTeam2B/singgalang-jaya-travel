import { 
  ArrowLeft, Search, Filter, Calendar, 
  MapPin, Clock, Users, FileText, 
  Download, ChevronRight, MoreHorizontal,
  Car, User, ArrowUpRight, CheckCircle2,
  Table as TableIcon
} from "lucide-react";
import { useState } from "react";
import { Link } from "react-router";

// --- Types & Mock Data ---
interface CompletedTrip {
  id: string;
  date: string;
  route: string;
  shift: "Pagi" | "Malam";
  vehicle: { plate: string; model: string };
  driver: { name: string };
  passengersCount: number;
  revenue: number;
  status: "Completed";
}

const mockHistoryData: CompletedTrip[] = [
  {
    id: "TRP-901",
    date: "2026-05-24",
    route: "Padang Panjang → Pekanbaru",
    shift: "Pagi",
    vehicle: { plate: "BA 4412 OP", model: "Toyota Avanza" },
    driver: { name: "Rudi Hermawan" },
    passengersCount: 5,
    revenue: 250000,
    status: "Completed"
  },
  {
    id: "TRP-902",
    date: "2026-05-24",
    route: "Pekanbaru → Padang Panjang",
    shift: "Malam",
    vehicle: { plate: "BA 1122 XY", model: "Toyota Avanza" },
    driver: { name: "Asep Sunandar" },
    passengersCount: 4,
    revenue: 200000,
    status: "Completed"
  },
  {
    id: "TRP-899",
    date: "2026-05-23",
    route: "Padang Panjang → Pekanbaru",
    shift: "Pagi",
    vehicle: { plate: "BA 9901 ZZ", model: "Toyota Avanza" },
    driver: { name: "Hendra Wijaya" },
    passengersCount: 5,
    revenue: 250000,
    status: "Completed"
  },
  {
    id: "TRP-898",
    date: "2026-05-23",
    route: "Pekanbaru → Padang Panjang",
    shift: "Malam",
    vehicle: { plate: "BA 4412 OP", model: "Toyota Avanza" },
    driver: { name: "Rudi Hermawan" },
    passengersCount: 3,
    revenue: 150000,
    status: "Completed"
  }
];

export function TripHistory() {
  const [filterDate, setFilterDate] = useState("");
  const [filterRoute, setFilterRoute] = useState("Semua Rute");
  const [filterShift, setFilterShift] = useState("Semua Shift");

  const filteredHistory = mockHistoryData.filter(trip => {
    const matchDate = filterDate ? trip.date === filterDate : true;
    const matchRoute = filterRoute !== "Semua Rute" ? trip.route === filterRoute : true;
    const matchShift = filterShift !== "Semua Shift" ? trip.shift === filterShift : true;
    return matchDate && matchRoute && matchShift;
  });

  const totalRevenue = filteredHistory.reduce((sum, trip) => sum + trip.revenue, 0);
  const totalPassengers = filteredHistory.reduce((sum, trip) => sum + trip.passengersCount, 0);

  return (
    <div className="pb-10 font-poppins">
      {/* Header & Back Button */}
      <div className="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <div className="flex items-center gap-2 mb-2">
            <Link 
              to="/admin/trip" 
              className="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500"
            >
              <ArrowLeft className="w-5 h-5" />
            </Link>
            <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight">Trip History</h1>
          </div>
          <p className="text-sm font-medium text-slate-500 ml-9">Rekapitulasi perjalanan yang telah selesai dikerjakan.</p>
        </div>
        <div className="flex items-center gap-3">
          <button className="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10">
            <Download className="w-4 h-4" /> Export Report
          </button>
        </div>
      </div>

      {/* Stats Overview */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Trip</p>
          <div className="flex items-end gap-2">
            <h3 className="text-2xl font-black text-slate-900">{filteredHistory.length}</h3>
            <p className="text-xs font-bold text-slate-400 mb-1">Perjalanan</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Penumpang</p>
          <div className="flex items-end gap-2">
            <h3 className="text-2xl font-black text-slate-900">{totalPassengers}</h3>
            <p className="text-xs font-bold text-slate-400 mb-1">Orang</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm">
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pendapatan (DP)</p>
          <div className="flex items-end gap-2">
            <h3 className="text-2xl font-black text-emerald-600">Rp {totalRevenue.toLocaleString()}</h3>
            <p className="text-xs font-bold text-slate-400 mb-1">IDR</p>
          </div>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] mb-8">
        <div className="flex flex-col lg:flex-row items-end gap-4">
          <div className="flex-1 w-full grid grid-cols-1 md:grid-cols-3 gap-4">
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <Calendar className="w-3 h-3" /> Tanggal
              </label>
              <input 
                type="date" 
                value={filterDate}
                onChange={(e) => setFilterDate(e.target.value)}
                className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-slate-900/10 outline-none"
              />
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <MapPin className="w-3 h-3" /> Rute
              </label>
              <select 
                value={filterRoute}
                onChange={(e) => setFilterRoute(e.target.value)}
                className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-slate-900/10 outline-none"
              >
                <option>Semua Rute</option>
                <option>Padang Panjang → Pekanbaru</option>
                <option>Pekanbaru → Padang Panjang</option>
              </select>
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <Clock className="w-3 h-3" /> Shift
              </label>
              <select 
                value={filterShift}
                onChange={(e) => setFilterShift(e.target.value)}
                className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-slate-900/10 outline-none"
              >
                <option>Semua Shift</option>
                <option>Pagi</option>
                <option>Malam</option>
              </select>
            </div>
          </div>
          <button 
            onClick={() => {
              setFilterDate("");
              setFilterRoute("Semua Rute");
              setFilterShift("Semua Shift");
            }}
            className="px-6 py-2.5 text-xs font-bold text-slate-400 hover:text-slate-900 transition-colors"
          >
            Reset Filter
          </button>
        </div>
      </div>

      {/* History Table */}
      <div className="bg-white rounded-[2rem] border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full border-collapse">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Trip Info</th>
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Route & Shift</th>
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver & Vehicle</th>
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Passengers</th>
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Revenue (DP)</th>
                <th className="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                <th className="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {filteredHistory.length > 0 ? filteredHistory.map((trip) => (
                <tr key={trip.id} className="hover:bg-slate-50/30 transition-colors group">
                  <td className="px-6 py-4">
                    <p className="text-sm font-black text-slate-900 mb-0.5">{trip.id}</p>
                    <p className="text-[10px] font-bold text-slate-400 uppercase">{trip.date}</p>
                  </td>
                  <td className="px-6 py-4">
                    <p className="text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                       {trip.route}
                    </p>
                    <span className={`px-2 py-0.5 rounded-md text-[9px] font-black uppercase border ${
                      trip.shift === 'Pagi' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100'
                    }`}>
                      {trip.shift}
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <User className="w-4 h-4" />
                      </div>
                      <div>
                        <p className="text-xs font-bold text-slate-900 mb-0.5">{trip.driver.name}</p>
                        <p className="text-[10px] font-bold text-slate-400 uppercase">{trip.vehicle.plate} • {trip.vehicle.model}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4 text-center">
                    <div className="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-700 text-xs font-black">
                      {trip.passengersCount}
                    </div>
                  </td>
                  <td className="px-6 py-4 text-right">
                    <p className="text-xs font-black text-slate-900">Rp {trip.revenue.toLocaleString()}</p>
                  </td>
                  <td className="px-6 py-4 text-center">
                    <span className="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase rounded-full border border-emerald-100">
                      <CheckCircle2 className="w-3 h-3" /> Completed
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center justify-center gap-2">
                      <button className="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View Manifest">
                        <FileText className="w-4 h-4" />
                      </button>
                      <button className="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all">
                        <MoreHorizontal className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              )) : (
                <tr>
                  <td colSpan={7} className="px-6 py-20 text-center">
                    <div className="flex flex-col items-center justify-center opacity-40">
                      <TableIcon className="w-12 h-12 mb-3 text-slate-300" />
                      <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">No trip history found</p>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}