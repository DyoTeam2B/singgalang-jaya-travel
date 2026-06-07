import { 
  LayoutDashboard, 
  History, 
  UserCircle, 
  LogOut, 
  Menu, 
  X,
  ChevronRight,
  Car,
  Bell,
  Search,
  Lock,
  ShieldCheck,
  Camera
} from "lucide-react";
import { useState, useEffect, createContext, useContext } from "react";
import { Link, useLocation, Outlet, useNavigate } from "react-router";

const menuItems = [
  { icon: LayoutDashboard, label: "Dashboard", path: "/driver" },
  { icon: History, label: "Riwayat Trip", path: "/driver/history" },
];

const LogoutContext = createContext<{ setIsLogoutModalOpen: (val: boolean) => void } | null>(null);

export function useDriverLogout() {
  const context = useContext(LogoutContext);
  if (!context) throw new Error("useDriverLogout must be used within LogoutProvider");
  return context;
}

export function DriverLayoutWrapper() {
  const [isLogoutModalOpen, setIsLogoutModalOpen] = useState(false);
  return (
    <LogoutContext.Provider value={{ setIsLogoutModalOpen }}>
      <DriverLayout isLogoutModalOpen={isLogoutModalOpen} setIsLogoutModalOpen={setIsLogoutModalOpen} />
    </LogoutContext.Provider>
  );
}

export function DriverLayout({ isLogoutModalOpen, setIsLogoutModalOpen }: { isLogoutModalOpen: boolean; setIsLogoutModalOpen: (val: boolean) => void }) {
  const [isSidebarMobileOpen, setIsSidebarMobileOpen] = useState(false);
  const location = useLocation();

  // Close sidebar on navigation (mobile)
  useEffect(() => {
    setIsSidebarMobileOpen(false);
  }, [location.pathname]);

  return (
    <div className="min-h-screen bg-slate-50 flex font-poppins relative overflow-x-hidden">
      
      {/* Sidebar - Desktop (Static) */}
      <aside className="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 lg:bg-slate-900 lg:text-slate-300 lg:z-50 lg:border-r lg:border-white/10 lg:shadow-2xl">
        <SidebarContent />
      </aside>

      {/* Sidebar - Mobile (Drawer) */}
      <div 
        className={`fixed inset-0 z-[100] lg:hidden transition-all duration-300 ${
          isSidebarMobileOpen ? "visible" : "invisible"
        }`}
      >
        {/* Overlay */}
        <div 
          className={`absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 ${
            isSidebarMobileOpen ? "opacity-100" : "opacity-0"
          }`}
          onClick={() => setIsSidebarMobileOpen(false)}
        />
        
        {/* Drawer Content */}
        <aside 
          className={`absolute inset-y-0 left-0 w-72 bg-slate-900 text-slate-300 shadow-2xl transition-transform duration-300 ease-out flex flex-col ${
            isSidebarMobileOpen ? "translate-x-0" : "-translate-x-full"
          }`}
        >
          <div className="flex justify-end p-4 lg:hidden">
            <button 
              onClick={() => setIsSidebarMobileOpen(false)}
              className="p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition-all"
            >
              <X className="w-5 h-5" />
            </button>
          </div>
          <SidebarContent />
        </aside>
      </div>

      {/* Main Container */}
      <div className="flex-1 flex flex-col lg:pl-72 min-h-screen">
        
        {/* Top Navbar */}
        <header className="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shrink-0">
          <div className="flex items-center gap-4">
            <button 
              onClick={() => setIsSidebarMobileOpen(true)}
              className="lg:hidden p-3 text-slate-500 hover:bg-slate-100 rounded-xl transition-all active:scale-95"
            >
              <Menu className="w-6 h-6" />
            </button>
            <div className="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl w-64 md:w-80 focus-within:ring-4 focus-within:ring-slate-900/5 transition-all">
              <Search className="w-4 h-4 text-slate-400 shrink-0" />
              <input 
                type="text" 
                placeholder="Cari trip..." 
                className="bg-transparent border-none outline-none text-xs font-bold w-full placeholder:text-slate-400"
              />
            </div>
          </div>

          <div className="flex items-center gap-4 sm:gap-6">
            <button className="relative p-2.5 text-slate-500 hover:bg-slate-50 rounded-xl transition-all">
              <Bell className="w-5 h-5" />
              <span className="absolute top-2.5 right-2.5 w-2 h-2 bg-blue-500 rounded-full border-2 border-white animate-pulse"></span>
            </button>
            <div className="h-8 w-px bg-slate-100 hidden sm:block"></div>
            <ProfileDropdown />
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-1 p-4 sm:p-6 lg:p-10 max-w-[1600px] mx-auto w-full">
          <Outlet />
        </main>
      </div>

      <LogoutModal isOpen={isLogoutModalOpen} onClose={() => setIsLogoutModalOpen(false)} />
    </div>
  );
}

function SidebarContent() {
  const location = useLocation();
  
  return (
    <div className="flex flex-col h-full overflow-hidden">
      {/* Sidebar Header */}
      <div className="h-24 flex items-center px-8 border-b border-white/5 shrink-0 bg-slate-950/20">
        <Link to="/driver" className="flex items-center gap-4">
          <div className="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20 animate-in zoom-in-50 duration-500">
            <Car className="w-5 h-5 text-white" />
          </div>
          <div className="flex flex-col">
            <span className="font-black text-white text-base leading-none tracking-tight mb-1 uppercase">
              Singgalang Jaya
            </span>
            <span className="font-bold text-blue-400 text-[10px] uppercase tracking-[0.2em] leading-none opacity-80">
              Driver Portal
            </span>
          </div>
        </Link>
      </div>

      {/* Sidebar Menu */}
      <div className="flex-1 overflow-y-auto py-8 px-6 space-y-2 no-scrollbar">
        <p className="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-6">Navigasi Driver</p>
        {menuItems.map((link) => {
          const isActive = location.pathname === link.path;
          return (
            <Link 
              key={link.label}
              to={link.path}
              className={`flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group ${
                isActive 
                  ? "bg-blue-600 text-white shadow-xl shadow-blue-600/20" 
                  : "text-slate-400 hover:bg-white/5 hover:text-white"
              }`}
            >
              <link.icon className={`w-5 h-5 transition-transform duration-300 group-hover:scale-110 ${isActive ? "text-white" : "text-slate-500 group-hover:text-blue-400"}`} />
              {link.label}
            </Link>
          );
        })}
      </div>

      {/* Sidebar Footer */}
      <div className="p-8 border-t border-white/5 bg-slate-950/20">
        <div className="p-5 bg-white/5 rounded-3xl border border-white/5">
          <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Status Kendaraan</p>
          <div className="flex items-center gap-2">
            <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            <span className="text-[10px] font-bold text-emerald-500 uppercase tracking-tighter truncate">BA 1234 XY • Ready</span>
          </div>
        </div>
      </div>
    </div>
  );
}

function ProfileDropdown() {
  const [isOpen, setIsOpen] = useState(false);
  const { setIsLogoutModalOpen } = useDriverLogout();
  const location = useLocation();

  useEffect(() => {
    setIsOpen(false);
  }, [location.pathname]);

  return (
    <div className="relative">
      <div 
        onClick={() => setIsOpen(!isOpen)}
        className="flex items-center gap-3 group cursor-pointer"
      >
        <div className="text-right hidden sm:block">
          <p className="text-xs font-black text-slate-900 leading-none mb-1 group-hover:text-blue-600 transition-colors uppercase tracking-widest">Hendra Wijaya</p>
          <p className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Driver Partner</p>
        </div>
        <div className="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center border border-slate-800 shadow-xl shadow-slate-900/10 relative overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=100&h=100&fit=crop" 
            alt="Driver" 
            className="w-full h-full object-cover opacity-80"
          />
          <div className="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
        </div>
      </div>

      {isOpen && (
        <>
          <div 
            className="fixed inset-0 z-[110]" 
            onClick={() => setIsOpen(false)}
          />
          <div className="absolute right-0 mt-4 w-56 bg-white rounded-[2rem] shadow-2xl border border-slate-100 py-4 z-[120] animate-in slide-in-from-top-2 duration-200">
            <div className="px-6 py-2 mb-2 border-b border-slate-50">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Akun Driver</p>
            </div>
            <Link to="/driver/profile" className="flex items-center gap-3 px-6 py-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
              <UserCircle className="w-4 h-4" />
              <span className="text-[11px] font-black uppercase tracking-widest">Profil Saya</span>
            </Link>
            <button 
              onClick={() => setIsLogoutModalOpen(true)}
              className="w-full flex items-center gap-3 px-6 py-3 text-rose-500 hover:bg-rose-50 transition-colors border-t border-slate-50 mt-2"
            >
              <LogOut className="w-4 h-4" />
              <span className="text-[11px] font-black uppercase tracking-widest">Logout</span>
            </button>
          </div>
        </>
      )}
    </div>
  );
}

function LogoutModal({ isOpen, onClose }: { isOpen: boolean; onClose: () => void }) {
  const navigate = useNavigate();
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
      <div className="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl overflow-hidden p-10 text-center animate-in zoom-in-95 duration-300">
        <div className="w-20 h-20 bg-rose-50 rounded-[2.5rem] flex items-center justify-center text-rose-500 mx-auto mb-6 shadow-xl shadow-rose-500/10">
          <LogOut className="w-10 h-10" />
        </div>
        <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-2">Konfirmasi Logout</h3>
        <p className="text-sm font-bold text-slate-400 mb-8 px-4 leading-relaxed">Apakah Anda yakin ingin keluar dari Portal Driver?</p>
        
        <div className="grid grid-cols-2 gap-4">
          <button onClick={onClose} className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
          <button 
            onClick={() => navigate("/login")}
            className="py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:bg-rose-600 transition-all"
          >
            Logout
          </button>
        </div>
      </div>
    </div>
  );
}
