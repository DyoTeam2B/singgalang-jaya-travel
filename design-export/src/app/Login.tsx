import { Navigation, Mail, Lock, ArrowLeft, Check, Loader2 } from "lucide-react";
import { Link, useNavigate } from "react-router";
import { ImageWithFallback } from "./components/figma/ImageWithFallback";
import { useState } from "react";
import { toast } from "sonner";

export function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [rememberMe, setRememberMe] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    // Simulate login delay
    setTimeout(() => {
      if (email === "admin@travel.com" && password === "password") {
        toast.success("Login Berhasil! Selamat datang Admin.");
        navigate("/admin");
      } else if (email === "driver@travel.com" && password === "password") {
        toast.success("Login Berhasil! Selamat datang Driver.");
        navigate("/driver");
      } else {
        toast.error("Email atau kata sandi salah.");
      }
      setIsLoading(false);
    }, 1500);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex font-poppins">
      {/* Left Side: Branding & Illustration */}
      <div className="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden flex-col justify-between p-12">
        {/* Background Image with Overlay */}
        <div className="absolute inset-0 z-0">
          <ImageWithFallback 
            src="https://images.unsplash.com/photo-1486673748761-a8d18475c757?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxoaWdod2F5JTIwcm9hZCUyMHNjZW5pY3xlbnwxfHx8fDE3NzkxMTY0MjB8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral" 
            alt="Travel background" 
            className="w-full h-full object-cover opacity-40"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        </div>

        {/* Top Logo */}
        <div className="relative z-10">
          <Link to="/" className="inline-flex items-center gap-3 group">
            <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform">
              <Navigation className="w-6 h-6 text-white" />
            </div>
            <div className="flex flex-col">
              <span className="font-extrabold text-white text-xl leading-none tracking-tight mb-0.5">
                Singgalang Jaya
              </span>
              <span className="font-bold text-blue-400 text-[11px] uppercase tracking-[0.15em] leading-none">
                Travel
              </span>
            </div>
          </Link>
        </div>

        {/* Bottom Text */}
        <div className="relative z-10 max-w-lg mt-auto">
          <h1 className="text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-6 leading-tight">
            Selamat Datang di <br />
            <span className="text-blue-400">Singgalang Jaya Travel</span>
          </h1>
          <p className="text-slate-300 text-lg font-medium leading-relaxed">
            Sistem manajemen transportasi terpadu untuk mempermudah operasional armada, penjadwalan, dan pelayanan pelanggan dengan standar premium.
          </p>
        </div>
      </div>

      {/* Right Side: Login Form */}
      <div className="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative">
        {/* Mobile Back Button */}
        <Link to="/" className="absolute top-6 left-6 lg:hidden flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
          <ArrowLeft className="w-4 h-4" />
          Kembali
        </Link>

        {/* Mobile Logo */}
        <div className="absolute top-6 right-6 lg:hidden flex items-center gap-2">
          <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <Navigation className="w-4 h-4 text-white" />
          </div>
        </div>

        <div className="w-full max-w-md">
          {/* Form Header */}
          <div className="mb-10 text-center lg:text-left">
            <h2 className="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
              Masuk ke Akun
            </h2>
            <p className="text-slate-500 font-medium text-lg">
              Silakan masukkan email dan kata sandi Anda.
            </p>
          </div>

          {/* Form */}
          <div className="bg-white p-8 sm:p-10 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
            <form onSubmit={handleLogin} className="space-y-6">
              
              {/* Email Field */}
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-700 block">
                  Alamat Email
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <Mail className="h-5 w-5 text-slate-400" />
                  </div>
                  <input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all"
                    placeholder="nama@email.com"
                    required
                  />
                </div>
              </div>

              {/* Password Field */}
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-700 block">
                  Kata Sandi
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <Lock className="h-5 w-5 text-slate-400" />
                  </div>
                  <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all"
                    placeholder="••••••••"
                    required
                  />
                </div>
              </div>

              {/* Remember Me & Forgot Password */}
              <div className="flex items-center justify-between pt-2">
                <label className="flex items-center gap-3 cursor-pointer group">
                  <div className="relative flex items-center justify-center">
                    <input 
                      type="checkbox" 
                      className="peer sr-only"
                      checked={rememberMe}
                      onChange={(e) => setRememberMe(e.target.checked)}
                    />
                    <div className="w-5 h-5 border-2 border-slate-300 rounded bg-white peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-colors group-hover:border-blue-400"></div>
                    <Check className="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" strokeWidth={3.5} />
                  </div>
                  <span className="text-sm font-semibold text-slate-600 select-none">
                    Ingat saya
                  </span>
                </label>

                <a href="#" className="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                  Lupa Sandi?
                </a>
              </div>

              {/* Submit Button */}
              <div className="pt-4">
                <button
                  type="submit"
                  disabled={isLoading}
                  className="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-600/20 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-all active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed"
                >
                  {isLoading ? (
                    <>
                      <Loader2 className="w-5 h-5 animate-spin mr-2" />
                      Memproses...
                    </>
                  ) : (
                    "Login"
                  )}
                </button>
              </div>

            </form>
          </div>

          <p className="text-center text-sm font-semibold text-slate-500 mt-10 px-4">
            Sistem akan secara otomatis mengenali peran Anda sebagai Admin atau Driver.
          </p>

        </div>
      </div>
    </div>
  );
}