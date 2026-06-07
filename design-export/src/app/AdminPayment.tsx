import { Search, Filter, CheckCircle2, XCircle, CreditCard, Receipt, AlertCircle, CheckCircle, X } from "lucide-react";
import { useState } from "react";

const initialPayments = [
  { 
    id: "BKG-1028", 
    name: "Siti Rahma", 
    bank: "Transfer BCA", 
    accountName: "Siti Rahmawati",
    dp: "Rp 150.000", 
    total: "Rp 300.000",
    paymentType: "DP",
    status: "Menunggu", 
    statusColor: "bg-amber-50 text-amber-700 border-amber-200",
    date: "24 Mei 2026, 14:30 WIB",
    route: "Pekanbaru ↔ Padang Panjang",
    shift: "Malam (20:00)",
    seats: "2 Kursi (A1, A2)",
    img: "https://images.unsplash.com/photo-1698067942087-53f552fe2f59?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxyZWNlaXB0JTIwcGFwZXJ8ZW58MXx8fHwxNzc5MDI4NDUzfDA&ixlib=rb-4.1.0&q=80&w=1080"
  },
  { 
    id: "BKG-1030", 
    name: "Hendra Gunawan", 
    bank: "Transfer Mandiri", 
    accountName: "Hendra G",
    dp: "Rp 150.000", 
    total: "Rp 150.000",
    paymentType: "Full Payment",
    status: "Menunggu", 
    statusColor: "bg-amber-50 text-amber-700 border-amber-200",
    date: "24 Mei 2026, 15:45 WIB",
    route: "Padang Panjang ↔ Pekanbaru",
    shift: "Pagi (10:00)",
    seats: "1 Kursi (B3)",
    img: "https://images.unsplash.com/photo-1634733988138-bf2c3a2a13fa?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2JpbGUlMjBiYW5raW5nJTIwcmVjZWlwdHxlbnwxfHx8fDE3NzkxMTc3ODV8MA&ixlib=rb-4.1.0&q=80&w=1080"
  },
  { 
    id: "BKG-1027", 
    name: "Ahmad Fauzi", 
    bank: "Qris / e-Wallet", 
    accountName: "Ahmad F",
    dp: "Rp 450.000", 
    total: "Rp 450.000",
    paymentType: "Full Payment",
    status: "Terverifikasi", 
    statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200",
    date: "23 Mei 2026, 09:15 WIB",
    route: "Padang Panjang ↔ Pekanbaru",
    shift: "Pagi (10:00)",
    seats: "3 Kursi (C1, C2, C3)",
    img: "https://images.unsplash.com/photo-1698067942087-53f552fe2f59?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxyZWNlaXB0JTIwcGFwZXJ8ZW58MXx8fHwxNzc5MDI4NDUzfDA&ixlib=rb-4.1.0&q=80&w=1080"
  },
  { 
    id: "BKG-1026", 
    name: "Dina Mariana", 
    bank: "Transfer BNI", 
    accountName: "Dina M",
    dp: "Rp 150.000", 
    total: "Rp 150.000",
    paymentType: "Full Payment",
    status: "Ditolak", 
    statusColor: "bg-rose-50 text-rose-700 border-rose-200",
    date: "23 Mei 2026, 11:20 WIB",
    route: "Padang Panjang ↔ Pekanbaru",
    shift: "Malam (20:00)",
    seats: "1 Kursi (A4)",
    img: "https://images.unsplash.com/photo-1634733988138-bf2c3a2a13fa?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2JpbGUlMjBiYW5raW5nJTIwcmVjZWlwdHxlbnwxfHx8fDE3NzkxMTc3ODV8MA&ixlib=rb-4.1.0&q=80&w=1080"
  },
  { 
    id: "BKG-1025", 
    name: "Reza Pratama", 
    bank: "Transfer BCA", 
    accountName: "Reza Pratama",
    dp: "Rp 300.000", 
    total: "Rp 300.000",
    paymentType: "Full Payment",
    status: "Terverifikasi", 
    statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200",
    date: "22 Mei 2026, 16:00 WIB",
    route: "Pekanbaru ↔ Padang Panjang",
    shift: "Pagi (10:00)",
    seats: "2 Kursi (B1, B2)",
    img: "https://images.unsplash.com/photo-1698067942087-53f552fe2f59?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxyZWNlaXB0JTIwcGFwZXJ8ZW58MXx8fHwxNzc5MDI4NDUzfDA&ixlib=rb-4.1.0&q=80&w=1080"
  },
];

export function AdminPayment() {
  const [payments, setPayments] = useState(initialPayments);
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedPaymentId, setSelectedPaymentId] = useState(initialPayments[0].id);
  
  const [isVerifyModalOpen, setIsVerifyModalOpen] = useState(false);
  const [isRejectModalOpen, setIsRejectModalOpen] = useState(false);
  const [rejectionReason, setRejectionReason] = useState("");

  const selectedPayment = payments.find(p => p.id === selectedPaymentId) || payments[0];

  const handleVerify = () => {
    setPayments(prev => prev.map(p => 
      p.id === selectedPaymentId 
        ? { ...p, status: "Terverifikasi", statusColor: "bg-emerald-50 text-emerald-700 border-emerald-200" } 
        : p
    ));
    setIsVerifyModalOpen(false);
  };

  const handleReject = () => {
    setPayments(prev => prev.map(p => 
      p.id === selectedPaymentId 
        ? { ...p, status: "Ditolak", statusColor: "bg-rose-50 text-rose-700 border-rose-200" } 
        : p
    ));
    setIsRejectModalOpen(false);
    setRejectionReason("");
  };

  const filteredPayments = payments.filter(p => 
    p.id.toLowerCase().includes(searchTerm.toLowerCase()) || 
    p.name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  return (
    <>
      <div className="mb-8">
        <h1 className="text-3xl font-black text-slate-900 tracking-tight mb-1">Verifikasi Pembayaran</h1>
        <p className="text-sm font-bold text-slate-500 uppercase tracking-widest">Validasi bukti transfer dan manifes penumpang</p>
      </div>

      <div className="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start relative">
        
        {/* Left Column: Table */}
        <div className="xl:col-span-2 bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden">
          
          {/* Toolbar */}
          <div className="p-6 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div className="relative w-full sm:w-80">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
              <input 
                type="text" 
                placeholder="Cari ID Booking atau Nama..." 
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none transition-all"
              />
            </div>

            <div className="flex items-center gap-3 w-full sm:w-auto">
              <div className="relative w-full sm:w-48">
                <Filter className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                <select 
                  className="w-full pl-11 pr-8 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none appearance-none cursor-pointer"
                >
                  <option>Menunggu Verifikasi</option>
                  <option>Terverifikasi</option>
                  <option>Ditolak</option>
                  <option>Semua Status</option>
                </select>
              </div>
            </div>
          </div>

          {/* Table */}
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="bg-slate-50/50">
                  <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Booking</th>
                  <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama & Bank</th>
                  <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Upload</th>
                  <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                  <th className="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-50">
                {filteredPayments.map((payment) => (
                  <tr 
                    key={payment.id} 
                    onClick={() => setSelectedPaymentId(payment.id)}
                    className={`transition-colors cursor-pointer group ${selectedPaymentId === payment.id ? 'bg-slate-50' : 'hover:bg-slate-50/50'}`}
                  >
                    <td className="px-8 py-6">
                      <span className="text-xs font-black text-slate-900">{payment.id}</span>
                    </td>
                    <td className="px-8 py-6">
                      <div className="text-xs font-black text-slate-900">{payment.name}</div>
                      <div className="text-[10px] font-bold text-slate-400 flex items-center gap-1 mt-0.5">
                        <CreditCard className="w-3 h-3" /> {payment.bank}
                      </div>
                    </td>
                    <td className="px-8 py-6 text-[11px] font-bold text-slate-500">{payment.date}</td>
                    <td className="px-8 py-6 text-xs font-black text-slate-900">{payment.dp}</td>
                    <td className="px-8 py-6">
                      <span className={`inline-flex items-center px-3 py-1 rounded-xl text-[9px] font-black uppercase border tracking-widest ${payment.statusColor}`}>
                        {payment.status === "Menunggu" && <AlertCircle className="w-3 h-3 mr-1" />}
                        {payment.status === "Terverifikasi" && <CheckCircle2 className="w-3 h-3 mr-1" />}
                        {payment.status === "Ditolak" && <XCircle className="w-3 h-3 mr-1" />}
                        {payment.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Right Column: Detail & Verification Preview */}
        <div className="xl:col-span-1 bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col sticky top-8">
          
          <div className="p-8 border-b border-slate-100 flex items-center justify-between">
            <h2 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">Detail & Bukti</h2>
            <span className="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
              {selectedPayment.id}
            </span>
          </div>

          <div className="p-8 space-y-8 overflow-y-auto max-h-[calc(100vh-200px)]">
            
            {/* Image Preview */}
            <div className="space-y-4">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto Bukti Transfer</p>
              <div className="relative w-full aspect-[3/4] bg-slate-50 rounded-[2rem] overflow-hidden border border-slate-100 group shadow-inner">
                <img 
                  src={selectedPayment.img} 
                  alt="Bukti Transfer" 
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div className="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/20 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer backdrop-blur-[2px]">
                  <span className="bg-white text-slate-900 text-[10px] font-black uppercase tracking-widest px-6 py-3 rounded-2xl shadow-2xl">Lihat Penuh</span>
                </div>
              </div>
            </div>

            {/* Booking Details */}
            <div className="space-y-4">
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Pembayaran</p>
              
              <div className="bg-slate-50 rounded-[2rem] p-6 border border-slate-100 space-y-4 shadow-inner">
                <div className="flex justify-between items-center">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Pengirim</span>
                  <span className="text-xs font-black text-slate-900">{selectedPayment.accountName}</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Metode</span>
                  <span className="text-xs font-black text-slate-900">{selectedPayment.bank}</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tipe</span>
                  <span className="text-xs font-black text-blue-600">{selectedPayment.paymentType}</span>
                </div>
                <div className="w-full h-px bg-slate-200 opacity-50"></div>
                <div className="flex justify-between items-start">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Jadwal</span>
                  <div className="text-right">
                    <p className="text-xs font-black text-slate-900 leading-tight">{selectedPayment.route}</p>
                    <p className="text-[10px] font-bold text-slate-400 mt-1">{selectedPayment.shift}</p>
                  </div>
                </div>
                
                <div className="w-full h-px bg-slate-200 opacity-50"></div>
                <div className="flex justify-between items-center">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Tagihan</span>
                  <span className="text-xs font-black text-slate-900">{selectedPayment.total}</span>
                </div>
                <div className="flex justify-between items-center pt-2">
                  <span className="text-[11px] font-black text-slate-900 uppercase tracking-widest">Nominal Dibayar</span>
                  <span className="text-2xl font-black text-blue-600 tracking-tighter">{selectedPayment.dp}</span>
                </div>
              </div>
            </div>

            {/* Action Buttons */}
            {selectedPayment.status === "Menunggu" ? (
              <div className="grid grid-cols-2 gap-4">
                <button 
                  onClick={() => setIsRejectModalOpen(true)}
                  className="flex items-center justify-center gap-2 py-4 bg-white border border-rose-100 text-rose-500 hover:bg-rose-50 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-rose-500/5"
                >
                  <XCircle className="w-4 h-4" /> Tolak
                </button>
                <button 
                  onClick={() => setIsVerifyModalOpen(true)}
                  className="flex items-center justify-center gap-2 py-4 bg-emerald-600 text-white hover:bg-emerald-700 shadow-xl shadow-emerald-600/20 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all"
                >
                  <CheckCircle className="w-4 h-4" /> Verifikasi
                </button>
              </div>
            ) : (
              <div className={`flex items-center justify-center gap-3 py-5 rounded-[2rem] text-[10px] font-black uppercase tracking-widest border border-current opacity-80 ${selectedPayment.statusColor}`}>
                {selectedPayment.status === "Terverifikasi" ? (
                  <><CheckCircle2 className="w-4 h-4" /> Terverifikasi</>
                ) : (
                  <><XCircle className="w-4 h-4" /> Pembayaran Ditolak</>
                )}
              </div>
            )}

          </div>

        </div>

      </div>

      {/* Verify Confirmation Modal */}
      {isVerifyModalOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div className="p-8 border-b border-slate-100 flex items-center justify-between">
              <h3 className="text-xl font-black text-slate-900">Verifikasi Pembayaran</h3>
              <button onClick={() => setIsVerifyModalOpen(false)} className="p-2 hover:bg-slate-50 rounded-xl transition-colors">
                <X className="w-5 h-5 text-slate-400" />
              </button>
            </div>
            <div className="p-8 space-y-6">
              <div className="grid grid-cols-2 gap-4">
                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                  <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Booking ID</p>
                  <p className="text-sm font-black text-slate-900">{selectedPayment.id}</p>
                </div>
                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                  <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                  <p className="text-sm font-black text-slate-900">{selectedPayment.name}</p>
                </div>
                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                  <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tipe Pembayaran</p>
                  <p className="text-sm font-black text-blue-600">{selectedPayment.paymentType}</p>
                </div>
                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                  <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nominal</p>
                  <p className="text-sm font-black text-emerald-600">{selectedPayment.dp}</p>
                </div>
              </div>

              <div className="space-y-3">
                <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pratinjau Bukti</p>
                <div className="w-full h-32 rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                  <img src={selectedPayment.img} alt="Bukti" className="w-full h-full object-cover" />
                </div>
              </div>

              <div className="p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                <p className="text-[11px] font-bold text-blue-700 leading-relaxed">
                  Apakah Anda yakin ingin memverifikasi pembayaran ini? Tindakan ini akan mengubah status booking menjadi <span className="font-black">Siap Masuk Trip</span>.
                </p>
              </div>
            </div>
            <div className="p-8 bg-slate-50/50 flex gap-4">
              <button 
                onClick={() => setIsVerifyModalOpen(false)}
                className="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all"
              >
                Batal
              </button>
              <button 
                onClick={handleVerify}
                className="flex-1 py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20"
              >
                Konfirmasi Verifikasi
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Reject Modal */}
      {isRejectModalOpen && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
            <div className="p-8 border-b border-slate-100 flex items-center justify-between">
              <h3 className="text-xl font-black text-slate-900">Tolak Pembayaran</h3>
              <button onClick={() => setIsRejectModalOpen(false)} className="p-2 hover:bg-slate-50 rounded-xl transition-colors">
                <X className="w-5 h-5 text-slate-400" />
              </button>
            </div>
            <div className="p-8 space-y-6">
              <div className="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div className="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-slate-200">
                  <img src={selectedPayment.img} alt="Bukti" className="w-full h-full object-cover" />
                </div>
                <div>
                  <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest">{selectedPayment.id}</p>
                  <p className="text-sm font-black text-slate-900">{selectedPayment.name}</p>
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alasan Penolakan</label>
                <textarea 
                  value={rejectionReason}
                  onChange={(e) => setRejectionReason(e.target.value)}
                  placeholder="Contoh: Bukti transfer tidak jelas atau nominal tidak sesuai..."
                  className="w-full h-32 p-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-rose-500/10 outline-none transition-all resize-none"
                />
              </div>

              <p className="text-[11px] font-bold text-rose-500 leading-relaxed italic">
                * Pelanggan akan diminta untuk mengunggah ulang bukti pembayaran baru.
              </p>
            </div>
            <div className="p-8 bg-slate-50/50 flex gap-4">
              <button 
                onClick={() => setIsRejectModalOpen(false)}
                className="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all"
              >
                Batal
              </button>
              <button 
                onClick={handleReject}
                disabled={!rejectionReason.trim()}
                className="flex-1 py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-xl shadow-rose-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Konfirmasi Tolak
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
