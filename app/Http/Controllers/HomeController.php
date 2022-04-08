<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\relatorio;
use App\Models\Assistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Qlib\Qlib;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function teste(){
      //$dados = $request->all();
      //var_dump($dados);
      return view('teste');
    }
    public function upload(Request $request){
      $dados = $request->all();
      var_dump($dados);
    }


    public function index()
    {
        $controlerFamilias = new FamiliaController(Auth::user());
        $dadosFamilias = $controlerFamilias->queryFamilias();
        $config = [
            'c_familias'=>$dadosFamilias,
        ];
        return view('home',[
            'config'=>$config,
        ]);
    }
    public function resumo(){

    }
}
