@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<div class="row">
  <div class="col-md-12">
    <form class="" action="{{ route('users.update',['id'=>$users->id]) }}" method="post">
      @method('PUT')
      <div classor="grupo">Nome do grupo</label>
        <input type="text" class="form-control" id="name" value="{{$users->name}}" name="name" aria-describedby="name" placeholder="Nome completo">
      </div>
      <div classor="grupo">Email</label>
        <input type="email" class="form-control" id="email" value="{{$users->email}}" name="email" aria-describedby="email" placeholder="E-mail">
      </div>
      <div class=form-group"">
        <a href=" {{route('users.index')}} " class="btn btn-light"> Voltar</a>
        <button type="submit" class="btn btn-primary">Atualizar</button>
      </div>
      @csrf
    </form>
  </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
