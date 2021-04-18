$(document).on('keyup', '.virtual-tour', (e) => {
    var listing_id = $(e.target).parent().attr('data-listing_id')
    var event = 'POST'
    var virtualTourId = false

    if($(e.target).attr('data-id') !== 'null') {
        event = 'PUT'
        var virtualTourId = $(e.target).attr('data-id')
    }

    switch(e.which){
        case 13: // Enter
          tour(listing_id, e.target.value, event, virtualTourId, e.target);
          break;
  
        case 46: // Delete
          e.target.value = ''
          break;
      }
})

function tour(listing_id, link, event, virtualTourId, element) {
    var url = BASE_URL+'/virtual-tour'
    if(virtualTourId) url = url+'/'+virtualTourId

    var request = newXMLHttpRequest({type: 'POST', url: url});

    var formData = new FormData()
    formData.append('_method', event)
    formData.append('url', link)
    formData.append('listing_id', listing_id)

    request.addEventListener('load', function(e){
      if(this.status == 200){
        if(event == 'POST') {
          var response = JSON.parse(this.response)
          $(element).attr('data-id', response.id)
        }
        notify('Sucesso', '', 'success')
      } else if(this.status == 403){
        notify('Ops...', 'Não foi possivel realizar a ação.', 'warning');
      } else {
        window.errorHandler(this);
      }
    }, false);

    request.send(formData);
}