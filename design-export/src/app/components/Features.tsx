import { MousePointerClick, Clock, MapPin, UserCheck } from "lucide-react";

const featuresData = [
  {
    id: 1,
    title: "Online Booking",
    description: "Pemesanan travel langsung melalui sistem website yang cepat, aman, dan mudah.",
    icon: MousePointerClick,
  },
  {
    id: 2,
    title: "Jadwal Teratur",
    description: "Keberangkatan shift pagi dan malam tersedia setiap hari tanpa khawatir delay.",
    icon: Clock,
  },
  {
    id: 3,
    title: "Door to Door Service",
    description: "Penjemputan dan pengantaran langsung sesuai dengan titik lokasi Anda.",
    icon: MapPin,
  },
  {
    id: 4,
    title: "Driver Profesional",
    description: "Driver berpengalaman, ramah, dan tersertifikasi untuk kenyamanan perjalanan Anda.",
    icon: UserCheck,
  },
];

export function Features() {
  return (
    <section id="fitur" className="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
      
      {/* Subtle Background Elements */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-64 bg-blue-100/40 rounded-full blur-[100px] -z-0 pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header - Centered */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <h2 className="text-slate-900 font-extrabold text-3xl md:text-4xl tracking-tight mb-4">
            Kenapa Memilih Singgalang Jaya Travel?
          </h2>
          <p className="text-slate-500 text-lg md:text-xl font-medium">
            Layanan travel nyaman dan mudah untuk perjalanan Anda
          </p>
        </div>

        {/* Features Grid */}
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
          {featuresData.map((feature) => {
            const Icon = feature.icon;
            return (
              <div 
                key={feature.id} 
                className="bg-white rounded-[2rem] border border-slate-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden"
              >
                
                {/* Subtle Hover Gradient (Glassmorphism highlight) */}
                <div className="absolute -inset-px bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>

                {/* Icon Container */}
                <div className="relative mb-8">
                  {/* Outer glowing ring on hover */}
                  <div className="absolute inset-0 bg-blue-100 rounded-2xl blur-xl opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                  
                  {/* Icon Box */}
                  <div className="relative w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300 shadow-sm">
                    <Icon className="w-7 h-7 text-slate-700 group-hover:text-white transition-colors duration-300" />
                  </div>
                </div>

                {/* Content */}
                <div className="relative z-10">
                  <h3 className="text-xl font-extrabold text-slate-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors duration-300">
                    {feature.title}
                  </h3>
                  <p className="text-slate-500 text-sm font-medium leading-relaxed">
                    {feature.description}
                  </p>
                </div>

              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}