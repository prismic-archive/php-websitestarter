<div class="video-banner  <?= $slice->getLabel() ?>">
	
<?php foreach($slice->getValue()->getArray() as $item) { ?>

    <?php
      $illustration = $item->get('illustration') ? $item->get('illustration')->getMain() : null;
      $illustrationUrl = $illustration ? $illustration->getUrl() : ($blankImage ? $blankImage->getUrl() : '');
      $videoLink = $item->get('video_link');
      $autoplay = $item->get('video_autoplay');
      $loop = $item->get('video_loop');
      $readMore = $item->get('read-more');
      $readMoreLabel = $item->get('read-more-label');

    ?>
			<div class="video">
				<video width="100%" <?= $autoplay && $autoplay->asText() == "Enabled" ? 'autoplay' : '' ?> <?= $loop && $loop->asText() == "Enabled" ? 'loop=1' : '' ?> preload="none" poster="<?= $illustrationUrl ?>" onclick="this.play();">  
					<source src="<?= $videoLink ? $linkResolver->resolve($videoLink) : '' ?>" width="auto" type="video/mp4" /> 
					Your browser does not support html5 video. 
				</video>
				
				<div class="video-container">

				<span class="play"></span>
					
					<div class="title"><?= $item->get('title') ? $item->get('title')->asText() : ''; ?></div>

					<?= $item->get('summary') ? $item->get('summary')->asHtml() : ''; ?>

					<?php if ($readMoreLabel): ?>
						<?php $url = $readMore ? $linkResolver->resolve($readMore) : null ?>
						<a class="button" <?= $url ? 'href="'.$url.'"' : '' ?>>
							<?= $readMoreLabel->asText() ?>
						</a>

					<?php endif ?>
				</div>
			</div>
<?php } ?>
</div>