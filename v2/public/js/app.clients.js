(function(){
  /**
   * Load clients into .client-groups-result based on selected groups.
   */
  document.querySelector('.client-groups').addEventListener('change', function(e){
    var target = document.querySelector('.clients');
    var values = this.getSelected();

    // Request clients
    var request = new XMLHttpRequest();
    request.open('GET', BASE_URL + '/clients?groups=' + JSON.stringify(values), true);
    request.setRequestHeader('Accept', 'application/json');

    request.addEventListener('load', function(e){
      if(this.status == 200){
        var option;
        var clients = JSON.parse(this.responseText);

        // Clear options
        target.clear();

        if(clients.length){
          // Populate the given dataset.target element
          clients.forEach(function(client){
            option = document.createElement('option');
            option.text = client.user_firstname + ' ' + client.user_lastname + ' (' + client.user_id + ')';
            option.value = client.user_id;
            target.add(option);
          });
        } else {
          var option = document.createElement('option');
          option.text = 'Selecione um grupo'
          target.add(option);
        }
      } else {
        console.error(this.status, this.responseText);
      }
    }, false);

    request.send();
  });


  if($('.client-listings').length){
    /**
     * Load listings based on selected clients.
     */
    document.querySelector('.clients').parentNode.on('change', 'clients', function(e){
      var table = $('.client-listings').data('data-table');
      var values = this.getSelected();

      var listingImages = $('.listing-images').length === 1;

      // Clear the table
      table.clear().draw();

      if(values[0] != ''){
        // Get the listings to not include in the search
        var exclude = [];
        var listings;

        if(listings = document.querySelectorAll('.client-listings-exclude')){
          listings.forEach(function(listing){
            exclude.push(listing.dataset.id);
          });
        }

        // Request listings
        var request = new XMLHttpRequest();
        request.open('GET', BASE_URL + '/listings?clients=' + JSON.stringify(values) + '&not_in=' + JSON.stringify(exclude), true);
        request.setRequestHeader('Accept', 'application/json');

        request.addEventListener('load', function(e){
          if(this.status == 200){
            var listings = JSON.parse(this.responseText);

            if(listings.length){
              // Populate the table
              listings.forEach(function(listing){
                var row = null;
                var virtual_tour_id = (listing.virtual_tour !== null) ? listing.virtual_tour.id : null
                var virtual_tour_value = (listing.virtual_tour !== null) ? listing.virtual_tour.url : ''

                // Listing images index
                if(listingImages) {
                  var checked = (listing.newconst && listing.newconst.listing_mcmv == 1) ? 'checked' : null

                  row = table.row.add([
                    '<label class="cbx"><input type="checkbox" name="ids" class="check-mcmv" value="'+listing.listing_id+'"'+
                    ' data-client="'+listing.listing_user_id+'" '+checked+' /><span></span></label>',
                    listing.listing_id,
                    listing.client.user_name + ' (' + listing.client.user_id + ')',
                    listing.client.group.name + ' (' + listing.client.group.id + ')',
                    (listing.newconst) ? listing.newconst.listing_title : null,
                    listing.listing_city + ' (' + listing.listing_state + ')',
                    '<div class="form-group" data-listing_id='+listing.listing_id+'><input name="url" type="text" value="'+virtual_tour_value+'" class="form-control virtual-tour" data-id="'+ virtual_tour_id +'"></div>',
                    `<a href="${BASE_URL}/image/${listing.listing_id}/edit" class="btn btn-primary" target="_blank"><i class="fa fa-edit"></i></a>`
                  ]);
                }
                // Create campaign
                else {
                  row = table.row.add([
                    `<label class="cbx"><input type="checkbox" name="listings[]" value="${listing.listing_id}" ${(listing.campaigns.length ? 'disabled' : '')}><span></span></label>`,
                    listing.listing_id,
                    listing.client.user_name + ' (' + listing.client.user_id + ')',
                    listing.client.group.name + ' (' + listing.client.group.id + ')',
                    (listing.newconst) ? listing.newconst.listing_title : null,
                    listing.listing_city + ' (' + listing.listing_state + ')',
                    '<div class="form-group" data-listing_id='+listing.listing_id+'><input name="url" type="text" value="'+virtual_tour_value+'" class="form-control virtual-tour" data-id="'+ virtual_tour_id +'"></div>',
                    `<i class="fa fa-${(listing.searchsource_onoff ? 'check' : 'close')}"></i>`
                  ]);
                }

                var node = row.node();

                node.children[0].className = 'text-center';
                node.children[node.children.length - 1].className = 'text-center';

                if(listing.campaigns.length){
                  node.className = 'active';

                  $(node).tooltip({
                    title: 'Esse Listing já faz parte da Campanha ' + listing.campaigns[0].id + '.',
                    placement: 'left',
                    container: 'body'
                  });
                }
              });

              table.draw();
            }
          }
        });

        request.send();
      }
    });
  }
})();
