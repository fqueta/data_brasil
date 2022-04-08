@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <form class="" action="{{ route('grupos-store') }}" method="post">
        <div classor="grupo">Nome do grupo</label>
          <input type="text" class="form-control" id="grupo" name="grupo" aria-describedby="grupo" placeholder="Nome do grupo">
        </div>
        <div class="form-group">
          <label for="ativo">Ativo</label><br>
          <select class="form-control" name="ativo">
              <option value="s">Ativar</option>
              <option value="n">Destivar</option>
          </select>
        </div>
        <div class="form-group">
          <label for="obs">Observação</label><br>
          <textarea name="obs" class="form-control" rows="8" cols="80"></textarea>
        </div>
        <div class=form-group"">
          <a href=" {{route('grupos-index')}} " class="btn btn-light"> Voltar</a>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
        @csrf
      </form>
    </div>
  </div>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
@stop
