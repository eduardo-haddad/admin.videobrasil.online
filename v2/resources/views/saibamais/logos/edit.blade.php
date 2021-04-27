@extends('layouts.app')

@section('content')

{{ Form::model($logosaibamais, ['route' => ['logosaibamais.update', $logosaibamais->id], 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}


<div class="page-title">
    <div class="title_left">
      <h3>Logo Saiba Mais</h3>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Português</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

            {{ Form::hidden('saibamais_id', $logosaibamais->saibamais_id) }}
            {{ Form::hidden('edition_id', $edition_id) }}

            <!-- Tipo de chancela -->
            <div class="item form-group {{ $errors->has('partner_roles_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_roles_id">Tipo de chancela</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::select('partner_roles', $partner_roles, $logosaibamais->partner_roles_id) }}

                    @if ($errors->has('partner_roles_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('partner_roles_id') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>

            <!-- Título -->
            <div class="item form-group {{ $errors->has('img') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="img">Imagem</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('img', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('img'))
                    <span class="help-block">
                        <strong>{{ $errors->first('img') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Url -->
            <div class="item form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="url">Url</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('url', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('url'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
        </div>
      </div>
    </div>


  </div>
  
  <div class="row">

    <div class="form-group text-right">
        <div class="col-md-12">
            <a class="btn btn-default" href="{{ route('home') }}">Cancelar</a>
            {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
        </div>
    </div>


  </div>

  {{ Form::close() }}

@endsection