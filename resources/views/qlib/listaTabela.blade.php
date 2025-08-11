@php
    $style = isset($conf['style']) ? $conf['style'] : false;
    $routa = isset($conf['routa']) ? $conf['routa'] : false;
    $redirect = isset($conf['redirect']) ? $conf['redirect'] : @$routa;
    $campos_tabela = isset($conf['campos_tabela']) ? $conf['campos_tabela'] : false;
    $dados = isset($conf['dados']) ? $conf['dados'] : false;
    $sb = '?';
    if(isset($_GET['page'])){
        $sb = '?page='.$_GET['page'].'&';
    }
    $processosController = new App\Http\Controllers\admin\processosController;
    if(isset($_GET['filter'])){
        $urlAtual = App\Qlib\Qlib::urlAtual();
        $urlAtual = rawurldecode($urlAtual);
        $redirect_base = base64_encode($urlAtual);
        $redirect = '&redirect_base='.$redirect_base.'&';
    }else{
        $redirect = route($redirect.'.index').$sb;
    }
@endphp
<style media="print">
    #DataTables_Table_0_wrapper .row:first-child{
        display: none;
    }
    .table td{
        padding: 0%;
    }
    .table thead th{
        padding: 0%;
    }
    #lista .card-body{
        padding: 0%;
    }
</style>
@if ($routa=='familias')
    <style>
        .btn-acao{
            width:10%;
        }
    </style>
@else
    <style>
        .btn-acao{
            width:5%;
        }
    </style>
@endif
<table class="table table-hover table-striped dataTable {{$routa}}" style="{{@$style}}">
    <thead>
        <tr>
            <th class="text-center d-print-none" style="width: 3%"><input onclick="gerSelect($(this));" type="checkbox" name="todos" id=""></th>
            <th class="text-center d-print-none btn-acao">...</th>
            @if (isset($campos_tabela) && is_array($campos_tabela))
                @foreach ($campos_tabela as $kh=>$vh)
                    @if (isset($vh['label']) && $vh['active'])
                        <th style="{{ @$vd['style'] }}">{{$vh['label']}}</th>
                    @endif
                @endforeach

            @else
                <th>#</th>
                <th>Nome</th>
                <th>Area</th>
                <th>Obs</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(isset($dados))
            @foreach($dados as $key => $val)
            @php
                if(isset($val->ID)){
                    $val->id = $val->ID;
                }
                $rlink = 'edit';
                if($routa=='familias'||$routa=='arquivamento-text'||$routa=='arquivamento-videos'||$routa=='decretos'||$routa=='processos-campo'||$routa=='processos-prefeitura'||$routa=='processos-cartorio'||$routa=='processos'||$routa=='users'||$routa=='beneficiarios'||$routa=='lotes'||$routa=='quadras'||$routa=='bairros'){
                    $rlink = 'show';
                }
                $linkShow = route($routa.'.'.$rlink,['id'=>$val->id]). '?redirect='.$redirect.'idCad='.$val->id;
                $linkDbckp = $linkShow;
            @endphp
            <tr style="cursor: pointer" ondblclick="window.location='{{ $linkDbckp}}'"  id="tr_{{$val->id}}" class="@if (isset($_GET['idCad']) && $_GET['idCad']==$val->id) table-info @endif" title="DÊ DOIS CLIQUES PARA ABRIR">
                    <td>
                        <input type="checkbox" class="checkbox" onclick="color_select1_0(this.checked,this.value);" value="{{$val->id}}" name="check_{{$val->id}}" id="check_{{$val->id}}">
                    </td>

                    <td class="text-right d-print-none" style="width:10%">

                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Ação') }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @can('update',$routa)
                                    @if ($routa=='quadras')
                                        @if ((new App\Http\Controllers\MapasController)->verificaMapa($val->id))
                                            <a class="dropdown-item" href="{{ route('mapas.'.$routa,['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}}"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ __('Mapa') }}</a>
                                            {{-- <a title="Mapa" href="  " title="visualizar" class="btn btn-sm btn-outline-secondary mr-2">
                                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            </a> --}}
                                        @else
                                            {{-- <a title="Sem Mapa" href="javascript:void(0)" title="visualizar" class="btn btn-sm btn-outline-warning mr-2" disabled>
                                                <i class="fa fa-map-marker " aria-hidden="true"></i>
                                            </a> --}}
                                        @endif
                                    @endif
                                    @if ($routa=='familias' || $routa=='arquivamento-text' || $routa=='arquivamento-videos' || $routa=='decretos' || $routa=='processos-campo' || $routa=='processos' || $routa=='processos-prefeitura' || $routa=='processos-cartorio' || $routa=='users'||$routa=='beneficiarios'||$routa=='lotes'||$routa=='quadras'||$routa=='bairros')

                                        <a class="dropdown-item" href="{{ $linkShow }}"><i class="fas fa-eye"></i> {{ __('Visualizar') }}</a>
                                        {!!App\Qlib\Qlib::btn_ver_certidao($val->token)!!}
                                    @endif
                                    @php
                                        $linkEdit = $routa.'.edit';
                                        if($routa=='processos'){
                                            $linkEdit = $val->post_type.'.edit';
                                        }
                                    @endphp
                                    <a href=" {{ route($linkEdit,['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="Editar" class="dropdown-item">
                                        <i class="fas fa-pen"></i> {{ __('Editar') }}
                                    </a>
                                    @else
                                    @if ($routa=='familias' || $routa=='decretos' || $routa=='processos-campo')
                                        {{-- <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}
                                        <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('Visualizar') }}
                                        </a>
                                        {!!App\Qlib\Qlib::btn_ver_certidao($val->token)!!}
                                    @else
                                        {{-- <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " class="btn btn-sm btn-outline-primary" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}

                                        <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="{{ __('Visualizar') }}" class="dropdown-item">
                                            <i class="fas fa-eye"></i> {{ __('Visualizar') }}
                                        </a>
                                    @endif

                                @endcan
                                @can('delete',$routa)
                                    <form id="frm-{{ $val->id }}" action="{{ route($routa.'.destroy',['id'=>$val->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-del="true" data-id="{{$val->id}}" name="button" title="Excluir" class="dropdown-item">
                                        <i class="fas fa-trash    "></i> {{ __('Excluir') }}
                                        </button>
                                    </form>
                                @endcan



                            </div>
                        </div>
                    </td>
                @if (isset($campos_tabela) && is_array($campos_tabela))
                    @foreach ($campos_tabela as $kd=>$vd)
                        @if (isset($vd['label']) && $vd['active'])
                            @php
                            if($vd['type']=='textarea'){

                               // dd($vd);
                            }
                            @endphp

                            @if (isset($vd['type']) && ($vd['type']=='select' || $vd['type']=='selector'))
                                @php
                                    if(isset($vd['cp_busca']) && !empty($vd['cp_busca'])){
                                        $cp = explode('][',$vd['cp_busca']);
                                        $kr = @$val[$cp[0]][$cp[1]];
                                        $td = @$vd['arr_opc'][$kr];
                                    }else{
                                        $td = @$vd['arr_opc'][$val->$kd];
                                    }
                                @endphp
                                <td class="{{str_replace('[]','',$kd)}}" title="{{@$vd['arr_opc'][$val->$kd]}}">{{$td}}

                                </td>
                            @elseif (isset($vd['type']) && ($vd['type']=='select_multiple'))
                                @php
                                // echo $kd;
                                $nk = str_replace('[]','',$kd);

                                $arr = $val->$nk;
                                if(isset($vd['cp_busca'])){
                                    $ak = explode('][',$vd['cp_busca']);
                                    if(isset($ak[1])&&!empty($ak[1])){
                                        $kd = $ak[1];
                                        $arr = @$val[$ak[0]][$ak[1]];
                                    }
                                }
                                $td = false;
                                if(is_array($arr)){
                                        foreach ($arr as $k => $v) {
                                            $td .= @$vd['arr_opc'][$v].',';
                                        }
                                    }
                                @endphp
                                <td class="{{str_replace('[]','',$kd)}}" title="{{@$td}}">{{@$td}}</td>
                            @elseif (isset($vd['type']) && $vd['type']=='chave_checkbox' && isset($vd['arr_opc'][$val->$kd]))
                                <td class="{{str_replace('[]','',$kd)}}" title="{{$vd['arr_opc'][$val->$kd]}}">{{$vd['arr_opc'][$val->$kd]}}</td>
                            @elseif (isset($vd['type']) && $vd['type']=='date')
                                <td class="{{str_replace('[]','',$kd)}}" title="{{$val->$kd}}">{{ Carbon\Carbon::parse($val->$kd)->format('d/m/Y')}}</td>
                            @elseif(isset($vd['cp_busca']) && !empty($vd['cp_busca']))
                                @php
                                    $cp = explode('][',$vd['cp_busca']);
                                    $td=false;
                                    if($vd['type'] == 'text_disabled'){
                                        if($cp[1]=='ocupantes'){
                                            $td = $processosController->ocupantes($val->ID);
                                        }
                                        if($cp[1]=='calculadora_dias'){
                                            $td = $processosController->calcula_dias(false,$val);
                                        }
                                    }else{
                                        $td =@$val[$cp[0]][$cp[1]];
                                    }
                                @endphp
                                @if (isset($cp[1]))
                                    <td class="{{$cp[1]}}" title="{{ @$val[$cp[0]][$cp[1]] }}">{{ $td }}</td>
                                @endif
                            @else
                                @php
                                    if(isset($vd['arr_opc']) && isset($vd['arr_opc'][$val->$kd])){
                                        $td = $vd['arr_opc'][$val->$kd];
                                    }else{
                                        $td = $val->$kd;
                                    }
                                @endphp

                                <td class="{{str_replace('[]','',$kd)}}" title="{{$td}}">
                                    {!!$td!!}
                                </td>
                            @endif
                        @endif
                    @endforeach
                @else

                    <td> {{$val->id}} </td>
                    <td> {{$val->nome_completo}} </td>
                    <td> {{$val->area_alvo}} </td>
                    <td> {{$val->obs}} </td>
                @endif
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
