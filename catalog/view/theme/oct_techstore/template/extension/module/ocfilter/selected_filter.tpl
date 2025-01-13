
<script>
    function displayPreloader(){
        $('.ocfilter').append('<div class="webfun_ocfilter_preloader"></div>');
        $('#res-products').eq(0).append('<div class="webfun_ocfilter_preloader"></div>');
    }
</script>
<div class="webfun_selected_options_wrapper">
    <?php if ($selecteds) { ?>
        <div class="list-group-item selected-options">
            <?php foreach ($selecteds as $key => $option) { ?>
                <div class="ocfilter-option">
                    <span><?php echo $option['name']; ?>:</span>
                    <?php foreach ($option['values'] as $value) { ?>
                        <button type="button" class="btn btn-xs btn-danger" style="padding: 1px 4px;" data-wf-option-id="<?php echo $key; ?>" data-wf-option-id-value="<?php echo $value['id']; ?>"><i class="fa fa-times"></i> <?php echo $value['name']; ?></button>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php //$count = count($selecteds); $selected = $selecteds; $first = array_shift($selected); ?>
            <?php //if ($count > 1 || count($first['values']) > 1) { ?>
            <button type="button" class="btn btn-block btn-danger" style="border-radius: 0;" onclick="displayPreloader(); location = '<?php echo $link; ?>';"><i class="fa fa-times-circle"></i> <?php echo $text_cancel_all; ?></button>
            <?php //} ?>
        </div>
    <?php } ?>
</div>