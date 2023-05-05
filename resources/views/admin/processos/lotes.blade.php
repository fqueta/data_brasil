@php
    $r = explode('.',Route::currentRouteName());
    $route = isset($r[1]) ? $r[1] : false;

@endphp
{{-- {{dd($dados)}} --}}

@if (isset($dados['data_lotes']) && is_array($dados['data_lotes']))
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">
            {{__('Lotes')}}

        </h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{__('Quadra')}}</th>
                    <th>{{__('Lotes')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $dados['data_lotes'] as $k=>$v)
                    <tr>
                        <td>{{@$dados['nome_quadra'][$k]}}</td>
                        <td>
                            @php
                                if(is_array($v)){
                                    // $tm1 = '<table class="table">{tr}</table>';
                                    $tm1 = '{tr}';
                                    // $tm2 = '
                                    // <tr>
                                    //     <td>{nome}</td>
                                    //     <td class="text-right">{acao}</td>
                                    // </tr>';
                                    if($route=='edit'){
                                        $tm2 = '<a href="{href_lote}" style="text-decoration:underline;">{nome}</a>, ';
                                    }else{
                                        $tm2 = '{nome}, ';
                                    }
                                    $tr = false;
                                    $ret = false;
                                    foreach ($v as $k1 => $v1) {
                                        $href_lote = route('lotes.show',['id' => $v1['id']]).'?redirect='.App\Qlib\Qlib::UrlAtual();
                                        $tr .= str_replace('{nome}',$v1['nome'],$tm2);
                                        $tr = str_replace('{href_lote}',$href_lote,$tr);
                                    }
                                    $tr = rtrim($tr,',');
                                    $ret = str_replace('{tr}',$tr,$tm1);
                                    echo $ret;
                                }
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- <div class="card-footer text-muted">

    </div> --}}
</div>

@endif
