<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class processosController extends Controller
{
    /**
     * Acompanha as mudaças nos processos e salva um evento de mudança
     * @param array
     * @return array
     */
    public function register_change_process($config = false)
    {
        //Trabalha depois da execução da atualização
        // Exemplo: $d = (new processosController)->register_change_process(['process_id' => 19,'save_status' => 'processos-prefeitura']);
        $ret['exec'] = false;
        $save_status   = isset($config['save_status'])    ? $config['save_status']   : false;
        $process_id    = isset($config['process_id'])     ? $config['process_id']    : false;
        $action_event  = isset($config['action_event'])   ? $config['action_event']  : 'change_process';
        $obs           = isset($config['obs'])   ? $config['obs']  : false; //Pode ser uma descrição
        $user_id = isset($config['user_id'])?$config['user_id']: Auth::id();
        if($save_status&&$process_id){
            $dp = Post::Find($process_id);
            if($dp->count()){

                // echo $process_id.'<br>';
                // echo $save_status;
                // dd($dp['post_type']);
                if($dp['post_type'] != $save_status){
                    //indeca que o processo mudou tem que criar um evendo de mudanção de processo
                    $labelProcesso  = Qlib::buscaValorDb([
                        'tab'=>'menus',
                        'campo_bus'=>'url',
                        'valor'=>$save_status,
                        'select'=>'description',
                        'compleSql'=>false,
                    ]);
                    if(!$labelProcesso){
                        $labelProcesso = $save_status;
                    }
                    $cfe = [
                        'action'=>'update', //update será invocado quando houver uma mudança de status
                        'tab'=>$action_event, //uma tage para identificar o tipo de evento
                        'user_id'=>$user_id,
                        'post_id'=>$process_id,
                        'config'=>[
                            'obs'=>$obs.' Processo enviado para <b>'.$labelProcesso.'</b>',
                            'status_inicial'=>$dp['post_type'],
                            'status_final'=>$save_status,
                        ],
                    ];
                    // dd($cfe);
                    $exec = (new EventController)->regEvent($cfe);
                    if($exec){
                        //dependendo do proximo processo tualizar a base de processos com informção sobre quando ocorreu a mudança.
                        // echo Qlib::dataBanco();
                        // $atualizar=Post::where('id',$process_id)->update([
                        //     'post_date_gmt'=>Qlib::dataBanco(),
                        // ]);
                        $ret['exec'] = $exec;
                        // $ret['atualizar'] = $atualizar;

                    }
                    // dd($ret);
                }
            }
        }
        return $ret;
    }
}
