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
				<a id="button-save" class="btn btn-primary" role="button"><?php echo $button_save_cn; ?></a>
				<a href="<?php echo $cn_list; ?>" data-toggle="tooltip" title="<?php echo $button_cn_list; ?>" class="btn btn-default" role="button"><i class="fa fa-list-ul"></i></a>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-danger" role="button"><i class="fa fa-reply"></i></a>
			</div>
    	</div>
  	</div>
  	<div class="container-fluid">
  	<?php if (!empty($error_warning)) { ?>
  		<?php foreach ($error_warning as $error) { ?>
    	<div class="alert alert-danger">
    		<i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      		<button type="button" class="close" data-dismiss="alert">&times;</button>
    	</div>
    	<?php } ?>
    <?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal">
					<div class="row">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading clearfix" style="padding-top: 5px; padding-bottom: 5px;">
									<h3 class="panel-title" style="padding-top: 5px;"><?php echo $text_sender; ?></h3>
									<div class="btn-group pull-right" data-toggle="buttons">
										<?php if ($sender_address_pick_up) { ?>
										<label class="btn btn-default btn-sm active" data-toggle="tooltip" title="<?php echo $button_address_pick_up; ?>"><input type="checkbox" name="sender_address_pick_up" id="input-sender_address_pick_up" value="1" checked><i class="fa fa-home" aria-hidden="true"></i></label>
										<?php } else { ?>
										<label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_address_pick_up; ?>"><input type="checkbox" name="sender_address_pick_up" id="input-sender_address_pick_up" value="1"><i class="fa fa-home" aria-hidden="true"></i></label>
										<?php } ?>
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender"><?php echo $entry_sender; ?></label>
										<div class="col-sm-9">
											<select name="sender" id="input-sender" class="form-control selectpicker show-tick" data-live-search="true">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($senders as $v) { ?>
												<?php if ($v['Ref'] == $sender) { ?>
												<option value="<?php echo $v['Ref']; ?>" selected="selected"><?php echo $v['Description']; ?><?php echo ($v['CityDescription']) ? ', ' . $v['CityDescription'] : ''; ?></option>
												<?php } else { ?>
												<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?><?php echo ($v['CityDescription']) ? ', ' . $v['CityDescription'] : ''; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender_contact_person"><?php echo $entry_contact_person; ?></label>
										<div class="col-sm-9">
											<select name="sender_contact_person" id="input-sender_contact_person" class="form-control selectpicker show-tick" data-live-search="true">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($sender_contact_persons as $v) { ?>
												<?php if ($v['Ref'] == $sender_contact_person) { ?>
												<option value="<?php echo $v['Ref']; ?>" selected="selected"><?php echo $v['Description'] . ', ' . $v['Phones']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Description'] . ', ' . $v['Phones']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender_contact_person_phone"><?php echo $entry_phone; ?></label>
										<div class="col-sm-9">
											<input type="text" name="sender_contact_person_phone" value="" placeholder="<?php echo $entry_phone; ?>" id="input-sender_contact_person_phone" class="form-control" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender_region"><?php echo $entry_region; ?></label>
										<div class="col-sm-9">
											<select name="sender_region" id="input-sender_region" class="form-control selectpicker show-tick" data-live-search="true">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($regions as $v) { ?>
												<?php if ($v['Ref'] == $sender_region) { ?>
												<option value="<?php echo $v['Ref']; ?>" selected="selected"><?php echo $v['Description']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender_city"><?php echo $entry_city; ?></label>
										<div class="col-sm-9">
											<select name="sender_city" id="input-sender_city" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-sender_department"><?php echo $entry_department; ?></label>
										<div class="col-sm-9">
											<select name="sender_department" id="input-sender_department" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-sender_address"><?php echo $entry_address; ?></label>
										<div class="col-sm-9">
											<select name="sender_address" id="input-sender_address" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading clearfix" style="padding-top: 5px; padding-bottom: 5px;">
									<h3 class="panel-title" style="padding-top: 5px;"><?php echo $text_recipient; ?></h3>
									<div class="btn-group pull-right" data-toggle="buttons">
										<?php if ($recipient_address_type == 'department') { ?>
										<label class="btn btn-default btn-sm active" data-toggle="tooltip" title="<?php echo $button_department_delivery; ?>"><input type="radio" name="recipient_address_type" value="department" id="input-recipient_address_type" checked><i class="fa fa-map-marker" aria-hidden="true"></i></label>
										<label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_doors_delivery; ?>"><input type="radio" name="recipient_address_type" value="doors" id="input-recipient_address_type"><i class="fa fa-home" aria-hidden="true"></i></label>
                                        <label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_poshtomat_delivery; ?>"><input type="radio" name="recipient_address_type" value="poshtomat" id="input-recipient_address_type"><i class="fa fa-building" aria-hidden="true"></i></label>
                                        <?php } elseif ($recipient_address_type == 'poshtomat') { ?>
                                        <label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_department_delivery; ?>"><input type="radio" name="recipient_address_type" value="department" id="input-recipient_address_type"><i class="fa fa-map-marker" aria-hidden="true"></i></label>
                                        <label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_doors_delivery; ?>"><input type="radio" name="recipient_address_type" value="doors" id="input-recipient_address_type"><i class="fa fa-home" aria-hidden="true"></i></label>
                                        <label class="btn btn-default btn-sm active" data-toggle="tooltip" title="<?php echo $button_poshtomat_delivery; ?>"><input type="radio" name="recipient_address_type" value="poshtomat" id="input-recipient_address_type" checked><i class="fa fa-building" aria-hidden="true"></i></label>
										<?php } else { ?>
										<label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_department_delivery; ?>"><input type="radio" name="recipient_address_type" value="department" id="input-recipient_address_type"><i class="fa fa-map-marker" aria-hidden="true"></i></label>
										<label class="btn btn-default btn-sm active" data-toggle="tooltip" title="<?php echo $button_doors_delivery; ?>"><input type="radio" name="recipient_address_type" value="doors" id="input-recipient_address_type" checked><i class="fa fa-home" aria-hidden="true"></i></label>
                                        <label class="btn btn-default btn-sm" data-toggle="tooltip" title="<?php echo $button_poshtomat_delivery; ?>"><input type="radio" name="recipient_address_type" value="poshtomat" id="input-recipient_address_type"><i class="fa fa-building" aria-hidden="true"></i></label>
										<?php } ?>
									</div>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient"><?php echo $entry_recipient; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<select name="recipient" id="input-recipient" class="form-control selectpicker show-tick" data-live-search="true"></select>
												<span class="input-group-btn">
													<button type="button" id="button-add_recipient" data-toggle="modal" data-target="#modal-add-recipient" data-tooltip="true" title="<?php echo $button_add; ?>" class="btn btn-default"><i class="fa fa-plus"></i></button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient_contact_person"><?php echo $entry_contact_person; ?></label>
										<div class="col-sm-9">
											<input type="text" name="recipient_contact_person" value="<?php echo $recipient_contact_person; ?>" placeholder="<?php echo $entry_contact_person; ?>" id="input-recipient_contact_person" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient_contact_person_phone"><?php echo $entry_phone; ?></label>
										<div class="col-sm-9">
											<input type="text" name="recipient_contact_person_phone" value="<?php echo $recipient_contact_person_phone; ?>" placeholder="<?php echo $entry_phone; ?>" id="input-recipient_contact_person_phone" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient_region"><?php echo $entry_region; ?></label>
										<div class="col-sm-9">
											<select name="recipient_region" id="input-recipient_region" class="form-control selectpicker show-tick" data-live-search="true">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($regions as $v) { ?>
												<?php if ($v['Ref'] == $recipient_region) { ?>
												<option value="<?php echo $v['Ref']; ?>" selected="selected"><?php echo $v['Description']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
											<input type="hidden" name="recipient_region_name" value="<?php echo $recipient_region_name; ?>" id="input-recipient_region_name" />
											<input type="hidden" name="recipient_district_name" value="<?php echo $recipient_district_name; ?>" id="input-recipient_district_name" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient_city" data-toggle="tooltip" data-placement="right" title="<?php echo $recipient_city_name; ?>"><?php echo $entry_city; ?></label>
										<div class="col-sm-9">
											<select name="recipient_city" id="input-recipient_city" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-recipient_department" data-toggle="tooltip" data-placement="right" title="<?php echo $recipient_department_name; ?>"><?php echo $entry_department; ?></label>
										<div class="col-sm-9">
											<select name="recipient_department" id="input-recipient_department" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-recipient_poshtomat" data-toggle="tooltip" data-placement="right" title="<?php echo $recipient_department_name; ?>"><?php echo $entry_poshtomat; ?></label>
										<div class="col-sm-9">
											<select name="recipient_poshtomat" id="input-recipient_poshtomat" class="form-control selectpicker show-tick" data-live-search="true"></select>
										</div>
									</div>
                                    <div class="form-group" style="display: none">
                                        <label class="col-sm-3 control-label" for="input-recipient_settlement_name"><?php echo $entry_settlement; ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="recipient_settlement_name" value="<?php echo $recipient_settlement_name; ?>" placeholder="<?php echo $entry_settlement; ?>" id="input-recipient_settlement_name" class="form-control" />
                                            <input type="hidden" name="recipient_settlement" value="<?php echo $recipient_settlement; ?>" id="input-recipient_settlement" />
                                        </div>
                                    </div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-recipient_street_name"><?php echo $entry_street; ?></label>
										<div class="col-sm-9">
											<input type="text" name="recipient_street_name" value="<?php echo $recipient_street_name; ?>" placeholder="<?php echo $entry_street; ?>" id="input-recipient_street_name" class="form-control" />
											<input type="hidden" name="recipient_street" value="<?php echo $recipient_street; ?>" id="input-recipient_street" />
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-recipient_house"><?php echo $entry_house; ?></label>
										<div class="col-sm-9">
											<input type="text" name="recipient_house" value="<?php echo $recipient_house; ?>" placeholder="<?php echo $entry_house; ?>" id="input-recipient_house" class="form-control" />
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-recipient_flat"><?php echo $entry_flat; ?></label>
										<div class="col-sm-9">
											<input type="text" name="recipient_flat" value="<?php echo $recipient_flat; ?>" placeholder="<?php echo $entry_flat; ?>" id="input-recipient_flat" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?php echo $text_departure_options; ?></h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-departure_type"><?php echo $entry_departure_type; ?></label>
										<div class="col-sm-9">
											<select name="departure_type" id="input-departure_type" class="form-control">
												<?php foreach ($references['cargo_types'] as $cargo_type) { ?>
												<?php if ($cargo_type['Ref'] == $departure) { ?>
												<option value="<?php echo $cargo_type['Ref']; ?>" selected="selected"><?php echo $cargo_type['Description']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $cargo_type['Ref']; ?>"><?php echo $cargo_type['Description']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-general_parameters"><?php echo $entry_general_parameters; ?></label>
										<div class="col-sm-9">
											<input type="checkbox" name="general_parameters" id="input-general_parameters" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if ($general_parameters) { echo ' checked'; }?>>
										</div>
									</div>
									<?php foreach ($references['tires_and_wheels'] as $t_and_w) { ?>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-tires_and_wheels_<?php echo $t_and_w['Ref']; ?>"><?php echo $t_and_w['Description']; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="tires_and_wheels[<?php echo $t_and_w['Ref']; ?>]" value="<?php echo isset($tires_and_wheels[$t_and_w['Ref']]) ? $tires_and_wheels[$t_and_w['Ref']] : ''?>" placeholder="<?php echo $t_and_w['Description']; ?>" id="input-tires_and_wheels_<?php echo $t_and_w['Ref']; ?>" class="form-control" />
												<span class="input-group-addon"><?php echo $text_pc; ?></span>
											</div>
										</div>
									</div>
									<?php } ?>
									<?php foreach ($parcels as $k => $v) { ?>
									<legend style="display: none;" id="text-seat-<?php echo $k; ?>"><?php echo $text_seat . ' ' . ($k + 1); ?></legend>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-weight-<?php echo $k; ?>"><?php echo $entry_weight; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="parcels[<?php echo $k; ?>][weight]" value="<?php echo (!empty($v['weight'])) ? $v['weight'] : 0; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight-<?php echo $k; ?>" class="form-control" />
												<span class="input-group-addon"><?php echo $text_kg; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-length-<?php echo $k; ?>"><?php echo $entry_length; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="parcels[<?php echo $k; ?>][length]" value="<?php echo (!empty($v['length'])) ? $v['length'] : 0; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length-<?php echo $k; ?>" class="form-control" />
												<span class="input-group-addon"><?php echo $text_cm; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-width-<?php echo $k; ?>"><?php echo $entry_width; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="parcels[<?php echo $k; ?>][width]" value="<?php echo (!empty($v['width'])) ? $v['width'] : 0; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width-<?php echo $k; ?>" class="form-control" />
												<span class="input-group-addon"><?php echo $text_cm; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-height-<?php echo $k; ?>"><?php echo $entry_height; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="parcels[<?php echo $k; ?>][height]" value="<?php echo (!empty($v['height'])) ? $v['height'] : 0; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height-<?php echo $k; ?>" class="form-control" />
												<span class="input-group-addon"><?php echo $text_cm; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-volume_weight-<?php echo $k; ?>"><?php echo $entry_volume_weight; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="parcels[<?php echo $k; ?>][volume_weight]" value="<?php echo (!empty($v['volume_weight'])) ? $v['volume_weight'] : 0; ?>" placeholder="<?php echo $entry_volume_weight; ?>" id="input-volume_weight-<?php echo $k; ?>" class="form-control" readonly/>
												<span class="input-group-addon"><?php echo $text_kg; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-manual_processing-<?php echo $k; ?>"><?php echo $entry_manual_processing; ?></label>
										<div class="col-sm-9">
											<input type="checkbox" name="parcels[<?php echo $k; ?>][manual_processing]" id="input-manual_processing-<?php echo $k; ?>" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($v['manual_processing'])) { echo ' checked'; } ?>>
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label class="col-sm-3 control-label" for="input-pack-<?php echo $k; ?>"><?php echo $entry_pack_type; ?></label>
										<div class="col-sm-2">
											<input type="checkbox" name="parcels[<?php echo $k; ?>][pack]" id="input-pack-<?php echo $k; ?>" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($v['pack'])) { echo ' checked'; } ?>>
										</div>
										<div class="col-sm-7"<?php if (empty($v['pack'])) { echo ' style="display: none;"'; } ?>>
											<select name="parcels[<?php echo $k; ?>][pack_type]" id="input-pack_type-<?php echo $k; ?>" class="form-control selectpicker show-tick" title="<?php echo $text_select; ?>" data-live-search="true">
												<?php foreach ($references['pack_types'] as $p) { ?>
												<?php if (!empty($v['pack_type']) && $v['pack_type'] == $p['Ref']) { ?>
												<option value="<?php echo $p['Ref']; ?>" selected><?php echo $p['Description']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $p['Ref']; ?>"><?php echo $p['Description']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<?php } ?>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-weight_general"><?php echo $entry_weight_general; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="weight_general" value="<?php echo $weight_general; ?>" placeholder="<?php echo $entry_weight_general; ?>" id="input-weight_general" class="form-control" />
												<span class="input-group-addon"><?php echo $text_kg; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-volume_general"><?php echo $entry_volume_general; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="volume_general" value="<?php echo $volume_general; ?>" placeholder="<?php echo $entry_volume_general; ?>" id="input-volume_general" class="form-control" />
												<span class="input-group-addon"><?php echo $text_cubic_meter; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-volume_weight_general"><?php echo $entry_volume_weight_general; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="volume_weight_general" value="<?php echo $volume_weight_general; ?>" placeholder="<?php echo $entry_volume_weight_general; ?>" id="input-volume_weight_general" class="form-control" readonly/>
												<span class="input-group-addon"><?php echo $text_kg; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-pack_general"><?php echo $entry_pack_type; ?></label>
										<div class="col-sm-2">
											<input type="checkbox" name="pack_general" id="input-pack_general" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($pack)) { echo ' checked'; } ?>>
										</div>
										<div class="col-sm-7"<?php if (empty($pack)) { echo ' style="display: none;"'; } ?>>
											<select name="pack_type_general" id="input-pack_type_general" class="form-control selectpicker show-tick" title="<?php echo $text_select; ?>" data-live-search="true">
											<?php foreach ($references['pack_types'] as $p) { ?>
											<?php if (!empty($parcels[0]['pack_type']) && $parcels[0]['pack_type'] == $p['Ref']) { ?>
												<option value="<?php echo $p['Ref']; ?>" selected><?php echo $p['Description']; ?></option>
											<?php } else { ?>
												<option value="<?php echo $p['Ref']; ?>"><?php echo $p['Description']; ?></option>
											<?php } ?>
											<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-seats_amount"><?php echo $entry_seats_amount; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<span class="input-group-btn">
													<button type="button" id="button-options_seat_calc" data-toggle="modal" data-target="#modal-options-seat-calc" data-tooltip="true" title="<?php echo $button_options_seat_calc; ?>" class="btn btn-default"><i class="fa fa-calculator"></i></button>
												</span>
												<input type="text" name="seats_amount" value="<?php echo $seats_amount; ?>" placeholder="<?php echo $entry_seats_amount; ?>" id="input-seats_amount" class="form-control" />
												<span class="input-group-btn">
													<button type="button" id="button-seat_plus" onclick="addSeat();" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i></button>
													<button type="button" id="button-seat_minus" onclick="deleteSeat();" class="btn btn-default"><i class="fa fa-minus" aria-hidden="true"></i></button>
												</span>
												<span class="input-group-addon"><?php echo $text_pc; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-declared_cost"><?php echo $entry_declared_cost; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<span class="input-group-btn">
													<button type="button" id="button-components_list" data-toggle="modal" data-target="#modal-totals-list" data-tooltip="true" title="<?php echo $button_components_list; ?>" class="btn btn-default"><i class="fa fa-list" aria-hidden="true"></i></button>
												</span>
												<input type="text" name="declared_cost" value="<?php echo $declared_cost; ?>" placeholder="<?php echo $entry_declared_cost; ?>" id="input-declared_cost" class="form-control" />
												<span class="input-group-addon"><?php echo $text_grn; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-departure_description"><?php echo $entry_departure_description; ?></label>
										<div class="col-sm-9">
											<input type="text" name="departure_description" value="<?php echo $departure_description; ?>" placeholder="<?php echo $entry_departure_description; ?>" id="input-departure_description" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?php echo $text_payment; ?></h3>
								</div>
								<div class="panel-body">
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-delivery_payer"><?php echo $entry_delivery_payer; ?></label>
										<div class="col-sm-9">
											<div class="btn-group" data-toggle="buttons">
												<?php foreach ($references['payer_types'] as $v) { ?>
												<?php if ($v['Ref'] == 'ThirdPerson' && empty($sender_options['CanPayTheThirdPerson'])) { ?>
												<label class="btn btn-default disabled">
													<input type="radio" name="delivery_payer" value="<?php echo $v['Ref']; ?>" id="input-delivery_payer-<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?>
												</label>
												<?php } elseif (!empty($delivery_payer) && $v['Ref'] == $delivery_payer) { ?>
												<label class="btn btn-default active">
													<input type="radio" name="delivery_payer" value="<?php echo $v['Ref']; ?>" id="input-delivery_payer-<?php echo $v['Ref']; ?>" checked><?php echo $v['Description']; ?>
												</label>
												<?php } else { ?>
												<label class="btn btn-default">
													<input type="radio" name="delivery_payer" value="<?php echo $v['Ref']; ?>" id="input-delivery_payer-<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?>
												</label>
												<?php } ?>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-third_person"><?php echo $entry_third_person; ?></label>
										<div class="col-sm-9">
											<select name="third_person" id="input-third_person" class="form-control">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($references['third_persons'] as $v) { ?>
												<?php if ($v['Ref'] == $third_person) { ?>
												<option value="<?php echo $v['Ref']; ?>" selected="selected"><?php echo $v['Description']; ?>, <?php echo $v['CityDescription']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?>, <?php echo $v['CityDescription']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-payment_type"><?php echo $entry_payment_type; ?></label>
										<div class="col-sm-9">
											<div class="btn-group" data-toggle="buttons">
												<?php foreach ($references['payment_types'] as $v) { ?>
												<?php if (!empty($payment_type) && $v['Ref'] == $payment_type) { ?>
												<label class="btn btn-default active">
													<input type="radio" name="payment_type" value="<?php echo $v['Ref']; ?>" id="input-payment_type-<?php echo $v['Ref']; ?>" checked><?php echo $v['Description']; ?>
												</label>
												<?php } else { ?>
												<label class="btn btn-default">
													<input type="radio" name="payment_type" value="<?php echo $v['Ref']; ?>" id="input-payment_type-<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?>
												</label>
												<?php } ?>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" for="input-backward_delivery"><?php echo $entry_backward_delivery; ?></label>
										<div class="col-sm-9">
											<select name="backward_delivery" id="input-backward_delivery" class="form-control">
												<option value="0"><?php echo $text_select; ?></option>
												<option value="N"<?php echo ($backward_delivery == 'N') ? ' selected="selected"' : ''; ?>><?php echo $text_no_backward_delivery; ?></option>
												<?php foreach ($references['backward_delivery_types'] as $backward_delivery_type) { ?>
												<?php if ($backward_delivery_type['Ref'] == $backward_delivery) { ?>
												<option value="<?php echo $backward_delivery_type['Ref']; ?>" selected="selected"><?php echo $backward_delivery_type['Description']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $backward_delivery_type['Ref']; ?>"><?php echo $backward_delivery_type['Description']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-backward_delivery_total"><?php echo $entry_backward_delivery_total; ?></label>
										<div class="col-sm-9">
											<div class="input-group">
												<input type="text" name="backward_delivery_total" value="<?php echo $backward_delivery_total; ?>" placeholder="<?php echo $entry_backward_delivery_total; ?>" id="input-backward_delivery_total" class="form-control" />
												<span class="input-group-addon"><?php echo $text_grn; ?></span>
											</div>
										</div>
									</div>
									<div class="form-group" style="display: none">
										<label class="col-sm-3 control-label" for="input-backward_delivery_payer"><?php echo $entry_backward_delivery_payer; ?></label>
										<div class="col-sm-9">
											<div class="btn-group" data-toggle="buttons">
												<?php foreach ($references['backward_delivery_payers'] as $v) { ?>
												<?php if (!empty($backward_delivery_payer) && $v['Ref'] == $backward_delivery_payer) { ?>
												<label class="btn btn-default active">
													<input type="radio" name="backward_delivery_payer" value="<?php echo $v['Ref']; ?>" id="input-backward_delivery_payer-<?php echo $v['Ref']; ?>" checked><?php echo $v['Description']; ?>
												</label>
												<?php } else { ?>
												<label class="btn btn-default">
													<input type="radio" name="backward_delivery_payer" value="<?php echo $v['Ref']; ?>" id="input-backward_delivery_payer-<?php echo $v['Ref']; ?>"><?php echo $v['Description']; ?>
												</label>
												<?php } ?>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
									<label class="col-sm-3 control-label" for="input-payment_control"><?php echo $entry_payment_control; ?></label>
									<div class="col-sm-9">
										<div class="input-group">
											<input type="text" name="payment_control" value="<?php echo $payment_control; ?>" placeholder="<?php echo $entry_payment_control; ?>" id="input-payment_control" class="form-control" />
											<span class="input-group-addon"><?php echo $text_grn; ?></span>
										</div>
									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h3 class="panel-title"><?php echo $text_additionally; ?></h3>
								</div>
								<div class="panel-body">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-departure_date"><?php echo $entry_departure_date; ?></label>
											<div class="col-sm-9">
												<div class="input-group date">
													<input type="text" name="departure_date" value="<?php echo $departure_date; ?>" placeholder="<?php echo $entry_departure_date; ?>" data-date-format="DD.MM.YYYY" id="input-departure_date" class="form-control">
													<span class="input-group-btn">
														<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group" style="display: none">
											<label class="col-sm-3 control-label" for="input-preferred_delivery_date"><?php echo $entry_preferred_delivery_date; ?></label>
											<div class="col-sm-9">
												<div class="input-group date">
													<input type="text" name="preferred_delivery_date" value="<?php echo $preferred_delivery_date; ?>" placeholder="<?php echo $entry_preferred_delivery_date; ?>" data-date-format="DD.MM.YYYY" id="input-preferred_delivery_date" class="form-control">
													<span class="input-group-btn">
														<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group" style="display: none">
											<label class="col-sm-3 control-label" for="input-time_interval"><?php echo $entry_preferred_delivery_time; ?></label>
											<div class="col-sm-9">
												<select name="time_interval" id="input-time_interval" class="form-control">
													<option value=""><?php echo $text_during_day; ?></option>
													<?php if (isset($time_intervals) && $time_intervals) { ?>
													<?php foreach ($time_intervals as $interval) { ?>
													<?php if ($interval['Number'] == $time_interval) { ?>
													<option value="<?php echo $interval['Number']; ?>" selected="selected"><?php echo $interval['Start'] . ' - ' . $interval['End']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $interval['Number']; ?>"><?php echo $interval['Start'] . ' - ' . $interval['End']; ?></option>
													<?php } ?>
													<?php } ?>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-order_number"><?php echo $entry_order_number; ?></label>
											<div class="col-sm-9">
												<input type="text" name="order_number" value="<?php echo $order_number; ?>" placeholder="<?php echo $entry_order_number; ?>" id="input-order_number" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-packing_number"><?php echo $entry_packing_number; ?></label>
											<div class="col-sm-9">
												<input type="text" name="packing_number" value="<?php echo $packing_number; ?>" placeholder="<?php echo $entry_packing_number; ?>" id="input-packing_number" class="form-control" />
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-additional_information"><?php echo $entry_departure_additional_information; ?></label>
											<div class="col-sm-9">
												<textarea name="additional_information" rows="2" id="input-additional_information" maxlength="100" class="form-control"><?php echo $additional_information; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-avia_delivery"><?php echo $entry_avia_delivery; ?></label>
											<div class="col-sm-9">
												<input type="checkbox" name="avia_delivery" id="input-avia_delivery" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($avia_delivery)) { echo ' checked'; } ?>>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-rise_on_floor"><?php echo $entry_rise_on_floor; ?></label>
											<div class="col-sm-9">
												<input type="text" name="rise_on_floor" value="<?php echo $rise_on_floor; ?>" placeholder="<?php echo $entry_rise_on_floor; ?>" id="input-rise_on_floor" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label" for="input-elevator"><?php echo $entry_elevator; ?></label>
											<div class="col-sm-9">
												<input type="checkbox" name="elevator" id="input-elevator" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-times'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($elevator)) { echo ' checked'; } ?>>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Modal Recipient START -->
					<div class="modal fade" id="modal-add-recipient" tabindex="-1" role="dialog" aria-labelledby="add-recipient-label">
						<div class="modal-dialog modal-sm" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="options-seat-label"><?php echo $heading_adding_recipient; ?></h4>
								</div>
								<div class="modal-body">
									<div class="form-group clearfix">
										<label class="col-sm-4 control-label" for="input-recipient_edrpou"><?php echo $entry_edrpou; ?></label>
										<div class="col-sm-8">
											<input type="text" name="recipient_edrpou" value="<?php echo $recipient_edrpou; ?>" placeholder="<?php echo $entry_edrpou; ?>"  id="input-recipient_edrpou" class="form-control" />
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" onclick="addRecipient();" class="btn btn-primary"><i class="fa fa-check"></i></button>
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal Recipient END -->

					<!-- Modal Seats START -->
					<div class="modal fade" id="modal-options-seat-calc" tabindex="-1" role="dialog" aria-labelledby="options-seat-calc-label">
						<div class="modal-dialog modal-lg" role="document" style="width: 85%;">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="options-seat-calc-label"><?php echo $heading_options_seat_calc; ?></h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table table-striped" id="table-seats-calc">
											<thead>
												<tr>
													<td class="text-center"><?php echo $column_number; ?></td>
													<td class="text-center"><?php echo $column_weight; ?></td>
													<td class="text-center"><?php echo $column_length; ?></td>
													<td class="text-center"><?php echo $column_width; ?></td>
													<td class="text-center"><?php echo $column_height; ?></td>
													<td class="text-center"></td>
													<td class="text-center"><?php echo $column_volume; ?></td>
													<td class="text-center"><?php echo $column_volume_weight; ?></td>
													<td class="text-center" width="100px"><?php echo $column_action; ?></td>
												</tr>
											</thead>
											<tbody>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="8"></td>
													<td class="text-center">
														<button type="button" onclick="addCalcSeat();" data-toggle="modal"  data-tooltip=true title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" onclick="saveCalcSeats();" class="btn btn-primary"><i class="fa fa-check"></i></button>
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal Seats END -->

					<!-- Modal Totals list START -->
					<div class="modal fade" id="modal-totals-list" tabindex="-1" role="dialog" aria-labelledby="totals-list-label">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="totals-list-label"><?php echo $heading_components_list; ?></h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table table-striped" id="table-totals_list">
											<thead>
												<tr>
													<td><?php echo $column_description; ?></td>
													<td class="text-center"><?php echo $column_price; ?></td>
													<td class="text-center"><?php echo $column_action; ?></td>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($totals as $i => $total) { ?>
												<tr>
													<td><?php echo $total['title']; ?></td>
													<td class="text-center"><?php echo $total['price']; ?> <?php echo $text_grn; ?></td>
													<td class="text-center">
														<?php if ($total['status']) { ?>
														<button type="button" class="btn btn-danger btn-xs" id="button-total_declared_cost_minus"><i class="fa fa-minus"></i></button>
														<?php } else { ?>
														<button type="button" class="btn btn-success btn-xs" id="button-total_declared_cost_plus"><i class="fa fa-plus"></i></button>
														<?php } ?>
													</td>
												</tr>
											<?php } ?>
											</tbody>
											<tfoot>
												<tr>
													<td><strong><?php echo $text_declared_cost; ?></strong></td>
													<td class="text-center" id="td-declared_cost_total"><strong><?php echo $declared_cost; ?> <?php echo $text_grn; ?></strong></td>
													<td></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" onclick="saveDeclaredCost();" class="btn btn-primary"><i class="fa fa-check"></i></button>
									<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i></button>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal Totals list END -->

				</form>
			</div>
		</div>
	</div>
</div>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-<?php echo $translations_code; ?>.min.js"></script>
<script type="text/javascript"><!--
	function validateForm(element) {
		var post_data = element.name + '=' + encodeURIComponent(element.value);

		if (element.name == 'sender_contact_person') {
			post_data += '&f_sender=' + encodeURIComponent($('#input-sender').val());
		} else if (element.name == 'sender_address') {
			post_data += '&f_sender=' + encodeURIComponent($('#input-sender').val()) + '&sender_city=' + encodeURIComponent($('#input-sender_city').val());
		} else if (element.name == 'recipient_settlement_name') {
            post_data += '&recipient_settlement=' + encodeURIComponent($('#input-recipient_settlement').val());
        } else if (element.name == 'recipient_street_name') {
			post_data += '&recipient_street=' + encodeURIComponent($('#input-recipient_street').val());
		}  else if (element.name == 'backward_delivery_total') {
			post_data += '&backward_delivery=' + encodeURIComponent($('#input-backward_delivery').val());
		}

		$.ajax({
			url: 'index.php?route=extension/shipping/novaposhta/getCNForm&token=<?php echo $token; ?>',
			type: 'POST',
			data: post_data,
			dataType: 'json',
			success: function(json) {
				checkErrors(json);
			}
		});
	}

	function checkErrors(array) {
		if (array['warning']) {
			if (array['warning'] instanceof Array) {
				for(var w in array['warning']) {
					$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + array['warning'][w] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			} else {
				$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + array['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		}

		for(var field in array['errors']) {
			$('div.form-group').has('label[for="input-' + field + '"]').addClass('has-error');
			$('#span-' + field).remove('.help-block');
			$('div.form-group > div[class^="col-sm"]').has('#input-' + field).append('<span id="span-' + field + '" class="help-block">' + array['errors'][field] + '</span>');
		}

		for(var field in array['success']) {
			$('div.form-group').has('label[for="input-' + field + '"]').removeClass('has-error');
			$('#span-' + field).remove('.help-block');
		}

		if (array['errors'] && array['errors']['parcels']) {
			for (var i in array['errors']['parcels']) {
				for (var field in array['errors']['parcels'][i]) {
					$('div.form-group').has('label[for="input-' + field + '-' + i + '"]').addClass('has-error');
					$('#span-' + field + '-' + i).remove('.help-block');
					$('div.form-group > div[class^="col-sm"]').has('#input-' + field + '-' + i).append('<span id="span-' + field + '-' + i + '" class="help-block">' + array['errors']['parcels'][i][field] + '</span>');
				}
			}
		}

		if (array['success'] && array['success']['parcels']) {
			for (var i in array['success']['parcels']) {
				for (var field in array['success']['parcels'][i]) {
					$('div.form-group').has('label[for="input-' + field + '-' + i + '"]').removeClass('has-error');
					$('#span-' + field + '-' + i).remove('.help-block');
				}
			}
		}
	}

	function addRecipient() {
		$.ajax({
			url: 'index.php?route=extension/shipping/novaposhta/addRecipient&token=<?php echo $token; ?>',
			type: 'POST',
			data: 'edrpou=' + encodeURIComponent($('#input-recipient_edrpou').val()),
			dataType: 'json',
			beforeSend: function () {
				$('body').fadeTo('fast', 0.7).prepend('<div id="ocmax-loader" style="position: fixed; top: 50%;	left: 50%; z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-3x fa-fw"></i></div>');
			},
            complete: function () {
                var $alerts = $('.alert-danger, .alert-success');

                if ($alerts.length !== 0) {
                    setTimeout(function() { $alerts.fadeOut(); }, 5000);
                }

				$('body').fadeTo('fast', 1)
				$('#ocmax-loader').remove();
            },
			success: function (json) {
				if(json['error']) {
					$('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('.container-fluid:eq(1)').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					getRecipients();
				}

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});

		$('#modal-add-recipient').modal('hide');
	}

	function addSeat() {
		var
				html = '',
				$seats = $('#input-seats_amount'),
				departure_type = $('#input-departure_type').val(),
				count = $('input[id^="input-weight-"]').length;

		if ((departure_type == 'Parcel' || departure_type == 'Cargo') && !$('#input-general_parameters').is(':checked')) {
			html += '<legend id="text-seat-' + count + '"><?php echo $text_seat; ?> ' + (count + 1) + '</legend>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-weight-' + count + '"><?php echo $entry_weight; ?></label><div class="col-sm-9"><div class="input-group"><input type="text" name="parcels[' + count + '][weight]" value="0" placeholder="<?php echo $entry_weight; ?>" id="input-weight-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_kg; ?></span></div></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-length-' + count + '"><?php echo $entry_length; ?></label><div class="col-sm-9"><div class="input-group"><input type="text" name="parcels[' + count + '][length]" value="0" placeholder="<?php echo $entry_length; ?>" id="input-length-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-width-' + count + '"><?php echo $entry_width; ?></label><div class="col-sm-9"><div class="input-group"><input type="text" name="parcels[' + count + '][width]" value="0" placeholder="<?php echo $entry_width; ?>" id="input-width-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-height-' + count + '"><?php echo $entry_height; ?></label><div class="col-sm-9"><div class="input-group"><input type="text" name="parcels[' + count + '][height]" value="0" placeholder="<?php echo $entry_height; ?>" id="input-height-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-volume_weight-' + count + '"><?php echo $entry_volume_weight; ?></label><div class="col-sm-9"><div class="input-group"><input type="text" name="parcels[' + count + '][volume_weight]" value="0" placeholder="<?php echo $entry_volume_weight; ?>" id="input-volume_weight-' + count + '" class="form-control" readonly><span class="input-group-addon"><?php echo $text_cm; ?></span></div></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-manual_processing-' + count + '"><?php echo $entry_manual_processing; ?></label><div class="col-sm-9"><input type="checkbox" name="parcels[' + count + '][manual_processing]" id="input-manual_processing-' + count + '" data-on="<i class=\'fa fa-check\'></i>" data-off="<i class=\'fa fa-times\'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($manual_processing)) { echo ' checked'; } ?>></div></div>';
			html += '<div class="form-group"><label class="col-sm-3 control-label" for="input-pack-' + count + '"><?php echo $entry_pack_type; ?></label><div class="col-sm-2"><input type="checkbox" name="parcels[' + count + '][pack]" id="input-pack-' + count + '" data-on="<i class=\'fa fa-check\'></i>" data-off="<i class=\'fa fa-times\'></i>" data-toggle="toggle" data-onstyle="info"<?php if (!empty($pack)) { echo ' checked'; } ?>></div><div class="col-sm-7"<?php if (empty($pack)) { echo ' style="display: none;"'; } ?>><select name="parcels[' + count + '][pack_type]" id="input-pack_type-' + count + '" class="form-control selectpicker show-tick" title="<?php echo $text_select; ?>" data-live-search="true"><?php foreach ($references['pack_types'] as $p) { ?><option value="<?php echo $p['Ref']; ?>"><?php echo $p['Description']; ?></option><?php } ?></select></div></div>';

			$('input[id^="input-pack-"]:last').parents('div.form-group').after(html);
			$('#input-manual_processing-' + count + ', #input-pack-' + count).bootstrapToggle();
			$('#input-pack_type-' + count).selectpicker('refresh');
		} else {
			$('[for="input-pack_general"]').filter(':visible').parent().fadeOut();
		}

		$seats.val(+$seats.val() + 1).trigger('change');
	}

	function deleteSeat() {
		var
				$seats = $('#input-seats_amount'),
				count = $('input[id^="input-weight-"]').length;

		if (count > 1) {
            $('legend[id^="text-seat-"]:last').remove();
			$('input[id^="input-weight-"]:last').parents('div.form-group').remove();
			$('input[id^="input-length-"]:last').parents('div.form-group').remove();
			$('input[id^="input-width-"]:last').parents('div.form-group').remove();
			$('input[id^="input-height-"]:last').parents('div.form-group').remove();
            $('input[id^="input-volume_weight-"]:last').parents('div.form-group').remove();
            $('input[id^="input-manual_processing-"]:last').parents('div.form-group').remove();
			$('input[id^="input-pack-"]:last').parents('div.form-group').remove();
		}

		if ($seats.val() == 2 && $('#input-general_parameters').is(':checked')) {
			$('[for="input-pack_general"]').filter(':hidden').parent().fadeIn();
		}

		if ($seats.val() > 1) {
			$seats.val(+$seats.val() - 1).trigger('change');
		}
	}

	function addCalcSeat() {
		var
				row = '<tr>',
				count = $('#table-seats-calc tbody tr').length;

		row += '<td>' + (count + 1) + '</td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_weight[' + count + ']" value="" id="input-seat-calc-weight-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_kg; ?></span></div></td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_length[' + count + ']" value="" id="input-seat-calc-length-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_width[' + count + ']" value="" id="input-seat-calc-width-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_height[' + count + ']" value="" id="input-seat-calc-height-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cm; ?></span></div></td>';
		row += '<td><label class="col-sm-12 control-label"><?php echo $text_or; ?></label></td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_volume[' + count + ']" value="" id="input-seat-calc-volume-' + count + '" class="form-control" /><span class="input-group-addon"><?php echo $text_cubic_meter; ?></span></div></td>';
		row += '<td><div class="input-group"><input type="text" name="seat_calc_volume_weight[' + count + ']" value="" id="input-seat-calc-volume_weight-' + count + '" class="form-control" readonly/><span class="input-group-addon"><?php echo $text_kg; ?></span></div></td>';
		row += '<td class="text-center"><button type="button" onclick="$(this).parents(\'tr\').remove()" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		row += '</tr>';

		$('#table-seats-calc tbody').append(row);
	}

	function saveCalcSeats() {
		var
				$trs = $('#table-seats-calc tbody tr'),
				seats = $trs.length,
				weight = 0,
				volume = 0;

		$trs.each(function(i, element) {
			var c_tr = $(element);

			if (!$('#input-weight-' + i).length) {
				addSeat();
			}

			$('#input-weight-' + i).val(c_tr.find('#input-seat-calc-weight-' + i).val());
			$('#input-length-' + i).val(c_tr.find('#input-seat-calc-length-' + i).val());
			$('#input-width-' + i).val(c_tr.find('#input-seat-calc-width-' + i).val());
			$('#input-height-' + i).val(c_tr.find('#input-seat-calc-height-' + i).val());
			$('#input-volume_weight-' + i).val(c_tr.find('#input-seat-calc-volume_weight-' + i).val());

			weight += +c_tr.find('#input-seat-calc-weight-' + i).val();
			volume += +c_tr.find('#input-seat-calc-volume-' + i).val();
		});

		$('#input-weight_general').val(weight);
		$('#input-volume_general').val(volume.toFixed(3)).trigger('change');
		$('#input-seats_amount').val(seats);

		$('#modal-options-seat-calc').modal('hide');
	}

	function saveDeclaredCost() {
		$('#input-declared_cost').val(parseInt($('#td-declared_cost_total')[0].outerText));

		$('#modal-totals-list').modal('hide');
	}

	function formHandler(element) {
		switch(element.id) {
			case 'input-sender_address_pick_up':
				if (element.checked) {
					$('label[for="input-sender_department"]').parent().fadeOut();
					$('label[for="input-sender_address"]').parent().fadeIn();
				} else {
					$('label[for="input-sender_department"]').parent().fadeIn();
					$('label[for="input-sender_address"]').parent().fadeOut();
				}

				break;

			case 'input-sender':
				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getSenderContactPersons&filter=' + encodeURIComponent(element.value),
					dataType: 'json',
					beforeSend: function () {
						$('#input-sender_contact_person').parent().css('position', 'relative').prepend('<div id="ocmax-loader-sender_contact_person" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-sender_contact_person').remove();
					},
					success: function (json) {
						var
								checked = "<?php echo $sender_contact_person; ?>",
								html = '<option value=""><?php echo $text_select; ?></option>';

						for (var i in json) {
							if (json[i]['Ref'] == checked) {
								html += '<option value="' + json[i]['Ref'] + '" selected="selected">' + json[i]['Description'] + ', ' + json[i]['Phones'] + '</option>';
							} else {
								html += '<option value="' + json[i]['Ref'] + '">' + json[i]['Description'] + ', ' + json[i]['Phones'] + '</option>';
							}
						}

						$('#input-sender_contact_person').html(html).selectpicker('refresh').trigger('change');
					}
				});

				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'post',
					data: 'action=getSenderOptions&filter=' + encodeURIComponent(element.value),
					dataType: 'json',
					success: function (json) {
						if (json['CanPayTheThirdPerson']) {
							$('#input-payer > option[value="ThirdPerson"]').prop('disabled', false).trigger('change');
						} else {
							$('#input-payer > option[value="ThirdPerson"]').prop('disabled', true).trigger('change');
						}

						if (json['CanAfterpaymentOnGoodsCost']) {
							$('[for="input-payment_control"]').filter(':hidden').parent().fadeIn();
						} else {
							$('[for="input-payment_control"]').filter(':visible').parent().fadeOut();
						}
					}
				});

				break;

			case 'input-sender_contact_person':
				var phone = element.selectedOptions[0].label.substr(element.selectedOptions[0].label.indexOf(', ') + 2);

				$('#input-sender_contact_person_phone').val(phone);

				break;

			case 'input-sender_region':
				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getCities&filter=' + encodeURIComponent(element.value),
					dataType: 'json',
					beforeSend: function () {
						$('#input-sender_city').parent().css('position', 'relative').prepend('<div id="ocmax-loader-sender_city" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-sender_city').remove();
					},
					success: function (json) {
						if (json instanceof Array) {
							var
									subtext,
									option_s,
									html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (!element.value) {
									subtext = ' data-subtext="' + json[i]['region_description'] + '"';
								} else {
									subtext = '';
								}

								if (json[i]['Ref'] == '<?php echo $sender_city; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option value="' + json[i]['Ref'] + '"' + subtext + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-sender_city').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				break;

			case 'input-sender_city':
				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getDepartments&filter=' + encodeURIComponent(element.value),
					dataType: 'json',
					beforeSend: function () {
						$('#input-sender_department').parent().css('position', 'relative').prepend('<div id="ocmax-loader-sender_department" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
						$('#input-sender_address').parent().css('position', 'relative').prepend('<div id="ocmax-loader-sender_address" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-sender_department').remove();
						$('#ocmax-loader-sender_address').remove();
					},
					success: function (json) {
						if (json instanceof Array || json instanceof Object) {
							var
									option_s,
									html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (json[i]['Ref'] == '<?php echo $sender_department; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option data-tokens="' + json[i]['Number'] + '" value="' + json[i]['Ref'] + '"' + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-sender_department').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getAddress&filter=' + encodeURIComponent(element.value) + '&sender=' + encodeURIComponent($('#input-sender').val()),
					dataType: 'json',
					success: function (json) {
						if (json instanceof Array || json instanceof Object) {
							var
									option_s,
									html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (json[i]['Ref'] == '<?php echo $sender_address; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option data-tokens="' + json[i]['Number'] + '" value="' + json[i]['Ref'] + '"' + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-sender_address').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				break;

			case 'input-recipient_address_type':
				if (element.value == 'doors') {
					$('[for="input-recipient_city"], [for="input-recipient_department"], [for="input-recipient_poshtomat"]').filter(':visible').parent().fadeOut();
					$('[for="input-recipient_settlement_name"], [for="input-recipient_street_name"], [for="input-recipient_house"], [for="input-recipient_flat"], [for="input-preferred_delivery_date"], [for="input-rise_on_floor"], [for="input-elevator"]').filter(':hidden').parent().fadeIn();
					$('#input-departure_type > option[value="TiresWheels"], #input-departure_type > option[value="Pallet"]').attr('disabled', false);

					if ($('#input-preferred_delivery_date').val()) {
						$('[for="input-time_interval"]').filter(':hidden').parent().fadeIn();
					}
				} else if (element.value == 'poshtomat') {
                    $('[for="input-recipient_city"], [for="input-recipient_poshtomat"]').filter(':hidden').parent().fadeIn();
                    $('[for="input-recipient_settlement_name"], [for="input-recipient_department"], [for="input-recipient_street_name"], [for="input-recipient_house"], [for="input-recipient_flat"], [for="input-preferred_delivery_date"], [for="input-time_interval"], [for="input-rise_on_floor"], [for="input-elevator"]').filter(':visible').parent().fadeOut();
					$('#input-departure_type > option[value="TiresWheels"], #input-departure_type > option[value="Pallet"]').attr('disabled', true);
					$('#input-general_parameters').bootstrapToggle('off');
				} else {
					$('[for="input-recipient_city"], [for="input-recipient_department"]').filter(':hidden').parent().fadeIn();
					$('[for="input-recipient_poshtomat"], [for="input-recipient_settlement_name"], [for="input-recipient_street_name"], [for="input-recipient_house"], [for="input-recipient_flat"], [for="input-preferred_delivery_date"], [for="input-time_interval"], [for="input-rise_on_floor"], [for="input-elevator"]').filter(':visible').parent().fadeOut();
					$('#input-departure_type > option[value="TiresWheels"], #input-departure_type > option[value="Pallet"], #input-volume_general, #input-seats_amount, #button-options_seat').attr('disabled', false);
				}

				break;

			case 'input-recipient_region':
				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getCities&filter=' + encodeURIComponent(element.value),
					dataType: 'json',
					beforeSend: function () {
						$('#input-recipient_city').parent().css('position', 'relative').prepend('<div id="ocmax-loader-recipient_city" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-recipient_city').remove();
					},
					success: function (json) {
						if (json instanceof Array) {
							var
									subtext,
									option_s,
									html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (!element.value) {
									subtext = ' data-subtext="' + json[i]['region_description'] + '"';
								} else {
									subtext = '';
								}

								if (json[i]['Ref'] == '<?php echo $recipient_city; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option value="' + json[i]['Ref'] + '"' + subtext + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-recipient_city').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				$('#input-recipient_region_name, #input-recipient_district_name').val('');

				break;

			case 'input-recipient_city':
				var option_s;

				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getDepartments&filter=' + encodeURIComponent(element.value) + '&type=department',
					dataType: 'json',
					beforeSend: function () {
						$('#input-recipient_department').parent().css('position', 'relative').prepend('<div id="ocmax-loader-recipient_department" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-recipient_department').remove();
					},
					success: function (json) {
						if (json instanceof Array || json instanceof Object) {
							var html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (json[i]['Ref'] == '<?php echo $recipient_department; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option data-tokens="' + json[i]['Number'] + '" value="' + json[i]['Ref'] + '"' + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-recipient_department').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'action=getDepartments&filter=' + encodeURIComponent(element.value) + '&type=poshtomat',
					dataType: 'json',
					beforeSend: function () {
						$('#input-recipient_poshtomat').parent().css('position', 'relative').prepend('<div id="ocmax-loader-recipient_poshtomat" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
					},
					complete: function () {
						$('#ocmax-loader-recipient_poshtomat').remove();
					},
					success: function (json) {
						if (json instanceof Array || json instanceof Object) {
							var html = '<option value=""><?php echo $text_select; ?></option>';

							for(var i in json) {
								if (json[i]['Ref'] == '<?php echo $recipient_poshtomat; ?>') {
									option_s = ' selected="selected"';
								} else {
									option_s = '';
								}

								html += '<option data-tokens="' + json[i]['Number'] + '" value="' + json[i]['Ref'] + '"' + option_s + '>' + json[i]['description'] + '</option>'
							}

							$('#input-recipient_poshtomat').html(html).selectpicker('refresh').trigger('change');
						}
					}
				});

				break;

			case 'input-recipient_settlement_name':
				var
						address_type = $('#input-recipient_address_type:checked').val(),
						delivery_date = $('#input-preferred_delivery_date').val();

				if (address_type == 'doors' && delivery_date) {
					$.ajax({
						url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
						type: 'post',
						data: 'action=getTimeIntervals&filter=' + encodeURIComponent(element.value) + '&delivery_date=' + encodeURIComponent(delivery_date),
						dataType: 'json',
						success: function (json) {
							var html = '<option value=""><?php echo $text_during_day; ?></option>';

							for (var i in json) {
								html += '<option value="' + json[i]['Number'] + '">' + json[i]['Start'] + ' - ' + json[i]['End'] + '</option>';
							}

							$('#input-time_interval').html(html);
						}
					});
				}

				break;

			case 'input-departure_type':
				if (element.value == 'Parcel' || element.value == 'Cargo') {
					var html = '<input type="text" name="weight_general" value="<?php echo $weight_general; ?>" placeholder="<?php echo $entry_weight_general; ?>" id="input-weight_general" class="form-control" />';

					$('#input-weight_general').replaceWith(html);

					$('#input-departure_description').attr('disabled', false);

					$('[for*="input-tires_and_wheels"]').filter(':visible').parent().fadeOut();
					$('[for="input-general_parameters"]').filter(':hidden').parent().fadeIn();

					if ($('#input-general_parameters').is(':checked')) {
						$('[for="input-weight_general"], [for="input-volume_weight_general"], [for="input-volume_general"]').filter(':hidden').parent().fadeIn();
					} else {
						$('[for*="input-weight-"], [for*="input-length-"], [for*="input-width-"], [for*="input-height-"], [for*="input-volume_weight-"], [for*="input-manual_processing-"], [for*="input-pack-"]').filter(':hidden').parent().fadeIn();
						$('[id*="text-seat-"]').filter(':hidden').fadeIn();
					}
				} else if (element.value == 'Documents') {
					var html = '<select name="weight_general" id="input-weight_general" class="form-control"><option value=""><?php echo $text_select; ?></option><option value="0.1">0.1</option><option value="0.5">0.5</option><option value="1">1</option></select>';

					$('#input-weight_general').replaceWith(html);

					$('[for="input-general_parameters"], [for*="input-tires_and_wheels"], [for="input-volume_weight_general"], [for="input-volume_general"]').filter(':visible').parent().fadeOut();
					$('[for*="input-weight-"], [for*="input-length-"], [for*="input-width-"], [for*="input-height-"], [for*="input-volume_weight-"], [for*="input-manual_processing-"], [for*="input-pack-"]').filter(':visible').parent().fadeOut();
					$('[id*="text-seat-"]').filter(':visible').fadeOut();

					$('[for="input-weight_general"]').filter(':hidden').parent().fadeIn();
					$('#input-departure_description').attr('disabled', true).val('Документи');
				} else if (element.value == 'TiresWheels') {
					$('[for="input-general_parameters"], [for="input-width"], [for="input-length"], [for="input-height"], [for="input-weight_general"], [for="input-volume_weight_general"], [for="input-volume_general"]').filter(':visible').parent().fadeOut();
					$('[for*="input-weight-"], [for*="input-length-"], [for*="input-width-"], [for*="input-height-"], [for*="input-volume_weight-"], [for*="input-manual_processing-"], [for*="input-pack-"]').filter(':visible').parent().fadeOut();
					$('[id*="text-seat-"]').filter(':visible').fadeOut();

					$('[for*="input-tires_and_wheels"]').filter(':hidden').parent().fadeIn();
					$('#input-departure_description').attr('disabled', true);
					$('#input-departure_description').val('Шини та диски');
				}

				break;

			case 'input-general_parameters':
				if ($(element).is(':checked')) {
					$('[for*="input-weight-"], [for*="input-length-"], [for*="input-width-"], [for*="input-height-"], [for*="input-volume_weight-"], [for*="input-manual_processing-"], [for*="input-pack-"]').filter(':visible').parent().fadeOut();
					$('[for="input-weight_general"], [for="input-volume_general"], [for*="input-volume_weight_general"]').filter(':hidden').parent().fadeIn();
					$('[id*="text-seat-"]').filter(':visible').fadeOut();
					$('[for="input-pack_general"]').filter(':hidden').parent().fadeIn();
				} else {
					$('[for*="input-weight-"], [for*="input-length-"], [for*="input-width-"], [for*="input-height-"], [for*="input-volume_weight-"], [for*="input-manual_processing-"], [for*="input-pack-"]').filter(':hidden').parent().fadeIn();
					$('[for="input-weight_general"], [for="input-volume_general"], [for*="input-volume_weight_general"]').filter(':visible').parent().fadeOut();
					$('[id*="text-seat-"]').filter(':hidden').fadeIn();
					$('[for="input-pack_general"]').filter(':visible').parent().fadeOut();
				}

				break;

			case 'input-pack_general':
			case (element.id.match(/input-pack-/) || {}).input:
				if ($(element).is(':checked')) {
					$(element).parents('.col-sm-2').siblings('.col-sm-7').filter(':hidden').fadeIn();
				} else {
					$(element).parents('.col-sm-2').siblings('.col-sm-7').filter(':visible').fadeOut();
				}

				break;

			case (element.id.match(/input-tires_and_wheels_/) || {}).input:
				var c = 0;

				$('input[id^="input-tires_and_wheels"]').each(function() {
					c += +this.value;
				} );

				$('#input-seats_amount').val(c);

				break;

			case 'input-volume_general':
				$('#input-volume_weight_general').val((element.value * 250).toFixed(3));

				break;

			case (element.id.match(/input-length-/) || {}).input:
			case (element.id.match(/input-width-/) || {}).input:
			case (element.id.match(/input-height-/) || {}).input:
				var i = parseInt(element.id.match(/\d+/));
				$('#input-volume_weight-' + i).val(($('#input-length-' + i).val() * $('#input-width-' + i).val() * $('#input-height-' + i).val() / 1000000 * 250).toFixed(3)).trigger('change');

				break;

			case (element.id.match(/input-seat-calc-volume-/) || {}).input:
				$(element).parents('tr').find('input[id^="input-seat-calc-volume_weight-"]').val((element.value * 250).toFixed(3));

				break;

			case (element.id.match(/input-seat-calc-length-/) || {}).input:
			case (element.id.match(/input-seat-calc-width-/) || {}).input:
			case (element.id.match(/input-seat-calc-height-/) || {}).input:
				var
						$row = $(element).parents('tr'),
						length = $row.find('input[id^="input-seat-calc-length-"]').val(),
						width = $row.find('input[id^="input-seat-calc-width-"]').val(),
						height = $row.find('input[id^="input-seat-calc-height-"]').val();

				$row.find('input[id^="input-seat-calc-volume-"]').val((width * length * height / 1000000).toFixed(3)).trigger('change');

				break;

			case 'input-declared_cost':
				var $backward_delivery_total = $('#input-backward_delivery_total');

				if (+element.value < +$backward_delivery_total.val() && $backward_delivery_total.is(':visible')) {
					element.value = $backward_delivery_total.val();
				}

				break;

			case 'input-payer':
				if (element.value == 'ThirdPerson') {
					$('[for="input-third_person"]').filter(':hidden').parent().fadeIn();
					$('#input-payment_type > option[value ="NonCash"]').prop('selected', true);
					$('#input-payment_type > option[value="Cash"]').prop({'disabled': true, 'selected': false});
				} else {
					$('[for="input-third_person"]').filter(':visible').parent().fadeOut();
					$('#input-payment_type > option[value="Cash"]').prop('disabled', false);
				}

				$('#input-payment_type').trigger('change');

				break;

			case 'input-backward_delivery':
				if (element.value == 'Money') {
					$('[for="input-backward_delivery_total"], [for="input-backward_delivery_payer"]').filter(':hidden').parent().fadeIn();
				} else {
					$('[for="input-backward_delivery_total"], [for="input-backward_delivery_payer"]').filter(':visible').parent().fadeOut();
				}

				break;

			case 'input-backward_delivery_total':
				var $declared_cost = $('#input-declared_cost');

				if (+element.value > +$declared_cost.val()) {
					$declared_cost.val(element.value);
				}

				break;

			case 'input-payment_control':
				if (element.value) {
					$('#input-backward_delivery > option[value ="N"]').prop('selected', true).trigger('change');
				} else {
					$('#input-backward_delivery > option[value ="Money"]').prop('selected', true).trigger('change');
				}

				break;

			case 'input-preferred_delivery_date':
				if (element.value && $('#input-recipient_address_type:checked').val() == 'doors') {
					$('[for="input-time_interval"]').filter(':hidden').parent().fadeIn();
					$('#input-recipient_settlement_name').trigger('change')
				} else {
					$('[for="input-time_interval"]').filter(':visible').parent().fadeOut();
				}

				break;

			case 'input-elevator':
				if ($('#input-elevator:checked').val()) {
					$('#input-rise_on_floor').attr('disabled', true);
				} else {
					$('#input-rise_on_floor').attr('disabled', false);
				}

				break;
		}
	}

	function getRecipients() {
		$.ajax({
			url: 'index.php?route=extension/shipping/novaposhta/getNPData&token=<?php echo $token; ?>',
			type: 'POST',
			data: 'action=getRecipients',
			dataType: 'json',
			beforeSend: function () {
				$('#input-recipient').parent().css('position', 'relative').prepend('<div id="ocmax-loader-recipient" style="position: absolute; left: 50%; top: 50%; transform: translateY(-50%); z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-lg fa-fw" aria-hidden="true"></i></div>');
			},
			complete: function () {
				$('#ocmax-loader-recipient').remove();
			},
			success: function (json) {
				var
						descr,
						option_s,
						html = '<option value=""><?php echo $text_select; ?></option>';

				for (var i in json) {
					if (json[i]['CounterpartyType'] == 'Organization') {
						descr = json[i]['Description'] + ' <?php echo $entry_edrpou; ?> ' + json[i]['EDRPOU'];
					} else {
						descr = json[i]['Description'];
					}

					if (($('#input-recipient_edrpou').val() && json[i]['EDRPOU'] == $('#input-recipient_edrpou').val()) || (json[i]['Ref'] && json[i]['Ref'] == '<?php echo $recipient; ?>') || (!json[i]['Ref'] && json[i]['Description'] == '<?php echo $recipient_name; ?>')) {
						option_s = ' selected="selected"';
					} else {
						option_s = '';
					}

					html += '<option value="' + json[i]['Ref'] + '"' + option_s + '>' + descr + '</option>';
				}

				$('#input-recipient').html(html).selectpicker('refresh').trigger('change');
			}
		});
	}

	$( function () {
		/* START Preparation */
		$.ajaxSetup({
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus);
			}
		});

		getRecipients();

		$('.selectpicker').selectpicker({
            liveSearchStyle: 'startsWith',
			iconBase: 'fa',
			tickIcon: 'fa-check'
		});

		$('[data-tooltip=true]').tooltip();

		$('.date').datetimepicker( {pickTime: false} ).on('change', function () {
			var input = $(this).find('input')[0];

			formHandler(input);
			validateForm(input);
		});

		$('form').on('change', 'input, select, textarea', function() {
			setTimeout(formHandler, 100, this);
			setTimeout(validateForm, 200, this);
		});

		$('#input-sender_address_pick_up, #input-sender, #input-sender_contact_person, #input-sender_region, #input-recipient_address_type:checked, #input-recipient, #input-recipient_region, #input-departure_type, #input-general_parameters, #input-pack_general, input[id^="input-pack-"], #input-delivery_payer, #input-backward_delivery, #input-elevator').each(function() {
			formHandler(this);
		});
		/* END Preparation */

		/* START Change totals list */
		$('#modal-totals-list').on('click', '#button-total_declared_cost_minus, #button-total_declared_cost_plus', function (e) {
			var b = $(e.currentTarget),
				cost = parseInt(b.parents('tr').find('td:eq(1)').text()),
				total = $('#td-declared_cost_total')[0].outerText;

			if (e.currentTarget.id == 'button-total_declared_cost_minus') {
				b.replaceWith('<button type="button" class="btn btn-success btn-xs" id="button-total_declared_cost_plus"><i class="fa fa-plus"></i></button>');

				total = total.replace(/-?\d+/, parseInt(total) - cost);
			} else {
				b.replaceWith('<button type="button" class="btn btn-danger btn-xs" id="button-total_declared_cost_minus"><i class="fa fa-minus"></i></button>');

				total = total.replace(/-?\d+/, parseInt(total) + cost);
			}

			$('#td-declared_cost_total').html('<strong>' + total + '</strong>');
		});
		/* END Change totals list */

		/* START Search recipient settlement */
		$('#input-recipient_settlement_name').autocomplete({
			source: function(request, response) {
                var post_data;

				post_data = 'settlement=' + encodeURIComponent(request)

				if ($('#input-recipient_region').val()) {
					post_data += encodeURIComponent(' ' + $('#input-recipient_region option:selected').text());
				}

				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/autocomplete&token=<?php echo $token; ?>',
					type: 'post',
					data: post_data,
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label:		  item['FullDescription'],
								value:		  item['Ref'],
								name:		  item['Description'],
								region_name:  item['Area'] || '',
								distric_name: item['Region'] || '',
							}
						} ));
					}
				});
			},
			select: function(item) {
				$(this).val(item['name']).trigger('change');
				$('#input-recipient_settlement').val(item['value']);
				$('#input-recipient_region_name').val(item['region_name']);
				$('#input-recipient_district_name').val(item['distric_name']);
			}
		});
		/* END Search recipient settlement */

		/* START Search street */
		$('#input-recipient_street_name').autocomplete({
			source: function(request, response) {
				$.ajax( {
					url: 'index.php?route=extension/shipping/novaposhta/autocomplete&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'filter=' + encodeURIComponent($('#input-recipient_settlement').val()) + '&street=' + encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['Description'],
								value: item['Description'],
								ref:   item['Ref']
							}
						} ));
					}
				});
			},
			select: function(item) {
				$(this).val(item['value']).trigger('change');
				$('#input-recipient_street').val(item['ref']);
			}
		});
		/* END Search street */

		/* START Search departure description */
		$('#input-departure_description').autocomplete({
			source: function(request, response) {
				$.ajax({
					url: 'index.php?route=extension/shipping/novaposhta/autocomplete&token=<?php echo $token; ?>',
					type: 'post',
					data: 'departure_description=' + encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['Description'],
								value: item['Description'],
							}
						}));
					}
				});
			},
			select: function(item) {
				$(this).val(item['value']).triggerHandler('change');
			}
		});
		/* END Search departure description */

		/* START Save CN */
		$('#button-save').on('click', function () {
			var $post_data = $('input[type="text"], input[type="radio"]:checked, input[type="checkbox"]:checked, select, textarea').filter(':visible').add('input[type="hidden"]').add('div.toggle:visible input[type="checkbox"]:checked');

			$.ajax({
				url: 'index.php?route=extension/shipping/novaposhta/saveCN&order_id=<?php echo $order_id; ?>&cn_ref=<?php echo $cn_ref; ?>&token=<?php echo $token; ?>',
				type: 'POST',
				data: $post_data,
				dataType: 'json',
				beforeSend: function() {
					$('body').fadeTo('fast', 0.8).prepend('<div id="ocmax-loader" style="position: fixed; top: 50%;	left: 50%; z-index: 9999;"><i class="fa fa-clock-o fa-spin fa-3x fa-fw"></i></div>');
				},
				complete: function(json){
					var $alerts = $('.alert-danger, .alert-success');

					if ($alerts.length !== 0) {
						setTimeout(function() { $alerts.remove(); }, 15000);
					}

					if (json['errors'] != 'undefined' || json['warning'] != 'undefined') {
						$('html, body').animate({ scrollTop: $('.has-error, .alert').offset().top }, 1000);
					}

					$('body').fadeTo('fast', 1)
					$('#ocmax-loader').remove();
				},
				success: function(json) {
					if (json['success']) {
						location.href = 'index.php?route=extension/shipping/novaposhta/getCNList&filter_departure_date_from=' + json['success'] + '&token=<?php echo $token; ?>';
					} else {
						$('.help-block').remove();
						$('div').removeClass('has-error has-success');

						checkErrors(json);
					}
				}
			});
		});
		/* END Save CN */
	});
//--></script>
<?php echo $footer; ?>