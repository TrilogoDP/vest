<?php $pi = 1; foreach($microdata_products as $product){ ?>{
	"@type": "ListItem",
	"position": "<?php echo $pi; ?>",
	"url": "<?php echo $product['href']; ?>"
}<?php if($pi != count($microdata_products)){ ?>,<?php } ?><?php $pi++; } ?> 