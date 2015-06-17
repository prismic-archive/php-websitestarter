<div class="row-centered-aired featured-mini flex-container">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

  <div class="col-3">

    <?php $illustration = $item->get('illustration'); ?>

    <div class="illustration round-image" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

    <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

    <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

    <?php $readMore = $item->get('read-more'); ?>

    <?php $readMoreLabel = $item->get('read-more-label'); ?>

    <?php if ($readMoreLabel): ?>

    <?php $url = $readMore ? $linkResolver->resolve($readMore) : null ?>

    <a class="button" <?= $url ? 'href="'.$url.'"' : '' ?>>

        <?= $readMoreLabel->asText() ?>

    </a>

    <?php endif ?>

  </div>

  <?php } ?>

</div>
