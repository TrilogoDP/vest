<?php if ($position == "column_left" OR $position == "column_right") { ?>
	<?php } else { ?>
	<div class="row product-tab-row">
		<div class="col-sm-12">
			<div class="oct-product-tab">
				<ul class="nav nav-tabs">
					<?php if ($special_products){ ?>
						<script>is_special = true;</script>
						<li class="active"><a href="#tab-special-<?php echo $module; ?>" data-toggle="tab"><i class="fa fa-gift" aria-hidden="true"></i> <span><?php echo $tab_special; ?></span></a></li>
						<?php }else{ ?>
						<script>is_special = false;</script>
					<?php } ?>
					<?php if ($latest_products){ ?>
						<li <?php if (!$special_products){ echo 'class="active"'; } ?>><a href="#tab-latest-<?php echo $module; ?>" data-toggle="tab"><i class="fa fa-bolt" aria-hidden="true"></i> <span><?php echo $tab_latest; ?></span></a></li>
					<?php } ?>
					<?php if ($featured_products){ ?>
						<li><a href="#tab-featured-<?php echo $module; ?>" data-toggle="tab"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <span><?php echo $tab_featured; ?></span></a></li>
					<?php } ?>
					<?php if ($bestseller_products){ ?>
						<li><a href="#tab-bestseller-<?php echo $module; ?>" data-toggle="tab"><i class="fa fa-star" aria-hidden="true"></i> <span><?php echo $tab_bestseller; ?></span></a></li>
					<?php } ?>
					<?php if ($top_viewed_products){ ?>
						<li><a href="#tab-top_viewed-<?php echo $module; ?>" data-toggle="tab"><i class="fa fa-eye" aria-hidden="true"></i> <span><?php echo $tab_top_viewed; ?></span></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content" style="min-height:430px;">
					<?php if ($special_products) { ?>
						<div id="tab-special-<?php echo $module; ?>" class="tab-pane active">
							<div id="owl-example4" class="owl-carousel owl-theme">
								<?php foreach ($special_products as $product) { ?>     
									<div class="product-item">
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
														<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
													</a>
													<?php } else { ?>
													<a href="<?php echo $product['href']; ?>">
														<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" />
													</a>
												<?php } ?>
											<?php } ?>
										</div>

										<div class="name <?php if ($product['rating'] === 0) { ?>no_rating<?php } ?>">
											<a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
										</div>


										<div class="reviews-wrap">
											<?php if ($product['rating'] !== 0) { ?>
												<div class="rating">
													<?php for ($i = 1; $i <= 5; $i++) { ?>
														<?php if ($product['rating'] < $i) { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
															</svg>
														<?php } else { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
															</svg>
														<?php } ?>
													<?php } ?>
												</div>
												<a href="<?php echo $product['href']; ?>#reviews" class="count-reviews">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
													</svg>
													<?php echo $product['reviews']; ?>
												</a>
											<?php } ?>
										</div>

										<?php if ($product['price']) { ?>
											<div class="price">
												<?php if (!$product['special']) { ?>
													<span class="price-new oct-price-new"><?php echo $product['price']; ?></span>
												<?php } else { ?>
													<span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
													<span class="price-old oct-price-old"><?php echo $product['price']; ?></span>
												<?php } ?>
											</div>
										<?php } ?>

										
										
										<div class="cart">
											<?php if ($product['quantity'] > 0) { ?>
												<a class="button-cart btn-green" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');">
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } else { ?>
												<a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
													<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button">
												<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#93BEB5"/>
												</svg>
											</a>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#C9C9C9"/>
												</svg>
											</a>
										</div>
									</div>
								<?php } ?>    
							</div>
						</div>
					<?php } ?>	  
					<?php if ($latest_products) { ?>
						<div id="tab-latest-<?php echo $module; ?>" class="tab-pane <?php if (!$special_products){ echo 'active'; } ?>">
							<div id="owl-example1" class="owl-carousel owl-theme">
								<?php foreach ($latest_products as $product) { ?>     
									<div class="product-item">
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
														<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
													</a>
													<?php } else { ?>
													<a href="<?php echo $product['href']; ?>">
														<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" />
													</a>
												<?php } ?>
											<?php } ?>
										</div>

										<div class="name <?php if ($product['rating'] === 0) { ?>no_rating<?php } ?>">
											<a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
										</div>


										<div class="reviews-wrap">
											<?php if ($product['rating'] !== 0) { ?>
												<div class="rating">
													<?php for ($i = 1; $i <= 5; $i++) { ?>
														<?php if ($product['rating'] < $i) { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
															</svg>
														<?php } else { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
															</svg>
														<?php } ?>
													<?php } ?>
												</div>
												<a href="<?php echo $product['href']; ?>#reviews" class="count-reviews">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
													</svg>
													<?php echo $product['reviews']; ?>
												</a>
											<?php } ?>
										</div>

										<?php if ($product['price']) { ?>
											<div class="price">
												<?php if (!$product['special']) { ?>
													<span class="price-new oct-price-new"><?php echo $product['price']; ?></span>
												<?php } else { ?>
													<span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
													<span class="price-old oct-price-old"><?php echo $product['price']; ?></span>
												<?php } ?>
											</div>
										<?php } ?>

										
										<div class="cart">
											<?php if ($product['quantity'] > 0) { ?>
												<a class="button-cart btn-green" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');">
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } else { ?>
												<a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
													<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button">
												<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#93BEB5"/>
												</svg>
											</a>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#C9C9C9"/>
												</svg>
											</a>
										</div>
									</div>
								<?php } ?>    
							</div>
						</div>
					<?php } ?>
					<?php if ($featured_products) { ?>
						<div id="tab-featured-<?php echo $module; ?>" class="tab-pane">
							<div id="owl-example2" class="owl-carousel owl-theme">
								<?php foreach ($featured_products as $product) { ?>     
									<div class="product-item">
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
														<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
													</a>
													<?php } else { ?>
													<a href="<?php echo $product['href']; ?>">
														<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" />
													</a>
												<?php } ?>
											<?php } ?>
										</div>

										<div class="name <?php if ($product['rating'] === 0) { ?>no_rating<?php } ?>">
											<a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
										</div>


										<div class="reviews-wrap">
											<?php if ($product['rating'] !== 0) { ?>
												<div class="rating">
													<?php for ($i = 1; $i <= 5; $i++) { ?>
														<?php if ($product['rating'] < $i) { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
															</svg>
														<?php } else { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
															</svg>
														<?php } ?>
													<?php } ?>
												</div>
												<a href="<?php echo $product['href']; ?>#reviews" class="count-reviews">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
													</svg>
													<?php echo $product['reviews']; ?>
												</a>
											<?php } ?>
										</div>
										<?php if ($product['price']) { ?>
											<div class="price">
												<?php if (!$product['special']) { ?>
													<span class="price-new oct-price-new"><?php echo $product['price']; ?></span>
												<?php } else { ?>
													<span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
													<span class="price-old oct-price-old"><?php echo $product['price']; ?></span>
												<?php } ?>
											</div>
										<?php } ?>

										
										<div class="cart">
											<?php if ($product['quantity'] > 0) { ?>
												<a class="button-cart btn-green" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');">
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } else { ?>
												<a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
													<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button">
												<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#93BEB5"/>
												</svg>
											</a>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#C9C9C9"/>
												</svg>
											</a>
										</div>
									</div>
								<?php } ?>    
							</div>
						</div>
					<?php } ?>
					<?php if ($bestseller_products) { ?>
						<div id="tab-bestseller-<?php echo $module; ?>" class="tab-pane">
							<div id="owl-example3" class="owl-carousel owl-theme">
								<?php foreach ($bestseller_products as $product) { ?>     
									<div class="product-item">
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
														<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
													</a>
													<?php } else { ?>
													<a href="<?php echo $product['href']; ?>">
														<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" />
													</a>
												<?php } ?>
											<?php } ?>
										</div>

										<div class="name <?php if ($product['rating'] === 0) { ?>no_rating<?php } ?>">
											<a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
										</div>


										<div class="reviews-wrap">
											<?php if ($product['rating'] !== 0) { ?>
												<div class="rating">
													<?php for ($i = 1; $i <= 5; $i++) { ?>
														<?php if ($product['rating'] < $i) { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
															</svg>
														<?php } else { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
															</svg>
														<?php } ?>
													<?php } ?>
												</div>
												<a href="<?php echo $product['href']; ?>#reviews" class="count-reviews">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
													</svg>
													<?php echo $product['reviews']; ?>
												</a>
											<?php } ?>
										</div>

										<?php if ($product['price']) { ?>
											<div class="price">
												<?php if (!$product['special']) { ?>
													<span class="price-new oct-price-new"><?php echo $product['price']; ?></span>
												<?php } else { ?>
													<span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
													<span class="price-old oct-price-old"><?php echo $product['price']; ?></span>
												<?php } ?>
											</div>
										<?php } ?>

										
										
										<div class="cart">
											<?php if ($product['quantity'] > 0) { ?>
												<a class="button-cart btn-green" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');">
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } else { ?>
												<a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
													<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button">
												<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#93BEB5"/>
												</svg>
											</a>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#C9C9C9"/>
												</svg>
											</a>
										</div>
									</div>
								<?php } ?>    
							</div>
						</div>
					<?php } ?>
					<?php if ($top_viewed_products) { ?>
						<div id="tab-top_viewed-<?php echo $module; ?>" class="tab-pane">
							<div id="owl-example5" class="owl-carousel owl-theme">
								<?php foreach ($top_viewed_products as $product) { ?>     
									<div class="product-item">
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
														<img data-original="<?php echo $product['thumb']; ?>" src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy-module" alt="<?php echo $product['name'] ?>" />
													</a>
													<?php } else { ?>
													<a href="<?php echo $product['href']; ?>">
														<img src="<?php echo $product['thumb']; ?>" class="img-responsive" alt="<?php echo $product['name'] ?>" />
													</a>
												<?php } ?>
											<?php } ?>
										</div>

										<div class="name <?php if ($product['rating'] === 0) { ?>no_rating<?php } ?>">
											<a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
										</div>


										<div class="reviews-wrap">
											<?php if ($product['rating'] !== 0) { ?>
												<div class="rating">
													<?php for ($i = 1; $i <= 5; $i++) { ?>
														<?php if ($product['rating'] < $i) { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
															</svg>
														<?php } else { ?>
															<svg width="15" height="15" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
															</svg>
														<?php } ?>
													<?php } ?>
												</div>
												<a href="<?php echo $product['href']; ?>#reviews" class="count-reviews">
													<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
													</svg>
													<?php echo $product['reviews']; ?>
												</a>
											<?php } ?>
										</div>

										<?php if ($product['price']) { ?>
											<div class="price">
												<?php if (!$product['special']) { ?>
													<span class="price-new oct-price-new"><?php echo $product['price']; ?></span>
												<?php } else { ?>
													<span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
													<span class="price-old oct-price-old"><?php echo $product['price']; ?></span>
												<?php } ?>
											</div>
										<?php } ?>

										

										
										<div class="cart">
											<?php if ($product['quantity'] > 0) { ?>
												<a class="button-cart btn-green" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');">
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } else { ?>
												<a class="out-of-stock-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
													<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button">
												<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#93BEB5"/>
												</svg>
											</a>
											<a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#C9C9C9"/>
												</svg>
											</a>
										</div>
									</div>
								<?php } ?>    
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<script>
		function initialize_owl(el) {
			el.owlCarousel({
				responsive:{
			        0:{
			            items:1,
			            margin:0,
			        },
			        600:{
			            items:2
			        },
			        1000:{
			            items:3
			        },
			        1400:{
			            items:5
			        }
			    },
				autoPlay: true,
				margin:30,
				stopOnHover:true,
				smartSpeed: 800,
				loop: true,
				nav:true,
			    dots: true,
			    navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>']
			});
		}
		
		<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
			function iniLazy(el){
				setTimeout(function() {
					el.find('img.lazy-module').lazyload({
						effect : "fadeIn"
					});
				}, 10);
			}
			
			function iniLazyTab(el){
				setTimeout(function() {
					el.find('img.lazy-module').lazyload();
				}, 10);
			}
			<?php } else { ?>
			function iniLazy(el){
				return;
			}
			
			function iniLazyTab(el){
				return;
			}
		<?php }?>
		function destroy_owl(el) {
			// el.data('owlCarousel').destroy();
		}
		
		$(function () {
			iniLazy($('#owl-example1'));
			var id_tab_first = '#owl-example4';
			if(!is_special){
				id_tab_first = '#owl-example1';
			}
			initialize_owl($(id_tab_first));
			
			$('a[href="#tab-latest-<?php echo $module; ?>"]').on('shown.bs.tab', function () {
				iniLazyTab($('#owl-example1'));
				initialize_owl($('#owl-example1'));
				}).on('hide.bs.tab', function () {
				destroy_owl($('#owl-example1'));
			});
			
			$('a[href="#tab-featured-<?php echo $module; ?>"]').on('shown.bs.tab', function () {
				iniLazyTab($('#owl-example2'));
				initialize_owl($('#owl-example2'));
				}).on('hide.bs.tab', function () {
				destroy_owl($('#owl-example2'));
			});
			
			$('a[href="#tab-bestseller-<?php echo $module; ?>"]').on('shown.bs.tab', function () {
				iniLazyTab($('#owl-example3'));
				initialize_owl($('#owl-example3'));
				}).on('hide.bs.tab', function () {
				destroy_owl($('#owl-example3'));
			});
			
			$('a[href="#tab-special-<?php echo $module; ?>"]').on('shown.bs.tab', function () {
				iniLazyTab($(id_tab_first));
				initialize_owl($(id_tab_first));
				}).on('hide.bs.tab', function () {
				destroy_owl($(id_tab_first));
			});
			
			$('a[href="#tab-top_viewed-<?php echo $module; ?>"]').on('shown.bs.tab', function () {
				iniLazyTab($('#owl-example5'));
				initialize_owl($('#owl-example5'));
				}).on('hide.bs.tab', function () {
				destroy_owl($('#owl-example5'));
			});
		});
	</script>
<?php } ?>