import { useState } from "react";
import {
  CalendarDays, Plus, Pencil, Power, X, Sun, Moon, Map, Users, Clock, Search,
  CheckCircle2, AlertCircle,
} from "lucide-react";

type ScheduleStatus = "Active" | "Full" | "Inactive";

interface Schedule {
  id: string;
  route: string;
  date: string;
  shift: "Pagi" | "Malam";
  departureTime: string;
  capacity: number;
  booked: number;
  status: ScheduleStatus;
}

const ROUTES = [
  "Padang Panjang → Pekanbaru",
  "Pekanbaru → Padang Panjang",
];

const initialSchedules: Schedule[] = [
  { id: "SCH-001", route: "Padang Panjang → Pekanbaru", date: "2026-06-10", shift: "Pagi", departureTime: "08:00", capacity: 5, booked: 3, status: "Active" },
  { id: "SCH-002", route: "Padang Panjang → Pekanbaru", date: "2026-06-10", shift: "Malam", departureTime: "20:00", capacity: 5, booked: 5, status: "Full" },
  { id: "SCH-003", route: "Pekanbaru → Padang Panjang", date: "2026-06-11", shift: "Pagi", departureTime: "08:00", capacity: 5, booked: 1, status: "Active" },
  { id: "SCH-004", route: "Pekanbaru → Padang Panjang", date: "2026-06-12", shift: "Malam", departureTime: "20:00", capacity: 5, booked: 0, status: "Inactive" },
];

export function AdminJadwal() {
  const [schedules, setSchedules] = useState<Schedule[]>(initialSchedules);
  const [search, setSearch] = useState("");
  const [isCreateOpen, setIsCreateOpen] = useState(false);
  const [editing, setEditing] = useState<Schedule | null>(null);
  const [toast, setToast] = useState<string | null>(null);

  const filtered = schedules.filter(s =>
    s.id.toLowerCase().includes(search.toLowerCase()) ||
    s.route.toLowerCase().includes(search.toLowerCase()) ||
    s.date.includes(search)
  );

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(null), 2400);
  };

  const handleCreate = (data: Omit<Schedule, "id" | "booked" | "status">) => {
    const id = `SCH-${String(schedules.length + 1).padStart(3, "0")}`;
    setSchedules([{ ...data, id, booked: 0, status: "Active" }, ...schedules]);
    setIsCreateOpen(false);
    showToast("Jadwal berhasil dibuat");
  };

  const handleEdit = (data: Schedule) => {
    setSchedules(schedules.map(s => s.id === data.id ? { ...data, status: s.booked >= data.capacity ? "Full" : s.status === "Inactive" ? "Inactive" : "Active" } : s));
    setEditing(null);
    showToast("Jadwal berhasil diperbarui");
  };

  const toggleStatus = (id: string) => {
    setSchedules(schedules.map(s => s.id === id ? { ...s, status: s.status === "Inactive" ? (s.booked >= s.capacity ? "Full" : "Active") : "Inactive" } : s));
    showToast("Status jadwal diubah");
  };

  return (
    <div className="font-poppins space-y-8">
      {/* Header */}
      <div className="flex flex-wrap items-center justify-between gap-4">
        <div>
          <p className="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Manajemen Jadwal</p>
          <h1 className="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Jadwal Keberangkatan</h1>
          <p className="text-sm font-bold text-slate-400 mt-1">Atur rute, shift, dan kapasitas keberangkatan harian.</p>
        </div>
        <button
          onClick={() => setIsCreateOpen(true)}
          className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 flex items-center gap-2"
        >
          <Plus className="w-4 h-4" /> Jadwal Baru
        </button>
      </div>

      {/* Search */}
      <div className="bg-white rounded-3xl border border-slate-100 p-5 shadow-sm">
        <div className="flex items-center gap-3 bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
          <Search className="w-4 h-4 text-slate-400" />
          <input
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Cari ID, rute, atau tanggal..."
            className="bg-transparent outline-none flex-1 text-sm font-bold text-slate-900 placeholder:text-slate-400"
          />
        </div>
      </div>

      {/* Schedule Grid */}
      <div className="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
        {filtered.map(s => (
          <div key={s.id} className="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div className="p-6 border-b border-slate-100 flex items-start justify-between gap-3">
              <div>
                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">{s.id}</p>
                <h3 className="text-base font-black text-slate-900 tracking-tight">{s.route}</h3>
              </div>
              <StatusBadge status={s.status} />
            </div>
            <div className="p-6 space-y-3 flex-1">
              <Row icon={<CalendarDays className="w-4 h-4" />} label="Tanggal" value={s.date} />
              <Row icon={s.shift === "Pagi" ? <Sun className="w-4 h-4 text-amber-500" /> : <Moon className="w-4 h-4 text-slate-700" />} label="Shift" value={s.shift} />
              <Row icon={<Clock className="w-4 h-4" />} label="Berangkat" value={s.departureTime} />
              <Row icon={<Users className="w-4 h-4" />} label="Kapasitas" value={`${s.booked}/${s.capacity} kursi`} />
            </div>
            <div className="p-4 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
              <button onClick={() => setEditing(s)} className="flex-1 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5">
                <Pencil className="w-3.5 h-3.5" /> Edit
              </button>
              <button onClick={() => toggleStatus(s.id)} className={`flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 ${s.status === "Inactive" ? "bg-emerald-600 hover:bg-emerald-700 text-white" : "bg-rose-50 hover:bg-rose-100 text-rose-600"}`}>
                <Power className="w-3.5 h-3.5" /> {s.status === "Inactive" ? "Aktifkan" : "Nonaktif"}
              </button>
            </div>
          </div>
        ))}
        {filtered.length === 0 && (
          <div className="col-span-full bg-white rounded-[2rem] border border-dashed border-slate-200 p-12 text-center">
            <AlertCircle className="w-10 h-10 text-slate-300 mx-auto mb-3" />
            <p className="text-sm font-bold text-slate-500">Tidak ada jadwal ditemukan.</p>
          </div>
        )}
      </div>

      {isCreateOpen && (
        <ScheduleModal title="Buat Jadwal Baru" onClose={() => setIsCreateOpen(false)} onSubmit={(d) => handleCreate(d)} />
      )}
      {editing && (
        <ScheduleModal title="Edit Jadwal" initial={editing} onClose={() => setEditing(null)} onSubmit={(d) => handleEdit({ ...editing, ...d })} />
      )}

      {toast && (
        <div className="fixed bottom-6 right-6 z-[400] bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-2 animate-in slide-in-from-bottom-4">
          <CheckCircle2 className="w-4 h-4 text-emerald-400" />
          <span className="text-xs font-bold">{toast}</span>
        </div>
      )}
    </div>
  );
}

function StatusBadge({ status }: { status: ScheduleStatus }) {
  const map = {
    Active: "bg-emerald-50 text-emerald-700 border-emerald-200",
    Full: "bg-amber-50 text-amber-700 border-amber-200",
    Inactive: "bg-slate-100 text-slate-500 border-slate-200",
  };
  return <span className={`text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full border ${map[status]}`}>{status}</span>;
}

function Row({ icon, label, value }: { icon: React.ReactNode; label: string; value: string }) {
  return (
    <div className="flex items-center justify-between">
      <div className="flex items-center gap-2 text-slate-500">
        {icon}
        <span className="text-[11px] font-bold uppercase tracking-widest">{label}</span>
      </div>
      <span className="text-sm font-black text-slate-900">{value}</span>
    </div>
  );
}

function ScheduleModal({
  title, initial, onClose, onSubmit,
}: {
  title: string;
  initial?: Schedule;
  onClose: () => void;
  onSubmit: (data: { route: string; date: string; shift: "Pagi" | "Malam"; departureTime: string; capacity: number }) => void;
}) {
  const [route, setRoute] = useState(initial?.route ?? ROUTES[0]);
  const [date, setDate] = useState(initial?.date ?? "");
  const [shift, setShift] = useState<"Pagi" | "Malam">(initial?.shift ?? "Pagi");
  const [departureTime, setDepartureTime] = useState(initial?.departureTime ?? "08:00");
  const [capacity, setCapacity] = useState(initial?.capacity ?? 5);

  const canSubmit = route && date && shift && departureTime && capacity > 0;

  return (
    <div className="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
      <div className="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
        <div className="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center text-white">
              <CalendarDays className="w-5 h-5" />
            </div>
            <h3 className="text-lg font-black text-slate-900 tracking-tight">{title}</h3>
          </div>
          <button onClick={onClose} className="p-2 rounded-xl hover:bg-slate-100 text-slate-400">
            <X className="w-5 h-5" />
          </button>
        </div>
        <div className="p-8 space-y-5">
          <Field label="Rute">
            <select value={route} onChange={(e) => setRoute(e.target.value)} className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600">
              {ROUTES.map(r => <option key={r} value={r}>{r}</option>)}
            </select>
          </Field>
          <Field label="Tanggal Keberangkatan">
            <input type="date" value={date} onChange={(e) => setDate(e.target.value)} className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600" />
          </Field>
          <div className="grid grid-cols-2 gap-4">
            <Field label="Shift">
              <div className="grid grid-cols-2 gap-2">
                {(["Pagi", "Malam"] as const).map(opt => (
                  <button key={opt} type="button" onClick={() => setShift(opt)} className={`py-3 rounded-xl text-xs font-black uppercase tracking-widest border ${shift === opt ? "bg-slate-900 text-white border-slate-900" : "bg-white border-slate-200 text-slate-500"}`}>
                    {opt}
                  </button>
                ))}
              </div>
            </Field>
            <Field label="Jam Berangkat">
              <input type="time" value={departureTime} onChange={(e) => setDepartureTime(e.target.value)} className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600" />
            </Field>
          </div>
          <Field label="Kapasitas (Penumpang)">
            <input type="number" min={1} max={20} value={capacity} onChange={(e) => setCapacity(Number(e.target.value))} className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600" />
          </Field>
        </div>
        <div className="px-8 py-5 bg-slate-50 border-t border-slate-100 grid grid-cols-2 gap-3">
          <button onClick={onClose} className="py-3.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100">Batal</button>
          <button
            disabled={!canSubmit}
            onClick={() => onSubmit({ route, date, shift, departureTime, capacity })}
            className="py-3.5 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 hover:bg-blue-700 disabled:bg-slate-300 disabled:shadow-none disabled:cursor-not-allowed"
          >
            Simpan Jadwal
          </button>
        </div>
      </div>
    </div>
  );
}

function Field({ label, children }: { label: string; children: React.ReactNode }) {
  return (
    <div className="space-y-2">
      <label className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{label}</label>
      {children}
    </div>
  );
}
