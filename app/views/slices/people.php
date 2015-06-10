<div class="row-centered-aired people">

  <?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php $illustration = $item->get('illustration'); ?>

    <div class="col-4 center">

      <div class="image illustration" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

      <div class="text">

        <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

        <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

      </div>

    </div>

  <?php } ?>

</div>
