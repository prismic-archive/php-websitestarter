
<?php $theme = the_theme(); ?>

<?php if($theme) : ?>

<?php foreach($theme->getGroup('theme.fonts')->getArray() as $item) { ?>

  <link href='<?= $item->getLink("font")->getUrl() ?>' rel='stylesheet' type='text/css'>

<?php } ?>

<?php else: ?>

<link href='//fonts.googleapis.com/css?family=Lato:300,400,700|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>

<?php endif ?>
