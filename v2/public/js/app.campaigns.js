(function(){
  /**
   * Clone the Campaign.
   */
  document.querySelector('table').on('click', 'clone-campaign', function(e){
    e.preventDefault();

    if(!this.getAttribute('disabled')){
      var form = document.querySelector('form[name="clone_campaign_form"]');
      form.action = this.href;
      form.submit();
    }
  });

  /**
   * Submits the form when "status" filtering changes.
   */
  $('[name="status[]"]').change(debounce(function(e){
    this.form.submit();
  }, 1000));
})();
