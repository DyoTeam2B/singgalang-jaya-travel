import { MapPin, Phone, Mail, Instagram, ArrowRight } from "lucide-react";

export function Contact() {
  const contacts = [
    {
      icon: Phone,
      label: "WhatsApp Admin",
      value: "+62 812-3456-7890",
      link: "#",
      color: "bg-emerald-50 text-emerald-600",
      hover: "hover:shadow-[0_20px_40px_rgb(16,185,129,0.1)] hover:-translate-y-1"
    },
    {
      icon: Instagram,
      label: "Instagram",
      value: "@singgalangjayatravel",
      link: "#",
      color: "bg-pink-50 text-pink-600",
      hover: "hover:shadow-[0_20px_40px_rgb(236,72,153,0.1)] hover:-translate-y-1"
    },
    {
      icon: Mail,
      label: "Email",
      value: "hello@singgalangjaya.com",
      link: "mailto:hello@singgalangjaya.com",
      color: "bg-blue-50 text-blue-600",
      hover: "hover:shadow-[0_20px_40px_rgb(37,99,235,0.1)] hover:-translate-y-1"
    }
  ];

  return (
    <section id="kontak" className="py-24 md:py-32 bg-white relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header */}
        <div className="mb-16 max-w-2xl text-center mx-auto">
          <h2 className="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
            Hubungi Kami
          </h2>
          <p className="text-slate-500 text-lg md:text-xl font-medium">
            Kami siap membantu perjalanan Anda 24/7
          </p>
        </div>

        <div className="grid lg:grid-cols-12 gap-8 items-stretch">
          
          {/* Contact Cards Grid */}
          <div className="lg:col-span-7 grid sm:grid-cols-2 gap-6">
            {contacts.map((item, idx) => (
              <a 
                key={idx}
                href={item.link}
                className={`bg-white rounded-[2rem] border border-slate-200/60 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group flex flex-col items-start gap-6 ${item.hover}`}
              >
                <div className={`w-14 h-14 rounded-2xl flex items-center justify-center ${item.color}`}>
                  <item.icon className="w-6 h-6" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-slate-500 mb-1">{item.label}</p>
                  <p className="text-lg font-bold text-slate-900">{item.value}</p>
                </div>
                <div className="mt-auto pt-4 flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-slate-900 transition-colors">
                  Hubungi Sekarang <ArrowRight className="w-4 h-4" />
                </div>
              </a>
            ))}
          </div>

          {/* Address Card */}
          <div className="lg:col-span-5 bg-slate-900 rounded-[2rem] p-10 lg:p-12 flex flex-col justify-between text-white relative overflow-hidden group shadow-[0_20px_40px_rgb(15,23,42,0.2)]">
            {/* Subtle highlight effect */}
            <div className="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -z-0 pointer-events-none group-hover:bg-blue-500/20 transition-colors"></div>
            
            <div className="relative z-10 space-y-6">
              <div className="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                <MapPin className="w-6 h-6 text-white" />
              </div>
              
              <div>
                <h3 className="text-2xl font-extrabold mb-3 tracking-tight">Kantor Pusat</h3>
                <p className="text-slate-300 text-lg font-medium leading-relaxed">
                  Padang Panjang, Sumatera Barat
                </p>
              </div>
            </div>

            <div className="relative z-10 mt-12 pt-8 border-t border-white/10">
              <p className="text-slate-400 text-sm font-bold tracking-wide uppercase">Buka Setiap Hari: 06:00 - 22:00 WIB</p>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}