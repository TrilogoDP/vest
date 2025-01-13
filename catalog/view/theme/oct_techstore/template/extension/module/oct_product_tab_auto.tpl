<style type="text/css">
	.tab-content .tab-pane{
	position: relative;
	}
	.prev_Slide{
	background: #fff;
	border: 1px solid #eaecee;
	transition: .15s ease-in-out;
	}
	.next_Slide{
	background: #fff;
	border: 1px solid #eaecee;
	transition: .15s ease-in-out;
	}
	.prev_Slide:hover,
	.next_Slide:hover{
	background: #2b3743;
	color: white;
	}
	.glyphicon-chevron-left::before{
		content: "\f104" !important; 
		font: normal normal normal 25px/1 FontAwesome;
	}
	.glyphicon-chevron-right::before {
	    content: "\f105" !important;
	    font: normal normal normal 25px/1 FontAwesome;
	}
	.scrtabs-tab-container{
		display: flex;
	    margin: 0 auto;
	    max-width: 1600px;
	    padding: 0 20px;
	}
	.scrtabs-tab-scroll-arrow{
		border: 1px solid #ddd !important;
		text-align: center;
		line-height: 45px;
		padding-top: 0 !important;
		color: #2b3743 
	}
	.scrtabs-tab-scroll-arrow .glyphicon{
		color: #2b3743 ;
	}
	.scrtabs-tab-scroll-arrow:hover{
		background-color: #2b3743 !important;
	}
	.scrtabs-tab-scroll-arrow:hover .glyphicon{
		color: #fff !important
	}
	.scrtabs-tab-scroll-arrow.scrtabs-disable,
	.scrtabs-tab-scroll-arrow.scrtabs-disable:hover{
		opacity: 0.5;
	}
	@media screen and (max-width: 560px) {
		.scroling_tab.nav-tabs {
		    display: flex !important;
		    height: 42px;
		}
		.tab-contents-mobile .tab-pane{
			padding-bottom: 1px;
		}
		.tab-contents-mobile > div.title{
			display: flex;
			align-items: center;
			justify-content: center;
			margin-top: 10px;
			margin-bottom: 10px;
			color: #fff;
			font-size: 16px;
		}
		.tab-contents-mobile > div.title i{
			margin-right: 5px;	
		}
	}
</style>
<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/js/jquery.scrolling-tabs.min.css">
<div class="our_product bg-light-yellow">
	<div class="header df aic jcc">
		<p class="title_module"><?php echo $heading_title; ?></p>
	</div>

	<?php if ($isMobile) { ?>

		<div class="product-tab-row mob-v">
			<div class="oct-product-tab">
				<div class="tab-contents tab-contents-mobile">
					<?php unset($tab); $ct = 0; foreach ($tabs as $tab) { ?>
						<?php if ($tab['is'] != 0) { ?>
						<div class="title">
							<span><?php echo $tab['tab']; ?></span>
						</div>	
						<div id="tab-<? echo $tab['index']; ?>-<?php echo $module; ?>" class="tab-pane active">
							<ul class="nav nav-tabs scroling_tab">
								<?php $ci = 0; foreach ($tab['data'] as $category) { ?>
									<li <?php if ($ci == 0) { ?>class="active"<?php } ?>><a class="nav-link" href="#tab-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" data-toggle="tab"><span><?php echo $category['category']['name']; ?></span></a></li>
									
								<?php $ci++; } ?>

							</ul>
							<!-- <button class="prev_Slide" type="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
							<button class="next_Slide" type="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>	 -->
							<div class="tab-content" style="min-height:430px;">
								<?php unset($category); $ci = 0; foreach ($tab['data'] as $category) { ?>
									<div id="tab-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" class="tab-pane <?php if ($ci == 0) { ?>active<?php } ?>">
										<div id="owl-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" class="owl-carousel owl-theme">
											<?php foreach($category['products'] as $product) { ?>
												<?php include(DIR_TEMPLATEINCLUDE . 'structured/product_single.tpl'); ?>
											<?php } ?>
										</div>
									</div>
								<?php $ci++; } ?>
							</div>
						</div>	  
						<?php } ?>
					<?php $ct++; } ?>
				</div>
			</div>
		</div>	
	<?php } else { ?>
		<div class="product-tab-row">
			<div class="oct-product-tab">
				
				<ul class="wrap nav nav-tabs">
					<?php $ct = 0; foreach ($tabs as $tab) { ?>
						<?php if ($tab['is'] != 0) { ?>
						<li <?php if ($ct == 0) { ?>class="active"<?php } ?>>
							<a href="#tab-<? echo $tab['index']; ?>-<?php echo $module; ?>" data-toggle="tab" class="top-tab">
								<span><?php echo $tab['tab']; ?></span>
							</a>
						</li>	
						<?php } ?>
					<?php $ct++; } ?>
				</ul>
				
				<div class="tab-content" style="min-height:430px;">
					<?php unset($tab); $ct = 0; foreach ($tabs as $tab) { ?>
						<?php if ($tab['is'] != 0) { ?>
						<div id="tab-<? echo $tab['index']; ?>-<?php echo $module; ?>" class="tab-pane <?php if ($ct == 0) { ?>active<?php } ?>">
							<ul class="nav nav-tabs scroling_tab">
								<?php $ci = 0; foreach ($tab['data'] as $category) { ?>
									<li <?php if ($ci == 0) { ?>class="active"<?php } ?>><a class="nav-link" href="#tab-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" data-toggle="tab"><span><?php echo $category['category']['name']; ?></span></a></li>
									
								<?php $ci++; } ?>

							</ul>
							<!-- <button class="prev_Slide" type="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>
							<button class="next_Slide" type="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>	 -->
							<div class="tab-content" style="min-height:430px;">
								<?php unset($category); $ci = 0; foreach ($tab['data'] as $category) { ?>
									<div id="tab-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" class="tab-pane <?php if ($ci == 0) { ?>active<?php } ?>">
										<div id="owl-<? echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>" class="shadow owl-carousel owl-theme">
											<?php foreach($category['products'] as $product) { ?>
												<?php include(DIR_TEMPLATEINCLUDE . 'structured/product_single.tpl'); ?>
											<?php } ?>
										</div>
									</div>
								<?php $ci++; } ?>
							</div>
						</div>	  
						<?php } ?>
					<?php $ct++; } ?>
				</div>
			</div>
		</div>	
	<?php } ?>
</div>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.6.1/dist/jquery.scrolling-tabs.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jquery-bootstrap-scrolling-tabs@2.6.1/dist/jquery.scrolling-tabs.min.css"> -->

<!-- <script src="https://vest.in.ua/catalog/view/theme/oct_techstore/js/scrolling-tab.js"></script> -->
<script src="https://vest.in.ua/catalog/view/theme/oct_techstore/js/jquery.scrolling-tabs.min.js"></script>	
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
	function initialize_owl(el) {
		el.owlCarousel({
			responsive:{
		        0:{
		        	margin:0,
		            items:2
		        },
		        600:{
		        	margin:0,
		            items:2
		        },
		        1000:{
		        	margin:30,
		            items:3
		        },
		        1400:{
		        	margin:30,
		            items:5
		        }
		    },
			autoPlay: true,
			margin:30,
			stopOnHover:true,
			smartSpeed: 800,
			loop: true,
			nav:true,
		    dots: true,
		    navText: ['<span class="arrow arrow-prev"></span>', '<span class="arrow arrow-next"></span>']
		});
	}
	
	function destroy_owl(el) {
		el.owlCarousel('destroy'); 
	}
	
	$(function () {
		
		<? /* Инициализируем первую карусель каждого таба */ ?>
		<? foreach ($tabs as $tab) { ?>
			<?php if ($tab['is'] != 0) { ?>
			<?php unset($category); foreach ($tab['data'] as $category) { ?>
				initialize_owl($('#owl-<?php echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>'));
			<?php break; } ?>
			<?php } ?>
		<?php } ?>
		
		<? /* Вешаем обработчики на табы второго уровня */ ?>
		<? foreach ($tabs as $tab) { ?>
			<?php if ($tab['is'] != 0) { ?>
			<?php unset($category); foreach ($tab['data'] as $category) { ?>
				
				$('a[href="'+ '#tab-<?php echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>' + '"]').on('shown.bs.tab', function () {				
					initialize_owl($('#owl-<?php echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>'));
					}).on('hide.bs.tab', function () {
					destroy_owl($('#owl-<?php echo $tab['index']; ?>-category-<?php echo $category['category']['category_id']; ?>'));
				});
				
				
				
			<?php } ?>
			<?php } ?>
		<?php } ?>
		
		
	
});
	
	$('.tab-pane .scroling_tab').scrollingTabs({
      	enableSwiping: true,
      	disableScrollArrowsOnFullyScrolled: true,
  	  	tabClickHandler: function (e) {
		   
   
	    	setTimeout(() => {
	    		$('.tab-pane .scroling_tab').scrollingTabs('scrollToActiveTab');
	    	}, 10);
	  	},
	})


	$('.top-tab').each(function(){
		$(this).on('click', function(){
			setTimeout(() => {
	      		$('.tab-pane .scroling_tab').scrollingTabs('refresh');
	    	}, 10);
		});
	});
	
</script>