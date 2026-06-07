import { 
  Search, Filter, Calendar, Clock, Eye, 
  Car, XCircle, MapPin, Users, 
  ChevronRight, UserCircle, Phone, CheckCircle2, CircleDashed, MoreVertical, X,
  ArrowRight, Info, AlertCircle, Banknote, ClipboardCheck, ArrowUpRight
} from "lucide-react";
import { useState } from "react";
import { Link } from "react-router";

// --- Types ---
type BookingStatus = 
  | "Menunggu Pembayaran" 
  | "Menunggu Verifikasi" 
  | "Terverifikasi" 
  | "Siap Masuk Trip" 
  | "Sudah Masuk Trip" 
  | "Cancelled";

interface Booking {
  id: string;
  customerName: string;
  customerPhone: string;
  route: string;
  date: string;
  shift: string;
  time: string;
  passengers: number;
  paymentStatus: "Belum Bayar" | "Menunggu Verifikasi" | "Terverifikasi";
  status: BookingStatus;
  totalFare: number;
}

// --- Mock Data ---
const allBookings: Booking[] = [
  {
    id: "BKG-2605-001",
    customerName: "Budi Santoso",
    customerPhone: "0812-3456-7890",
    route: "Padang Panjang → Pekanbaru",
    date: "26 Mei 2026",
    shift: "Pagi",
    time: "10:00 WIB",
    passengers: 2,
    paymentStatus: "Menunggu Verifikasi",
    status: "Menunggu Verifikasi",
    totalFare: 400000,
  },
  {
    id: "BKG-2605-002",
    customerName: "Ahmad Fauzi",
    customerPhone: "0813-5678-9012",
    route: "Pekanbaru → Padang Panjang",
    date: "26 Mei 2026",
    shift: "Malam",
    time: "20:00 WIB",
    passengers: 1,
    paymentStatus: "Terverifikasi",
    status: "Siap Masuk Trip",
    totalFare: 200000,
  },
  {
    id: "BKG-2605-003",
    customerName: "Dina Mariana",
    customerPhone: "0852-1122-3344",
    route: "Padang Panjang → Pekanbaru",
    date: "26 Mei 2026",
    shift: "Pagi",
    time: "10:00 WIB",
    passengers: 3,
    paymentStatus: "Terverifikasi",
    status: "Sudah Masuk Trip",
    totalFare: 600000,
  },
  {
    id: "BKG-2605-004",
    customerName: "Joko Anwar",
    customerPhone: "0811-9988-7766",
    route: "Pekanbaru → Padang Panjang",
    date: "27 Mei 2026",
    shift: "Pagi",
    time: "10:00 WIB",
    passengers: 1,
    paymentStatus: "Belum Bayar",
    status: "Menunggu Pembayaran",
    totalFare: 200000,
  },
  {
    id: "BKG-2605-005",
    customerName: "Rina Sari",
    customerPhone: "0822-3344-5566",
    route: "Padang Panjang → Pekanbaru",
    date: "25 Mei 2026",
    shift: "Malam",
    time: "20:00 WIB",
    passengers: 2,
    paymentStatus: "Belum Bayar",
    status: "Cancelled",
    totalFare: 400000,
  },
];

// --- Status Styles ---
const getStatusStyles = (status: BookingStatus) => {
  switch (status) {
    case "Menunggu Pembayaran": return "bg-slate-100 text-slate-600 border-slate-200";
    case "Menunggu Verifikasi": return "bg-amber-50 text-amber-600 border-amber-200";
    case "Terverifikasi": return "bg-blue-50 text-blue-600 border-blue-200";
    case "Siap Masuk Trip": return "bg-emerald-50 text-emerald-600 border-emerald-200";
    case "Sudah Masuk Trip": return "bg-slate-900 text-white border-slate-900";
    case "Cancelled": return "bg-rose-50 text-rose-600 border-rose-200";
    default: return "bg-slate-50 text-slate-500 border-slate-200";
  }
};

export function AdminBooking() {
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState("Semua Status");

  const filteredBookings = allBookings.filter(b => {
    const matchesSearch = b.id.toLowerCase().includes(searchTerm.toLowerCase()) || 
                          b.customerName.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "Semua Status" || b.status === statusFilter;
    return matchesSearch && matchesStatus;
  });

  return (
    <div className="pb-8 space-y-8 animate-in fade-in duration-500">
      
      {/* Page Header */}
      <div>
        <h1 className="text-3xl font-black text-slate-900 tracking-tight">Manajemen Booking</h1>
        <p className="text-sm font-bold text-slate-500 mt-1 uppercase tracking-widest">
          Kelola manifes dan status pembayaran penumpang
        </p>
      </div>

      {/* Workflow Helper */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-start gap-4">
          <div className="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0 font-black">1</div>
          <div>
            <h3 className="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Cek Detail</h3>
            <p className="text-[11px] font-bold text-slate-400 leading-relaxed">Periksa rute, tanggal, dan jumlah penumpang yang dipesan.</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-start gap-4">
          <div className="w-10 h-10 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 shrink-0 font-black">2</div>
          <div>
            <h3 className="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Verifikasi Bayar</h3>
            <p className="text-[11px] font-bold text-slate-400 leading-relaxed">Cek bukti transfer DP (Rp50rb) di halaman Pembayaran.</p>
          </div>
        </div>
        <div className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-start gap-4 relative overflow-hidden group">
          <div className="absolute top-0 right-0 w-20 h-20 bg-emerald-500/5 rounded-full -mr-10 -mt-10 group-hover:scale-110 transition-transform duration-500"></div>
          <div className="w-10 h-10 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shrink-0 font-black">3</div>
          <div>
            <h3 className="text-xs font-black text-slate-900 uppercase tracking-widest mb-1">Assign ke Trip</h3>
            <p className="text-[11px] font-bold text-slate-400 leading-relaxed">Setelah lunas/DP, masukkan penumpang ke jadwal trip yang tersedia.</p>
          </div>
        </div>
      </div>

      {/* Toolbar */}
      <div className="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-[2rem] border border-slate-100 shadow-sm">
        <div className="relative w-full md:w-80">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            type="text" 
            placeholder="Cari ID atau Nama..." 
            className="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none transition-all"
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        
        <div className="flex items-center gap-3 w-full md:w-auto">
          <div className="relative flex-1 md:w-56">
            <Filter className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <select 
              className="w-full pl-11 pr-8 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none appearance-none"
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
            >
              <option>Semua Status</option>
              <option>Menunggu Pembayaran</option>
              <option>Menunggu Verifikasi</option>
              <option>Terverifikasi</option>
              <option>Siap Masuk Trip</option>
              <option>Sudah Masuk Trip</option>
              <option>Cancelled</option>
            </select>
          </div>
        </div>
      </div>

      {/* Booking Table */}
      <div className="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Booking</th>
                <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelanggan</th>
                <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Rute & Jadwal</th>
                <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pembayaran</th>
                <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Booking</th>
                <th className="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {filteredBookings.map((b) => (
                <tr key={b.id} className="hover:bg-slate-50/30 transition-colors group">
                  <td className="px-8 py-6">
                    <span className="text-xs font-black text-slate-900">{b.id}</span>
                  </td>
                  <td className="px-8 py-6">
                    <div>
                      <p className="text-xs font-black text-slate-900">{b.customerName}</p>
                      <p className="text-[10px] font-bold text-slate-400">{b.customerPhone}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="space-y-1">
                      <p className="text-[10px] font-black text-slate-900">{b.route}</p>
                      <div className="flex items-center gap-2">
                        <Calendar className="w-3 h-3 text-blue-600" />
                        <span className="text-[10px] font-bold text-slate-500">{b.date} • {b.shift}</span>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-2">
                      <div className={`w-1.5 h-1.5 rounded-full ${
                        b.paymentStatus === 'Terverifikasi' ? 'bg-emerald-500' : 
                        b.paymentStatus === 'Menunggu Verifikasi' ? 'bg-amber-500' : 'bg-slate-300'
                      }`}></div>
                      <span className="text-[10px] font-black text-slate-900 uppercase">{b.paymentStatus}</span>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className={`px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border ${getStatusStyles(b.status)}`}>
                      {b.status}
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button
                        title="Lihat Detail"
                        className="w-9 h-9 flex items-center justify-center bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all"
                      >
                        <Eye className="w-3.5 h-3.5" />
                      </button>
                      {b.status === "Menunggu Verifikasi" && (
                        <Link
                          to="/admin/pembayaran"
                          className="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20"
                        >
                          <Banknote className="w-3.5 h-3.5" /> Ke Pembayaran
                        </Link>
                      )}
                      {(b.status === "Siap Masuk Trip" || b.status === "Terverifikasi") && (
                        <Link
                          to="/admin/trip"
                          className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20"
                        >
                          <ClipboardCheck className="w-3.5 h-3.5" /> Assign ke Trip
                        </Link>
                      )}
                      {(b.status === "Menunggu Pembayaran" || b.status === "Menunggu Verifikasi") && (
                        <button
                          title="Batalkan"
                          className="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-100 transition-all"
                        >
                          <XCircle className="w-3.5 h-3.5" />
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Legend / Status Explanation */}
        <div className="p-8 bg-slate-50/50 border-t border-slate-50">
          <div className="flex flex-wrap items-center gap-4">
            <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2 flex items-center gap-2">
              <Info className="w-4 h-4" /> Keterangan Status:
            </span>
            {[
              { label: "Menunggu Pembayaran", color: "bg-slate-100 text-slate-600" },
              { label: "Menunggu Verifikasi", color: "bg-amber-50 text-amber-600" },
              { label: "Terverifikasi", color: "bg-blue-50 text-blue-600" },
              { label: "Siap Masuk Trip", color: "bg-emerald-50 text-emerald-600" },
              { label: "Sudah Masuk Trip", color: "bg-slate-900 text-white" },
            ].map((item) => (
              <div key={item.label} className="flex items-center gap-2">
                <span className={`px-2 py-1 rounded-lg text-[8px] font-black uppercase ${item.color} border border-current opacity-70`}>
                  {item.label}
                </span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}