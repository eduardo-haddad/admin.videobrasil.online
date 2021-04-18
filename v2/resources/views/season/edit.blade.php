@extends('layouts.app')

@section('content')

{{ Form::model($season, ['route' => ['season.update', $season->id], 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}


  <div class="page-title">
    <div class="title_left">
      <h3>Sobre</h3>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Título <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_pt', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('title_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_pt') }}</strong>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Title</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_en', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('title_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_en') }}</strong>
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
