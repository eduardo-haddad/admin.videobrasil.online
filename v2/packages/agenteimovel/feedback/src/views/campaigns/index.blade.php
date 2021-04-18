@extends('feedback::layouts.app')

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12">
      <h4>Campanha {{strtoupper($campaign->name)}}</h4>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>Geral da Campanha</h4></div>
        <div class="panel-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Leads Contratados</th>
                <th>Leads Gerados</th>
                <th>Leads Válidos</th>
                <th>Leads Descartados</th>
                <th>Leads Contatados</th>
                <th>Leads Analisados</th>
                <th>Leads Pretendem Visitar</th>
                <th>Leads Marcaram Visita</th>
                <th>Leads Interessados sem contato</th>
                <th>Hot Leads</th>
                <th>Leads não contatados pelo corretor</th>
                <th>Tentativas pelo WhatsApp</th>
                <th>Tentativas por telefone</th>
                <th>Média nota corretores</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td>{{$campaign->budget}}</td>
                  <td>{{$campaign->leads->count()}}</td>
                  <td>{{$campaign->leads_valid}}</td>
                  <td>{{$campaign->leads_disqualified}}</td>
                  <td>{{$campaign->leads_contacted}}</td>
                  <td>{{$campaign->leads_analyzed}}</td>
                  <td>{{$campaign->leads_wants_visist}}</td>
                  <td>{{$campaign->leads_booked}}</td>
                  <td>{{$campaign->leads_interest_no_contact}}</td>
                  <td>{{$campaign->leads_hotlead}}</td>
                  <td>{{$campaign->leads_no_broker_contact}}</td>
                  <td>{{$campaign->attempts_whatsapp}}</td>
                  <td>{{$campaign->attempts_phone}}</td>
                  <td>{{$campaign->service_rate_avg}}</td>
                </tr>
            </tbody>
          </table>
          <hr>
          <b>Leads - Porcentagem</b>
          <table class="table table-hover mt-15">
              <thead>
                <tr>
                  <th>Contratados</th>
                  <th>Analisados</th>
                  <th>Pretendem Visitar</th>
                  <th>Marcaram Visita</th>
                  <th>Hot Leads</th>
                  <th>Não contatados pelo corretor</th>
                  <th>Interessados sem contato</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>{{$campaign->percentages->leads_contacted}}</td>
                    <td>{{$campaign->percentages->leads_analyzed}}</td>
                    <td>{{$campaign->percentages->leads_wants_visist}}</td>
                    <td>{{$campaign->percentages->leads_booked}}</td>
                    <td>{{$campaign->percentages->leads_hotlead}}</td>
                    <td>{{$campaign->percentages->leads_no_broker_contact}}</td>
                    <td>{{$campaign->percentages->leads_interest_no_contact}}</td>
                  </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-15">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>Por Empreendimento</h4></div>
        <div class="panel-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Empreendimento</th>
                <th>Leads Gerados</th>
                <th>Leads Válidos</th>
                <th>Leads Descartados</th>
                <th>Leads Contatados</th>
                <th>Leads Analisados</th>
                <th>Leads Pretendem Visitar</th>
                <th>Leads Marcaram Visita</th>
                <th>Leads Interessados sem contato</th>
                <th>Hot Leads</th>
                <th>Leads não contatados pelo corretor</th>
                <th>Tentativas pelo WhatsApp</th>
                <th>Tentativas por telefone</th>
                <th>Média nota corretores</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pivots as $pivot)
                <tr>
                  <td>{{$pivot->newconst->listing_title}}</td>
                  <td>{{$pivot->leads_count}}</td>
                  <td>{{$pivot->leads_valid}}</td>
                  <td>{{$pivot->leads_disqualified}}</td>
                  <td>{{$pivot->leads_contacted}}</td>
                  <td>{{$pivot->leads_analyzed}}</td>
                  <td>{{$pivot->leads_wants_visist}}</td>
                  <td>{{$pivot->leads_booked}}</td>
                  <td>{{$pivot->leads_interest_no_contact}}</td>
                  <td>{{$pivot->leads_hotlead}}</td>
                  <td>{{$pivot->leads_no_broker_contact}}</td>
                  <td>{{$pivot->attempts_whatsapp}}</td>
                  <td>{{$pivot->attempts_phone}}</td>
                  <td>{{$pivot->service_rate_avg}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{$pivots->links()}}
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-2">
      <div class="panel panel-default">
        <div class="panel-heading">Média nota dada aos corretores do período anterior</div>
        <div class="panel-body"><h3 class="text-center">{{$client->service_rate->prev_month->avg}}</h3></div>
      </div>
    </div>
    <div class="col-xs-2">
      <div class="panel panel-default">
        <div class="panel-heading">Média nota dada aos corretores no período atual</div>
        <div class="panel-body"><h3 class="text-center">{{$client->service_rate->current_month->avg}}</h3></div>
      </div>
    </div>
  </div>
</div>

@endsection
