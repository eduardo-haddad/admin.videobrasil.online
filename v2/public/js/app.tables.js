/*
|--------------------------------------------------------------------------
| Table Navigation
|--------------------------------------------------------------------------
*/

(function($) {
  var methods = {
    /**
     * Initialize the plugin
     */
    init: function(){
      var table = this;

      $(table).find('tr td').each(function(key, td){
        // Add tabindex attribute to every cell.
        // Adding negative tab index to not interfire in the original navigation flow.
        var tabindex = -(key+1);
        $(td).attr('tabindex', tabindex);
      });

      if($(table).find('.cell-prio').length){
        // Focus on first cell with .cell-prio class
        $(table).find('.cell-prio:first').addClass('active').focus();
      } else if($(table).find('tbody td').length > 1) {
        // Focus on first cell
        $(table).find('tbody td:first').addClass('active').focus();
      }

      // Focus on cell when clicking on it
      $(table).click(function(e){
        if($(e.target).context == $(e.target).closest('td').context && $(e.target).closest('td').hasClass('active')
        && e.target.localName !== 'a'){
          return false;
        }
        $('td.active').removeClass('active');
        $(e.target).closest('td').addClass('active');
        $(e.target).focus();
      });

      // Add active class when focusing on cell
      $(table).find('td').focus(function(e){
        if($(e.target).context == $(this).closest('td').context && $(this).closest('td').hasClass('active')){
          return false;
        }
        $('td.active').removeClass('active');
        $(this).addClass('active');
      });

      // Add active class on cell when focusing on child cell element
      // $(table).find('td > *').focus(function(e){
      //   if($(e.target).closest('td').hasClass('active')){
      //     return false;
      //   }
      //   $(e.target).closest('td').addClass('active');
      // });

      // // Remove active class when unfocusing cell
      // $(table).find('td').blur(function(e){
      //   if($(e.relatedTarget).prop('tagName') !== 'INPUT'){
      //     $(this).removeClass('active');
      //   }
      // });

      // // Remove active class when unfocusing cell
      // $(document).click(function(e){
      //   if(!$(e.target).closest('.table-navigation').length){
      //     $(table).find('td.active').removeClass('active');
      //   }
      // });

      // Handle keydown event inside the table
      $(table).keydown(function(e){
        var $cell;
        var $current = $(table).find('.parent-row td.active');

        if(e.which == 13){
          // Enter
          if($current.find('a').length){
            $current.find('a')[0].click();
          }
        }
        else if(e.which == 9 && e.shiftKey){
          // Shift + Tab
          $cell = $current.prev();
          $cell.length ? event.preventDefault() : $current.removeClass('active');
        }
        else if(e.which == 9){
          // Tab
          $cell = $current.next();
          $cell.length ? event.preventDefault() : $current.removeClass('active');
        }

        else if(e.which == 40 && e.ctrlKey){
          // Ctrl + Down arrow
          var row = $(table).find('td.active').parent()[0];

          if(e.currentTarget.dispatchAppEvent('row.opening', {detail: {row: row}})){
            methods.open.call(table);
          }
        }
        else if(e.which == 38 && e.ctrlKey){
          // Ctrl + Up arrow
          var row = $(table).find('tr.child-row.active')[0];

          if(row !== undefined && !$(row).hasClass('preqa') && e.currentTarget.dispatchAppEvent('row.closing', {detail: {row: row}})){
            methods.close.call(table);
          }else if(row !== undefined && $(row).hasClass('preqa') && e.currentTarget.dispatchAppEvent('row.closing.noform', {detail: {row: row}})){
            methods.close.call(table);
          }
        }
        else if(e.which == 27){
          // Esc
          methods.close.call(table);
        }

        else if(e.which == 37){
          // Left Arrow
          e.preventDefault();
          $cell = $current.prev();
        }
        else if(e.which == 38){
          // Up arrow
          e.preventDefault();
          var $tr = $($current.closest('tr').prevUntil(null, '.parent-row')[0]);

          if(!($cell = $tr.find('.cell-prio'))){
            $cell = $tr.find('td:eq(' + $current.index() + ')');
          }
        }
        else if(e.which == 39){
          // Right arrow
          e.preventDefault();
          $cell = $current.next();
        }
        else if(e.which == 40){
          // Down arrow
          e.preventDefault();
          var $tr = $($current.closest('tr').nextUntil(null, '.parent-row')[0]);

          if(!($cell = $tr.find('.cell-prio'))){
            $cell = $tr.find('td:eq(' + $current.index() + ')');
          }
        }

        methods.focus.call(table, $current, $cell);
      });
    },

    /**
     * Opens the row
     */
    open: function(){
      // Ctrl + Down arrow
      var $current = $(this).find('td.active');
      var $child = $current.closest('tr').next('tr.child-row');

      if($child && $child.length){
        $('tr.child-row').removeClass('active');
        $current.removeClass('active');
        $child.addClass('active');
        $child.find('.form-control').first().focus();
      }
    },

    /**
     * Closes the row
     */
    close: function(){
      var $current = $(this).find('td.active');
      var $child = $(this).find('tr.child-row.active').removeClass('active');

      if(!$current.length){
        if(!($cell = $child.prev().find('.cell-prio'))){
          $cell = $child.prev().find('td:eq(' + $current.index() + ')');
        }
      } else {
        if(!($cell = $current.closest('tr').prev().find('.cell-prio'))){
          $cell = $current.closest('tr').prev().find('td:eq(' + $current.index() + ')');
        }
      }

      methods.focus.call(this, $current, $cell);
    },

    /**
     * Remove focus from $current and focus on given $cell
     */
    focus: function($current, $cell){
      if($cell && $cell.length){
        $current.removeClass('active');
        $cell.addClass('active').focus();
        $cell.find('> *:not(a)').focus();
      }
    }
  }

  $.fn.tableNavigation = function(methodOrOptions) {
    if (methods[methodOrOptions]) {
      return methods[ methodOrOptions ].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof methodOrOptions === 'object' || ! methodOrOptions) {
      // Default to "init"
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  methodOrOptions + ' does not exist on jQuery.tableNavigation');
    }
  };
}(jQuery));

/*
|--------------------------------------------------------------------------
| Table Freezed
|--------------------------------------------------------------------------
*/

(function($) {
  var methods = {
    /**
     * Initialize the plugin
     */
    init: function(){
      $(this).wrap('<div class="table-freezed-container mt-15"></div>')
             .wrap('<div class="table-freezed-content"></div>');

      $(this).find('thead tr th').each(function(index, th){
        $(th).wrapInner('<div class="out-th"><div class="in-th"></div></div>');
      });
    },
  }

  $.fn.tableFreezed = function(methodOrOptions) {
    if (methods[methodOrOptions]) {
      return methods[ methodOrOptions ].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof methodOrOptions === 'object' || ! methodOrOptions) {
      // Default to "init"
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' +  methodOrOptions + ' does not exist on jQuery.tableNavigation');
    }
  };
}(jQuery));
