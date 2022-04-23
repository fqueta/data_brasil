<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoteRequest;
use stdClass;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Beneficiario;
use App\Models\Documento;
use App\Models\Familia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LotesController extends Controller
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
        $this->routa = 'lotes';
        $this->label = 'Lote';
        $this->view = 'padrao';
        $this->tab = $this->routa;
    }
    public function queryLote($get=false,$config=false)
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

        if(isset($get['term'])){
            //Autocomplete
            if(isset($get['bairro']) && !empty($get['bairro'])){
                $sql = "SELECT l.*,q.nome quadra_valor,b.nome nome_bairro,b.matricula FROM lotes as l
                JOIN quadras as q ON q.id=l.quadra
                JOIN bairros as b ON b.id=q.id
                WHERE (l.nome LIKE '%".$get['term']."%' OR q.nome LIKE '%".$get['term']."%' ) AND (l.bairro='".$get['bairro']."') AND l.excluido !='s' AND l.deletado
                ";
            }else{
                //$lote =  Lote::join('quadras','quadras.id','=','lotes.quadra')->where('lotes.excluido','=','n')->where('lotes.deletado','=','n')->orderBy('id',$config['order'])->select('lotes.nome');
                $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
                JOIN quadras as q ON q.id=l.quadra
                WHERE (l.nome LIKE '%".$get['term']."%' OR q.nome LIKE '%".$get['term']."%' ) AND l.excluido !='s' AND l.deletado
                ";
            }
            $lote = DB::select($sql);
            $ret['lote'] = $lote;
            return $ret;
        }else{
            $lote =  Lote::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        }

        //$lote =  DB::table('lotes')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $lote_totais = new stdClass;
        $campos = isset($_SESSION['campos_lotes_exibe']) ? $_SESSION['campos_lotes_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;

        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $lote->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            if(is_array($value)){
                                // dd($value);
                            }else{
                                $lote->where($key,'LIKE','%'. $value. '%');
                                if($campos[$key]['type']=='select'){
                                    $value = $campos[$key]['arr_opc'][$value];
                                }
                                $arr_titulo[$campos[$key]['label']] = $value;
                                $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            }
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $lote;
                if($config['limit']=='todos'){
                    $lote = $lote->get();
                }else{
                    $lote = $lote->paginate($config['limit']);
                }
        }else{
            $fm = $lote;
            if($config['limit']=='todos'){
                $lote = $lote->get();
            }else{
                $lote = $lote->paginate($config['limit']);
            }
        }
        $lote_totais->todos = $fm->count();
        $lote_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $lote_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $lote_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['lote'] = $lote;
        $ret['lote_totais'] = $lote_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$lote_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$lote_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$lote_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$lote_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){
        $user = Auth::user();
        $quadra = new QuadrasController($user);
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            //'bairro'=>['label'=>'Bairro','active'=>false,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'quadra'=>[
                'label'=>'Quadra',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$quadra->campos(),
                    'route_index'=>route('quadras.index'),
                    'id_form'=>'frm-quadras',
                    'action'=>route('quadras.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Quadra',
                ],'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'5',
                'class'=>'select2'
            ],
            'nome'=>['label'=>'N. do Lote','active'=>true,'placeholder'=>'Ex.: 14','type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'7'],
            //'config[complemento]'=>['label'=>'Complemento','active'=>true,'placeholder'=>'Ex.: A','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][complemento'],
            'cep'=>['label'=>'CEP','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'mask-cep onchange=buscaCep1_0(this.value)','tam'=>'3'],
            'endereco'=>['label'=>'Endereço','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'7'],
            'numero'=>['label'=>'Numero','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'complemento'=>['label'=>'Complemento','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
            'cidade'=>['label'=>'Cidade','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'config[uf]'=>['label'=>'UF','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'2','cp_busca'=>'config][uf'],
            'config[valor_lote]'=>['label'=>'Valor do Lote','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'moeda','cp_busca'=>'config][valor_lote'],
            'config[valor_edificacao]'=>['label'=>'Valor da Edificação','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'moeda','cp_busca'=>'config][valor_edificacao'],
            'config[area_lote]'=>['label'=>'Área quadrada(m²)','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'','cp_busca'=>'config][area_lote'],
            'config[area_construcao]'=>['label'=>'Área construção(m²)','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'','cp_busca'=>'config][area_construcao'],
            //'config[registro]'=>['label'=>'Registro','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][registro'],
            //'config[livro]'=>['label'=>'Livro','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][livro'],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
        ];
    }
    public function index(User $user)
    {
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:'n';
        $this->authorize('ler', $this->routa);
        $title = 'Lotes Cadastrados';
        $titulo = $title;
        $queryLote = $this->queryLote($_GET);
        $queryLote['config']['exibe'] = 'html';
        $routa = $this->routa;
        if(isset($_GET['term'])){
            $ret = false;
            $ajax = 's';
            $campos = $this->campos();
            if($queryLote['lote']){
               //$ret = $queryLote['lote'];
                foreach ($queryLote['lote'] as $key => $v) {
                    $ret[$key]['value'] = ' Lote: '.$v->nome.' | quadra: '.$v->quadra_valor;
                    //$ret[$key]['id'] = $v['id'];
                    $ret[$key]['dados'] = $v;
                    //if(id_array($v))
                }
            }
        }else{
            $ret = [
                'dados'=>$queryLote['lote'],
                'title'=>$title,
                'titulo'=>$titulo,
                'campos_tabela'=>$queryLote['campos'],
                'lote_totais'=>$queryLote['lote_totais'],
                'titulo_tabela'=>$queryLote['tituloTabela'],
                'arr_titulo'=>$queryLote['arr_titulo'],
                'config'=>$queryLote['config'],
                'routa'=>$routa,
                'view'=>$this->view,
                'i'=>0,
            ];
        }
        if($ajax=='s'){
            return response()->json($ret);
        }else{
            return view($this->view.'.index',$ret);
        }
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar lote';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-lotes',
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
    public function store(StoreLoteRequest $request)
    {
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';
        $dados['token'] = isset($dados['token'])?$dados['token']:uniqid();
        if (isset($dados['quadra']) && !empty($dados['quadra'])) {
            /**adicionar o bairro ao lote */
            $dados['bairro'] = Qlib::buscaValorDb([
                'tab'=>'quadras',
                'campo_bus'=>'id',
                'valor'=>$dados['quadra'],
                'select'=>'bairro',
            ]);
        }
        $salvar = Lote::create($dados);
        $dados['id'] = $salvar->id;

        $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
            JOIN quadras as q ON q.id=l.quadra
            WHERE l.id = '".$dados['id']."' AND l.excluido !='s' AND l.deletado
            ";
        $dadosAtualizados = Qlib::dados_tab($this->tab,[
            'sql'=>$sql,
            'id'=>$dados['id'],
        ]);
        if(!$sql){
            $d = $dadosAtualizados;
        }else{
            $d = $dadosAtualizados[0];
        }
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$d,
        ];
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
        //
    }

    public function edit($lote,User $user)
    {
        $id = $lote;
        $dados = Lote::where('id',$id)->get();
        $routa = 'lotes';
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de lotes';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $listFiles = false;
            $campos = $this->campos();
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-lotes',
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
            if (isset($data['quadra']) && !empty($data['quadra'])) {
                /**adicionar o bairro ao lote */
                $data['bairro'] = Qlib::buscaValorDb([
                    'tab'=>'quadras',
                    'campo_bus'=>'id',
                    'valor'=>$data['quadra'],
                    'select'=>'bairro',
                ]);
            }
            $atualizar=Lote::where('id',$id)->update($data);
            $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
            JOIN quadras as q ON q.id=l.quadra
            WHERE l.id = '$id' AND l.excluido !='s' AND l.deletado
            ";
            $dadosAtualizados = Qlib::dados_tab($this->tab,[
                'sql'=>$sql,
                'id'=>$id
            ]);
            if(!$sql){
                $d = $dadosAtualizados;
            }else{
                $d = $dadosAtualizados[0];
            }
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
                'dados'=>@$d,
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
        $routa = 'lotes';
        $user = Auth::user();
        $reg_excluido = ['data'=>date('d-m-Y'),'autor'=>$user->id];
        if (!$post = Lote::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Lote::where('id',$id)->update(['excluido'=>'s','reg_excluido'=>Qlib::lib_array_json($reg_excluido)]);
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
    public function docBeneficiario($id_lote = false,$config=false)
    {
        $ret = false;
        if($id_lote){
            //$familia = Familia::where('loteamento','LIKE','%"'.$id_lote.'"%')->get();
            /*$sql = "SELECT f.*,b.* FROM familias As f
            JOIN beneficiarios b ON f.id_beneficiario=b.id
            WHERE f.loteamento LIKE '%\"$id_lote\"%' AND f.excluido='n' AND f.deletado='n'
            ";*/
            $sql = "SELECT f.* FROM familias As f
            WHERE f.loteamento LIKE '%\"$id_lote\"%' AND f.excluido='n' AND f.deletado='n' ORDER BY f.id ASC
            ";
            $familia = Qlib::dados_tab('familias',['sql'=>$sql]);
            $dLote = Lote::FindOrFail($id_lote);
            //dd($familia);
            if($familia && $dLote){
                $tema = Documento::where('url','lista-beneficiario')->where('excluido','n')->where('deletado','n')->get();
                $tema2 = Documento::where('url','lista-beneficiario-2')->where('excluido','n')->where('deletado','n')->get();
                $tema3 = Documento::where('url','lista-beneficiario-3')->where('excluido','n')->where('deletado','n')->get();
                $doc = false;
                $tm1 = $tema[0]->conteudo;
                $tm2 = $tema2[0]->conteudo;
                $tm3 = $tema3[0]->conteudo;
                $bairro = Qlib::buscaValorDb([
                    'tab'=>'bairros',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['bairro'],
                    'select'=>'nome',
                ]);
                $quadra = Qlib::buscaValorDb([
                    'tab'=>'quadras',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['quadra'],
                    'select'=>'nome',
                ]);
                $arr_sh = [];
                $tot_familias = count($familia);
                $n_benficiario = false;
                $i=0;
                foreach ($familia as $key => $fm) {
                    if(isset($tema[0]->conteudo) && $dLote && ($fm['id_beneficiario']>0)){
                        $i++;
                        $dadosBen = Beneficiario::FindOrFail($fm['id_beneficiario']);
                        $dadosCon = false;
                        if($fm['id_conjuge']>0){
                            $dadosCon = Beneficiario::FindOrFail($fm['id_conjuge']);
                        }
                        if($b = $dadosBen){
                            //Qlib::lib_print($b);
                            //Qlib::lib_print($dLote);
                            $lote = $dLote['nome'];
                            $tipo_beneficiario = 'REURB (S)';
                            $nome_beneficiario = $b['nome'];
                            $filhoa_de = 'filho de';
                            $nascidoa = 'nascido';
                            if($b['sexo']=='f'){
                                $filhoa_de = 'filha de';
                                $nascidoa = 'nascida';
                            }
                            if($tot_familias>1){
                                $n_benficiario = "<b>".$i.")</b> ";
                            }
                            $arr_sh = [
                                'lote'=>['lab'=>'Tipo','v'=>$lote],
                                'quadra'=>['lab'=>'Tipo','v'=>$quadra],
                                'lote_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words(Qlib::limpar_texto($lote))],
                                'quadra_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words($quadra)],
                                'tipo_beneficiario'=>['lab'=>'Tipo','v'=>$tipo_beneficiario],
                                'nome_beneficiario'=>['lab'=>'Nome','v'=>$n_benficiario.$nome_beneficiario],
                                'cpf'=>['lab'=>'CPF','v'=>$b['cpf']],
                                'endereco'=>['lab'=>'Endereço','v'=>$dLote['endereco']],
                                'numero'=>['lab'=>'numero','v'=>$dLote['numero']],
                                'cidade'=>['lab'=>'cidade','v'=>$dLote['cidade']],
                                'cep'=>['lab'=>'cep','v'=>$dLote['cep']],
                                'bairro'=>['lab'=>'bairro','v'=>$bairro],
                                'area'=>['lab'=>'bairro','v'=>$bairro],
                                'filha de'=>['lab'=>'','v'=>$filhoa_de],
                                'filho de'=>['lab'=>'','v'=>$filhoa_de],
                                'nascida'=>['lab'=>'','v'=>$nascidoa],
                                'nascido'=>['lab'=>'','v'=>$nascidoa],
                            ];
                            if($dadosCon){
                                $doc .= str_replace('{lote}',$lote,$tm2);
                                $doc = $this->docConjuge($dadosCon,$doc,$b);
                            }else{
                                $doc .= str_replace('{lote}',$lote,$tm3);
                            }
                            foreach ($arr_sh as $ks => $vs) {
                                $doc = str_replace('{'.$ks.'}',$vs['v'],$doc);
                            }
                            //$doc = str_replace('{nome_beneficiario}',$nome_beneficiario,$doc);
                            if(is_array($b['config'])){
                                foreach ($b['config'] as $kc => $vc) {
                                    if($kc=='escolaridade'||$kc=='estado_civil'){
                                        if($kc=='escolaridade'){
                                            $ta = 'escolaridades';
                                        }
                                        if($kc=='estado_civil'){
                                            $ta = 'estadocivils';
                                        }
                                        $vc=Qlib::buscaValorDb([
                                            'tab'=>$ta,
                                            'campo_bus'=>'id',
                                            'valor'=>$vc,
                                            'select'=>'nome',
                                        ]);
                                    }
                                    if($kc=='nascimento')
                                        $vc = Qlib::dataExibe($vc);
                                    $doc = str_replace('{'.$kc.'}',$vc,$doc);
                                }
                            }
                        }else{
                            $doc .= 'Beneficiário ('.$fm['id_beneficiario'].') não foi encontrado!';
                        }
                    }
                }
                $ret = str_replace('{dados_beneficiario}',$doc,$tm1);
                foreach ($arr_sh as $ks => $vs) {
                    $ret = str_replace('{'.$ks.'}',$vs['v'],$ret);
                }
                if(is_array($dLote['config'])){
                    foreach ($dLote['config'] as $kl => $vl) {
                        $ret = str_replace('{'.$kl.'}',$vl,$ret);
                    }
                }
            }
        }
        return $ret;
    }
    public function docConjuge($dadosDeste = null,$tema=false,$dadosConjuge=false)
    {
        $ret = $tema?$tema:false;
        if($dadosDeste && $dadosConjuge){
            $dadCo = $dadosConjuge;
            $dc = $dadosDeste;
            if($dc['sexo']=='f'){
                if(isset($dadCo['config']['estado_civil']) && $dadCo['config']['estado_civil']==2){
                    $seu_companheiro = 'sua esposa';
                }else{
                    $seu_companheiro = 'sua companheira';
                }
            }else{
                if(isset($dadCo['config']['estado_civil']) && $dadCo['config']['estado_civil']==2){
                    $seu_companheiro = 'seu marido';
                }else{
                    $seu_companheiro = 'seu companheiro';
                }
            }
            $arr_sh = [
                'nome'=>['lab'=>'Nome','v'=>$dc['nome']],
                'cpf'=>['lab'=>'Nome','v'=>$dc['cpf']],
                //'seu companheiro'=>['lab'=>'Nome','v'=>$seu_companheiro],
            ];
            $ret = str_replace('{seu companheiro}',$seu_companheiro,$ret);
            foreach ($arr_sh as $ks => $vs) {
                $ret = str_replace('{'.$ks.'_conjuge}',$vs['v'],$ret);
            }
            if(is_array($dc['config'])){
                if(empty(@$dc['config']['data_uniao'])){
                    $dc['config']['data_uniao']=$dadCo['config']['data_uniao'];
                }
                foreach ($dc['config'] as $kco => $vco) {
                    if($kco=='escolaridade'||$kco=='estado_civil'){
                        if($kco=='escolaridade'){
                            $ta = 'escolaridades';
                        }
                        if($kco=='estado_civil'){
                            $ta = 'estadocivils';
                        }
                        $vco=Qlib::buscaValorDb([
                            'tab'=>$ta,
                            'campo_bus'=>'id',
                            'valor'=>$vco,
                            'select'=>'nome',
                        ]);
                    }
                    if($kco=='nascimento')
                    $vco = Qlib::dataExibe($vco);
                    $ret = str_replace('{'.$kco.'_conjuge}',$vco,$ret);
                }
            }
        }
        return $ret;
    }
}
