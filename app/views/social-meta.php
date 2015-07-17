<meta name="p:domain_verify" content="48ae95a94ba8fd30f31a003069eeff53"/>
<?php if(isShareReady()) { ?>
   <?php if(open_graph_card_exists()) {
      switch(open_graph_card_type()) { 
         case 'general_card' :
         ?>
         <!-- Open Graph data (facebook & linkedin)-->
         <meta property="og:title" content="<?= general_card_title() ?>">
         <meta property="og:type" content="article">
         <meta property="og:url" content="<?= page_url() ?>">
         <meta property="og:image" content="<?= general_card_image() ?>">
         <meta property="og:description" content="<?= general_card_description() ?>">
         <meta property="article:section" content="Article Section">

         <?php
         break;

         case 'product_card' : 
         ?>

         <meta property="og:title" content="<?=  product_card_title() ?>" />
         <meta property="og:description" content="<?= product_card_description() ?>" />
         <meta property="og:image" content="<?= product_card_single_image() ?>" />
         <meta property="og:images" content="<?= product_card_images() ?>" />
         <meta property="og:type" content="product" />
         <meta property="og:price:amount" content="<?= product_card_amount() ?>" />
         <meta property="og:price:currency" content="<?= product_card_currency() ?>" />

         <?php
         break;

         case 'place_card' :
         ?>

         <meta property="og:title" content="<?= place_card_title() ?>" />
         <meta property="og:type" content="place" />
         <meta property="og:description" content="<?= place_card_description() ?>" />
         <meta property="place:location:latitude" content="<?= place_card_latitude() ?>" />
         <meta property="place:location:longitude" content="<?= place_card_longitude() ?>" />

         <?php
         break;

         default: return;
      }
   }
   ?>

   <?php
   if(twitter_card_exists()) {
     switch(twitter_card_type()){
       case 'twitter_summary' :
       ?>
   <!-- Twitter Card data -->
       <meta name="twitter:card" content="summary" />
       <meta name="twitter:site" content="<?= twitter_summary_site() ?>" />
       <meta name="twitter:title" content="<?= twitter_summary_title() ?>">
       <meta name="twitter:description" content="<?= twitter_summary_description() ?>">
       <meta name="twitter:image:src" content="<?= twitter_summary_image() ?>">
       <?php 
       break;

       case 'twitter_summary_large' :
       ?>
       <meta name="twitter:card" content="summary_large_image" />
       <meta name="twitter:site" content="<?= twitter_summary_large_site() ?>" />
       <meta name="twitter:creator" content="<?= twitter_summary_large_creator() ?>" />
       <meta name="twitter:title" content="<?= twitter_summary_large_title() ?>">
       <meta name="twitter:description" content="<?= twitter_summary_large_description() ?>">
       <meta name="twitter:image:src" content="<?= twitter_summary_large_image() ?>">
       <?php
       break;

       case 'twitter_app' :
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
} else {
   ?>
   <meta property="og:title" content="<?= default_title() ?>">
   <meta property="og:type" content="article">
   <meta property="og:url" content="<?= page_url() ?>">
   <meta property="og:image" content="<?= default_image() ?>">
   <meta property="og:description" content="<?= default_description() ?>">
   <?php
}
?>