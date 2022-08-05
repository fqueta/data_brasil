<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\wp\ApiWpController;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use stdClass;
use App\Models\Post;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public $post_type;
    public $sec;
    public $i_wp;//integração com wp
    public $wp_api;//integração com wp
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $seg1 = request()->segment(1);
        $type = false;
        if($seg1){
            $type = substr($seg1,0,-1);
        }
        $this->post_type = $type;
        $this->sec = $seg1;
        $this->user = $user;
        $this->routa = $this->sec;
        $this->label = 'Posts';
        $this->view = 'admin.posts';
        $this->i_wp = Qlib::qoption('i_wp');//indegração com Wp s para sim
        //$this->wp_api = new ApiWpController();
        $this->wp_api = false;

    }
    public function queryPost($get=false,$config=false)
    {

        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Post::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];
        if($this->post_type){
            $post =  Post::where('post_status','!=','inherit')->where('post_type','=',$this->post_type)->orderBy('id',$config['order']);
        }else{
            $post =  Post::where('post_status','!=','inherit')->orderBy('id',$config['order']);
        }
        //$post =  DB::table('posts')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $post_totais = new stdClass;
        $campos = isset($_SESSION['campos_posts_exibe']) ? $_SESSION['campos_posts_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $post->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $post->where($key,'LIKE','%'. $value. '%');
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
                $fm = $post;
                if($config['limit']=='todos'){
                    $post = $post->get();
                }else{
                    $post = $post->paginate($config['limit']);
                }
        }else{
            $fm = $post;
            if($config['limit']=='todos'){
                $post = $post->get();
            }else{
                $post = $post->paginate($config['limit']);
            }
        }
        $post_totais->todos = $fm->count();
        $post_totais->esteMes = $fm->whereYear('post_date', '=', $ano)->whereMonth('post_date','=',$mes)->count();
        $post_totais->ativos = $fm->where('post_status','=','publish')->count();
        $post_totais->inativos = $fm->where('post_status','!=','publish')->count();
        $ret['post'] = $post;
        $ret['post_totais'] = $post_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['post_type'] = $this->post_type;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$post_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$post_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$post_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$post_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos($sec=false){
        $sec = $sec?$sec:$this->sec;
        $hidden_editor = '';
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        $ret = [
            'ID'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_date_gmt'=>['label'=>'Data do decreto','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'post_title'=>['label'=>'Nome','active'=>true,'placeholder'=>'Ex.: Nome do decreto','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'9'],
            'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
            //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'post_status'=>['label'=>'Publicar','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Publicado','pending'=>'Pendente']],
            'post_content'=>['label'=>'Conteudo','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
        ];
        return $ret;
    }
    public function index(User $user)
    {
        $this->authorize('is_admin', $user);
        if($this->sec=='posts'){
            $title = 'Cadastro de postagens';
        }elseif($this->sec=='pages'){
            $title = 'Cadastro de paginas';
        }elseif($this->sec=='decretos'){
            $title = 'Cadastro de Decretos';
        }else{
            $title = 'Sem tipo';
        }
        $titulo = $title;
        $queryPost = $this->queryPost($_GET);
        $queryPost['config']['exibe'] = 'html';
        $routa = $this->routa;
        //if(isset($queryPost['post']));
        $ret = [
            'dados'=>$queryPost['post'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryPost['campos'],
            'post_totais'=>$queryPost['post_totais'],
            'titulo_tabela'=>$queryPost['tituloTabela'],
            'arr_titulo'=>$queryPost['arr_titulo'],
            'config'=>$queryPost['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ];
        return view($this->view.'.index',$ret);
    }
    public function create(User $user)
    {
        $this->authorize('is_admin', $user);
        if($this->sec=='posts'){
            $title = 'Cadastro de postagens';
        }elseif($this->sec=='decretos'){
            $title = 'Cadastro de decreto';
        }elseif($this->sec=='pages'){
            $title = 'Cadastro de paginas';
        }
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-posts',
            'route'=>$this->routa,
            'view'=>$this->view,
            'arquivos'=>'jpeg,jpg,png',
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
    public function salvarPostMeta($config = null)
    {
        $post_id = isset($config['post_id'])?$config['post_id']:false;
        $meta_key = isset($config['meta_key'])?$config['meta_key']:false;
        $meta_value = isset($config['meta_value'])?$config['meta_value']:false;
        $ret = false;
        if($post_id&&$meta_key&&$meta_value){
            $verf = Qlib::totalReg('wp_postmeta',"WHERE post_id='$post_id' AND meta_key='$meta_key'");
            if($verf){
                $ret=DB::table('wp_postmeta')->where('post_id',$post_id)->where('meta_key',$meta_key)->update([
                    'meta_value'=>$meta_value,
                ]);
            }else{
                $ret=DB::table('wp_postmeta')->insert([
                    'post_id'=>$post_id,
                    'meta_value'=>$meta_value,
                    'meta_key'=>$meta_key,
                ]);
            }
            //$ret = DB::table('wp_postmeta')->storeOrUpdate();
        }
        return $ret;
    }
    public function store(StorePostRequest $request)
    {
        //$this->authorize('create', $this->routa);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        //$dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';
        $userLogadon = Auth::id();
        $dados['post_author'] = $userLogadon;
        $dados['token'] = !empty($dados['token'])?$dados['token']:uniqid();
        if($this->i_wp=='s' && isset($dados['post_type'])){
            //$endPoint = isset($dados['endPoint'])?$dados['endPoint']:$dados['post_type'].'s';
            $endPoint = 'post';
            $params = $this->geraParmsWp($dados);

            if($params){
                $salvar = $this->wp_api->exec2([
                    'endPoint'=>$endPoint,
                    'method'=>'POST',
                    'params'=>$params
                ]);
                if(isset($salvar['arr']['id']) && $salvar['arr']['id']){
                    $mens = $this->label.' cadastrado com sucesso!';
                    $color = 'success';
                    $idCad = $salvar['arr']['id'];
                }else{
                    $mens = 'Erro ao salvar '.$this->label.'';
                    $color = 'danger';
                    $idCad = 0;
                    if(isset($salvar['arr']['status'])&&$salvar['arr']['status']==400 && isset($salvar['arr']['message']) && !empty($salvar['arr']['message'])){
                        $mens = $salvar['arr']['message'];
                    }
                }
            }else{
                $color = 'danger';
                $mens = 'Parametros invalidos!';
            }
        }else{
            $salvar = Post::create($dados);
            if(isset($salvar->id) && $salvar->id){
                $mens = $this->label.' cadastrado com sucesso!';
                $color = 'success';
                $idCad = $salvar->id;
            }else{
                $mens = 'Erro ao salvar '.$this->label.'';
                $color = 'danger';
                $idCad = 0;
            }
        }
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$mens,
            'color'=>$color,
            'idCad'=>$idCad,
            'exec'=>true,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$idCad;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$idCad]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        $dados = Post::findOrFail($id);
        $this->authorize('ler', $this->routa);
        if(!empty($dados)){
            $title = 'Cadastro da decretos';
            $titulo = $title;
            //dd($dados);
            $dados['ac'] = 'alt';
            if(isset($dados['config'])){
                $dados['config'] = Qlib::lib_json_array($dados['config']);
            }
            $arr_escolaridade = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
            $arr_estadocivil = Qlib::sql_array("SELECT id,nome FROM estadocivils ORDER BY nome ", 'nome', 'id');
            $listFiles = false;
            //$dados['renda_familiar'] = number_format($dados['renda_familiar'],2,',','.');
            $campos = $this->campos();
            if(isset($dados['token'])){
                $listFiles = _upload::where('token_produto','=',$dados['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-familias',
                'route'=>$this->routa,
                'view'=>$this->view,
                'id'=>$id,
            ];
            // if($dados['loteamento']>0){
            //     $bairro = Bairro::find($dados['bairro']);
            //     $dados['matricula'] = isset($bairro['matricula'])?$bairro['matricula']:false;
            // }
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
            $ret = [
                'value'=>$dados,
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
            return view($this->view.'.show',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->routa.'.index',$ret);
        }
    }
    public function geraParmsWp($dados=false)
    {
        $params=false;
        if($dados && is_array($dados)){

            $arr_parm = [
                'post_name'=>'post_name',
                'post_title'=>'post_title',
                'post_content'=>'post_content',
                'post_excerpt'=>'post_excerpt',
                'post_status'=>'post_status',
                'post_type'=>'post_type',
            ];
            foreach ($dados as $kp => $vp) {
                if(isset($arr_parm[$kp])){
                    $params[$kp] = $dados[$kp];
                }
            }
        }
        return $params;
    }

    public function edit($post,User $user)
    {
        $id = $post;
        $dados = Post::where('id',$id)->get();
        $routa = 'posts';
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de posts';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            if(isset($dados[0]['post_date_gmt'])){
                $dExec = explode(' ',$dados[0]['post_date_gmt']);
                if(isset($dExec[0])){
                    $dados[0]['post_date_gmt'] = $dExec[0];
                }
            }
            $listFiles = false;
            $campos = $this->campos();
            if($this->i_wp=='s' && !empty($dados[0]['post_name'])){
                $dadosApi = $this->wp_api->list([
                    'params'=>'/'.$dados[0]['post_name'].'?_type='.$dados[0]['post_type'],
                ]);
                if(isset($dadosApi['arr']['arquivos'])){
                    $listFiles = $dadosApi['arr']['arquivos'];
                }
            }else{
                if(isset($dados[0]['token'])){
                    $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
                }
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-posts',
                'route'=>$this->routa,
                'view'=>$this->view,
                'sec'=>$this->sec,
                'id'=>$id,
                'arquivos'=>'jpeg,jpg,png,pdf,PDF',
            ];
            if(isset($dados[0]['ID']) && $this->i_wp=='s'){
                $imagem_destacada = DB::table('wp_postmeta')->
                where('post_id',$dados[0]['ID'])->
                where('meta_key','imagem_destacada')->get();
                if(isset($imagem_destacada[0])){
                    $dados[0]['imagem_destacada'] = $imagem_destacada[0];
                }
            }

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
            return redirect()->route($routa.'.index',$ret);
        }
    }

    public function update(StorePostRequest $request, $id)
    {
        $this->authorize('update', $this->routa);

        $data = [];
        $mens=false;
        $color=false;
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $d_meta = false;
        if(isset($dados['d_meta'])){
            $d_meta = $dados['d_meta'];
            if(isset($dados['ID'])){
                $d_meta['post_id'] = $dados['ID'];

            }
            unset($dados['d_meta']);
        }
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'){
                /*if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        $data[$key] = Qlib::dtBanco($value);
                    }
                }else{*/
                    $data[$key] = $value;
                //}
            }
        }
        //$data['ativo'] = isset($data['ativo'])?$data['ativo']:'n';
        $userLogadon = Auth::id();
        $data['post_author'] = $userLogadon;
        $data['token'] = !empty($data['token'])?$data['token']:uniqid();
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            if($this->i_wp=='s' && isset($dados['post_type'])){
                $endPoint = 'post/'.$id;
                $arr_parm = [
                    'post_name'=>'post_name',
                    'post_title'=>'post_title',
                    'post_content'=>'post_content',
                    'post_excerpt'=>'post_excerpt',
                    'post_status'=>'post_status',
                    'post_type'=>'post_type',
                ];
                $params = $this->geraParmsWp($dados);
                if($params){
                    $atualizar = $this->wp_api->exec2([
                        'endPoint'=>$endPoint,
                        'method'=>'PUT',
                        'params'=>$params
                    ]);
                    if(isset($atualizar['exec']) && $atualizar['exec']){
                        $mens = $this->label.' cadastrado com sucesso!';
                        $color = 'success';
                        $id = $id;
                    }else{
                        $mens = 'Erro ao salvar '.$this->label.'';
                        $color = 'danger';
                        $id = 0;
                        if(isset($atualizar['arr']['status'])&&$atualizar['arr']['status']==400 && isset($atualizar['arr']['message']) && !empty($atualizar['arr']['message'])){
                            $mens = $atualizar['arr']['message'];
                        }
                    }
                }else{
                    $color = 'danger';
                    $mens = 'Parametros invalidos!';
                }
            }else{
                $atualizar=Post::where('id',$id)->update($data);
                if($atualizar){
                    $mens = $this->label.' cadastrado com sucesso!';
                    $color = 'success';
                    $id = $id;
                }else{
                    $mens = 'Erro ao salvar '.$this->label.'';
                    $color = 'danger';
                    $id = 0;
                }
            }
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>$mens,
                'color'=>$color,
                'idCad'=>$id,
                'return'=>$route,
            ];
            if($atualizar && $d_meta && $this->i_wp=='s'){
                $ret['salvarPostMeta'] = $this->salvarPostMeta($d_meta);
            }

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
        $routa = 'posts';
        if (!$post = Post::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->routa.'.index')]);
            }else{
                $ret = redirect()->route($routa.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }
        $color = 'success';
        $mens = 'Registro deletado com sucesso!';
        if($this->i_wp=='s'){
            $endPoint = 'post/'.$id;
            $delete = $this->wp_api->exec2([
                'endPoint'=>$endPoint,
                'method'=>'DELETE'
            ]);
            if($delete['exec']){
                $mens = 'Registro '.$id.' deletado com sucesso!';
                $color = 'success';
            }else{
                $color = 'danger';
                $mens = 'Erro ao excluir!';
            }
        }else{
            Post::where('id',$id)->delete();
            $mens = 'Registro '.$id.' deletado com sucesso!';
            $color = 'success';
        }
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__($mens),'color'=>$color,'return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>$mens,'color'=>$color]);
        }
        return $ret;
    }
}
