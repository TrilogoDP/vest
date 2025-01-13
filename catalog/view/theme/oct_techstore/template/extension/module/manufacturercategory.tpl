<div class="ocf-offcanvas ocfilter-mobile hidden-sm hidden-md hidden-lg">
	<div class="ocfilter-mobile-handle">
		<button type="button" class="btn btn-primary" data-toggle="offcanvas"><i class="fa fa-filter"></i></button>
	</div>
	<div class="ocf-offcanvas-body"></div>
</div>
<style>
	.ocf-option-values small.badge {
    border-radius: .25em;
    background-color: #257985;
	color:  #fff;
    float: right;
    line-height: 14px;
    padding: .1em .6em .1em;
    font-size: 9px;
    font-weight: 400;
    vertical-align: middle;
	}
	.ocf-option-values{
	padding:10px;
	}
</style>
<div class="panel ocfilter panel-default" id="ocfilter">
	<div class="panel-heading"><?php echo $heading_title; ?></div>
	<div class="hidden" id="ocfilter-button">
		<button class="btn btn-primary disabled" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Загрузка.."></button>
	</div>
	<div class="">
		<div class="box">
			<div class="box-content" id="sstore-3-level">
				<?php foreach ($categories as $category) { ?>
					<div class="row ocf-option-values">
						<div class="col-xs-10"><a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a></div>
						<div class="col-xs-2"><small class="badge"><?php echo $category['count']; ?></small></div>
					</div>				
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
	$(function() {
		$('body').append($('.ocfilter-mobile').remove().get(0).outerHTML);
		
		var options = {
			mobile: $('.ocfilter-mobile').is(':visible'),
			php: {			
			},
			text: {				
			}
		};
		
		if (options.mobile) {
			$('.ocf-offcanvas-body').html($('#ocfilter').remove().get(0).outerHTML);
		}
		
		$('[data-toggle="offcanvas"]').on('click', function(e) {
			$(this).toggleClass('active');
			$('body').toggleClass('modal-open');
			$('.ocfilter-mobile').toggleClass('active');
		});			
	});
//--></script>