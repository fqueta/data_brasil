@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if(isset($users['image']) && !empty($users['image']))
                    <img class="profile-user-img img-fluid img-circle" src="{{ url("storage/{$users['image']}") }}" alt="{{$users['image']}}">
                    @else
                    <img class="profile-user-img img-fluid img-circle" src="{{ $users['gender'] == 'female' ? 'https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg' : 'https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg' }}"
                        alt="User profile picture">
                    @endif
                </div>
                <h3 class="profile-username text-center">{{$users['name']}}</h3>
                <p class="text-muted text-center">{{$users['profile']}}</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Status</b> <a class="float-right">{{$users['status']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Gênero</b> <a class="float-right">{{$users['gender']}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>E-mail</b> <a class="float-right">{{$users['email']}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-12 div-salvar">
      <div class="row">
        <div class="col-6">
          <a href=" {{route('users.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          <a href=" {{route('users.edit',['id'=>$users['id']])}} " class="btn btn-primary">Editar <i class="fa fa-chevron-right"></i></a>
          <span style="position: absolute;top: 0px;left: 171px;">
            <form action="{{ route('users.destroy',['id'=>$users['id']]) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" name="button" title="Excluir" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                  </svg>
              </button>
            </form>
          </span>
        </div>
        <div class="col-6 text-right">


        </div>
      </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
          });
    </script>
@stop
