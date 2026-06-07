import { 
  Search, Filter, Eye, Edit, Car, Phone, 
  UserCircle, CheckCircle2, XCircle, AlertCircle, Map, X, CreditCard,
  Mail, Lock, Trash2, Power, UserPlus
} from "lucide-react";
import { useState } from "react";

// --- Types & Mock Data ---
type DriverStatus = "Tersedia" | "Sedang Bertugas" | "Tidak Aktif";

interface Driver {
  id: string;
  name: string;
  phone: string;
  email: string;
  licenseNumber: string;
  status: DriverStatus;
  assignedTrip: string | null;
  assignedVehicle: string | null;
  route: string | null;
  imageUrl: string;
  vehicleName: string;
  vehiclePlate: string;
  vehicleCapacity: number;
}

const initialDrivers: Driver[] = [
  {
    id: "DRV-001",
    name: "Hendra Gunawan",
    phone: "0812-3456-7890",
    email: "hendra@singgalang.com",
    licenseNumber: "SIM B1 - 1234567890",
    status: "Sedang Bertugas",
    assignedTrip: "TRP-2405-A1",
    assignedVehicle: "Toyota Avanza (BA 1234 XY)",
    route: "Padang Panjang ↔ Pekanbaru",
    imageUrl: "https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=150&h=150&fit=crop",
    vehicleName: "Toyota Avanza",
    vehiclePlate: "BA 1234 XY",
    vehicleCapacity: 5,
  },
  {
    id: "DRV-002",
    name: "Budi Santoso",
    phone: "0813-5678-9012",
    email: "budi@singgalang.com",
    licenseNumber: "SIM A - 0987654321",
    status: "Tersedia",
    assignedTrip: null,
    assignedVehicle: null,
    route: null,
    imageUrl: "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=150&h=150&fit=crop",
    vehicleName: "Toyota Avanza",
    vehiclePlate: "BA 5678 ZZ",
    vehicleCapacity: 5,
  },
  {
    id: "DRV-003",
    name: "Ahmad Fauzi",
    phone: "0852-1122-3344",
    email: "ahmad@singgalang.com",
    licenseNumber: "SIM B1 - 1122334455",
    status: "Sedang Bertugas",
    assignedTrip: "TRP-2405-B1",
    assignedVehicle: "Toyota Avanza (BM 5678 QA)",
    route: "Pekanbaru ↔ Padang Panjang",
    imageUrl: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&h=150&fit=crop",
    vehicleName: "Toyota Avanza",
    vehiclePlate: "BM 5678 QA",
    vehicleCapacity: 5,
  },
  {
    id: "DRV-004",
    name: "Reza Pratama",
    phone: "0811-9988-7766",
    email: "reza@singgalang.com",
    licenseNumber: "SIM A - 5566778899",
    status: "Tidak Aktif",
    assignedTrip: null,
    assignedVehicle: null,
    route: null,
    imageUrl: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop",
    vehicleName: "Toyota Avanza",
    vehiclePlate: "BA 9999 AA",
    vehicleCapacity: 5,
  },
];

export function AdminDriver() {
  const [drivers, setDrivers] = useState<Driver[]>(initialDrivers);
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState("Semua Status");
  const [selectedDriverId, setSelectedDriverId] = useState<string | null>("DRV-001");
  
  // Modal State
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [newDriver, setNewDriver] = useState({
    name: "",
    phone: "",
    email: "",
    password: "",
    status: "Tersedia" as DriverStatus,
    vehicleName: "Toyota Avanza",
    vehiclePlate: "",
    vehicleCapacity: 5,
  });

  const selectedDriver = drivers.find(d => d.id === selectedDriverId);

  const filteredDrivers = drivers.filter(d => {
    const matchesSearch = d.id.toLowerCase().includes(searchTerm.toLowerCase()) || 
                         d.name.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "Semua Status" || d.status === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const handleAddDriver = (e: React.FormEvent) => {
    e.preventDefault();
    const id = `DRV-00${drivers.length + 1}`;
    const driverToAdd: Driver = {
      id,
      name: newDriver.name,
      phone: newDriver.phone,
      email: newDriver.email,
      licenseNumber: "Belum Verifikasi",
      status: newDriver.status,
      assignedTrip: null,
      assignedVehicle: null,
      route: null,
      imageUrl: "https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=150&h=150&fit=crop",
      vehicleName: newDriver.vehicleName || "Toyota Avanza",
      vehiclePlate: newDriver.vehiclePlate,
      vehicleCapacity: newDriver.vehicleCapacity || 5,
    };

    setDrivers([...drivers, driverToAdd]);
    setIsAddModalOpen(false);
    setNewDriver({ name: "", phone: "", email: "", password: "", status: "Tersedia", vehicleName: "Toyota Avanza", vehiclePlate: "", vehicleCapacity: 5 });
  };

  const getStatusBadge = (status: DriverStatus) => {
    const styles: Record<DriverStatus, string> = {
      "Tersedia": "bg-emerald-50 text-emerald-700 border-emerald-200",
      "Sedang Bertugas": "bg-blue-50 text-blue-700 border-blue-200",
      "Tidak Aktif": "bg-slate-100 text-slate-600 border-slate-200",
    };
    return styles[status] || "bg-slate-100 text-slate-700 border-slate-200";
  };

  const deactivateDriver = (id: string) => {
    setDrivers(drivers.map(d => d.id === id ? { ...d, status: "Tidak Aktif" } : d));
  };

  return (
    <div className="pb-8 flex flex-col h-full font-poppins relative">
      {/* Top Header */}
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-1">Manajemen Driver</h1>
          <p className="text-sm font-bold text-slate-500 uppercase tracking-widest">
            Kelola data pengemudi dan ketersediaan armada.
          </p>
        </div>
        <button 
          onClick={() => setIsAddModalOpen(true)}
          className="flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-white bg-slate-900 px-6 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 whitespace-nowrap"
        >
          <UserPlus className="w-4 h-4" /> Tambah Driver
        </button>
      </div>

      {/* Toolbar */}
      <div className="mb-8 flex flex-col xl:flex-row xl:items-center gap-4">
        <div className="relative w-full xl:w-[400px] shrink-0">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            type="text" 
            placeholder="Cari ID Driver atau Nama..." 
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full pl-11 pr-4 py-4 bg-white border border-slate-100 rounded-[1.5rem] text-xs font-bold focus:ring-4 focus:ring-slate-900/5 outline-none transition-all shadow-xl shadow-slate-900/5"
          />
        </div>

        <div className="relative w-full xl:w-64">
          <Filter className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
          <select 
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
            className="w-full pl-11 pr-8 py-4 bg-white border border-slate-100 shadow-xl shadow-slate-900/5 rounded-[1.5rem] text-xs font-bold text-slate-900 appearance-none focus:ring-4 focus:ring-slate-900/5 cursor-pointer outline-none"
          >
            <option>Semua Status</option>
            <option>Tersedia</option>
            <option>Sedang Bertugas</option>
            <option>Tidak Aktif</option>
          </select>
        </div>
      </div>

      <div className="flex flex-col xl:flex-row gap-8 items-start h-[calc(100vh-280px)] overflow-hidden">
        
        {/* Left Column: Data Table */}
        <div className="flex-1 w-full h-full bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden">
          <div className="overflow-auto flex-1 no-scrollbar">
            <table className="w-full text-left border-collapse">
              <thead className="sticky top-0 z-10 bg-white">
                <tr className="border-b border-slate-50">
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned Trip</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {filteredDrivers.map((driver) => {
                  const isSelected = selectedDriverId === driver.id;
                  return (
                    <tr 
                      key={driver.id} 
                      onClick={() => setSelectedDriverId(driver.id)}
                      className={`transition-all cursor-pointer group ${isSelected ? 'bg-slate-50' : 'hover:bg-slate-50/50'}`}
                    >
                      <td className="px-8 py-6 whitespace-nowrap">
                        <div className="flex items-center gap-4">
                          <img src={driver.imageUrl} alt={driver.name} className="w-10 h-10 rounded-2xl object-cover border border-slate-100 shadow-sm" />
                          <div>
                            <p className="text-xs font-black text-slate-900">{driver.name}</p>
                            <p className="text-[10px] font-bold text-slate-400 uppercase">{driver.id}</p>
                          </div>
                        </div>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        <div className="text-[11px] font-bold text-slate-600">{driver.phone}</div>
                        <div className="text-[10px] font-medium text-slate-400">{driver.email}</div>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        {driver.assignedTrip ? (
                          <div className="flex items-center gap-2 text-blue-600">
                             <Map className="w-3.5 h-3.5" />
                             <span className="text-xs font-black">{driver.assignedTrip}</span>
                          </div>
                        ) : (
                          <span className="text-[10px] font-bold text-slate-300 uppercase italic">Standby</span>
                        )}
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black border uppercase tracking-widest ${getStatusBadge(driver.status)}`}>
                          {driver.status}
                        </span>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap text-right">
                        <div className="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                           <button className="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-blue-600 transition-colors shadow-sm">
                              <Edit className="w-4 h-4" />
                           </button>
                           {driver.status !== "Tidak Aktif" && (
                             <button 
                               onClick={(e) => { e.stopPropagation(); deactivateDriver(driver.id); }}
                               className="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-rose-500 transition-colors shadow-sm"
                             >
                                <Power className="w-4 h-4" />
                             </button>
                           )}
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>

        {/* Right Column: Detail Panel */}
        {selectedDriver ? (
          <div className="w-full xl:w-[400px] h-full shrink-0 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden animate-in slide-in-from-right-8 duration-500">
            <div className="p-8 border-b border-slate-50 flex items-center justify-between">
              <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Detail Profil Driver</h3>
              <span className="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">{selectedDriver.id}</span>
            </div>

            <div className="flex-1 overflow-y-auto p-8 space-y-8 no-scrollbar">
              <div className="flex flex-col items-center text-center">
                <div className="w-28 h-28 rounded-[2.5rem] p-1 border border-slate-100 shadow-inner mb-6">
                  <img src={selectedDriver.imageUrl} alt={selectedDriver.name} className="w-full h-full object-cover rounded-[2.2rem]" />
                </div>
                <h3 className="text-xl font-black text-slate-900 leading-tight mb-2">{selectedDriver.name}</h3>
                <span className={`inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl text-[10px] font-black border uppercase tracking-[0.2em] ${getStatusBadge(selectedDriver.status)}`}>
                  {selectedDriver.status}
                </span>
              </div>

              <div className="space-y-6">
                 <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-5">
                    <div className="flex items-center gap-4">
                       <div className="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100">
                          <Mail className="w-5 h-5" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                          <p className="text-xs font-bold text-slate-900">{selectedDriver.email}</p>
                       </div>
                    </div>
                    <div className="flex items-center gap-4">
                       <div className="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100">
                          <Phone className="w-5 h-5" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor HP</p>
                          <p className="text-xs font-bold text-slate-900">{selectedDriver.phone}</p>
                       </div>
                    </div>
                    <div className="flex items-center gap-4 pt-3 border-t border-slate-100">
                       <div className="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20">
                          <Car className="w-5 h-5" />
                       </div>
                       <div>
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kendaraan</p>
                          <p className="text-xs font-bold text-slate-900">{selectedDriver.vehicleName} · {selectedDriver.vehiclePlate}</p>
                          <p className="text-[10px] font-bold text-slate-400">Kapasitas {selectedDriver.vehicleCapacity} penumpang</p>
                       </div>
                    </div>
                 </div>

                 <div className="space-y-4">
                    <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tugas Trip</p>
                    {selectedDriver.assignedTrip ? (
                      <div className="p-6 bg-blue-900 rounded-[2rem] text-white shadow-xl shadow-blue-900/20 relative overflow-hidden">
                         <div className="relative z-10">
                            <p className="text-lg font-black leading-tight mb-1">{selectedDriver.route}</p>
                            <p className="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-6">{selectedDriver.assignedTrip}</p>
                            
                            <div className="flex items-center gap-3 bg-white/10 p-4 rounded-2xl backdrop-blur-md">
                               <Car className="w-5 h-5 text-blue-200" />
                               <span className="text-[11px] font-bold">{selectedDriver.assignedVehicle}</span>
                            </div>
                         </div>
                         <div className="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                      </div>
                    ) : (
                      <div className="p-8 border-2 border-dashed border-slate-100 rounded-[2rem] flex flex-col items-center justify-center text-center opacity-50">
                         <Map className="w-8 h-8 text-slate-300 mb-3" />
                         <p className="text-[10px] font-black text-slate-400 uppercase">Driver sedang standby</p>
                      </div>
                    )}
                 </div>
              </div>
            </div>

            <div className="p-8 bg-slate-50/50 border-t border-slate-100 grid grid-cols-2 gap-4">
               <button className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Edit Profil</button>
               <button className="py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all">Lihat Riwayat</button>
            </div>
          </div>
        ) : (
          <div className="w-full xl:w-[400px] h-full flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 opacity-40">
             <UserCircle className="w-16 h-16 text-slate-300 mb-6" />
             <p className="text-sm font-black text-slate-500 uppercase tracking-[0.2em]">Pilih Driver untuk Detail</p>
          </div>
        )}
      </div>

      {/* Add Driver Modal */}
      {isAddModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
              <form onSubmit={handleAddDriver}>
                <div className="p-8 border-b border-slate-100 flex items-center justify-between">
                   <div>
                      <h3 className="text-2xl font-black text-slate-900 tracking-tight">Tambah Driver Baru</h3>
                      <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lengkapi profil dan akses login driver</p>
                   </div>
                   <button 
                     type="button"
                     onClick={() => setIsAddModalOpen(false)} 
                     className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors"
                   >
                      <X className="w-5 h-5" />
                   </button>
                </div>
                
                <div className="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Driver</label>
                      <div className="relative">
                         <UserCircle className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="text" 
                           value={newDriver.name}
                           onChange={(e) => setNewDriver({...newDriver, name: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="Nama Lengkap" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor HP</label>
                      <div className="relative">
                         <Phone className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="tel" 
                           value={newDriver.phone}
                           onChange={(e) => setNewDriver({...newDriver, phone: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="0812-xxxx-xxxx" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email (Login ID)</label>
                      <div className="relative">
                         <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="email" 
                           value={newDriver.email}
                           onChange={(e) => setNewDriver({...newDriver, email: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="driver@singgalang.com" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                      <div className="relative">
                         <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="password" 
                           value={newDriver.password}
                           onChange={(e) => setNewDriver({...newDriver, password: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="••••••••" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4 md:col-span-2 p-5 bg-blue-50/40 border border-blue-100 rounded-2xl">
                      <p className="text-[10px] font-black text-blue-700 uppercase tracking-widest flex items-center gap-2"><Car className="w-3.5 h-3.5" /> Informasi Kendaraan Driver</p>
                      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div className="space-y-2">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kendaraan</label>
                          <input
                            type="text"
                            value={newDriver.vehicleName}
                            onChange={(e) => setNewDriver({ ...newDriver, vehicleName: e.target.value })}
                            className="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10"
                            placeholder="Toyota Avanza"
                          />
                        </div>
                        <div className="space-y-2">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</label>
                          <input
                            required
                            type="text"
                            value={newDriver.vehiclePlate}
                            onChange={(e) => setNewDriver({ ...newDriver, vehiclePlate: e.target.value })}
                            className="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10"
                            placeholder="BA 1234 XY"
                          />
                        </div>
                        <div className="space-y-2">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas</label>
                          <input
                            type="number"
                            min={1}
                            max={20}
                            value={newDriver.vehicleCapacity}
                            onChange={(e) => setNewDriver({ ...newDriver, vehicleCapacity: Number(e.target.value) })}
                            className="w-full px-4 py-3 bg-white border-none rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10"
                          />
                        </div>
                      </div>
                   </div>

                   <div className="space-y-4 md:col-span-2">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Awal</label>
                      <div className="grid grid-cols-3 gap-4">
                         {["Tersedia", "Sedang Bertugas", "Tidak Aktif"].map((s) => (
                           <button
                             type="button"
                             key={s}
                             onClick={() => setNewDriver({...newDriver, status: s as DriverStatus})}
                             className={`py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border ${
                               newDriver.status === s 
                                 ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' 
                                 : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'
                             }`}
                           >
                             {s}
                           </button>
                         ))}
                      </div>
                   </div>
                </div>

                <div className="p-10 bg-slate-50/50 border-t border-slate-100 flex gap-4">
                   <button 
                     type="button"
                     onClick={() => setIsAddModalOpen(false)}
                     className="flex-1 py-5 bg-white border border-slate-200 text-slate-600 rounded-3xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm"
                   >
                      Batal
                   </button>
                   <button 
                     type="submit"
                     className="flex-1 py-5 bg-blue-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-blue-600/20 hover:bg-blue-700 transition-all"
                   >
                      Simpan Data Driver
                   </button>
                </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
