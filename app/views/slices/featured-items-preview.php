<div class="row-centered-separate featured-preview">

  <div class="col-2 list-pane">

    <ul>

    <?php foreach($slice->getValue()->getArray() as $item) { ?>

      <?php $illustration = $item->get('illustration'); ?>

      <li data-illustration="<?= $illustration ? $illustration->getMain()->getUrl() : '' ?>">

      <div class="illustration" <?= $illustration ? 'style="background-image: url('.$illustration->getView("icon")->getUrl().')"' : '' ?>></div>

      <h3><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></h3>

      <?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

    </li>

    <?php } ?>

    </ul>

  </div>

  <div class="col-2 preview-pane">

  </div>

</div>
