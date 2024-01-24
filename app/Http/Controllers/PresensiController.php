<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class PresensiController extends Controller
{
    public function create() {
        $hariini = date("Y-m-d");
        $nis = Auth::guard('siswa')->user()->nis;
        $cek = DB::table('presensi')->where('tgl_presensi',$hariini)->where('nis', $nis)->count();
        $lokasi_sekolah = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        return view('presensi.create', compact('cek', 'lokasi_sekolah'));
    }
    public function store(Request $request){
        $nis = Auth::guard('siswa')->user()->nis;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi_sekolah = DB::table('konfigurasi_lokasi')->where('id',1)->first();
        $lok = explode(",",$lokasi_sekolah->lokasi_sekolah);
        $latitudesekolah = $lok[0];
        $longitudesekolah =  $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudesekolah, $longitudesekolah, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis', $nis)->count();
        if($cek > 0){
            $ket = "out";
        }else{
            $ket = "in";
        }

        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName =$nis . "_" . $tgl_presensi . "_" . $ket;
        $image_parts = explode(";base64", $image); //Gambar di enkripsi menggunakan base 64
        $image_base64 = base64_decode($image_parts[1]); //Gambar di dekode (mengembalikan menjadi gambar)
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if($radius > $lokasi_sekolah->radius) {
            echo "error|Anda berada di luar radius";
        }else{
        if($cek > 0){
            $data_pulang = [
                'jam_out' => $jam,
                'foto_out' => $fileName,
                'lokasi_out' => $lokasi
            ];
            $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nis', $nis)->update($data_pulang);
            if($update){
                echo "success|Kamu berhasil melakukan absen pulang";
                Storage::put($file, $image_base64);
            }else{
                echo "error|Silahkan Coba Lagi!";
            }
        }else{
            $data = [
                'nis' => $nis,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi
            ];

            $simpan = DB::table('presensi')->insert($data);
            if($simpan){
                echo "success|Kamu berhasil melakukan absen masuk";
                Storage::put($file, $image_base64);
            }else{
                echo "error|Silahkan Coba Lagi!";
                }
            }
        }
    }

    //fungsi ini untuk menghitung jarak
    function distance($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nis = Auth::guard('siswa')->user()->nis;
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        return view('presensi.editprofile', compact('siswa'));
    }

    public function updateprofile(Request $request)
    {
        $nis = Auth::guard('siswa')->user()->nis;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $siswa = DB::table('siswa')->where('nis', $nis)->first();
        if ($request->hasFile('foto'))
        {
            $foto = $nis . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $siswa->foto;
        }

        if(empty($request->password))
        {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        }else{
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto,
            ];
        }

        $update = DB::table('siswa')->where('nis', $nis)->update($data);
        if($update){
            if ($request->hasFile('foto')){
                $folderPath = "public/uploads/siswa/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        }else{
            return Redirect::back()->with(['error' => 'Data gagal diupdate']);
        }
    }

    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                    "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nis = Auth::guard('siswa')->user()->nis;

        $histori = DB::table('presensi')
        ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
        ->where('nis', $nis)
        ->orderBy('tgl_presensi')
        ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin()
    {
        $nis = Auth::guard('siswa')->user()->nis;
        $dataizin = DB::table('pengajuan_izin')->where('nis', $nis)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request){
        $nis = Auth::guard('siswa')->user()->nis;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nis' => $nis,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if($simpan){
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
        }else{
            return redirect('/presensi/izin')->with(['error' => 'Data gagal disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
        ->select('presensi.*', 'nama_lengkap', 'nama_jurusan')
        ->join('siswa','presensi.nis', '=', 'siswa.nis')
        ->join('jurusan', 'siswa.kode_jurusan', '=','jurusan.kode_jurusan')
        ->where('tgl_presensi',$tanggal)
        ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
        ->join('siswa', 'presensi.nis', '=', 'siswa.nis')
        ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"];
        $siswa = DB::table('siswa')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'siswa'));
    }

    public function cetaklaporan(Request $request)
    {
        $nis = $request->nis;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"];
        $siswa = DB::table('siswa')->where('nis', $nis)
        ->join('jurusan', 'siswa.kode_jurusan', '=', 'jurusan.kode_jurusan')
        ->first();

        $presensi = DB::table('presensi')
        ->where('nis',$nis)
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->orderBy('tgl_presensi')
        ->get();

        if(isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            //mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename= Laporan Presensi Siswa $time.xls");

            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'siswa', 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'siswa', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"];

        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"];
        $tahun = $request->tahun;
        $rekap = DB::table('presensi')
        ->selectRaw('presensi.nis,nama_lengkap,
        MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
        MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
        MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
        MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
        MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
        MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
        MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
        MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
        MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
        MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
        MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
        MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
        MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
        MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
        MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
        MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
        MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
        MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
        MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
        MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
        MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
        MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
        MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
        MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
        MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
        MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
        MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
        MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
        MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
        MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
        MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31
        ')
        ->join('siswa', 'presensi.nis', '=', 'siswa.nis')
        ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->groupByRaw('presensi.nis,nama_lengkap')
        ->get();

        if(isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            //mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename= Rekap Presensi Siswa $time.xls");
        }
        return view('presensi.cetakrekap',compact('bulan','tahun','namabulan','rekap'));
    }

    public function izinsakit(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->select('id','tgl_izin', 'pengajuan_izin.nis', 'nama_lengkap', 'kelas', 'status', 'status_approved', 'keterangan');
        $query->join('siswa','pengajuan_izin.nis', '=', 'siswa.nis');
        if(!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }

        if(!empty($request->nis)){
            $query->where('pengajuan_izin.nis', $request->nis);
        }

        if(!empty($request->nama_lengkap)){
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2'){
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin', 'desc');
        $izinsakit = $query->paginate(5);
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izin')->where('id',$id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);

        if($update) {
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        }else {
            return Redirect::back()->with(['warning' => 'Data gagal diupdate']);
        }
    }

    public function batalkanizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id',$id)->update([
            'status_approved' => 0
        ]);

        if($update) {
            return Redirect::back()->with(['success' => 'Data berhasil diupdate']);
        }else {
            return Redirect::back()->with(['warning' => 'Data gagal diupdate']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nis = Auth::guard('siswa')->user()->nis;

        $cek = DB::table('pengajuan_izin')->where('nis', $nis)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }
}
