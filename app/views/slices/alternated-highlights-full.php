<div class="row-separate alternated-items">

<?php $index = 0; ?>

<?php foreach($slice->getValue()->getArray() as $item) { ?>

<?php $odd = ($index % 2 != 0) ?>

<div class="<?= $odd ? "alternate" : ""; ?>">

<div class="row alternated-highlights-full">

  <?php $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null; ?>

  <?php if(!$odd): ?>

    <div class="col-illustration">

      <div class="illustration" <?= $illustration ? 'style="background-image: url('.$illustration->getUrl().')"' : '' ?>></div>

    </div>

  <?php endif ?>

  <div class="col-text">

     <div class="text-wrapper">

       <h2><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h2>

       <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

     </div>
  </div>

  <?php if($odd): ?>

    <div class="col-illustration">

      <div class="illustration" <?= $illustration ? 'style="background-image: url('.$illustration->getUrl().')"' : '' ?>></div>

    </div>

  <?php endif ?>

</div>

</div>

<?php $index++; ?>

<?php } ?>

</div>
