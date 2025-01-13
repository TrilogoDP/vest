<?php echo $header; ?>

<style>
	.table-responsive tbody tr > td:first-child{

	}
</style>

<div class="wrap fdc">
	<?php echo $content_top; ?>

	<div class="breadcrumb-box">
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
				<?php if($count == 0) { ?>
					<li>
						<a href="<?php echo $breadcrumb['href']; ?>">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
							</svg>
							
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
	<h1 class="title_module"><?php echo $heading_title; ?></h1>
</div>

<div class="wrap">
	<?php echo $column_left; ?>
	<div id="content" class="<?php echo $class; ?>">
		<?php if ($products) { ?>
			<?php
				$count = 0;

				foreach ($products as $product) {
				 
				    $count++;
				}

			?>
			<div class="compare-wrap count_product_<?php echo $count; ?> dragscroll">


					<div class="compare-item">
						<div class="left-column top_left">
							<!-- <p><?php echo $text_name; ?></p> -->
						</div>
						<div class="product-compare top_compare">
							<?php foreach ($products as $product) { ?>
								<div class="name">
									<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
									<a href="<?php echo $product['remove']; ?>" class="oct-button oct-button-inv">
							    		<i class="fa fa-times" aria-hidden="true"></i> 
							    	</a>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="compare-item">
						<div class="left-column">
							<!-- <p><?php echo $text_image; ?></p> -->
						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
					    		<div class="text-center compare-img-td">
					    			<?php if ($product['thumb']) { ?>
					      				<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />
				      				<?php } ?>
					    		</div>
					   		<?php } ?>
						</div>
					</div>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_price; ?></p>
						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
								
							    <div><?php if ($product['price']) { ?>
							    	<?php if ($isMobile) { ?>
										<p><?php echo $text_price; ?></p>
									<?php } ?>
							      	<?php if (!$product['special']) { ?>
								      	<?php echo $product['price']; ?>
						      		<?php } else { ?>
						      			<strike><?php echo $product['price']; ?></strike> <?php echo $product['special']; ?>
							      	<?php } ?>
							      	<?php } ?>
							    </div>
						    <?php } ?>
						</div>
					</div>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_model; ?></p>
						</div>
						<div class="product-compare">
						 	<?php foreach ($products as $product) { ?>
						 		
						    	<div>
						    		<?php if ($isMobile) { ?>
							 			<p><?php echo $text_model; ?></p>
									<?php } ?>
						    		<?php echo $product['model']; ?>
						    	</div>
						    <?php } ?>
						</div>
					</div>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_manufacturer; ?></p>
						</div>
						<div class="product-compare">
							
						 	<?php foreach ($products as $product) { ?>
						 		
						    	<div>
						    		<?php if ($isMobile) { ?>
							 			<p><?php echo $text_manufacturer; ?></p>
									<?php } ?>
						    		<?php echo $product['manufacturer']; ?>
						    	</div>
						    <?php } ?>
						</div>
					</div>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_availability; ?></p>
						</div>
						<div class="product-compare">
						 	<?php foreach ($products as $product) { ?>
						 		
						    	<div>
						    		<?php if ($isMobile) { ?>
							 			<p><?php echo $text_availability; ?></p>
									<?php } ?>
						    		<?php echo $product['availability']; ?>
						    	</div>
						    <?php } ?>
						</div>
					</div>
					<?php if ($review_status) { ?>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_rating; ?></p>
						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
								
								<div class="rating_wrap">
								    <div class="rating">
										<?php for ($i = 1; $i <= 5; $i++) { ?>
											<?php if ($product['rating'] < $i) { ?>
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
									<a href="<?php echo $product['href']; ?>" class="count-reviews">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M13.25 0.5H2.75C2.15326 0.5 1.58097 0.737053 1.15901 1.15901C0.737053 1.58097 0.5 2.15326 0.5 2.75V10.25C0.5 10.8467 0.737053 11.419 1.15901 11.841C1.58097 12.2629 2.15326 12.5 2.75 12.5H11.4425L14.2175 15.2825C14.2876 15.352 14.3707 15.407 14.4621 15.4443C14.5534 15.4817 14.6513 15.5006 14.75 15.5C14.8484 15.5025 14.946 15.482 15.035 15.44C15.172 15.3837 15.2892 15.2882 15.372 15.1654C15.4547 15.0426 15.4993 14.8981 15.5 14.75V2.75C15.5 2.15326 15.2629 1.58097 14.841 1.15901C14.419 0.737053 13.8467 0.5 13.25 0.5ZM14 12.9425L12.2825 11.2175C12.2124 11.148 12.1293 11.093 12.0379 11.0557C11.9466 11.0183 11.8487 10.9994 11.75 11H2.75C2.55109 11 2.36032 10.921 2.21967 10.7803C2.07902 10.6397 2 10.4489 2 10.25V2.75C2 2.55109 2.07902 2.36032 2.21967 2.21967C2.36032 2.07902 2.55109 2 2.75 2H13.25C13.4489 2 13.6397 2.07902 13.7803 2.21967C13.921 2.36032 14 2.55109 14 2.75V12.9425Z" fill="#97A9B1"/>
										</svg>
										<?php echo $product['reviews']; ?>
									</a>
								</div>
						    <?php } ?>
						</div>
					</div>
					<?php } ?>
					<div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_summary; ?></p>
						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
								
						    	<div class="description">
						    		<?php if ($isMobile) { ?>
										<p><?php echo $text_summary; ?></p>
									<?php } ?>
						    		<?php echo $product['description']; ?>
						    	</div>
						    <?php } ?>
						</div>
					</div>
					<!-- <div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_weight; ?></p>
						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
								
					    		<div>
					    			<?php if ($isMobile) { ?>
										<p><?php echo $text_weight; ?></p>
									<?php } ?>
					    			<?php echo $product['weight']; ?>
					    		</div>
					    	<?php } ?>
						</div>
					</div> -->
					<!-- <div class="compare-item">
						<div class="left-column">
							<p><?php echo $text_dimension; ?></p>
						</div>
						<div class="product-compare">
						 	<?php foreach ($products as $product) { ?>
						 		
						    	<div>
						    		<?php if ($isMobile) { ?>
							 			<p><?php echo $text_dimension; ?></p>
									<?php } ?>
						    		<?php echo $product['length']; ?> x <?php echo $product['width']; ?> x <?php echo $product['height']; ?>
						    	</div>
						    <?php } ?>
						</div>
					</div> -->
					<?php foreach ($attribute_groups as $attribute_group) { ?>
						<div class="compare-item no_color">
							<div class="left-column">
								<p><b><?php echo $attribute_group['name']; ?></b></p>
							</div>
							<div class="product-compare">
								<?php foreach ($products as $product) { ?>
									
									<div>
										<p><b><?php echo $attribute_group['name']; ?></b></p>
									</div>
								<?php } ?>
							</div>
						</div>
						<?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
							<div class="compare-item ">
								<div class="left-column">
									<p><?php echo $attribute['name']; ?></p>
								</div>
								<div class="product-compare">
									<?php foreach ($products as $product) { ?>
										
										
										<?php if (isset($product['attribute'][$key])) { ?>
									    	<div>
									    		<?php if ($isMobile) { ?>
													<p><?php echo $attribute['name']; ?></p>
												<?php } ?>
									    		<?php echo $product['attribute'][$key]; ?>
									    	</div>
									    <?php } else { ?>
									    	<div></div>
									    <?php } ?>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					<div class="compare-item">
						<div class="left-column">

						</div>
						<div class="product-compare">
							<?php foreach ($products as $product) { ?>
						  	<div class="button_wrap">
							    <?php if ($product['quantity'] > 0) { ?>
							    	<a class="button-cart oct-button" title="<?php echo $button_cart; ?>" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
						   				<span class="hidden-xs"><?php echo $button_cart; ?></span>
							    		<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.66365V2.85855L1.19797 2.87107L2.39594 2.88359L4.43062 7.19795C5.5497 9.57083 6.51561 11.6218 6.57714 11.7555L6.68899 11.9987L5.84962 13.4451C5.10245 14.7327 4.99854 14.9377 4.90335 15.3115C4.65165 16.2998 4.85537 17.1217 5.51324 17.7723C5.65851 17.916 5.9026 18.1006 6.05567 18.1826C6.62732 18.4886 6.1924 18.4731 14.226 18.4739L21.5199 18.4746V17.2789V16.0832H14.5748C6.95983 16.0832 7.42225 16.1005 7.42225 15.815C7.42225 15.7478 7.65855 15.2435 7.94741 14.6942L8.47252 13.6956L13.1173 13.6814L17.7621 13.6672L18.0655 13.563C18.6086 13.3764 19.0087 13.055 19.2947 12.5758C19.3824 12.4288 20.3944 10.5994 21.5436 8.51041C22.6928 6.42145 23.6708 4.6701 23.717 4.61853C23.9214 4.39041 23.9561 3.99751 23.8073 3.59801C23.7012 3.31292 23.4954 3.09089 23.2237 2.96804C23.0463 2.88776 22.5893 2.88298 14.0257 2.87131L5.01436 2.85897L4.47944 1.66393L3.94453 0.468844L1.97226 0.468797L0 0.46875V1.66365ZM6.44088 19.8004C5.18559 20.2304 4.50017 21.5796 4.90489 22.824C5.16612 23.6273 5.95185 24.2956 6.80307 24.4386C8.0171 24.6425 9.24299 23.7893 9.50244 22.5597C9.59048 22.1424 9.58605 21.9305 9.48073 21.5167C9.26614 20.6739 8.63147 20.0141 7.79887 19.7685C7.42295 19.6576 6.81629 19.6719 6.44088 19.8004ZM18.3912 19.8004C17.1359 20.2304 16.4505 21.5796 16.8552 22.824C17.1164 23.6273 17.9021 24.2956 18.7534 24.4386C19.9674 24.6425 21.1933 23.7893 21.4527 22.5597C21.5408 22.1424 21.5363 21.9305 21.431 21.5167C21.2164 20.6739 20.5818 20.0141 19.7492 19.7685C19.3732 19.6576 18.7666 19.6719 18.3912 19.8004Z" fill="white"/>
										</svg>
							    	</a>
							    <?php } else { ?>
							    	<a class="button-cart out-of-stock-button oct-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>>
							    		<span class="hidden-xs"><?php echo $product['product_preorder_text']; ?></span>
							    		<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.66365V2.85855L1.19797 2.87107L2.39594 2.88359L4.43062 7.19795C5.5497 9.57083 6.51561 11.6218 6.57714 11.7555L6.68899 11.9987L5.84962 13.4451C5.10245 14.7327 4.99854 14.9377 4.90335 15.3115C4.65165 16.2998 4.85537 17.1217 5.51324 17.7723C5.65851 17.916 5.9026 18.1006 6.05567 18.1826C6.62732 18.4886 6.1924 18.4731 14.226 18.4739L21.5199 18.4746V17.2789V16.0832H14.5748C6.95983 16.0832 7.42225 16.1005 7.42225 15.815C7.42225 15.7478 7.65855 15.2435 7.94741 14.6942L8.47252 13.6956L13.1173 13.6814L17.7621 13.6672L18.0655 13.563C18.6086 13.3764 19.0087 13.055 19.2947 12.5758C19.3824 12.4288 20.3944 10.5994 21.5436 8.51041C22.6928 6.42145 23.6708 4.6701 23.717 4.61853C23.9214 4.39041 23.9561 3.99751 23.8073 3.59801C23.7012 3.31292 23.4954 3.09089 23.2237 2.96804C23.0463 2.88776 22.5893 2.88298 14.0257 2.87131L5.01436 2.85897L4.47944 1.66393L3.94453 0.468844L1.97226 0.468797L0 0.46875V1.66365ZM6.44088 19.8004C5.18559 20.2304 4.50017 21.5796 4.90489 22.824C5.16612 23.6273 5.95185 24.2956 6.80307 24.4386C8.0171 24.6425 9.24299 23.7893 9.50244 22.5597C9.59048 22.1424 9.58605 21.9305 9.48073 21.5167C9.26614 20.6739 8.63147 20.0141 7.79887 19.7685C7.42295 19.6576 6.81629 19.6719 6.44088 19.8004ZM18.3912 19.8004C17.1359 20.2304 16.4505 21.5796 16.8552 22.824C17.1164 23.6273 17.9021 24.2956 18.7534 24.4386C19.9674 24.6425 21.1933 23.7893 21.4527 22.5597C21.5408 22.1424 21.5363 21.9305 21.431 21.5167C21.2164 20.6739 20.5818 20.0141 19.7492 19.7685C19.3732 19.6576 18.7666 19.6719 18.3912 19.8004Z" fill="white"/>
										</svg>
							    	</a>
							    <?php } ?>
							    	<a href="<?php echo $product['remove']; ?>" class="oct-button oct-button-inv">
							    		<span class="hidden-xs"><?php echo $button_remove; ?></span>
							    		<i class="fa fa-times" aria-hidden="true"></i> 
							    	</a>
						  	</div>
						  	<?php } ?>
						</div>
					</div>

			</div>
		<?php } else { ?>
			<p class="text-left empty-text"><?php echo $text_empty; ?></p>
			<div class="buttons">
			<div class="text-left">
				<a href="<?php echo $continue; ?>" class="oct-button"><?php echo $button_continue; ?></a></div>
			</div>
		<?php } ?>
	</div>
  	<?php echo $column_right; ?>
</div>

<div class="wrap fdc">
	<?php echo $content_bottom; ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragscroll/0.0.8/dragscroll.min.js" integrity="sha512-/ncZdOhQm5pgj5KHy720Ck7XF5RzYK6rtUsLNnGcitXrKT3wUYzTrPlOSG7SdL2kDzkuLEOFvrQRyllcZkeAlg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

	let items = document.getElementsByClassName("compare-item");
	let targetItem = items[items.length - 2];
	targetItem.classList.add("last");




	var mainBlock = $('.compare-wrap');
	let leftColumn = $('.top_left').width();
	let product = $('.top_compare').width();

	if(leftColumn + product > mainBlock.width()){
		$(mainBlock).addClass('has_scroll')
	}
	
	
</script>
<?php echo $footer; ?>