   <!-- Google+ data -->
<meta itemprop="name" content="<?= open_graph_title() ?>">
<meta itemprop="description" content="<?= open_graph_description() ?>">
<meta itemprop="image" content="<?= open_graph_image() ?>">

<!-- Open Graph data (facebook & linkedin)-->
<meta property="og:title" content="<?= open_graph_title() ?>">
<meta property="og:type" content="article">
<meta property="og:url" content="<?= page_url() ?>">
<meta property="og:image" content="<?= open_graph_image() ?>">
<meta property="og:description" content="<?= open_graph_description() ?>">
<meta property="article:section" content="Article Section">

<!-- Twitter Card data -->
<?php
if(twitter_card_exist()) {
	switch(twitter_card_type()){
		case 'twitter_summary' :
?>
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="<?= twitter_summary_site() ?>" />
		<meta name="twitter:creator" content="<?= twitter_summary_creator() ?>" />
		<meta name="twitter:title" content="<?= twitter_summary_title() ?>">
		<meta name="twitter:description" content="<?= twitter_summary_description() ?>">
		<meta name="twitter:image:src" content="<?= twitter_summary_image() ?>">
<?php 
		break;

		case 'twitter_summary_large' :
?>
		<meta name="twitter:card" content="summary_large" />
		<meta name="twitter:site" content="<?= twitter_summary_large_site() ?>" />
		<meta name="twitter:creator" content="<?= twitter_summary_large_creator() ?>" />
		<meta name="twitter:title" content="<?= twitter_summary_large_title() ?>">
		<meta name="twitter:description" content="<?= twitter_summary_large_description() ?>">
		<meta name="twitter:image:src" content="<?= twitter_summary_large_image() ?>">
<?php
		break;

		case 'twitter-app' :
?>
		<meta name="twitter:card" content="app" />
		<meta name="twitter:site" content="<?= twitter_app_site() ?>" />
		<meta name="twitter:creator" content="<?= twitter_app_creator() ?>" />
		<meta name="twitter:app:country" content="<?= twitter_app_country() ?>" />
		<meta name="twitter:app:name:iphone" content="<?= twitter_app_iphone_name() ?>">
		<meta name="twitter:app:id:iphone" content="<?= twitter_app_iphone_id() ?>">
		<meta name="twitter:app:url:iphone" content="<?= twitter_app_iphone_url() ?>">
		<meta name="twitter:app:name:ipad" content="<?= twitter_app_ipad_name() ?>">
		<meta name="twitter:app:id:ipad" content="<?= twitter_app_ipad_id() ?>">
		<meta name="twitter:app:url:ipad" content="<?= twitter_app_ipad_url() ?>">
		<meta name="twitter:app:name:googleplay" content="<?= twitter_app_android_name() ?>">
		<meta name="twitter:app:id:googleplay" content="<?= twitter_app_android_id() ?>">
		<meta name="twitter:app:url:googleplay" content="<?= twitter_app_android_url() ?>">
<?php
		break;

		default: return;
	}
}
?>