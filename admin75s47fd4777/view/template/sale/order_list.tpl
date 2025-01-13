<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">

		<?php if ($aqe_enabled) { ?><label for="batch_edit" class="hidden" id="batch-edit-container"><input type="checkbox" id="batch-edit"<?php echo ($batch_edit) ? ' checked' : ''; ?>> <?php echo $text_batch_edit; ?></label><?php } ?>
			
				<button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
				<button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" formtarget="_blank" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
				<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
				<button type="button" id="button-delete" form="form-order" formaction="<?php echo $delete; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>

		<?php if ($aqe_enabled) { ?>
  <div class="alerts">
	<div class="container-fluid" id="alerts">
	</div>
  </div>
		<?php } ?>
			
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
								<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
								<input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
								<select name="filter_order_status" id="input-order-status" class="form-control">
									<option value="*"></option>
									<?php if ($filter_order_status == '0') { ?>
										<option value="0" selected="selected"><?php echo $text_missing; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_missing; ?></option>
									<?php } ?>
									<?php foreach ($order_statuses as $order_status) { ?>
										<?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
								<input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
								<div class="input-group date">
									<input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span></div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
								<div class="input-group date">
									<input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span></div>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
						</div>
					</div>
				</div>
				<form method="post" action="" enctype="multipart/form-data" id="form-order">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table_products">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked).trigger('change');" /></td>				  
									<td class="text-right"><?php if ($sort == 'o.order_id') { ?>
										<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
									<?php } ?></td>
									<td class="text-left">Товары</td>
									<td class="text-left"></td>
									<td class="text-left"><?php if ($sort == 'customer') { ?>
										<a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
									<?php } ?></td>
									<td class="text-left"></td>
									<td class="text-left"><?php if ($sort == 'order_status') { ?>
										<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
									<?php } ?></td>
									<td class="text-right"><?php if ($sort == 'o.total') { ?>
										<a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
									<?php } ?></td>
									<td class="text-left"><?php if ($sort == 'o.date_added') { ?>
										<a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
									<?php } ?></td>
									<td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
										<a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
									<?php } ?></td>
									<td class="text-right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							
							<tbody>
								<?php if ($orders) { ?>
									<?php foreach ($orders as $order) { ?>
										<tr>
											<td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
												<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
											<?php } ?>
											<input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" /></td>
											<td class="text-right">
												<strong><?php echo $order['order_id']; ?></strong>
												<?php if ($order['system_num']) { ?>
												<br /><span class="label label-warning"><?php echo $order['system_num']; ?></span>
												<?php } ?>
											</td>
											<td class="text-right">
												<div class="row">
													<?php foreach ($order['products'] as $product) { ?>
														<div class="col-md-4">
															<img class="img-rounded" data-toggle="tooltip" data-html="true" src="<? echo $product['thumb']; ?>" title="<img src='<? echo $product['image']; ?>' /><br /><? echo $product['name']; ?>" />

															<?php if ($product['excluded_tax_class_id']) { ?>
																<span class="label label-danger">НДС</span>
															<?php } ?>
															<br />

														</div>
													<? } ?>
												</div>
											</td>
											<td class="text-center">
												<i class="fa fa-mobile-phone <?php echo $order['donotcall']?'text-danger':'text-success'; ?>" style="font-size:26px;"></i>																							
											</td>
											<td class="text-left">
												<?php if ($order['customer_href']) { ?>
													<a href="<?php echo $order['customer_href']; ?>" target="_blank"><?php echo $order['customer']; ?></a><br />
													<?php } else { ?>
													<?php echo $order['customer']; ?><br />
												<?php } ?>
												<?php if ($order['customer_id']) { ?>
													<code><?php echo $order['customer_id']; ?></code>
												<?php } ?>
												<code><?php echo $order['telephone']; ?></code>
											</td>
											<td class="text-left">
												<?php if ($order['shipping_country']) {  ?>
													<span class="label label-info"><?php echo $order['shipping_country']; ?></span>
												<? } ?>
												<?php if ($order['shipping_zone']) {  ?>
													<span class="label label-success"><?php echo $order['shipping_zone']; ?></span>
												<? } ?>
												<?php if ($order['shipping_city']) {  ?>
													<span class="label label-warning"><?php echo $order['shipping_city']; ?></span><br />
												<? } ?>
												<?php if ($order['shipping_postcode']) {  ?>
													<span class="label label-danger"><?php echo $order['shipping_postcode']; ?></span><br />
												<? } ?>
												<?php if ($order['shipping_method']) {  ?>
													<small><?php echo preg_replace("/<img[^>]+\>/i", "", $order['shipping_method']); ?></small>
												<?php } ?>
												<?php if ($order['track_no']) {  ?>
													<br /><span class="label label-danger"><?php echo $order['track_no']; ?></label>
												<?php } ?>
											</td>
											<td class="text-left" style="width:120px;">
												<p id="order_name_<?php echo $order['order_id']; ?>"><?php echo $order['order_status']; ?></p>
												<? /*
													<select class="change_status" id="change_status_<?php echo $order['order_id']; ?>" onchange="addOrderStatus(<?php echo $order['order_id']; ?>, $(this).val())">
													<?php foreach($order_statuses as $status){ ?>
													<option value="<?php echo $status['order_status_id']; ?>" <?php if($order['order_status'] == $status['name']) echo 'selected'; ?>><?php echo $status['name']; ?></option>
													<?php } ?>
													</select>
													<a class="close_edit_order fa fa-times" id="close_edit_<?php echo $order['order_id']; ?>" onclick="$('#order_name_<?php echo $order['order_id']; ?>').show(); $('#close_edit_<?php echo $order['order_id']; ?>').hide(); $('#change_status_<?php echo $order['order_id']; ?>').hide();"></a>
													<a class="edit_order fa fa-pencil" id="edit_order_<?php echo $order['order_id']; ?>" onclick="$('#order_name_<?php echo $order['order_id']; ?>').hide(); $('#edit_order_<?php echo $order['order_id']; ?>').hide(); $('#close_edit_<?php echo $order['order_id']; ?>').show(); $('#change_status_<?php echo $order['order_id']; ?>').show();"> <?php echo $button_edit; ?></a>		
													*/ ?>
													</td>
												<td class="text-right"><?php echo $order['total']; ?></td>
												<td class="text-left"><?php echo $order['date_added']; ?></td>
												<td class="text-left"><?php echo $order['date_modified']; ?></td>
												<td class="text-right">
													
													<a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a> <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
													<?php if ($order['customer_id']) { ?>	
														<div class="btn-group" data-toggle="tooltip" title="Войти в магазин">
															<button type="button" data-toggle="dropdown" class="btn btn-info dropdown-toggle"><i class="fa fa-lock"></i></button>
															<ul class="dropdown-menu pull-right">
																<li><a href="index.php?route=customer/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $order['customer_id']; ?>&store_id=0" target="_blank"><?php echo $text_default; ?></a></li>
																<?php foreach ($stores as $store) { ?>
																	<li><a href="index.php?route=customer/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $order['customer_id']; ?>&store_id=<?php echo $store['store_id']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
																<?php } ?>
															</ul>
														</div>
													<?php } ?>
												</td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
				<!---//*** mf begin -->
				<script>
					function addOrderStatus(order_id, status_id){
						$.ajax({
							url: 'index.php?route=sale/order/addorderstatus&token=<?php echo $token; ?>',
							type: 'post',
							data: { order_id: order_id, status_id: status_id },
							success: function(data) {
								$("#order_name_"+order_id).html($("#change_status_"+order_id+" option:selected").text());
								$("#order_name_"+order_id).show();
								$("#change_status_"+order_id).hide();
								$("#close_edit_"+order_id).hide();
								$("#edit_order"+order_id).hide();
							},
							error: function() {
								
							}
						});					  
					}
				</script>
				<!---//*** mf end -->		
				<div class="row">
					<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
					<div class="col-sm-6 text-right"><?php echo $results; ?></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		$('#button-filter').on('click', function() {
			url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
			
			var filter_order_id = $('input[name=\'filter_order_id\']').val();
			
			if (filter_order_id) {
				url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
			}
			
			var filter_customer = $('input[name=\'filter_customer\']').val();
			
			if (filter_customer) {
				url += '&filter_customer=' + encodeURIComponent(filter_customer);
			}
			
			var filter_order_status = $('select[name=\'filter_order_status\']').val();
			
			if (filter_order_status != '*') {
				url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
			}
			
			var filter_total = $('input[name=\'filter_total\']').val();
			
			if (filter_total) {
				url += '&filter_total=' + encodeURIComponent(filter_total);
			}
			
			var filter_date_added = $('input[name=\'filter_date_added\']').val();
			
			if (filter_date_added) {
				url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
			}
			
			var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
			
			if (filter_date_modified) {
				url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
			}
			
			location = url;
		});
	//--></script>
	<script type="text/javascript"><!--
		$('input[name=\'filter_customer\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['customer_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'filter_customer\']').val(item['label']);
			}
		});
	//--></script>
	<script type="text/javascript"><!--
		$('input[name^=\'selected\']').on('change', function() {
			$('#button-shipping, #button-invoice').prop('disabled', true);
			
			var selected = $('input[name^=\'selected\']:checked');
			
			if (selected.length) {
				$('#button-invoice').prop('disabled', false);
			}
			
			for (i = 0; i < selected.length; i++) {
				if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
					$('#button-shipping').prop('disabled', false);
					
					break;
				}
			}
		});
		
		$('#button-shipping, #button-invoice').prop('disabled', true);
		
		$('input[name^=\'selected\']:first').trigger('change');
		
		// IE and Edge fix!
		$('#button-shipping, #button-invoice').on('click', function(e) {
			$('#form-order').attr('action', this.getAttribute('formAction'));
		});
		
		$('#button-delete').on('click', function(e) {
			$('#form-order').attr('action', this.getAttribute('formAction'));
			
			if (confirm('<?php echo $text_confirm; ?>')) {
				$('#form-order').submit();
				} else {
				return false;
			}
		});
	//--></script> 
	<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
	<script type="text/javascript"><!--
		$('.date').datetimepicker({
			pickTime: false
		});
	//--></script></div>
	<?php echo $footer; ?>				