<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{
    public function realidadeSocial($config = null)
    {
        $dados = $this->Rel_Qsocial($config);
        dd($dados);
        $ret = false;

    }
    public function Rel_Qsocial($config = null)
    {
        $dados = Familia::join('quadras','quadras.id','familias.quadra')->
        where('familias.excluido','=','n')->where('familias.deletado','=','n')->paginate();
        $ret = [
            'dados'=>$dados,
        ];
        return $ret;
    }
}
