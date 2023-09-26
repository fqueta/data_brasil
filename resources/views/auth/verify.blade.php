{{-- @extends('adminlte::auth.verify') --}}
@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('adminlte::adminlte.verify_message'))

@section('auth_body')

    @if(session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('adminlte::adminlte.verify_email_sent') }}
        </div>
    @endif
    @php
      $send = session('message-very');
    @endphp
    @if ($send=='enviado')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sucesso:</strong>
            <span>link de verificação foi enviado, por favor acesse a caixa de entrada do seu e-mail</span>
            <span>Caso não encontre procure na caixa de span ou na lixeira.</span>
         </div>

    @else
        {{ __('adminlte::adminlte.verify_check_your_email') }}
        {{ __('adminlte::adminlte.verify_if_not_recieved') }},

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <input type="hidden" name="send-email" value="s" />
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary mt-2 align-baseline">
                    {{ __('adminlte::adminlte.verify_request_another') }}
                </button>.
            </div>
        </form>
    @endif
@stop
