<div class="row-centered-separate featured-items-simple">

    <ul>

    <?php foreach($slice->getValue()->getArray() as $item) { ?>

      <?php $illustration = $item->get('illustration'); ?>

      <li class="col-2" data-illustration="<?= $illustration->getMain()->getUrl(); ?>">

      <div class="illustration" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

      <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

      <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

    </li>

    <?php } ?>

    </ul>

</div>
