@php
    // dd($dados);
@endphp
<style>
    .btn-acao{
        position: absolute;
        top: 2px;
        right: 2px;
        background-color: rgb(0, 0, 0,0.6);
        border-radius: 2px;
    }
    .grid .card{
        height: 252px;
    }
    .grid img{
        max-height: 200px;
        object-fit: cover;
        object-position: center;
    }
</style>
<div class="row">
    <div class="col-12 mb-4">
        <h5> {{__('Lista de arquivos')}} </h5>
    </div>
</div>
<div class="row">
    @if(isset($dados))
        @foreach($dados as $key => $val)
            @php
                $val = $val->toArray();
            @endphp
            <div class="col-md-3 col-6 grid mb-3">
                <div class="card text-left">
                    <a href="{{route($routa.'.show',['id'=>$val['ID']])}}">
                        <img class="card-img-top" src="{{$val['link_thumbnail']}}" alt="{{$val['post_title']}}">
                    </a>
                    <div class="card-body">
                        <h4 class="card-title"><a href="{{route($routa.'.show',['id'=>$val['ID']])}}">{{$val['post_title']}}</a></h4>
                        <div class="d-flex btn-acao">
                        @can('update', $routa)
                            <a href="{{route($routa.'.edit',['id'=>$val['ID']])}}" class="btn btn-outline-light" title="{{__('Editar')}}"> <i class="fas fa-pen"></i> </a>


                        @endcan
                        @can('delete',$routa)
                                <form id="frm-{{ $val['ID'] }}" action="{{ route($routa.'.destroy',['id'=>$val['ID']]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-del="true" data-id="{{$val['ID']}}" name="button" title="Excluir" class="btn btn-outline-light">
                                        <i class="fas fa-trash    "></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
