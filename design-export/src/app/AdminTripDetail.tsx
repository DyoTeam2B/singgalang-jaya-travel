import { 
  Car, User, MapPin, Calendar, Clock, 
  Users, Wallet, ArrowLeft, MoreVertical, 
  CheckCircle2, XCircle, UserPlus, Truck,
  ChevronRight, ExternalLink, Phone,
  Banknote, Info, AlertCircle, Search, X
} from "lucide-react";
import { useState } from "react";
import { Link, useNavigate } from "react-router";

// --- Types ---
interface Driver {
  id: string;
  name: string;
  phone: string;
  status: "Available" | "Assigned";
  avatar: string;
}

interface Passenger {
  id: string;
  name: string;
  phone: string;
  pickup: string;
  destination: string;
  pax: number;
  totalFare: number;
  paymentStatus: "DP Lunas" | "Lunas" | "Pending";
}

interface TripDetail {
  id: string;
  route: string;
  date: string;
  shift: "Pagi" | "Malam";
  time: string;
  vehicle: { plate: string; model: string } | null;
  driver: { name: string; id: string } | null;
  capacity: number;
  status: "Scheduled" | "On Trip" | "Completed" | "Cancelled";
  passengers: Passenger[];
}

// --- Mock Drivers ---
const mockDrivers: Driver[] = [
  { id: "DRV-001", name: "Hendra Wijaya", phone: "0812-3456-7890", status: "Assigned", avatar: "https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=100&h=100&fit=crop" },
  { id: "DRV-002", name: "Budi Pratama", phone: "0812-1111-2222", status: "Available", avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop" },
  { id: "DRV-003", name: "Agus Santoso", phone: "0852-3333-4444", status: "Available", avatar: "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=100&h=100&fit=crop" },
  { id: "DRV-004", name: "Reza Kurniawan", phone: "0821-5555-6666", status: "Assigned", avatar: "https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=100&h=100&fit=crop" },
];

// --- Mock Trip Data ---
const initialTripData: TripDetail = {
  id: "TRP-102",
  route: "Padang Panjang → Pekanbaru",
  date: "2026-05-26",
  shift: "Pagi",
  time: "10:00 WIB",
  vehicle: { plate: "BA 1234 XY", model: "Toyota Avanza" },
  driver: { name: "Hendra Wijaya", id: "DRV-001" },
  capacity: 5,
  status: "Scheduled",
  passengers: [
    { 
      id: "BKG-101", name: "Budi Santoso", phone: "0812-3456-7890", 
      pickup: "Jl. Sudirman No. 12, Padang Panjang", destination: "Terminal AKAP, Pekanbaru", 
      pax: 2, totalFare: 400000, paymentStatus: "DP Lunas" 
    },
    { 
      id: "BKG-102", name: "Siti Rahma", phone: "0821-9988-7766", 
      pickup: "Simpang PDG Panjang (Dekat SPBU)", destination: "Mall SKA, Pekanbaru", 
      pax: 1, totalFare: 200000, paymentStatus: "Lunas" 
    },
    { 
      id: "BKG-105", name: "Reza Pratama", phone: "0852-1122-3344", 
      pickup: "Loket Singgalang Padang Panjang", destination: "Loket Singgalang Pekanbaru", 
      pax: 2, totalFare: 400000, paymentStatus: "DP Lunas" 
    },
  ]
};

export function AdminTripDetail() {
  const [trip, setTrip] = useState<TripDetail>(initialTripData);
  const [isDriverModalOpen, setIsDriverModalOpen] = useState(false);
  const [isVehicleModalOpen, setIsVehicleModalOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState("");
  const [vehicleSearchTerm, setVehicleSearchTerm] = useState("");
  const navigate = useNavigate();

  const mockVehicles = [
    { plate: "BA 1234 XY", model: "Toyota Avanza", capacity: 5, status: "Available" },
    { plate: "BA 8822 AB", model: "Toyota Innova", capacity: 7, status: "Available" },
    { plate: "BA 9900 CD", model: "Suzuki Ertiga", capacity: 5, status: "In Use" },
    { plate: "BA 7711 EF", model: "Toyota Hiace", capacity: 14, status: "Maintenance" },
    { plate: "BA 4433 GH", model: "Toyota Avanza", capacity: 5, status: "Available" },
  ];

  const filteredDrivers = mockDrivers.filter(d => 
    d.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
    d.id.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const filteredVehicles = mockVehicles.filter(v => 
    v.plate.toLowerCase().includes(vehicleSearchTerm.toLowerCase()) || 
    v.model.toLowerCase().includes(vehicleSearchTerm.toLowerCase())
  );

  const handleAssignDriver = (driver: Driver) => {
    setTrip(prev => ({
      ...prev,
      driver: { name: driver.name, id: driver.id }
    }));
    setIsDriverModalOpen(false);
  };

  const handleAssignVehicle = (v: any) => {
    setTrip(prev => ({
      ...prev,
      vehicle: { plate: v.plate, model: v.model },
      capacity: v.capacity
    }));
    setIsVehicleModalOpen(false);
  };

  const totalPax = trip.passengers.reduce((sum, p) => sum + p.pax, 0);
  const estRevenue = trip.passengers.reduce((sum, p) => sum + p.totalFare, 0);
  const remainingSeats = trip.capacity - totalPax;

  const formatCurrency = (amount: number) => 
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);

  const getStatusColor = (status: string) => {
    switch (status) {
      case "Scheduled": return "bg-blue-50 text-blue-600 border-blue-100";
      case "On Trip": return "bg-amber-50 text-amber-600 border-amber-100";
      case "Completed": return "bg-emerald-50 text-emerald-600 border-emerald-100";
      case "Cancelled": return "bg-red-50 text-red-600 border-red-100";
      default: return "bg-slate-50 text-slate-600 border-slate-100";
    }
  };

  return (
    <div className="flex-1 overflow-y-auto bg-slate-50 p-8 h-full relative">
      <div className="max-w-6xl mx-auto space-y-8">
        
        {/* Breadcrumbs & Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="space-y-1">
            <button 
              onClick={() => navigate(-1)}
              className="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors mb-2"
            >
              <ArrowLeft className="w-3 h-3" /> Kembali ke Trip
            </button>
            <div className="flex items-center gap-4">
               <h1 className="text-3xl font-black text-slate-900 tracking-tight">Detail Trip {trip.id}</h1>
               <span className={`px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border ${getStatusColor(trip.status)}`}>
                  {trip.status}
               </span>
            </div>
          </div>
          
          <div className="flex items-center gap-3">
             <button className="flex items-center gap-2 px-6 py-4 bg-white border border-slate-200 text-red-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all">
                <XCircle className="w-4 h-4" /> Batalkan Trip
             </button>
             <button className="flex items-center gap-2 px-6 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-xl shadow-blue-600/20">
                <CheckCircle2 className="w-4 h-4" /> Selesaikan Trip
             </button>
          </div>
        </div>

        {/* Top Info Cards */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
           
           {/* Main Trip Info */}
           <div className="lg:col-span-2 bg-white rounded-[3rem] border border-slate-100 shadow-sm p-8 space-y-8">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div className="space-y-6">
                    <div>
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Rute Perjalanan</p>
                       <h3 className="text-xl font-black text-slate-900">{trip.route}</h3>
                    </div>
                    <div className="flex items-center gap-8">
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                          <div className="flex items-center gap-2">
                             <Calendar className="w-4 h-4 text-blue-600" />
                             <span className="text-sm font-bold text-slate-700">{trip.date}</span>
                          </div>
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Shift</p>
                          <div className="flex items-center gap-2">
                             <Clock className="w-4 h-4 text-blue-600" />
                             <span className="text-sm font-bold text-slate-700">{trip.shift} ({trip.time})</span>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div className="space-y-6">
                    <div className="flex items-start justify-between p-6 bg-slate-50 rounded-[2rem] border border-slate-100 group">
                       <div className="flex items-center gap-4">
                          <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-slate-400 group-hover:text-blue-600 transition-colors">
                             <Truck className="w-6 h-6" />
                          </div>
                          <div>
                             <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Armada</p>
                             <p className="text-sm font-black text-slate-900">{trip.vehicle?.plate || 'Belum Ditentukan'}</p>
                             <p className="text-[10px] font-bold text-slate-400">{trip.vehicle?.model || '-'}</p>
                          </div>
                       </div>
                       <button 
                         onClick={() => setIsVehicleModalOpen(true)}
                         className="p-2 text-slate-300 hover:text-blue-600 transition-colors"
                       >
                          <MoreVertical className="w-5 h-5" />
                       </button>
                    </div>

                    <div className="flex items-start justify-between p-6 bg-slate-50 rounded-[2rem] border border-slate-100 group">
                       <div className="flex items-center gap-4">
                          <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-slate-400 group-hover:text-blue-600 transition-colors">
                             <User className="w-6 h-6" />
                          </div>
                          <div>
                             <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Driver</p>
                             <p className="text-sm font-black text-slate-900">{trip.driver?.name || 'Belum Ditentukan'}</p>
                             <p className="text-[10px] font-bold text-slate-400">{trip.driver?.id || '-'}</p>
                          </div>
                       </div>
                       <button 
                         onClick={() => setIsDriverModalOpen(true)}
                         className="p-2 text-slate-300 hover:text-blue-600 transition-colors"
                       >
                          <MoreVertical className="w-5 h-5" />
                       </button>
                    </div>
                 </div>
              </div>

              {/* Action Buttons for Assigning */}
              <div className="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                 <button 
                    onClick={() => setIsVehicleModalOpen(true)}
                    className="flex items-center justify-center gap-3 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all"
                 >
                    <Truck className="w-4 h-4" /> Atur Armada
                 </button>
                 <button 
                    onClick={() => setIsDriverModalOpen(true)}
                    className="flex items-center justify-center gap-3 py-4 bg-white border border-slate-200 text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all"
                 >
                    <UserPlus className="w-4 h-4" /> Pilih Driver
                 </button>
              </div>
           </div>

           {/* Trip Summary Card */}
           <div className="bg-slate-900 rounded-[3rem] p-8 text-white relative overflow-hidden">
              <div className="absolute top-0 right-0 w-32 h-32 bg-blue-600/20 rounded-full -mr-16 -mt-16 blur-3xl"></div>
              
              <div className="relative z-10 space-y-8">
                 <div>
                    <h3 className="text-xs font-black text-white/40 uppercase tracking-[0.2em] mb-6">Ringkasan Trip</h3>
                    <div className="space-y-6">
                       <div className="flex items-center justify-between">
                          <div className="flex items-center gap-3">
                             <Users className="w-4 h-4 text-blue-400" />
                             <span className="text-xs font-bold text-white/60">Total Penumpang</span>
                          </div>
                          <span className="text-lg font-black">{totalPax} / {trip.capacity} PAX</span>
                       </div>
                       <div className="flex items-center justify-between">
                          <div className="flex items-center gap-3">
                             <Info className="w-4 h-4 text-amber-400" />
                             <span className="text-xs font-bold text-white/60">Kursi Tersedia</span>
                          </div>
                          <span className="text-lg font-black text-amber-400">{remainingSeats} Kursi</span>
                       </div>
                       <div className="w-full h-px bg-white/10"></div>
                       <div>
                          <p className="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1">Estimasi Pendapatan</p>
                          <h4 className="text-3xl font-black text-blue-400">{formatCurrency(estRevenue)}</h4>
                       </div>
                    </div>
                 </div>

                 <div className="bg-white/5 rounded-2xl p-4 border border-white/5">
                    <div className="flex items-center gap-3 mb-2">
                       <AlertCircle className="w-4 h-4 text-blue-400" />
                       <span className="text-[10px] font-black uppercase tracking-widest">Catatan Admin</span>
                    </div>
                    <p className="text-[11px] text-white/50 leading-relaxed font-medium">
                       Pastikan driver sudah dikonfirmasi 2 jam sebelum keberangkatan dimulai.
                    </p>
                 </div>
              </div>
           </div>
        </div>

        {/* Passenger Manifest Table */}
        <div className="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
           <div className="p-8 border-b border-slate-50 flex items-center justify-between">
              <h3 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-3">
                <Users className="w-5 h-5 text-blue-600" /> Manifest Penumpang
              </h3>
              <button className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">
                 Cetak Manifest (PDF)
              </button>
           </div>

           <div className="overflow-x-auto">
              <table className="w-full">
                 <thead>
                    <tr className="bg-slate-50/50">
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Penumpang</th>
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Titik Jemput & Tujuan</th>
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pax</th>
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Biaya</th>
                       <th className="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Bayar</th>
                       <th className="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                 </thead>
                 <tbody className="divide-y divide-slate-50">
                    {trip.passengers.map((p) => (
                       <tr key={p.id} className="hover:bg-slate-50/30 transition-colors group">
                          <td className="px-8 py-6">
                             <div>
                                <p className="text-xs font-black text-slate-900 mb-0.5">{p.name}</p>
                                <p className="text-[10px] font-bold text-slate-400">{p.id}</p>
                             </div>
                          </td>
                          <td className="px-8 py-6">
                             <div className="flex items-center gap-2">
                                <Phone className="w-3.5 h-3.5 text-slate-400" />
                                <span className="text-xs font-bold text-slate-700">{p.phone}</span>
                             </div>
                          </td>
                          <td className="px-8 py-6 max-w-xs">
                             <div className="space-y-1">
                                <div className="flex items-center gap-2">
                                   <div className="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                   <p className="text-[10px] font-bold text-slate-600 truncate">{p.pickup}</p>
                                </div>
                                <div className="flex items-center gap-2">
                                   <div className="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                   <p className="text-[10px] font-bold text-slate-400 truncate">{p.destination}</p>
                                </div>
                             </div>
                          </td>
                          <td className="px-8 py-6">
                             <span className="text-xs font-black text-slate-900">{p.pax} PAX</span>
                          </td>
                          <td className="px-8 py-6">
                             <p className="text-xs font-black text-slate-900">{formatCurrency(p.totalFare)}</p>
                          </td>
                          <td className="px-8 py-6">
                             <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase border ${
                                p.paymentStatus === 'Lunas' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'
                             }`}>
                                {p.paymentStatus}
                             </span>
                          </td>
                          <td className="px-8 py-6 text-right">
                             <button className="p-2 text-slate-300 hover:text-blue-600 transition-colors">
                                <ExternalLink className="w-4 h-4" />
                             </button>
                          </td>
                       </tr>
                    ))}
                 </tbody>
              </table>
           </div>
        </div>

      </div>

      {/* Driver Selection Modal */}
      {isDriverModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4">
           {/* Backdrop */}
           <div 
             onClick={() => setIsDriverModalOpen(false)}
             className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
           />
           
           {/* Modal Content */}
           <div className="relative w-full max-w-xl bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
              {/* Modal Header */}
              <div className="p-8 border-b border-slate-50 flex items-center justify-between shrink-0">
                 <div>
                    <h3 className="text-xl font-black text-slate-900">Pilih Driver</h3>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tugaskan driver untuk trip {trip.id}</p>
                 </div>
                 <button 
                   onClick={() => setIsDriverModalOpen(false)}
                   className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 hover:text-slate-900 transition-all"
                 >
                    <X className="w-5 h-5" />
                 </button>
              </div>

              {/* Search Bar */}
              <div className="p-8 py-6 border-b border-slate-50 shrink-0">
                 <div className="relative">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" />
                    <input 
                      type="text" 
                      placeholder="Cari Nama atau ID Driver..."
                      className="w-full pl-12 pr-6 py-4 bg-slate-50 rounded-2xl text-xs font-bold border border-transparent focus:border-blue-500 focus:bg-white transition-all outline-none"
                      value={searchTerm}
                      onChange={(e) => setSearchTerm(e.target.value)}
                    />
                 </div>
              </div>

              {/* Driver List */}
              <div className="flex-1 overflow-y-auto p-4 space-y-2">
                 {filteredDrivers.length > 0 ? filteredDrivers.map((driver) => (
                    <div 
                      key={driver.id}
                      className="p-4 rounded-3xl border border-slate-50 hover:border-blue-200 hover:bg-blue-50/20 transition-all flex items-center justify-between group"
                    >
                       <div className="flex items-center gap-4">
                          <div className="w-12 h-12 rounded-2xl overflow-hidden border border-slate-100 shrink-0">
                             <img src={driver.avatar} alt={driver.name} className="w-full h-full object-cover" />
                          </div>
                          <div>
                             <h4 className="text-sm font-black text-slate-900">{driver.name}</h4>
                             <div className="flex items-center gap-3 mt-1">
                                <span className="text-[10px] font-bold text-slate-400">{driver.id}</span>
                                <span className="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <span className={`text-[9px] font-black uppercase tracking-tighter ${
                                   driver.status === 'Available' ? 'text-emerald-600' : 'text-amber-600'
                                }`}>
                                   {driver.status}
                                </span>
                             </div>
                          </div>
                       </div>
                       <button 
                         onClick={() => handleAssignDriver(driver)}
                         className={`px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all ${
                            driver.status === 'Available' 
                              ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/10 hover:bg-blue-700' 
                              : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                         }`}
                         disabled={driver.status !== 'Available'}
                       >
                          Tugaskan
                       </button>
                    </div>
                 )) : (
                   <div className="py-20 text-center">
                      <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                         <Search className="w-6 h-6 text-slate-300" />
                      </div>
                      <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Driver tidak ditemukan</p>
                   </div>
                 )}
              </div>

              {/* Modal Footer */}
              <div className="p-8 bg-slate-50/50 border-t border-slate-50 text-center shrink-0">
                 <p className="text-[10px] font-bold text-slate-400">
                    * Hanya driver dengan status <span className="text-emerald-600">Available</span> yang dapat ditugaskan.
                 </p>
              </div>
           </div>
        </div>
      )}

      {/* Vehicle Selection Modal */}
      {isVehicleModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4">
           {/* Backdrop */}
           <div 
             onClick={() => setIsVehicleModalOpen(false)}
             className="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
           />
           
           {/* Modal Content */}
           <div className="relative w-full max-w-xl bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
              {/* Modal Header */}
              <div className="p-8 border-b border-slate-50 flex items-center justify-between shrink-0">
                 <div>
                    <h3 className="text-xl font-black text-slate-900">Atur Armada</h3>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pilih kendaraan untuk trip {trip.id}</p>
                 </div>
                 <button 
                   onClick={() => setIsVehicleModalOpen(false)}
                   className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 hover:text-slate-900 transition-all"
                 >
                    <X className="w-5 h-5" />
                 </button>
              </div>

              {/* Search Bar */}
              <div className="p-8 py-6 border-b border-slate-50 shrink-0">
                 <div className="relative">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300" />
                    <input 
                      type="text" 
                      placeholder="Cari Plat Nomor atau Model..."
                      className="w-full pl-12 pr-6 py-4 bg-slate-50 rounded-2xl text-xs font-bold border border-transparent focus:border-blue-500 focus:bg-white transition-all outline-none"
                      value={vehicleSearchTerm}
                      onChange={(e) => setVehicleSearchTerm(e.target.value)}
                    />
                 </div>
              </div>

              {/* Vehicle List */}
              <div className="flex-1 overflow-y-auto p-4 space-y-2">
                 {filteredVehicles.length > 0 ? filteredVehicles.map((v) => (
                    <div 
                      key={v.plate}
                      className="p-4 rounded-3xl border border-slate-50 hover:border-blue-200 hover:bg-blue-50/20 transition-all flex items-center justify-between group"
                    >
                       <div className="flex items-center gap-4">
                          <div className="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors shrink-0">
                             <Truck className="w-6 h-6" />
                          </div>
                          <div>
                             <h4 className="text-sm font-black text-slate-900">{v.plate}</h4>
                             <div className="flex items-center gap-3 mt-1">
                                <span className="text-[10px] font-bold text-slate-400">{v.model}</span>
                                <span className="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <span className="text-[10px] font-bold text-slate-400">{v.capacity} PAX</span>
                                <span className="w-1 h-1 bg-slate-200 rounded-full"></span>
                                <span className={`text-[9px] font-black uppercase tracking-tighter ${
                                   v.status === 'Available' ? 'text-emerald-600' : v.status === 'Maintenance' ? 'text-red-600' : 'text-amber-600'
                                }`}>
                                   {v.status}
                                </span>
                             </div>
                          </div>
                       </div>
                       <button 
                         onClick={() => handleAssignVehicle(v)}
                         className={`px-6 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all ${
                            v.status === 'Available' 
                              ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/10 hover:bg-blue-700' 
                              : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                         }`}
                         disabled={v.status !== 'Available'}
                       >
                          Pilih
                       </button>
                    </div>
                 )) : (
                   <div className="py-20 text-center">
                      <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                         <Truck className="w-6 h-6 text-slate-300" />
                      </div>
                      <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Armada tidak ditemukan</p>
                   </div>
                 )}
              </div>

              {/* Modal Footer */}
              <div className="p-8 bg-slate-50/50 border-t border-slate-50 text-center shrink-0">
                 <p className="text-[10px] font-bold text-slate-400">
                    * Hanya kendaraan dengan status <span className="text-emerald-600">Available</span> yang dapat dipilih.
                 </p>
              </div>
           </div>
        </div>
      )}
    </div>
  );
}