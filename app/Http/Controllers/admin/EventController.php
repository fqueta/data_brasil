<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Qlib\Qlib;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Registra eventos no sistema
     * @return bool;
     */
    protected $user;
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }
    public function regEvent($config=false)
    {
        //return true;
        //$ev = new EventController;
        //$user = $this->user;
        $ret =false;
        if($config){
            if(isset($config['action']) && isset($config['action'])){
                $action = isset($config['action'])?$config['action']:false;
                $tab = isset($config['tab'])?$config['tab']:false;
                $conf = isset($config['config'])?$config['config']:[];
                $conf['IP'] = Qlib::get_client_ip();
                $user_id = isset($config['user_id'])?$config['user_id']:Auth::id();
                $post_id = isset($config['post_id'])?$config['post_id']:NULL;
                $ds = [
                    'token'=>uniqid(),
                    'user_id'=>$user_id,
                    'post_id'=>$post_id,
                    'action'=>$action,
                    'tab'=>$tab,
                    'config'=>Qlib::lib_array_json($conf),
                ];
                // dd($ds);
                $ret = Event::create($ds);
            }
        }
        return $ret;
    }
    public function listEventsUser($config = null)
    {
        $id_user = isset($config['id_user'])?$config['id_user'] : 0;
        $d = Event::where('user_id','=',$id_user)->orderBy('id','DESC')->get();
        return $d;

    }
    public function listEventsPost($config = null)
    {
        //$id_user = isset($config['id_user'])?$config['id_user'] : 0;
        $post_id = isset($config['post_id'])?$config['post_id'] : 0;
        $d = Event::where('post_id','=',$post_id)->orderBy('id','DESC')->get();
        return $d;
    }
    public function listarEvent($config=false){
        $request = request();
        $regev = false;
        if(isset($config['tab'])){
            $label = isset($config['this']->label)?$config['this']->label:$config['tab'];
            $user = auth()->user();
            $routeName = $request->route()->getName();
            $pRoute = explode('.',$routeName);
            $action = @$pRoute[1];
            $acaoObs = false;
            $link = false;
            $id = $request->route()->parameter('id');
            if($action=='index'){
                $acaoObs = __('Listou cadastros de ').$label;
                $link = Qlib::UrlAtual();
            }elseif($action=='create'){
                $acaoObs = __('Abriu tela de cadastro de ').$label;
                $link = Qlib::UrlAtual();
            }elseif($action=='store'){
                $acaoObs = __('Criou cadastros de ').$label;
                if(isset($config['id'])){
                    $link = route($pRoute[0].'.show',['id'=>$config['id']]);
                    $id = $config['id'];
                }
            }elseif($action=='show'){
                $acaoObs = __('Visualizou cadastros de ').$label;
                $link = route($routeName,['id'=>$id]);
            }elseif($action=='edit'){
                $link = route($routeName,['id'=>$id]);
                $acaoObs = __('Abriu tela de Edição de ').$label;
            }elseif($action=='perfil'){
                $action = 'edit';
                $id = isset($config['id'])?$config['id']:$id;
                $link = route($routeName,['id'=>$id]);
                $acaoObs = __('Abriu tela de Edição de ').$label;
            }elseif($action=='update'){
                $acaoObs = __('Atualizou cadastro de ').$label;
                $link = route($pRoute[0].'.show',['id'=>$id]);
            }
            //dd($request->route()->parameter('id'));
            //REGISTRAR EVENTO DE LISTA
            $cfe = [
                'action'=>$action,
                'tab'=>$config['tab'],
                'user_id'=>$user['id'],
                'post_id'=>$id,
                'config'=>[
                    'obs'=>$acaoObs,
                    'label'=>$label,
                    'link'=>$link,
                ],
            ];
            // dd($cfe);
            $regev = $this->regEvent($cfe);
        }
        return $regev;
    }
}
