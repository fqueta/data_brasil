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
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Solte arquivos aqui para</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="min-height: 400px">
                <div class="container-fluid">
                    <form id="file-upload" action="{{route('uploads.store')}}" method="post" class="dropzone" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="token_produto" value="{{@$config['token_produto']}}" />
                        <input type="hidden" name="pasta" value="{{@$config['pasta']}}" />
                        <input type="hidden" name="arquivos" value="{{@$config['arquivos']}}" />
                        <input type="hidden" name="typeN" value="{{@$config['typeN']}}" />
                        <div class="fallback">
                            <input name="file" type="file" multiple />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- @if($config['ac']=='alt')
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                @if ($config['sec']=='decretos')
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
                            'pasta'=>'posts/'.date('Y').'/'.date('m'),
                            'token_produto'=>$value['token'],
                            'tab'=>'posts',
                            'listFiles'=>@$listFiles,
                            'routa'=>@$config['route'],
                            'arquivos'=>@$config['arquivos'],
                        ])}}
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{__('Imagem Destacada')}}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(App\Qlib\Qlib::qoption('i_wp')=='s')
                            {{App\Qlib\Qlib::gerUploadWp([
                                'pasta'=>$config['route'].'/'.date('Y').'/'.date('m'),
                                'id'=>@$config['id'],
                                'token_produto'=>$value['token'],
                                'tab'=>$config['route'],
                                'listFiles'=>@$listFiles,
                                'routa'=>@$config['route'],
                                'arquivos'=>@$config['arquivos'],
                                'typeN'=>@$config['typeN'],
                            ])}}

                        @else
                            <form id="imagem-detacada" action="">
                                <div class="input-group mb-3" style="max height: 250px;min-height:200px">
                                    <img id="holder" style="width:100%;" src="{{@$value[imagem_destacada]->meta_value}}">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-outline-primary">
                                            <i class="fas fa-file-image"></i> {{__('Inserir')}}
                                        </a>
                                        <a id="lfm-remove" class="btn btn-outline-danger d-none">
                                            <i class="fas fa-trash" onclick="lib_removeImageLfm(this);"></i> {{__('Remover')}}
                                        </a>
                                    </span>
                                    <input id="thumbnail" onchange="lib_carregaImageLfm(this)" class="form-control" type="hidden" name="d_meta[meta_value]" value="{{@$value[imagem_destacada]->meta_value}}">
                                    <input class="form-control" type="hidden" name="d_meta[meta_key]" value="imagem_destacada">
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if ($config['sec']!='decretos')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{__('Categoria(s)')}}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{__('Galeria(s)')}}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif --}}
</div>

@stop

@section('css')
    <style>
        .interface-interface-skeleton__sidebar{
            display: none;
        }
    </style>
        @include('qlib.csslib')
        <link rel="stylesheet" href="{{url('/css/dropzone.min.css')}}">
    @stop

    @section('js')
    @include('qlib.jslib')
    <script src="{{url('/js/dropzone.min.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
            // $('.dropzone').dropzone();
            $('[mask-cpf]').inputmask('999.999.999-99');
            $('[mask-data]').inputmask('99/99/9999');
            $('[mask-cep]').inputmask('99.999-999');
            var myDropzone = $(".dropzone").dropzone({
                // url: $(this).attr('action'),
                error: function (file, response) {
                    console.log("Erro");
                    console.log(response);
                },
                success: function (file, response) {
                    console.log("Sucesso");
                    console.log(response);

                },
                complete: function (file) {
                    console.log("Complete");
                }
            });
        });
    </script>
    {{-- @include($config['view'].'.js_submit') --}}
@stop
