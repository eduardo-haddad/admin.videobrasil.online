<div class="x_panel lead-profile">
  <div class="x_content">
    <img class="thumbnail center-block" style="max-width:100%" src="{{ ($lead->pipl && isset($lead->pipl->picture)) ? $lead->pipl->picture : asset('images/avatar.png') }}">

    <div class="text-center">
      <h2>Lead gerado em</h2>
      <h4>
        <i class="fa fa-calendar"></i> @date($lead->datetime)
        <i class="fa fa-clock-o"></i> @time($lead->datetime)
      </h4>

      <br />

      <h2>Empreendimento</h2>
      <h4>
        <a target="_blank" href="{{ portal_route($lead->listing->slug) }}">{{ $lead->listing->newconst->listing_title_pt }}</a>
      </h4>
    </div>

    <hr />

    <h2 class="text-center">{{ $lead->fromname }}</h2>

    <p class="text-center">
      @if($lead->pipl && isset($lead->pipl->age))
        {{ $lead->pipl->age }}
      @else
        Idade não fornecida
      @endif
    </p>

    <div class="row">
      <h4>
        <i class="fa fa-map-marker"></i>
        @if($lead->pipl && isset($lead->pipl->location))
          {{ $lead->pipl->location }}
        @else
          Sem informação
        @endif
      </h4>

      <h4>
        <i class="fa fa-graduation-cap"></i>
        @if($lead->pipl && isset($lead->pipl->education))
          {{ $lead->pipl->education }}
        @else
          Sem informação
        @endif
      </h4>

      <h4>
        <i class="fa fa-briefcase"></i>
        @if($lead->pipl && isset($lead->pipl->job))
          {{ $lead->pipl->job }}
        @else
          Sem informação
        @endif
      </h4>

      <h4>
        <i class="fa fa-phone"></i>
        {{ $lead->phone($lead->fromphone1)->format('N') }}
      </h4>

      <h4>
        <i class="fa fa-envelope"></i>
        <a class="truncate" href="mailto:{{ $lead->fromemail }}">{{ $lead->fromemail }}</a>
      </h4>

      <h4>
        <i class="fa fa-facebook-square"></i>
        @if($lead->pipl && isset($lead->pipl->urls) && isset($lead->pipl->urls['facebook.com']))
          <a target="_blank" class="truncate" href="{{ $lead->pipl->urls['facebook.com'] }}">{{ $lead->pipl->urls['facebook.com'] }}</a>
        @else
          Sem informação
        @endif
      </h4>

      <h4>
        <i class="fa fa-linkedin-square"></i>
        @if($lead->pipl && isset($lead->pipl->urls) && isset($lead->pipl->urls['linkedin.com']))
          <a target="_blank" class="truncate" href="{{ $lead->pipl->urls['linkedin.com'] }}">{{ $lead->pipl->urls['linkedin.com'] }}</a>
        @else
          Sem informação
        @endif
      </h4>
    </div>
  </div>
</div>
