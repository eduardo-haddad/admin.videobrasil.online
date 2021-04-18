$(document).ready(() => {
  $.ajaxSetup({ cache: true });
  $.getScript('https://connect.facebook.net/pt_BR/sdk.js', function(){
    FB.init({
      appId: '{your-app-id}',
      version: 'v5.0'
    });
    checkLogin()
    if(sessionStorage.getItem("user_access_token") == undefined) {
      FB.getLoginStatus(updateStatusCallback);
    }
  });
})

function updateStatusCallback (data) {
  if(data.status == 'connected') {
    sessionStorage.setItem("user_access_token", data.authResponse.accessToken);
    $('.fb-login-button').attr('disabled', 'disabled').addClass('d-none')
    $('.fb-login-button, .fb-login-warning').attr('disabled', 'disabled').addClass('d-none')
    $('.reach-estimate').removeClass('d-none')
  }
}

function checkLogin () {
  if(sessionStorage.getItem("user_access_token") == undefined) {
    FB.getLoginStatus(updateStatusCallback);
  }else{
    $('.reach-estimate').removeClass('d-none')
    $('.fb-login-button, .fb-login-warning').attr('disabled', 'disabled').addClass('d-none')
  }
}

function selectCampaigns($element){
  $element.select2({
      ajax: {
        url: BASE_URL + '/campaigns/facebook',
        dataType: 'json',
        data: (params) => {
          let query = {
              search_string: params.term,
          }
          
          return query;
        },
        processResults: function (data) {
          return {
            results: $.map(data, function(obj) {
              return {id: obj.id, text: obj.name};
            })
          };
        },
      }
    });
}