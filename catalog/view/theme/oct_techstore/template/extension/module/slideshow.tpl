<div class="wrap mb-wrap">
	<div id="slideshow<?php echo $module; ?>" class="main-slider <?php if (count($banners) > 1) { ?>owl-carousel<?php } ?> default-slideshow oct-slideshow-box <?php echo (count($banners) == 1)?'one_banner':'' ?> <?php if ($hidefilter) { ?>hidefilter<? } ?>" style="opacity: 1;">
		<?php $i=0; foreach ($banners as $banner) { ?>
			<div class="item <?php if ($banner['link'] != 'init_callback_popup') { ?>banner-promo-single<?php } ?>" data-gtm-banner='{"id": "<?php echo $banner['banner_analytics_id']; ?>", "name": "<?php echo $banner['title']; ?>", "creative": "InMainSlideshowCreative", "position": "slot<?php echo $i; ?>", "url": "<?php echo $banner['link']; ?>"}'>
				<?php if ($banner['link']) { ?>
					<?php if ($banner['link'] == 'init_callback_popup') { ?>
						<a onclick="get_oct_popup_call_phone();" title="<?php echo $banner['title']; ?>">
							<img src="<?php echo $banner['image']; ?>" class="img-responsive" width="1230" height="380" alt="<?php echo $banner['title']; ?>" loading="lazy"/>
						</a>
						<?php } else { ?>
						<a href="<?php echo $banner['link']; ?>" title="<?php echo $banner['title']; ?>">
							<img src="<?php echo $banner['image']; ?>" class="img-responsive" width="1230" height="380" alt="<?php echo $banner['title']; ?>" loading="lazy"/>
						</a>
					<?php } ?>
					<?php } else { ?>				
					<img src="<?php echo $banner['image']; ?>" class="img-responsive" width="1230" height="380" alt="<?php echo $banner['title']; ?>" loading="lazy"/>
					
				<?php } ?>
			</div>
		<?php $i++; } ?>
	</div>
	<?php if (count($banners) > 1) { ?>
		<script><!--
			$('#slideshow<?php echo $module; ?>').owlCarousel({
				items: 1,
				autoplay:true,
				autoplayTimeout:5000,
				autoHeight:true,
				singleItem: true,
				nav:true,
			    dots: false,
			    navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>']	
			});
		--></script>
	<? } ?>
</div>