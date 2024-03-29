@extends('adminlte::page')

@section('title')
    {{$title}}
@stop
@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mens">
    </div>
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informações</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{App\Qlib\Qlib::formulario([
                    'campos'=>$campos,
                    'config'=>$config,
                    'value'=>$value,
                ])}}

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12 {{@$displayCertidao}}" id="arquivo-certidao">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Certidão</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{App\Qlib\Qlib::gerUploadAquivos([
                            'pasta'=>'familias/'.date('Y').'/'.date('m'),
                            'token_produto'=>$value['token'],
                            'tab'=>'familias',
                            'listFiles'=>@$listCertidao,
                            'routa'=>@$routa,
                            'categoria'=>'certidao',
                            'arquivos'=>@$config['arquivos_certidao'],
                        ])}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Arquivos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{App\Qlib\Qlib::gerUploadAquivos([
                            'pasta'=>'familias/'.date('Y').'/'.date('m'),
                            'token_produto'=>$value['token'],
                            'tab'=>'familias',
                            'listFiles'=>@$listFiles,
                            'routa'=>@$routa,
                            'categoria'=>'arquivos',
                            'arquivos'=>@$config['arquivos'],
                        ])}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    @include('qlib.csslib')
    @php
        if(isset($value['tags[]'])&&is_array($value['tags[]'])){
            if(in_array(3,$value['tags[]'])){
                $exibeCategoria = '';
            }else{
                $exibeCategoria = '[div-id="config[categoria_pendencia]"]{display:none}';
            }
            if(in_array(10,$value['tags[]'])){
                $exibeProcesso = '';
            }else{
                $exibeProcesso = '[div-id="config[categoria_processo]"]{display:none}';
            }
        }else{
            $exibeCategoria = '[div-id="config[categoria_pendencia]"]{display:none}';
            $exibeProcesso = '[div-id="config[categoria_processo]"]{display:none}';
        }
    @endphp
    <style>
        .note-table,.note-insert,.note-view{
            display: none;
        }
        /* {!!$exibeCategoria!!}
        {!!$exibeProcesso!!} */
    </style>

@stop

@section('js')
    @include('qlib.jslib')
    <script type="text/javascript">

    $(function(){
        $('a.print-card').on('click',function(e){
            openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
        });
        $('#inp-cpf,#inp-cpf_conjuge').inputmask('999.999.999-99');
            /*if (isset($config['ac']) && $config['ac']=='alt')
                let btn_ficha_ocupante = '<a title="Imprimir a ficha de ocupante" href="javascript:abrirjanelaPadraoConsulta(\'https://cmd.databrasil.app.br/lotes/ficha-ocupantes/797/1177\')" class="btn btn-outline-secondary"><i class="fa fa-print" aria-hidden="true"></i> {{__('Ficha de ocupante')}}</a>';

            endif*/
          });
    </script>
    @include('qlib.js_submit')
@stop
