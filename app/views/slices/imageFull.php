<div class="imageFull <?= $slice->getLabel() ?>">
<?php 
	$items = $slice->getValue()->getArray();
	$item = $items[0];
	$illustrationUrl = $item->get('illustration') ? $item->get('illustration')->getMain()->getUrl() : null;
?>	
  <img src="<?= $illustrationUrl ?>" />

</div>
