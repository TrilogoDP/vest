<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="row">
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
			<br>
			<h1><i class="fa fa-truck"></i> <?php echo $heading_title; ?> v. <?php echo $v; ?></h1>
			<div class="pull-right">
				<div class="btn-group">
					<a href="<?php echo $customized_printing; ?>" target="_blank" id="button-customized-printing" data-toggle="tooltip" title="<?php echo $text_customized_printing; ?>" class="btn btn-default" disabled="disabled" role="button"><i class="fa fa-print"></i></a>
					<button type="button" id="button-html-caret" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled"><span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li class="dropdown-header"><i class="fa fa-file-pdf-o fa-fw"></i> <?php echo $text_download_pdf; ?></li>
						<li><a href="<?php echo $print_cn_a4_pdf; ?>" target="_blank" id="button-pdf-cn-a4"><?php echo $text_cn_a4; ?></a></li>
						<li><a href="<?php echo $print_cn_a5_pdf; ?>" target="_blank" id="button-pdf-cn-a5"><?php echo $text_cn_a5; ?></a></li>
						<li><a href="<?php echo $print_mark_85_85_pdf; ?>" target="_blank" id="button-pdf-m85x85"><?php echo $text_mark_85_85; ?></a></li>
						<li><a href="<?php echo $print_mark_100_100_pdf; ?>" target="_blank" id="button-pdf-m100x100"><?php echo $text_mark_100_100; ?></a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header"><i class="fa fa-print fa-fw"></i> <?php echo $text_print_html; ?></li>
						<li><a href="<?php echo $print_cn_a4_html; ?>" target="_blank" id="button-html-cn-a4"><?php echo $text_cn_a4; ?></a></li>
						<li><a href="<?php echo $print_cn_a5_html; ?>" target="_blank" id="button-html-cn-a5"><?php echo $text_cn_a5; ?></a></li>
						<li><a href="<?php echo $print_mark_85_85_html; ?>" target="_blank" id="button-html-m85x85"><?php echo $text_mark_85_85; ?></a></li>
						<li><a href="<?php echo $print_mark_100_100_html; ?>" target="_blank" id="button-html-m100x100"><?php echo $text_mark_100_100; ?></a></li>
					</ul>
				</div>
				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle" id="button-service" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled="disabled"><i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a onclick="addToRegistry(this);" style="cursor: pointer;" id="button-add-to-registry"><i class="fa fa-indent text-info fa-fw" aria-hidden="true"></i> <?php echo $text_add_to_registry; ?></a></li>
						<li><a onclick="deleteFromRegistry(this);" style="cursor: pointer;" id="button-delete-from-registry"><i class="fa fa-outdent text-warning fa-fw" aria-hidden="true"></i> <?php echo $text_delete_from_registry; ?></a></li>
						<li><a onclick="printSettings(this);" style="cursor: pointer;" id="button-print"><i class="fa fa-print fa-fw"></i> <?php echo $text_print_settings; ?></a></li>
						<li><a onclick="deleteCN(this);" style="cursor: pointer;" id="button-delete"><i class="fa fa-trash-o text-danger fa-fw"></i> <?php echo $text_delete; ?></a></li>
					</ul>
				</div>
				<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary" role="button"><i class="fa fa-plus"></i></a>
				<a href="<?php echo $back_to_orders; ?>" data-toggle="tooltip" title="<?php echo $button_back_to_orders; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
		</div>
	</div>
  	<div class="container-fluid">
   		<?php if ($success) { ?>
    		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      			<button type="button" class="close" data-dismiss="alert">&times;</button>
    		</div>
    	<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_consignment_note_list; ?></h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<label class="control-label" for="input-filter_cn_type"><?php echo $entry_cn_number; ?></label>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="input-filter_cn_type"><?php echo $entry_cn_type; ?></label>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="input-filter_departure_date_from"><?php echo $entry_departure_date; ?></label>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<input type="text" name="filter_cn_number" value="<?php echo $filter_cn_number; ?>" placeholder="<?php echo $entry_cn_number; ?>" id="input-filter_cn_number" class="form-control" />
						</div>
						<div class="col-sm-4">
							<select name="filter_cn_type" id="input-filter_cn_type" class="form-control selectpicker show-tick" title="<?php echo $text_select; ?>" multiple>
								<?php foreach ($filters as $k => $v) { ?>
									<?php if (in_array($k, $filter_cn_type)) { ?>
										<option value="<?php echo $k; ?>" selected><?php echo $v; ?></option>
									<?php } else { ?>
										<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
						<div class="col-sm-2">
							<div class="input-group date">
								<input type="text" name="filter_departure_date_from" value="<?php echo $filter_departure_date_from; ?>" placeholder="<?php echo $entry_departure_date; ?>" data-date-format="DD.MM.YYYY" id="input-filter_departure_date_from" class="form-control" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="input-group date">
								<input type="text" name="filter_departure_date_to" value="<?php echo $filter_departure_date_to; ?>" placeholder="<?php echo $entry_departure_date; ?>" data-date-format="DD.MM.YYYY" id="input-filter_departure_date_to" class="form-control" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-sm-4 col-sm-offset-8">
								<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
							</div>
						</div>
					</div>
				</div>
				<form method="post" enctype="multipart/form-data" id="form">
					<div class="table-responsive" style="overflow-y:visible; overflow-x:visible;">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name^=selected]').prop('checked', this.checked); $('input[name^=selected]').triggerHandler('change');" /></td>
									<td<?php if (!in_array('cn_identifier', $displayed_information)) { echo ' style="display: none"'; } ?>><?php echo $column_cn_identifier; ?></td>
									<?php if (in_array('cn_number', $displayed_information)) { ?>
									<td><?php echo $column_cn_number; ?></td>
									<?php } ?>
									<?php if (in_array('order_number', $displayed_information)) { ?>
									<td><?php echo $column_order_number; ?></td>
									<?php } ?>
									<?php if (in_array('registry', $displayed_information)) { ?>
									<td><?php echo $column_registry; ?></td>
									<?php } ?>
									<?php if (in_array('create_date', $displayed_information)) { ?>
									<td><?php echo $column_create_date; ?></td>
									<?php } ?>
									<?php if (in_array('actual_shipping_date', $displayed_information)) { ?>
									<td><?php echo $column_actual_shipping_date; ?></td>
									<?php } ?>
									<?php if (in_array('preferred_shipping_date', $displayed_information)) { ?>
									<td><?php echo $column_preferred_shipping_date; ?></td>
									<?php } ?>
									<?php if (in_array('estimated_shipping_date', $displayed_information)) { ?>
									<td><?php echo $column_estimated_shipping_date; ?></td>
									<?php } ?>
									<?php if (in_array('recipient_date', $displayed_information)) { ?>
									<td><?php echo $column_recipient_date; ?></td>
									<?php } ?>
									<?php if (in_array('last_updated_status_date', $displayed_information)) { ?>
									<td><?php echo $column_last_updated_status_date; ?></td>
									<?php } ?>
									<?php if (in_array('sender', $displayed_information)) { ?>
									<td><?php echo $column_sender; ?></td>
									<?php } ?>
									<?php if (in_array('sender_contact_person', $displayed_information)) { ?>
									<td><?php echo $column_sender_contact_person; ?></td>
									<?php } ?>
									<?php if (in_array('sender_address', $displayed_information)) { ?>
									<td><?php echo $column_sender_address; ?></td>
									<?php } ?>
									<?php if (in_array('recipient', $displayed_information)) { ?>
									<td><?php echo $column_recipient; ?></td>
									<?php } ?>
									<?php if (in_array('recipient_contact_person', $displayed_information)) { ?>
									<td><?php echo $column_recipient_contact_person; ?></td>
									<?php } ?>
									<?php if (in_array('recipient_address', $displayed_information)) { ?>
									<td><?php echo $column_recipient_address; ?></td>
									<?php } ?>
									<?php if (in_array('weight', $displayed_information)) { ?>
									<td><?php echo $column_weight; ?></td>
									<?php } ?>
									<?php if (in_array('seats_amount', $displayed_information)) { ?>
									<td><?php echo $column_seats_amount; ?></td>
									<?php } ?>
									<?php if (in_array('declared_cost', $displayed_information)) { ?>
									<td><?php echo $column_declared_cost; ?></td>
									<?php } ?>
									<?php if (in_array('payment_control', $displayed_information)) { ?>
									<td><?php echo $column_payment_control; ?></td>
									<?php } ?>
									<?php if (in_array('shipping_cost', $displayed_information)) { ?>
									<td><?php echo $column_shipping_cost; ?></td>
									<?php } ?>
									<?php if (in_array('backward_delivery', $displayed_information)) { ?>
									<td><?php echo $column_backward_delivery; ?></td>
									<?php } ?>
									<?php if (in_array('service_type', $displayed_information)) { ?>
									<td><?php echo $column_service_type; ?></td>
									<?php } ?>
									<?php if (in_array('description', $displayed_information)) { ?>
									<td><?php echo $column_description; ?></td>
									<?php } ?>
									<?php if (in_array('additional_information', $displayed_information)) { ?>
									<td><?php echo $column_additional_information; ?></td>
									<?php } ?>
									<?php if (in_array('payer_type', $displayed_information)) { ?>
									<td><?php echo $column_payer_type; ?></td>
									<?php } ?>
									<?php if (in_array('payment_method', $displayed_information)) { ?>
									<td><?php echo $column_payment_method; ?></td>
									<?php } ?>
									<?php if (in_array('departure_type', $displayed_information)) { ?>
									<td><?php echo $column_departure_type; ?></td>
									<?php } ?>
									<?php if (in_array('packing_number', $displayed_information)) { ?>
									<td><?php echo $column_packing_number; ?></td>
									<?php } ?>
									<?php if (in_array('rejection_reason', $displayed_information)) { ?>
									<td><?php echo $column_rejection_reason; ?></td>
									<?php } ?>
									<?php if (in_array('status', $displayed_information)) { ?>
									<td><?php echo $column_status; ?></td>
									<?php } ?>
									<td class="text-center" width="130px"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
							<?php if ($cns) { ?>
								<?php foreach ($cns as $cn) { ?>
									<?php if ($cn['DeletionMark']) { ?>
										<tr class="danger">
									<?php } elseif ($cn['Printed']) { ?>
										<tr class="active">
									<?php } else { ?>
										<tr>
									<?php } ?>
											<td class="text-center">
												<input type="checkbox" name="selected[]" value="<?php echo $cn['IntDocNumber']; ?>" />
											</td>
											<td<?php if (!in_array('cn_identifier', $displayed_information)) { echo ' style="display: none"'; } ?>>
												<?php echo $cn['Ref']; ?>
												<input type="hidden" name="refs[]" value="<?php echo $cn['Ref']; ?>" />
											</td>
											<?php if (in_array('cn_number', $displayed_information)) { ?>
											<td><?php echo $cn['IntDocNumber']; ?></td>
											<?php } ?>
											<?php if (in_array('order_number', $displayed_information)) { ?>
												<td class="text-center">
												<?php if (isset($cn['order'])) { ?>
													<a href="<?php echo $cn['order']; ?>" target="_blank"><?php echo $cn['order_id']; ?></a>
												<?php } ?>
												</td>
											<?php } ?>
											<td<?php if (!in_array('registry', $displayed_information)) { echo ' style="display: none"'; } ?>>
											<?php echo $cn['ScanSheetNumber']; ?>
											<input type="hidden" name="registry[]" value="<?php echo $cn['ScanSheetNumber']; ?>" />
											</td>
											<?php if (in_array('create_date', $displayed_information)) { ?>
											<td><?php echo $cn['create_date']; ?></td>
											<?php } ?>
											<?php if (in_array('actual_shipping_date', $displayed_information)) { ?>
											<td><?php echo $cn['actual_shipping_date']; ?></td>
											<?php } ?>
											<?php if (in_array('preferred_shipping_date', $displayed_information)) { ?>
											<td><?php echo $cn['preferred_shipping_date']; ?></td>
											<?php } ?>
											<?php if (in_array('estimated_shipping_date', $displayed_information)) { ?>
											<td><?php echo $cn['estimated_shipping_date']; ?></td>
											<?php } ?>
											<?php if (in_array('recipient_date', $displayed_information)) { ?>
											<td><?php echo $cn['recipient_date']; ?></td>
											<?php } ?>
											<?php if (in_array('last_updated_status_date', $displayed_information)) { ?>
											<td><?php echo $cn['last_updated_status_date']; ?></td>
											<?php } ?>
											<?php if (in_array('sender', $displayed_information)) { ?>
											<td><?php echo $cn['sender']; ?></td>
											<?php } ?>
											<?php if (in_array('sender_contact_person', $displayed_information)) { ?>
											<td><?php echo $cn['sender_contact_person']; ?></td>
											<?php } ?>
											<?php if (in_array('sender_address', $displayed_information)) { ?>
											<td><?php echo $cn['sender_address']; ?></td>
											<?php } ?>
											<?php if (in_array('recipient', $displayed_information)) { ?>
											<td><?php echo $cn['recipient']; ?></td>
											<?php } ?>
											<?php if (in_array('recipient_contact_person', $displayed_information)) { ?>
											<td><?php echo $cn['recipient_contact_person']; ?></td>
											<?php } ?>
											<?php if (in_array('recipient_address', $displayed_information)) { ?>
											<td><?php echo $cn['recipient_address']; ?></td>
											<?php } ?>
											<?php if (in_array('weight', $displayed_information)) { ?>
											<td><?php echo $cn['Weight']; ?></td>
											<?php } ?>
											<?php if (in_array('seats_amount', $displayed_information)) { ?>
											<td><?php echo $cn['SeatsAmount']; ?></td>
											<?php } ?>
											<?php if (in_array('declared_cost', $displayed_information)) { ?>
											<td><?php echo $cn['declared_cost']; ?></td>
											<?php } ?>
											<?php if (in_array('payment_control', $displayed_information)) { ?>
											<td><?php echo $cn['payment_control']; ?></td>
											<?php } ?>
											<?php if (in_array('shipping_cost', $displayed_information)) { ?>
											<td><?php echo $cn['shipping_cost']; ?></td>
											<?php } ?>
											<?php if (in_array('backward_delivery', $displayed_information)) { ?>
											<td><?php echo $cn['backward_delivery']; ?></td>
											<?php } ?>
											<?php if (in_array('service_type', $displayed_information)) { ?>
											<td><?php echo $cn['service_type']; ?></td>
											<?php } ?>
											<?php if (in_array('description', $displayed_information)) { ?>
											<td><?php echo $cn['Description']; ?></td>
											<?php } ?>
											<?php if (in_array('additional_information', $displayed_information)) { ?>
											<td><?php echo $cn['AdditionalInformation']; ?></td>
											<?php } ?>
											<?php if (in_array('payer_type', $displayed_information)) { ?>
											<td><?php echo $cn['payer_type']; ?></td>
											<?php } ?>
											<?php if (in_array('payment_method', $displayed_information)) { ?>
											<td><?php echo $cn['payment_method']; ?></td>
											<?php } ?>
											<?php if (in_array('departure_type', $displayed_information)) { ?>
											<td><?php echo $cn['departure_type']; ?></td>
											<?php } ?>
											<?php if (in_array('packing_number', $displayed_information)) { ?>
											<td><?php echo $cn['PackingNumber']; ?></td>
											<?php } ?>
											<?php if (in_array('rejection_reason', $displayed_information)) { ?>
											<td><?php echo $cn['RejectionReason']; ?></td>
											<?php } ?>
											<?php if (in_array('status', $displayed_information)) { ?>
											<td><?php echo $cn['status']; ?></td>
											<?php } ?>
											<td class="text-center">
												<div class="btn-group">
													<a href="<?php echo $customized_printing, '/orders[]/', $cn['IntDocNumber']; ?>" id="button-customized-printing-<?php echo $cn['IntDocNumber']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $text_customized_printing; ?>" class="btn btn-default btn-sm" role="button"><i class="fa fa-print"></i></a>
													<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li class="dropdown-header"><i class="fa fa-file-pdf-o fa-fw"></i> <?php echo $text_download_pdf; ?></li>
														<li><a href="<?php echo $print_cn_a4_pdf, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_cn_a4; ?></a></li>
														<li><a href="<?php echo $print_cn_a5_pdf, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_cn_a5; ?></a></li>
														<li><a href="<?php echo $print_mark_85_85_pdf, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_mark_85_85; ?></a></li>
														<li><a href="<?php echo $print_mark_100_100_pdf, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_mark_100_100; ?></a></li>
														<li><a href="<?php echo $print_registry_pdf, '/refs[]/', $cn['ScanSheetNumber']; ?>" target="_blank"><?php echo $text_registry; ?></a></li>
														<li role="separator" class="divider"></li>
                                                        <li class="dropdown-header"><i class="fa fa-print fa-fw"></i> <?php echo $text_print_html; ?></li>
														<li><a href="<?php echo $print_cn_a4_html, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_cn_a4; ?></a></li>
														<li><a href="<?php echo $print_cn_a5_html, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_cn_a5; ?></a></li>
														<li><a href="<?php echo $print_mark_85_85_html, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_mark_85_85; ?></a></li>
														<li><a href="<?php echo $print_mark_100_100_html, '/orders[]/', $cn['IntDocNumber']; ?>" target="_blank"><?php echo $text_mark_100_100; ?></a></li>
														<li><a href="<?php echo $print_registry_html, '/refs[]/', $cn['ScanSheetNumber']; ?>" target="_blank"><?php echo $text_registry; ?></a></li>
													</ul>
												</div>
												<div class="btn-group">
													<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i> <span class="caret"></span></button>
													<ul class="dropdown-menu dropdown-menu-right">
														<?php if ($cn['ScanSheetNumber']) { ?>
														<li><a onclick="deleteFromRegistry(this);" style="cursor: pointer;"><i class="fa fa-outdent text-warning fa-fw" aria-hidden="true"></i> <?php echo $text_delete_from_registry; ?></a></li>
														<?php } else { ?>
														<li><a onclick="addToRegistry('<?php echo $cn['IntDocNumber']; ?>');" style="cursor: pointer;"><i class="fa fa-indent text-info fa-fw" aria-hidden="true"></i> <?php echo $text_add_to_registry; ?></a></li>
														<?php } ?>
														<li><a href="<?php echo $add, '&cn_ref=', $cn['Ref']; ?>"><i class="fa fa-pencil text-primary fa-fw"></i> <?php echo $text_edit; ?></a></li>
														<?php if (!isset($cn['order'])) { ?>
														<li><a onclick="assignmentOrder('<?php echo $cn['IntDocNumber']; ?>', '<?php echo $cn['Ref']; ?>');" style="cursor: pointer;"><i class="fa fa-plus-square text-success fa-fw" aria-hidden="true"></i> <?php echo $text_assignment_order; ?></a></li>
														<?php } ?>
														<li><a onclick="printSettings(this);" style="cursor: pointer;"><i class="fa fa-print fa-fw"></i> <?php echo $text_print_settings; ?></a></li>
														<li><a onclick="deleteCN(this);" style="cursor: pointer;"><i class="fa fa-trash-o text-danger fa-fw"></i> <?php echo $text_delete; ?></a></li>
													</ul>
												</div>
											</td>
										</tr>
									<?php } ?>
								<?php } else { ?>
									<tr>
										<td class="text-center" colspan="<?php echo count($displayed_information) + 2; ?>"><?php echo $text_no_results; ?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
					<div class="col-sm-6 text-right"><?php echo $results; ?></div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal assignment order to CN START -->
	<div class="modal fade" id="modal-assignment-order-to-cn" tabindex="-1" role="dialog" aria-labelledby="modal-assignment-order-to-cn-label">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal-assignment-order-to-cn-label"><?php echo $text_order; ?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group clearfix">
						<input type="hidden" name="cn_number" value="" id="input-cn_number" />
						<input type="hidden" name="cn_ref" value="" id="input-cn_ref" />
						<label class="col-sm-4 control-label" for="input-order_number"><?php echo $entry_order_number; ?></label>
						<div class="col-sm-8">
							<input type="text" name="order_number" value="" placeholder="<?php echo $entry_order_number; ?>" id="input-order_number" class="form-control" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="assignmentOrder();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal assignment order to CN END -->
	<!-- Add to registry START -->
	<div class="modal fade" id="modal-add-to-registry" tabindex="-1" role="dialog" aria-labelledby="modal-add-to-registry-label">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal-add-to-registry-label"><?php echo $heading_adding_to_registry; ?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="cn_number_registry" value="" id="input-cn_number_registry">
					<div class="form-group">
						<label class="control-label" for="input-registry"><?php echo $entry_registry; ?></label>
						<div class="col-sm-12 input-group">
							<select name="registry" id="input-registry" class="form-control selectpicker show-tick">
								<option value=""><?php echo $text_add; ?></option>
								<?php foreach ($registers as $v) { ?>
								<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Number'], ' (', $v['DateTime'], ')'; ?></option>
								<?php } ?>
							</select>
							<span class="input-group-btn" style="display: none;">
								<button type="button" id="button-delete-registry" data-toggle="tooltip" title="<?php echo $button_delete_registry; ?>" class="btn btn-danger" onclick="deleteRegistry();"><i class="fa fa-trash-o fa-fw"></i></button>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="input-registry_date"><?php echo $entry_create_date; ?></label>
						<div class="input-group date">
							<input type="text" name="registry_date" value="" placeholder="<?php echo $entry_create_date; ?>" data-date-format="DD.MM.YYYY" id="input-registry_date" class="form-control" />
							<span class="input-group-btn">
								<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="addToRegistry();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Add to registry END -->
	<!-- Print settings START -->
	<div class="modal fade" id="modal-print-settings" tabindex="-1" role="dialog" aria-labelledby="modal-print-settings-label">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal-print-settings-label"><?php echo $text_print_settings; ?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" value="" id="input-print_button_id">
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label" for="input-print_format"><?php echo $entry_print_format; ?></label>
						<div class="col-sm-8">
							<select name="print_format" id="input-print_format" class="form-control selectpicker show-tick">
								<?php foreach ($print_formats as $k => $v) { ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label" for="input-number_of_copies"><?php echo $entry_number_of_copies; ?></label>
						<div class="col-sm-8">
							<input type="text" name="number_of_copies" value="" placeholder="<?php echo $entry_number_of_copies; ?>" id="input-number_of_copies" class="form-control" />
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label" for="input-template_type"><?php echo $entry_template_type; ?></label>
						<div class="col-sm-8">
							<select name="template_type" id="input-template_type" class="form-control selectpicker show-tick">
								<?php foreach ($template_types as $k => $v) { ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label" for="input-print_type"><?php echo $entry_print_type; ?></label>
						<div class="col-sm-8">
							<select name="print_type" id="input-print_type" class="form-control selectpicker show-tick">
								<?php foreach ($print_types as $k => $v) { ?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label" for="input-print_start"><?php echo $entry_print_start; ?></label>
						<div class="col-sm-8">
							<div class="btn-group-vertical" id="div-vertical-1" data-toggle="buttons">
								<?php for ($i = 1; $i <= 6; $i++) { ?>
								<label class="btn btn-default">
									<input type="radio" name="print_start" value="<?php echo $i; ?>" id="input-print_start-<?php echo $i; ?>" autocomplete="off"><?php echo $i; ?>
								</label>
								<?php } ?>
							</div>
							<div class="btn-group-vertical" id="div-vertical-2" data-toggle="buttons">
								<?php for ($i = 1; $i <= 6; $i++) { ?>
								<label class="btn btn-default">
									<input type="radio" name="print_start" value="<?php echo $i; ?>" id="input-print_start-<?php echo $i; ?>" autocomplete="off"><?php echo $i; ?>
								</label>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="printSettings();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Print settings END -->
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-<?php echo $translations_code; ?>.min.js"></script>
<script type="text/javascript"><!--
	function assignmentOrder(number, ref) {
		if ($('#modal-assignment-order-to-cn').is(':hidden')) {
			$('#input-cn_number').val(number);
			$('#input-cn_ref').val(ref);

			$('#modal-assignment-order-to-cn').modal('show');
		} else {
			var post_data = 'order_id=' + $('#input-order_number').val() + '&cn_number=' + encodeURIComponent($('#input-cn_number').val()) + '&cn_ref=' + encodeURIComponent($('#input-cn_ref').val());

			$.ajax({
				url: 'index.php?route=extension/shipping/novaposhta/addCNToOrder&token=<?php echo $token; ?>',
				type: 'POST',
				data: post_data,
				dataType: 'json',
				success: function(json) {
					if(json['error']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						setTimeout(function() { location.reload(); }, 5000);
					}

					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
			});

			$('#modal-assignment-order-to-cn').modal('hide');
		}
	}

	function addToRegistry(cn_number) {
		if ($('#modal-add-to-registry').is(':hidden')) {
			$('#input-cn_number_registry').val(cn_number);
			$('#input-registry > option:eq(1)').prop('selected', true).trigger('change');
			$('#modal-add-to-registry').modal('show');
		} else {
			if (!confirm('<?php echo $text_confirm; ?>')) {
				return false;
			}

			var
					$selected_cn_mumbers = $('input[name^="selected"]:checked');
					post_data = '&action=add&registry=' + $('#input-registry').val() + '&date=' + encodeURIComponent($('#input-registry_date').val());

			if ($selected_cn_mumbers.length > 0) {
				$selected_cn_mumbers.each(function(i) {
					post_data += '&cns[]=' + $(this).val();
				});
			} else {
				post_data += '&cns[]=' + $('#input-cn_number_registry').val();
			}

			$.ajax({
				type: 'POST',
				url: 'index.php?route=extension/shipping/novaposhta/registryCN&token=<?php echo $token; ?>',
				data: post_data,
				dataType: 'json',
				success: function(json) {
					if (json['error'] && json['error'] instanceof Array) {
						for(var i in json['error']) {
							$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'][i] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					}

					if (json['success']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success']['text'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						if (json['success']['cns'] instanceof Array) {
							for(var i in json['success']['cns']) {
								$('tr:contains("' + json['success']['cns'][i] + '")').addClass('info');
							}
						}
					}
				}
			});

			$('#modal-add-to-registry').modal('hide');
		}
	}

	function deleteFromRegistry(self) {
		if (!confirm('<?php echo $text_confirm; ?>')) {
			return false;
		}

		var post_data = '&action=delete';

		if (self.id == 'button-delete-from-registry') {
			$('input[name^="selected"]:checked').each(function(i) {
				post_data += '&cns[]=' + $(this).val();
			});
		} else {
			post_data += '&cns[]=' + $(self).parents('tr').find('input[name^="selected"]').val();
		}

		$.ajax({
			type: 'POST',
			url: 'index.php?route=extension/shipping/novaposhta/registryCN&token=<?php echo $token; ?>',
			data: post_data,
			dataType: 'json',
			success: function(json) {
				if (json['error'] && json['error'] instanceof Array) {
					for(var i in json['error']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'][i] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}

				if (json['success']) {
					$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success']['text'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					if (json['success']['cns'] instanceof Array) {
						for(var i in json['success']['cns']) {
							$('tr:contains("' + json['success']['cns'][i] + '")').addClass('warning');
						}
					}
				}
			}
		});
	}

	function deleteRegistry() {
		if (!confirm('<?php echo $text_confirm; ?>')) {
			return false;
		}

		var post_data = '&action=delete_registry&registry=' + $('#input-registry').val();

		$.ajax({
			type: 'POST',
			url: 'index.php?route=extension/shipping/novaposhta/registryCN&token=<?php echo $token; ?>',
			data: post_data,
			dataType: 'json',
			success: function(json) {
				if (json['error'] && json['error'] instanceof Array) {
					for(var i in json['error']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'][i] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}

				if (json['success']) {
					$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success']['text'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					if (json['success']['cns'] instanceof Array) {
						for(var i in json['success']['sheets']) {
							$('tr:contains("' + json['success']['sheets'][i] + '")').addClass('warning');
						}
					}
				}
			}
		});
	}

	function printSettings(self) {
		if ($('#modal-print-settings').is(':hidden')) {
			var p_id;

			if (self.id == 'button-print') {
				p_id = 'button-customized-printing';
			} else {
				p_id = $(self).parents('tr').find('a[id^="button-customized-printing"]')[0].id;
			}

			$('#input-print_button_id').val(p_id);

			$('#modal-print-settings').modal('show');
		} else {
			var
					print_type,
					print_format,
					page_format,
					copies,
					print_direction,
					position,
					new_href,
					selected_format = $('#input-print_format').val(),
					template_type = $('#input-template_type').val(),
					number_of_copies = $('#input-number_of_copies').val() || '1';

			if (selected_format == 'document_A4') {
				print_type = 'orders';
				print_format = 'printDocument';
				page_format = 'A4';
				copies = '/copies/' + number_of_copies;
			} else if (selected_format == 'document_A5') {
				print_type = 'orders';
				print_format = 'printDocument';
				page_format = 'A5';
				copies = '/copies/' + number_of_copies;
			} else if (selected_format == 'mark_85_85') {
				print_type = 'orders';
				print_format = 'printMarking85x85';
				copies = '/copies/' + number_of_copies;

				if (template_type == 'html') {
					print_direction = $('#input-print_type').val();
					position = $('input[id^="input-print_start"]:checked').val();
				}
			} else if (selected_format == 'mark_100_100') {
				print_type = 'orders';
				print_format = 'printMarking100x100';
				copies = '/copies/' + number_of_copies;

				if (template_type == 'html') {
					print_direction = $('#input-print_type').val();
					position = $('input[id^="input-print_start"]:checked').val();
				}
			} else if (selected_format == 'registry') {
				print_type = 'scanSheet';
				print_format = 'printScanSheet';
			}

			new_href = 'https://my.novaposhta.ua/' + print_type + '/' + print_format + '/apiKey/<?php echo $key_api; ?>/type/' + template_type;

			if (page_format) {
				new_href += '/pageFormat/' + page_format;
			}

			if (print_direction) {
				new_href += '/printDirection/' + print_direction + '/position/' + position;
			}

			if (copies) {
				new_href += copies;
			}

			if ($('#input-print_button_id').val() == 'button-customized-printing') {
				setTimeout(function () {
					$('input[name^="selected"]').trigger('change');
				}, 1000);
			} else {
				var cn_number = parseInt($('#input-print_button_id').val().replace(/\D/g, ''));

				if (selected_format == 'registry') {
					new_href += '/refs[]/' + $('input[value="' + cn_number + '"]').parents('tr').find('input[name="registry[]"]').val();
				} else {
					new_href += '/orders[]/' + cn_number;
				}
			}

			$('#' + $('#input-print_button_id').val()).attr('href', new_href);

			$('#modal-print-settings').modal('hide');
		}
	}

	function deleteCN(self) {
		if (!confirm('<?php echo $text_confirm; ?>')) {
			return false;
		}

		var post_data;

		if (self.id == 'button-delete') {
			post_data = $('input[name^="selected"]:checked').parents('tr').find('input[name^="refs"]').serialize();

			$('input[name^="selected"]:checked').parents('tr').find('a[href*="order_id"]').each(function(i) {
				post_data += '&orders[]=' + $(this).text();
			});
		} else {
			post_data = $(self).parents('tr').find('input[name^="refs"]').serialize();
			post_data += '&orders[]=' + $(self).parents('tr').find('a[href*="order_id"]').text();
		}

		$.ajax({
			type: 'POST',
			url: 'index.php?route=extension/shipping/novaposhta/deleteCN&token=<?php echo $token; ?>',
			data: post_data,
			dataType: 'json',
			beforeSend: function () {
				$(self).find('i').addClass('fa-spin');
				$(self).parents('div.btn-group').find('i').addClass('fa-spin');
			},
			complete: function () {
				var $alerts = $('.alert-danger, .alert-success');

				if ($alerts.length !== 0) {
					setTimeout(function() { $alerts.fadeOut(); }, 5000);
				}

				$(self).find('i').removeClass('fa-spin');
				$(self).parents('div.btn-group').find('i').removeClass('fa-spin');
			},
			success: function(json) {
				if (json['success']) {
					for(var i in json['success']['refs']) {
						$('input[value ="' + json['success']['refs'][i]['Ref'] + '"]').parents('tr').fadeOut('slow');
					}

					$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success']['text'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['warning']) {
					for(var w in json['warning']) {
						$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'][w] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			}
		});
	}

	function formHandler(element) {
		switch (element.id) {
			case 'input-registry':
					if (element.value) {
						$('label[for="input-registry_date"]').parent('.form-group').filter(':visible').fadeOut();
						$('#button-delete-registry').parents('span').filter(':hidden').fadeIn();
					} else {
						$('label[for="input-registry_date"]').parent('.form-group').filter(':hidden').fadeIn();
						$('#button-delete-registry').parents('span').filter(':visible').fadeOut();
					}

				break;
			case 'input-print_format':
			case 'input-template_type':
				var
						print_format = $('#input-print_format').val(),
						template_type = $('#input-template_type').val();

				if (print_format == 'mark_85_85' || print_format == 'mark_100_100') {
					$('#input-number_of_copies, #input-template_type').parents('div.form-group').fadeIn();

					if (template_type == 'html') {
						$('#input-print_type, input[id^="input-print_start"]').parents('div.form-group').fadeIn();
					} else {
						$('#input-print_type, input[id^="input-print_start"]').parents('div.form-group').fadeOut();
					}
				} else if (print_format == 'registry') {
					$('#input-number_of_copies, #input-print_type, input[id^="input-print_start"]').parents('div.form-group').fadeOut();
				} else {
					$('#input-number_of_copies, #input-template_type').parents('div.form-group').fadeIn();
					$('#input-print_type, input[id^="input-print_start"]').parents('div.form-group').fadeOut();
				}

				break;

			case 'input-print_type':
				var
					$print_start_1 = $('#div-vertical-1'),
					$print_start_2 = $('#div-vertical-2')

				if (element.value == 'horPrint') {
					$print_start_1.find('label:odd').hide();
					$print_start_1.find('label:even').show();
					$print_start_2.find('label:odd').show();
					$print_start_2.find('label:even').hide();
				} else {
					$print_start_1.find('label:lt(3)').show();
					$print_start_1.find('label:gt(2)').hide();
					$print_start_2.find('label:lt(3)').hide();
					$print_start_2.find('label:gt(2)').show();
				}

				break;
		}
	}

	$(function() {
		/* START Preparation */
		$.ajaxSetup({
			beforeSend: function (jqXHR, settings) {
				if (settings.url.match(/novaposhta/i)) {
					$('body').fadeTo('fast', 0.7).prepend('<div id="ocmax-loader" style="position: fixed; top: 50%;	left: 50%; z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-3x fa-fw"></i></div>');
				}
			},
			complete: function () {
				var $alerts = $('.alert-danger, .alert-success');

				if ($alerts.length !== 0) {
					setTimeout(function() { $alerts.fadeOut(); }, 10000);
				}

				$('body').fadeTo('fast', 1);
				$('#ocmax-loader').remove();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});

		$('.selectpicker').selectpicker({
			liveSearchStyle: 'startsWith',
			iconBase: 'fa',
			tickIcon: 'fa-check'
		});

		$('.date').datetimepicker({
			pickTime: false
		});
		/* END Preparation */

		if ('<?php echo $cn_number; ?>') {
			$('tr:contains("<?php echo $cn_number; ?>")').addClass('success');
		}

		$('input[name^="selected"]').on('change', function(e) {
			var
				orders = '',
				selected = $('input[name^="selected"]:checked');

			for(var i = 0; i < selected.length; i++) {
				orders += '/orders[]/' + selected[i].value;
			}

			$('#button-customized-printing, a[id^="button-pdf"], a[id^="button-html"]').each( function(indx) {
				$(this).attr('href', $(this).attr('href').replace(/\/orders\[\]\/.*/g, ''));
				$(this).attr('href', $(this).attr('href') + orders);
			});

			if (selected.length) {
				$('#button-customized-printing, [id^="button-pdf"], [id^="button-html"], #button-service').attr('disabled', false);
			} else {
				$('#button-customized-printing, [id^="button-pdf"], [id^="button-html"], #button-service').attr('disabled', true);
			}
		});

		$('#button-filter').on('click', function() {
			var
				url = 'index.php?route=extension/shipping/novaposhta/getCNList&token=<?php echo $token; ?>',
				filter_cn_number = $('#input-filter_cn_number').val(),
				filter_cn_type = $('#input-filter_cn_type').val(),
				filter_departure_date_from = $('#input-filter_departure_date_from').val(),
				filter_departure_date_to = $('#input-filter_departure_date_to').val();

			if (filter_cn_number) {
				url += '&filter_cn_number=' + encodeURIComponent(filter_cn_number);
			}

			if (filter_cn_type) {
				for (var i in filter_cn_type) {
					url += '&filter_cn_type[]=' + encodeURIComponent(filter_cn_type[i]);
				}
			}

			if (filter_departure_date_from) {
				url += '&filter_departure_date_from=' + encodeURIComponent(filter_departure_date_from);
			}

			if (filter_departure_date_to) {
				url += '&filter_departure_date_to=' + encodeURIComponent(filter_departure_date_to);
			}

			location = url;
		});

		$('#input-print_format, #input-template_type, #input-print_type').each(function() {
			formHandler(this);
		});

		$('input, select, textarea').on('change', function(e) {
			setTimeout(formHandler, 200, this);
		});

		$('#div-vertical-1, #div-vertical-2').on('click', function (e) {
			$('#div-vertical-1, #div-vertical-2').not('#' + e.currentTarget.id).find('label').removeClass('active').find('input').removeAttr('checked');
		});
	} );
//--></script>
<?php echo $footer; ?>