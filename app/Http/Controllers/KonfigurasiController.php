<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasisekolah()
    {
        $lokasi_sekolah = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view('konfigurasi.lokasisekolah',compact('lokasi_sekolah'));
    }

    public function updatelokasisekolah(Request $request)
    {
        $lokasi_sekolah = $request->lokasi_sekolah;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id',1)->update([
            'lokasi_sekolah' => $lokasi_sekolah,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back()->with(['success' => 'Lokasi berhasil diupdate!']);
        }else{
            return Redirect::back()->with(['warning' => 'Lokasi gagal diupdate!']);
        }
    }
}
