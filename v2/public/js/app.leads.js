(function(){
  /*
  |--------------------------------------------------------------------------
  | Claim & Unclaim Feature
  |--------------------------------------------------------------------------
  */

  /**
   * Handle key events on .claim-lead element.
   */
  $('.claim-lead').keydown(function(e){
    switch(e.which){
      case 13: // Enter
        claim.call(this, $(this).data('claim-url'));
        break;

      case 46: // Delete
        unclaim.call(this, $(this).data('unclaim-url'));
        break;
    }
  });

  /**
   * Claim the Lead.
   *
   * @param {string} url
   */
  function claim(url){
    var cell = this;
    var request = newXMLHttpRequest({type: 'PATCH', url: url});

    request.addEventListener('load', function(e){
      if(this.status == 200){
        var user = JSON.parse(this.responseText);
        $(cell).html(user.name);
        $(cell).parent().addClass('info');
      } else if(this.status == 403){
        notify('Ops...', 'Esse lead já foi reivindicado.', 'warning');
      } else {
        window.errorHandler(this);
      }
    }, false);

    request.send();
  }

  /**
   * Unclaim the Lead.
   *
   * @param {string} url
   */
  function unclaim(url){
    var row = this;
    var request = newXMLHttpRequest({type: 'PATCH', url: url});

    request.addEventListener('load', function(e){
      if(this.status == 200){
        $(row).html('<i class="fa fa-hand-o-right"></i> Reivindicar!');
        $(row).parent().removeClass('info');
      } else {
        notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
        console.error(this.status, this.responseText);
      }
    }, false);

    request.send();
  }

  /*
  |--------------------------------------------------------------------------
  | Date & Time Features
  |--------------------------------------------------------------------------
  */

  (function(){
    var $input;
    var $modal = $('#date-time-modal');

    /**
     * Handle key events on input.handle-date-time
     *
     * Press Backspace to open Date Time Picker.
     * Press Enter to fill the input with current date and time.
     * Press Delete to remove date and time from the input.
     */
    $(document).on('keydown', 'input.handle-date-time', function(e){
      if(e.which != 9){
        // If key pressed is different from Tab,
        // prevent default behaviour.
        e.preventDefault();
      }

      switch (e.which) {
        case 8: // Backspace
          $input = $(e.target);
          $modal.modal('show');
          break;

        case 13: // Enter
          if($(this).val() == ''){
            $(this).val(moment().format('DD/MM/YYYY HH:mm')).trigger('change');
          }
          break;

        case 46: // Delete
          $(this).val('').trigger('change');
          break;
      }
    });

    $(document).on('keydown', 'td', (e) => {
      // Shift + B
      if(e.which == 66 && e.shiftKey) {
        $input = $(e.target).parent().find('input.handle-date-time').first();
        $modal.modal('show');
      }
    });

    /**
     * Fill the input with selected date and time.
     */
    $modal.find('.btn-primary').click(function(e){
      var dtp = $('.date-time-picker-inline').data('DateTimePicker');
      var date = dtp.date();

      if(date){
        $input.val(date.format('DD/MM/YYYY HH:mm')).trigger('change');
      }

      $modal.modal('hide');
    });

    /**
     * Focus on primary action button when modal shows up.
     */
    $modal.on('shown.bs.modal', function(e){
      $modal.find('.btn-primary').focus();
    });

    /**
     * Restore focus when modal hides.
     */
    $modal.on('hidden.bs.modal', function(e){
      $input.focus();
    });
  })();

  /*
  |--------------------------------------------------------------------------
  | Updates the Lead QA when input changes.
  |--------------------------------------------------------------------------
  */

  $(document).on('change', '.update-lead-qa', function(e){
    updateLead(this)
  });

  $('table').on('click', 'a.update-lead-qa', function(e){
    $(this).parent().find('select[name=attempts]').val($(this).text().toLowerCase())
    updateLead($(this).parent().find('select[name=attempts]')[0])
  });

  function updateLead(element){
    var input = element;
    var row = $(element).closest('tr')[0];

    if(input.tagName.toLowerCase() == 'select'){
      // If the input being updated is a dropdown, disabled 
      input.defaultValue = 0
      input.disabled = 'disabled';
    }

    var request = newXMLHttpRequest({
      type: 'PATCH',
      url: element.dataset.url,
      headers: {
        'Content-Type': 'application/json; charset=utf-8'
      }
    });

    request.addEventListener('load', function(e){
      if(this.status == 200){
        if(this.responseText){
          var response = JSON.parse(this.responseText);

          if(response.attempts){
            var attempts = row.querySelector(input.tagName.toLowerCase()+'[name="attempts"]');
            var popover = $(attempts).parent().find('a[data-toggle=popover]');

            $(attempts.options[0]).text(response.attempts.toUpperCase())
            $(attempts).val(0)

            if(response.lastAttempt){
              appendAttempt.call(popover[0], response.lastAttempt);
              $(popover[0]).removeClass('btn-success')
              if($(popover[0]).find('span')) {
                let attemtsNumber = parseInt($(popover[0]).find('span').text())
                $(popover[0]).find('span').text(attemtsNumber+1)
              }
            } else {
              removeLastAttempt.call(popover[0]);
            }
          }
          // if(response.attemptOptions){
          //   var attempts = row.querySelector(input.tagName.toLowerCase()+'[name="attempts"]');
          //   var $popover = $(attempts).next();

          //   if(attempts.tagName.toLowerCase() == 'select') attempts.addMany(response.attemptOptions, attempts.value, true)

          //   // Update list of attempts
          //   if(response.lastAttempt){
          //     appendAttempt.call($popover[0], response.lastAttempt);
          //   } else {
          //     removeLastAttempt.call($popover[0]);
          //   }
          // }
        }
      } else {
        window.errorHandler(element);
        input.value = input.defaultValue;
      }

      // Reenable attempts dropdown & refocus
      input.removeAttribute('disabled');

      if($(input).closest('td.active').length && input.getAttribute('readonly') === null){
        input.focus();
      }
    }, false);

    var data = {};
    data[element.name] = element.value;
    request.send(JSON.stringify(data));
  }

  /*
  |--------------------------------------------------------------------------
  | Validates/Invalidates email address
  |--------------------------------------------------------------------------
  */

  $('.toggle-input').click(function(e){
    e.preventDefault();
    var $toggle = $(this).find('.fa');
    var $input = $(this).find('input');
    var value = !eval($input.val());

    var request = newXMLHttpRequest({
      type: 'PATCH',
      url: $input.data('url'),
      headers: {
        'Content-Type': 'application/json; charset=utf-8'
      }
    });

    $toggle.attr('class', 'fa fa-circle-o-notch spin');

    request.addEventListener('load', function(e){
      if(this.status == 200){
        $input.val(value);
        $toggle.attr('class', 'fa fa-' + (value ? 'check-circle green' : 'times-circle red'));
      } else {
        window.errorHandler(this);
      }
    });

    var data = {};
    data[$input.attr('name')] = value;
    request.send(JSON.stringify(data));
  });

  /*
  |--------------------------------------------------------------------------
  | Attempts & Callbacks
  |--------------------------------------------------------------------------
  */

  /**
   * Delete the last attempt when pressing Del.
   */
  $('select[name=attempts]').keydown(function(e){
    if(e.which == 46){
      var value = $(this).val();

      if(value.length){
        var options = [];
        value = value.substring(0, value.length - 1);
        options[value] = value.toUpperCase();
        this.addMany(options);
        $(this).val(value).trigger('change');
      }
    }
  });

  /**
   * Append the given attempt to the popover.
   *
   * @param {object} attempt
   */
  function appendAttempt(attempt)
  {
    var content = $(this).attr('data-content');
    var channel = attempt.channel.toUpperCase();
    var datetime = moment(attempt.created_at).format('ddd, D [de] MMM, YYYY [às] HH:mm');

    $(this).attr('data-content', (content ? content : '') + '<span class=\'label label-info label-attempt\'>' + channel + '</span> ' + datetime + '<br />');
  }

  /**
   * Remove the last attempt from the popover.
   */
  function removeLastAttempt()
  {
    var content = $(this).attr('data-content');
    var end = content.lastIndexOf('<span ');

    if(end !== -1){
      $(this).attr('data-content', content.substr(0, end).trim());
    }
  }

  (function(){
    var $callback;
    var $modal = $('#edit-contact-attempt-modal');

    /**
     * Opens the modal to edit the callback.
     */
    $(document).on('click', '.callbacks a', function(e){
      e.preventDefault();
      $callback = $(this);
      var request = newXMLHttpRequest({type: 'GET', url: $callback.attr('href')});

      request.addEventListener('load', function(e){
        $modal.find('.modal-body').html(request.responseText);
        $modal.modal('show');
      });

      request.send();
    });

    $modal.on('click', 'form input[type=submit]', function(e){
      e.preventDefault();
      submit.call($modal.find('form[name=edit-contact-attempt-form]')[0]).then(function(response){
        $callback.replaceWith(response);
        $modal.modal('hide');
      });
    });
  })();

  /*
  |--------------------------------------------------------------------------
  | Hotlead & Edit Lead features.
  |--------------------------------------------------------------------------
  */

  (function(){
    var $row;
    var $cell;
    var $modal = $('#edit-lead-modal');

    $('.table-navigation tr.parent-row td').keydown(function(e){
      $row = $(this).closest('tr');
      $cell = $row.find('td.active');

      if(e.shiftKey && e.which == 72){
        // Hotlead (Shift + H)
        var hotlead = $row.data('hotlead');

        copy(document.getElementById(hotlead), function(){
          notify('Sucesso!', 'Hotlead copiado com sucesso', 'success');
        });
      } else if(e.which == 113){
        // Edit lead (F2)
        var request = new XMLHttpRequest();
        request.open('GET', $row.data('edit-url'), true);
        request.setRequestHeader('Accept', 'application/json');

        request.addEventListener('load', function(e){
          $modal.find('.modal-body').html(request.responseText);
          $modal.modal('show');
        });

        request.send();
      }
    });

    /**
     * Saves the Lead.
     */
    $modal.on('click', 'form input[type=submit]', function(e){
      e.preventDefault();
      submit.call($modal.find('form[name=edit-lead-form]')[0]).then(function(response){
        var lead = JSON.parse(response);

        for (var key in lead) {
          // Update row values
          $row.find('.' + key).html(lead[key]);
        }

        $modal.modal('hide');
      });
    });

    /**
     * Focus on first form input when modal shows up.
     */
    $modal.on('shown.bs.modal', function(e){
      $(this).find('form .form-control:first').focus();
    });

    /**
     * Restore focus when modal hides.
     */
    $modal.on('hidden.bs.modal', function(e){
      $cell.focus();
    });
  })();

  /*
  |--------------------------------------------------------------------------
  | Expanded row form features.
  |--------------------------------------------------------------------------
  */

  (function(){
    var $cell;
    var focus;
    var $modal = $('#confirm-row-opening');

    /**
     * Prevent row from opening if first_talk_at is empty,
     * then display the confirmation modal.
     */
    document.addEventListener('row.opening', function(e){
      e.preventDefault();
      var input = e.detail.row.querySelector('input[name=first_talk_at]');

      if(!input) {
        $(e.target).tableNavigation('open');
        return true;
      }

      if(input.value != ''){
        $(e.target).tableNavigation('open');
      } else {
        $cell = $(e.target).find('td.active');
        $modal.modal('show');
      }
    });

    /**
     * Opens the row when clicking on confirm
     */
    $modal.find('.btn-primary').click(function(e){
      focus = false;
      $modal.modal('hide');
      $cell.focus().closest('table').tableNavigation('open');
    });

    /**
     * Focus on primary action when modal shows up.
     */
    $modal.on('shown.bs.modal', function(e){
      focus = true;
      $modal.find('.btn-primary').focus();
    });

    /**
     * Restore focus when modal hides.
     */
    $modal.on('hidden.bs.modal', function(e){
      if(focus){
        $cell.focus();
      }
    });
  })();

  /**
   * Submits the form before closing the row
   */
  document.addEventListener('row.closing', function(e){
    e.preventDefault();
    var form = e.detail.row.querySelector('form[name=edit_lead_qa_form], form[name=edit_lead_purchase_form]');

    submit.call(form).then(function(response){
      $(e.target).tableNavigation('close');
      update.call(form, response);
    });
  });

  document.addEventListener('row.closing', function(e){
      $(e.target).tableNavigation('close');
  });

  /**
   * Closes the row when clickin on "Cancel" button
   */
  $('form[name=edit_lead_qa_form] button[type=cancel], form[name=edit_lead_purchase_form] button[type=cancel]').click(function(e){
    e.preventDefault();
    $(this).closest('.table-navigation').tableNavigation('close');
  });

  /**
   * Prevent form for being submmited when pressing Enter on input
   */
  $('form[name=edit_lead_qa_form] input, form[name=edit_lead_purchase_form] input').not('[type=submit]').keypress(function(e){
    if(e.which == 13){
      e.preventDefault();
      return false;
    }
  });

  $('form[name=edit_lead_qa_form] input[type=submit], form[name=edit_lead_purchase_form] input[type=submit]').click(function(e){
    e.preventDefault();
    var form = $(this).parents('form')[0];

    submit.call(form).then(function(response){
      update.call(form, response);
    });
  });

  /**
   * Handles form submission
   */
  $('form[name=edit_lead_qa_form]').submit(function(e){
    e.preventDefault();
    var form = this;

    submit.call(form).then(function(response){
      update.call(form, response);
    });
  });

  /**
   * Submits the form
   */
  function submit(){
    var form = this;
    var $submit = $(form).find('input[type=submit]');

    return new Promise(function(resolve, reject){
      var request = newXMLHttpRequest({
        type: 'PATCH',
        url: form.action,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      });

      request.addEventListener('load', function(e){
        form.enable();
        $submit.val('Salvar').focus();

        if(this.status == 200){
          $(form).find('.callback-input').val('');
          $(form).find('.dirty').removeClass('dirty');
          notify('Sucesso!', 'O lead foi atualizado com sucesso!', 'success');
          resolve(this.responseText);
        } else if(this.status == 422) {
          var errors = JSON.parse(this.responseText).errors;
          var key = Object.keys(errors)[0];

          $(form).find('[name="' + key + '"]').focus();
          notify('Erro!', errors[key][0], 'error');
          reject();
        } else {
          window.errorHandler(this);
          reject();
        }
      }, false);

      request.send($(form).serialize());
      form.disable();
      $submit.val('Salvando...');
    });
  }

  /**
   * Updates the list of callbacks and history.
   */
  function update(response)
  {
    var $row = $(this).closest('.child-row');
    response = JSON.parse(response);

    if(response.lead_qa_friendly_status){
      var status = $row.prev().find('.lead-qa-status')[0];
      var last = status.classList[status.classList.length - 1];

      if(last != 'lead-qa-status'){
        status.classList.remove(last);
      }

      status.classList.add(response.lead_qa_friendly_status);
    }

    if(response.callback){
      $row.find('.callbacks').append(response.callback);
      $row.find('.callbacks').parent().prev('p').remove();
    }

    if(response.history){
      $row.find('.history').append(response.history);
      $row.find('.history').parent().prev('p').remove();
    }
  }
})();

/*
  |--------------------------------------------------------------------------
  | Send lead
  |--------------------------------------------------------------------------
  */
  let form = {}

  $('button.modal-open').on('click', (e) => {
    form.element = $(e.currentTarget).parents('tr').find('form')
    $('#contact-modal').modal('show');
  })

  $('#contact-modal').find('.btn-primary').click((e) => {
    let valid = true;
    $.each($('#contact-modal').find('input'), (i, element) => {
      if(!element.value){
        $(element).parent().find('.error').remove()
        $(element).parent().append('<span class="label label-danger error">Campo necessário!</span>')
        valid = false;
      }
    })
    
    if(valid) {
      form.data = $('#contact-modal').find('form').serialize()
      sendLead(form)
    }
  })

  function sendLead (form) {
    $.ajax({
      type: "POST",
      url: $(form.element).attr('action'),
      data: $(form.element).serialize() + '&' + form.data,
      success: (success) => {
        console.log(success)
        $('#contact-modal').modal('toggle');
        notify('Sucesso!', 'O lead foi enviado com sucesso!', 'success');
        const date = new Date();
        $(form.element).parents('tr').find('#sendAt').find('button').remove();
        $(form.element).parents('tr').find('#sendAt').html(date.getFullYear()+'-'
                                                        +(date.getMonth()+1)+'-'
                                                        +date.getDate()+' '
                                                        +date.getHours()+':'
                                                        +date.getMinutes() + ':'
                                                        +date.getSeconds())
      },
      error: (error) => {
        console.log(error)
        notify('Erro!', 'O lead não foi enviado! '+error.responseText, 'error');
      }
    });
  }

/*
  |--------------------------------------------------------------------------
  | Edit phone
  |--------------------------------------------------------------------------
  */

$('.fromphone1').on('click', '.btn-edit', (e) => {
  $(e.delegateTarget).find('.wpp').attr('href', 'https://api.whatsapp.com/send?1=pt_BR&phone='+$(e.delegateTarget).find('input[name=fromphone1]').val())
})