@extends('layouts.appless')

@section('content')

  <section class="login_content">
    {{ Form::open(['route' => 'password.request', 'method' => 'POST', 'class' => 'form-horizontal'])}}
      {{ Form::hidden('token', $token) }}
      <h1 class="text-center l-15">Cadastrar nova senha</h1>

      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif

      <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        {{ Form::email('email', $email, ['placeholder' => 'E-mail', 'class' => 'form-control', 'autofocus']) }}

        @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        {{ Form::password('password', ['placeholder' => 'Nova senha', 'class' => 'form-control']) }}

        @if ($errors->has('password'))
          <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
        {{ Form::password('password_confirmation', ['placeholder' => 'Confirmar senha', 'class' => 'form-control']) }}

        @if ($errors->has('password_confirmation'))
          <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
          </span>
        @endif
      </div>

      <div>
        {{ Form::submit('Salvar', ['class' => 'btn btn-primary btn-block']) }}
      </div>

      <div class="separator"></div>
    {{ Form::close() }}
  </section>

@endsection
