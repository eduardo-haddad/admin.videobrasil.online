/**
 * Add a event listener that works with dynamic created elements
 *
 * @param {string} eventName
 * @param {string} dynamicChild - class name
 * @param {function} handler
 *
 * eg: document.querySelector('.static-parent').on(click, 'dynamic-child-class', function(){...});
 */
HTMLElement.prototype.on = function(eventName, dynamicChild, handler){
  (function(dynamicChild){
    this.addEventListener(eventName, function(e){
      if (e.target.classList.contains(dynamicChild)) {
        handler.call(e.target, e);
      } else {
        e.stopPropagation();
      }
    });
  })(dynamicChild);
}

/**
 * Disable all fields
 */
HTMLFormElement.prototype.disable = function(){
  Array.prototype.forEach.call(this.elements, function(element){
    element.disabled = 'disabled';
  });
}

/**
 * Enable all fields
 */
HTMLFormElement.prototype.enable = function(){
  Array.prototype.forEach.call(this.elements, function(element){
    element.removeAttribute('disabled');
  });
}

/**
 * Return an array of selected values
 *
 * @return {array}
 */
HTMLSelectElement.prototype.getSelected = function(){
  var values = [];
  var option;

  for (var i=0, len = this.options.length; i < len; i++) {
    option = this.options[i];

    if(option.selected){
      values.push(option.value);
    }
  }

  return values;
}

/**
 * Add multiple options to the select element.
 *
 * @param {array} options
 * @param {string} previousValue
 * @param {boolean} clear
 */
HTMLSelectElement.prototype.addMany = function(options, previousValue, clear){
  if(clear){
    this.clear();
  }

  for(var key in options){
    option = document.createElement('option');
    option.text = options[key]
    option.value = key;

    if(previousValue == key){
      options.selected = 'selected';
    }

    this.add(option);
  }
}

/**
 * Clear all available options
 */
HTMLSelectElement.prototype.clear = function(){
  this.options.length = 0;
}

/**
 * Return an array of checked values
 */
HTMLTableElement.prototype.getChecked = function(){
  var values = [];

  this.querySelectorAll('tbody input[type="checkbox"]:not(.not-bulk)').forEach(function(checkbox){
    if(checkbox.checked){
      values.push(checkbox.value);
    }
  });

  return values;
}

/**
 * Dispatches custom event
 *
 * @param {string} name
 * @param {object} args
 */
Element.prototype.dispatchAppEvent = function(name, args){
  var props = Object.assign({bubbles: true, cancelable: true}, args);
  var e = new CustomEvent(name, props);

  return this.dispatchEvent(e);
}

/**
 * Returns a new XMLHttpRequest object
 */
window.newXMLHttpRequest = function(config){
  var request = new XMLHttpRequest();

  request.open(config.type, config.url, true);

  if(config.type != 'GET'){
    request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
  }

  if(config.headers){
    for(header in config.headers){
      request.setRequestHeader(header, config.headers[header]);
    }
  }

  request.setRequestHeader('Accept', 'application/json');

  return request;
}

/**
 * Global Error Handling
 */
window.errorHandler = function(request){
  switch (request.status) {
    case 422:
      var errors = JSON.parse(request.responseText).errors;
      notify('Erro!', errors[Object.keys(errors)[0]][0], 'error');
      break;

    default:
      notify('Erro!', 'Alguma coisa deu errado. Tente novamente.', 'error');
      console.error(request.status, request.responseText);
  }
}

/**
 * Creates a new notification
 *
 * @param {string} title
 * @param {string} text
 * @param {string} type
 */
window.notify = function(title, text, type){
  PNotify.alert({
    title: title,
    text: text,
    type: type ? type : 'info',
    styling: 'bootstrap3',
    delay: 1500
  });
}

/**
 * Copy to clipboard.
 *
 * @param {HTMLElement} target
 * @param {function} success
 */
window.copy = function(target, success){
  var temp = document.createElement('div');
  var range = document.createRange();
  var selection = window.getSelection();

  // Create a temp element since we can't copy hidden elements
  temp.style.position = 'absolute';
  temp.style.top = '-1000px';
  temp.style.right = '-1000px';
  temp.style.color = 'initial';
  temp.innerText = ['text', 'hidden'].indexOf(target.type) !== -1 ? target.value : target.innerText;
  document.body.appendChild(temp);

  range.selectNode(temp);
  selection.removeAllRanges();
  selection.addRange(range);

  if(document.execCommand('copy')){
    success.call();
  } else {
    notify('Erro!', 'Não foi possível copiar o valor.', 'error');
  }

  // Remove the temp element
  //document.body.removeChild(temp);
}

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing.
 *
 * @param {function} func
 * @param {int} wait
 * @param {bool} immediate
 */
window.debounce = function(func, wait, immediate) {
	var timeout;

	return function() {
		var context = this, args = arguments;

    var later = function() {
			timeout = null;

			if (!immediate){
        func.apply(context, args);
      }
		};

    var callNow = immediate && !timeout;
    clearTimeout(timeout);
		timeout = setTimeout(later, wait);

    if (callNow){
      func.apply(context, args);
    }
	};
}

/**
 * Creates new cookie
 *
 * @param {string} name
 * @param {string} value
 * @param {int} exdays
 */
window.setCookie = function(name, value, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

/**
 * Gets the cookie's value
 *
 * @param {string} name
 */
window.getCookie = function(name) {
    var name = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');

    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];

        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }

    return;
}
