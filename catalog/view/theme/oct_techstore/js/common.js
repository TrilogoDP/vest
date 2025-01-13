function getURLVar(t) {
    var e = [],
        o = document.location.search.split("?");
    if (o[1]) {
        var n = o[1].split("&");
        for (i = 0; i < n.length; i++) {
            var a = n[i].split("=");
            a[0] && a[1] && (e[a[0]] = a[1])
        }
        return e[t] ? e[t] : ""
    }
}

function is_touch_device() {
    var t = " -webkit- -moz- -o- -ms- ".split(" ");
    if ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch) return !0;
    var e, o = ["(", t.join("touch-enabled),("), "heartz", ")"].join("");
    return e = o, window.matchMedia(e).matches
}
$.webfunUrlParam = function(t) {
    var e = new RegExp("[?&]" + t + "=([^&#]*)").exec(window.location.search);
    return null !== e && (e[1] || 0)
}, $(window).load(function() {}), $(document).ready(function() {
    0 < $("#input-sort").length && null == $("#input-sort").val() && 0 < $(".sort-row").length && ($("#input-sort").html($.trim($("#input-sort").html())), $("#input-limit").html($.trim($("#input-limit").html()))), is_touch_device() && $(document).mouseup(function(t) {
        var e = $(".ocfilter-mobile");
        e.hasClass("active") && (e.is(t.target) || 0 !== e.has(t.target).length || $(".ocf-offcanvas .ocfilter-mobile-handle .btn").trigger("click"))
    }), $(".text-danger").each(function() {
        var t = $(this).parent().parent();
        t.hasClass("form-group") && t.addClass("has-error")
    }), $("#form-currency .currency-select").on("click", function(t) {
        t.preventDefault(), $("#form-currency input[name='code']").val($(this).attr("name")), $("#form-currency").submit()
    }), $("#search input[name='search']").on("keydown", function(t) {
        13 == t.keyCode && $("header #search input[name='search']").parent().find("button").trigger("click") && $("#search .dropdown-menu").hide()
    }), $("#list-view").click(function() {
        $("#content .product-grid > .clearfix").remove(), $("#content .product-table").hide(), $("#content .row .product-layout").show(), $("#content .row > .product-grid").attr("class", "product-layout product-list col-xs-6"), $("#price-view").removeClass("active"), $("#grid-view").removeClass("active"), $("#list-view").addClass("active"), localStorage.setItem("display", "list")
    }), $("#price-view").click(function() {
        $("#content .product-table").show(), $("#content .row .product-layout").hide(), $("#list-view").removeClass("active"), $("#grid-view").removeClass("active"), $("#price-view").addClass("active"), localStorage.setItem("display", "price")
    }), $("#grid-view").click(function() {
        var t = $("#column-right, #column-left").length;
        $("#content .product-table").hide(), $("#content .row .product-layout").show(), 2 == t ? $("#content .product-list").attr("class", "product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-6") : 1 == t ? $("#content .product-list").attr("class", "product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-6") : $("#content .product-list").attr("class", "product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-6"), $("#price-view").removeClass("active"), $("#list-view").removeClass("active"), $("#grid-view").addClass("active"), localStorage.setItem("display", "grid")
    }), "list" == localStorage.getItem("display") ? ($("#list-view").trigger("click"), $("#list-view").addClass("active")) : "price" == localStorage.getItem("display") ? ($("#price-view").trigger("click"), $("#price-view").addClass("active")) : ($("#grid-view").trigger("click"), $("#grid-view").addClass("active")), $(document).on("keydown", "#collapse-checkout-option input[name='email'], #collapse-checkout-option input[name='password']", function(t) {
        13 == t.keyCode && $("#collapse-checkout-option #button-login").trigger("click")
    }), $("[data-toggle='tooltip']").tooltipster({
        theme: "tooltipster-shadow",
        trigger: "custom",
        animation: "fade",
        delay: 40,
        triggerOpen: {
            mouseenter: !0,
            touchstart: !0
        },
        triggerClose: {
            click: !0,
            scroll: !0,
            tap: !0,
            mouseleave: !0
        }
    }), $(document).ajaxStop(function() {
        $("[data-toggle='tooltip']").tooltip({
            container: "body"
        })
    })
});
var cart = {
        add: function(t, e) {
            $.ajax({
                url: "index.php?route=checkout/cart/add",
                type: "post",
                data: "product_id=" + t + "&quantity=" + (void 0 !== e ? e : 1),
                dataType: "json",
                beforeSend: function() {
                    $("#cart > button").button("loading")
                },
                complete: function() {
                    $("#cart > button").button("reset")
                },
                success: function(t) {
                    $(".alert, .text-danger").remove(), t.redirect && (location = t.redirect), t.success && ($("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + t.success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'), setTimeout(function() {
                        $("#cart > button").html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + t.total + "</span>")
                    }, 100), $("html, body").animate({
                        scrollTop: 0
                    }, "slow"), $("#cart > ul").load("index.php?route=common/cart/info ul li"))
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        },
        update: function(t, e) {
            $.ajax({
                url: "index.php?route=checkout/cart/edit",
                type: "post",
                data: "key=" + t + "&quantity=" + (void 0 !== e ? e : 1),
                dataType: "json",
                beforeSend: function() {
                    $("#cart > button").button("loading")
                },
                complete: function() {
                    $("#cart > button").button("reset")
                },
                success: function(t) {
                    setTimeout(function() {
                        $("#cart > button").html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + t.total + "</span>")
                    }, 100), "checkout/cart" == getURLVar("route") || "checkout/checkout" == getURLVar("route") ? location = "index.php?route=checkout/cart" : $("#cart > ul").load("index.php?route=common/cart/info ul li")
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        },
        remove: function(t) {
            $.ajax({
                url: "index.php?route=checkout/cart/remove",
                type: "post",
                data: "key=" + t,
                dataType: "json",
                beforeSend: function() {
                    $("#cart > button").button("loading")
                },
                complete: function() {
                    $("#cart > button").button("reset")
                },
                success: function(t) {
                    setTimeout(function() {
                        $("#cart-total").html(t.total)
                    }, 100);
                    var e = String(document.location.pathname);
                    "/cart/" == e || "/checkout/" == e || "checkout/cart" == getURLVar("route") || "checkout/checkout" == getURLVar("route") ? location = "index.php?route=checkout/cart" : $("#cart > ul").load("index.php?route=common/cart/info ul li")
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        }
    },
    voucher = {
        add: function() {},
        remove: function(t) {
            $.ajax({
                url: "index.php?route=checkout/cart/remove",
                type: "post",
                data: "key=" + t,
                dataType: "json",
                beforeSend: function() {
                    $("#cart > button").button("loading")
                },
                complete: function() {
                    $("#cart > button").button("reset")
                },
                success: function(t) {
                    setTimeout(function() {
                        $("#cart > button").html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + t.total + "</span>")
                    }, 100), "checkout/cart" == getURLVar("route") || "checkout/checkout" == getURLVar("route") ? location = "index.php?route=checkout/cart" : $("#cart > ul").load("index.php?route=common/cart/info ul li")
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        }
    },
    wishlist = {
        add: function(t) {
            $.ajax({
                url: "index.php?route=account/wishlist/add",
                type: "post",
                data: "product_id=" + t,
                dataType: "json",
                success: function(t) {
                    $(".alert").remove(), t.redirect && (location = t.redirect), t.success && $("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + t.success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'), $("#wishlist-total span").html(t.total), $("#wishlist-total").attr("title", t.total), $("html, body").animate({
                        scrollTop: 0
                    }, "slow")
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        },
        remove: function() {}
    },
    compare = {
        add: function(t) {
            $.ajax({
                url: "index.php?route=product/compare/add",
                type: "post",
                data: "product_id=" + t,
                dataType: "json",
                success: function(t) {
                    $(".alert").remove(), t.success && ($("#content").parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + t.success + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>'), $("#compare-total").html(t.total), $("html, body").animate({
                        scrollTop: 0
                    }, "slow"))
                },
                error: function(t, e, o) {
                    alert(o + "\r\n" + t.statusText + "\r\n" + t.responseText)
                }
            })
        },
        remove: function() {}
    };

function webfunDoLiveSearchMobile1(t, r) {
    return 38 != t.keyCode && 40 != t.keyCode && ($("#livesearch_search_results").remove(), updown = -1, !("" == r || r.length < 3) && (r = encodeURI(r), $.ajax({
        url: $("base").attr("href") + "index.php?route=product/search/ajax&keyword=" + r,
        dataType: "json",
        success: function(t) {
            if (0 < t.length) {
                var e, o, i = document.createElement("ul");
                for (var n in i.id = "msearchresults1", t) {
                    if (e = document.createElement("li"), eListDiv = document.createElement("div"), eListDiv.setAttribute("style", "height: 10px; clear: both;"), eListDivpr = document.createElement("span"), eListDivpr.innerHTML = t[n].price, eListDivpr.setAttribute("style", "height: 14px; color: #147927;"), "" != t[n].special && eListDivpr.setAttribute("style", "text-decoration: line-through;"), eListDivprspec = document.createElement("span"), eListDivprspec.innerHTML = t[n].special, eListDivprspec.setAttribute("style", "font-weight: bold; margin-left: 8px; color: #a70d0d; font-size: 16px;"), eListDivstatus = document.createElement("span"), eListDivstatus.innerHTML = t[n].stock, eListDivstatus.setAttribute("style", "height: 14px; color: #337ab7; margin-left: 15px; font-weight: bold;"), eListImg = document.createElement("img"), eListImg.src = t[n].image, eListImg.setAttribute("style", "margin-right: 10px;"), eListImg.align = "left", (o = document.createElement("a")).setAttribute("style", "display: block;"), o.appendChild(document.createTextNode(t[n].name)), void 0 !== t[n].href) {
                        var a = decodeURIComponent(t[n].href);
                        o.href = a.replace("&amp;", "&")
                    } else o.href = $("base").attr("href") + "index.php?route=product/product&product_id=" + t[n].product_id + "&keyword=" + r;
                    e.appendChild(o), i.appendChild(eListImg), i.appendChild(e), i.appendChild(eListDivpr), "" != t[n].special && i.appendChild(eListDivprspec), i.appendChild(eListDivstatus), i.appendChild(eListDiv)
                }
                0 < $("#msearchresults1").length && $("#msearchresults1").remove(), $("#searchm1").append(i)
            }
        }
    }), !0))
}

function webfunUpDownEvent1(t) {
    var e = document.getElementById("livesearch_search_results");
    if ($("input[name=webfun_search1]"), e) {
        var o = e.childNodes.length - 1;
        if (-1 != updown && void 0 !== e.childNodes[updown] && $(e.childNodes[updown]).removeClass("highlighted"), 38 == t.keyCode ? updown = 0 < updown ? --updown : updown : 40 == t.keyCode && (updown = updown < o ? ++updown : updown), 0 <= updown && updown <= o) {
            $(e.childNodes[updown]).addClass("highlighted");
            var i = e.childNodes[updown].childNodes[0].text;
            void 0 === i && (i = e.childNodes[updown].childNodes[0].innerText), $("input[name=webfun_search1]").val(new String(i).replace(/(\s\(.*?\))$/, ""))
        }
    }
    return !1
}

function delay(o, i) {
    var n = 0;
    return function() {
        var t = this,
            e = arguments;
        clearTimeout(n), n = setTimeout(function() {
            o.apply(t, e)
        }, i || 0)
    }
}
$(document).delegate(".agree", "click", function(t) {
        t.preventDefault(), $("#modal-agree").remove();
        var e = this;
        $.ajax({
            url: $(e).attr("href"),
            type: "get",
            dataType: "html",
            success: function(t) {
                html = '<div id="modal-agree" class="modal">', html += '  <div class="modal-dialog">', html += '    <div class="modal-content">', html += '      <div class="modal-header">', html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>', html += '        <h4 class="modal-title">' + $(e).text() + "</h4>", html += "      </div>", html += '      <div class="modal-body">' + t + "</div>", html += "    </div", html += "  </div>", html += "</div>", $("body").append(html), $("#modal-agree").modal("show")
            }
        })
    }),
    function(o) {
        o.fn.autocomplete = function(t) {
            return this.each(function() {
                this.timer = null, this.items = new Array, o.extend(this, t), o(this).attr("autocomplete", "off"), o(this).on("focus", function() {
                    this.request()
                }), o(this).on("blur", function() {
                    setTimeout(function(t) {
                        t.hide()
                    }, 200, this)
                }), o(this).on("keydown", function(t) {
                    switch (t.keyCode) {
                        case 27:
                            this.hide();
                            break;
                        default:
                            this.request()
                    }
                }), this.click = function(t) {
                    t.preventDefault(), value = o(t.target).parent().attr("data-value"), value && this.items[value] && this.select(this.items[value])
                }, this.show = function() {
                    var t = o(this).position();
                    o(this).siblings("ul.dropdown-menu").css({
                        top: t.top + o(this).outerHeight(),
                        left: t.left
                    }), o(this).siblings("ul.dropdown-menu").show()
                }, this.hide = function() {
                    o(this).siblings("ul.dropdown-menu").hide()
                }, this.request = function() {
                    clearTimeout(this.timer), this.timer = setTimeout(function(t) {
                        t.source(o(t).val(), o.proxy(t.response, t))
                    }, 200, this)
                }, this.response = function(t) {
                    if (html = "", t.length) {
                        for (i = 0; i < t.length; i++) this.items[t[i].value] = t[i];
                        for (i = 0; i < t.length; i++) t[i].category || (html += '<li data-value="' + t[i].value + '"><a href="#">' + t[i].label + "</a></li>");
                        var e = new Array;
                        for (i = 0; i < t.length; i++) t[i].category && (e[t[i].category] || (e[t[i].category] = new Array, e[t[i].category].name = t[i].category, e[t[i].category].item = new Array), e[t[i].category].item.push(t[i]));
                        for (i in e)
                            for (html += '<li class="dropdown-header">' + e[i].name + "</li>", j = 0; j < e[i].item.length; j++) html += '<li data-value="' + e[i].item[j].value + '"><a href="#">&nbsp;&nbsp;&nbsp;' + e[i].item[j].label + "</a></li>"
                    }
                    html ? this.show() : this.hide(), o(this).siblings("ul.dropdown-menu").html(html)
                }, o(this).after('<ul class="dropdown-menu"></ul>'), o(this).siblings("ul.dropdown-menu").delegate("a", "click", o.proxy(this.click, this))
            })
        }
    }(window.jQuery), $(document).ready(function() {
        $("input[name=webfun_search1]").keyup(delay(function(t) {
            webfunDoLiveSearchMobile1(t, this.value)
        }, 500)).focus(function(t) {
            webfunDoLiveSearchMobile1(t, this.value)
        }).keydown(function(t) {
            webfunUpDownEvent1(t)
        }).blur(function() {}), $("input[name='webfun_search1']").on("keydown", function(t) {
            if (13 == t.keyCode) {
                if ($("input[name='webfun_search1']").val().length <= 0) return !1;
                $("#oct-m-search-button1").trigger("click")
            }
        })
    });