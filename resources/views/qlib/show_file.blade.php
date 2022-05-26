<div class="row">
    <style>
        .grade-img img{
            object-fit: cover;
            height: 150px;
        }
    </style>
    @if (isset($files)&&is_object($files))
        @foreach ($files as $k=>$file)
            @php
                if(isset($file->config)){
                    $file->config = App\Qlib\Qlib::lib_json_array($file->config);
                    //App\Qlib\Qlib::lib_print($file->config);
                    $dominio_arquivo=App\Qlib\Qlib::qoption('dominio_arquivos').'/';
                }
            @endphp
            <div class="col-md-2 grade-img mb-2">
                <a href="{{$dominio_arquivo.$file->pasta}}" data-maxwidth="80%" title="{{$file->nome}}" data-gall="gall1" class="venobox">
                    <img src="{{$dominio_arquivo.$file->pasta}}" class="shadow w-100" alt="{{$file->nome}}" srcset="">
                </a>

            </div>
        @endforeach
    @endif
</div>
