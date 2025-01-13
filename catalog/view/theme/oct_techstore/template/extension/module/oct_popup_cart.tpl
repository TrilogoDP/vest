<div id="cart-popup" class="white-popup middle-popup mfp-with-anim">
	<div id="popup-cart-inner">
		<div class="oct-carousel-header"><?php echo $heading_title; ?></div>
		<?php if ($products || $vouchers) { ?>
			<?php if ($attention) { ?>
				<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<?php if ($success) { ?>
				<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
				<?php } else { ?>
				<div id="success-message"></div>
			<?php } ?>
			<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
			<div class="popup-text">
				<p><?php echo $text_cart_items; ?></p>
			</div>
			<div class="popup-cart-box">
				<form action="index.php?route=checkout/cart/edit" method="post" enctype="multipart/form-data">
					<div class="oct-cart-box">

						<?php if ($error_warning) { ?>
							<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							</div>
							<?php } ?>
							
						<?php foreach ($products as $product) { ?>
							<?php if ($isMobile) { ?>
								<div class="oct-cart-item">
									<div class="delete-item">
										<button type="button" class="delete" onclick="passEcommerceToDataLayer('removeFromCart', <?php echo $product['product_id']; ?>, <?php echo $product['quantity']; ?>, false); update(this, 'remove');">
											<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.32166 5.99961L11.8083 1.51278C11.9317 1.38927 11.9998 1.22449 12 1.04878C12 0.872976 11.9319 0.708 11.8083 0.584683L11.4151 0.19161C11.2915 0.0678049 11.1267 0 10.9508 0C10.7752 0 10.6104 0.0678049 10.4868 0.19161L6.0002 4.67815L1.51337 0.19161C1.38995 0.0678049 1.22507 0 1.04927 0C0.873659 0 0.70878 0.0678049 0.585366 0.19161L0.192 0.584683C-0.064 0.840683 -0.064 1.25707 0.192 1.51278L4.67873 5.99961L0.192 10.4862C0.0684878 10.61 0.000487805 10.7747 0.000487805 10.9504C0.000487805 11.1262 0.0684878 11.2909 0.192 11.4145L0.585268 11.8076C0.708683 11.9313 0.873658 11.9992 1.04917 11.9992C1.22498 11.9992 1.38985 11.9313 1.51327 11.8076L6.0001 7.32098L10.4867 11.8076C10.6103 11.9313 10.7751 11.9992 10.9507 11.9992H10.9509C11.1266 11.9992 11.2914 11.9313 11.415 11.8076L11.8082 11.4145C11.9316 11.291 11.9997 11.1262 11.9997 10.9504C11.9997 10.7747 11.9316 10.61 11.8082 10.4863L7.32166 5.99961Z" fill="black" fill-opacity="0.5"/>
											</svg>
										</button>
										<input name="product_key" value="<?php echo $product['key']; ?>" style="display: none;" hidden />
										<input name="product_id_q" value="<?php echo $product['product_id']; ?>" style="display: none;" hidden />         
									</div>
									<div class="item-image">
										<?php if ($product['thumb']) { ?>
											<a href="<?php echo $product['href']; ?>">
												<?php if ($product['option']) { ?>
													<?php foreach ($product['option'] as $option) { ?>
														<?php if ($product['thumb']) { ?>
															<img src="<?php echo $option['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
														<?php } ?>
													<?php } ?>
												<?php } else { ?>
													<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
												<?php } ?>												
											</a>
										<?php } ?>
									</div>
									<div class="item-name">
										
										<?php if (!$product['stock']) { ?>
											<span class="text-danger">***</span>
										<?php } ?>
									<?php if ($product['option']) { ?>
										<?php foreach ($product['option'] as $option) { ?>
											<br />
											<small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
											<?php if ($product['quantity'] > $option['quantity']) { ?>
												<span class="text-danger"><?php echo $error_option_stock; ?></span>
											<?php } ?>
										<?php } ?>
									<?php } ?>
										<?php if ($product['reward']) { ?>
											<br />
											<small><?php echo $product['reward']; ?></small>
										<?php } ?>
										<?php if ($product['recurring']) { ?>
											<br />
											<small><?php echo $text_recurring_item; ?> <?php echo $product['recurring']; ?></small>
										<?php } ?>
										<p class="quantity"><?php echo $text_quantity; ?> х<span class="count"><?php echo $product['quantity']; ?></span></p>


										<div class="item-price">
											<?php if ($product['old_total']) { ?>
												<span class="price-new red"><?php echo $product['total']; ?></span>
												<span class="price-old"><?php echo $product['old_total']; ?></span>
											<?php } else { ?>
												<span class="price-new"><?php echo $product['total']; ?></span>
											<?php } ?>
										</div>
										<div class="item-quantity">
											<div class="input-group btn-block">
												<a onclick="minus(this,'<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>');" class="quantity-m">
													<svg width="16" height="2" viewBox="0 0 16 2" fill="none" xmlns="http://www.w3.org/2000/svg">
														<line y1="1" x2="16" y2="1" stroke="#002C3E" stroke-width="2"/>
													</svg>
												</a>
												<input name="product_id_q" value="<?php echo $product['product_id']; ?>" style="display: none;" type="hidden" />
												<input name="product_id" value="<?php echo $product['key']; ?>" style="display: none;" type="hidden" />
												<input type="text" data-minimum="1" name="quantity" value="<?php echo $product['quantity']; ?>" class="form-control" onchange="update_manual(this, '<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>'); return validate(this);" keypress="update_manual(this, '<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>'); return validate(this);" />
												<a onclick="plus(this,'<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>');"  class="quantity-p">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M15 7H9V1C9 0.734784 8.89464 0.48043 8.70711 0.292893C8.51957 0.105357 8.26522 0 8 0C7.73478 0 7.48043 0.105357 7.29289 0.292893C7.10536 0.48043 7 0.734784 7 1V7H1C0.734784 7 0.48043 7.10536 0.292893 7.29289C0.105357 7.48043 0 7.73478 0 8C0 8.26522 0.105357 8.51957 0.292893 8.70711C0.48043 8.89464 0.734784 9 1 9H7V15C7 15.2652 7.10536 15.5196 7.29289 15.7071C7.48043 15.8946 7.73478 16 8 16C8.26522 16 8.51957 15.8946 8.70711 15.7071C8.89464 15.5196 9 15.2652 9 15V9H15C15.2652 9 15.5196 8.89464 15.7071 8.70711C15.8946 8.51957 16 8.26522 16 8C16 7.73478 15.8946 7.48043 15.7071 7.29289C15.5196 7.10536 15.2652 7 15 7Z" fill="#002C3E"/>
													</svg>
												</a>
											</div>
										</div>
									</div>
									
									
									<div class="name">
										<a href="<?php echo $product['href']; ?>" class="oct-popup-cart-link"><?php echo $product['name']; ?></a>
									</div>
								</div>
							<?php } else { ?>
								<div class="oct-cart-item">
									<div class="delete-item">
										<button type="button" class="delete" onclick="passEcommerceToDataLayer('removeFromCart', <?php echo $product['product_id']; ?>, <?php echo $product['quantity']; ?>, false); update(this, 'remove');">
											<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.32166 5.99961L11.8083 1.51278C11.9317 1.38927 11.9998 1.22449 12 1.04878C12 0.872976 11.9319 0.708 11.8083 0.584683L11.4151 0.19161C11.2915 0.0678049 11.1267 0 10.9508 0C10.7752 0 10.6104 0.0678049 10.4868 0.19161L6.0002 4.67815L1.51337 0.19161C1.38995 0.0678049 1.22507 0 1.04927 0C0.873659 0 0.70878 0.0678049 0.585366 0.19161L0.192 0.584683C-0.064 0.840683 -0.064 1.25707 0.192 1.51278L4.67873 5.99961L0.192 10.4862C0.0684878 10.61 0.000487805 10.7747 0.000487805 10.9504C0.000487805 11.1262 0.0684878 11.2909 0.192 11.4145L0.585268 11.8076C0.708683 11.9313 0.873658 11.9992 1.04917 11.9992C1.22498 11.9992 1.38985 11.9313 1.51327 11.8076L6.0001 7.32098L10.4867 11.8076C10.6103 11.9313 10.7751 11.9992 10.9507 11.9992H10.9509C11.1266 11.9992 11.2914 11.9313 11.415 11.8076L11.8082 11.4145C11.9316 11.291 11.9997 11.1262 11.9997 10.9504C11.9997 10.7747 11.9316 10.61 11.8082 10.4863L7.32166 5.99961Z" fill="black" fill-opacity="0.5"/>
											</svg>
										</button>
										<input name="product_key" value="<?php echo $product['key']; ?>" style="display: none;" hidden />
										<input name="product_id_q" value="<?php echo $product['product_id']; ?>" style="display: none;" hidden />         
									</div>
									<div class="item-image">
										<?php if ($product['thumb']) { ?>
											<a href="<?php echo $product['href']; ?>">
												<?php if ($product['option']) { ?>
													<?php foreach ($product['option'] as $option) { ?>
														<?php if ($product['thumb']) { ?>
															<img src="<?php echo $option['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
														<?php } ?>
													<?php } ?>
												<?php } else { ?>
													<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail popup-img-thumbnail" />
												<?php } ?>	
											</a>
										<?php } ?>
									</div>
									<div class="item-name">
										<a href="<?php echo $product['href']; ?>" class="oct-popup-cart-link"><?php echo $product['name']; ?></a>
										<?php if (!$product['stock']) { ?>
											<span class="text-danger">***</span>
										<?php } ?>
									<?php if ($product['option']) { ?>
										<?php foreach ($product['option'] as $option) { ?>
											<br />
											<small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
											<?php if ($product['quantity'] > $option['quantity']) { ?>
												<span class="text-danger"><?php echo $error_option_stock; ?></span>
											<?php } ?>
										<?php } ?>
									<?php } ?>
										<?php if ($product['reward']) { ?>
											<br />
											<small><?php echo $product['reward']; ?></small>
										<?php } ?>
										<?php if ($product['recurring']) { ?>
											<br />
											<small><?php echo $text_recurring_item; ?> <?php echo $product['recurring']; ?></small>
										<?php } ?>
										<p class="quantity"><?php echo $text_quantity; ?> х<span class="count"><?php echo $product['quantity']; ?></span></p>
									</div>
									<div class="item-quantity">
										<div class="input-group btn-block">
											<a onclick="minus(this,'<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>');" class="quantity-m">
												<svg width="16" height="2" viewBox="0 0 16 2" fill="none" xmlns="http://www.w3.org/2000/svg">
													<line y1="1" x2="16" y2="1" stroke="#002C3E" stroke-width="2"/>
												</svg>
											</a>
											<input name="product_id_q" value="<?php echo $product['product_id']; ?>" style="display: none;" type="hidden" />
											<input name="product_id" value="<?php echo $product['key']; ?>" style="display: none;" type="hidden" />
											<input type="text" data-minimum="1" name="quantity" value="<?php echo $product['quantity']; ?>" class="form-control" onchange="update_manual(this, '<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>'); return validate(this);" keypress="update_manual(this, '<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>'); return validate(this);" />
											<a onclick="plus(this,'<?php echo $product['key']; ?>', '<?php echo $product['product_id']; ?>');"  class="quantity-p">
												<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M15 7H9V1C9 0.734784 8.89464 0.48043 8.70711 0.292893C8.51957 0.105357 8.26522 0 8 0C7.73478 0 7.48043 0.105357 7.29289 0.292893C7.10536 0.48043 7 0.734784 7 1V7H1C0.734784 7 0.48043 7.10536 0.292893 7.29289C0.105357 7.48043 0 7.73478 0 8C0 8.26522 0.105357 8.51957 0.292893 8.70711C0.48043 8.89464 0.734784 9 1 9H7V15C7 15.2652 7.10536 15.5196 7.29289 15.7071C7.48043 15.8946 7.73478 16 8 16C8.26522 16 8.51957 15.8946 8.70711 15.7071C8.89464 15.5196 9 15.2652 9 15V9H15C15.2652 9 15.5196 8.89464 15.7071 8.70711C15.8946 8.51957 16 8.26522 16 8C16 7.73478 15.8946 7.48043 15.7071 7.29289C15.5196 7.10536 15.2652 7 15 7Z" fill="#002C3E"/>
												</svg>
											</a>
										</div>
									</div>
									<div class="item-price">
										<?php if ($product['old_total']) { ?>
											<span class="price-new red"><?php echo $product['total']; ?></span>
											<span class="price-old"><?php echo $product['old_total']; ?></span>
										<?php } else { ?>
											<span class="price-new"><?php echo $product['total']; ?></span>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
						<?php foreach ($vouchers as $voucher) { ?>
							<div>
								<div class="delete-td"><button type="button" onclick="oct_popup_voucher_remove('<?php echo $voucher['key']; ?>');">×</button></div>
								<div class="text-center image-td"></div>
								<div class="text-left"><span class="popup-table-text"><?php echo $voucher['description']; ?></span></div>
								<div class="text-center">
									<input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
								</div>
								<div class="text-right popup-table-text"><?php echo $voucher['amount']; ?></div>
							</div>
						<?php } ?>
					
					</div>
					
					<?php if ($addons) { ?>		
						<section class="hr-bottom buy-togeter-products-wrap" name="buy-togeter-products">						
							<div class="buy-togeter-products-head">
								<h4><?php echo $text_related; ?></h4>
							</div>
							<div id="oct-addons" class="buy-togeter-products-content">
								<?php foreach ($addons as $addon) { ?>
									<div class="item" data-product-id="<?php echo $addon['product_id']; ?>" data-sort-order="<?php echo $addon['sort_order']; ?>">
										<?php if ($isMobile) { ?>
											<div class="img">
												<img src="<?php echo $addon['thumb_addon']; ?>" title="<?php echo $addon['name']; ?>" alt="<?php echo $addon['name']; ?>" width="50" height="50" class="img-responsive" />
											</div>
											<div class="about">
												<a href="<?php echo $addon['href']; ?>" class="name" title="<?php echo $addon['name']; ?>">
													<?php echo $addon['name']; ?>
												</a>
												<div>
													<div class="price">
														<?php if ($addon['special']) { ?>
															<span class="oct-price-new red"><?php echo $addon['special']; ?></span>
															<span class="oct-price-old"><?php echo $addon['price']; ?></span>
														<?php } else { ?>
															<span class="oct-price-new"><?php echo $addon['price']; ?></span>
														<?php } ?>
													</div>
													<div class="btn">
														<a class="btn-green" onclick="add_to_cart_addon(<?php echo $main_product_id; ?>, <?php echo $addon['product_id']; ?>);">
															<?php echo $button_addon; ?>			
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"></path>
															</svg>
														</a>
													</div>
												</div>
											</div>
											
										<?php } else { ?>
											<div class="about">
												<div class="img">
													<img src="<?php echo $addon['thumb_addon']; ?>" title="<?php echo $addon['name']; ?>" alt="<?php echo $addon['name']; ?>" width="50" height="50" class="img-responsive" />
												</div>
												<a href="<?php echo $addon['href']; ?>" title="<?php echo $addon['name']; ?>">
													<?php echo $addon['name']; ?>
												</a>
											</div>
											<div class="price">
												<?php if ($addon['special']) { ?>
													<span class="oct-price-new red"><?php echo $addon['special']; ?></span>
													<span class="oct-price-old"><?php echo $addon['price']; ?></span>
												<?php } else { ?>
													<span class="oct-price-new"><?php echo $addon['price']; ?></span>
												<?php } ?>
											</div>
											<div class="btn">
												<a class="btn-green" onclick="add_to_cart_addon(<?php echo $main_product_id; ?>, <?php echo $addon['product_id']; ?>);">
													<?php echo $button_addon; ?>			
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"></path>
													</svg>
												</a>
											</div>
										
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</section>
					<?php } ?>
					
					
				</form>
				<div class="popup-total-cart">
					<?php foreach ($totals as $total) { ?>
						<div class="total-text"><?php echo $total['title']; ?>: <span class="gold"><?php echo $total['text']; ?></span></div>
					<?php } ?>
					<div class="popup-buttons-box">
						
						<a class="text-button" onclick="$.magnificPopup.close();"><?php echo $button_shopping; ?></a>
					
						<a class="popup-button popup-button-wide" id="popup-button-link" href="<?php echo $checkout_link; ?>"><?php echo $button_checkout; ?></a>
						
					</div>
				</div>							
				
			</div>
			<?php } else { ?>
			<div class="popup-text">
				<p><?php echo $empty; ?></p>
			</div>
			<div class="popup-buttons-box cart-empty text-center">
				<a class="text-button" onclick="$.magnificPopup.close();"><?php echo $button_shopping; ?></a>
			</div>
		<?php } ?>
	</div>
	<script>
	
		passEcommerceStepsToDataLayer('PopupCartOpened');
	
		function masked(element, status) {
			if (status == true) {
				$('<div/>')
				.attr({ 'class':'masked' })
				.prependTo(element);
				$('<div class="masked_loading" />').insertAfter($('.masked'));
				} else {
				$('.masked').remove();
				$('.masked_loading').remove();
			}
		}
		function validate(input) {
			input.value = input.value.replace(/[^\d,]/g, '');
		}
		
		function add_to_cart_addon(pid, aid){
			masked('#popup-cart-inner', true);
			
			passEcommerceToDataLayer('addToCart', pid, 1, false);
			$.ajax({
				url: 'index.php?route=extension/module/oct_popup_cart&addon=1&product_id='+pid+'&addon_id='+aid,
				type: 'POST',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				}
			});
		}

		function update(target, status) {
			masked('#popup-cart-inner', true);
			var input_val    = $(target).parent().parent().parent().children('input[name=quantity]').val(),
			quantity     = parseInt(input_val),
			product_id   = $(target).parent().parent().parent().children('input[name=product_id]').val(),
			product_id_q = $(target).parent().parent().parent().children('input[name=product_id_q]').val(),
			product_key  = $(target).next().val(),
			urls         = null;
			if (quantity <= 0) {
				masked('#popup-cart-inner', false);
				quantity = $(target).parent().parent().parent().children('input[name=quantity]').val(1);
				return;
			}
			if (status == 'update') {
				urls = 'index.php?route=extension/module/oct_popup_cart&update=' + product_id + '&quantity=' + quantity;
				} else if (status == 'add') {
				urls = 'index.php?route=extension/module/oct_popup_cart&add=' + target + '&quantity=1';
				} else {
				urls = 'index.php?route=extension/module/oct_popup_cart&remove=' + product_key;
			}
			$.ajax({
				url: urls,
				type: 'get',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				} 
			});
		}

		function plus(id, product_id, real_product_id){
			let quantity = parseInt($(id).parent().children('.form-control').val());
			let minimum = parseInt($(id).parent().children('.form-control').attr('data-minimum')) || 1;

			quantity = quantity + minimum;
			(quantity == 0) ? quantity = minimum: false;

			passEcommerceToDataLayer('editCart', real_product_id, quantity, false);
			$.ajax({
				url: 'index.php?route=extension/module/oct_popup_cart&update=' + product_id + '&quantity=' + quantity,
				type: 'get',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				} 
			});
		}

		function minus(id, product_id, real_product_id){
			let quantity = parseInt($(id).parent().children('.form-control').val());
			let minimum = parseInt($(id).parent().children('.form-control').attr('data-minimum')) || 1;

			quantity = quantity - minimum;
			(quantity == 0) ? quantity = minimum: false;

			console.log('quantity-->', quantity);
			console.log('minimum-->', minimum);
			console.log('product_id-->', product_id);

			passEcommerceToDataLayer('editCart', real_product_id, quantity, false);
			$.ajax({
				url: 'index.php?route=extension/module/oct_popup_cart&update=' + product_id + '&quantity=' + quantity,
				type: 'get',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				} 
			});
		}
		
		function update_manual(target, product_id, real_product_id) {
			masked('#popup-cart-inner', true);
			var input_val = $(target).val(),
			quantity  = parseInt(input_val);
			if (quantity <= 0) {
				masked('#popup-cart-inner', false);
				quantity = $(target).val(1);
				return;
			}
			
			passEcommerceToDataLayer('editCart', real_product_id, quantity, false);
			$.ajax({
				url: 'index.php?route=extension/module/oct_popup_cart&update=' + product_id + '&quantity=' + quantity,
				type: 'get',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				} 
			});
		}
		function oct_popup_voucher_remove(voucher_key) {
			masked('#popup-cart-inner', true);
			$.ajax({
				url: 'index.php?route=extension/module/oct_popup_cart&remove=' + voucher_key,
				type: 'get',
				dataType: 'html',
				success: function(data) {
					$.ajax({
						url: 'index.php?route=extension/module/oct_popup_cart/status_cart',
						type: 'get',
						dataType: 'json',
						success: function(json) {
							masked('#popup-cart-inner', false);
							
							$("#cart-total").html(json['total']);
							$('#cart > ul').load('index.php?route=common/cart/info ul li');
							
							$.ajax({
								url:  'index.php?route=extension/module/oct_page_bar/update_html',
								type: 'get',
								dataType: 'json',
								success: function(json) {
									$("#oct-bottom-cart-quantity").html(json['total_cart']);
								}
							});
							
							$('#popup-cart-inner').html($(data).find('#popup-cart-inner > *'));
						} 
					});
				} 
			});
		}

	</script>

<?php if ($error_option_stock) { ?>
    <script>
        document.getElementById('popup-button-link').href = 'javascript:void(0)';
        document.getElementById('popup-button-link').classList.add('link_disabled');
    </script>
<?php } ?>
</div>	