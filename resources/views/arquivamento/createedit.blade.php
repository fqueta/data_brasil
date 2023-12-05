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
    <div class="col-md-8">
        <div class="row">
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
                            <div class="row sortable-videos">
                                {{-- <div class="col-md-12 "> --}}
                                    @if (isset($value['videos']['videos_alt']) && is_array($value['videos']['videos_alt']))
                                        @foreach ($value['videos']['videos_alt'] as $kv=>$vv)
                                        <div class="card mb-2 card-{{$kv}} col-md-6">
                                            <div class="card-header">
                                                <i class="fas fa-arrows-alt-v"></i>
                                                <div class="card-tools">
                                                    <button onclick="removeVideoYT('{{$kv}}')" type="button" class="btn btn-default"><i class="fas fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 mb-1">
                                                        <input type="text" placeholder="{{__('Cole aqui o link do video do YouTube')}}" data-id="{{$kv}}" class="form-control" name="meta[videos][]" value="{{$vv['value']}}" />
                                                    </div>
                                                    <div class="col-12 iframe">
                                                        <iframe width="100%" height="215" data-iframe="{{$kv}}" src="{{$vv['src']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="card mb-2 card-0 col-md-6">
                                            <div class="card-header">
                                                <i class="fas fa-arrows-alt-v"></i>
                                                <div class="card-tools">
                                                    <button onclick="removeVideoYT('0')" type="button" class="btn btn-default"><i class="fas fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 mb-1">
                                                        <input type="text" placeholder="{{__('Cole aqui o link do video do YouTube')}}" class="form-control" data-id="0" name="meta[videos][]" value="" />
                                                    </div>
                                                    <div class="col-12 iframe">
                                                        <iframe width="100%" height="215" data-iframe="0" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                {{-- </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <button type="buttom" class="btn btn-primary" id="btn-add-videos"> <i class="fas fa-plus"></i> {{__('Adicionar vídeos')}} </button>
                                    <a href="http://youtube.com" class="btn btn-danger" title="{{__('Acesso ao youtube')}}" target="_blank">
                                        <i class="fab fa-youtube    "></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{__('Imagem de Capa')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @include('admin.media.painel_select_media',['ac'=>'view','value'=>$value])
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
            $('[name="meta[videos][]"]').on('change', function(){
                rendeVideoYT($(this));
            });
            $('#btn-add-videos').on('click', function(e){
                e.preventDefault();
                addVideoYT();
            });
            $('#btn-remove').on('click', function(e){
                e.preventDefault();
                removeVideoYT();
            });
        });

    </script>
    @include('qlib.js_submit',['compleSerialize'=>"'&'+$('#frm-videos').serialize()"]);
@stop
