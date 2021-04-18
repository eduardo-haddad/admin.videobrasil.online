@extends('layouts.app')

@section('content')

{{ Form::open(['route' => ['linksaibamais.store', $saibamais_id], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}


<div class="page-title">
    <div class="title_left">
      <h3>Link Saiba Mais</h3>
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

            {{ Form::hidden('saibamais_id', $saibamais_id) }}
            {{ Form::hidden('edition_id', $edition_id) }}

            <!-- Título -->
            <div class="item form-group {{ $errors->has('title_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_pt">Título</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('title_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Url -->
            <div class="item form-group {{ $errors->has('url_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="url_pt">Url</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('url_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('url_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Blank -->
            <div class="item form-group {{ $errors->has('blank') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="blank">Abrir em nova aba</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::checkbox('blank') }}

                    @if ($errors->has('blank'))
                    <span class="help-block">
                        <strong>{{ $errors->first('blank') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Download -->
            <div class="item form-group {{ $errors->has('download') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="download">Download</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::checkbox('download') }}

                    @if ($errors->has('download'))
                    <span class="help-block">
                        <strong>{{ $errors->first('download') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Texto de substituição -->
            <div class="item form-group {{ $errors->has('text_replacement') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="text_replacement">Texto de substituição (ex: lista de artistas)</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('text_replacement', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('text_replacement'))
                    <span class="help-block">
                        <strong>{{ $errors->first('text_replacement') }}</strong>
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

            <!-- Título -->
            <div class="item form-group {{ $errors->has('title_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_en">Title</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('title_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>         
            
            <!-- Url -->
            <div class="item form-group {{ $errors->has('url_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="url_en">Url</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('url_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('url_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('url_en') }}</strong>
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