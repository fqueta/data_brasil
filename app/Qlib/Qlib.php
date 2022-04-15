<?php
namespace App\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
class Qlib
{
  public function lib_print($data){
      if(is_array($data) || is_object($data)){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
      }else{
        echo $data;
      }
  }
  static function dtBanco($data) {
			$data = trim($data);
			if (strlen($data) != 10)
			{
				$rs = false;
			}
			else
			{
				$arr_data = explode("/",$data);
				$data_banco = $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
				$rs = $data_banco;
			}
			return $rs;
	}
  static function dataExibe($data) {
			$val = trim(strlen($data));
			$data = trim($data);$rs = false;
			if($val == 10){
					$arr_data = explode("-",$data);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco;
			}
			if($val == 19){
					$arr_inic = explode(" ",$data);
					$arr_data = explode("-",$arr_inic[0]);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco."-".$arr_inic[1] ;
			}

			return $rs;
	}
  static function lib_json_array($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = $json;
		}elseif(!empty($json) && Qlib::isJson($json)&&!is_array($json)){
			$ret = json_decode($json,true);
		}
		return $ret;
	}
	public static function lib_array_json($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = json_encode($json,JSON_UNESCAPED_UNICODE);
		}
		return $ret;
	}
    static function precoBanco($preco){
            $sp = substr($preco,-3,-2);
            if($sp=='.'){
                $preco_venda1 = $preco;
            }else{
                $preco_venda1 = str_replace(".", "", $preco);
                $preco_venda1 = str_replace(",", ".", $preco_venda1);
            }
            return $preco_venda1;
    }
    static function isJson($string) {
		$ret=false;
		if (is_object(json_decode($string)) || is_array(json_decode($string)))
		{
			$ret=true;
		}
		return $ret;
	}
  static function Meses($val=false){
  		$mese = array('01'=>'JANEIRO','02'=>'FEVEREIRO','03'=>'MARÇO','04'=>'ABRIL','05'=>'MAIO','06'=>'JUNHO','07'=>'JULHO','08'=>'AGOSTO','09'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
  		if($val){
  			return $mese[$val];
  		}else{
  			return $mese;
  		}
	}
  static function totalReg($tabela, $condicao = false,$debug=false){
			//necessario
			$sql = "SELECT COUNT(*) AS totalreg FROM {$tabela} $condicao";
			if($debug)
				 echo $sql.'<br>';
			//return $sql;
			$td_registros = DB::select($sql);
			if(isset($td_registros[0]->totalreg) && $td_registros[0]->totalreg > 0){
				return $td_registros[0]->totalreg;
			}else
				return 0;
	}
  static function zerofill( $number ,$nroDigo=6, $zeros = null ){
		$string = sprintf( '%%0%ds' , is_null( $zeros ) ?  $nroDigo : $zeros );
		return sprintf( $string , $number );
	}
  static function encodeArray($arr){
			$ret = false;
			if(is_array($arr)){
				$ret = base64_encode(json_encode($arr));
			}
			return $ret;
	}
  static function decodeArray($arr){
			$ret = false;
			if($arr){
				//$ret = base64_encode(json_encode($arr));
				$ret = base64_decode($arr);
				$ret = json_decode($ret,true);

			}
			return $ret;
	}
    static function qForm($config=false){
        if(isset($config['type'])){
            $config['campo'] = isset($config['campo'])?$config['campo']:'teste';
            $config['label'] = isset($config['label'])?$config['label']:false;
            $config['placeholder'] = isset($config['placeholder'])?$config['placeholder']:false;
            $config['selected'] = isset($config['selected']) ? $config['selected']:false;
            $config['tam'] = isset($config['tam']) ? $config['tam']:'12';
            $config['col'] = isset($config['col']) ? $config['col']:'md';
            $config['event'] = isset($config['event']) ? $config['event']:false;
            $config['ac'] = isset($config['ac']) ? $config['ac']:'cad';
            $config['option_select'] = isset($config['option_select']) ? $config['option_select']:true;
            $config['label_option_select'] = isset($config['label_option_select']) ? $config['label_option_select']:'Selecione';
            $config['option_gerente'] = isset($config['option_gerente']) ? $config['option_gerente']:false;
            $config['class'] = isset($config['class']) ? $config['class'] : false;
            $config['style'] = isset($config['style']) ? $config['style'] : false;
            $config['class_div'] = isset($config['class_div']) ? $config['class_div'] : false;
            if(@$config['type']=='chave_checkbox' && @$config['ac']=='cad'){
                if(@$config['checked'] == null && isset($config['valor_padrao']))
                $config['checked'] = $config['valor_padrao'];
            }
            if(@$config['type']=='html_vinculo' && @$config['ac']=='alt'){
                $tab = $config['data_selector']['tab'];
                $id = $config['value'];
                $config['data_selector']['placeholder'] = isset($config['data_selector']['placeholder'])?$config['data_selector']['placeholder']:'Digite para iniciar a consulta...';
                $config['data_selector']['list'] = Qlib::dados_tab($tab,['id'=>$id]);
                if($config['data_selector']['list'] && isset($config['data_selector']['table']) && is_array($config['data_selector']['table'])){
                    foreach ($config['data_selector']['table'] as $key => $v) {
                        if(isset($v['type']) && $v['type']=='arr_tab' && isset($config['data_selector']['list'][$key]) && isset($v['conf_sql'])){
                            $config['data_selector']['list'][$key.'_valor'] = Qlib::buscaValorDb([
                                'tab'=>$v['conf_sql']['tab'],
                                'campo_bus'=>$v['conf_sql']['campo_bus'],
                                'select'=>$v['conf_sql']['select'],
                                'valor'=>$config['data_selector']['list'][$key],
                            ]);
                        }
                    }
                    //dd($config);
                }
            }
            return view('qlib.campos_form',['config'=>$config]);
        }else{
            return false;
        }
    }
    static function sql_array($sql, $ind, $ind_2, $ind_3 = '', $leg = '',$type=false){
        $table = DB::select($sql);
        $userinfo = array();
        if($table){
            //dd($table);
            for($i = 0;$i < count($table);$i++){
                $table[$i] = (array)$table[$i];
                if($ind_3 == ''){
                    $userinfo[$table[$i][$ind_2]] =  $table[$i][$ind];
                }elseif(is_array($ind_3) && isset($ind_3['tab'])){
                    /*É sinal que o valor vira de banco de dados*/
                    $sql = "SELECT ".$ind_3['campo_enc']." FROM `".$ind_3['tab']."` WHERE ".$ind_3['campo_bus']." = '".$table[$i][$ind_2]."'";
                    $userinfo[$table[$i][$ind_2]] = $sql;
                }else{
                    if($type){
                        if($type == 'data'){
                            /*Tipo de campo exibe*/
                            $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . Qlib::dataExibe($table[$i][$ind_3]);
                        }
                    }else{
                        $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . $table[$i][$ind_3];
                    }
                }
            }
        }

        return $userinfo;
    }
    static function formatMensagem($config=false){
        if($config){
            $config['mens'] = isset($config['mens']) ? $config['mens'] : false;
            $config['color'] = isset($config['color']) ? $config['color'] : false;
            $config['time'] = isset($config['time']) ? $config['time'] : 4000;
            return view('qlib.format_mensagem', ['config'=>$config]);
        }else{
            return false;
        }
	}
    static function gerUploadAquivos($config=false){
        if($config){
            $config['parte'] = isset($config['parte']) ? $config['parte'] : 'painel';
            $config['token_produto'] = isset($config['token_produto']) ? $config['token_produto'] : false;
            $config['listFiles'] = isset($config['listFiles']) ? $config['listFiles'] : false; // array com a lista
            $config['time'] = isset($config['time']) ? $config['time'] : 4000;
            if($config['listFiles']){
                $tipo = false;
                foreach ($config['listFiles'] as $key => $value) {
                    if(isset($value['config'])){
                        $arr_conf = Qlib::lib_json_array($value['config']);
                        if(isset($arr_conf['extenssao']) && !empty($arr_conf['extenssao']))
                        {
                            if($arr_conf['extenssao'] == 'jpg' || $arr_conf['extenssao']=='png' || $arr_conf['extenssao'] == 'jpeg'){
                                $tipo = 'image';
                            }elseif($arr_conf['extenssao'] == 'doc' || $arr_conf['extenssao'] == 'docx') {
                                $tipo = 'word';
                            }elseif($arr_conf['extenssao'] == 'xls' || $arr_conf['extenssao'] == 'xlsx') {
                                $tipo = 'excel';
                            }else{
                                $tipo = 'download';
                            }
                        }
                        $config['listFiles'][$key]['tipo_icon'] = $tipo;
                    }
                }
            }
            if($config['parte']){
                $view = 'qlib.uploads.painel';
                return view($view, ['config'=>$config]);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    static function formulario($config=false){
        if($config['campos']){
            $view = 'qlib.formulario';
            return view($view, ['conf'=>$config]);
        }else{
            return false;
        }
    }
    static function listaTabela($config=false){
        if($config['campos_tabela']){
            $view = 'qlib.listaTabela';
            return view($view, ['conf'=>$config]);
        }else{
            return false;
        }
    }
    static function UrlAtual(){
        return URL::full();
    }
    static function ver_PermAdmin($perm=false,$url=false){
        $ret = false;
        if(!$url){
            $url = URL::current();
            $arr_url = explode('/',$url);
        }
        if($url && $perm){
            $arr_permissions = [];
            $logado = Auth::user();
            $id_permission = $logado->id_permission;
            $dPermission = Permission::findOrFail($id_permission);
            if($dPermission && $dPermission->active=='s'){
                $arr_permissions = Qlib::lib_json_array($dPermission->id_menu);
                if(isset($arr_permissions[$perm][$url])){
                    $ret = true;
                }
            }
        }
        return $ret;
    }
    static public function html_vinculo($config = null)
    {
        /**
        Qlib::html_vinculo([
            'campos'=>'',
            'type'=>'html_vinculo',
            'dados'=>'',
        ]);
         */

        $ret = false;
        $campos = isset($config['campos'])?$config['campos']:false;
        $type = isset($config['type'])?$config['type']:false;
        $dados = isset($config['dados'])?$config['dados']:false;
        if(!$campos)
            return $ret;
        if(is_array($campos) && $dados){
            foreach ($campos as $key => $value) {
                if($value['type']==$type){
                    $id = $dados[$key];
                    $tab = $value['data_selector']['tab'];
                    $d_tab = DB::table($tab)->find($id);
                    if($d_tab){
                        $ret[$key] = (array)$d_tab;
                    }
                }
            }
        }
        return $ret;
    }
    static public function dados_tab($tab = null,$config)
    {
        $ret = false;
        if($tab){
            $id = isset($config['id']) ? $config['id']:false;
            $sql = isset($config['sql']) ? $config['sql']:false;
            if($sql){
                $d = DB::select($sql);
                $ret = (array)$d;
                return $ret;
            }
            $obj_list = DB::table($tab)->find($id);
            if($list=(array)$obj_list){
                    if(is_array($list)){
                        foreach ($list as $k => $v) {
                            if(Qlib::isJson($v)){
                                $list[$k] = Qlib::lib_json_array($v);
                            }
                        }
                    }
                    $ret = $list;
            }
        }
        return $ret;
    }
    static public function buscaValorDb($config = false)
    {
        /*Qlib::buscaValorDd([
            'tab'=>'',
            'campo_bus'=>'',
            'valor'=>'',
            'select'=>'',
            'compleSql'=>'',
        ]);
        */
        $ret=false;
        $tab = isset($config['tab'])?$config['tab']:false;
        $campo_bus = isset($config['campo_bus'])?$config['campo_bus']:'id';//campo select
        $valor = isset($config['valor'])?$config['valor']:false;
        $select = isset($config['select'])?$config['select']:false; //
        $compleSql = isset($config['compleSql'])?$config['compleSql']:false; //
        if($tab && $campo_bus && $valor && $select){
            $sql = "SELECT $select FROM $tab WHERE $campo_bus='$valor' $compleSql";
            if(isset($config['debug'])&&$config['debug']){
                echo $sql;
            }
            $d = DB::select($sql);
            if($d)
                $ret = $d[0]->$select;
        }
        return $ret;
    }
}
