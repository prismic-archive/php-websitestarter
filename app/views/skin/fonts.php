
<?php $skin = the_skin(); ?>

<?php if($skin && $skin->getGroup('skin.fonts')) : ?>

<?php foreach($skin->getGroup('skin.fonts')->getArray() as $item) { ?>

  <link href='<?= $item->getLink("font")->getUrl() ?>' rel='stylesheet' type='text/css'>

<?php } ?>

<?php else: ?>

<link href='//fonts.googleapis.com/css?family=Lato:300,400,700|PT+Serif:400,400italic' rel='stylesheet' type='text/css'>

<?php endif ?>
