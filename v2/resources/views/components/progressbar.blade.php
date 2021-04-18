<div class="progress">
  @php
    if(!isset($total)){
      $percent = $part == 0 ? 0 : 100;
    } else {
      $percent = $total > 0 ? ceil($part * 100 / $total) : 0;
      $percent = $percent > 100 ? 100 : $percent;
    }
  @endphp

  <div class="progress-bar progress-bar-{{ $type }}" aria-valuenow="{{ $percent }}" style="min-width:4%; width: {{ $percent }}%;">
    {{ $slot }}
  </div>
</div>
