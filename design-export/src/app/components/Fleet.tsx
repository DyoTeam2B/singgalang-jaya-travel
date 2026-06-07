import { Users, MapPin, CheckCircle2, ChevronRight } from "lucide-react";
import { ImageWithFallback } from "./figma/ImageWithFallback";

const fleetData = [
  { id: 1, name: "Toyota Avanza", desc: "Travel nyaman dan siap berangkat", capacity: "Maksimal 5 Penumpang", route: "Padang Panjang ↔ Pekanbaru", status: "Tersedia" },
  { id: 2, name: "Toyota Avanza", desc: "Travel nyaman dan siap berangkat", capacity: "Maksimal 5 Penumpang", route: "Padang Panjang ↔ Pekanbaru", status: "Tersedia" },
  { id: 3, name: "Toyota Avanza", desc: "Travel nyaman dan siap berangkat", capacity: "Maksimal 5 Penumpang", route: "Padang Panjang ↔ Pekanbaru", status: "Tersedia" },
  { id: 4, name: "Toyota Avanza", desc: "Travel nyaman dan siap berangkat", capacity: "Maksimal 5 Penumpang", route: "Padang Panjang ↔ Pekanbaru", status: "Tersedia" },
];

export function Fleet() {
  return (
    <section id="armada" className="py-24 md:py-32 bg-white relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {/* Header - Top Left Aligned */}
        <div className="mb-16 max-w-2xl">
          <h2 className="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
            Armada Travel
          </h2>
          <p className="text-slate-500 text-lg md:text-xl font-medium">
            Kendaraan nyaman untuk perjalanan Anda
          </p>
        </div>

        {/* Fleet Cards Grid */}
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
          {fleetData.map((car) => (
            <div 
              key={car.id} 
              className="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 p-3 group flex flex-col"
            >
              
              {/* Top Area: Image & Floating Badge */}
              <div className="relative h-48 w-full rounded-[1.5rem] overflow-hidden mb-5 bg-slate-100">
                <ImageWithFallback 
                  src="https://images.unsplash.com/photo-1596429916858-6f97b5b9cf48?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3aGl0ZSUyMHRveW90YSUyMGF2YW56YSUyMGNhciUyMG9uJTIwc3RyZWV0fGVufDF8fHx8MTc3OTExMjczNHww&ixlib=rb-4.1.0&q=80&w=1080"
                  alt={car.name}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                />
                <div className="absolute top-3 right-3 bg-white/80 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-white/50">
                  <span className="relative flex h-2 w-2">
                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                  </span>
                  <span className="text-[10px] font-extrabold text-slate-800 uppercase tracking-wider">Tersedia</span>
                </div>
              </div>

              {/* Middle Area: Name, Desc, Capacity */}
              <div className="px-3 flex-grow flex flex-col">
                <h4 className="text-xl font-extrabold text-slate-900 tracking-tight mb-1.5">{car.name}</h4>
                <p className="text-slate-500 text-sm font-medium mb-5">{car.desc}</p>
                
                <div className="flex items-center gap-2.5 text-slate-700 bg-slate-50/80 w-fit px-3 py-2 rounded-xl border border-slate-100 mb-6">
                  <Users className="w-4 h-4 text-blue-600" />
                  <span className="text-sm font-bold">{car.capacity}</span>
                </div>
              </div>

              {/* Clean Divider Line */}
              <div className="mx-3 border-t border-dashed border-slate-200 mb-5"></div>

              {/* Bottom Area: Route, Status, Action */}
              

            </div>
          ))}
        </div>
      </div>
    </section>
  );
}