<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" @isset($zindex) style="z-index: {{ $zindex }}" @endisset>
  <div class="modal-dialog" role="document">
    {{Form::open(['name' => 'delete', 'url' => '', 'Method' => $method ])}}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ $title }}</h4>
      </div>
      <div class="modal-body">
        {{ $slot }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default " data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger delete">Confirmar</button>
      </div>
    </div>
    {{Form::close()}}
  </div>
</div>
