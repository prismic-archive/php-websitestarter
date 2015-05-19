<div class="row-centered-separate featured-mini">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

  <div class="col-3">

    <?php $illustration = $item->get('illustration'); ?>

    <div class="illustration round-image" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

    <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

    <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

  </div>

  <?php } ?>

</div>
