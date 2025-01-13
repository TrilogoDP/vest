<?php echo $header; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>


<div class="wrap fdc article-page">
    <div class="breadcrumb-box">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $count => $breadcrumb) { ?>
                <?php if($count == 0) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $oct_home_text; ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.58739 0.13678C9.05769 0.48304 0.195692 7.3649 0.105589 7.49994C-0.103284 7.81291 0.00648012 8.23722 0.357574 8.47405C0.500463 8.57045 0.54897 8.57553 1.32619 8.57553H2.14438V13.3635C2.14438 18.6313 2.134 18.4204 2.41839 18.918C2.59307 19.2237 2.91996 19.5509 3.22531 19.7257C3.68737 19.9904 3.78581 20 6.0259 20H8.06077L8.20816 19.8996C8.28921 19.8443 8.3975 19.7425 8.44882 19.6733C8.5395 19.5508 8.54271 19.49 8.56438 17.4643C8.5864 15.4082 8.58801 15.3782 8.68944 15.1539C8.81873 14.868 9.14383 14.5452 9.43375 14.4148C9.74428 14.275 10.2378 14.275 10.5483 14.4148C10.8383 14.5452 11.1634 14.868 11.2927 15.1539C11.3941 15.3782 11.3957 15.4082 11.4177 17.4643C11.4394 19.49 11.4426 19.5508 11.5333 19.6733C11.5846 19.7425 11.6929 19.8443 11.7739 19.8996L11.9213 20H13.9562C16.1963 20 16.2947 19.9904 16.7568 19.7257C17.0621 19.5509 17.389 19.2237 17.5637 18.918C17.8481 18.4204 17.8377 18.6313 17.8377 13.3635V8.57553H18.6782C19.4764 8.57553 19.5261 8.57049 19.6661 8.47512C19.9111 8.30817 20 8.15203 20 7.88841C20 7.71455 19.9727 7.61472 19.8942 7.50123C19.8032 7.36976 11.2694 0.748703 10.4797 0.196848C10.1323 -0.0458327 9.89154 -0.0620769 9.58739 0.13678Z" fill="#6CBBB0"/>
                            </svg>
                            Головна
                        </a>
                    </li>
                <?php } elseif($count+1<count($breadcrumbs)) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>" title="<?php echo $breadcrumb['text']; ?>">
                            <?php echo $breadcrumb['text']; ?>
                        </a>
                    </li>                   
                <?php } ?>
                    
            <?php } ?>
        </ul>
    </div>

    <?php echo $content_top; ?>

    <h1 class="title-page"><?php echo $heading_title; ?></h1>

    <div class="blog-article-wrap">
        <?php echo $column_left; ?>  
        <div id="content" class="<?php echo $class; ?> oct-news-content">
            <div class="oct-news-content-box">
                <?php if ($thumb) { ?>
                    <ul class="thumbnails">
                        <?php if ($thumb) { ?>
                            <li>
                                <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
                                    <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
                <div id="description_block">
                    <?php echo $description; ?>
                </div>
                <?php if ($images) { ?>
                    <div class="thumbnails">
                        <?php foreach ($images as $image) { ?>
                            <div class="w-100">
                                <a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"> <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="panel">
                    <div class="panel-body oct-panel">
                        <div class="pull-left">
                            <div class="badge-box">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span class="badge"><?php echo $date_added; ?></span>
                            </div>
                            <div class="badge-box">
                                <i class="fa fa-comment-o" aria-hidden="true"></i>
                                <span class="badge"><?php echo $comments; ?></span>
                            </div>
                            <div class="badge-box">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span class="badge"><?php echo $viewed; ?></span>
                            </div>
                        </div>
                        <div class="share-buttons pull-right text-right">
                            <a class="share-btn-fb" rel="noindex nofollow" href="https://www.facebook.com/sharer.php?u=<?php echo $share; ?>&t=<?php echo $share_txt_with_name; ?>" target="_blank" onclick="return Share.me(this);">
                                <div class="facebook share-block">
                                    <i class="fa fa-facebook" aria-hidden="true"></i>
                                </div>
                            </a>
                            
                            <a class="share-btn-p" rel="noindex nofollow" href="https://www.pinterest.com/pin/create/bookmarklet/?url=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this);">
                                <div class="pinterest share-block">
                                    <i class="fa fa-pinterest" aria-hidden="true"></i>
                                </div>
                            </a>
                            
                            <a class="share-btn-tw" rel="noindex nofollow" href="https://twitter.com/intent/tweet?url=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this)">
                                <div class="twitter share-block">
                                    <i class="fa fa-twitter" aria-hidden="true"></i>
                                </div>
                            </a>                                                                
                            
                            <a class="share-btn-eml" rel="noindex nofollow" href="mailto:?subject=<?php echo $share_txt_with_name; ?>&body=<?php echo $share; ?>" target="_blank" onclick="return Share.me(this)">
                                <div class="email share-block">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </div>
                            </a>
                            
                            <a class="share-btn-viber" rel="noindex nofollow" href="viber://forward?text=" target="_blank" onclick="return ShareViber.me(this, '<?php echo $share_txt; ?>')">
                                <div class="viber share-block">
                                    <i class="fa fa-viber" aria-hidden="true" style="font-size:16px;"></i>
                                </div>
                            </a>
                            
                            <a class="share-btn-telegram" rel="noindex nofollow" href="https://telegram.me/share/url?url=<?php echo $share; ?>&text=" target="_blank" onclick="return ShareViber.me(this, '<?php echo $text_look_at_this; ?>')">
                                <div class="telegram share-block">
                                    <i class="fa fa-telegram" aria-hidden="true"></i>
                                </div>
                            </a>
                            
                            <a class="share-btn-share" id="mobile-share-button-line" style="display:none;" rel="noindex nofollow" href="#" target="_blank" onclick="share(); return false;">
                                <div class="general-share share-block">
                                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                                </div>
                            </a>
                            <script>
                                ShareViber = {
                                    me : function(el, text){                                                                        
                                        ShareViber.popup(el.href + encodeURIComponent(text));
                                        ga('send', 'event', 'share', 'click', 'sharebutton');
                                        return false;
                                    },
                                    
                                    popup: function(url) {
                                        window.open(url,'','toolbar=0,status=0,width=626,height=436');
                                    }
                                };
                                
                                Share = {
                                    me : function(el){                                                                      
                                        Share.popup(el.href);
                                        ga('send', 'event', 'share', 'click', 'sharebutton');
                                        return false;
                                    },
                                    
                                    popup: function(url) {
                                        window.open(url,'','toolbar=0,status=0,width=626,height=436');
                                    }
                                };
                            </script>
                        </div>
                    </div>
                </div>
                <?php if ($tags) { ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div style="float:left;">
                                <?php echo $text_tags; ?>
                                <?php for ($i = 0; $i < count($tags); $i++) { ?>
                                    <?php if ($i < (count($tags) - 1)) { ?>
                                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                                        <?php } else { ?>
                                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($comment_show && $comment_write) { ?>
                    <div class="comments-wrap">
                        <h3><?php echo $text_comments; ?></h3>
                        <?php if ($comment_show) { ?>
                            <div id="comments"></div>
                        <?php } ?>
                        <?php if ($comment_write) { ?>
                            <form class="form-horizontal" id="form-comments">
                                <div class="form-group required full-width">
                                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                                    <input type="text" name="name" value="" id="input-name" class="form-control" />
                                </div>
                                <div class="form-group required full-width">
                                    <label class="control-label" for="input-comments"><?php echo $entry_comment; ?></label>
                                    <textarea name="text" rows="5" id="input-comments" class="form-control"></textarea>
                                    <div class="help-block"><?php echo $text_note; ?></div>
                                </div>
                                <div class="form-group required full-width ratingme-wrap">
                                    <label class="control-label"><?php echo $entry_rating; ?></label>
                                    <select id="ratingme" name="rating">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <script>
                                        $(function() {
                                            $('#ratingme').barrating({
                                                theme: 'fontawesome-stars'
                                            });
                                        });
                                    </script>
                                </div>
                                <?php echo $captcha; ?>
                                <?php if ($text_terms) { ?>
                                    <div>
                                        <?php echo $text_terms; ?> <input type="checkbox" name="terms" value="1" style="width:auto;height:auto;display:inline-block;margin: 0;" />
                                    </div>
                                <?php } ?>
                                <div class="buttons">
                                    <div class="text-left">
                                        <button type="button" id="button-comments" data-loading-text="<?php echo $text_loading; ?>" class="btn-green"><?php echo $button_continue; ?></button>
                                    </div>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if ($articles) { ?>
                <h3><?php echo $text_related_articles; ?></h3>
                <div class="row news-row">
                    <?php foreach ($articles as $article) { ?>
                        <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="product-thumb">
                                <div class="image"><a href="<?php echo $article['href']; ?>"><img src="<?php echo $article['thumb']; ?>" alt="<?php echo $article['name']; ?>" title="<?php echo $article['name']; ?>" class="img-responsive" /></a></div>
                                <div>
                                    <div class="caption">
                                        <h4><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h4>
                                        <p><?php echo $article['description']; ?></p>
                                        <div class="pull-left">
                                            <div class="badge-box">
                                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                <span class="badge"><?php echo $article['date_added']; ?></span>
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="badge-box">
                                                <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                <span class="badge"><?php echo $article['comments']; ?></span>
                                            </div>
                                            <div class="badge-box">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <span class="badge"><?php echo $article['viewed']; ?></span>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>  
        </div>
        <?php echo $column_right; ?>
    </div>
</div>
<?php if ($products) { ?>
    <div class="row oct-carousel-row">
        <div class="col-sm-12">
            <div class="oct-carousel-box">
                <h2 class="oct-carousel-header"><?php echo $text_related_products; ?></h2>
                <div id="owl-carousel-module-products" class="owl-carousel owl-theme">
                    <?php foreach ($products as $product) { ?>     
                        <div class="item">
                            <div class="image">
                                <?php if ($product['special']) { ?>
                                    <div class="oct-discount-box special">
                                        <div class="oct-discount-item">-<?php echo $product['saving']; ?>%</div>
                                    </div>
                                <?php } ?>
                                <?php if ($product['oct_product_stickers']) { ?>
                                    <div class="oct-sticker-box">
                                        <?php foreach ($product['oct_product_stickers'] as $product_sticker) { ?>
                                            <div class="oct-sticker-item" style="color: <?php echo $product_sticker['color']; ?>; background: <?php echo $product_sticker['background']; ?>;"><?php echo $product_sticker['text']; ?></div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($oct_popup_view_data['status']) && $oct_popup_view_data['status'] && $product['quantity'] > 0) { ?>
                                    <div class="quick-view"><a onclick="get_oct_popup_product_view('<?php echo $product['product_id']; ?>');"><?php echo $button_popup_view; ?></a></div>
                                <?php } ?>   
                                <?php if ($product['thumb']) { ?>
                                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name'] ?>" /></a>
                                <?php } ?>
                            </div>
                            <div class="name">
                                <a href="<?php echo $product['href']; ?>"><?php echo $product['name'] ?></a>
                            </div>
                            <?php if ($product['rating']) { ?>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <?php if ($product['rating'] < $i) { ?>
                                            <i class="fa fa-star-o" aria-hidden="true"></i>
                                            <?php } else { ?>
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($product['price']) { ?>
                                <div class="price">
                                    <?php if (!$product['special']) { ?>
                                        <span class="price-new oct-price-normal"><?php echo $product['price']; ?></span>
                                        <?php } else { ?>
                                        <span class="price-old oct-price-old"><?php echo $product['price']; ?></span><span class="price-new oct-price-new"><?php echo $product['special']; ?></span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="cart">
                                <?php if ($product['quantity'] > 0) { ?>
                                    <a class="button-cart oct-button" title="<?php echo $button_cart; ?>" onclick="get_oct_popup_add_to_cart('<?php echo $product['product_id']; ?>', '1');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo $button_cart; ?></a>
                                    <?php } else { ?>
                                    <a class="out-of-stock-button oct-button" href="javascript: void(0);" <?php if (isset($product['product_preorder_status']) && $product['product_preorder_status'] == 1) { ?>onclick="get_oct_product_preorder('<?php echo $product['product_id']; ?>'); return false;"<?php } ?>><?php echo $product['product_preorder_text']; ?></a>
                                <?php } ?>
                                <a onclick="get_oct_popup_add_to_wishlist('<?php echo $product['product_id']; ?>');" title="<?php echo $button_wishlist; ?>" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a>
                                <a onclick="get_oct_popup_add_to_compare('<?php echo $product['product_id']; ?>');" title="<?php echo $button_compare; ?>" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    <?php } ?>   
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#owl-carousel-module-products').owlCarousel({
            items: 4,
            itemsDesktop : [1921, 4],
            itemsDesktop : [1199, 4],
            itemsDesktopSmall : [979, 3],
            itemsTablet : [768, 2],
            itemsMobile : [479, 1],
            autoPlay: false,
            navigation: true,
            slideMargin: 10,
            navigationText: ['<i class="fa fa-angle-left fa-5x" aria-hidden="true"></i>', '<i class="fa fa-angle-right fa-5x" aria-hidden="true"></i>'],
            stopOnHover:true,
            smartSpeed: 800,
            loop: true,
            pagination: false
        });
    </script>
<?php } ?>  

<?php echo $content_bottom; ?>

<script><!--
    $('.img-class').each(function(){
        var $this = $(this); 
        $this.wrap('<a class="hrefimage" href="' + $this.attr('src') + '"></a>');
    });
    
    $(document).ready(function() {
        $('.hrefimage').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', 
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300 
            }
        });
    });
    $('#comments').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();
        
        $('#comments').fadeOut('slow');
        
        $('#comments').load(this.href);
        
        $('#comments').fadeIn('slow');
    });
    
    $('#comments').load('index.php?route=octemplates/blog_article/comments&oct_blog_article_id=<?php echo $oct_blog_article_id; ?>');
    
    $('#button-comments').on('click', function() {
        $.ajax({
            url: 'index.php?route=octemplates/blog_article/write&oct_blog_article_id=<?php echo $oct_blog_article_id; ?>',
            type: 'post',
            dataType: 'json',
            data: $("#form-comments").serialize(),
            beforeSend: function() {
                $('#button-comments').button('loading');
            },
            complete: function() {
                $('#button-comments').button('reset');
            },
            success: function(json) {
                $('.alert-success, .alert-danger').remove();
                
                if (json['error']) {
                    $('#comments').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }
                
                if (json['success']) {
                    $('#comments').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    
                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                    $('input[name=\'terms\']:checked').prop('checked', false);
                }
            }
        });
    });
    
    function comment_plus(oct_blog_comment_id) {
        $.ajax({
            url: 'index.php?route=octemplates/blog_article/comment_plus&oct_blog_comment_id='+oct_blog_comment_id+'&oct_blog_article_id=<?php echo $oct_blog_article_id; ?>',
            dataType: 'json',
            success: function(json) {
                $('.alert-danger').remove();
                
                if (json['error']) {
                    $('#comments').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    } else {
                    $('#comment_plus_count-'+oct_blog_comment_id).html(json['value']);
                }
            }
        });
    }
    
    function comment_minus(oct_blog_comment_id) {
        $.ajax({
            url: 'index.php?route=octemplates/blog_article/comment_minus&oct_blog_comment_id='+oct_blog_comment_id+'&oct_blog_article_id=<?php echo $oct_blog_article_id; ?>',
            dataType: 'json',
            success: function(json) {
                $('.alert-danger').remove();
                
                if (json['error']) {
                    $('#comments').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    } else {
                    $('#comment_minus_count-'+oct_blog_comment_id).html(json['value']);
                }
            }
        });
    }
    
    $(document).ready(function() {
        $('#description_block').find('img').addClass('img-responsive');
        
        $('.thumbnails').magnificPopup({
            type:'image',
            delegate: 'a',
            gallery: {
                enabled:true
            }
        });
    });
    //-->
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
