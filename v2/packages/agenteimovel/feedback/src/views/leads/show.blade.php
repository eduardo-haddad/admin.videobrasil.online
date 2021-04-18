@extends('feedback::layouts.app')

@section('navbar')
  <li>
    <a href="{{ _route('feedback.leads.index') }}"><i class="fa fa-chevron-left"></i> Voltar</a>
  </li>
@endsection

@section('content')

  <div class="p-10">
    @if($access)
      <div class="row">
        <div class="col-xs-12">
          <div class="alert alert-info" role="alert">
            <i class="fa fa-clock-o"></i> A sua sessão expira em @datetime($access->expired_at)
          </div>
        </div>
      </div>
    @endif

    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-3">
        @include('feedback::leads.partials.profile')
      </div>

      <div class="col-xs-12 col-sm-12 col-md-9">
        <div class="row">
          <div class="col-xs-12 col-sm-6 col-md-6">
            @include('feedback::leads.partials.supplier')
          </div>

          <div class="col-xs-12 col-sm-6 col-md-6">
            @include('feedback::leads.partials.client')
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
