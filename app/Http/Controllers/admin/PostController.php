<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BairroController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\QuadrasController;
use App\Http\Controllers\wp\ApiWpController;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use stdClass;
use App\Models\Post;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

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
        // $this->post_type = $type;
        $this->post_type = trim($seg1);
        $this->sec = $seg1;
        $this->user = $user;
        $this->routa = $this->sec;
        $this->label = 'Posts';
        $this->tab = 'posts';
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
            if(isset($get['ano']) && !empty($get['ano'])){
                if(isset($get['filter']['post_type'])){
                    unset($get['filter']['post_type']);
                }
                if($this->post_type=='processos'){
                    $post =  Post::where('post_status','!=','inherit')->where('comment_count','LIKE',$get['ano'])->where('post_type','LIKE','%'.$this->post_type.'%')->orderBy('id',$config['order']);
                }else{
                    $post =  Post::where('post_status','!=','inherit')->where('comment_count','LIKE',$get['ano'])->where('post_type','=',$this->post_type)->orderBy('id',$config['order']);
                }
            }else{
                if($this->post_type=='processos'){
                    $post =  Post::where('post_status','!=','inherit')->where('post_type','LIKE','%'.$this->post_type.'%')->orderBy('id',$config['order']);
                }else{
                    $post =  Post::where('post_status','!=','inherit')->where('post_type','=',$this->post_type)->orderBy('id',$config['order']);
                }
            }
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
                if(isset($get['filter']['post_status'])){
                    $get['filter']['post_status'] = 'publish';
                }else{
                    $get['filter']['post_status'] = 'pending';
                }
                //dd($get['filter']);
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id' || $key=='ID'){
                            $post->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            if(is_array($value)){
                                foreach ($value as $kb => $vb) {
                                    if(!empty($vb)){
                                        if($key=='tags'){
                                            $post->where($key,'LIKE', '%"'.$vb.'"%' );
                                        }else{
                                            if($kb=='quadras'){
                                                $post->Where($key,'LIKE', '%"'.$vb.'"%');
                                            }else{
                                                $post->Where($key,'LIKE', '%"'.$kb.'":"'.$vb.'"%' );
                                            }
                                            // Qlib::lib_print($campos[$key]);
                                        }
                                    }
                                }
                            }else{
                                $post->where($key,'LIKE','%'. $value. '%');
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
        $anos = Qlib::sql_distinct('posts','comment_count',"WHERE post_type LIKE '%".$this->post_type."%' ORDER BY comment_count ASC",false);
        $ret['anos'] = $anos;
        return $ret;
    }
    public function campos_precessos($post_id=false){
        $hidden_editor = '';
        $user = $this->user;
        $bairro = new BairroController($user);
        $quadra = new QuadrasController($user);
        $data = false;
        if($post_id){
            $data = Post::Find($post_id);
        }
        if(isset($data['bairro'])){
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$data['bairro']."' AND ".Qlib::compleDelete()." ORDER BY nome ASC",'nome','id');
        }else{
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' ORDER BY nome ASC",'nome','id');
        }
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        $arr_ano_base=[];
        foreach (range(2019,date('Y')) as $value) {
            $arr_ano_base[$value] = $value;
        }
        $ar_local = Qlib::sql_array("SELECT id,nome FROM tags WHERE ativo='s' AND pai='14' ORDER BY ordem ASC",'nome','id');
        $data['post_type'] = isset($data['post_type']) ? $data['post_type'] : $this->post_type;
        $data['post_title'] = isset($data['post_title']) ? $data['post_title'] : __('Processo em campo');
        $ret = [
            'ID'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            // 'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_type'=>[
                'label'=>'Local do processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='14' ORDER BY ordem ASC",'nome','value'),'exibe_busca'=>'d-block',
                'event'=>'required onchange=selectLocalProcesso(this.value,\''.$this->post_type.'\')',
                'tam'=>'12',
                'exibe_busca'=>false,
                'option_select'=>true,
                // 'cp_busca'=>'config][local',
                'value'=>$data['post_type'],
                'class'=>'select2',
            ],
            'comment_count'=>[
                'label'=>'Ano Base',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_ano_base,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>false,
                'option_select'=>true,
                'class'=>'select2',
            ],
            'post_date_gmt'=>['label'=>'Entrega Prefeitura','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'config[numero_oficio]'=>['label'=>'N° Ofício','active'=>true,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][numero_oficio'],
            'post_modified_gmt'=>['label'=>'Entrega Cartório','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'post_title'=>['label'=>'Título','active'=>false,'placeholder'=>'Ex.: Título do processo','type'=>'hidden','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'7'],
            'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
            'config[area]'=>[
                'label'=>'Área',
                'active'=>true,
                'type'=>'select',
                'campo'=>'bairro',
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                //'event'=>'onchange=carregaMatricula($(this).val(),\'familias\')',
                'event'=>'onchange=carregaQuadras($(this).val())',
                'tam'=>'6',
                //'value'=>@$_GET['config']['area'],
                'cp_busca'=>'config][area',
                'class'=>'select2'
            ],
            'config[quadras][]'=>[
                'label'=>'Quadras',
                'active'=>true,
                'type'=>'select_multiple',
                'arr_opc'=>$arr_opc_quadras,
                'exibe_busca'=>'d-block',
                'event'=>'onchange=lib_abrirModalConsultaVinculo(\'loteamento\',\'fechar\');',
                'tam'=>'3',
                'cp_busca'=>'config][quadras',
                'class'=>'select2',
                'value'=>@$_GET['config']['quadras'],
            ],
            'config[matricula]'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][matricula'],
            'config[responsável]'=>[
                'label'=>'Responsável',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'12',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsável',
                'class'=>'select2',
            ],

            //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'post_status'=>['label'=>'Status','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Em vigor','pending'=>'Cancelado']],
            'post_content'=>['label'=>'Ocorrências','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
        ];
        return $ret;
    }
    public function campos_pc($post_id=false){
        // Processos em campo
        $hidden_editor = '';
        $user = $this->user;
        $bairro = new BairroController($user);
        $quadra = new QuadrasController($user);
        $lote = new LotesController($user);
        $data = false;
        if($post_id){
            $data = Post::Find($post_id);
        }
        if(isset($data['bairro'])){
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$data['bairro']."' AND ".Qlib::compleDelete()." ORDER BY nome ASC",'nome','id');
        }else{
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' ORDER BY nome ASC",'nome','id');
        }
        $data['post_type'] = isset($data['post_type']) ? $data['post_type'] : $this->post_type;
        $data['post_title'] = isset($data['post_title']) ? $data['post_title'] : __('Processo em campo');
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        $arr_ano_base=[];
        foreach (range(2019,date('Y')) as $value) {
            $arr_ano_base[$value] = $value;
        }
        $json_nao_sim = Qlib::qoption('json_nao_sim');
        $arr_nao_sim = Qlib::lib_json_array($json_nao_sim);
        $ret = [
            'ID'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            // 'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_type'=>[
                'label'=>'Local do processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='14' ORDER BY ordem ASC",'nome','value'),'exibe_busca'=>'d-block',
                'event'=>'required onchange=selectLocalProcesso(this.value,\''.$this->post_type.'\')',
                'tam'=>'12',
                'exibe_busca'=>true,
                'option_select'=>false,
                // 'cp_busca'=>'config][local',
                'value'=>$data['post_type'],
                'class'=>'select2',
            ],
            'comment_count'=>[
                'label'=>'Ano Base',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_ano_base,'exibe_busca'=>'d-block',
                'event'=>'required',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>true,
                'class'=>'select2',
            ],
            'post_title'=>['label'=>'Título','active'=>true,'placeholder'=>'Ex.: Título do processo','type'=>'hidden','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'7','value'=>$data['post_title']],
            'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
            'config[area]'=>[
                'label'=>'Área',
                'active'=>true,
                'type'=>'select',
                'campo'=>'bairro',
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                //'event'=>'onchange=carregaMatricula($(this).val(),\'familias\')',
                'event'=>'onchange=carregaQuadras($(this).val(),\'config[quadras][]\'); data-selector=bairro required',
                'tam'=>'6',
                //'value'=>@$_GET['config']['area'],
                'cp_busca'=>'config][area',
                // 'class'=>'select2'
            ],
            'config[quadras][]'=>[
                'label'=>'Quadras',
                'active'=>true,
                'type'=>'select_multiple',
                'arr_opc'=>$arr_opc_quadras,
                'exibe_busca'=>'d-block',
                'event'=>'onchange=lib_abrirModalConsultaVinculo(\'loteamento\',\'fechar\'); data-selector=quadra required',
                'tam'=>'3',
                'cp_busca'=>'config][quadras',
                'class'=>'select2',
                'value'=>@$_GET['config']['quadras'],
            ],
            'config[matricula]'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][matricula'],
            'config[responsavel]'=>[
                'label'=>'Responsável',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel',
                'class'=>'select2',
            ],
            'config[responsavel2]'=>[
                'label'=>'Responsável',
                'active'=>false,
                'type'=>'hidden',
                // 'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>false,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel2',
                // 'class'=>'select2',
            ],
            'config[responsavel3]'=>[
                'label'=>'Responsável',
                'active'=>false,
                'type'=>'hidden',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>false,
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel3',
                // 'class'=>'select2',
            ],
            'config[data_realizado]'=>['label'=>'Data realizado','cp_busca'=>'config][data_realizado','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
             'config[lotes]'=>[
                'label'=>'Informações do lote',
                'active'=>false,
                'type'=>'html_vinculo',
                'cp_busca'=>'config][lotes',
                'exibe_busca'=>'d-none',
                'event'=>'', //dampo para selecionar esse input
                'tam'=>'12',
                'script'=>'',
                'data_selector'=>[
                    'campos'=>$lote->campos(),
                    'route_index'=>route('lotes.index'),
                    'id_form'=>'frm-loteamento',
                    'tipo'=>'array', // int para somente um ou array para vários
                    'action'=>route('lotes.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'campo'=>'config[lotes]',
                    'value'=>[],
                    'label'=>'Informações do lote',
                    'table'=>[
                        'quadra'=>['label'=>'Quadra','type'=>'arr_tab',
                        'conf_sql'=>[
                            'tab'=>'quadras',
                            'campo_bus'=>'id',
                            'select'=>'nome',
                            'param'=>['bairro'],
                            ]
                        ],
                        'nome'=>['label'=>'Lote','type'=>'text'],
                    ],
                    'tab' =>'lotes',
                    'placeholder' =>'Digite somente o número do Lote...',
                    'janela'=>[
                        'url'=>route('lotes.create').'',
                        'param'=>['bairro','quadra'],
                        'form-param'=>'',
                    ],
                    'salvar_primeiro' =>false,//exigir cadastro do vinculo antes de cadastrar este
                ],
            ],
            'config[cadastro]'=>[
                'label'=>'Cadastro',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][cadastro',
                // 'class'=>'select2',
            ],
            'config[mapa_memorial]'=>[
                'label'=>'Mapa e Memorial',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][mapa_memorial',
                // 'class'=>'select2',
            ],
            'config[atendimento]'=>[
                'label'=>'Atendimento Jurídico',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['Nenhuma'=>'Nenhuma ação','agendado'=>'Agendado','realizado'=>'Realizado'],'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][atendimento',
                // 'class'=>'select2',
            ],
            'config[processo]'=>[
                'label'=>'Processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][processo',
                // 'class'=>'select2',
            ],
            'post_date_gmt'=>['label'=>'Data Entregue','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'post_content'=>['label'=>'Relato da(s) pendência(s) encontrada(s)','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
            'post_status'=>['label'=>'Status','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Resolvido','pending'=>'Não resolvido']],
        ];
        return $ret;
    }
    public function campos_pp($post_id=false){
        // Processos na prefeitura
        $hidden_editor = '';
        $user = $this->user;
        $bairro = new BairroController($user);
        $quadra = new QuadrasController($user);
        $lote = new LotesController($user);
        $data = false;
        if($post_id){
            $data = Post::Find($post_id);
        }


        if(isset($data['bairro'])){
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$data['bairro']."' AND ".Qlib::compleDelete()." ORDER BY nome ASC",'nome','id');
        }else{
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' ORDER BY nome ASC",'nome','id');
        }
        $ocupantes = false;
        if($data){
            // dd($data);
        }

        $data['post_type'] = isset($data['post_type']) ? $data['post_type'] : $this->post_type;
        $data['post_title'] = isset($data['post_title']) ? $data['post_title'] : __('Processo em campo');
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        $arr_ano_base=[];
        foreach (range(2019,date('Y')) as $value) {
            $arr_ano_base[$value] = $value;
        }

        $json_nao_sim = Qlib::qoption('json_nao_sim');
        $arr_nao_sim = Qlib::lib_json_array($json_nao_sim);
        $ret = [
            'ID'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            // 'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_type'=>[
                'label'=>'Local do processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='14' ORDER BY ordem ASC",'nome','value'),'exibe_busca'=>'d-block',
                'event'=>'required onchange=selectLocalProcesso(this.value,\''.$this->post_type.'\')',
                'tam'=>'12',
                'exibe_busca'=>true,
                'option_select'=>false,
                // 'cp_busca'=>'config][local',
                'value'=>$data['post_type'],
                'class'=>'select2',
            ],
            'comment_count'=>[
                'label'=>'Ano Base',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_ano_base,'exibe_busca'=>'d-block',
                'event'=>'required',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>true,
                'class'=>'select2',
            ],
            'post_date_gmt'=>['label'=>'Entrega Prefeitura','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'6','title'=>'Data de entrega à prefeitura'],
            'post_modified_gmt'=>['label'=>'Entrega Cartório','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3','title'=>'Data de entrega ao cartório'],
            'post_title'=>['label'=>'Título','active'=>false,'placeholder'=>'Ex.: Título do processo','type'=>'hidden','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'7','value'=>$data['post_title']],
            'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
            'config[area]'=>[
                'label'=>'Área',
                'active'=>true,
                'type'=>'select',
                'campo'=>'bairro',
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                //'event'=>'onchange=carregaMatricula($(this).val(),\'familias\')',
                'event'=>'onchange=carregaQuadras($(this).val(),\'config[quadras][]\'); data-selector=bairro',
                'tam'=>'6',
                //'value'=>@$_GET['config']['area'],
                'cp_busca'=>'config][area',
                // 'class'=>'select2'
            ],
            'config[quadras][]'=>[
                'label'=>'Quadras',
                'active'=>true,
                'type'=>'select_multiple',
                'arr_opc'=>$arr_opc_quadras,
                'exibe_busca'=>'d-block',
                'event'=>'onchange=lib_abrirModalConsultaVinculo(\'loteamento\',\'fechar\'); data-selector=quadra',
                'tam'=>'3',
                'cp_busca'=>'config][quadras',
                'class'=>'select2',
                'value'=>@$_GET['config']['quadras'],
            ],
            'config[matricula]'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][matricula'],
            'config[ocupantes]'=>['label'=>'Ocupantes','active'=>true,'type'=>'hidden_text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','value'=>$ocupantes,'placeholder'=>'','cp_busca'=>'config][ocupantes'],
            'config[responsavel]'=>[
                'label'=>'Responsável',
                'active'=>false,
                'type'=>'hidden',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel',
                // 'class'=>'select2',
            ],
            'config[responsavel2]'=>[
                'label'=>'Responsável',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel2',
                'class'=>'select2',
            ],
            'config[responsavel3]'=>[
                'label'=>'Responsável',
                'active'=>false,
                'type'=>'hidden',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsavel3',
                // 'class'=>'select2',
            ],
            'config[data_realizado]'=>['label'=>'Data realizado','cp_busca'=>'config][data_realizado','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            // 'config[lote]'=>['label'=>'lotes','cp_busca'=>'config][lote','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            //  'config[lotes]'=>[
            //     'label'=>'Informações do lote',
            //     'active'=>false,
            //     'type'=>'html_vinculo',
            //     'cp_busca'=>'config][lotes',
            //     'exibe_busca'=>'d-none',
            //     'event'=>'', //dampo para selecionar esse input
            //     'tam'=>'12',
            //     'script'=>'',
            //     'data_selector'=>[
            //         'campos'=>$lote->campos(),
            //         'route_index'=>route('lotes.index'),
            //         'id_form'=>'frm-loteamento',
            //         'tipo'=>'array', // int para somente um ou array para vários
            //         'action'=>route('lotes.store'),
            //         'campo_id'=>'id',
            //         'campo_bus'=>'nome',
            //         'campo'=>'config[lotes]',
            //         'value'=>[],
            //         'label'=>'Informações do lote',
            //         'table'=>[
            //             'quadra'=>['label'=>'Quadra','type'=>'arr_tab',
            //             'conf_sql'=>[
            //                 'tab'=>'quadras',
            //                 'campo_bus'=>'id',
            //                 'select'=>'nome',
            //                 'param'=>['bairro'],
            //                 ]
            //             ],
            //             'nome'=>['label'=>'Lote','type'=>'text'],
            //         ],
            //         'tab' =>'lotes',
            //         'placeholder' =>'Digite somente o número do Lote...',
            //         'janela'=>[
            //             'url'=>route('lotes.create').'',
            //             'param'=>['bairro','quadra'],
            //             'form-param'=>'',
            //         ],
            //         'salvar_primeiro' =>false,//exigir cadastro do vinculo antes de cadastrar este
            //     ],
            // ],
            'config[cadastro]'=>[
                'label'=>'Cadastro',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][cadastro',
                // 'class'=>'select2',
            ],
            'config[mapa_memorial]'=>[
                'label'=>'Mapa e Memorial',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][mapa_memorial',
                // 'class'=>'select2',
            ],
            'config[atendimento]'=>[
                'label'=>'Atendimento Jurídico',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>['Nenhuma'=>'Nenhuma ação','agendado'=>'Agendado','realizado'=>'Realizado'],'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][atendimento',
                // 'class'=>'select2',
            ],
            'config[processo]'=>[
                'label'=>'Processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_nao_sim,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'exibe_busca'=>true,
                'option_select'=>false,
                'cp_busca'=>'config][processo',
            ],
            //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'post_content'=>['label'=>'Ocorrências','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
            'post_status'=>['label'=>'Status','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Resolvido','pending'=>'Não resolvido']],
        ];
        return $ret;
    }
    public function campos_pca($post_id=false){
        $hidden_editor = '';
        $user = $this->user;
        $bairro = new BairroController($user);
        $quadra = new QuadrasController($user);
        $data = $dados?$dados:false;
        if(isset($data['bairro'])){
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$data['bairro']."' AND ".Qlib::compleDelete()." ORDER BY nome ASC",'nome','id');
        }else{
            $arr_opc_quadras = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' ORDER BY nome ASC",'nome','id');
        }
        $data['post_type'] = isset($data['post_type']) ? $data['post_type'] : $this->post_type;
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        $arr_ano_base=[];
        foreach (range(2019,date('Y')) as $value) {
            $arr_ano_base[$value] = $value;
        }
        $ret = [
            'ID'=>['label'=>'Id','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            // 'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'post_type'=>[
                'label'=>'Local do processo',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='14' ORDER BY ordem ASC",'nome','value'),'exibe_busca'=>'d-block',
                'event'=>'required onchange=selectLocalProcesso(this.value,\''.$this->post_type.'\')',
                'tam'=>'12',
                'exibe_busca'=>true,
                'option_select'=>false,
                // 'cp_busca'=>'config][local',
                'value'=>$data['post_type'],
                'class'=>'select2',
            ],
            'comment_count'=>[
                'label'=>'Ano Base',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>$arr_ano_base,'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'3',
                'exibe_busca'=>true,
                'option_select'=>true,
                'class'=>'select2',
            ],
            'post_date_gmt'=>['label'=>'Entrega Prefeitura','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'config[numero_oficio]'=>['label'=>'N° Ofício','active'=>true,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][numero_oficio'],
            'post_modified_gmt'=>['label'=>'Entrega Cartório','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'post_title'=>['label'=>'Título','active'=>true,'placeholder'=>'Ex.: Título do processo','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'7'],
            'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
            'config[area]'=>[
                'label'=>'Área',
                'active'=>true,
                'type'=>'select',
                'campo'=>'bairro',
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                //'event'=>'onchange=carregaMatricula($(this).val(),\'familias\')',
                'event'=>'onchange=carregaQuadras($(this).val())',
                'tam'=>'6',
                //'value'=>@$_GET['config']['area'],
                'cp_busca'=>'config][area',
                'class'=>'select2'
            ],
            'config[quadras][]'=>[
                'label'=>'Quadras',
                'active'=>true,
                'type'=>'select_multiple',
                'arr_opc'=>$arr_opc_quadras,
                'exibe_busca'=>'d-block',
                'event'=>'onchange=lib_abrirModalConsultaVinculo(\'loteamento\',\'fechar\');',
                'tam'=>'3',
                'cp_busca'=>'config][quadras',
                'class'=>'select2',
                'value'=>@$_GET['config']['quadras'],
            ],
            'config[matricula]'=>['label'=>'Matricula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][matricula'],
            'config[responsável]'=>[
                'label'=>'Responsável',
                'active'=>true,
                'type'=>'select',
                'arr_opc'=>Qlib::sql_array("SELECT id,name FROM users WHERE ativo='s' AND id_permission>'1'",'name','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'12',
                'exibe_busca'=>true,
                'option_select'=>true,
                'cp_busca'=>'config][responsável',
                'class'=>'select2',
            ],

            //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'post_status'=>['label'=>'Status','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Em vigor','pending'=>'Cancelado']],
            'post_content'=>['label'=>'Ocorrências','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
        ];
        return $ret;
    }
    public function campos($post_id=false){
        $hidden_editor = '';
        if(Qlib::qoption('editor_padrao')=='laraberg'){
            $hidden_editor = 'hidden';
        }
        if($this->post_type=='processos'){
            $ret = $this->campos_precessos($post_id);
            // $ret = $this->campos_pc($post_id);
        }elseif($this->post_type=='processos-campo'){
            $ret = $this->campos_pc($post_id);
        }elseif($this->post_type=='processos-prefeitura'){
            $ret = $this->campos_pp($post_id);
        }elseif($this->post_type=='processos-cartorio'){
            $ret = $this->campos_pca($post_id);
        }elseif($this->post_type=='menu'){
            $ret = $this->campos_menus($post_id);
        }else{
            $ret = [
                'ID'=>['label'=>'Id','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'post_type'=>['label'=>'tipo de post','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','event'=>'','tam'=>'2','value'=>$this->post_type],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'config[numero]'=>['label'=>'Numero','active'=>true,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'2','cp_busca'=>'config][numero'],
                'post_date_gmt'=>['label'=>'Data do decreto','active'=>true,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
                'post_title'=>['label'=>'Título','active'=>true,'placeholder'=>'Ex.: Título do decreto','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=lib_typeSlug(this)','tam'=>'7'],
                'post_name'=>['label'=>'Slug','active'=>false,'placeholder'=>'Ex.: nome-do-post','type'=>'hidden','exibe_busca'=>'d-block','event'=>'type_slug=true','tam'=>'12'],
                //'post_excerpt'=>['label'=>'Resumo (Opcional)','active'=>true,'placeholder'=>'Uma síntese do um post','type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                //'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
                'post_status'=>['label'=>'Status','active'=>true,'type'=>'chave_checkbox','value'=>'publish','valor_padrao'=>'publish','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['publish'=>'Em vigor','pending'=>'Cancelado']],
                'post_content'=>['label'=>'Conteudo','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>$hidden_editor,'tam'=>'12','class_div'=>'','class'=>'editor-padrao summernote','placeholder'=>__('Escreva seu conteúdo aqui..')],
            ];
        }
        return $ret;
    }
    public function selectType($sec=false)
    {
        $ret['exec']=false;
        $ret['title']=false;
        $title = false;
        if($sec){
            $name = request()->route()->getName();
            if($sec=='posts'){
                $title = __('Cadastro de postagens');
            }elseif($sec=='decretos'){
                $title = __('Cadastro de Decretos');
                if($name=='decretos.edit'){
                    $title = __('Editar Cadastro de Decretos');
                }
            }elseif($sec=='processos'){
                $title = __('Cadastro de processos');
            }elseif($sec=='processos-campo'){
                $title = __('Cadastro de processos em campo');
            }elseif($sec=='menus'){
                $title = __('Cadastro de menus');
            }elseif($sec=='pacotes_lances'){
                $title = __('Cadastro de pacotes');
            }else{
                $title = __('Sem titulo');
            }
        }
        $ret['title'] = $title;
        return $ret;
    }
    public function index(User $user)
    {
        //$this->authorize('is_admin', $user);
        $this->authorize('ler', $this->routa);
        $selTypes = $this->selectType($this->sec);
        $title = $selTypes['title'];
        $titulo = $title;
        $queryPost = $this->queryPost($_GET);
        $queryPost['config']['exibe'] = 'html';
        $routa = $this->routa;
        //if(isset($queryPost['post']));
        $ret = [
            'dados'=>$queryPost['post'],
            'title'=>$title,
            'titulo'=>$titulo,
            'anos'=>@$queryPost['anos'],
            'campos_tabela'=>$queryPost['campos'],
            'post_totais'=>$queryPost['post_totais'],
            'titulo_tabela'=>$queryPost['tituloTabela'],
            'arr_titulo'=>$queryPost['arr_titulo'],
            'config'=>$queryPost['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ];
        //REGISTRAR EVENTOS
        (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
        if($this->routa=='processos' || $this->routa=='processos-campo' || $this->routa=='processos-prefeitura' || $this->routa=='processos-cartorio'){
           $this->view = 'admin.processos';

        }
        return view($this->view.'.index',$ret);
    }
    public function create(User $user)
    {
        $this->authorize('is_admin2', $user);
        //Selecionar o tipo de postagem

        $selTypes = $this->selectType($this->sec);
        $title = $selTypes['title'];
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
         //REGISTRAR EVENTO CADASTRO
         $regev = Qlib::regEvent(['action'=>'create','tab'=>$this->tab,'config'=>[
            'obs'=>'Abriu tela de cadastro',
            'link'=>$this->routa,
            ]
        ]);
        if($this->routa=='processos-campo'){
            $this->view = 'admin.processos';
        }
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
        // $dados['post_date_gmt'] = !empty($dados['post_date_gmt'])?$dados['post_date_gmt']:'STR_TO_DATE(0000-00-00 00:00:00)';
        if(!$dados['post_date_gmt'])
            unset($dados['post_date_gmt']);
        // dd($dados);
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
                //REGISTRAR EVENTO STORE
                if($salvar->id){
                    $regev = Qlib::regEvent(['action'=>'store','tab'=>$this->tab,'config'=>[
                        'obs'=>'Cadastro guia Id '.$salvar->id,
                        'link'=>$this->routa,
                        ]
                    ]);
                }
            }else{
                $mens = 'Erro ao salvar '.$this->label.'';
                $color = 'danger';
                $idCad = 0;
            }
        }
        //REGISTRAR EVENTOS
        (new EventController)->listarEvent(['tab'=>$this->tab,'id'=>@$salvar->id,'this'=>$this]);

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
            $title = 'Visualização da decretos';
            $titulo = $title;
            //dd($dados);
            $dados['ac'] = 'alt';
            if(isset($dados['config'])){
                $dados['config'] = Qlib::lib_json_array($dados['config']);
            }
            $listFiles = false;
            //$dados['renda_familiar'] = number_format($dados['renda_familiar'],2,',','.');
            $campos = $this->campos($id);
            if(isset($dados['token'])){
                $listFiles = _upload::where('token_produto','=',$dados['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-familias',
                'route'=>$this->routa,
                'view'=>$this->view,
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
                if($this->routa =='processos' || $this->routa =='processos-campo' || $this->routa =='processos-prefeitura' || $this->routa =='processos-cartorio'){
                    $config['eventos'] = (new EventController)->listEventsPost(['post_id'=>$id,'tab'=>'change_process']);
                }else{
                    $config['eventos'] = (new EventController)->listEventsPost(['post_id'=>$id]);
                }
            }else{
                $config['class_card1'] = 'col-md-12';
                $config['class_card2'] = 'd-none';
            }
            $ret = [
                'value'=>$dados,
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'routa'=>$this->routa,
                'routa'=>$this->routa,
                'exec'=>true,
            ];
            //REGISTRAR EVENTOS
            (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
            return view('padrao.show',$ret);
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
            //dd($dados[0]['config']['numero']);
            $listFiles = false;
            $campos = $this->campos($id);
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
            //REGISTRAR EVENTOS
            (new EventController)->listarEvent(['tab'=>$this->tab,'this'=>$this]);
            $dados[0]['id'] = isset($dados[0]['ID'])?$dados[0]['ID']:0;
            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'exec'=>true,
            ];
            if($this->routa=='processos-campo' || $this->routa=='processos-prefeitura' || $this->routa=='processos-cartorio'){
                $this->view = 'admin.processos';
            }
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
                $data[$key] = $value;
            }
        }
        $data['post_status'] = isset($data['post_status'])?$data['post_status']:'pending';
        $userLogadon = Auth::id();
        $data['post_author'] = $userLogadon;
        $data['token'] = !empty($data['token'])?$data['token']:uniqid();
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){

            if(!@$data['post_date_gmt'])
                unset($data['post_date_gmt']);
            if(!@$data['post_modified_gmt'])
                unset($data['post_modified_gmt']);
            // REGISTRA EVENDOS DE MUDANÇAS DE STATUS.
            $salv = (new processosController)->register_change_process(['process_id' => $id,'save_status' => @$data['post_type']]);

            $atualizar=Post::where('id',$id)->update($data);
            if($atualizar){
                $mens = $this->label.' cadastrado com sucesso!';
                $color = 'success';
                $id = $id;
                //REGISTRAR EVENTOS
                (new EventController)->listarEvent(['tab'=>$this->routa,'this'=>$this]);

            }else{
                $mens = 'Erro ao salvar '.$this->label.'';
                $color = 'danger';
                $id = 0;
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
            //REGISTRAR EVENTO
            $regev = Qlib::regEvent(['action'=>'destroy','tab'=>$this->tab,'config'=>[
                'obs'=>'Exclusão de cadastro Id '.$id,
                'link'=>$this->routa,
                ]
            ]);

        }
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__($mens),'color'=>$color,'return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>$mens,'color'=>$color]);
        }
        return $ret;
    }
}
