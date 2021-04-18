@extends('feedback::layouts.app')

@section('content')

<div class="container-fluid">
<div class="row">
  <div class="col-xs-12">
    <h4>{{strtoupper($client->user_name)}}</h4>
  </div>
</div>

<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Data de Início</th>
      <th>Data de Termino</th>
      <th>Leads da Verba</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($campaigns as $campaign)
      <tr id="{{$campaign->report ? 'report-'.$campaign->report->id : ''}}">
        <td>{{$campaign->name}}</td>
        <td>{{$campaign->start_date->format('d/m/Y')}}</td>
        <td>{{$campaign->end_date->format('d/m/Y')}}</td>
        <td>{{$campaign->budget}}</td>
        <td>
          <a class="btn btn-sm btn-primary" href="{{_route('feedback.campaign.index', $campaign->id)}}">Detalhes de campanha</a>
          {{Form::open(['name' => 'report', 'url' => route('campaigns.reports.store'), 'method' => 'POST'])}}
            {{Form::hidden('by_campaign', $campaign->id)}}
            {{Form::hidden('name', $campaign->id.$campaign->name.'-'.$campaign->start_date)}}
            {{Form::hidden('type', 'Campaigns.All')}}
            <div class="btn-group" role="group" aria-label="...">
            <button class="btn btn-sm btn-primary">Gerar novo relatório <span class="badge badge-default"><i class="fa fa-refresh"></i></span></button>
            @if($campaign->report)
              <a href="{{ asset('storage/' . $campaign->report->file) }}" class="btn btn-sm btn-primary download" {{$campaign->report->status == 'processing' ? 'disabled' : '' }} target="_blank" download>
                  {{$campaign->report->status == 'ready' ? 'Baixar Relátorio' : 'Relatório sendo gerado' }}
                <span class="badge badge-default">
                  <i class="fa {{$campaign->report->status == 'ready' ? 'fa-download' : 'fa-refresh spin' }}"></i>
                </span>
              </a>
            @endif
            </div>
          {{Form::close()}}
        </td>
      </tr>        
    @endforeach
  </tbody>
</table>
{{$campaigns->links()}}
</div>

@endsection
