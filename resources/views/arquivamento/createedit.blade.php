@extends('adminlte::page')

@section('title')
{{config('app.name')}} {{config('app.version')}} - {{$titulo}}
@stop

@section('footer')
    @include('footer')
@stop

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')

@include('admin.partes.header')
<div class="row">
    <div class="col-md-12 mens">
    </div>
    <div class="col-md-7">
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
    <div class="col-md-5">
        <div class="row">
            <div class="col-12">
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
                            'pasta'=>$config['route'].'/'.date('Y').'/'.date('m'),
                            'token_produto'=>$value['token'],
                            'tab'=>$config['route'],
                            'listFiles'=>@$listFiles,
                            'routa'=>@$config['route'],
                            'arquivos'=>@$config['arquivos'],
                            'typeN'=>@$config['typeN'],
                        ])}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Videos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="frm-videos" method="post">
                            <div class="row">
                                <div class="col-md-12 sortable-videos">
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <i class="fas fa-arrows-alt-v"></i>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    <input type="text" class="form-control" name="meta[videos][]" value="https://www.youtube.com/embed/1WBSkly9G7c?si=Tt_ZkK4jl4hHh7Kl" />
                                                </div>
                                                <div class="col-12 iframe">
                                                    <iframe width="100%" height="215" src="https://www.youtube.com/embed/1WBSkly9G7c?si=Tt_ZkK4jl4hHh7Kl" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <i class="fas fa-arrows-alt-v"></i>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    <input type="text" class="form-control" name="meta[videos][]" value="https://www.youtube.com/embed/ZvSPrDMTqMA" />
                                                </div>
                                                <div class="col-12 iframe">
                                                    <iframe width="100%" height="215" src="https://www.youtube.com/embed/ZvSPrDMTqMA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 ">
                                    <button type="buttom" class="btn btn-primary" id="btn-add-videos"> <i class="fas fa-plus"></i> {{__('Adicionar vídeos')}} </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
            $('[mask-cpf]').inputmask('999.999.999-99');
            $('[mask-data]').inputmask('99/99/9999');
            $('[mask-cep]').inputmask('99.999-999');
            $('.sortable-videos').sortable();
        });

    </script>
    @include('qlib.js_submit',['compleSerialize'=>"'&'+$('#frm-videos').serialize()"]);
@stop
