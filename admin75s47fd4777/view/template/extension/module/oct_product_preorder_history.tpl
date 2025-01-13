<?php if ($histories) { ?>
	<div class="btn-group">
		<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-trash-o"></i> <?php echo $button_delete_menu; ?> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">		
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
				<td class="text-left" colspan="2">Товар</td>
				<td class="text-left">Телефон</td>	
				<td class="text-left">Заметка</td>
				<td class="text-left">Дата</td>
				<td class="text-left">Сообщение</td>
				<td class="text-left">Отправлено</td>
			<? /*	<td class="text-center"></td> */ ?>
			</tr>
		</thead>
		<tbody>
			<?php if ($histories) { ?>
				<?php foreach ($histories as $history) { ?>
					<tr>
						<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $history['request_id']; ?>" /></td>
						<td class="text-left" width="1">
						<img class="img-rounded" data-toggle="tooltip" data-html="true" src="<? echo $history['thumb']; ?>" title="<img src='<? echo $history['image']; ?>' /><br /><? echo $history['product']['name']; ?>" /></div>
					</td>
					<td class="text-left">			
						<? echo $history['product']['name']; ?><br />
						<kbd><? echo $history['product']['product_id']; ?></kbd>
						<?php if ($history['product']['quantity']) { ?>
							<span class="label label-success">Наличие: <? echo $history['product']['quantity']; ?></span>
							<?php } else { ?>
							<span class="label label-danger">Наличие: <? echo $history['product']['quantity']; ?></span>
						<?php } ?>
					</td> 
					<td class="text-left">
						<b><?php echo $history['telephone']; ?></b><br />
						<img src="language/<?php echo $history['language']['code']; ?>/<?php echo $history['language']['code']; ?>.png" title="<?php echo $history['language']['name']; ?>" />
					</td>
					<td class="text-left">		  
						<textarea name="note" class="form-control" rows=2 style="max-height:40px;max-width:70%;display:inline-block;float:left;"><?php echo $history['note']; ?></textarea>		  
						<a onclick="update_note(<?php echo $history['request_id']; ?>, $(this).prev().val());" class="btn btn-primary" style="display:inline-block;float:left;margin-left:10px;"><i class="fa fa-edit"></i></a>
					</td>
					<td class="text-left">
						<small><?php echo $history['date_added']; ?><br /><?php echo $history['time_added']; ?></small>
					</td>
					<td class="text-left">
						<?php if ($history['sms_text']) { ?>						
							<small><code><?php echo $history['sms_text']; ?></code></small>
						<?php } ?>
					</td>
					<td class="text-left">
						<?php if ($history['sms_text']) { ?>						
							<small><?php echo $history['sms_date_added']; ?><br /><?php echo $history['sms_time_added']; ?></small>
						<?php } ?>
					</td>
				<? /*
					<td class="text-center">
						<a onclick="delete_selected(<?php echo $history['request_id']; ?>);" data-toggle="tooltip" title="" class="btn btn-warning" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
					</td>
				*/ ?>
				</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
	<div class="col-sm-6 text-right"><?php echo $results; ?></div>
	</div>				