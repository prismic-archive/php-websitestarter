<div class="row-centered-aired faq <?= $slice->getLabel() ? "flex-container" : "" ?>">

  <?php $opentag = false; ?>

  <?php $items = $slice->getValue()->getArray(); ?>

  <?php foreach($items as $item) { ?>

    <div class="<?= $slice->getLabel() ?>">

      <h3><?= $item->get('question') ? $item->get('question')->asText() : 'Empty'; ?></h3>

      <?= $item->get('answer') ? $item->get('answer')->asHtml() : ''; ?>

    </div>

  <?php } ?>

</div>
