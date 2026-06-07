import { CalendarCheck, ChevronRight, Clock, Users, ArrowRight } from "lucide-react";
import { Link } from "react-router";
import { ImageWithFallback } from "./figma/ImageWithFallback";

export function Hero() {
  return (
    <section id="home" className="relative pt-16 pb-24 md:pt-24 md:pb-32 bg-slate-50 overflow-hidden">
      {/* Subtle Background Pattern */}
      <div className="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"></div>
      
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 items-center">
          
          {/* Left Content */}
          <div className="lg:col-span-6 flex flex-col gap-8">
            
            
            <h1 className="text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 leading-[1.1] tracking-tight">
              Travel <br />
              <span className="text-blue-600">Padang Panjang</span>
              <span className="flex items-center gap-3 mt-2 text-slate-400">
                <ArrowRight className="w-8 h-8 md:w-12 md:h-12" />
                <span className="text-slate-900">Pekanbaru</span>
              </span>
            </h1>
            
            <p className="text-lg md:text-xl text-slate-500 max-w-lg leading-relaxed font-medium">
              Nikmati perjalanan nyaman, aman, dan tepat waktu dengan sistem booking tiket online langsung dari perangkat Anda.
            </p>
            
            <div className="flex flex-col sm:flex-row gap-4 mt-2">
              <Link to="/booking" className="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-semibold text-lg transition-all shadow-lg shadow-blue-600/25 flex items-center justify-center gap-2 group w-full sm:w-auto">
                Booking Sekarang
                <ChevronRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              <a href="#jadwal" className="bg-white hover:bg-slate-50 text-slate-700 px-8 py-4 rounded-full font-semibold text-lg transition-all shadow-sm border border-slate-200 flex items-center justify-center gap-2 group w-full sm:w-auto">
                <CalendarCheck className="w-5 h-5 text-slate-400 group-hover:text-blue-500 transition-colors" />
                Lihat Jadwal
              </a>
            </div>
          </div>

          {/* Right Content / Graphics */}
          <div className="lg:col-span-6 relative z-0">
            {/* Main Image Container */}
            <div className="relative rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-900/10 border-8 border-white bg-white">
              <ImageWithFallback 
                src="https://images.unsplash.com/photo-1596429916858-6f97b5b9cf48?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3aGl0ZSUyMHRveW90YSUyMGF2YW56YSUyMGNhciUyMG9uJTIwc3RyZWV0fGVufDF8fHx8MTc3OTExMjczNHww&ixlib=rb-4.1.0&q=80&w=1080" 
                alt="Toyota Avanza Travel"
                className="w-full h-auto object-cover aspect-[4/3] transform hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-slate-900/30 to-transparent pointer-events-none"></div>
            </div>

            {/* Floating Schedule Card */}
            

            {/* Floating Passenger Card */}
            
            
          </div>

        </div>
      </div>
    </section>
  );
}