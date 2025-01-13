<div class="ocf-offcanvas ocfilter-mobile hidden-sm hidden-md hidden-lg">
  <div class="ocfilter-mobile-handle">
    <button type="button" class="btn btn-primary" data-toggle="offcanvas">
      
        <span><?php echo $text_filter; ?></span>
        <i class="fa fa-filter"></i></button>
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
<script>
	function do_ajax_content_reload(elem){
		console.log('ajaxable-container-reloader clicked');		
		$('#sstore-3-level').append('<div class="webfun_ocfilter_preloader"></div>');
		$('#res-products .row').eq(0).append('<div class="webfun_ocfilter_preloader"></div>');
		
		var display = localStorage.getItem('display');
		
		$( "#res-products-wrap" ).load( elem.attr('data-href') + " #res-products" , function() { if (display == 'list'){ $('#list-view').trigger('click'); } else {   $('#grid-view').trigger('click'); };    $('.webfun_ocfilter_preloader').remove();
			window.history.pushState({}, $('h1').html(), elem.attr('data-href').replace(/&amp;/g, '&'));
			$("img.lazy").lazyload({
				effect : "fadeIn"
			});
		});
		return false;
	};
	
</script>
<div class="panel ocfilter panel-default" id="ocfilter">
	<div class="panel-heading"><?php echo $heading_title; ?></div>
	<div class="hidden" id="ocfilter-button">
		<button class="btn btn-primary disabled" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Загрузка.."></button>
	</div>
	<div class="">
		<div class="box">
			<div class="box-content" id="sstore-3-level">
				<?php foreach ($categories as $category) { ?>
					<div class="ocf-option-values">
						<label>
							<a class="ajaxable-container-reloader" onclick="do_ajax_content_reload($(this)); return false;"  data-href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
							<small class="badge"><?php echo $category['count']; ?></small>
						</label>
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

	    setTimeout(function() {
	        $('#ocfilter').ocfilter(options);
	    }, 1);

	    function toogleClass(){
	        $('[data-toggle="offcanvas"]').addClass('handler'); 
	        setTimeout(function() {
	            $('[data-toggle="offcanvas"]').removeClass('handler'); 
	        }, 5000);
	    }
	        setTimeout(function(){
			toogleClass();
			}, 1000)

			setInterval(function(){
			toogleClass();
			}, 60000)
		
	});
//--></script>