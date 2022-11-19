<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RelatoriosController;
use App\Models\Event;
use App\Models\Tag;
use App\Qlib\Qlib;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class EventController extends Controller
{
    /**
     * Registra eventos no sistema
     * @return bool;
     */
    protected $user;
    public $label;
    public $view;
    public $tab;
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->routa = request()->route()->getName();
        $this->label = 'Relatorios';
        $this->view = 'padrao';
        $this->tab = 'events';
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
        $id_user = isset($config['id_user'])?$config['id_user'] : Auth::id();
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
    public function queryEvents($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //dd($get);
        //$idUltimaEtapa = Etapa::where('ativo','=','s')->where('excluido','=','n')->where('deletado','=','n')->max('id');
        $idUltimaEtapa=false;
        $tags = Tag::where('ativo','=','s')->where('pai','=','1')->where('excluido','=','n')->where('deletado','=','n')->OrderBy('ordem','asc')->get();
        $id_pendencia = 3;
        $id_imComRegistro = 4;
        $id_recusas = 5;
        $id_nLocalizado = 6;
        $completos = 0;
        $pendentes = 0;
        // $etapas = Etapa::where('ativo','=','s')->where('excluido','=','n')->OrderBy('ordem','asc')->get();
        //$todasEvents = Event::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        DB::enableQueryLog();

        if(isset($get['ano']) && !empty($get['ano'])){
            $familia = Event::where('excluido','=','n')->where('deletado','=','n')->whereYear('data_exec',$get['ano'])->orderBy('id',$config['order']);
            $countFam = Event::where('excluido','=','n')->where('deletado','=','n')->whereYear('data_exec',$get['ano'])->orderBy('id',$config['order']);
            //Totalizadores.
            $totProcesso['entregue'] = Event::where('excluido','=','n')->where('deletado','=','n')->whereYear('data_exec',$get['ano'])->orderBy('id',$config['order'])->where('config','LIKE','%"categoria_processo":"processo_entregue"%')->count();
            $totProcesso['certidao'] = Event::where('excluido','=','n')->where('deletado','=','n')->whereYear('data_exec',$get['ano'])->orderBy('id',$config['order'])->where('config','LIKE','%"categoria_processo":"certidao"%')->count();
        }else{
            $familia =  Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
            $countFam =  Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
            $totProcesso['entregue'] = Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('config','LIKE','%"categoria_processo":"processo_entregue"%')->count();
            $totProcesso['certidao'] = Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('config','LIKE','%"categoria_processo":"certidao"%')->count();

        }
        //$familia =  DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $familia_totais = new stdClass;
        //$campos = isset($_SESSION['campos_familias_exibe']) ? $_SESSION['campos_familias_exibe'] : $this->campos();
        $rel = new RelatoriosController($this->user);
        $campos = $rel->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $familia->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }elseif(is_array($value)){
                            foreach ($value as $kb => $vb) {
                                if(!empty($vb)){
                                    if($key=='tags'){
                                        $familia->where($key,'LIKE', '%"'.$vb.'"%' );
                                    }else{
                                        $familia->where($key,'LIKE', '%"'.$kb.'":"'.$vb.'"%' );
                                    }
                                }
                            }
                        }else{
                            if($key=='quadra'){
                                $familia->where($key,'=', $value);
                                if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
                                    $value = $campos[$key]['arr_opc'][$value];
                                }
                                $arr_titulo[$campos[$key]['label']] = Qlib::valorTabDb('quadras','id',$value,'nome');
                            }elseif($key=='id_beneficiario'){
                                $familia->where($key,'=', $value);
                                if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
                                    $value = $campos[$key]['arr_opc'][$value];
                                }
                                $arr_titulo[$campos[$key]['label']] = Qlib::valorTabDb('beneficiarios','id',$value,'nome');
                            }else{
                                //dd( $campos);exit;
                                $arr_titulo[$campos[$key]['label']] = $value;
                                $familia->where($key,'LIKE','%'. $value. '%');
                                if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
                                    $value = $campos[$key]['arr_opc'][$value];
                                }
                            }
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $familia;
                if($config['limit']=='todos'){
                    $familia = $familia->get();
                }else{
                    $familia = $familia->paginate($config['limit']);
                }
                //$query = DB::getQueryLog();
                //$query = end($query);
                //dd($query);

                if($idUltimaEtapa)
                $completos = $familia->where('etapa','=',$idUltimaEtapa)->count();
                $pendentes = $familia->where('tags','LIKE','%"'.$id_pendencia.'"')->count();
                $familia_totais->todos = $fm->count();
                $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
                $familia_totais->idoso = $fm->where('idoso','=','s')->count();
                $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
        }else{
            $fm = $familia;
            if($idUltimaEtapa){
                $completos = $countFam->where('etapa','=',$idUltimaEtapa)->count();
            }

            if($config['limit']=='todos'){
                $familia = $familia->get();
            }else{
                $familia = $familia->paginate($config['limit']);
            }
            $familia_totais->todos = $fm->count();
            $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
            $familia_totais->idoso = $fm->where('idoso','=','s')->count();
            $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
        }
        $progresso = [];
        if($etapas){
            foreach ($etapas as $key => $value) {
                $progresso[$key]['label'] = $value['nome'];
                $progresso[$key]['total'] = Event::where('etapa','=',$value['id'])->where('excluido','=','n')->where('deletado','=','n')->count();
                $progresso[$key]['geral'] = $familia_totais->todos;
                if($progresso[$key]['total']>0 && $progresso[$key]['geral'] >0){
                    $porceto = round($progresso[$key]['total']*100/$progresso[$key]['geral'],2);
                }else{
                    $porceto = 0;
                }
                $progresso[$key]['porcento'] = $porceto;
                $progresso[$key]['color'] = $this->colorPorcento($porceto);
            }
        }
        $familia_totais->completos = $completos;

        $colTabela = $rel->colTabela($familia);
        //$ret['familia'] = $familia;
        $ret['familia'] = $colTabela;
        $ret['familia_totais'] = $familia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        //$ret['progresso'] = $progresso;
        $ret['link_completos'] = route('familias.index').'?filter[etapa]='.$idUltimaEtapa;
        $ret['link_idosos'] = route('familias.index').'?filter[idoso]=s';
        $cardTags = [];
        $ret['cards_home'] = [];
        $compleLinkVisu = false;
        $compleLinkVisu1 = false;
        if(isset($get['ano'])&&$get['ano']){
            $compleLinkVisu = '&ano='.$get['ano'];
        }
        if($compleLinkVisu){
            $compleLinkVisu1 = '?'.$compleLinkVisu;
        }
        $cards_homeTodos = [
                'label'=>'TODOS OS CADASTROS',
                'valor'=>$familia_totais->todos,
                'obs'=>'São cadastros completos sistematizados por ano de execução do projeto ou programa. Incluem cadastros completos, cadastros com pendências e imóveis com registro anterior junto ao Cartório de Registro de Imóveis.',
                'href'=>route('familias.index').$compleLinkVisu1,
                'icon'=>'fa fa-map-marked-alt',
                'lg'=>'3',
                'xs'=>'6',
                'color'=>'warning',

        ];
        if(!empty($tags)){
            foreach ($tags as $kt => $vt) {
                if(isset($get['ano'])&&!empty($get['ano'])){
                    $countFamTag =  Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('tags','LIKE','%"'.$vt['id'].'"%')->whereYear('data_exec',$get['ano'])->count();
                }else{

                    $countFamTag =  Event::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('tags','LIKE','%"'.$vt['id'].'"%')->count();
                }

                $cardTags[$vt['id']] =
                [
                    'label'=>$vt['nome'],
                    'obs'=>$vt['obs'],
                    'valor'=>$countFamTag,
                    'href'=>route('familias.index').'?filter[tags][]='.$vt['id'].$compleLinkVisu,
                    'icon'=>$vt['config']['icon'],
                    'lg'=>'3',
                    'xs'=>'6',
                    'color'=>$vt['config']['color'],
                ];
                array_push($ret['cards_home'],$cardTags[$vt['id']]);
            }
        }
        array_push($ret['cards_home'],$cards_homeTodos);

        $anos = Qlib::sql_distinct();
        $ret['anos'] = $anos;
        $ret['totProcesso'] = $totProcesso;
        $ret['config']['acao_massa'] = [
            //['link'=>'#edit_etapa','event'=>'edit_etapa','icon'=>'fa fa-pencil','label'=>'Editar etapa'],
            ['link'=>'#edit_situacao','event'=>'edit_situacao','icon'=>'fa fa-pencil','label'=>'Editar Situação'],
        ];
        //dd($ret);
        return $ret;
    }
    public function listAcessos(Request $request)
    {
        $d = $this->listEventsUser();
        $title = 'Relatório de Acessos';
        $titulo = $title;
        $config = [];
        $ret = [
            'dados'=>$d,
            'title'=>$title.' | '.config('app.name'),
            'titulo'=>$titulo,
            'routa'=>$this->routa,
            'view'=>$this->view,
            'titulo_tabela'=>$title,
            'config'=>$config,
        ];
        return view('relatorios.acessos.index',$ret);
    }
}
