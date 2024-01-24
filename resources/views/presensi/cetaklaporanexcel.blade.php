<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
<style>
    @page {
        size: A4
    }

    .tabledatasiswa {
        margin-top: 40px;
    }
    .tabledatasiswa tr td {
        padding: 5px;
    }
    .tablepresensi {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .tablepresensi tr th{
        border: 1px solid #0a0a0a;
        padding: 8px;
        background-color: #9b9b9b;
    }

    .tablepresensi tr td{
        border: 1px solid #0a0a0a;
        padding: 8px;
        font-size: 12px;
    }

    .foto {
        width: 40px;
        height: 50px;
    }
</style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 120PX">
                <img src="{{ asset('/assets/img/logo-sekolah.png') }}" width="100" height="100" alt="">
            </td>
            <td>
                <h1 style="margin-top: 2">SEKOLAH MASTER INDONESIA</h1>
                <h3 style="margin-top: -0.5em;">LAPORAN PRESENSI SISWA
                    PERIODE {{ strtoupper($namabulan[$bulan] )}} {{ $tahun }}</h3>
            </td>
        </tr>
    </table>
    <table class="tabledatasiswa">
        <tr>
            <td rowspan="6">
                @php
                $path = Storage::url('/uploads/siswa/'.$siswa->foto);
                @endphp
            <img src="{{ url($path) }}" alt="" width="100" height="130">
            </td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td>{{ $siswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $siswa->kelas }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>:</td>
            <td>{{ $siswa->kode_jurusan }}</td>
        </tr>
        <tr>
            <td>No. Hp</td>
            <td>:</td>
            <td>{{ $siswa->no_hp }}</td>
        </tr>
    </table>
    <table class="tablepresensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Keterangan</th>
        </tr>
        <tr>
            @foreach ($presensi as $d )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date("d-m-Y",strtotime($d->tgl_presensi )) }}</td>
                <td>{{ $d->jam_in }}</td>
                <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen'}}</td>
                <td>
                    @if ($d->jam_out != null)
                    @else
                    @endif
                <td>
                    @if ($d->jam_in > '13.00')
                    Terlambat
                    @else
                    Tepat Waktu
                    @endif
                </td>
            </tr>
            @endforeach
        </tr>
    </table>

    <table width="100%" style="margin-top: 100px">
        <tr>
            <td colspan="2" style="text-align: right">Depok, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: right; vertical-align:bottom" height="100px">
                <u>Sri Lestari</u><br>
                <b>Pengelola TKB</b>
            </td>
        </tr>
    </table>
  </section>

</body>

</html>
