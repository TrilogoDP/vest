<?php echo $header; ?><?php echo $column_left; ?> 
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?> 
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>	
		<?php if ($success) { ?>
		<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?> 
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>	
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<div class="tab-content">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
							<div class="col-sm-10">
								<select name="oct_information_bar_status" id="input-status" class="form-control">
									<?php if ($oct_information_bar_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div id="all_settings">
							<ul class="nav nav-tabs" id="language">
								<?php foreach ($languages as $language) { ?>
								<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
								<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-text<?php echo $language['language_id']; ?>"><?php echo $entry_text; ?></label>
										<div class="col-sm-10">
											<textarea name="oct_information_bar_data[module_text][<?php echo $language['language_id']; ?>]" class="form-control summernote" placeholder="<?php echo $entry_text; ?>" id="input-text<?php echo $language['language_id']; ?>"><?php echo isset($oct_information_bar_data['module_text'][$language['language_id']]) ? $oct_information_bar_data['module_text'][$language['language_id']] : ''; ?></textarea>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-day"><?php echo $entry_value; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[value]" value="<?php echo (isset($oct_information_bar_data['value']) && $oct_information_bar_data['value']) ? $oct_information_bar_data['value'] : 'oct_information_bar'; ?>" id="input-day" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-day"><span data-toggle="tooltip" title="<?php echo $entry_helper_max_day; ?>"><?php echo $entry_max_day; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[max_day]" value="<?php echo $oct_information_bar_data['max_day']; ?>" id="input-day" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-background_bar"><?php echo $entry_background_bar; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[background_bar]" value="<?php echo (isset($oct_information_bar_data['background_bar']) && $oct_information_bar_data['background_bar']) ? $oct_information_bar_data['background_bar'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-background_bar" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-color_text"><?php echo $entry_bar_color_text; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[color_text]" value="<?php echo (isset($oct_information_bar_data['color_text']) && $oct_information_bar_data['color_text']) ? $oct_information_bar_data['color_text'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-color_text" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-color_url"><?php echo $entry_bar_color_url; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[color_url]" value="<?php echo (isset($oct_information_bar_data['color_url']) && $oct_information_bar_data['color_url']) ? $oct_information_bar_data['color_url'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-color_url" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-background_button"><?php echo $entry_bar_background_button; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[background_button]" value="<?php echo (isset($oct_information_bar_data['background_button']) && $oct_information_bar_data['background_button']) ? $oct_information_bar_data['background_button'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-background_button" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-background_button_hover"><?php echo $entry_bar_background_button_hover; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[background_button_hover]" value="<?php echo (isset($oct_information_bar_data['background_button_hover']) && $oct_information_bar_data['background_button_hover']) ? $oct_information_bar_data['background_button_hover'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-background_button_hover" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-color_text_button"><?php echo $entry_bar_color_text_button; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[color_text_button]" value="<?php echo (isset($oct_information_bar_data['color_text_button']) && $oct_information_bar_data['color_text_button']) ? $oct_information_bar_data['color_text_button'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-color_text_button" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-color_text_button_hover"><?php echo $entry_bar_color_text_button_hover; ?></label>
								<div class="col-sm-10">
									<input type="text" name="oct_information_bar_data[color_text_button_hover]" value="<?php echo (isset($oct_information_bar_data['color_text_button_hover']) && $oct_information_bar_data['color_text_button_hover']) ? $oct_information_bar_data['color_text_button_hover'] : 'rgba(0, 0, 0, .85)'; ?>" id="input-color_text_button_hover" class="form-control spectrum" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="select-oct_information_bar_data_indormation_id"><?php echo $entry_link; ?></label>
								<div class="col-sm-10">
									<select id="select-oct_information_bar_data_indormation_id" name="oct_information_bar_data[indormation_id]" class="form-control">
										<option value=""> -- </option>
										<?php foreach ($informations as $information) { ?>
										<option value="<?php echo $information['information_id']; ?>" <?php if ($oct_information_bar_data['indormation_id'] == $information['information_id']) { ?>selected="selected"<?php } ?>><?php echo $information['title']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	<?php if ($ckeditor) { ?>
	  <?php foreach ($languages as $language) { ?>
	      ckeditorInit('input-text<?php echo $language['language_id']; ?>', getURLVar('token'));
	  <?php } ?>
	<?php } ?>
</script>
<script>
	$('#language a:first').tab('show');
</script>
<script type="text/javascript">
$(".spectrum").spectrum({
	preferredFormat: "rgb",
	showInitial: true,
	showInput: true,
	showAlpha: true,
	showPalette: true,
	palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]]
});
</script>
<?php echo $footer; ?>