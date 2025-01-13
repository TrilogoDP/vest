<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_base_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    <div class="container" style="width: auto !important;">
     <?php if ($error_warning) { ?>
     <div class="warning"><?php echo $error_warning; ?></div>
     <?php } ?>
     <?php if ($success) { ?>
     <div class="success"><?php echo $success; ?></div>
     <?php } ?>
     <div class="panel panel-default">
     <div class="panel-body">
      <div class="content">
       <form action="<?php echo $start; ?>" method="post" enctype="multipart/form-data" id="form">
          <?php echo $entry_description; ?>
          <?php echo $entry_restore; ?>
          <input type="file" name="xmlfile" />
       </form>
      <div class="text-right">
		<a onclick="$('#form').submit();" data-toggle="tooltip" class="btn btn-primary" type="button"><i>Start / Continue</i></a>
	  </div>
      </div>
     </div>
     </div>
    </div>
</div>
<?php echo $footer; ?>