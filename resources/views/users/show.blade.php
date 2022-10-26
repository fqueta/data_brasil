@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 52px;
    }
    ul.timeline > li:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }
    .card-h{
        height: 350px
    }
</style>
<div class="row">
    <div class="col-md-12 mens">
    </div>
    <div class="col-md-8">
        <div class="card card-primary card-outline d-flex">
            <div class="card-header">
                <h3 class="card-title">{{__('Informações')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body card-h">
                {{App\Qlib\Qlib::show([
                    'campos'=>$campos,
                    'config'=>$config,
                    'value'=>$value,
                ])}}
            </div>
            <div class="card-footer">&nbsp;</div>
        </div>
        {{-- @include('qlib.show_files') --}}
    </div>
    @if(isset($eventos) && is_object($eventos))
    <div class="col-md-4 mt-0 mb-5">

        <div class="row">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">{{__('Histórico de Acessos')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body card-h overflow-auto mw-100 d-flex">
                    <ul class="timeline">
                        @foreach ($eventos as $ke=>$ve)
                            @php
                                $conf = App\Qlib\Qlib::lib_json_array($ve['config']);
                                $dt = App\Qlib\Qlib::dataExibe($ve['created_at']);
                                $data = explode('-',$dt);
                                $title = isset($conf['obs'])?$conf['obs']:false;
                                // $createdAt = Illuminate\Support\Carbon::parse($item['created_at']);
                                // dd($createdAt);
                            @endphp
                            <li>
                                <a target="_blank" href="https://www.totoprayogo.com/#">Evento {{$ve['action']}}</a>
                                <a href="#" class="float-right">{{$data[0]}}</a>
                                {{-- <p>&nbsp;</p> --}}
                                <p>Em {{$dt}} o sistema registrou <b>{{$title}}</b></p>
                            </li>
                        @endforeach
                        {{-- <li>
                            <a href="#">21 000 Job Seekers</a>
                            <a href="#" class="float-right">4 March, 2014</a>
                            <p>Curabitur purus sem, malesuada eu luctus eget, suscipit sed turpis. Nam pellentesque felis vitae justo accumsan, sed semper nisi sollicitudin...</p>
                        </li>
                        <li>
                            <a href="#">Awesome Employers</a>
                            <a href="#" class="float-right">1 April, 2014</a>
                            <p>Fusce ullamcorper ligula sit amet quam accumsan aliquet. Sed nulla odio, tincidunt vitae nunc vitae, mollis pharetra velit. Sed nec tempor nibh...</p>
                        </li> --}}
                    </ul>
                </div>
                <div class="card-footer">&nbsp;</div>
            </div>
        </div>
    </div>
    @endif
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
@stop

