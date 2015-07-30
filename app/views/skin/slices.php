  <?php $skin = the_skin(); ?>

<?php if($skin) : ?>

<?php include('fonts.php') ?>

<?php
  
  $videoBannerHeight = $skin->getNumber('skin.video_banner_height');
  $videoBannerDarkBackground = $skin->getColor('skin.video_banner_dark_background'); 
  $videoBannerDarkText = $skin->getColor('skin.video_banner_dark_text');
  $videoBannerDarkOverlay = $skin->getColor('skin.video_banner_dark_overlay');
  $videoBannerOverlayOpacity= $skin->getColor('skin.video_banner_overlay_opacity');
  $videoBannerButtonText = $skin->getColor('skin.video_banner_button_text');
  $videoBannerButtonBorder = $skin->getColor('skin.video_banner_button_border');

  $videoBannerLightBackground = $skin->getColor('skin.video_banner_light_background'); 
  $videoBannerLightText = $skin->getColor('skin.video_banner_light_text');
  $videoBannerLightOverlay = $skin->getColor('skin.video_banner_light_overlay');

  $mediumVideoBannerHeight = $skin->getNumber('skin.medium_video_banner_height');

  $smallVideoBannerHeight = $skin->getNumber('skin.small_video_banner_height');




?>

<style>
.video-banner, .video-banner .video, .video-banner video {
    <?php if($videoBannerHeight) { ?>
      height: <?= $videoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }
  
  .video-banner.video-banner-dark .video {
    <?php if($videoBannerDarkBackground) { ?>
      background-color: <?= $videoBannerDarkBackground->asText()?>;
    <?php } ?>
  }  

  .video-banner.video-banner-dark .video * {
    <?php if($videoBannerDarkText) { ?>
      color: <?= $videoBannerDarkText->asText()?>;
    <?php } ?>
  }

  .video-banner.video-banner-dark .video::before {
    <?php if($videoBannerDarkOverlay) { ?>
      background-color: <?= $videoBannerDarkOverlay->asText()?>;
    <?php } ?>
     
     <?php if($videoBannerOverlayOpacity) { ?>
      opacity: <?= $videoBannerOverlayOpacity->getValue() ?>;
    <?php } ?>
  }

  .video-banner.video-banner-light .video {
    <?php if($videoBannerLightBackground) { ?>
      background-color: <?= $videoBannerLightBackground->asText()?>;
    <?php } ?>
  }
  .video-banner.video-banner-light .video * {
    <?php if($videoBannerLightText) { ?>
      color: <?= $videoBannerLightText->asText()?>;
    <?php } ?>
  }
  .video-banner.video-banner-light .video::before {
    <?php if($videoBannerLightOverlay) { ?>
      background-color: <?= $videoBannerLightOverlay->asText()?>;
    <?php } ?>
     
     <?php if($videoBannerOverlayOpacity) { ?>
      opacity: <?= $videoBannerOverlayOpacity->getValue() ?>;
    <?php } ?>
  }

  .video-banner.medium{
    <?php if($mediumVideoBannerHeight) { ?>
      height: <?= $mediumVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }
  .video-banner.medium .video{
        <?php if($mediumVideoBannerHeight) { ?>
      height: <?= $mediumVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }
  .video-banner.medium .video video {
    <?php if($mediumVideoBannerHeight) { ?>
      height: <?= $mediumVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }

  .video-banner.small {
        <?php if($smallVideoBannerHeight) { ?>
      height: <?= $smallVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }
  .video-banner.small .video{
            <?php if($smallVideoBannerHeight) { ?>
      height: <?= $smallVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }
  .video-banner.small .video video {
            <?php if($smallVideoBannerHeight) { ?>
      height: <?= $smallVideoBannerHeight->getValue() ?>vh;
    <?php } ?>
  }



  .video-banner.video-banner-dark .video .play {
    <?php if($videoBannerButtonText) { ?>
      color: <?= $videoBannerButtonText->asText()?>;
    <?php } ?>
  }
  .video-banner .video .video-container .play::after {
     <?php if($videoBannerButtonBorder) { ?>
      border-color: <?= $videoBannerButtonBorder->asText()?>;
    <?php } ?>
  }

  </style>


<?php endif ?>