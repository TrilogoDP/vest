<div id="oct-slideshow<?php echo $module; ?>" class="oct-slideshow-box owl-carousel" style="opacity: 1;">
  <?php foreach ($oct_banners_plus as $banner) { ?>
  <div class="item container">
    <div class="row">
      <div class="col-sm-4 col-sm-offset-1 oct-slideshow-left">
      <div class="oct-slideshowplus-header"><?php echo $banner['title']; ?></div>
        <?php echo $banner['description']; ?>
        <div class="oct-slideshow-item-button">
          <a href="<?php echo $banner['link']; ?>" class=" oct-button"><?php echo $banner['button']; ?> <i class="fa fa-angle-right" aria-hidden="true"></i></a>
        </div>
      </div>
      <div class="col-sm-6 oct-slideshow-right">
        <a href="<?php echo $banner['link']; ?>"><img class="img-responsive" src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<script><!--
$('#oct-slideshow<?php echo $module; ?>').owlCarousel({
  items: 6,
  autoPlay: false,
  singleItem: true,
  stopOnHover: true,
  navigation: <?php echo ($arrows[$banner_id]) ? 'true' : 'false'; ?>,
  navigationText: ['<i class="fa fa-angle-left fa-5x"></i>', '<i class="fa fa-angle-right fa-5x"></i>'],
  pagination: <?php echo ($pagination[$banner_id]) ? 'true' : 'false'; ?>
});
--></script>
