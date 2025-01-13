<?php if($microdata_breadcrumbs){ ?>
<?php if($config_item_breadcrumb){ ?>
<?php if($config_item_syntax == "ld" or $config_item_syntax == "all"){ ?>
<script type="application/ld+json">
{
"@context": "http://schema.org",
"@type": "BreadcrumbList",
"itemListElement": [<?php $bi = 1; foreach ($microdata_breadcrumbs as $breadcrumb) { ?>{
"@type": "ListItem",
"position": <?php echo $bi; ?>,
"item": {
"@id": "<?php echo $breadcrumb['href']; ?>",
"name": "<?php echo $breadcrumb['text']; ?>"
}
}<?php if($bi != count($microdata_breadcrumbs)){ ?>,<?php } ?><?php $bi++; } ?>]
}	  
</script>
<?php } ?>
<?php if($config_item_syntax == "md" or $config_item_syntax == "all"){ ?>
<span itemscope itemtype="http://schema.org/BreadcrumbList">
<?php $bi = 1; foreach ($microdata_breadcrumbs as $breadcrumb) { ?>
<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
<link itemprop="item" href="<?php echo $breadcrumb['href']; ?>">
<meta itemprop="name" content="<?php echo $breadcrumb['text']; ?>" />
<meta itemprop="position" content="<?php echo $bi; ?>" />
</span>
<?php $bi++; } ?>
</span>			
<?php } ?>
<?php } ?>
<?php } ?>