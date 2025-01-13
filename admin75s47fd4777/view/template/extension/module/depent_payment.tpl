<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-depent-payment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-depent-payment" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="depent_payment_status" id="input-status" class="form-control">
                <?php if ($depent_payment_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <?php foreach($shipping['extensions'] as $ship) { ?>
		  <div class="form-group">
		    <label class="col-sm-2 control-label"><?php echo $ship['name'];?></label>
			<div class="col-sm-10">
			  <div class="well well-sm" style="height: 150px; overflow: auto;">
			  <?php foreach ($payments['extensions'] as $payment) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (isset($depent_payment[$ship['extension']]) && in_array($payment['extension'], $depent_payment[$ship['extension']])) { ?>
                    <input type="checkbox" name="depent_payment[<?php echo $ship['extension'];?>][]" value="<?php echo $payment['extension'];?>" checked="checked" />
                    <?php echo $payment['name'];?>
                    <?php } else { ?>
                    <input type="checkbox" name="depent_payment[<?php echo $ship['extension'];?>][]" value="<?php echo $payment['extension'];?>"/>
                    <?php echo $payment['name'];?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
				</div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
			  </div>
			</div>
		  <?php } ?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
