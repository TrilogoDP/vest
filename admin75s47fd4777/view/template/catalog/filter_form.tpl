<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-filter" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
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
						<label class="col-sm-2 control-label"><?php echo $entry_group; ?></label>
						<div class="col-sm-10">
							<?php foreach ($languages as $language) { ?>
								<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','filter_group_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','filter_group_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','filter_group_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
									<input type="text" name="filter_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter_group_description[$language['language_id']]) ? $filter_group_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_group; ?>" class="form-control" />
								</div>
								<?php if (isset($error_group[$language['language_id']])) { ?>
									<div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-selection">Использовать в подборе</label>
						<div class="col-sm-10">
							<select name="selection" id="input-selection" class="form-control">
								<?php if ($selection) { ?>
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
						<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
					<table id="filter" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left required"><?php echo $entry_name; ?></td>
								<td class="text-right"><?php echo $entry_sort_order; ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<?php $filter_row = 0; ?>
							<?php foreach ($filters as $filter) { ?>
								<tr id="filter-row<?php echo $filter_row; ?>">
									<td class="text-left" style="width: 70%;"><input type="hidden" name="filter[<?php echo $filter_row; ?>][filter_id]" value="<?php echo $filter['filter_id']; ?>" />
										<?php foreach ($languages as $language) { ?>
											<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','filter[<?php echo $filter_row; ?>][filter_description][<?php echo $at_source; ?>][name]',1);"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','filter[<?php echo $filter_row; ?>][filter_description][<?php echo $at_source; ?>][name]',1);">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','filter[<?php echo $filter_row; ?>][filter_description][<?php echo $at_source; ?>][name]',1);">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="filter[<?php echo $filter_row; ?>][filter_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($filter['filter_description'][$language['language_id']]) ? $filter['filter_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
											</div>
											<?php if (isset($error_filter[$filter_row][$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_filter[$filter_row][$language['language_id']]; ?></div>
											<?php } ?>
										<?php } ?></td>
										<td class="text-right"><input type="text" name="filter[<?php echo $filter_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>
										<td class="text-left"><button type="button" onclick="$('#filter-row<?php echo $filter_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
								</tr>
								<?php $filter_row++; ?>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td class="text-left"><a onclick="addFilterRow();" data-toggle="tooltip" title="<?php echo $button_filter_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		var filter_row = <?php echo $filter_row; ?>;
		
		function addFilterRow() {
			html  = '<tr id="filter-row' + filter_row + '">';	
			html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
			<?php foreach ($languages as $language) { ?>
				html += '  <div class="input-group">';
				html += '    <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
<?php if($language['language_id'] != $at_source && $at_status) { ?>
    html += '<a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),\'input\',\'filter[' + filter_row + '][filter_description][<?php echo $at_source; ?>][name]\',1);"><?php echo $btn_copy; ?></a>';
    <?php if($at_status == 1){ ?>
        html += '<a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),\'<?php echo $at_api_key; ?>\',\'<?php echo $at_g_language[$at_source]; ?>\',\'<?php echo $at_g_language[$language['language_id']]; ?>\',';
        html += '\'input\',\'filter['+filter_row+'][filter_description][<?php echo $at_source; ?>][name]\',1);"><?php echo $btn_translate; ?></a>';
    <?php } else if($at_status == 2) { ?>
        html += '<a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),\'<?php echo $at_m_language[$at_source]; ?>\',\'<?php echo $at_m_language[$language['language_id']]; ?>\',';
        html += '\'input\',\'filter['+filter_row+'][filter_description][<?php echo $at_source; ?>][name]\',1);"><?php echo $btn_translate; ?></a>';
    <?php } ?>
<?php } ?>
html += '<input type="text" name="filter[' + filter_row + '][filter_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_name; ?>" class="form-control" />';
				html += '  </div>';
			<?php } ?>
			html += '  </td>';
			html += '  <td class="text-right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" /></td>';
			html += '  <td class="text-left"><button type="button" onclick="$(\'#filter-row' + filter_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';	
			
			$('#filter tbody').append(html);
			
			filter_row++;
		}
	//--></script></div>
	<?php echo $footer; ?> 	