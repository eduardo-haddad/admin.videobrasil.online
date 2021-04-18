@can('manage', App\User::class)
  <div class="item form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="position">
      Permissões <br />
      <small class="text-navy">Marque os módulos de acesso</small>
    </label>

    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="row">
        {{ Form::hidden('roles', '') }}

        @foreach($roles as $chunk)
          <div class="col-xs-12 col-sm-6">
            @foreach($chunk as $role)
              <div class="checkbox">
                <label class="cbx">
                  @php $prop = ($role->alias == 'broker-lp' || $role->alias == 'client-tracking') ? ['data-toggle-target' => '.clients-wrapper'] : []; @endphp
                  {{ Form::checkbox('roles[]', $role->id, null, $prop) }}
                  <span></span> {{ $role->name }}
                </label>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>


      @if ($errors->has('roles'))
        <span class="help-block">
          <strong>{{ $errors->first('roles') }}</strong>
        </span>
      @endif
    </div>
  </div>

  @php $model = Form::getModel(); @endphp

@endcan
