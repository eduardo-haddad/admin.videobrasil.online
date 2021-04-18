@extends('layouts.app')

@section('content')

@if(!empty($saibamais))
  {{ Form::model($saibamais, ['route' => ['saibamais.update', $saibamais->id], 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}
@else
  {{ Form::open(['route' => ['saibamais.store'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}
@endif


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

            {{ Form::hidden('edition_id', $edition_id) }}


            <!-- Replace text -->
            <div class="item form-group {{ $errors->has('replace_text') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="replace_text">Substituir texto de links? (ex: lista de artistas) <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::checkbox('replace_text') }}

                    @if ($errors->has('replace_text'))
                    <span class="help-block">
                        <strong>{{ $errors->first('replace_text') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>

            <!-- Conteúdo -->
            <div class="item form-group {{ $errors->has('content_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_pt">Conteúdo </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('content_pt', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('content_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content_pt') }}</strong>
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

            <!-- Content -->
            <div class="item form-group {{ $errors->has('content_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_en">Content </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('content_en', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('content_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content_en') }}</strong>
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
