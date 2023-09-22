<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Qlib\Qlib;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        $ret = Qlib::redirectLogin();
        //REGISTRAR EVENTO
        $regev = Qlib::regEvent(['action'=>'login','tab'=>'user','config'=>[
            'obs'=>'Usuario logado',
            'link'=>$ret,
                ]
        ]);

        return $ret;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        // $this->VerifiedActive($request);
        $this->middleware('guest')->except('logout');
    }
    protected function VerifiedActive(Request $request){
        $d = $request->all();
        if(isset($d['email']) && ($email=$d['email'])){
            $us = User::Where('email',$email)->get();
            if($us->count()){
                if(isset($us['ativo'])=='n'){
                    return redirect()->route('cobranca.suspenso');
                }
            }
        }
    }
}
