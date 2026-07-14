<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Singgalang Jaya Travel</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        @page {
            margin: 40px 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #0b1329;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #0b1329;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-weight: 900;
        }
        .header p {
            margin: 0;
            color: #555555;
            font-size: 10px;
            font-weight: bold;
        }
        .section-title {
            font-size: 12px;
            color: #0b1329;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        .summary-label {
            font-weight: bold;
            color: #4a5568;
            background-color: #f7fafc;
            width: 25%;
        }
        .summary-value {
            font-weight: bold;
            color: #1a202c;
        }
        .data-table th {
            background-color: #0b1329;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            font-size: 10px;
            text-transform: uppercase;
        }
        .data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #def7ec; color: #03543f; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fde8e8; color: #9b1c1c; }
        .badge-info { background-color: #e1effe; color: #1e429f; }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Singgalang Jaya Travel</h1>
        <p>Laporan Operasional & Keuangan</p>
        <p>Periode: {{ $periodLabel }} | Shift: {{ $shift === 'semua' ? 'Semua Shift' : ucfirst($shift) }}</p>
    </div>

    <!-- A. LAPORAN PENDAPATAN -->
    <div class="section-title">A. Laporan Pendapatan</div>
    <table class="summary-table">
        <tr>
            <td class="summary-label">Periode</td>
            <td class="summary-value" colspan="3">{{ $periodLabel }}</td>
        </tr>
        <tr>
            <td class="summary-label">Total DP Terverifikasi</td>
            <td class="summary-value">Rp {{ number_format($totalDp, 0, ',', '.') }}</td>
            <td class="summary-label">Total Pelunasan Terverifikasi</td>
            <td class="summary-value">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="summary-label" style="background-color: #ebf8ff; color: #2b6cb0;">Total Pendapatan Terverifikasi</td>
            <td class="summary-value" style="color: #2b6cb0;" colspan="3">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- B. LAPORAN BOOKING -->
    <div class="section-title">B. Laporan Booking</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>No. Booking</th>
                <th>Pelanggan</th>
                <th>Jadwal Keberangkatan</th>
                <th class="text-center">Status</th>
                <th class="text-right">Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td style="font-weight: bold;">{{ $booking->kode_booking }}</td>
                    <td>
                        {{ $booking->pelanggan->nama ?? 'N/A' }}<br>
                        <span style="color: #718096; font-size: 9px;">{{ $booking->pelanggan->no_hp ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $booking->jadwal ? $booking->jadwal->tanggal_keberangkatan->format('d M Y') : '-' }}<br>
                        <span style="color: #718096; font-size: 9px;">
                            {{ $booking->jadwal ? $booking->jadwal->rute->asal . ' -> ' . $booking->jadwal->rute->tujuan : '-' }}<br>
                            Shift {{ $booking->jadwal ? ucfirst($booking->jadwal->shift) : '-' }} ({{ $booking->jadwal ? \Carbon\Carbon::parse($booking->jadwal->jam_berangkat)->format('H:i') : '-' }} WIB)
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = 'badge-pending';
                            if (in_array($booking->status_booking, ['completed', 'dikonfirmasi', 'assigned_to_trip', 'on_trip'])) {
                                $statusClass = 'badge-success';
                            } elseif ($booking->status_booking === 'cancelled' || $booking->status_booking === 'expired') {
                                $statusClass = 'badge-danger';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ str_replace('_', ' ', $booking->status_booking) }}
                        </span>
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #a0aec0; padding: 20px;">Tidak ada data booking dalam periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- C. LAPORAN TRIP -->
    <div class="section-title">C. Laporan Trip</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Trip ID</th>
                <th>Driver & Armada</th>
                <th>Jadwal Perjalanan</th>
                <th class="text-center">Penumpang</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tripSummary as $trip)
                <tr>
                    <td style="font-weight: bold;">TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <strong>{{ $trip->driver->nama_driver ?? '-' }}</strong><br>
                        <span style="color: #718096; font-size: 9px;">{{ $trip->armada ? $trip->armada->nama_mobil . ' (' . $trip->armada->nomor_plat . ')' : '-' }}</span>
                    </td>
                    <td>
                        {{ $trip->jadwal ? $trip->jadwal->tanggal_keberangkatan->format('d M Y') : '-' }}<br>
                        <span style="color: #718096; font-size: 9px;">
                            {{ $trip->jadwal ? $trip->jadwal->rute->asal . ' -> ' . $trip->jadwal->rute->tujuan : '-' }}<br>
                            Shift {{ $trip->jadwal ? ucfirst($trip->jadwal->shift) : '-' }}
                        </span>
                    </td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ $trip->detailTrips->count() }} Pax
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = 'badge-info';
                            if ($trip->status_trip === 'completed') {
                                $statusClass = 'badge-success';
                            } elseif ($trip->status_trip === 'cancelled') {
                                $statusClass = 'badge-danger';
                            } elseif ($trip->status_trip === 'on_trip') {
                                $statusClass = 'badge-pending';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ $trip->status_trip }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #a0aec0; padding: 20px;">Tidak ada data trip dalam periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i:s') }} | Singgalang Jaya Travel Laporan Keuangan
    </div>

</body>
</html>
