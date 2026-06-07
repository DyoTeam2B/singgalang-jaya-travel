import { createBrowserRouter } from "react-router";
import { Root } from "./Root";
import { Home } from "./Home";
import { Login } from "./Login";
import { DriverDashboard } from "./DriverDashboard";
import { AdminLayoutWrapper } from "./AdminLayout";
import { AdminDashboard } from "./AdminDashboard";
import { AdminBooking } from "./AdminBooking";
import { AdminPayment } from "./AdminPayment";
import { AdminTrip } from "./AdminTrip";
import { AdminDriver } from "./AdminDriver";
import { AdminJadwal } from "./AdminJadwal";
import { AdminLaporan } from "./AdminLaporan";
import { AdminProfile } from "./AdminProfile";
import { TripHistory } from "./TripHistory";
import { DriverManifest } from "./DriverManifest";
import { BookingForm } from "./components/BookingForm";
import { BookingDetail } from "./components/BookingDetail";
import { BookingPayment } from "./components/BookingPayment";
import { BookingStatus } from "./components/BookingStatus";

import { DriverLayoutWrapper } from "./components/DriverLayout";
import { DriverHistory } from "./DriverHistory";
import { DriverProfile } from "./DriverProfile";

import { AdminTripDetail } from "./AdminTripDetail";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: Root,
    children: [
      { index: true, Component: Home },
      { path: "booking", Component: BookingForm },
      { path: "booking/detail", Component: BookingDetail },
      { path: "booking/payment", Component: BookingPayment },
      { path: "cek-status", Component: BookingStatus },
    ],
  },
  {
    path: "/login",
    Component: Login,
  },
  {
    path: "/driver",
    Component: DriverLayoutWrapper,
    children: [
      { index: true, Component: DriverDashboard },
      { path: "history", Component: DriverHistory },
      { path: "profile", Component: DriverProfile },
      { path: "manifest", Component: DriverManifest },
    ]
  },
  {
    path: "/admin",
    Component: AdminLayoutWrapper,
    children: [
      { index: true, Component: AdminDashboard },
      { path: "booking", Component: AdminBooking },
      { path: "pembayaran", Component: AdminPayment },
      { path: "trip", Component: AdminTrip },
      { path: "trip/detail", Component: AdminTripDetail },
      { path: "trip/history", Component: TripHistory },
      { path: "driver", Component: AdminDriver },
      { path: "jadwal", Component: AdminJadwal },
      { path: "laporan", Component: AdminLaporan },
      { path: "profile", Component: AdminProfile },
    ]
  }
]);