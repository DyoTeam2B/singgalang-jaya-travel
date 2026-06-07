import { CarFront, MessageCircle, Info } from "lucide-react";
import { ImageWithFallback } from "./figma/ImageWithFallback";

export function Charter() {
  return (
    <section id="charter" className="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
          <div className="flex flex-col lg:flex-row">
            
            {/* Left Content */}
            <div className="flex-1 p-10 md:p-16 lg:p-20 flex flex-col justify-center">
              <div className="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider w-fit mb-6">
                <CarFront className="w-4 h-4" /> Layanan Privat
              </div>
              
              <h2 className="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-6">
                Sewa Mobil Charter
              </h2>
              <p className="text-slate-500 text-lg md:text-xl font-medium mb-8 leading-relaxed">
                Tersedia layanan charter mobil untuk kebutuhan perjalanan pribadi dan rombongan.
              </p>

              <div className="bg-slate-50 rounded-2xl p-5 flex items-start gap-4 border border-slate-100 mb-10">
                <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm border border-slate-200/60">
                  <Info className="w-5 h-5 text-blue-600" />
                </div>
                <p className="text-slate-700 font-semibold leading-snug pt-2">
                  Pemesanan charter dilakukan melalui admin.
                </p>
              </div>

              <a
                href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20layanan%20charter%20Singgalang%20Jaya."
                target="_blank"
                rel="noreferrer"
                className="bg-[#25D366] hover:bg-[#128C7E] text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-[#25D366]/20 active:scale-[0.98] w-fit flex items-center gap-2"
              >
                <MessageCircle className="w-5 h-5" />
                Hubungi Admin
              </a>
            </div>

            {/* Right Image */}
            <div className="flex-1 bg-slate-100 relative min-h-[350px] lg:min-h-full">
              <ImageWithFallback 
                src="https://images.unsplash.com/photo-1650807486050-a142ea418b19?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3aGl0ZSUyMHRveW90YSUyMGF2YW56YSUyMG1vZGVybiUyMGNhcnxlbnwxfHx8fDE3NzkxMTU3NzF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
                alt="Charter Toyota Avanza"
                className="absolute inset-0 w-full h-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-r from-slate-900/10 to-transparent pointer-events-none"></div>
            </div>

          </div>
        </div>
      </div>
    </section>
  );
}