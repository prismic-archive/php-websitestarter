
<div class="blog-post" <?= the_wio_attributes() ?>>

    <h2 class="blog-post-title">

        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

    </h2>

    <p class="blog-post-meta">

        <?= the_date_link() ?>

        <?= the_author_link() ?>

        <?php the_category(', ') ?>

        <?php the_tags('', ', ') ?>

    </p>

    <?php the_excerpt() ?>

</div>
