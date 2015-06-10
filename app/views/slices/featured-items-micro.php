<div class="row-centered-aired featured-micro">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

  <div class="col-4">

    <?php $illustration = $item->get('illustration'); ?>

    <div class="illustration squared-image" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

    <div class="text">

      <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

      <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

    </div>

  </div>

  <?php } ?>

</div>
