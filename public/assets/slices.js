$(function(){

  $('.button.home').click(function(e) {
    e.preventDefault();
  });

  (function Slider() {

    if(window.Zanimo) {

      var duration = 400;

      (function HomeButton() {

        var matches = prismic.endpoint.match(new RegExp("(https?://(.*?)/)")) || [];

        var baseURL = matches[2].replace(/\.cdn\.prismic\.io/, ".prismic.io");

        if(baseURL == 'blogtemplate.prismic.io') {

          $('.home .slides .button').addClass('home');

        }

      })();

      function previous(e) {
        e.preventDefault();

        var $slides = $(this).parent('.slides');

        if(!$slides.is('.moving')) {

          var $current = $slides.find('.slide.active');
          var $prev = $current.prev('.slide');

          if (!$prev.length) {
            $prev = $slides.find('.slide:last');
          }

          $slides.addClass('moving');

          Zanimo($prev[0], 'transform', 'translate3d(-100%, 0, 0)', 0).then(function() {
            Zanimo($current[0], 'transform', 'translate3d(100%, 0, 0)', duration);
            Zanimo($prev[0], 'transform', 'translate3d(0, 0, 0)', duration).fin(function() {
              $slides.removeClass('moving');
            });
          });

          $current.removeClass('active');
          $prev.addClass('active');

        }
      }

      function next(e) {
        e.preventDefault();

        var $slides = $(this).parent('.slides');

        if(!$slides.is('.moving')) {

          var $current = $slides.find('.slide.active');
          var $next = $current.next('.slide');

          if (!$next.length) {
            $next = $slides.find('.slide:first');
          }

          $slides.addClass('moving');

          Zanimo($next[0], 'transform', 'translate3d(100%, 0, 0)', 0).then(function() {
            window.setTimeout(function() {
              Zanimo($current[0], 'transform', 'translate3d(-100%, 0, 0)', duration);
            }, 10);
            Zanimo($next[0], 'transform', 'translate3d(0, 0, 0)', duration).fin(function() {
              $slides.removeClass('moving');
            });
          });

          $current.removeClass('active');
          $next.addClass('active');

        }
      }

      var $init = $('.slides .slide:first');
      $init.addClass('active');
      Zanimo($init[0], 'transform', 'translate3d(0, 0, 0)', 0);

      $('.slides .arrow-prev').on('click', previous);
      $('.slides .arrow-next').on('click', next);

    }

  })();

  (function FeaturedItem() {
    var viewportWidth =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    if(viewportWidth > 767) {
      $('.featured-items').each(function() {
        var $featuredItems = $(this);
        var height = $featuredItems.find('> div').toArray().reduce(function(acc, x) {
          var h = $(x).height();
          return (h > acc) ? h : acc;
        }, 0);
        var buttonHeight = $featuredItems.find('.button').height();
        $featuredItems.find('> div').css('height', 20 + buttonHeight + height + 'px');
      });
    }
  })();

  (function FeaturedItemPreview() {

    function select() {
      var $previewPane = $(this).parents('.featured-preview').find('.preview-pane');
      var url = $(this).data('illustration');
      $previewPane.css('background', 'url('+url+') no-repeat center center');
    }

    $('.featured-preview [data-illustration]:not(:first-child)').each(function() {
      var url = $(this).data('illustration');
      var image = new Image();
      image.src = url;
    });

    $('.featured-preview [data-illustration]').on('mouseenter', select);
    $('.featured-preview [data-illustration]').first().map(select);

  })();

  (function Map() {

    $('.contact-us .map').each(function() {

      var mapEl = this,
          $map = $(mapEl),
          address = $map.data('address');

      if(address) {

        new google.maps.Geocoder().geocode({address: address}, function(results, status) {

          if(results && results[0]) {

            var location = results[0].geometry.location;
            var map = new google.maps.Map(mapEl, {
              center: location,
              zoom: 16,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            map.setOptions({styles: [
              {
                "featureType": "poi",
                "stylers": [
                  { "saturation": -100 },
                  { "visibility": "off" }
                ]
              },{
                "stylers": [
                  { "saturation": -100 }
                ]
              }
            ]});


            var marker = new google.maps.Marker({
              position: location,
              map: map
            });

          }

        });

      }

    });


  })();


  (function ContactUs() {

    var $form = $('form[name=contact-form]'),
        $submit = $form.find('button.send'),
        pubKey = $form.find('[name=pubkey]').val(),
        token = $form.find('[name=token]').val(),
        $sender = $form.find('[name=sender]'),
        $subject = $form.find('[name=subject]'),
        $message = $form.find('[name=message]'),
        $feedback = $form.find('.feedback'),

        notEmpty = function($input) {
          if ($.trim($input.val()) == "") {
            $input.addClass("has-error");
            $submit.attr("disabled", "disabled");
          } else {
            $input.removeClass("has-error");
            validate();
          }
        },

        validate = function() {
          if ($(".has-error").length > 0) return;

          if ($.trim($sender.val()) == "" ||
              $.trim($subject.val()) == "" ||
              $.trim($message.val()) == "") { // not changed empty val
            return;
          }
          $submit.removeAttr("disabled");
        },

        onChange = function() {

          $feedback.text('');

          var $input = $(this);
          notEmpty($input);
        };

    $subject.on('change keyup', onChange);

    $message.on('change keyup', onChange);

    $sender.on('change keyup', function() {

      $feedback.text('');

      var email = $sender.val();

      if (email.length < 7) { // quick client validation
        $sender.addClass("has-error");
        $submit.attr("disabled", "disabled");
        return;
      }

      run_validator(email, {
        api_key: pubKey,
        success: function(res){
          if (res.is_valid) {
            $sender.removeClass("has-error");
            validate();
          } else {
            $sender.addClass("has-error");
            $submit.attr("disabled", "disabled");
          }
        }
      });
    });

    $submit.click(function() {

      $submit.attr("disabled", "disabled");

      $submit.text('sending...');

      $.ajax({
        type: "POST",
        url: "/contact",
        dataType: "json",
        data: {
          'token': token,
          'sender': $sender.val(),
          'subject': $subject.val(),
          'message': $message.val()
        }
      }).then(function(res) {
        $feedback.addClass('success');
        $feedback.text($feedback.data('success'));
      }).fail(function(res) {
        $feedback.addClass('error');
        $feedback.text($feedback.data('error'));
      }).always(function() {
        $subject.val('');
        $message.val('');
        $sender.val('');
        $submit.text('send');
        $submit.removeAttr("disabled");
      });

      return false;
    });

  })();
});
