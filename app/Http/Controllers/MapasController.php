<?php

namespace App\Http\Controllers;

use App\Models\_upload;
use App\Models\Quadra;
use App\Qlib\Qlib;
use Illuminate\Http\Request;

class MapasController extends Controller
{
    public function quadras($id = null)
    {
        $ret = $this->queryQuadras($id);
        return view('mapas.quadras',$ret);
    }

    public function queryQuadras($id_quadra = null)
    {
        $ret = false;
        if($id_quadra){
            $dados = Quadra::FindOrFail($id_quadra);
            $config = false;
            $ret['exec'] = false;
            $title = 'Quadra '.$dados['nome'];
            $titulo = $title;
            $ret['mens'] = false;
            $ret['title'] = $title;
            $ret['titulo'] = $titulo;

            if($dados['token']){
                $file = _upload::where('token_produto','=',$dados['token'])->get();
                $arr_confile = Qlib::lib_json_array($file[0]['config']);
                $dados['lotes'] = Qlib::totalReg('lotes',"WHERE quadra='".$dados['id']."' AND ".Qlib::compleDelete());
                $dados['familias'] = Qlib::totalReg('familias',"WHERE quadra='".$dados['id']."' AND ".Qlib::compleDelete());
                $dados['arr_bairros'] = Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s' AND ".Qlib::compleDelete(),'nome','id');
                if($file[0]['pasta'] && $arr_confile['extenssao']=='svg'){
                    $config = [
                        'dados'=>$dados,
                        'local'=>'quadras',
                        'svg_file'=>'/storage/'.$file[0]['pasta'],
                    ];
                }else{
                    $ret['mens'] = Qlib::formatMensagem('Erro Arquivo de mapa inválido!','danger');
                }
            }
            if($config){
                $ret = [
                    'config'=>$config,
                    'exec'=>true,
                    'title'=>$title,
                    'titulo'=>$titulo,
                ];
            }
        }
        return $ret;
    }
    public function exibeMapas($config = null)
    {
        if(isset($config['dados']) && isset($config['svg_file'])){
            return view('mapas.todos',['config'=>$config]);
        }else{
            return Qlib::formatMensagemInfo('Dados insuficientes','danger');
        }
    }
}
