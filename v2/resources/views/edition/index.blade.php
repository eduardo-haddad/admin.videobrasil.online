@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>Edição</h3>
    </div>
  </div>

  @include('includes.success')
  @include('includes.errors')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Todos as edições</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('edition.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Edição</a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-15">
            <thead>
              <tr>

                <th>ID</th>
                <th>Título</th>
                <th>Subtítulo</th>
                <th>Ativa</th>
                <th>Tipo</th>
                <th>Criada em</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($editions as $edition)
                <tr class="{{ $user->status_context }}">
                  <td class="text-center">{{ $edition->id }}</td>
                  <td>
                    <div class="ml-5">
                      {{ $edition->title_pt }}
                    </div>
                  </td>
                  <td>{{ $edition->subtitle_pt }}</td>
                  @if(!empty($edition->current))
                    <td><i class="fa fa-circle lead-qa-status hotlead"></i></td>
                  @else
                    <td><i class="fa fa-circle lead-qa-status no-interest"></i></td>
                  @endif
                  <td>{{ !empty($edition->seasonType()->get()[0]) ? $edition->seasonType()->get()[0]->title_pt : "" }}</td>
                  <td>@datetime($edition->createdAt)</td>
                  <td class="text-left">
                    <div class="btn-group">
                      <!-- Edit -->
                      <a href="{{ route('edition.edit', ['id' => $edition->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                      <!-- Vídeos -->
                      <a href="{{ route('video.index', ['id' => $edition->id]) }}" title="Vídeos" class="btn btn-default btn-sm"><i class="fa fa-film"></i></a>
                      <!-- Details -->
                      <a href="{{ route('saibamais.index', ['id' => $edition->id]) }}" title="Detalhes" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>
                      <!-- Delete -->
                      <form action="{{ route('edition.destroy', ['id' => $edition->id]) }}" method="POST">
                          <input name="_method" type="hidden" value="DELETE">
                          {{ csrf_field() }}
                          <button type="submit" onclick="return confirm('Tem certeza que deseja remover este item?')" class="btn btn-default btn-sm"><i class="fa fa-trash"></i></button>
                      </form>
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
