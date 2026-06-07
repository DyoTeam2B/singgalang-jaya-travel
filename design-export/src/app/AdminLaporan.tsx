import { 
  Calendar, Filter, Download, Eye, TrendingUp, Ticket, 
  Map as MapIcon, Users, Wallet, CheckCircle2, AlertCircle, X,
  ArrowUpRight, ArrowDownRight, ClipboardList, PieChart, Banknote
} from "lucide-react";
import { useState } from "react";
import { 
  AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer 
} from "recharts";

// --- Mock Data ---
const revenueData = [
  { name: '13 Mei', revenue: 12000000 },
  { name: '14 Mei', revenue: 15500000 },
  { name: '15 Mei', revenue: 11000000 },
  { name: '16 Mei', revenue: 18500000 },
  { name: '17 Mei', revenue: 24000000 },
  { name: '18 Mei', revenue: 28500000 },
  { name: '19 Mei', revenue: 19000000 },
];

const reportTableData = [
  { 
    id: 'RPT-001', 
    date: '19 Mei 2026', 
    booking: 45, 
    trip: 12, 
    revenue: 19000000, 
    dpRevenue: 2250000, 
    fullRevenue: 16750000,
    collectedRemaining: 1500000,
    cancelled: 2,
    passengers: 128,
    status: 'Selesai' 
  },
  { id: 'RPT-002', date: '18 Mei 2026', booking: 82, trip: 18, revenue: 28500000, dpRevenue: 4100000, fullRevenue: 24400000, collectedRemaining: 3000000, cancelled: 5, passengers: 210, status: 'Selesai' },
  { id: 'RPT-003', date: '17 Mei 2026', booking: 65, trip: 15, revenue: 24000000, dpRevenue: 3250000, fullRevenue: 20750000, collectedRemaining: 2500000, cancelled: 3, passengers: 180, status: 'Selesai' },
  { id: 'RPT-004', date: '16 Mei 2026', booking: 52, trip: 14, revenue: 18500000, dpRevenue: 2600000, fullRevenue: 15900000, collectedRemaining: 1800000, cancelled: 4, passengers: 156, status: 'Selesai' },
  { id: 'RPT-005', date: '15 Mei 2026', booking: 30, trip: 8, revenue: 11000000, dpRevenue: 1500000, fullRevenue: 9500000, collectedRemaining: 1000000, cancelled: 1, passengers: 84, status: 'Selesai' },
];

const tripSummaryData = [
  { id: 'TRP-101', route: 'Padang Panjang ↔ Pekanbaru', shift: 'Pagi', driver: 'Hendra G', vehicle: 'Avanza (BA 1234 XY)', pax: 5, revenue: 1500000 },
  { id: 'TRP-102', route: 'Pekanbaru ↔ Padang Panjang', shift: 'Malam', driver: 'Budi S', vehicle: 'Innova (BM 5678 QA)', pax: 4, revenue: 1200000 },
  { id: 'TRP-103', route: 'Padang Panjang ↔ Pekanbaru', shift: 'Malam', driver: 'Ahmad F', vehicle: 'Hiace (BA 9988 ZZ)', pax: 12, revenue: 3600000 },
  { id: 'TRP-104', route: 'Pekanbaru ↔ Padang Panjang', shift: 'Pagi', driver: 'Reza P', vehicle: 'Avanza (BM 1122 QQ)', pax: 5, revenue: 1500000 },
];

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value);
};

const CustomTooltip = ({ active, payload, label }: any) => {
  if (active && payload && payload.length) {
    return (
      <div className="bg-white p-3 border border-slate-200 shadow-xl rounded-xl">
        <p className="text-xs font-bold text-slate-500 mb-1">{label}</p>
        <p className="text-sm font-extrabold text-blue-900">
          {formatCurrency(payload[0].value)}
        </p>
      </div>
    );
  }
  return null;
};

export function AdminLaporan() {
  const [dateFilter, setDateFilter] = useState("7 Hari Terakhir");
  const [shiftFilter, setShiftFilter] = useState("Semua Shift");
  
  // Modals
  const [selectedReport, setSelectedReport] = useState<any>(null);
  const [isExportModalOpen, setIsExportModalOpen] = useState(false);
  const [exportRange, setExportRange] = useState("Hari Ini");

  return (
    <div className="pb-8 flex flex-col h-full space-y-8 font-poppins relative">
      
      {/* Top Header & Filters */}
      <div className="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-1">Laporan Keuangan</h1>
          <p className="text-sm font-bold text-slate-500 uppercase tracking-widest">
            Analisis pendapatan dan performa armada
          </p>
        </div>

        <div className="flex flex-wrap items-center gap-3">
          <div className="relative">
            <Filter className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
            <select 
              value={shiftFilter}
              onChange={(e) => setShiftFilter(e.target.value)}
              className="pl-11 pr-8 py-3.5 bg-white border border-slate-100 shadow-xl shadow-slate-900/5 rounded-2xl text-xs font-black text-slate-900 appearance-none focus:ring-4 focus:ring-slate-900/5 cursor-pointer outline-none min-w-[160px]"
            >
              <option>Semua Shift</option>
              <option>Shift Pagi</option>
              <option>Shift Malam</option>
            </select>
          </div>

          <div className="relative">
            <Calendar className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
            <select 
              value={dateFilter}
              onChange={(e) => setDateFilter(e.target.value)}
              className="pl-11 pr-8 py-3.5 bg-white border border-slate-100 shadow-xl shadow-slate-900/5 rounded-2xl text-xs font-black text-slate-900 appearance-none focus:ring-4 focus:ring-slate-900/5 cursor-pointer min-w-[180px]"
            >
              <option>Hari Ini</option>
              <option>7 Hari Terakhir</option>
              <option>Bulan Ini</option>
              <option>Kustom Tanggal</option>
            </select>
          </div>

          <button 
            onClick={() => setIsExportModalOpen(true)}
            className="flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-white bg-slate-900 px-6 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 whitespace-nowrap"
          >
            <Download className="w-4 h-4" /> Export PDF
          </button>
        </div>
      </div>

      {/* Summary Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div className="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col relative group hover:scale-[1.02] transition-all duration-300">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm">
              <Ticket className="w-6 h-6" />
            </div>
            <span className="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
              <ArrowUpRight className="w-3 h-3" /> 12%
            </span>
          </div>
          <div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Booking</p>
            <h3 className="text-3xl font-black text-slate-900 tracking-tighter">349 <span className="text-xs font-bold text-slate-400">Pax</span></h3>
          </div>
        </div>

        <div className="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col group hover:scale-[1.02] transition-all duration-300">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm">
              <Wallet className="w-6 h-6" />
            </div>
            <span className="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
              <ArrowUpRight className="w-3 h-3" /> 8%
            </span>
          </div>
          <div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pendapatan Bersih</p>
            <h3 className="text-3xl font-black text-slate-900 tracking-tighter">Rp 128.5 Jt</h3>
          </div>
        </div>

        <div className="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col group hover:scale-[1.02] transition-all duration-300">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100 shadow-sm">
              <MapIcon className="w-6 h-6" />
            </div>
            <span className="flex items-center gap-1 text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-1 rounded-lg border border-rose-100">
              <ArrowDownRight className="w-3 h-3" /> 2%
            </span>
          </div>
          <div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Trip</p>
            <h3 className="text-3xl font-black text-slate-900 tracking-tighter">86 <span className="text-xs font-bold text-slate-400">Keberangkatan</span></h3>
          </div>
        </div>

        <div className="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col group hover:scale-[1.02] transition-all duration-300">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shadow-sm">
              <Users className="w-6 h-6" />
            </div>
            <span className="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
              <ArrowUpRight className="w-3 h-3" /> 15%
            </span>
          </div>
          <div>
            <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Okupansi Rata-rata</p>
            <h3 className="text-3xl font-black text-slate-900 tracking-tighter">92% <span className="text-xs font-bold text-slate-400">Kapasitas</span></h3>
          </div>
        </div>
      </div>

      {/* Main Content Layout */}
      <div className="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
        
        {/* Revenue Chart */}
        <div className="xl:col-span-3 bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
          <div className="flex items-center justify-between mb-8">
            <div>
              <h3 className="text-xl font-black text-slate-900 tracking-tight mb-1">Grafik Pendapatan</h3>
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Analisis mingguan berdasarkan shift operasional</p>
            </div>
            <div className="flex items-center gap-4">
              <span className="flex items-center gap-2 text-[10px] font-black text-slate-600 uppercase tracking-widest">
                <span className="w-3 h-3 rounded-full bg-blue-600"></span>
                Pendapatan
              </span>
            </div>
          </div>
          
          <div className="h-80 w-full no-scrollbar">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={revenueData} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                <defs key="defs">
                  <linearGradient id="colorRevenue" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#2563eb" stopOpacity={0.15}/>
                    <stop offset="95%" stopColor="#2563eb" stopOpacity={0}/>
                  </linearGradient>
                </defs>
                <CartesianGrid key="grid" strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                <XAxis 
                  key="xaxis"
                  dataKey="name" 
                  axisLine={false} 
                  tickLine={false} 
                  tick={{ fontSize: 10, fill: '#94a3b8', fontWeight: 900 }}
                  dy={15}
                />
                <YAxis 
                  key="yaxis"
                  axisLine={false} 
                  tickLine={false} 
                  tick={{ fontSize: 10, fill: '#94a3b8', fontWeight: 900 }}
                  tickFormatter={(val) => `${val / 1000000}Jt`}
                />
                <Tooltip key="tooltip" content={<CustomTooltip />} />
                <Area 
                  key="area"
                  type="monotone" 
                  dataKey="revenue" 
                  stroke="#2563eb" 
                  strokeWidth={4}
                  fillOpacity={1} 
                  fill="url(#colorRevenue)" 
                  animationDuration={1500}
                />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </div>

        {/* Report Table */}
        <div className="xl:col-span-3 bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden flex flex-col">
          <div className="p-8 border-b border-slate-50 flex items-center justify-between bg-white">
            <h3 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Rincian Laporan Harian</h3>
            <span className="text-[10px] font-black text-blue-600 bg-blue-50 px-4 py-1.5 rounded-xl border border-blue-100">7 Hari Terakhir</span>
          </div>
          
          <div className="overflow-x-auto no-scrollbar">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-slate-50/50">
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Laporan</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Booking & Trip</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendapatan</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {reportTableData.map((row) => (
                  <tr key={row.id} className="hover:bg-slate-50/50 transition-all group">
                    <td className="px-8 py-6 whitespace-nowrap">
                      <div className="flex items-center gap-4">
                        <div className="w-10 h-10 rounded-2xl bg-white flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm group-hover:scale-110 transition-transform">
                          <Calendar className="w-5 h-5" />
                        </div>
                        <div>
                          <p className="text-xs font-black text-slate-900">{row.date}</p>
                          <p className="text-[10px] font-bold text-slate-400">{row.id}</p>
                        </div>
                      </div>
                    </td>
                    <td className="px-8 py-6 whitespace-nowrap">
                       <div className="flex items-center gap-3">
                          <div className="flex flex-col">
                             <p className="text-xs font-black text-slate-900">{row.booking}</p>
                             <p className="text-[9px] font-black text-slate-400 uppercase">Booking</p>
                          </div>
                          <div className="w-px h-6 bg-slate-200"></div>
                          <div className="flex flex-col">
                             <p className="text-xs font-black text-slate-900">{row.trip}</p>
                             <p className="text-[9px] font-black text-slate-400 uppercase">Trip</p>
                          </div>
                       </div>
                    </td>
                    <td className="px-8 py-6 whitespace-nowrap">
                      <p className="text-xs font-black text-slate-900">{formatCurrency(row.revenue)}</p>
                      <p className="text-[9px] font-bold text-emerald-600 uppercase">Net Revenue</p>
                    </td>
                    <td className="px-8 py-6 whitespace-nowrap text-center">
                      <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black border border-emerald-100 bg-emerald-50 text-emerald-600 uppercase tracking-widest shadow-sm shadow-emerald-600/5">
                        <CheckCircle2 className="w-3.5 h-3.5" /> {row.status}
                      </span>
                    </td>
                    <td className="px-8 py-6 whitespace-nowrap text-right">
                      <div className="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button 
                          onClick={() => setSelectedReport(row)}
                          className="p-3 rounded-2xl transition-all text-slate-400 bg-white border border-slate-100 hover:text-blue-600 shadow-sm hover:shadow-lg"
                        >
                          <Eye className="w-4.5 h-4.5" />
                        </button>
                        <button 
                          onClick={() => setIsExportModalOpen(true)}
                          className="p-3 rounded-2xl transition-all text-slate-400 bg-white border border-slate-100 hover:text-rose-600 shadow-sm hover:shadow-lg"
                        >
                          <Download className="w-4.5 h-4.5" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

      </div>

      {/* Report Detail Modal */}
      {selectedReport && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in-95 duration-300">
              <div className="p-8 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                 <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight">Rincian Laporan Operasional</h3>
                    <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{selectedReport.date} • {selectedReport.id}</p>
                 </div>
                 <button onClick={() => setSelectedReport(null)} className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors">
                    <X className="w-6 h-6" />
                 </button>
              </div>
              
              <div className="flex-1 overflow-y-auto p-8 no-scrollbar">
                 <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Pendapatan</p>
                       <p className="text-xl font-black text-slate-900">{formatCurrency(selectedReport.revenue)}</p>
                    </div>
                    <div className="p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                       <p className="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">DP Collected</p>
                       <p className="text-xl font-black text-blue-600">{formatCurrency(selectedReport.dpRevenue)}</p>
                    </div>
                    <div className="p-6 bg-emerald-50/50 rounded-[2rem] border border-emerald-100">
                       <p className="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Lunas Collected</p>
                       <p className="text-xl font-black text-emerald-600">{formatCurrency(selectedReport.fullRevenue)}</p>
                    </div>
                    <div className="p-6 bg-amber-50/50 rounded-[2rem] border border-amber-100">
                       <p className="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-2">Pelunasan Driver</p>
                       <p className="text-xl font-black text-amber-600">{formatCurrency(selectedReport.collectedRemaining)}</p>
                    </div>
                 </div>

                 <div className="grid grid-cols-3 gap-6 mb-10">
                    <div className="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm">
                       <div className="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                          <Ticket className="w-6 h-6" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase">Booking</p>
                          <p className="text-lg font-black text-slate-900">{selectedReport.booking}</p>
                       </div>
                    </div>
                    <div className="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm">
                       <div className="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
                          <Users className="w-6 h-6" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase">Penumpang</p>
                          <p className="text-lg font-black text-slate-900">{selectedReport.passengers}</p>
                       </div>
                    </div>
                    <div className="flex items-center gap-4 p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm">
                       <div className="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-400">
                          <AlertCircle className="w-6 h-6" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-rose-400 uppercase">Dibatalkan</p>
                          <p className="text-lg font-black text-rose-600">{selectedReport.cancelled}</p>
                       </div>
                    </div>
                 </div>

                 <div className="bg-white border border-slate-100 rounded-[3rem] overflow-hidden shadow-xl shadow-slate-900/5">
                    <div className="p-6 border-b border-slate-50 flex items-center gap-2">
                       <ClipboardList className="w-4 h-4 text-blue-600" />
                       <h4 className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Rangkuman Trip Harian</h4>
                    </div>
                    <table className="w-full text-left">
                       <thead>
                          <tr className="bg-slate-50/50">
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Trip ID & Route</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver & Armada</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Pax</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Revenue</th>
                          </tr>
                       </thead>
                       <tbody className="divide-y divide-slate-50">
                          {tripSummaryData.map(trip => (
                             <tr key={trip.id} className="hover:bg-slate-50/50 transition-colors">
                                <td className="px-6 py-4">
                                   <p className="text-xs font-black text-slate-900">{trip.id}</p>
                                   <p className="text-[10px] font-bold text-slate-400">{trip.route} ({trip.shift})</p>
                                </td>
                                <td className="px-6 py-4">
                                   <p className="text-xs font-black text-slate-900">{trip.driver}</p>
                                   <p className="text-[10px] font-bold text-slate-400 uppercase">{trip.vehicle}</p>
                                </td>
                                <td className="px-6 py-4 text-center">
                                   <span className="text-xs font-black text-slate-900">{trip.pax}</span>
                                </td>
                                <td className="px-6 py-4 text-right">
                                   <span className="text-xs font-black text-blue-600">{formatCurrency(trip.revenue)}</span>
                                </td>
                             </tr>
                          ))}
                       </tbody>
                    </table>
                 </div>
              </div>

              <div className="p-8 border-t border-slate-100 flex justify-end gap-4 bg-slate-50/30">
                 <button onClick={() => setSelectedReport(null)} className="px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Tutup</button>
                 <button className="px-8 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 transition-all flex items-center gap-2">
                    <Download className="w-4 h-4" /> Cetak Laporan
                 </button>
              </div>
           </div>
        </div>
      )}

      {/* Export Confirmation Modal */}
      {isExportModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-md rounded-[3rem] shadow-2xl overflow-hidden p-10 text-center animate-in zoom-in-95 duration-300">
              <div className="w-20 h-20 bg-blue-50 rounded-[2rem] flex items-center justify-center text-blue-600 mx-auto mb-6 shadow-xl shadow-blue-500/10">
                 <PieChart className="w-10 h-10" />
              </div>
              <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-2">Export Laporan PDF</h3>
              <p className="text-sm font-bold text-slate-400 mb-8 px-4 leading-relaxed">Pilih rincian laporan yang ingin Anda unduh ke format dokumen PDF.</p>
              
              <div className="space-y-4 mb-10 text-left">
                 <div className="space-y-2">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Rentang Waktu</label>
                    <div className="relative">
                       <Calendar className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                       <select 
                          value={exportRange}
                          onChange={(e) => setExportRange(e.target.value)}
                          className="w-full pl-11 pr-8 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 appearance-none outline-none focus:ring-4 focus:ring-blue-600/5 cursor-pointer"
                       >
                          <option>Hari Ini</option>
                          <option>7 Hari Terakhir</option>
                          <option>Bulan Ini (Mei 2026)</option>
                          <option>Kustom Tanggal</option>
                       </select>
                    </div>
                 </div>
                 
                 <div className="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-center gap-3">
                    <Banknote className="w-5 h-5 text-blue-600" />
                    <span className="text-[10px] font-black text-blue-600 uppercase tracking-widest">Sertakan Rincian Pendapatan Driver</span>
                 </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                 <button onClick={() => setIsExportModalOpen(false)} className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                 <button className="py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-2">
                    <Download className="w-3.5 h-3.5" /> Export PDF
                 </button>
              </div>
           </div>
        </div>
      )}
    </div>
  );
}
