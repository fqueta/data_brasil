@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
                <div class="card-header">
                    <h1 class="card-title">{{__('Informações')}}</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                    @if (isset($campos) && is_array($campos))
                        @foreach ($campos as $k=>$v)
                        @if ($v['type']=='text')
                            <div class="col-{{$v['tam']}}">
                                <label for="{{$k}}">{{$v['label']}}:</label>
                                {{@$value[$k]}}
                            </div>
                        @elseif ($v['type']=='select' || $v['type']=='selector')
                            <div class="col-{{$v['tam']}}">
                                <label for="{{$k}}">{{$v['label']}}:</label>
                                {{@$v['arr_opc'][$value[$k]]}}
                            </div>
                        @elseif ($v['type']=='html_vinculo')
                            @if (@$v['script_show'])
                                @php
                                    $config = [
                                        'label'=>$campos[$k]['label'],
                                        'value'=>$value[$k],
                                    ];
                                @endphp
                                @if(isset($config))
                                    @include($v['script_show'],$config)
                                @else
                                    @include($v['script_show'])
                                @endif
                            @endif
                        @elseif ($v['type']=='html')
                            @php
                                $config = $campos[$k];
                                $config['col']
                                //dd($config);
                                //$config['value'] = $value[$k];
                            @endphp
                            <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
                                <div class="card card-secondary">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            {{__($config['label'])}}
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                    <div class="row" id="row-{{$config['data_selector']['campo']}}">
                                            <div class="col-6 mb-2 btn-consulta-vinculo">
                                                <button type="button" class="btn btn-default btn-block" data-toggle="button" aria-pressed="false" onclick="lib_abrirModalConsultaVinculo('{{@$config['data_selector']['campo']}}','abrir');"> <i class="fa fa-search" aria-hidden="true"></i> {{__('Usar cadastrado')}}</button>
                                            </div>
                                            <div class="col-6 mb-2 btn-voltar-vinculo" style="display: none">
                                                <button type="button" class="btn btn-default btn-block" data-toggle="button" aria-pressed="false" onclick="lib_abrirModalConsultaVinculo('{{@$config['data_selector']['campo']}}','fechar');">
                                                    <span class="pull-left">
                                                        <i class="fa fa-chevron-left " aria-hidden="true"></i> {{__('Voltar')}}
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <button type="button" class="btn btn-outline-primary btn-block" data-ac="{{$config['ac']}}" data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}" onclick="lib_vinculoCad($(this));" > <i class="fa fa-plus" aria-hidden="true"></i> {{__('Cadastrar')}}</button>
                                            </div>
                                            <div class="col-md-12 mb-2" style="display: none;" id="inp-cad-{{$config['data_selector']['campo']}}">
                                                <input id="inp-auto-{{$config['data_selector']['campo']}}" type="text"
                                                    url="{{$config['data_selector']['route_index']}}"
                                                    class="autocomplete form-control"
                                                    data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}"
                                                    placeholder="{{__(@$config['data_selector']['placeholder'])}}"
                                                    onclick="this.value=''"
                                                    />
                                            </div>

                                            @if ($config['script'])
                                                @if(isset($config['dados']))
                                                    @include($config['script'],@$config['dados'])
                                                @else
                                                    @include($config['script'])
                                                @endif
                                            @endif
                                            @if (isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                                            <div class="col-md-12 ">
                                                @php
                                                    $tema = '<td id="td-{k}">{v}</td>';
                                                    @endphp
                                                <tm class="d-none">{{$tema}}</tm>
                                                <table class="table table-hover" id="table-{{$config['type']}}-{{$config['data_selector']['campo']}}">
                                                    <thead>
                                                        <tr>
                                                            @foreach ($config['data_selector']['table'] as $kh=>$vh)
                                                            <th id="th-{{$kh}}">{{$vh['label']}}</th>
                                                            @endforeach
                                                            <th class="text-right">{{__('Ação')}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if (isset($config['data_selector']['list']) && is_array($config['data_selector']['list']) && isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                                                        @if (@$config['data_selector']['tipo']=='array')
                                                            @foreach ($config['data_selector']['list'] as $klis=>$vlis)
                                                                <tr id="tr-{{$klis}}-{{$config['data_selector']['list'][$klis]['id']}}"><input id="inp-{{$klis}}-{{$config['data_selector']['list'][$klis]['id']}}" type="hidden" name="{{$config['campo']}}[]" value="{{$config['data_selector']['list'][$klis]['id']}}">
                                                                    @foreach ($config['data_selector']['table'] as $kb=>$vb)
                                                                        @if ($vb['type']=='text')
                                                                            <td id="td-{{$kb}}">{{$config['data_selector']['list'][$klis][$kb]}}</td>
                                                                        @elseif ($vb['type']=='arr_tab')
                                                                            <td id="td-{{$kb}}_valor">{{$config['data_selector']['list'][$klis][$kb.'_valor']}}</td>
                                                                        @endif
                                                                    @endforeach
                                                                    <td class="text-right">
                                                                        <button type="button" btn-alt onclick="lib_htmlVinculo('alt','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}','{{$klis}}')" title="{{__('Editar')}}" class="btn btn-outline-secondary"><i class="fas fa-pencil-alt"></i> </button>
                                                                        <button type="button" onclick="lib_htmlVinculo('del','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}','{{$klis}}')" class="btn btn-outline-danger" title="{{__('Remover')}}" > <i class="fa fa-trash" aria-hidden="true"></i> </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr id="tr-{{$config['data_selector']['list']['id']}}"><input type="hidden" name="{{$config['campo']}}" value="{{$config['data_selector']['list']['id']}}">
                                                                @foreach ($config['data_selector']['table'] as $kb=>$vb)
                                                                    @if ($vb['type']=='text')
                                                                        <td id="td-{{$kb}}">{{$config['data_selector']['list'][$kb]}}</td>
                                                                    @elseif ($vb['type']=='arr_tab')
                                                                        <td id="td-{{$kb}}_valor">{{$config['data_selector']['list'][$kb.'_valor']}}</td>
                                                                    @endif
                                                                @endforeach
                                                                <td class="text-right">
                                                                    <button type="button" btn-alt onclick="lib_htmlVinculo('alt','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}')" title="{{__('Editar')}}" class="btn btn-outline-secondary"><i class="fas fa-pencil-alt"></i> </button>
                                                                    <button type="button" onclick="lib_htmlVinculo('del','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}')" class="btn btn-outline-danger" title="{{__('Remover')}}" > <i class="fa fa-trash" aria-hidden="true"></i> </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                    </tbody>
                                                </table>
                                                @endif
                                            </div>
                                    </div>
                                    </div>
                                    <div class="card-footer text-muted">
                                        {{@$footer}}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    @endif
                    </div>
                </div>
                <div class="card-footer text-muted d-print-none">
                    Footer
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    @include('qlib.csslib')
@stop

@section('js')
    @include('qlib.jslib')
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
            $('#inp-cpf,#inp-cpf_conjuge').inputmask('999.999.999-99');
          });
    </script>
    include('qlib.js_submit')
@stop
