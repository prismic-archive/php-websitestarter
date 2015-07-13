<?php get_header() ?>

<?php

  $headerImageUrl = blog_home_image_url() ? blog_home_image_url() : (the_blankimage() ? the_blankimage()->getUrl() : '');

?>

<div class="blog-header home" style="<?= $headerImageUrl ? 'background-image: url('.$headerImageUrl.')' : '' ?>">

  <div class="wrapper">

    <h1 class="blog-title"><?= blog_home_title() ?></h1>

    <p class="lead blog-description"><?= blog_home_description() ?></p>

  </div>

</div>

<div class="container blog-main">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php get_template_part('content'); ?>

<?php endwhile; else : ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

</div>

<footer class="blog-footer single">

    <?php if (get_previous_posts_link_url()) : ?>

      <a href="<?=get_previous_posts_link_url()?>" class="previous">

        <span class="label">previous</span>

        <p class="title">Page <?= current_page_number() - 1 ?></p>

      </a>

    <?php endif ?>

    <a class="menu" href="/blog">Home</a>

    <?php if (get_next_posts_link_url()) : ?>

      <a href="<?=get_next_posts_link_url()?>" class="next">

        <span class="label">next</span>

        <p class="title">Page <?= current_page_number() + 1 ?></p>

      </a>

    <?php endif ?>

</footer>


<!-- Handle footer -->
<script src="/assets/blog.js"></script>

<?php get_footer() ?>
