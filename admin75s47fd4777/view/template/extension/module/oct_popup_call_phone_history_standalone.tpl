<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">					
			<button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<div id="history"></div>
	</div>
</div>
<script type="text/javascript">
	$('#history').delegate('.pagination a', 'click', function(e) {
		e.preventDefault();
		$('#history').load(this.href);
	});
	$('#history').load('index.php?route=extension/module/oct_popup_call_phone/history&token=<?php echo $token; ?>');
	
	function delete_selected(request_id) {
		$.ajax({
			type: 'post',
			url:  'index.php?route=extension/module/oct_popup_call_phone/delete_selected&token=<?php echo $token; ?>&delete=' + request_id,
			dataType: 'json',
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_popup_call_phone/history&token=<?php echo $token; ?>');
			}
		});
	}
	function delete_all() {
		$.ajax({
			type: 'get',
			url:  'index.php?route=extension/module/oct_popup_call_phone/delete_all&token=<?php echo $token; ?>',
			dataType: 'json',
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_popup_call_phone/history&token=<?php echo $token; ?>');
			}
		});
	}
	function delete_all_selected() {
		$.ajax({
			type: 'post',
			url:  'index.php?route=extension/module/oct_popup_call_phone/delete_all_selected&token=<?php echo $token; ?>',
			data: $('#history input[type=\'checkbox\']:checked'),
			dataType: 'json',
			success: function(json) {
				$('#history').load('index.php?route=extension/module/oct_popup_call_phone/history&token=<?php echo $token; ?>');
			}
		});
	}
</script>
<?php echo $footer; ?>