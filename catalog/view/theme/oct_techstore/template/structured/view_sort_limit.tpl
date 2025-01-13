<?php if ($products) { ?>
	<div class="sort-container" data-path="<?php if (isset($webfun_path)) { echo $webfun_path; } ?>" <?php if (isset($oct_techstore_data['cat_sorttype']) && $oct_techstore_data['cat_sorttype'] != 'on') { ?>style="display:none !important;"<?php } ?>>
		
		<div class="sort-right" style="margin-left: auto;">
			<div class="sort_nav_select">
				<?php foreach ($sorts as $sorts1) { ?>
					<?php if ($sorts1['value'] == $sort . '-' . $order) { ?>
						<span><?php echo $sorts1['text']; ?></span>					
					<?php } ?>
				<?php } ?>
				<div class="sort_nav_select_wrap form-group input-group input-group-sm input-sort-div">
					<?php unset($sorts1); foreach ($sorts as $sorts2) { ?>
						<?php if ($sorts2['value'] == $sort . '-' . $order) { ?>
							<a href="<?php echo $sorts2['href']; ?>" class="selected"><?php echo $sorts2['text']; ?></a>
						<?php } else { ?>
							<a href="<?php echo $sorts2['href']; ?>"><?php echo $sorts2['text']; ?></a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>	
		</div>
		
	</div>
	<script>
		var sort_select = document.querySelector('.sort_nav_select');
		sort_select.addEventListener('click', function(e){
			this.classList.add('open');
		});

		$(document).mouseup(function (e){
			var popupSelect = $('.sort_nav_select, .sort_nav_select_wrap');
			if(!popupSelect.is(e.target) && popupSelect.has(e.target).length == 0){
				popupSelect.removeClass('open');
			}
		})

	</script>
<?php } ?>
