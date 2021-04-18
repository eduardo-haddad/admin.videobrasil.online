<div class="form-inline form-group">
  Mostrar {{ Form::select('paginate', ['15' => 15, '30' => 30, '50' => 50], '15', ['class' => 'form-control']) }} resultados
</div>

@push('scripts')

  <script>
    $('select[name=paginate]').change(function(e){
      this.form.submit();
    });
  </script>

@endpush
