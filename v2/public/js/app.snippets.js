(function(){
  /**
   * Validates the uri input when value is changed.
   * Using debounce to prevent multiple requests (see app.prototype.js debounce function for reference)
   */
  $('input[name="uri"]').bind('input', debounce(function(event){
    var $input = $(this);
    var $parent = $input.parents('.item');
    var $icon = $input.next().find('.fa');

    if($input.val() != ''){
      var request = new XMLHttpRequest();

      request.open('GET', BASE_URL + '/snippets/ping?uri=' + $input.val(), true);

      request.addEventListener('load', function(e){
        $icon.removeClass('fa-refresh spin');

        if(JSON.parse(this.responseText)){
          $icon.removeClass('fa-close red').addClass('fa-check green');
          $parent.removeClass('has-error');
        } else {
          $icon.removeClass('fa-check green').addClass('fa-close red');
          $parent.addClass('has-error');
        }
      });

      request.send();
      $icon.removeClass('fa-check green fa-close red').addClass('fa-refresh spin');
      $parent.removeClass('has-error');
    } else {
      $icon.removeClass('fa-check green fa-refresh spin').addClass(' fa-close red');
      $parent.addClass('has-error');
    }
  }, 350));
})();
