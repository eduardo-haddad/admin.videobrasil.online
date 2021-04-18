@extends('layouts.app')

@section('content')

{{ Form::model($equipe, ['route' => ['equipe.update', $equipe->id], 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}


  <div class="page-title">
    <div class="title_left">
      <h3>Equipe</h3>
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

          

            <div class="item form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nome</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <div class="item form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Cargo</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('role_pt', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('role_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('role_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>



        </div>
      </div>
    </div>


  </div>
  
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Inglês</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">

          <div class="item form-group {{ $errors->has('name') ? 'has-error' : '' }}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                  {{ Form::text('role_en', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                  @if ($errors->has('role_en'))
                  <span class="help-block">
                      <strong>{{ $errors->first('role_en') }}</strong>
                  </span>
                  @endif
              </div>  
          </div>

    
        </div>
      </div>
    </div>


    <div class="form-group text-right">
        <div class="col-md-12">
            <a class="btn btn-default" href="{{ route('home') }}">Cancelar</a>
            {{ Form::submit('Salvar', ['class' => 'btn btn-primary']) }}
        </div>
    </div>


  </div>

  {{ Form::close() }}

@endsection
