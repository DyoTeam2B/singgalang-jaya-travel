import { 
  Search, Filter, Plus, Car, User, Calendar, 
  Clock, MapPin, Users, ChevronRight, X, 
  CheckCircle2, AlertCircle, MoreHorizontal,
  ChevronDown, ArrowRight, UserCircle, MoveRight,
  ArrowDownCircle, PlusCircle, LayoutGrid, ListFilter,
  ArrowUpRight, History, Trash2, XCircle as XCircleIcon,
  Phone, Banknote, ShieldCheck, ClipboardList, Truck,
  Pencil
} from "lucide-react";
import { useState } from "react";
import { Link, useNavigate } from "react-router";

// --- Types & Mock Data ---
type TripStatus = "New" | "Ready" | "On Trip" | "Cancelled";

interface Passenger {
  id: string;
  name: string;
  phone: string;
  pickup: string;
  destination: string;
  pax: number;
  paymentStatus: "DP Lunas" | "Lunas";
  remainingPayment: number;
}

interface Trip {
  id: string;
  route: string;
  date: string;
  shift: "Pagi" | "Malam";
  time: string;
  vehicle?: { plate: string; model: string };
  driver?: { name: string; id: string };
  passengers: Passenger[];
  status: TripStatus;
}

const mockVerifiedBookings: Passenger[] = [
  { id: "BKG-101", name: "Budi Santoso", phone: "0812-3456-7890", pickup: "Jl. Sudirman No. 12", destination: "Terminal AKAP", pax: 2, paymentStatus: "DP Lunas", remainingPayment: 300000 },
  { id: "BKG-102", name: "Siti Rahma", phone: "0821-9988-7766", pickup: "Simpang PDG Panjang", destination: "Mall SKA", pax: 1, paymentStatus: "Lunas", remainingPayment: 0 },
  { id: "BKG-103", name: "Ahmad Fauzi", phone: "0852-1122-3344", pickup: "Pasar Baru", destination: "Bandara SSK II", pax: 1, paymentStatus: "DP Lunas", remainingPayment: 150000 },
];

interface DriverOption {
  id: string; name: string; phone: string;
  vehicle: { plate: string; model: string; capacity: number };
}

const allDrivers: DriverOption[] = [
  { id: "DRV-001", name: "Hendra Wijaya", phone: "0812-1111-2222", vehicle: { plate: "BA 1234 XY", model: "Toyota Avanza", capacity: 5 } },
  { id: "DRV-002", name: "Rusdi Saputra", phone: "0813-3333-4444", vehicle: { plate: "BA 5678 ZK", model: "Toyota Avanza", capacity: 5 } },
  { id: "DRV-003", name: "Yusuf Ramadhan", phone: "0852-5555-6666", vehicle: { plate: "BA 9012 AB", model: "Toyota Avanza", capacity: 5 } },
  { id: "DRV-004", name: "Bambang Tri", phone: "0811-7777-8888", vehicle: { plate: "BA 3456 CD", model: "Toyota Avanza", capacity: 5 } },
];

// Available schedules feed Create Trip (in real app would come from /admin/jadwal)
interface ScheduleOption {
  id: string; route: string; date: string; shift: "Pagi" | "Malam"; departureTime: string; capacity: number;
}
const allSchedules: ScheduleOption[] = [
  { id: "SCH-001", route: "Padang Panjang → Pekanbaru", date: "2026-06-10", shift: "Pagi", departureTime: "08:00", capacity: 5 },
  { id: "SCH-002", route: "Padang Panjang → Pekanbaru", date: "2026-06-10", shift: "Malam", departureTime: "20:00", capacity: 5 },
  { id: "SCH-003", route: "Pekanbaru → Padang Panjang", date: "2026-06-11", shift: "Pagi", departureTime: "08:00", capacity: 5 },
];

const initialTrips: Trip[] = [
  {
    id: "TRP-001",
    route: "Padang Panjang → Pekanbaru",
    date: "2026-05-26",
    shift: "Pagi",
    time: "10:00 WIB",
    vehicle: { plate: "BA 1234 XY", model: "Toyota Avanza" },
    driver: { name: "Hendra Wijaya", id: "DRV-001" },
    passengers: [],
    status: "New"
  },
  {
    id: "TRP-002",
    route: "Padang Panjang → Pekanbaru",
    date: "2026-05-26",
    shift: "Malam",
    time: "20:00 WIB",
    status: "Ready",
    passengers: [
       { id: "BKG-105", name: "Reza Pratama", phone: "0852-1122-3344", pickup: "Loket PP", destination: "Mall SKA", pax: 2, paymentStatus: "DP Lunas", remainingPayment: 300000 }
    ],
  }
];

export function AdminTrip() {
  const [activeTab, setActiveTab] = useState<TripStatus>("New");
  const [trips, setTrips] = useState<Trip[]>(initialTrips);
  const [bookings, setBookings] = useState<Passenger[]>(mockVerifiedBookings);
  
  // Modals state
  const [selectedTripForManifest, setSelectedTripForManifest] = useState<Trip | null>(null);
  const [selectedTripForReady, setSelectedTripForReady] = useState<Trip | null>(null);
  const [isCancelConfirmOpen, setIsCancelConfirmOpen] = useState<string | null>(null);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
  const [editingTrip, setEditingTrip] = useState<Trip | null>(null);
  const [toast, setToast] = useState<string | null>(null);

  const navigate = useNavigate();

  const filteredTrips = trips.filter(t => t.status === activeTab);

  // Drivers/vehicles considered "busy" if assigned to a non-cancelled trip
  const busyDriverIds = new Set(
    trips.filter(t => t.status !== "Cancelled" && t.driver).map(t => t.driver!.id)
  );
  const availableDriversFor = (excludeTripId?: string) => {
    const exclude = excludeTripId ? trips.find(t => t.id === excludeTripId)?.driver?.id : undefined;
    return allDrivers.filter(d => !busyDriverIds.has(d.id) || d.id === exclude);
  };

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(null), 3000);
  };

  const handleCreateTrip = (data: { scheduleId: string; driverId: string }) => {
    const driver = allDrivers.find(d => d.id === data.driverId);
    const schedule = allSchedules.find(s => s.id === data.scheduleId);
    if (!driver || !schedule) return;
    const newTrip: Trip = {
      id: `TRP-${String(trips.length + 1).padStart(3, '0')}`,
      route: schedule.route,
      date: schedule.date,
      shift: schedule.shift,
      time: `${schedule.departureTime} WIB`,
      driver: { id: driver.id, name: driver.name },
      vehicle: { plate: driver.vehicle.plate, model: driver.vehicle.model },
      passengers: [],
      status: "New",
    };
    setTrips([...trips, newTrip]);
    setActiveTab("New");
    setIsCreateModalOpen(false);
    showToast(`Trip ${newTrip.id} dibuat • ${driver.name} (${driver.vehicle.plate}).`);
  };

  const handleEditTrip = (data: { tripId: string; date: string; shift: "Pagi" | "Malam"; driverId: string }) => {
    const driver = allDrivers.find(d => d.id === data.driverId);
    if (!driver) return;
    setTrips(trips.map(t => t.id === data.tripId ? {
      ...t,
      date: data.date,
      shift: data.shift,
      time: data.shift === "Pagi" ? "10:00 WIB" : "20:00 WIB",
      driver: { id: driver.id, name: driver.name },
      vehicle: { plate: driver.vehicle.plate, model: driver.vehicle.model },
    } : t));
    setEditingTrip(null);
    showToast(`Trip ${data.tripId} berhasil diperbarui.`);
  };

  const addBookingToTrip = (bookingId: string, tripId: string) => {
    const booking = bookings.find(b => b.id === bookingId);
    if (!booking) return;

    const trip = trips.find(t => t.id === tripId);
    if (!trip || trip.status === "Cancelled") return;

    const currentTotal = trip.passengers.reduce((sum, b) => sum + b.pax, 0);
    if (currentTotal + booking.pax > 5) {
      alert("Kapasitas penuh! (Max 5 Penumpang)");
      return;
    }

    setTrips(trips.map(t => 
      t.id === tripId ? { ...t, passengers: [...t.passengers, booking] } : t
    ));
    setBookings(bookings.filter(b => b.id !== bookingId));
  };

  const removeFromTrip = (booking: Passenger, tripId: string) => {
    setTrips(trips.map(t => 
      t.id === tripId ? { ...t, passengers: t.passengers.filter(b => b.id !== booking.id) } : t
    ));
    setBookings([...bookings, booking]);
  };

  const confirmSetReady = (tripId: string) => {
    setTrips(trips.map(t => 
      t.id === tripId ? { ...t, status: "Ready" } : t
    ));
    setSelectedTripForReady(null);
    setActiveTab("Ready");
  };

  const cancelTripAction = (tripId: string) => {
    const trip = trips.find(t => t.id === tripId);
    if (trip) {
      setBookings([...bookings, ...trip.passengers]);
      setTrips(trips.map(t => 
        t.id === tripId ? { ...t, status: "Cancelled", passengers: [] } : t
      ));
      setIsCancelConfirmOpen(null);
      setActiveTab("Cancelled");
    }
  };

  const formatCurrency = (amount: number) => 
    new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);

  return (
    <div className="pb-8 flex flex-col h-full font-poppins relative">
      {/* Page Header & Actions */}
      <div className="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-1">Manajemen Trip Aktif</h1>
          <p className="text-sm font-bold text-slate-500 uppercase tracking-widest">Atur keberangkatan dan alokasi armada</p>
        </div>
        <div className="flex items-center gap-3">
          <Link 
            to="/admin/trip/history"
            className="flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm"
          >
            <History className="w-4 h-4" /> Riwayat Trip
          </Link>
          <button
            onClick={() => setIsCreateModalOpen(true)}
            className="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10"
          >
            <Plus className="w-4 h-4" /> Trip Baru
          </button>
        </div>
      </div>

      {/* Trip Status Tabs */}
      <div className="flex items-center gap-2 bg-slate-100/80 p-1.5 rounded-[2rem] w-fit mb-8 border border-slate-200 shadow-inner overflow-x-auto no-scrollbar">
        {(["New", "Ready", "On Trip", "Cancelled"] as TripStatus[]).map((status) => (
          <button
            key={status}
            onClick={() => setActiveTab(status)}
            className={`px-6 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap ${
              activeTab === status 
                ? "bg-white text-slate-900 shadow-sm border border-slate-200" 
                : "text-slate-400 hover:text-slate-600 hover:bg-white/50"
            }`}
          >
            {status}
            <span className={`ml-3 px-2 py-0.5 rounded-lg text-[9px] ${
              activeTab === status ? "bg-slate-900 text-white" : "bg-slate-200 text-slate-500"
            }`}>
              {trips.filter(t => t.status === status).length}
            </span>
          </button>
        ))}
      </div>

      {/* Main Content Area */}
      <div className="flex flex-col xl:flex-row gap-8 items-start h-[calc(100vh-280px)]">
        
        {/* Left: Verified Booking List */}
        <div className="w-full xl:w-[420px] h-full flex flex-col shrink-0">
          <div className="flex items-center justify-between mb-4 px-4 py-2 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <h2 className="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
              <ShieldCheck className="w-4 h-4 text-emerald-500" /> Antrean Booking
            </h2>
            <span className="text-[10px] font-black text-slate-400 uppercase">{bookings.length} Penumpang</span>
          </div>
          
          <div className="flex-1 overflow-y-auto space-y-4 pr-1">
            {bookings.length > 0 ? bookings.map((b) => (
              <div key={b.id} className="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 group relative hover:border-blue-200 transition-all">
                <div className="flex items-center justify-between mb-4">
                  <div className="flex items-center gap-2">
                    <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{b.id}</span>
                    <span className="w-1 h-1 bg-slate-200 rounded-full"></span>
                    <span className="text-[10px] font-black text-blue-600 uppercase tracking-widest">{b.paymentStatus}</span>
                  </div>
                  <div className="px-2.5 py-1 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-lg shadow-slate-900/10">
                    {b.pax} PAX
                  </div>
                </div>

                <div className="mb-4">
                   <h4 className="text-sm font-black text-slate-900 mb-1">{b.name}</h4>
                   <p className="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                      <Phone className="w-3 h-3" /> {b.phone}
                   </p>
                </div>

                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 mb-4 text-[10px] font-bold text-slate-600">
                  <div className="flex items-start gap-2">
                    <div className="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1 shrink-0"></div>
                    <p className="truncate">{b.pickup}</p>
                  </div>
                  <div className="flex items-start gap-2">
                    <div className="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1 shrink-0"></div>
                    <p className="truncate">{b.destination}</p>
                  </div>
                </div>

                <div className="flex flex-col gap-2">
                   {filteredTrips.map(t => (
                      <button 
                        key={t.id}
                        onClick={() => addBookingToTrip(b.id, t.id)}
                        className="w-full py-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 border border-blue-100"
                      >
                        Add to {t.id} <ArrowUpRight className="w-3.5 h-3.5" />
                      </button>
                   ))}
                </div>
              </div>
            )) : (
              <div className="h-full flex flex-col items-center justify-center text-center opacity-40 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-200 p-8">
                <ClipboardList className="w-12 h-12 mb-4 text-slate-300" />
                <p className="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Tidak ada antrean booking</p>
              </div>
            )}
          </div>
        </div>

        {/* Right: Trip Card Management Area */}
        <div className="flex-1 w-full h-full flex flex-col">
          <div className="mb-4 px-4 py-2 bg-white rounded-2xl border border-slate-100 shadow-sm w-fit">
            <h2 className="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
              <Car className="w-4 h-4 text-blue-600" /> Daftar Trip {activeTab}
            </h2>
          </div>

          <div className="flex-1 overflow-y-auto space-y-8 pr-1">
            {filteredTrips.length > 0 ? filteredTrips.map((trip) => {
              const totalPax = trip.passengers.reduce((sum, b) => sum + b.pax, 0);
              const isFull = totalPax >= 5;

              return (
                <div key={trip.id} className="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden animate-in slide-in-from-bottom-4 duration-500">
                  <div className="p-8 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div className="flex items-center gap-6">
                      <div className="w-16 h-16 bg-blue-950 rounded-[2rem] flex items-center justify-center text-white shadow-2xl shadow-blue-900/20">
                        <Car className="w-8 h-8" />
                      </div>
                      <div>
                        <div className="flex items-center gap-3 mb-2">
                          <span className="text-xl font-black text-slate-900 tracking-tight">{trip.id}</span>
                          <span className={`px-3 py-1 rounded-xl text-[9px] font-black uppercase tracking-widest border ${
                            trip.shift === 'Pagi' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100'
                          }`}>
                            {trip.shift} ({trip.time})
                          </span>
                        </div>
                        <div className="flex flex-wrap items-center gap-4">
                          <button 
                            onClick={() => navigate('/admin/trip/detail')}
                            className="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors"
                          >
                            <User className="w-3.5 h-3.5" /> {trip.driver?.name || "Assign Driver"}
                          </button>
                          <button 
                            onClick={() => navigate('/admin/trip/detail')}
                            className="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors"
                          >
                            <Truck className="w-3.5 h-3.5" /> {trip.vehicle?.plate || "Assign Vehicle"}
                          </button>
                        </div>
                      </div>
                    </div>

                    <div className="flex flex-col items-end gap-2">
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Okupansi Kursi</p>
                       <div className="flex items-center gap-4">
                          <div className="w-32 h-3 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                            <div 
                              className={`h-full transition-all duration-1000 ${isFull ? 'bg-rose-500' : 'bg-blue-600 shadow-[0_0_10px_rgba(37,99,235,0.5)]'}`}
                              style={{ width: `${(totalPax / 5) * 100}%` }}
                            ></div>
                          </div>
                          <span className={`text-lg font-black ${isFull ? 'text-rose-600' : 'text-slate-900'}`}>
                            {totalPax}/5
                          </span>
                       </div>
                    </div>
                  </div>

                  {/* Trip Manifest Preview */}
                  <div className="p-8 bg-white">
                    <div className="flex items-center justify-between mb-6">
                      <h5 className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Manifes Keberangkatan</h5>
                      <span className="text-[10px] font-black text-blue-600 uppercase tracking-widest">Route: {trip.route}</span>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {trip.passengers.length > 0 ? trip.passengers.map((p) => (
                        <div key={p.id} className="flex items-center gap-4 bg-slate-50 p-4 rounded-3xl border border-slate-100 group hover:border-blue-200 transition-all">
                          <div className="w-10 h-10 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-xs font-black text-slate-900 shrink-0 shadow-sm">
                            {p.pax}
                          </div>
                          <div className="flex-1 min-w-0">
                            <p className="text-xs font-black text-slate-900 truncate">{p.name}</p>
                            <p className="text-[9px] font-bold text-slate-400 truncate mt-0.5">{p.pickup}</p>
                          </div>
                          <button 
                            onClick={() => removeFromTrip(p, trip.id)}
                            className="p-2 text-slate-300 hover:text-rose-600 transition-colors opacity-0 group-hover:opacity-100"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      )) : (
                        <div className="col-span-2 py-12 flex flex-col items-center justify-center border-2 border-dashed border-slate-50 rounded-[2.5rem] bg-slate-50/30">
                          <ArrowDownCircle className="w-10 h-10 text-slate-200 mb-3" />
                          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tarik booking dari daftar kiri</p>
                        </div>
                      )}
                    </div>
                  </div>

                  {/* Card Actions Footer */}
                  <div className="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <div className="flex items-center gap-6">
                      {trip.status === "New" && (
                        <button 
                          onClick={() => setIsCancelConfirmOpen(trip.id)}
                          className="flex items-center gap-2 text-[10px] font-black text-rose-500 uppercase tracking-widest hover:text-rose-700 transition-colors"
                        >
                          <XCircleIcon className="w-4 h-4" /> Batalkan Trip
                        </button>
                      )}
                      <button
                         onClick={() => setSelectedTripForManifest(trip)}
                         className="flex items-center gap-2 text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline"
                      >
                         <ClipboardList className="w-4 h-4" /> View Manifest
                      </button>
                      {trip.status === "New" && (
                        <button
                          onClick={() => setEditingTrip(trip)}
                          className="flex items-center gap-2 text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors"
                        >
                          <Pencil className="w-4 h-4" /> Edit Trip
                        </button>
                      )}
                    </div>
                    <div className="flex items-center gap-3">
                      <button
                        onClick={() => setSelectedTripForReady(trip)}
                        disabled={trip.status !== "New" || trip.passengers.length === 0}
                        className={`px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all ${
                          trip.status === "New" && trip.passengers.length > 0 
                            ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20 hover:bg-blue-700' 
                            : 'bg-slate-200 text-slate-400 cursor-not-allowed'
                        }`}
                      >
                        Set Ready
                      </button>
                    </div>
                  </div>
                </div>
              );
            }) : (
              <div className="py-24 flex flex-col items-center justify-center text-center opacity-40 bg-slate-50/50 rounded-[4rem] border-2 border-dashed border-slate-200 p-12">
                <Car className="w-20 h-20 mb-6 text-slate-300" />
                <p className="text-sm font-black text-slate-400 uppercase tracking-[0.3em]">
                   Tidak ada trip {activeTab.toLowerCase()}
                </p>
              </div>
            )}
          </div>
        </div>

      </div>

      {/* Manifest Modal */}
      {selectedTripForManifest && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
              <div className="p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
                 <div>
                    <h3 className="text-2xl font-black text-slate-900 tracking-tight">Trip Manifest {selectedTripForManifest.id}</h3>
                    <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{selectedTripForManifest.route} • {selectedTripForManifest.date}</p>
                 </div>
                 <button onClick={() => setSelectedTripForManifest(null)} className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors">
                    <X className="w-5 h-5" />
                 </button>
              </div>
              
              <div className="flex-1 overflow-y-auto p-8">
                 <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Driver</p>
                       <p className="text-sm font-black text-slate-900">{selectedTripForManifest.driver?.name || "Belum Ditentukan"}</p>
                    </div>
                    <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Armada</p>
                       <p className="text-sm font-black text-slate-900">{selectedTripForManifest.vehicle?.plate || "Belum Ditentukan"}</p>
                    </div>
                    <div className="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                       <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Okupansi</p>
                       <p className="text-sm font-black text-slate-900">{selectedTripForManifest.passengers.reduce((s,p) => s+p.pax, 0)} / 5 PAX</p>
                    </div>
                 </div>

                 <div className="bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm">
                    <table className="w-full text-left">
                       <thead>
                          <tr className="bg-slate-50/50">
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penumpang</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jemput & Tujuan</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Bayar</th>
                             <th className="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Sisa Bayar</th>
                          </tr>
                       </thead>
                       <tbody className="divide-y divide-slate-50">
                          {selectedTripForManifest.passengers.map(p => (
                             <tr key={p.id}>
                                <td className="px-6 py-4">
                                   <p className="text-xs font-black text-slate-900">{p.name}</p>
                                   <p className="text-[9px] font-bold text-slate-400">{p.pax} PAX • {p.phone}</p>
                                </td>
                                <td className="px-6 py-4">
                                   <p className="text-[10px] font-bold text-slate-600 truncate max-w-[200px]">{p.pickup}</p>
                                   <p className="text-[10px] font-bold text-slate-400 truncate max-w-[200px]">{p.destination}</p>
                                </td>
                                <td className="px-6 py-4">
                                   <span className={`px-2 py-1 rounded-lg text-[9px] font-black uppercase border ${
                                      p.paymentStatus === 'Lunas' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100'
                                   }`}>
                                      {p.paymentStatus}
                                   </span>
                                </td>
                                <td className="px-6 py-4 text-right text-xs font-black text-slate-900">
                                   {formatCurrency(p.remainingPayment)}
                                </td>
                             </tr>
                          ))}
                       </tbody>
                    </table>
                 </div>
              </div>

              <div className="p-8 border-t border-slate-100 flex justify-end gap-4 bg-slate-50/30">
                 <button className="px-8 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Print Manifest</button>
                 <button onClick={() => setSelectedTripForManifest(null)} className="px-8 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 transition-all">Tutup</button>
              </div>
           </div>
        </div>
      )}

      {/* Set Ready Confirmation Modal */}
      {selectedTripForReady && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-md rounded-[3rem] shadow-2xl overflow-hidden p-10 text-center">
              <div className="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-500 mx-auto mb-6 shadow-xl shadow-emerald-500/10">
                 <CheckCircle2 className="w-10 h-10" />
              </div>
              <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-2">Set Trip Ready?</h3>
              <p className="text-sm font-bold text-slate-400 mb-8 px-4 leading-relaxed">Pastikan semua data sudah benar sebelum trip muncul di dashboard driver.</p>
              
              <div className="space-y-4 mb-10 text-left">
                 <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div className={`w-5 h-5 rounded-full flex items-center justify-center ${selectedTripForReady.driver ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-transparent'}`}>
                       <CheckCircle2 className="w-3 h-3" />
                    </div>
                    <span className={`text-[11px] font-black uppercase tracking-widest ${selectedTripForReady.driver ? 'text-slate-900' : 'text-slate-400'}`}>Driver Telah Ditugaskan</span>
                 </div>
                 <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div className={`w-5 h-5 rounded-full flex items-center justify-center ${selectedTripForReady.vehicle ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-transparent'}`}>
                       <CheckCircle2 className="w-3 h-3" />
                    </div>
                    <span className={`text-[11px] font-black uppercase tracking-widest ${selectedTripForReady.vehicle ? 'text-slate-900' : 'text-slate-400'}`}>Armada Telah Ditugaskan</span>
                 </div>
                 <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div className={`w-5 h-5 rounded-full flex items-center justify-center ${selectedTripForReady.passengers.length > 0 ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-transparent'}`}>
                       <CheckCircle2 className="w-3 h-3" />
                    </div>
                    <span className={`text-[11px] font-black uppercase tracking-widest ${selectedTripForReady.passengers.length > 0 ? 'text-slate-900' : 'text-slate-400'}`}>Penumpang Telah Terisi</span>
                 </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                 <button onClick={() => setSelectedTripForReady(null)} className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                 <button 
                    onClick={() => confirmSetReady(selectedTripForReady.id)}
                    disabled={!selectedTripForReady.driver || !selectedTripForReady.vehicle || selectedTripForReady.passengers.length === 0}
                    className="py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                 >
                    Confirm Ready
                 </button>
              </div>
           </div>
        </div>
      )}

      {/* Cancel Confirmation Modal */}
      {isCancelConfirmOpen && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
           <div className="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl overflow-hidden p-10 text-center">
              <div className="w-20 h-20 bg-rose-50 rounded-[2rem] flex items-center justify-center text-rose-500 mx-auto mb-6">
                 <XCircleIcon className="w-10 h-10" />
              </div>
              <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-2">Batalkan Trip?</h3>
              <p className="text-sm font-bold text-slate-400 mb-8 px-4 leading-relaxed">Penumpang yang sudah masuk akan dikembalikan ke daftar antrean booking.</p>
              
              <div className="grid grid-cols-2 gap-4">
                 <button onClick={() => setIsCancelConfirmOpen(null)} className="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Tidak</button>
                 <button onClick={() => cancelTripAction(isCancelConfirmOpen)} className="py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:bg-rose-600 transition-all">Ya, Batalkan</button>
              </div>
           </div>
        </div>
      )}

      {/* Create Trip Modal */}
      {isCreateModalOpen && (
        <CreateTripModal
          availableDrivers={availableDriversFor()}
          schedules={allSchedules}
          onClose={() => setIsCreateModalOpen(false)}
          onSubmit={handleCreateTrip}
        />
      )}

      {/* Edit Trip Modal */}
      {editingTrip && (
        <EditTripModal
          trip={editingTrip}
          availableDrivers={availableDriversFor(editingTrip.id)}
          onClose={() => setEditingTrip(null)}
          onSubmit={handleEditTrip}
        />
      )}

      {/* Toast */}
      {toast && (
        <div className="fixed bottom-6 right-6 z-[400] px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 bg-emerald-600 text-white animate-in slide-in-from-bottom-4 fade-in duration-300">
          <CheckCircle2 className="w-5 h-5" />
          <p className="text-xs font-bold">{toast}</p>
        </div>
      )}
    </div>
  );
}

// ============== Create Trip Modal ==============
function CreateTripModal({
  availableDrivers, schedules, onClose, onSubmit,
}: {
  availableDrivers: DriverOption[];
  schedules: ScheduleOption[];
  onClose: () => void;
  onSubmit: (data: { scheduleId: string; driverId: string }) => void;
}) {
  const [scheduleId, setScheduleId] = useState("");
  const [driverId, setDriverId] = useState("");

  const selectedSchedule = schedules.find(s => s.id === scheduleId);
  const selectedDriver = availableDrivers.find(d => d.id === driverId);
  const canSubmit = scheduleId && driverId;

  return (
    <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
      <div className="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div className="p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
          <div>
            <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Trip Baru</p>
            <h3 className="text-2xl font-black text-slate-900 tracking-tight">Buat Trip Baru</h3>
          </div>
          <button onClick={onClose} className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors">
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto p-8 space-y-5">
          <FieldSelect
            label="Jadwal Keberangkatan"
            value={scheduleId}
            onChange={setScheduleId}
            placeholder={schedules.length === 0 ? "Tidak ada jadwal aktif" : "Pilih jadwal aktif"}
            options={schedules.map(s => ({ value: s.id, label: `${s.date} • ${s.shift} • ${s.route}` }))}
            disabled={schedules.length === 0}
            hint={`${schedules.length} jadwal tersedia`}
          />
          <FieldSelect
            label="Driver"
            value={driverId}
            onChange={setDriverId}
            placeholder={availableDrivers.length === 0 ? "Tidak ada driver tersedia" : "Pilih driver tersedia"}
            options={availableDrivers.map(d => ({ value: d.id, label: `${d.name} • ${d.vehicle.plate} (${d.vehicle.model})` }))}
            disabled={availableDrivers.length === 0}
            hint={`${availableDrivers.length} driver tersedia • kendaraan mengikuti driver`}
          />

          {selectedDriver && (
            <div className="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl space-y-1">
              <p className="text-[10px] font-black text-blue-700 uppercase tracking-widest">Kendaraan Otomatis</p>
              <p className="text-sm font-black text-slate-900">{selectedDriver.vehicle.model} · {selectedDriver.vehicle.plate}</p>
              <p className="text-[11px] font-bold text-slate-500">Kapasitas {selectedDriver.vehicle.capacity} penumpang</p>
            </div>
          )}

          {selectedSchedule && (
            <div className="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-[11px] font-bold text-slate-600 space-y-0.5">
              <p>Tanggal: <span className="text-slate-900 font-black">{selectedSchedule.date}</span></p>
              <p>Shift: <span className="text-slate-900 font-black">{selectedSchedule.shift} • {selectedSchedule.departureTime} WIB</span></p>
              <p>Rute: <span className="text-slate-900 font-black">{selectedSchedule.route}</span></p>
            </div>
          )}

          <div className="flex items-start gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl">
            <AlertCircle className="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
            <p className="text-[11px] font-bold text-amber-900 leading-relaxed">
              Trip dibuat dari <span className="font-black">Jadwal</span>. Kendaraan otomatis mengikuti driver.
            </p>
          </div>
        </div>

        <div className="p-8 border-t border-slate-100 flex gap-4 bg-slate-50/30 shrink-0">
          <button onClick={onClose} className="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
            Batal
          </button>
          <button
            disabled={!canSubmit}
            onClick={() => onSubmit({ scheduleId, driverId })}
            className="flex-1 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition-all disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed"
          >
            <span className="inline-flex items-center gap-2 justify-center">
              <Plus className="w-4 h-4" /> Create Trip
            </span>
          </button>
        </div>
      </div>
    </div>
  );
}

// ============== Edit Trip Modal ==============
function EditTripModal({
  trip, availableDrivers, onClose, onSubmit,
}: {
  trip: Trip;
  availableDrivers: DriverOption[];
  onClose: () => void;
  onSubmit: (data: { tripId: string; date: string; shift: "Pagi" | "Malam"; driverId: string }) => void;
}) {
  const [date, setDate] = useState(trip.date);
  const [shift, setShift] = useState<"Pagi" | "Malam">(trip.shift);
  const [driverId, setDriverId] = useState(trip.driver?.id || "");
  const selectedDriver = availableDrivers.find(d => d.id === driverId);

  const canSubmit = date && shift && driverId;

  return (
    <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
      <div className="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div className="p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
          <div>
            <p className="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Edit Trip</p>
            <h3 className="text-2xl font-black text-slate-900 tracking-tight">Ubah Trip {trip.id}</h3>
          </div>
          <button onClick={onClose} className="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors">
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto p-8 space-y-5">
          <FieldDate label="Tanggal" value={date} onChange={setDate} />
          <FieldSelect
            label="Shift"
            value={shift}
            onChange={(v) => setShift(v as "Pagi" | "Malam")}
            options={[{ value: "Pagi", label: "Pagi (10:00 WIB)" }, { value: "Malam", label: "Malam (20:00 WIB)" }]}
          />
          <FieldSelect
            label="Driver"
            value={driverId}
            onChange={setDriverId}
            options={availableDrivers.map(d => ({ value: d.id, label: `${d.name} • ${d.vehicle.plate} (${d.vehicle.model})` }))}
            hint={`${availableDrivers.length} driver • kendaraan mengikuti driver`}
          />
          {selectedDriver && (
            <div className="p-4 bg-blue-50/60 border border-blue-100 rounded-2xl">
              <p className="text-[10px] font-black text-blue-700 uppercase tracking-widest mb-1">Kendaraan</p>
              <p className="text-sm font-black text-slate-900">{selectedDriver.vehicle.model} · {selectedDriver.vehicle.plate}</p>
              <p className="text-[11px] font-bold text-slate-500">Kapasitas {selectedDriver.vehicle.capacity} penumpang</p>
            </div>
          )}
        </div>

        <div className="p-8 border-t border-slate-100 flex gap-4 bg-slate-50/30 shrink-0">
          <button onClick={onClose} className="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
            Batal
          </button>
          <button
            disabled={!canSubmit}
            onClick={() => onSubmit({ tripId: trip.id, date, shift, driverId })}
            className="flex-1 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition-all disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed"
          >
            <span className="inline-flex items-center gap-2 justify-center">
              <CheckCircle2 className="w-4 h-4" /> Simpan Perubahan
            </span>
          </button>
        </div>
      </div>
    </div>
  );
}

// ============== Reusable Form Fields ==============
function FieldDate({ label, value, onChange }: { label: string; value: string; onChange: (v: string) => void }) {
  return (
    <div>
      <label className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 block">{label}</label>
      <input
        type="date"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all"
      />
    </div>
  );
}

function FieldSelect({
  label, value, onChange, options, placeholder = "Pilih...", disabled = false, hint,
}: {
  label: string;
  value: string;
  onChange: (v: string) => void;
  options: { value: string; label: string }[];
  placeholder?: string;
  disabled?: boolean;
  hint?: string;
}) {
  return (
    <div>
      <div className="flex items-center justify-between mb-2">
        <label className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{label}</label>
        {hint && <span className="text-[9px] font-bold text-slate-400">{hint}</span>}
      </div>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        disabled={disabled}
        className="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all appearance-none disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <option value="">{placeholder}</option>
        {options.map(o => (
          <option key={o.value} value={o.value}>{o.label}</option>
        ))}
      </select>
    </div>
  );
}
