<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/assets/reset.css">
    <link rel="stylesheet" href="/assets/common.css">
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="stylesheet" href="/assets/blog.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/assets/vendor/jquery-1.11.2.min.js"></script>

    <!-- disqus integration -->
    <?php if(disqus_forum()): ?>
    <script type="application/javascript">
      $(document).ready(function() {
          window.disqium = Disqium('.blog-main.single');
      });
    </script>
    <?php endif ?>

    <link rel="stylesheet" href="/assets/vendor/disqium/disqium.css" />
    <script src="/assets/vendor/disqium/disqium.js"></script>

    <?php include('prismic.php') ?>

    <?php include('skin/blog.php') ?>

</head>

<body>

    <div id="right-panel">

        <?php get_sidebar(); ?>

    </div>

    <div class="main" <?= the_wio_attributes(); ?>>

        <a id="menu-hamburger" href="#right-panel"></a>

<?php full_articles(true) ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php

  $headerImageUrl = post_thumbnail_url() ? post_thumbnail_url() : (the_blankimage() ? the_blankimage()->getUrl() : '');

?>

<div class="blog-header single" style="<?= $headerImageUrl ? 'background-image: url('.$headerImageUrl.')' : '' ?>">

    <div class="wrapper">

       <h1><?= single_post_title(); ?></h1>

       <p class="description">
         <?php single_post_shortlede(); ?>
       </p>

       <p class="meta">
         <?php single_post_date(); ?><?php single_post_author(); ?>
       </p>

    </div>
</div>

<div class="blog-main single container">

    <?php the_content(); ?>

</div>

<?php endwhile; ?>

<footer class="blog-footer single">

    <?php if (previous_post_link_url()) : ?>

      <a href="<?=previous_post_link_url()?>" class="previous">

        <span class="label">Previous article</span>

        <p class="title"><?=previous_post_link_title()?></p>

      </a>

    <?php endif ?>

    <a class="menu" href="/blog">Home</a>

    <?php if (next_post_link_url()) : ?>

      <a href="<?=next_post_link_url()?>" class="next">

        <span class="label">Next article</span>

        <p class="title"><?=next_post_link_title()?></p>

      </a>

    <?php endif ?>

</footer>

<!-- Hamburger menu -->
<script src="/assets/vendor/jquery.panelslider.js"></script>

<script type="text/javascript">

  $(document).ready(function() {
    $('#menu-hamburger').panelslider({side: 'right', duration: 200 });
  });

</script>

<!-- Handle footer -->
<script src="/assets/blog.js"></script>

</body>

</html>
