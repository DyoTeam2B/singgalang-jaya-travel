import { 
  CheckCircle2, 
  Ticket, 
  User, 
  Clock, 
  MapPin, 
  Copy, 
  Building2, 
  Car,
  UploadCloud,
  ChevronRight,
  ShieldCheck,
  Loader2,
  FileCheck
} from "lucide-react";
import { useSearchParams, useNavigate } from "react-router";
import { useState, useRef } from "react";
import { toast } from "sonner";

export function BookingPayment() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const fileInputRef = useRef<HTMLInputElement>(null);
  
  const [isUploading, setIsUploading] = useState(false);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);

  const shift = searchParams.get("shift") === "malam" ? "Malam (20.00 WIB)" : "Pagi (08.00 WIB)";
  const route = searchParams.get("route") || "Padang Panjang → Pekanbaru";
  const numPassengers = parseInt(searchParams.get("passengers") || "1");
  const pricePerSeat = 150000;
  const totalFare = numPassengers * pricePerSeat;
  const dpThreshold = 50000;
  
  const isDpPayment = totalFare > dpThreshold;
  const amountToPay = isDpPayment ? 50000 : totalFare;
  const remainingBalance = totalFare - amountToPay;

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      setSelectedFile(e.target.files[0]);
      toast.success("File berhasil dipilih");
    }
  };

  const handleUploadClick = () => {
    fileInputRef.current?.click();
  };

  const handleSubmit = () => {
    if (!selectedFile) {
      toast.error("Silakan pilih bukti transfer terlebih dahulu");
      return;
    }

    setIsUploading(true);
    
    // Simulate upload process
    setTimeout(() => {
      setIsUploading(false);
      toast.success("Bukti pembayaran berhasil dikirim.");
      
      // Prepare data for redirect
      const params = new URLSearchParams();
      params.set("status", "pending");
      params.set("id", "SJT-882910");
      params.set("name", "Budi Santoso");
      params.set("route", route);
      params.set("pickup", "Jl. Sudirman No. 123, Padang Panjang"); // Mock data
      params.set("destination", "Terminal AKAP, Pekanbaru"); // Mock data
      params.set("date", "18 Mei 2026");
      params.set("shift", shift);
      params.set("passengers", numPassengers.toString());
      params.set("paymentType", isDpPayment ? "DP" : "Full Payment");
      
      // Redirect to Booking Status page after a short delay
      setTimeout(() => {
        navigate(`/cek-status?${params.toString()}`);
      }, 1500);
    }, 2000);
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
            <div className="flex items-center gap-2 text-slate-700">
              <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 text-xs">
                <CheckCircle2 className="w-4 h-4 text-slate-700" />
              </div>
              <span>Detail Review</span>
            </div>
            <ChevronRight className="w-4 h-4 text-slate-300" />
            <div className="flex items-center gap-2 text-blue-600">
              <div className="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs shadow-sm shadow-blue-600/20">3</div>
              <span>{isDpPayment ? 'Pembayaran DP' : 'Pembayaran Penuh'}</span>
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
            {isDpPayment ? 'Pembayaran DP' : 'Pembayaran Penuh'}
          </h1>
          <p className="text-slate-500 text-lg font-medium">
            {isDpPayment 
              ? 'Silakan lakukan pembayaran DP untuk mengamankan kursi Anda.' 
              : 'Silakan lakukan pembayaran penuh untuk melanjutkan booking.'
            }
          </p>
        </div>

        {/* Two Column Layout */}
        <div className="grid lg:grid-cols-12 gap-8 items-start">
          
          {/* LEFT SIDE - BOOKING DETAIL CARD */}
          <div className="lg:col-span-7 bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div className="p-6 md:p-8 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
              <div>
                <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Booking ID</p>
                <div className="flex items-center gap-2">
                  <Ticket className="w-5 h-5 text-blue-600" />
                  <span className="text-xl font-extrabold text-slate-900 font-mono tracking-tight">SJT-882910</span>
                </div>
              </div>
              <div className="bg-amber-50 border border-amber-200/50 px-4 py-2 rounded-full flex items-center gap-2">
                <span className="relative flex h-2 w-2">
                  <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                  <span className="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                <span className="text-xs font-extrabold text-amber-700 uppercase tracking-wider">
                  {isDpPayment ? 'Menunggu DP' : 'Menunggu Pembayaran'}
                </span>
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

              {/* Grid Info */}
              <div className="grid sm:grid-cols-2 gap-6">
                <div>
                  <div className="flex items-center gap-2 mb-1.5">
                    <User className="w-4 h-4 text-slate-400" />
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Nama Pelanggan</p>
                  </div>
                  <p className="text-base font-bold text-slate-900">Budi Santoso</p>
                </div>

                <div>
                  <div className="flex items-center gap-2 mb-1.5">
                    <Clock className="w-4 h-4 text-slate-400" />
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Keberangkatan</p>
                  </div>
                  <p className="text-base font-bold text-slate-900">18 Mei 2026</p>
                  <p className="text-sm font-semibold text-blue-600 mt-0.5">Shift {shift}</p>
                </div>

                <div>
                  <div className="flex items-center gap-2 mb-1.5">
                    <User className="w-4 h-4 text-slate-400" />
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Jumlah Penumpang</p>
                  </div>
                  <p className="text-base font-bold text-slate-900">{numPassengers} Orang</p>
                </div>

                <div>
                  <div className="flex items-center gap-2 mb-1.5">
                    <Car className="w-4 h-4 text-slate-400" />
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-widest">Armada</p>
                  </div>
                  <p className="text-base font-bold text-slate-900">Toyota Avanza</p>
                </div>
              </div>
              
              <div className="border-t border-dashed border-slate-200"></div>

              {/* Total Summary */}
              <div className="space-y-3">
                <div className="flex items-center justify-between text-slate-500 font-medium">
                  <p className="text-sm">Total Biaya ({numPassengers} Kursi)</p>
                  <p className="text-sm">Rp {totalFare.toLocaleString('id-ID')}</p>
                </div>
                
                <div className="flex items-center justify-between text-blue-600 font-bold">
                  <p className="text-sm">Jenis Pembayaran</p>
                  <p className="text-sm">{isDpPayment ? 'DP (Uang Muka)' : 'Pembayaran Penuh'}</p>
                </div>

                <div className="flex items-end justify-between pt-2 border-t border-slate-100">
                  <div>
                    <p className="text-sm font-bold text-slate-900 mb-1">Total Bayar Sekarang</p>
                    <p className="text-slate-400 text-xs font-medium">
                      {isDpPayment ? 'DP Flat Rp 50.000' : 'Sesuai total tarif'}
                    </p>
                  </div>
                  <div className="text-right">
                    <p className="text-2xl md:text-3xl font-extrabold text-blue-600 tracking-tight">
                      Rp {amountToPay.toLocaleString('id-ID')}
                    </p>
                  </div>
                </div>

                {isDpPayment && (
                  <div className="flex items-center justify-between p-3 bg-blue-50/50 rounded-xl border border-blue-100/50">
                    <div>
                      <p className="text-xs font-bold text-blue-700 uppercase tracking-wider">Sisa Bayar di Travel</p>
                      <p className="text-[10px] text-blue-500 font-medium">Dibayarkan saat keberangkatan</p>
                    </div>
                    <p className="text-lg font-extrabold text-blue-700">Rp {remainingBalance.toLocaleString('id-ID')}</p>
                  </div>
                )}
              </div>

            </div>
          </div>

          {/* RIGHT SIDE - PAYMENT CARD */}
          <div className="lg:col-span-5 space-y-6">
            
            {/* Payment Instruction */}
            <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8 relative overflow-hidden">
              {/* Soft Background Accent */}
              <div className="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full blur-2xl -z-0 pointer-events-none"></div>

              <div className="relative z-10">
                <div className="flex items-center gap-3 mb-6">
                  <div className="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <Building2 className="w-5 h-5" />
                  </div>
                  <h3 className="text-lg font-bold text-slate-900">Transfer Bank</h3>
                </div>

                <div className="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6">
                  <p className="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">
                    {isDpPayment ? 'Jumlah DP (Wajib)' : 'Total Pembayaran'}
                  </p>
                  <p className="text-3xl font-extrabold text-blue-600 tracking-tight mb-6">
                    Rp {amountToPay.toLocaleString('id-ID')}
                  </p>

                  <div className="space-y-4 border-t border-slate-200/60 pt-4">
                    <div>
                      <p className="text-xs font-semibold text-slate-500 mb-1">Bank Tujuan</p>
                      <p className="text-base font-bold text-slate-900">Bank BCA</p>
                    </div>
                    
                    <div>
                      <p className="text-xs font-semibold text-slate-500 mb-1">Nomor Rekening</p>
                      <div className="flex items-center justify-between bg-white px-3 py-2 rounded-xl border border-slate-200">
                        <p className="text-lg font-bold text-slate-900 tracking-wider font-mono">1234 5678 90</p>
                        <button className="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Copy Rekening">
                          <Copy className="w-4 h-4" />
                        </button>
                      </div>
                    </div>

                    <div>
                      <p className="text-xs font-semibold text-slate-500 mb-1">Atas Nama</p>
                      <p className="text-sm font-bold text-slate-900 uppercase">CV Singgalang Jaya Travel</p>
                    </div>
                  </div>
                </div>

                <div className="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                  <p className="text-xs text-blue-800 leading-relaxed font-medium">
                    {isDpPayment 
                      ? "Pembayaran DP sebesar Rp 50.000 wajib dilakukan untuk validasi kursi. Sisa pembayaran dilakukan secara tunai saat perjalanan."
                      : "Lakukan pembayaran penuh sesuai total tarif di atas dan unggah bukti transfer untuk konfirmasi otomatis."
                    }
                  </p>
                </div>
              </div>
            </div>

            {/* Upload Section */}
            <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8">
              <h3 className="text-base font-bold text-slate-900 mb-4">Unggah Bukti Transfer</h3>
              
              <input 
                type="file" 
                className="hidden" 
                ref={fileInputRef} 
                onChange={handleFileChange}
                accept="image/*"
              />

              <div 
                onClick={handleUploadClick}
                className={`border-2 border-dashed transition-all rounded-2xl p-8 text-center cursor-pointer group mb-6 ${
                  selectedFile 
                    ? 'border-emerald-200 bg-emerald-50/30 hover:bg-emerald-50/50' 
                    : 'border-slate-200 bg-slate-50 hover:bg-blue-50/50 hover:border-blue-400'
                }`}
              >
                <div className={`w-14 h-14 rounded-full flex items-center justify-center shadow-sm mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 ${
                  selectedFile ? 'bg-emerald-500 text-white' : 'bg-white text-blue-500'
                }`}>
                  {selectedFile ? <FileCheck className="w-6 h-6" /> : <UploadCloud className="w-6 h-6" />}
                </div>
                {selectedFile ? (
                  <>
                    <p className="text-sm font-bold text-emerald-700 mb-1">File Berhasil Dipilih</p>
                    <p className="text-xs font-medium text-emerald-600 truncate max-w-xs mx-auto">{selectedFile.name}</p>
                  </>
                ) : (
                  <>
                    <p className="text-sm font-bold text-slate-700 mb-1">Pilih File Bukti</p>
                    <p className="text-xs font-medium text-slate-400">Format: JPG, PNG (Maks 5MB)</p>
                  </>
                )}
              </div>

              <button 
                onClick={handleSubmit}
                disabled={isUploading}
                className={`w-full py-4 rounded-xl font-bold text-base transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-2 ${
                  isUploading 
                    ? 'bg-slate-400 cursor-not-allowed text-white' 
                    : 'bg-slate-900 hover:bg-slate-800 text-white shadow-slate-900/15'
                }`}
              >
                {isUploading ? (
                  <>
                    <Loader2 className="w-5 h-5 animate-spin" />
                    Memproses...
                  </>
                ) : (
                  <>
                    <ShieldCheck className="w-5 h-5" />
                    {isDpPayment ? 'Upload Bukti DP' : 'Upload Bukti Pembayaran'}
                  </>
                )}
              </button>
            </div>

          </div>

        </div>
      </div>
    </div>
  );
}