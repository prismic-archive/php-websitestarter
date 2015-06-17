<div class="row-centered-aired featured-items-simple">

    <ul class="flex-container">

    <?php foreach($slice->getValue()->getArray() as $item) { ?>

      <?php $illustration = $item->get('illustration'); ?>

      <li class="col-2" data-illustration="<?= $illustration ? $illustration->getMain()->getUrl() : ''; ?>">

      <div class="illustration squared-image" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

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

      </li>

    <?php } ?>

    </ul>

</div>
