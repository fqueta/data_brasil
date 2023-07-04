@extends('adminlte::page')

@section('title', 'Data Brasil - Painel')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 tit-sep" >Painel</h1>
    </div><!-- /.col -->
    <div class="col-sm-6 text-right">
        <div class="btn-group" role="group" aria-label="actions">
            @can('create','familias')
                <a href="{{route('familias.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Novo cadastro</a>
            @endcan
            <a href="{{route('familias.index')}}" class="btn btn-secondary"><i class="fa fa-list"></i> Ver cadastros</a>
        </div>
    </div><!-- /.col -->
</div>
@stop

@section('content')
@include('admin.partes.header')
@can('ler','familias')
{{-- Inicio painel filtro ano --}}
<div class="row">
    <div class="col-md-12 mb-3">
        @include('familias.filtro_ano',['arr_ano'=>@$config['c_familias']['anos']])
    </div>
</div>
{{-- Fim painel filtro ano --}}
<div class="row card-top">
    @if (isset($config['c_familias']['cards_home']))
        <div class="col-md-12 text-center mb-3">
            <h4 class="tit-sep">{{__('Cadastros socioeconômicos')}}</h4>
        </div>
        @foreach ($config['c_familias']['cards_home'] as $k=>$v)
            <div class="col-lg-{{$v['lg']}} col-{{$v['xs']}}">
                    <!-- small box -->
                    <div data-toggle="tooltip" data-html="true" class="small-box bg-{{$v['color']}}" title="{!!$v['obs']!!}">
                    <div class="inner">
                        <h3>{{$v['valor']}}</h3>

                        <p>{{$v['label']}}</p>
                    </div>
                    <div class="icon">
                        <i class="{{$v['icon']}}"></i>
                    </div>
                    <a href="{{$v['href']}}" title="{{__('Ver detalhes de ')}}{{__($v['label'])}}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
        @endforeach
    @endif
    @if (isset($config['card_qualification']) && is_array($config['card_qualification']))
        @foreach ($config['card_qualification'] as $kcf=>$vcf)
        <div class="col-lg-{{$vcf['tam']}} col-6">
            <div data-toggle="tooltip" data-html="true" data-placement="top" class="small-box {{$vcf['color']}}" title="">
            <div class="inner">
                <h3>{{$vcf['total']}}</h3>
                <p>{{$vcf['label']}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-check"></i>
            </div>
            <a href="{{$vcf['link']}}" title="{{$vcf['title']}}" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        @endforeach
    @endif
 </div>
 <div class="row mb-5">
    <div class="col-md-12 text-center  mb-3">
        <div class="col-md-12 text-center  mb-3">
            <h4 class="tit-sep">{{__('Processo Jurídico')}}</h4>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div data-toggle="tooltip" data-html="true" data-placement="top" class="small-box bg-info" title="">
        <div class="inner">
            <h3>{{$config['totalDecretos']}}</h3>
            <p>DECRETOS MUNICIPAIS</p>
        </div>
        <div class="icon">
            <i class="fa fa-check"></i>
        </div>
        <a href="{{route('decretos.index')}}" title="Ver detalhes dos decretos" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div data-toggle="tooltip" data-html="true" data-placement="top" class="small-box bg-warning" title="{!! App\Qlib\Qlib::buscaValorDb(['tab'=>'tags','campo_bus'=>'value','valor'=>'processo_entregue','select'=>'obs']) !!}">
        <div class="inner">
            <h3>{{$config['c_familias']['totProcesso']['entregue']}}</h3>
            <p>PROCESSOS ENTREGUES</p>
        </div>
        <div class="icon">
            <i class="fa fa-check"></i>
        </div>
        <a href="/familias?filter[config][categoria_processo]=processo_entregue" title="Ver detalhes dos processos entregues" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div data-toggle="tooltip" data-html="true" data-placement="top" class="small-box bg-success" title="{!! App\Qlib\Qlib::buscaValorDb(['tab'=>'tags','campo_bus'=>'value','valor'=>'certidao','select'=>'obs']) !!}">
        <div class="inner">
            <h3>{{$config['c_familias']['totProcesso']['certidao']}}</h3>
            <p>CERTIDÕES</p>
        </div>
        <div class="icon">
            <i class="fa fa-check"></i>
        </div>
        <a href="/familias?filter[config][categoria_processo]=certidao" title="Ver detalhes das certidões" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
    <div class="row">
        {{-- @if (isset($config['c_familias']['progresso']))
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <strong>Progresso dos cadastros</strong>
                </div>
                <div class="card-body">
                    @foreach ($config['c_familias']['progresso'] as $k=>$v)
                        <div class="progress-group">
                            {{$v['label']}}
                            <span class="float-right"><b>{{$v['total']}}</b>/{{$v['geral']}}</span>
                            <div class="progress progress-sm">
                                <div class="progress-bar {{$v['color']}}" style="width: {{$v['porcento']}}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif --}}
        @if (isset($config['card_lotes']) && is_array($config['card_lotes']))

            <div class="col-md-12 text-center mb-3">
                <h4 class="tit-sep">{{__('Lotes cadastrados')}}</h4>
            </div>
            @foreach ($config['card_lotes'] as $klotes=>$vlotes)


            <div class="col-lg-4 col-6">
                <div data-toggle="tooltip" data-html="true" data-placement="top" class="small-box {{$vlotes['color']}}" title="">
                <div class="inner">
                    <h3>{{$vlotes['valor']}}</h3>
                    <p> {{$vlotes['label']}} </p>
                </div>
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>
                <a href="{{$vlotes['link_view']}}" title="Ver detalhes dos decretos" class="small-box-footer">Visualizar <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            @endforeach
        @endif
        <div class="col-md-12 text-center mb-3">
            <h4 class="tit-sep">{{__('Cadastros topográficos')}}</h4>
        </div>
        <div class="col-md-12">
            @if (isset($config['mapa']['config']))
                {!! App\Http\Controllers\MapasController::exibeMapas($config['mapa']['config']) !!}
            @endif
        </div>
    </div>
    @else
    <div class="col-md-12">

        <h3>Seja bem vindo para ter acesso entre em contato com o suporte</h3>
    </div>

    @endcan


  </div>
@stop

@section('css')
    @include('qlib.csslib')
    <style>
        .tit-sep{
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
@stop

@section('js')
    @include('qlib.jslib')
    @include('mapas.jslib')
@stop
