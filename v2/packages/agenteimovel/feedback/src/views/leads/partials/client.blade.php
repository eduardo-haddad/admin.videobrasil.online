<div class="x_panel lead-client">
  <div class="x_title">
    <h2>Informações do(a) {{ $lead->listing->client->user_name }}</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-thumbs-up"></i> Feedback
        </h4>
      </div>

      <div class="panel-body">
        <table class="table table-responsive table-no-border mb-0">
          <tbody>
            <tr>
              <td>1º Visita</td>
              <td>
                <strong>
                  @if($feedback['first_visit'])
                    @datetime($feedback['first_visit']->created_at)
                  @else
                    Sem informação
                  @endif
                </strong>
              </td>
            </tr>
            <tr>
              <td>1º Tentativa de Contato</td>
              <td>
                <strong>
                  @if($feedback['first_contact_attempt'])
                    @datetime($feedback['first_contact_attempt']->created_at)
                  @else
                    Sem informação
                  @endif
                </strong>
              </td>
            </tr>
            <tr>
              <td>Tentativas em Total</td>
              <td><strong>{{ $feedback['attempts_count'] }}</strong></td>
            </tr>
            <tr>
              <td>Status</td>
              <td><strong>{{ $feedback['status'] }}</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading" role="tab">
        <h4 class="panel-title">
          <i class="fa fa-book"></i> Log de Atividades
        </h4>
      </div>

      <table class="table table-responsive table-hover data-table-less mb-0">
        <thead>
          <tr>
            <th>Data</th>
            <th>Horário</th>
            <th>Atividade</th>
          </tr>
        </thead>
        <tbody>
          @if($lead->accesses->isNotEmpty())
            @foreach($lead->accesses as $access)
              <tr>
                <td>@date($access->created_at)</td>
                <td>@time($access->created_at)</td>
                <td>{{ \Feedback\Lead\Access::getAnswer($access->answer) }}</td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="3" class="text-center">Nenhuma atividade realizada até o momento.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
