<div class="item form-group {{ $errors->has('notifications') ? 'has-error' : '' }}">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">
    Notificações <br />
    <small class="text-navy">Marque quando quer ser notificado</small>
  </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    {{ Form::hidden('notifications', '') }}

    @foreach($notifications as $notification)
      <div class="checkbox">
        <label class="cbx">
          {{ Form::checkbox('notifications[]', $notification->id) }}
          <span></span> {{ $notification->name }}
        </label>
        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="{{ $notification->description }}"></i>
      </div>
    @endforeach

    @if ($errors->has('notifications'))
      <span class="help-block">
        <strong>{{ $errors->first('notifications') }}</strong>
      </span>
    @endif
  </div>
</div>
