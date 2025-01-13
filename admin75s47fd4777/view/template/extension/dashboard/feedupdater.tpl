<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-tabs"></i> Фиды данных</h3>
	</div>
	<ul class="list-group">
		<li class="list-group-item" style="height:50px;">
			<?php if ($status == 'idle') { ?>
				<button class="btn btn-success pull-left" id="feedupdaterstatus">Ожидание</button>
				<?php } else { ?>
				<button class="btn btn-danger pull-left" id="feedupdaterstatus">Работаем, <?php echo $status; ?></button>
			<?php } ?>
			
			<button type="button" class="btn btn-primary pull-right" <?php if ($status != 'idle') { ?>style="display:none;"<?php } ?> id="feedupdatercommand">Запустить</button>
			
		</li>
		<?php if ($files) { ?>
			<?php foreach ($files as $file => $time) { ?>
				<li class="list-group-item"><?php echo $file; ?><br />
				<small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $time; ?></small></li>
			<?php } ?>
			<?php } else { ?>
			<li class="list-group-item text-center"><?php echo $text_no_results; ?></li>
		<?php } ?>
	</ul>
</div>
<script type="text/javascript">
	
	function updateStatus(){
		
		$.ajax({
			url: "<?php echo str_replace('&amp;', '&', $update_url); ?>",
			dataType : 'text',
			success: function(text){
				let _el = $('#feedupdaterstatus');
				let _btn = $('#feedupdatercommand');
				
				if (text == 'Ожидание'){
					_el.removeClass('btn-danger btn-success').addClass('btn-success');
					_el.text(text);
					_btn.show();
					} else {
					_el.removeClass('btn-danger btn-success').addClass('btn-danger');
					_el.text(text);
					_btn.hide();
				}
			}
		});
		
	}
	
	$(document).ready(function() {
		$('#feedupdatercommand').click(function(){
			$.get("<?php echo str_replace('&amp;', '&', $command_url); ?>", function(){ updateStatus(); });
		});
		
		setInterval(
		function() { updateStatus(); }, 4000);   			
	});
</script>