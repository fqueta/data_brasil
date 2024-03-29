<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\EventController;
use App\Http\Requests\StoreQuadraRequest;
use Illuminate\Http\Request;
use stdClass;
use App\Models\Quadra;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Lote;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuadrasController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public $tab;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'quadras';
        $this->tab = 'quadras';
        $this->label = 'Quadra';
        $this->view = 'padrao';
    }
    public function queryQuadra($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
            'campo_order'=>isset($get['campo_order']) ? $get['campo_order']: 'id',
        ];

        $quadra =  Quadra::where('excluido','=','n')->where('deletado','=','n')->orderBy($config['campo_order'],$config['order']);
        //$quadra =  DB::table('quadras')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $quadra_totais = new stdClass;
        $campos = isset($_SESSION['campos_quadras_exibe']) ? $_SESSION['campos_quadras_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $quadra->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $quadra->where($key,'LIKE','%'. $value. '%');
                            if($campos[$key]['type']=='select'){
                                $value = $campos[$key]['arr_opc'][$value];
                            }
                            $arr_titulo[$campos[$key]['label']] = $value;
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $quadra;
                if($config['limit']=='todos'){
                    $quadra = $quadra->get();
                }else{
                    $quadra = $quadra->paginate($config['limit']);
                }
        }else{
            $fm = $quadra;
            if($config['limit']=='todos'){
                $quadra = $quadra->get();
            }else{
                $quadra = $quadra->paginate($config['limit']);
            }
        }
        $quadra_totais->todos = $fm->count();
        $quadra_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $quadra_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $quadra_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['quadra'] = $quadra;
        $ret['quadra_totais'] = $quadra_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$quadra_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$quadra_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$quadra_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$quadra_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos($id=false,$data=false){
        $user = Auth::user();
        $bairro = new BairroController($user);
        $arr_lotes = false;
        $data=false;
        if($id){
            // $data = Quadra::Find($id);
            $arr_lotes = (new QuadrasController($user))->lotes($id);
        }
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'matricula'=>['label'=>'Matricula','value'=>'','active'=>false,'type'=>'hidden_text','exibe_busca'=>'d-none','event'=>'','tam'=>'12'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'bairro'=>[
                'label'=>'Área*',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$bairro->campos(),
                    'route_index'=>route('bairros.index'),
                    'id_form'=>'frm-bairros',
                    'action'=>route('bairros.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Bairro',
                ],'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'onchange=carregaMatricula(this.value)',
                'tam'=>'12',
                'class'=>'select2'
            ],
            'nome'=>['label'=>'Numero da Quadra','active'=>true,'placeholder'=>'Ex.: 14','type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'12','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'lotes'=>[
                'label'=>__('Listagem de lotes'),
                'type'=>'html',
                'active'=>false,
                'script'=>'admin.processos.lotes',
                'script_show'=>'admin.processos.lotes',
                'dados'=>$arr_lotes,
            ]
        ];
    }

    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:'n';
        $title = 'Quadras Cadastradas';
        $titulo = $title;
        $queryQuadra = $this->queryQuadra($_GET);
        $queryQuadra['config']['exibe'] = 'html';
        $routa = $this->routa;
        (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);

        $ret = [
            'dados'=>$queryQuadra['quadra'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryQuadra['campos'],
            'quadra_totais'=>$queryQuadra['quadra_totais'],
            'titulo_tabela'=>$queryQuadra['tituloTabela'],
            'arr_titulo'=>$queryQuadra['arr_titulo'],
            'config'=>$queryQuadra['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ];
        if($ajax=='s'){
            return response()->json($ret);
        }else{
            return view($this->view.'.index',$ret);
        }
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar quadra';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-quadras',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];
        $campos = $this->campos();
        return view($this->view.'.createedit',[
            'config'=>$config,
            'title'=>$title,
            'titulo'=>$titulo,
            'campos'=>$campos,
            'value'=>$value,
        ]);
    }
    public function store(StoreQuadraRequest $request)
    {
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        $salvar = Quadra::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$dados
        ];
        //REGISTRAR EVENTOS
        (new EventController)->listarEvent(['tab'=>$this->tab,'id'=>$salvar->id,'this'=>$this]);

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        $dados = Quadra::findOrFail($id);
        $this->authorize('ler', $this->routa);
        if(!empty($dados)){
            $title = __('Visualização de quadras');
            $titulo = $title;
            $dados['ac'] = 'alt';
            if(isset($dados['config'])){
                $dados['config'] = Qlib::lib_json_array($dados['config']);
            }
            $listFiles = false;
            $campos = $this->campos($id);
            if(isset($dados['token'])){
                $listFiles = _upload::where('token_produto','=',$dados['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-quadras',
                'route'=>$this->routa,
                'id'=>$id,
                'class_card1'=>'col-md-8',
                'class_card2'=>'col-md-4',
            ];
            if(!$dados['matricula'])
                $config['display_matricula'] = 'd-none';
            if(isset($dados['config']) && is_array($dados['config'])){
                foreach ($dados['config'] as $key => $value) {
                    if(is_array($value)){

                    }else{
                        $dados['config['.$key.']'] = $value;
                    }
                }
            }
            $subdomain = Qlib::get_subdominio();
            if(Gate::allows('is_admin2', [$this->routa]) && $subdomain !='cmd'){
                $config['eventos'] = (new EventController)->listEventsPost(['post_id'=>$id]);
            }else{
                $config['class_card1'] = 'col-md-12';
                $config['class_card2'] = 'd-none';
            }
            $config['mapas']=(new MapasController(Auth::user()))->queryQuadras($id,'n');
            $ret = [
                'value'=>$dados,
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'routa'=>$this->routa,
                // 'eventos'=>(new EventController)->listEventsPost(['post_id'=>$id]),
                'exec'=>true,
            ];
            //REGISTRAR EVENTOS
            (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
            return view('quadras.show',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->routa.'.index',$ret);
        }
    }

    public function edit($quadra,User $user)
    {
        $id = $quadra;
        $dados = Quadra::where('id',$id)->get();
        $routa = 'quadras';
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:false;

        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de quadras';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            if(isset($dados[0]['bairro'])){
                $dados[0]['bairro_nome'] = Qlib::buscaValorDb([
                    'tab'=>'bairros',
                    'campo_bus'=>'id',
                    'valor'=>$dados[0]['bairro'],
                    'select'=>'nome',
                    'compleSql'=>'',
                    'debug'=>false,
                ]);
            }
            $listFiles = false;
            $campos = $this->campos($id);
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-quadras',
                'route'=>$this->routa,
                'id'=>$id,
                'arquivos'=>'html,svg,pdf,jpeg,jpg',
                'typeN'=>'2', //salvar o nome verdadeiro do arquivo
            ];

            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'exec'=>true,
            ];

            if($ajax=='s'){
                return response()->json($ret);
            }else{
                return view($this->view.'.createedit',$ret);
            }
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->view.'.index',$ret);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => ['required'],
        ]);
        $data = [];
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'){
                if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        $data[$key] = Qlib::dtBanco($value);
                    }
                }elseif($key == 'token') {
                    if(!$value)
                    $data[$key] = uniqid();
                }elseif($key == 'renda_familiar') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['ativo'] = isset($data['ativo'])?$data['ativo']:'n';
        $data['autor'] = $userLogadon;
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Quadra::where('id',$id)->update($data);
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = $this->routa.'.edit';
            $ret = [
                'exec'=>false,
                'id'=>$id,
                'mens'=>'Erro ao receber dados',
                'color'=>'danger',
            ];
        }
        if($atualizar){
            //REGISTRAR EVENTOS
            (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
        }
        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$id;
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function destroy($id,Request $request)
    {
        $this->authorize('delete', $this->routa);
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'quadras';
        if (!$post = Quadra::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            //REGISTRAR EVENTOS
            (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
            return $ret;
        }

        Quadra::where('id',$id)->delete();
        //REGISTRAR EVENTOS
        (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);

        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
    /**
     * exibe lotes de uma quadra ou de várias quadras.
     * @ param string ou array
     */
    public function lotes($quadras=false){
        //uso com parametro array $lotes = (new QuadrasController($user))->lotes([3,2,4]);
        //uso com parametro string $lotes = (new QuadrasController($user))->lotes(4);
        $ret['exec'] = false;
        $ret['data_lotes'] = [];
        $ret['total_lotes_quadras'] = [];
        $ret['total_lotes'] = 0;
        $ret['nome_quadra'] = [];
        if($quadras){
            if(is_array($quadras)){
                foreach ($quadras as $v) {
                    $lotes = Lote::where('quadra','=', $v)->get();
                    $ret['nome_quadra'][$v] = Qlib::buscaValorDb(['tab'=>'quadras','campo_bus'=>'id','valor'=>$v,'select'=>'nome']);
                    if($lotes->count() > 0){
                        $ret['exec'] = true;
                        $ret['total_lotes'] += $lotes->count();
                        foreach ($lotes as $kl => $vl) {
                            $ret['data_lotes'][$v][$kl] = $vl;
                            $ret['total_lotes_quadras'][$v] = $lotes->count();
                        }
                    }else{
                        $ret['total_lotes_quadras'][$v] = 0;

                    }
                }
            }else{
                $lotes = Lote::where('quadra','=', $quadras)->get();
                if($lotes->count() > 0){
                    $ret['exec'] = true;
                    $ret['total_lotes'] += $lotes->count();
                    foreach ($lotes as $kl => $vl) {
                        $ret['data_lotes'][$quadras][$kl] = $vl;
                        $ret['total_lotes_quadras'][$quadras] = $lotes->count();
                        $ret['nome_quadra'][$quadras] = Qlib::buscaValorDb(['tab'=>'quadras','campo_bus'=>'id','valor'=>$quadras,'select'=>'nome']);

                    }
                }else{
                    $ret['total_lotes_quadras'][$quadras] = 0;
                }
            }
        }
        return $ret;
    }
}
