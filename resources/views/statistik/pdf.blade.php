<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Perangkat Lab</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; }
        .header p { margin: 5px 0 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .summary-box { margin-top: 20px; padding: 10px; border: 1px dashed #666; width: 300px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN TRANSAKSI PEMINJAMAN LABORATORIUM</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th>Nama Peminjam</th>
                <th>Perangkat</th>
                <th class="text-center">Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $index => $pinjam)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pinjam->user->name ?? 'User Terhapus' }}</td>
                    <td>{{ $pinjam->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                    <td class="text-center">{{ $pinjam->jumlah }} Unit</td>
                    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->translatedFormat('d M Y') }}</td>
                    <td class="text-center">{{ ucfirst($pinjam->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada transaksi pada rentang waktu ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <strong>Ringkasan Data:</strong><br><br>
        Total Pengajuan: {{ $statistik['total_pengajuan'] }}<br>
        Disetujui / Dipinjam: {{ $statistik['disetujui'] + $statistik['dipinjam'] }}<br>
        Berhasil Dikembalikan: {{ $statistik['dikembalikan'] }}<br>
        Ditolak: {{ $statistik['ditolak'] }}
    </div>

</body>
</html>