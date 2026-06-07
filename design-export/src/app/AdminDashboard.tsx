import { 
  Ticket, Map, Wallet, Clock, CheckCircle2, ChevronRight, Activity
} from "lucide-react";

const recentBookings = [
  { id: "BKG-1029", name: "Budi Santoso", route: "Padang Panjang → Pekanbaru", shift: "Pagi", status: "Lunas", statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200" },
  { id: "BKG-1028", name: "Siti Rahma", route: "Pekanbaru → Padang Panjang", shift: "Malam", status: "Pending Verifikasi", statusColor: "bg-amber-50 text-amber-700 border-amber-200" },
  { id: "BKG-1027", name: "Ahmad Fauzi", route: "Padang Panjang → Pekanbaru", shift: "Pagi", status: "Lunas", statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200" },
  { id: "BKG-1026", name: "Dina Mariana", route: "Padang Panjang → Pekanbaru", shift: "Malam", status: "Dikonfirmasi", statusColor: "bg-blue-50 text-blue-700 border-blue-200" },
  { id: "BKG-1025", name: "Reza Pratama", route: "Pekanbaru → Padang Panjang", shift: "Pagi", status: "Lunas", statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200" },
];

const activities = [
  { title: "Booking Baru Diterima", desc: "Budi Santoso memesan 2 kursi (Shift Pagi)", time: "10 menit yang lalu" },
  { title: "Pembayaran Diverifikasi", desc: "Bukti transfer BKG-1028 telah diverifikasi oleh sistem", time: "25 menit yang lalu" },
  { title: "Trip Telah Ditugaskan", desc: "Driver Hendra ditugaskan untuk Trip #8821", time: "1 jam yang lalu" },
  { title: "Trip Selesai", desc: "Driver Ahmad (Shift Malam) tiba di tujuan", time: "2 jam yang lalu" },
];

export function AdminDashboard() {
  return (
    <div className="font-poppins">
      <div className="mb-8">
        <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">Dashboard Admin</h1>
        <p className="text-sm font-medium text-slate-500">Ringkasan aktivitas dan operasional travel Singgalang Jaya hari ini.</p>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {[
          { label: "Total Booking", value: "1,242", trend: "+12%", icon: Ticket, color: "text-blue-600", bg: "bg-blue-50", trendColor: "text-emerald-600" },
          { label: "Pending Verifikasi", value: "18", trend: "Perlu Tindakan", icon: Clock, color: "text-amber-600", bg: "bg-amber-50", trendColor: "text-amber-600" },
          { label: "Trip Aktif", value: "12", trend: "Berlangsung", icon: Map, color: "text-indigo-600", bg: "bg-indigo-50", trendColor: "text-slate-500" },
          { label: "Total Revenue", value: "Rp 125.8M", trend: "+8.4%", icon: Wallet, color: "text-emerald-600", bg: "bg-emerald-50", trendColor: "text-emerald-600" },
        ].map((stat, idx) => (
          <div key={idx} className="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow">
            <div className="flex justify-between items-start mb-4">
              <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${stat.bg}`}>
                <stat.icon className={`w-6 h-6 ${stat.color}`} />
              </div>
              <span className={`text-xs font-bold ${stat.trendColor} bg-slate-50 px-2.5 py-1 rounded-full border border-slate-100`}>
                {stat.trend}
              </span>
            </div>
            <div>
              <h3 className="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">{stat.value}</h3>
              <p className="text-sm font-semibold text-slate-500">{stat.label}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Bottom Layout: Table & Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Recent Bookings Table */}
        <div className="lg:col-span-2 bg-white rounded-[1.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] overflow-hidden flex flex-col">
          <div className="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h2 className="text-lg font-bold text-slate-900">Booking Terbaru</h2>
              <p className="text-sm font-medium text-slate-500 mt-0.5">Daftar pemesanan tiket terakhir</p>
            </div>
            <button className="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
              Lihat Semua <ChevronRight className="w-4 h-4" />
            </button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-slate-50 border-b border-slate-100">
                  <th className="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">ID Booking</th>
                  <th className="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Nama Pelanggan</th>
                  <th className="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Jadwal/Rute</th>
                  <th className="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Shift</th>
                  <th className="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {recentBookings.map((booking, idx) => (
                  <tr key={idx} className="hover:bg-slate-50/50 transition-colors">
                    <td className="p-4 text-sm font-bold text-slate-900 whitespace-nowrap">{booking.id}</td>
                    <td className="p-4 text-sm font-semibold text-slate-600 whitespace-nowrap">{booking.name}</td>
                    <td className="p-4 text-sm font-medium text-slate-500 whitespace-nowrap">{booking.route}</td>
                    <td className="p-4 text-sm font-semibold text-slate-600 whitespace-nowrap">{booking.shift}</td>
                    <td className="p-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border ${booking.statusColor}`}>
                        {booking.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Activity Panel */}
        <div className="bg-white rounded-[1.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.03)] p-6">
          <div className="flex items-center justify-between mb-6">
            <div>
              <h2 className="text-lg font-bold text-slate-900">Aktivitas Terkini</h2>
              <p className="text-sm font-medium text-slate-500 mt-0.5">Log sistem realtime</p>
            </div>
            <div className="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center">
              <Activity className="w-4 h-4 text-slate-400" />
            </div>
          </div>

          <div className="space-y-6">
            {activities.map((act, idx) => (
              <div key={idx} className="flex gap-4 relative">
                {/* Connecting Line */}
                {idx !== activities.length - 1 && (
                  <div className="absolute top-8 left-[11px] w-px h-full bg-slate-100 -bottom-6"></div>
                )}
                
                <div className="relative z-10 w-6 h-6 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center shrink-0 shadow-sm">
                  <CheckCircle2 className="w-3.5 h-3.5 text-blue-600" />
                </div>
                
                <div className="pt-0.5">
                  <p className="text-sm font-bold text-slate-900 mb-0.5">{act.title}</p>
                  <p className="text-xs font-medium text-slate-500 leading-snug mb-1">{act.desc}</p>
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{act.time}</p>
                </div>
              </div>
            ))}
          </div>
          
          <button className="w-full mt-8 py-2.5 text-sm font-bold text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
            Lihat Semua Log
          </button>
        </div>

      </div>
    </div>
  );
}