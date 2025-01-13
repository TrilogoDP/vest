<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-apri" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($update) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $update; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>	
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-apri" class="form-horizontal">
			<ul class="nav nav-tabs" id="tabs">
				<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
				<li><a href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="tab-general">  
					<div class="form-group required">
						<label class="col-sm-3 control-label" for="secret-code"><?php echo $entry_cron_password;?></label>
						<div class="col-sm-9">
							<input type="text" name="apri_secret_code" placeholder="<?php echo $entry_cron_password;?>" id="secret-code" value="<?php echo $apri_secret_code; ?>" class="form-control" />
							<?php if ($error_cron_password) { ?>
							<div class="text-danger"><?php echo $error_cron_password; ?></div>
							<?php } ?>
						</div>
					</div>
					  
					<div class="form-group">
						<label class="col-sm-3 control-label" for="start-date"><span data-toggle="tooltip" title="<?php echo $help_start_date; ?>"><?php echo $entry_start_date;?></span></label>
						<div class="col-sm-9">
							<div class="input-group date">
								<input type="text" name="apri_start_date" placeholder="<?php echo $entry_start_date;?>" data-date-format="YYYY-MM-DD" id="start-date" value="<?php echo $apri_start_date; ?>" class="form-control" />
								<span class="input-group-btn">
								  <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					  
					<div class="form-group">
						<label class="col-sm-3 control-label" for="days-after"><span data-toggle="tooltip" title="<?php echo $help_days_after; ?>"><?php echo $entry_days_after;?></span></label>
						<div class="col-sm-9">
							<input type="text" name="apri_days_after" placeholder="<?php echo $entry_days_after;?>" id="days_after" value="<?php echo $apri_days_after; ?>" class="form-control" />
						</div>
					</div>

					<div class="form-group">
						  <label class="col-sm-3 control-label" for="allowed-statuses"><span data-toggle="tooltip" title="<?php echo $help_allowed_statuses; ?>"><?php echo $entry_allowed_statuses; ?></span></label>
						  <div class="col-sm-9">
							<div class="well well-sm" style="height: 150px; overflow: auto;">
							  <?php foreach ($order_statuses as $order_status) { ?>
							  <div class="checkbox">
								<label>
								  <?php if (in_array($order_status['order_status_id'], $apri_allowed_statuses)) { ?>
								  <input type="checkbox" name="apri_allowed_statuses[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
								  <?php echo $order_status['name']; ?>
								  <?php } else { ?>
								  <input type="checkbox" name="apri_allowed_statuses[]" value="<?php echo $order_status['order_status_id']; ?>" />
								  <?php echo $order_status['name']; ?>
								  <?php } ?>
								</label>
							  </div>
							  <?php } ?>
							</div>
							<?php if ($error_allowed_statuses) { ?>
							<div class="text-danger"><?php echo $error_allowed_statuses; ?></div>
							<?php } ?>
						  </div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-allow-unsubscribe"><span data-toggle="tooltip" title="<?php echo $help_allow_unsubscribe; ?>"><?php echo $entry_allow_unsubscribe; ?></span></label>
						<div class="col-sm-9">
						  <select name="apri_allow_unsubscribe" id="input-allow-unsubscribe" class="form-control">
							<?php if ($apri_allow_unsubscribe) { ?>
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
						<label class="col-sm-3 control-label" for="input-log-admin"><?php echo $entry_log_to_admin; ?></label>
						<div class="col-sm-9">
						  <select name="apri_log_admin" id="input-log-admin" class="form-control">
							<?php if ($apri_log_admin) { ?>
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
					
				<div class="tab-pane" id="tab-mail">
					<ul class="nav nav-tabs" id="language">
						<?php foreach ($languages as $language) { ?>
						<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
						<?php } ?>
					</ul>
					<div class="tab-content">
						<?php foreach ($languages as $language) { ?>
						<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
							<div class="col-sm-offset-3 alert alert-info"><?php echo $help_customized; ?></div>
							<div class="form-group hide">
								<label class="col-sm-3 control-label" for="input-use-html-email"><span data-toggle="tooltip" title="<?php echo $help_html_email; ?>"><?php echo $entry_use_html_email; ?></span></label>
								<div class="col-sm-9">
								  <select name="apri_use_html_email" id="input-use-html-email" class="form-control">
									<?php if ($apri_use_html_email) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								  </select>
								</div>
							</div>								    
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-subject-<?php echo $language['language_id']; ?>"><?php echo $entry_mail_subject; ?></label>
								<div class="col-sm-9">
								  <input type="text" name="apri_mail[<?php echo $language['language_id']; ?>][subject]" value="<?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['subject'] : ''; ?>" placeholder="<?php echo $entry_mail_subject; ?>" id="input-subject-<?php echo $language['language_id']; ?>" class="form-control" />
								  <?php if (isset($error_mail_subject[$language['language_id']])) { ?>
								  <div class="text-danger"><?php echo $error_mail_subject[$language['language_id']]; ?></div>
								  <?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-message-<?php echo $language['language_id']; ?>"><?php echo $entry_mail_message; ?></label>
								<div class="col-sm-9">
								  <textarea name="apri_mail[<?php echo $language['language_id']; ?>][message]" placeholder="<?php echo $entry_mail_message; ?>" id="input-message-<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['message'] : ''; ?></textarea>
								  <?php if (isset($error_mail_message[$language['language_id']])) { ?>
								  <div class="text-danger"><?php echo $error_mail_message[$language['language_id']]; ?></div>
								  <?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-log-subject-<?php echo $language['language_id']; ?>"><?php echo $entry_mail_log_subject; ?></label>
								<div class="col-sm-9">
								  <input type="text" name="apri_mail[<?php echo $language['language_id']; ?>][log_subject]" value="<?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['log_subject'] : ''; ?>" placeholder="<?php echo $entry_mail_log_subject; ?>" id="input-log-subject-<?php echo $language['language_id']; ?>" class="form-control" />
								  <?php if (isset($error_mail_log_subject[$language['language_id']])) { ?>
								  <div class="text-danger"><?php echo $error_mail_log_subject[$language['language_id']]; ?></div>
								  <?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-log-message-<?php echo $language['language_id']; ?>"><?php echo $entry_mail_log_message; ?></label>
								<div class="col-sm-9">
								  <textarea name="apri_mail[<?php echo $language['language_id']; ?>][log_message]" placeholder="<?php echo $entry_mail_log_message; ?>" id="input-log-message-<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($apri_mail[$language['language_id']]) ? $apri_mail[$language['language_id']]['log_message'] : ''; ?></textarea>
								  <?php if (isset($error_mail_log_message[$language['language_id']])) { ?>
								  <div class="text-danger"><?php echo $error_mail_log_message[$language['language_id']]; ?></div>
								  <?php } ?>
								</div>
							</div>
						</div>		
						<?php } ?>						
					</div>
				</div>
			</div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>  
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

//$('#tabs li:first-child a').tab('show');
$('#language li:first-child a').tab('show');
//--></script>
<?php echo $footer; ?>