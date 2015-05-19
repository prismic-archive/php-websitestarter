<div class="row-centered-separate featured-items">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration'); ?>

    <div class="col-3 center">

      <div class="illustration round-image" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

      <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

      <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

      <?php $readMore = $item->get('read-more'); ?>

      <?php $readMoreLabel = $item->get('read-more-label'); ?>

      <?php if ($readMore): ?>

      <?php $url = $linkResolver->resolve($readMore); ?>

      <a class="button" href="<?= $url ?>">

          <?= $readMoreLabel ? $readMoreLabel->asText() : 'learn more' ?>

      </a>

      <?php endif ?>

    </div>

  <?php } ?>

</div>
