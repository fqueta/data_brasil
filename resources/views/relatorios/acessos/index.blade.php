@extends('adminlte::page')
@section('title', $title)

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
  <!--<p>Selecione os publicadores do seu familia para enviar o relatorio para o secretário</p>-->
  <style media="print">
      #DataTables_Table_0_wrapper .row:first-child{
          display: none;
      }
      .table td{
          padding: 0%;
      }
      .table thead th{
          padding: 0%;
      }
      #lista .card-body{
          padding: 0%;
      }
  </style>
  <div class="row">
    {{-- @include('relatorios.config_exibe') --}}
    <div class="col-md-12 mens">
    </div>
    @can('is_admin')
    <div class="col-md-12 d-print-none">
      {{-- <div class="row pl-2 pr-2">
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Total de Famílias</span>
                  <span class="info-box-number">{{ @$familia_totais->todos }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-calendar"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Cadastros deste Mês</span>
                  <span class="info-box-number">{{ @$familia_totais->esteMes }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-male"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Famílias com Idosos</span>
                  <span class="info-box-number">{{ @$familia_totais->idoso }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-child"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Crianças e adolescentes</span>
                  <span class="info-box-number">{{ @$familia_totais->criancas }}</span>
              </div>
          </div>
      </div> --}}

    </div>
@endcan
    <div class="col-md-12" id="lista">
      <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @if (!empty($arr_titulo))
                    Lista de:

                    @foreach ($arr_titulo as $k=>$pTitulo)
                        <label for=""> Todo com {{ $k }}</label> = {{ $pTitulo }}, e
                    @endforeach
                @else
                    {{ $titulo_tabela }}
                @endif
            </h4>

            @can('is_admin')
            <div class="card-tools d-flex d-print-none">
                    {{-- @include('relatorios.dropdow_actions') --}}
                    <!--include('qlib.dropdow_acaomassa')-->
            </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- {{App\Qlib\Qlib::listaTabela([
                    'campos_tabela'=>$campos_tabela,
                    'config'=>$config,
                    'dados'=>$dados,
                    'routa'=>$routa,
                    'redirect'=>$redirect,
                ])}} --}}
                @include('qlib.menus.datas')
            </div>
        </div>
        <div class="card-footer d-print-none">
            <div class="table-responsive">
                {{-- @if ($config['limit']!='todos')
                {{ $familias->appends($_GET)->links() }}
                @endif --}}
            </div>
        </div>
      </div>
    </div>
  </div>
  @stop

  @section('css')
      <link rel="stylesheet" href="{{url('/')}}/css/lib.css">
  @stop

  @section('js')
    @include('qlib.jslib')
    <script>
        $(function(){
            $('.dataTable').DataTable({
                "paging":   false,
                stateSave: true
            });
        });
    </script>
   @stop
