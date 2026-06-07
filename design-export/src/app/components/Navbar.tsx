import { Navigation, Menu, X, User } from "lucide-react";
import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router";

export function Navbar() {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [activeSection, setActiveSection] = useState("");
  const location = useLocation();

  useEffect(() => {
    if (location.pathname !== "/") {
      setActiveSection("");
      return;
    }

    const handleScroll = () => {
      const sections = ["home", "jadwal", "armada", "charter", "cek-status", "kontak"];
      let currentSection = "";
      
      // Get current scroll position plus an offset for the navbar
      const scrollY = window.scrollY + 100;

      for (const section of sections) {
        const el = document.getElementById(section);
        if (el) {
          const { top, height } = el.getBoundingClientRect();
          const offsetTop = top + window.scrollY;
          
          if (scrollY >= offsetTop && scrollY < offsetTop + height) {
            currentSection = section;
          }
        }
      }
      
      // If we are at the very top, highlight home
      if (window.scrollY < 50) {
        currentSection = "home";
      }

      setActiveSection(currentSection);
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    // Trigger once on mount
    setTimeout(handleScroll, 100);
    
    return () => window.removeEventListener("scroll", handleScroll);
  }, [location.pathname]);

  const navLinks = [
    { name: "Home", href: "/#home", id: "home" },
    { name: "Jadwal", href: "/#jadwal", id: "jadwal" },
    { name: "Armada", href: "/#armada", id: "armada" },
    { name: "Charter", href: "/#charter", id: "charter" },
    { name: "Cek Status", href: "/cek-status", id: "cek-status", type: "link" },
    { name: "Kontak", href: "/#kontak", id: "kontak" },
  ];

  const handleMenuClick = () => {
    setIsMobileMenuOpen(false);
  };

  return (
    <nav className="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] transition-all">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-20">
          
          {/* LEFT SIDE: Brand & Logo */}
          <Link to="/" className="flex items-center gap-3 shrink-0 cursor-pointer group">
            <div className="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
              <Navigation className="w-5 h-5 text-white" />
            </div>
            <div className="flex flex-col">
              <span className="font-extrabold text-slate-900 text-lg leading-none tracking-tight mb-0.5">
                Singgalang Jaya
              </span>
              <span className="font-bold text-blue-600 text-[10px] uppercase tracking-[0.15em] leading-none">
                Travel
              </span>
            </div>
          </Link>

          {/* CENTER MENU: Desktop Navigation */}
          <div className="hidden lg:flex items-center gap-8">
            {navLinks.map((link) => {
              const isActive = activeSection === link.id || (link.type === "link" && location.pathname === link.href);
              
              const LinkComponent = link.type === "link" ? Link : "a";
              const linkProps = link.type === "link" ? { to: link.href } : { href: link.href };

              return (
                <LinkComponent
                  key={link.name}
                  {...linkProps}
                  className={`text-sm font-semibold transition-colors relative group py-2 ${
                    isActive ? "text-blue-600" : "text-slate-500 hover:text-blue-600"
                  }`}
                >
                  {link.name}
                  {/* Subtle Hover Underline Indicator */}
                  <span className={`absolute bottom-0 left-0 h-0.5 bg-blue-600 transition-all duration-300 rounded-full ${
                    isActive ? "w-full opacity-100" : "w-0 opacity-0 group-hover:w-full group-hover:opacity-100"
                  }`}></span>
                </LinkComponent>
              );
            })}
          </div>

          {/* RIGHT SIDE: Action Buttons */}
          <div className="hidden lg:flex items-center shrink-0">
            <Link to="/login" className="flex items-center gap-2 text-sm font-bold text-white bg-slate-900 px-6 py-2.5 rounded-xl hover:bg-slate-800 transition-all shadow-sm">
              <User className="w-4 h-4" />
              Login
            </Link>
          </div>

          {/* Mobile Menu Toggle */}
          <div className="lg:hidden flex items-center">
            <button 
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="p-2 -mr-2 rounded-lg text-slate-500 hover:bg-slate-50 transition-colors"
              aria-label="Toggle menu"
            >
              {isMobileMenuOpen ? (
                <X className="w-6 h-6" />
              ) : (
                <Menu className="w-6 h-6" />
              )}
            </button>
          </div>

        </div>
      </div>

      {/* Mobile Menu Dropdown */}
      {isMobileMenuOpen && (
        <div className="lg:hidden bg-white border-t border-slate-100 absolute w-full px-4 pt-4 pb-6 shadow-xl shadow-slate-900/5">
          <div className="flex flex-col space-y-1 mb-6">
            {navLinks.map((link) => {
              const isActive = activeSection === link.id || (link.type === "link" && location.pathname === link.href);
              const LinkComponent = link.type === "link" ? Link : "a";
              const linkProps = link.type === "link" ? { to: link.href } : { href: link.href };
              
              return (
                <LinkComponent
                  key={link.name}
                  {...linkProps}
                  onClick={handleMenuClick}
                  className={`px-4 py-3 rounded-xl text-base font-semibold transition-colors ${
                    isActive 
                      ? "text-blue-600 bg-blue-50" 
                      : "text-slate-600 hover:text-blue-600 hover:bg-blue-50"
                  }`}
                >
                  {link.name}
                </LinkComponent>
              );
            })}
          </div>
          <div className="flex flex-col px-2 border-t border-slate-100 pt-6">
            <Link to="/login" onClick={handleMenuClick} className="w-full flex justify-center items-center gap-2 text-sm font-bold text-white bg-slate-900 px-5 py-3 rounded-xl hover:bg-slate-800 transition-all shadow-sm">
              <User className="w-4 h-4" />
              Login
            </Link>
          </div>
        </div>
      )}
    </nav>
  );
}