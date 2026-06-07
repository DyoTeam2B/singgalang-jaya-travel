import { 
  CheckCircle2, 
  Ticket, 
  User, 
  Clock, 
  MapPin, 
  Car,
  ChevronRight,
  Phone,
  Calendar,
  Users,
  CreditCard,
  ArrowLeft,
  Info
} from "lucide-react";
import { useSearchParams, useNavigate, Link } from "react-router";

export function BookingDetail() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  
  const shift = searchParams.get("shift") === "malam" ? "Malam (20.00 WIB)" : "Pagi (08.00 WIB)";
  const route = searchParams.get("route") || "Padang Panjang → Pekanbaru";

  const handleNext = () => {
    navigate(`/booking/payment?shift=${searchParams.get("shift") || "pagi"}&route=${encodeURIComponent(route)}`);
  };

  const handleBack = () => {
    navigate(-1);
  };

  return (
    <div className="py-12 md:py-20 bg-slate-50 min-h-screen">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Step Indicator */}
        <div className="flex items-center justify-center mb-12 overflow-x-auto pb-4">
          <div className="flex items-center gap-2 sm:gap-4 text-sm font-bold min-w-max">
            <div className="flex items-center gap-2 text-slate-700">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 text-xs">
                <CheckCircle2 className="w-4 h-4 text-slate-700" />
              </div>
              <span>Booking</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-blue-600">
              <div className="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs shadow-sm shadow-blue-600/20">2</div>
              <span>Detail Review</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-slate-400">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">3</div>
              <span>Pembayaran DP</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-slate-400">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs">4</div>
              <span>Verifikasi</span>
            </div>
          </div>
        </div>

        {/* Top Section */}
        <div className="text-center max-w-2xl mx-auto mb-12">
          <h1 className="text-slate-900 font-extrabold text-3xl md:text-4xl tracking-tight mb-3">
            Review Detail Booking
          </h1>
          <p className="text-slate-500 text-lg font-medium">
            Pastikan data perjalanan Anda sudah benar sebelum melanjutkan
          </p>
        </div>

        {/* Two Column Layout */}
        <div className="grid lg:grid-cols-12 gap-8 items-start">
          
          {/* LEFT SIDE - BOOKING DETAIL CARD */}
          <div className="lg:col-span-8 space-y-6">
            <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
              <div className="p-6 md:p-8 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <Ticket className="w-5 h-5" />
                  </div>
                  <div>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Booking ID</p>
                    <span className="text-lg font-extrabold text-slate-900 font-mono tracking-tight">SJT-882910</span>
                  </div>
                </div>
                <div className="bg-blue-50 border border-blue-200/50 px-4 py-2 rounded-full">
                  <span className="text-xs font-extrabold text-blue-700 uppercase tracking-wider">Menunggu Review</span>
                </div>
              </div>

              <div className="p-6 md:p-8 space-y-8">
                
                {/* Rute */}
                <div>
                  <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Rute Perjalanan</h4>
                  <div className="flex items-center gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                      <MapPin className="w-5 h-5 text-blue-600" />
                    </div>
                    <div className="flex items-center gap-3 flex-1 flex-wrap">
                      <span className="text-lg font-bold text-slate-900">{route}</span>
                    </div>
                  </div>
                </div>

                <div className="grid sm:grid-cols-2 gap-x-8 gap-y-6">
                  
                  {/* Pemesan Info */}
                  <div className="space-y-4">
                    <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Pemesan</h4>
                    <div>
                      <div className="flex items-center gap-2 mb-1.5">
                        <User className="w-4 h-4 text-slate-400" />
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Nama Lengkap</p>
                      </div>
                      <p className="text-base font-bold text-slate-900">Budi Santoso</p>
                    </div>
                    <div>
                      <div className="flex items-center gap-2 mb-1.5">
                        <Phone className="w-4 h-4 text-slate-400" />
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Nomor HP</p>
                      </div>
                      <p className="text-base font-bold text-slate-900">081234567890</p>
                    </div>
                  </div>

                  {/* Perjalanan Info */}
                  <div className="space-y-4">
                    <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Detail Keberangkatan</h4>
                    <div>
                      <div className="flex items-center gap-2 mb-1.5">
                        <Calendar className="w-4 h-4 text-slate-400" />
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Tanggal</p>
                      </div>
                      <p className="text-base font-bold text-slate-900">18 Mei 2026</p>
                    </div>
                    <div>
                      <div className="flex items-center gap-2 mb-1.5">
                        <Clock className="w-4 h-4 text-slate-400" />
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Shift</p>
                      </div>
                      <p className="text-base font-bold text-slate-900">{shift}</p>
                    </div>
                  </div>

                  {/* Lokasi Jemput & Tujuan */}
                  <div className="sm:col-span-2 space-y-4 mt-2">
                    <h4 className="text-sm font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Lokasi Penjemputan & Tujuan</h4>
                    <div className="grid sm:grid-cols-2 gap-6">
                      <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                          <MapPin className="w-3.5 h-3.5 text-blue-600" />
                          Lokasi Jemput
                        </p>
                        <p className="text-sm font-semibold text-slate-900">
                          Jl. Sudirman No. 45, Padang Panjang (Depan Indomaret)
                        </p>
                      </div>
                      <div className="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                          <MapPin className="w-3.5 h-3.5 text-indigo-600" />
                          Lokasi Tujuan
                        </p>
                        <p className="text-sm font-semibold text-slate-900">
                          Jl. Riau No. 12, Pekanbaru (Samping Mall Ciputra)
                        </p>
                      </div>
                    </div>
                  </div>

                  {/* Penumpang & Armada */}
                  <div className="sm:col-span-2 grid sm:grid-cols-2 gap-6 mt-2">
                    <div>
                      <div className="flex items-center gap-2 mb-1.5">
                        <Users className="w-4 h-4 text-slate-400" />
                        <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Jumlah Penumpang</p>
                      </div>
                      <p className="text-base font-bold text-slate-900">2 Orang</p>
                    </div>
                    <div>
                      
                      
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          {/* RIGHT SIDE - PAYMENT SUMMARY CARD */}
          <div className="lg:col-span-4 relative">
            <div className="sticky top-28 bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
              <div className="p-6 md:p-8">
                <div className="flex items-center gap-3 mb-6">
                  <div className="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <CreditCard className="w-5 h-5" />
                  </div>
                  <h3 className="text-lg font-extrabold text-slate-900 tracking-tight">Ringkasan Biaya</h3>
                </div>
                
                <div className="space-y-4">
                  <div className="flex justify-between items-center">
                    <p className="text-sm font-bold text-slate-500">Harga Tiket (2x)</p>
                    <p className="text-sm font-bold text-slate-900">Rp 300.000</p>
                  </div>
                  
                  <div className="border-t border-dashed border-slate-200 my-4"></div>
                  
                  <div className="flex items-end justify-between mb-2">
                    <div>
                      <p className="text-sm font-bold text-slate-900 mb-1">Total Harga</p>
                    </div>
                    <div className="text-right">
                      <p className="text-xl font-extrabold text-slate-900 tracking-tight">Rp 300.000</p>
                    </div>
                  </div>

                  <div className="bg-blue-50/50 rounded-2xl p-4 border border-blue-100 flex justify-between items-center">
                    <div>
                      <p className="text-xs font-bold text-blue-700 uppercase tracking-widest mb-0.5">Wajib Transfer</p>
                      <p className="text-sm font-bold text-slate-700">DP (Down Payment)</p>
                    </div>
                    <p className="text-lg font-extrabold text-blue-600">Rp 50.000</p>
                  </div>

                  <div className="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex justify-between items-center">
                    <div>
                      <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">Sisa Pembayaran</p>
                      <p className="text-[11px] font-bold text-slate-400">Dibayar saat perjalanan</p>
                    </div>
                    <p className="text-lg font-extrabold text-slate-900">Rp 250.000</p>
                  </div>

                  {/* Payment Rule Callout */}
                  <div className="bg-slate-900 text-white rounded-2xl p-4 space-y-1.5">
                    <p className="text-[10px] font-extrabold uppercase tracking-widest text-blue-300">Aturan Pembayaran</p>
                    <p className="text-xs font-medium leading-relaxed text-slate-200">
                      <span className="font-bold text-white">Total ≤ Rp 50.000</span> → Bayar Penuh.<br/>
                      <span className="font-bold text-white">Total &gt; Rp 50.000</span> → Bayar DP flat <span className="font-bold text-white">Rp 50.000</span>, sisa dibayar saat perjalanan.
                    </p>
                  </div>

                </div>

                <div className="mt-8 space-y-3">
                  <button 
                    onClick={handleNext}
                    className="w-full bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-xl font-bold text-base transition-all shadow-lg shadow-slate-900/15 flex items-center justify-center gap-2 group"
                  >
                    Lanjut Pembayaran DP
                    <ChevronRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                  </button>
                  <button 
                    onClick={handleBack}
                    className="w-full bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-bold text-base transition-all flex items-center justify-center gap-2"
                  >
                    <ArrowLeft className="w-5 h-5" />
                    Kembali Edit
                  </button>
                </div>

                <div className="mt-5 flex items-start gap-2 text-xs font-medium text-slate-500">
                  <Info className="w-4 h-4 shrink-0 text-slate-400" />
                  <p>Anda belum dikenakan biaya pada tahap ini.</p>
                </div>
              </div>
            </div>
          </div>
          
        </div>

      </div>
    </div>
  );
}