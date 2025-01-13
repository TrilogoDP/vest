<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->


         <script>
			$(document).ajaxComplete(function(event, request, settings) {
				var current_url = settings.url;
				if (current_url.indexOf("cart/add") > 1) {
					$.ajax({
						url: 'index.php?route=checkout/cart/checkGifts',
						type: 'POST',
						dataType: 'json',
						success: function(json) {							
							if(json!=null && json.gifts_with_options) {
								$.fancybox.open({
									href: 'index.php?route=checkout/cart/giftTeaserOptions&product_id='+json.gifts_with_options, 
									type : 'ajax',
									width:'40%',
									height:'50%',
									openEffect : 'elastic',
									openSpeed  : 150,
									fitToView   : true,
									closeBtn  : true,
								});
							}
						}
					});
				}
			});

            $(document).ajaxComplete(function(event, request, settings) { 
                var current_url = settings.url;
                if (current_url.indexOf("checkout/cart/remove") > -1) {
                    location.reload();
                }
            });
			 $(document).ajaxComplete(function(event, request, settings) { 
                var current_url = settings.url;
                if (current_url.indexOf("checkout/cart/GiftAdd") > -1) {
                    location.reload();
                }
            });
        </script>
      
</body></html>