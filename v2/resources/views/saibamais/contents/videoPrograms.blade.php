
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Vídeos</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('video.create', ['edition->id' => $edition->id]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Vídeo</a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover mt-15">
            <thead>
              <tr>

                <th>#</th>
                <th>Título</th>
                <th>vimeo_id</th>
                <th>Criado em</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              @foreach($videos as $video)
                <tr class="">
                  <td class="text-left">{{ $video->id }}</td>
                  <td>
                    <div class="ml-5">
                      {{ $video->title_pt }}
                    </div>
                  </td>
                  <td>{{ $video->vimeo_id }}</td>
                  <td>@datetime($video->createdAt)</td>
                  <td class="text-left">
                    <div class="btn-group">
                      <!-- Edit -->
                      <a href="{{ route('video.edit', ['id' => $video->id, 'edition_id' => $edition->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                      <!-- Delete -->
                      <form action="{{ route('video.destroy', ['id' => $video->id]) }}" method="POST">
                          <input name="_method" type="hidden" value="DELETE">
                          {{ csrf_field() }}
                          {{ Form::hidden('edition_id', $edition->id) }}
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