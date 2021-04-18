@extends('layouts.app')

@section('content')

{{ Form::open(['route' => ['video.store', $edition_id], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}

  <div class="page-title">
    <div class="title_left">
      <h3>Vídeo</h3>
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

            <!-- Ordem -->
            <div class="item form-group {{ $errors->has('order') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="order">Ordem <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('order', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('order'))
                    <span class="help-block">
                        <strong>{{ $errors->first('order') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- vimeo_id -->
            <div class="item form-group {{ $errors->has('vimeo_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vimeo_id">vimeo_id <span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('vimeo_id', null, ['class' => 'form-control', 'required', 'autofocus']) }}

                    @if ($errors->has('vimeo_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('vimeo_id') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- vimeo_id_pt -->
            <div class="item form-group {{ $errors->has('vimeo_id_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vimeo_id_pt">[somente para vídeos em idiomas diferentes] vimeo_id (pt)</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('vimeo_id_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('vimeo_id_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('vimeo_id_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- vimeo_id_en -->
            <div class="item form-group {{ $errors->has('vimeo_id_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vimeo_id_en">[somente para vídeos em idiomas diferentes] vimeo_id (en)</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('vimeo_id_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('vimeo_id_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('vimeo_id_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
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
            
            <!-- Titlebox -->
            <div class="item form-group {{ $errors->has('title_box_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_box_pt">Texto para player</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_box_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('title_box_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_box_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Poster -->
            <div class="item form-group {{ $errors->has('poster_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="poster_pt">Imagem home</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('poster_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('poster_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('poster_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Thumb -->
            <div class="item form-group {{ $errors->has('thumb_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="thumb_pt">Imagem playlist</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('thumb_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('thumb_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('thumb_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Categoria -->
            <div class="item form-group {{ $errors->has('category_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_pt">Categoria</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('category_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('category_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Ficha técnica -->
            <div class="item form-group {{ $errors->has('specs_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specs_pt">Ficha técnica</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('specs_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('specs_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('specs_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Legenda -->
            <div class="item form-group {{ $errors->has('caption_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="caption_pt">Legenda</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('caption_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('caption_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('caption_pt') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- main_preview_html_pt -->
            <div class="item form-group {{ $errors->has('main_preview_html_pt') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="main_preview_html_pt">Html customizado para a home</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('main_preview_html_pt', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('main_preview_html_pt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('main_preview_html_pt') }}</strong>
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
            
            <!-- Titlebox -->
            <div class="item form-group {{ $errors->has('title_box_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title_box_en">Player text</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('title_box_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('title_box_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_box_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Poster -->
            <div class="item form-group {{ $errors->has('poster_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="poster_en">Home image</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('poster_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('poster_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('poster_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Thumb -->
            <div class="item form-group {{ $errors->has('thumb_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="thumb_en">Playlist image</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('thumb_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('thumb_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('thumb_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Categoria -->
            <div class="item form-group {{ $errors->has('category_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_en">Category</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::text('category_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('category_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Ficha técnica -->
            <div class="item form-group {{ $errors->has('specs_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="specs_en">Specs</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('specs_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('specs_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('specs_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- Legenda -->
            <div class="item form-group {{ $errors->has('caption_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="caption_en">Caption</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('caption_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('caption_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('caption_en') }}</strong>
                    </span>
                    @endif
                </div>  
            </div>
            
            <!-- main_preview_html_en -->
            <div class="item form-group {{ $errors->has('main_preview_html_en') ? 'has-error' : '' }}">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="main_preview_html_en">Custom html for home</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    {{ Form::textarea('main_preview_html_en', null, ['class' => 'form-control', 'autofocus']) }}

                    @if ($errors->has('main_preview_html_en'))
                    <span class="help-block">
                        <strong>{{ $errors->first('main_preview_html_en') }}</strong>
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
