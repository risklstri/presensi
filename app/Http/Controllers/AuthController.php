<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if(Auth::guard('siswa')->attempt(['nis' => $request->nis, 'password' => $request->password]))
        {
            return redirect('/dashboard');
        }else{
            return redirect('/')->with(['warning'=>'Username / Password Salah']);
        }
    }

    public Function proseslogout() {
        if (Auth::guard('siswa')->check()){
            Auth::guard('siswa')->logout();
            return redirect('/');
        }
    }

    public function prosesloginadmin(Request $request)
    {
        if(Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return redirect('/panel/dashboardadmin');
        }else{
            return redirect('/panel')->with(['warning'=>'Email / Password Salah']);
        }
    }

    public Function proseslogoutadmin() {
        if (Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }
}
