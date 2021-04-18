@extends('feedback::layouts.app')

@section('content')

  <div class="jumbotron questionnaire">
    {{ Form::open(['url' => _route('feedback.leads.questionnaire', ['lead' => $lead_id]), 'method' => 'POST', 'class' => '', 'novalidate']) }}
      <div class="form-group {{ $errors->has('answer') ? 'has-error' : '' }}">
        <h3>Para acessar a página do Lead, escolha uma das opções abaixo:</h3>

        @if ($errors->has('answer'))
          <span class="help-block">
            <strong>{{ $errors->first('answer') }}</strong>
          </span>
        @endif

        @php $answers = \Feedback\Lead\Access::getAnswers(); @endphp

        @foreach($answers as $key => $value)
          <div class="radio">
            <label>
              <input type="radio" name="answer" value="{{ $key }}">
              <span></span>
              {{ $value }}
            </label>
          </div>
        @endforeach
      </div>

      <div class="form-group">
        {{ Form::submit('Enviar', ['class' => 'btn btn-primary btn-block']) }}
      </div>
    {{ Form::close() }}
  </div>

@endsection
