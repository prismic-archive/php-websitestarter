<?php $skin = the_skin(); ?>

<?php if($skin) : ?>

<?php include('fonts.php') ?>

<?php

  $faviconImage = $skin->getImage('skin.favicon-image');

  $textColor = $skin->getColor('skin.errorpage-text-color');

  $titleColor = $skin->getColor('skin.errorpage-title-color');

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
