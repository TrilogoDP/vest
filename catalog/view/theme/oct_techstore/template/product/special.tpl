<?php if(!isset($ajax_mf) || $ajax_mf == FALSE){ echo $header;  }?>
<script src="catalog/view/theme/oct_techstore/js/jquery.lazyload.js"></script>
<style>
	.sort-container .sort-left .appearance .btn-group{
		display: none;
	}
</style>

<?php if ($products) { ?>	
	<?php $pli = 0; foreach ($products as &$product) { ?>
		<?php $product['ecommerceData']['position'] = $pli; $pli++;  ?>
		<?php if (!empty($heading_title)) { ?>								
			<?php $product['ecommerceData']['list'] = prepareEcommString($heading_title);  ?>
			<?php } else { ?>
			<?php $product['ecommerceData']['list'] = 'Undefined Products List';  ?>
		<?php } ?>	
	<?php } ?>
	<?php unset($product);?>
<?php } ?>

<div class="wrap fdc ajax_mf">
	
	<div class="breadcrumb-box">
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
				<?php if($count == 0) { ?>
					<li>
						<a href="<?php echo $breadcrumb['href']; ?>">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
							</svg>
							<?php echo $breadcrumb['text']; ?>
						</a>
					</li>
					<?php } elseif($count+1<count($breadcrumbs)) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } else { ?>
					<!-- <li><span><?php echo $breadcrumb['text']; ?></span></li> -->
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
	<?php echo $content_top; ?>
	<h1 class="title_module"><?php echo $heading_title; ?><?php if (!empty($seo_page)) { echo $seo_page; } ?></h1>
	
	<div class="content_special">
		<?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
			<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
			<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>">
			<?php if ($products) { ?>
				<?php include(DIR_TEMPLATEINCLUDE . 'structured/view_sort_limit.tpl'); ?>
				<div id="res-products-wrap">
					<div id="res-products">
						
						<?php foreach ($products as $product) { ?>
							<div class="product-item product-grid product-layout">
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

									<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
										<a href="<?php echo $product['href']; ?>" class="lazy_link">
											<img data-original="<?php echo $product['thumb']; ?>" width="100%" <?php if ($isMobile) { ?>height="120"<?php } else { ?>height="258"<?php } ?> src="<?php echo $oct_lazyload_image; ?>" class="img-responsive lazy" alt="<?php echo $product['name'] ?>" />
										</a>
										<?php } else { ?>
										<a href="<?php echo $product['href']; ?>">

											<img src="<?php echo $product['thumb']; ?>" height="200" class="img-responsive" alt="<?php echo $product['name'] ?>" />
											
										</a>
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
											<span class="price-new oct-price-new red"><?php echo $product['special']; ?></span>
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

								<div class="additional-info">
									<p class="oct-product-desc"><?php echo $product['description']; ?></p>
									<?php if (isset($product['oct_options']) && $product['oct_options']) { ?>
										<div class="cat-options">
											<?php foreach ($product['oct_options'] as $option) { ?>
												<?php if ($option['type'] == 'radio') { ?>
													<div class="form-group">
														<label class="control-label"><?php echo $option['name']; ?></label>
														<br/>
														<?php if ($option['product_option_value']) { ?>
															<?php foreach ($option['product_option_value'] as $product_option_value) { ?>
																<?php if ($product_option_value['image']) { ?>
																	<div class="radio">
																		<img src="<?php echo $product_option_value['image']; ?>" alt="<?php echo $product_option_value['name']; ?>" class="img-thumbnail" title="<?php echo $product_option_value['name']; ?>" />
																	</div>
																	<?php } else { ?>
																	<div class="radio">
																		<label class="not-selected"><?php echo $product_option_value['name']; ?></label>
																	</div>
																<?php } ?>
															<?php } ?>
														<?php } ?>
													</div>
													<?php } else { ?>
													<div class="form-group size-box">
														<label class="control-label"><?php echo $option['name']; ?></label>
														<br/>
														<?php if ($option['product_option_value']) { ?>
															<?php foreach ($option['product_option_value'] as $product_option_value) { ?>
																<div class="radio">
																	<label class="not-selected"><?php echo $product_option_value['name']; ?></label>
																</div>
															<?php } ?>
														<?php } ?>
													</div>
												<?php } ?>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if (isset($product['oct_attributes']) && $product['oct_attributes']) { ?>
										<div class="cat-options">
											<?php foreach ($product['oct_attributes'] as $attribute) { ?>
												<div class="form-group size-box">
													<label class="control-label"><?php echo $attribute['name']; ?></label>: <span><?php echo $attribute['text']; ?></span>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						
						<div class="pagination-wrap">
							<?php echo $pagination; ?>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<p class="text-left empty-text"><?php echo $text_empty; ?></p>
				<div class="buttons">
					<div class="text-left"><a href="<?php echo $continue; ?>" class="oct-button"><?php echo $button_continue; ?></a></div>
				</div>
			<?php } ?>
		</div>
		<?php echo $column_right; ?>
	</div>

</div>
<div class="container">
	<?php echo $content_bottom; ?>
</div>
<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
	<script>
		$(function() {
			setTimeout(function() {
				$("img.lazy").lazyload({
					effect : "fadeIn"
				});
			}, 10);
		});
	</script>
<?php } ?>



<script>
	<?php if ($products) { ?>	
		$(document).ready(function(){			
			
			if ((typeof fbq !== 'undefined')){
				<?php
					$contentIDString = "";
					foreach ($products as $product){
						$contentIDString .= "'" . (int)$product['product_id'] . "',";
					}
					$contentIDString = rtrim($content_ids_str, ',');
				?>
				
				fbq('track', 'ViewContent', 
				{
					content_type: 'product',
					content_ids: [<?php echo $contentIDString; ?>]
				});
			}
			
			<?php $chunkedProducts = array_chunk($products, 5); ?>			
			<?php $k = 0; $totalPrice = 0; foreach ($chunkedProducts as $key => $products) { ?>
				window.dataLayer = window.dataLayer || [];
				console.log('dataLayer.push impressions');
				dataLayer.push({
					'event': 'productImpression',
					'ecommerce': {
						'currencyCode': '<?php echo !empty($products[0]['ecommerceCurrency'])?$products[0]['ecommerceCurrency']:'UAH'; ?>',  
						'impressions':[
						<?php $i = 0; foreach ($products as $product) { ?>							
							{
								<?php foreach ($product['ecommerceData'] as $ecommerceKey => $ecommerceValue) { ?>
									'<?php echo $ecommerceKey; ?>': '<?php echo $ecommerceValue; ?>',
								<?php if ($ecommerceKey == 'price') { $totalPrice += $ecommerceValue; } } ?>
								'position': '<?php echo $k; ?>',
								<?php if (!empty($heading_title)) { ?>								
									'list': '<?php echo prepareEcommString($heading_title); ?>'
									<?php } else { ?>
									'list': 'Undefined Products List'
								<?php } ?>
							}<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
							<?php $i++; ?>
							<?php $k++; ?>
						<?php } ?>
						]
					}
				});	
				
				dataLayer.push({
					'event': 'view_item_list',
					'value': '<?php echo $totalPrice; ?>',
					'items': [
						<?php $i = 0; unset($product); foreach ($products as $product) { ?>
						{
							'id': '<?php echo $product['product_id']; ?>', 
      						'google_business_vertical': 'retail'
						}<?php if ($i < (count($products) - 1)) {?>,<?php } ?>
						<?php $i++; ?>
					<?php } ?>
					]				
				});
			<?php } ?>
		});
		<?php } ?>			
	</script>

<?php if(!isset($ajax_mf) || $ajax_mf == FALSE){ echo $footer; } ?>