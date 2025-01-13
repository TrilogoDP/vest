<div id="callback-popup" class="white-popup mfp-with-anim narrow-popup" data-effect="mfp-move-from-top">
	<h2 class="popup-header"><?php echo $heading_title; ?></h2>
	<?php if (isset($oct_techstore_cont_phones)) { ?>
		<div class="oct-m-phones-popup">
			<ul>
				<?php foreach($oct_techstore_cont_phones as $element) { ?>
					<li><i class="fa fa-volume-control-phone" aria-hidden="true"></i><a href="#" class="phoneclick" onclick="window.location.href='tel:+<?php echo preg_replace('/\D/', '', $element); ?>';return false;"><?php echo $element; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
	<?php if ((isset($oct_techstore_data['ps_whatsapp_id']) && strlen($oct_techstore_data['ps_whatsapp_id']) > 1) or (isset($oct_techstore_data['ps_telegram_id']) && strlen($oct_techstore_data['ps_telegram_id']) > 1) or (isset($oct_techstore_data['ps_viber_id']) && strlen($oct_techstore_data['ps_viber_id']) > 1)) { ?>
		<div class="oct-messengers">
		<?php } ?>
		<?php if(isset($oct_techstore_data['ps_whatsapp_id']) && strlen($oct_techstore_data['ps_whatsapp_id']) > 1) { ?>
			<a class="oct-messengers-whatsapp" rel="nofollow" href="https://api.whatsapp.com/send?phone=<?php echo $oct_techstore_data['ps_whatsapp_id']; ?>" title="Whatsapp" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
		<?php } ?>
		<?php if(isset($oct_techstore_data['ps_telegram_id']) && strlen($oct_techstore_data['ps_telegram_id']) > 1) { ?>
			<a class="oct-messengers-telegram" rel="nofollow" href="http://t.me/<?php echo $oct_techstore_data['ps_telegram_id']; ?>" title="Telegram" target="_blank"><i class="fa fa-telegram" aria-hidden="true"></i></a>
		<?php } ?>
		<?php if(isset($oct_techstore_data['ps_viber_id']) && strlen($oct_techstore_data['ps_viber_id']) > 1) { ?>
			<a rel="nofollow" class="oct-messengers-viber viber-mobile" href="viber://add?number=<?php echo $oct_techstore_data['ps_viber_id']; ?>" title="Viber" target="_blank"><i class="fa fa-viber" aria-hidden="true"></i></a>
			<a rel="nofollow" class="oct-messengers-viber viber-desktop" href="viber://chat?number=<?php echo $oct_techstore_data['ps_viber_id']; ?>" title="Viber" target="_blank"><i class="fa fa-viber" aria-hidden="true"></i></a>
		<?php } ?>
		<?php if ((isset($oct_techstore_data['ps_whatsapp_id']) && strlen($oct_techstore_data['ps_whatsapp_id']) > 1) or (isset($oct_techstore_data['ps_telegram_id']) && strlen($oct_techstore_data['ps_telegram_id']) > 1) or (isset($oct_techstore_data['ps_viber_id']) && strlen($oct_techstore_data['ps_viber_id']) > 1)) { ?>
		</div>
	<?php } ?>
	<div class="popup-form-box">
		<div id="popup-center" class="popup-center">
		
			<div>
				<?php echo $text_call_back; ?>
			</div>
		
			<form method="post" enctype="multipart/form-data" id="call-phone-form">
				<input value="<?php echo $referer;?>" type="hidden" name="referer" />
				<?php if ($oct_popup_call_phone_data['name']) { ?>
					<input class="input-text" value="<?php echo $name;?>" placeholder="<?php echo $enter_name; ?>" type="text" name="name" />
				<?php } ?>
				<?php if ($oct_popup_call_phone_data['telephone']) { ?>
					<input class="input-text" value="<?php echo $telephone;?>" placeholder="<?php echo $enter_telephone; ?>" type="text" name="telephone" />
					<?php if ($mask) { ?>
						<script>
							var isMobile = {
								Android: function() {
									return navigator.userAgent.match(/Android/i);
								},
								BlackBerry: function() {
									return navigator.userAgent.match(/BlackBerry/i);
								},
								iOS: function() {
									return navigator.userAgent.match(/iPhone|iPad|iPod/i);
								},
								Opera: function() {
									return navigator.userAgent.match(/Opera Mini/i);
								},
								Windows: function() {
									return navigator.userAgent.match(/IEMobile/i);
								},
								Chrome: function() {
									return navigator.userAgent.match(/Chrome/i);
								}
							};
							
							if( !isMobile.Android() ) {
								$("#callback-popup [name='telephone']").inputmask('<?php echo $mask; ?>');
							}
						</script>
					<?php } ?>
				<?php } ?>
				<?php if ($oct_popup_call_phone_data['time']) { ?>
					<input class="input-text datetime" value="<?php echo $time;?>" placeholder="<?php echo $enter_time; ?>" type="text" name="time" />
				<?php } ?>
				<?php if ($oct_popup_call_phone_data['comment']) { ?>
					<textarea name="comment" placeholder="<?php echo $enter_comment; ?>"><?php echo $comment;?></textarea>
				<?php } ?>
				<?php if ($text_terms) { ?>
					<div>
						<?php echo $text_terms; ?> <input type="checkbox" name="terms" value="1" style="width:auto;height:auto;display:inline-block;margin: 0;" />
					</div>
					<br/>
				<?php } ?>
			</form>
		</div>
		<div id="popup-counter" class="popup-center" style="display:none;">
			<div class="uptocall-mini-phone"><i class="fa fa-phone" aria-hidden="true"></i></div><hr/><div><?php echo $text_wait; ?></div>
		</div>
		<button class="oct-button" title="<?php echo $button_send; ?>" type="button" id="popup-send-button"><?php echo $button_send; ?></button>
	</div>
	<style>	
		.uptocall-mini-phone {
		display:inline-block;
		-moz-border-radius: 50% !important;
		-webkit-border-radius: 50% !important;
		border-radius: 50% !important;
		-moz-background-clip: padding;
		-webkit-background-clip: padding-box;
		background-clip: padding-box;
		background-color: #0e8f00;
		width: 75px;
		height: 75px;
		transform: rotate(40deg);
		border: 10px solid rgba(21,150,175,.15);
		}
		.uptocall-mini-phone i{
		font-size: 32px;
		position: relative;
		color:#FFF;
		top: 12px;
		left: 2px;
		-webkit-animation: uptocallphone 1.5s linear infinite;
		-moz-animation: uptocallphone 1.5s linear infinite;
		animation: uptocallphone 1.5s linear infinite;
		}
	</style>
	<?php if ($oct_popup_call_phone_data['time']) { ?>
		<script src="catalog/view/javascript/jquery/datetimepicker/moment.js"></script>
		<script src="catalog/view/javascript/jquery/datetimepicker/locale/ru.js"></script>
		<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>		
		<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" />
		<script><!--
			$('#callback-popup .date').datetimepicker({
			pickTime: false,
			});
			
			var nowDate = new Date();
			var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
			
			$('#callback-popup .datetime').datetimepicker({
			pickDate: true,
			pickTime: true,
			minDate: today
			});
			
			$('#callback-popup .time').datetimepicker({
			pickDate: false,
			});
			
		//--></script>
	<?php } ?>
	<script><!--
		function masked(element, status) {
			if (status == true) {
				$('<div/>').attr({ 'class':'masked' }).prependTo(element);
				$('<div class="masked_loading" />').insertAfter($('.masked'));
				} else {
				$('.masked').remove();
				$('.masked_loading').remove();
			}
		}
		
		function primitiveTimer(){
			var display = $('code#popup-phone-time');
			var timeLeft = parseInt(display.text());
			
			var timer = setInterval(function(){
				if (--timeLeft >= 0) {
					display.text(timeLeft);
					} else {    	
					clearInterval(timer)
				}
			}, 1000);
		}
		
		$('#popup-send-button').on('click', function() {
			if ($(".micro-price").length){
				var perprice = ($(".micro-price").text());
				} else {
				var perprice = 'nope';
			}
			masked('#callback-popup #popup-center', true);
			
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/oct_popup_call_phone/validate',
				dataType: 'json',
				data: $('#call-phone-form').serialize(),
				
				success: function(json) {
					if (json['error']) {
						if (json['error']['field']) {
							masked('#callback-popup .popup-center', false);
							$('#callback-popup .text-danger').remove();
							$.each(json['error']['field'], function(i, val) {
								$('#callback-popup [name="' + i + '"]').addClass('error_style').after('<div class="text-danger">' + val + '</div>');
							});
						}
						} else {
						$.ajax({
							type: 'post',
							url:  'index.php?route=extension/module/oct_popup_call_phone/send',
							dataType: 'json',
							data: $('#call-phone-form').serialize(),
							beforeSend : function(){																				
								$('#callback-popup .oct-m-phones-popup').hide(); 
								$('#callback-popup #popup-send-button').hide();  
								$('#callback-popup #popup-center').html('');
								$('#callback-popup #popup-counter').show();
								primitiveTimer();
								
								if (perprice === "nope"){
									ga('send', 'event', 'uptocall', 'click');
									} else {
									ga('send', 'event', 'uptocall', 'click', '', parseInt((parseInt(perprice)/3)));
								}
							},
							success: function(json) {
								if (json['error']) {
									if (json['error']['field']) {
										masked('#callback-popup .popup-center', false);
										$('#callback-popup .text-danger').remove();
										$.each(json['error']['field'], function(i, val) {
											$('#callback-popup [name="' + i + '"]').addClass('error_style').after('<div class="text-danger">' + val + '</div>');
										});
									}
									} else {
									if (json['output']) {
										masked('#callback-popup .popup-center', false);
										$('#popup-send-button').remove();
										$('#callback-popup #popup-counter').html(json['output']);
										
										 passEventToDataLayer('callbackorder', 'callback', 'send', 'callbackfrompopup');
									}
								}
							}
						});
					}
				}
			});
			
		});
	//--></script>
</div>
