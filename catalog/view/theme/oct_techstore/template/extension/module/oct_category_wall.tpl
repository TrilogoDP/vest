<div class="wrap home-page__category fdc">
	<div class="header df aic jcc">
		<p class="title_module"><?php echo $heading_title; ?>
	</div>
	<div class="wrap_home-page__category">
		<?php foreach ($categories as $category) { ?>
				<div class="item">
					<a href="<?php echo $category['href']; ?>" class="oct-category-item-link" rel="noindex, follow"></a>
					<a href="<?php echo $category['href']; ?>" class="oct-category-item-header" rel="noindex, follow"><?php echo $category['name']; ?></a>
					<?php if ($category['thumb']) { ?>
						<img src="<?php echo $category['thumb']; ?>" class="lazy" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" />
					<?php } ?>
				</div>
		<?php } ?>
	</div>
</div>
