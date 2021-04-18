@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Equipe</h3>
    </div>
  </div>

  @include('includes.success')
  @include('includes.errors')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Todos os membros</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('register') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Membro da equipe</a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-15">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Registrado em</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($equipe as $membro)
                <tr class="{{ $user->status_context }}">
                  <td class="text-center">{{ $membro->id }}</td>
                  <td>
                    <div class="ml-5">
                      {{ $membro->name }}
                    </div>
                  </td>
                  <td>{{ $membro->role_pt }}</td>
                  <td>@datetime($membro->createdAt)</td>
                  <td class="text-left">
                    <div class="btn-group">
                      <a href="{{ route('equipe.edit', ['id' => $membro->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                    </div>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection
