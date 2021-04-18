<div class="x_panel lead-supplier">
  <div class="x_title">
    <h2>Informações do fornecedor</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-check-circle"></i> Validação dos dados
        </h4>
      </div>

      <div class="panel-body">
        <table class="table table-responsive table-no-border mb-0">
          <tbody>
            <tr>
              <td>Telefone</td>
              <td>
                @if($lead->validation->phone === true)
                  <i class="fa fa-check-circle green"></i> <strong>Válido</strong>
                @elseif($lead->validation->phone === false)
                  <i class="fa fa-times-circle red"></i> <strong>Inválido</strong>
                @else
                  <i class="fa fa-question-circle"></i> <strong>Sem verificação</strong>
                @endif
              </td>
            </tr>
            <tr>
              <td>E-mail</td>
              <td>
                @if($lead->validation->email === true)
                  <i class="fa fa-check-circle green"></i> <strong>Válido</strong>
                @elseif($lead->validation->email === false)
                  <i class="fa fa-times-circle red"></i> <strong>Inválido</strong>
                @else
                  <i class="fa fa-question-circle"></i> <strong>Sem verificação</strong>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="panel-footer">
        @if($lead->validation->status === true)
          <h4>Status: <i class="fa fa-check-circle green"></i> Válido</h4>
        @elseif($lead->validation->status === false)
          <h4>Status: <i class="fa fa-times-circle red"></i> Inválido</h4>

          @if(isset($lead->validation->message))
            <p>{{ $lead->validation->message }}</p>
          @endif
        @else
          <h4>Status: <i class="fa fa-question-circle"></i> Sem verificação</h4>
        @endif
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-phone"></i> Tentativas de contato
        </h4>
      </div>

      <table class="table table-responsive table-hover mb-0">
        <thead>
          <th>Data</th>
          <th>Horário</th>
          <th>Canal</th>
          <th>Resultado</th>
        </thead>
        <tbody>
          @if($lead->qa && $lead->qa->contact_attempts->isNotEmpty())
            @foreach($lead->qa->contact_attempts as $attempt)
              <tr>
                <td>@date($attempt->created_at)</td>
                <td>@time($attempt->created_at)</td>
                <td>
                  <span class="label label-info label-attempt">
                    {{ strtoupper($attempt->channel) }}
                  </span>
                </td>
                <td>
                  @if($attempt->answered_at || ($lead->qa->first_talk_at && $loop->last))
                    Resposta
                  @else
                    Sem resposta
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="4" class="text-center">Nenhuma tentativa realizada até o momento.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    <div class="panel panel-info">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-comments-o"></i> Resultado da conversa
        </h4>
      </div>

      <div class="panel-body">
        <table class="table table-responsive table-no-border mb-0">
          <tbody>
            <tr>
              <td>Falou com o corretor?</td>
              <td>
                <strong>
                  @if($lead->qa && $lead->qa->talked_to_broker)
                    {{ App\Lead\Qa::getOption($lead->qa->talked_to_broker) }}
                  @else
                    Sem resposta
                  @endif
                </strong>
              </td>
            </tr>
            <tr>
              <td>Buscando imóvel?</td>
              <td>
                <strong>
                  @if($lead->qa && $lead->qa->searching_immobile)
                    {{ App\Lead\Qa::getOption($lead->qa->searching_immobile) }}
                  @else
                    Sem resposta
                  @endif
                </strong>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel panel-danger">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-fire"></i> Hotlead
        </h4>
      </div>

      <div class="panel-body">
        <table class="table table-responsive table-no-border mb-0">
          <tbody>
            <tr>
              <td>Data e horário da conversa</td>
              <td>
                @if($lead->qa && $lead->qa->hotlead)
                  <i class="fa fa-calendar"></i> @date($lead->qa->hotlead)
                  <i class="fa fa-clock-o"></i> @time($lead->qa->hotlead)
                @else
                  Sem informação
                @endif
              </td>
            </tr>
            <tr>
              <td>Melhor horário para ligar</td>
              <td>
                @if($lead->qa && $lead->qa->hotlead_preferable_datetime)
                  <i class="fa fa-calendar"></i> @date($lead->qa->hotlead_preferable_datetime)
                  <i class="fa fa-clock-o"></i> @time($lead->qa->hotlead_preferable_datetime)
                @else
                  Sem informação
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
