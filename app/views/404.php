<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= single_post_title() ?> <?= site_title() ?></title>
    <link rel="stylesheet" href="/assets/common.css">
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="stylesheet" href="/assets/errorpage.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/assets/vendor/jquery-1.11.2.min.js"></script>

    <?php include('prismic.php') ?>

    <?php include('theme/errorpage.php') ?>

</head>

<body class="error-page">

    <div id="right-panel">
        <?php get_sidebar() ?>
    </div>

    <div id="main" <?= the_wio_attributes(); ?>>

        <a id="menu-hamburger" href="#right-panel"></a>

        <div class="container">

        <div class="illustration" style="<?= notfound_image_url() ? 'background-image: url('.notfound_image_url().')' : ''?>"></div>

        <h1><?= notfound_title() ?></h1>

        <p><?= notfound_description() ?></p>

        </div>

<?php get_footer() ?>
