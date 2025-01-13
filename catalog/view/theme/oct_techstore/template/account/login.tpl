<?php echo $header; ?>
<div class="wrap fdc login_page">
	
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

	<h1 class="title_module text-center"><?php echo $heading_title; ?><?php if (!empty($seo_page)) { echo $seo_page; } ?></h1>
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
		<?php } ?>
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
		<?php } ?>
		<div id="content" class="account-content">
			<?php echo $content_top; ?>

	
					<div class="well">
						<h2></i><?php echo $text_returning_customer; ?></h2>						
						<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
							<div class="form-group text-center">
								
									<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
								
							</div>

							
							<div class="form-group pas_wrap">
								
								<input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
								<span class="password-toggle" onclick="passwordToggle($(this));"><i class="fa fa-eye"></i></span>	
								
							</div>
							
							<div class="button_wrap">
								<input type="submit" value="<?php echo $button_login; ?>" class="oct-button btn-green" />
							</div>
							<a href="<?php echo $forgotten; ?>" class="forgotten-btn"><?php echo $text_forgotten; ?></a>
							<?php if ($redirect) { ?>
								<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
							<?php } ?>
						</form>
					</div>
				
				
					<div class="well" >
						
							<h2><?php echo $text_enter_social; ?></h2>							
							<div class="btn-group-register" id="no-pwa-register">
											
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
			
			
			<? /*	
				<div class="col-sm-6">
				<div class="well" style="padding:20px;"> 
				<h2><i class="fa fa-user-plus"></i><?php echo $text_new_customer; ?></h2>	
				<div class="row">
				<div class="col-xs-12">
				<p class="text" style="height:auto;min-height:120px;"><?php echo $text_register_account; ?></p>
				</div>
				</div>
				<div class="row">
				<div class="col-xs-12">
				<input type="submit" onclick="location.href='<?php echo $register; ?>'" value="<?php echo $text_register; ?>" class="oct-button" style="width:100%" />
				</div>
				</div>
				</div>
			</div>			*/ ?>
		</div>
		
		<?php echo $content_bottom; ?>
	
</div>
<?php echo $footer; ?>