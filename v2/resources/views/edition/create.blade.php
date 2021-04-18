@extends('layouts.app')

@section('content')

{{ Form::open(['route' => ['edition.store'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}


  <div class="page-title">
    <div class="title_left">
      <h3>Edições</h3>
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

            <!-- Tipo de temporada -->
            <div class="item form-group {{ $errors->has('season_type_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="season_type_id">Tipo de temporada</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::select('season_types', $season_types, null) }}

                    @if ($errors->has('season_type_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('season_type_id') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>

            <!-- Título -->
            <div class="item form-group {{ $errors->has('title_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_pt">Título <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_pt', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('title_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Sub-título -->
            <div class="item form-group {{ $errors->has('subtitle_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subtitle_pt">Sub-título</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('subtitle_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('subtitle_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('subtitle_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Ativa? -->
            <div class="item form-group {{ $errors->has('current') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="current">Ativa?</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::checkbox('current') }}

                    @if ($errors->has('current'))
                    <span class="help-block">
                        <strong>{{ $errors->first('current') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Agrupar programas? -->
            <div class="item form-group {{ $errors->has('group_programs') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group_programs">Agrupar programas?</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::checkbox('group_programs') }}

                    @if ($errors->has('group_programs'))
                    <span class="help-block">
                        <strong>{{ $errors->first('group_programs') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>

            <!-- Cor de fundo -->
            <div class="item form-group {{ $errors->has('bg_color') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bg_color">Cor de fundo</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('bg_color', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('bg_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('bg_color') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>

            <!-- Imagem de fundo -->
            <div class="item form-group {{ $errors->has('bg_img_desktop') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bg_img_desktop">Imagem de fundo</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('bg_img_desktop', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('bg_img_desktop'))
                    <span class="help-block">
                        <strong>{{ $errors->first('bg_img_desktop') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Imagem de fundo mobile -->
            <div class="item form-group {{ $errors->has('bg_img_mobile') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bg_img_mobile">Imagem de fundo mobile</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('bg_img_mobile', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('bg_img_mobile'))
                    <span class="help-block">
                        <strong>{{ $errors->first('bg_img_mobile') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Quantidade de vídeos -->
            <div class="item form-group {{ $errors->has('videos_to_show') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="videos_to_show">Quantidade de vídeos na home inferior [padrão: 3]</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('videos_to_show', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('videos_to_show'))
                    <span class="help-block">
                        <strong>{{ $errors->first('videos_to_show') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Título customizado do frame principal -->
            <div class="item form-group {{ $errors->has('main_preview_custom_title_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="main_preview_custom_title_pt">Título customizado do frame principal</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('main_preview_custom_title_pt', null, ['class' => 'form-control', 'autofocus']) }} 

                    @if ($errors->has('main_preview_custom_title_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('main_preview_custom_title_pt') }}</strong>
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
            
            <!-- Sub-título -->
            <div class="item form-group {{ $errors->has('subtitle_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subtitle_en">Subtitle</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('subtitle_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('subtitle_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('subtitle_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Título customizado do frame principal -->
            <div class="item form-group {{ $errors->has('main_preview_custom_title_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="main_preview_custom_title_en">Main preview customized title</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('main_preview_custom_title_en', null, ['class' => 'form-control', 'autofocus']) }} 

                    @if ($errors->has('main_preview_custom_title_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('main_preview_custom_title_en') }}</strong>
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
