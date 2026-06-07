import { Construction } from "lucide-react";

export function AdminComingSoon() {
  return (
    <div className="flex flex-col items-center justify-center h-[60vh] text-center px-4">
      <div className="w-24 h-24 bg-blue-50 text-blue-600 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner border border-blue-100">
        <Construction className="w-10 h-10" />
      </div>
      <h2 className="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Segera Hadir</h2>
      <p className="text-slate-500 font-medium text-lg max-w-md mx-auto leading-relaxed">
        Halaman ini masih dalam tahap pengembangan. Silakan kembali lagi dalam waktu dekat.
      </p>
      
      <button 
        onClick={() => window.history.back()}
        className="mt-8 px-6 py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors shadow-sm"
      >
        Kembali ke Halaman Sebelumnya
      </button>
    </div>
  );
}