<div class="row-centered-separate container blog-main recent-posts <?= $slice->getLabel() ?>">

<?php $arr = $slice->getValue()->getArray(); $params = $arr[0]; ?>

<?php recent_posts($params->getNumber('max')); ?>
<?php full_articles($params->getBoolean('full-articles')); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php get_template_part('content'); ?>

<?php endwhile; else : ?>

    <p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

</div>
