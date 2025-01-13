
<div class="title"><?php echo $heading_title; ?></div>


  <div class="subscribe-content" id="static-subscribe-wrapper-footer">
    <form method="post" enctype="multipart/form-data" id="static-subscribe-form-footer">
      <div class="input-box">
        <input id="newsletter" class="input-text required-entry validate-email" title="<?php echo $enter_email; ?>" type="text" name="email" placeholder="<?php echo $enter_email; ?>" />
      </div>
      <?php if ($text_terms) { ?>
        <div class="oct-text-terms"><?php echo $text_terms; ?> <input type="checkbox" name="terms" value="1" style="width:auto;height:auto;display:inline-block;margin: 0;" /></div>
      <?php } ?>
      <div class="actions">
        <button class="button" id="static-subscribe-button-footer" title="<?php echo $button_subscribe; ?>" type="button"><span><?php echo $button_subscribe; ?></span> <i class="fa fa-angle-right"></i></button>
      </div>
    </form> 
  </div>

<script>
function masked(element, status) {
  if (status == true) {
    $('<div/>')
    .attr({ 'class':'masked' })
    .prependTo(element);
    $('<div class="masked_loading" />').insertAfter($('.masked'));
  } else {
    $('.masked').remove();
    $('.masked_loading').remove();
  }
}
$('#static-subscribe-button-footer').on('click', function() {
  masked('#static-subscribe-wrapper-footer', true);
  $.ajax({
    type: 'post',
    url:  'index.php?route=extension/module/oct_static_subscribe/make_subscribe',
    dataType: 'json',
    data: $('#static-subscribe-form-footer').serialize(),
    success: function(json) {
      $('#static-subscribe-wrapper-footer .alert').remove();

      if (json['error']) {
        masked('#static-subscribe-wrapper-footer', false);
        $('#static-subscribe-form-footer').after('<div class="alert alert-danger"><i class="fa fa-check-circle"></i> '+json['error']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }

      if (json['output']) {
        masked('#static-subscribe-wrapper-footer', false);
        $('#static-subscribe-form-footer').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+json['output']+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
      }
    }
  });
});
</script>