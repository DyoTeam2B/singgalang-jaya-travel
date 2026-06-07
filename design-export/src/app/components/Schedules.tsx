import { ArrowRight, Sun, Moon, Car, Users, CheckCircle2 } from "lucide-react";
import { Link } from "react-router";

export function Schedules() {
  return (
    <section id="jadwal" className="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header - Top Left Aligned */}
        <div className="mb-16 max-w-2xl">
          <h2 className="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
            Jadwal Keberangkatan Hari Ini
          </h2>
          <p className="text-slate-500 text-lg md:text-xl font-medium">
            Pilih jadwal perjalanan yang tersedia
          </p>
        </div>

        {/* Schedule Cards Grid */}
        <div className="grid lg:grid-cols-2 gap-10">
          
          {/* Card 1: Morning Shift */}
          <div className="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col p-6">
            
            {/* Soft Glassmorphism Effect */}
            <div className="absolute top-0 right-0 w-64 h-64 bg-blue-50/50 rounded-full blur-3xl -z-0 pointer-events-none group-hover:bg-blue-100/50 transition-colors"></div>

            <div className="relative z-10 flex-1 flex flex-col">
              
              {/* Top Area: Route & Badge */}
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div className="flex items-center flex-wrap gap-2 md:gap-3">
                  <div className="flex items-center gap-2">
                    <span className="w-2.5 h-2.5 rounded-full bg-blue-600 shadow-[0_0_0_4px_rgb(37,99,235,0.1)]"></span>
                    <span className="text-slate-900 font-bold text-lg md:text-xl tracking-tight">Padang Panjang</span>
                  </div>
                  <ArrowRight className="w-5 h-5 text-slate-300" />
                  <div className="flex items-center gap-2">
                    <span className="w-2.5 h-2.5 rounded-full border-2 border-slate-400 bg-white"></span>
                    <span className="text-slate-900 font-bold text-lg md:text-xl tracking-tight">Pekanbaru</span>
                  </div>
                </div>
                <div className="bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 border border-blue-100/50 w-fit">
                  <Sun className="w-4 h-4" /> Shift Pagi
                </div>
              </div>

              {/* Middle Area: Time & Seats */}
              <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-4">
                <div>
                  <p className="text-slate-500 text-sm font-semibold mb-1 uppercase tracking-wider">Keberangkatan</p>
                  <h3 className="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tighter">
                    08.00 <span className="text-slate-300 text-3xl font-medium mx-0.5">-</span> 10.00
                  </h3>
                </div>
                
                <div className="w-full sm:w-48 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                  <div className="flex justify-between items-end mb-2">
                    <span className="text-xs font-bold text-slate-500 uppercase tracking-wide">Sisa Kursi</span>
                    <span className="text-xl font-extrabold text-blue-600 leading-none">2</span>
                  </div>
                  {/* Progress Bar */}
                  <div className="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div className="h-full bg-blue-500 rounded-full" style={{ width: '40%' }}></div>
                  </div>
                  <p className="text-[10px] text-slate-400 font-semibold mt-1.5 text-right">Kapasitas: 5</p>
                </div>
              </div>

              {/* Ticket Divider */}
              <div className="relative -mx-6 my-6">
                <div className="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-r border-slate-200/60 z-20 shadow-inner"></div>
                <div className="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-l border-slate-200/60 z-20 shadow-inner"></div>
                <div className="border-t-2 border-dashed border-slate-200 w-full relative z-10"></div>
              </div>

              {/* Vehicle & Price Area */}
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex items-center gap-3">
                  <div className="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 shrink-0">
                    <Car className="w-6 h-6 text-slate-700" />
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <p className="text-slate-900 font-bold text-sm">Toyota Avanza</p>
                      <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
                    </div>
                    <p className="text-slate-500 text-xs font-medium flex items-center gap-1">
                      <Users className="w-3.5 h-3.5" /> Maks. 5 Penumpang
                    </p>
                  </div>
                </div>
                <div>
                  <p className="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Harga per kursi</p>
                  <p className="text-slate-900 font-extrabold text-xl tracking-tight">Rp 150.000</p>
                </div>
              </div>

            </div>

            {/* Bottom Button */}
            <Link to="/booking?shift=pagi" className="w-full h-12 mt-6 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-900/15 active:scale-[0.98] flex justify-center items-center relative z-10">
              Booking Sekarang
            </Link>
            
          </div>


          {/* Card 2: Night Shift */}
          <div className="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col p-6">
            
            {/* Soft Glassmorphism Effect */}
            <div className="absolute top-0 right-0 w-64 h-64 bg-slate-100/60 rounded-full blur-3xl -z-0 pointer-events-none group-hover:bg-slate-200/60 transition-colors"></div>

            <div className="relative z-10 flex-1 flex flex-col">
              
              {/* Top Area: Route & Badge */}
              <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div className="flex items-center flex-wrap gap-2 md:gap-3">
                  <div className="flex items-center gap-2">
                    <span className="w-2.5 h-2.5 rounded-full bg-slate-900 shadow-[0_0_0_4px_rgb(15,23,42,0.1)]"></span>
                    <span className="text-slate-900 font-bold text-lg md:text-xl tracking-tight">Padang Panjang</span>
                  </div>
                  <ArrowRight className="w-5 h-5 text-slate-300" />
                  <div className="flex items-center gap-2">
                    <span className="w-2.5 h-2.5 rounded-full border-2 border-slate-400 bg-white"></span>
                    <span className="text-slate-900 font-bold text-lg md:text-xl tracking-tight">Pekanbaru</span>
                  </div>
                </div>
                <div className="bg-slate-900 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 w-fit">
                  <Moon className="w-4 h-4" /> Shift Malam
                </div>
              </div>

              {/* Middle Area: Time & Seats */}
              <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-4">
                <div>
                  <p className="text-slate-500 text-sm font-semibold mb-1 uppercase tracking-wider">Keberangkatan</p>
                  <h3 className="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tighter">
                    20.00 <span className="text-slate-300 text-3xl font-medium mx-0.5">-</span> 22.00
                  </h3>
                </div>
                
                <div className="w-full sm:w-48 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                  <div className="flex justify-between items-end mb-2">
                    <span className="text-xs font-bold text-slate-500 uppercase tracking-wide">Sisa Kursi</span>
                    <span className="text-xl font-extrabold text-blue-600 leading-none">4</span>
                  </div>
                  {/* Progress Bar */}
                  <div className="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div className="h-full bg-blue-500 rounded-full" style={{ width: '80%' }}></div>
                  </div>
                  <p className="text-[10px] text-slate-400 font-semibold mt-1.5 text-right">Kapasitas: 5</p>
                </div>
              </div>

              {/* Ticket Divider */}
              <div className="relative -mx-6 my-6">
                <div className="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-r border-slate-200/60 z-20 shadow-inner"></div>
                <div className="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-l border-slate-200/60 z-20 shadow-inner"></div>
                <div className="border-t-2 border-dashed border-slate-200 w-full relative z-10"></div>
              </div>

              {/* Vehicle & Price Area */}
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div className="flex items-center gap-3">
                  <div className="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 shrink-0">
                    <Car className="w-6 h-6 text-slate-700" />
                  </div>
                  <div>
                    <div className="flex items-center gap-2">
                      <p className="text-slate-900 font-bold text-sm">Toyota Avanza</p>
                      <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
                    </div>
                    <p className="text-slate-500 text-xs font-medium flex items-center gap-1">
                      <Users className="w-3.5 h-3.5" /> Maks. 5 Penumpang
                    </p>
                  </div>
                </div>
                <div>
                  <p className="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Harga per kursi</p>
                  <p className="text-slate-900 font-extrabold text-xl tracking-tight">Rp 150.000</p>
                </div>
              </div>

            </div>

            {/* Bottom Button */}
            <Link to="/booking?shift=malam" className="w-full h-12 mt-6 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-900/15 active:scale-[0.98] flex justify-center items-center relative z-10">
              Booking Sekarang
            </Link>
            
          </div>

        </div>
      </div>
    </section>
  );
}