<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan BK</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 40px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .header .text {
            text-align: center;
            flex-grow: 1;
        }

        .header .text h1 {
            font-size: 22px;
            margin: 0;
        }

        .header .text p {
            font-size: 14px;
            margin: 2px 0;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 30px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            padding: 8px 12px;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            width: 35%;
            text-align: left;
            font-weight: normal;
        }

        .signature {
            margin-top: 15px;
            text-align: right;
        }

        .signature p {
            margin: 0;
            font-size: 14px;
        }

        .signature .name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="Logo Sekolah">
        <div class="text">
            <h1>BK SMAN 1 KWANYAR</h1>
            <p>Jl. Raya Dlemer No. 31, Kec. Kwanyar, Bangkalan</p>
        </div>
    </div>

    <div class="report-title">Laporan Bimbingan Konseling</div>

    <table>
        <tr><th>Kategori :</th><td>{{ $rekap->jadwal->kategori }}</td></tr>
        <tr><th>Nama Siswa :</th><td>{{ $rekap->jadwal->siswa->nama_siswa }}</td></tr>
        <tr><th>Guru BK :</th><td>{{ $rekap->guruBk->nama_guru_bk }}</td></tr>
        <tr><th>Hasil BK :</th><td>{{ $rekap->hasil_bk }}</td></tr>
        <tr><th>Kehadiran Siswa :</th><td>{{ $rekap->kehadiran_siswa ? 'Hadir' : 'Tidak Hadir' }}</td></tr>
        @if ($rekap->jadwal->kategori === 'Pemanggilan Ortu')
        <tr><th>Kehadiran Orang Tua :</th><td>{{ $rekap->kehadiran_ortu ? 'Hadir' : 'Tidak Hadir' }}</td></tr>
        @endif
        <tr><th>Catatan Tambahan :</th><td>{{ $rekap->catatan_tambahan }}</td></tr>
    </table>

    <div class="signature">
        <p>Bangkalan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>Guru BK</p>
        <p class="name">{{ $rekap->guruBk->nama_guru_bk }}</p>
    </div>

</body>
</html>
