<div id="compare-popup" class="white-popup mfp-with-anim narrow-popup">
	<h2 class="popup-header"><?php echo $heading_title; ?></h2>
	<div class="popup-text">
		<p><?php echo $text_success_add; ?> <?php echo $product_name; ?></p>
	</div>
	<div class="popup-buttons-box">
		<a class="text-button" href="<?php echo $compare_url; ?>"><?php echo $text_compare; ?></a>
		<a class="popup-button popup-button-wide" onclick="$.magnificPopup.close();"><?php echo $button_ok; ?></a>
	</div>
</div>