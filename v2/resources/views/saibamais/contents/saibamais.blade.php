<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Saiba mais</h2>
          <div class="clearfix"></div>
        </div>

        <div class="btn-group">
          <a href="{{ route('saibamais.edit', ['edition->id' => $edition->id]) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Saiba mais</a>
        </div>

      </div>
    </div>
  </div>

  @if(!empty($saibamais))
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Links</h2>
            <div class="clearfix"></div>
          </div>

          <div class="btn-group">
            <a href="{{ route('linksaibamais.create', ['edition_id' => $edition->id, 'saibamais->id' => $saibamais->id]) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Link</a>
          </div>

          <div class="table-responsive">
            <table class="table table-hover mt-15">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Título</th>
                  <th>Url</th>
                  <th>Download</th>
                  <th>Nova aba</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                @foreach($links as $link)
                  <tr class="">
                    <td>{{ $link->id }}</td>
                    <td><div class="ml-5">{{ !empty($link->title_pt) ? $link->title_pt : "" }}</div></td>
                    <td>{{ !empty($link->url_pt) ? $link->url_pt : "" }}</td>
                    <td>{{ !empty($link->download) ? "sim" : "não" }}</td>
                    <td>{{ $link->blank ? "sim" : "não" }}</td>
                    <td class="text-left">
                      <div class="btn-group">
                        <!-- Edit -->
                        <a href="{{ route('linksaibamais.edit', ['edition_id' => $edition->id, 'id' => $link->id]) }}" title="Editar" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>
                        <!-- Delete -->
                        <form action="{{ route('linksaibamais.destroy', ['id' => $link->id]) }}" method="POST">
                            <input name="_method" type="hidden" value="DELETE">
                            {{ csrf_field() }}
                            {{ Form::hidden('saibamais_id', $saibamais->id) }}
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
  @endif