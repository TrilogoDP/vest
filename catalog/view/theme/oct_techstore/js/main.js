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
                    $("#cart > ul").load("index.php?route=common/cart/info ul li"),
                    
                    passEcommerceToDataLayer('addToCart', product_id, (void 0 !== t ? t : 1), false),
                    
                    $.ajax({
                        url: "index.php?route=extension/module/oct_page_bar/update_html",
                        type: "get",
                        dataType: "json",
                        success: function (e) {
                            $("#oct-bottom-cart-quantity").html(e.total_cart);
                        },
                    }));
        },
        error: function (e, t, o) {
            alert(o + "\r\n" + e.statusText + "\r\n" + e.responseText);
        },
    });
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

$('#mainsearch').focus(function(e) {
    doLiveSearcStart();    
    showSearch();       
}); 

// $('#search input').keyup(function(){      
//     $('.search__field .clear_btn').show();
//     let query = $('#mainsearch').val();
//     if (query.length >= 1){         
//         doLiveSearcStart();    
//         showSearch(); 
//     }
// });

var timeoutID;

document.querySelector('#search').addEventListener('input', function(e) {
    clearTimeout(timeoutID);
    $('#oct-mobile-search .clear_btn').show();
    timeoutID = setTimeout(function() {
        doLiveSearcStart(); 
        showSearch(); 
    }, 1000);
});


var valSearch = $('#search input').prop('value');
if(valSearch.length  > 0){
    $('.search__field .clear_btn').show();
}
    


// mob
$('#search .clear_btn').on('click', function(){
    $('#mainsearch').val('');
    $('#search').find('.autocomplete_wrap').hide();
    $(this).hide();
});


// $('#mobilesearch').keyup(function(){      
//     $('#oct-mobile-search .clear_btn').show();
//     let query = $('#mobilesearch').val();
//     if (query.length >= 1){         
//         doLiveSearchMobileStart();    
//         showSearchMob(); 
//     }
// });

document.querySelector('#mobilesearch').addEventListener('input', function(e) {
    clearTimeout(timeoutID);
    $('#oct-mobile-search .clear_btn').show();
    timeoutID = setTimeout(function() {
        doLiveSearchMobileStart();    
        showSearchMob(); 
        console.log('test1');
    }, 1000);
});




$('#oct-mobile-search .clear_btn').on('click', function(){
    $('#mobilesearch').val('');
    $('#oct-mobile-search').find('.autocomplete_wrap').hide();
    $(this).hide();
});


var valSearchMob = $('#mobilesearch').prop('value');
if(valSearchMob.length  > 0){
    $('#oct-mobile-search .clear_btn').show();
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

$(document).mouseup(function (e) {
    var searchWrap = $("#search");
    if (!searchWrap.is(e.target) && searchWrap.has(e.target).length === 0) {
        $("#search").find(".autocomplete_wrap").hide();
    }
});
function doLiveSearchMobile(e, c) {
   var value = $("#mobilesearch").val();
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
                beforeSend: function(){
                    $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-spinner fa-spin"></i>');  
                    $('#oct-mobile-search').find('.clear_btn').addClass('spinner');
                },
                complete: function(e){
                    $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-times"></i>');  
                    $('#oct-mobile-search').find('.clear_btn').removeClass('spinner');                
                },
                success: function (html) {
                    $("#oct-mobile-search .autocomplete_wrap").html(html);
                    $("#oct-mobile-search .autocomplete_wrap").show();
                },
            }),
            0)
        ))
    );
}
function doLiveSearchMobileStart(e, c) {
   var value = $("#mobilesearch").val();
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
            $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-spinner fa-spin"></i>');  
            $('#oct-mobile-search').find('.clear_btn').addClass('spinner');
        },
        complete: function(e){
            $('#oct-mobile-search').find('.clear_btn').html('<i class="fa fa-times"></i>');  
            $('#oct-mobile-search').find('.clear_btn').removeClass('spinner');                
        },
        success: function (html) {
            $("#oct-mobile-search .autocomplete_wrap").html(html);
            $("#oct-mobile-search .autocomplete_wrap").show();
        },
    });
}
function showSearchMob(){
    $('#oct-mobile-search-box').find('.autocomplete_wrap').show(); 
}
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

function viewport() {
    var e = window,
        t = "inner";
    return (
        "innerWidth" in window || ((t = "client"), (e = document.documentElement || document.body)),
        {
            width: e[t + "Width"],
            height: e[t + "Height"],
        }
    );
}

function hidePanel() {
    $("#hide-slide-panel").fadeOut(),
        $("#oct-slide-panel .oct-slide-panel-content").removeClass("oct-slide-panel-content-opened"),
        $("#oct-bluring-box").removeClass("oct-bluring"),
        $(".oct-slide-panel-item-content").removeClass("oct-panel-active"),
        $(".oct-panel-link-active").removeClass("oct-panel-link-active");
}
$(document).ready(function () {
    var e = viewport().width,
        t = $(window).height();

    function o(o, n) {
        var i = 0;
        return function () {
            var e = this,
                t = arguments;
            clearTimeout(i),
                (i = setTimeout(function () {
                    o.apply(e, t);
                }, n || 0));
        };
    }
    e <= 992
        ? $("#menu-mobile-box").prepend($("#menu"))
        : ($("ul.menu.flex").flexMenu(),
          $("ul.flexMenu-popup").mouseleave(function () {
              $(".flexMenu-popup").css("display", "none");
          })),
        $("#menu-mobile-toggle").on("click", function () {
            $("#menu-mobile").slideToggle(50, "swing"), $("html").toggleClass("noscroll"), $("#oct-bluring-box").css("height", t);
        }),
        $(".megamenu-toggle-a").on("click", function () {
            $(this).parent().toggleClass("open");
        }),
        $(".oct-mm-simplecat .dropdown-toggle").on("click", function () {
            $(this).parent().toggleClass("open");
            $(".oct-mm-simplecat .megamenu-toggle-a").removeClass("opened");
        }),
        $(".oct-mm-simplecat .megamenu-toggle-a").on("click", function () {
            $(this).parent().toggleClass("open");
            $(this).toggleClass("opened");
        }),
        $(".parent-title-toggle").on("click", function (e) {
            $(this).toggleClass("opened"), $(this).next().toggleClass("megamenu-ischild-opened"), e.preventDefault(), e.stopPropagation();
        }),
        $("#menu .navbar-header").on("click", function (e) {
            $(this).next().toggleClass("in"), e.preventDefault(), e.stopPropagation();
        }),
        $("#back-top").hide(),
        $(function () {
            $(window).scroll(function () {
                450 < $(this).scrollTop() ? $("#back-top").fadeIn() : $("#back-top").fadeOut();
            }),
                $("#back-top a").click(function () {
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
        }),
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
            }),
        $(document).bind("keydown", function (e) {
            try {
                13 == e.keyCode && 0 < $(".highlighted").length && (document.location.href = $(".highlighted").find("a").first().attr("href"));
            } catch (e) {}
        }),
        $(".navbar-nav > li > .dropdown-toggle").click(function () {
            void 0 === $(this).attr("href") || (window.location = $(this).attr("href"));
        }),
        $("#msrch")
            .find("[name=search]")
            .first()
            .focus(function (e) {
                doLiveSearchMobile(e, this.value);
            })
            .keydown(function (e) {
                upDownEvent(e);
            })
            .blur(function () {}),
        $(document).bind("keydown", function (e) {
            try {
                13 == e.keyCode && 0 < $(".highlighted").length && (document.location.href = $(".highlighted").find("a").first().attr("href"));
            } catch (e) {}
        }),
        $("#oct-mobile-search-box input[name='search']").on("keydown", function (e) {
            if (13 == e.keyCode) {
                if ($("input[name='search']").val().length <= 0) return !1;
                $("#oct-m-search-button").trigger("click");
            }
        }),
        $("#search input[name='search']").on("keydown", function (e) {
            if (13 == e.keyCode) {
                if ($("input[name='search']").val().length <= 0) return !1;
                $("#oct-search-button").trigger("click");
            }
        }),
        $("#search a").on("click", function () {
            $(".cats-button").html('<span class="category-name">' + $(this).html() + ' </span><i class="fa fa-caret-down" aria-hidden="true"></i>'), $(".selected_oct_cat").val($(this).attr("id"));
        }),
        $("#search .dropdown").on("click", function () {
            $(this).toggleClass("open-dropdown");
        }),
        $("#search .dropdown").mouseleave(function () {
            $(this).removeClass("open-dropdown");
        }),
        $(".thumbnails a").on("click", function (e) {
            $(".thumbnails a").removeClass("selected-thumb"), $(this).addClass("selected-thumb");
        }),
        $("#sstore-3-level li.active").addClass("open").children("ul").show(),
        $("#sstore-3-level li.has-sub>a.toggle-a").on("click", function () {
            $(this).removeAttr("href");
            var e = $(this).parent("li");
            e.hasClass("open")
                ? (e.removeClass("open"), e.find("li").removeClass("open"), e.find("ul").slideUp(200))
                : (e.addClass("open"),
                  e.children("ul").slideDown(200),
                  e.siblings("li").children("ul").slideUp(200),
                  e.siblings("li").removeClass("open"),
                  e.siblings("li").find("li").removeClass("open"),
                  e.siblings("li").find("ul").slideUp(200));
        });
    var n = document.location.toString();
    $("a")
        .filter(function () {
            return -1 != n.indexOf(this.href);
        })
        .addClass("current-link"),
        $(".oct-panel-link").on("click", function () {
            if ($(this).parent().hasClass("oct-panel-link-active")) $(this).parent().removeClass("oct-panel-link-active"), hidePanel();
            else {
                $("#hide-slide-panel").fadeIn(),
                    $("#oct-bluring-box").addClass("oct-bluring"),
                    $("#oct-slide-panel .oct-slide-panel-content").addClass("oct-slide-panel-content-opened"),
                    $(".oct-slide-panel-heading > .container > div").removeClass("oct-panel-link-active"),
                    $(this).parent().addClass("oct-panel-link-active"),
                    $(".oct-slide-panel-item-content").removeClass("oct-panel-active");
                var e = $(this).parent()[0].id;
                "oct-last-seen-link" === e
                    ? $("#oct-last-seen-content").toggleClass("oct-panel-active").load("index.php?route=extension/module/oct_page_bar/block_viewed")
                    : "oct-favorite-link" === e
                    ? $("#oct-favorite-content").toggleClass("oct-panel-active").load("index.php?route=extension/module/oct_page_bar/block_wishlist")
                    : "oct-compare-link" === e
                    ? $("#oct-compare-content").toggleClass("oct-panel-active").load("index.php?route=extension/module/oct_page_bar/block_compare")
                    : "oct-bottom-cart-link" === e && $("#oct-bottom-cart-content").toggleClass("oct-panel-active").load("index.php?route=extension/module/oct_page_bar/block_cart");
            }
        }),
        $("#oct-bluring-box, #hide-slide-panel").click(function () {
            hidePanel();
        }),
        $("#info-mobile-toggle").on("click", function () {
            $("#info-mobile").slideToggle(50, "swing"), $("html").toggleClass("noscroll");
        }),
        $("#search-mobile-toggle").on("click", function () {
            $(".oct-m-search").slideToggle(50, "swing"), $("html").toggleClass("noscroll");
            $('#mobilesearch').focus();
        }),
        $("#oct-menu-box").css("overflow", "visible");
}),
    $(function () {
        $(window).height();
        $(".dropdown-menu button").click(function (e) {
            e.stopPropagation();
        });
        var e = $(window).height() - 58,
            t = viewport().width,
            o = $(".footer-contacts-ul").clone();
        $(".closempanel").click(function () {
            $(".m-panel-box").fadeOut("fast"), $("#oct-bluring-box").removeAttr("style"), $("html").removeClass("noscroll");
        }),
            t <= 992
                ? ($("#m-wishlist").append($("#oct-favorite-quantity")),
                  $("#m-compare").append($("#oct-compare-quantity")),
                  $("#m-cart").append($("#oct-bottom-cart-quantity")),
                  $(".product-thumb").bind("touchmove", !0),
                  $(".product-buttons-box a").removeAttr("data-toggle"),
                  $("#info-mobile-box").html(o),
                  $("#info-mobile ul").prepend($(".top-left-info-links li")),
                  $("#oct-mobile-search-box, #menu-mobile-box, #info-mobile-box").css("height", e),
                  $("#info-mobile .footer-contacts-ul").prepend($("#language")),
                  $("#info-mobile .footer-contacts-ul").prepend($("#currency")))
                : $("ul.menu.flex").flexMenu(),
            t < 768 &&
                ($(".content-row .left-info").prepend($(".product-header")),
                $("#content").prepend($(".oct-news-panel")),
                $("footer .third-row .h5").on("click", function () {
                    $(this).next().slideToggle(), $(this).toggleClass("open");
                })),
            $(window).on("resize", function () {
                var e = $(this);
                if (e.width() <= 992) {
                    $("#m-wishlist").append($("#oct-favorite-quantity")),
                        $("#m-compare").append($("#oct-compare-quantity")),
                        $("#m-cart").append($("#oct-bottom-cart-quantity")),
                        $("#info-mobile-box").html(o),
                        $("#info-mobile ul").append($(".top-left-info-links li.apppli")),
                        $("#info-mobile .footer-contacts-ul").prepend($("#language")),
                        $("#info-mobile .footer-contacts-ul").prepend($("#currency")),
                        $("#menu-mobile-box").prepend($("#menu"));
                    var t = $(window).height() - 58;
                    $("#oct-mobile-search-box, #menu-mobile-box, #info-mobile-box").css("height", t);
                } else {
                    $("#oct-favorite-link .oct-panel-link").append($("#oct-favorite-quantity")),
                        $("#oct-compare-link .oct-panel-link").append($("#oct-compare-quantity")),
                        $("#oct-bottom-cart-link .oct-panel-link").append($("#oct-bottom-cart-quantity")),
                        $("#top-left-links ul").append($("#info-mobile ul li.apppli")),
                        $(".language-currency").prepend($("#currency")),
                        $(".language-currency").prepend($("#language")),
                        $("#oct-menu-box").prepend($("#menu")),
                        $("ul.menu.flex").flexMenu();
                    t = $(window).height() - 58;
                    $("#oct-mobile-search-box, #menu-mobile-box, #info-mobile-box").css("height", t);
                }
                e.width() < 768 ? $(".content-row .left-info").prepend($(".product-header")) : $("#product-info-right").prepend($(".product-header"));
            });
    }),
    (jQuery.browser = {}),
    (jQuery.browser.msie = !1),
    (jQuery.browser.version = 0),
    navigator.userAgent.match(/MSIE ([0-9]+)\./) && ((jQuery.browser.msie = !0), (jQuery.browser.version = RegExp.$1));
