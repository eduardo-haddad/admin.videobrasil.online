(function(){
    /**
     * Load listing into based on selected clients.
     */
    document.querySelector('.clients').addEventListener('change', function(e){
      var target = document.querySelector('.listings');
      var values = this.getSelected();
  
      // Request clients
      var request = new XMLHttpRequest();
      request.open('GET', BASE_URL + '/listings?clients=' + JSON.stringify(values), true);
      request.setRequestHeader('Accept', 'application/json');
  
      request.addEventListener('load', function(e){
        if(this.status == 200){
          var option;
          var listings = JSON.parse(this.responseText);
  
          // Clear options
          target.clear();
  
          if(listings.length){
            // Populate the given dataset.target element
            listings.forEach(function(listing){
              option = document.createElement('option');
              option.text = listing.newconst.listing_title
              option.value = listing.listing_id
              target.add(option);
            });
          } else {
            var option = document.createElement('option');
            option.text = 'Selecione um cliente'
            target.add(option);
          }
        } else {
          console.error(this.status, this.responseText);
        }
      }, false);
  
      request.send();
    });
  })();

  $(document).ready(() => {
    /**
     * Set selected listings as mcmv
     */
    $(document).on('click', '.check-mcmv', (e) => {
      $.ajax({
        type: "PUT",
        url: BASE_URL+'/listings/'+$(e.target).val(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {'listing_mcmv': $(e.target).is(':checked') ? '1' : '0'},
        success: (data) => {
          notify('Sucesso', 'Empreendimento '+($(e.target).is(':checked') ? 'marcado' : 'desmarcado')+' como minha casa minha vida', 'success')
        },
        error: (error) => {
          notify('Erro', error, 'error')
          console.log(error)
        }
      });
      
    });

    $(document).on('click', '.bulk-client-mcmv', (e) => {
      e.preventDefault();
          $.ajax({
            type: "PUT",
            url: BASE_URL+'/clients/'+$('.bulk-action').find('table').find('input[name=ids]').first().attr('data-client') ,
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'listing_mcmv_all': $(e.target).attr('data-mcmv')},
            success: (data) => {
                $('.bulk-action').find('table').find('input[name=ids]').attr('checked', ($(e.target).attr('data-mcmv') == 'on' ? true : false))
                notify('Sucesso', 'Empreendimentos '+($(e.target).attr('data-mcmv') == 'on' ? 'marcados' : 'desmarcados')+' como minha casa minha vida', 'success')
              
            },
            error: (error) => {
              notify(error, 'error')
              console.log(error)
            }
          });
    });
  })
  