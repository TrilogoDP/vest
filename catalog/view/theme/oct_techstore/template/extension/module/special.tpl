<?php if ($position == "column_left" OR $position == "column_right") { ?>
<div class="row oct-carousel-row oct-col-module">
<?php } else { ?>
<div class="row oct-carousel-row">
<?php } ?>
  <div class="col-sm-12">
    <div class="oct-carousel-box">
      <div class="oct-carousel-header"><?php echo $heading_title; ?></div>
      <div id="oct-special-<?php echo $module; ?>" class="owl-carousel owl-theme">
        <?php foreach ($products as $product) { ?>     
        <div class="item">
          <div class="image">
           <div class="label_wrap">
                  
                    <?php if ($product['action_stickers']) { ?>
                      <div class="discount-box_wrap">
                        <?php foreach ($product['action_stickers'] as $action_sticker) { ?>
                          <div class="item">                          
                            <span class="label label-danger" style="<? if ($action_sticker['label_color']) { ?>color:<? echo $action_sticker['label_color']; ?>!important;<?php } ?> <? if ($action_sticker['label_bg']) { ?>background-color:<? echo $action_sticker['label_bg']; ?>!important;<?php } ?>"><? echo $action_sticker['label']; ?></span>  
                          </div>
                        <?php } ?>  
                      </div>
                    <?php } ?>
                        
                    <?php if ($product['oct_product_stickers']) { ?>
                      <div class="sticker-box">
                        <?php foreach ($product['oct_product_stickers'] as $product_sticker) { ?>
                          <div class="item" style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;"><?php echo $product_sticker['text']; ?></div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                    <?php if ($product['special']) { ?>
                      <div class="special">-<?php echo $product['saving']; ?>%</div>
                    <?php } ?>
                        
                  </div>
            <?php if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) { ?>
            <div class="quick-view"><a onclick="get_oct_popup_product_view('<?php echo $product['product_id']; ?>');"><?php echo $button_popup_view; ?></a></div>
            <?php } ?>   
            <?php if ($product['thumb']) { ?>
				<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
					<a href="<?php echo $product['href']; ?>" class="lazy_link">
						<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" width="150" height="180"/>
					</a>
				<?php } else { ?>
					<a href="<?php echo $product['href']; ?>">
						<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" width="150" height="180"/>
					</a>
				<?php } ?>
            <?php } ?>
          </div>
          <div class="name">
            <a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
          </div>
          <?php if ($product['rating']) { ?>
          <div class="rating">
            <?php for ($i = 1; $i <= 5; $i++) { ?>
            <?php if ($product['rating'] < $i) { ?>
            <i class="fa fa-star-o" aria-hidden="true"></i>
            <?php } else { ?>
            <i class="fa fa-star" aria-hidden="true"></i>
            <?php } ?>
            <?php } ?>
          </div>
          <?php } ?>
          <?php if ($product['price']) { ?>
          <div class="price">
                <?php if (!$product['special']) { ?>
                  <span class="price-new oct-price-normal"><?php echo $product['price']; ?></span>
                <?php } else { ?>
                  <span class="price-old oct-price-old"><?php echo $product['price']; ?></span><span class="price-new oct-price-new red"><?php echo $product['special']; ?></span>
                <?php } ?>
              </div>
          <?php } ?>
          <div class="cart">
            <?php if ($product['quantity'] > 0) { ?>
            <a class="button-cart oct-button" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo $button_cart; ?></a>
            <?php } else { ?>
            <a class="out-of-stock-button oct-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span></a>
            <?php } ?>
            <a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a>
            <a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a>
          </div>
        </div>
        <?php } ?>   
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
	<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
	setTimeout(function() {
		$("#oct-special-<?php echo $module; ?> img.lazy-module").lazyload({
			effect : "fadeIn"
		});
	}, 10);
	<?php } ?>
	
	$('#oct-special-<?php echo $module; ?>').owlCarousel({
		items: <?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>,
		itemsDesktop : [1921,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>],
		itemsDesktop : [1199,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>],
		itemsDesktopSmall : [979,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 3; } ?>],
		itemsTablet : [768,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 2; } ?>],
		itemsMobile : [479,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 1; } ?>],
		autoPlay: false,
		navigation: true,
		slideMargin: 10,
		navigationText: ['<i class="fa fa-angle-left fa-5x" aria-hidden="true"></i>', '<i class="fa fa-angle-right fa-5x" aria-hidden="true"></i>'],
		stopOnHover:true,
		<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
		afterMove : function(){
			setTimeout(function() {
				$("#oct-special-<?php echo $module; ?> img.lazy-module").lazyload();
			}, 10);
		},
		<?php } ?>
		smartSpeed: 800,
		loop: true,
		pagination: false
	});
});
</script>