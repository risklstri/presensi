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
        size: F4
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
        font-size: 10px;
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
<body class="F4 landscape">

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
                    <h3 style="margin-top: -0.5em;">REKAP PRESENSI SISWA
                        PERIODE {{ strtoupper($namabulan[$bulan] )}} {{ $tahun }}</h3>
                </td>
            </tr>
        </table>

        <table class="tablepresensi">
            <tr>
                <th rowspan="2">NIS</th>
                <th rowspan="2">Nama Siswa</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">TH</th>
                <th rowspan="2">TK</th>
            </tr>
            <tr>
                <?php
                for ($i=1; $i <=31; $i++) {
                ?>
                <th>{{ $i }}</th>
                <?php
                }
                ?>
            </tr>
            @foreach ($rekap as $d)
            <tr>
                <td>{{ $d->nis }}</td>
                <td>{{ $d->nama_lengkap }}</td>

                <?php
                $totalhadir = 0;
                $totalterlambat = 0;
                for($i=1; $i<= 31; $i++){
                    $tgl = "tgl_".$i;
                    if (empty($d->$tgl)) {
                        $hadir = ['',''];
                        $totalhadir += 0;
                    }else {
                        $hadir = explode("-",$d->$tgl);
                        $totalhadir += 1;
                        if($hadir[0] > "13:00:00")
                        $totalterlambat += 1;
                    }
                ?>

                <td>
                    <span style="color: {{ $hadir[0] > "13:00:00" ? "red" : "" }}">{{ $hadir[0] }}</span><br>
                    <span style="color: {{ $hadir[1] < "17:00:00" ? "red" : "" }}">{{ $hadir[1] }}</span><br>
                </td>
                <?php
                }
                ?>
                <td>{{ $totalhadir }}</td>
                <td>{{ $totalterlambat }}</td>
            </tr>
            @endforeach
        </table>

        <table width="100%" style="margin-top: 100px">
            <tr>
                <td></td>
                <td style="text-align: right">Depok, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: right; vertical-align:bottom">
                </td>
                <td style="text-align: right; vertical-align:bottom" height="100px">
                    <u>Sri Lestari</u><br>
                    <b>Pengelola TKB</b>
                </td>
            </tr>
        </table>
  </section>

</body>

</html>
