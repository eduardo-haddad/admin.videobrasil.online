@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Usuários</h3>
    </div>
  </div>

  {{ Form::model($_user, ['route' => ['users.update', $_user->id], 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Editar usuário</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-content"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            @include('users.forms.account')
          </div>
        </div>

        <div class="x_panel">
          <div class="x_title">
            <h2>Alterar senha</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-content"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">
            @include('users.forms.password')
          </div>
        </div>
      </div>
    </div>
  {{ Form::close() }}

@endsection
