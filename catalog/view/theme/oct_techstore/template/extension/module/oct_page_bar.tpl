<div id="oct-slide-panel">
	<div class="oct-slide-panel-heading">
		<div id="hide-slide-panel"><i class="fa fa-times" aria-hidden="true"></i></div>
		<div class="container">
			<?php if ($oct_page_bar_data['show_viewed_bar']) { ?>
			<div id="oct-last-seen-link" class="col-sm-3 col-xs-3">
				<a href="javascript:void(0);" class="oct-panel-link">
					<i class="fa fa-eye" aria-hidden="true"></i>
					<span class="hidden-xs hidden-sm"><?php echo $text_viewed; ?></span>
					<span id="oct-last-seen-quantity" class="oct-slide-panel-quantity"><?php echo $total_viewed; ?></span>
				</a>
			</div>
			<?php } ?>
			<?php if ($oct_page_bar_data['show_wishlist_bar']) { ?>
			<div id="oct-favorite-link" class="col-sm-3 col-xs-3">
				<a href="javascript:void(0);" class="oct-panel-link">
					<i class="fa fa-heart-o" aria-hidden="true"></i>
					<span class="hidden-xs hidden-sm"><?php echo $text_wishlist; ?></span>
					<span id="oct-favorite-quantity" class="oct-slide-panel-quantity"><?php echo $total_wishlist; ?></span>
				</a>
			</div>
			<?php } ?>
			<?php if ($oct_page_bar_data['show_compare_bar']) { ?>
			<div id="oct-compare-link" class="col-sm-3 col-xs-3">
				<a href="javascript:void(0);" class="oct-panel-link">
					<i class="fa fa-sliders" aria-hidden="true"></i>
					<span class="hidden-xs hidden-sm"><?php echo $text_compare; ?></span>
					<span id="oct-compare-quantity" class="oct-slide-panel-quantity"><?php echo $total_compare; ?></span>
				</a>
			</div>
			<?php } ?>
			<?php if ($oct_page_bar_data['show_cart_bar']) { ?>
			<div id="oct-bottom-cart-link" class="col-sm-3 col-xs-3">
				<a href="javascript:void(0);" class="oct-panel-link">
					<i class="fa fa-shopping-basket" aria-hidden="true"></i>
					<span class="hidden-xs hidden-sm"><?php echo $text_cart; ?></span>
					<span id="oct-bottom-cart-quantity" class="oct-slide-panel-quantity"><?php echo $total_cart; ?></span>
				</a>
			</div>
			<?php } ?>
		</div>	
	</div>
	<div class="oct-slide-panel-content">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?php if ($oct_page_bar_data['show_viewed_bar']) { ?>
					<div id="oct-last-seen-content" class="oct-slide-panel-item-content"></div>
					<?php } ?>
					<?php if ($oct_page_bar_data['show_wishlist_bar']) { ?>
					<div id="oct-favorite-content" class="oct-slide-panel-item-content"></div>
					<?php } ?>
					<?php if ($oct_page_bar_data['show_compare_bar']) { ?>
					<div id="oct-compare-content" class="oct-slide-panel-item-content"></div>
					<?php } ?>
					<?php if ($oct_page_bar_data['show_cart_bar']) { ?>
					<div id="oct-bottom-cart-content" class="oct-slide-panel-item-content"></div>
					<?php } ?>
				</div>
			</div>
		</div>			
	</div>
</div>