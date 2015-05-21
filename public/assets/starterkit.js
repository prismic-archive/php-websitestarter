$(function() {


  window.addEventListener("message", function(message) {

    if(message.data.event == 'starterkit:resize') {
      $('.starter-popup').height(message.data.height + 'px');
    }

    if(message.data.event == 'starterkit:fork') {
      window.location.href = message.data.url;
    }
  });

  $('.button.home').click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    displayPopup();

  });

  $(document.body).click(function() {
    var $overlay = $('.starter-overlay'),
        $popup = $('.starter-popup');

    if($overlay.length) {
      $overlay.remove();
      $popup.remove();
    }
  });

  //----------

  function displayPopup() {

    var url = 'https://prismic.io/starterkit/fork';

    var html =
          '<div class="starter-popup">\
            <iframe style="border:none; border-radius: 4px;" height="100%" width="100%" src="' + url + '"></iframe>\
          </div>';

    var $popup = $(html).css('position', 'absolute')
                        .css('top', '50%')
                        .css('left', '50%')
                        .css('-webkit-transform','translate(-50%, -50%)')
                        .css('-ms-transform', 'translate(-50%, -50%)')
                        .css('transform', 'translate(-50%, -50%)')
                        .css('height', '414px')
                        .css('width', '350px')
                        .css('background', '#FFF')
                        .css('z-index', '2')
                        .css('transition', 'height 0.2s')
                        .css('moz-transition', 'height 0.2s')
                        .css('-o-transition', 'height 0.2s')
                        .css('-webkit-transition', 'height 0.2s')
                        .css('border-radius', '4px');

    var $body = $(document.body);

    $('<div class="starter-overlay"></div>').css('position', 'fixed')
                                            .css('top', '0')
                                            .css('left', '0')
                                            .css('right', '0')
                                            .css('bottom', '0')
                                            .css('background', 'rgba(50, 51, 114, 0.94)')
                                            .css('z-index', '1')
                                            .appendTo($body);

    $body.append($popup);
  }
});
