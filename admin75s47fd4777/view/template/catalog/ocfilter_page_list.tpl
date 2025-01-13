<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?php echo $add; ?>" data-toggle="tooltip" title="Добавить страницу фильтра" class="btn btn-primary"><i class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
		<div class="panel panel-default">
			<div class="panel-body">

				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-category-id"><?php echo $entry_category; ?></label>
								<select name="filter_category_id" id="input-category-id" class="form-control ocfilter-categories">
									<option value="*"></option>
									<?php foreach ($categories as $category) { ?>
										<?php if ($category['category_id'] == $filter_category_id) { ?>
											<option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>" selected="selected"><?php echo $category['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>"><?php echo $category['name']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-name">Название</label>
								<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="input-status">Статус</label>
								<select name="filter_status" id="input-status" class="form-control">
									<option value="*"></option>
									<?php if ($filter_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
									<?php } ?>
									<?php if (($filter_status !== null) && !$filter_status) { ?>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</div>							
						</div>
						<div class="col-sm-1">
							<div class="form-group">
								<label class="control-label" for="input-status">Лимит</label>							
								<select name="limit" class="form-control">
									<option value="*"></option>
									<?php foreach ([$config_limit, 50, 100, 200] as $limits) { ?>
										<option value="<?php echo $limits; ?>" <?php if ($limits == $limit) { ?>selected="selected"<?php } ?> ><?php echo $limits; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-1">
							<div class="form-group">
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
							</div>
						</div>
					</div>
				</div>


				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked).trigger('change');" /></td>
									<td class="left">Категория</td>
									<td class="left">Название</td>									
									<td class="left">Параметр</td>
									<td class="left">ЧПУ</td>
									<td class="left">Статус</td>
									<td class="left">Мегаменю</td>
									<td class="left">Сортировка</td>
									<td class="right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($pages) { ?>
									<?php foreach ($pages as $page) { ?>
										<tr>
											<td class="text-center">
												<?php if ($page['selected']) { ?>
													<input type="checkbox" name="selected[]" value="<?php echo $page['ocfilter_page_id']; ?>" checked="checked" />
													<?php } else { ?>
													<input type="checkbox" name="selected[]" value="<?php echo $page['ocfilter_page_id']; ?>" />
												<?php } ?>
											</td>
											<td class="left"><?php echo $page['category']; ?></td>
											<td class="left">
												<?php echo $page['title']; ?>
												<?php if ($page['name']) { ?>
													<br /><code><?php echo $page['name']; ?></code>
												<?php } ?>												
											</td>
											<td class="left"><code><?php echo $page['params']; ?></code></td>
											<td class="left"><?php echo $page['keyword']; ?></td>
											<td class="right">
												<?php if ($page['status']) { ?>
													<span class="label label-success">
														<?php echo $text_enabled; ?>
													</span>
												<?php } else { ?>
													<span class="label label-danger">
														<?php echo $text_disabled; ?>
													</span>
												<?php } ?>											
											</td>
											<td class="right">
												<?php if ($page['megamenu']) { ?>
													<span class="label label-success">
														<?php echo $text_enabled; ?>
													</span>
												<?php } else { ?>
													<span class="label label-danger">
														<?php echo $text_disabled; ?>
													</span>
												<?php } ?>
											</td>
											<td class="left">
												<code><?php echo $page['sort_order']; ?></code>
											</td>
											<td class="right">
												<a href="<?php echo $page['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
											</td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
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
</div>
<script type="text/javascript">
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/ocfilter_page&token=<?php echo $token; ?>';

	var filter_title = $('input[name=\'filter_title\']').val();

	if (filter_title) {
		url += '&filter_title=' + encodeURIComponent(filter_title);
	}

	var filter_category_id = $('select[name=\'filter_category_id\']').val();

  	if (filter_category_id != '*') {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var limit = $('select[name=\'limit\']').val();

	if (limit != '*') {
		url += '&limit=' + encodeURIComponent(limit);
	}

	location = url;
});
</script>
<?php echo $footer; ?>