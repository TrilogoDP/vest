<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<!----//*** mf begin ***-->
				<a class="btn btn-default update1c" title="Update 1c"><i class="fa fa-refresh"></i></a>
				<script type="text/javascript">
					$(".update1c").click(function(){
						if(confirm("Отримати дані з 1с?") == true){
							$.ajax({
								url: 'index.php?route=catalog/product/update1c&token=<?php echo $token; ?>&product_id=' +  <?php echo $_GET['product_id'] ?>,
								dataType: 'text',
								success: function(data) {
									
									location.reload();
								},
								complete: function (data) {},					
							});				
						}
					});
				</script>
				<!----//*** mf end ***-->
				<?php if (isset($product_page)) { ?><a class="btn btn-info" href="<?php echo $product_page; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_view; ?>"><i class="fa fa-eye"></i></a><?php } ?>

<!-- quicksave -->
	  <?php if (isset($pidqs) && $pidqs) { ?>
	  <button id="qsave" style="margin: 0 10px;" data-toggle="tooltip" title="Quick Save" class="btn btn-warning"><i class="fa fa-save"></i></button>
	  <?php } ?>
<!-- quicksave end -->
			
				<button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">

<?php if($at_status == 2) { ?>
<?php if(isset($microsoft_token_error)){ $m_token_error = $microsoft_token_error; } else { $m_token_error = ''; } ?>
<input type="hidden" name="microsoft_token" id="microsoft_token" data-error="<?php echo $m_token_error; ?>" value="<?php echo $m_token; ?>">
<script type="text/javascript">$(document).ready(function(){setInterval(updateMicrosoftToken,54e4)});</script>
<?php } ?>
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>

        <?php if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) { ?>
        <li><a href="#tab-extra_tabs" data-toggle="tab"><?php echo $tab_extra_tabs; ?></a></li>
        <?php } ?>
      
						<?php if ($google_merchant_center_status) {?><li><a href="#tab-taxonomy" data-toggle="tab">GMC</a></li><?php } ?>
						<li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
						<li><a href="#tab-attribute" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
						<li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
						<li><a href="#tab-discount" data-toggle="tab"><?php echo $tab_discount; ?></a></li>
						<li><a href="#tab-special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
						<li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
<li><a href="#tab-epicentr" data-toggle="tab"><i style="color:#0f62c1;">Epicentr XML</i></a></li>
						<?php if ($pricehistory) { ?>
							<li><a href="#tab-pricehistory" data-toggle="tab">Динамика цен</a></li>
						<? } ?>
						
						<?php if (!empty($_GET['product_id'])) { ?>
							<li><a href="#tab-searchdebug" data-toggle="tab">Отладка поиска</a></li>
						<?php } ?>
						<li class="hidden"><a href="#tab-recurring" data-toggle="tab">[РП]</a></li>
						<li class="hidden"><a href="#tab-reward" data-toggle="tab">[БНС]</a></li>
						<li class="hidden"><a href="#tab-design" data-toggle="tab">[ДИЗ]</a></li>
						<?php if (false && $salestock_info) { ?>
							<li><a href="#tab-salestock" data-toggle="tab">Продажи, история</a></li>
						<? } ?>
						
						
					</ul>
					
					<div class="tab-content">	
						<?php if (!empty($_GET['product_id'])) { ?>
							<div class="tab-pane" id="tab-searchdebug">
								<div class="panel panel-default">
									<div class="panel-heading">Товар в индексе</div>
									<div class="panel-body" id="searchdebug-results"></div>
									
									<script>
										$(document).ready(function(){
											$('#searchdebug-results').load('https://vest.in.ua/index.php?route=hobotix/search/test&id=<?php echo $_GET['product_id']; ?>');
										});
									</script>
								</div>
							</div>	
						<? } ?>
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
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_name[$language['language_id']])) { ?>
												<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
											<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control ckeditor-editor"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-fake_description<?php echo $language['language_id']; ?>">Описание для маркетплейсов</label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="product_description[<?php echo $language['language_id']; ?>][fake_description]" placeholder="<?php echo $entry_description; ?>" id="input-fake_description<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control ckeditor-editor"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['fake_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-h1<?php echo $language['language_id']; ?>"><?php echo $entry_meta_h1; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_h1]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_h1'] : ''; ?>" placeholder="<?php echo $entry_meta_h1; ?>" id="input-meta-h1<?php echo $language['language_id']; ?>" class="form-control" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
											<div class="col-sm-10">

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
												<input type="text" name="product_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						
						<?php if ($google_merchant_center_status) {?>
							<div class="tab-pane" id="tab-taxonomy">
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-dnmerchant">Не выгружать</label>
									<div class="col-sm-10">
										<select name="dnmerchant" id="input-dnmerchant" class="form-control">
											<?php if ($dnmerchant) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
										<span class="help">при установке "включено" товар не будет выгружаться в GMC ни при каких условиях</span>
									</div>
								</div>
								
								
								<div class="form-group">
									<label class="col-sm-2 control-google-merchant-gender" for="input-google-merchant-gender"><?php echo $entry_google_merchant_gender; ?></label>
									<div class="col-sm-10">																				
										<select name="google_merchant_product_gender"  style="max-width: 90%;" id="input-google-merchant-product-gender" class="form-control">
											<?php if ($google_merchant_product['gender'] == '') { ?>
												<option value="" selected="selected">Не задано</option>
											<?php } else { ?>
												<option value="">Не задано</option>
											<?php } ?>
											
											<?php foreach ($google_gender as $merchant_gender) {
												if ($merchant_gender == $google_merchant_product['gender']) { ?>
												<option value="<?php echo $merchant_gender; ?>" selected="selected"><?php echo $merchant_gender; ?></option>
												<?php } else { ?>
												<option value="<?php echo $merchant_gender; ?>"><?php echo $merchant_gender; ?></option>
											<?php } ?>
											<?php } ?>											
										</select>
										
										
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-google-merchant-age-group" for="input-google-merchant-age-group"><?php echo $entry_google_merchant_age_group; ?></label>
									<div class="col-sm-10">																				
										<select name="google_merchant_product_age_group" id="input-google-merchant-product-age-group" class="form-control">

											<?php if ($google_merchant_product['age_group'] == '') { ?>
												<option value="" selected="selected">Не задано</option>
											<?php } else { ?>
												<option value="">Не задано</option>
											<?php } ?>

											<?php foreach ($google_age_group as $merchant_age_group) {
												if ($merchant_age_group==$google_merchant_product['age_group']) { ?>
													<option value="<?php echo $merchant_age_group; ?>" selected="selected"><?php echo $merchant_age_group; ?></option>
												<?php } else { ?>
													<option value="<?php echo $merchant_age_group; ?>"><?php echo $merchant_age_group; ?></option>
											<?php } ?>
											<?php } ?>
										</select>																				
									</div>
								</div>
								<?php if ($google_merchant_center_attribute=="-1" || $google_merchant_center_attribute=="") {?>
									<div class="form-group">
										<label class="col-sm-2 control-google-merchant-color" for="input-google-merchant-color"><span data-toggle="tooltip" data-html="true" title="<?php echo htmlspecialchars($help_google_merchant_color); ?>"><?php echo $entry_google_merchant_color; ?></span></label>
										<div class="col-sm-10">
											
											<input type="text" name="google_merchant_product_color" value="<?php echo isset($google_merchant_product['color']) ? $google_merchant_product['color'] : ''; ?>" placeholder="<?php echo $entry_google_merchant_color; ?>" id="input-google-merchant-color" class="form-control" />
											
										</div>
									</div>
								<?php } ?>
								
							</div>
							
						<?php } ?>
						
						
						<div class="tab-pane" id="tab-data">

        <?php if (isset($oct_product_stickers_data['status']) && $oct_product_stickers_data['status']) { ?>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $enter_oct_product_stickers; ?></label>
          <div class="col-sm-10">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
              <?php foreach ($oct_product_stickers as $product_sticker) { ?>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="oct_product_stickers[<?php echo $product_sticker['product_sticker_id']; ?>]" value="<?php echo $product_sticker['product_sticker_id']; ?>" <?php echo (isset($product_sticker_id[$product_sticker['product_sticker_id']]) && !empty($product_sticker_id[$product_sticker['product_sticker_id']])) ? 'checked' : ''; ?> 
                  /> <?php echo $product_sticker['title']; ?>
                </label>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
								<div class="col-sm-10">
									<input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />

									<span class="help text-danger"><i class="fa fa-exclamation-circle"></i> Модель товару. Для товарів від постачальників, це поле не чіпаємо, якщо товар додано від постачальника!</span>
									<?php if ($error_model) { ?>
										<div class="text-danger"><?php echo $error_model; ?></div>
									<?php } ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-model_marketplace">Модель для маркетплейсов</label>
								<div class="col-sm-10">
									<input type="text" name="model_marketplace" value="<?php echo $model_marketplace; ?>" placeholder="Модель для маркетплейсов" id="input-model_marketplace" class="form-control" />
									<span class="help text-info"><i class="fa fa-info-circle"></i> Це поле передаємо як модель на маркетплейси: hotline</span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_sku; ?>"><?php echo $entry_sku; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sku">Удаленные SKU</label>
								<?php for ($i=0; $i<=4; $i++) { ?>
									<div class="col-sm-2">
										
										<input type="text" name="product_sku_deleted[]" value="<?php if (!empty($product_sku_deleted[$i])) { echo $product_sku_deleted[$i]['sku_deleted']; } ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku-<?php echo $i; ?>" class="form-control" />
										
									</div>
								<?php } ?>
								<div class="col-sm-10 pull-right text-danger"><i class="fa fa-exclamation-circle"></i> внимание, две первые цифры артикула - код поставщика, при добавлении в удаленные учитывайте это</div>
								<div class="col-sm-10 pull-right text-info"><i class="fa fa-info-circle"></i> товары с этими артикулами никогда не будут загружаться, или обновляться из фидов поставщика</div>
							</div>
							
							<div class="form-group<?php echo ($hide_upc == true)? ' hide':''; ?>">
								<label class="col-sm-2 control-label" for="input-upc"><span data-toggle="tooltip" title="<?php echo $help_upc; ?>"><?php echo $entry_upc; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" class="form-control" />
								</div>
							</div>
							<div class="form-group<?php echo ($hide_ean == true)? ' hide':''; ?>">
								<label class="col-sm-2 control-label" for="input-ean"><span data-toggle="tooltip" title="<?php echo $help_ean; ?>"><?php echo $entry_ean; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="ean" value="<?php echo $ean; ?>" placeholder="<?php echo $entry_ean; ?>" id="input-ean" class="form-control" />
								</div>
							</div>
							<div class="form-group<?php echo ($hide_jan == true)? ' hide':''; ?>">
								<label class="col-sm-2 control-label" for="input-jan"><span data-toggle="tooltip" title="<?php echo $help_jan; ?>"><?php echo $entry_jan; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="jan" value="<?php echo $jan; ?>" placeholder="<?php echo $entry_jan; ?>" id="input-jan" class="form-control" />
								</div>
							</div>
							<div class="form-group<?php echo ($hide_isbn == true)? ' hide':''; ?>">
								<label class="col-sm-2 control-label" for="input-isbn"><span data-toggle="tooltip" title="<?php echo $help_isbn; ?>"><?php echo $entry_isbn; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="isbn" value="<?php echo $isbn; ?>" placeholder="<?php echo $entry_isbn; ?>" id="input-isbn" class="form-control" />
								</div>
							</div>
							<div class="form-group<?php echo ($hide_mpn == true)? ' hide':''; ?>">
								<label class="col-sm-2 control-label" for="input-mpn"><span data-toggle="tooltip" title="<?php echo $help_mpn; ?>"><?php echo $entry_mpn; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" class="form-control" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-youtube_single">Youtube</label>
								<div class="col-sm-10">
									https://www.youtube.com/watch?v=<input type="text" name="youtube_single" value="<?php echo $youtube_single; ?>" placeholder="IcTxPneqvok" id="input-youtube_single" class="form-control" />
									<img src="/admin75s47fd4777/view/image/video_in_product.jpg">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-location"><?php echo $entry_location; ?></label>
								<div class="col-sm-10">
									<input type="text" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-bestseller">Бестселлер</label>
								<div class="col-sm-4">
									Только в основной категории
									<select name="bestseller" id="input-bestseller" class="form-control">
										<?php if ($bestseller) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-3">
									90дн: <input type="number" name="orders_90" value="<?php echo $orders_90; ?>" placeholder="0" id="input-orders_90" class="form-control" />
								</div>
								<div class="col-sm-3">
									180дн: <input type="number" name="orders_180" value="<?php echo $orders_180; ?>" placeholder="0" id="input-orders_180" class="form-control" />
								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
								<div class="col-sm-5">
									<input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
								</div>
								<div class="col-sm-5">
									<div class="input-group date">
										<input type="text" name="price_date" value="<?php echo $price_date; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-price-date" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
									<span class="help">дата задания "нашей цены"</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-dnup">Только наша цена</label>
								<div class="col-sm-5">
									<select name="dnup" id="input-dnup" class="form-control">
										<?php if ($dnup) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
									<span class="help">при включении цена не будет обновляться от поставщиков, только из 1С</span>
								</div>
								<div class="col-sm-5">
									<div class="input-group date">
										<input type="text" name="dnup_date" value="<?php echo $dnup_date; ?>" placeholder="" data-date-format="YYYY-MM-DD" id="input-dnup-date" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
									<span class="help">дата задания флага "только наша цена"</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
								<div class="col-sm-10">
									<select name="tax_class_id" id="input-tax-class" class="form-control">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
											<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
												<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								
								<label class="col-sm-2 control-label" for="input-quantity">Наявність</label>

								<div class="col-sm-3">			
									<span class="label label-info">Загальна кількість на фронті</span>						
									<input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
									<span class="help">дорівнює сумі складу та поставщика</span>							
								</div>

								<div class="col-sm-3">			
									<span class="label label-success">Наявність на своєму складі</span>						
									<input type="text" name="stock" value="<?php echo $stock; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-stock" class="form-control" />
									<span class="help">тільки наш склад</span>						
								</div>

								<div class="col-sm-3">			
									<span class="label label-warning">Наявність у поставщика</span>						
									<input type="text" name="supplier" value="<?php echo $supplier; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-supplier" class="form-control" />
									<span class="help">тільки поставщик</span>						
								</div>



							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-subtract"><?php echo $entry_subtract; ?></label>
								<div class="col-sm-10">
									<select name="subtract" id="input-subtract" class="form-control">
										<?php if ($subtract) { ?>
											<option value="1" selected="selected"><?php echo $text_yes; ?></option>
											<option value="0"><?php echo $text_no; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_yes; ?></option>
											<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
								<div class="col-sm-10">
									<select name="stock_status_id" id="input-stock-status" class="form-control">
										<?php foreach ($stock_statuses as $stock_status) { ?>
											<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_shipping; ?></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($shipping) { ?>
											<input type="radio" name="shipping" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="shipping" value="1" />
											<?php echo $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$shipping) { ?>
											<input type="radio" name="shipping" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="shipping" value="0" />
											<?php echo $text_no; ?>
										<?php } ?>
									</label>
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
								<label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-length"><?php echo $entry_dimension; ?></label>
								<div class="col-sm-10">
									<div class="row">
										<div class="col-sm-4">
											<input type="text" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
										</div>
										<div class="col-sm-4">
											<input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
										</div>
										<div class="col-sm-4">
											<input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-length-class"><?php echo $entry_length_class; ?></label>
								<div class="col-sm-10">
									<select name="length_class_id" id="input-length-class" class="form-control">
										<?php foreach ($length_classes as $length_class) { ?>
											<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-weight"><?php echo $entry_weight; ?></label>
								<div class="col-sm-10">
									<input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
								<div class="col-sm-10">
									<select name="weight_class_id" id="input-weight-class" class="form-control">
										<?php foreach ($weight_classes as $weight_class) { ?>
											<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-archive">Архивный товар</label>
								<div class="col-sm-5">
									<select name="archive" id="input-archive" class="form-control">
										<?php if ($archive) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-sm-5">
									<input type="hidden" name="archive_auto" value="<?php echo $archive_auto; ?>" id="input-archive_auto" class="form-control" />

									<?php if ($archive) { ?>
										<?php if ($archive_auto) { ?>
											<span class="help text-danger">Признак установлен автоматически</span>
										<?php } else { ?>
											<span class="help text-info">Признак установлен вручную</span>
										<?php } ?>
									<?php } ?>
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
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
							<!---//*** mf begin --->
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_custom_yml_mf; ?></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($custom_yml_mf) { ?>
											<input type="radio" name="custom_yml_mf" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="custom_yml_mf" value="1" />
											<?php echo $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$custom_yml_mf) { ?>
											<input type="radio" name="custom_yml_mf" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="custom_yml_mf" value="0" />
											<?php echo $text_no; ?>
										<?php } ?>
									</label>				
								</div>
							</div>			  
							<!---//*** mf end --->
						</div>
						<div class="tab-pane" id="tab-links">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-manufacturer"><?php echo $entry_manufacturer; ?></label>
								<div class="col-sm-10">
									<select id="input-manufacturer" name="manufacturer_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach ($manufacturers as $manufacturer) { ?>
											<?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
												<option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category"><?php echo $entry_main_category; ?></label>
								<div class="col-sm-10">
									<select id="main_category_id" name="main_category_id" class="form-control">
										<option value="0" selected="selected"><?php echo $text_none; ?></option>
										<?php foreach($categories as $category) { ?>
											<?php if($category['category_id'] == $main_category_id) { ?>
												<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="min-height: 150px;max-height: 500px;overflow: auto;">
										<table class="table table-striped">
											<?php foreach ($categories as $category) { ?>
												<tr>
													<td class="checkbox">
														<label>
															<?php if (in_array($category['category_id'], $product_category)) { ?>
																<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
																<?php echo $category['name']; ?>
																<?php } else { ?>
																<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
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
								<label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="<?php echo $help_filter; ?>"><?php echo $entry_filter; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
									<div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_filters as $product_filter) { ?>
											<div id="product-filter<?php echo $product_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_filter['name']; ?>
												<input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<div class="checkbox">
											<label>
												<?php if (in_array(0, $product_store)) { ?>
													<input type="checkbox" name="product_store[]" value="0" checked="checked" />
													<?php echo $text_default; ?>
													<?php } else { ?>
													<input type="checkbox" name="product_store[]" value="0" />
													<?php echo $text_default; ?>
												<?php } ?>
											</label>
										</div>
										<?php foreach ($stores as $store) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($store['store_id'], $product_store)) { ?>
														<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
														<?php echo $store['name']; ?>
														<?php } else { ?>
														<input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
														<?php echo $store['name']; ?>
													<?php } ?>
												</label>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-download"><span data-toggle="tooltip" title="<?php echo $help_download; ?>"><?php echo $entry_download; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" class="form-control" />
									<div id="product-download" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_downloads as $product_download) { ?>
											<div id="product-download<?php echo $product_download['download_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_download['name']; ?>
												<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
									<div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_relateds as $product_related) { ?>
											<div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
												<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-attribute">

<?php if($at_status){ ?>
    <div class="form-group">
        <div class="col-sm-12">
            <a class="btn btn-default" onclick="$('a[class^=\'btn-attr-translate\']').trigger('click')"><?php echo $btn_translate_all; ?></a>&nbsp;
            <a class="btn btn-default" onclick="$('a[class^=\'btn-attr-copy\']').trigger('click')"><?php echo $btn_copy_all; ?></a>
        </div>
    </div>
<?php } ?>
							<div class="table-responsive">
								<table id="attribute" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left col-xs-3"><?php echo $entry_attribute; ?></td>
											<td class="text-left col-xs-8"><?php echo $entry_text; ?></td>
											<td class="col-xs-1"></td>
										</tr>
									</thead>
									<tbody>
										<?php $attribute_row = 0; ?>
										<?php foreach ($product_attributes as $product_attribute) { ?>
											<tr id="attribute-row<?php echo $attribute_row; ?>">
												<td class="text-left" style="width: 40%;"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
												<input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" />
												<?php if (!empty($product_attribute['group'])) { ?>
													<span class="label label-info"><?php echo $product_attribute['group']; ?></span>
												<?php } else { ?>
													<?php if (!$product_attribute['group_id']) { ?>
														<span class="label label-warning">Без группы</span>
													<?php } else { ?>
														<span class="label label-danger">Пустое название группы</span>
														<a href="<?php echo $product_attribute['group_href']?>">редактировать</a>
													<? } ?>
												<?php } ?>
												</td>
												<td class="text-left"><?php foreach ($languages as $language) { ?>
													<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-attr-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $at_source; ?>][text]',1);"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-attr-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $at_source; ?>][text]',1);">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-attr-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $at_source; ?>][text]',1);"><?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
														<textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
														<br /><a onclick="$(this).parent().children('textarea').summernote();">включить редактор</a>
													</div>
												<?php } ?></td>
												<td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $attribute_row++; ?>
										<?php } ?>
										
										
										<tr id="highlight-row">
											<td class="text-left" style="width: 40%;">Product Highlight (Google Merchant)</td>
											
											<td class="text-left">
												<?php foreach ($languages as $language) { ?>
													<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'textarea','product_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','textarea','product_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
														<textarea name="product_description[<?php echo $language['language_id']; ?>][highlight]" placeholder="Каждая особенность с новой строки" id="input-highlight<?php echo $language['language_id']; ?>" data-lang="<?php echo $lang; ?>" class="form-control"  rows="10"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['highlight'] : ''; ?></textarea>
														<br />
														<span class="help">Рекомендуется от 4 до 6, минимум – 2, максимум – 10</span>
													</div>
												<?php } ?>
											</td>
											
											<td class="text-left"></td>
										</tr>
										
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>

        <?php if (isset($oct_product_tabs_data['status']) && $oct_product_tabs_data['status']) { ?>
        <div class="tab-pane" id="tab-extra_tabs">
          <div class="col-sm-2">
            <ul class="nav nav-pills nav-stacked" id="extra_tabs">
              <?php $extra_tab_row = 0; ?>
              <?php foreach ($oct_product_extra_tabs as $oct_product_extra_tab) { ?>
              <li><a href="#tab-extra_tabs<?php echo $extra_tab_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-extra_tabs<?php echo $extra_tab_row; ?>\']').parent().remove(); $('#tab-extra_tabs<?php echo $extra_tab_row; ?>').remove(); $('#extra_tabs a:first').tab('show');"></i> <?php echo $oct_product_extra_tab['title']; ?></a></li>
              <?php $extra_tab_row++; ?>
              <?php } ?>
              <li>
                <input type="text" name="extra_tabs" value="" placeholder="<?php echo $entry_extra_tab; ?>" id="input-extra_tabs" class="form-control" />
              </li>
            </ul>
          </div>
          <div class="col-sm-10">
            <div class="tab-content">
              <?php $extra_tab_row = 0; ?>
              <?php foreach ($oct_product_extra_tabs as $oct_product_extra_tab) { ?>
              <div class="tab-pane" id="tab-extra_tabs<?php echo $extra_tab_row; ?>">
                <input type="hidden" name="oct_product_extra_tab[<?php echo $extra_tab_row; ?>][title]" value="<?php echo $oct_product_extra_tab['title']; ?>" />
                <input type="hidden" name="oct_product_extra_tab[<?php echo $extra_tab_row; ?>][extra_tab_id]" value="<?php echo $oct_product_extra_tab['extra_tab_id']; ?>" />
                <ul class="nav nav-tabs" id="extra_tab_description_div<?php echo $extra_tab_row; ?>">
                  <?php foreach ($languages as $language) { ?>
                  <li><a href="#extra_tab_description<?php echo $extra_tab_row; ?><?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                  <?php } ?>
                </ul>
                <div class="tab-content">
                  <?php foreach ($languages as $language) { ?>
                  <div class="tab-pane" id="extra_tab_description<?php echo $extra_tab_row; ?><?php echo $language['language_id']; ?>">
                    <textarea name="oct_product_extra_tab[<?php echo $extra_tab_row; ?>][oct_product_extra_tab_description][<?php echo $language['language_id']; ?>][text]" placeholder="<?php echo $entry_text; ?>" class="form-control summernote"><?php echo isset($oct_product_extra_tab['oct_product_extra_tab_description'][$language['language_id']]) ? $oct_product_extra_tab['oct_product_extra_tab_description'][$language['language_id']]['text'] : ''; ?></textarea>
                  </div>
                  <?php } ?>
                </div>
              </div>
              <?php $extra_tab_row++; ?>
              <?php } ?>
            </div>
          </div>
        </div>
        <script>
          var extra_tab_row = <?php echo $extra_tab_row; ?>;

          $('input[name=\'extra_tabs\']').autocomplete({
            'source': function(request, response) {
              $.ajax({
                url: 'index.php?route=catalog/oct_product_tabs/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                  response($.map(json, function(item) {
                    return {
                      label: item['title'],
                      value: item['extra_tab_id']
                    }
                  }));
                }
              });
            },
            'select': function(item) {
              html  = '<div class="tab-pane" id="tab-extra_tabs'+extra_tab_row+'">';
                html  += '<input type="hidden" name="oct_product_extra_tab['+extra_tab_row+'][title]" value="'+item['label']+'" />';
                html  += '<input type="hidden" name="oct_product_extra_tab['+extra_tab_row+'][extra_tab_id]" value="'+item['value']+'" />';
                html  += '<ul class="nav nav-tabs" id="extra_tab_description_div'+extra_tab_row+'">';
                  <?php foreach ($languages as $language) { ?>
                  html  += '<li><a href="#extra_tab_description'+extra_tab_row+'<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
                  <?php } ?>
                html  += '</ul>';
                html  += '<div class="tab-content">';
                  <?php foreach ($languages as $language) { ?>
                  html  += '<div class="tab-pane" id="extra_tab_description'+extra_tab_row+'<?php echo $language['language_id']; ?>">';
                    html  += '<textarea name="oct_product_extra_tab['+extra_tab_row+'][oct_product_extra_tab_description][<?php echo $language['language_id']; ?>][text]" placeholder="<?php echo $entry_text; ?>" class="form-control summernote" id="extra_tab_description_textarea'+extra_tab_row+'<?php echo $language['language_id']; ?>"></textarea>';
                  html  += '</div>';
                  <?php } ?>
                html  += '</div>';
              html  += '</div>';

              $('#tab-extra_tabs .col-sm-10 > .tab-content').append(html);

              $('#extra_tabs > li:last-child').before('<li><a href="#tab-extra_tabs' + extra_tab_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-extra_tabs' + extra_tab_row + '\\\']\').parent().remove(); $(\'#tab-extra_tabs' + extra_tab_row + '\').remove(); $(\'#extra_tabs a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');

              $('#extra_tabs a[href=\'#tab-extra_tabs' + extra_tab_row + '\']').tab('show');

              $('#extra_tab_description_div'+extra_tab_row).tab('show');

              $('#extra_tab_description_div'+extra_tab_row+' a:first').trigger('click');
       
							$('.summernote').summernote({height: 100});

							<?php if ($ckeditor) { ?>
								<?php foreach ($languages as $language) { ?>
									ckeditorInit('extra_tab_description_textarea'+extra_tab_row+'<?php echo $language['language_id']; ?>', getURLVar('token'));
								<?php } ?>
							<?php } ?>

              extra_tab_row++;
            }
          });

          $('#extra_tabs a:first').tab('show');

          <?php $extra_tab_row = 0; ?>
          <?php foreach ($oct_product_extra_tabs as $oct_product_extra_tab) { ?>
            $('#extra_tab_description_div<?php echo $extra_tab_row; ?> a:first').tab('show');

						<?php if ($ckeditor) { ?>
							<?php foreach ($languages as $language) { ?>
								ckeditorInit('extra_tab_description_textarea<?php echo $extra_tab_row; ?><?php echo $language['language_id']; ?>', getURLVar('token'));
							<?php } ?>
						<?php } ?>

          <?php $extra_tab_row++; ?>
          <?php } ?>

          </script>
        <?php } ?>
      
						<div class="tab-pane" id="tab-option">
							<div class="row">
								<div class="col-sm-2">
									<ul class="nav nav-pills nav-stacked" id="option">
										<?php $option_row = 0; ?>
										<?php foreach ($product_options as $product_option) { ?>
											<li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
											<?php $option_row++; ?>
										<?php } ?>
										<li>
											<input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" class="form-control" />
										</li>
									</ul>
								</div>
								<div class="col-sm-10">
									<div class="tab-content">
										<?php $option_row = 0; ?>
										<?php $option_value_row = 0; ?>
										<?php foreach ($product_options as $product_option) { ?>
											<div class="tab-pane" id="tab-option<?php echo $option_row; ?>">
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
												<input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
													<div class="col-sm-10">
														<select name="product_option[<?php echo $option_row; ?>][required]" id="input-required<?php echo $option_row; ?>" class="form-control">
															<?php if ($product_option['required']) { ?>
																<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																<option value="0"><?php echo $text_no; ?></option>
																<?php } else { ?>
																<option value="1"><?php echo $text_yes; ?></option>
																<option value="0" selected="selected"><?php echo $text_no; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<?php if ($product_option['type'] == 'text') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'textarea') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-10">
															<textarea name="product_option[<?php echo $option_row; ?>][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control"><?php echo $product_option['value']; ?></textarea>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'file') { ?>
													<div class="form-group" style="display: none;">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control" />
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'date') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-3">
															<div class="input-group date">
																<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value<?php echo $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'time') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-10">
															<div class="input-group time">
																<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'datetime') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
														<div class="col-sm-10">
															<div class="input-group datetime">
																<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' || $product_option['type'] == 'oct_quantity') { ?>
													<div class="table-responsive">
														<table id="option-value<?php echo $option_row; ?>" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<td class="text-left"><?php echo $entry_option_value; ?></td>
																	<td class="text-right"><?php echo $entry_quantity; ?></td>
																	<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?><td class="text-left" <?php /* if (!$productIsAddedFromSupplier) { ?>style="display:none"<?php } */ ?>><?php echo $entry_option_stock; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?><td class="text-left" <?php /* if ($productIsAddedFromSupplier) { ?>style="display:none"<?php } */ ?>><?php echo $entry_option_supplier; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?><td class="text-left"><?php echo $entry_option_sku; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?><td class="text-left"><?php echo $entry_option_optsku; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?><td class="text-left"><?php echo $entry_option_ean; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_model']) { ?><td class="text-left"><?php echo $entry_option_model; ?></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?><td class="text-left"><?php echo $entry_option_image; ?></td><?php } ?>
																	<?php } ?>
																	<td class="text-left"><?php echo $entry_subtract; ?></td>																	
																	<td class="text-right"><?php echo $entry_price; ?></td>
																	<td class="text-right"><?php echo $entry_option_points; ?></td>
																	<td class="text-right"><?php echo $entry_weight; ?></td>
																	<td></td>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
																	<tr id="option-value-row<?php echo $option_value_row; ?>">
																		<td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" class="form-control">
																			<?php if (isset($option_values[$product_option['option_id']])) { ?>
																				<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																					<?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
																						<option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
																						<?php } else { ?>
																						<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
																					<?php } ?>
																				<?php } ?>
																			<?php } ?>
																		</select>
																		<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
																		<td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
																		
																		<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
																			<td class="text-right" <?php /* if (!$productIsAddedFromSupplier) { ?>style="display:none"<?php }  */ ?>><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][stock]" value="<?php echo $product_option_value['stock']; ?>" placeholder="<?php echo $entry_option_stock; ?>" class="form-control" /></td>

																			<td class="text-right" <?php /* if (!$productIsAddedFromSupplier) { ?>style="display:none"<?php }  */ ?>><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][supplier]" value="<?php echo $product_option_value['supplier']; ?>" placeholder="<?php echo $entry_option_supplier; ?>" class="form-control" /></td>
																		<?php } ?>



																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?><td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][sku]" value="<?php echo $product_option_value['sku']; ?>" placeholder="<?php echo $entry_option_sku; ?>" class="form-control" /></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?><td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][optsku]" value="<?php echo $product_option_value['optsku']; ?>" placeholder="<?php echo $entry_option_optsku; ?>" class="form-control" /></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?><td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][ean]" value="<?php echo $product_option_value['ean']; ?>" placeholder="<?php echo $entry_option_ean; ?>" class="form-control" /></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_model']) { ?><td class="text-right"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][model]" value="<?php echo $product_option_value['model']; ?>" placeholder="<?php echo $entry_option_model; ?>" class="form-control" /></td><?php } ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>
																			<td class="text-left">
																				<a href="" id="thumb-image<?php echo $option_row; ?>-<?php echo $option_value_row; ?>" data-toggle="image" class="img-thumbnail"><img class="img-responsive" src="<?php echo $product_option_value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
																				<input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][image]" value="<?php echo $product_option_value['image']; ?>" id="input-image<?php echo $option_row; ?>-<?php echo $option_value_row; ?>" />
																			</td>
																		<?php } ?>
																		
																		
																		<td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="form-control">
																			<?php if ($product_option_value['subtract']) { ?>
																				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
																				<option value="0"><?php echo $text_no; ?></option>
																				<?php } else { ?>
																				<option value="1"><?php echo $text_yes; ?></option>
																				<option value="0" selected="selected"><?php echo $text_no; ?></option>
																			<?php } ?>
																		</select></td>
																		<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="form-control">
																			<?php if ($product_option_value['price_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['price_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
																		<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" class="form-control">
																			<?php if ($product_option_value['points_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['points_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>
																		<td class="text-right"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" class="form-control">
																			<?php if ($product_option_value['weight_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['weight_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>
																		<td class="text-left"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
																	</tr>
																	<?php $option_value_row++; ?>
																<?php } ?>
															</tbody>
															<tfoot>
																<tr>
																	<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
																		<?php $oct_result_row_add = 6; ?>
																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<?php if ($oct_advanced_options_settings_data['allow_model']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>
																			<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
																		<?php } ?>
																		<td colspan="<?php echo $oct_result_row_add; ?>"></td>
																		<?php } else { ?>
																		<td colspan="6"></td>
																	<?php } ?>
																	<td class="text-left"><button type="button" onclick="addOptionValue('<?php echo $option_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
																</tr>
															</tfoot>
														</table>
													</div>
													<select id="option-values<?php echo $option_row; ?>" style="display: none;">
														<?php if (isset($option_values[$product_option['option_id']])) { ?>
															<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																<option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												<?php } ?>
											</div>
											<?php $option_row++; ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-recurring">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_recurring; ?></td>
											<td class="text-left"><?php echo $entry_customer_group; ?></td>
											<td class="text-left"></td>
										</tr>
									</thead>
									<tbody>
										<?php $recurring_row = 0; ?>
										<?php foreach ($product_recurrings as $product_recurring) { ?>
											
											<tr id="recurring-row<?php echo $recurring_row; ?>">
												<td class="text-left"><select name="product_recurring[<?php echo $recurring_row; ?>][recurring_id]" class="form-control">
													<?php foreach ($recurrings as $recurring) { ?>
														<?php if ($recurring['recurring_id'] == $product_recurring['recurring_id']) { ?>
															<option value="<?php echo $recurring['recurring_id']; ?>" selected="selected"><?php echo $recurring['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-left"><select name="product_recurring[<?php echo $recurring_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_recurring['customer_group_id']) { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-left"><button type="button" onclick="$('#recurring-row<?php echo $recurring_row; ?>').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $recurring_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<td class="text-left"><button type="button" onclick="addRecurring()" data-toggle="tooltip" title="<?php echo $button_recurring_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-discount">
							<div class="table-responsive">
								<table id="discount" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_customer_group; ?></td>
											<td class="text-right"><?php echo $entry_quantity; ?></td>
											<td class="text-right"><?php echo $entry_priority; ?></td>
											<td class="text-right"><?php echo $entry_price; ?></td>
											<td class="text-left"><?php echo $entry_date_start; ?></td>
											<td class="text-left"><?php echo $entry_date_end; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $discount_row = 0; ?>
										<?php foreach ($product_discounts as $product_discount) { ?>
											<tr id="discount-row<?php echo $discount_row; ?>">
												<td class="text-left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
												<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
												<td class="text-right"><input type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
												<td class="text-left" style="width: 20%;"><div class="input-group date">
													<input type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
													<span class="input-group-btn">
														<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
													</span></div></td>
													<td class="text-left" style="width: 20%;"><div class="input-group date">
														<input type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
														</span></div></td>
														<td class="text-left"><button type="button" onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $discount_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6"></td>
											<td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?php echo $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-special">
							<div class="table-responsive">
								<table id="special" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_customer_group; ?></td>
											<td class="text-right"><?php echo $entry_priority; ?></td>
											<td class="text-right"><?php echo $entry_price; ?></td>
											<td class="text-left"><?php echo $entry_date_start; ?></td>
											<td class="text-left"><?php echo $entry_date_end; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $special_row = 0; ?>
										<?php foreach ($product_specials as $product_special) { ?>
											<tr id="special-row<?php echo $special_row; ?>">
												<td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
												<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>
												<td class="text-left" style="width: 20%;"><div class="input-group date">
													<input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
													<span class="input-group-btn">
														<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
													</span></div></td>
													<td class="text-left" style="width: 20%;"><div class="input-group date">
														<input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
														</span></div></td>
														<td class="text-left"><button type="button" onclick="$('#special-row<?php echo $special_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $special_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5"></td>
											<td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?php echo $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>


		<div class="tab-pane" id="tab-epicentr">

					<div class="form-group">
		          <label class="col-sm-2 control-label" for="input-epicentr">Название товара [RU]</label>
		         	<div class="col-sm-10">
		            <input type="text" name="epicentr_name" placeholder="Если это поле пустое - выгружается главное название товара" id="input-epicentr_name"  value="<? echo $epicentr_name; ?>" class="form-control" autocomplete="off">
							</div>
		      </div>

		     	<div class="form-group">
		          <label class="col-sm-2 control-label" for="input-epicentr">Название товара [UA]</label>
		         	<div class="col-sm-10">
		            <input type="text" name="epicentr_name_ua" placeholder="Если это поле пустое - выгружается главное название товара" id="input-epicentr_name_ua"  value="<? echo $epicentr_name_ua; ?>" class="form-control" autocomplete="off">
							</div>
		      </div>
		    
		    		<div class="form-group">
		                    <label class="col-sm-2 control-label" for="input-epicentr">Цена товара</label>
		                    <div class="col-sm-10">
		                    <input type="text" name="epicentr_price" placeholder="Эта цена будет выводиться вместо базовой цены товара" id="input-epicentr_price"  value="<? echo $epicentr_price; ?>" class="form-control" autocomplete="off">
							</div>
		            </div>
		            
					<div class="form-group">
		                    <label class="col-sm-2 control-label" for="input-epicentr">Количество товара</label>
		                    <div class="col-sm-10">
		                    <input type="number" name="epicentr_quantity" placeholder="Если это поле пустое - выгружается базовое количество" id="input-epicentr_quantity"  value="<? echo $epicentr_quantity; ?>" class="form-control" autocomplete="off">
							</div>
		            </div>


		            <div class="form-group">

		                <label class="col-sm-2 control-label" for="input-epicentr">Статус выгрузки товара</label>
		                <div class="col-sm-10">
		                    <select name="epicentr_status" id="input-epicentr_status" class="form-control">
		           
		                    <option value="0" <? if($epicentr_status == 0){ ?> selected="selected" <?}?> >Не выгружается</option>
		                    <option value="1" <? if($epicentr_status == 1){ ?> selected="selected" <?}?> >Выгружается</option>

		                    </select>
		                </div>
		            
		            </div>

                    <div class="form-group col-sm-5">
                          <table id="epicentr-images" class="table table-striped table-bordered table-hover">
                            <thead>
                              <tr>
                                <td class="text-center">Изображение</td>
                                <td class="text-center">Состояние выгрузки</td>
                                <td class="text-center">Опция</td>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $image_row = 0; ?>
                              <?php foreach ($product_images as $product_image) { ?>
                              <tr id="image-row<?php echo $image_row; ?>">
                                <td class="text-center">
                                  <img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
                                </td>
                                <td class="text-center">
                                    <?php if ($product_image['epicentr_status']) { ?>
                                        <input type="checkbox" name="product_image[<?php echo $image_row; ?>][epicentr_status]" value="1" checked="checked" />
                                    <?php } else { ?>
                                        <input type="checkbox" name="product_image[<?php echo $image_row; ?>][epicentr_status]" value="1" />
                                    <?php } ?>
                                </td>
                                <td>
                                  <select name="product_image[<?php echo $image_row; ?>][epicentr_option]" class="form-control">
  
                                    <option value="">-Выберите опцию-</option>

                                    <?php foreach ($product_options as $option) { ?>
                                    <option value="" disabled><?php echo $option['name']; ?></option>
                                    <?php foreach ($option['product_option_value'] as $options) { ?>
                                      <?if($product_image['epicentr_option'] == $options['product_option_value_id']){?>
                                        <option value="<?php echo $options['product_option_value_id']; ?>" selected="selected"><?php echo $options['name']; ?></option>
                                      <?php } else { ?>
                                        <option value="<?php echo $options['product_option_value_id']; ?>"><?php echo $options['name']; ?></option>
                                      <?php } ?>
                                    <? } ?>
                                    <?php } ?>
                                  </select>
                                </td>
                              </tr>
                              <?php $image_row++; ?>
                              <?php } ?>
                            </tbody>
                          </table>
                   </div>

                    <div class="form-group col-sm-7">
                             <label class="col-sm-12 text-left" for="input-epicentr"><span data-toggle="tooltip" title="Если длина этого описания меньше 50 символов или это поле пустое, то будет выведено основное описание">Описание товара [RU]</span></label>
                             <div class="col-sm-12">
                             <textarea name="epicentr_description" placeholder="Если это поле пустое, то выгружается базовое описание товара" id="epicentr-description" class="form-control ckeditor-editor"><?php echo $epicentr_description; ?></textarea>
                             </div>
										<hr>
                             <label class="col-sm-12 text-left" for="input-epicentr"><span data-toggle="tooltip" title="Если длина этого описания меньше 50 символов или это поле пустое, то будет выведено основное описание">Описание товара [UA]</span></label>
                             <div class="col-sm-12">
                             <textarea name="epicentr_description_ua" placeholder="Если это поле пустое, то выгружается базовое описание товара" id="epicentr-description-ua" class="form-control ckeditor-editor"><?php echo $epicentr_description_ua; ?></textarea>
                             </div>
                    </div>
     



		          <div class="form-group">
		            <div style="text-align:center;" class="col-sm-12">
		             Epicentr XML v1.2.2 By Alex Soloviov :: <a href='https://shop.ionline.su'>shop.ionline.su</a>
		            </div>
		          </div>
		          

		</div>
		
        <script>
        
         //   $('#epicentr-description, #epicentr-description-ua').summernote({
         //   	height: 450
        //  });

		ckeditorInit('epicentr-description', getURLVar('token'));
		ckeditorInit('epicentr-description-ua', getURLVar('token'));			

        </script> 
			
						<div class="tab-pane" id="tab-image">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_image; ?></td>

        <?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
          <!-- oct_advanced_options_settings start -->
          <td class="text-left"><?php echo $entry_option_value; ?></td>
          <!-- oct_advanced_options_settings end -->
        <?php } ?>
      
										</tr>
									</thead>
									
									<tbody>
										<tr>
											<td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
												<br />
												<code style="max-width:250px;display:inline-block;"><?php echo $image; ?></code>
												
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="table-responsive">
								<table id="images" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?php echo $entry_additional_image; ?></td>
											<td class="text-right" width="200px;">YouTube - <img src="/admin75s47fd4777/view/image/video_in_product.jpg"></td>
											<td class="text-left" width="400px"><?php echo $entry_option_value; ?></td>
											<td class="text-right" width="100px"><?php echo $entry_sort_order; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $image_row = 0; ?>
										<?php foreach ($product_images as $product_image) { ?>
											<tr id="image-row<?php echo $image_row; ?>">
												<td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
													<br />
													<code style="max-width:250px;display:inline-block;"><?php echo $product_image['image']; ?></code>
												</td>
												
												<td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][video_in_product]" value="<?php echo $product_image['video_in_product']; ?>" placeholder="<?php echo $entry_video_in_product; ?>" class="form-control" /></td>
												
												<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
													<!-- oct_advanced_options_settings start -->
													<td class="text-right">
														<?php asort($product_options); foreach ($product_options as $product_option) { ?>
															<?php if ($product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'select') { ?>
																<div class="col-sm-12 col-md-12 col-lg-12">
																	<div class="well well-sm" style="height: 150px; overflow: auto;text-align:left;margin-bottom:4px;">
																		<?php if (isset($option_values[$product_option['option_id']])) { ?>
																			<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																				<?php $add_value = false; foreach ($product_option['product_option_value'] as $product_option_value) {
																					if ($product_option_value['option_value_id'] == $option_value['option_value_id']){
																						$add_value = true;
																						break;
																					}
																				} ?>
																				
																				<?php if ($add_value) { ?>
																					<label>
																						<?php if (in_array($option_value['option_value_id'], $product_image['image_by_option'])) { ?>
																						<input type="checkbox" name="product_image[<?php echo $image_row; ?>][image_by_option][]" value="<?php echo $option_value['option_value_id']; ?>" checked="checked"/> <?php echo $product_option['name']; ?> > <?php echo $option_value['name']; ?></label>
																						<?php } else { ?>
																					<input type="checkbox" name="product_image[<?php echo $image_row; ?>][image_by_option][]" value="<?php echo $option_value['option_value_id']; ?>" /> <?php echo $product_option['name']; ?> > <?php echo $option_value['name']; ?></label>
																				<?php } ?>
																				<br/>
																			<?php } ?>
																		<?php } ?>
																	<?php } ?>
																</div>
															</div>
														<?php } ?>
													<?php } ?>
												</td>
												<!-- oct_advanced_options_settings end -->
											<?php } ?>
											
											<td class="text-right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
											
											<td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
										</tr>
										<?php $image_row++; ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"></td>
										<td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="tab-reward">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?php echo $help_points; ?>"><?php echo $entry_points; ?></span></label>
							<div class="col-sm-10">
								<input type="text" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-left"><?php echo $entry_customer_group; ?></td>
										<td class="text-right"><?php echo $entry_reward; ?></td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($customer_groups as $customer_group) { ?>
										<tr>
											<td class="text-left"><?php echo $customer_group['name']; ?></td>
											<td class="text-right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" /></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
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
										<td class="text-left"><select name="product_layout[0]" class="form-control">
											<option value=""></option>
											<?php foreach ($layouts as $layout) { ?>
												<?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
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
											<td class="text-left"><select name="product_layout[<?php echo $store['store_id']; ?>]" class="form-control">
												<option value=""></option>
												<?php foreach ($layouts as $layout) { ?>
													<?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
					
					<?php if ($pricehistory) { ?>
						<div class="tab-pane" id="tab-pricehistory">
							<div class="row">
								<div class="col-sm-6">
									<div class="table-responsive">          
										<table class="table">
											<thead>
												<tr>
													<th>Поставщик</th>
													<th>Цена</th>
													<th>Дата</th>												
												</tr>
											</thead>
											<tbody>
												<? foreach ($pricehistory as $priceline) { ?>
													<tr>
														<td><span class="label label-info"><?php echo $priceline['suppler_code'] ?></span></td>
														<td><?php echo $priceline['price']; ?></td>
														<td><?php echo $priceline['date']; ?></td>															
													</tr>
												<? } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-sm-6">
									
								</div>
							</div>
						</div>
					<? } ?>
					
					<?php if ($salestock_info) { ?>
						<div class="tab-pane" id="tab-salestock">
							<div class="row">
								<div class="col-sm-6">
									<div class="table-responsive">          
										<table class="table">
											<thead>
												<tr>
													<th>Заказ</th>
													<th>Колво</th>
													<th>Добавлен</th>
													<th>Изменен</th>
												</tr>
											</thead>
											<tbody>
												<? foreach ($salestock_info['orders'] as $_order) { ?>
													<tr>
														<td><a href="<?php echo $_order['href']; ?>"><?php echo $_order['order_id']; ?></a></td>
														<td><?php echo $_order['quantity']; ?></td>
														<td><?php echo $_order['date_added']; ?></td>
														<td><?php echo $_order['date_modified']; ?></td>
													</tr>
												<? } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-sm-6">
									
								</div>
							</div>
						</div>
					<? } ?>
					
					

				<div class="tab-pane" id="tab-faq">
				<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $faq_name; ?></label>
				<div class="col-sm-10">
				<?php foreach($languages as $language) { ?>
                    <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="product_description[<?php echo $language['language_id']; ?>][faq_name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['faq_name'] : ''; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" style="width:50%;display:inline-block;"/></div><br />
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
				<?php foreach ($product_faq as $product_faq) { ?>
                    <tr id="faq-row<?php echo $faq_row; ?>">
					<td class="text-center">
					<?php foreach($languages as $language) { ?>
						<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="product_faq[<?php echo $faq_row; ?>][question][<?php echo $language['language_id']; ?>]" value="<?php if (isset($product_faq['question'][$language['language_id']])) echo $product_faq['question'][$language['language_id']]; ?>" class="form-control" style="display:inline-block;width:80%;" /></div><br />
					<?php } ?>
					</td>
					<td class="text-center">
					<?php foreach($languages as $language) { ?>
						<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><textarea rows="3" name="product_faq[<?php echo $faq_row; ?>][faq][<?php echo $language['language_id']; ?>]" class="form-control summernote" style="display:inline-block;width:80%;"><?php if (isset($product_faq['faq'][$language['language_id']])) echo $product_faq['faq'][$language['language_id']]; ?></textarea></div><br />
					<?php } ?> 
					</td>
					<td class="text-center"><input type="text" name="product_faq[<?php echo $faq_row; ?>][icon]" value="<?php echo $product_faq['icon']; ?>" class="form-control" /></td>
					<td class="text-center"><input type="text" name="product_faq[<?php echo $faq_row; ?>][sort_order]" value="<?php echo $product_faq['sort_order']; ?>" class="form-control" /></td>
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
	// Manufacturer
	$('input[name=\'manufacturer\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						manufacturer_id: 0,
						name: '<?php echo $text_none; ?>'
					});
					
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'manufacturer\']').val(item['label']);
			$('input[name=\'manufacturer_id\']').val(item['value']);
		}
	});
	
	// Category
	$('input[name=\'category\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
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
			$('input[name=\'category\']').val('');
			
			$('#product-category' + item['value']).remove();
			
			$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
		}
	});
	
	$('#product-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
	
	// Filter
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
			
			$('#product-filter' + item['value']).remove();
			
			$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
		}
	});
	
	$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
	
	// Downloads
	$('input[name=\'download\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['download_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'download\']').val('');
			
			$('#product-download' + item['value']).remove();
			
			$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
		}
	});
	
	$('#product-download').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
	
	// Related
	$('input[name=\'related\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'related\']').val('');
			
			$('#product-related' + item['value']).remove();
			
			$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
		}
	});
	
	$('#product-related').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
//--></script>
<script type="text/javascript"><!--
	var attribute_row = <?php echo $attribute_row; ?>;
	
	function addAttribute() {
		html  = '<tr id="attribute-row' + attribute_row + '">';
		html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
		html += '  <td class="text-left">';
		<?php foreach ($languages as $language) { ?>
			html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
<?php if($language['language_id'] != $at_source && $at_status) { ?>
    html += '<a class="btn-attr-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),\'textarea\',\'product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $at_source; ?>][text]\',1);"><?php echo $btn_copy; ?></a>';
    <?php if($at_status == 1){ ?>
        html += '<a class="btn-attr-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),\'<?php echo $at_api_key; ?>\',\'<?php echo $at_g_language[$at_source]; ?>\',\'<?php echo $at_g_language[$language['language_id']]; ?>\',';
        html += '\'textarea\',\'product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $at_source; ?>][text]\',1);"><?php echo $btn_translate; ?></a>';
    <?php } else if($at_status == 2) { ?>
        html += '<a class="btn-attr-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),\'<?php echo $at_m_language[$at_source]; ?>\',\'<?php echo $at_m_language[$language['language_id']]; ?>\',';
        html += '\'textarea\',\'product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $at_source; ?>][text]\',1);"><?php echo $btn_translate; ?></a>';
    <?php } ?>
<?php } ?>
html += '<textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
		<?php } ?>
		html += '  </td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		
		$('#attribute tbody').append(html);
		
		attributeautocomplete(attribute_row);
		
		attribute_row++;
	}
	
	function attributeautocomplete(attribute_row) {
		$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								category: item.attribute_group,
								label: item.name,
								value: item.attribute_id
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
				$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
			}
		});
	}
	
	$('#attribute tbody tr').each(function(index, element) {
		attributeautocomplete(index);
	});
//--></script>
<script type="text/javascript"><!--
	var option_row = <?php echo $option_row; ?>;
	
	$('input[name=\'option\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							category: item['category'],
							label: item['name'],
							value: item['option_id'],
							type: item['type'],
							option_value: item['option_value']
						}
					}));
				}
			});
		},
		'select': function(item) {
			html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
			html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
			html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';
			
			html += '	<div class="form-group">';
			html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
			html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
			html += '	      <option value="1"><?php echo $text_yes; ?></option>';
			html += '	      <option value="0"><?php echo $text_no; ?></option>';
			html += '	  </select></div>';
			html += '	</div>';
			
			if (item['type'] == 'text') {
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'textarea') {
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'file') {
				html += '	<div class="form-group" style="display: none;">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'date') {
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'time') {
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'datetime') {
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
				html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
				html += '	</div>';
			}
			
			if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image' || item['type'] == 'oct_quantity') {
				html += '<div class="table-responsive">';
				html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
				html += '  	 <thead>';
				html += '      <tr>';
				html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
				html += '        <td class="text-right"><?php echo $entry_quantity; ?></td>';
				<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
					<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>html += '        <td class="text-left"><?php echo $entry_option_stock; ?></td>';<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>html += '        <td class="text-left"><?php echo $entry_option_sku; ?></td>';<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>html += '        <td class="text-left"><?php echo $entry_option_optsku; ?></td>';<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?>html += '        <td class="text-left"><?php echo $entry_option_ean; ?></td>';<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_model']) { ?>html += '        <td class="text-left"><?php echo $entry_option_model; ?></td>';<?php } ?>
					<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>html += '        <td class="text-left"><?php echo $entry_option_image; ?></td>';<?php } ?>
				<?php } ?>
				html += '        <td class="text-left"><?php echo $entry_subtract; ?></td>';
				html += '        <td class="text-right"><?php echo $entry_price; ?></td>';
				html += '        <td class="text-right"><?php echo $entry_option_points; ?></td>';
				html += '        <td class="text-right"><?php echo $entry_weight; ?></td>';
				html += '        <td></td>';
				html += '      </tr>';
				html += '  	 </thead>';
				html += '  	 <tbody>';
				html += '    </tbody>';
				html += '    <tfoot>';
				html += '      <tr>';
				<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
					<?php $oct_result_row_add = 6; ?>
					<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					<?php if ($oct_advanced_options_settings_data['allow_model']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>
						<?php $oct_result_row_add = $oct_result_row_add + 1; ?>
					<?php } ?>
					html += '        <td colspan="<?php echo $oct_result_row_add; ?>"></td>';
					<?php } else { ?>
					html += '        <td colspan="6"></td>';
				<?php } ?>
				html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
				html += '      </tr>';
				html += '    </tfoot>';
				html += '  </table>';
				html += '</div>';
				
				html += '  <select id="option-values' + option_row + '" style="display: none;">';
				
				for (i = 0; i < item['option_value'].length; i++) {
					html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
				}
				
				html += '  </select>';
				html += '</div>';
			}
			
			$('#tab-option .tab-content').append(html);
			
			$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick=" $(\'#option a:first\').tab(\'show\');$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove();"></i>' + item['label'] + '</li>');
			
			$('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
			
			$('[data-toggle=\'tooltip\']').tooltip({
				container: 'body',
				html: true
			});
			
			$('.date').datetimepicker({
				pickTime: false
			});
			
			$('.time').datetimepicker({
				pickDate: false
			});
			
			$('.datetime').datetimepicker({
				pickDate: true,
				pickTime: true
			});
			
			option_row++;
		}
	});
//--></script>
<script type="text/javascript"><!--
	var option_value_row = <?php echo $option_value_row; ?>;
	
	function addOptionValue(option_row) {
		html  = '<tr id="option-value-row' + option_value_row + '">';
		html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
		html += $('#option-values' + option_row).html();
		html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
		
		<?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
			<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][stock]" value="" placeholder="<?php echo $entry_option_stock; ?>" class="form-control" /></td>';<?php } ?>
			
			<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][sku]" value="" placeholder="<?php echo $entry_option_sku; ?>" class="form-control" /></td>';<?php } ?>
			
			<?php if ($oct_advanced_options_settings_data['allow_sku']) { ?>html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][optsku]" value="" placeholder="<?php echo $entry_option_optsku; ?>" class="form-control" /></td>';<?php } ?>
			
			<?php if ($oct_advanced_options_settings_data['allow_ean']) { ?>html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][ean]" value="" placeholder="<?php echo $entry_option_ean; ?>" class="form-control" /></td>';<?php } ?>
			<?php if ($oct_advanced_options_settings_data['allow_model']) { ?>html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][model]" value="" placeholder="<?php echo $entry_option_model; ?>" class="form-control" /></td>';<?php } ?>
			<?php if ($oct_advanced_options_settings_data['quantity_status']) { ?>html += '  <td class="text-left"><a href="" id="thumb-image' + option_row + '-' + option_value_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][image]" value="" id="input-image' + option_row + '-' + option_value_row + '" /></td>';<?php } ?>
		<?php } ?>
		
		html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
		html += '    <option value="1"><?php echo $text_yes; ?></option>';
		html += '    <option value="0"><?php echo $text_no; ?></option>';
		html += '  </select></td>';
		html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
		html += '    <option value="+">+</option>';
		html += '    <option value="-">-</option>';
		html += '  </select>';
		html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
		html += '    <option value="+">+</option>';
		html += '    <option value="-">-</option>';
		html += '  </select>';
		html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
		html += '    <option value="+">+</option>';
		html += '    <option value="-">-</option>';
		html += '  </select>';
		html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		
		$('#option-value' + option_row + ' tbody').append(html);
		$('[rel=tooltip]').tooltip();
		
		option_value_row++;
	}
//--></script>
<script type="text/javascript"><!--
	var discount_row = <?php echo $discount_row; ?>;
	
	function addDiscount() {
		html  = '<tr id="discount-row' + discount_row + '">';
		html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '  </select></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
		html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		
		$('#discount tbody').append(html);
		
		$('.date').datetimepicker({
			pickTime: false
		});
		
		discount_row++;
	}
//--></script>
<script type="text/javascript"><!--
	var special_row = <?php echo $special_row; ?>;
	
	function addSpecial() {
		html  = '<tr id="special-row' + special_row + '">';
		html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
		<?php } ?>
		html += '  </select></td>';
		html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
		html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		
		$('#special tbody').append(html);
		
		$('.date').datetimepicker({
			pickTime: false
		});
		
		special_row++;
	}
//--></script>
<script type="text/javascript"><!--
	var image_row = <?php echo $image_row; ?>;
	
	function addImage() {
		html  = '<tr id="image-row' + image_row + '">';
		html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][video_in_product]" value="" placeholder="Введите ссылку на видео" class="form-control" /></td>';

        <?php if (isset($oct_advanced_options_settings_data['status']) && $oct_advanced_options_settings_data['status']) { ?>
        // oct_advanced_options_settings start
          html += '  <td class="text-right">';
            <?php asort($product_options); foreach ($product_options as $product_option) { ?>
              <?php if ($product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'select') { ?>
          html += '    <div class="col-sm-12 col-md-12 col-lg-12">';
          html += '      <div class="well well-sm" style="height: 150px; overflow: auto;text-align:left;margin-bottom:4px;">';
                            <?php if (isset($option_values[$product_option['option_id']])) { ?>
                              <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
          html += '            <label><input type="checkbox" name="product_image[' + image_row + '][image_by_option][]" value="<?php echo $option_value['option_value_id']; ?>" /> <?php echo $product_option['name']; ?> > <?php echo $option_value['name']; ?></label><br/>';
                              <?php } ?>
                            <?php } ?>
          html += '      </div>';
          html += '    </div>';
              <?php } ?>
            <?php } ?>
          html += '  </td>';
          // oct_advanced_options_settings end
        <?php } ?>
      
		html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		
		$('#images tbody').append(html);
		
		image_row++;
	}
//--></script>
<script type="text/javascript"><!--
	var recurring_row = <?php echo $recurring_row; ?>;
	
	function addRecurring() {
		html  = '<tr id="recurring-row' + recurring_row + '">';
		html += '  <td class="left">';
		html += '    <select name="product_recurring[' + recurring_row + '][recurring_id]" class="form-control">>';
		<?php foreach ($recurrings as $recurring) { ?>
			html += '      <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>';
		<?php } ?>
		html += '    </select>';
		html += '  </td>';
		html += '  <td class="left">';
		html += '    <select name="product_recurring[' + recurring_row + '][customer_group_id]" class="form-control">>';
		<?php foreach ($customer_groups as $customer_group) { ?>
			html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
		<?php } ?>
		html += '    <select>';
		html += '  </td>';
		html += '  <td class="left">';
		html += '    <a onclick="$(\'#recurring-row' + recurring_row + '\').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>';
		html += '  </td>';
		html += '</tr>';
		
		$('#tab-recurring table tbody').append(html);
		
		recurring_row++;
	}
//--></script>
<script type="text/javascript"><!--
	$('.date').datetimepicker({
		pickTime: false
	});
	
	$('.time').datetimepicker({
		pickDate: false
	});
	
	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true
	});
//--></script>
<script type="text/javascript"><!--
	$('#language a:first').tab('show');
	$('#option a:first').tab('show');
//--></script></div>

<script type="text/javascript">
	<?php // if ($ckeditor) { ?>
		<?php foreach ($languages as $language) { ?>
			ckeditorInit('input-description<?php echo $language['language_id']; ?>', getURLVar('token'));
			ckeditorInit('input-fake_description<?php echo $language['language_id']; ?>', getURLVar('token'));			
		<?php } ?>
	<?php // } ?>
</script>

<script type = "text/javascript" > 
$("#qsave").on("click", function() {
    for (var zz = $(".note-editor").length, i = 0; zz > i; i++) {
        var yy = $(".note-editor").eq(i).parent().children("textarea").attr("id");
        if ("function" == typeof $().code) var content = $("#" + yy).code();
        else var content = $("#" + yy).summernote("code");
        $("#" + yy).html(content)
    }

    const allEditors = CKEDITOR.instances;
    for (var zz = $(".ckeditor-editor").length, i = 0; zz > i; i++) {
    	var yy = $(".ckeditor-editor").eq(i).parent().children("textarea").attr("id");
    	$("#" + yy).html(CKEDITOR.instances[yy].getData());    	
    }

    $.ajax({
        type: "post",
        data: $("form").serialize(),
        url: "index.php?route=catalog/product/qsave&token=<?php echo $token; ?>&product_id=<?php echo $pidqs; ?>",
        dataType: "json",
        beforeSend: function() {
            $("#qsave").prop("disabled", !0)
        },
        complete: function() {
            $("#qsave").prop("disabled", !1)
        },
        success: function(e) {
            if ($(".alert").remove(), $(".text-danger").remove(), $(".form-group").removeClass("has-error"), e.error) {
                if (html = '<div class="alert alert-danger">', html += " " + e.error.warning + ' <button type="button" class="close" data-dismiss="alert">&times;</button></br>', e.error.model && ($("#input-model").after('<div class="text-danger">' + e.error.model + "</div>"), html += '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.model), e.error.keyword && ($("#input-keyword").after('<div class="text-danger">' + e.error.keyword + "</div>"), html += '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.keyword), e.error.name) {
                    var r = "";
                    for (i in e.error.name) {
                        var a = $("#input-name" + i);
                        $(a).parent().hasClass("input-group") ? ($(a).parent().after('<div class="text-danger">' + e.error.name[i] + "</div>"), r = '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.name[i]) : ($(a).after('<div class="text-danger">' + e.error.name[i] + "</div>"), r = '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.name[i])
                    }
                    html += r
                }
                if (e.error.meta_title) {
                    var r = "";
                    for (i in e.error.meta_title) {
                        var a = $("#input-meta-title" + i);
                        $(a).parent().hasClass("input-group") ? ($(a).parent().after('<div class="text-danger">' + e.error.meta_title[i] + "</div>"), r = '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.meta_title[i]) : ($(a).after('<div class="text-danger">' + e.error.meta_title[i] + "</div>"), r = '</br><i class="fa fa-exclamation-circle"></i> ' + e.error.meta_title[i])
                    }
                    html += r
                }
                $(".text-danger").parentsUntil(".form-group").parent().addClass("has-error"), html += " </div>", $("#content > .container-fluid").prepend(html)
            }
            e.success && $("#content > .container-fluid").prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + e.success + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>')
        },
        error: function(e, r, a) {
            alert(a + "\r\n" + e.statusText + "\r\n" + e.responseText)
        }
    })
});
</script>


				<script type="text/javascript"><!--
				var faq_row = <?php echo $faq_row; ?>;
				function addFaq() {
				html  = '<tr id="faq-row' + faq_row + '">';
				html += '  <td class="text-center">';
				<?php foreach($languages as $language) { ?>
					html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><input type="text" name="product_faq[' + faq_row + '][question][<?php echo $language['language_id']; ?>]" value="" class="form-control" style="display:inline-block;width:80%;" /></div><br />';
				<?php } ?>
				html += '</td>';
				html += '  <td class="text-center">';
				<?php foreach($languages as $language) { ?>
					html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" style="display:inline-block;"/></span><textarea rows="3" name="product_faq[' + faq_row + '][faq][<?php echo $language['language_id']; ?>]" value="" class="form-control summernote" style="display:inline-block;width:80%;"></textarea></div><br />';
				<?php } ?>
				html += '</td>';
				html += '  <td class="text-center" style="width:10%"><input type="text" name="product_faq[' + faq_row + '][icon]" value=""  class="form-control" /></td>';
				html += '  <td class="text-center" style="width:10%"><input type="text" name="product_faq[' + faq_row + '][sort_order]" value=""  class="form-control" /></td>';
				html += '  <td class="text-center"><button type="button" onclick="$(\'#faq-row' + faq_row + '\').remove();" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
				html += '</tr>';
				
				$('#faq tbody').append(html);
				
				faq_row++;
				}
				//--></script>
			


			

  <!-- OCFilter start -->
  <script type="text/javascript"><!--
  ocfilter.php = {
  	text_select: '<?php echo $text_select; ?>',
  	ocfilter_select_category: '<?php echo $ocfilter_select_category; ?>',
  	entry_values: '<?php echo $entry_values; ?>',
  	tab_ocfilter: '<?php echo $tab_ocfilter; ?>'
  };

  ocfilter.php.languages = [];

  <?php foreach ($languages as $language) { ?>
  ocfilter.php.languages.push({
  	'language_id': <?php echo $language['language_id']; ?>,
  	'name': '<?php echo $language['name']; ?>',
    'image': '<?php echo $language['image']; ?>'
  });
  <?php } ?>
  //--></script>
  <!-- OCFilter end -->
      
<?php echo $footer; ?>
