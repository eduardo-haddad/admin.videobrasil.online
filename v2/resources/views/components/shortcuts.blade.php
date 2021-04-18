<div class="modal fade" id="modal-help-shortcuts" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-keyboard-o"></i> Atalhos</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <h4><span class="label label-default">Geral</span></h4>

            <div class="shortcuts">
              <div class="shortcut">
                <label class="col-sm-8">Atalhos</label>
                <div class="col-sm-4">
                  <span class="btn btn-sm btn-primary">Shift + ?</span>
                </div>
              </div>

              {{ $left }}
            </div>
          </div>

          <div class="col-sm-6">
            {{ $right }}
          </div>
        </div>

        <hr />

        {{ $slot }}
      </div>
    </div>
  </div>
</div>

@section('navbar')

  <li>
    <a onclick="$('#modal-help-shortcuts').modal('toggle');"><i class="fa fa-keyboard-o pull-right"></i> Atalhos</a>
  </li>

@endsection

@push('scripts')

  <script>
    $(document).keypress(function(e){
      if(e.which == 63 && e.shiftKey){
        $('#modal-help-shortcuts').modal('toggle');
      }
    });
  </script>

@endpush
