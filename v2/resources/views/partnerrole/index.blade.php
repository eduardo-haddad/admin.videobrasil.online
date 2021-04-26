@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Chancela</h3>
    </div>
  </div>

  @include('includes.success')
  @include('includes.errors')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Tipos</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('partnerroles.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Tipo de chancela</a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-15">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Registrado em</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($roles as $role)
                <tr class="{{ $user->status_context }}">
                  <td class="text-center">{{ $role->id }}</td>
                  <td>
                    <div class="ml-5">
                      {{ $role->role_pt }}
                    </div>
                  </td>
                  <td>@datetime($role->createdAt)</td>
                  <td class="text-left">
                    <div class="btn-group">
                      <a href="{{ route('partnerroles.edit', ['id' => $role->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                    </div>
                    <!-- Delete -->
                    <form action="{{ route('partnerroles.destroy', ['id' => $role->id]) }}" method="POST">
                        <input name="_method" type="hidden" value="DELETE">
                        {{ csrf_field() }}
                        <button type="submit" onclick="return confirm('Tem certeza que deseja remover este item?')" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection
