(function(){
  /**
   * Reload the XML.
   */
  document.querySelector('table').on('click', 'reload-xml', function(e){
    e.preventDefault();

    if(!this.getAttribute('disabled')){
      var form = document.querySelector('form[name="reload_xml_form"]');
      form.action = this.href;
      form.submit();
    }
  });
})();
