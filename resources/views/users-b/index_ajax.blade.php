@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop
@section('content')
  <p>The .table-bordered class adds borders on all sides of the table and the cells:</p>
  <div class="row">
    <div class="col-md-12 text-right mb-3">
      <a href="{{ route('users.create') }}" class="btn btn-success"> Novo user </a>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <table id="list-users" class="table dtr-inline table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th class="text-center">...</th>
              </tr>
            </thead>

          </table>
        </div>
      </div>

    </div>
  </div>
  @stop

  @section('css')
      <link rel="stylesheet" href="/css/admin_custom.css">
  @stop

  @section('js')
  <script>
    $(function(){
        $('#list-users').DataTable({
            processing : true,
            serverSide : true,
            "ajax": "{{route('users.ajax')}}",
            columns : [
              {"data":"id"},
               {"data":"name"},
               {"data":"email"},
            ]
        });
    });
    function carregaUsuarios()
     {
        $("#exemplo").dataTable().fnDestroy();
        $('#exemplo').DataTable({
                    processing :true,
                    serverSide:true,
                    //searching:false,
                    ajax: {
                           url: "{{route('users.ajax')}}",
                           method: 'GET',
                           data: {
                                 tipoCadastro: $('#idTipo').val()
                           }
                        },
                    columns : [
                             {"data":"NmRazao" },
                             {"data":"NmFantasia" },
                             {"data":"Email" },
                             {"data":"..." },
                           ]
                   });
     }
  </script>
@stop
