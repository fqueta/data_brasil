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
                        @elseif ($v['type']=='html')
                            @if ($v['script'])
                                @if(isset($value['dados']))
                                    @include($v['script'],@$v['dados'])
                                @else
                                    @include($v['script'])
                                @endif
                            @endif
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
    @include('qlib.js_submit')
@stop
