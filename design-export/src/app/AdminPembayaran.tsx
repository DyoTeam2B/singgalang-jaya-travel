import { 
  Search, Filter, CheckCircle2, XCircle, Eye, 
  CreditCard, UserCircle, Phone, Calendar, 
  ExternalLink, Check, X, ImageIcon, Download,
  Wallet
} from "lucide-react";
import { useState } from "react";
import { ImageWithFallback } from "./components/figma/ImageWithFallback";

// --- Types & Mock Data ---
type PaymentStatus = "Pending" | "Verified" | "Rejected";
type PaymentType = "DP (Flat Rp50.000)" | "Full Payment";

interface Payment {
  id: string;
  bookingId: string;
  customerName: string;
  customerPhone: string;
  amount: number;
  type: PaymentType;
  date: string;
  time: string;
  proofUrl: string; // Mock URL
  status: PaymentStatus;
  bankName: string;
}

const mockPayments: Payment[] = [
  {
    id: "PAY-2605-001",
    bookingId: "BKG-2605-001",
    customerName: "Budi Santoso",
    customerPhone: "0812-3456-7890",
    amount: 50000,
    type: "DP (Flat Rp50.000)",
    date: "25 Mei 2026",
    time: "14:20 WIB",
    proofUrl: "https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=500&auto=format&fit=crop",
    status: "Pending",
    bankName: "Bank Central Asia (BCA)"
  },
  {
    id: "PAY-2605-002",
    bookingId: "BKG-2605-002",
    customerName: "Siti Rahma",
    customerPhone: "0813-5678-9012",
    amount: 150000,
    type: "Full Payment",
    date: "25 Mei 2026",
    time: "15:45 WIB",
    proofUrl: "https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=500&auto=format&fit=crop",
    status: "Verified",
    bankName: "Bank Mandiri"
  },
  {
    id: "PAY-2605-003",
    bookingId: "BKG-2605-003",
    customerName: "Ahmad Fauzi",
    customerPhone: "0852-1122-3344",
    amount: 50000,
    type: "DP (Flat Rp50.000)",
    date: "24 Mei 2026",
    time: "09:10 WIB",
    proofUrl: "https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=500&auto=format&fit=crop",
    status: "Rejected",
    bankName: "BNI"
  },
  {
    id: "PAY-2605-004",
    bookingId: "BKG-2605-004",
    customerName: "Dina Mariana",
    customerPhone: "0811-9988-7766",
    amount: 220000,
    type: "Full Payment",
    date: "25 Mei 2026",
    time: "18:00 WIB",
    proofUrl: "https://images.unsplash.com/photo-1554224155-1696413565d3?q=80&w=500&auto=format&fit=crop",
    status: "Pending",
    bankName: "Bank Central Asia (BCA)"
  }
];

export function AdminPembayaran() {
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState("Semua Status");
  const [selectedPaymentId, setSelectedPaymentId] = useState<string | null>(null);
  const [isPreviewOpen, setIsPreviewOpen] = useState(false);
  const [isVerifyModalOpen, setIsVerifyModalOpen] = useState(false);
  const [isRejectModalOpen, setIsRejectModalOpen] = useState(false);
  const [rejectReason, setRejectReason] = useState("");
  const [toast, setToast] = useState<{ type: 'success' | 'error'; msg: string } | null>(null);

  const showToast = (type: 'success' | 'error', msg: string) => {
    setToast({ type, msg });
    setTimeout(() => setToast(null), 3000);
  };

  const filteredPayments = mockPayments.filter(p => {
    const matchesSearch = p.bookingId.toLowerCase().includes(searchTerm.toLowerCase()) || 
                          p.customerName.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = statusFilter === "Semua Status" || p.status === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const selectedPayment = mockPayments.find(p => p.id === selectedPaymentId);

  return (
    <div className="pb-8 flex flex-col h-full font-poppins">
      {/* Header */}
      <div className="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">Verifikasi Pembayaran</h1>
          <p className="text-sm font-medium text-slate-500">Konfirmasi bukti transfer DP dan Pelunasan dari pelanggan.</p>
        </div>
        <div className="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-xl border border-blue-100">
          <Wallet className="w-5 h-5 text-blue-600" />
          <div>
            <p className="text-[10px] font-bold text-blue-500 uppercase leading-none mb-0.5">Menunggu Verifikasi</p>
            <p className="text-sm font-bold text-blue-900 leading-none">
              {mockPayments.filter(p => p.status === 'Pending').length} Pembayaran
            </p>
          </div>
        </div>
      </div>

      {/* Toolbar */}
      <div className="mb-6 flex flex-col xl:flex-row xl:items-center gap-4">
        <div className="relative w-full xl:w-96 shrink-0">
          <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
          <input 
            type="text" 
            placeholder="Cari ID Booking atau Nama..." 
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all shadow-sm"
          />
        </div>

        <div className="flex flex-wrap items-center gap-3 w-full">
          <div className="relative flex-1 min-w-[150px]">
            <Filter className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
            <select 
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="w-full pl-10 pr-8 py-2.5 bg-white border border-slate-200 shadow-sm rounded-xl text-sm font-medium text-slate-700 appearance-none focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 cursor-pointer"
            >
              <option>Semua Status</option>
              <option value="Pending">Pending</option>
              <option value="Verified">Verified</option>
              <option value="Rejected">Rejected</option>
            </select>
          </div>
        </div>
      </div>

      {/* Main Grid */}
      <div className="flex flex-col xl:flex-row gap-6 items-start h-[calc(100vh-220px)]">
        
        {/* Table Section */}
        <div className="flex-1 w-full h-full bg-white rounded-[1.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col overflow-hidden">
          <div className="overflow-auto flex-1">
            <table className="w-full text-left border-collapse min-w-[900px]">
              <thead className="sticky top-0 z-10">
                <tr className="bg-slate-50/80 backdrop-blur-md border-b border-slate-200">
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">ID Booking</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pelanggan</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Tipe</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Nominal</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Status Bukti</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Status Verifikasi</th>
                  <th className="px-5 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {filteredPayments.map((p) => {
                  const isSelected = selectedPaymentId === p.id;
                  return (
                    <tr 
                      key={p.id} 
                      onClick={() => setSelectedPaymentId(p.id)}
                      className={`transition-colors cursor-pointer ${isSelected ? 'bg-blue-50/30' : 'hover:bg-slate-50/80'}`}
                    >
                      <td className="px-5 py-4 text-sm font-bold text-slate-900 whitespace-nowrap relative">
                        {isSelected && (
                          <div className="absolute left-0 top-0 bottom-0 w-1 bg-blue-900 rounded-r-full"></div>
                        )}
                        {p.bookingId}
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap">
                        <p className="text-sm font-semibold text-slate-800">{p.customerName}</p>
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap">
                        <span className={`text-xs font-bold ${p.type.includes('DP') ? 'text-blue-600' : 'text-emerald-600'}`}>
                          {p.type}
                        </span>
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap text-sm font-extrabold text-slate-900">
                        Rp {p.amount.toLocaleString('id-ID')}
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap">
                        <div className="flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 w-fit">
                          <ImageIcon className="w-3.5 h-3.5" />
                          Sudah Unggah
                        </div>
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap">
                        <span className={`inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase tracking-wider
                          ${p.status === 'Verified' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 
                            p.status === 'Rejected' ? 'bg-rose-50 text-rose-700 border-rose-200' : 
                            'bg-amber-50 text-amber-700 border-amber-200'}
                        `}>
                          {p.status}
                        </span>
                      </td>
                      <td className="px-5 py-4 whitespace-nowrap text-right">
                        <button className="p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                          <Eye className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>

        {/* Detail Panel */}
        {selectedPayment ? (
          <div className="w-full xl:w-[400px] h-full shrink-0 bg-white rounded-[1.5rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.06)] flex flex-col overflow-hidden animate-in slide-in-from-right-8 fade-in duration-300">
            <div className="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
              <div>
                <p className="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Rincian Pembayaran</p>
                <h3 className="text-base font-extrabold text-slate-900">{selectedPayment.bookingId}</h3>
              </div>
              <button 
                onClick={() => setSelectedPaymentId(null)}
                className="w-7 h-7 flex items-center justify-center rounded-full bg-slate-200/50 text-slate-500 hover:bg-slate-200 transition-colors"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-5 space-y-6">
              {/* Customer Info */}
              <div className="flex items-center gap-3">
                <div className="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 shrink-0 border border-blue-100">
                  <UserCircle className="w-7 h-7" />
                </div>
                <div>
                  <p className="text-sm font-bold text-slate-900">{selectedPayment.customerName}</p>
                  <p className="text-xs font-medium text-slate-500 flex items-center gap-1 mt-0.5">
                    <Phone className="w-3 h-3" /> {selectedPayment.customerPhone}
                  </p>
                </div>
              </div>

              {/* Transaction Details */}
              <div className="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-4">
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-[10px] font-bold text-slate-400 uppercase mb-1">Tipe Pembayaran</p>
                    <p className="text-xs font-bold text-slate-900">{selectedPayment.type}</p>
                  </div>
                  <div>
                    <p className="text-[10px] font-bold text-slate-400 uppercase mb-1">Bank Pengirim</p>
                    <p className="text-xs font-bold text-slate-900">{selectedPayment.bankName}</p>
                  </div>
                </div>
                <div className="pt-3 border-t border-slate-200/60">
                  <p className="text-[10px] font-bold text-slate-400 uppercase mb-1">Nominal Transfer</p>
                  <p className="text-xl font-extrabold text-blue-900">Rp {selectedPayment.amount.toLocaleString('id-ID')}</p>
                </div>
                <div className="flex items-center gap-3 pt-1">
                  <div className="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">
                    <Calendar className="w-3 h-3" /> {selectedPayment.date}
                  </div>
                  <div className="text-[10px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">
                    {selectedPayment.time}
                  </div>
                </div>
              </div>

              {/* Proof Image Preview */}
              <div>
                <div className="flex items-center justify-between mb-3">
                  <h4 className="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bukti Transfer</h4>
                  <button 
                    onClick={() => setIsPreviewOpen(true)}
                    className="text-[10px] font-bold text-blue-600 hover:underline flex items-center gap-1"
                  >
                    Perbesar <ExternalLink className="w-3 h-3" />
                  </button>
                </div>
                <div 
                  className="relative group cursor-pointer aspect-[4/3] rounded-2xl overflow-hidden border border-slate-200 shadow-sm"
                  onClick={() => setIsPreviewOpen(true)}
                >
                  <ImageWithFallback 
                    src={selectedPayment.proofUrl}
                    alt="Bukti Transfer"
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                    <Eye className="text-white opacity-0 group-hover:opacity-100 transition-opacity w-8 h-8" />
                  </div>
                </div>
              </div>
            </div>

            {/* Verification Buttons */}
            {selectedPayment.status === 'Pending' ? (
              <div className="p-4 border-t border-slate-100 bg-slate-50 grid grid-cols-2 gap-3 shrink-0">
                <button
                  onClick={() => setIsRejectModalOpen(true)}
                  className="flex items-center justify-center gap-2 py-3 bg-white border border-rose-200 text-rose-600 rounded-xl text-xs font-bold hover:bg-rose-50 transition-all shadow-sm"
                >
                  <XCircle className="w-4 h-4" /> Tolak Bukti
                </button>
                <button
                  onClick={() => setIsVerifyModalOpen(true)}
                  className="flex items-center justify-center gap-2 py-3 bg-blue-900 text-white rounded-xl text-xs font-bold hover:bg-blue-950 transition-all shadow-md shadow-blue-900/10"
                >
                  <CheckCircle2 className="w-4 h-4" /> Verifikasi
                </button>
              </div>
            ) : (
              <div className="p-4 border-t border-slate-100 bg-slate-50 shrink-0">
                <div className={`flex items-center justify-center gap-2 py-3 rounded-xl text-xs font-bold border ${
                  selectedPayment.status === 'Verified' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100'
                }`}>
                  {selectedPayment.status === 'Verified' ? <Check className="w-4 h-4" /> : <X className="w-4 h-4" />}
                  Sudah {selectedPayment.status === 'Verified' ? 'Diverifikasi' : 'Ditolak'}
                </div>
              </div>
            )}
          </div>
        ) : (
          <div className="w-full xl:w-[400px] h-full shrink-0 bg-slate-50/50 rounded-[1.5rem] border-2 border-slate-200 border-dashed flex flex-col items-center justify-center text-center p-8">
            <div className="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-200 mb-4 shadow-sm">
              <CreditCard className="w-8 h-8" />
            </div>
            <p className="text-sm font-bold text-slate-400">Pilih pembayaran untuk verifikasi</p>
          </div>
        )}
      </div>

      {/* Lightbox Preview Modal */}
      {isPreviewOpen && selectedPayment && (
        <div className="fixed inset-0 z-[100] bg-slate-900/95 backdrop-blur-sm flex items-center justify-center p-4 sm:p-8 animate-in fade-in duration-300">
          <button 
            onClick={() => setIsPreviewOpen(false)}
            className="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors"
          >
            <X className="w-6 h-6" />
          </button>
          
          <div className="max-w-4xl w-full h-full flex flex-col items-center justify-center">
            <div className="w-full bg-white rounded-t-2xl p-4 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                  <ImageIcon className="w-5 h-5" />
                </div>
                <div>
                  <p className="text-xs font-bold text-slate-500 uppercase leading-none mb-1">Bukti Transfer - {selectedPayment.bookingId}</p>
                  <p className="text-sm font-extrabold text-slate-900 leading-none">{selectedPayment.customerName}</p>
                </div>
              </div>
              <button className="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">
                <Download className="w-4 h-4" /> Simpan Gambar
              </button>
            </div>
            <div className="w-full h-[70vh] bg-slate-100 rounded-b-2xl overflow-hidden flex items-center justify-center">
              <ImageWithFallback 
                src={selectedPayment.proofUrl}
                alt="Full Bukti Transfer"
                className="max-w-full max-h-full object-contain"
              />
            </div>
            <p className="mt-4 text-white/60 text-xs font-medium">Klik di luar gambar atau tekan tombol X untuk menutup.</p>
          </div>

          <div className="absolute inset-0 -z-10" onClick={() => setIsPreviewOpen(false)}></div>
        </div>
      )}

      {/* Verify Confirmation Modal */}
      {isVerifyModalOpen && selectedPayment && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
            <div className="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-5">
              <CheckCircle2 className="w-8 h-8" />
            </div>
            <h3 className="text-xl font-extrabold text-slate-900 text-center mb-2">Verifikasi Pembayaran?</h3>
            <p className="text-sm font-medium text-slate-500 text-center mb-6 leading-relaxed">
              Pembayaran <span className="font-bold text-slate-900">{selectedPayment.bookingId}</span> sebesar
              <span className="font-bold text-slate-900"> Rp {selectedPayment.amount.toLocaleString('id-ID')}</span> akan ditandai sebagai <span className="font-bold text-emerald-600">Verified</span> dan booking siap untuk masuk Trip.
            </p>
            <div className="grid grid-cols-2 gap-3">
              <button
                onClick={() => setIsVerifyModalOpen(false)}
                className="py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-all"
              >
                Batal
              </button>
              <button
                onClick={() => {
                  setIsVerifyModalOpen(false);
                  showToast('success', 'Pembayaran berhasil diverifikasi. Booking siap masuk Trip.');
                }}
                className="py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20"
              >
                Ya, Verifikasi
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Reject Reason Modal */}
      {isRejectModalOpen && selectedPayment && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-200">
          <div className="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
            <div className="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mx-auto mb-5">
              <XCircle className="w-8 h-8" />
            </div>
            <h3 className="text-xl font-extrabold text-slate-900 text-center mb-2">Tolak Bukti Pembayaran</h3>
            <p className="text-sm font-medium text-slate-500 text-center mb-5">
              Tuliskan alasan penolakan agar pelanggan dapat mengunggah ulang bukti yang valid.
            </p>
            <textarea
              value={rejectReason}
              onChange={(e) => setRejectReason(e.target.value)}
              placeholder="Misal: Nominal transfer tidak sesuai, bukti tidak jelas, dll."
              rows={4}
              className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/30 focus:border-rose-400 transition-all resize-none mb-5"
            />
            <div className="grid grid-cols-2 gap-3">
              <button
                onClick={() => { setIsRejectModalOpen(false); setRejectReason(""); }}
                className="py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-all"
              >
                Batal
              </button>
              <button
                disabled={!rejectReason.trim()}
                onClick={() => {
                  setIsRejectModalOpen(false);
                  setRejectReason("");
                  showToast('error', 'Bukti pembayaran ditolak. Pelanggan akan diminta unggah ulang.');
                }}
                className="py-3 bg-rose-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20 disabled:bg-rose-300 disabled:cursor-not-allowed disabled:shadow-none"
              >
                Kirim Penolakan
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Toast */}
      {toast && (
        <div className={`fixed bottom-6 right-6 z-[400] px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-in slide-in-from-bottom-4 fade-in duration-300 ${
          toast.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'
        }`}>
          {toast.type === 'success' ? <CheckCircle2 className="w-5 h-5" /> : <XCircle className="w-5 h-5" />}
          <p className="text-xs font-bold">{toast.msg}</p>
        </div>
      )}
    </div>
  );
}