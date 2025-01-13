<?php if ($show_price) { ?>
<div class="list-group-item ocfilter-option webfun-option-price">
  <div class="ocf-option-name">
    <?php echo $text_price; ?>
  </div>

  <div class="ocf-option-values">
    <div class="form-inline">
      <div class="form-group">
        <input name="price[min]" value="<?php echo $min_price_get; ?>" type="text" class="form-control input-sm" id="min-price-value" />
      </div>
      <div class="form-group">-</div>
      <div class="form-group">
        <input name="price[max]" value="<?php echo $max_price_get; ?>" type="text" class="form-control input-sm" id="max-price-value" />
      </div>
    </div>

    <div id="scale-price" class="scale ocf-target" style="display: none!important;" data-option-id="p"
      data-start-min="<?php echo $min_price_get; ?>"
      data-start-max="<?php echo $max_price_get; ?>"
      data-range-min="<?php echo $min_price; ?>"
      data-range-max="<?php echo $max_price; ?>"
      data-element-min="#price-from"
      data-element-max="#price-to"
      data-control-min="#min-price-value"
      data-control-max="#max-price-value"
    ></div>
  </div>
</div>
<?php } ?>