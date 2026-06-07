import { useState, useEffect } from "react";
import { 
  Search, 
  Ticket, 
  MapPin, 
  Clock, 
  User, 
  Car, 
  Phone, 
  CheckCircle2, 
  ShieldCheck, 
  CircleDashed, 
  AlertCircle,
  RefreshCw,
  Home,
  ChevronRight,
  CreditCard,
  History,
  MapPinned
} from "lucide-react";
import { useSearchParams, Link } from "react-router";

export function BookingStatus() {
  const [searchParams] = useSearchParams();
  const [hasSearched, setHasSearched] = useState(false);
  const [searchLoading, setSearchLoading] = useState(false);
  const [refreshing, setRefreshing] = useState(false);

  // Mock data state
  const [bookingData, setBookingData] = useState({
    id: "SJT-882910",
    name: "Budi Santoso",
    route: "Padang Panjang → Pekanbaru",
    pickup: "Jl. Sudirman No. 123, Padang Panjang",
    destination: "Terminal AKAP, Pekanbaru",
    date: "18 Mei 2026",
    shift: "Pagi (08.00 WIB)",
    passengers: "2",
    paymentType: "DP",
    paymentStatus: "Menunggu Verifikasi",
    totalFare: 300000,
    amountPaid: 50000,
    remainingBalance: 250000,
    currentStatus: 2 // 0 to 6 based on the 7 steps
  });

  const statusSteps = [
    "Booking Dibuat",
    "Menunggu Pembayaran",
    "Menunggu Verifikasi Admin",
    "Booking Dikonfirmasi",
    "Masuk Trip",
    "Dalam Perjalanan",
    "Selesai"
  ];

  useEffect(() => {
    const status = searchParams.get("status");
    if (status === "pending" || searchParams.get("id")) {
      setHasSearched(true);
      
      // Load data from URL if available
      const isDp = (searchParams.get("paymentType") || "DP") === "DP";
      const passengers = parseInt(searchParams.get("passengers") || "2");
      const totalFare = passengers * 150000;
      const amountPaid = isDp ? 50000 : totalFare;

      setBookingData({
        id: searchParams.get("id") || "SJT-882910",
        name: searchParams.get("name") || "Budi Santoso",
        route: searchParams.get("route") || "Padang Panjang → Pekanbaru",
        pickup: searchParams.get("pickup") || "Jl. Sudirman No. 123, Padang Panjang",
        destination: searchParams.get("destination") || "Terminal AKAP, Pekanbaru",
        date: searchParams.get("date") || "18 Mei 2026",
        shift: searchParams.get("shift") || "Pagi (08.00 WIB)",
        passengers: passengers.toString(),
        paymentType: isDp ? "DP (Uang Muka)" : "Pembayaran Penuh",
        paymentStatus: status === "pending" ? "Menunggu Verifikasi Admin" : "Lunas",
        totalFare: totalFare,
        amountPaid: amountPaid,
        remainingBalance: totalFare - amountPaid,
        currentStatus: status === "pending" ? 2 : 3
      });
    }
  }, [searchParams]);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setSearchLoading(true);
    setTimeout(() => {
      setSearchLoading(false);
      setHasSearched(true);
      setBookingData(prev => ({ ...prev, currentStatus: 3, paymentStatus: "Lunas" }));
    }, 800);
  };

  const handleRefresh = () => {
    setRefreshing(true);
    setTimeout(() => setRefreshing(false), 1000);
  };

  return (
    <div className="py-12 md:py-20 bg-slate-50 min-h-screen font-poppins">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Header Section */}
        <div className="text-center max-w-2xl mx-auto mb-10">
          <h1 className="text-slate-900 font-extrabold text-3xl md:text-4xl tracking-tight mb-3">
            Cek Status Booking
          </h1>
          <p className="text-slate-500 text-lg font-medium">
            Pantau status perjalanan dan pembayaran Anda secara real-time.
          </p>
        </div>

        {/* Search Bar - Hidden if showing auto-results from redirect unless user wants to search another */}
        {!hasSearched && (
          <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 md:p-8 mb-8">
            <form onSubmit={handleSearch} className="grid sm:grid-cols-12 gap-4 items-end">
              <div className="sm:col-span-5">
                <label htmlFor="bookingId" className="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                  ID Booking
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <Ticket className="h-5 w-5 text-slate-400" />
                  </div>
                  <input
                    type="text"
                    id="bookingId"
                    placeholder="SJT-XXXXXX"
                    className="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    defaultValue="SJT-882910"
                    required
                  />
                </div>
              </div>
              
              <div className="sm:col-span-4">
                <label htmlFor="phone" className="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                  Nomor HP
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <Phone className="h-5 w-5 text-slate-400" />
                  </div>
                  <input
                    type="tel"
                    id="phone"
                    placeholder="08xxxxxxxxxx"
                    className="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    defaultValue="081234567890"
                    required
                  />
                </div>
              </div>

              <div className="sm:col-span-3">
                <button
                  type="submit"
                  disabled={searchLoading}
                  className="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 px-4 rounded-xl font-bold transition-all shadow-md shadow-slate-900/10 active:scale-[0.98] flex items-center justify-center gap-2 h-[52px]"
                >
                  {searchLoading ? (
                    <CircleDashed className="w-5 h-5 animate-spin" />
                  ) : (
                    <>
                      <Search className="w-5 h-5" />
                      Cari Booking
                    </>
                  )}
                </button>
              </div>
            </form>
          </div>
        )}

        {hasSearched && (
          <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
            
            {/* Quick Actions & Status Badge */}
            <div className="flex flex-wrap items-center justify-between gap-4">
              <div className="flex items-center gap-3">
                <div className="bg-blue-600 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow-sm shadow-blue-600/20">
                  {bookingData.id}
                </div>
                <div className={`px-4 py-1.5 rounded-full text-sm font-bold border ${
                  bookingData.currentStatus >= 3 
                    ? "bg-emerald-50 border-emerald-200 text-emerald-700" 
                    : "bg-amber-50 border-amber-200 text-amber-700"
                }`}>
                  {statusSteps[bookingData.currentStatus]}
                </div>
              </div>
              <div className="flex items-center gap-2">
                <button 
                  onClick={handleRefresh}
                  disabled={refreshing}
                  className="flex items-center gap-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 px-4 py-2 rounded-xl hover:bg-slate-50 transition-colors shadow-sm disabled:opacity-50"
                >
                  <RefreshCw className={`w-4 h-4 ${refreshing ? 'animate-spin' : ''}`} />
                  Refresh Status
                </button>
                <Link to="/" className="flex items-center gap-2 text-sm font-bold text-white bg-slate-900 px-4 py-2 rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                  <Home className="w-4 h-4" />
                  Kembali ke Beranda
                </Link>
              </div>
            </div>

            {/* Main Content Grid */}
            <div className="grid lg:grid-cols-3 gap-6">
              
              {/* Left Column: Details */}
              <div className="lg:col-span-2 space-y-6">
                
                {/* Information Card */}
                <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                  <div className="p-6 md:p-8 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                    <History className="w-5 h-5 text-blue-600" />
                    <h3 className="text-lg font-bold text-slate-900">Detail Pemesanan</h3>
                  </div>
                  
                  <div className="p-6 md:p-8">
                    <div className="grid sm:grid-cols-2 gap-y-8 gap-x-12">
                      
                      {/* Customer */}
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-slate-400 mb-1">
                          <User className="w-4 h-4" />
                          <span className="text-[10px] font-bold uppercase tracking-widest">Nama Pelanggan</span>
                        </div>
                        <p className="text-base font-bold text-slate-900">{bookingData.name}</p>
                        <p className="text-sm font-medium text-slate-500">{bookingData.passengers} Penumpang</p>
                      </div>

                      {/* Route */}
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-slate-400 mb-1">
                          <MapPinned className="w-4 h-4" />
                          <span className="text-[10px] font-bold uppercase tracking-widest">Rute Perjalanan</span>
                        </div>
                        <p className="text-base font-bold text-slate-900">{bookingData.route}</p>
                      </div>

                      {/* Pickup */}
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-slate-400 mb-1">
                          <MapPin className="w-4 h-4 text-blue-600" />
                          <span className="text-[10px] font-bold uppercase tracking-widest">Lokasi Jemput</span>
                        </div>
                        <p className="text-sm font-semibold text-slate-900 leading-relaxed">{bookingData.pickup}</p>
                      </div>

                      {/* Destination */}
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-slate-400 mb-1">
                          <MapPin className="w-4 h-4 text-emerald-600" />
                          <span className="text-[10px] font-bold uppercase tracking-widest">Lokasi Tujuan</span>
                        </div>
                        <p className="text-sm font-semibold text-slate-900 leading-relaxed">{bookingData.destination}</p>
                      </div>

                      {/* Schedule */}
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-slate-400 mb-1">
                          <Clock className="w-4 h-4" />
                          <span className="text-[10px] font-bold uppercase tracking-widest">Keberangkatan</span>
                        </div>
                        <p className="text-base font-bold text-slate-900">{bookingData.date}</p>
                        <div className="inline-flex items-center gap-1.5 mt-1 bg-blue-50 px-2 py-0.5 rounded-md">
                          <span className="text-[10px] font-extrabold text-blue-600 uppercase">Shift {bookingData.shift.split(' ')[0]}</span>
                        </div>
                      </div>

                      {/* Fleet (Conditional) */}
                      {bookingData.currentStatus >= 4 && (
                        <div className="space-y-1">
                          <div className="flex items-center gap-2 text-slate-400 mb-1">
                            <Car className="w-4 h-4" />
                            <span className="text-[10px] font-bold uppercase tracking-widest">Armada & Driver</span>
                          </div>
                          <p className="text-base font-bold text-slate-900">Toyota Avanza (Ahmad Rizki)</p>
                          <a href="tel:081234567890" className="text-xs font-bold text-blue-600 hover:underline">Hubungi Driver</a>
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {/* Timeline Card */}
                <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                  <div className="p-6 md:p-8 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                    <History className="w-5 h-5 text-blue-600" />
                    <h3 className="text-lg font-bold text-slate-900">Timeline Perjalanan</h3>
                  </div>
                  
                  <div className="p-8 md:p-10">
                    <div className="relative">
                      {/* Vertical Line */}
                      <div className="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100 -translate-x-1/2"></div>
                      
                      <div className="space-y-8 relative">
                        {statusSteps.map((step, index) => {
                          const isCompleted = index < bookingData.currentStatus;
                          const isCurrent = index === bookingData.currentStatus;
                          
                          return (
                            <div key={step} className="flex items-start gap-6 relative">
                              {/* Indicator Circle */}
                              <div className={`w-8 h-8 rounded-full shrink-0 flex items-center justify-center z-10 border-4 border-white shadow-sm ${
                                isCompleted ? 'bg-emerald-500 text-white' : 
                                isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-100' : 
                                'bg-slate-200 text-slate-400'
                              }`}>
                                {isCompleted ? <CheckCircle2 className="w-4 h-4" /> : 
                                 isCurrent ? <div className="w-2 h-2 rounded-full bg-white animate-pulse" /> :
                                 <span className="text-[10px] font-bold">{index + 1}</span>}
                              </div>
                              
                              <div className="pt-1">
                                <p className={`text-sm font-bold ${isCurrent ? 'text-blue-600' : isCompleted ? 'text-slate-900' : 'text-slate-400'}`}>
                                  {step}
                                </p>
                                {isCurrent && (
                                  <p className="text-xs font-medium text-slate-500 mt-1">
                                    {index === 2 ? "Admin sedang memproses verifikasi bukti bayar Anda." : 
                                     index === 4 ? "Armada sedang disiapkan untuk menjemput Anda." :
                                     "Status saat ini."}
                                  </p>
                                )}
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {/* Right Column: Payment */}
              <div className="space-y-6">
                
                {/* Payment Card */}
                <div className="bg-white rounded-3xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                  <div className="p-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                    <CreditCard className="w-5 h-5 text-blue-600" />
                    <h3 className="text-base font-bold text-slate-900">Informasi Pembayaran</h3>
                  </div>
                  
                  <div className="p-6 space-y-6">
                    <div className="space-y-4">
                      <div className="flex justify-between items-center">
                        <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">Jenis</span>
                        <span className="text-sm font-bold text-slate-900">{bookingData.paymentType}</span>
                      </div>
                      <div className="flex justify-between items-center">
                        <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">Status</span>
                        <div className={`flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase ${
                          bookingData.paymentStatus.includes("Verifikasi") 
                            ? "bg-amber-50 text-amber-700" 
                            : "bg-emerald-50 text-emerald-700"
                        }`}>
                          {bookingData.paymentStatus.includes("Verifikasi") && <AlertCircle className="w-3 h-3" />}
                          {bookingData.paymentStatus}
                        </div>
                      </div>
                      <div className="border-t border-dashed border-slate-100 pt-4 mt-4">
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Biaya</span>
                          <span className="text-sm font-bold text-slate-900">Rp {bookingData.totalFare.toLocaleString('id-ID')}</span>
                        </div>
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-xs font-bold text-slate-400 uppercase tracking-widest">Sudah Dibayar</span>
                          <span className="text-sm font-bold text-emerald-600">Rp {bookingData.amountPaid.toLocaleString('id-ID')}</span>
                        </div>
                        {bookingData.remainingBalance > 0 && (
                          <div className="flex justify-between items-center mt-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <span className="text-xs font-bold text-blue-700 uppercase tracking-widest">Sisa Bayar</span>
                            <span className="text-lg font-extrabold text-blue-700">Rp {bookingData.remainingBalance.toLocaleString('id-ID')}</span>
                          </div>
                        )}
                      </div>
                    </div>

                    {bookingData.currentStatus === 2 && (
                      <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <p className="text-[10px] text-slate-500 font-medium leading-relaxed">
                          Pembayaran DP Anda sedang diverifikasi. Silakan hubungi admin jika status tidak berubah dalam 1x24 jam.
                        </p>
                      </div>
                    )}
                  </div>
                </div>

                {/* Help Card */}
                <div className="bg-slate-900 rounded-3xl p-6 text-white relative overflow-hidden group">
                  <div className="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-600/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                  <h4 className="text-lg font-bold mb-2 relative z-10">Butuh Bantuan?</h4>
                  <p className="text-slate-400 text-xs mb-6 relative z-10 leading-relaxed">
                    Jika Anda mengalami kendala dengan pesanan Anda, tim support kami siap membantu 24/7.
                  </p>
                  <a href="https://wa.me/6281234567890" target="_blank" rel="noreferrer" className="w-full inline-flex justify-center items-center gap-2 py-3 bg-white text-slate-900 rounded-xl font-bold text-sm hover:bg-blue-50 transition-colors relative z-10 shadow-lg shadow-white/5">
                    <Phone className="w-4 h-4" />
                    Hubungi WhatsApp
                  </a>
                </div>

              </div>
            </div>
          </div>
        )}

        {/* Empty State / Not Found (Optional enhancement) */}
        {hasSearched && bookingData.id === "" && (
          <div className="text-center py-20 bg-white rounded-3xl border border-slate-200 shadow-sm">
            <div className="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Search className="w-8 h-8 text-slate-300" />
            </div>
            <h3 className="text-lg font-bold text-slate-900 mb-1">Booking Tidak Ditemukan</h3>
            <p className="text-slate-500 text-sm max-w-xs mx-auto">Silakan periksa kembali ID Booking Anda atau hubungi admin kami.</p>
            <button 
              onClick={() => setHasSearched(false)}
              className="mt-6 font-bold text-blue-600 hover:text-blue-700 underline underline-offset-4"
            >
              Cari Ulang
            </button>
          </div>
        )}

      </div>
    </div>
  );
}