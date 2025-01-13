<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">

<!-- quicksave -->
	  <?php if (isset($pidqs) && $pidqs) { ?>
	  <button id="qsave" style="margin: 0 10px;" data-toggle="tooltip" title="Quick Save" class="btn btn-warning"><i class="fa fa-save"></i></button>
	  <?php } ?>
<!-- quicksave end -->
			
				<button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">

<?php if($at_status == 2) { ?>
<?php if(isset($microsoft_token_error)){ $m_token_error = $microsoft_token_error; } else { $m_token_error = ''; } ?>
<input type="hidden" name="microsoft_token" id="microsoft_token" data-error="<?php echo $m_token_error; ?>" value="<?php echo $m_token; ?>">
<script type="text/javascript">$(document).ready(function(){setInterval(updateMicrosoftToken,54e4)});</script>
<?php } ?>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
						<li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
<li><a href="#tab-epicentr" data-toggle="tab"><i style="color:#0f62c1;">Epicentr XML</i></a></li>
<li><a href="#tab-faq" data-toggle="tab"><?php echo $tab_faq; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<ul class="nav nav-tabs" id="language">
								<?php foreach ($languages as $language) { ?>
									<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">

<?php if($at_status){ ?>
    <?php if($language['language_id'] != $at_source){ ?>
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <a class="btn btn-default" onclick="$('.btn-translate<?php echo $language['language_id']; ?>').trigger('click')"><?php echo $btn_translate_to; ?>&nbsp;<?php echo $language['name']; ?></a>&nbsp;
            <a class="btn btn-default" onclick="$('.btn-copy<?php echo $language['language_id']; ?>').trigger('click')"><?php echo $btn_copy_to; ?>&nbsp;<?php echo $language['name']; ?></a>&nbsp;
            <a class="btn btn-default" onclick="$('input[name*=\'[<?php echo $language['language_id']; ?>][meta_title]\']').val($('input[name*=\'[<?php echo $language['language_id']; ?>][name]\'],input[name*=\'[<?php echo $language['language_id']; ?>][title]\']').val());"><?php echo $btn_copy_name; ?></a>
        </div>
    </div>
    <?php } else { ?>
    <div class="form-group">
        <label class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <a class="btn btn-default" onclick="$('a[class^=\'btn-translate\']').trigger('click')"><?php echo $btn_translate_all; ?></a>&nbsp;
            <a class="btn btn-default" onclick="$('a[class^=\'btn-copy\']').trigger('click')"><?php echo $btn_copy_all; ?></a>&nbsp;
            <a class="btn btn-default" onclick="$('input[name*=\'[<?php echo $language['language_id']; ?>][meta_title]\']').val($('input[name*=\'[<?php echo $language['language_id']; ?>][name]\'],input[name*=\'[<?php echo $language['language_id']; ?>][title]\']').val());"><?php echo $btn_copy_name; ?></a>
        </div>
    </div>
    <?php } ?>
<?php } ?>
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_name[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-alternate_name<?php echo $language['language_id']; ?>">Синонимы, или альтернативные названия</label>
											<div class="col-sm-10">
												<textarea rows="20" name="category_description[<?php echo $language['language_id']; ?>][alternate_name]" placeholder="<?php echo $entry_description; ?>" id="input-alternate_name<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['alternate_name'] : ''; ?></textarea>
												<span class="help"><i class="fa fa-info-circle"></i> каждое с новой строки</span>
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control summernote"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','category_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','category_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">							
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-taxonomy_id">Google/FB Merchant:</label>
									<div class="col-sm-10">
										<select name="taxonomy_id" id="input-taxonomy_id" class="form-control">
											<option value="0" <?php if (!$taxonomy_id) { ?>selected="selected"<? } ?>><?php echo $text_none; ?></option>
											<?php foreach ($google_merchant_category as $merchant_category) { ?>
												<?php if ($taxonomy_id == $merchant_category['taxonomy_id']) { ?>
													<option value="<?php echo $merchant_category['taxonomy_id']; ?>" selected="selected"><?php echo $merchant_category['name']; ?></option>
												<?php } else { ?>
													<option value="<?php echo $merchant_category['taxonomy_id']; ?>"><?php echo $merchant_category['name']; ?></option>
												<?php } ?>
											<?php } ?>											
										</select>
									</div>
								</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-addon-discount">Процент скидки "вместе"</label>
								<div class="col-sm-2">
									
									<div class="input-group">
										<input type="number" step="1" name="addon_discount" value="<?php echo $addon_discount; ?>" placeholder="5" class="form-control" size="2" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">%</button>
										</span>
									</div>
									
									
								</div>
								<div class="col-sm-8">
									<span class="help">
										<i class="fa fa-info-circle"></i> скидка дается на товары этой категории, при условии, что они положены в корзину из блока "с этим покупают" и их цена не выше, чем цена основного товара
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-rozetka_overprice">Наценка для Rozetka</label>
								<div class="col-sm-2">
									
									<div class="input-group">
										<input type="number" step=".1" name="rozetka_overprice" value="<?php echo $rozetka_overprice; ?>" placeholder="5" class="form-control" size="2" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">%</button>
										</span>
									</div>
									
									
								</div>
								<div class="col-sm-8">
									<span class="help">
										<i class="fa fa-info-circle"></i> наценка для маркетплейса розетка, только для товаров, у которых эта категория является основной
									</span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-afp-discount">Процент скидки "за отзыв"</label>
								<div class="col-sm-2">
									
									<div class="input-group">
										<input type="text" name="afp_discount" value="<?php echo $afp_discount; ?>" placeholder="5" class="form-control" size="2" />
										<span class="input-group-btn">
											<button type="button" class="btn btn-default">%</button>
										</span>
									</div>
									
									
								</div>
								<div class="col-sm-8">
									<span class="help">
										<i class="fa fa-info-circle"></i> скидка, которая будет применена с промокодом, у которого "вид скидки" равен коду <code>afp_dicsount</code>
									</span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-free_delivery"><span class="bg-warning" data-toggle="tooltip" title="">Бесплатная доставка</span></label>
								<div class="col-sm-2">
									<select name="free_delivery" id="input-free_delivery" class="form-control">
										<?php if ($free_delivery) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-8">
									<span class="help">
										<i class="fa fa-info-circle"></i> бесплатная доставка при наличии в корзине хотя бы одного товара из этой категории и сумме, которая удовлетворяет условиям
									</span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-hide_manufacturer"><span class="bg-success" data-toggle="tooltip" title="">Спрятать бренд</span></label>
								<div class="col-sm-2">
									<select name="hide_manufacturer" id="input-hide_manufacturer" class="form-control">
										<?php if ($hide_manufacturer) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-8">
									<span class="help">
										<i class="fa fa-info-circle"></i> у товаров из этой категории будет спрятан бренд
									</span>
								</div>
							</div>
							
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-rozetka"><span class="bg-success" data-toggle="tooltip" title="Rozetka YML">Розетка</span></label>
								<div class="col-sm-10">
									<select name="rozetka" id="input-rozetka" class="form-control">
										<?php if ($rozetka) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-prom"><span class="bg-info" data-toggle="tooltip" title="Prom YML">Prom.ua</span></label>
								<div class="col-sm-10">
									<select name="prom" id="input-prom" class="form-control">
										<?php if ($prom) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-hotline"><span class="bg-danger" data-toggle="tooltip" title="Hotline YML">Hotline</span></label>
								<div class="col-sm-10">
									<select name="hotline" id="input-hotline" class="form-control">
										<?php if ($hotline) { ?>
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
								<label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
								<div class="col-sm-10">
									<select name="parent_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach ($categories as $category) { ?>
											<?php if ($category['category_id'] == $parent_id) { ?>
												<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
									<div id="category-filter" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($category_filters as $category_filter) { ?>
											<div id="category-filter<?php echo $category_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category_filter['name']; ?>
												<input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category">Исключать категории без фильтра</label>
								<div class="col-sm-10">
									<span class="help text-danger"><i class="fa fa-exclamation-circle"></i> В этой категории без фильтра будут исключаться товары, которые находятся так же в выбранных категориях</span>
									<div class="well well-sm" style="min-height: 150px;max-height: 400px;overflow: auto;">
										<table class="table table-striped">
											<?php foreach ($all_categories as $category) { ?>
												<tr>
													<td class="checkbox">
														<label>
															<?php if (in_array($category['category_id'], $category_excluded)) { ?>
																<input type="checkbox" name="category_excluded[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
																<?php echo $category['name']; ?>
																<?php } else { ?>
																<input type="checkbox" name="category_excluded[]" value="<?php echo $category['category_id']; ?>" />
																<?php echo $category['name']; ?>
															<?php } ?>
														</label>
													</td>
												</tr>
											<?php } ?>
										</table>
									</div>
								<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category">Категории для подбора аксессуаров</label>
								<div class="col-sm-10">
									<div class="well well-sm" style="min-height: 150px;max-height: 500px;overflow: auto;">
										<table class="table table-striped">
											<?php foreach ($all_categories as $category) { ?>
												<tr>
													<td class="checkbox">
														<label>
															<?php if (in_array($category['category_id'], $category_category)) { ?>
																<input type="checkbox" name="category_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
																<?php echo $category['name']; ?>
																<?php } else { ?>
																<input type="checkbox" name="category_category[]" value="<?php echo $category['category_id']; ?>" />
																<?php echo $category['name']; ?>
															<?php } ?>
														</label>
													</td>
												</tr>
											<?php } ?>
										</table>
									</div>
								<a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<div class="checkbox">
											<label>
												<?php if (in_array(0, $category_store)) { ?>
													<input type="checkbox" name="category_store[]" value="0" checked="checked" />
													<?php echo $text_default; ?>
													<?php } else { ?>
													<input type="checkbox" name="category_store[]" value="0" />
													<?php echo $text_default; ?>
												<?php } ?>
											</label>
										</div>
										<?php foreach ($stores as $store) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($store['store_id'], $category_store)) { ?>
														<input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
														<?php echo $store['name']; ?>
														<?php } else { ?>
														<input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
														<?php echo $store['name']; ?>
													<?php } ?>
												</label>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
									<?php if ($error_keyword) { ?>
										<div class="text-danger"><?php echo $error_keyword; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label">SVG для меню</label>								
								<div class="col-sm-8">
									<textarea rows="5" name="top_svg" placeholder="Код SVG plain-text" id="input-top_svg"  class="form-control"><?php echo $top_svg; ?></textarea>
								</div>
								<div class="col-sm-2"><?php echo html_entity_decode($top_svg, ENT_QUOTES, 'UTF-8')  ?></div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
								<div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
									<input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-efs">Исключить из "скидочных"</label>
								<div class="col-sm-10">
									<div class="checkbox">
										<label>
											<?php if ($efs) { ?>
												<input type="checkbox" name="efs" value="1" checked="checked" id="input-efs" />
												<?php } else { ?>
												<input type="checkbox" name="efs" value="1" id="input-efs" />
											<?php } ?>
										&nbsp; </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
								<div class="col-sm-10">
									<div class="checkbox">
										<label>
											<?php if ($top) { ?>
												<input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
												<?php } else { ?>
												<input type="checkbox" name="top" value="1" id="input-top" />
											<?php } ?>
										&nbsp; </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-column"><span data-toggle="tooltip" title="<?php echo $help_column; ?>"><?php echo $entry_column; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control">
										<?php if ($status) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>


		<div class="tab-pane" id="tab-epicentr">

					<div class="form-group">
		                    <label class="col-sm-2 control-label" for="input-epicentr">Название категории</label>
		                    <div class="col-sm-10">
		                    <input type="text" name="epicentr_name" placeholder="Если это поле пустое - выгружается главное название категории" id="input-epicentr_name"  value="<? echo $epicentr_name; ?>" class="form-control" autocomplete="off">
							</div>
		            </div>

		          <div class="form-group">
		            <label class="col-sm-2 control-label" for="input-sort-order">Страна производитель товара</label>
		            <div class="col-sm-10">
		              <input type="text" name="epicentr_country" value="<?php echo $epicentr_country; ?>" placeholder="Например: Украина" id="input-epicentr_country" class="form-control" />
		            </div>
		          </div>

		          <div class="form-group">
		                    <label class="col-sm-2 control-label" for="input-epicentr">Коэффициент наценки</label>
		                    <div class="col-sm-10">
		                    <input type="text" name="epicentr_coeff" placeholder="Если это поле пустое, то берется значение из настроек модуля" id="input-epicentr_coeff"  value="<? echo $epicentr_coeff; ?>" class="form-control" autocomplete="off">
							</div>
		          </div>
            
		            
		          <div class="form-group">
		            <div style="text-align:center;" class="col-sm-12">
		             Epicentr XML v1.2.2 By Alex Soloviov :: <a href='https://shop.ionline.su'>shop.ionline.su</a>
		            </div>
		          </div>	        
	          

		</div>

			
						<div class="tab-pane" id="tab-design">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_store; ?></td>
											<td class="text-left"><?php echo $entry_layout; ?></td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left"><?php echo $text_default; ?></td>
											<td class="text-left"><select name="category_layout[0]" class="form-control">
												<option value=""></option>
												<?php foreach ($layouts as $layout) { ?>
													<?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
														<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
														<?php } else { ?>
														<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<?php foreach ($stores as $store) { ?>
											<tr>
												<td class="text-left"><?php echo $store['name']; ?></td>
												<td class="text-left"><select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control">
													<option value=""></option>
													<?php foreach ($layouts as $layout) { ?>
														<?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
															<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>

				<div class="tab-pane" id="tab-faq">
				<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $faq_name; ?></label>
				<div class="col-sm-10">
				<?php foreach($languages as $language) { ?>
                    <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="category_description[<?php echo $language['language_id']; ?>][faq_name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['faq_name'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" style="width:50%;display:inline-block;"/></div><br />
				<?php } ?>
				</div>
				</div>  
				<div class="table-responsive">
                <table id="faq" class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
				<td class="text-center"><?php echo $column_question; ?></td>
				<td class="text-center"><?php echo $column_faq; ?></td>
				<td class="text-center" style="width:10%"><?php echo $column_icon; ?></td>
				<td class="text-center" style="width:10%"><?php echo $column_sort_order; ?></td>
				<td class="text-center" style="width:10%"></td>
				</tr>
				</thead>
				<tbody>
				<?php $faq_row = 0; ?>
				<?php foreach ($category_faq as $category_faq) { ?>
                    <tr id="faq-row<?php echo $faq_row; ?>">
					<td class="text-center">
					<?php foreach($languages as $language) { ?>
						<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="category_faq[<?php echo $faq_row; ?>][question][<?php echo $language['language_id']; ?>]" value="<?php if (isset($category_faq['question'][$language['language_id']])) echo $category_faq['question'][$language['language_id']]; ?>" class="form-control" style="display:inline-block;width:80%;" /></div><br />
					<?php } ?>
					</td>
					<td class="text-center">
					<?php foreach($languages as $language) { ?>
						<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><textarea rows="3" name="category_faq[<?php echo $faq_row; ?>][faq][<?php echo $language['language_id']; ?>]" class="form-control summernote" style="display:inline-block;width:80%;"><?php if (isset($category_faq['faq'][$language['language_id']])) echo $category_faq['faq'][$language['language_id']]; ?></textarea></div><br />
					<?php } ?> 
					</td>
					<td class="text-center"><input type="text" name="category_faq[<?php echo $faq_row; ?>][icon]" value="<?php echo $category_faq['icon']; ?>" class="form-control" /></td>
					<td class="text-center"><input type="text" name="category_faq[<?php echo $faq_row; ?>][sort_order]" value="<?php echo $category_faq['sort_order']; ?>" class="form-control" /></td>
					<td class="text-center"><button type="button" onclick="$('#faq-row<?php echo $faq_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr> 
                    <?php $faq_row++; ?>
				<?php } ?>
				</tbody>
				<tfoot>
				<tr>
				<td colspan="4"></td>
				<td class="text-center"><button type="button" onclick="addFaq();" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
				</tr>
				</tfoot>
                </table>
				</div>
				</div>
			
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript"><!--
		<?php if ($ckeditor) { ?>
			<?php foreach ($languages as $language) { ?>
				ckeditorInit('input-description<?php echo $language['language_id']; ?>', getURLVar('token'));
			<?php } ?>
		<?php } ?>
	//--></script>
	<script type="text/javascript"><!--
		$('input[name=\'path\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						json.unshift({
							category_id: 0,
							name: '<?php echo $text_none; ?>'
						});
						
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['category_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'path\']').val(item['label']);
				$('input[name=\'parent_id\']').val(item['value']);
			}
		});
	//--></script> 
	<script type="text/javascript"><!--
		$('input[name=\'filter\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['filter_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'filter\']').val('');
				
				$('#category-filter' + item['value']).remove();
				
				$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
			}
		});
		
		$('#category-filter').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
	//--></script> 
	<script type="text/javascript"><!--
		$('#language a:first').tab('show');
	//--></script></div>

				<script type="text/javascript"><!--
				var faq_row = <?php echo $faq_row; ?>;
				function addFaq() {
				html  = '<tr id="faq-row' + faq_row + '">';
				html += '  <td class="text-center">';
				<?php foreach($languages as $language) { ?>
					html += ' <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="category_faq[' + faq_row + '][question][<?php echo $language['language_id']; ?>]" value="" class="form-control" style="display:inline-block;width:80%;" /></div><br />';
				<?php } ?>
				html += '</td>';
				html += '  <td class="text-center">';
				<?php foreach($languages as $language) { ?>
					html += ' <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><textarea rows="3" name="category_faq[' + faq_row + '][faq][<?php echo $language['language_id']; ?>]" value="" class="form-control" style="display:inline-block;width:80%;"></textarea></div><br />';
				<?php } ?>
				html += '</td>';
				html += '  <td class="text-center" style="width:10%"><input type="text" name="category_faq[' + faq_row + '][icon]" value=""  class="form-control" /></td>';
				html += '  <td class="text-center" style="width:10%"><input type="text" name="category_faq[' + faq_row + '][sort_order]" value=""  class="form-control" /></td>';
				html += '  <td class="text-center"><button type="button" onclick="$(\'#faq-row' + faq_row + '\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
				html += '</tr>';
				
				$('#faq tbody').append(html);
				
				faq_row++;
				}
				//--></script>
			

<script type="text/javascript"><!--
//quicksave
$("#qsave").on("click",function(){
for(var zz=$(".note-editor").length,i=0;zz>i;i++){var yy=$(".note-editor").eq(i).parent().children("textarea").attr("id");if("function"==typeof $().code)var content=$("#"+yy).code();else var content=$("#"+yy).summernote("code");$("#"+yy).html(content)}
$.ajax({type:"post",data:$("form").serialize(),url:"index.php?route=catalog/category/qsave&token=<?php echo $token; ?>&category_id=<?php echo $pidqs; ?>",dataType:"json",beforeSend:function(){$("#qsave").prop("disabled",!0)},complete:function(){$("#qsave").prop("disabled",!1)},success:function(e){if($(".alert").remove(),$(".text-danger").remove(),$(".form-group").removeClass("has-error"),e.error){if(html='<div class="alert alert-danger">',html+=" "+e.error.warning+' <button type="button" class="close" data-dismiss="alert">&times;</button></br>',e.error.keyword&&($("#input-keyword").after('<div class="text-danger">'+e.error.keyword+"</div>"),html+='</br><i class="fa fa-exclamation-circle"></i> '+e.error.keyword),e.error.name){var r="";for(i in e.error.name){var a=$("#input-name"+i);$(a).parent().hasClass("input-group")?($(a).parent().after('<div class="text-danger">'+e.error.name[i]+"</div>"),r='</br><i class="fa fa-exclamation-circle"></i> '+e.error.name[i]):($(a).after('<div class="text-danger">'+e.error.name[i]+"</div>"),r='</br><i class="fa fa-exclamation-circle"></i> '+e.error.name[i])}html+=r}if(e.error.meta_title){var r="";for(i in e.error.meta_title){var a=$("#input-meta-title"+i);$(a).parent().hasClass("input-group")?($(a).parent().after('<div class="text-danger">'+e.error.meta_title[i]+"</div>"),r='</br><i class="fa fa-exclamation-circle"></i> '+e.error.meta_title[i]):($(a).after('<div class="text-danger">'+e.error.meta_title[i]+"</div>"),r='</br><i class="fa fa-exclamation-circle"></i> '+e.error.meta_title[i])}html+=r}$(".text-danger").parentsUntil(".form-group").parent().addClass("has-error"),html+=" </div>",$("#content > .container-fluid").prepend(html)}e.success&&$("#content > .container-fluid").prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+e.success+'  <button type="button" class="close" data-dismiss="alert">&times;</button></div>')},error:function(e,r,a){alert(a+"\r\n"+e.statusText+"\r\n"+e.responseText)}})});
//quicksave end
//--></script>
			
	<?php echo $footer; ?>
		