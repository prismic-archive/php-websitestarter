<div class="slides row-separate <?= $slice->getLabel() ?>">

<?php if(count($slice->getValue()->getArray()) > 1): ?>

    <a href="#" class="arrow-prev">&nbsp;</a>

<?php endif ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php

      $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null;

      $blankImage = the_theme()->getImage('theme.blank-image') ? the_theme()->getImage('theme.blank-image')->getMain() : null;

      $illustrationUrl = $illustration ? $illustration->getUrl() : ($blankImage ? $blankImage->getUrl() : '');

      $readMore = $item->get('read-more');

      $readMoreLabel = $item->get('read-more-label');
    ?>

    <div class="slide" style="<?= $illustrationUrl ? 'background-image: url('.$illustrationUrl.')' : '' ?>">

        <div class="slide-container">

            <?= $item->get('title') ? $item->get('title')->asHtml() : ''; ?>

            <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

            <?php if ($readMore && $readMoreLabel): ?>

            <?php $url = $linkResolver->resolve($readMore); ?>

            <a class="button" href="<?= $url ?>">

              <?= $readMoreLabel->asText() ?>

            </a>

            <?php endif ?>

        </div>

    </div>

<?php } ?>

<?php if(count($slice->getValue()->getArray()) > 1): ?>

    <a href="#" class="arrow-next">&nbsp;</a>

<?php endif ?>

</div>
