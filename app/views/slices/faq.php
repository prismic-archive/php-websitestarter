<div class="row-centered-separate faq">

  <?php $opentag = false; ?>

  <?php $items = $slice->getValue()->getArray(); ?>

  <?php foreach($items as $item) { ?>

    <div class="col-2">

      <h3><?= $item->get('question') ? $item->get('question')->asText() : 'Empty'; ?></h3>

      <?= $item->get('answer') ? $item->get('answer')->asHtml() : ''; ?>

    </div>

  <?php } ?>

</div>
