<?php $skin = the_skin(); ?>

<?php if($skin) : ?>

<?php include('fonts.php') ?>

<?php

  $faviconImage = $skin->getImage('skin.favicon-image');

  $sidebarTextColor = $skin->getColor('skin.sidebar-text-color');
  $sidebarBackgroundColor = $skin->getColor('skin.sidebar-background-color');

  $textColor = $skin->getColor('skin.blog-text-color');

  $backgroundColor = $skin->getColor('skin.blog-background-color');

  $footerBackgroundColor = $skin->getColor('skin.blog-footer-background-color');

  $footerTextColor = $skin->getColor('skin.blog-footer-text-color');

  $titleColor = $skin->getColor('skin.blog-title-color');

  $textFont = $skin->getText('skin.blog-text-font');

  $titleFont = $skin->getText('skin.blog-title-font');

  $monospaceFont = $skin->getText('skin.blog-monospace-font');

  $metaTextColor = $skin->getColor('skin.blog-meta-text-color');

  $headerTextColor = $skin->getColor('skin.blog-header-text-color');

  $imageLabelTextColor = $skin->getColor('skin.blog-imagelabel-text-color');

?>

<?php if ($faviconImage && $faviconImage->getMain()) : ?>

  <link rel="icon" type="image/png" href="<?=$faviconImage->getMain()->getUrl()?>">

<?php else: ?>

  <link rel="icon" type="image/png" href="/app/static/favicon.png">

<?php endif ?>

<style>

body {

  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;

  <?= $textFont ? 'font-family:'.$textFont : '' ?>;

  <?= $textColor ? 'color:'.$textColor->asText() : '' ?>;
}

#right-panel, #right-panel h3, #right-panel a, #right-panel .search, #right-panel .search input {
  <?= $sidebarTextColor ? 'color:'.$sidebarTextColor->asText() : '' ?>;
  <?= $sidebarBackgroundColor ? 'background-color:'.$sidebarBackgroundColor->asText() : '' ?>;
}

a {
  <?= $textColor ? 'color:'.$textColor->asText() : '' ?>;
}

h1, .h1,
h2, .h2,
h3, .h3,
h4, .h4,
h5, .h5,
h6, .h6 {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
  <?= $titleColor ? 'color:'.$titleColor->asText() : ''; ?>;
}

.previous-post, .next-post {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.blog-header {
  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
  <?= $backgroundColor ? 'background-color:'.$backgroundColor->asText() : ''; ?>;
}

.blog-header h1, .blog-header .date, .blog-header .shortlede, .blog-header .author {
  <?= $headerTextColor ? 'color:'.$headerTextColor->asText() : '' ?>;
}

.blog-footer.single {

  <?= $footerBackgroundColor ? 'background-color:'.$footerBackgroundColor->asText() : '' ?>;
 }

.blog-footer.single a .label {

  <?= $footerTextColor ? 'color:'.$footerTextColor->asText() : '' ?>;

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;
}

.blog-footer.single .menu::before {
  <?= $footerTextColor ? 'color:'.$footerTextColor->asText() : '' ?>;
}

.blog-description {

  <?= $titleFont ? 'font-family:'.$titleFont : ''; ?>;

  <?= $headerTextColor ? 'color:'.$headerTextColor->asText() : '' ?>;
}

.blog-post-title a, .blog-post-meta a {

  <?= $titleFont ? 'font-family:'.$titleFont : '' ?>;
}

.blog-post-meta a {

  <?= $metaTextColor ? 'color:'.$metaTextColor->asText() : '' ?>;
}

.blog-post-title a {

  <?= $titleColor ? 'color:'.$titleColor->asText() : '' ?>;
}

.blog-post-meta > *, .blog-post-meta .tags::before, .blog-post-meta .categories::before, .blog-post-meta .author::before {

  <?= $metaTextColor ? 'color:'.$metaTextColor->asText() : '' ?>;
}

.recent-posts.single .image-label {

  <?= $imageLabelTextColor ? 'color:'.$imageLabelTextColor->asText() : '' ?>;
}

.recent-posts.single .image-left + .image-label, .recent-posts.single .image-full-column + .image-label {

  <?= $imageLabelTextColor ? 'border-right-color:'.$imageLabelTextColor->asText() : '' ?>;
}

.recent-posts.single span.monospace {
  <?= $monospaceFont ? 'font-family:'.$monospaceFont : ''; ?>;
 }

.recent-posts.single .block-citation {
  <?= $imageLabelTextColor ? 'border-left-color:'.$imageLabelTextColor->asText() : '' ?>;
}

.contact-page form[name=contact-form] .form-group label {
  <?= $textFont ? 'font-family:'.$textFont : '' ?>;
}

.contact-page .contact-header .wrapper {
  <?= $headerTextColor ? 'color:'.$headerTextColor->asText() : ''; ?>;
}

.contact-page .contact-header .wrapper h1 {
  <?= $headerTextColor ? 'color:'.$headerTextColor->asText() : ''; ?>;
}

</style>

<?php endif ?>
