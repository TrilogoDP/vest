<?php echo $header; ?>
<style>
	#slideshow0 .collapse-wrap{
	    left: -330px;
	    top: -8px;
        display: flex;
        position: absolute;
	    background: #FFFEFE;
	    box-shadow: 0px 25px 59px rgb(0 44 62 / 12%);
	    border-radius: 0px 0px 12px 12px;
	    width: 300px;
	    z-index: 1;
	}

	#slideshow0 .collapse-wrap .list-menu{
	    display: block;
	    width: 100%;
	    position: relative;
	}

	#slideshow0 .collapse-wrap .second-level-li a{
	    padding: 10.6px 9px;
	    font-size: 16px;
	    line-height: 22px;
	    position: relative;
	    display: flex;
	    align-items: center;
	    justify-content: flex-start;
	    gap: 10px;
	    transition: .15s ease-in-out;
	}
	#slideshow0 .collapse-wrap .second-level-li > a:hover{
	    background: #f2fbfe;
	}
	#slideshow0 .collapse-wrap .second-level-li.special a svg,
	#slideshow0 .collapse-wrap .second-level-li a svg:first-child{
	    margin-right: 0px;
	    width: 18px;
	    min-width: 24px;
	    height: 18px;
	    display: flex;
	    align-items: center;
	    justify-content: center;
	}
	#slideshow0 .collapse-wrap .second-level-li:not(.special) a svg:last-child{
	    margin-left: auto;
	    width: 8px;
	    height: 12px;
	    min-width: 8px;
	    margin-right: 0;
	}
	#slideshow0 .collapse-wrap .megamenu-ischild li:not(:last-child) > a::before,
	#slideshow0 .collapse-wrap .second-level-li:not(:last-child) > a::before{
	    content: '';
	    background: linear-gradient(90deg,rgba(129,209,239,0) 0%,rgb(129 209 239 / 40%) 50%,rgba(129,209,239,0) 100%);
	    position: absolute;
	    left: 0;
	    bottom: 0;
	    right: 0;
	    margin: auto;
	    height: 1px;
	    width: 80%;
	}
	#slideshow0 .collapse-wrap .second-level-li.has-child .arrow{
	    transform: rotate(-90deg);
	}


	#slideshow0 .collapse-wrap .megamenu-ischild{
		display: none;
	    position: absolute;
	    z-index: 11;
	    height: 100%;
	    background: #FFFEFE;
	    box-shadow: 0px 25px 59px rgb(0 44 62 / 12%);
	    border-radius: 0 0 12px 12px;
	    overflow: hidden;
	}
	#slideshow0 .collapse-wrap .megamenu-ischild > ul{
	    columns: 4;
	    position: relative;
	    min-height: 100%;
	}
	#slideshow0 .collapse-wrap .megamenu-ischild > ul > li{

	    break-inside: avoid-column;
	/*    overflow: hidden;*/
	}
	#slideshow0 .collapse-wrap .second-level-li.has-child:hover .megamenu-ischild{
	    display: flex;
	}

	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li{
	    padding: 5px  11px 5px;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > a{
	    font-size: 16px;
	    line-height: 21px;
	    position: relative;
	    display: flex;
	    align-items: center;
	    justify-content: flex-start;
	    padding: 0;
	    transition: .15s ease-in-out;
	    margin-bottom: 5px;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > a:hover{
	    color: #6cbbb0;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li .has-child{
	    position: relative;
	}

	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li .has-child ul{
	    display: none;
	    position: absolute;
	    left: 100%;
	    background: #fff;
	    z-index: 1;
	    top: 0;
	    padding: 5px 15px;
	    box-shadow: 0px 25px 59px rgb(0 44 62 / 12%);
	    border-radius: 12px;
	    width: 200px;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li .has-child:hover ul{
	    display: block;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > ul li a,
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > a{
	    font-size: 14px;
	    line-height: 21px;
	    padding: 0;
	    color: #39aafd;
	    transition: .15s ease-in-out;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > ul li a:hover,
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > a:hover{
	    color: #6cbbb0;
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul{
	/*    padding-left: 10px;*/
	}
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild ul li a::before,
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > a::before,
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > a::before,
	#slideshow0 .collapse-wrap .second-level-li .megamenu-ischild > ul > li > ul > li > a::before{
	    display: none !important;
	}
</style>
<?php
	// if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '80.92.235.22') {
		echo '<div id="oct-mobile-search1" style="display: none;">
				<div class="input-group">
					<input type="text" name="webfun_search1" class="form-control" placeholder="' . $oct_techstore_msearch . '">
					<span class="input-group-btn">
						<input type="button" id="oct-m-search-button1" value="' . $oct_techstore_msearchb . '" class="oct-button">
					</span>
				</div>
				<div class="oct-msearchresults1" id="searchm1">
					<div id="msearchresults1"></div>
				</div>
			</div>';
	// }
?>
<?php echo $content_top; ?>
<?php echo $content_bottom; ?>

<script>
	$(document).ready(function () {
		let mmenuClone = $('#menu > .collapse-wrap').clone();
		mmenuClone.appendTo('#slideshow0');

		setTimeout(function() {

            $('#slideshow0 .second-level-li.has-child').each(function(index, el) {
		        $(el).hover(function() {
		            var $row = $(el),
		            submenuId = $row.find('.megamenu-ischild'),
		            $submenu = $(submenuId),
		            width = $('.header-second-line .wrap').width();
		            
		            
		            $submenu.css({
		                display: "block",
		                top: 0,
		                width: width - 300,
		                left: 300,
		                // height: height - 4  
		            });
		            
		            $row.find("a").addClass("maintainHover");
		            $row.addClass("open_menu");
		        }, function(){
		            var $row = $(el),
		            submenuId = $row.find('.megamenu-ischild'),
		            $submenu = $(submenuId);
		            
		            $submenu.css("display", "none");
		            $row.find("a").removeClass("maintainHover");
		            $row.removeClass("open_menu");
		        });
		        
		    });
        }, 500);
		
	});
</script>
<?php echo $footer; ?>