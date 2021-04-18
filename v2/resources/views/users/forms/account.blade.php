@cannot('manage', App\User::class)
  <div class="alert alert-info" role="alert">
    <i class="fa fa-lightbulb-o"></i> Você não tem permissão para alterar o nome, username ou e-mail da sua conta.
  </div>
@endcannot

<div class="item form-group {{ $errors->has('name') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nome <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    @can('manage', App\User::class)
      {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
    @else
      {{ Form::text('name', null, ['class' => 'form-control', 'readonly']) }}
    @endcan

    @if ($errors->has('name'))
      <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('username') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Username</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    @can('manage', App\User::class)
      {{ Form::text('username', null, ['class' => 'form-control']) }}
    @else
      {{ Form::text('username', null, ['class' => 'form-control', 'readonly']) }}
    @endcan

    @if ($errors->has('username'))
      <span class="help-block">
        <strong>{{ $errors->first('username') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('email') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">E-mail <span class="required">*</span></label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    @can('manage', App\User::class)
      {{ Form::email('email', null, ['class' => 'form-control', 'required']) }}
    @else
      {{ Form::email('email', null, ['class' => 'form-control', 'readonly']) }}
    @endcan

    @if ($errors->has('email'))
      <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
      </span>
    @endif
  </div>
</div>

<div class="item form-group {{ $errors->has('position') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Cargo</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::text('position', null, ['class' => 'form-control']) }}

    @if ($errors->has('position'))
      <span class="help-block">
        <strong>{{ $errors->first('position') }}</strong>
      </span>
    @endif
  </div>
</div>


<div class="form-group text-right">
  <div class="col-md-6 col-md-offset-3">
    {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
  </div>
</div>
