<div id="filter-column"></div>
<div class="box" id="oct-ismobile">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content filtered">
    <input type="hidden" name="temp_limit" value="0" />
    <?php if ($oct_product_filter_data['selected_values_position'] == 1) { ?>
    <div id="oct-selected-values"></div>
    <?php } ?>
    <form method="post" enctype="multipart/form-data" id="oct-product-filter-data">
      <?php if ($filter_prices) { ?>
      <div class="inner-box<?php if (!$oct_product_filter_data['price_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['price_sort_order']; ?>" data-type="price">
        <div class="item-title">
          <a class="list-group-item item-name"><span class="name"><?php echo $text_block_price; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['price_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
        </div>
        <div class="list-group-item item-content price-item">
          <div id="oct-price-slider"></div>
          <div class="row">
            <div class="col-xs-6"><input type="<?php if ($oct_product_filter_data['price_input_status']) { ?>text<?php } else { ?>hidden<?php } ?>" name="low_price" value="<?php echo $filter_prices['low_price']; ?>" class="form-control oct-inp-pr-filter" /></div>
            <div class="col-xs-6"><input type="<?php if ($oct_product_filter_data['price_input_status']) { ?>text<?php } else { ?>hidden<?php } ?>" name="high_price" value="<?php echo $filter_prices['high_price']; ?>" class="form-control oct-inp-pr-filter" /></div>
          </div>
          <?php if ($filter_special_only) { ?>
			<?php if ($special_only_total) { ?>
			  <div class="checkbox">
				<input id="special-only" type="checkbox" name="special_only" value="1" <?php if ($special_only) { ?>checked="checked"<?php } ?> <?php if (!$special_only_total) { ?>disabled<?php } ?>/>
				<label><?php echo $text_special_products_only; ?></label>
				<?php if ($special_only) { ?>
				  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
				<?php } else { ?>
				  <?php if ($oct_product_filter_data['show_totals']) { ?>
					<span class="label label-<?php if ($special_only_total) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo ($special_only_total) ? $special_only_total : 0; ?></span>
				  <?php } ?>
				<?php } ?>
			  </div>
			<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
				<div class="checkbox">
					<input id="special-only" type="checkbox" name="special_only" value="1" <?php if ($special_only) { ?>checked="checked"<?php } ?> <?php if (!$special_only_total) { ?>disabled<?php } ?>/>
					<label><?php echo $text_special_products_only; ?></label>
					<?php if ($special_only) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if ($special_only_total) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo ($special_only_total) ? $special_only_total : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
          <?php } ?>
        </div>
      </div>
      <?php } // filter_prices ?>
      <?php if ($filter_tag) { ?>
      <div class="inner-box<?php if (!$oct_product_filter_data['tag_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['tag_sort_order']; ?>" data-type="tag">
        <div class="item-title">
          <a class="list-group-item item-name"><span class="name"><?php echo $text_block_tag; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['tag_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
        </div>
        <div class="list-group-item item-content">
          <?php if ($tag) { ?>
          <div class="input-group">
            <input type="text" name="tag" value="<?php echo $tag; ?>" class="form-control" placeholder="<?php echo $entry_tag; ?>" />
            <span class="input-group-addon" onclick="$(this).prev().val('');oct_start_filter(true, true, 0, 1, '4');" style="cursor: pointer;"><i class="fa fa-times"></i></span>
          </div>
          <?php } else { ?>
		  <div>
			<input type="text" name="tag" value="<?php echo $tag; ?>" class="form-control" placeholder="<?php echo $entry_tag; ?>" />
		  </div>
          <?php } ?>
          <small class="oct-filter-faq"><?php echo $text_tag_faq; ?></small>
        </div>
      </div>
      <?php } // filter_tag ?>
      <?php if ($filter_manufacturer) { ?>
        <div class="inner-box<?php if (!$oct_product_filter_data['manufacturer_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['manufacturer_sort_order']; ?>" data-type="manufacturer">
          <div class="item-title">
            <a class="list-group-item item-name"><span class="name"><?php echo $text_block_manufacturer; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['manufacturer_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
          </div>
          <div class="list-group-item item-content">
            <?php if ($oct_product_filter_data['manufacturer_display_type'] == 1) { ?>
              <div class="select">
                <select name="m_id[]" class="form-control" id="manufacturers_select_view">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($filter_manufacturer as $manufacturer_key => $manufacturer) { ?>
					<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>
						<option id="manufacturer-key-<?php echo $manufacturer_key; ?>" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>selected<?php } ?>><?php echo $manufacturer['name']; ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
							(<?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?>)
						<?php } ?>
						</option>
					  <?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						<option id="manufacturer-key-<?php echo $manufacturer_key; ?>" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>selected<?php } ?>><?php echo $manufacturer['name']; ?>
						  <?php if ($oct_product_filter_data['show_totals']) { ?>
						  (<?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?>)
						  <?php } ?>
						</option>
					<?php } ?>
                  <?php } ?>
                </select>
              </div>
            <?php } elseif ($oct_product_filter_data['manufacturer_display_type'] == 2) { ?>
              <?php foreach ($filter_manufacturer as $manufacturer_key => $manufacturer) { ?>
				<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>
				  <div class="radio">
					<input type="radio" id="manufacturer-key-<?php echo $manufacturer_key; ?>" name="m_id" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" /> <?php echo $manufacturer['name']; ?></label>
					<?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '5');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
				  <div class="radio">
					<input type="radio" id="manufacturer-key-<?php echo $manufacturer_key; ?>" name="m_id" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" /> <?php echo $manufacturer['name']; ?></label>
					<?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '6');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php foreach ($filter_manufacturer as $manufacturer_key => $manufacturer) { ?>
				<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>
				  <div class="checkbox">
					<input type="checkbox" id="manufacturer-key-<?php echo $manufacturer_key; ?>" name="m_id[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" /> <?php echo $manufacturer['name']; ?></label>
					<?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
				  <div class="checkbox">
					<input type="checkbox" id="manufacturer-key-<?php echo $manufacturer_key; ?>" name="m_id[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php if (!isset($manufacturer['total'][$manufacturer['manufacturer_id']])) { ?>disabled<?php } ?> <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><img src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" /> <?php echo $manufacturer['name']; ?></label>
					<?php if (in_array($manufacturer['manufacturer_id'], $manufacturer['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($manufacturer['total'][$manufacturer['manufacturer_id']]) && $manufacturer['total'][$manufacturer['manufacturer_id']]) ? $manufacturer['total'][$manufacturer['manufacturer_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      <?php } // filter_manufacturer ?? ?>
      <?php if ($filter_stock) { ?>
        <div class="inner-box<?php if (!$oct_product_filter_data['stock_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['stock_sort_order']; ?>" data-type="stock">
          <div class="item-title">
            <a class="list-group-item item-name"><span class="name"><?php echo $text_block_stock; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['stock_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
          </div>
          <div class="list-group-item item-content">
            <?php if ($oct_product_filter_data['stock_display_type'] == 1) { ?>
              <div class="select">
                <select name="stock[]" class="form-control" id="filter_stock_id_select">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($filter_stock as $stock_key => $stock) { ?>
					<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>
						<option id="stock-key-<?php echo $stock_key; ?>" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>selected<?php } ?>><?php echo $stock['name']; ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						(<?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?>)
						<?php } ?>
						</option>
					<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						<option id="stock-key-<?php echo $stock_key; ?>" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>selected<?php } ?>><?php echo $stock['name']; ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						(<?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?>)
						<?php } ?>
						</option>
					<?php } ?>
                  <?php } ?>
                </select>
              </div>
            <?php } elseif ($oct_product_filter_data['stock_display_type'] == 2) { ?>
              <?php foreach ($filter_stock as $stock_key => $stock) { ?>
				<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>
					<div class="radio">
					  <input type="radio" id="stock-key-<?php echo $stock_key; ?>" name="stock" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><?php echo $stock['name']; ?></label>
					  <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '7');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
					<div class="radio">
					  <input type="radio" id="stock-key-<?php echo $stock_key; ?>" name="stock" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><?php echo $stock['name']; ?></label>
					  <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '8');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php foreach ($filter_stock as $stock_key => $stock) { ?>
				<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>
					<div class="checkbox">
					  <input type="checkbox" id="stock-key-<?php echo $stock_key; ?>" name="stock[]" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><?php echo $stock['name']; ?></label>
					  <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
					<div class="checkbox">
					  <input type="checkbox" id="stock-key-<?php echo $stock_key; ?>" name="stock[]" value="<?php echo $stock['stock_key']; ?>" <?php if (!isset($stock['total'][$stock['stock_key']]) || !$stock['total'][$stock['stock_key']]) { ?>disabled<?php } ?> <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><?php echo $stock['name']; ?></label>
					  <?php if (in_array($stock['stock_key'], $stock['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($stock['total'][$stock['stock_key']]) && $stock['total'][$stock['stock_key']]) ? $stock['total'][$stock['stock_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($filter_review) { ?>
        <div class="inner-box<?php if (!$oct_product_filter_data['review_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['review_sort_order']; ?>" data-type="review">
          <div class="item-title">
            <a class="list-group-item item-name"><span class="name"><?php echo $text_block_reviews; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['review_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
          </div>
          <div class="list-group-item item-content">
            <?php if ($oct_product_filter_data['review_display_type'] == 1) { ?>
              <div class="select">
                <select name="rating[]" class="form-control" id="filter_review_id_select">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($filter_review as $review_key => $review) { ?>
					<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>
						<option id="review-key-<?php echo $review_key; ?>" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>selected<?php } ?>><?php echo $review['name']; ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						(<?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?>)
						<?php } ?>
						</option>
					<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						<option id="review-key-<?php echo $review_key; ?>" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>selected<?php } ?>><?php echo $review['name']; ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						(<?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?>)
						<?php } ?>
						</option>
					<?php } ?>
                  <?php } ?>
                </select>
              </div>
            <?php } elseif ($oct_product_filter_data['review_display_type'] == 2) { ?>
              <?php foreach ($filter_review as $review_key => $review) { ?>
				<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>
					<div class="radio">
					  <input type="radio" id="review-key-<?php echo $review_key; ?>" name="rating" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><img src="<?php echo $review['thumb']; ?>" class="oct-rating-stars" alt="<?php echo $review_key; ?>" /></label>
					  <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '9');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
					<div class="radio">
					  <input type="radio" id="review-key-<?php echo $review_key; ?>" name="rating" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><img src="<?php echo $review['thumb']; ?>" class="oct-rating-stars" alt="<?php echo $review_key; ?>" /></label>
					  <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '10');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php foreach ($filter_review as $review_key => $review) { ?>
				<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>
					<div class="checkbox">
					  <input type="checkbox" id="review-key-<?php echo $review_key; ?>" name="rating[]" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><img src="<?php echo $review['thumb']; ?>" class="oct-rating-stars"  alt="<?php echo $review_key; ?>" /></label>
					  <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
					<div class="checkbox">
					  <input type="checkbox" id="review-key-<?php echo $review_key; ?>" name="rating[]" value="<?php echo $review['review_value_id']; ?>" <?php if (!$review['total'][$review['review_key']]) { ?>disabled<?php } ?> <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>checked="checked"<?php } ?>/>
					  <label><img src="<?php echo $review['thumb']; ?>" class="oct-rating-stars"  alt="<?php echo $review_key; ?>" /></label>
					  <?php if (in_array($review['review_value_id'], $review['checked'])) { ?>
						<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					  <?php } else { ?>
						<?php if ($oct_product_filter_data['show_totals']) { ?>
						<span class="label label-<?php if (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($review['total'][$review['review_key']]) && $review['total'][$review['review_key']]) ? $review['total'][$review['review_key']] : 0; ?></span>
						<?php } ?>
					  <?php } ?>
					</div>
				<?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($filter_attribute) { ?>
        <div class="inner-box" data-sort="<?php echo $oct_product_filter_data['attribute_sort_order']; ?>" data-type="attribute">
          <?php foreach ($filter_attribute as $attribute_key => $attribute) { ?>
            <?php if (isset($oct_product_filter_data['attribute_display_status'][$attribute['attribute_id']]) && $oct_product_filter_data['attribute_display_status'][$attribute['attribute_id']] == 1) { ?>
              <?php if (isset($oct_product_filter_data['attribute_display_type'][$attribute['attribute_id']]) && $oct_product_filter_data['attribute_display_type'][$attribute['attribute_id']] == 1) { ?>
                <div class="<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>expanded<?php } ?>" data-attribute-group-id="<?php echo $attribute['attribute_group_id']; ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $attribute['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($attribute['values']) { ?>
                      <div class="select">
                        <select name="attribute[<?php echo $attribute['attribute_name_mod']; ?>]" class="form-control" id="select_attribute_<?php echo $attribute_key; ?>">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($attribute['values'] as $attribute_value_key => $attribute_value) { ?>
							<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>
							  <option id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>selected<?php } ?>><?php echo $attribute_value['attribute_value']; ?>
								<?php if ($oct_product_filter_data['show_totals']) { ?>
								(<?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?>)
								<?php } ?>
							  </option>
							<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
								<option id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>selected<?php } ?>><?php echo $attribute_value['attribute_value']; ?>
								<?php if ($oct_product_filter_data['show_totals']) { ?>
								(<?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?>)
								<?php } ?>
							  </option>
							<?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } elseif (isset($oct_product_filter_data['attribute_display_type'][$attribute['attribute_id']]) && $oct_product_filter_data['attribute_display_type'][$attribute['attribute_id']] == 2) { ?>
                <div class="<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>expanded<?php } ?>" data-attribute-group-id="<?php echo $attribute['attribute_group_id']; ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $attribute['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($attribute['values']) { ?>
                      <?php foreach ($attribute['values'] as $attribute_value_key => $attribute_value) { ?>
						<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>
						  <div class="radio">
							<input type="radio" id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" name="attribute[<?php echo $attribute['attribute_name_mod']; ?>]" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php echo $attribute_value['attribute_value']; ?></label>
							<?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '11');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						  <div class="radio">
							<input type="radio" id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" name="attribute[<?php echo $attribute['attribute_name_mod']; ?>]" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php echo $attribute_value['attribute_value']; ?></label>
							<?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '12');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              <?php } else { ?>
                <div class="<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>expanded<?php } ?>" data-attribute-group-id="<?php echo $attribute['attribute_group_id']; ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $attribute['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['attribute_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($attribute['values']) { ?>
                      <?php foreach ($attribute['values'] as $attribute_value_key => $attribute_value) { ?>
						<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>
							<div class="checkbox">
								<input type="checkbox" id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" name="attribute[<?php echo $attribute['attribute_name_mod']; ?>][]" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>checked="checked"<?php } ?>/>
								<label><?php echo $attribute_value['attribute_value']; ?></label>
								<?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>
								  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
								<?php } else { ?>
								  <?php if ($oct_product_filter_data['show_totals']) { ?>
								  <span class="label label-<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?></span>
								  <?php } ?>
								<?php } ?>
							</div>
						<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
							<div class="checkbox">
								<input type="checkbox" id="attribute-value-key-<?php echo $attribute_key; ?>-<?php echo $attribute_value_key; ?>" name="attribute[<?php echo $attribute['attribute_name_mod']; ?>][]" value="<?php echo $attribute_value['attribute_value_mod']; ?>" <?php if (!isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>checked="checked"<?php } ?>/>
								<label><?php echo $attribute_value['attribute_value']; ?></label>
								<?php if (in_array($attribute_value['attribute_value_mod'], $attribute['checked'])) { ?>
								  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
								<?php } else { ?>
								  <?php if ($oct_product_filter_data['show_totals']) { ?>
								  <span class="label label-<?php if (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) && $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']]) ? $attribute['total'][$attribute['attribute_group_id']][$attribute['attribute_name_mod']][$attribute_value['attribute_value_mod']] : 0; ?></span>
								  <?php } ?>
								<?php } ?>
							</div>
						<?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($filter_option) { ?>
        <div class="inner-box" data-sort="<?php echo $oct_product_filter_data['option_sort_order']; ?>" data-type="option">
          <?php foreach ($filter_option as $option_key => $option) { ?>
            <?php if (isset($oct_product_filter_data['option_display_status'][$option['option_id']]) && $oct_product_filter_data['option_display_status'][$option['option_id']] == 1) { ?>
              <?php if (isset($oct_product_filter_data['option_display_type'][$option['option_id']]) && $oct_product_filter_data['option_display_type'][$option['option_id']] == 1) { ?>
                <div class="<?php if (!$oct_product_filter_data['option_collapsed']) { ?>expanded<?php } ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $option['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['option_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($option['values']) { ?>
                      <div class="select">
                        <select name="option[<?php echo $option['option_name_mod']; ?>]" class="form-control" id="select_option_<?php echo $option_key; ?>">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($option['values'] as $option_value_key => $option_value) { ?>
							<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>
								<option id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?>
								<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>selected<?php } ?>><?php echo $option_value['option_value_name']; ?>
								<?php if ($oct_product_filter_data['show_totals']) { ?>
								(<?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?>)
								<?php } ?>
								</option>
							<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
								<option id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?>
								<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>selected<?php } ?>><?php echo $option_value['option_value_name']; ?>
								<?php if ($oct_product_filter_data['show_totals']) { ?>
								(<?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?>)
								<?php } ?>
								</option>
							<?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } elseif (isset($oct_product_filter_data['option_display_type'][$option['option_id']]) && $oct_product_filter_data['option_display_type'][$option['option_id']] == 2) { ?>
                <div class="<?php if (!$oct_product_filter_data['option_collapsed']) { ?>expanded<?php } ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $option['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['option_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($option['values']) { ?>
                      <?php foreach ($option['values'] as $option_value_key => $option_value) { ?>
						<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>
						  <div class="radio">
							<input type="radio" id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" name="option[<?php echo $option['option_name_mod']; ?>]" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?> <?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php if (isset($oct_product_filter_data['option_display_image'][$option['option_id']]) && $oct_product_filter_data['option_display_image'][$option['option_id']] == 1) { ?>
							  <img src="<?php echo $option_value['option_value_image']; ?>" alt="<?php echo $option_value['option_value_name']; ?>" /> <?php } ?>
							  <?php echo $option_value['option_value_name']; ?>
							</label>
							<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '13');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						  <div class="radio">
							<input type="radio" id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" name="option[<?php echo $option['option_name_mod']; ?>]" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?> <?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php if (isset($oct_product_filter_data['option_display_image'][$option['option_id']]) && $oct_product_filter_data['option_display_image'][$option['option_id']] == 1) { ?>
							  <img src="<?php echo $option_value['option_value_image']; ?>" alt="<?php echo $option_value['option_value_name']; ?>" /> <?php } ?>
							  <?php echo $option_value['option_value_name']; ?>
							</label>
							<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '14');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($option['total'][$option_value['option_value_id']]) && $option['total'][$option_value['option_value_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              <?php } else { ?>
                <div class="<?php if (!$oct_product_filter_data['option_collapsed']) { ?>expanded<?php } ?>">
                  <div class="item-title">
                    <a class="list-group-item item-name"><span class="name"><?php echo $option['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['option_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                  </div>
                  <div class="list-group-item item-content">
                    <?php if ($option['values']) { ?>
                      <?php foreach ($option['values'] as $option_value_key => $option_value) { ?>
						<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>
						  <div class="checkbox">
							<input type="checkbox" id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" name="option[<?php echo $option['option_name_mod']; ?>][]" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?> <?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php if (isset($oct_product_filter_data['option_display_image'][$option['option_id']]) && $oct_product_filter_data['option_display_image'][$option['option_id']] == 1) { ?>
							  <img src="<?php echo $option_value['option_value_image']; ?>" alt="<?php echo $option_value['option_value_name']; ?>" /> <?php } ?>
							  <?php echo $option_value['option_value_name']; ?>
							</label>
							<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						  <div class="checkbox">
							<input type="checkbox" id="option-value-key-<?php echo $option_key; ?>-<?php echo $option_value_key; ?>" name="option[<?php echo $option['option_name_mod']; ?>][]" value="<?php echo $option_value['option_value_id']; ?>" <?php if (!isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']])) { ?>disabled<?php } ?> <?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>checked="checked"<?php } ?> />
							<label><?php if (isset($oct_product_filter_data['option_display_image'][$option['option_id']]) && $oct_product_filter_data['option_display_image'][$option['option_id']] == 1) { ?>
							  <img src="<?php echo $option_value['option_value_image']; ?>" alt="<?php echo $option_value['option_value_name']; ?>" /> <?php } ?>
							  <?php echo $option_value['option_value_name']; ?>
							</label>
							<?php if (in_array($option_value['option_value_id'], $option['checked'])) { ?>
							  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
							<?php } else { ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  <span class="label label-<?php if (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($option['total'][$option['option_name_mod']][$option_value['option_value_id']]) && $option['total'][$option['option_name_mod']][$option_value['option_value_id']]) ? $option['total'][$option['option_name_mod']][$option_value['option_value_id']] : 0; ?></span>
							  <?php } ?>
							<?php } ?>
						  </div>
						<?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($filter_sticker) { ?>
        <div class="inner-box<?php if (!$oct_product_filter_data['sticker_collapsed']) { ?> expanded<?php } ?>" data-sort="<?php echo $oct_product_filter_data['sticker_sort_order']; ?>" data-type="sticker">
          <div class="item-title">
            <a class="list-group-item item-name"><span class="name"><?php echo $text_block_stickers; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['sticker_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
          </div>
          <div class="list-group-item item-content">
            <?php if ($oct_product_filter_data['sticker_display_type'] == 1) { ?>
              <div class="select">
                <select name="sticker[]" class="form-control" id="filter_sticker_select_id">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($filter_sticker as $sticker_key => $sticker) { ?>
					<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>
					  <option id="sticker-key-<?php echo $sticker_key; ?>" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>selected<?php } ?>><?php echo $sticker['name']; ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  (<?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?>)
					  <?php } ?>
					  </option>
					<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
					  <option id="sticker-key-<?php echo $sticker_key; ?>" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>selected<?php } ?>><?php echo $sticker['name']; ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  (<?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?>)
					  <?php } ?>
					  </option>
					<?php } ?>
                  <?php } ?>
                </select>
              </div>
            <?php } elseif ($oct_product_filter_data['sticker_display_type'] == 2) { ?>
              <?php foreach ($filter_sticker as $sticker_key => $sticker) { ?>
				<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>
				  <div class="radio">
					<input type="radio" id="sticker-key-<?php echo $sticker_key; ?>" name="sticker" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><?php echo $sticker['name']; ?></label>
					<?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '15');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
				  <div class="radio">
					<input type="radio" id="sticker-key-<?php echo $sticker_key; ?>" name="sticker" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><?php echo $sticker['name']; ?></label>
					<?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '16');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } ?>
              <?php } ?>
            <?php } else { ?>
              <?php foreach ($filter_sticker as $sticker_key => $sticker) { ?>
				<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>
				  <div class="checkbox">
					<input type="checkbox" id="sticker-key-<?php echo $sticker_key; ?>" name="sticker[]" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><?php echo $sticker['name']; ?></label>
					<?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
				  <div class="checkbox">
					<input type="checkbox" id="sticker-key-<?php echo $sticker_key; ?>" name="sticker[]" value="<?php echo $sticker['product_sticker_id']; ?>" <?php if (!isset($sticker['total'][$sticker['product_sticker_id']])) { ?>disabled<?php } ?> <?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>checked="checked"<?php } ?>/>
					<label><?php echo $sticker['name']; ?></label>
					<?php if (in_array($sticker['product_sticker_id'], $sticker['checked'])) { ?>
					  <span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
					<?php } else { ?>
					  <?php if ($oct_product_filter_data['show_totals']) { ?>
					  <span class="label label-<?php if (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($sticker['total'][$sticker['product_sticker_id']]) && $sticker['total'][$sticker['product_sticker_id']]) ? $sticker['total'][$sticker['product_sticker_id']] : 0; ?></span>
					  <?php } ?>
					<?php } ?>
				  </div>
				<?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
      <?php if ($filter_standard) { ?>
        <div class="inner-box" data-sort="<?php echo $oct_product_filter_data['standard_sort_order']; ?>" data-type="standard">
          <?php foreach ($filter_standard as $standard_key => $standard) { ?>
            <?php if (isset($oct_product_filter_data['standard_display_type'][$standard['filter_group_id']]) && $oct_product_filter_data['standard_display_type'][$standard['filter_group_id']] == 1) { ?>
              <div class="<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>expanded<?php } ?>" data-standard-group-id="<?php echo $standard['filter_group_id']; ?>">
                <div class="item-title">
                  <a class="list-group-item item-name"><span class="name"><?php echo $standard['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                </div>
                <div class="list-group-item item-content">
                  <?php if ($standard['values']) { ?>
                    <div class="select">
                      <select name="standard[<?php echo $standard['filter_name_mod']; ?>]" class="form-control" id="select_standard_<?php echo $standard_key; ?>">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($standard['values'] as $standard_value_key => $standard_value) { ?>
						  <?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>
							<option id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>selected<?php } ?>><?php echo $standard_value['filter_value']; ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  (<?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?>)
							  <?php } ?>
							</option>
						  <?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
							<option id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>selected<?php } ?>><?php echo $standard_value['filter_value']; ?>
							  <?php if ($oct_product_filter_data['show_totals']) { ?>
							  (<?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?>)
							  <?php } ?>
							</option>
						  <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  <?php } ?>
                </div>
              </div>
            <?php } elseif (isset($oct_product_filter_data['standard_display_type'][$standard['filter_group_id']]) && $oct_product_filter_data['standard_display_type'][$standard['filter_group_id']] == 2) { ?>
              <div class="<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>expanded<?php } ?>" data-standard-group-id="<?php echo $standard['filter_group_id']; ?>">
                <div class="item-title">
                  <a class="list-group-item item-name"><span class="name"><?php echo $standard['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                </div>
                <div class="list-group-item item-content">
                  <?php if ($standard['values']) { ?>
                    <?php foreach ($standard['values'] as $standard_value_key => $standard_value) { ?>
					  <?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>
						<div class="radio">
						  <input type="radio" id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" name="standard[<?php echo $standard['filter_name_mod']; ?>]" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>checked="checked"<?php } ?> />
						  <label><?php echo $standard_value['filter_value']; ?></label>
						  <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>
							<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '17');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
						  <?php } else { ?>
							<?php if ($oct_product_filter_data['show_totals']) { ?>
							<span class="label label-<?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?></span>
							<?php } ?>
						  <?php } ?>
						</div>
					  <?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						<div class="radio">
						  <input type="radio" id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" name="standard[<?php echo $standard['filter_name_mod']; ?>]" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>checked="checked"<?php } ?> />
						  <label><?php echo $standard_value['filter_value']; ?></label>
						  <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>
							<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().removeAttr('checked');oct_start_filter(true, true, 0, 1, '18');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
						  <?php } else { ?>
							<?php if ($oct_product_filter_data['show_totals']) { ?>
							<span class="label label-<?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?></span>
							<?php } ?>
						  <?php } ?>
						</div>
					  <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            <?php } else { ?>
              <div class="<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>expanded<?php } ?> <?php if($standard['sort_order'] == '-1') echo 'compatibility'; if($standard['sort_order'] == '-2') echo 'models'; ?>" data-standard-group-id="<?php echo $standard['filter_group_id']; ?>">
                <div class="item-title">
                  <a class="list-group-item item-name"><span class="name"><?php echo $standard['name']; ?></span><span class="link"><i class="fa fa-<?php if (!$oct_product_filter_data['standard_collapsed']) { ?>minus<?php } else { ?>plus<?php } ?>"></i></span></a>
                </div>
                <div class="list-group-item item-content">
                  <?php if ($standard['values']) { ?>
                    <?php foreach ($standard['values'] as $standard_value_key => $standard_value) { ?>
					  <?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>
						<div class="checkbox">
						  <input type="checkbox" id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" name="standard[<?php echo $standard['filter_name_mod']; ?>][]" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>checked="checked"<?php } ?>/>
						  <label><?php echo $standard_value['filter_value']; ?></label>
						  <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>
							<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
						  <?php } else { ?>
							<?php if ($oct_product_filter_data['show_totals']) { ?>
							<span class="label label-<?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?></span>
							<?php } ?>
						  <?php } ?>
						</div>
					  <?php } elseif(isset($oct_product_filter_data['display_empty_totals']) && $oct_product_filter_data['display_empty_totals']) { ?>
						<div class="checkbox">
						  <input type="checkbox" id="standard-value-key-<?php echo $standard_key; ?>-<?php echo $standard_value_key; ?>" name="standard[<?php echo $standard['filter_name_mod']; ?>][]" value="<?php echo $standard_value['filter_value_mod']; ?>" <?php if (!isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']])) { ?>disabled<?php } ?> <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>checked="checked"<?php } ?>/>
						  <label><?php echo $standard_value['filter_value']; ?></label>
						  <?php if (in_array($standard_value['filter_value_mod'], $standard['checked'])) { ?>
							<span class="label label-danger oct-filter-total-label" onclick="$(this).prev().prev().click();"><i class="fa fa-times oct-filter-icon-unselect"></i></span>
						  <?php } else { ?>
							<?php if ($oct_product_filter_data['show_totals']) { ?>
							<span class="label label-<?php if (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) { ?>primary<?php } else { ?>default<?php } ?> oct-filter-total-label"><?php echo (isset($standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) && $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']]) ? $standard['total'][$standard['filter_name_mod']][$standard_value['filter_value_mod']] : 0; ?></span>
							<?php } ?>
						  <?php } ?>
						</div>
					  <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
    </form>
    <?php if ($oct_product_filter_data['selected_values_position'] == 2) { ?>
    <div id="oct-selected-values"></div>
    <?php } ?>
  </div>
</div>
<script><!--
$(document).ready(function() {
	$(".inner-box .item-content").each(function(i,lines) {
		if ($(this).find("div").length == 0) {
			$(this).prev().hide();
			$(this).hide();
		}
	});
});
$(function() {
	$('body').prepend('<div id="filter-mobile"></div>');
	$('.mobile-category-header').prepend('<div id="filter-button" class="oct-button"><i class="fa fa-search"></i> <?php echo $heading_title; ?></div>');
	$('.box-heading').prepend('<div id="filter-close"><i class="fa fa-times"></i></div>');

	var portraitWidth = $(window).width();
	if (portraitWidth <= '992'){
		$("#oct-ismobile") .appendTo("#filter-mobile");
	}

	$("#filter-button").click(function() {
		if ($('#filter-mobile').is(':empty')){
			$("#oct-ismobile") .appendTo("#filter-mobile");
		}

		$("#filter-mobile").toggleClass("filter-show");

		return false;
	});

	$("#filter-close").click(function() {
		$("#filter-mobile").removeClass("filter-show");

		return false;
	});


	var isMobile = {
        Android: function() {
          return navigator.userAgent.match(/Android/i);
        }
	};
	var win = $(this);
	if (!isMobile.Android()) {
		$(window).on('resize', function(){
			if (win.width() <= 992) {
				$("#oct-ismobile") .appendTo("#filter-mobile");
			} else {
				$("#oct-ismobile") .prependTo("#filter-column");
			}
		});
	} else {
		if (win.width() <= 992) {
			$("#oct-ismobile") .appendTo("#filter-mobile");
		} else {
			$("#oct-ismobile") .prependTo("#filter-column");
		}

		window.addEventListener("orientationchange", function() {
			if(window.orientation == 90) {
				$("#oct-ismobile") .prependTo("#filter-column");
			}
		}, false);
	}
});
<?php if ($filter_prices) { ?>
var oct_price_slider = document.getElementById('oct-price-slider');
noUiSlider.create(oct_price_slider, {
  start: [<?php echo $filter_prices['low_price']; ?>, <?php echo $filter_prices['high_price']; ?>],
  behaviour: 'hover',
  margin: 1,
  connect: true,
  range: {
    'min': [<?php echo $filter_range_prices['low_price']; ?>],
    'max': [<?php echo $filter_range_prices['high_price']; ?>]
  },
  format: wNumb({
    decimals: 0
  }),
  slide: function(event, ui) {
    if (ui.value == parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())-1) {
      return false;
    }
  }
});
oct_price_slider.noUiSlider.on('update', function(values, handle) {
  $('#oct-product-filter-data input[name=\'low_price\']').val(values[0]);
  $('#oct-product-filter-data input[name=\'high_price\']').val(values[1]);
});
oct_price_slider.noUiSlider.on('end', function() {
	oct_start_filter(true, true, 0, 1, '19');
});
<?php } ?>
<?php if (isset($oct_product_filter_data['collapse_expand_type'])) { ?>
  <?php if (in_array('3', $oct_product_filter_data['collapse_expand_type'])) { ?>
  $(document).on('click', '#oct-product-filter-data a.list-group-item', function(e) {
    $(this).parent().parent().toggleClass("expanded");
    $(this).find("i").toggleClass("fa-minus").addClass("fa-plus");
  });
  <?php } else { ?>
    <?php if (in_array('1', $oct_product_filter_data['collapse_expand_type'])) { ?>
    $(document).on('click', '#oct-product-filter-data a.list-group-item .name', function(e) {
      $(this).parent().parent().parent().toggleClass("expanded");
      $(this).next().find("i").toggleClass("fa-minus").addClass("fa-plus");
    });
    <?php } ?>
    <?php if (in_array('2', $oct_product_filter_data['collapse_expand_type'])) { ?>
    $(document).on('click', '#oct-product-filter-data a.list-group-item .link', function(e) {
      $(this).parent().parent().parent().toggleClass("expanded");
      $(this).find("i").toggleClass("fa-minus").addClass("fa-plus");
    });
    <?php } ?>
  <?php } ?>
<?php } else { ?>
  $(document).on('click', '#oct-product-filter-data a.list-group-item', function(e) {
    $(this).parent().parent().toggleClass("expanded");
    $(this).find("i").toggleClass("fa-minus").addClass("fa-plus");
  });
<?php } ?>
$(document).on('click', '#oct-product-filter-data .list-group-item label', function(e) {
  $(this).prev().click();
});
<?php if ($filter_prices) { ?>
$(document).on('change', '#oct-product-filter-data input[name=\'low_price\']', function() {
  oct_price_slider.noUiSlider.set([parseInt($(this).val()), null]);
});
$(document).on('change', '#oct-product-filter-data input[name=\'high_price\']', function() {
  oct_price_slider.noUiSlider.set([null, parseInt($(this).val())]);
});
$(document).on('change', '#oct-product-filter-data input[name=\'special_only\']', function() {
  oct_price_slider.noUiSlider.set([<?php echo $filter_range_prices['low_price']; ?>, <?php echo $filter_range_prices['high_price']; ?>]);
});
<?php } ?>
<?php if (isset($oct_filter) && $oct_filter) { ?>
$(function() {
	$(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).bind('click', function(e) {
		$(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).unbind("click");
		
		e.preventDefault();
	  var page = oct_get_value_from_url($(this).attr('href'), 'page');

	  if (page) {
		oct_start_filter(true, true, 0, page, '32');
	  } else {
		oct_start_filter(true, true, 0, 1, '20');
	  }
	});
});
<?php } ?>
function show_f(){
	//***mf Begin ***/
	$('div[data-type="standard"] .expanded.compatibility input[type=checkbox]').each(function() {
		var is_checked = false;
		var name_f = $(this).parent().find('label').html();
		if (this.checked) {
		   is_checked = true;
		}
		$('div[data-type="standard"] .expanded.models').each(function() {
			if($(this).find('div a span').html().indexOf(name_f) > 0){
				if(is_checked){
					$(this).addClass('show');
				}else{
					$(this).removeClass('show');
				}
			}
		});		
	});
	//***mf End ***/ 
}
function button_hide_long_list(no_c) {
    var max = <?php echo $oct_product_filter_data['visible_items_in_block_limit']; ?>-1;

    $('.list-group-item').each(function(i,lines) {
        var pr = $(this).parent().attr('data-type');
        if (no_c != pr) {
          if ($(this).find(".checkbox").length > parseInt(max+1) && $(this).find(".checkbox input:checked").length == 0) {
            $(this).children('.checkbox:gt('+max+')').hide().end().append(
              $('<a class="oct-filter-view"><?php echo $text_full_list; ?> (+'+parseInt($(this).find(".checkbox").length - (max+1))+') <i class="fa fa-caret-down" aria-hidden="true"></i></a>').click(function() {
                $(this).siblings(':hidden').show().end().remove();
              })
            );
          }
          if ($(this).find(".radio").length > parseInt(max+1) && $(this).find(".radio input:checked").length == 0) {
            $(this).children('.radio:gt('+max+')').hide().end().append(
              $('<a class="oct-filter-view"><?php echo $text_full_list; ?> (+'+parseInt($(this).find(".radio").length - (max+1))+') <i class="fa fa-caret-down" aria-hidden="true"></i></a>').click(function() {
                $(this).siblings(':hidden').show().end().remove();
              })
            );
          }
      }
    });
}

function hide_long_list() {
  var max = <?php echo $oct_product_filter_data['visible_items_in_block_limit']; ?>-1;
  $('.list-group-item').each(function(i,lines) {
    if ($(this).find(".checkbox").length > parseInt(max+1)) {
      $(this).children('.checkbox:gt('+max+')').hide().end().append(
        $('<a class="oct-filter-view"><?php echo $text_full_list; ?> (+'+parseInt($(this).find(".checkbox").length - (max+1))+') <i class="fa fa-caret-down" aria-hidden="true"></i></a>').click(function() {
          $(this).siblings(':hidden').show().end().remove();
        })
      );
    }
    if ($(this).find(".radio").length > parseInt(max+1)) {
      $(this).children('.radio:gt('+max+')').hide().end().append(
        $('<a class="oct-filter-view"><?php echo $text_full_list; ?> (+'+parseInt($(this).find(".radio").length - (max+1))+') <i class="fa fa-caret-down" aria-hidden="true"></i></a>').click(function() {
          $(this).siblings(':hidden').show().end().remove();
        })
      );
    }
  });
}
function sort_filter_blocks() {
  var $wrapper = $('#oct-product-filter-data');
  $wrapper.find('.inner-box').sort(function(a, b) {
    return +a.dataset.sort - +b.dataset.sort;
  }).appendTo($wrapper);
}
function filter_mask(element, status) {
  if (status == true) {
    $('<div/>')
    .attr({'class':'masked'})
    .prependTo(element);
    $('<div class="masked_loading" />').insertAfter($('.masked'));
  } else {
    $('.masked').remove();
    $('.masked_loading').remove();
  }
}
$(function() {
  <?php if ($filter_prices) { ?>
  if (parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()) == parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())) {
    $('#oct-price-slider').remove();
  }
  <?php } ?>
  sort_filter_blocks();
  hide_long_list();
  $('select[id="input-limit"], select[id="input-sort"]').removeAttr('onchange');
  <?php if ($custom_sort) { ?>
    $('select[id="input-sort"]').empty();
    custom_sort_options = '';
    <?php foreach ($custom_sort as $sort_value) { ?>
    custom_sort_options += '<option value="&sort=<?php echo $sort_value["value"]; ?>" <?php if ($sort_value["checked"]) { ?>selected="selected"<?php } ?>><?php echo $sort_value["name"]; ?></option>';
    <?php } ?>
    $('select[id="input-sort"]').append(custom_sort_options);
  <?php } ?>
  <?php if ($custom_limit) { ?>
    $('select[id="input-limit"]').empty();
    custom_limit_options = '';
    <?php foreach ($custom_limit as $limit_value) { ?>
    custom_limit_options += '<option value="&limit=<?php echo $limit_value["value"]; ?>" <?php if ($limit_value["checked"]) { ?>selected="selected"<?php } ?>><?php echo $limit_value["value"]; ?></option>';
    <?php } ?>
    $('select[id="input-limit"]').append(custom_limit_options);
  <?php } ?>
 <?php if ($filter_start) { ?>
	  var page = oct_get_value_from_url($(location).attr('href'), 'page');
    oct_start_filter(true, true, 0, page, '33');
  <?php } ?>
});
$('#button-reset').on('click', function() {
  oct_reset_filter();
});

$(document).on('change', '#oct-product-filter-data input[type=\'text\'], #oct-product-filter-data input[type=\'hidden\'], #oct-product-filter-data input[type=\'radio\'], #oct-product-filter-data input[type=\'checkbox\'], #oct-product-filter-data select', function() {
	<?php if (isset($oct_product_filter_data['how_filter']) && $oct_product_filter_data['how_filter'] == 1) { ?>
		oct_start_filter(true, true, 0, 1, '21');
	<?php } else { ?>
		oct_button_to_filter($(this));
	<?php } ?>
});

$(document).on('change', 'select[id="input-sort"], select[id="input-limit"]', function() {
    oct_start_filter(true, true, 0, 1, '22');
});

function oct_reset_filter() {
    <?php if ($filter_prices) { ?>
        $('#oct-product-filter-data input[name=\'low_price\']').val('<?php echo $filter_range_prices['low_price']; ?>');
        $('#oct-product-filter-data input[name=\'high_price\']').val('<?php echo $filter_range_prices['high_price']; ?>');
    <?php } ?>

    $('#oct-product-filter-data input[type=\'radio\']').removeAttr('checked');
    $('#oct-product-filter-data input[type=\'checkbox\']').removeAttr('checked');
    $('#oct-product-filter-data select').val('');
    oct_start_filter(true, true, 0, 1, '23');
}

function reset_selected_value(value, id, type) {
  /*
  if (type == 'checkbox') {
    $("#oct-product-filter-data #"+id).click();
  }
  */
  if (type == 'checkbox') {
    $("#oct-product-filter-data #"+id).removeAttr('checked');
    oct_start_filter(true, true, 0, 1, '24');
  }

  if (type == 'radio') {
    $("#oct-product-filter-data #"+id).removeAttr('checked');
    oct_start_filter(true, true, 0, 1, '25');
  }

  if (type == 'select') {
    $("#oct-product-filter-data #"+id).parent().val('').change();
	oct_start_filter(true, true, 0, 1, '26');
  }
}
function oct_scroll_to_top() {
  $("html, body").animate({
    scrollTop: $('#oct-filter-tag').offset().top -60
  }, {
    duration: 800,
    easing: "swing"
  });
}
function oct_get_value_from_url(url, type) {
  var value = 0;
  var arr;
  var arr2;

  if (type == 'page') {
      arr = url.split("?");
      var p = arr[1].split("&");

      if (p.length == 1) {
          arr2 = url.split("?");
      } else {
          arr2 = url.split("&");
      }
  }

  if (type == 'sort' || type == 'limit' || type == 'order') {
    arr2 = url.split("&");
  }

  for(var i = 0; i < arr2.length; i++){
    var value = arr2[i].split("=");
    if (value[0] === type) {
      return value[1];
    }
  }
}

function oct_show_more_products() {
	$('#button-show-more-products').append('<i class="fa fa-refresh fa-spin fa-fw"></i>');
	oct_start_filter(true, true, <?php echo $oct_product_filter_data['show_more_limit']; ?>, 1, '34');
}

function oct_button_to_filter(el) {
	var id_el = el.attr('id');

	var oct_pf = [];
	var oct_field_data = $('#oct-product-filter-data input[type=\'text\'], #oct-product-filter-data input[type=\'hidden\'], #oct-product-filter-data input[type=\'radio\']:checked, #oct-product-filter-data input[type=\'checkbox\']:checked, #oct-product-filter-data select').serializeArray();

	$.each(oct_field_data, function(i, field) {
		if (field.value) {
			oct_pf.push({
				name: field.name,
				value: field.value
			});
		}
	});

	oct_pf.push({
		name: 'global_id',
		value: '<?php echo $global_id; ?>'
	  }, {
		name: 'global_type',
		value: '<?php echo $global_type; ?>'
	  }, {
		name: 'route',
		value: '<?php echo $route; ?>'
	  }, {
		name: 'sort',
		value: oct_get_value_from_url($('select[id="input-sort"]').val(), 'sort')
	  }, {
		name: 'order',
		value: oct_get_value_from_url($('select[id="input-sort"]').val(), 'order')
	  }, {
		name: 'limit',
		value: 15
	  }, {
		name: 'page',
		value: 1
	  }, {
		name: 'url',
		value: '<?php echo $url; ?>'
	});

	$.ajax({
		type: 'post',
		url: 'index.php?route=extension/module/oct_product_filter/filterProducts',
		dataType: 'json',
		data: oct_pf,
		beforeSend: function() {
			var t = $('body').find("#" + id_el).parent().offset().top;
			var o = $('body').find("#oct-ismobile").offset().top;

			$('body').find("#oct-ismobile").find(".popover").remove();
			var w_width = $(window).width();

			if (w_width <= '992'){
				var t_t = t-o+18;

				$("#oct-ismobile").append('<div class="popover bottom" style="top:' + t_t + 'px;left:45px;display:block;"><div class="arrow"></div><div class="popover-content"><button class="btn oct-button btn-lg" onclick="oct_start_filter(true, true, 0, 1, "27");"></button></div></div>');
			} else {
				var t_t = t-o-18;

				$("#oct-ismobile").append('<div class="popover right" style="top:' + t_t + 'px;margin-left:25px;left:255px;display:block;"><div class="arrow"></div><div class="popover-content"><button class="btn oct-button btn-lg" onclick="oct_start_filter(true, true, 0, 1, "28");"></button></div></div>');
			}

			$("#oct-ismobile").find('.popover button').html('<span><img style="vertical-align:middle;" src="catalog/view/theme/oct_techstore/image/91.svg" /></span> <?php echo $text_loading_view; ?>...');
        	$("#oct-ismobile").find('.popover button').attr('disabled', 'disabled');
        	$("#oct-ismobile").find('.popover button').addClass('disabled');
		},
		success: function(json) {
			if (json['total']) {
				<?php if (isset($oct_product_filter_data['how_filter']) && $oct_product_filter_data['how_filter'] == 0) { ?>
				$.ajax({
					url: 'index.php?route=extension/module/oct_product_filter',
					type: 'POST',
					data: oct_pf,
					dataType: 'html',
					success: function(data) {
						var selectBox = $('#oct-product-filter-data select').length ? $('#oct-product-filter-data select').val() : 0;
						var haveSelectedOptions = 0;

                        var par_el_b = $('body').find("#" + id_el).parents('div.inner-box').attr('data-type');

						$('#oct-product-filter-data').html($(data).find('#oct-product-filter-data').children());
						button_hide_long_list(par_el_b);
						sort_filter_blocks();
						<?php if ($filter_prices) { ?>
						var oct_price_slider = document.getElementById('oct-price-slider');
						noUiSlider.create(oct_price_slider, {
						  start: [parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()), parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())],
						  behaviour: 'hover',
						  connect: true,
						  margin: 1,
						  range: {
							'min': [<?php echo $filter_range_prices['low_price']; ?>],
							'max': [<?php echo $filter_range_prices['high_price']; ?>]
						  },
						  format: wNumb({
							decimals: 0
						  }),
						});
						oct_price_slider.noUiSlider.on('update', function(values, handle) {
						  $('#oct-product-filter-data input[name=\'low_price\']').val(values[0]);
						  $('#oct-product-filter-data input[name=\'high_price\']').val(values[1]);
						});
						if (parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()) == parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())) {
						  $('#oct-price-slider').remove();
						}
						oct_price_slider.noUiSlider.on('end', function() {
						  oct_start_filter(true, true, 0, 1, '29');

						});
						<?php } ?>
                        if (json['oct_filter']) {
                            $(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).bind('click', function(e) {
								$(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).unbind("click");
								
								e.preventDefault();
							  var page = oct_get_value_from_url($(this).attr('href'), 'page');

							  if (page) {
								oct_start_filter(true, true, 0, page, '35');
							  } else {
								oct_start_filter(true, true, 0, 1, '30');
							  }
							});
                        }
						$(".inner-box .item-content").each(function(i,lines) {
							if ($(this).find("div").length == 0) {
								$(this).prev().hide();
								$(this).hide();
							}
						});

						var t = $('body').find("#" + id_el).parent().offset().top;
						var o = $('body').find("#oct-ismobile").offset().top;
						var w_width = $(window).width();

						if (w_width <= '992'){
							var t_t = t-o+18;
						} else {
							var t_t = t-o-18;
						}

						$(window).on('resize', function(){
							if (w_width <= '992'){
								var t_t = t-o+18;
							} else {
								var t_t = t-o-18;
							}
						});

						$("#oct-ismobile").find('.popover').css({top:t_t});
						$("#oct-ismobile").find('.popover button').html('<?php echo $button_view; ?> (' + json['total'] + ')');
						$("#oct-ismobile").find('.popover button').removeAttr('disabled');
						$("#oct-ismobile").find('.popover button').removeClass('disabled');

						<?php if(isset($oct_product_filter_data['display_empty_totals']) && !$oct_product_filter_data['display_empty_totals']) { ?>
							if (w_width > '992'){
								$('html, body').animate({scrollTop: t-250}, 'slow');
							}
						<?php } ?>
					}
				});
				<?php } elseif (isset($oct_product_filter_data['how_filter']) && $oct_product_filter_data['how_filter'] == 2) { ?>
					var t = $('body').find("#" + id_el).parent().offset().top;
					var o = $('body').find("#oct-ismobile").offset().top;
					var w_width = $(window).width();

					if (w_width <= '992'){
						var t_t = t-o+18;
					} else {
						var t_t = t-o-18;
					}

					$(window).on('resize', function(){
						if (w_width <= '992'){
							var t_t = t-o+18;
						} else {
							var t_t = t-o-18;
						}
					});

					$("#oct-ismobile").find('.popover').css({top:t_t});
					$("#oct-ismobile").find('.popover button').html('<?php echo $button_view; ?> (' + json['total'] + ')');
					$("#oct-ismobile").find('.popover button').removeAttr('disabled');
					$("#oct-ismobile").find('.popover button').removeClass('disabled');
				<?php } ?>
			}
		}
	});
}

// main function
function oct_start_filter(count_totals, render_products, increase_limit, page, test = "_") {
	// console.log("asd "+count_totals +" "+ render_products +" "+ increase_limit +" "+ page + " "+ test);//***
	console.log('test = ' + test);
	$('body').find("#oct-ismobile").find(".popover").remove();
  <?php if ($oct_product_filter_data['mask_on_filtering']) { ?>
  filter_mask('#oct-product-filter-data', true);
  filter_mask('#res-products', true);
  <?php } ?>
  var oct_pf = [];
  var oct_field_data = $('#oct-product-filter-data input[type=\'text\'], #oct-product-filter-data input[type=\'hidden\'], #oct-product-filter-data input[type=\'radio\']:checked, #oct-product-filter-data input[type=\'checkbox\']:checked, #oct-product-filter-data select').serializeArray();

  $.each(oct_field_data, function(i, field) {
    if (field.value) {
      oct_pf.push({
        name: field.name,
        value: field.value
      });
			console.log('2name = ' + field.name + ' ||| value = ' + field.value);
    }
  });
 
  if (increase_limit) {
    $('input[name="temp_limit"]').val(parseInt($('input[name="temp_limit"]').val()) + parseInt(increase_limit));
    var limit = parseInt(oct_get_value_from_url($('select[id="input-limit"]').val(), 'limit')) + parseInt($('input[name="temp_limit"]').val());
  } else {
    var limit = oct_get_value_from_url($('select[id="input-limit"]').val(), 'limit');
  }

	  //***mf begin */
	  var lang_mf = "ru";
	  if(window.location.href.indexOf("/ua/") != -1){
		  lang_mf = "ua";
	  }
	  //***mf end */  
 
  oct_pf.push({
    name: 'global_id',
    value: '<?php echo $global_id; ?>'
  }, {
    name: 'global_type',
    value: '<?php echo $global_type; ?>'
  }, {
    name: 'route',
    value: '<?php echo $route; ?>'
  }, {
    name: 'sort',
    value: oct_get_value_from_url($('select[id="input-sort"]').val(), 'sort')
  }, {
    name: 'order',
    value: oct_get_value_from_url($('select[id="input-sort"]').val(), 'order')
  }, {
    name: 'limit',
    value: limit
  }, {
    name: 'page',
    value: page
  }, {
    name: 'url',
    value: '<?php echo $url; ?>'
  }, {
    name: 'lang_mf',
    value: lang_mf
  });
  $.ajax({
    type: 'post',
    url: 'index.php?route=extension/module/oct_product_filter/filterProducts',
    dataType: 'json',
    data: oct_pf,
    success: function(json) {
      if (render_products) {
        if (json['products']) {
          product = '<div class="row filter-products">';
          if (json['error']) {
            product += '<div class="col-sm-12"><p>'+json['error']+'</p></div>';
          }
          $.each(json['products'], function(i,value) {
		  var class_quantity = (value['quantity'] <= 0 && value['product_preorder_status'] != 1) ? ' no_quantity' : '';
          product += '<div class="product-layout product-list col-sm-6 col-xs-6 122424421">';
            product += '<div class="product-thumb'+ class_quantity +'">';
              product += '<div class="image">';
                if (json['oct_popup_view_status'] == '1' && value['quantity'] > 0) {
                  product += '<div class="quick-view"><a onclick="get_oct_popup_product_view('+value['product_id']+');">'+json['language']['button_popup_view']+'</a></div>';
                }
                if (value['economy']) {
                  product += '<div class="oct-discount-box">';
                    product += '<div class="oct-discount-item">-'+value['economy']+'%</div>';
                  product += '</div>';
                }
                if (value['oct_product_stickers']) {
                  product += '<div class="oct-sticker-box">';
                    $.each(value['oct_product_stickers'], function(i,product_sticker) {
                      product += '<div class="oct-sticker-item" style="color: '+product_sticker['color']+'; background: '+product_sticker['background']+';">'+product_sticker['text']+'</div>';
                    });
                  product += '</div>';
                }
				<?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
                product += '<a href="'+value['href']+'" class="lazy_link"><img data-original="'+value['thumb']+'" src="<?php echo $oct_lazyload_image; ?>" title="'+value['name']+'" alt="'+value['name']+'" class="img-responsive lazy" /></a>';
				<?php } else { ?>
				product += '<a href="'+value['href']+'"><img src="'+value['thumb']+'" title="'+value['name']+'" alt="'+value['name']+'" class="img-responsive" /></a>';
				<?php } ?>
              product += '</div>';
              product += '<div>';
                product += '<div class="caption">';
				 if(value['sku'].length > 0){
					product += '<p class="cat-model">'+json['language']['text_sku']+' <span>'+value['sku']+'</span></p>';
				 }else{
					product += '<p class="cat-model"></p>';
				 }
                  product += '<h4 title="'+value['name']+'"><a href="'+value['href']+'">'+value['name']+'</a></h4>';
                  product += '<div class="rating">';
                  if (value['rating'] > 0) {
                    $.each(["1","2","3","4","5",], function(index,element) {
                      if (value['rating'] < element) {
                        product += '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> ';
                      } else {
                        product += '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span> ';
                      }
                    });
                  } else {
                    $.each(["1","2","3","4","5",], function(index,element) {
                      product += '<span class="fa fa-stack"><i class="fa fa-star-o" aria-hidden="true"></i></span> ';
                    });
                  }
                  product += '</div>';
                  product += '<hr>';
                  product += '<div class="cat-box-effect">';
                    product += '<div class="cat-box-effect-inner">';
                      if (value['price']) {
                        product += '<p class="price">';
                        if (!value['special']) {
                          product += '<span class="common-price">'+value['price']+'</span>';
                        } else {
                          product += '<span class="price-new">'+value['special']+'</span> <span class="price-old">'+value['price']+'</span>';
                        }
                        product += '</p>';
                      }
                      product += '<div class="cart">';
                        if (value['quantity'] > 0) {
                          product += '<a class="button-cart oct-button" title="'+json['language']['button_cart']+'" rel="noindex nofollow" onclick="get_oct_popup_add_to_cart('+value['product_id']+', '+value['minimum']+');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">'+json['language']['button_cart']+'</span></a>';
                        } else {
                          if (value['product_preorder_status'] == 1) {
                            product += '<a class="button-cart out-of-stock-button oct-button" onclick="get_oct_product_preorder('+value['product_id']+');"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">'+value['product_preorder_text']+'</span></a>';
                          } else {
                            product += '<a class="button-cart out-of-stock-button oct-button" href="javascript: void(0);"><i class="fa fa-shopping-basket" aria-hidden="true"></i> <span class="hidden-xs">'+value['product_preorder_text']+'</span></a>';
                          }
                        }
                        product += ' <a rel="noindex nofollow" onclick="get_oct_popup_add_to_wishlist('+value['product_id']+');" title="'+json['language']['button_wishlist']+'" class="wishlist oct-button"><i class="fa fa-heart" aria-hidden="true"></i></a>';
                        product += ' <a rel="noindex nofollow" onclick="get_oct_popup_add_to_compare('+value['product_id']+');" title="'+json['language']['button_compare']+'" class="compare oct-button"><i class="fa fa-sliders" aria-hidden="true"></i></a>';
                      product += '</div>';
                    product += '</div>';
                  product += '</div>';
                  product += '<p class="oct-product-stock">'+json['language']['text_stock']+' <span>'+value['stock']+'</span></p>';
                  product += '<div class="oct-additional-info">';
                  product += '  <p class="oct-product-desc">'+value['description']+'</p>';
                  if (value['oct_options']) {
                    product += '<div class="cat-options">';
                      $.each(value['oct_options'], function(io,option) {
                        if (option['type'] == 'radio') {
                          product += '<div class="form-group">';
                            product += '<label class="control-label">'+option['name']+'</label>';
                            product += '<br/>';
                            if (option['product_option_value']) {
                              $.each(option['product_option_value'], function(pov,povalue) {
                                if (povalue['image']) {
                                  product += '<div class="radio">';
                                    product += '<img src="'+povalue['image']+'" alt="'+povalue['name']+'" class="img-thumbnail" title="'+povalue['name']+'" />';
                                  product += '</div>';
                                } else {
                                  product += '<div class="radio"><label class="not-selected">'+povalue['name']+'</label></div>';
                                }
                              });
                            }
                          product += '</div>';
                        } else {
                          product += '<div class="form-group size-box">';
                            product += '<label class="control-label">'+option['name']+'</label>';
                            product += '<br/>';
                            if (option['product_option_value']) {
                              $.each(option['product_option_value'], function(pov,povalue) {
                                product += '<div class="radio">';
                                  product += '<label class="not-selected">'+povalue['name']+'</label>';
                                product += '</div>';
                              });
                            }
                          product += '</div>';
                        }
                      });
                    product += '</div>';
                  }
                  if (value['oct_attributes']) {
                    product += '<div class="cat-options">';
                      $.each(value['oct_attributes'], function(ia,attribute) {
                        product += '<div class="form-group size-box">';
                          product += '<label class="control-label">'+attribute['name']+'</label>';
                          product += '<br/>';
	                        product += '<span>'+attribute['text']+'</span>';
                        product += '</div>';
                      });
                    product += '</div>';
                  }
                  product += '</div>';
                product += '</div>';
              product += '</div>';
            product += '</div>';
          product += '</div>';
          });
          product += '</div>';
          if (json['show_more_limit_show']) {
            product += '<div class="row">';
            product += '  <div class="col-sm-12 text-center"><button onclick="oct_show_more_products()" id="button-show-more-products" class="oct-button current-link">'+json['results_button_show_more_products']+'</button><br/><br/></div>';
            product += '</div>';
          }
          product += '<div class="row pagination-row">';
          product += '  <div class="col-sm-12 text-center" id="oct-product-pagination">'+json['pagination']+'</div>';
          product += '</div>';
          $('#res-products').html(product);
          $('#res-products').fadeIn();

		  <?php if (isset($oct_lazyload) && $oct_lazyload) { ?>
		  setTimeout(function() {
			$("img.lazy").lazyload();
		  }, 10);
		  <?php } ?>
        }
      }
      <?php if (isset($oct_product_filter_data['enable_seo'])) { ?>
        $.ajax({
          type: 'post',
          url: 'index.php?route=extension/module/oct_product_filter/filterProducts&update_seo_url=1',
          dataType: 'json',
          data: oct_pf,
          success: function(json) {
            window.history.pushState({}, $('h1').html(), json['url'].replace(/&amp;/g, '&'));
          }
        });
      <?php } ?>

      if (count_totals) {
        $.ajax({
          url: 'index.php?route=extension/module/oct_product_filter',
          type: 'POST',
          data: oct_pf,
          dataType: 'html',
          success: function(data) {
            var selectBox = $('#oct-product-filter-data select').length ? $('#oct-product-filter-data select').val() : 0;
            var haveSelectedOptions = 0;
            <?php if ($oct_product_filter_data['selected_values_position'] == 1 || $oct_product_filter_data['selected_values_position'] == 2) { ?>
            $('#oct-selected-values').html('');
            if ($('#oct-product-filter-data input[type=\'checkbox\']:checked').length || $('#oct-product-filter-data input[type=\'radio\']:checked').length || selectBox !== 0) {
              checkbox_item  = '<div class="item-title">';
              checkbox_item += '  <a class="list-group-item item-name"><span class="name"><?php echo $text_selected_values; ?></span></a>';
              checkbox_item += '</div>';
              checkbox_item += '<div class="list-group-item item-content">';

              $.each(['price','manufacturer','option','attribute','sticker','stock','review','standard'], function(i,value) {
                $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                  var $this = $(this);
                  var $items = $this.find('input[type=\'checkbox\']:checked');
                  $.each($items, function(index) {
                    if (index == 0) {
                      checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().find('.name').html()+'</div>';
                    }
                    haveSelectedOptions = 1;
                    checkbox_item += '<div class="checkbox">';
                    checkbox_item += '  <label class="oct-selected-values-label">'+$(this).parent().find('label').html()+'</label>';
                    checkbox_item += '  <span class="label label-danger oct-filter-total-label" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'checkbox\');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>';
                    checkbox_item += '</div>';
                  });
                });

                $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                  var $this = $(this);
                  var $items = $this.find('input[type=\'radio\']:checked');
                  $.each($items, function(index) {
                    if (index == 0) {
                      checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().find('.name').html()+'</div>';
                    }
                    haveSelectedOptions = 1;
                    checkbox_item += '<div class="checkbox">';
                    checkbox_item += '  <label class="oct-selected-values-label">'+$(this).parent().find('label').html()+'</label>';
                    checkbox_item += '  <span class="label label-danger oct-filter-total-label" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'radio\');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>';
                    checkbox_item += '</div>';
                  });
                });

                $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                  var $this = $(this);
                  var $items = $this.find('select option:selected');
                  $.each($items, function(index) {

                    if ($(this).val()) {
                      if (index == 0) {
                        checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().parent().find('.name').html()+'</div>';
                      }
	                   haveSelectedOptions = 1;
                      checkbox_item += '<div class="checkbox">';
                      checkbox_item += '  <label class="oct-selected-values-label">'+$(this).text().replace(/\(.*/, "")+'</label>';
                      checkbox_item += '  <span class="label label-danger oct-filter-total-label" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'select\');"><i class="fa fa-times oct-filter-icon-unselect"></i></span>';
                      checkbox_item += '</div>';
                    }
                  });
                });
              });

              checkbox_item += '</div>';
              if(haveSelectedOptions > 0) {
              checkbox_item += '<button type="button" id="button-reset" class="oct-button" onclick="oct_reset_filter();"><?php echo $button_reset; ?></button>';
			  setTimeout(show_f,500);//**mf**/
              $('#oct-selected-values').append(checkbox_item);
              }
            }
            <?php } elseif ($oct_product_filter_data['selected_values_position'] == 3) { ?>
            $('#oct-selected-values').remove();
            if ($('#oct-product-filter-data input[type=\'checkbox\']:checked').length || $('#oct-product-filter-data input[type=\'radio\']:checked').length || selectBox !== 0) {
              checkbox_item  = '<div id="oct-selected-values">';
                checkbox_item += '<div class="panel panel-default">';
                checkbox_item += '  <div class="panel-heading"><?php echo $text_selected_values; ?></div>';
                checkbox_item += '  <div class="panel-body">';

                $.each(['price','manufacturer','option','attribute','sticker','stock','review','standard'], function(i,value) {
                  $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                    var $this = $(this);
                    var $items = $this.find('input[type=\'checkbox\']:checked');
                    $.each($items, function(index) {
                      if (index == 0) {
                        checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().find('.name').html()+'</div>';
                      }
                      checkbox_item += '<button type="button" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'checkbox\');" class="btn btn-default btn-xs">'+$(this).parent().find('label').html()+' <i class="fa fa-times oct-filter-icon-unselect"></i></button>';
                    });
                  });
                  $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                    var $this = $(this);
                    var $items = $this.find('input[type=\'radio\']:checked');
                    $.each($items, function(index) {
                      if (index == 0) {
                        checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().find('.name').html()+'</div>';
                      }
                      checkbox_item += '<button type="button" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'radio\');" class="btn btn-default btn-xs">'+$(this).parent().find('label').html()+' <i class="fa fa-times oct-filter-icon-unselect"></i></button>';
                    });
                  });
                  $('#oct-product-filter-data [data-type="'+value+'"] > div').each(function(index) {
                    var $this = $(this);
                    var $items = $this.find('select option:selected');
                    $.each($items, function(index) {
                      if ($(this).val()) {
                        if (index == 0) {
                          checkbox_item += '<div class="oct-selected-group-label">'+$(this).parent().parent().parent().parent().find('.name').html()+'</div>';
                        }
                        checkbox_item += '<button type="button" onclick="reset_selected_value(this,\''+$(this).attr('id')+'\', \'select\');" class="btn btn-default btn-xs">'+$(this).text().replace(/\(.*/, "")+' <i class="fa fa-times oct-filter-icon-unselect"></i></button>';
                      }
                    });
                  });
                });
                checkbox_item += '  </div>';
                checkbox_item += '</div>';
              checkbox_item += '</div>';
              $('#res-products').before(checkbox_item);
            }
            <?php } ?>
            $('#oct-product-filter-data').html($(data).find('#oct-product-filter-data').children());
            hide_long_list();
            sort_filter_blocks();
            <?php if ($filter_prices) { ?>
            var oct_price_slider = document.getElementById('oct-price-slider');
            noUiSlider.create(oct_price_slider, {
              start: [parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()), parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())],
              behaviour: 'hover',
              connect: true,
              margin: 1,
              range: {
                'min': [<?php echo $filter_range_prices['low_price']; ?>],
                'max': [<?php echo $filter_range_prices['high_price']; ?>]
              },
              format: wNumb({
                decimals: 0
              }),
            });
            oct_price_slider.noUiSlider.on('update', function(values, handle) {
              $('#oct-product-filter-data input[name=\'low_price\']').val(values[0]);
              $('#oct-product-filter-data input[name=\'high_price\']').val(values[1]);
            });
            if (parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()) == parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())) {
              $('#oct-price-slider').remove();
            }
            oct_price_slider.noUiSlider.on('end', function() {
              oct_start_filter(true, true, 0, 1, "1");

            });
            <?php } ?>
            if (json['oct_filter']) {
				$(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).bind('click', function(e) {
					$(<?php if (isset($oct_product_filter_data['enable_seo'])) { ?>'#content .pagination a'<?php } else { ?>'#oct-product-pagination a'<?php } ?>).unbind("click");
					
					e.preventDefault();
				  var page = oct_get_value_from_url($(this).attr('href'), 'page');

				  if (page) {
					oct_start_filter(true, true, 0, page, '31');
				  } else {
					oct_start_filter(true, true, 0, 1, "2");
				  }
				});
			}
			$(".inner-box .item-content").each(function(i,lines) {
				if ($(this).find("div").length == 0) {
					$(this).prev().hide();
					$(this).hide();
				}
			});
          }
        });
      }
      <?php if ($filter_prices) { ?>
      noUiSlider;
      oct_price_slider.noUiSlider.set([parseInt($('#oct-product-filter-data input[name=\'low_price\']').val()), parseInt($('#oct-product-filter-data input[name=\'high_price\']').val())]);
      <?php } ?>
       $("#filter-mobile").removeClass("filter-show");
      <?php if ($oct_product_filter_data['mask_on_filtering']) { ?>
      filter_mask('#oct-product-filter-data', false);
      filter_mask('#res-products', false);
      <?php } ?>
      if (localStorage.getItem('display') == 'list') {
        $('#list-view').trigger('click');
      } else {
        $('#grid-view').trigger('click');
      }
      if (!increase_limit) {
        oct_scroll_to_top();
      }
      history.pushState({}, $('h1').html(), json['url'].replace(/&amp;/g, '&'));
	  $('input[name="redirect"]').val(json['url'].replace(/&amp;/g, '&'));
    }
  });
  //setTimeout(show_f, 5000);//***mf
}
//--></script>

<?php if(strpos($_SERVER['QUERY_STRING'], "/filter/") !== false || strpos($_SERVER['QUERY_STRING'], "oct_filter=1") !== false){ ?>
<script>
//***mf begin
$( window ).load(function() {
  oct_start_filter(true, true, 0, 1, "3");
  // setTimeout(show_f,5000);
});
//***mf end
</script>
<?php } ?>