<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\While_;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nis = Auth::guard('siswa')->user()->nis;
        $presensihariini = DB::table('presensi')->where('nis', $nis)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')
        ->where('nis', $nis)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        ->orderBy('tgl_presensi')
        ->get();

        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nis) as jmlhadir, SUM(IF(jam_in > "13.00",1,0)) as jmlterlambat')
        ->where('nis', $nis)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        ->first();

        $namabulan = ["", "januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $rekapizin = DB::table('pengajuan_izin')
        ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        ->where('nis', $nis)
        ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
        ->where('status_approved', 1)
        ->first();
        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini',
        'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'rekapizin'));
    }

    public function dashboardadmin(){
        $hariini = date("Y-m-d");
        $rekappresensi = DB::table('presensi')
        ->selectRaw('COUNT(nis) as jmlhadir, SUM(IF(jam_in > "13.00",1,0)) as jmlterlambat')
        ->where('tgl_presensi', $hariini)
        ->first();

        $rekapizin = DB::table('pengajuan_izin')
        ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        ->where('tgl_izin', $hariini)
        ->where('status_approved', 1)
        ->first();
        return view('dashboard.dashboardamin', compact('rekappresensi', 'rekapizin'));
    }
}
