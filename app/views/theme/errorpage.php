<?php $theme = the_theme(); ?>

<?php if($theme) : ?>

<?php include('fonts.php') ?>

<?php

  $faviconImage = $theme->getImage('theme.favicon-image');

  $textColor = $theme->getColor('theme.errorpage-text-color');

  $titleColor = $theme->getColor('theme.errorpage-title-color');

?>

<?php if ($faviconImage && $faviconImage->getMain()) : ?>

  <link rel="icon" type="image/png" href="<?=$faviconImage->getMain()->getUrl()?>">

<?php else: ?>

  <link rel="icon" type="image/png" href="/app/static/favicon.png">

<?php endif ?>

<style>

.error-page h1 {

  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.error-page p {

  <?= $textColor ? 'color:'.$textColor->asText() : ''; ?>;
}

</style>

<?php endif ?>
