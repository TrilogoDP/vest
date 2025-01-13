<div class="simplecheckout-block" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $has_error ? 'data-error="true"' : '' ?>>
	<?php if ($display_header) { ?>
		<div class="checkout-heading panel-heading"><?php echo $text_cart ?></div>
	<?php } ?>
	<?php if ($attention) { ?>
		<div class="alert alert-danger simplecheckout-warning-block"><?php echo $attention; ?></div>
	<?php } ?>
	<?php if ($error_warning) { ?>
		<div class="alert alert-danger simplecheckout-warning-block"><?php echo $error_warning; ?></div>
	<?php } ?>
    <div class="table-responsive">
        <div class="simplecheckout-cart">
        	<?php foreach ($products as $product) { ?>
        		<?php if (!empty($product['recurring'])) { ?>
        			<div class="simplecheckout-recurring-product">
        				<img src="<?php echo $additional_path ?>catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" />
        				<span style="float:left;line-height:18px; margin-left:10px;">
							<strong><?php echo $text_recurring_item ?></strong>
							<?php echo $product['profile_description'] ?>
						</span>
        			</div>
    			<?php } ?>
    			<div class="product-item-simplecheckout">
    				<?php if ($isMobile) { ?>
	    				<div class="image">							
	    					<?php if ($product['thumb']) { ?>
								<a href="<?php echo $product['href']; ?>">
									<?php if ($product['option']) { ?>
										<?php foreach ($product['option'] as $option) { ?>
											<?php if ($product['thumb']) { ?>
												<img src="<?php echo $option['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
											<?php } ?>
										<?php } ?>
									<?php } else { ?>
										<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
									<?php } ?>
								</a>
							<?php } ?>	
	    				</div>
	    				<div class="quantity">
	    					<div class="input-group btn-block">
								<button class="button btn-success button_oc btn oct-button minus" data-onclick="decreaseProductQuantity" type="submit">
									<svg width="16" height="2" viewBox="0 0 16 2" fill="none" xmlns="http://www.w3.org/2000/svg">
										<line y1="1" x2="16" y2="1" stroke="#002C3E" stroke-width="2"/>
									</svg>
								</button>
								<input class="form-control" type="text" data-onchange="changeProductQuantity" <?php echo $quantity_step_as_minimum ? 'onfocus="$(this).blur()" data-minimum="' . $product['minimum'] . '"' : '' ?> name="quantity[<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
								<button class="button btn-success button_oc btn oct-button plus" data-onclick="increaseProductQuantity" type="submit">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M15 7H9V1C9 0.734784 8.89464 0.48043 8.70711 0.292893C8.51957 0.105357 8.26522 0 8 0C7.73478 0 7.48043 0.105357 7.29289 0.292893C7.10536 0.48043 7 0.734784 7 1V7H1C0.734784 7 0.48043 7.10536 0.292893 7.29289C0.105357 7.48043 0 7.73478 0 8C0 8.26522 0.105357 8.51957 0.292893 8.70711C0.48043 8.89464 0.734784 9 1 9H7V15C7 15.2652 7.10536 15.5196 7.29289 15.7071C7.48043 15.8946 7.73478 16 8 16C8.26522 16 8.51957 15.8946 8.70711 15.7071C8.89464 15.5196 9 15.2652 9 15V9H15C15.2652 9 15.5196 8.89464 15.7071 8.70711C15.8946 8.51957 16 8.26522 16 8C16 7.73478 15.8946 7.48043 15.7071 7.29289C15.5196 7.10536 15.2652 7 15 7Z" fill="#002C3E"/>
									</svg>
								</button>     
							</div>
	    				</div>
	    				<div class="input-group btn-block" style="max-width: 200px;">
							<a data-onclick="removeProduct" data-product-key="<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key'] ?>">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.32166 5.99961L11.8083 1.51278C11.9317 1.38927 11.9998 1.22449 12 1.04878C12 0.872976 11.9319 0.708 11.8083 0.584683L11.4151 0.19161C11.2915 0.0678049 11.1267 0 10.9508 0C10.7752 0 10.6104 0.0678049 10.4868 0.19161L6.0002 4.67815L1.51337 0.19161C1.38995 0.0678049 1.22507 0 1.04927 0C0.873659 0 0.70878 0.0678049 0.585366 0.19161L0.192 0.584683C-0.064 0.840683 -0.064 1.25707 0.192 1.51278L4.67873 5.99961L0.192 10.4862C0.0684878 10.61 0.000487805 10.7747 0.000487805 10.9504C0.000487805 11.1262 0.0684878 11.2909 0.192 11.4145L0.585268 11.8076C0.708683 11.9313 0.873658 11.9992 1.04917 11.9992C1.22498 11.9992 1.38985 11.9313 1.51327 11.8076L6.0001 7.32098L10.4867 11.8076C10.6103 11.9313 10.7751 11.9992 10.9507 11.9992H10.9509C11.1266 11.9992 11.2914 11.9313 11.415 11.8076L11.8082 11.4145C11.9316 11.291 11.9997 11.1262 11.9997 10.9504C11.9997 10.7747 11.9316 10.61 11.8082 10.4863L7.32166 5.99961Z" fill="black" fill-opacity="0.5"/>
								</svg>
							</a>
						</div>
						<div class="name">
	    					<a href="<?php echo $product['href']; ?>">
	    						<?php echo $product['name']; ?><?php echo $product['gift_label']; ?>
	    						<?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
									<span class="product-warning">***</span>
								<?php } ?>
	    					</a>
	    					<p class="total price">
	    						<?php if ($product['old_total']) { ?>
									<span class="price-new"><?php echo $product['total']; ?></span>
									<span class="price-old"><?php echo $product['old_total']; ?></span>
								<?php } else { ?>
									<span class="price-new"><?php echo $product['total']; ?></span>
								<?php } ?>
	    					</p>
	    				</div>
	    			</div> 
	    		<?php } else if($isTablet || !$isMobile) { ?>
	    			<div class="image">
	    					<?php if ($product['thumb']) { ?>
								<a href="<?php echo $product['href']; ?>">
									<?php if ($product['option']) { ?>
										<?php foreach ($product['option'] as $option) { ?>
											<?php if ($product['thumb']) { ?>
												<img src="<?php echo $option['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
											<?php } ?>
										<?php } ?>
									<?php } else { ?>
										<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
									<?php } ?>
								</a>
							<?php } ?>	
	    				</div>
	    				<div class="name">
	    					<a href="<?php echo $product['href']; ?>">
	    						<?php echo $product['name']; ?><?php echo $product['gift_label']; ?>
	    						<?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
									<span class="product-warning">***</span>
								<?php } ?>
	    					</a>
	    					<p class="total price">
	    						<?php if ($product['old_total']) { ?>
									<span class="price-new"><?php echo $product['total']; ?></span>
									<span class="price-old"><?php echo $product['old_total']; ?></span>
								<?php } else { ?>
									<span class="price-new"><?php echo $product['total']; ?></span>
								<?php } ?>
	    					</p>
	    				</div>
	    				<div class="quantity">
	    					<div class="input-group btn-block">
								<button class="button btn-success button_oc btn oct-button minus" data-onclick="decreaseProductQuantity" type="submit">
									<svg width="16" height="2" viewBox="0 0 16 2" fill="none" xmlns="http://www.w3.org/2000/svg">
										<line y1="1" x2="16" y2="1" stroke="#002C3E" stroke-width="2"/>
									</svg>
								</button>
								<input class="form-control" type="text" data-onchange="changeProductQuantity" <?php echo $quantity_step_as_minimum ? 'onfocus="$(this).blur()" data-minimum="' . $product['minimum'] . '"' : '' ?> name="quantity[<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
								<button class="button btn-success button_oc btn oct-button plus" data-onclick="increaseProductQuantity" type="submit">
									<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M15 7H9V1C9 0.734784 8.89464 0.48043 8.70711 0.292893C8.51957 0.105357 8.26522 0 8 0C7.73478 0 7.48043 0.105357 7.29289 0.292893C7.10536 0.48043 7 0.734784 7 1V7H1C0.734784 7 0.48043 7.10536 0.292893 7.29289C0.105357 7.48043 0 7.73478 0 8C0 8.26522 0.105357 8.51957 0.292893 8.70711C0.48043 8.89464 0.734784 9 1 9H7V15C7 15.2652 7.10536 15.5196 7.29289 15.7071C7.48043 15.8946 7.73478 16 8 16C8.26522 16 8.51957 15.8946 8.70711 15.7071C8.89464 15.5196 9 15.2652 9 15V9H15C15.2652 9 15.5196 8.89464 15.7071 8.70711C15.8946 8.51957 16 8.26522 16 8C16 7.73478 15.8946 7.48043 15.7071 7.29289C15.5196 7.10536 15.2652 7 15 7Z" fill="#002C3E"/>
									</svg>
								</button>     
							</div>
	    				</div>
	    				<div class="input-group btn-block" style="max-width: 200px;">
							<a data-onclick="removeProduct" data-product-key="<?php echo !empty($product['cart_id']) ? $product['cart_id'] : $product['key'] ?>">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.32166 5.99961L11.8083 1.51278C11.9317 1.38927 11.9998 1.22449 12 1.04878C12 0.872976 11.9319 0.708 11.8083 0.584683L11.4151 0.19161C11.2915 0.0678049 11.1267 0 10.9508 0C10.7752 0 10.6104 0.0678049 10.4868 0.19161L6.0002 4.67815L1.51337 0.19161C1.38995 0.0678049 1.22507 0 1.04927 0C0.873659 0 0.70878 0.0678049 0.585366 0.19161L0.192 0.584683C-0.064 0.840683 -0.064 1.25707 0.192 1.51278L4.67873 5.99961L0.192 10.4862C0.0684878 10.61 0.000487805 10.7747 0.000487805 10.9504C0.000487805 11.1262 0.0684878 11.2909 0.192 11.4145L0.585268 11.8076C0.708683 11.9313 0.873658 11.9992 1.04917 11.9992C1.22498 11.9992 1.38985 11.9313 1.51327 11.8076L6.0001 7.32098L10.4867 11.8076C10.6103 11.9313 10.7751 11.9992 10.9507 11.9992H10.9509C11.1266 11.9992 11.2914 11.9313 11.415 11.8076L11.8082 11.4145C11.9316 11.291 11.9997 11.1262 11.9997 10.9504C11.9997 10.7747 11.9316 10.61 11.8082 10.4863L7.32166 5.99961Z" fill="black" fill-opacity="0.5"/>
								</svg>
							</a>
						</div>
	    			</div> 
	    		<?php } ?>
    		<?php } ?>
        </div>
	</div>
	
	<?php foreach ($totals as $total) { ?>
		<div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
			<span><?php echo preg_replace("/<img[^>]+\>/i", "", $total['title']); ?>:</span>
			<span class="simplecheckout-cart-total-value"><b><?php echo $total['text']; ?></b></span>
			<span class="simplecheckout-cart-total-remove" hidden>
				<?php if ($total['code'] == 'coupon') { ?>
					<i data-onclick="removeCoupon" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
				<?php } ?>
				<?php if ($total['code'] == 'voucher') { ?>
					<i data-onclick="removeVoucher" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
				<?php } ?>
				<?php if ($total['code'] == 'reward') { ?>
					<i data-onclick="removeReward" title="<?php echo $button_remove; ?>" class="fa fa-times-circle"></i>
				<?php } ?>
			</span>
		</div>
	<?php } ?>
	<?php if (isset($modules['coupon'])) { ?>
		<div class="simplecheckout-cart-total coupon">
			<span class="inputs">
				<input class="form-control" type="text" data-onchange="reloadAll" name="coupon" value="<?php echo $coupon; ?>"  placeholder="<?php echo $entry_coupon; ?>"/>
			</span>
			<span hidden class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button btn-primary button_oc btn oct-button"><span><?php echo $button_coupon; ?></span></a></span>
		</div>
	<?php } ?>
	<?php if (isset($modules['reward']) && $points > 0) { ?>
		<div class="simplecheckout-cart-total">
			<span class="inputs"><?php echo $entry_reward; ?>&nbsp;<input class="form-control" type="text" name="reward" data-onchange="reloadAll" value="<?php echo $reward; ?>" /></span>
		</div>
	<?php } ?>
	<?php if (isset($modules['voucher'])) { ?>
		<div class="simplecheckout-cart-total">
			<span class="inputs"><?php echo $entry_voucher; ?>&nbsp;<input class="form-control" type="text" name="voucher" data-onchange="reloadAll" value="<?php echo $voucher; ?>" /></span>
		</div>
	<?php } ?>
	<?php /* if (isset($modules['coupon']) || (isset($modules['reward']) && $points > 0) || isset($modules['voucher'])) { ?>
		<div class="simplecheckout-cart-total simplecheckout-cart-buttons">
        <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button btn-primary button_oc btn oct-button"><span><?php echo $button_update; ?></span></a></span>
		</div>
	<?php } */ ?>
	<input type="hidden" name="remove" value="" id="simplecheckout_remove">
	<div style="display:none;" id="simplecheckout_cart_total"><?php echo $cart_total ?></div>
	<?php if ($display_weight) { ?>
		<div style="display:none;" id="simplecheckout_cart_weight"><?php echo $weight ?></div>
	<?php } ?>
	<?php if (!$display_model) { ?>
		<style>
			.simplecheckout-cart col.model,
			.simplecheckout-cart th.model,
			.simplecheckout-cart td.model {
			display: none;
			}
		</style>
	<?php } ?>
	<div class="simplecheckout-summary-totals">
      <button id="simplecheckout-button-main-confirm" class="btn simplecheckout_button_confirm"  onclick="doSubmitOrderByFakeButton();"><?php echo $button_order_fake_btn; ?><?php echo $button_order; ?></button>
      <span id="errorMesageSimple"></span>
    </div>
</div>


<script>
  function doSubmitOrderByFakeButton(){
    
    if ($('input[name=\'payment_method_current\']').val() != ''){
      if (typeof window.simplecheckout_0 == 'object' && window.simplecheckout_0.isPaymentFormEmpty()){                    
    //    window.simplecheckout_0.copyPaymentFormFromDefaultIfIsEmptyAndFinishOrder();
      }
    }
    
    $('#simplecheckout_button_confirm').trigger('click');
  }
</script>
