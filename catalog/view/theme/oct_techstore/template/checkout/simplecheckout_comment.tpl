<div class="simplecheckout-block" id="simplecheckout_comment">
	<?php if ($display_header) { ?>
		<div class="checkout-heading panel-heading"><?php echo $label ?></div>
	<?php } ?>
	<div class="simplecheckout-block-content">
		<textarea class="form-control" name="comment" id="comment" placeholder="<?php echo $placeholder ?>" data-reload-payment-form="true"><?php echo $comment ?></textarea>
	</div>
	<?php if ($show_donotcall) { ?>
		<div class="simplecheckout-block-content text-right show_donotcall_wrap" style="margin-top:7px;">
			
			<div class="pretty p-default p-bigger">
				<input type="checkbox" name="donotcall" value="1" <?php if ($donotcall) { ?>checked="checked"<?php } ?>>
				<div class="state p-success control-label">
					<label style="font-size:14px;"><span><?php echo $text_donotcall; ?></span></label>
				</div>
			</div>
			
			
		</div>	
	<?php } ?>
</div>