<?php echo $header; ?>
<div class="account-page-twoColumn personal_area-page--order_list">
	<div class="wrap fdc">
		<div class="breadcrumb-box">
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
                    <?php if($count == 0) { ?>
                        <li>
                            <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $oct_home_text; ?>">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
                                </svg>
                                Головна
                            </a>
                        </li>
                    <?php } elseif($count+1<count($breadcrumbs)) { ?>
                        <li>
                            <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>">
                                <?php echo $breadcrumb['text']; ?>
                            </a>
                        </li>   
                     <?php } else { ?>
                      <!--   <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <span itemprop="name"><?php echo $breadcrumb['text']; ?></span>  
                        </li>     -->               
                    <?php } ?>
                        
                <?php } ?>
            </ul>
        </div>
		<div id="content" class="account-content">
			<?php echo $content_top; ?>
			<h1 class="title-page"><?php echo $heading_title; ?></h1>
			<div class="two_column"> 
                <div class="side_bar">
                    <?php echo $column_left; ?>
                </div> 
                <div class="account_content">
					<?php if ($orders) { ?>
						<div class="order_list accordion_list_order">
							<?php foreach ($orders as $order) { ?> 
								<div class="order_item completed">

									<div class="head about_order">
										<div class="detail_order_wrap">
											<div class="date">
												<?php if ($order['preorder']) { ?>
													<p class="text"><?php echo $column_date_added; ?>:</p>
													<p class="value"><?php echo $order['date_added']; ?></p>
												<?php } else { ?>
													<p class="text"><?php echo $column_date_added; ?>:</p>
													<p class="value"><?php echo $order['date_added']; ?></p>
												<?php } ?>
											</div>
											<div class="order">
												<p class="text"><?php echo $column_order_id; ?>:</p>
												<p class="value"><?php echo $order['order_id']; ?></p> 
											</div>
											<div class="status">
												<p class="text"><?php echo $column_status; ?>:</p>
												<p class="value"><?php echo $order['status']; ?></p>
											</div>
											<div class="shipping">
												<p class="text"><?php echo $column_delivery; ?>:</p>
												<p class="value" style="font-weight: 400"><?php echo $order['shipping_method']; ?></p>
											</div>
											<?php if (!empty($order['track_no'])) { ?>
												<div class="shiping">
													<p class="text"><?php echo $text_track_no; ?>: </p>
													<!-- <p class="value"><?php echo preg_replace("/<img[^>]+\>/i", "", $order['shipping_method']); ?></p> -->
													<?php if (!empty($order['track_no'])) { ?>
														<span class="value label label-info track_number" data-number="<?php echo $order['track_no']; ?>" title="<?php echo $text_copy; ?>">
															<?php echo $order['track_no']; ?>
															<button class="btn_copy_track_number">
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 115.77 122.88" style="enable-background:new 0 0 115.77 122.88" xml:space="preserve"><style type="text/css" style="&#10;    /* fill: red; */&#10;">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M89.62,13.96v7.73h12.19h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02v0.02 v73.27v0.01h-0.02c-0.01,3.84-1.57,7.33-4.1,9.86c-2.51,2.5-5.98,4.06-9.82,4.07v0.02h-0.02h-61.7H40.1v-0.02 c-3.84-0.01-7.34-1.57-9.86-4.1c-2.5-2.51-4.06-5.98-4.07-9.82h-0.02v-0.02V92.51H13.96h-0.01v-0.02c-3.84-0.01-7.34-1.57-9.86-4.1 c-2.5-2.51-4.06-5.98-4.07-9.82H0v-0.02V13.96v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07V0h0.02h61.7 h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02V13.96L89.62,13.96z M79.04,21.69v-7.73v-0.02h0.02 c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v64.59v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h12.19V35.65 v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07v-0.02h0.02H79.04L79.04,21.69z M105.18,108.92V35.65v-0.02 h0.02c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v73.27v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h61.7h0.02 v0.02c0.91,0,1.75-0.39,2.37-1.01c0.61-0.61,1-1.46,1-2.37h-0.02V108.92L105.18,108.92z" style="&#10;    fill: var(--color-dark-blue);&#10;"/></g></svg>
															</button>
														</span>
													<?php } ?>
												</div>
											<?php } ?>
											<!-- <?php if (!$isMobile) { ?>
												<div class="deliveru">
													<p class="text">Доставка</p>
													<p class="value">-</p>
												</div>
											<?php } ?> -->
											<div class="total_price">
												<p class="text"><?php echo $column_total; ?>:</p>
												<p class="value"><?php echo $order['total']; ?></p>
											</div>
											
										</div>
										
										<button class="btn_detail">
											<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
											  <path d="M11.8082 3.04747L11.4151 2.65167C11.2914 2.52805 11.1268 2.45996 10.9509 2.45996C10.7751 2.45996 10.6103 2.52805 10.4866 2.65167L6.00268 7.1358L1.51357 2.64669C1.39006 2.52308 1.22528 2.45508 1.04947 2.45508C0.873666 2.45508 0.708786 2.52308 0.585176 2.64669L0.192002 3.04006C-0.0640005 3.29587 -0.0640005 3.71255 0.192002 3.96836L5.53683 9.3324C5.66034 9.45591 5.82493 9.54294 6.00229 9.54294H6.00434C6.18025 9.54294 6.34483 9.45582 6.46835 9.3324L11.8082 3.9829C11.9319 3.85938 11.9998 3.68982 12 3.51401C12 3.33811 11.9319 3.17089 11.8082 3.04747Z" fill="#E96335"/>
											</svg>
										</button>
											
									</div>
										
									<div class="detail_order">
										
											
													
										<div class="order-products-table">
											
												<?php foreach ($order['products'] as $product) { ?>
													<div class="order-details_product">
														<a href="<?php echo $product['href']; ?>" class="img" target="_blank" title="<?php echo $product['name']; ?>">
															
															<img src="<?php echo $product['image']; ?>" />
															
																		
														</a>	
														<div class="name">
															<a href="<?php echo $product['href']; ?>" target="_blank" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a>
															
															<p class="text"><?php echo $column_quantity; ?> х <?php echo $product['quantity']; ?></p>
															
														</div>
														<div class="order-product-price">
															<p class="value">
																<?php if ($order['preorder'] && $product['price_isnull']) { ?>
																	-
																<?php } else { ?>
																	<?php echo $product['total']; ?> грн
																<?php } ?>
															</p>
															
														</div>
													</div>
												<?php } ?>
											
										</div>

									</div>
								</div>	
							<?php } ?>
						</div>
						
						<div class="pagination-wrap">
							<?php echo $pagination; ?>
						</div>
					<?php } else { ?>
						<p class="text-center"><?php echo $text_empty; ?></p>
					 	<div class="buttons">
							<div class="text-left">
								<a href="<?php echo $continue; ?>" class="oct-button"><?php echo $button_continue; ?></a>
							</div>
						</div>
					<?php } ?>

					<?php echo $content_bottom; ?>
				</div>
		</div> 
	</div>
</div>

<script>
	var list_order = document.querySelectorAll('.accordion_list_order .order_item');
	
	list_order.forEach(function(e){
		e.querySelector('.btn_detail').addEventListener('click', function () {
		    e.classList.toggle('open');
		});
	});



	var trackNumbers = document.querySelectorAll('.track_number');

	trackNumbers.forEach(function(trackNumber) {

	    trackNumber.addEventListener('click', function() {

	        var dataNumber = this.getAttribute('data-number');

	        navigator.clipboard.writeText(dataNumber).then(function() {
	            var successMessage = document.createElement('div');
	            successMessage.classList.add('copy-success-message');
	            successMessage.textContent = 'ТТН скопійовано';
	           
	            
	           	trackNumber.appendChild(successMessage);
	            setTimeout(function() {
	                successMessage.parentNode.removeChild(successMessage);
	            }, 2000);
	        }, function(err) {
	            console.error('Не вдалося скопіювати текст: ', err);
	            
	        });
	    });
	});
</script>
<?php echo $footer; ?>
