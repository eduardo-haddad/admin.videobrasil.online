@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Usuários</h3>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Registrar novo usuário</h2>
          <div class="clearfix"></div>
        </div>

        <div class="x_content">
          {{ Form::open(['route' => 'register', 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}
            @include('users.forms.register')
          {{ Form::close() }}
        </div>
      </div>
    </div>
  </div>

@endsection
