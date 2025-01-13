<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-attribute" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
<?php if($at_status){ ?>
<?php if($at_status == 2) { ?>
<?php if(isset($microsoft_token_error)){ $m_token_error = $microsoft_token_error; } else { $m_token_error = ''; } ?>
<input type="hidden" name="microsoft_token" id="microsoft_token" data-error="<?php echo $m_token_error; ?>" value="<?php echo $m_token; ?>">
<script type="text/javascript">$(document).ready(function(){setInterval(updateMicrosoftToken,54e4)});</script>
<?php } ?>
<div class="form-group">
    <label class="col-sm-2 control-label"></label>
    <div class="col-sm-10">
        <a class="btn btn-default" onclick="$('a[class^=\'btn-translate\']').trigger('click')"><?php echo $btn_translate_all; ?></a>&nbsp;
        <a class="btn btn-default" onclick="$('a[class^=\'btn-copy\']').trigger('click')"><?php echo $btn_copy_all; ?></a>
    </div>
</div>
<?php } ?>
					<div class="form-group required">
						<label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<?php foreach ($languages as $language) { ?>
								<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','attribute_description[<?php echo $at_source; ?>][name]',1);"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','attribute_description[<?php echo $at_source; ?>][name]',1);">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','attribute_description[<?php echo $at_source; ?>][name]',1);">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
									<input type="text" name="attribute_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($attribute_description[$language['language_id']]) ? $attribute_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
								</div>
								<?php if (isset($error_name[$language['language_id']])) { ?>
									<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-highlight">Product Highlight (Google Merchant)</label>
						<div class="col-sm-10">
							<select name="highlight" id="input-highlight" class="form-control">
								<?php if ($highlight) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="suppler-mappings">У поставщиков</label>
						<div class="col-sm-10">
							<textarea rows="20" name="suppler_mappings" placeholder="названия атрибута у поставщиков" id="suppler-mappings" class="form-control"><?php echo $suppler_mappings; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-attribute-group"><?php echo $entry_attribute_group; ?></label>
						<div class="col-sm-10">
							<select name="attribute_group_id" id="input-attribute-group" class="form-control">
								<option value="0"></option>
								<?php foreach ($attribute_groups as $attribute_group) { ?>
									<?php if ($attribute_group['attribute_group_id'] == $attribute_group_id) { ?>
										<option value="<?php echo $attribute_group['attribute_group_id']; ?>" selected="selected"><?php echo $attribute_group['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $attribute_group['attribute_group_id']; ?>"><?php echo $attribute_group['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_attribute_group) { ?>
								<div class="text-danger"><?php echo $error_attribute_group; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
