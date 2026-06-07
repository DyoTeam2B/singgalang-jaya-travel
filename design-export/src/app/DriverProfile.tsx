import { 
  UserCircle, Mail, Phone, ShieldCheck, MapPin, 
  Lock, History, Save, LogOut, Camera, Car
} from "lucide-react";
import { useState } from "react";
import { useDriverLogout } from "./components/DriverLayout";

export function DriverProfile() {
  const { setIsLogoutModalOpen } = useDriverLogout();
  const [activeTab, setActiveTab] = useState("info");
  
  return (
    <div className="pb-12 flex flex-col space-y-10 animate-in fade-in duration-500 font-poppins">
      
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <h1 className="text-4xl font-black text-slate-900 tracking-tight mb-2">Profil Driver</h1>
          <p className="text-sm font-bold text-slate-400 uppercase tracking-[0.3em]">Pengaturan Akun & Keamanan</p>
        </div>
        
        <div className="flex items-center gap-3">
           <button 
             onClick={() => setIsLogoutModalOpen(true)}
             className="px-6 py-4 bg-white border border-slate-200 text-rose-500 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest hover:bg-rose-50 transition-all flex items-center gap-2 shadow-sm"
           >
              <LogOut className="w-4 h-4" /> Logout
           </button>
           <button className="px-8 py-4 bg-slate-900 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all flex items-center gap-2">
              <Save className="w-4 h-4" /> Simpan Perubahan
           </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {/* Sidebar Profile */}
        <div className="lg:col-span-4 space-y-6">
           <div className="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 p-8 text-center relative overflow-hidden group">
              <div className="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-blue-600 to-indigo-700"></div>
              
              <div className="relative mt-8 mb-6 inline-block">
                 <div className="w-32 h-32 rounded-[2.5rem] bg-white p-1.5 shadow-2xl relative">
                    <div className="w-full h-full rounded-[2.2rem] bg-slate-100 flex items-center justify-center text-slate-400 overflow-hidden">
                       <img 
                         src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=200&h=200&fit=crop" 
                         alt="Profile" 
                         className="w-full h-full object-cover"
                       />
                    </div>
                    <button className="absolute -bottom-2 -right-2 p-3 bg-blue-600 text-white rounded-2xl shadow-xl hover:bg-blue-700 transition-all border-4 border-white group-hover:scale-110">
                       <Camera className="w-4 h-4" />
                    </button>
                 </div>
              </div>

              <div className="mb-8">
                 <h3 className="text-xl font-black text-slate-900 tracking-tight">Hendra Wijaya</h3>
                 <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mt-1 bg-blue-50 inline-block px-3 py-1 rounded-lg">Driver Partner</p>
              </div>

              <div className="space-y-3 pt-6 border-t border-slate-50">
                 <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl text-left">
                    <ShieldCheck className="w-5 h-5 text-emerald-500" />
                    <div>
                       <p className="text-[9px] font-black text-slate-400 uppercase">Status Akun</p>
                       <p className="text-xs font-black text-slate-900 uppercase tracking-tighter">Terverifikasi</p>
                    </div>
                 </div>
                 <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl text-left">
                    <Car className="w-5 h-5 text-indigo-500" />
                    <div>
                       <p className="text-[9px] font-black text-slate-400 uppercase">Armada Tetap</p>
                       <p className="text-xs font-black text-slate-900 uppercase tracking-tighter">BA 1234 XY</p>
                    </div>
                 </div>
              </div>
           </div>

           {/* Tab Navigation */}
           <div className="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 p-4 space-y-1">
              <button 
                onClick={() => setActiveTab("info")}
                className={`w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all ${activeTab === 'info' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-50'}`}
              >
                 <UserCircle className="w-4 h-4" /> Informasi Profil
              </button>
              <button 
                onClick={() => setActiveTab("password")}
                className={`w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all ${activeTab === 'password' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-slate-50'}`}
              >
                 <Lock className="w-4 h-4" /> Keamanan Sandi
              </button>
              
           </div>
        </div>

        {/* Content Area */}
        <div className="lg:col-span-8 bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
           
           {activeTab === 'info' && (
              <div className="p-10 animate-in slide-in-from-right-4 duration-300">
                 <div className="flex items-center gap-3 mb-10 pb-6 border-b border-slate-50">
                    <div className="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                       <UserCircle className="w-5 h-5" />
                    </div>
                    <h4 className="text-lg font-black text-slate-900 uppercase tracking-widest">Informasi Dasar</h4>
                 </div>

                 <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Nama Lengkap</label>
                       <div className="relative">
                          <UserCircle className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                          <input 
                            type="text" 
                            defaultValue="Hendra Wijaya"
                            className="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none"
                          />
                       </div>
                    </div>

                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Alamat Email</label>
                       <div className="relative">
                          <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                          <input 
                            type="email" 
                            defaultValue="hendra.driver@singgalangjaya.com"
                            className="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none"
                          />
                       </div>
                    </div>

                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Nomor Telepon</label>
                       <div className="relative">
                          <Phone className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                          <input 
                            type="text" 
                            defaultValue="0812-3456-7890"
                            className="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none"
                          />
                       </div>
                    </div>

                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Wilayah Operasi</label>
                       <div className="relative">
                          <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                          <input 
                            type="text" 
                            defaultValue="Sumatera Barat - Riau"
                            className="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-blue-600/5 transition-all outline-none"
                          />
                       </div>
                    </div>
                 </div>

                 <div className="mt-12 p-6 bg-blue-50 rounded-[2rem] border border-blue-100 flex items-start gap-4">
                    <div className="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
                       <Car className="w-5 h-5" />
                    </div>
                    <div>
                       <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Informasi Kendaraan</p>
                       <p className="text-[11px] font-bold text-blue-800/70 leading-relaxed">Anda saat ini ditugaskan untuk mengoperasikan Toyota Avanza (BA 1234 XY). Pastikan kendaraan dalam kondisi prima sebelum keberangkatan.</p>
                    </div>
                 </div>
              </div>
           )}

           {activeTab === 'password' && (
              <div className="p-10 animate-in slide-in-from-right-4 duration-300">
                 <div className="flex items-center gap-3 mb-10 pb-6 border-b border-slate-50">
                    <div className="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                       <Lock className="w-5 h-5" />
                    </div>
                    <h4 className="text-lg font-black text-slate-900 uppercase tracking-widest">Ganti Kata Sandi</h4>
                 </div>

                 <div className="max-w-md space-y-8">
                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Kata Sandi Saat Ini</label>
                       <input 
                         type="password" 
                         placeholder="••••••••"
                         className="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none"
                       />
                    </div>

                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Kata Sandi Baru</label>
                       <input 
                         type="password" 
                         placeholder="Minimal 8 Karakter"
                         className="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none"
                       />
                    </div>

                    <div className="space-y-2">
                       <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Ulangi Kata Sandi Baru</label>
                       <input 
                         type="password" 
                         placeholder="Konfirmasi Sandi"
                         className="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-black text-slate-900 focus:ring-4 focus:ring-indigo-600/5 transition-all outline-none"
                       />
                    </div>

                    <button className="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                       Update Kata Sandi
                    </button>
                 </div>
              </div>
           )}

           {activeTab === 'activity' && (
              <div className="p-10 animate-in slide-in-from-right-4 duration-300">
                 <div className="flex items-center gap-3 mb-10 pb-6 border-b border-slate-50">
                    <div className="w-10 h-10 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                       <History className="w-5 h-5" />
                    </div>
                    <h4 className="text-lg font-black text-slate-900 uppercase tracking-widest">Aktivitas Terakhir</h4>
                 </div>

                 <div className="space-y-4">
                    {[
                       { date: "Hari Ini, 08:00", event: "Login Driver App", device: "Safari / iPhone 15 Pro", status: "Berhasil" },
                       { date: "24 Mei, 19:45", event: "Update Manifes Trip TRP-101", device: "Safari / iPhone 15 Pro", status: "Berhasil" },
                       { date: "23 Mei, 08:15", event: "Login Driver App", device: "Safari / iPhone 15 Pro", status: "Berhasil" },
                    ].map((log, idx) => (
                       <div key={idx} className="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                          <div className="flex items-center gap-4">
                             <div className="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 shadow-sm">
                                <History className="w-4 h-4" />
                             </div>
                             <div>
                                <p className="text-xs font-black text-slate-900">{log.event}</p>
                                <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{log.date} • {log.device}</p>
                             </div>
                          </div>
                          <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border bg-slate-100 text-slate-500 border-slate-200`}>
                             {log.status}
                          </span>
                       </div>
                    ))}
                 </div>

                 <button className="w-full mt-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-2 border-dashed border-slate-100 rounded-3xl hover:bg-slate-50 transition-colors">
                    Lihat Aktivitas Lengkap
                 </button>
              </div>
           )}

        </div>

      </div>
    </div>
  );
}
