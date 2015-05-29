<?php get_header() ?>

<div class="blog-header tag" style="<?= the_blankimage() ? 'background-image: url('.the_blankimage()->getUrl().')' : '' ?>">

</div>

<div class="blog-main container">

<span class="value tag"><?= single_tag_title() ?></span>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php get_template_part('content'); ?>

<?php endwhile; else : ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

<?php previous_posts_link() ?>

<?php next_posts_link() ?>

</div>

<?php get_footer() ?>
