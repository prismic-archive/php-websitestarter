<div class="slides <?= $slice->getLabel() ?>">

<?php if(count($slice->getValue()->getArray()) > 1): ?>
    <a href="#" class="arrow-prev">&nbsp;</a>
<?php endif ?>

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php
      $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null;
      $blankImage = the_skin() && the_skin()->getImage('skin.blank-image') ? the_skin()->getImage('skin.blank-image')->getMain() : null;
      $illustrationUrl = $illustration ? $illustration->getUrl() : ($blankImage ? $blankImage->getUrl() : '');
      $readMore = $item->get('read-more');
      $readMoreLabel = $item->get('read-more-label');
      $optionalLink = $item->get('optional-link');
      $optionalLinkLabel = $item->get('optional-link-label');

    ?>

    <div data-illustration="<?= $illustrationUrl ? $illustrationUrl : '' ?>" class="slide <?= $index == 0 ? 'active' : '' ?>" style="<?= $illustrationUrl ? 'background-image: url('.$illustrationUrl.')' : '' ?>">

        <div class="slide-container">

            <?= $item->get('title') ? $item->get('title')->asHtml() : ''; ?>

            <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

            <?php if ($readMoreLabel): ?>

            <?php $url = $readMore ? $linkResolver->resolve($readMore) : null ?>

            <a class="button" <?= $url ? 'href="'.$url.'"' : '' ?>>

                <?= $readMoreLabel->asText() ?>

            </a>

            <?php endif ?>

              <?php if ($optionalLinkLabel): ?>

            <a class="inline-link" href="<?= $optionalLink->asText() ?>">
            <?= $optionalLinkLabel->asHtml() ?>
            </a>

            <?php endif ?>

        </div>

    </div>

    <?php $index++; ?>

<?php } ?>

<?php if(count($slice->getValue()->getArray()) > 1): ?>
    <a href="#" class="arrow-next">&nbsp;</a>
<?php endif ?>

</div>
