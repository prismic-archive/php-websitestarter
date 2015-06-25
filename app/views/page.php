<!DOCTYPE html>
<!--[if lte IE 9 ]> <html class="ie"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class=""> <!--<![endif]-->
<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="alternate" type="application/rss+xml" title="<?= site_title() ?>'s Feed" href="/feed" />
    <link rel="stylesheet" href="/assets/reset.css">
    <link rel="stylesheet" href="/assets/common.css">
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="stylesheet" href="/assets/page.css">
    <link rel="stylesheet" href="/assets/font.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/assets/vendor/jquery-1.11.2.min.js"></script>
    <script src="/assets/slices.js"></script>

    <?php include('prismic.php') ?>

    <?php include('skin/page.php') ?>

</head>

<body class="page <?= is_home() ? "home" : "" ?>">

    <div id="right-panel">

        <?php get_sidebar() ?>

    </div>

    <div class="main" <?= the_wio_attributes(); ?>>

        <a id="menu-hamburger" href="#right-panel"></a>

        <?php while (have_posts()) : the_post(); ?>

        <div id="page-content">

            <?php page_content() ?>

        </div>


<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>
