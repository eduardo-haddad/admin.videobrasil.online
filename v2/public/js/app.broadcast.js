(function(){
  var broadcast = pusher.subscribe('broadcast');

  // Fired when report is ready to download
  broadcast.bind('report-complete', function(report) {
    var $row = $('#report-' + report.id);
    var $btn_group = $row.find('.btn-group');

    $btn_group.find('.download').attr('href', report.file)
                                .removeAttr('disabled');

    $row.find('.label-success').removeClass('hide');
    $row.find('.fa-refresh.spin').addClass('hide');
  });

  // Fired when campaigns report is ready to download
  broadcast.bind('report-complete', function(report) {
    var $row = $('#report-' + report.id);
    var $btn_group = $row.find('.btn-group');

    $btn_group.find('.download').attr('href', report.file)
                                .text('Baixar Relátorio');
                                
    $row.find('.fa-refresh.spin').addClass('hide');
  });

  // Fired when xml is ready to download
  broadcast.bind('xml-complete', function(xml) {
    var $row = $('#xml-' + xml.id);
    var $btn_group = $row.find('.btn-group');

    $btn_group.find('.download').attr('href', xml.file)
                                .removeAttr('disabled');

    $btn_group.find('.reload-xml').removeAttr('disabled');

    $row.find('.label-success').removeClass('hide');
    $row.find('.label-default').addClass('hide');
    $row.find('.fa-refresh.spin').addClass('hide');
  });

  // Fired when slot table is updated
  broadcast.bind('slotTable-updated', function(data) {
    notify('Sucesso', 'Ficha publicada na tabela '+data.slot_table, 'success')
    console.log(data)
  });

  broadcast.bind('slotTable-failed', function(error) {
    notify('Erro', 'Ficha erro ao publicar ficha', 'warning')
    console.log(error)
  });

  broadcast.bind('new-lead', function(data) {
    var hasRole = window.USER.roles.filter((role) => { return role.alias == 'lead-manager'})

    if(hasRole.length == 0) return false

    PNotify.removeAll()
    PNotify.notice({
      title: 'Novo lead - '+data.type,
      text: 'Clique para acessar',
      hide: false,
      modules: {
        Desktop: {
          desktop: true,
          fallback: true,
          icon: '/v2/images/favicon.ico',
        }
      }
    }).on('click', function(e) {
      if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) {
        return;
      }
      let url = BASE_URL + '/leads/pre/'+data.id+'/edit'
      window.open(url,'_blank');
    })
  });

  broadcast.bind('callback', function(data) {
    if (data.id !== window.USER.id) return false
    PNotify.notice({
      title: 'Callback!',
      text: 'Você tem um callback agendado para '+data.callback.callback_time+'. Clique para acessar',
      modules: {
        Desktop: {
          desktop: true,
          fallback: true,
          icon: '/v2/images/favicon.ico',
        }
      }
    }).on('click', function(e) {
      if ($('.ui-pnotify-closer, .ui-pnotify-sticker, .ui-pnotify-closer *, .ui-pnotify-sticker *').is(e.target)) {
        return;
      }
      let url = BASE_URL + '/leads/pre/'+data.callback.lead_id+'/edit'
      window.open(url,'_blank');
    })
  })
})();
