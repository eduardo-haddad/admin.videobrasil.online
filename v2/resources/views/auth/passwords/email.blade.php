@extends('layouts.appless')

@section('content')

  <section class="login_content">
    {{ Form::open(['route' => 'password.email', 'method' => 'POST', 'class' => 'form-horizontal']) }}
      <h1 class="text-center l-15">Esqueceu a senha?</h1>
      <p class="text-center">Digite seu endereço de e-mail para redefinir a senha.</p>

      @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
      @endif

      <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::email('email', null, ['placeholder' => 'E-mail', 'class' => 'form-control', 'autofocus'])}}

        @if ($errors->has('email'))
          <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
        @endif
      </div>

      <div>
        {{ Form::submit('Enviar', ['class' => 'btn btn-primary btn-block']) }}
      </div>

      <div class="separator">
        <p class="text-right"><a href="{{ route('login') }}"><i class="fa fa-long-arrow-left"></i> Login</a></p>
      </div>
    {{ Form::close() }}
  </section>

@endsection
