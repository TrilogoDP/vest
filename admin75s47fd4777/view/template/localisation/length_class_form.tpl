<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-length-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-length-class" class="form-horizontal">
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
            <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"> <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','length_class_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','length_class_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','length_class_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
                <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
              </div>
              <?php if (isset($error_title[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_unit; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>

<?php if($language['language_id'] != $at_source && $at_status){ ?>
    <a class="btn-copy<?php echo $language['language_id']; ?> btn-t-copy" onclick="getCopy($(this),'input','length_class_description[<?php echo $at_source; ?>]');"><?php echo $btn_copy; ?></a>
    <?php if($at_status == 1){ ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-g-translate" onclick="getGoogleTranslate($(this),'<?php echo $at_api_key; ?>','<?php echo $at_g_language[$at_source]; ?>','<?php echo $at_g_language[$language['language_id']]; ?>','input','length_class_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } else if($at_status == 2) { ?>
        <a class="btn-translate<?php echo $language['language_id']; ?> btn-m-translate" onclick="getMicrosoftTranslate($(this),'<?php echo $at_m_language[$at_source]; ?>','<?php echo $at_m_language[$language['language_id']]; ?>','input','length_class_description[<?php echo $at_source; ?>]');">
        <?php echo $btn_translate; ?></a>
    <?php } ?>
<?php } ?>
                <input type="text" name="length_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $entry_unit; ?>" class="form-control" />
              </div>
              <?php if (isset($error_unit[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_unit[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><span data-toggle="tooltip" title="<?php echo $help_value; ?>"><?php echo $entry_value; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>