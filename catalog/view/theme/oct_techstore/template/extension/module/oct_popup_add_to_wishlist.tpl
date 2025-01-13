<div id="wishlist-popup" class="white-popup mfp-with-anim narrow-popup">
	<h2 class="popup-header"><?php echo $heading_title; ?></h2>
	<div class="popup-text">
		<?php if ($success_add) { ?>
			<p><?php echo $text_success_add; ?> <?php echo $product_name; ?></p>
		<?php } else { ?>
			<p><?php echo $text_warning_add; ?></p>
		<?php } ?>
	</div>
	<div class="popup-buttons-box">
		<?php if ($success_add) { ?>
			<a class="text-button" href="<?php echo $wishlist_url; ?>"><?php echo $text_wishlist; ?></a>
		<?php } ?>
		<a class="popup-button popup-button-wide" onclick="$.magnificPopup.close();"><?php echo $button_ok; ?></a>
	</div>
	
</div>