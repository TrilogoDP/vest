<?php if ($gifts) { ?>
	<div class="row">
		<div class="col-sm-12">				
			<?php foreach ($gifts as $gift) { ?>
			<div class="row">			
				<div class="col-sm-2"><img src="<? echo $gift['image']; ?>" /></div>
				<div class="col-sm-10"><? echo $gift['name']; ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>