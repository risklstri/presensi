<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function index(Request $request){
        $query = Siswa::query();
        $query->select('siswa.*', 'nama_jurusan');
        $query->join('jurusan', 'siswa.kode_jurusan', '=', 'jurusan.kode_jurusan');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_siswa)){
            $query->where('nama_lengkap','like','%'.$request->nama_siswa.'%');
        }

        if(!empty($request->kode_jurusan)){
            $query->where('siswa.kode_jurusan', $request->kode_jurusan);
        }

        $siswa = $query->paginate(5);

        $jurusan = DB::table('jurusan')->get();
        return view('siswa.index', compact('siswa', 'jurusan'));
    }

    public function store(Request $request){
        $nis = $request->nis;
        $nama_lengkap = $request->nama_lengkap;
        $kelas = $request->kelas;
        $no_hp = $request->no_hp;
        $kode_jurusan = $request->kode_jurusan;
        $password = Hash::make('12345');
        if ($request->hasFile('foto'))
        {
            $foto = $nis . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = null;
        }

        try {
            $data =[
                'nis'=>$nis,
                'nama_lengkap'=>$nama_lengkap,
                'kelas'=>$kelas,
                'no_hp'=>$no_hp,
                'kode_jurusan'=>$kode_jurusan,
                'foto'=>$foto,
                'password'=>$password
            ];
            $simpan = DB::table('siswa')->insert($data);
            if ($simpan){
                if ($request->hasFile('foto')){
                    $folderPath = "public/uploads/siswa/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data berhasil disimpan']);
            }
        } catch (\Exception $e) {
            if($e->getCode() == 23000) {
                $message = "karena NIS " .$nis. " sudah ada!";
            }
            return Redirect::back()->with(['warning'=>'Data gagal disimpan ' . $message]);
        }
    }

    public function edit(Request $request) {
        $nis = $request->nis;
        $jurusan = DB::table('jurusan')->get();
        $siswa = DB::table('siswa')->where('nis',$nis)->first();
        return view('siswa.edit', compact('jurusan', 'siswa'));
    }

    public function update($nis, Request $request) {
        $nis = $request->nis;
        $nama_lengkap = $request->nama_lengkap;
        $kelas = $request->kelas;
        $no_hp = $request->no_hp;
        $kode_jurusan = $request->kode_jurusan;
        $password = Hash::make('12345');
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto'))
        {
            $foto = $nis . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $old_foto;
        }

        try {
            $data =[
                'nama_lengkap'=>$nama_lengkap,
                'kelas'=>$kelas,
                'no_hp'=>$no_hp,
                'kode_jurusan'=>$kode_jurusan,
                'foto'=>$foto,
                'password'=>$password
            ];
            $update = DB::table('siswa')->where('nis',$nis)->update($data);
            if ($update){
                if ($request->hasFile('foto')){
                    $folderPath = "public/uploads/siswa/";
                    $folderPathOld = "public/uploads/siswa/".$old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data berhasil diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning'=>'Data gagal diupdate']);
        }
    }

    public function delete($nis) {
        $delete = DB::table('siswa')->where('nis',$nis)->delete();
        if($delete){
            return Redirect::back()->with(['success'=>'Data berhasil dihapus']);
        }else{
            return Redirect::back()->with(['warning'=>'Data gagal dihapus']);
        }
    }

    public function resetpassword($nis)
    {
        $nis = Crypt::decrypt($nis);
        $password = Hash::make('12345');
        $reset = DB::table('siswa')->where('nis',$nis)->update([
            'password' => $password
        ]);

        if($reset){
            return Redirect::back()->with(['success' => 'Password berhasil direset']);
        }else{
            return Redirect::back()->with(['warning' => 'Password gagal direset']);
        }
    }
}
