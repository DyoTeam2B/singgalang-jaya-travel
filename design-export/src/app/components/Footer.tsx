import { Navigation, Phone, Instagram, Mail, MapPin } from "lucide-react";

export function Footer() {
  return (
    <footer className="bg-white border-t border-slate-200 pt-20 pb-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Main Footer Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
          
          {/* Column 1: Brand & Description */}
          <div className="lg:pr-8">
            <div className="flex items-center gap-3 mb-6">
              <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <Navigation className="w-5 h-5 text-white" />
              </div>
              <h3 className="text-xl font-extrabold text-slate-900 tracking-tight leading-none">
                Singgalang Jaya Travel
              </h3>
            </div>
            <p className="text-slate-500 font-medium leading-relaxed text-sm">
              Layanan travel Padang Panjang ↔ Pekanbaru dengan sistem booking online.
            </p>
          </div>

          {/* Column 2: Menu */}
          <div>
            <h4 className="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Menu</h4>
            <ul className="space-y-4">
              {['Home', 'Jadwal', 'Armada', 'Charter', 'Kontak'].map((item) => (
                <li key={item}>
                  <a href={`#${item.toLowerCase()}`} className="text-slate-500 hover:text-blue-600 font-medium transition-colors text-sm">
                    {item}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 3: Kontak */}
          <div>
            <h4 className="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Kontak</h4>
            <ul className="space-y-4">
              <li>
                <a href="#" className="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                  <Phone className="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" />
                  <span className="font-medium text-sm">WhatsApp</span>
                </a>
              </li>
              <li>
                <a href="#" className="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                  <Instagram className="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" />
                  <span className="font-medium text-sm">Instagram</span>
                </a>
              </li>
              <li>
                <a href="#" className="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                  <Mail className="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" />
                  <span className="font-medium text-sm">Email</span>
                </a>
              </li>
            </ul>
          </div>

          {/* Column 4: Alamat */}
          <div>
            <h4 className="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Alamat</h4>
            <div className="flex items-start gap-3">
              <MapPin className="w-4 h-4 text-slate-400 shrink-0 mt-0.5" />
              <p className="font-medium leading-relaxed text-slate-500 text-sm">
                Padang Panjang,<br />
                Sumatera Barat
              </p>
            </div>
          </div>

        </div>

        {/* Bottom Footer & Divider */}
        <div className="border-t border-slate-200 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-slate-400 text-sm font-medium">
            &copy; {new Date().getFullYear()} Singgalang Jaya Travel. All rights reserved.
          </p>
          <div className="flex items-center gap-4">
            <a href="#" className="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors">
              <Instagram className="w-4 h-4" />
            </a>
            <a href="#" className="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors">
              <Mail className="w-4 h-4" />
            </a>
          </div>
        </div>
        
      </div>
    </footer>
  );
}