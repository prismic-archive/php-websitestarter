<?php get_header() ?>

<?php

  $headerImageUrl = single_cat_illustration_url() ? single_cat_illustration_url() : (the_blankimage() ? the_blankimage()->getUrl() : '');

?>

<div class="blog-header category" style="<?= $headerImageUrl ? 'background-image: url('.$headerImageUrl.')' : '' ?>">

    <div class="wrapper">

        <h1><?= single_cat_title() ?></h1>

        <?= category_description(); ?>

    </div>

</div>

<div class="blog-main category container">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php get_template_part('content'); ?>

<?php endwhile; else : ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

</div>

<?php get_footer() ?>
