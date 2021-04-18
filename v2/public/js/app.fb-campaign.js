$(document).ready(() => {
    $("select.listing").select2();

    $("select.city").select2({
      ajax: {
        url: BASE_URL + '/district/locality',
        dataType: 'json',
        data: (params) => {
          let query = {
            search: params.term,
            column: 'locality_name'
          }
          
          return query;
        },
        processResults: function (data) {
          return {
            results: $.map(data, function(obj) {
              return {id: obj.locality_id, text: obj.locality_name+' - '+obj.locality_state_abbreviation};
            })
          };
        },
      }
    });

    //
    $("select.district").select2({
      ajax: {
        url: BASE_URL + '/district',
        dataType: 'json',
        data: (params) => {
          let query = {
            search: params.term,
            column: 'DISTRITO'
          }
          
          return query;
        },
        processResults: function (data) {
          return {
            results: $.map(data, function(obj) {
              return {id: obj, text: obj};
            })
          };
        },
      }
    });
    

    getTargetingType('select.interest', ['interests'])
    getTargetingType('select.behavior', ['behaviors'])
    getTargetingType('select.life-events', ['life_events'])
    
    $(document).on('change', 'select.listing', e => {
      if($('.active').length !== 0) {
        var target_image = $(e.target).parents('.active').find('.image-picker')
        var target_desc = $(e.target).parents('.active').find('.description')
        $(e.target).parents('.active').find('.image_picker_selector').remove()
      }else{
        var target_image = $('.image-picker')
        var target_desc = $('.description')
        $(e.target).find('.image_picker_selector').remove()
      }
      
      $(target_image).html('')
      
      let listing_id = $(e.target).find('option:selected').text()
      
      $(target_image).parent().removeClass('d-none')
      
      // Request clients
      var request = new XMLHttpRequest();
      request.open('GET', BASE_URL + '/listings?listing_id=' + listing_id, true);
      request.setRequestHeader('Accept', 'application/json');
      
      request.addEventListener('load', function(e){
        if(this.status == 200){
          let listing = JSON.parse(this.responseText);

          if(!listing){
            return false
          }
          
          if(listing && listing.images){
            $.each(listing.images, (i, element) => {
              let newElement = "<option data-img-src='https://media.agenteimovel.com.br/images/"+element.image_myListings+"' value='https://media.agenteimovel.com.br/images/"+element.image_myListings+"'></option>"
              $(target_image).append(newElement)
              $(target_image).imagepicker({limit: 4})
            })

            if($('.active').length !== 0) {
              $('.active').find('.title').val(listing.newconst.listing_title.toUpperCase())
              $('.active').find('.subtitle').val(listing.listing_stname.toUpperCase())
            }else{
              $('.title').val(listing.newconst.listing_title.toUpperCase())
              $('.subtitle').val(listing.listing_stname.toUpperCase())
            }
           
          }
          
          if(listing.newconst && (listing.newconst.listing_description !== 'NULL' && listing.newconst.listing_description !== '')){
            $(target_desc).text(listing.newconst.listing_description)
          }else{
            $(target_desc).val(listing.listing_detail)
          }

          if($('.active').find('.ad-name').length !== 0 && $('.active').find('.adset-name').length !== 0) {
            let adname = ($('.active').find('.ad-name').val().split("_"))

            $('.active').find('.ad-name').val(listing.listing_id+'_'+(typeof adname[1] === 'undefined' ? adname[0] : adname[1]))
            $('.active').find('.adset-name').val($('.active').find('.adset-name').val().split("#")[0]+'#'+listing.listing_id)
          }
          
        } else {
          notify('Erro', this.responseText, 'error')
          console.error(this.status, this.responseText);
        }
      }, false);
      
      request.send();
    })

    $(document).on('change', 'select.city', (e) => {

      let valid = ['9668','2754','7043'];

      if(valid.includes($(e.currentTarget).val())) {
        $('.district').parent().removeClass('d-none')
        $('.neigh').parent().addClass('d-none')
      }else{
        $('.district').parent().addClass('d-none')
        $('.neigh').parent().removeClass('d-none')

        $("select.neigh").select2({
          ajax: {
            url: BASE_URL + '/district/neighborhood?city_id='+$('.active').find('select.city').val(),
            dataType: 'json',
            data: (params) => {
              let query = {
                search: params.term,
                column: 'neighborhood_name'
              }
              
              return query;
            },
            processResults: function (data) {
              return {
                results: $.map(data, function(obj) {
                  return {id: obj.neighborhood_id, text: obj.neighborhood_name};
                })
              };
            },
          }
        });

        var request = new XMLHttpRequest();
        request.open('GET', BASE_URL + '/district/locality?city_id='+$(e.currentTarget).val(), true);
        request.setRequestHeader('Accept', 'application/json');
        
        request.addEventListener('load', function(e){
          const ceps = JSON.parse(this.responseText);
          
          if(ceps) {
            if($('.active').length !== 0) {
              $('.active').find('.cep').text('')
            } else {
              $('.cep').text('')
            }

            $.each(ceps, (i, e) => {
              if($('.active').length !== 0) {
                $('.active').find('.cep').append(e+'\n')
              } else {
                $('.cep').append(e+'\n')
              }
            })

            $('.active').find('.cep').trigger('change')
          }

        })
        
        request.send();

      }
    })

    $(document).on('change', 'select.neigh', (e) => {
        
        var request = new XMLHttpRequest();
        request.open('GET', BASE_URL + '/district/neighborhood?neigh_id='+$(e.currentTarget).val(), true);
        request.setRequestHeader('Accept', 'application/json');
        
        request.addEventListener('load', function(e){
          const ceps = JSON.parse(this.responseText);
          
          if(ceps) {
            if($('.active').length !== 0) {
              $('.active').find('.cep').text('')
            } else {
              $('.cep').text('')
            }

            $.each(ceps, (i, e) => {
              if($('.active').length !== 0) {
                $('.active').find('.cep').append(e+'\n')
              } else {
                $('.cep').append(e+'\n')
              }
            })

            $('.active').find('.cep').trigger('change')

          }
        })
        
        request.send();
    })
    
    $(document).on('change', 'select.district', e => {
      $('.active').find('.cep').text('');
      
      var request = new XMLHttpRequest();
      request.open('GET', BASE_URL + '/district?search='+$(e.target).val()+'&column=DISTRITO&address=true', true);
      request.setRequestHeader('Accept', 'application/json');
      
      request.addEventListener('load', function(e){
        const ceps = JSON.parse(this.responseText);
        
        if(ceps) {
          if($('.active').length !== 0) {
            $('.active').find('.cep').text('')
          } else {
            $('.cep').text('')
          }

          $.each(ceps, (i, e) => {
            if($('.active').length !== 0) {
              $('.active').find('.cep').append(e+'\n')
            } else {
              console.log(e)
              $('.cep').append(e+'\n')
            }
          })

          $('.active').find('.cep').trigger('change')
        }
      })
      
      request.send();
    })
    
    $('.add-tab').on('click', (e) => {

      $('.active').find('.select2-hidden-accessible').removeAttr('data-select2-id')
      $('.active').find('.select2').remove()
      $('.active').find('.select2-hidden-accessible').removeClass('select2-hidden-accessible')

      $('.active').find("select.city").select2({
        ajax: {
          url: BASE_URL + '/district/locality',
          dataType: 'json',
          data: (params) => {
            let query = {
              search: params.term,
              column: 'locality_name'
            }
            
            return query;
          },
          processResults: function (data) {
            return {
              results: $.map(data, function(obj) {
                return {id: obj.locality_id, text: obj.locality_name};
              })
            };
          },
        }
      });

      $('.active').find("select.district").select2({
        ajax: {
          url: BASE_URL + '/district',
          dataType: 'json',
          data: (params) => {
            let query = {
              search: params.term,
              column: 'DISTRITO'
            }
            
            return query;
          },
          processResults: function (data) {
            return {
              results: $.map(data, function(obj) {
                return {id: obj, text: obj};
              })
            };
          },
        }
      });

      $('.active').find('select.listing').select2()

      getTargetingType('select.interest', 'interests')
      getTargetingType('select.behavior', 'behaviors')
    })

    $(".preview").on('click', (event) => {
      event.preventDefault();

      var form = document.getElementById('campaign-form')
      var formData = new FormData(form)

      if($('.active').length !== 0){
        var active_adset = (($('div.active').attr('id').match(/\d+/)[0]) - 1)
        formData.append('active_adset', active_adset)
      }

      $('.active').find('.ad-preview').html('<i class="fa fa-spinner fa-spin" style="font-size: 20px;"></i><br><strong>Gerando preview')

      $(document).scrollTop( $('.ad-preview').offset().top);

      $.ajax({
          url: BASE_URL + '/campaigns/facebook/preview',
          type: 'POST',
          data: formData,
          success: (data) => {
            $('.ad-preview').last().html(JSON.parse(data))
          },
          error: (error) => {
            $('.ad-preview').last().html('<div class="alert alert-danger">Erro ao gerar pre-visualização<hr>'+error.responseText+'</div>')
          },
          cache: false,
          contentType: false,
          processData: false
      })

    })


    var selectedImages = []
    $(document).on('click', 'img.image_picker_image', (e) => {
      var $img = $(e.target)
      var seletect = $img.parents('.thumbnails').find('.selected')
      var $selectElement = $($img.parents('.thumbnails').parent().find('select.image-picker'))

      if($img.parent().hasClass('selected')) {
        selectedImages.push($img.attr('src'))
        $img.before('<span class="badge" style="position:sticky;">'+seletect.length+'</span>')

        if(seletect.length == 4) {
          $selectElement.find('option').remove()          
          $.each(selectedImages, (i, e) => {
            var option = '<option data-img-src="'+e+'" value="'+e+'" selected></option>'
            $selectElement.append(option)
          })

          selectedImages = []
        }
      }else{
        $img.parent().find('span').remove()
        selectedImages.findIndex((element, index) => {
          if(element == $img.attr('src')) selectedImages.pop(index)
        })
      }
    })
  })

  function getTargetingType (element, target) {
    $(element).select2({
      ajax: {
        url: BASE_URL + '/campaigns/facebook/interests',
        dataType: 'json',
        data: (params) => {
          let query = {
            search: params.term,
            target: target
          }
          
          return query;
        },
        processResults: function (data) {
          return {
            results: $.map(data, function(obj, index) {
              return {
                id: obj.id,
                text: obj.name,
              };
            })
          };
        },
      }
    });
  }