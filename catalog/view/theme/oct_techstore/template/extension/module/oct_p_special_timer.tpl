<link href="catalog/view/javascript/octemplates/p_special_timer/flipclock.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/octemplates/p_special_timer/flipclock.js"></script>
<?php if ($position == "column_left" OR $position == "column_right") { ?>
<?php } else { ?>
<div class="row oct-day-goods-row">
  <div class="col-sm-12">
    <div class="oct-day-goods-box">
      <div class="oct-carousel-header"><?php echo $heading_title; ?></div>
      <div id="oct-day-goods-<?php echo $module; ?>" class="owl-carousel owl-theme">
        <?php foreach ($products as $product) { ?>     
          <div class="item">
            <div class="oct-day-goods-item">
              <div class="image">
                <div class="label_wrap">
                  
                    <?php if ($product['action_stickers']) { ?>
                      <div class="discount-box_wrap">
                        <?php foreach ($product['action_stickers'] as $action_sticker) { ?>
                          <div class="item">                          
                            <span class="label label-danger" style="<? if ($action_sticker['label_color']) { ?>color:<? echo $action_sticker['text_color']; ?>!important;<?php } ?> <? if ($action_sticker['label_bg']) { ?>background-color:<? echo $action_sticker['label_bg']; ?>!important;<?php } ?>"><? echo $action_sticker['label']; ?></span>  
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
                <?php if ($product['thumb']) { ?>
					<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
						<a href="<?php echo $product['href']; ?>" class="lazy_link">
							<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" width="150" height="180" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
						</a>
					<?php } else { ?>
						<a href="<?php echo $product['href']; ?>">
							<img src="<?php echo $product['thumb']; ?>" class="img-responsive" width="150" height="180" alt="<?php echo $product['name'] ?>" />
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
              <div class="things-to-buy">
                <div class="cart">
                  <?php if ($product['quantity'] > 0) { ?>
                    <a class="button-cart" title="<?php echo $button_cart_time; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>');"><i class="fa fa-hourglass-half" aria-hidden="true"></i> <?php echo $button_cart_time; ?></a>
                  <?php } else { ?>
                    <a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span></a>
                  <?php } ?>
                </div>
                <div class="counter">
                  <div id="clock-<?php echo $product['product_id']; ?>"></div>
                  <script>
                  $(document).ready(function() {
                    var futureDate  = new Date("<?php echo $product['date_end']; ?> 00:00:00".replace(/-/g, "/"));
                    var currentDate = new Date("<?php echo date("Y-m-d H:i:s"); ?>".replace(/-/g, "/"));
                    var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
                    function dayDiff(first, second) {
                      return (second-first)/(1000*60*60*24);
                    }
                    if (dayDiff(currentDate, futureDate) < 100) {
                      $('.clock').addClass('twoDayDigits');
                    } else {
                      $('.clock').addClass('threeDayDigits');
                    }
                    if(diff < 0) {
                      diff = 0;
                    }
                    clock = $('#clock-<?php echo $product['product_id']; ?>').FlipClock(diff, {
                      clockFace: 'DailyCounter',
                      countdown: true,
                      language: '<?php echo $language_code; ?>'
                    });
                  });
                  </script>
                </div>
              </div>
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
		$("#oct-day-goods-<?php echo $module; ?> img.lazy-module").lazyload({
			effect : "fadeIn"
		});
	}, 10);
	<?php } ?>
	
	$('#oct-day-goods-<?php echo $module; ?>').owlCarousel({
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
				$("#oct-day-goods-<?php echo $module; ?> img.lazy-module").lazyload();
			}, 10);
		},
		<?php } ?>
		smartSpeed: 800,
		loop: true,
		pagination: false
	});
});
</script>
<?php } ?>