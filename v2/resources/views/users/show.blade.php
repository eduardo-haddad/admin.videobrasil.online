@extends('layouts.app')

@section('content')

  <div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3">
      <div class="x_panel">
        <div class="x_title">
          <h2>Perfil</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <img class="thumbnail center-block" src="{{ asset('images/ai-s.png') }}">
            <h3>{{ $profile->name }}</h3>

            <ul class="list-unstyled">
              @if($profile->position)
                <li><i class="fa fa-briefcase"></i> {{ $profile->position }}</li>
              @endif
              <li><i class="fa fa-envelope"></i> <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a></li>
            </ul>

            @if($user->can('owns', $profile))
              <a href="{{ route('account.edit') }}" class="btn btn-primary btn-block"><i class="fa fa-edit m-right-xs"></i> Editar Perfil</a>
            @endif
        </div>
      </div>
    </div>

    <div class="col-xs-12 col-sm-9 col-md-9">
      @include('leads.results.qa', ['subtitle' => 'Últimos 7 Dias'])
    </div>
  </div>

@endsection

@push('scripts')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

@endpush
