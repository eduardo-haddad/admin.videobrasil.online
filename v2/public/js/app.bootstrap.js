$(document).ready(function(e){
  // Init NProgress
  NProgress.start();

  $(window).load(function() {
    NProgress.done();
  });

  if($('[data-toggle="tooltip"]').length){
    // Init tooltip
    $('[data-toggle="tooltip"]').tooltip();
  }

  if($('[data-toggle="popover"]').length){
    // Init popover

    $.each($('[data-toggle="popover"]'), (i, e) => {

      $(e).popover({
        trigger: $(e).attr('trigger'),
        container: 'body',
        html: true
      });
    })
    
  }

  if($('[data-toggle="collapse"]').length){
    // Create cookie to store the current state of the collapse panel
    $('[data-toggle="collapse"]').click(function(e){
      var position = this.href.indexOf('#');
      var name = this.href.substr(position + 1);
      var cookie = getCookie('collapse');
      var panel;

      if(cookie){
        cookie = JSON.parse(cookie);
        panel = cookie[name];
      } else {
        cookie = {};
      }

      cookie[name] = (panel === 1) ? 0 : 1;
      setCookie('collapse', JSON.stringify(cookie));
    });
  }

  if($('[data-toggle-target]').length){
    // Toggle hidden class for the given target
    $('[data-toggle-target]').click(function(e){
      var target = $(this).data('toggle-target');
      $(target).toggleClass('hidden');
    });
  }

  if($('[data-for]').length){
    // Mimics "for" attribute behaviour
    $('[data-for]').click(function(e){
      $(this).toggleClass('active');
      $(document.getElementById(this.dataset.for)).trigger('click');
    });

    $('[data-for]').each(function(){
      $(this).toggleClass('active', document.getElementById(this.dataset.for).checked);
    });
  }

  // Create cookie to store the current state of the collapse sidebar
  $('#menu_toggle').click(function(){
    var cookie = getCookie('collapse');

    if(cookie){
      cookie = JSON.parse(cookie);
      body = cookie['sidebar'];
    } else {
      cookie = {};
    }

    cookie['sidebar'] = $('body').hasClass('nav-md') ? 1 : 0;
    setCookie('collapse', JSON.stringify(cookie));
  });

  if($('.date-picker').length){
    // Init Date Picker
    $('.date-picker').datetimepicker({
      format: DATE_FORMAT
    });
  }

  if($('.date-time-picker').length){
    // Init Date Time Picker
    document.querySelectorAll('.date-time-picker').forEach(function(node){
      $(node).datetimepicker({
        format: DATETIME_FORMAT,
        sideBySide: true,
        useCurrent: (!$(node).hasClass('use-placeholder'))
      });
    });

    $('.date-time-picker.use-placeholder').focus(function(e){
      if($(this).val() == ''){
        $(this).val(this.placeholder);
      }
    });
  }

  if($('.date-picker').length){
    // Init Date Time Picker
    document.querySelectorAll('.date-picker').forEach(function(node){
      $(node).datetimepicker();
    });

    $('.date-picker.use-placeholder').focus(function(e){
      if($(this).val() == ''){
        $(this).val(this.placeholder);
      }
    });
  }

  if($('.time-picker').length){
    // Init Date Time Picker
    document.querySelectorAll('.time-picker').forEach(function(node){
      $(node).datetimepicker({
        format: 'LT',
      });
    });
  }

  if($('.date-time-picker-inline').length){
    // Init Date Time Picker (Inline)
    $('.date-time-picker-inline').datetimepicker({
      format: DATETIME_FORMAT,
      inline: true,
      sideBySide: true
    });
  }

  if($('.date-range-picker').length){
    // Init Date Range Picker
    $('.date-range-picker').daterangepicker({
      autoUpdateInput: false,
      ranges: {
        'Hoje': [moment(), moment()],
        'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
        'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
        'Esse Mês': [moment().startOf('month'), moment().endOf('month')],
        'Mês Passado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      maxSpan: {
        days: 31
      },
      locale: {
        format: DATE_FORMAT,
        separator: ' - ',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'De',
        toLabel: 'Até',
        customRangeLabel: 'Personalizado',
        weekLabel: 'S',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro']
      }
    });

    $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('.date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  }

  if($('.data-table').length){
    // Init DataTable
    document.querySelectorAll('.data-table').forEach(function(node){
      $(node).data('data-table', $(node).DataTable({
        lengthMenu: [15, 30, 50],
        language: {
          url: BASE_URL + '/lang/pt/datatable.json'
        }
      }));
    });
  }

  if($('.data-table-less').length){
    // Init DataTable
    document.querySelectorAll('.data-table-less').forEach(function(node){
      $(node).data('data-table', $(node).DataTable({
        lengthChange: false,
        searching: false,
        pagingType: 'simple',
        order: [],
        language: {
          url: BASE_URL + '/lang/pt/datatable.json'
        }
      }));
    });
  }

  if($('.table-navigation').length){
    // Init Table Navigation
    $('.table-navigation').tableNavigation();
  }

  if($('.table-freezed').length){
    // Init Table Freezed
    $('.table-freezed').tableFreezed();
  }

  if($('.collapse-content').length){
    // Init collapse content
    $('.collapse-content').click(function(e){
      $(this).closest('.x_panel').find('.x_content').slideToggle(200);
      $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
    });
  }

  if($('.bulk-action').length){
    // Init bulk action (check all)
    document.querySelectorAll('.bulk-action').forEach(function(node){
      node.on('click', 'check-all', function(event){
        var checked = this.checked;

        node.querySelectorAll('tbody input[type="checkbox"]:not(.check-all):not(.not-bulk)').forEach(function(checkbox){
          checkbox.checked = checked
        });
      });
    });
  }

  if($('.toggle-switch').length){
    // Init Switchery
    document.querySelectorAll('.toggle-switch').forEach(function(node){
      $(node).data('switchery', new Switchery(node, {
        color: '#26B99A',
      }));
    });
  }

  if($('.panel-filter').length){
    (function(){
      // Fix .panel-filter on top when scrolling
      var element = document.querySelector('.panel-filter');
      var elementOffset = element.getBoundingClientRect().top + window.scrollY;

      $(element).parent().prepend('<div class="element-replace" style="display:none; height:' + $(element).find('.panel-heading').height() * 4 + 'px"></div>');

      $(window).scroll(function(e){
        if(elementOffset < window.pageYOffset - 5){
          var left = $('.left_col').width() + 'px';
          $('.element-replace').show();
          $(element).addClass('fixed').css({left: left});
        } else {
          $('.element-replace').hide();
          $(element).removeClass('fixed');
        }
      });
    })();
  }

  if($('.toggle-resource-status').length){
    /**
     * Toggle the resource status.
     *
     * @attr {string} data-href
     */
    $('.toggle-resource-status').change(function(e){
      var checkbox = this;
      var row = $(checkbox).parents('tr')[0];

      var request = new XMLHttpRequest();
      request.open('PATCH', $(this).data('href'), true);
      request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
      request.setRequestHeader('Content-Type','application/json; charset=utf-8');
      request.setRequestHeader('Accept', 'application/json');

      request.addEventListener('load', function(e){
        if(this.status == 200){
          row.className = JSON.parse(this.responseText);
        } else {
          checkbox.checked = !checkbox.checked;
          $(checkbox).data('switchery').setPosition(!checkbox.checked);
          notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
          console.error(this.status, this.responseText);
        }
      }, false);

      request.send(JSON.stringify({'status': checkbox.checked}));
    });
  }

  if($('.destroy-resource').length){
    /**
     * Destroy the resource.
     *
     * @attr {string} href
     * @attr {string} data-modal - id of confirmation modal
     * @attr {string} message
     */
    $('.destroy-resource').click(function(e){
      e.preventDefault();
      var row = $(this).parents('tr');
      var href = $(this).attr('href');
      var modal = '#' + $(this).data('modal');
      var message = $(this).data('message');

      $(modal).modal('show');

      $(modal).find('.btn-primary').click(function(e){
        e.preventDefault();
        var request = new XMLHttpRequest();
        request.open('DELETE', href, true);
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

        request.addEventListener('load', function(e){
          $(modal).find('.btn-primary').unbind('click');
          $(modal).modal('hide');

          if(this.status == 200){
            notify('Sucesso!', message, 'success');
            // Fade out and remove the row
            row.fadeOut(row.remove);
          } else {
            notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
            console.error(this.status, this.responseText);
          }
        }, false);

        request.send();
      });
    });
  }

  $.each($('.mask-double'), (i, e) => {
    new Cleave(e, {
      numeral: true,
      numeralDecimalMark: ',',
      delimiter: '.',
      prefix: 'R$ '
    });
    
    // Add decimals on blur event
    $(e).on('blur', function(e){
      if(this.value != 'R$ ' && this.value.indexOf(',') === -1){
        this.value = this.value + ',00';
      }
    });
  })

  if($('.ctc').length){
    $('.ctc').each(function(key, element){
      if(element.dataset.ctcTarget){
        element = $(element).find(element.dataset.ctcTarget);
      }

      $(element).append('<span class="ctc-child"></span>');
    });

    // Copy to Clipboard
    $('.ctc').keydown(function(e){
      if(e.which == 13 || (e.ctrlKey && e.which == 67)){
        var target = this.dataset.ctcTarget ? this.querySelector(this.dataset.ctcTarget) : this;

        copy(target, function(){
          $(e.target).find('.ctc-child').fadeIn('fast').fadeOut('slow');
        });
      }
    });
  }

  if($('form.highlight-dirty').length){
    // Init form dirty status
    $('form.highlight-dirty input').change(function(e){
      if(this.value != this.defaultValue){
        $(this).addClass('dirty');
      } else {
        $(this).removeClass('dirty');
      }
    });

    $('form.highlight-dirty select').change(function(e){
      var defaultSelected = [].filter.call(this.options, function(option){
        return option.defaultSelected;
      })[0].value;

      if(this.value != defaultSelected){
        $(this).addClass('dirty');
      } else {
        $(this).removeClass('dirty');
      }
    });
  }

  if($('form button[type=reset]').length){
    // Init reset form button
    $('form button[type=reset]').click(function(e){
      e.preventDefault();
      var elements = $(this).closest('fieldset')[0].elements;

      for (var i = 0; i < elements.length; i++) {
        var element = elements[i];

        if(['submit', 'reset'].indexOf(element.type) === -1){
          switch (element.type) {
            case 'checkbox':
              element.checked = false;
              break;

            case 'select-one':
              element.value = element.options[0].value;
              break;

            default:
              element.value = '';
          }

          elements[i].dispatchEvent(new Event('change'));
        }
      }
    });
  }

  // Init Validator
  validator.message.date = 'A data não é válida.';
  validator.message.empty = 'O campo é obrigatório.';
  validator.message.email = 'O endereço de email é inválido.';

  // Validate field on "blur" event, a 'select' on 'change' event
  $('form[novalidate]').on('blur', 'input[required], input.optional, select.required', validator.checkField)
           .on('change', 'select.required', validator.checkField)
           .on('keypress', 'input[required][pattern]', validator.keypress);

  $('form[novalidate]').submit(function(e) {
    e.preventDefault();

    // evaluate the form using generic validation
    if (!validator.checkAll($(this))) {
      return false;
    }

    this.submit();
  });
});
