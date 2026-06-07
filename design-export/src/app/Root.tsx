import { useEffect } from "react";
import { Outlet, useLocation } from "react-router";
import { Navbar } from "./components/Navbar";
import { Footer } from "./components/Footer";

export function Root() {
  const location = useLocation();
  const isLoginPage = location.pathname === "/login";

  useEffect(() => {
    document.documentElement.classList.add('scroll-smooth');
  }, []);

  return (
    <div className="min-h-screen bg-slate-50 font-poppins flex flex-col">
      {!isLoginPage && <Navbar />}
      <div className="flex-1">
        <Outlet />
      </div>
      {!isLoginPage && <Footer />}
    </div>
  );
}