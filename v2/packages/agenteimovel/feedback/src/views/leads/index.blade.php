@extends('feedback::layouts.app')

@section('content')

  <div class="jumbotron questionnaire" style="height: 220px;">
    {{ Form::open(['url' => _route('feedback.leads.index'), 'method' => 'POST', 'class' => '', 'novalidate']) }}
      <div class="form-group {{ $errors->has('id') ? 'has-error' : '' }}">
        <h3>Qual o código do Lead?</h3>

        <div class="form-group">
          {{ Form::text('id', null, ['class' => 'form-control', 'autofocus']) }}

          @if ($errors->has('id'))
            <span class="help-block">
              <strong>{{ $errors->first('id') }}</strong>
            </span>
          @endif
        </div>
      </div>

      <div class="form-group">
        {{ Form::submit('Enviar', ['class' => 'btn btn-primary btn-block']) }}
      </div>
    {{ Form::close() }}
  </div>

@endsection
