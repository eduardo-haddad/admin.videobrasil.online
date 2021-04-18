<div class="input-group">
  {{ Form::text('search_string', null, ['class' => 'form-control', 'placeholder' => $placeholder]) }}
  <span class="input-group-btn">
    <button type="submit" class="btn btn-default">
      <i class="fa fa-search"></i>
    </button>
  </span>
</div>

@push('scripts')

  <script>
    $(document).keydown(function(e){
      if(e.which == 66 && e.ctrlKey){
        e.preventDefault();
        $('input[name=search_string]').focus();
      }
    });
  </script>

@endpush
