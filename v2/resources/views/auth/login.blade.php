@extends('layouts.appless')

@section('content')

  <section class="login_content">
    {{ Form::open(['route' => 'login', 'method' => 'POST', 'class' => 'form-horizontal',]) }}
      <h1 class="text-center">Login</h1>

      <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        {{ Form::text('email', null, ['placeholder' => 'E-mail ou Username', 'class' => 'form-control', 'autofocus']) }}

        @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        {{ Form::password('password', ['placeholder' => 'Senha', 'class' => 'form-control',]) }}

        @if ($errors->has('password'))
          <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group">
        <label class="cbx">
          {{ Form::checkbox('remember') }}
          <span></span>
          Lembrar meus dados
        </label>
      </div>

      <div>
        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
      </div>

      <div class="separator">
        <p class="text-right"><a href="{{ route('password.request') }}">Esqueceu a senha?</a></p>
      </div>
    {{ Form::close() }}
  </section>

@endsection
