
<?php $theme = the_theme(); ?>

<?php if($theme) : ?>

<?php include('fonts.php') ?>

<?php

  $faviconImage = $theme->getImage('theme.favicon-image');

  $textColor = $theme->getColor('theme.page-text-color');

  $backgroundColor = $theme->getColor('theme.page-background-color');

  $titleColor = $theme->getColor('theme.page-title-color');

  $textFont = $theme->getText('theme.page-text-font');

  $titleFont = $theme->getText('theme.page-title-font');

  $themeColor = $theme->getColor('theme.page-main-color');

  $themeAlternateColor = $theme->getColor('theme.page-alternate-color');

  $slideTextColor = $theme->getColor('theme.page-slide-text-color');

  $faqSeparationColor = $theme->getColor('theme.page-faq-separation-color');

  $slideHeight = $theme->getNumber('theme.page-slide-height');

  $slideHomeButtonBackgroundFirstColor = $theme->getColor('theme.page-slide-home-button-background-first-color');

  $slideHomeButtonBackgroundSecondColor = $theme->getColor('theme.page-slide-home-button-background-second-color');
?>

<?php if ($faviconImage && $faviconImage->getMain()) : ?>

  <link rel="icon" type="image/png" href="<?=$faviconImage->getMain()->getUrl()?>">

<?php else: ?>

  <link rel="icon" type="image/png" href="/app/static/favicon.png">

<?php endif ?>

<style>

body {

  <?= $textFont ? 'font-family:'.$textFont : '' ?>;

  <?= $textColor ? 'color:'.$textColor->asText() : '' ?>;

  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;
}


h1, h2, h3 {

  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>
}

h2, h3 {

  <?= $titleColor ? 'color:'.$titleColor->asText() : '' ?>;
}

.button {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : '' ?>;
  <?= $titleColor ? 'border-color:'.$titleColor->asText() : ''; ?>;
}

.button:hover {
  <?= $titleColor ? 'background:'.$titleColor->asText() : ''; ?>;
  <?= $titleColor ? 'border-color:'.$titleColor->asText() : ''; ?>;
  <?= $backgroundColor ? 'color:'.$backgroundColor->asText() : ''; ?>;
}

.faq h3 {
  <?= $faqSeparationColor ? 'border-bottom-color:'.$faqSeparationColor->asText() : ''; ?>;
}

.slides .slide {
  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;
}

.button.home {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : ''; ?>;
  <?= $slideHomeButtonBackgroundFirstColor ? 'background-color:'.$slideHomeButtonBackgroundFirstColor->asText() : ''; ?>;
  <?= $slideHomeButtonBackgroundSecondColor ? 'box-shadow: 0px 3px '.$slideHomeButtonBackgroundSecondColor->asText() : ''; ?>;
}

.round-image {
  <?= $themeColor ? 'background-color:'.$themeColor->asText() : ''; ?>;
}

.featured-preview {
  <?= $themeAlternateColor ? 'background-color:'.$themeAlternateColor->asText() : ''; ?>;
}

.alternated-items > div {
  <?= $themeColor ? 'background: '.$themeColor->asText() : ''; ?>;
}

.alternated-items .alternate {
  <?= $themeAlternateColor ? 'background-color: '.$themeAlternateColor->asText() : ''; ?>;
}

.featured-items-simple .illustration {
  <?= $themeColor ? 'background-color: '.$themeColor->asText() : ''; ?>;
}

.featured-preview li:hover {
  <?= $themeColor ? 'background-color: '.$themeColor->asText() : ''; ?>;
}

.slides .slide h2 {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.slides, .slides h2, .slide-arrows a {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : '' ?>;
}

.slides .arrow-prev, .slides .arrow-next {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : '' ?>;
}

.slides .button {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : '' ?>;
}

.slides p {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.slides .button:not(.home):hover {
  <?= $slideTextColor ? 'background:'.$slideTextColor->asText() : ''; ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : '' ?>;
}

.contact-us form[name=contact-form] .form-group label {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.slides {
  <?= $slideHeight ? 'height:'.$slideHeight->asText().'vh' : ''; ?>;
}

</style>

<?php endif ?>
