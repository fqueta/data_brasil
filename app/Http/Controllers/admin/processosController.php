<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Familia;
use App\Models\Post;
use App\Qlib\Qlib;
use App\Qlib\QlibFacade;
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
    /**
     * @param integer
     * @return integer
     *
     */
    public function ocupantes($process_id=false){
        $ret = 0;
        if($process_id){
            $quadras = Post::Find($process_id);
            if($quadras->count() > 0){
                if(isset($quadras['config'])){
                    $arr_q = Qlib::lib_json_array($quadras['config']);
                    if(isset($arr_q['quadras'])){
                        foreach ($arr_q['quadras'] as $k => $v) {
                            $fm = Familia::where('quadra','=',$v)->where('deletado','=','n')->where('excluido','=','n');
                            if($fm->count()){
                                $ret += $fm->count();
                            }
                        }
                    }
                }
            }
        }
        return $ret;
    }
    public function calcula_dias( $id = null,$data=false,$campos='post_date_gmt@post_modified_gmt')
    {
        $ret =false;
        if($id && !$data){
            $data = Post::find($id);
        }
        if($campos){
            $arr_campos = explode('@', $campos);
        }
        if(isset($data[$arr_campos[0]]) && !empty($data[$arr_campos[0]]) && isset($data[$arr_campos[1]]) && !empty($data[$arr_campos[1]])){
            $di = explode(' ', $data[$arr_campos[0]]);    //Data inicial
            $df = explode(' ', $data[$arr_campos[1]]); //Data Final
            if($di[0] && $df[0]){
                $ret = Qlib::diffDate($di[0],$df[0],'D');
                if($ret<0){
                    $ret = 0;
                }
            }
        }
        return $ret;
    }
    public function list_historico_devolutivas($dados,$tm=false){
        $tr = false;
        $dconf = isset($dados['config'])?$dados['config']:false;
        $campo_his_dev = 'config[hist_dev][{id}]';
        $r_n = explode('.',request()->route()->getName());
        $route_name = isset($r_n[1]) ? $r_n[1] : false;
        if(isset($dconf['nota_devolutiva']) && $dconf['nota_devolutiva']=='s'){
            $class_display = '';
        }else{
            $class_display = 'd-none d-print-none';
        }
        if(isset($dconf['hist_dev']) && is_array($dconf['hist_dev']) && $tm){
            $title_calc_dias = __('Contagem de dias entre o Envio ao cartório e a data da Nota Devolutiva.');
            $title_calc_dias2 = __('Contagem de dias entre o recebimento da Nota Devolutiva e o efetivo cumprimento da mesma.');
            // $tr .= '<input type="hidden" id="title_calc_dias" value="{title_calc_dias}">';
            // $tr .= '<input type="hidden" id="title_calc_dias" value="{title_calc_dias}">';

            foreach ($dconf['hist_dev'] as $k => $v) {
                $di = explode(' ', $dados['post_modified_gmt']);
                if($di[0] && $v['data']){
                    $cal_dias = Qlib::diffDate($di[0],$v['data'],'D');
                }else{
                    $cal_dias = false;
                }
                if($v['data'] && $v['data_cumprimento']){
                    $cal_dias2 = Qlib::diffDate($v['data'],$v['data_cumprimento'],'D');
                }else{
                    $cal_dias2 = false;
                }
                $acao = '<button type="button" onclick="remove_hist_devolucao(\'{id}\')" class="btn btn-outline-danger"><i class="fa fa-times"></i></button>';
                $sel_juri = false;
                $sel_topo = false;
                $sel_admi = false;
                if(@$v['area']=='Jurídico'){
                    $sel_juri = 'selected';
                }
                if(@$v['area']=='Administrativo'){
                    $sel_admi = 'selected';
                }
                if(@$v['area']=='Topografia'){
                    $sel_topo = 'selected';
                }
                if($route_name=='show'){
                    $data = Qlib::dataExibe($v['data']);
                    $data_cumprimento = Qlib::dataExibe($v['data_cumprimento']);
                }else{
                    $data = $v['data'];
                    $data_cumprimento = $v['data_cumprimento'];
                }
                $tr .= str_replace('{campo}',$campo_his_dev,$tm);
                $tr = str_replace('{protocolo}',$v['protocolo'],$tr);
                $tr = str_replace('{motivo}',$v['motivo'],$tr);
                $tr = str_replace('{talao}',$v['talao'],$tr);
                $tr = str_replace('{data}',$data,$tr);
                $tr = str_replace('{data_cumprimento}',$data_cumprimento,$tr);
                $tr = str_replace('{cal_dias}',$cal_dias,$tr);
                $tr = str_replace('{cal_dias2}',$cal_dias2,$tr);
                $tr = str_replace('{acao}',$acao,$tr);
                $tr = str_replace('{title_calc_dias}',$title_calc_dias,$tr);
                $tr = str_replace('{title_calc_dias2}',$title_calc_dias2,$tr);
                $tr = str_replace('{sel_juri}',$sel_juri,$tr);
                $tr = str_replace('{sel_admi}',$sel_admi,$tr);
                $tr = str_replace('{sel_topo}',$sel_topo,$tr);
                $tr = str_replace('{area}',@$v['area'],$tr);
                $tr = str_replace('{id}',$k,$tr);
            }
        }
        return $tr;
    }
}
