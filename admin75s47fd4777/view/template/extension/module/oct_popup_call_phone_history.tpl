<?php if ($histories) { ?>
	<div class="btn-group">
		<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-trash-o"></i> <?php echo $button_delete_menu; ?> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a onclick="delete_all();"><?php echo $button_delete_all; ?></a></li>
			<li><a onclick="delete_all_selected();"><?php echo $button_delete_selected; ?></a></li>
		</ul>
	</div>
	<br/><br/>
<?php } ?>
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				<td class="text-left" >Имя</td>
				<td class="text-left" >Телефон</td>
				<td class="text-left" >Источник</td>
				<td class="text-left" >Комментарий</td>
				<td class="text-left" >Был ли звонок</td>
				<td class="text-left"><?php echo $column_date_added; ?></td>
				<td class="text-center" ><?php echo $column_action; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php if ($histories) { ?>
				<?php foreach ($histories as $history) { ?>
					<tr>
						<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $history['request_id']; ?>" /></td>
						<td class="text-left">
							<?php /* if ($history['info']) { ?>
								<?php foreach ($history['info'] as $info) { ?>
								<p><strong><?php echo $info['name']; ?>:</strong> <?php echo $info['value']; ?></p>
								<?php } ?>
							<?php } */ ?> 
							<b><?php echo $history['name']; ?></b>
						</td>
						<td class="text-left">
							<b><?php echo $history['telephone']; ?></b>
						</td>
						<td class="text-left" style="max-width:300px;">
							<small><a href="<?php echo $history['referer']; ?>" target="_blank"><?php echo $history['referer']; ?></a></small>
						</td>
						<td class="text-left" style="max-width:400px;">
							<?php if ($history['comment']) { ?>
								<blockquote><small><?php echo $history['comment']; ?></small></blockquote>
							<? } ?>
						</td>
						<td class="text-left" width="1">
							<?php if ($history['binotel_call_id']) { ?>
								<span class="label label-success">Да, id: <?php echo $history['binotel_call_id']; ?></span>
							<?php } else { ?>
								<span class="label label-danger">Нет, надо перезвонить</span>
							<?php } ?>
						</td>
						<td class="text-left" width="1"><?php echo $history['date_added']; ?></td>
						<td class="text-center" width="1">
							<a onclick="delete_selected(<?php echo $history['request_id']; ?>);" data-toggle="tooltip" title="" class="btn btn-warning" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
	<div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>