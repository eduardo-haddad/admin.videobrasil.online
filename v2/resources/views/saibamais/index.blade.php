@extends('layouts.app')

@section('content')

  <div class="page-title">
    <div class="title_left">
      <h3>{{ "{$edition->title_pt} - {$edition->subtitle_pt}" }}</h3>
    </div>
  </div>

  @include('includes.success')
  @include('includes.errors')

  <!-- Saiba mais -->
  @include('saibamais.contents.saibamais')

@endsection
