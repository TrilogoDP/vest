// CATEGORY HEADER

let btn_category = document.getElementsByClassName('#menu .category-btn');
console.log(btn_category);

$(document).ready(function () {

    $(document).on('keydown', function(e) {
        if (e.keyCode === 13) {
            $('#popup-login-button').click();
        }
    });

    $('select[data-type="select2"]').select2();

    $('.second-level-li.has-child').each(function(index, el) {
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

    $('#menu .second-level-li.has-child > a').each(function() {
        $(this).on('click', function(event) {
            if ('ontouchstart' in window || navigator.msMaxTouchPoints) {
                event.preventDefault();
            }
        });
    });

	$.webfunUrlParam = function(t) {
	    var e = new RegExp("[?&]" + t + "=([^&#]*)").exec(window.location.search);
	    return null !== e && (e[1] || 0)
	}
	$("#back-top").hide()

	$(window).scroll(function () {
	    450 < $(this).scrollTop() ? $("#back-top").fadeIn() : $("#back-top").fadeOut();
	}),
	$("#back-top").click(function () {
	    return (
	        $("body,html").animate(
	            {
	                scrollTop: 0,
	            },
	            800
	        ),
	        !1
	    );
	});

    $('.product-special #column-left .ocfilter .ocf-option-values label a').each(function(index, el) {
        $(this).on('click', function(){
            return (
                $("body,html").animate(
                    {
                        scrollTop: 0,
                    },
                    800
                ),
                !1
            );
        });
    });
    
	

    $("#list-view").click(function() {
        $("#content .product-table").hide(), 
        $("#content .product-item").show(), 
        $("#content .product-grid").attr("class", "product-item product-list"), 
        $("#grid-view").removeClass("active"), 
        $("#list-view").addClass("active"), 
        localStorage.setItem("display", "list")
    });

     $("#grid-view").click(function() {
        var t = $("#column-right, #column-left").length;
        $("#content .product-table").hide(), 
        $("#content .product-item").show(), 
        2 == t ? $("#content .product-list").attr("class", "product-item product-grid") : 1 == t ? $("#content .product-list").attr("class", "product-item product-grid") : $("#content .product-list").attr("class", "product-item product-grid"), 

        $("#list-view").removeClass("active"), 
        $("#grid-view").addClass("active"), 
        localStorage.setItem("display", "grid")
    });

 	if (localStorage.getItem('display') == 'list') {
        $('#list-view').trigger('click');
  	} else {
        $('#grid-view').trigger('click');
  	}


    //Search 
    let isMainSearchFocused = false;

    $('#mainsearch').focus(function(e) {
        isMainSearchFocused = true;
        doLiveSearcStart();    
        showSearch();       
    }); 


    window.addEventListener('scroll', function() {
        if (isMainSearchFocused) {
            hideLiveSearch();
            $('#mainsearch').blur();
        }
    });

    $('#menu').hover(function() {
        if (isMainSearchFocused) {
            hideLiveSearch();
            $('#mainsearch').blur();
        }
    });
    //Search  End


    if($('body').hasClass('common-home')){
        var $block1 = $('.common-home .menu-wrap .collapse-wrap');
        var $block2 = $('.main-advantage');

        var offset1 = $block1.offset();
        var offset2 = $block2.offset();

        if (
            offset1.top < offset2.top + $block2.height() &&
            offset1.top + $block1.height() > offset2.top &&
            offset1.left < offset2.left + $block2.width() &&
            offset1.left + $block1.width() > offset2.left
        ) {
            $('.main-advantage').addClass('offset_block');
        } 
    }
      



    $(window).on('scroll', function() {
        var scrollPosition = $(this).scrollTop();


        if (scrollPosition >= 60) {
          $('.header-second-line').addClass('fixed');
          $('.header-first-line').addClass('fixed');
        } else {
          $('.header-second-line').removeClass('fixed');
          $('.header-first-line').removeClass('fixed');
        }
    });

    

    var timeoutID;
    var searchElement = document.querySelector('#search');

   
    if (searchElement) {
        searchElement.addEventListener('input', function(e) {
            clearTimeout(timeoutID);
            $('#oct-mobile-search .clear_btn').show();
            timeoutID = setTimeout(function() {
                doLiveSearcStart(); 
                showSearch(); 
            }, 1000);
        });
    }

    var valSearch = $('#search input').prop('value');
    if(valSearch && valSearch.length  > 0){
        $('.search__field .clear_btn').show();
    }

    $('#search .clear_btn').on('click', function(){
        $('#mainsearch').val('');
        $('#search').find('.autocomplete_wrap').hide();
        $(this).hide();
    });
           
   

    $(document).mouseup(function (e) {
        var searchWrap = $("#search");
        if (!searchWrap.is(e.target) && searchWrap.has(e.target).length === 0) {
            $("#search").find(".autocomplete_wrap").hide();
        }
    });


    // document.querySelector('#mobilesearch').addEventListener('input', function(e) {
    //     clearTimeout(timeoutID);
    //     $('#oct-mobile-search .clear_btn').show();
    //     timeoutID = setTimeout(function() {
    //         doLiveSearchMobileStart();    
    //         showSearchMob(); 
    //     }, 1000);
    // });


    // function doLiveSearchMobileStart(e, c) {
    //    var value = $("#mobilesearch").val();
    //     $.ajax({
    //         url: "index.php?route=hobotix/search",
    //         dataType: "html",
    //         type: "GET",
    //         data: {
    //             query: value,
    //         },
    //         error: function (json) {
    //             console.log(json);
    //         },
    //         beforeSend: function(){
    //             $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-spinner fa-spin"></i>');  
    //             $('#oct-mobile-search').find('.clear_btn').addClass('spinner');
    //         },
    //         complete: function(e){
    //             $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-times"></i>');  
    //             $('#oct-mobile-search').find('.clear_btn').removeClass('spinner');                
    //         },
    //         success: function (html) {
    //             $("#oct-mobile-search .autocomplete_wrap").html(html);
    //             $("#oct-mobile-search .autocomplete_wrap").show();
    //         },
    //     });
    // }

    $("#search")
        .find("[name=search]")
        .first()
        .focus(function (e) {
            doLiveSearch(e, this.value);
        })
        .keydown(function (e) {
            upDownEvent(e);
        })
        .blur(function () {
            window.setTimeout("$('#livesearch_search_results').remove();updown=0;", 900);
        });

    $("#search input[name='search']").on("keydown", function (e) {
        if (13 == e.keyCode) {
            if ($("input[name='search']").val().length <= 0) return !1;
            $("#oct-search-button").trigger("click");
        }
    });
    $("#oct-mobile-search-box input[name='search']").on("keydown", function (e) {
        if (13 == e.keyCode) {
            if ($("input[name='search']").val().length <= 0) return !1;
            $("#oct-m-search-button").trigger("click");
        }
    });

    if (document.documentElement.clientWidth < 1250) {

        $('#menu').on('click', function(event) {
            event.preventDefault();
            $('.mobile-menu').addClass('open');
            $('.menu-overlay').addClass('open');
            $('html').addClass('open_menu');
        });
        $('.menu-overlay').on('click', function(event) {
            event.preventDefault();
            $('.mobile-menu').removeClass('open');
            $('.menu-overlay').removeClass('open');
            $('html').removeClass('open_menu');
            $('.mobile-menu #menu').removeClass('open');
            $('.mobile-menu .has_children').removeClass('open');
            $('.mobile-menu #menu .collapse-wrap .second-level-li.has-child').each(function() {
                $(this).removeClass('open');
            });
            $('mobile-menu .menu-wrap .collapse-wrap .second-level-li .megamenu-ischild > ul > li.has-child').each(function() {
                $(this).removeClass('open');
            });
        });
        $('.mobile-menu #menu .category-btn-header').on('click', function(event) {
            event.preventDefault();
            $(this).closest('#menu').addClass('open');
        });
        $('.mobile-menu #menu .collapse-wrap > .btn-back').on('click', function(event) {
            event.preventDefault();
            $('.mobile-menu #menu').removeClass('open');
        });
        $('.mobile-menu .has_children > span').each(function(index, el) {
            $(this).on('click', function(event) {
                event.preventDefault();
                $(this).closest('.has_children').addClass('open');
            });
        });
        
        $('.mobile-menu .has_children .btn-back').on('click', function(event) {
            $('.mobile-menu .has_children').removeClass('open');
        });  
        $('.mobile-menu #menu .collapse-wrap .second-level-li.has-child > a').each(function() {
            
            $(this).on('click', function(event) {
                event.preventDefault();
                $(this).closest('.has-child').addClass('open');
            });
            
        });
        
        $('.mobile-menu #menu .collapse-wrap .second-level-li.has-child .megamenu-ischild .btn-back').each(function() {
            
            $(this).on('click', function(event) {

                $(this).closest('.has-child').removeClass('open');
            });
        });

        $('.mobile-menu .menu-wrap .collapse-wrap .second-level-li .megamenu-ischild > ul > li.has-child > a').each(function() {
            
            $(this).on('click', function(event) {
                event.preventDefault();
                $(this).closest('.has-child').addClass('open');
            });
            
        });
         $('.mobile-menu #menu .collapse-wrap .second-level-li.has-child .megamenu-ischild  > ul > li.has-child a .btn-back').each(function() {
            
            $(this).on('click', function(event) {

                $(this).closest('.has-child').removeClass('open');
            });
        });
    }
    if (document.documentElement.clientWidth < 560) {
        $('#mainsearch').on('focus', function(){
            $('#search .clear_btn').show();
        })
        $('.footer_wrap .item .title').each(function() {
            $(this).on('click', function(){
                $(this).closest('.item').toggleClass('open');
            });
        });

        
    }   
  
});

function upDownEvent(e) {
    var t = document.getElementById("livesearch_search_results");
    if (($("#search").find("[name=search]").first(), t)) {
        var o = t.childNodes.length - 1;
        if (
            (-1 != updown && void 0 !== t.childNodes[updown] && $(t.childNodes[updown]).removeClass("highlighted"),
            38 == e.keyCode ? (updown = 0 < updown ? --updown : updown) : 40 == e.keyCode && (updown = updown < o ? ++updown : updown),
            0 <= updown && updown <= o)
        ) {
            $(t.childNodes[updown]).addClass("highlighted");
            var n = t.childNodes[updown].childNodes[0].text;
            void 0 === n && (n = t.childNodes[updown].childNodes[0].innerText),
                $("#search")
                    .find("[name=search]")
                    .first()
                    .val(new String(n).replace(/(\s\(.*?\))$/, ""));
        }
    }
    return !1;
}


function get_oct_popup_found_cheaper(e) {
    setTimeout(function () {
        $.magnificPopup.open({
            tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
            items: {
                src: "index.php?route=extension/module/oct_popup_found_cheaper&product_id=" + e,
                type: "ajax",
            },
            midClick: !0,
            removalDelay: 200,
        });
    }, 1);
}

function get_oct_popup_purchase(e) {
    setTimeout(function () {
        $.magnificPopup.open({
            tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
            items: {
                src: "index.php?route=extension/module/oct_popup_purchase&product_id=" + e,
                type: "ajax",
            },
            midClick: !0,
            removalDelay: 200,
        });
    }, 1);
}

function get_oct_popup_subscribe() {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_subscribe",
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });
}

function get_oct_popup_call_phone() {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_call_phone",
            type: "ajax",
            ajax: {
                settings: {
                    type: "POST",
                    data: {},
                },
            },
        },
        midClick: !0,
        removalDelay: 200,
    });
    
    passEventToDataLayer('callbackopen', 'callback', 'click', 'callbackbutton');
}

function get_oct_product_preorder(e) {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_product_preorder&product_id=" + e,
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });
}

function oct_get_product_id(e) {
    for (var t = 0, o = e.split("&"), n = 0; n < o.length; n++) {
        if ("product_id" === (t = o[n].split("="))[0]) return t[1];
    }
}



function validate(e) {
    e.value = e.value.replace(/[^\d,]/g, "");
}

function doLiveSearch(e, c) {
    var value = $("#mainsearch").val();
    return (
        38 != e.keyCode &&
        40 != e.keyCode &&
        ((updown = -1),
        !(
            "" == c ||
            c.length < 1 ||
            ((c = encodeURI(c)),
            $.ajax({
                url: "index.php?route=hobotix/search",
                dataType: "html",
                type: "GET",
                data: {
                    query: value,
                },
                error: function (json) {
                    console.log(json);
                },
                success: function (html) {
                    $('.search__field .clear_btn').show();
                    $("#search .autocomplete_wrap").html(html);
                    $("#search .autocomplete_wrap").show();
                },
            }),
            0)
        ))
    );
}
function showSearch(){
    $('#search').find('.autocomplete_wrap').show(); 
}

function get_oct_popup_login() {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_login",
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });

     if (document.documentElement.clientWidth < 920) {
        
        event.preventDefault();
        $('.mobile-menu').removeClass('open');
        $('.menu-overlay').removeClass('open');
        $('.mobile-menu #menu').removeClass('open');
        $('.mobile-menu .has_children').removeClass('open');
        $('.mobile-menu #menu .collapse-wrap .second-level-li.has-child').each(function() {
            $(this).removeClass('open');
        });
       
     }   
}


function doLiveSearcStart(e, c) {
    var value = $("#mainsearch").val();
    $.ajax({
        url: "index.php?route=hobotix/search",
        dataType: "html",
        type: "GET",
        data: {
            query: value,
        },
        error: function (json) {
            console.log(json);
        },
        beforeSend: function(){
            $('#search').find('.clear_btn').html('<i class="fa fa-spinner fa-spin"></i>');  
            $('#search').find('.clear_btn').addClass('spinner');
        },
        complete: function(e){
            $('#search').find('.clear_btn').html('<i class="fa fa-times"></i>');  
            $('#search').find('.clear_btn').removeClass('spinner');                
        },
        success: function (html) {
            $("#search .autocomplete_wrap").html(html);
            $("#search .autocomplete_wrap").show();
        },
    });
}

function hideLiveSearch() {
    $("#search .autocomplete_wrap").hide();
}
      


 function removeHistory(id){
    $.ajax({
        url: "index.php?route=hobotix/search/clear",
        type: 'POST',
        data: {
            id: id
        },
        beforeSend: function(){
            $('#search').find('.clear_history_btn').html('<i class="fa fa-spinner fa-spin"></i>');  
            $('#search').find('.clear_history_btn').addClass('spinner');
        },
        complete: function(e){
            $('#search').find('.clear_history_btn').html('<i class="fa fa-times"></i>');  
            $('#search').find('.clear_history_btn').removeClass('spinner');                
        },
        success: function() {
            doLiveSearcStart();  
        }
    });
    
}


function get_oct_popup_product_view(e) {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_view&product_id=" + e,
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });
}



function get_oct_popup_add_to_wishlist(t) {
    let product_id = t;
    $.ajax({
        url: "index.php?route=account/wishlist/add",
        type: "post",
        data: "product_id=" + t,
        dataType: "json",
        success: function (e) {
            $.magnificPopup.open({
                tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
                items: {
                    src: "index.php?route=extension/module/oct_popup_add_to_wishlist&product_id=" + t,
                    type: "ajax",
                },
                midClick: !0,
                removalDelay: 200,
            }),
                $("#wishlist-total span").html(e.total),
                $("#wishlist-total").attr("title", e.total),
                
                passEcommerceToDataLayer('addToWishList', product_id, 1, false),
                
                $.ajax({
                    url: "index.php?route=extension/module/oct_page_bar/update_html",
                    type: "get",
                    dataType: "json",
                    success: function (e) {
                        $("#oct-favorite-quantity").html(e.total_wishlist);
                        $("#oct-favorite-quantity").removeClass('hidden');
                    },
                });
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
}

function remove_wishlist(e) {
    let product_id = e;
    $.ajax({
        url: "index.php?route=extension/module/oct_page_bar/remove_wishlist&remove=" + e,
        type: "get",
        dataType: "json",
        success: function (e) {
            $.ajax({
                url: "index.php?route=extension/module/oct_page_bar/update_html",
                type: "get",
                dataType: "json",
                success: function (e) {
                    passEcommerceToDataLayer('removedFromWishList', product_id, 1, false);
                    $("#oct-favorite-quantity").html(e.total_wishlist);
                    if(e.total_wishlist === 0){
                        $("#oct-favorite-quantity").addClass('hidden');
                    }
                },
            }),
                $("#oct-favorite-content").load("index.php?route=extension/module/oct_page_bar/block_wishlist");
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
}

function get_oct_popup_add_to_compare(t) {
    let product_id = t;
    $.ajax({
        url: "index.php?route=product/compare/add",
        type: "post",
        data: "product_id=" + t,
        dataType: "json",
        success: function (e) {
            $.magnificPopup.open({
                tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
                items: {
                    src: "index.php?route=extension/module/oct_popup_add_to_compare&product_id=" + t,
                    type: "ajax",
                },
                midClick: !0,
                removalDelay: 200,
            }),
                passEcommerceToDataLayer('addToCompare', product_id, 1, false),
                $("#compare-total").html(e.total),
                $.ajax({
                    url: "index.php?route=extension/module/oct_page_bar/update_html",
                    type: "get",
                    dataType: "json",
                    success: function (e) {
                        $("#oct-compare-quantity").html(e.total_compare);
                        $("#oct-compare-quantity").removeClass('hidden');
                    },
                });
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
}

function remove_compare(e) {
    let product_id = e;
    $.ajax({
        url: "index.php?route=extension/module/oct_page_bar/remove_compare&remove=" + e,
        type: "get",
        dataType: "json",
        success: function (e) {
            $.ajax({
                url: "index.php?route=extension/module/oct_page_bar/update_html",
                type: "get",
                dataType: "json",
                success: function (e) {
                    $("#oct-compare-quantity").html(e.total_compare);
                    if(e.total_wishlist === 0){
                        $("#oct-compare-quantity").addClass('hidden');
                    }
                },
            }),
                $("#oct-compare-content").load("index.php?route=extension/module/oct_page_bar/block_compare");
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
}


function get_oct_popup_cart() { 
    if (document.documentElement.clientWidth < 920) {
        $('.mobile-menu').removeClass('open');
        $('.menu-overlay').removeClass('open');
        $('.mobile-menu #menu').removeClass('open');
        $('.mobile-menu .has_children').removeClass('open');
    }      
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_cart",
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });
}

function get_oct_popup_add_to_cart(e, t) {
    let product_id = e;
    $.ajax({
        url: "index.php?route=checkout/cart/add",
        type: "post",
        data: "product_id=" + e + "&quantity=" + (void 0 !== t ? t : 1),
        dataType: "json",
        success: function (e) {
            e.redirect && (location = e.redirect),
                e.success &&
                    (get_oct_popup_cart(),
                    $("#cart-total").html(e.total),
                    $("#cart-total").removeClass('hidden'),
                    $("#cart > ul").load("index.php?route=common/cart/info ul li"),
                    
                    passEcommerceToDataLayer('addToCart', product_id, (void 0 !== t ? t : 1), false),
                    
                    $.ajax({
                        url: "index.php?route=extension/module/oct_page_bar/update_html",
                        type: "get",
                        dataType: "json",
                        success: function (e) {
                            console.log('TestR1')
                            $("#oct-bottom-cart-quantity").html(e.total_cart);
                            setTimeout(function(){
                                $("#cart-total").removeClass('hidden');
                            }, 2000)
                           
                        },
                    }));
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
}


function get_oct_popup_product_options(e) {
    $.magnificPopup.open({
        tLoading: '<img src="catalog/view/theme/oct_techstore/image/ring-alt.svg" />',
        items: {
            src: "index.php?route=extension/module/oct_popup_product_options&product_id=" + e,
            type: "ajax",
        },
        midClick: !0,
        removalDelay: 200,
    });
}

