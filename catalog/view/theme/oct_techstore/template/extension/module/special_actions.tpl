<?php if (count($specials) >= 2) { ?>
	<div class="promotions-content-wrap">
		<div class="wrap df fdc">
			<div class="oct-carousel-header header df aic jcc">
				<p class="title_module">
					<?php echo $heading_title; ?>
				</p>
			</div>
		</div>
		<div class="promotions-content">	
			<div id="oct-special-actions-0" class="owl-carousel owl-theme shadow">
				<?php $i=0; foreach ($specials as $special) { $i++; ?>
					<div class="item <?php if (!$special['active']) { ?>deactivated<? } ?>">
						<?php if($special['thumb']) { ?>
							<div class="image-promotions">
								<a href="<?php echo $special['href']; ?>"><img src="<?php echo $special['thumb']; ?>" alt="<?php echo $special['title']; ?>" title="<?php echo $special['title']; ?>" class="img-responsive" loading="lazy" /></a>
									<?php if ($special['active'] && $special['dateDiff']) { ?>
										<div class="promotion-days-left"><?php echo $text_special; ?> <span><?php echo $special['dateDiff']; ?></span> <?php echo $text_days; ?></div>
									<? } else { ?>
										<span class="promotion-days-left action_deactivated"><?php echo $text_ended; ?></span>
									<? } ?>
							</div>
							
						<?php } ?>
						<div class="inform-promotions">
							<h3 class="title">
								<?php echo $special['title']; ?>
							</h3>
							<p class="description"><?php echo $special['description']; ?></p>
							<a href="<?php echo $special['href']; ?>" class="webfun_load_more_product"><?php echo $text_more; ?></a>
						</div>		
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php if (count($specials) >= 1){ ?>
		<script>
			$(function() {
				$('#oct-special-actions-0').owlCarousel({
						
				 	loop:true,
				    margin:30,
				    nav:true,
				    dots: true,
				    navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>'],	
				    responsive:{
				        0:{
				            items:2,
				            margin: 0
				        },
				        600:{
				            items:2
				        },
				        1000:{
				            items:3
				        },
				        1400:{
				            items:5
				        }
				    }
				});
			});
		</script>
		
	<?php } ?>
<?php } ?>	