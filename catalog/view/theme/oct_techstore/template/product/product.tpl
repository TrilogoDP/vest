<?php echo $header; ?>



<link rel="stylesheet" href="catalog/view/theme/oct_techstore/js/cloud-zoom/cloudzoom.css">
<link rel="stylesheet" href="catalog/view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.css">
<link rel="stylesheet" href="catalog/view/theme/oct_techstore/js/fancy-box/jquery.fancybox.min.css">
<link rel="stylesheet" href="catalog/view/theme/oct_techstore/js/toast/jquery.toast.css	">
<script src="catalog/view/theme/oct_techstore/js/fancy-box/jquery.fancybox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>
<script src="catalog/view/theme/oct_techstore/js/cloud-zoom/cloud-zoom.1.0.2.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="catalog/view/theme/oct_techstore/js/toast/jquery.toast.js"></script>
<style>
	#back-top .wraper{
		bottom: 80px !important;
	}
	.helpcrunch-iframe-wrapper iframe,
	iframe[name='helpcrunch-iframe']{
		bottom: 65px !important;
	}
	iframe.open{
		bottom: 0 !important;
	}
</style>
<div class="wrap fdc">
	<div class="breadcrumb-box">
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
				<?php if($count == 0) { ?>
					<li>
						<a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $text_home; ?>">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
							</svg>
							<?php echo $text_home; ?>
						</a>
					</li>
				<?php } elseif($count+1<count($breadcrumbs)) { ?>
					<li>
						<a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>">
							<?php echo $breadcrumb['text']; ?>
						</a>
					</li>					
				<?php } ?>

			<?php } ?>

		</ul>
		<div id="mobile-share-button" class="breadcrumb product-breadcrumb" style="display:none;">
			<i class="fa fa-share-alt" style="font-size:25px; color:#7cbc00 !important;"  onclick="share();" aria-hidden="true"></i>
		</div>
	</div>
	<div id="content" class="product-page">
		<div class="head-product">
			<?php if ($isMobile) { ?>
				<h1 class="product-header"><?php echo $heading_title; ?></h1>
				<div class="product-info__code product-info-li main-product-sku">
					<span class="code">
						<?php echo $text_sku; ?>
						<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
							<strong id="main-product-sku"><?php echo $sku; ?></strong>
						<?php } else { ?>
							<strong><?php echo $sku; ?></strong>
						<?php } ?>
					</span>
				</div>
			<?php } ?>
			<div class="left-info">
				<?php if ($thumb || $images) { ?>
					<div class="main-image">
						<div class="label_wrap">
							<?php if ($action_stickers) { ?>
								<div class="actions_stickers_container">
									<?php foreach ($action_stickers as $action_sticker) { ?>										
										<div class="actions_stickers_item">
											<span class="actions_stickers_name" style="<? if ($action_sticker['label_color']) { ?>
												color:<? echo $action_sticker['label_color']; ?>!important;
												<?php } ?> <? if ($action_sticker['label_bg']) { ?>
												background-color:<? echo $action_sticker['label_bg']; ?>!important;
												<?php } ?>">
												<? echo $action_sticker['label']; ?>
											</span>
										</div>									
									<?php } ?>	
								</div>
							<?php } ?>
							<?php if ($oct_product_stickers) { ?>
								<div class="product-sticker-box">
									<?php foreach ($oct_product_stickers as $product_sticker) { ?>
										<span style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;">
											<?php echo $product_sticker['text']; ?>
										</span>
									<?php } ?>
								</div>
							<?php } ?>
							<?php if ($special) { ?>
								<span class="main-product-you-save">-<?php echo $economy; ?>%</span>
							<?php } ?>
						</div>

						

						<?php if ($images) { ?>
							<?php if ($thumb) { ?>
								<div class="image thumbnails-one thumbnail">
									<a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" <?php if (!$check_zoom) { ?>onclick="$('.cloud-zoom-gallery').eq(0).click(); return false; "<?php } ?> data-fancybox="images" class='cloud-zoom' id='zoom1' data-index="0">
										<img src="<?php echo $thumb; ?>" height="600" title="<?php echo $heading_title; ?>" class="img-responsive" alt="<?php echo $heading_title; ?>" />
										<?php if ($tech_pr_micro == "on") { ?><meta itemprop="image" content="<?php echo $popup; ?>" /><?php } ?>
									</a>
								</div>
							<?php } ?>
						<?php } ?>

						<?php if (!empty($gift_teaser)) { ?>
							<div class="gift_teaser_container">
								<?php if ($gift_teaser['gifts']) { ?>			
									<?php foreach ($gift_teaser['gifts'] as $gift) { ?>		
										<div class="gift_teaser_item">
											<span class="label label-danger"><? echo $text_gift; ?></span>
											<img src="<? echo $gift['image']; ?>" />
											<span class="gift_teaser_name"><? echo $gift['name']; ?></span>
										</div>
									<?php } ?>
								<?php } ?>
							</div>	
						<?php } ?>

						

						<?php if (count($images) > 0) { ?>
							<script>
								function do_youtube_popup_view(e){
									console.log(e.attr('href'));
									var embedString = '//www.youtube.com/embed/' + e.attr('data-youtube-id') + '?autoplay=1';

									$.magnificPopup.open({
										items: {
											src: embedString
										},
										type: 'iframe',
										iframe: {
											patterns: {
												youtube: {
													index: 'youtube.com/',
													id: 'v=',
													src: `${embedString}?autoplay=1&rel=0`
												}
											}
										}
									});

									return false;
								}
							</script>
							<div class="image-additional img-<?php echo count($images) ?>" id="image-additional">
								<div class="thumbnails all-carousel">
									<?php $data_index = 0; foreach ($images as $image) { ?>
										<?php if ($image['video_in_product']) { ?>
											<?php if ($isMobile) { ?>
												<?php if ($image['thumb'] != $thumb_noimage) { ?>		

													<iframe class="thumbnail mfp-iframe" width="350" height="350" style="border: 0;" src="https://www.youtube.com/embed/<?php echo $image['video_in_product']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" >
													</iframe>

												<?php } else { ?>


													<iframe class="thumbnail mfp-iframe" width="350" height="350" style="border: 0;" src="https://www.youtube.com/embed/<?php echo $image['video_in_product']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" >
													</iframe>
													

												<?php } ?>	
											<?php } else { ?>
												<?php if ($image['thumb'] != $thumb_noimage) { ?>		

													<a class="thumbnail mfp-iframe" href="http://www.youtube.com/watch?v=<?php echo $image['video_in_product']; ?>"><span></span>
														<img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
													</a>

												<?php } else { ?>

													<a class="thumbnail mfp-iframe" href="http://www.youtube.com/watch?v=<?php echo $image['video_in_product']; ?>" data-youtube-id="<?php echo $image['video_in_product']; ?>" onclick="return do_youtube_popup_view($(this));"><span></span>
														<img height="<?php echo $image['video_in_product_height']; ?>px"width="<?php echo $image['video_in_product_width']; ?>px" style="height:<?php echo $image['video_in_product_height']; ?>px!important;" src="http://i4.ytimg.com/vi/<?php echo $image['video_in_product']; ?>/hqdefault.jpg" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />

													</a>

												<?php } ?>	
											<?php } ?>		
										<?php } else { ?>
											<?php if ($isMobile) { ?>
												<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" data-fancybox="images" data-index="<?php echo $data_index; ?>" data-main-img="<?php echo $image['main_img']; ?>" data-main-popup="<?php echo $image['main_popup']; ?>" class="thumbnail cloud-zoom-gallery <?php if ($data_index == 0) { ?>selected-thumb<?php } ?>" data-rel="useZoom: 'zoom1', smallImage: '<?php echo $image['popup']; ?>'">
													<?php if ($tech_pr_micro == "on") { ?><meta itemprop="image" content="<?php echo $image['popup']; ?>" /><?php } ?>
													<img src="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" width="350" height="350" class="" />
												</a>
											<?php } else { ?>
												<a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" data-fancybox="images" data-index="<?php echo $data_index; ?>" data-main-img="<?php echo $image['main_img']; ?>" data-main-popup="<?php echo $image['main_popup']; ?>" class="thumbnail cloud-zoom-gallery <?php if ($data_index == 0) { ?>selected-thumb<?php } ?>" data-rel="useZoom: 'zoom1', smallImage: '<?php echo $image['popup']; ?>'">
													<?php if ($tech_pr_micro == "on") { ?><meta itemprop="image" content="<?php echo $image['popup']; ?>" /><?php } ?>
													<img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" width="90" height="95" class="" />
												</a>
											<?php } ?>
											
										<? } ?>
										<?php $data_index++; } ?>
									</div>
								</div>
							<?php } ?>
							<script>
								$(function() {
									$("#image-additional .all-carousel").owlCarousel({
										loop:false,
										margin:30,
										nav:false,
										dots: true,
										navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>'],	
										responsive:{
											0:{
												items:1
											},
											600:{
												items:2,
												slideBy: 1,
											},
											750:{
												items:6,
												slideBy: 1,
											},
											1000:{
												items:4,
												slideBy: 1,
											},
											1400:{
												items:5,
												slideBy: 1,
											},
											1550:{
												items:6,
												slideBy: 6,
											}
										}
									});
								})
							</script>
						</div>
					<?php } ?>
				</div>
				<div class="right-info df fdc">
					<?php if (!$isMobile) { ?>
						<h1 class="product-header"><?php echo $heading_title; ?></h1>
					<?php } ?>
					
					<div class="head-product-info df aic">
						<div id="live-stock" class="stock <?php if ($outofstock) { ?>outofstock<? } ?>">
							<?php echo $stock; ?>
						</div>
						<div class="review-wrap df aic">
							<div class="product-rating-wrap">
								<?php for ($i = 1; $i <= 5; $i++) { ?>
									<?php if ($rating < $i) { ?>
										<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#d2d2d2"/>
										</svg>
									<?php } else { ?>
										<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M10.0489 0.927053C10.3483 0.00574231 11.6517 0.00573993 11.9511 0.927051L13.6942 6.29179C13.828 6.70382 14.212 6.98278 14.6452 6.98278H20.2861C21.2548 6.98278 21.6576 8.22239 20.8738 8.7918L16.3103 12.1074C15.9598 12.362 15.8132 12.8134 15.947 13.2254L17.6902 18.5902C17.9895 19.5115 16.935 20.2776 16.1513 19.7082L11.5878 16.3926C11.2373 16.138 10.7627 16.138 10.4122 16.3926L5.84869 19.7082C5.06498 20.2776 4.0105 19.5115 4.30985 18.5902L6.05296 13.2254C6.18683 12.8134 6.04018 12.362 5.68969 12.1074L1.12616 8.7918C0.342451 8.22239 0.745225 6.98278 1.71395 6.98278H7.35477C7.788 6.98278 8.17196 6.70382 8.30583 6.2918L10.0489 0.927053Z" fill="#FDB953"/>
										</svg>
									<?php } ?>
								<?php } ?>
							</div>
							<button id="btn_reviews_header" onclick="openTab(event, 'reviews'); scrollToReviews()"><?php echo $oct_text_review; ?></button>
						</div>
					</div>
					<div id="product">
						<?php if ($manufacturer) { ?>
							<div itemprop="brand" itemtype="http://schema.org/Brand" itemscope style="display: none;">
								<meta itemprop="name" content="<?php echo $manufacturer; ?>" />
							</div>
						<?php } ?>
						<div class="option-wrap">
							<?php if ($options) { ?>
								<div class="option">
									<?php foreach ($options as $option) { ?>
										<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status'] && $oct_advanced_options_settings_data['quantity_status'] && $option['type'] == 'oct_quantity') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li oct-quantity-div">
												<label class="control-label"><?php echo $option['name']; ?></label>
												<div class="table-responsive" id="input-option<?php echo $option['product_option_id']; ?>">
													<table class="table">
														<thead>
															<tr>
																<?php if ($oct_advanced_options_settings_data['allow_column_q_image']) { ?>
																	<td class="oct-col-option-image"><?php echo $text_col_option_image; ?></td>
																<?php } ?>
																<td><?php echo $text_col_option_name; ?></td>
																<?php if ($oct_advanced_options_settings_data['allow_column_q_sku']) { ?>
																	<td class="oct-col-sku"><?php echo $text_col_option_sku; ?></td>
																<?php } ?>
																<?php if ($oct_advanced_options_settings_data['allow_column_q_model']) { ?>
																	<td class="oct-col-model"><?php echo $text_col_option_model; ?></td>
																<?php } ?>
																<td><?php echo $text_col_option_price; ?></td>
																<td><?php echo $text_col_option_quantity; ?></td>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($option['product_option_value'] as $option_value) { ?>
																<tr>
																	<?php if ($oct_advanced_options_settings_data['allow_column_q_image']) { ?>
																		<td class="text-left oct-col-option-image" style="vertical-align: middle;"><img src="<?php echo $option_value['o_v_image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /></td>
																	<?php } ?>
																	<td class="text-left" style="vertical-align: middle;"><?php echo $option_value['name']; ?></td>
																	<?php if ($oct_advanced_options_settings_data['allow_column_q_sku']) { ?>
																		<td class="text-left oct-col-sku" style="vertical-align: middle;"><?php echo $option_value['sku']; ?></td>
																	<?php } ?>
																	<?php if ($oct_advanced_options_settings_data['allow_column_q_model']) { ?>
																		<td class="text-left oct-col-model" style="vertical-align: middle;"><?php echo $option_value['model']; ?></td>
																	<?php } ?>
																	<td class="text-left" style="vertical-align: middle;"><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?></td>
																	<td class="text-left oct-input-td" style="vertical-align: middle;">
																		<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" style="display: none !important; visibility: hidden !important;" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" />
																		<button class="oct-button opt-oct-button left-opt-button" type="button" onclick="oct_option_quantity_minus(this,'<?php echo $option_value['product_option_value_id']; ?>');"><i class="fa fa-minus" aria-hidden="true"></i></button>
																		<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
																			<input type="text" value="0" size="2" onchange="oct_option_quantity_manual(this,'<?php echo $option_value['product_option_value_id']; ?>'); return validate(this);" onkeyup="oct_option_quantity_manual(this,'<?php echo $option_value['product_option_value_id']; ?>'); return validate(this);" class="oct-quantity-text-input form-control"   />
																		<?php } else { ?>
																			<input type="text" value="0" size="2" onchange="oct_option_quantity_manual(this,'<?php echo $option_value['product_option_value_id']; ?>'); return validate(this);" onkeyup="oct_option_quantity_manual(this,'<?php echo $option_value['product_option_value_id']; ?>'); return validate(this);" class="oct-quantity-text-input"  />
																		<?php } ?>
																		<button class="oct-button opt-oct-button right-opt-button" type="button" onclick="oct_option_quantity_plus(this,'<?php echo $option_value['product_option_value_id']; ?>');"><i class="fa fa-plus" aria-hidden="true"></i></button>
																	</td>
																</tr>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
										<?php } ?>



										<?php if ($option['type'] == 'select') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
												<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
												<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
													<select onchange="oct_update_prices_opt();" name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
													<?php } else { ?>
														<select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
														<?php } ?>
														<option value=""><?php echo $text_select; ?></option>
														<?php foreach ($option['product_option_value'] as $option_value) { ?>
															<option value="<?php echo $option_value['product_option_value_id']; ?>" <?php if (!$option_value['quantity_status']) { ?>disabled="disabled"<?php } ?> data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>"><?php echo $option_value['name']; ?>
															<?php if ($option_value['price']) { ?>
																(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
															<?php } ?>
														</option>
													<?php } ?>
												</select>
											</div>
										<?php } ?>




										<!-- TEST end -->



















										<?php if ($option['type'] == 'radio') { ?>
											<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li list-li">
												<span class="control-label options-header"><?php echo $option['name']; ?></span>

												<div class="options-box" id="input-option<?php echo $option['product_option_id']; ?>">
													<?php foreach ($option['product_option_value'] as $option_value) { ?>
														<?php if ($option_value['image']) { ?>
															<div class="radio radio-img">

																<?php if ($option_value['quantity_status']) { ?>
																	<label class="<?php if ($option_value['selected']) { ?>selected-img<?php } else { ?>not-selected-img<?php } ?> <?php if ($option['big_option_images']) { ?>big-img-thumbnail<?php } ?> optid-<?php echo $option['option_id'];?>" data-toggle="tooltip" title="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>">
																	<?php } else { ?>
																		<!-- <label class="not-selected-img optid-<?php echo $option['option_id'];?> no_quantity_status" data-toggle="tooltip" title="<?php echo $option_value['name']; ?>: <?php echo mb_strtolower($text_oct_option_disable); ?>"> -->
																			<label class="optid-<?php echo $option['option_id'];?> no_quantity_status <?php if ($option_value['selected']) { ?>selected-img<?php } else { ?>not-selected-img<?php } ?> <?php if ($option['big_option_images']) { ?>big-img-thumbnail<?php } ?> optid-<?php echo $option['option_id'];?>" data-toggle="tooltip" title="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>">
																			<?php } ?>

																			<?php if ($option_value['quantity_status']) { ?>
																				<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>

																					<input onchange="oct_update_prices_opt();" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" class="none" <?php if ($option_value['selected']) { ?>checked="checked"<?php } ?> />
																				<?php } else { ?>
																					<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" class="none" />
																				<?php } ?>
																				<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail <?php if ($option['big_option_images']) { ?>big-img-thumbnail<?php } ?>" <?php if ($option['big_option_images']) { ?>style="height:50px;width:50px;"<?php } ?> />
																			<?php } else { ?>
																				<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" class="none"  />
																				<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
																			<?php } ?>
																		</label>
																	</div>

																<?php } else if ($option_value['quantity_status']) { ?>
																	<div class="radio oct-product-radio radio-img">
																		<?php if ($option_value['quantity_status']) { ?>
																			<label class="optid-<?php echo $option['option_id'];?>" data-toggle="tooltip">
																			<?php } else { ?>
																				<label class="optid-<?php echo $option['option_id'];?>" data-toggle="tooltip" title="<?php echo $text_oct_option_disable; ?>" style="opacity:0.5;cursor:not-allowed;">
																				<?php } ?>

																				<?php if ($option_value['quantity_status']) { ?>
																					<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>

																						<input onchange="oct_update_prices_opt();" type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" class="product-input-radio none" />
																					<?php } else { ?>
																						<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" class="product-input-radio none" />
																					<?php } ?>
																				<?php } else { ?>
																					<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" class="product-input-radio none"  />
																				<?php } ?>

																				<?php echo $option_value['name']; ?>
																			</label>
																			<?php if ($option_value['price']) { ?>
																				(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
																			<?php } ?>
																		</div>
																	<?php } ?>
																<?php } ?>
															</div>
														</div>
													<?php } ?>





													<?php if ($option['type'] == 'checkbox') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li list-li">
															<label class="control-label"><?php echo $option['name']; ?></label>
															<div class="options-box" id="input-option<?php echo $option['product_option_id']; ?>">
																<?php foreach ($option['product_option_value'] as $option_value) { ?>
																	<div class="checkbox">
																		<label <?php if (!$option_value['quantity_status']) { ?>style="opacity:0.5;cursor:not-allowed;" title="<?php echo $text_oct_option_disable; ?>"<?php } ?>>
																			<?php if ($option_value['quantity_status']) { ?>
																				<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
																					<input onchange="oct_update_prices_opt();" type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" />
																				<?php } else { ?>
																					<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" data-option-sku="<?php echo $option_value['sku']; ?>" data-option-model="<?php echo $option_value['model']; ?>" />
																				<?php } ?>
																			<?php } else { ?>
																				<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" disabled="disabled" />
																			<?php } ?>
																			<?php if ($option_value['image']) { ?>
																				<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
																			<?php } ?>
																			<?php echo $option_value['name']; ?>
																			<?php if ($option_value['price']) { ?>
																				(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
																			<?php } ?>
																		</label>
																	</div>
																<?php } ?>
															</div>
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'text') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
															<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'textarea') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
															<textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'file') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label"><?php echo $option['name']; ?></label>
															<button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
															<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'date') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
															<div class="input-group date">
																<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																</span>
															</div>
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'datetime') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
															<div class="input-group datetime">
																<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span>
															</div>
														</div>
													<?php } ?>
													<?php if ($option['type'] == 'time') { ?>
														<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
															<label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
															<div class="input-group time">
																<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span>
															</div>
														</div>
													<?php } ?>





















												<?php } ?>
											</div>
										<?php } ?>

										<?php if (!$isMobile) { ?>
											<div class="product-info__code product-info-li main-product-sku">
												<span class="code">
													<?php echo $text_sku; ?>
													<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
														<strong id="main-product-sku"><?php echo $sku; ?></strong>
													<?php } else { ?>
														<strong><?php echo $sku; ?></strong>
													<?php } ?>
												</span>
											</div>
										<?php } ?>
									</div>
									<div class="order-wrap hr-bottom">
										<?php if(!$archive){ ?>
											<div class="product-price">
												<?php if ($price) { ?>
													<div class="row">												
														<div class="col-md-4 col-sm-12 price-col"<?php if ($tech_pr_micro == "on") { ?> itemprop="offers" itemscope itemtype="https://schema.org/Offer"<?php } ?>>
															<?php if ($tech_pr_micro == "on") {  ?>
																<?php if ($disable_buy == 0) {
																	$stockinfo = "InStock";
																} elseif ($disable_buy == 2) {
																	$stockinfo = "OutOfStock";
																} else {
																	$stockinfo = "PreOrder";
																}  ?>
																<span itemprop="availability" class="micro-availability" content="https://schema.org/<?php echo $stockinfo; ?>"><?php echo $stock."\r\n"; ?></span>
																<meta itemprop="priceCurrency" content="<?php echo $oct_tech_currency_code_data; ?>" />
																<meta itemprop="priceValidUntil" content="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" />
																<?php
																$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
																echo '<meta itemprop="url" content="' . $actual_link . '" />';
																?>
																<span class="micro-price" itemprop="price" content="<?php if (!$special) { echo preg_replace('/[^0-9,.]+/','',rtrim($price, "."));}else{echo preg_replace('/[^0-9,.]+/','',rtrim($special, "."));} ?>"><?php if (!$special) { echo preg_replace('/[^0-9,.]+/','',rtrim($price, "."));}else{echo preg_replace('/[^0-9,.]+/','',rtrim($special, "."));} ?></span>
															<?php } ?>

															<?php if ($tax) { ?>
																<p><?php echo $text_tax; ?> <span id="main-product-tax"><?php echo $tax; ?></span></p>
															<?php } ?>
															<?php if ($points) { ?>
																<p><?php echo $text_points; ?> <?php echo $points; ?></p>
															<?php } ?>
															<?php if ($discounts) { ?>
																<?php foreach ($discounts as $discount) { ?>
																	<p><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></p>
																<?php } ?>
															<?php } ?>
														</div>
														<?php if (isset($oct_popup_found_cheaper_data['status']) && $oct_popup_found_cheaper_data['status']) { ?>
															<div class="col-lg-5 col-md-4 col-sm-6">
																<div class="found-cheaper">
																	<a href="javascript:void(0);" onclick="get_oct_popup_found_cheaper('<?php echo $product_id; ?>');"><?php echo $text_oct_popup_found_cheaper; ?></a>
																</div>
															</div>
														<?php } ?>											
													</div>
												<?php } ?>
												<?php if ($minimum > 1 && !$archive) { ?>
													<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
													<input type="hidden" id="minimumval" value="<?php echo $minimum; ?>">
												<?php } ?>
												<?php if ($reward) { ?>
													<span class="oct-reward"><?php echo $text_reward; ?> <?php echo $reward; ?></span>
												<?php } ?>
												<?php if (!$special) { ?>
													<span id="main-product-price" class="oct-price-new"><?php echo $price; ?></span>
												<?php } else { ?>
													<span id="main-product-special" class="oct-price-new"><?php echo $special; ?></span>
													<span id="main-product-price" class="oct-price-old"><?php echo $price; ?></span>
												<?php } ?>
												<?php if ($minimum > 1) { ?>
													<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
													<input type="hidden" id="minimumval" value="<?php echo $minimum; ?>">
												<?php } ?>
												<?php if ($tax) { ?>
													<p><?php echo $text_tax; ?> <span id="main-product-tax"><?php echo $tax; ?></span></p>
												<?php } ?>
											</div>
											<?php if ($isMobile) { ?>
												<a href="javascript:void(0);" rel="noindex nofollow" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="oct-button button-cart-mobile"  <?php if ($outofstock) { ?>style="display:none"<?php } ?>>
													<?php echo $button_cart; ?>
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
													</svg>
												</a>
											<?php } ?>
											<div id="buttons-buy-section" class="buttons-buy-section_wrap" <?php if ($outofstock) { ?>style="display:none"<?php } ?>>
												<?php if (!$isMobile) { ?>
													<a href="javascript:void(0);" rel="noindex nofollow" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="oct-button">
														<?php echo $button_cart; ?>
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
														</svg>
													</a>
												<?php } ?>

												<a href="javascript:void(0);" class="buy-one-click" <?php if ($outofstock) { ?>style="display:none"<?php } ?>><?php echo $text_buy_one_click; ?></a>			
												<a href="javascript:void(0);" data-toggle="tooltip" class="oct-button button-wishlist" title="<?php echo $button_wishlist; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('<?php echo $product_id; ?>');">
													<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="#002C3E" fill-opacity="0.2"/>
													</svg>
												</a>
												<a href="javascript:void(0);" data-toggle="tooltip" class="oct-button button-compare" title="<?php echo $button_compare; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('<?php echo $product_id; ?>');">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="#002C3E" fill-opacity="0.2"/>
													</svg>
												</a>
											</div>	
											
										<?php } else { ?>
											<div class="archive_product">
												<?php echo $text_archive; ?>
											</div>
										<?php } ?>
									</div>
									<?php if(!$archive){ ?>
										<div id="preorder-row" <?php if (!$outofstock) { ?>style="display:none;"<? } ?>>											

											<div class="popup-form-box " id="preorder-form">
												<div class="preorder-row-container">

													<input type="tel" name="telephone" id="preorder_telephone" placeholder="<?php echo $enter_telephone; ?>" value="<?php echo $fastorder_telephone; ?>" required />
													<button rel="noindex nofollow" id="button-cart-mf" class="btn oct-button" onClick="recall();return false;">						
														<span class="fa fa-shopping-cart product-icon">&nbsp;</span>
														<span><?php echo $text_mf_stock; ?></span>
													</button>
												</div>
												<script type="text/javascript">
													function recall(){
														let telephone = $("#preorder_telephone").val();																	
														if(telephone.length > 0){
															masked('#preorder_telephone', true);
															$("#preorder_telephone").css("border","1px solid #d3d3d3");
															$.ajax({
																type: 'POST',
																dataType: 'json',
																url: '/index.php?route=extension/module/oct_product_preorder/send',
																data: { 
																	'radio': $('#product input[type=\'radio\']:checked').serialize(),
																	'checkbox': $('#product input[type=\'checkbox\']:checked').serialize(),
																	'product_id': '<?php echo $product_id; ?>',
																	'telephone': telephone
																},
																success: function(json){
																	if (json['error']) {
																		masked('#preorder_telephone', false);
																		$('#preorder-form .text-danger').remove();

																		if (json['error']['field']) {
																			$.each(json['error']['field'], function(i, val) {
																				$('#preorder-form [name="' + i + '"]').addClass('error_style').after('<div class="text-danger" style="width:100%">' + val + '</div>');
																			});
																		}

																	} else {
																		if (json['output']) {
																			masked('#preorder_telephone', false);
																			$('#button-cart-mf').remove();
																			$('#preorder-form').html(json['output']);
																		}

																	}
																}
															});						
														}else{
															$("#preorder_telephone").css("border","1px solid #F00");
														}
													}
												</script>																
											</div>
										</div>	





										<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
											<div class="number" style="display:none;">
												<input name="product_id" value="<?php echo $product_id; ?>" style="display: none;" type="hidden">
												<div class="frame-change-count">
													<div class="btn-minus">
														<button type="button" id="superminus" onclick="$(this).parent().next().val(~~$(this).parent().next().val()-1); oct_update_product_quantity('<?php echo $product_id; ?>');">
															<span class="icon-minus"><i class="fa fa-minus"></i></span>
														</button>
													</div>
													<input type="text" name="quantity" value="<?php echo $minimum; ?>" size="8" class="plus-minus" id="input-quantity" onchange="oct_update_prices_opt(); return validate(this);" onkeyup="oct_update_prices_opt(); return validate(this);">
													<div class="btn-plus">
														<button type="button" id="superplus" onclick="$(this).parent().prev().val(~~$(this).parent().prev().val()+1); oct_update_product_quantity('<?php echo $product_id; ?>');">
															<span class="icon-plus"><i class="fa fa-plus"></i></span>
														</button>
													</div>
												</div>
											</div>
										<?php } else { ?>
											<div class="number <?php if($outofstock){ echo 'hidden'; }?>">
												<input name="product_id" value="<?php echo $product_id; ?>" style="display: none;" type="hidden">
												<div class="frame-change-count">
													<div class="btn-minus">
														<button type="button" id="superminus" onclick="$(this).parent().next().val(~~$(this).parent().next().val()-1);">
															<span class="icon-minus"><i class="fa fa-minus"></i></span>
														</button>
													</div>
													<input type="text" name="quantity" value="<?php echo $minimum; ?>" size="8" class="plus-minus" id="input-quantity" onchange="return validate(this);" onkeyup="return validate(this);">
													<div class="btn-plus">
														<button type="button" id="superplus" onclick="$(this).parent().prev().val(~~$(this).parent().prev().val()+1);">
															<span class="icon-plus"><i class="fa fa-plus"></i></span>
														</button>
													</div>
												</div>
											</div>
										<?php } ?>
									<?php } ?>






									<?php if ($current_action) { ?>
										<div class="after-header-row">
											<div class="action-alert">
												<p ><?php echo $text_action; ?> <?php echo $current_action['title']; ?></p>

												<?php if ($current_action['date_end']) { ?>
														
													<div class="promotion-days-left">
														<?php echo $current_action['date_text']; ?>
															
														</div>
													
												<? } ?>
												<div class="">
													<a href="<?php echo $current_action['href']; ?>" title="<?php echo $current_action['title']; ?>"><?php echo $button_show_more; ?></a>
												</div>
											</div>
										</div>

									<?php } ?>
									<?php if (!$outofstock) { ?>
										<?php if ($current_special['date_text']) { ?>					
											<div class="promotion-days 1"><?php echo $current_special['date_text']; ?></div>
										<? } ?>
									<? } ?>

									<?php if ($products && !$outofstock) { ?>	
										<section id="buy-togeter-products" class="hr-bottom" name="buy-togeter-products">										
											<div class="buy-togeter-products-head">
												<h4><?php echo $text_related; ?></h4>
											</div>
											<div id="oct-addons" class="buy-togeter-products-content">
												<?php foreach ($products as $addon) { ?>
													<div class="item" data-product-id="<?php echo $addon['product_id']; ?>" data-sort-order="<?php echo $addon['sort_order']; ?>">
														<div class="pretty">
															<?php if ($isTablet) { ?>
																23
																<?php } ?>
															<input type="checkbox" name="product_addon[]" value="<?php echo $addon['product_id']; ?>" />						
														</div>
														<div class="about">
															<div class="img">
																<img src="<?php echo $addon['thumb_addon']; ?>" title="<?php echo $addon['name']; ?>" alt="<?php echo $addon['name']; ?>" width="50" height="50" class="img-responsive" />
															</div>
															<?php if ($isTablet) { ?>
																<a href="<?php echo $addon['href']; ?>" title="<?php echo $addon['name']; ?>">
																	<?php echo $addon['name']; ?>
																</a>
															<?php } else if( $isPC) {?>
																<a href="<?php echo $addon['href']; ?>" title="<?php echo $addon['name']; ?>">
																	<?php echo $addon['name']; ?>
																</a>
															<?php } ?>

														</div>
														<div class="price">
															<?php if ($addon['special']) { ?>
																<span id="main-product-special" class="oct-price-new red"><?php echo $addon['special']; ?></span>
																<span id="main-product-price" class="oct-price-old"><?php echo $addon['price']; ?></span>
															<?php } else { ?>
																<span id="main-product-price" class="oct-price-new"><?php echo $addon['price']; ?></span>
															<?php } ?>
														</div>
														<?php if ($isMobile && !$isTablet) { ?>
															<div class="name">
																<a href="<?php echo $addon['href']; ?>" title="<?php echo $addon['name']; ?>">
																	<?php echo $addon['name']; ?>
																</a>
															</div>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
										</section>
									<?php } ?>

									<?php if (false && $free_delivery) { ?>
										<div class="free-delivery-text">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> <?php echo $free_delivery_text; ?>
										</div>
									<?php } ?>


									<?php if ($garanted_text) { ?>
										<div class="product-advantages-box hr-bottom">

											<div class="product-advantages-item">
												<a href="<?php echo $delivery_link; ?>" target="_blank" id="open-popup-garanted-delivery">
													<svg width="31" height="35" viewBox="0 0 41 35" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M0 7.51483V15.0297H12.0518H24.1035V7.51483V0H21.1006H18.0977V6.26928C18.0977 9.71738 18.088 12.5386 18.0761 12.5386C18.0643 12.5386 16.9832 12.1678 15.6738 11.7146L13.293 10.8906L10.9122 11.7146C9.60273 12.1678 8.50366 12.5386 8.46978 12.5386C8.43591 12.5386 8.4082 9.71738 8.4082 6.26928V0H4.2041H0V7.51483ZM10.8906 4.5255C10.8906 7.01453 10.9163 9.05101 10.9478 9.05101C10.9792 9.05101 11.5197 8.87447 12.149 8.65874L13.293 8.26648L14.437 8.65874C15.0662 8.87447 15.6067 9.05101 15.6381 9.05101C15.6696 9.05101 15.6953 7.01453 15.6953 4.5255V0H13.293H10.8906V4.5255ZM6.00586 5.06524V6.31079H4.80469H3.60352V5.06524V3.81969H4.80469H6.00586V5.06524ZM26.5255 12.5178L26.5459 20.0534L33.7729 20.0746L41 20.0958V18.8503V17.605L35.5747 17.5836L30.1494 17.5623L30.1289 11.2722L30.1083 4.98221H28.3067H26.505L26.5255 12.5178ZM32.5918 10.0059V15.0297H36.7959H41L40.9986 12.2687C40.997 9.14924 40.9494 8.74028 40.4692 7.72865C40.1003 6.95142 39.1836 5.9691 38.4626 5.57858C37.5159 5.06574 36.9832 4.98221 34.661 4.98221H32.5918V10.0059ZM6.00586 11.293V12.5386H4.80469H3.60352V11.293V10.0474H4.80469H6.00586V11.293ZM0 18.8493V20.0949H12.0518H24.1035V18.8493V17.6038H12.0518H0V18.8493ZM0 26.8624V31.2218H1.7871H3.57413L3.68063 30.6662C4.02833 28.8525 5.49784 27.1801 7.32715 26.5163C7.90427 26.307 8.07099 26.2841 9.00879 26.2865C9.93481 26.2887 10.1206 26.3144 10.6904 26.5189C12.5111 27.1723 13.889 28.7324 14.3124 30.6198L14.4475 31.2218H20.5H26.5525L26.6876 30.6198C27.0859 28.8445 28.3736 27.3071 30.0293 26.6302C32.6347 25.5651 35.6494 26.7936 36.8705 29.418C37.0664 29.8392 37.2722 30.4174 37.3277 30.7028L37.4285 31.2218H39.2143H41V26.8624V22.503H20.5H0V26.8624ZM8.29753 28.8178C7.79168 28.9495 7.05512 29.4003 6.75339 29.7629C5.39695 31.3926 5.84058 33.7932 7.671 34.7276C8.16645 34.9805 8.26206 35 9.00711 35C9.70867 35 9.86667 34.9723 10.264 34.7791C12.6122 33.6376 12.6277 30.185 10.2896 29.0505C9.71892 28.7736 8.85496 28.6727 8.29753 28.8178ZM31.2902 28.8209C30.4584 29.0242 29.6557 29.6882 29.2805 30.4833C28.5185 32.0981 29.1688 34.0173 30.736 34.7791C31.1333 34.9723 31.2913 35 31.9929 35C32.7379 35 32.8336 34.9805 33.329 34.7276C34.8361 33.9582 35.4779 32.0377 34.7356 30.5181C34.0865 29.189 32.6597 28.4862 31.2902 28.8209Z" fill="#6CBBB0"/>
													</svg>
													<span>
														<?php echo $delivery_text;?>
													</span>
												</a>
									<!-- <script>
										$(document).delegate('#open-popup-garanted-delivery', 'click', function(e) {
											e.preventDefault();

											var element = this;
											var History = window.History;

											$.ajax({
												url: $(element).attr('href') + '?info=1',
												type: 'get',
												dataType: 'json',
												success: function(json) {
													$.magnificPopup.open({
														tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
														items: {
															src:  '<div id="service-popup" class="white-popup mfp-with-anim wide-popup">'+
															'<h2 class="popup-header">' + json.title + '</h2>'+
															'<div class="popup-text service-popup-text">'+
															'<p>' + json.html + '</p>'+
															'</div>'+
															'</div>',
															type: 'inline'
														},
														showCloseBtn: true,
														midClick: true,
														removalDelay: 200,
														callbacks: {
															close: function () {
																$(window).unbind('statechange', closePopup).off('resize', closePopup);							
															}
														}
													});

												}
											});
										});

										function closePopup () {
											$.magnificPopup.close();
										}
									</script> -->
								</div>

								<?php foreach ($garanted_text as $garanted) { ?>
									<div class="product-advantages-item">
										<a href="<?php echo $garanted['link']; ?>" target="_blank"  <?php if ($garanted['popup'] == 'on' && $garanted['link'] && $garanted['link'] != '#') { ?>id="open-popup-garanted-<?php echo $garanted['id']; ?>"<?php } ?>>
											<?php if ($garanted['icon']) { ?>
												<i class="<?php echo $garanted['icon']; ?>" aria-hidden="true"></i>
											<?php } ?>
											<span>
												<!--//***mf begin -->
												<?php $garanted_text = ''; ?>
												<?php foreach ($attribute_groups as $attribute_group) { ?>
													<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
														<?php if(strpos(mb_strtolower($garanted['name']), "гарант") !== false && strpos(mb_strtolower($attribute['name']), "гарант") !== false){
															$garanted_text = $attribute['name'].':'.$attribute['text'];
														} ?>
													<?php } ?>
												<?php } ?>
												<?php if(strlen($garanted_text) > 0){
													echo $garanted_text;
												}else{
													echo $garanted['name'];
												} ?>
												<!--//***mf end -->					
											</span>
										</a>
										<!-- <?php if ($garanted['popup'] == 'on' && $garanted['link'] && $garanted['link'] != '#') { ?>
											<script>
												$(document).delegate('#open-popup-garanted-<?php echo $garanted['id']; ?>', 'click', function(e) {
													e.preventDefault();

													var element = this;
													var History = window.History;																																							

													$.ajax({
														url: $(element).attr('href') + '?info=1',
														type: 'get',
														dataType: 'json',
														success: function(json) {
															$.magnificPopup.open({
																tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
																items: {
																	src:  '<div id="service-popup" class="white-popup mfp-with-anim wide-popup">'+
																	'<h2 class="popup-header">' + json.title + '</h2>'+
																	'<div class="popup-text service-popup-text">'+
																	'<p>' + json.html + '</p>'+
																	'</div>'+
																	'</div>',
																	type: 'inline'
																},
																showCloseBtn: true,
																midClick: true,
																removalDelay: 200,
																callbacks: {
																	open: function () {
																								//	History.pushState({ url: document.location.href }, document.title, "?info");
																								//	History.Adapter.bind(window, 'statechange', closePopup);
																							},
																							close: function () {
																								$(window).unbind('statechange', closePopup).off('resize', closePopup);
																								//	var State = History.getState();
																								//	History.replaceState(null, document.title, State.data["url"]);
																							}
																						}
																					});

														}
													});
												});

												function closePopup () {																					
													$.magnificPopup.close();
												}
											</script>
											<?php } ?> -->
										</div>
									<?php } ?>
								</div>              	

								<div class="share-buttons">

									<a class="share-btn-fb" rel="noindex nofollow" href="https://www.facebook.com/sharer.php?u=<?php echo $share; ?>&t=<?php echo $share_txt_with_name; ?>" target="_blank" onclick="return Share.me(this);">
										<div class="facebook share-block">
											<svg width="12" height="23" viewBox="0 0 12 23" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M7.65708 12.3648H11.3793L11.9637 8.56055H7.65632V6.48134C7.65632 4.90099 8.16957 3.49962 9.63891 3.49962H12V0.179741C11.5852 0.123382 10.7078 0 9.04996 0C5.58819 0 3.55867 1.8393 3.55867 6.0297V8.56055H0V12.3648H3.55867V22.821C4.26344 22.9276 4.97729 23 5.71007 23C6.37245 23 7.01893 22.9391 7.65708 22.8522V12.3648Z" fill="white"/>
											</svg>
										</div>
									</a>

									<a class="share-btn-p" rel="noindex nofollow" href="https://www.pinterest.com/pin/create/bookmarklet/?url=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this);">
										<div class="pinterest share-block">
											<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M10 0C4.5 0 0 4.5 0 10C0 14.125 2.5 17.625 6 19.125C6 18.375 5.99999 17.625 6.12499 16.875C6.37499 16 7.37499 11.375 7.37499 11.375C7.37499 11.375 7.00001 10.75 7.00001 9.75C7.00001 8.25 7.87501 7.125 8.87501 7.125C9.75001 7.125 10.25 7.75 10.25 8.625C10.25 9.5 9.625 10.875 9.375 12.125C9.125 13.125 9.875 14 11 14C12.875 14 14.125 11.625 14.125 8.625C14.125 6.375 12.625 4.75 10 4.75C7 4.75 5.12501 7 5.12501 9.5C5.12501 10.375 5.37501 11 5.75001 11.5C5.87501 11.75 6 11.75 5.875 12C5.875 12.125 5.75 12.625 5.625 12.75C5.5 13 5.37501 13.125 5.12501 13C3.75001 12.375 3.125 10.875 3.125 9.125C3.125 6.25 5.5 2.875 10.25 2.875C14.125 2.875 16.625 5.625 16.625 8.625C16.625 12.5 14.5 15.5 11.25 15.5C10.125 15.5 9.125 14.875 8.75 14.25C8.75 14.25 8.12499 16.5 7.99999 17C7.74999 17.75 7.37501 18.5 7.00001 19.125C7.87501 19.375 8.87499 19.5 9.87499 19.5C15.375 19.5 19.875 15 19.875 9.5C20 4.5 15.5 0 10 0Z" fill="white"/>
											</svg>
										</div>
									</a>

									<a class="share-btn-tw" rel="noindex nofollow" href="https://twitter.com/intent/tweet?url=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this)">
										<div class="twitter share-block">
											<svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M19.6 1.89999C18.9 2.19999 18.1 2.4 17.3 2.5C18.1 2 18.8 1.2 19.1 0.300003C18.3 0.800003 17.5 1.1 16.5 1.3C15.8 0.500003 14.7 0 13.6 0C11.4 0 9.59999 1.8 9.59999 4C9.59999 4.3 9.6 4.59999 9.7 4.89999C6.4 4.69999 3.39999 3.1 1.39999 0.699997C1.09999 1.3 0.899994 2 0.899994 2.7C0.899994 4.1 1.6 5.3 2.7 6C2 6 1.39999 5.8 0.899994 5.5C0.899994 7.4 2.29999 9.09999 4.09999 9.39999C3.79999 9.49999 3.4 9.5 3 9.5C2.7 9.5 2.5 9.49999 2.2 9.39999C2.7 11 4.2 12.2 6 12.2C4.6 13.3 2.9 13.9 1 13.9C0.7 13.9 0.4 13.9 0 13.8C1.8 14.9 3.9 15.6 6.2 15.6C13.6 15.6 17.6 9.5 17.6 4.2V3.7C18.4 3.4 19.1 2.69999 19.6 1.89999Z" fill="white"/>
											</svg>
										</div>
									</a>																

									<a class="share-btn-eml" rel="noindex nofollow" href="mailto:?subject=<?php echo $share_txt_with_name; ?>&body=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this)">
										<div class="email share-block">
											<svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M1.55762 0.0627184C1.44762 0.097074 1.3221 0.138835 1.27865 0.15552C1.21829 0.178674 2.42514 1.41135 6.39824 5.38455C11.2265 10.2129 11.6111 10.5863 11.7977 10.6261C11.9212 10.6524 12.079 10.6518 12.2071 10.6245C12.4037 10.5825 12.7077 10.2874 17.5781 5.40869C20.4176 2.56439 22.7415 0.219451 22.7425 0.197656C22.7435 0.175862 22.6307 0.12243 22.492 0.0788882C22.2584 0.00558386 21.4806 -0.000227928 11.9987 6.42073e-06C3.59927 0.00024077 1.72167 0.0114898 1.55762 0.0627184ZM0.101614 1.46755L0 1.77703V8.43057V15.0841L0.101614 15.3936L0.203181 15.703L3.83943 12.0668L7.47564 8.43057L3.83943 4.79432L0.203181 1.15811L0.101614 1.46755ZM20.1529 4.79928L16.5217 8.43066L20.1585 12.0674L23.7953 15.7041L23.8976 15.3834L24 15.0626L23.9869 8.33683C23.9741 1.70204 23.9726 1.60802 23.879 1.38946L23.784 1.16791L20.1529 4.79928ZM4.83317 13.0417C2.83914 15.0358 1.22597 16.6785 1.24828 16.692C1.27064 16.7056 1.40347 16.7505 1.54352 16.7919C1.77379 16.86 2.77113 16.8671 11.9987 16.8671C21.2262 16.8671 22.2235 16.86 22.4538 16.7919C22.5939 16.7505 22.7267 16.7056 22.749 16.692C22.7714 16.6785 21.158 15.0356 19.1637 13.0412L15.5378 9.41507L14.4127 10.5344C13.7939 11.15 13.1927 11.7078 13.0767 11.7739C12.4736 12.1175 11.607 12.1261 10.9441 11.7949C10.8354 11.7407 10.2734 11.2207 9.60765 10.5586L8.45868 9.41591L4.83317 13.0417Z" fill="white"/>
											</svg>
										</div>
									</a>

									<a class="share-btn-viber" rel="noindex nofollow" href="viber://forward?text=" target="_blank" onclick="return ShareViber.me(this, '<?php echo $share_txt; ?>')">
										<div class="viber share-block">
											<svg width="21" height="23" viewBox="0 0 21 23" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M16.2539 0.545914C12.4424 -0.181971 8.48429 -0.181971 4.67277 0.545914C2.98691 0.909856 0.861257 2.94793 0.494764 4.54928C-0.164922 7.67919 -0.164922 10.8819 0.494764 14.0118C0.934555 15.6131 3.06021 17.6512 4.67277 18.0152C4.74607 18.0152 4.81937 18.0879 4.81937 18.1607V22.7464C4.81937 22.9648 5.11257 23.1103 5.25916 22.892L7.45812 20.6355C7.45812 20.6355 9.21728 18.8158 9.51047 18.5247C9.51047 18.5247 9.58377 18.4519 9.65707 18.4519C11.856 18.5247 14.1283 18.3063 16.3272 17.9424C18.0131 17.5784 20.1387 15.5403 20.5052 13.939C21.1649 10.8091 21.1649 7.6064 20.5052 4.47649C20.0654 2.94793 17.9398 0.909856 16.2539 0.545914ZM16.3272 14.2302C15.9607 14.958 15.5209 15.5403 14.788 15.9043C14.5681 15.9771 14.3482 16.0499 14.1283 16.1227C13.8351 16.0499 13.6152 15.9771 13.3953 15.9043C11.0497 14.958 8.85079 13.6478 7.09162 11.7553C6.13874 10.6635 5.33246 9.42611 4.67277 8.11592C4.37958 7.46082 4.08639 6.87851 3.86649 6.22342C3.6466 5.64111 4.01309 5.0588 4.37958 4.62207C4.74607 4.18534 5.18586 3.89418 5.69895 3.67582C6.06544 3.45745 6.43194 3.60303 6.72513 3.89418C7.31152 4.62207 7.8979 5.34995 8.3377 6.15063C8.63089 6.66015 8.55759 7.24245 8.0445 7.6064C7.8979 7.67919 7.82461 7.75198 7.67801 7.89755C7.60471 7.97034 7.45811 8.04313 7.38481 8.1887C7.23822 8.40707 7.23822 8.62544 7.31152 8.8438C7.8979 10.5179 8.99738 11.8281 10.6832 12.556C10.9764 12.7016 11.1963 12.7744 11.5628 12.7744C12.0759 12.7016 12.2958 12.1193 12.6623 11.8281C13.0288 11.537 13.4686 11.537 13.9084 11.7553C14.2749 11.9737 14.6414 12.2649 15.0811 12.556C15.4476 12.8472 15.8141 13.0655 16.1806 13.3567C16.4005 13.5023 16.4738 13.8662 16.3272 14.2302ZM13.2487 8.77101C13.1021 8.77101 13.1754 8.77101 13.2487 8.77101C12.9555 8.77101 12.8822 8.62544 12.8089 8.40707C12.8089 8.26149 12.8089 8.04313 12.7356 7.89755C12.6623 7.6064 12.5157 7.31524 12.2225 7.09688C12.0759 7.02409 11.9293 6.9513 11.7827 6.87851C11.5628 6.80572 11.4162 6.80572 11.1963 6.80572C10.9764 6.73294 10.9031 6.58736 10.9031 6.36899C10.9031 6.22342 11.123 6.07784 11.2696 6.07784C12.4424 6.15063 13.322 6.80572 13.4686 8.1887C13.4686 8.26149 13.4686 8.40707 13.4686 8.47986C13.4686 8.62544 13.3953 8.77101 13.2487 8.77101ZM12.5157 5.56832C12.1492 5.42274 11.7827 5.27717 11.3429 5.20438C11.1963 5.20438 10.9764 5.13159 10.8298 5.13159C10.6099 5.13159 10.4634 4.98601 10.5366 4.76765C10.5366 4.54928 10.6832 4.4037 10.9031 4.47649C11.6361 4.54928 12.2958 4.69486 12.9555 4.98601C14.2749 5.64111 15.0079 6.73293 15.2277 8.1887C15.2277 8.26149 15.2277 8.33428 15.2277 8.40707C15.2277 8.55265 15.2277 8.69822 15.2277 8.91659C15.2277 8.98938 15.2277 9.06217 15.2277 9.13496C15.1545 9.42611 14.6414 9.4989 14.5681 9.13496C14.5681 9.06217 14.4948 8.91659 14.4948 8.8438C14.4948 8.18871 14.3482 7.53361 14.055 6.9513C13.6152 6.2962 13.1021 5.85947 12.5157 5.56832ZM16.4738 9.93563C16.2539 9.93563 16.1073 9.71727 16.1073 9.4989C16.1073 9.06217 16.034 8.62543 15.9607 8.1887C15.6675 5.85947 13.7618 3.96697 11.4895 3.60303C11.123 3.53024 10.7565 3.53024 10.4634 3.45745C10.2435 3.45745 9.95026 3.45745 9.87696 3.1663C9.80366 2.94793 10.0236 2.72957 10.2435 2.72957C10.3168 2.72957 10.3901 2.72957 10.3901 2.72957C10.5367 2.72957 13.3953 2.80236 10.3901 2.72957C13.4686 2.80236 16.034 4.84044 16.5471 7.89755C16.6204 8.40707 16.6937 8.91659 16.6937 9.4989C16.8403 9.71727 16.6937 9.93563 16.4738 9.93563Z" fill="white"/>
											</svg>
										</div>
									</a>

									<a class="share-btn-telegram" rel="noindex nofollow" href="https://telegram.me/share/url?url=<?php echo $share; ?>&text=" target="_blank" onclick="return ShareViber.me(this, '<?php echo $text_look_at_this; ?>')">
										<div class="telegram share-block">
											<svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M19 0.602225L15.9946 16.2923C15.9946 16.2923 15.5741 17.3801 14.4189 16.8584L7.48458 11.3526L7.45242 11.3364C8.38909 10.4654 15.6524 3.70266 15.9698 3.39612C16.4613 2.92136 16.1562 2.63873 15.5856 2.99736L4.85679 10.053L0.717638 8.61077C0.717638 8.61077 0.0662573 8.37083 0.00359284 7.84911C-0.0598962 7.32653 0.739076 7.0439 0.739076 7.0439L17.6131 0.188948C17.6131 0.188948 19 -0.44207 19 0.602225Z" fill="white"/>
											</svg>
										</div>
									</a>

									<a class="share-btn-share" id="mobile-share-button-line" rel="noindex nofollow" href="#" target="_blank" onclick="share(); return false;">
										<div class="general-share share-block">
											<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M16.3968 0.0282352C16.2698 0.0505747 16.0464 0.108339 15.9002 0.156634C14.7504 0.536664 13.8783 1.32793 13.4345 2.39373C13.1974 2.96315 13.1614 3.16115 13.1648 3.87873C13.1675 4.44655 13.1816 4.56845 13.282 4.89007C13.3447 5.09117 13.3927 5.25732 13.3886 5.25926C13.3845 5.26115 11.948 6.10807 10.1964 7.14129C8.44484 8.17446 6.97818 9.03244 6.93721 9.04789C6.88425 9.06782 6.78383 9.0021 6.59018 8.82071C6.00702 8.27462 5.32138 7.94142 4.5197 7.81457C4.11656 7.75074 3.31321 7.7847 2.95398 7.88073C1.73906 8.20545 0.739076 9.06468 0.272711 10.1846C0.0389149 10.746 0 10.955 0 11.6481C0 12.3411 0.0389149 12.5501 0.272711 13.1115C0.739076 14.2314 1.73906 15.0907 2.95398 15.4154C3.31321 15.5114 4.11656 15.5454 4.5197 15.4815C5.4337 15.3369 6.44285 14.7792 6.93405 14.1473C7.02723 14.0274 7.12068 13.9294 7.14178 13.9294C7.17127 13.9294 13.2479 16.9647 13.3068 17.0089C13.3142 17.0144 13.2851 17.1422 13.2422 17.2927C13.1436 17.6387 13.1317 18.6534 13.2219 19.0085C13.5346 20.2384 14.4292 21.2645 15.5967 21.7324C16.1689 21.9618 16.3819 22 17.0883 22C17.7948 22 18.0077 21.9618 18.58 21.7324C19.5559 21.3413 20.3435 20.5685 20.7422 19.6111C20.976 19.0496 21.0149 18.8407 21.0149 18.1476C21.0149 17.4545 20.976 17.2456 20.7422 16.6841C20.2758 15.5642 19.2759 14.705 18.0609 14.3803C17.7017 14.2843 16.8984 14.2503 16.4952 14.3141C15.5474 14.4641 14.6813 14.9534 14.0402 15.7009L13.9219 15.8388L13.5638 15.6631C11.9645 14.879 7.72309 12.7193 7.7291 12.6922C7.92105 11.8273 7.91964 11.2469 7.72415 10.5985C7.66075 10.3883 7.62043 10.2049 7.63452 10.1911C7.6486 10.1773 9.1053 9.31175 10.8716 8.26778L14.0831 6.36965L14.4377 6.69527C14.9967 7.20861 15.6527 7.53531 16.4068 7.67597C16.8287 7.75466 17.6764 7.72857 18.0609 7.62505C19.2793 7.29702 20.2759 6.44045 20.7422 5.32068C20.976 4.75922 21.0149 4.55029 21.0149 3.8572C21.0149 3.16412 20.976 2.95519 20.7422 2.39373C20.2759 1.27404 19.2838 0.421135 18.0609 0.0887111C17.7625 0.00757431 16.7282 -0.0300026 16.3968 0.0282352Z" fill="white"/>
											</svg>
										</div>
									</a>
								</div>

								<script>
									ShareViber = {
										me : function(el, text){																		
											ShareViber.popup(el.href + encodeURIComponent(text));
											passEventToDataLayer('share', 'share', 'click', 'sharebutton');
											return false;
										},

										popup: function(url) {
											window.open(url,'','toolbar=0,status=0,width=626,height=436');
										}
									};

									Share = {
										me : function(el){																		
											Share.popup(el.href);
											passEventToDataLayer('share', 'share', 'click', 'sharebutton');
											return false;
										},

										popup: function(url) {
											window.open(url,'','toolbar=0,status=0,width=626,height=436');
										}
									};
								</script>


								<?php if ($oct_techstore_pr_social_button_script) { ?>
									<?php echo $oct_techstore_pr_social_button_script; ?>
								<?php } ?>

							<?php } ?>


						</div>
					</div>
				</div>
				<div class="about-product-wrap">
					<div class="tab">
						<?php if ($has_description) { ?>
							<button class="tablinks active" onclick="openTab(event, 'description')">
								<?php echo $tab_description; ?>
							</button>
						<?php } ?>
						<?php if ($attribute_groups || $ocfilters) { ?>
							<button class="tablinks <?php if(!$has_description) {?>active<?php }?>" onclick="openTab(event, 'characteristics')">
								<?php echo $tab_attribute; ?>
							</button>
						<?php } ?>
						<?php if ($review_status) { ?>
							<button id="reviews_content" class="tablinks" onclick="openTab(event, 'reviews')">
								<?php echo $tab_review; ?>
							</button>
						<?php } ?>
					</div>
					<?php if ($has_description) { ?>
						<div id="description" class="tabcontent" style="display: block;">
							<?php echo $description; ?>
						</div>
					<?php } ?>
					<?php if ($attribute_groups || $ocfilters) { ?>
						<div id="characteristics" class="tabcontent" style="<?php if(!$has_description) {?>display: block<?php }?>">
							<div class="table">
								<?php if ($attribute_groups) {?>
									<?php foreach ($attribute_groups as $attribute_group) { ?>
										<p class="head-td 1">
											<?php echo $attribute_group['name']; ?>
										</p>
										<div class="body-characteristics">
											<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
												<div  class="items">
													<div class="name"><?php echo $attribute['name']; ?></div>
													<div class="attr">
														<?php if (!empty($attribute['href'])) { ?>
															<a href="<?php echo $attribute['href']; ?>" title="<?php echo $attribute['title']; ?>"><?php echo $attribute['text']; ?></a>
														<?php } else { ?>
															<?php echo str_replace(PHP_EOL, '<br />', html_entity_decode($attribute['text'], ENT_QUOTES, 'UTF-8')); ?>
														<?php } ?>
													</div>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } ?>

								<?php if ($ocfilters) {?>
									<div class="body-characteristics">
										<?php foreach ($ocfilters as $ocfilter) { ?>
											<div  class="items">
												<div class="name"><?php echo $ocfilter[0]['name']; ?></div>
												<div class="attr">
													<?php $c = 1; $e = count($ocfilter); foreach ($ocfilter as $key => $value) { ?>
														<?php if (!empty($value['href'])) { ?>
															<a href="<?php echo $value['href']; ?>" title="<?php echo $value['title']; ?>"><?php echo trim($value['value']); ?></a><?php if ($c < $e) { ?>,<?php } $c++; ?>
														<?php } else { ?>
															<?php echo trim($value['value']); ?><?php if ($c < $e) { ?>,<?php } $c++; ?>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
							<div class="attribute_footer"><?php echo $attribute_footer; ?></div>
						</div>
					<?php } ?>
					<?php if ($review_status) { ?>
						<div id="reviews" class="tabcontent">
							<form class="form-horizontal" id="form-review">
								<div id="review"><?php echo $review; ?></div>
								<span class="title scrolled"><?php echo $text_write; ?> <?php echo $heading_title; ?></span>

								<?php if ($is_afp_session) { ?>
									<h3 class="text-success"><i class="fa fa-thumbs-up"></i> <?php echo $text_afp; ?></h3>
								<?php } ?>

								<?php if ($review_guest) { ?>
									<div class="form-group required full-width">
										<label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
										<input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" />
									</div>
									<?php if (isset($oct_product_reviews_data['status']) && $oct_product_reviews_data['status']) { ?>
										<div  class="two-column">
											<div class="form-group positive-text-box">
												<label class="control-label" for="input-positive_text"><?php echo $entry_positive_text; ?></label>
												<textarea  placeholder="<?php echo $entry_positive_text; ?>" name="positive_text" rows="4" id="input-positive_text" class="form-control"></textarea>
											</div>
											<div class="form-group negative-text-box">
												<label class="control-label" for="input-negative_text"><?php echo $entry_negative_text; ?></label>
												<textarea placeholder="<?php echo $entry_negative_text; ?>" name="negative_text" rows="4" id="input-negative_text" class="form-control"></textarea>
											</div>
										</div>
										<input type="hidden" name="where_bought" value="1" />
									<?php } ?>
									<div class="form-group required full-width">
										<label class="control-label" for="input-review"><?php echo $entry_review; ?></label>
										<textarea placeholder="<?php echo $entry_review; ?>" name="text" rows="5" id="input-review" class="form-control"></textarea>
										<div class="help-block"><?php echo $text_note; ?></div>
									</div>
									<div class="form-group required ratingme-wrap">
										<label><?php echo $entry_rating; ?></label>
										<div>
											<select id="ratingme" name="rating">
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5" selected>5</option>
											</select>
											<script>
												$(function() {
													$('#ratingme').barrating({
														theme: 'fontawesome-stars'
													});
												});
											</script>
										</div>
									</div>
									<?php echo $captcha; ?>
									<?php if ($text_terms) { ?>
										<div>
											<?php echo $text_terms; ?> <input type="checkbox" name="terms" value="1" style="width:auto;height:auto;display:inline-block;margin: 0;" />
										</div>
									<?php } ?>
									<div class="buttons">
										<button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn-green"><?php echo $oct_text_review; ?></button>
									</div>
								<?php } else { ?>
									<?php echo $text_login; ?>
								<?php } ?>
							</form>
						</div>
					<?php } ?>
				</div>
			</div>	
		</div>
		<?php if(!$archive){ ?>
			<?php if ($isMobile) { ?>
				<div id="fixed_price-mob" <?php if ($outofstock) { ?>style="display:none"<?php } ?>>
					<div class="price">
						<?php if (!$special) { ?>
							<span class="oct-price-new"><?php echo $price; ?></span>
						<?php } else { ?>
							<span class="oct-price-new"><?php echo $special; ?></span>
							<span class="oct-price-old"><?php echo $price; ?></span>
						<?php } ?>
					</div>
					<a rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product_id; ?>', '1');" data-loading-text="<?php echo $text_loading; ?>" class="oct-button button-cart-mobile"  <?php if ($outofstock) { ?>style="display:none"<?php } ?>>
						<?php echo $button_cart; ?>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.1949V2.3898L1.19797 2.40232L2.39594 2.41484L4.43062 6.7292C5.5497 9.10208 6.51561 11.153 6.57714 11.2867L6.68899 11.53L5.84962 12.9764C5.10245 14.264 4.99854 14.4689 4.90335 14.8428C4.65165 15.8311 4.85537 16.6529 5.51324 17.3036C5.65851 17.4473 5.9026 17.6319 6.05567 17.7138C6.62732 18.0199 6.1924 18.0044 14.226 18.0051L21.5199 18.0058V16.8101V15.6144H14.5748C6.95983 15.6144 7.42225 15.6318 7.42225 15.3462C7.42225 15.2791 7.65855 14.7747 7.94741 14.2254L8.47252 13.2268L13.1173 13.2127L17.7621 13.1985L18.0655 13.0943C18.6086 12.9076 19.0087 12.5863 19.2947 12.107C19.3824 11.96 20.3944 10.1306 21.5436 8.04166C22.6928 5.9527 23.6708 4.20135 23.717 4.14978C23.9214 3.92166 23.9561 3.52876 23.8073 3.12926C23.7012 2.84417 23.4954 2.62214 23.2237 2.49929C23.0463 2.41901 22.5893 2.41423 14.0257 2.40256L5.01436 2.39022L4.47944 1.19518L3.94453 9.37803e-05L1.97226 4.68901e-05L0 0V1.1949ZM6.44088 19.3317C5.18559 19.7616 4.50017 21.1108 4.90489 22.3552C5.16612 23.1586 5.95185 23.8269 6.80307 23.9699C8.0171 24.1738 9.24299 23.3205 9.50244 22.091C9.59048 21.6737 9.58605 21.4617 9.48073 21.048C9.26614 20.2051 8.63147 19.5454 7.79887 19.2997C7.42295 19.1888 6.81629 19.2031 6.44088 19.3317ZM18.3912 19.3317C17.1359 19.7616 16.4505 21.1108 16.8552 22.3552C17.1164 23.1586 17.9021 23.8269 18.7534 23.9699C19.9674 24.1738 21.1933 23.3205 21.4527 22.091C21.5408 21.6737 21.5363 21.4617 21.431 21.048C21.2164 20.2051 20.5818 19.5454 19.7492 19.2997C19.3732 19.1888 18.7666 19.2031 18.3912 19.3317Z" fill="white"/>
						</svg>
					</a>
				</div>
			<?php } ?>
		<?php } ?>
		<div id="oneclick-popup"  class="white-popup mfp-with-anim narrow-popup mfp-hide" style="display: none;">
			<form method="post" enctype="multipart/form-data" id="purchase-form" class="popup-form-box">
				<h2 class="popup-header"><?php echo $text_oct_popup_purchase; ?></h2>
				<input name="product_id" value="<?php echo $product_id; ?>" style="display: none;" type="hidden" />
				<input type="text" name="firstname" value="Quick Order" style="display: none;" type="hidden" >
				<input type="email" name="email" value="technik@vest.in.ua" style="display: none;" type="hidden" >
				<input type="tel" name="telephone" placeholder="<?php echo $enter_telephone; ?>" value="<?php echo $fastorder_telephone; ?>" class="">
				<div class="popup-buttons-box"><a class="popup-button" rel="noindex nofollow" id="popup-checkout-button"><?php echo $text_call_me; ?></a></div>					
			</form>
		</div>
		<?php echo $content_bottom; ?>	
		<?php $mask = '(999) 999-99-99'; if ($mask) { ?>
			<script>
				var isMobile = {
					Android: function() {
						return navigator.userAgent.match(/Android/i);
					},
					BlackBerry: function() {
						return navigator.userAgent.match(/BlackBerry/i);
					},
					iOS: function() {
						return navigator.userAgent.match(/iPhone|iPad|iPod/i);
					},
					Opera: function() {
						return navigator.userAgent.match(/Opera Mini/i);
					},
					Windows: function() {
						return navigator.userAgent.match(/IEMobile/i);
					},
					Chrome: function() {
						return navigator.userAgent.match(/Chrome/i);
					}
				};

		// if ( !isMobile.Android() ) {
		//   $("#oneclick-popup [name='telephone']").inputmask('<?php echo $mask; ?>');
		// } else {            
		//   $("#oneclick-popup [name='telephone']").inputmask('<?php echo $mask; ?>');
		//      }
			</script>
		<?php } ?>
		<script>
			$(document).ready(function() {
				$('.buy-one-click').on('click', function() {
					$('#oneclick-popup').addClass('popup-opened');	
						$.magnificPopup.open({
								tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
								midClick: !0,
								removalDelay: 200,
								items: {
									src: '#oneclick-popup',
								},
								type: 'inline',
								callbacks: {
									close: function() {
			                    $('#oneclick-popup').removeClass('popup-opened'); // Видаляємо клас при закритті попапа
			                }
			            }
			        });
				});
			});

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

			let clickCount = localStorage.getItem('buttonClickCount') || 0;

			$('#popup-checkout-button').on('click', function() {
				if (clickCount < 3) {
                    clickCount++;
                    localStorage.setItem('buttonClickCount', clickCount);
                    // alert('Вы нажимали на кнопку' + clickCount + 'раз!');
					$.ajax({
					type: 'post',
					url:  'index.php?route=extension/module/oct_popup_purchase/make_order',
					dataType: 'json',
					data: $('#purchase-form input[type=\'hidden\'], #purchase-form input,  #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select'),
					beforeSend: function(){
						masked('#oneclick-popup', true);
						$('#oneclick-popup #popup-checkout-button').attr('disabled', true);
					},
					success: function(json) {
						$('#oneclick-popup .alert, #oneclick-popup .text-danger').remove();


						if (json.error) {
							masked('#oneclick-popup', false);
							$('#oneclick-popup .text-danger').remove();

							if (json['error']['field']) {
								$.each(json['error']['field'], function(i, val) {
									$('#oneclick-popup [name="' + i + '"]').addClass('error_style').after('<div class="text-danger">' + val + '</div>');
								});
							}
							if (json['error']['option']) {
								for (i in json['error']['option']) {
									var element = $('#oneclick-popup #input-option' + i.replace('_', '-'));
									element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
								}
							}
							if (json['error']['recurring']) {
								$('#oneclick-popup select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
							}

							if (json['error']['quantity']) {
								$('#oneclick-popup .payment-quantity').after('<div class="text-danger">' + json['error']['quantity'] + '</div>');
							}
							$('#oneclick-popup #popup-checkout-button').attr('disabled', false);

						} else {
							if (json.output) {
								console.log(json.output);
								console.log($('#oneclick-popup #purchase-form'));
								masked('#oneclick-popup', false);
								$('#popup-checkout-button').remove();
								$('#oneclick-popup #purchase-form').html(json.output);
							}

							var date = new Date();
							var timestamp = date.getTime();
							var title = ($(".product-header").text());
							if($("#main-product-special").length) {
								var price = ($("#main-product-special").text());
								price = price.replace(" грн", "");
								price = price.replace(" ", "");
							} else{
								var price = ($("#main-product-price").text());
								price = price.replace(" грн", "");
								price = price.replace(" ", "");
							}
							if (typeof fbq != 'undefined'){
								fbq('track', 'Purchase', {value: price, currency: 'UAH', content_ids: ['<?php echo $product_id; ?>'], content_type: 'product'});
							}
							if (typeof ttq != 'undefined'){
								ttq.track('Checkout', {value: price, currency: 'UAH', content_id: '<?php echo $product_id; ?>', content_type: 'product'});
							}

							window.dataLayer = window.dataLayer || [];
							dataLayer.push({
								'event': 'purchase',
								'value': price,
								'items': [{
									'id': <?php echo $product_id; ?>, 
									'google_business_vertical': 'retail'
								}]
							});

							dataLayer.push({
								'event': 'fastOrderPurchaseSuccess',
								'ecommerce': {
									'currencyCode': 'UAH',  
									'customer':{
										email: 		json.customerData.email,
										telephone:  json.telephone,
										new: 		json.customerData.new,
										country: 	json.customerData.country
									},
									'purchase': {
										'id': json.order_id,                        
										'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
										'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
										'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',

										'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>',
										'actionField': {
											'id': '<? echo $google_ecommerce_info['transactionId'] ?>',                        
											'affiliation': '<? echo $google_ecommerce_info['transactionAffiliation'] ?>',
											'revenue': '<? echo $google_ecommerce_info['transactionTotal'] ?>',  
											'tax':'<? echo $google_ecommerce_info['transactionTax'] ?>',
											'shipping': '<? echo $google_ecommerce_info['transactionShipping'] ?>'
										},
										'products': [
											<?php $i=0; foreach ($google_ecommerce_info['transactionProducts'] as $transactionProduct) { ?>
												{
													'id' : '<? echo $transactionProduct['id']; ?>',
													'sku': '<? echo $transactionProduct['sku'] ?>',
													'name' : '<? echo $transactionProduct['name'] ?>',
													'brand' : '<? echo $transactionProduct['manufacturer'] ?>',
													'category' : "<? echo $transactionProduct['category'] ?>",
													'price' : '<? echo $transactionProduct['price'] ?>',
													'quantity' : '<? echo $transactionProduct['quantity'] ?>',
													'total' : '<? echo $transactionProduct['total'] ?>'
												}	<? if ($i < count($google_ecommerce_info['transactionProducts'])) { ?>,<?php } $i++; ?>
											<? } ?>

											]					
									}	
								}
							});
						}

					}
				});
                } else {
                    $(this).prop('disabled', true);
                    alert('Подозрительная активность!');
                }
});				  
</script>	
<script><!--
$('#button-cart-mobile, #button-cart-mobile-fixed').on('click', function() {
	$('#button-cart').trigger('click');
});
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add&oct_dirrect_add=1',
		type: 'post',
		data: $('#product input[type=\'text\']:not(\'.oct-quantity-text-input\'), #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		success: function(json) {	
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				$('.text-danger').parent().addClass('has-error');
			} else {
				if($("#main-product-special").length) {
					var price = ($("#main-product-special").text());
					price = price.replace(" грн", "");
					price = price.replace(" ", "");
				}else{
					var price = ($("#main-product-price").text());

					price = price.replace(" грн", "");
					price = price.replace(" ", "");
				}
				fbq('track', 'AddToCart', { content_name: "<?php echo $heading_title; ?>", content_ids: [<?php echo $product_id; ?>], content_type: 'product', value: price, currency: 'UAH'});
				ttq.track('AddToCart', { content_name: "<?php echo $heading_title; ?>", content_id: '<?php echo $product_id; ?>', content_type: 'product', value: price, currency: 'UAH'});

				passEcommerceToDataLayer('addToCart', <?php echo $product_id; ?>, 1, false);

			}
			if (json['success']) {
				console.log('success')
				get_oct_popup_cart();
				$("#cart-total").removeClass('hidden');
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
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log('error', thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
		//-->
</script>
<?php if ($isMobile) { ?>
 	<script>
	    $(document).ready(function() {

	      	var productHeight = $('#product').height();

	      	$(window).scroll(function() {
	        	if ($(this).scrollTop() > productHeight) {
	          		$('#fixed_price-mob').addClass('show');
	          		$('#cookie-consent').addClass('show_price');
	          		
        		} else {
	          		$('#fixed_price-mob').removeClass('show');
	          		$('#cookie-consent').removeClass('show_price');
	        	}
	      	});
	    });
	</script>
<?php } ?>
<script>
	$(document).ready(function() {
		var text = window.location.href;
		var regex = /#(\w+)/gi;
		var match = regex.exec(text);

		if (match && match[1] === 'reviews') {
			setTimeout(function() {
				var elmnt = document.getElementById("reviews_content");
				if (elmnt) {
					elmnt.scrollIntoView({
						behavior: 'smooth',
						block: 'start'
					});
					var clickEvent = new Event('click');
					elmnt.classList.add('active');
					elmnt.dispatchEvent(clickEvent);
				}
			}, 1000);
		}
	});
	function scrollToReviews() {
		var elmnt = document.getElementById("reviews");
		elmnt.scrollIntoView({ behavior: 'smooth', block: 'start'});
	}
	function share() {
		var text = "<?php echo $text_look_at_this; ?>";
		if ('share' in navigator) {
			navigator.share({
				title: document.title,
				text: text,
				url: location.href,
			});
			ga('send', 'event', 'share', 'click', 'sharebutton');
			window.dataLayer = window.dataLayer || [];			
		} else {					
		}
	}
	function openTab(evt, tabName) {
		console.log('evt', evt)
		var i, tabcontent, tablinks;

		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}

		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].classList.remove("active");
		}

		document.getElementById(tabName).style.display = "block";
		evt.currentTarget.classList.add("active");
	}

	
	function checkSku(){
		var sku_val =  $('#main-product-sku').html();

		console.log(sku_val);
		if(sku_val.length > 0){
			console.log(11);
			$(".main-product-sku").show();
		}else{
			console.log(22);
			$(".main-product-sku").hide();
		}
	}
	$(document).ready(function() {
		checkSku();
	}); 
</script>
			<script><!--


				// $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
				
				function copyCouponToClipboard(){
					let copyText = document.getElementById("#thankyou_afp_coupon_coupon");
					copyText.select();
					document.execCommand("copy");
				}
			
				$('#button-review').on('click', function() {
					$.ajax({
						url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
						type: 'post',
						dataType: 'json',
						data: $("#form-review").serialize(),
						beforeSend: function() {
							$('#button-review').button('loading');
						},
						complete: function() {
							$('#button-review').button('reset');
						},
						success: function(json) {
							$('.alert-success, .alert-danger').remove();
							
							if (json['error']) {
								setTimeout(function(){$.toast(json['error']);}, 0);
							}
							
							if (json['success']) {
								
								if (json['coupon']){
									
									let html = "<div class='white-popup mfp-with-anim middle-popup'>";
									html += "<div class='text-success text-center'><i class='fa fa-4x fa-thumbs-up'></i></div>";
									html += "<div class='text-center'><?php echo $text_afp_thank;?></div>";
									html += "<div class='text-center' id='thankyou_afp_coupon_copy' style='margin-top:30px; margin-bottom:30px;'>";
									html += "<input type='text' value='" + json['coupon'] + "' id='thankyou_afp_coupon' style='background: -webkit-linear-gradient(top,#f5f5f5 0,#fdfdfd 100%); border: 1px solid #ecedef; text-align:center; height: 40px; font-size:22px; padding:10px 10px' />"; 
									html += "<br /><button id='thankyou_afp_coupon_copy'  data-clipboard-action='copy' data-clipboard-target='#thankyou_afp_coupon' style='font-size:14px; margin-top:5px; padding:7px 10px; max-width:100%;'><i class='fa fa-copy'></i> <?php echo $text_copy; ?></button>";
									html += '</div>';
									html += "<div class='text-center' style='color:rgba(0,0,0,.4)'><?php echo $text_afp_alert; ?></code>";
									html += "<div class='clearfix'></div>";
									html += "<div class='popup-buttons-box text-center'>";
									html += "<a class='popup-button' onclick='$.magnificPopup.close();'><?php echo $btn_continue; ?></a>";
									html += "</div>";
									html += "</div>"
									
									setTimeout(function() {
										$.magnificPopup.open({
											tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
											items: {
												src: html,
												type: "inline"
											},
											callbacks: {
												open: function() {
													
													var clipboard = new ClipboardJS('#thankyou_afp_coupon_copy');
													
													clipboard.on('success', function(e) {
														$('#thankyou_afp_coupon').removeClass('text-danger').addClass('text-success');
													});
													
													clipboard.on('error', function(e) {
														console.log(e);
													});
													
													if (navigator.clipboard1) {
														console.log('[PWA] navigator has clipboard');														
														$('#thankyou_afp_coupon, #thankyou_afp_coupon_copy').on('click, touch', function(){
															navigator.clipboard.writeText(json['coupon'])
															.then(() => {
																$('#thankyou_afp_coupon').removeClass('text-danger').addClass('text-success');
															})
															.catch(err => {															
																console.log('Something went wrong', err);
															});
														});
													} else {
														console.log('[PWA] navigator has no clipboard');
													}
												}
											},
											midClick: !0,
											removalDelay: 200
										})
									}, 0)
									
								} else {									
									setTimeout(function(){$.toast({text: json['success'], heading: "", icon: 'success'});}, 0);
								}
								
								$('input[name=\'name\']').val('');
								$('textarea[name=\'text\']').val('');
								<?php if (isset($oct_product_reviews_data['status']) && $oct_product_reviews_data['status']) { ?>
									$('textarea[name=\'positive_text\']').val('');
									$('textarea[name=\'negative_text\']').val('');
								<?php } ?>
								$('input[name=\'rating\']:checked').prop('checked', false);
								<?php if ($text_terms) { ?>
									$('input[name=\'terms\']:checked').prop('checked', false);
								<?php } ?>
							}
						}
					});
					<?php if ($captcha) { ?>
						grecaptcha.reset();
					<?php } ?>
				});
			

$(function() {
	var hash = window.location.hash;
	if (hash) {
		var hashpart = hash.split('#');
		var  vals = hashpart[1].split('-');
		for (i=0; i<vals.length; i++) {
			$('div.options').find('select option[value="'+vals[i]+'"]').attr('selected', true).trigger('select');
			$('div.options').find('input[type="radio"][value="'+vals[i]+'"]').attr('checked', true).trigger('click');
		}
	}
})
				//-->
			</script>


			<?php if (isset($oct_product_reviews_data['status']) && $oct_product_reviews_data['status']) { ?>
				<script>
					function review_reputation(review_id, reputation_type) {
						$.ajax({
							url: 'index.php?route=product/product/oct_review_reputation&review_id=' + review_id + '&reputation_type=' + reputation_type,
							dataType: 'json',
							success: function(json) {
								$('#form-review .alert-success, #form-review .alert-danger').remove();
								
								if (json['error']) {
									alert(json['error']);
								}
								if (json['success']) {
									alert(json['success']);
									$('#review').load(`index.php?route=product/product/review&product_id=<?php echo $product_id; ?>${numberPage !== 0 ? '&page=' + numberPage : ''}`);

							
									
								}
							}
						});
					}
				</script>
			<?php } ?>

<script>
	$(document).ready(function(){
		passEcommerceToDataLayer('productDetail', '<?php echo $product_id?>');

		setTimeout(function(){
			//Tracking chat changes
			var iframe = document.querySelector('iframe[name="helpcrunch-iframe"]');

			var observer = new MutationObserver(function(mutations) {
			  mutations.forEach(function(mutation) {

				    if (mutation.attributeName === 'style') {

				      	iframe.classList.toggle('open');
				   
				      	var currentStyles = iframe.getAttribute('style');
				    }
			  	});
			});

			var config = { attributes: true };
			observer.observe(iframe, config);

		}, 2000);
		
	});
</script>

<script>

	$(function() {
		<?php if ($minimum > 1) { ?>
			oct_update_prices_opt();
		<?php } ?>
	});
	function oct_update_prices_opt() {

		var minimumval = $('#minimumval').val();

		$.ajax({
			type: 'post',
			url:  'index.php?route=product/product/update_prices',
			data: $('#product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select'),
			dataType: 'json',
			success: function(json) {
				console.log('price: ', json);
				// console.log('special: ', json['special']);

				// <?php if (!$special) { ?>
				// 	$('#main-product-price').html(json['price']);
				// <?php } ?>

				// $('#main-product-special').html(json['special']);
				// $('#main-product-tax').html(json['tax']);
				// $('#main-product-you-save').html(json['you_save']);


			}
		});
	}

	<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
		$(document).on('change', '#product input[type=\'radio\']', function() {
			console.log(0);
			$('#main-product-sku').html($(this).attr('data-option-sku'));checkSku()
		});
		$(document).on('change', '#product input[type=\'text\']', function() {
			console.log(1);
			$('#main-product-sku').html($(this).attr('data-option-sku'));checkSku()
		});
	<?php } ?>

	<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
		$(document).on('change', '#product input[type=\'checkbox\']', function() {
			if ($(this).is(':checked')) {
				console.log(2);
				$('#main-product-sku').html($(this).attr('data-option-sku'));checkSku()
			} else {
				console.log(3);
				$('#main-product-sku').html('<?php echo $sku; ?>');checkSku()
			}
		});
	<?php } ?>

	<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
		$(document).on('change', '#product select', function() {
			console.log(4);
			$('#main-product-sku').html($(this).find(':selected').attr('data-option-sku'));checkSku()
		});
	<?php } ?>
	
</script>

<script>
	$(document).on('change', '#product .radio-img', function() {
		
		$.ajax({
			url: 'index.php?route=product/product/getPImages&product_id=<?php echo $product_id; ?>&option_id='+$('#product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select').val(),
			type: 'post',
			data: $('#product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select'),
			dataType: 'json',
			success: function(json) {

				var items2 = [];
				
				if (json['text_data'] && json['text_data']['name']){
					$('h1.product-header').html(json['text_data']['name']);
					$('ul.breadcrumb li').last().children('span').children('span').html(json['text_data']['name']);
				}

				if (json['stock_data'] && json['stock_data']['stock']){
					$('#live-stock').text(json['stock_data']['stock']);					
				}

				if (json['stock_data']){
					if (parseInt(json['stock_data']['quantity']) > 0) {
						$('#live-stock').removeClass('outofstock');

						$('#buttons-buy-section').show();
						$('#oneclick-popup').show();
						$('#oneclick-popup').show();
						$('#preorder-row').hide();
						$('.button-cart-mobile').show();
					} else {
						$('#live-stock').removeClass('outofstock').addClass('outofstock');

						$('#buttons-buy-section').hide();
						$('#oneclick-popup').hide();
						$('#oneclick-popup').hide();
						$('#preorder-row').show();
						$('.button-cart-mobile').hide();
					}
				}

				if (json['images']) {
					var patterns  = '<div class="thumbnails all-carousel">';
					$.each(json['images'], function(i,val) {
					   if (!val['video_in_product'] || (typeof val['video_in_product'] === 'string' && val['video_in_product'].trim() === '')) {
					        patterns += '   <a href="'+val['popup']+'" data-fancybox="images" data-index="'+i+'" data-main-img="'+val['main_img']+'" data-main-popup="'+val['main_popup']+'" class="cloud-zoom-gallery" data-rel="useZoom: \'zoom1\', smallImage: \''+val['popup']+'\'">';
					        patterns += '     <img class="img-responsive" src="'+val['popup']+'" title="<?php echo str_replace("'","\'", $heading_title); ?>" alt="<?php echo str_replace("'","\'", $heading_title); ?>" />';
					        patterns += '   </a>';
					    }
					});

				     <?php if ($image['video_in_product']) { ?>
				        patterns += '   <a class="thumbnail mfp-iframe" href="http://www.youtube.com/watch?v=<?php echo $image['video_in_product']; ?>" onclick="return do_youtube_popup_view($(this));" data-youtube-id="<?php echo $image['video_in_product']; ?>"><span></span> '
				        patterns += '   <img height="<?php echo $image['video_in_product_height']; ?>px" width="<?php echo $image['video_in_product_width']; ?>px" style="height:<?php echo $image['video_in_product_height']; ?>px!important;" src="http://i4.ytimg.com/vi/<?php echo $image['video_in_product']; ?>/hqdefault.jpg" title="<?php echo str_replace("'","\'", $heading_title); ?>" alt="<?php echo str_replace("'","\'", $heading_title); ?>" /> '
				        patterns += '   </a>';
				    <?php } ?>
					patterns += '</div>';
				}
				
				$('.left-info #image-additional').html(patterns);
				
				$("#image-additional .all-carousel").owlCarousel({
					loop:false,
					margin:30,
					nav:false,
					slideBy: 1,
					dots: true,
					navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>'],	
					responsive:{
						0:{
							items:1
						},
						600:{
							items:2,
							slideBy: 1,
						},
						750:{
							items:6,
							slideBy: 1,
						},
						1000:{
							items:4,
							slideBy: 1,
						},
						1400:{
							items:5,
							slideBy: 1,
						},
						1550:{
							items:6,
							slideBy: 6,
						}
					}
				});
				
				<?php if ($check_zoom) { ?>
					$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom({position: 'inside'});
					
					if ($('.cloud-zoom-gallery').eq(0).length) {
						$('.cloud-zoom-gallery').eq(0).click();
						$('.cloud-zoom-gallery').eq(0).addClass('selected-thumb');
					}
				<?php } else { ?>
					if ($('.cloud-zoom-gallery').eq(0).length) {
						$('.left-info .thumbnails-one').find('a').attr('href', $('.cloud-zoom-gallery').eq(0).attr('data-main-popup'));
						$('.left-info .thumbnails-one a').find('img').attr('src', $('.cloud-zoom-gallery').eq(0).attr('data-main-img'));
					}
				<?php } ?>
				$('.thumbnails a').on('click', function(e) {
					$(".thumbnails a").removeClass("selected-thumb");
					$(this).addClass("selected-thumb");
				});
			}

		});
	});
	
	var numberPage = 0;
	$('#review').delegate('.pagination a', 'click', function(e) {
		e.preventDefault();

		let value = this.textContent;
		numberPage = parseInt(value);
		console.log('numberPage', numberPage)

		$('#review').fadeOut('slow');

		$('#review').load(this.href);

		$('#review').fadeIn('slow');
		let review = document.getElementById("review");
		review.scrollIntoView({ behavior: 'smooth', block: 'start'});
	});

	$(function() {
		$('label.optid-<?php echo $option['option_id'];?>').click(function(){
			if ($(this).find('input[type=radio]').is('input:disabled')) {
	        //$('label.selected-img').removeClass('selected-img').addClass('not-selected-img');
			} else {
				$('label.optid-<?php echo $option['option_id'];?>').removeClass('selected-img').addClass('not-selected-img');
				$(this).removeClass('not-selected-img').addClass('selected-img');
			}
		});
	});
</script>
<?php if ($check_zoom) { ?>
	<script>

		
		function viewport() {
			var e = window,
			a = 'inner';

			if (!('innerWidth' in window)) {
				a = 'client';
				e = document.documentElement || document.body;
			}

			return {
				width: e[a + 'Width'],
				height: e[a + 'Height']
			};
		}

		$(document).ready(function() {
			if (!is_touch_device()) {
				$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom({position: 'inside'});
			}
		});

		
		$(function() {
			
			var getWidth = viewport().width;
			
			if($('[data-fancybox]').length){							
				$.fancybox.defaults.hash = false;
			}
			
			if(getWidth > 480) {
				var class_in = ".mousetrap";
			} else {
				var class_in = ".cloud-zoom";
			}
			
			$('body').delegate(class_in, 'click', function() {						
				var iar = [];
				var ind = '';
				
				if($('[data-fancybox]').length > 1){								
					$('.thumbnails a.cloud-zoom-gallery').each(function(index) {
						iar.push({src: $(this).attr('href')});	
						
						console.log(iar);
						
						if ($(this).attr('href') == $("#zoom1").attr('href')) {
							ind = index;
						}
					});
				} else {
					iar.push({src: $("#zoom1").attr('href')});
					ind = 0;
				}
				$.fancybox.open(iar, {padding : 0, index: ind});
				return false;
			});
		});
		function is_touch_device() {
			var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
			var mq = function(query) {
				return window.matchMedia(query).matches;
			}
			
			if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
				return true;
			}
			
			var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
			return mq(query);
		}
	</script>
<?php } else { ?>
	<script>
		$(function() {
			if($('[data-fancybox]').length){
				$.fancybox.defaults.hash = false;
			}
		});
	</script>
<?php } ?>



<script type="text/javascript">
	var button = document.getElementById('popup-checkout-button');
	button.addEventListener(
		'click',
		function() {
			var google_tag_params = {
				ecomm_prodid: "<?php echo $product_id; ?>",
				ecomm_totalvalue: <?php echo $fbprice; ?>,
				ecomm_pagetype: "purchase"
			};
		},
		false
		);
	</script>

	<!-- Add Pixel Events to the button's click handler -->
	<script type="text/javascript">
         /* var button = document.getElementById('popup-checkout-button');
          button.addEventListener(
            'click',
            function() {
              fbq('track', 'Purchase', {
			    value: <?php echo $fbprice; ?>,
				currency: '<?php echo $fbcurrency; ?>',
                content_ids: [<?php echo $product_id; ?>],
                content_type: 'product'

              });
            }

          );		*/
	</script>
	<!-- Add Pixel Events to the button's click handler -->
	<script type="text/javascript">
          /* var button = document.getElementById('button-cart_OLD');
          button.addEventListener(
            'click',
            function() {
              fbq('track', 'AddToCart', {
                content_name: '<?php echo $heading_title; ?>',
                content_ids: ['<?php echo $product_id; ?>'],
                content_type: 'product',
                value: <?php echo $fbprice; ?>,
                currency: '<?php echo $fbcurrency; ?>'
              });
            }
          ); */
	</script>


	<script>
	// 	document.addEventListener("DOMContentLoaded", function() {
    // // Получаем текст доступности из элемента с классом 'micro-availability'
    // var availabilityElement = document.querySelector('.micro-availability');
    // var availabilityText = availabilityElement ? availabilityElement.textContent.trim() : null;

    // // Сопоставляем текст доступности с соответствующим URL schema.org
    // var availabilityMap = {
	// 			"В наличии": "http://schema.org/InStock",
	// 			"В наявності": "http://schema.org/InStock",
	// 			"Нет в наличии": "http://schema.org/OutOfStock",
	// 			"Предзаказ": "http://schema.org/PreOrder",
	// 			"Немає в наявності": "http://schema.org/OutOfStock",
	// 			// Добавьте дополнительные сопоставления при необходимости
	// 		}; 
	// 		// Находим тег скрипта с id 'product-schema'
	// 		var schemaElement = document.getElementById('product-schema');
	// 		if (schemaElement && availabilityText) {
	// 			try {
	// 				// Парсим содержимое JSON-LD
	// 				var schemaJson = JSON.parse(schemaElement.textContent);

	// 				// Обновляем поле 'availability' внутри 'offers'
	// 				var availabilityUrl = availabilityMap[availabilityText] || "http://schema.org/OutOfStock";
	// 				if (schemaJson.offers && typeof schemaJson.offers === 'object') {
	// 					schemaJson.offers.availability = availabilityUrl;
	// 				}

	// 				// Обновляем содержимое тега скрипта
	// 				schemaElement.textContent = JSON.stringify(schemaJson);
	// 			} catch (e) {
	// 				console.error("Ошибка при парсинге JSON-LD:", e);
	// 			}
	// 		}
	// 		});
	</script>
	<?php echo $footer; ?>

