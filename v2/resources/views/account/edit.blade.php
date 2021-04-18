@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Configurações</h3>
    </div>
  </div>

  <div class="clearfix"></div>

  @can('manage', App\User::class)
    <div class="alert alert-info" role="alert">
      <i class="fa fa-lightbulb-o"></i> Você está alterando as configurações da sua <b>própria</b> conta.
    </div>
  @endcan

  @include('includes.success')

  {{ Form::model($user, ['route' => 'account.update', 'method' => 'PUT', 'class' => 'form-horizontal form-label-left', 'novalidate']) }}
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Geral</h2>
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
