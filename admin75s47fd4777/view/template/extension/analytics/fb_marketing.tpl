<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="save-settings-button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      	<i class="fa fa-code pull-right text-muted" data-toggle="modal" data-target="#settingsModal"></i>
      </div>
      <div class="panel-body">
       <form method="post" enctype="multipart/form-data" id="form-fb-marketing" class="form-horizontal">
          <!-- Navigation Buttons -->
          <div class="col-md-2">
			<ul class="nav nav-pills nav-stacked" id="configTabs">
				<li class="active"><a href="#pixel-config" data-toggle="pill" ><?php echo $text_pixel_configuration; ?></a></li>
				<li><a href="#advanced_matching_data" data-toggle="pill"><?php echo $text_advanced_matching_data; ?></a></li>
				<li class="hidden"><a href="#marketing-api" data-toggle="pill"><?php echo $text_marketing_api; ?></a></li>
				<li><a href="#about" data-toggle="pill"><?php echo $text_about; ?></a></li>
			</ul>
		  </div>
          <!-- Content -->
		  <div class="col-md-10">
			<div class="tab-content">
          		<div class="tab-pane fade in active" id="pixel-config">
          		  <ul class="nav nav-tabs">
					 <?php foreach ($stores as $store) { ?>
						 <li><a href="#tab-store<?php echo $store['store_id']; ?>" data-toggle="tab"><?php echo $store['name']; ?></a></li>
					<?php } ?>
				  </ul>
	 			  <div class="tab-content">	
	 			  		<?php if(!empty($_GET['debug'])) {
	 			  			ini_set('xdebug.var_display_max_depth', 5);
							ini_set('xdebug.var_display_max_children', 256);
							ini_set('xdebug.var_display_max_data', 1024);
							
							var_dump($fb_marketing_advanced_matching_data);
	 			  			echo '<br />';
	 			  			var_dump($fb_marketing_status);
	 			  			echo '<br />';
	 			  			var_dump($fb_marketing_pixel_id);
	 			  			echo '<br />';
	 			  			var_dump($fb_marketing_product_catalog_id);
	 			  			echo '<br />';
	 			  			var_dump($fb_marketing_advanced_matching);
	 			  			echo '<br />';
	 			  			var_dump($fb_marketing_events);
	 			  			echo '<br />';
	 			  		
	 			  		    }
	 			  		?>
	 			  		<?php $pixel_event_row = array(); ?>
					  <?php foreach ($stores as $store) { ?>
					  <div class="tab-pane fade" id="tab-store<?php echo $store['store_id']; ?>">
						  <div class="form-group">
							<label class="col-sm-2 control-label" for="fb-marketing-status<?php echo $store['store_id']; ?>"><?php echo $entry_status; ?></label>
							<div class="col-sm-10">
							 <input type="checkbox" <?php echo (!empty($fb_marketing_status[$store['store_id']]) ? 'checked="checked"' : '' ); ?> id="fb_marketing_status<?php echo $store['store_id']; ?>" value="<?php echo !empty($fb_marketing_status[$store['store_id']]) ? '1' : '0' ; ?>" name="fb_marketing_status[<?php echo $store['store_id']; ?>]" class="form-control" data-toggle="toggle" data-on="<?php echo $text_enabled; ?>" data-off="<?php echo $text_disabled; ?>" data-onstyle="success" data-offstyle="danger" data-size="large">
							</div>
						  </div>
						
						  <div class="form-group">
							<label for="fb_marketing_pixel_id_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":500}" title="<?php echo htmlspecialchars($help_pixel_id); ?>"><?php echo $entry_pixel_id; ?></span></label>
							<div class="col-sm-10">
							  <input type="text" id="fb_marketing_pixel_id_<?php echo $store['store_id']; ?>" name="fb_marketing_pixel_id[<?php echo $store['store_id']; ?>]" value="<?php echo $fb_marketing_pixel_id[$store['store_id']]; ?>" placeholder="<?php echo $entry_pixel_id; ?>" class="required form-control">
							</div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_product_catalog_id_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":500}" title="<?php echo htmlspecialchars($help_product_catalog_id); ?>"><?php echo $entry_product_catalog_id; ?></span></label>
							<div class="col-sm-10">
							 <input type="text" id="fb_marketing_product_catalog_id_<?php echo $store['store_id']; ?>" name="fb_marketing_product_catalog_id[<?php echo $store['store_id']; ?>]" value="<?php echo $fb_marketing_product_catalog_id[$store['store_id']]; ?>" placeholder="<?php echo $entry_product_catalog_id; ?>" class="required form-control" >
							</div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_advanced_matching_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":5000}" title="<?php echo htmlspecialchars($help_advanced_matching); ?>"><?php echo $entry_advanced_matching; ?></span></label>
							<div class="col-sm-10">
							 <input type="checkbox" <?php echo ($fb_marketing_advanced_matching[$store['store_id']] == 'on' ? 'checked="checked"' : '' ); ?> id="fb_marketing_advanced_matching_<?php echo $store['store_id']; ?>" name="fb_marketing_advanced_matching[<?php echo $store['store_id']; ?>]" class="form-control" data-toggle="toggle">
							</div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_values_vat_inc_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":5000}" title="<?php echo htmlspecialchars($help_values_vat_inc); ?>"><?php echo $entry_values_vat_inc; ?></span></label>
							<div class="col-sm-10">
							 <input type="checkbox" <?php echo ($fb_marketing_values_vat_inc[$store['store_id']] == 'on' ? 'checked="checked"' : '' ); ?> id="fb_marketing_values_vat_inc_<?php echo $store['store_id']; ?>" name="fb_marketing_values_vat_inc[<?php echo $store['store_id']; ?>]" class="form-control" data-toggle="toggle">
							</div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_manual_only_mode_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":5000}" title="<?php echo htmlspecialchars($help_manual_only_mode); ?>"><?php echo $entry_manual_only_mode; ?></span></label>
							<div class="col-sm-10">
							 <input type="checkbox" <?php echo ($fb_marketing_manual_only_mode[$store['store_id']] == 'on' ? 'checked="checked"' : '' ); ?> id="fb_marketing_manual_only_mode_<?php echo $store['store_id']; ?>" name="fb_marketing_manual_only_mode[<?php echo $store['store_id']; ?>]" class="form-control" data-toggle="toggle">
							</div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_pid_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":5000}" title="<?php echo $help_pid; ?>"><?php echo $entry_pid; ?></span></label>
							<div class="col-sm-10">
								 <select id="fb_marketing_pid_<?php echo $store['store_id']; ?>" name="fb_marketing_pid[<?php echo $store['store_id']; ?>]" class="form-control" >
							 		<?php foreach ($pid_options as $key => $value) { ?>
							 			<option value="<?php echo $key; ?>" <?php echo  $key == $fb_marketing_pid[$store['store_id']] ? 'selected="selected"' : '' ; ?>><?php echo $value; ?></option>
							 		<?php } ?>
								 </select>
							 </div>
						  </div>
						  <div class="form-group">
							<label for="fb_marketing_product_feed_<?php echo $store['store_id']; ?>" class="col-sm-2 control-label"><span data-toggle="tooltip" data-html="true" data-delay="{"show":25,"hide":5000}" title="<?php echo htmlspecialchars($help_product_feed); ?>"><?php echo $entry_product_feed; ?></span></label>
							<div class="col-sm-10">
								<div class="row">
									<div class="col-sm-1">
										<input type="checkbox" <?php echo ($fb_marketing_product_feed[$store['store_id']] == 'on' ? 'checked="checked"' : '' ); ?> id="fb_marketing_product_feed_<?php echo $store['store_id']; ?>" name="fb_marketing_product_feed[<?php echo $store['store_id']; ?>]" class="form-control" data-toggle="toggle">
							 		</div>
							 		<div class="col-sm-11">
										<?php foreach ($languages as $language) { ?>
									 	<div class="input-group">
											<input type="url" id="feed-url-<?php echo $store['store_id']; ?>-<?php echo $language['code']; ?>" value="<?php echo (empty($store['ssl']) ? $store['url'] : $store['ssl']) . $data_feed . '&lang=' . $language['code']; ?>" class="form-control" readonly>
											<span class="input-group-btn">
												<a href="<?php echo (empty($store['ssl']) ? $store['url'] : $store['ssl'])  . $data_feed . '&lang=' . $language['code']; ?>" target="_blank" class="btn btn-default">Go!</a>
												<a onclick="copyToClipboard('feed-url-<?php echo $store['store_id']; ?>-<?php echo $language['code']; ?>');" class="btn btn-default">Copy To Clipboard</a>
											</span>
										</div><!-- // .input-group -->
										<?php } ?>
									</div> <!-- // .col-sm-9 -->
								</div>	<!-- // .row -->
							  </div>
							</div>
						  <div class="table-responsive">
						  <h3><?php echo $entry_events; ?></h3>
						  <table class="table table-condensed table-hover" id="events">
						  	<thead>
						  		<th><?php echo $text_status; ?></th>
						  		<th><?php echo $text_name; ?></th>
						  		<th><?php echo $text_path; ?></th>
						  		<th><?php echo $text_value; ?></th>
						  	</thead>
						  	<tbody>
							  <?php foreach($events as $event) { ?>
						  		<tr>
									<td>
									<?php if (!empty($fb_marketing_events[$store['store_id']][strtolower($event)]['status'])) { ?>
										<a  id="active-<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>" onclick="<?php if ($permission) { ?>deactivate('<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>');<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>">
										<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
										</a>
										<input name="fb_marketing_events[<?php echo $store['store_id']; ?>][<?php echo strtolower($event); ?>][status]" id="event-status-<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>" type="hidden" value="1">
										<?php } else { ?>
										<a  id="inactive-<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>" onclick="<?php if ($permission) { ?>activate('<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>');<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>">
										<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
										</a>
										<input name="fb_marketing_events[<?php echo $store['store_id']; ?>][<?php echo strtolower($event); ?>][status]" id="event-status-<?php echo strtolower($event); ?>_<?php echo $store['store_id']; ?>" type="hidden" value="0">
									<?php } ?>
									</td>
									<td ><?php echo $event; ?></td>
									<td>
										<input type="text" <?php $end = end($fb_marketing_events[$store['store_id']][strtolower($event)]['path']); echo empty($end) ? 'readonly' : '' ;?> value="<?php echo !empty($fb_marketing_events[$store['store_id']][strtolower($event)]['path'][0]) ? $fb_marketing_events[$store['store_id']][strtolower($event)]['path'][0] : '' ;?>" name="fb_marketing_events[<?php echo $store['store_id']; ?>][<?php echo strtolower($event); ?>][path]" placeholder="<?php echo $placeholder_path; ?>" class="form-control"></td>
									<td>
										<input type="text" <?php $end = end($fb_marketing_events[$store['store_id']][strtolower($event)]['value']); echo empty($end) ? 'readonly' : '' ;?> value="<?php echo !empty($fb_marketing_events[$store['store_id']][strtolower($event)]['value'][0]) ? $fb_marketing_events[$store['store_id']][strtolower($event)]['value'][0] : '' ;?>" name="fb_marketing_events[<?php echo $store['store_id']; ?>][<?php echo strtolower($event); ?>][value]" placeholder="<?php echo $placeholder_value; ?>" class="form-control"></td>						  		
						  		</tr>
							   <?php } ?>
							</tbody>
					   </table>
					</div> <!-- // .table-responsive -->
					<hr/>
					<div class="table-responsive">
						<h3><?php echo $entry_custom_events; ?> <small><span class="label bg-primary" onclick="$('#custom-event-help').toggleClass('hidden');"><i class="fa fa-question"></i></span></small></h3>
						<div class="well well-sm hidden" id="custom-event-help"><?php echo $help_custom_events; ?></div>
						<table class="table table-condensed table-hover" id="custom-events-<?php echo $store['store_id']; ?>">
							<thead>
						  		<th><?php echo $text_status; ?></th>
						  		<th><?php echo $text_name; ?></th>
						  		<th><?php echo $text_path; ?></th>
						  		<th><?php echo $text_match; ?></th>
						  		<th><?php echo $text_value; ?></th>
						  	</thead>
						  	<?php $pixel_event_row[$store['store_id']] = 0; ?>
							<tbody>
								<?php foreach($fb_marketing_custom_events as $store_id => $c_events) { ?>
							     	<?php if ($store_id == $store['store_id'] && !empty($c_events)) { ?>
										<?php foreach($c_events as $custom_event) { ?>
											<tr id="custom-event-row-<?php echo $store['store_id']; ?>_<?php echo $pixel_event_row[$store['store_id']]; ?>" class="bg-warning">
												<td>
												<?php if (!empty($custom_event['status'])) { ?>
													<a  id="active-<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>" onclick="<?php if ($permission) { ?>deactivate('<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>');<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>">
													<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
													</a>
													<input name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][status]" id="custom-event-status-<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>" type="hidden" value="1">
													<?php } else { ?>
													<a  id="inactive-<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>" onclick="<?php if ($permission) { ?>activate('<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>');<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>">
													<i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i>
													</a>
													<input name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][status]" id="custom-event-status-<?php echo $pixel_event_row[$store['store_id']]; ?>_<?php echo $store['store_id']; ?>" type="hidden" value="0">
												<?php } ?>
												</td>
												<td><select onchange="autofill(<?php echo $store['store_id']; ?>,<?php echo $pixel_event_row[$store['store_id']]; ?>);" name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][type]" class="form-control">
													<?php foreach ($custom_events as $event_option) { ?>
														<option value="<?php echo strtolower($event_option); ?>" <?php echo $custom_event['type'] == strtolower($event_option) ? 'selected="selected"' : '' ;  ?>><?php echo $event_option; ?></option>
													<?php } ?>
													</select></td>
												<td><input type="text" value="<?php echo $custom_event['path']; ?>" name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][path]" placeholder="<?php echo $placeholder_path; ?>" class="form-control"></td>
												<td>
													<select name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][match]" class="form-control">
													<?php foreach ($match_options as $match_option => $match_option_name) { ?>
														<option value="<?php echo $match_option; ?>" <?php echo ($match_option == $custom_event['match'] ? 'selected="selected"' : '' ); ?>><?php echo $match_option_name; ?></option>
													<?php } ?>
												</td>
												<td><input type="text" value="<?php echo $custom_event['value']; ?>" name="fb_marketing_custom_events[<?php echo $store['store_id']; ?>][<?php echo $pixel_event_row[$store['store_id']]; ?>][value]" placeholder="<?php echo $placeholder_value; ?>" class="form-control"></td>
												<td><button type="button" class="btn-primary" onclick="saveCustomEvent(<?php echo $store['store_id']; ?>,<?php echo $pixel_event_row[$store['store_id']]; ?>)"><i class="fa fa-save"></i></button><button type="button" class="btn-danger" onclick="removeCustomEvent(<?php echo $store['store_id']; ?>,<?php echo $pixel_event_row[$store['store_id']]; ?>"><i class="fa fa-minus-circle"></i></button><button class="btn-default" type="button" onclick="goTo(<?php echo $store['store_id']; ?>,<?php echo $pixel_event_row[$store['store_id']]; ?>,'<?php echo empty($store['ssl']) ?  $store['url'] : $store['ssl']; ?>');"><i class="fa fa-link"></button></td>
											</tr>
											<?php $pixel_event_row[$store['store_id']]++; ?>
									   <?php } ?>
									<?php } ?>
							   <?php } ?>
							</tbody>
							 <tfoot>
								<tr>
								  <td colspan="5"></td>
								  <td class="text-left"><button type="button" class="btn btn-success pull-right" onclick="addPixelEventRow(<?php echo $store['store_id']; ?>);"><i class="fa fa-plus"></i></button></td>
								</tr>
							  </tfoot>
						  </table>
						</div> <!-- // .table-responsive -->
					  </div> <!-- // .tab-pane --> 
					  <?php } ?>
				  </div><!-- // .tab-content -->
				</div>
				<div class="tab-pane fade" id="advanced_matching_data">
					<h4>Advanced Matching with the Pixel</h4>
					<p>The Facebook pixel has an advanced matching feature that enables you to send your customer data through the pixel to match more website actions with Facebook users. With this additional data, you can report and optimize your ads for more conversions and build larger re-marketing audiences. You can pass the customer identifiers such as email, phone number that you collect from your website during the check-out, account sign-in or registration process as parameters in the pixel. Facebook will then use this information to match pixel events with Facebook users when the Facebook cookie is not present on the browser that fires the pixel.</p>
				
					<?php foreach($adv_match_types as $key => $value) { ?>
					    <label for="fb_marketing_advanced_matching_data_<?php echo $key; ?>" class="checkbox-inline btn btn-default">
						  <input type="checkbox" <?php echo (!empty($fb_marketing_advanced_matching_data[$key]) ? 'checked="checked"' : '' ); ?> id="fb_marketing_advanced_matching_data_<?php echo $key; ?>" name="fb_marketing_advanced_matching_data[<?php echo $key; ?>]" class="form-control" data-toggle="toggle">
						  <?php echo $value; ?>
						</label>
					<?php } ?>
					<p class="hidden"><small><?php echo $text_ajax_save; ?></small></p>
				</div>
          		<div class="tab-pane fade" id="marketing-api">
          		</div>
          		<div class="tab-pane fade" id="about">
          			<p>To create your Facebook pixel:
						<ol>
						<li>Go to your Facebook Pixel tab in <a href="https://www.facebook.com/ads/manager/pixel/facebook_pixel/" target="_blank">Facebook Ads Manager</a>.</li>
						<li>Click <b>Create a Pixel</b>.</li>
						<li>Enter a name for your pixel. There's only one pixel per ad account, so choose a name that represents your business.</li>
						<li>Make sure you've checked the box to accept the terms.</li>
						<li>Click <b>Create Pixel</b>.</li>
						<li>Copy the Pixel ID and paste it here.</li>
						</ol>
						<a href="https://www.facebook.com/business/help/742478679120153" target="_blank">More</a>
					</p>
          		</div>
          	</div> <!-- // .tab-pane -->
          	</div> <!-- // .tab-content -->
		  </div> <!-- // Content -->
        </form>
    </div>
        <div class="panel-footer"><p>Facebook Pixel & Marketing - v<?php echo $version; ?> &copy; 2015 - <?php echo date('Y'); ?></p></div>
      </div>
    </div>
</div>

<div id="settings-modal"></div>

<script type="text/javascript"><!--

<?php if ($permission) { ?>
	$('#save-settings-button').click(function(){
		validateForm();
	});
	
	function removeCustomEvent(store_id,row) {
		$('#custom-event-row-' + store_id + '_' + row).remove();
		$('#save-settings-button').trigger('click');
	}
	
<?php } else { ?>
	$('#save-settings-button').click(function(){
		$('.alert').remove();
		$(".panel-default").before('<div class="alert alert-warning"><?php echo $error_permission; ?></div>');
	});
	
	function removeCustomEvent(row) {
		$('#save-settings-button').trigger('click');
	}
	
<?php } ?>
	
	$(function() {
		$('input[name^=\'fb_marketing_status\']').change(function() {
		  var value = $(this).val();
			if(value == 1) {
		 		$(this).val(0);
		 	} else {
		 		$(this).val(1);
		 	}
		})
		
		loadSettingsModal();
		
	})
	
	$('.checkbox-inline').on('click', function (evt) {
		$(this).find($('input[type^=\'checkbox\']')).bootstrapToggle('toggle');
		saveAdvMatchingData($(this).find($('input[type^=\'checkbox\']')).prop('id'));
		evt.stopPropagation();
		evt.preventDefault();
	})
	
	var custom_event_row = {<?php $object = ''; foreach ($stores as $store) { $object .= $store['store_id'] . ':' . $pixel_event_row[$store['store_id']] . ','; } echo substr($object,0,-1) ; ?>};
	
	function addPixelEventRow(store_id) {
		
		html = ' <tr id="custom-event-row-' + store_id + '_' + custom_event_row[store_id] + '" class="bg-warning">';
		html += '<td><input type="hidden" name="fb_marketing_custom_events[' + store_id + '][' + custom_event_row[store_id]  + '][status]" value="1"></td>';
		html += '<td ><select onchange="autofill(' + store_id + ',' + custom_event_row[store_id] + ');" name="fb_marketing_custom_events[' + store_id + '][' + custom_event_row[store_id] + '][type]" class="form-control">';
		<?php foreach ($custom_events as $event_option) { ?>
			html += '<option value="<?php echo strtolower($event_option); ?>"><?php echo $event_option; ?></option>';
		<?php } ?>
		html += '</select>';
		html += '</td>';
		html += '<td><input type="text" value="" name="fb_marketing_custom_events[' + store_id + '][' + custom_event_row[store_id] + '][path]" class="form-control"></td>';
		html += '<td><select name="fb_marketing_custom_events[' + store_id + '][' + custom_event_row[store_id] + '][match]" class="form-control">';
		<?php foreach ($match_options as $match_option => $match_option_name) { ?>
			html += '<option value="<?php echo $match_option; ?>"><?php echo $match_option_name; ?></option>';
		<?php } ?>
		html += '</select>';
		html += '</td>';
		html += '<td><input type="text" value="" name="fb_marketing_custom_events[' + store_id + '][' + custom_event_row[store_id] + '][value]" class="form-control"></td>';					
		html += '<td><button type="button" class="btn-primary" onclick="saveCustomEvent(' + store_id + ',' + custom_event_row[store_id] + ');"><i class="fa fa-save"></i></button>';
		html += '<button type="button" class="btn-danger" onclick="removeCustomEvent(' + store_id + ',' + custom_event_row[store_id] + ');"><i class="fa fa-minus-circle"></i></button>'
		html += '</td>';							
		html += '</tr>';
		
		$('#custom-events-' + store_id + ' tbody').append(html);
		custom_event_row[store_id]++;
	}
	
	function autofill(store_id,row) {
		var event_type = $('select[name="fb_marketing_custom_events[' + store_id + '][' + row + '][type]"]').val();
		var path = '';
		var value = '';
		
		switch (event_type) {
			case 'addtocart' : 
			case 'addtowishlist' : 
			case 'pageview' : 
			case 'addpaymentinfo' :
				break;
			case 'viewcontent' : 
				path = 'product/product';
				value = '[product_price]';
				break;
			case 'completeregistration' : 
				path = 'account/success';
				break;
			case 'initiatecheckout' : 
				path = 'checkout/checkout';
				value = '[cart_total]';
				break;
			case 'purchase' : 
				path = 'checkout/success';
				value = '[order_total]';
				break;
			case 'search' :
				path = 'product/search';
				break;
		}
		
		$('input[name="fb_marketing_custom_events[' + store_id + '][' + row + '][path]"]').val(path);
		$('input[name="fb_marketing_custom_events[' + store_id + '][' + row + '][value]"]').val(value);
	}
	
	function saveCustomEvent(store_id,row) {
		$('#save-settings-button').trigger('click');
	}
	
	function goTo(store_id, row, base) {
		var pathVal = $("input[name='fb_marketing_custom_events[" + store_id + "][" + row + "][path]']").val();
		var match = $("select[name='fb_marketing_custom_events[" + store_id + "][" + row + "][match]']").val();
		
		switch (match) {
			case 'route':
				var path = 'index.php?route=' + pathVal; 
				break;
			case 'strict':
				var path = 'index.php' + pathVal; 
				break;
			case 'ends':
			case 'contains':
				var path = pathVal;
				break;
		}
		window.open(base + path);
	}
	
	function loadSettingsModal() {
		$('#settings-modal').load('index.php?route=<?php echo $controller_path; ?>/loadSettingsModal&token=<?php echo $token; ?>');
	}
	
	function validateForm() {
		if ( $('input[name^="fb_marketing"]').val() === '' ) {
			$(this).after('<span class="error"> Please enter a value </span>');
		} else {
		 	saveData();
		}	
	}
	
	function saveOCEvent(row) {
		<?php if ($permission) { ?>
			$.ajax({
				url:'index.php?route=<?php echo $controller_path; ?>/saveOCEvent&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: { 
					trigger : $('#oc-event' + row + ' input[name="oc_event_trigger"]').val(),
					action :  $('#oc-event' + row + ' input[name="oc_event_action"]').val(),
				},
				success: function(json) {
					alertJson('alert alert-success', json);
				},
				error: function(json) {
					alertJson('alert alert-warning', json);
				}
			});
			return false;
	    <?php } else { ?>	    
	    	$('.alert').remove();
			$(".panel-default").before('<div class="alert alert-warning"><?php echo $error_permission; ?></div>');
		 <?php } ?>
	}
	
	function saveData() {
		$.ajax({
			url:'index.php?route=<?php echo $controller_path; ?>/saveSettings&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: { settings : $('#form-fb-marketing').serialize() },
			
			success: function(json) {
				alertJson('alert alert-success', json);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
		return false;
	}
	
	function saveAdvMatchingData(id) {
		$.ajax({
			url:'index.php?route=<?php echo $controller_path; ?>/toggleAdvMatchTypes&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				id: id,
			},
			success: function(json) {
				alertJson('alert alert-success', json);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
	}
	
	function alertJson(action, json) {
		
		$('.alert').remove();
		
		window.scrollTo(0, 0);
		
		if (json['success']) {
			$(".panel-default").before('<div class="' + action + '">' + json['success'] + '</div>');
		} else if (json['warning']) {
			$(".panel-default").before('<div class="' + action + '">' + json['warning'] + '</div>');
		}
		
	}
	
	function activate(row) {
		
		$.ajax({
			url:'index.php?route=<?php echo $controller_path; ?>/toggle&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				row: row,
			},
			success: function(json) {
				alertJson('alert alert-success', json);
				$('#inactive-' + row).replaceWith('<a  id="active-' + row + '" onclick="<?php if ($permission) { ?>deactivate(\'' + row + '\');<?php } ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="<?php echo $text_enabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>');
				$('#event-status-' + row).val(1);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
	}
	
	function deactivate(row) {
		
		$.ajax({
			url:'index.php?route=<?php echo $controller_path; ?>/toggle&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			data: {
				row: row,
			},
			success: function(json) {
				alertJson('alert alert-success', json);
				$('#active-' + row).replaceWith('<a id="inactive-' + row + '" onclick="<?php if ($permission) { ?>activate(\'' + row + '\');<?php } ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="<?php echo $text_disabled; ?>"><i class="fa fa-minus-circle fa-rotate-90 fa-2x"></i></a>');
				$('#event-status-' + row).val(0);
			},
			error: function(json) {
				alertJson('alert alert-warning', json);
			}
		});
	
	}
	
	function copyToClipboard(selector) {
		  var copyText = document.getElementById(selector);
		  copyText.select();
		  document.execCommand("copy");
		  var json = { success: "Copied product feed URL <span class=\"text-primary\"><b>" + copyText.value + "</b></span> to clipboard!"};
		  alertJson('alert alert-success', json);
	}
	
//--></script>
<script type="text/javascript"><!--
	$('#pixel-config a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>