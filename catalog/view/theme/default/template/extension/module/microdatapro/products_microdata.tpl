<?php $pri = 1; foreach($microdata_products as $product){ ?>
	<?php if($related_block){ ?>
		<span id="related-product-<?php echo $pri; ?>" itemprop="isRelatedTo" itemscope itemtype="http://schema.org/Product">
			<?php }else{ ?>
			<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<?php } ?>			
			<meta itemprop="position" content="<?php echo $pri; ?>" />		
			<span itemprop="item" itemscope itemtype="http://schema.org/Product">
				<meta itemprop="name" content="<?php echo $product['name']; ?>" />
				<meta itemprop="description" content="<?php echo $product['microdata_description']; ?>" />
				<link itemprop="url" href="<?php echo $product['href']; ?>" />
				<link itemprop="brand" href="<?php echo $product['manufacturer']; ?>" />
				<link itemprop="manufacturer" href="<?php echo $product['manufacturer']; ?>" />
				<link itemprop="image" href="<?php echo $product['thumb']; ?>" />
				<?php if ($product['sku']) { ?>
					<meta itemprop="sku" content="<?php echo $product['sku'] ?>" />
				<?php } ?>
				<?php if ($product['mpn']) { ?>
					<meta itemprop="mpn" content="<?php echo $product['mpn'] ?>" />	
				<?php } ?>
				<?php if ($product['ean']) { ?>
					<meta itemprop="ean" content="<?php echo $product['ean'] ?>" />
				<?php } ?>
				<?php if ($product['model']) { ?>
					<meta itemprop="model" content="<?php echo $product['model'] ?>" />
				<?php } ?>
				<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<meta itemprop="priceCurrency" content="<?php echo $microdata_code; ?>" />
					<meta itemprop="price" content="<?php echo $product['microdata_price']; ?>" />
					<link itemprop="url" href="<?php echo $product['href']; ?>" />
					<?php if(isset($product['microdata_stock'])){ ?>
						<meta itemprop="availability" content="http://schema.org/<?php echo ($product['microdata_stock'] > 0)?"InStock":"OutOfStock"; ?>" />
					<?php } ?>
					<meta itemprop="priceValidUntil" content="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" />
				</span>
			</span>
		</span>	
	<?php $pri++; } ?>				