   <!-- Google+ data -->
<meta itemprop="name" content="<?= page_social_cards_title() ?>">
<meta itemprop="description" content="<?= page_social_cards_description() ?>">
<meta itemprop="image" content="<?= page_social_cards_image() ?>">

<!-- Twitter Card data -->
<meta name="twitter:card" content="<?= page_social_twitter_card() ?>" />
<meta name="twitter:site" content="<?= page_social_twitter_site() ?>" />
<meta name="twitter:creator" content="<?= page_social_twitter_creator() ?>" />
<meta name="twitter:title" content="<?= page_social_cards_title() ?>">
<meta name="twitter:description" content="<?= page_social_cards_description() ?>">
<meta name="twitter:image:src" content="<?= page_social_cards_image() ?>">

<?php
if(page_social_twitter_images_gallery()) {
	$imageUrls = page_social_twitter_images_gallery();
	for($i = 0; $i < count($imageUrls);  $i++) {
?>
		<meta name="twitter:image<?= $i ?>" content="<?= $imageUrls[$i] ?>">
<?php
	} 
}
?>

<!-- Open Graph data (facebook & linkedin)-->
<meta property="og:title" content="<?= page_social_cards_title() ?>">
<meta property="og:type" content="article">
<meta property="og:url" content="<?= page_url() ?>">
<meta property="og:image" content="<?= page_social_cards_image() ?>">
<meta property="og:description" content="<?= page_social_cards_description() ?>">
<meta property="article:section" content="Article Section">