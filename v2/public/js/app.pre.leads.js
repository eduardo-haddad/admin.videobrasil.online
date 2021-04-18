$(document).ready(() => {
  $('form select[name=by_status').on('change', (e) => {
    if($(e.target).val()){
      var elements = $(e.target).parents('fieldset')[0].elements;
      
      for (var i = 0; i < elements.length; i++) {
        var element = elements[i];
        
        if(element.name == 'by_status') {
          return false;
        }
        
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
    }
  })

  $('.ad-preview').hover((e) => {
    let $element = $(e.target)[0]

    var request = newXMLHttpRequest({
      type: 'GET',
      url: $element.dataset.url,
      headers: {
        'Content-Type': 'application/json; charset=utf-8'
      }
    });

    request.addEventListener('load', function(e){
      if(this.status == 200){
        $element.dataset.content = this.response
        $('.popover-content').last().html(this.response)

        if(!this.response) {
          $('.popover-content').last().text('Lead sem anúncio')
        }
        
      } else {
        window.errorHandler(this);
      }
    });

    if(!$element.dataset.content){
      $('.popover-content').last().text('Carregando...')
      request.send()
    }
  })


  //Consulta Alternativa and Visita Alternativa handler
  $('.ca-btn').on('click', (e) => {
    var text = $(e.target).parent().find('.ca-text').text()

    $(e.target).parent().find('input[name=presale_type]').val('C.A')

    $('#contact-modal').find('textarea').text(text)
  })

  $('.va-btn').on('click', (e) => {
    var text = $(e.target).parent().find('.va-text').text()

    $(e.target).parent().find('input[name=presale_type]').val('V.A')

    $('#contact-modal').find('textarea').text(text)
  })
})