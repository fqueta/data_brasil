<?php

namespace App\Http\Controllers;
use stdClass;
use App\Models\Beneficiario;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBeneficiarioRequest;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;


class BeneficiariosController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'beneficiarios';
        $this->label = 'Beneficiario';
        $this->view = 'padrao';
    }
    public function queryBeneficiario($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        $beneficiario =  Beneficiario::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$beneficiario =  DB::table('beneficiarios')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $beneficiario_totais = new stdClass;
        $campos = isset($_SESSION['campos_beneficiarios_exibe']) ? $_SESSION['campos_beneficiarios_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $beneficiario->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $beneficiario->where($key,'LIKE','%'. $value. '%');
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
                $fm = $beneficiario;
                if($config['limit']=='todos'){
                    $beneficiario = $beneficiario->get();
                }else{
                    $beneficiario = $beneficiario->paginate($config['limit']);
                }
        }else{
            $fm = $beneficiario;
            if($config['limit']=='todos'){
                $beneficiario = $beneficiario->get();
            }else{
                $beneficiario = $beneficiario->paginate($config['limit']);
            }
        }
        $beneficiario_totais->todos = $fm->count();
        $beneficiario_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $beneficiario_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $beneficiario_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['beneficiario'] = $beneficiario;
        $ret['beneficiario_totais'] = $beneficiario_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$beneficiario_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$beneficiario_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$beneficiario_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$beneficiario_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){
        $user = Auth::user();
        $estadocivil = new EstadocivilController($user);
        $escolaridade = new EscolaridadeController($user);
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'tipo'=>[
                'label'=>'tipo de cadastro*',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>[1=>'Proprietário',2=>'Companheiro de proprietário'],'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'12',
                'class'=>'',
                'option_select'=>false,
            ],
            'nome'=>['label'=>'Nome da Beneficiario','active'=>true,'placeholder'=>'Ex.: Cadastrado','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'config[nacionalidade]'=>['label'=>'Nacionalidade','active'=>false,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'config[estado_civil]'=>[
                'label'=>'Estado Civil',
                'active'=>false,
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
            'config[rg]'=>['label'=>'RG','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][rg'],
            'cpf'=>['label'=>'CPF','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'mask-cpf','tam'=>'3'],
            'config[telefone]'=>['label'=>'Telefone','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','cp_busca'=>'config][telefone'],
            'config[nascimento]'=>['label'=>'Nascimento','cp_busca'=>'config][nascimento','active'=>true,'type'=>'date','tam'=>'3','exibe_busca'=>'d-block','event'=>'mask-data'],
            'config[profissao]'=>['label'=>'Profissão','active'=>true,'type'=>'text','tam'=>'3','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][profissao'],
            'config[escolaridade]'=>[
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
            'config[mae]'=>['label'=>'Mãe','cp_busca'=>'config][mae','active'=>true,'type'=>'text','tam'=>'12','exibe_busca'=>'d-block','event'=>''],
            'config[pai]'=>['label'=>'Pai','cp_busca'=>'config][pai','active'=>true,'type'=>'text','tam'=>'12','exibe_busca'=>'d-block','event'=>''],
            'conjuge'=>['label'=>'Cônjuge ou parceiro','active'=>false,'type'=>'html_vinculo','exibe_busca'=>'d-none','event'=>'','tam'=>'12','script'=>'beneficiarios.conjuge',
                'data_selector'=>[
                    'campos'=>$this->campos_parceiro(),
                    'route_index'=>route('beneficiarios.index'),
                    'id_form'=>'frm-conjuge',
                    'action'=>route('beneficiarios.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'value'=>['tipo'=>2],
                    'label'=>'Cônjuge ou parceiro',
                ],
            ],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
        ];
    }
    public function campos_parceiro(){
        $user = Auth::user();
        $estadocivil = new EstadocivilController($user);
        $escolaridade = new EscolaridadeController($user);
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'tipo'=>[
                'label'=>'tipo de cadastro*',
                'active'=>true,
                'type'=>'hidden',
                'event'=>'',
                'tam'=>'12',
                'class'=>'',
                'option_select'=>false,
                'value'=>2,
            ],
            'conjuge'=>[
                'label'=>'cônjuge',
                'active'=>true,
                'type'=>'text',
                'event'=>'',
                'tam'=>'12',
                'class'=>'',
                'option_select'=>false,
                'placeholder'=>'',
                'value'=>'',
            ],
            'nome'=>['label'=>'Nome completo','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>"onclick=lib_carregaConjuge('#frm-conjuge','#frm-beneficiarios')",'tam'=>'12'],
            'config[nacionalidade]'=>['label'=>'Nacionalidade','active'=>false,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>''],
            'config[estado_civil]'=>[
                'label'=>'Estado Civil',
                'active'=>false,
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
            'config[rg]'=>['label'=>'RG','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][rg','placeholder'=>''],
            'cpf'=>['label'=>'CPF','active'=>true,'type'=>'tel','exibe_busca'=>'d-block','event'=>'mask-cpf','tam'=>'3','placeholder'=>''],
            'config[telefone]'=>['label'=>'Telefone','active'=>true,'type'=>'tel','tam'=>'3','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','cp_busca'=>'config][telefone','placeholder'=>''],
            'config[nascimento]'=>['label'=>'Nascimento','cp_busca'=>'config][nascimento','active'=>true,'type'=>'date','tam'=>'3','exibe_busca'=>'d-block','event'=>'mask-data','placeholder'=>''],
            'config[profissao]'=>['label'=>'Profissão','active'=>true,'type'=>'text','tam'=>'3','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][profissao','placeholder'=>''],
            'config[escolaridade]'=>[
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
            'config[mae]'=>['label'=>'Mãe','cp_busca'=>'config][mae','active'=>true,'type'=>'text','tam'=>'12','exibe_busca'=>'d-block','event'=>'','placeholder'=>''],
            'config[pai]'=>['label'=>'Pai','cp_busca'=>'config][pai','active'=>true,'type'=>'text','tam'=>'12','exibe_busca'=>'d-block','event'=>'','placeholder'=>''],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12','placeholder'=>''],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
        ];
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $title = 'Beneficiários Cadastrados';
        $titulo = $title;
        $queryBeneficiario = $this->queryBeneficiario($_GET);
        $queryBeneficiario['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryBeneficiario['beneficiario'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryBeneficiario['campos'],
            'beneficiario_totais'=>$queryBeneficiario['beneficiario_totais'],
            'titulo_tabela'=>$queryBeneficiario['tituloTabela'],
            'arr_titulo'=>$queryBeneficiario['arr_titulo'],
            'config'=>$queryBeneficiario['config'],
            'routa'=>$routa,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar beneficiario';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-beneficiarios',
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
    public function store(StoreBeneficiarioRequest $request)
    {
        /*$validatedData = $request->validate([
            'nome' => ['required','string','unique:beneficiarios'],
        ]);*/

        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Beneficiario::create($dados);
        $route = $this->routa.'.index';
        /** criar vinculo com  **/
        $verificaCriaVinculo = false;
        if($salvar->id)
            $verificaCriaVinculo = $this->verificaCriaVinculo($dados,$salvar->id);

        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'verificaCriaVinculo'=>$verificaCriaVinculo,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }
    public function verificaCriaVinculo($dados = null,$conjuge=false)
    {
        $ret = false;
        if(isset($dados['tipo']) && $dados['tipo'] == 2 && isset($dados['conjuge']) && !empty($dados['conjuge'])){
            $ret = Beneficiario::where('id',$dados['conjuge'])->update([
                'conjuge'=>$conjuge,
            ]);
        }
        return $ret;
    }
    public function show($id)
    {
        //
    }

    public function edit($beneficiario,User $user)
    {
        $id = $beneficiario;
        $dados = Beneficiario::where('id',$id)->get();
        $routa = 'beneficiarios';
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de beneficiarios';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config']) && is_array($dados[0]['config'])){
                foreach ($dados[0]['config'] as $key => $value) {
                    if(is_array($value)){

                    }else{
                        $dados[0]['config['.$key.']'] = $value;
                    }
                }
            }
            $listFiles = false;
            $campos = $this->campos();
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-beneficiarios',
                'route'=>$this->routa,
                'id'=>$id,
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
            return view($this->view.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->view.'.index',$ret);
        }
    }

    public function update(StoreBeneficiarioRequest $request, $id)
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
            $atualizar=Beneficiario::where('id',$id)->update($data);
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
        $routa = 'beneficiarios';
        if (!$post = Beneficiario::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Beneficiario::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
