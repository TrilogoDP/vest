<div id="auth-popup" class="white-popup mfp-with-anim narrow-popup">
	<h2 class="popup-header"><?php echo $heading_title; ?></h2>
	<? /* <div class="popup-text">
		<p class="blue"><?php echo $text_login; ?></p>
		</div>
	*/ ?>
	<div class="popup-login-error-text"></div>
	<div class="popup-form-box">
		<form method="post" enctype="multipart/form-data" id="popup-login-form">
			<input class="input-text" title="<?php echo $entry_email; ?>" placeholder="<?php echo $entry_email; ?>" type="text" name="email" />
			<div class="pas_wrap">
				<input class="input-text" title="<?php echo $entry_password; ?>" placeholder="<?php echo $entry_password; ?>" type="password" name="password" />
				<span class="password-toggle" onclick="passwordToggle($(this));"><i class="fa fa-eye"></i></span>	
			</div>
			<?php if ($text_terms) { ?>
				<div>
					<?php echo $text_terms; ?> <input type="checkbox" name="terms" value="1" style="width:auto;height:auto;display:inline-block;margin: 0;" />
				</div>
			<?php } ?>
		</form>
		<button class="oct-button" title="<?php echo $button_login; ?>" type="button" id="popup-login-button"><?php echo $button_login; ?></button>

		<div class="auth-popup-links">
			<a class="reg-popup-link" href="<?php echo $register_url; ?>"><?php echo $button_register; ?></a><br/>
			<a class="forget-popup-link" href="<?php echo $forgotten_url; ?>"><?php echo $button_forgotten; ?></a>
		</div>
	</div>
	
	<h2 class="popup-header"><?php echo $text_enter_social; ?></h2>
	<div class="row btn-group-register">
		
		<div class="auth_btn_wrap">
			<button type="button" onclick="social_auth.googleplus(this)" data-loading-text="Loading" class="btn btn-primary btn-google">
				<span>Google</span>      
				<div class="btn-img">
                 	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" width="46px" height="46px" viewBox="0 0 46 46" version="1.1">
					    <defs>
					        <filter x="-50%" y="-50%" width="200%" height="200%" filterUnits="objectBoundingBox" id="filter-1">
					            <feOffset dx="0" dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/>
					            <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter1" result="shadowBlurOuter1"/>
					            <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.168 0" in="shadowBlurOuter1" type="matrix" result="shadowMatrixOuter1"/>
					            <feOffset dx="0" dy="0" in="SourceAlpha" result="shadowOffsetOuter2"/>
					            <feGaussianBlur stdDeviation="0.5" in="shadowOffsetOuter2" result="shadowBlurOuter2"/>
					            <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.084 0" in="shadowBlurOuter2" type="matrix" result="shadowMatrixOuter2"/>
					            <feMerge>
					                <feMergeNode in="shadowMatrixOuter1"/>
					                <feMergeNode in="shadowMatrixOuter2"/>
					                <feMergeNode in="SourceGraphic"/>
								</feMerge>
							</filter>
					        <rect id="path-2" x="0" y="0" width="40" height="40" rx="2"/>
						</defs>
					    <g id="Google-Button" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
					        <g id="9-PATCH" sketch:type="MSArtboardGroup" transform="translate(-608.000000, -160.000000)"/>
					        <g id="btn_google_light_normal" sketch:type="MSArtboardGroup" transform="translate(-1.000000, -1.000000)">
					            
					            <g id="logo_googleg_48dp" sketch:type="MSLayerGroup" transform="translate(15.000000, 15.000000)">
					                <path d="M17.64,9.20454545 C17.64,8.56636364 17.5827273,7.95272727 17.4763636,7.36363636 L9,7.36363636 L9,10.845 L13.8436364,10.845 C13.635,11.97 13.0009091,12.9231818 12.0477273,13.5613636 L12.0477273,15.8195455 L14.9563636,15.8195455 C16.6581818,14.2527273 17.64,11.9454545 17.64,9.20454545 L17.64,9.20454545 Z" id="Shape" fill="#4285F4" sketch:type="MSShapeGroup"/>
					                <path d="M9,18 C11.43,18 13.4672727,17.1940909 14.9563636,15.8195455 L12.0477273,13.5613636 C11.2418182,14.1013636 10.2109091,14.4204545 9,14.4204545 C6.65590909,14.4204545 4.67181818,12.8372727 3.96409091,10.71 L0.957272727,10.71 L0.957272727,13.0418182 C2.43818182,15.9831818 5.48181818,18 9,18 L9,18 Z" id="Shape" fill="#34A853" sketch:type="MSShapeGroup"/>
					                <path d="M3.96409091,10.71 C3.78409091,10.17 3.68181818,9.59318182 3.68181818,9 C3.68181818,8.40681818 3.78409091,7.83 3.96409091,7.29 L3.96409091,4.95818182 L0.957272727,4.95818182 C0.347727273,6.17318182 0,7.54772727 0,9 C0,10.4522727 0.347727273,11.8268182 0.957272727,13.0418182 L3.96409091,10.71 L3.96409091,10.71 Z" id="Shape" fill="#FBBC05" sketch:type="MSShapeGroup"/>
					                <path d="M9,3.57954545 C10.3213636,3.57954545 11.5077273,4.03363636 12.4404545,4.92545455 L15.0218182,2.34409091 C13.4631818,0.891818182 11.4259091,0 9,0 C5.48181818,0 2.43818182,2.01681818 0.957272727,4.95818182 L3.96409091,7.29 C4.67181818,5.16272727 6.65590909,3.57954545 9,3.57954545 L9,3.57954545 Z" id="Shape" fill="#EA4335" sketch:type="MSShapeGroup"/>
					                <path d="M0,0 L18,0 L18,18 L0,18 L0,0 Z" id="Shape" sketch:type="MSShapeGroup"/>
								</g>
					            <g id="handles_square" sketch:type="MSLayerGroup"/>
							</g>
						</g>
					</svg>           
				</div>
			</button>
			<button type="button" onclick="social_auth.facebook(this)" data-loading-text="Loading" class="btn btn-primary btn-facebook">
				<span>Facebook</span> 
				<svg width="17" height="30" viewBox="0 0 17 30" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.6143 15.9644H15.3601L16.1052 11.1139H10.6134V8.46293C10.6134 6.44798 11.2678 4.66123 13.1412 4.66123H16.1516V0.428389C15.6226 0.35653 14.504 0.199219 12.3903 0.199219C7.97651 0.199219 5.38886 2.54433 5.38886 7.88709V11.1139H0.851562V15.9644H5.38886V29.296C6.28745 29.432 7.19761 29.5242 8.1319 29.5242C8.97643 29.5242 9.80069 29.4465 10.6143 29.3358V15.9644Z" fill="white"/>
				</svg> 
			</button>	
		</div>		
	</div>
	
	
	<script><!--
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
		$('#popup-login-button').on('click', function() {
			masked('#auth-popup', true);
			$.ajax({
				type: 'post',
				url:  'index.php?route=extension/module/oct_popup_login/login',
				dataType: 'json',
				data: $('#popup-login-form').serialize(),
				success: function(json) {
					$('.popup-login-error-text .text-danger').remove();
					
					if (json['warning']) {
						masked('#auth-popup', false);
						$('.popup-login-error-text').html('<div class="text-danger">'+json['warning']+'</div>');
						} else {
						masked('#auth-popup', false);
						$('.popup-login-error-text').hide();
						location = '<?php echo $account_url; ?>';
					}
				}
			});
		});
	//--></script>
</div>