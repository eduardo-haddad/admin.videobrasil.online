(function(){
  var rows = [];

  /**
   * Shows the confirmation modal to disqualify the Lead.
   */
  $('table').on('click', '.disqualify-lead', function(e){
    e.preventDefault();
    rows = [];
    rows.push($(this).parents('tr'));
    $('#disqualify-lead-modal form').attr('action', $(this).attr('href'));
    $('#disqualify-lead-modal form input[name="ids"]').removeAttr('value');
    $('#disqualify-lead-modal').modal('show');
  });

  /**
   * Shows the confirmation modal to disqualify all selected Leads.
   */
  $('.bulk-action').on('click', '.bulk-disqualify-lead', function(e){
    e.preventDefault();
    rows = [];
    var checked = $(this).parents('.bulk-action').find('table')[0].getChecked();

    if(checked.length){
      checked.forEach(function(value){
        rows.push(document.getElementById(value));
      });

      $('#disqualify-lead-modal form').attr('action', $(this).attr('href'));
      $('#disqualify-lead-modal form input[name="ids"]').attr('value', JSON.stringify(checked));
      $('#disqualify-lead-modal').modal('show');
    }
  });

  $('tr').keydown(function(e){
    if(e.which == 68 && e.shiftKey){
      // Shift + D
        rows = [];
        rows.push($(this));

        console.log(($(this).find('.disqualify-lead').attr('data-revert')))

        if($(this).find('.disqualify-lead').attr('data-revert') == 1){
          $('#disqualify-lead-modal form').find('input[name=disqualified]').val('0')
          $('#disqualify-lead-modal .modal-title span').show();
          $('#disqualify-lead-modal .modal-title span').not('.revert-disqualify').hide();
          $('#disqualify-lead-modal .modal-body div').show();
          $('#disqualify-lead-modal .modal-body div').not('.revert-disqualify').hide();
          $('#disqualify-lead-modal .modal-body div').find('input').removeAttr('disabled')
          $('#disqualify-lead-modal .modal-body div').not('.revert-disqualify').find('input').attr('disabled', 'disabled')
          $('#disqualify-lead-modal .modal-footer div').show();
          $('#disqualify-lead-modal .modal-footer div').not('.revert-disqualify').hide();
        }else{
          $('#disqualify-lead-modal form').find('input[name=disqualified]').val('1')
          $('#disqualify-lead-modal .modal-title span').hide();
          $('#disqualify-lead-modal .modal-title span').not('.revert-disqualify').show();
          $('#disqualify-lead-modal .modal-body div').hide();
          $('#disqualify-lead-modal .modal-body div').not('.revert-disqualify').show();
          $('#disqualify-lead-modal .modal-body div').find('input').attr('disabled', 'disabled')
          $('#disqualify-lead-modal .modal-body div').not('.revert-disqualify').find('input').removeAttr('disabled')
          $('#disqualify-lead-modal .modal-footer div').hide();
          $('#disqualify-lead-modal .modal-footer div').not('.revert-disqualify').show();
        }
        $('#disqualify-lead-modal form').attr('action', $(this).find('.disqualify-lead').attr('href'));
        $('#disqualify-lead-modal form input[name="ids"]').removeAttr('value');
        $('#disqualify-lead-modal').modal('show');
      }

      // Shift + O
    if(e.which == 79 && e.shiftKey) {
      $('#disqualify-lead-modal form').find('input[name=disqualified]').val('1')
      $('#disqualify-lead-modal form').find('input[name=disqualified_reason_type]').val('other_product')
      
      var request = new XMLHttpRequest();
      request.open('PATCH', $(e.currentTarget).find('.disqualify-lead').attr('href'), true);
      request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
      request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      request.setRequestHeader('Accept', 'application/json');

      request.addEventListener('load', function(e){
        if(this.status == 200){
          $('#disqualify-lead-modal').modal('hide');
          notify('Sucesso!', 'O marcado como busca outro produto.', 'success');

          // Fade out and remove the row
          // rows.forEach(function(row){
          //   $(row).fadeOut($(row).remove);
          // });
        } else if(this.status == 422) {
          var errors = JSON.parse(this.responseText).errors;
          notify('Erro!', errors[Object.keys(errors)[0]][0], 'error');
        } else {
          console.error(this.status, this.responseText);
          $('#disqualify-lead-modal').modal('hide');
          notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
        }
      }, false);

      request.send($('#disqualify-lead-modal form').serialize());
    }
  })

  /**
   * Shows/Hides the disqualify lead textarea.
   */
  if($('.disqualify-lead-options').length){
    document.querySelector('.disqualify-lead-options').addEventListener('click', function(e){
      if(['subsidized', 'other'].indexOf(e.target.children[0].value) != -1){
        $('.disqualified-reason-msg').removeClass('hidden');
      } else {
        $('.disqualified-reason-msg').addClass('hidden');
      }
    });
  }
  

  /**
   * Submits the form to disqualify the Lead.
   */
  if($('form[name="disqualify-lead-form"]').length){
    document.querySelector('form[name="disqualify-lead-form"]').addEventListener('submit', function(e){
      e.preventDefault();

      var request = new XMLHttpRequest();
      request.open('PATCH', this.action, true);
      request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
      request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      request.setRequestHeader('Accept', 'application/json');

      request.addEventListener('load', function(e){
        if(this.status == 200){
          $('#disqualify-lead-modal').modal('hide');
          notify('Sucesso!', 'O lead foi desqualificado com sucesso!', 'success');

          // Fade out and remove the row
          rows.forEach(function(row){
            $(row).fadeOut($(row).remove);
          });
        } else if(this.status == 422) {
          var errors = JSON.parse(this.responseText).errors;
          notify('Erro!', errors[Object.keys(errors)[0]][0], 'error');
        } else {
          console.error(this.status, this.responseText);
          $('#disqualify-lead-modal').modal('hide');
          notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
        }
      }, false);

      request.send($(this).serialize());
    });
  }

  
  if($('select[name="by_resource"]').length){
    /**
     * Shows/Hides the extra field when showing by resource.
     */
    document.querySelector('select[name="by_resource"]').addEventListener('change', function(e){
      if(e.target.value){
        var text = e.target.options[e.target.selectedIndex].text;
  
        $('.resource-id label').text('Id do(a) ' + text);
        $('.resource-id').removeClass('hidden');
      } else {
        $('.resource-id').addClass('hidden');
      }
    });

      /**
     * Trigger the "change" Event on select to
     * display/hidden the extra field when page loads.
     */
    document.querySelector('select[name="by_resource"]').dispatchEvent(new Event('change'));
  }

  
})();
