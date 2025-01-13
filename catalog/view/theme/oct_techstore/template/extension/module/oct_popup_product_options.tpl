<div id="product-options-popup" class="white-popup mfp-with-anim narrow-popup">
	<h2 class="popup-header"><?php echo $heading_title; ?></h2>
	<?php if ($stock_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $stock_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
	<?php if ($minimum > 1) { ?>
		<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	<?php } ?>
	<?php if (!$stock_warning) { ?>
		<form method="post" enctype="multipart/form-data" id="purchase-form" class="popup-form-opt-box">
			<?php if ($oct_popup_product_options_data['show_quantity']) { ?>
				<div class="row">
					<div class="col-sm-12">
						<h3 class="popup-h3 blue text-center"><?php echo $product_name; ?></h3>
					</div>
					<div class="col-sm-12 text-center">
					<img src="<?php echo $thumb; ?>" alt="<?php echo $product_name; ?>" />
				</div>
					<div class="payment-quantity">
						<label><?php echo $entry_quantity; ?></label>
						<div class="number">
							<div class="frame-change-count">
								<div class="btn-minus">
									<button type="button" id="superminus" onclick="$(this).parent().next().val(~~$(this).parent().next().val()-1); popup_check_min_quantity();">
										<span class="icon-minus"><i class="fa fa-minus"></i></span>
									</button>
								</div>
								<input type="text" name="quantity" value="<?php echo $minimum; ?>" maxlength="3" size="8" class="plus-minus" onchange="popup_check_min_quantity(); return validate(this);" keypress="popup_check_min_quantity(); return validate(this);" id="input-quantity">
								<div class="btn-plus">
									<button type="button" id="superplus" onclick="$(this).parent().prev().val(~~$(this).parent().prev().val()+1); popup_check_min_quantity();">
										<span class="icon-plus"><i class="fa fa-plus"></i></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<input type="hidden" name="quantity" value="<?php echo $minimum; ?>" />
			<?php } ?>
			<input name="product_id" value="<?php echo $product_id; ?>" style="display: none;" type="hidden" />
			<div class="clearfix"></div>
			<?php if ($options) { ?>			
				<?php foreach ($options as $option) { ?>
					<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status'] && $oct_advanced_options_settings_data['quantity_status'] && $option['type'] == 'oct_quantity') { ?>
						<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li oct-quantity-div">
							<label class="control-label"><?php echo $option['name']; ?></label>
							<div class="table-responsive" id="options-popup-input-option<?php echo $option['product_option_id']; ?>">
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
						<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li list-li">
							<label class="control-label options-header" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
							<select name="option[<?php echo $option['product_option_id']; ?>]" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control" onchange="popup_check_min_quantity();">
								<option value=""><?php echo $text_select; ?></option>
								<?php foreach ($option['product_option_value'] as $option_value) { ?>
									<option value="<?php echo $option_value['product_option_value_id']; ?>" <?php if (!$option_value['quantity_status']) { ?>disabled="disabled"<?php } ?>><?php echo $option_value['name']; ?>
										<?php if ($option_value['price']) { ?>
											(<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
										<?php } ?>
									</option>
								<?php } ?>
							</select>
						</div>
					<?php } ?>
					<?php if ($option['type'] == 'radio') { ?>
						<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li list-li text-center">
							<h3 class="control-label options-header text-center"><?php echo $option['name']; ?></h3>
							<div class="options-box text-center" id="options-popup-input-option<?php echo $option['product_option_id']; ?>">
								<?php foreach ($option['product_option_value'] as $option_value) { ?>
									<?php if ($option_value['image'] && $option_value['quantity_status']) { ?>
										<div class="radio radio-img">
											<?php if ($option_value['quantity_status']) { ?>
												<label class="not-selected-img optmid-<?php echo $option['option_id'];?>" title="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>">
													<?php } else { ?>
													<label class="not-selected-img optmid-<?php echo $option['option_id'];?>" title="<?php echo $text_option_disable; ?>" style="opacity:0.2;cursor:default;">
													<?php  } ?> 
													<?php if ($option_value['quantity_status']) { ?>
														<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" class="none" />
														<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
														<?php } else { ?>
														<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" class="none" disabled="disabled" />
														<img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
													<?php } ?>
												</label>
											</div>
											<?php } else if ($option_value['quantity_status']) { ?>
											<div class="radio oct-product-radio">
												<?php if ($option_value['quantity_status']) { ?>
													<label class="optid-<?php echo $option['option_id'];?>">
														<?php } else { ?>
														<label class="optid-<?php echo $option['option_id'];?>" title="<?php echo $text_option_disable; ?>" style="opacity:0.5;cursor:default;">
														<?php } ?>  
														<?php if ($option_value['quantity_status']) { ?>
															<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" class="product-input-radio none" />
															<?php } else { ?>
															<input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" class="product-input-radio none" disabled="disabled" />
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
									<div class="options-box" id="options-popup-input-option<?php echo $option['product_option_id']; ?>">
										<?php foreach ($option['product_option_value'] as $option_value) { ?>
											<div class="checkbox">
												<label <?php if (!$option_value['quantity_status']) { ?>style="opacity:0.5;cursor:default;" title="<?php echo $text_option_disable; ?>"<?php } ?>>
													<?php if ($option_value['quantity_status']) { ?>  
														<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
															<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" onchange="popup_check_min_quantity();"/>
															<?php } else { ?>
															<input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
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
									<label class="control-label" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'textarea') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
									<label class="control-label" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'file') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
									<label class="control-label"><?php echo $option['name']; ?></label>
									<button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
									<input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" />
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'date') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
									<label class="control-label" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group date">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'datetime') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
									<label class="control-label" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group datetime">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							<?php } ?>
							<?php if ($option['type'] == 'time') { ?>
								<div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?> product-info-li">
									<label class="control-label" for="options-popup-input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
									<div class="input-group time">
										<input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="options-popup-input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					<?php } ?>			
				<?php } ?>



				<?php if ($products) { ?>
					<link rel="stylesheet" href="catalog/view/theme/oct_techstore/stylesheet/pretty-checkbox.min.css" />
					<section class="hr-bottom buy-togeter-products-wrap" name="buy-togeter-products">						
						<div class="buy-togeter-products-head">
							<h4><?php echo $text_related; ?></h4>
						</div>
						<div id="oct-addons" class="buy-togeter-products-content">
							<?php foreach ($products as $addon) { ?>
								<div class="item" data-product-id="<?php echo $addon['product_id']; ?>" data-sort-order="<?php echo $addon['sort_order']; ?>">
									<?php if ($isMobile) { ?>
											<div class="checkbox-group">
												<div class="pretty p-icon p-smooth p-thick">
													<input type="checkbox" name="product_addon[]" value="<?php echo $addon['product_id']; ?>" />
													<div class="state p-success">
														<i class="icon fa fa-check"></i>
														<label></label>
													</div>
												</div>
											</div>
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
															<span class="oct-price-new red"><?php echo $addon['price']; ?></span>
															<span class="oct-price-old"><?php echo $addon['special']; ?></span>
														<?php } else { ?>
															<span class="oct-price-new"><?php echo $addon['price']; ?></span>
														<?php } ?>
													</div>
												</div>
											</div>
											
										<?php } else { ?>
											<div class="checkbox-group">
												<div class="pretty p-icon p-smooth p-thick">
													<input type="checkbox" name="product_addon[]" value="<?php echo $addon['product_id']; ?>" />
													<div class="state p-success">
														<i class="icon fa fa-check"></i>
														<label></label>
													</div>
												</div>
											</div>
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
													<span class="oct-price-new red"><?php echo $addon['price']; ?></span>
													<span class="oct-price-old"><?php echo $addon['special']; ?></span>
												<?php } else { ?>
													<span class="oct-price-new"><?php echo $addon['price']; ?></span>
												<?php } ?>
											</div>
										
										<?php } ?>
								</div>
							<?php } ?>
						</div>
					</section>
				<?php } ?>
				
			</form>
			
			<div class="popup-buttons-box">

				<a class="text-button popup-button oct-button-inv" onclick="$.magnificPopup.close();"><?php echo $button_shopping; ?></a>
				<?php if (!$stock_warning) { ?>
					<a class="popup-button popup-button-wide" id="popup-checkout-button" <?php /* onclick="ga('send', 'event', 'quick_order', 'click');" */ ?>>
					 	<?php echo $button_checkout; ?>
					 	
					</a>
				<?php } ?>   
			</div>
				<?php if (!$stock_warning) { ?>
				<script>
					function popup_check_min_quantity() {
						var input_val = $('#product-options-popup').find('input[name=quantity]').val();
						var quantity = parseInt(input_val);
						<?php if ($minimum > 1) { ?>
							if (quantity < <?php echo $minimum; ?>) {
								quantity = $('#product-options-popup').find('input[name=quantity]').val(<?php echo $minimum; ?>);
								return;
							}
							<?php } else { ?>
							if (quantity == 0) {
								quantity = $('#product-options-popup').find('input[name=quantity]').val(1);
								return;
							}
						<?php } ?>
					}
				</script>
				<?php if ($options) { ?>
					<!-- if options start -->
					<script src="catalog/view/javascript/jquery/datetimepicker/moment.js"></script>
					<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>
					<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" />
					<script>
						$('#product-options-popup .date').datetimepicker({
							pickTime: false,
						});
						$('#product-options-popup .datetime').datetimepicker({
							pickDate: true,
							pickTime: true
						});
						$('#product-options-popup .time').datetimepicker({
							pickDate: false,
						});
					</script>
					<script>
						$(document).on('click', '#product-options-popup button[id^=\'button-upload\']', function() {
							var node = this;
							$('#form-upload').remove();
							$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
							$('#form-upload input[name=\'file\']').trigger('click');
							if (typeof timer != 'undefined') {
								clearInterval(timer);
							}
							timer = setInterval(function() {
								if ($('#form-upload input[name=\'file\']').val() != '') {
									clearInterval(timer);
									$.ajax({
										url: 'index.php?route=tool/upload',
										type: 'post',
										dataType: 'json',
										data: new FormData($('#form-upload')[0]),
										cache: false,
										contentType: false,
										processData: false,
										beforeSend: function() {
											$(node).button('loading');
										},
										complete: function() {
											$(node).button('reset');
										},
										success: function(json) {
											$('#product-options-popup .text-danger').remove();
											if (json['error']) {
												$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
											}
											if (json['success']) {
												alert(json['success']);
												$(node).parent().find('input').attr('value', json['code']);
											}
										},
										error: function(xhr, ajaxOptions, thrownError) {
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								}
							}, 500);
						});
						
						<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
							<?php if ($oct_advanced_options_settings_data['allow_autoselect_option']) { ?>
								$(function() {
									$('#product-options-popup div.product-info-li').not('.oct-quantity-div').each(function(i, val) {
										$(this).find('input[type=\'radio\']').eq(0).click();
										$(this).find('input[type=\'checkbox\']').eq(0).click();
										$(this).find('select option:nth-child(2)').attr("selected", "selected").trigger('change');
									});
								});
							<?php } ?>
						<?php } ?>
					</script>
					<?php foreach ($options as $option) { ?>
						<?php if ($option['type'] == 'radio') { ?>
							<?php foreach ($option['product_option_value'] as $option_value) { ?>
								<?php if ($option_value['image']) { ?>
									<script>

											$('label.optmid-<?php echo $option['option_id'];?>').click(function(){
												
												if ($(this).find('input[type=radio]').is('input:disabled')) {
													$('label.selected-img').removeClass('selected-img').addClass('not-selected-img');
													$(this).css({
														'opacity': 0.5,
														'cursor': 'default'
													});
												} else {
													$('label.optmid-<?php echo $option['option_id'];?>').removeClass('selected-img').addClass('not-selected-img');
													$(this).removeClass('not-selected-img').addClass('selected-img');
												}
											}); 
										   
									</script>
									<?php } else { ?>
									<script>
										
											$('label.optid-<?php echo $option['option_id'];?>').click(function(){
												
												if ($(this).find('input[type=radio]').is('input:disabled')) {
													$('label.selected').removeClass('selected').addClass('not-selected');
													$(this).css({
														'opacity': 0.5,
														'cursor': 'default'
													});
													} else {
													$('label.optid-<?php echo $option['option_id'];?>').removeClass('selected').addClass('not-selected');
													$(this).removeClass('not-selected').addClass('selected');
												}
											}); 
									</script>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					<script>
						function oct_option_quantity_minus(e,i) {
							if (~~$(e).next().val() > 0) { 
								$(e).next().val(~~$(e).next().val()-1); 
								$(e).prev().val(i+'|'+$(e).next().val()); 
								$(e).prev().prop('checked', true); 
							} 
							if (~~$(e).next().val() <= 0) { 
								$(e).prev().removeAttr('checked'); 
							} 
						}
						
						function oct_option_quantity_manual(e,i) {
							$(e).prev().prev().val(i+'|'+$(e).val());
							$(e).prev().prev().prop('checked', true); 
							if ($(e).val() <= 0) { 
								$(e).prev().prev().removeAttr('checked'); 
							}
						}
						
						function oct_option_quantity_plus(e,i) {
							$(e).prev().val(~~$(e).prev().val()+1); 
							$(e).prev().prev().prev().val(i+'|'+$(e).prev().val()); 
							$(e).prev().prev().prev().prop('checked', true); 
						}
					</script>
					<!-- if options end -->
				<?php } ?>
				<script><!--
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

					let clickCount = localStorage.getItem('buttonClickCount') || 0;
					
					$('#popup-checkout-button').on('click', function() {
						masked('#product-options-popup', true);
						if (clickCount < 3) {
                    clickCount++;
                    localStorage.setItem('buttonClickCount', clickCount);
                    // alert('Вы нажимали на кнопку' + clickCount + 'раз!');
						$.ajax({
							type: 'post',
							url:  'index.php?route=extension/module/oct_popup_product_options/add',
							dataType: 'json',
							data: $('#product-options-popup #purchase-form input[type=\'text\']:not(\'.oct-quantity-text-input\'), #product-options-popup #purchase-form input[type=\'hidden\'], #product-options-popup #purchase-form input[type=\'radio\']:checked, #product-options-popup #purchase-form input[type=\'checkbox\']:checked, #product-options-popup #purchase-form select, #product-options-popup #purchase-form textarea'),
							success: function(json) {
								$('#product-options-popup .alert, #product-options-popup .text-danger').remove();
								if (json['error']) {
									if (json['error']['option']) {
										masked('#product-options-popup', false);
										for (i in json['error']['option']) {
											var element = $('#product-options-popup #options-popup-input-option' + i.replace('_', '-'));
											element.after('<div class="text-danger text-center" style="font-size:15px;">' + json['error']['option'][i] + '</div>');
										}
									}
									} else {
									if (json['success']) {
										masked('#product-options-popup', false);
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
										
										// $.magnificPopup.open({
										//   tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
										//   items: {
										//     src: "index.php?route=extension/module/oct_popup_add_to_cart&product_id=" + <?php echo $product_id; ?>,
										//     type: "ajax"
										//   },
										//   midClick: true,
										//   removalDelay: 200
										// });
										
										get_oct_popup_cart();
									}
								}
							}
						});
					} else {
                    $(this).prop('disabled', true);
                    alert('Подозрительная активность!');
                }
					});
					//-->
				</script>
			<?php } ?>
		</div>						