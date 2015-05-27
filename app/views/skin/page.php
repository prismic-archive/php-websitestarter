
<?php $skin = the_skin(); ?>

<?php if($skin) : ?>

<?php include('fonts.php') ?>

<?php
  $faviconImage = $skin->getImage('skin.favicon-image');
  $sidebarTextColor = $skin->getColor('skin.sidebar-text-color');
  $sidebarBackgroundColor = $skin->getColor('skin.sidebar-background-color');

  $textColor = $skin->getColor('skin.page-text-color');
  $textFont = $skin->getText('skin.page-text-font');
  $backgroundColor = $skin->getColor('skin.page-background-color');
  $titleColor = $skin->getColor('skin.page-title-color');
  $titleFont = $skin->getText('skin.page-title-font');
  $skinColor = $skin->getColor('skin.page-main-color');
  $skinAlternateColor = $skin->getColor('skin.page-alternate-color');
  $faqSeparationColor = $skin->getColor('skin.page-faq-separation-color');

  $slideTextColor = $skin->getColor('skin.page-slide-text-color');
  $slideHeight = $skin->getNumber('skin.page-slide-height');
  $slideOverlayColor = $skin->getColor('skin.page-slide-overlay-color');
  $slideOverlayOpacity = $skin->getNumber('skin.page-slide-overlay-opacity');
  $slideHomeButtonBackgroundFirstColor = $skin->getColor('skin.page-slide-home-button-background-first-color');
  $slideHomeButtonBackgroundSecondColor = $skin->getColor('skin.page-slide-home-button-background-second-color');
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

#right-panel, #right-panel h3, #right-panel a, #right-panel .search, #right-panel .search input {
  <?= $sidebarTextColor ? 'color:'.$sidebarTextColor->asText() : '' ?>;
  <?= $sidebarBackgroundColor ? 'background-color:'.$sidebarBackgroundColor->asText() : '' ?>;
}

.main h1, .main h2, .main h3 {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>
}

.main h2, .main h3 {
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
  <?= $skinColor ? 'background-color:'.$skinColor->asText() : ''; ?>;
}

.featured-preview {
  <?= $skinAlternateColor ? 'background-color:'.$skinAlternateColor->asText() : ''; ?>;
}

.alternated-items > div {
  <?= $skinColor ? 'background: '.$skinColor->asText() : ''; ?>;
}

.alternated-items .alternate {
  <?= $skinAlternateColor ? 'background-color: '.$skinAlternateColor->asText() : ''; ?>;
}

.featured-items-simple .illustration {
  <?= $skinColor ? 'background-color: '.$skinColor->asText() : ''; ?>;
}

.featured-preview li:hover {
  <?= $skinColor ? 'background-color: '.$skinColor->asText() : ''; ?>;
}

.main .slides .main .slide h2 {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.main .slides, .main .slides h2, .main .slide-arrows a {
  <?= $slideTextColor ? 'color:'.$slideTextColor->asText() : '' ?>;
}

.main .slides .arrow-prev, .main .slides .arrow-next {
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

.slide::before {
  <?= $slideOverlayColor ? 'background-color:'.$slideOverlayColor->asText() : ''; ?>;
  <?= $slideOverlayOpacity ? 'opacity:'.$slideOverlayOpacity->asText() : ''; ?>;
}

</style>

<?php endif ?>
