@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Usuários</h3>
    </div>
  </div>

  @include('includes.success')
  @include('includes.errors')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Todos os Usuários</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('register') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Registrar Usuário</a>
        </div>

        <div class="text-right">
          Legenda:
          <span class="label label-default">Desativados</span>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-15">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Super Admin</th>
                <th>Registrado em</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr class="{{ $user->status_context }}">
                  <td class="text-center">{{ $user->id }}</td>
                  <td>
                    <div>
                      <img src="{{ asset('images/ai-s.png') }}" class="avatar" alt="Avatar">
                    </div>
                    <div class="ml-5">
                      {{ $user->name }} @if($user->position) <br /><small>{{ $user->position }}</small>@endif
                    </div>
                  </td>
                  <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                  <td class="text-center"><i class="fa fa-{{ $user->isSuperAdmin() ? 'check' : 'close'}}"></i></td>
                  <td>@datetime($user->created_at)</td>
                  <td>
                    <input type="checkbox" {{ $user->status ? 'checked' : '' }} class="toggle-switch toggle-resource-status" data-href="{{ route('users.update', ['id' => $user->id]) }}" />
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="{{ route('users.show', ['id' => $user->id]) }}" title="Perfil" class="btn btn-default btn-sm"><i class="fa fa-user"></i></a>
                      <a href="{{ route('users.edit', ['id' => $user->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{ $users->links() }}
      </div>
    </div>
  </div>

@endsection
