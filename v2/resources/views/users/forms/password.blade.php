<div class="item form-group {{ $errors->has('password') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Sua senha <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::password('password', ['class' => 'form-control']) }}

    @if ($errors->has('password'))
      <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nova senha <span class="required">*</span></label>
    <div class="col-md-6 col-sm-6 col-xs-12">
      {{ Form::password('new_password', ['class' => 'form-control']) }}

      @if ($errors->has('new_password'))
        <span class="help-block">
          <strong>{{ $errors->first('new_password') }}</strong>
        </span>
      @endif
    </div>
</div>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar nova senha <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::password('new_password_confirmation', ['class' => 'form-control']) }}
  </div>
</div>

<div class="form-group text-right">
  <div class="col-md-6 col-md-offset-3">
    {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
  </div>
</div>
