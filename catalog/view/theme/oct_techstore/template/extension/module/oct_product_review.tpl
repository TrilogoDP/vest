<?php if ($position == "column_left" OR $position == "column_right") { ?>
<div class="row oct-carousel-row oct-col-module">
<?php } else { ?>
<div class="row oct-carousel-row">
<?php } ?>
<div class="col-sm-12">
    <div class="oct-carousel-box oct-last-reviews">
      <div class="oct-carousel-header"><?php echo $heading_title; ?></div>
      <div id="oct-last-reviews-<?php echo $module; ?>" class="owl-carousel owl-theme">
        <?php foreach ($reviews as $review) { ?>
          <div class="item">
            <div class="image">
              <a href="<?php echo $review['href']; ?>"><img class="img-responsive" width="150" height="180" src="<?php echo $review['thumb']; ?>" alt="<?php echo $review['name']; ?>" /></a>
            </div>
            <div class="name">
              <a href="<?php echo $review['href']; ?>"><?php echo $review['name']; ?></a>
            </div>
            <?php if ($review['rating']) { ?>
              <div class="rating">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                <?php if ($review['rating'] < $i) { ?>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <?php } else { ?>
                <i class="fa fa-star" aria-hidden="true"></i>
                <?php } ?>
                <?php } ?>
              </div>
            <?php } ?>
            <div class="review-text">
            <?php echo $review['text']; ?>
            </div>
          </div>
        <?php } ?>      
      </div>
    </div>
  </div>
</div>
<script>
  $('#oct-last-reviews-<?php echo $module; ?>').owlCarousel({
     items: <?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>,
    itemsDesktop : [1921,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>],
    itemsDesktop : [1199,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 4; } ?>],
    itemsDesktopSmall : [979,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 3; } ?>],
    itemsTablet : [768,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 2; } ?>],
    itemsMobile : [479,<?php if($position == "column_left" OR $position == "column_right") { echo 1; } else { echo 1; } ?>],
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