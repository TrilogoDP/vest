<?php if ($options || $show_price) { ?>
<div class="ocf-offcanvas ocfilter-mobile hidden-sm hidden-md hidden-lg 212">
    <div class="ocfilter-mobile_bg"></div>
  <div class="ocfilter-mobile-handle">
    <button type="button" class="btn btn-primary" data-toggle="offcanvas">
      
      <?php
            if ($lang == 'ru') {
                $textBtn = 'Фильтр';
            } else if ($lang == 'ua') {
                $textBtn = 'Фільтр';
            }
        ?>
        <span><?php echo $textBtn; ?></span>
        <i class="fa fa-filter"></i></button>
  </div>
  <div class="ocf-offcanvas-body"></div>
</div>

<div class="panel ocfilter panel-default" id="ocfilter">
  <div class="panel-heading"><?php echo $heading_title; ?></div>
  <div class="hidden" id="ocfilter-button">
    <button class="btn btn-primary disabled" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Загрузка.."></button>
  </div>
  <div class="list-group">
    <?php include 'selected_filter.tpl'; ?>

    <?php include 'filter_price.tpl'; ?>

    <?php include 'filter_list.tpl'; ?>
  </div>
</div>

<!-- <script src="//code.jquery.com/mobile/1.5.0-alpha.1/jquery.mobile-1.5.0-alpha.1.min.js"></script> -->
<script type="text/javascript"><!--
$(function() {
    $('body').append($('.ocfilter-mobile').remove().get(0).outerHTML);

    var options = {
        mobile: $('.ocfilter-mobile').is(':visible'),
        php: {
            searchButton : <?php echo $search_button; ?>,
            showPrice    : <?php echo $show_price; ?>,
            showCounter  : <?php echo $show_counter; ?>,
            manualPrice  : <?php echo $manual_price; ?>,
            link         : '<?php echo $link; ?>',
            path         : '<?php echo $path; ?>',
            params       : '<?php echo $params; ?>',
            index        : '<?php echo $index; ?>'
        },
        text: {
            show_all: '<?php echo $text_show_all; ?>',
            hide    : '<?php echo $text_hide; ?>',
            load    : '<?php echo $text_load; ?>',
            any     : '<?php echo $text_any; ?>',
            select  : '<?php echo $button_select; ?>'
        }
    };

    if (options.mobile) {
        $('.ocf-offcanvas-body').html($('#ocfilter').remove().get(0).outerHTML);
        // jQuery(window).on( "swipeleft", function(event) {
        //     $('[data-toggle="offcanvas"]').removeClass('active');
        //     $('body').removeClass('modal-open');
        //     $('.ocfilter-mobile').removeClass('active'); 
        // })

    }

    $('[data-toggle="offcanvas"]').on('click', function(e) {
        $(this).toggleClass('active');
        $('body').toggleClass('modal-open');
        $('.ocfilter-mobile').toggleClass('active');

    });

    $('.ocf-offcanvas.ocfilter-mobile .ocfilter-mobile_bg').on('click', function(e){
        $('.ocfilter-mobile').toggleClass('active');
    })

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
<script type="text/javascript">
   window.onload = function() {
    (function(d) {
        var
            ce = function(e, n) {
                var a = document.createEvent("CustomEvent");
                a.initCustomEvent(n, true, true, e.target);
                e.target.dispatchEvent(a);
                a = null;
                return false
            },
            nm = true,
            sp = {
                x: 0,
                y: 0
            },
            ep = {
                x: 0,
                y: 0
            },
            touch = {
                touchstart: function(e) {
                    sp = {
                        x: e.touches[0].pageX,
                        y: e.touches[0].pageY
                    }
                },
                touchmove: function(e) {
                    nm = false;
                    ep = {
                        x: e.touches[0].pageX,
                        y: e.touches[0].pageY
                    }
                },
                touchend: function(e) {
                    if (nm) {
                        ce(e, 'fc')
                    } else {
                        var x = ep.x - sp.x,
                            xr = Math.abs(x),
                            y = ep.y - sp.y,
                            yr = Math.abs(y);
                        if (Math.max(xr, yr) > 20) {
                            ce(e, (xr > yr ? (x < 0 ? 'swl' : 'swr') : (y < 0 ? 'swu' : 'swd')))
                        }
                    };
                    nm = true
                },
                touchcancel: function(e) {
                    nm = false
                }
            };
        for (var a in touch) {
            d.addEventListener(a, touch[a], false);
        }
    })(document);
    //EXAMPLE OF USE
    var h = function(e) {
        console.log(e.type, e)
    };
    // document.body.addEventListener('fc', h, false); // 0-50ms vs 500ms with normal click
    // document.body.addEventListener('swl', h, false);
    // document.body.addEventListener('swr', h, false);
    // document.body.addEventListener('swu', h, false);
    // document.body.addEventListener('swd', h, false);

    var hideFilter=function(e){
       
        if(document.querySelector('.ocf-offcanvas.ocfilter-mobile').classList.contains('active')){
            document.querySelector('.ocf-offcanvas.ocfilter-mobile').classList.remove('active')
        } 
    }
    document.body.addEventListener('swl',hideFilter,false);
}

</script>
<?php } ?>