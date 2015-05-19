<?php get_header() ?>

<div class="blog-header author" style="<?= the_blankimage() ? 'background-image: url('.the_blankimage()->getUrl().')' : '' ?>">

    <div class="wrapper">

        <div class="author-image" style="background-image: url(<?= single_author_image_url() ?>)"></div>

        <h1 class="blog-title author-name"><?= single_author_name() ?></h1>

        <div class="author-bio">

            <?= single_author_bio() ?>

        </div>

        <div class="author-sites">

            <?= single_author_links() ?>

        </div>

    </div>

</div>

<div class="blog-main container author">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php get_template_part('content'); ?>
<?php endwhile; else : ?>
<?php endif; ?>
</div>

<?php get_footer() ?>
