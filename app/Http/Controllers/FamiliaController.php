<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
use App\Models\_upload;
use App\Rules\FullName;
use App\Rules\RightCpf;

use stdClass;
use App\Http\Requests\StoreFamilyRequest;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\FamiliasExport;
use App\Exports\FamiliasExportView;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Exports\UsersExport;
use App\Models\Bairro;
use App\Models\Etapa;
use App\Models\Tag;
use DataTables;
use Illuminate\Support\Facades\Auth;

class FamiliaController extends Controller
{
    protected $user;
    public $routa;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'familias';
    }
    public function queryFamilias($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        $idUltimaEtapa = Etapa::where('ativo','=','s')->where('excluido','=','n')->where('deletado','=','n')->max('id');
        $tags = Tag::where('ativo','=','s')->where('pai','=','1')->where('excluido','=','n')->where('deletado','=','n')->get();
        $id_pendencia = 3;
        $id_imComRegistro = 4;
        $id_recusas = 5;
        $id_nLocalizado = 6;
        $completos = 0;
        $pendentes = 0;
        $etapas = Etapa::where('ativo','=','s')->where('excluido','=','n')->OrderBy('id','asc')->get();
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        DB::enableQueryLog();
        $familia =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        $countFam =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        //$familia =  DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $familia_totais = new stdClass;
        $campos = isset($_SESSION['campos_familias_exibe']) ? $_SESSION['campos_familias_exibe'] : $this->campos();
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
                            $familia->where($key,'LIKE','%'. $value. '%');
                            if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
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
                $progresso[$key]['total'] = Familia::where('etapa','=',$value['id'])->where('excluido','=','n')->where('deletado','=','n')->count();
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
        //dd($familia[0]['config']);
        /*
        foreach ($familia as $key => $value) {
            if(is_array($value['config'])){
                //$familia[$key]['config'] = Qlib::lib_json_array($value['config']);
                foreach ($familia[$key]['config'] as $k => $val) {
                    if(!is_array($val))
                        $familia[$key]['config['.$k.']'] = $val;
                }
            }
        }*/
        //dd($familia[0]['config']);
        $ret['familia'] = $familia;
        $ret['familia_totais'] = $familia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['progresso'] = $progresso;
        $ret['link_completos'] = route('familias.index').'?filter[etapa]='.$idUltimaEtapa;
        $ret['link_idosos'] = route('familias.index').'?filter[idoso]=s';
        $cardTags = [];
        $ret['cards_home'] = [
            [
                'label'=>'Lotes cadastrados',
                'valor'=>$familia_totais->todos,
                'href'=>route('familias.index'),
                'icon'=>'fa fa-map-marked-alt',
                'lg'=>'2',
                'xs'=>'6',
                'color'=>'info',
            ],
            [
                'label'=>'Cadastros completos',
                'valor'=>$familia_totais->completos,
                'href'=>$ret['link_completos'],
                'icon'=>'fa fa-check',
                'lg'=>'2',
                'xs'=>'6',
                'color'=>'success',
            ],
        ];
        if(!empty($tags)){
            foreach ($tags as $kt => $vt) {
                $countFamTag =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('tags','LIKE','%"'.$vt['id'].'"%')->count();
                $cardTags[$vt['id']] =
                [
                    'label'=>$vt['nome'],
                    'valor'=>$countFamTag,
                    'href'=>route('familias.index').'?filter[tags][]='.$vt['id'],
                    'icon'=>$vt['config']['icon'],
                    'lg'=>'2',
                    'xs'=>'6',
                    'color'=>$vt['config']['color'],
                ];
                array_push($ret['cards_home'],$cardTags[$vt['id']]);
            }
        }

        $ret['config']['acao_massa'] = [
            ['link'=>'#edit_etapa','event'=>'edit_etapa','icon'=>'fa fa-pencil','label'=>'Editar etapa'],
        ];
        return $ret;
    }
    public function colorPorcento($val=0){
        $ret = 'bg-danger';
        if($val<=25){
            $ret = 'bg-danger';
        }elseif($val > 25 && $val <= 50){
            $ret = 'bg-warning';
        }elseif($val > 50 && $val <= 85){
            $ret = 'bg-primary';
        }elseif($val > 85){
            $ret = 'bg-success';
        }
        return $ret;
    }
    public function index(User $user)
    {
        $this->authorize('is_admin', $user);
        $this->authorize('ler', $this->routa);
        $title = 'Famílias Cadastradas';
        $titulo = $title;
        $queryFamilias = $this->queryFamilias($_GET);
        $queryFamilias['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($routa.'.index',[
            'dados'=>$queryFamilias['familia'],
            'familias'=>$queryFamilias['familia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryFamilias['campos'],
            'familia_totais'=>$queryFamilias['familia_totais'],
            'titulo_tabela'=>$queryFamilias['tituloTabela'],
            'arr_titulo'=>$queryFamilias['arr_titulo'],
            'config'=>$queryFamilias['config'],
            'routa'=>$routa,
            'i'=>0,
        ]);
    }
    public function exportAll(User $user)
    {
        $this->authorize('is_admin', $user);
        return Excel::download(new FamiliasExport, 'Familias_'.date('d_m_Y').'.xlsx');
    }
    public function exportFilter(User $user)
    {
        $this->authorize('is_admin', $user);
        $dados = new FamiliasExportView;
        //return $dados->view();
        return Excel::download(new FamiliasExportView, 'Familias_'.date('d_m_Y').'.xlsx');
    }
    public function campos(){
        $user = Auth::user();
        $bairro = new BairroController($user);
        $etapa = new EtapaController($user);
        $escolaridade = new EscolaridadeController($user);
        $estadocivil = new EstadocivilController($user);
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>''],
            'loteamento'=>[
                'label'=>'Bairro ou distrito*',
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
                'tam'=>'6',
                'class'=>'select2'
            ],
            'tipo_residencia'=>[
                'label'=>'tipo de residência*',
                'active'=>true,
                'type'=>'select',
                // 'data_selector'=>[
                //     'campos'=>$bairro->campos(),
                //     'route_index'=>route('bairros.index'),
                //     'id_form'=>'frm-bairros',
                //     'action'=>route('bairros.store'),
                //     'campo_id'=>'id',
                //     'campo_bus'=>'nome',
                //     'label'=>'Bairro',
                // ],
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM tags WHERE ativo='s' AND pai='2'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'class'=>'',
                'option_select'=>false,
            ],
            'endereco'=>['label'=>'Rua','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'10'],
            'numero'=>['label'=>'Número','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'tags[]'=>[
                'label'=>'Situação',
                'active'=>true,
                'type'=>'select_multiple',
                // 'data_selector'=>[
                //     'campos'=>$etapa->campos(),
                //     'route_index'=>route('etapas.index'),
                //     'id_form'=>'frm-etapas',
                //     'action'=>route('etapas.store'),
                //     'campo_id'=>'id',
                //     'campo_bus'=>'nome',
                //     'label'=>'Etapa',
                // ],
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM tags WHERE ativo='s' AND pai='1'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'class'=>'',
                'option_select'=>false,
                'tam'=>'12',
                'cp_busca'=>'tags]['
            ],
            'etapa'=>[
                'label'=>'Etapa de cadastro*',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$etapa->campos(),
                    'route_index'=>route('etapas.index'),
                    'id_form'=>'frm-etapas',
                    'action'=>route('etapas.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Etapa',
                ],'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM etapas WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
            ],
            'area_alvo'=>['label'=>'Área Alvo*','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'2','placeholder'=>''],
            'matricula'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','placeholder'=>''],
            'config[registro]'=>['label'=>'Registro','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','placeholder'=>'','cp_busca'=>'config][registro'],
            'config[livro]'=>['label'=>'Livro','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','placeholder'=>'','cp_busca'=>'config][livro'],
            'quadra'=>['label'=>'Quadra*','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'lote'=>['label'=>'Lote*','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'nome_completo'=>['label'=>'Proprietário','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'cpf'=>['label'=>'CPF proprietário','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'nome_conjuge'=>['label'=>'Nome do Cônjuge','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'cpf_conjuge'=>['label'=>'CPF do Cônjuge','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'telefone'=>['label'=>'Telefone','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);'],
            'config[telefone2]'=>['label'=>'Telefone2','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','cp_busca'=>'config][telefone2'],
            'escolaridade'=>[
                'label'=>'Escolaridade',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$escolaridade->campos(),
                    'route_index'=>route('escolaridades.index'),
                    'id_form'=>'frm-escolaridades',
                    'action'=>route('escolaridades.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Escolaridade',
                ],
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM escolaridades WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'3',
                'class'=>'select2',
            ],
            'estado_civil'=>[
                'label'=>'Estado Civil',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$estadocivil->campos(),
                    'route_index'=>route('estado-civil.index'),
                    'id_form'=>'frm-estado-civil',
                    'action'=>route('estado-civil.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Estado Civil',
                ],
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM estadocivils WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'3',
                'class'=>'select2',
            ],
            'situacao_profissional'=>['label'=>'Situação Profissional','type'=>'text','active'=>true,'exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
            'bcp_bolsa_familia'=>['label'=>'BPC ou Bolsa Família','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
            'renda_familiar'=>['label'=>'Renda Fam.','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2','class'=>'moeda'],
            'qtd_membros'=>['label'=>'Membros','active'=>true,'type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'membros'=>['label'=>'lista de Membros','active'=>false,'type'=>'html','exibe_busca'=>'d-none','event'=>'','tam'=>'12','script'=>'familias.lista_membros'],
            'idoso'=>['label'=>'Idoso','active'=>true,'type'=>'chave_checkbox','value'=>'s','exibe_busca'=>'d-none','event'=>'','tam'=>'6','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'crianca_adolescente'=>['label'=>'Criança e Adolescente','active'=>true,'exibe_busca'=>'d-none','event'=>'','type'=>'chave_checkbox','value'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'6','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'doc_imovel'=>['label'=>'Doc Imóvel','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'obs'=>['label'=>'Observação','active'=>true,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','rows'=>'4','cols'=>'80','tam'=>'12'],
        ];
    }
    public function camposJson(User $user)
    {
        return response()->json($this->campos());
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar Lote';
        $titulo = $title;
        //$Users = Users::all();
        //$roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        $familia = ['ac'=>'cad','token'=>uniqid()];
        $arr_escolaridade = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
        $arr_estadocivil = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-familias',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
            'matricula'=>false,
        ];
        if(!$value['matricula'])
            $config['display_matricula'] = 'd-none';
        $campos = $this->campos();
        return view($this->routa.'.createedit',[
            'config'=>$config,
            'title'=>$title,
            'titulo'=>$titulo,
            'arr_escolaridade'=>$arr_escolaridade,
            'arr_estadocivil'=>$arr_estadocivil,
            'campos'=>$campos,
            'value'=>$value,
            'routa'=>$this->routa,
        ]);
    }

    public function store(StoreFamilyRequest $request)
    {
        $this->authorize('create', $this->routa);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        /*
        if (isset($dados['image']) && $dados['image']->isValid()){
            $nameFile = Str::of($dados['name'])->slug('-').'.'.$dados['image']->getClientOriginalExtension();
            $image = $dados['image']->storeAs('users',$nameFile);
            $dados['image'] = $image;
        }*/
        $userLogadon = Auth::id();
        $arr_camposArr = ['membros'];
        foreach ($arr_camposArr as $key => $value) {
            if(isset($dados[$value])){
                $dados[$value] = Qlib::lib_array_json($dados[$value]);
            }
        }
        $dados['idoso'] = isset($dados['idoso'])?$dados['idoso']:'n';
        $dados['crianca_adolescente'] = isset($dados['crianca_adolescente'])?$dados['crianca_adolescente']:'n';
        $dados['renda_familiar'] = $dados['renda_familiar']?$dados['renda_familiar']:'0,00';
        $dados['autor'] = $userLogadon;
        $dados['token'] = uniqid();
        $renda_familiar = str_replace('R$','',$dados['renda_familiar']);
        $dados['renda_familiar'] = Qlib::precoBanco($renda_familiar);
        $salvar = Familia::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>'Salvo com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show(Familia $familia)
    {
        //
    }

    public function edit($id,User $user)
    {
        $dados = Familia::where('id',$id)->get();
        //$roles = DB::select("SELECT * FROM roles ORDER BY id ASC");
        //$permissions = DB::select("SELECT * FROM permissions ORDER BY id ASC");
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de família';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $arr_escolaridade = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
            $arr_estadocivil = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
            $listFiles = false;
            $dados[0]['renda_familiar'] = number_format($dados[0]['renda_familiar'],2,',','.');
            $campos = $this->campos();
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-familias',
                'route'=>$this->routa,
                'id'=>$id,
            ];
            if($dados[0]['loteamento']>0){
                $bairro = Bairro::find($dados[0]['loteamento']);
                $dados[0]['matricula'] = $bairro['matricula'];
                //dd($dados[0]['matricula']);
            }
            if(!$dados[0]['matricula'])
                $config['display_matricula'] = 'd-none';
            if(isset($dados[0]['config']) && is_array($dados[0]['config'])){
                foreach ($dados[0]['config'] as $key => $value) {
                    if(is_array($value)){

                    }else{
                        $dados[0]['config['.$key.']'] = $value;
                    }
                }
            }
            //$dados[0]['tags'] = Qlib::lib_json_array($dados[0]['tags']);
            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'arr_escolaridade'=>$arr_escolaridade,
                'arr_estadocivil'=>$arr_estadocivil,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'routa'=>$this->routa,
                'exec'=>true,
            ];
            return view($this->routa.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->routa.'.index',$ret);
        }
    }
    public function update(StoreFamilyRequest $request, $id)
    {
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
                }elseif($key == 'renda_familiar') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                    //$data[$key] = number_format($value,2,'.','');
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['idoso'] = isset($data['idoso'])?$data['idoso']:'n';
        $data['crianca_adolescente'] = isset($data['crianca_adolescente'])?:'n';
        $data['config']['atualizado_por'] = $userLogadon;
        $data['tags'] = isset($data['tags'])?$data['tags']:false;
        if(!empty($data)){
            //dd($data);
            $atualizar=Familia::where('id',$id)->update($data);
            $route = 'familias.index';
            $ret = [
                'exec'=>true,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = 'familias.edit';
            $ret = [
                'exec'=>false,
                'id'=>$id,
                'mens'=>'Erro ao receber dados',
                'color'=>'danger',
            ];
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
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        if (!$post = Familia::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route('familias.index')]);
            }else{
                $ret = redirect()->route('familias.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Familia::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route('familias.index')]);
        }else{
            $ret = redirect()->route('familias.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
    public function ajaxPost(Request $request){
        $post = $request->all();
        $ret['exec'] = false;
        $ret['mens'] = 'Opção inválida';
        $ret['color'] = 'danger';
        if(!isset($post['opc'])){
            return response()->json($ret);
        }
        $ret['atualiza'] = false;
        if($post['opc']=='salvar_etapa_massa'){
            if(isset($post['ids']) && isset($post['etapa'])){
                $dEtapa = Etapa::find($post['etapa']);
                $ret['etapa'] = $dEtapa['nome'];
                $arr_ids = explode('_',$post['ids']);
                if(is_array($arr_ids)){
                    foreach ($arr_ids as $k => $v) {
                        if($v){
                            $ds = [
                                'etapa'=>$post['etapa'],
                            ];
                            $ret['atualiza'][$v] = Familia::where('id',$v)->update($ds);
                        }
                    }
                }
                $ret['ids']= $arr_ids;
                if($ret['atualiza']){
                    $ret['exec'] = true;
                    $ret['mens'] = 'Cadastro(s) Atualizado(s) com sucesso!';
                    $ret['color'] = 'success';
                }
            }
        }
        return response()->json($ret);
    }
}
