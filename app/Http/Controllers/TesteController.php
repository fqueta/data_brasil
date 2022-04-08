<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Auth::logout();
        //echo __FILE__;
       // $dados = Auth::check();
        //$dados = Qlib::sql_array("SELECT id,nome FROM escolaridades ORDER BY nome ", 'nome', 'id');
        //$dados = DB::select("SELECT * FROM familias WHERE excluido='n' AND deletado='n' ORDER BY id DESC");
        //$dados = Familia::where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','desc')->get();
        //$dados = DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->OrderBy('id','desc')->get();
        $arrPermiss = [
            "master"=>
            [
                "ler"=>["cadastros"=>"s","familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","config"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "create"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
            ],
            "adminstrador"=>
            [
                "ler"=>["cadastros"=>"s","familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios"=>"n","relatorios_geral"=>"n","relatorios_evolucao"=>"n","config"=>"s","sistema"=>"n","users"=>"s","permissions"=>"s"],
                "create"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "update"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"],
                "delete"=>["familias"=>"s","bairros"=>"s","etapas"=>"s","escolaridade"=>"s","estado-civil"=>"s","relatorios_geral"=>"s","relatorios_evolucao"=>"s","sistema"=>"s","users"=>"s","permissions"=>"s"]
            ],
        ];
        dd($arrPermiss);
        /*
        $url = 'https://po.presidenteolegario.mg.gov.br/api/pages/o-que-e-covid-19';
        $d = file_get_contents($url);
        $arr = Qlib::lib_json_array($d);
        $arr['cont'] = Qlib::lib_json_array($arr['content']);
        Qlib::lib_print($arr['cont']);*/
        //return view('teste');
    }
    public function ajax(){
        $limit = isset($_GET['limit']) ?$_GET['limit'] : 50;
        $page = isset($_GET['page']) ?$_GET['page'] : 1;
        $site=false;

        $urlApi = $site?$site: 'https://po.presidenteolegario.mg.gov.br';
        $link = $urlApi.'/api/diaries?page='.$page.'&limit='.$limit;
        $link_html = dirname(__FILE__).'/html/front.html';
        $dir_img = $urlApi.'/uploads/posts/image_previews/{id}/thumbnail/{image_preview_file_name}';
        $dir_file = $urlApi.'/uploads/diaries/files/{id}/original/{file_file_name}';

        //$arquivo = $this->carregaArquivo($link_html);
        //$temaHTML = explode('<!--separa--->',$arquivo);
        $api = file_get_contents($link);
        $arr_api = Qlib::lib_json_array($api);
        /*
        $tema1 = '<ul id="conteudo" class="list-group">{tr}</ul>';
        $tema2 = '<li class="list-group-item" itemprop="headline"><a href="{link_file}" target="_blank">{file_file_name} – {date}</a></li>';
        $tr=false;
        if(isset($arr_api['data']) && !empty($arr_api['data'])){
          foreach ($arr_api['data'] as $key => $value) {
              $link = false;
              $link_file = str_replace('{id}',$value['id'],$dir_file);
              $link_file = str_replace('{file_file_name}',$value['file_file_name'],$link_file);


              $conteudoPost = isset($value['content'])?:false;
              $date = false;
              $time = false;
              $datetime = str_replace(' ','T',$value['date']);
              $d = explode(' ',$value['date']);

              if(isset($d[0])){
                $date = Qlib::dataExibe($d[0]);
              }
              if(isset($d[1])){
                $time = $d[1];
              }
              $file_name = str_replace('.pdf','',$value['file_file_name']);
              $file_name = str_replace('.PDF','',$file_name);
              $tr .= str_replace('{file_file_name}',$file_name,$tema2);
              $tr = str_replace('{link}',$link,$tr);
              $tr = str_replace('{link_file}',$link_file,$tr);
              $tr = str_replace('{time}',$time,$tr);
              $tr = str_replace('{date}',$date,$tr);
              $tr = str_replace('{description}',$value['description'],$tr);
              $tr = str_replace('{datetime}',$datetime,$tr);
          }
        }
        $link_veja_mais = '/diario-oficial';
        $ret = str_replace('{tr}',$tr,$tema1);
        //$ret = str_replace('{id_sec}',$id_sec,$ret);
        $ret = str_replace('{link_veja_mais}',$link_veja_mais,$ret);
        */
        return response()->json($arr_api);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
