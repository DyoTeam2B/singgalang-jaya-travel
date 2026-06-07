import { 
  Search, Filter, Eye, Edit, Car, Users, 
  CheckCircle2, AlertCircle, Wrench, X, User, Zap,
  Camera, Package, PlusCircle, Trash2, Power
} from "lucide-react";
import { useState } from "react";

// --- Types & Mock Data ---
type ArmadaStatus = "Tersedia" | "Digunakan" | "Maintenance";

interface Armada {
  id: string;
  name: string;
  model: string;
  plate: string;
  capacity: number;
  status: ArmadaStatus;
  assignedDriver: string | null;
  driverPhone: string | null;
  mileage: string;
  lastService: string;
  imageUrl: string;
}

const initialArmadas: Armada[] = [
  {
    id: "ARM-001",
    name: "Singgalang-01",
    model: "Toyota Avanza",
    plate: "BA 1234 XY",
    capacity: 5,
    status: "Digunakan",
    assignedDriver: "Hendra Gunawan",
    driverPhone: "0812-3456-7890",
    mileage: "45,230 km",
    lastService: "12 Apr 2026",
    imageUrl: "https://images.unsplash.com/photo-1733965961857-99e62b0fc869?w=400&h=300&fit=crop",
  },
  {
    id: "ARM-002",
    name: "Singgalang-02",
    model: "Toyota Hiace",
    plate: "BM 5678 QA",
    capacity: 14,
    status: "Tersedia",
    assignedDriver: null,
    driverPhone: null,
    mileage: "112,450 km",
    lastService: "05 Mei 2026",
    imageUrl: "https://images.unsplash.com/photo-1694802180731-1ee2ea37692a?w=400&h=300&fit=crop",
  },
  {
    id: "ARM-003",
    name: "Singgalang-03",
    model: "Toyota Innova",
    plate: "BA 9988 ZZ",
    capacity: 7,
    status: "Digunakan",
    assignedDriver: "Ahmad Fauzi",
    driverPhone: "0852-1122-3344",
    mileage: "67,100 km",
    lastService: "28 Apr 2026",
    imageUrl: "https://images.unsplash.com/photo-1464219789935-c2d9d9aba644?w=400&h=300&fit=crop",
  },
  {
    id: "ARM-004",
    name: "Singgalang-04",
    model: "Suzuki Ertiga",
    plate: "BM 1122 QQ",
    capacity: 5,
    status: "Maintenance",
    assignedDriver: null,
    driverPhone: null,
    mileage: "89,000 km",
    lastService: "19 Mei 2026",
    imageUrl: "https://images.unsplash.com/photo-1733965961857-99e62b0fc869?w=400&h=300&fit=crop",
  },
];

export function AdminArmada() {
  const [armadas, setArmadas] = useState<Armada[]>(initialArmadas);
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState("Semua Status");
  const [selectedArmadaId, setSelectedArmadaId] = useState<string | null>("ARM-001");
  
  // Modal State
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    name: "",
    model: "Toyota Avanza",
    plate: "",
    capacity: 5,
    status: "Tersedia" as ArmadaStatus,
    imageUrl: "https://images.unsplash.com/photo-1733965961857-99e62b0fc869?w=400&h=300&fit=crop"
  });

  const selectedArmada = armadas.find(a => a.id === selectedArmadaId);

  const filteredArmadas = armadas.filter(a => {
    const matchesSearch = a.id.toLowerCase().includes(searchTerm.toLowerCase()) || 
                         a.plate.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         a.name.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "Semua Status" || a.status === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const handleAddArmada = (e: React.FormEvent) => {
    e.preventDefault();
    const id = `ARM-00${armadas.length + 1}`;
    const newItem: Armada = {
      id,
      ...formData,
      assignedDriver: null,
      driverPhone: null,
      mileage: "0 km",
      lastService: "N/A",
    };
    setArmadas([...armadas, newItem]);
    setIsAddModalOpen(false);
    setFormData({
      name: "",
      model: "Toyota Avanza",
      plate: "",
      capacity: 5,
      status: "Tersedia",
      imageUrl: "https://images.unsplash.com/photo-1733965961857-99e62b0fc869?w=400&h=300&fit=crop"
    });
  };

  const setMaintenance = (id: string) => {
    setArmadas(armadas.map(a => a.id === id ? { ...a, status: "Maintenance" } : a));
  };

  const getStatusBadge = (status: ArmadaStatus) => {
    const styles: Record<ArmadaStatus, string> = {
      "Tersedia": "bg-emerald-50 text-emerald-700 border-emerald-200",
      "Digunakan": "bg-blue-50 text-blue-700 border-blue-200",
      "Maintenance": "bg-rose-50 text-rose-700 border-rose-200",
    };
    return styles[status] || "bg-slate-100 text-slate-700 border-slate-200";
  };

  const getStatusIcon = (status: ArmadaStatus) => {
    if (status === "Tersedia") return <CheckCircle2 className="w-3 h-3" />;
    if (status === "Digunakan") return <Zap className="w-3 h-3" />;
    if (status === "Maintenance") return <Wrench className="w-3 h-3" />;
    return <AlertCircle className="w-3 h-3" />;
  };

  return (
    <div className="pb-8 flex flex-col h-full font-poppins relative">
      {/* Top Header */}
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-1">Manajemen Armada</h1>
          <p className="text-sm font-bold text-slate-500 uppercase tracking-widest">
            Kelola unit kendaraan dan status ketersediaan armada.
          </p>
        </div>
        <button 
          onClick={() => setIsAddModalOpen(true)}
          className="flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-white bg-slate-900 px-6 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 whitespace-nowrap"
        >
          <PlusCircle className="w-4 h-4" /> Tambah Armada
        </button>
      </div>

      {/* Toolbar */}
      <div className="mb-8 flex flex-col xl:flex-row xl:items-center gap-4">
        <div className="relative w-full xl:w-[400px] shrink-0">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            type="text" 
            placeholder="Cari ID, Nama, atau Plat Nomor..." 
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
            <option>Digunakan</option>
            <option>Maintenance</option>
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
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Armada</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama & Model</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kapasitas</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                  <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {filteredArmadas.map((armada) => {
                  const isSelected = selectedArmadaId === armada.id;
                  return (
                    <tr 
                      key={armada.id} 
                      onClick={() => setSelectedArmadaId(armada.id)}
                      className={`transition-all cursor-pointer group ${isSelected ? 'bg-slate-50' : 'hover:bg-slate-50/50'}`}
                    >
                      <td className="px-8 py-6 whitespace-nowrap">
                        <span className="text-xs font-black text-slate-900">{armada.id}</span>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        <div className="flex items-center gap-4">
                          <div className="w-12 h-9 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shadow-sm shrink-0">
                             <img src={armada.imageUrl} alt={armada.name} className="w-full h-full object-cover" />
                          </div>
                          <div>
                            <p className="text-xs font-black text-slate-900">{armada.name}</p>
                            <p className="text-[10px] font-bold text-slate-400 uppercase">{armada.model}</p>
                          </div>
                        </div>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        <span className="inline-block px-3 py-1 bg-slate-100 rounded-lg text-xs font-black text-slate-900 tracking-tighter border border-slate-200">
                           {armada.plate}
                        </span>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap text-center">
                        <div className="inline-flex items-center gap-2 text-slate-600">
                           <Users className="w-4 h-4 text-slate-300" />
                           <span className="text-xs font-black">{armada.capacity}</span>
                        </div>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap">
                        <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black border uppercase tracking-widest ${getStatusBadge(armada.status)}`}>
                          {armada.status}
                        </span>
                      </td>
                      <td className="px-8 py-6 whitespace-nowrap text-right">
                        <div className="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                           <button className="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-blue-600 transition-colors shadow-sm">
                              <Edit className="w-4 h-4" />
                           </button>
                           {armada.status !== "Maintenance" && (
                             <button 
                               onClick={(e) => { e.stopPropagation(); setMaintenance(armada.id); }}
                               className="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-rose-500 transition-colors shadow-sm"
                               title="Set Maintenance"
                             >
                                <Wrench className="w-4 h-4" />
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
        {selectedArmada ? (
          <div className="w-full xl:w-[400px] h-full shrink-0 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden animate-in slide-in-from-right-8 duration-500">
            <div className="p-8 border-b border-slate-50 flex items-center justify-between">
              <h3 className="text-sm font-black text-slate-900 uppercase tracking-widest">Profil Kendaraan</h3>
              <span className="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">{selectedArmada.id}</span>
            </div>

            <div className="flex-1 overflow-y-auto p-8 space-y-8 no-scrollbar">
              <div className="flex flex-col items-center">
                <div className="w-full aspect-[4/3] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-900/10 border border-slate-100 mb-8 relative group">
                  <img src={selectedArmada.imageUrl} alt={selectedArmada.name} className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                  <div className="absolute top-4 right-4">
                     <span className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl text-[10px] font-black border uppercase tracking-widest shadow-xl backdrop-blur-md ${getStatusBadge(selectedArmada.status)}`}>
                        {selectedArmada.status}
                     </span>
                  </div>
                </div>
                <div className="text-center">
                   <h3 className="text-2xl font-black text-slate-900 leading-tight mb-2">{selectedArmada.name}</h3>
                   <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">{selectedArmada.model}</p>
                   <div className="inline-block px-6 py-3 bg-slate-900 text-white rounded-2xl text-xl font-black tracking-[0.2em] shadow-2xl shadow-slate-900/30">
                      {selectedArmada.plate}
                   </div>
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                 <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 text-center">
                    <Users className="w-6 h-6 text-slate-300 mx-auto mb-2" />
                    <p className="text-[10px] font-black text-slate-400 uppercase mb-1">Kapasitas</p>
                    <p className="text-sm font-black text-slate-900">{selectedArmada.capacity} PAX</p>
                 </div>
                 <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 text-center">
                    <Zap className="w-6 h-6 text-slate-300 mx-auto mb-2" />
                    <p className="text-[10px] font-black text-slate-400 uppercase mb-1">Mileage</p>
                    <p className="text-sm font-black text-slate-900">{selectedArmada.mileage}</p>
                 </div>
              </div>

              <div className="space-y-4">
                 <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Driver Saat Ini</p>
                 {selectedArmada.assignedDriver ? (
                    <div className="p-6 bg-blue-50 rounded-[2rem] border border-blue-100 flex items-center gap-4">
                       <div className="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-blue-600 border border-blue-200 shadow-sm">
                          <User className="w-6 h-6" />
                       </div>
                       <div>
                          <p className="text-xs font-black text-slate-900">{selectedArmada.assignedDriver}</p>
                          <p className="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">{selectedArmada.driverPhone}</p>
                       </div>
                    </div>
                 ) : (
                    <div className="p-8 border-2 border-dashed border-slate-100 rounded-[2rem] flex flex-col items-center justify-center text-center opacity-50">
                       <User className="w-8 h-8 text-slate-300 mb-3" />
                       <p className="text-[10px] font-black text-slate-400 uppercase">Armada sedang parkir / maintenance</p>
                    </div>
                 )}
              </div>
            </div>

            <div className="p-8 bg-slate-50/50 border-t border-slate-100 grid grid-cols-2 gap-4">
               <button className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Edit Info</button>
               {selectedArmada.status === "Maintenance" ? (
                 <button 
                  onClick={() => setArmadas(armadas.map(a => a.id === selectedArmada.id ? { ...a, status: "Tersedia" } : a))}
                  className="py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all"
                 >
                   Selesai Servis
                 </button>
               ) : (
                 <button 
                  onClick={() => setMaintenance(selectedArmada.id)}
                  className="py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all"
                 >
                   Maintenance
                 </button>
               )}
            </div>
          </div>
        ) : (
          <div className="w-full xl:w-[400px] h-full flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 opacity-40">
             <Car className="w-16 h-16 text-slate-300 mb-6" />
             <p className="text-sm font-black text-slate-500 uppercase tracking-[0.2em]">Pilih Armada untuk Detail</p>
          </div>
        )}
      </div>

      {/* Add Armada Modal */}
      {isAddModalOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-300">
              <form onSubmit={handleAddArmada}>
                <div className="p-8 border-b border-slate-100 flex items-center justify-between">
                   <div>
                      <h3 className="text-2xl font-black text-slate-900 tracking-tight">Tambah Armada Baru</h3>
                      <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Daftarkan unit kendaraan baru ke sistem</p>
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
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kendaraan (Callsign)</label>
                      <div className="relative">
                         <Zap className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="text" 
                           value={formData.name}
                           onChange={(e) => setFormData({...formData, name: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="Contoh: Singgalang-05" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Merek / Model</label>
                      <div className="relative">
                         <Car className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="text" 
                           value={formData.model}
                           onChange={(e) => setFormData({...formData, model: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="Contoh: Toyota Hiace" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</label>
                      <div className="relative">
                         <Package className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="text" 
                           value={formData.plate}
                           onChange={(e) => setFormData({...formData, plate: e.target.value})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="Contoh: BA 1234 XY" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas (Pax)</label>
                      <div className="relative">
                         <Users className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                         <input 
                           required
                           type="number" 
                           value={formData.capacity}
                           onChange={(e) => setFormData({...formData, capacity: parseInt(e.target.value)})}
                           className="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all"
                           placeholder="Default: 5" 
                         />
                      </div>
                   </div>

                   <div className="space-y-4 md:col-span-2">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Awal</label>
                      <div className="grid grid-cols-3 gap-4">
                         {["Tersedia", "Digunakan", "Maintenance"].map((s) => (
                           <button
                             type="button"
                             key={s}
                             onClick={() => setFormData({...formData, status: s as ArmadaStatus})}
                             className={`py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border ${
                               formData.status === s 
                                 ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' 
                                 : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'
                             }`}
                           >
                             {s}
                           </button>
                         ))}
                      </div>
                   </div>
                   
                   <div className="space-y-4 md:col-span-2">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto Kendaraan</label>
                      <div className="w-full h-40 border-2 border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center bg-slate-50/50 hover:bg-slate-50 transition-all cursor-pointer">
                         <Camera className="w-8 h-8 text-slate-300 mb-2" />
                         <p className="text-[10px] font-bold text-slate-400 uppercase">Klik untuk upload foto armada</p>
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
                      Simpan Data Armada
                   </button>
                </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
