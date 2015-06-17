<div class="alternated-items">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

<?php $odd = ($index % 2 != 0) ?>

<?php $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null ?>

<div class="<?= $odd ? "alternate" : ""; ?>">

<div class="row-centered alternated-highlights flex-container">

  <?php if(!$odd): ?>

    <div class="col-2">

        <div class="illustration squared-image" <?= $illustration ? 'style="background-image: url('.$illustration->getUrl().')"' : '' ?>></div>

    </div>

  <?php endif ?>

  <div class="col-2">

     <div class="text-wrapper">

       <h2><?= $item->get('title') ? $item->get('title')->asText() : 'No title'; ?></h2>

       <?php if($item->get('summary')): ?>

         <?= $item->get('summary')->asHtml(); ?>

       <?php endif ?>

       <?php $readMore = $item->get('read-more'); ?>

       <?php $readMoreLabel = $item->get('read-more-label'); ?>

       <?php if ($readMoreLabel): ?>

       <?php $url = $readMore ? $linkResolver->resolve($readMore) : null ?>

       <a class="button" <?= $url ? 'href="'.$url.'"' : '' ?>>

           <?= $readMoreLabel->asText() ?>

       </a>

       <?php endif ?>

     </div>

  </div>

  <?php if($odd): ?>

    <div class="col-2">

      <div class="illustration squared-image" <?= $illustration ? 'style="background-image: url('.$illustration->getUrl().')"' : '' ?>></div>

    </div>

  <?php endif ?>

</div>

</div>

<?php $index++; ?>

<?php } ?>

</div>
