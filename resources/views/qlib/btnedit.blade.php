
@php
    if(isset($config['ac']) && $config['ac']=='alt'){
        $_GET['redirect'] = isset($_GET['redirect']) ? $_GET['redirect'] : route($config['route'].'.index').'?idCad='.@$value['id'];
        if(isset($_GET['redirectPos'])&&$_GET['redirectPos']=='n'){
            $_GET['redirect'] = false;
        }
    }
    if(isset($_GET['redirect_base'])&&!empty($_GET['redirect_base'])){
        $redirect_base = base64_decode($_GET['redirect_base']);
        $redirect_base .= '&idCad='.@$_GET['idCad'];
    }
@endphp
<div class="col-md-12 div-salvar bg-light d-print-none">
        @if (isset($redirect_base) && $redirect_base)
            <a href="{{$redirect_base}}"  btn-volter="true" redirect="{{@$redirect_base}}" class="btn btn-outline-secondary"><i class="fa fa-chevron-left"></i> Voltar</a>
        @else
           <button type="button" btn-volter="true" href="{{route($config['route'].'.index')}}" onclick="btVoltar($(this))" redirect="{{@$_GET['redirect']}}" class="btn btn-outline-secondary"><i class="fa fa-chevron-left"></i> Voltar</button>
        @endif
        @if (isset($config['ac']) && $config['ac']=='alt')
            @can('create',$config['route'])
                <a href="{{route($config['route'].'.create')}}" class="btn btn-default"> <i class="fas fa-plus"></i> Novo cadastro</a>
            @endcan
            @if($config['route']=='users' && isset($config['local']) && $config['local']=='perfil')
                <a href="{{route('sistema.perfil.edit')}}?redirect={{App\Qlib\Qlib::urlAtual()}}" btn="editar"  class="btn btn-outline-primary"> <i class="fa fa-pen" aria-hidden="true"></i> Editar</a>
            @else
                @can('update',$config['route'])
                    <button type="button" btn="print" onclick="window.print();" class="btn btn-outline-primary"><i class="fas fa-print"></i></button>
                    <a href="{{route($config['route'].'.edit',['id'=>$config['id']])}}?redirect={{App\Qlib\Qlib::urlAtual()}}" btn="editar"  class="btn btn-outline-primary"> <i class="fa fa-pen" aria-hidden="true"></i> Editar</a>
                @endcan
            @endif
        @else

        @endif
</div>
