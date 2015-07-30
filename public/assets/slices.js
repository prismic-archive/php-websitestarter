$(document).ready(function () {

  'use strict';

  $('.play').on('click', function () {
    if($($(this).closest('.video-banner')).hasClass('active')) {
        $($(this).closest('.video-banner')).removeClass('active');
        $($(this).closest('.video-banner')).find('.video video').removeAttr('controls');
        $($(this).closest('.video-banner')).find('.video video').attr('muted', true);
    } else {
        $($(this).closest('.video-banner')).addClass('active');
        $($(this).closest('.video-banner')).find('.video video').get(0).pause();
        $($(this).closest('.video-banner')).find('.video video').get(0).currentTime = 0;
        $($(this).closest('.video-banner')).find('.video video').get(0).play();
        $($(this).closest('.video-banner')).find('.video video').attr('controls', true);
        $($(this).closest('.video-banner')).find('.video video').removeAttr('muted');
    }
  });

  (function Slider() {

    function previous(e) {
      e.preventDefault();

      var $slides = $(this).parent('.slides');

      var $current = $slides.find('.slide.active'),
          $prev = $current.prev('.slide');

      if (!$prev.length) {
        $prev = $slides.find('.slide:last');
      }

      $current.removeClass('active');

      $prev.addClass('active');
    }

    function next(e) {
      e.preventDefault();

      var $slides = $(this).parent('.slides');

      var $current = $slides.find('.slide.active'),
          $next = $current.next('.slide');

      if (!$next.length) {
        $next = $slides.find('.slide:first');
      }

      $current.removeClass('active');

      $next.addClass('active');
    }

    // Preload image

    $('.slides [data-illustration]').each(function() {

      var url = $(this).data('illustration');

      var image = new Image();

      image.src = url;

    });

    $('.slides .arrow-prev').on('click', previous);
    $('.slides .arrow-next').on('click', next);

  })();

  (function FeaturedItemPreview() {

    function select() {
      var paneId = $(this).attr('data-paneid');
      var $previewPane = $(this).parents('.featured-preview').find('.preview-pane');
      $previewPane.find('.preview-image').css('opacity', 0);
      $previewPane.find('.preview-image[data-paneid="' + paneId + '"]').css('opacity', 1);
    }

    $('.featured-preview [data-paneid]').on('mouseenter', select);
    $('.featured-preview [data-paneid]').first().map(select);

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
        $sender = $form.find('[name=sender]'),
        $subject = $form.find('[name=subject]'),
        $mailto = $form.find('[name=mailto]'),
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

    var lastEmail;

    $sender.on('change keyup', function() {

      $feedback.text('');

      var email = $sender.val();

      lastEmail = email;

      if (email.length < 7) { // quick client validation
        $sender.addClass("has-error");
        $submit.attr("disabled", "disabled");
        return;
      }

      run_validator(email, {
        api_key: pubKey,
        success: function(res){
          if(res.address == lastEmail) {
            if (res.is_valid) {
              $sender.removeClass("has-error");
              validate();
            } else {
              $sender.addClass("has-error");
              $submit.attr("disabled", "disabled");
            }
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
          'sender': $sender.val(),
          'subject': $subject.val(),
          'message': $message.val(),
          'mailto': $mailto.val()
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
