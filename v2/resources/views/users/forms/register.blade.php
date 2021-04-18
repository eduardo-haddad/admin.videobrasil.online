<div class="item form-group {{ $errors->has('name') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Nome <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'autofocus']) }}

    @if ($errors->has('name'))
      <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('username') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Username</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::email('username', null, ['class' => 'form-control']) }}

    @if ($errors->has('username'))
      <span class="help-block">
        <strong>{{ $errors->first('username') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('email') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">E-mail <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::email('email', null, ['class' => 'form-control', 'required']) }}

    @if ($errors->has('email'))
      <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Senha <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::password('password', ['class' => 'form-control', 'required']) }}

      @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
    </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar senha <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::password('password_confirmation', ['class' => 'form-control', 'required']) }}
  </div>
</div>


<div class="form-group text-right">
  <div class="col-md-6 col-md-offset-3">
    <a class="btn btn-default" href="{{ route('users.index') }}">Cancelar</a>
    {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
  </div>
</div>
