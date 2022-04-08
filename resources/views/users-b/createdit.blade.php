@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<form class="" action="@if($users['ac']=='cad'){{ route('users.store') }}@elseif($users['ac']=='alt'){{ route('users.update',['id'=>$users['id']]) }}@endif" method="post" enctype="multipart/form-data">
    @if($users['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
      <div class="form-group col-md-12">
        <label for="role">Tipo de usuário</label>
        <select class="form-control" name="profile">
          <option @if(isset($users['profile'])&&$users['profile']=='admin') selected @endif value="admin">Adminstrador</option>
          <option @if(isset($users['profile'])&&$users['profile']=='user') selected @endif value="user">Usuário</option>
        </select>
      </div>
      <div class="form-group col-md-12">
          <label for="nome">Nome completo</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" aria-describedby="name" placeholder="Nome do usuário" value="@if(isset($users['name'])){{$users['name']}}@elseif($users['ac']=='cad'){{old('name')}}@endif" />
          @error('nome')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label for="email">E-mail</label>
          <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-describedby="email" placeholder="E-mail válido" value="@if(isset($users['email'])){{$users['email']}}@elseif($users['ac']=='cad'){{old('email')}}@endif" />
          @error('email')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>

      <div class="form-group col-md-12">
          <label for="password">Senha</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-describedby="password" placeholder="Senha" value="" />
          @error('password')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-6">
          <label for="ativo">Status</label>
          <select class="form-control" name="status">
            <option value="actived" @if(isset($users['status'])&&$users['status']=='actived') selected @endif >Ativado</option>
            <option value="inactived"  @if(isset($users['status'])&&$users['status']=='inactived') selected @endif >Inativo</option>
            <option value="pre_registred"  @if(isset($users['status'])&&$users['status']=='pre_registred') selected @endif >Pré cadastrado</option>
          </select>
      </div>
      <div class="form-group col-md-6">
          <label for="ativo">Genero</label>
          <select class="form-control" name="gender">
            <option value="male" @if(isset($users['gender'])&&$users['gender']=='male') selected @endif >Masculino</option>
            <option value="female"  @if(isset($users['gender'])&&$users['gender']=='female') selected @endif >Feminino</option>
          </select>
      </div>
      <div class="form-group col-md-12 mb-5">
        @include('admin.partes.form_img')
      </div>
      <div class="col-md-12 div-salvar">
        <div class=form-group"">
          <a href=" {{route('users.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
        </div>
      </div>
      @csrf
    </div>
</form>
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
