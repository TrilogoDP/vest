<?php if($microdata_twitter_status){ ?>
<meta name="twitter:card" content="summary" />
<meta name="twitter:creator" content="<?php echo $microdata_twitter_account; ?>" />
<meta name="twitter:title" content="<?php echo trim($microdata_heading_title); ?>" />
<meta name="twitter:description" content="<?php echo trim($microdata_description_shot); ?>" />
<meta name="twitter:image" content="<?php echo $microdata_popup_tw; ?>" />
<?php } ?>
<?php if($microdata_opengraph_status){ ?>
<meta property="og:type" content="<?php echo $microdata_og_type; ?>" />
<meta property="og:title" content="<?php echo trim($microdata_heading_title); ?>" />
<meta property="og:description" content="<?php echo trim($microdata_description_shot); ?>" />
<meta property="og:image" content="<?php echo $microdata_popup; ?>" />
<meta property="og:url" content="<?php echo $microdata_url; ?>" />
<?php } ?>