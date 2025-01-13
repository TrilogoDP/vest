<?php echo $header;?>
<?php $extension_id = str_replace('_', '-', $extension); ?>
<link rel="stylesheet" type="text/css" href="<?php echo (defined('JPATH_ADMINISTRATOR') ? 'admin/' : ''); ?>view/stylesheet/<?php echo $stylesheet; ?>.css" />

<?php echo $column_left; ?>

<div id="content" class="<?php echo $extension_id; ?>">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-<?php echo $extension_id; ?>" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<button onclick="$('#apply').attr('value', '1'); $('#form-<?php echo $extension_id; ?>').submit();" form="form-<?php echo $extension_id; ?>" data-toggle="tooltip" title="<?php echo $button_apply; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
        		<a href="<?php echo $maincancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<?php if ($errors) { ?>
			<?php foreach ($errors as $error) { ?>
				<div class="alert alert-danger">
    				<i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
      				<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
			<?php } ?>
		<?php } elseif ($success) { ?>
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
        		<form action="<?php echo $mainaction; ?>" method="post" enctype="multipart/form-data" id="form-<?php echo $extension_id; ?>" class="form-horizontal">
        			<input type="hidden" name="apply" id="apply" value="0">
        	
      				<?php if (count(array_keys($settings)) > 1) { ?>
      					<ul class="nav nav-tabs">
      						<?php $tabkey = 0; ?>
	      					<?php foreach ($settings as $tab => $options) { ?>
            					<li class="nav-tab<?php echo ($tabkey == 0 ? ' active' : ''); ?>"><a href="#tab-<?php echo str_replace('_', '-', $tab); ?>" data-toggle="tab"<?php echo ($tab == 'new' ? " title='".$button_add_tab."'" : ''); ?>><?php echo ${'tab_'.$tab}; ?></a></li>
            					<?php $tabkey ++; ?>
            				<?php } ?>
          				</ul>
          	        <?php } ?>
          	        
          	        <div class="tab-content">
          	        	<?php $tabkey = 0; ?>
          	        	<?php foreach ($settings as $tab => $options) { ?>        	
      						<div class="tab-pane<?php echo ($tabkey == 0 ? ' active' : ''); ?>" id="tab-<?php echo str_replace('_', '-', $tab); ?>">
      							<?php if (!empty(${'help_'.$tab}) && (${'help_'.$tab} != 'help_'.$tab)) { ?>
      								<span class="notice"><?php echo ${'help_'.$tab}; ?></span>
      							<?php } ?>      							
								<?php foreach ($options as $key => $option) { ?>
									<?php $key_id = str_replace('_', '-', $key); ?>
									<?php if ($option == 'title') { ?>
										<h2><?php echo ${'entry_'.$key}; ?></h2>
										<?php if (!empty(${'help_'.$key}) && (${'help_'.$key} != 'help_'.$key)) { ?>
											<span class="note"><?php echo ${'help_'.$key}; ?></span>
										<?php } ?>
									<?php } elseif ($option == 'notice') { ?>
										<span class="notice"><?php echo ${'entry_'.$key}; ?></span>							
									<?php } elseif ($option == 'hidden') { ?>
										<input type="hidden" name="<?php echo $extension.'_'.$key; ?>" value="<?php echo ${$extension.'_'.$key}; ?>" />
									<?php } else { ?>
										<div class="form-group <?php echo $extension_id.'-'.$key_id; ?>">
											<label class="col-sm-2 control-label" for="input-<?php echo $key_id; ?>">
												<?php if (${'help_'.$key} && (${'help_'.$key} != 'help_'.$key)) { ?>
													<span data-toggle="tooltip" title="<?php echo ${'help_'.$key}; ?>"><?php echo ${'entry_'.$key}; ?></span>
												<?php } else {?>
													<?php echo ${'entry_'.$key}; ?>
												<?php } ?>
											</label>
											<div class="col-sm-10">
												<?php if ($option == 'html') { ?>
													<?php echo ${$extension.'_'.$key}; ?>
												<?php } elseif ($option == 'text') { ?>
													<span id="input-<?php echo $key_id; ?>" class="input-text">
														<?php echo ${$extension.'_'.$key}; ?>
													</span>
												<?php } elseif ($option == 'date') { ?>
													<div class="input-group date">
														<input type="text" name="<?php echo $extension.'_'.$key; ?>" value="<?php echo ${$extension.'_'.$key}; ?>" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												<?php } elseif (($option == 'input') || ($option == 'multi-input')) { ?>
													<?php if ($option == 'multi-input') { ?>
														<?php foreach ($languages as $language) { ?>
															<div class="input-group">
																<span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span>
																<input type="text" name="<?php echo $extension.'_'.$key; ?>[<?php echo $language['language_id']; ?>]" value="<?php echo (isset(${$extension.'_'.$key}[$language['language_id']]) ? ${$extension.'_'.$key}[$language['language_id']] : ''); ?>" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" class="form-control" />
															</div>
														<?php } ?>
													<?php } else { ?>
														<input type="text" name="<?php echo $extension.'_'.$key; ?>" value="<?php echo ${$extension.'_'.$key}; ?>" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" class="form-control" />
													<?php } ?>
												<?php } elseif (($option == 'textarea') || ($option == 'multi-textarea')) { ?>
													<?php if ($option == 'multi-textarea') { ?>
        												<ul class="nav nav-tabs" id="<?php echo $extension; ?>-<?php echo $key_id; ?>-languages">
        													<?php foreach ($languages as $language) { ?>
        														<li><a href="#<?php echo $extension; ?>-<?php echo $key_id; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
        													<?php } ?>
			      										</ul>
      													<div class="tab-content">
															<?php foreach ($languages as $language) { ?>
																<div class="tab-pane" id="<?php echo $extension; ?>-<?php echo $key_id; ?>-<?php echo $language['language_id']; ?>">
																	<textarea name="<?php echo $extension; ?>_<?php echo $key; ?>[<?php echo $language['language_id']; ?>]" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>-<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo (isset(${$extension.'_'.$key}[$language['language_id']]) ? ${$extension.'_'.$key}[$language['language_id']] : ''); ?></textarea>
																</div>
															<?php } ?>
														</div>
													<?php } else { ?>	
														<textarea name="<?php echo $extension; ?>_<?php echo $key; ?>" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" class="form-control summernote"><?php echo ${$extension.'_'.$key}; ?></textarea>
													<?php } ?>			
												<?php } elseif ($option == 'select') { ?>
													<select name="<?php echo $extension.'_'.$key; ?>" id="input-<?php echo $key_id; ?>" class="form-control">
														<?php foreach (${$key} as $item) { ?>
															<option value="<?php echo $item[0]; ?>"<?php echo ($item[0] === ${$extension.'_'.$key} ? ' selected="selected"' : ''); ?>><?php echo $item[1]; ?></option>
														<?php } ?>
													</select>
												<?php } elseif ($option == 'checkbox') { ?>
													<div class="well well-sm" style="height: 100px; overflow: auto;">
														<?php foreach (${$key} as $item) { ?>
															<div class="checkbox">
																<label>
																	<input type="checkbox" name="<?php echo $extension.'_'.$key; ?>[]" value="<?php echo $item[0]; ?>"<?php echo (is_array(${$extension.'_'.$key}) && in_array($item[0], ${$extension.'_'.$key}) ? ' checked="checked"' : ''); ?> /> <?php echo $item[1]; ?>
																</label>
															</div>
														<?php } ?>
													</div>
													<div class="checkbox-select">
														<a onclick="$(this).parent().parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all;?></a>
													</div>
												<?php } elseif (($option == 'autocomplete') || ($option == 'multiautocomplete')) { ?>
													<?php if ($option == 'multiautocomplete') { ?>
														<input type="text" name="autocomplete_<?php echo $key; ?>" value="" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" class="form-control" />
														<div id="<?php echo $extension_id.'-'.$key_id; ?>" class="well well-sm autocomplete" style="height: 150px; overflow: auto;">
															<?php foreach (${$key} as $item) { ?>
																<div id="<?php echo $extension_id.'-'.$key_id.'-'.$item[0]; ?>">
																	<i class="fa fa-minus-circle"></i> <?php echo $item[1]; ?>
																	<input type="hidden" name="<?php echo $extension.'_'.$key; ?>[]" value="<?php echo $item[0]; ?>" />
																</div>
															<?php } ?>
														</div>
                										<div class="checkbox-select">
															<a onclick="$('#<?php echo $extension_id.'-'.$key_id; ?> div').remove();"><?php echo $text_remove_all; ?></a>
														</div>
													<?php } else { ?>
														<input type="text" name="autocomplete_<?php echo $key; ?>" value="<?php echo ${'autocomplete_'.$key}; ?>" placeholder="<?php echo ${'entry_'.$key}; ?>" id="input-<?php echo $key_id; ?>" class="form-control" />
														<input type="hidden" name="<?php echo $extension.'_'.$key; ?>" value="<?php echo ${$extension.'_'.$key}; ?>" />
													<?php } ?>
												<?php } elseif ($option == 'radio') { ?>
													<label class="radio-inline">
														<input type="radio" name="<?php echo $extension.'_'.$key; ?>" value="1"<?php echo (${$extension.'_'.$key} ? ' checked="checked"' : ''); ?> /><?php echo $text_yes; ?>
													</label>
													<label class="radio-inline">
														<input type="radio" name="<?php echo $extension.'_'.$key; ?>" value="0"<?php echo (!${$extension.'_'.$key} ? ' checked="checked"' : ''); ?> /><?php echo $text_no; ?>
													</label>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<?php $tabkey ++; ?>
						<?php } ?>
					</div>								
					
            	</form>   		
          	</div>
      	</div>
    </div>
</div>
<?php echo $footer; ?>
					
<script type="text/javascript">

$(".items").on("click", ".add-item", function()
{			
	tab = $(this).closest('.item-row').attr('id');
	values = tab.split("-");
	valuetype = values[0];

	itemkey = $.now();

	html = "<div id='" + valuetype + "-" + itemkey + "' class='item-row'>";
	
	html += "<div class='item-inputs'>";
	html += "<select name='<?php echo $extension; ?>_" + valuetype + "[" + itemkey + "][payment]' class='form-control'>";
	html += "<option value=''><?php echo $entry_payment; ?></option>";
			
	<?php foreach ($payments as $payment) { ?>
		html += "<option value='<?php echo $payment[0]; ?>'><?php echo $payment[1]; ?></option>";
	<?php } ?>
			
	html += "</select>";
	html += '</div>';
				
	html += "<div class='item-inputs'>";
	html += "<input type='text' name='<?php echo $extension; ?>_" + valuetype + "[" + itemkey + "][minimum]' class='form-control' value='' placeholder='<?php echo $entry_minimum; ?>' />";
	html += '</div>';
					
	html += "<div class='item-inputs'>";
	html += "<input type='text' name='<?php echo $extension; ?>_" + valuetype + "[" + itemkey + "][maximum]' class='form-control' value='' placeholder='<?php echo $entry_maximum; ?>' />";
	html += '</div>';
	
	html += "<div class='item-inputs'>";
	html += "<input type='text' name='<?php echo $extension; ?>_" + valuetype + "[" + itemkey + "][maximum_over]' class='form-control' value='' placeholder='<?php echo $entry_maximum; ?>' />";
	html += '</div>';
					
	html += "<div class='item-inputs'>";
	html += "<input type='text' name='<?php echo $extension; ?>_" + valuetype + "[" + itemkey + "][value]' class='form-control' value='' placeholder='<?php echo $entry_value; ?>' />";
	html += '</div>';
              		
    html += "<div class='item-buttons'>";
	html += "<a data-toggle='tooltip' title='<?php echo $button_item_add; ?>' class='btn btn-primary add-item'><i class='fa fa-plus'></i></a>";
	html += "</div>";
	
	html += "</div>";
	
	$(this).removeClass("add-item");
	$(this).addClass("remove-item");
	
	$(this).removeClass("btn-primary");
	$(this).addClass("btn-danger");
	
	$(this).attr("title", "<?php echo $button_items_remove; ?>");
	$(this).attr("data-original-title", "<?php echo $button_item_remove; ?>");

	$(this).children().removeClass("fa-plus");
	$(this).children().addClass("fa-minus");
		
	$(this).closest(".item-row").after(html);
});

$(".items").on("click", ".remove-item", function()
{
	html = "<a data-toggle='tooltip' title='<?php echo $button_item_add; ?>' class='btn btn-primary add-item'><i class='fa fa-plus'></i></a>";
	
	if ($(this).closest(".item-row").is(':last-child')) {
		$(this).closest(".item-row").prev().children(".remove-item").after(html);
	}
	
	if (!$(this).closest(".item-row").is(':only-child')) {
		$(this).closest(".item-row").remove();
	}
});

</script>