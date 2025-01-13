<div id="footer_app" class="app_wrap" style="display: none;">
	<div class="container">
		<div class="row">
			<div class="col-12 app_content">
				<span class="text"><?php echo $text_retranslate_app_block; ?></span>
				<button id="download_app" class="app_btn">
					<i class="fa fa-cloud-download"></i>
					<?php echo $text_retranslate_app_install; ?>
				</button>	
			</div>
		</div>
	</div>
</div>

<script>

	function passwordToggle(elem){
	let passwdInput = elem.prev('input');
	let eye = elem.children('i');
	
	if (passwdInput.attr('type') === "password") {
		passwdInput.attr('type', 'text');
		eye.removeClass('fa-eye, fa-eye-slash').addClass('fa-eye-slash');
		} else {
		passwdInput.attr('type', 'password');
		eye.removeClass('fa-eye, fa-eye-slash').addClass('fa-eye');
	}
}

$(document).ready(function() {
	$('input[type=password]').after('<span class="password-toggle" onclick="passwordToggle($(this));"><i class="fa fa-eye"></i></span>');
});

//  social_auth

var social_auth = {

    'googleplus': function(th) {
        
        var button = th;
        
        var text_old = $(button).html();
        var text_loading = $(button).attr('data-loading-text');
        if(text_loading == ''){
            text_loading = 'Loading';
        }

        // login googleplus

        $(button).html(text_loading);

        var googleplus_url = '<?php echo $gp_login; ?>';

        var w = 500;
        var h = 600;

        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;


        window.open(googleplus_url, 'googleplus auth', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);


    },
    'facebook': function(th) {
        
        var button = th;
        
        var text_old = $(button).html();
        var text_loading = $(button).attr('data-loading-text');
        if(text_loading == ''){
            text_loading = 'Loading';
        }

        // Facebook instagram

        $(button).html(text_loading);

        var url = '<?php echo $fb_login; ?>';

        var w = 500;
        var h = 600;

        var left = (screen.width - w) / 2;
        var top = (screen.height - h) / 4;


        window.open(url, 'Facebook auth', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);


    }
}




</script>

<footer>	

	<div class="wrap jcc footer_wrap">


				<div class="item copyright_wrap">
					<span class="oct-copy">
						<div class="logo df">
							<a href="<?php echo $home; ?>">
								<svg version="1.1" id="Слой_1" width="135" height="50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 256.2 76.5" style="enable-background:new 0 0 256.2 76.5;" xml:space="preserve">
									<style type="text/css">
										.st0{fill:#FFFFFF;}
										.st1{fill:#49B9AE;}
									</style>

									<path class="st0" d="M57.7,6.2c-0.3-1.3-1-2.5-1.8-3.4c-0.8-0.9-1.8-1.6-3-2.1C51.7,0.3,50.5,0,49.2,0c-1.7,0-3.3,0.5-4.7,1.5
										c-1.5,1-2.5,2.6-3.1,4.6c-2.1,7.2-4.3,14.8-6.6,22.4c-1.8,5.8-3.7,11.8-5.7,17.7c-1.9-5.9-3.8-11.7-5.5-17.5
										c-2.2-7.6-4.4-15.1-6.5-22.3c-0.7-2.1-1.8-3.7-3.3-4.8C12.4,0.5,10.7,0,9,0C7.8,0,6.5,0.3,5.3,0.8C4.1,1.3,3,2.1,2.2,3
										C1.3,4,0.6,5.2,0.3,6.5c-0.4,1.4-0.3,3,0.1,4.6c2.6,9.1,5.4,18.5,8.4,28c3,9.4,6.3,19.2,9.7,29.1c1.1,3.1,2.4,5.2,4.2,6.5
										c1.7,1.2,3.8,1.9,6.1,1.9c2.1,0,4.1-0.6,5.9-1.9c1.8-1.3,3.3-3.4,4.3-6.4c3.5-10,6.8-19.9,9.9-29.4c3.1-9.5,6-19,8.6-28.2
										C57.9,9.1,58,7.6,57.7,6.2z"/>
									<path class="st0" d="M191.3,41.7c-1.6-2.9-4-5.3-7.4-7.2c-1.3-0.7-2.8-1.3-4.4-1.9c-1.6-0.5-3.3-1-5-1.5c-1.7-0.4-3.5-0.8-5.3-1.2
										c-1.7-0.3-3.4-0.7-5-1.1c-3.3-0.8-5.8-1.7-7.2-2.6c-0.4-0.3-1.6-1.1-1.6-3.7c0-2,0.8-3.5,2.5-4.6c1.9-1.2,4.7-1.9,8.4-1.9
										c1.5,0,2.8,0.1,3.9,0.2c1.1,0.2,2.1,0.3,3,0.5c1.5,0.3,2.7,0.6,3.6,1c1.3,0.5,2.6,0.7,4,0.7c2.8,0,5-0.9,6.6-2.7
										c1.5-1.7,2.3-3.6,2.3-5.6c0-1.7-0.5-3.3-1.6-4.7c-1.1-1.5-2.8-2.6-5-3.2c-2.8-0.9-5.7-1.5-8.7-1.8c-2.9-0.3-6-0.5-9.3-0.5
										c-3.8,0-7.5,0.5-10.9,1.6c-3.5,1-6.6,2.6-9.2,4.6c-2.6,2.1-4.8,4.6-6.3,7.7c-1.5,3-2.3,6.6-2.3,10.5c0,4.5,1,8.1,3,10.8
										c1.9,2.6,4.4,4.8,7.4,6.4c1.3,0.6,2.9,1.2,4.6,1.7c1.7,0.5,3.4,1,5.1,1.3c1.7,0.4,3.4,0.8,5.1,1.1c1.6,0.4,3.1,0.7,4.4,1
										c4.6,1.2,6.6,2.2,7.4,2.9c1,0.8,1.4,1.9,1.4,3.4c0,2.3-0.9,4-2.8,5.3c-2,1.4-5.5,2.1-10.4,2.1c-1.8,0-3.4-0.1-4.7-0.3
										c-1.4-0.2-2.6-0.4-3.7-0.6c-1.9-0.4-3.4-0.9-4.4-1.3c-1.5-0.6-3-0.9-4.5-0.9c-2.6,0-4.8,0.9-6.3,2.6c-1.4,1.6-2.1,3.5-2.1,5.7
										c0,1.6,0.5,3.2,1.5,4.6c1,1.5,2.7,2.6,4.8,3.4c3.2,1.1,6.5,1.8,9.9,2.2c3.3,0.4,7,0.6,10.8,0.6c4.3,0,8.3-0.5,12-1.5
										c3.7-1,7-2.6,9.8-4.6c2.8-2.1,5-4.7,6.6-7.9c1.6-3.1,2.4-6.8,2.4-10.9C193.6,47.9,192.8,44.6,191.3,41.7z"/>
									<path class="st0" d="M253.9,2.5C252.3,0.8,250.3,0,248,0h-41.4c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1
										c0,2.4,0.8,4.4,2.4,6.1c1.6,1.7,3.5,2.5,5.8,2.5h12.3v50.5c0,2.4,0.8,4.5,2.4,6.2c1.6,1.7,3.6,2.6,6,2.6c2.3,0,4.3-0.9,6-2.6
										c1.6-1.7,2.4-3.8,2.4-6.2V17.2H248c2.3,0,4.2-0.8,5.8-2.5c1.6-1.7,2.4-3.7,2.4-6.1C256.2,6.2,255.4,4.2,253.9,2.5z"/>

									<path class="st1" d="M119.5,17c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
										H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z"/>
									<path class="st1" d="M119.5,46.7c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
										H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z"/>
									<path class="st1" d="M119.5,76.5c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
										H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z"/>

								</svg>
							</a>
						</div>
						<div class="mb-hidden">
							<?php echo $oct_powered; ?>
						</div>
						
					</span>
					<div class="payment-box mb-hidden">
						<?php if ($oct_techstore_data['ps_sberbank'] == 'on') { ?>
							<span class="sberbank"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_privat'] == 'on') { ?>
							<span class="privat24"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_yamoney'] == 'on') { ?>
							<span class="yandex-money"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_webmoney'] == 'on') { ?>
							<span class="webmoney"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_visa'] == 'on') { ?>
							<span class="visa"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_qiwi'] == 'on') { ?>
							<span class="qiwi"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_skrill'] == 'on') { ?>
							<span class="skrill"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_interkassa'] == 'on') { ?>
							<span class="interkassa"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_liqpay'] == 'on') { ?>
							<span class="liqpay"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_paypal'] == 'on') { ?>
							<span class="paypal"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_robokassa'] == 'on') { ?>
							<span class="robokassa"></span>
						<?php } ?>
						<?php echo (isset($oct_techstore_data['ps_mastercard']) && $oct_techstore_data['ps_mastercard'] == 'on') ? '<span class="mastercard"></span>' : ''; ?>
						<?php echo (isset($oct_techstore_data['ps_maestro']) && $oct_techstore_data['ps_maestro'] == 'on') ? '<span class="maestro"></span>' : ''; ?>
						<?php if ($ps_additional_icons) { ?>
							<?php foreach ($ps_additional_icons as $ps_additional_icon) { ?>
								<span class="custom-payment"><img src="<?php echo $ps_additional_icon['image']; ?>" alt=""></span>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				
				<div class="item">
					<div class="title"><?php echo $text_information; ?> <a class="f-acc-toggle"></a></div>
					<ul class="list-unstyled">
						<?php if ($oct_techstore_footer_informations) { ?>
							<?php foreach ($oct_techstore_footer_informations as $information) { ?>
								<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
							<?php } ?>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_contact_link'] == 'on') { ?>
							<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_return_link'] == 'on') { ?>
							<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
						<?php } ?>											
						<?php if ($oct_techstore_data['foot_show_block_sitemap_link'] == 'on') { ?>
							<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_manufacturer_link'] == 'on') { ?>
							<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_voucher_link'] == 'on') { ?>
							<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_affiliate_link'] == 'on') { ?>
							<li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
						<?php } ?>
						<?php if ($oct_techstore_data['foot_show_block_special_link'] == 'on') { ?>
							<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
							<li><a href="<?php echo $newproducts; ?>"><?php echo $text_newproducts; ?></a></li>
						<?php } ?>
					</ul>
				</div>
				<?php if ($oct_techstore_footer_categories) { ?>
					<div class="item">
						<div class="title"><?php echo $text_categories; ?> <a class="f-acc-toggle"></a></div>
						<ul class="list-unstyled">
							<?php foreach ($oct_techstore_footer_categories as $category) { ?>
								<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } else { ?>
					<div class="item">
						<div class="title"><?php echo $text_account; ?> <a class="f-acc-toggle"></a></div>
						<ul class="list-unstyled">
							<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
							<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
							<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
							<li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
							<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
						</ul>
					</div>
				<?php } ?>
				<div class="item footer-contacts">
					<div class="title"><?php echo $text_our_contacts; ?> <a class="f-acc-toggle"></a></div>
					<ul class="footer-contacts-ul">
						<?php if ($oct_techstore_cont_email) { ?>
							<li>
								<a href="mailto:<?php echo $oct_techstore_cont_email; ?>">
									<svg width="24" height="17" viewBox="0 0 24 17" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M1.55762 0.0627184C1.44762 0.097074 1.3221 0.138835 1.27865 0.15552C1.21829 0.178674 2.42514 1.41135 6.39824 5.38455C11.2265 10.2129 11.6111 10.5863 11.7977 10.6261C11.9212 10.6524 12.079 10.6518 12.2071 10.6245C12.4037 10.5825 12.7077 10.2874 17.5781 5.40869C20.4176 2.56439 22.7415 0.219451 22.7425 0.197656C22.7435 0.175862 22.6307 0.12243 22.492 0.0788882C22.2584 0.00558386 21.4806 -0.000227928 11.9987 6.42073e-06C3.59927 0.00024077 1.72167 0.0114898 1.55762 0.0627184ZM0.101614 1.46755L0 1.77703V8.43057V15.0841L0.101614 15.3936L0.203181 15.703L3.83943 12.0668L7.47564 8.43057L3.83943 4.79432L0.203181 1.15811L0.101614 1.46755ZM20.1529 4.79928L16.5217 8.43066L20.1585 12.0674L23.7953 15.7041L23.8976 15.3834L24 15.0626L23.9869 8.33683C23.9741 1.70204 23.9726 1.60802 23.879 1.38946L23.784 1.16791L20.1529 4.79928ZM4.83317 13.0417C2.83914 15.0358 1.22597 16.6785 1.24828 16.692C1.27064 16.7056 1.40347 16.7505 1.54352 16.7919C1.77379 16.86 2.77113 16.8671 11.9987 16.8671C21.2262 16.8671 22.2235 16.86 22.4538 16.7919C22.5939 16.7505 22.7267 16.7056 22.749 16.692C22.7714 16.6785 21.158 15.0356 19.1637 13.0412L15.5378 9.41507L14.4127 10.5344C13.7939 11.15 13.1927 11.7078 13.0767 11.7739C12.4736 12.1175 11.607 12.1261 10.9441 11.7949C10.8354 11.7407 10.2734 11.2207 9.60765 10.5586L8.45868 9.41591L4.83317 13.0417Z" fill="#6CBBB0"/>
									</svg>
							 		<?php echo $oct_techstore_cont_email; ?>
							 	</a>
							</li>
						<?php } ?>	
						<?php if ($oct_techstore_cont_phones) { ?>
							<?php foreach($oct_techstore_cont_phones as $element) { ?>
								<li>
									<a href="#" class="phoneclick" onclick="window.location.href='tel:+<?php echo preg_replace('/\D/', '', $element); ?>';return false;">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<g clip-path="url(#clip0_375_216218)">
											<path d="M23.0001 11C22.7348 11 22.4805 10.8946 22.2929 10.7071C22.1054 10.5196 22.0001 10.2652 22.0001 9.99999C21.9979 7.87891 21.1544 5.84531 19.6546 4.34547C18.1547 2.84564 16.1211 2.00211 14.0001 1.99999C13.7348 1.99999 13.4805 1.89463 13.2929 1.7071C13.1054 1.51956 13.0001 1.26521 13.0001 0.99999C13.0001 0.734773 13.1054 0.480419 13.2929 0.292883C13.4805 0.105347 13.7348 -1.03541e-05 14.0001 -1.03541e-05C16.6513 0.00290124 19.1932 1.0574 21.0679 2.93214C22.9426 4.80687 23.9971 7.34872 24.0001 9.99999C24.0001 10.2652 23.8947 10.5196 23.7072 10.7071C23.5196 10.8946 23.2653 11 23.0001 11ZM20.0001 9.99999C20.0001 8.40869 19.3679 6.88257 18.2427 5.75735C17.1175 4.63213 15.5914 3.99999 14.0001 3.99999C13.7348 3.99999 13.4805 4.10535 13.2929 4.29288C13.1054 4.48042 13.0001 4.73477 13.0001 4.99999C13.0001 5.26521 13.1054 5.51956 13.2929 5.7071C13.4805 5.89463 13.7348 5.99999 14.0001 5.99999C15.0609 5.99999 16.0783 6.42142 16.8285 7.17156C17.5786 7.92171 18.0001 8.93912 18.0001 9.99999C18.0001 10.2652 18.1054 10.5196 18.2929 10.7071C18.4805 10.8946 18.7348 11 19.0001 11C19.2653 11 19.5196 10.8946 19.7072 10.7071C19.8947 10.5196 20.0001 10.2652 20.0001 9.99999ZM22.1831 22.164L23.0931 21.115C23.6723 20.5339 23.9975 19.7469 23.9975 18.9265C23.9975 18.106 23.6723 17.3191 23.0931 16.738C23.0621 16.707 20.6561 14.856 20.6561 14.856C20.0786 14.3063 19.3116 14.0002 18.5143 14.0014C17.7171 14.0025 16.951 14.3107 16.3751 14.862L14.4691 16.468C12.9132 15.8241 11.4999 14.8792 10.3103 13.6875C9.12074 12.4958 8.17827 11.0809 7.53705 9.52399L9.13705 7.62399C9.68877 7.04816 9.99735 6.28187 9.99865 5.48439C9.99996 4.68692 9.69388 3.91962 9.14405 3.34199C9.14405 3.34199 7.29105 0.93899 7.26005 0.90799C6.6895 0.333738 5.91593 0.00701166 5.10648 -0.00159557C4.29702 -0.0102028 3.51668 0.3 2.93405 0.86199L1.78405 1.86199C-5.00995 9.74399 9.62005 24.261 17.7621 24C18.5842 24.0048 19.399 23.8447 20.1584 23.5294C20.9177 23.2141 21.6061 22.7498 22.1831 22.164Z" fill="#6CBBB0"/>
											</g>
											<defs>
											<clipPath id="clip0_375_216218">
											<rect width="24" height="24" fill="white"/>
											</clipPath>
											</defs>
										</svg>
										<?php echo $element; ?>
									</a>
								</li>
							<?php } ?>
						<?php } ?>
											
						<?php if ($oct_techstore_cont_skype) { ?>
							<li><a href="skype:<?php echo $oct_techstore_cont_skype; ?>"><i class="fa fa-skype" aria-hidden="true"></i> <?php echo $oct_techstore_cont_skype; ?></a></li>
						<?php } ?>
						
						<?php if ((isset($oct_techstore_data['ps_whatsapp_id']) && strlen($oct_techstore_data['ps_whatsapp_id']) > 1) or (isset($oct_techstore_data['ps_telegram_id']) && strlen($oct_techstore_data['ps_telegram_id']) > 1) or (isset($oct_techstore_data['ps_viber_id']) && strlen($oct_techstore_data['ps_viber_id']) > 1)) { ?>
							<li class="oct-messengers">
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
							</li>
						<?php } ?>
						<?php if ($oct_techstore_cont_clock) { ?>
							<?php foreach ($oct_techstore_cont_clock as $clock) { ?>
								<li class="clock">
									<svg width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M8.86491 0.0742093C8.5793 0.215612 8.50514 0.382867 8.48714 0.925877L8.47115 1.40852H6.64263C4.62159 1.40852 4.59993 1.41139 4.38885 1.70861C4.28835 1.85015 4.28376 1.90186 4.27035 3.04481L4.25638 4.23376H14.1282H24L23.986 3.04481C23.9654 1.28661 24.1425 1.40852 21.6095 1.40852H19.7767V0.980075C19.7767 0.291941 19.5772 0.0194469 19.0736 0.0194469C18.5699 0.0194469 18.3704 0.291941 18.3704 0.980075V1.40852H16.5892H14.8079V0.980075C14.8079 0.291941 14.6084 0.0194469 14.1048 0.0194469C13.6011 0.0194469 13.4016 0.291941 13.4016 0.980075V1.40852H11.6438H9.88595L9.8852 0.972965C9.88455 0.609262 9.86715 0.50713 9.77973 0.353955C9.59415 0.0288173 9.20002 -0.0917263 8.86491 0.0742093ZM4.19666 6.241C4.01033 9.74175 2.5713 13.3196 0.489086 15.4591C0.26488 15.6895 0.0620492 15.9293 0.0383302 15.9919C-0.0771244 16.2969 0.078315 16.7044 0.369459 16.86C0.526961 16.9442 0.86784 16.9473 9.87536 16.9473C19.004 16.9473 19.2219 16.9453 19.3918 16.8567C19.4875 16.8069 19.7161 16.6109 19.8998 16.4212C21.1923 15.0866 22.349 13.1011 23.0315 11.0455C23.5221 9.56767 23.8616 7.76997 23.9333 6.27029L23.9632 5.64638H14.0957H4.2283L4.19666 6.241ZM23.8591 12.8785C23.2241 14.4429 22.0234 16.2797 20.8958 17.4121C20.3884 17.9216 20.1549 18.0946 19.7846 18.2354L19.5189 18.3364L11.8899 18.3489L4.26088 18.3613V19.4926C4.26088 20.5335 4.26843 20.6387 4.35524 20.8097C4.41599 20.9294 4.51556 21.0294 4.63467 21.0904C4.81823 21.1844 4.89141 21.1852 14.1282 21.1852C23.365 21.1852 23.4382 21.1844 23.6217 21.0904C23.741 21.0293 23.8404 20.9294 23.9014 20.8092C23.9944 20.626 23.9958 20.5548 23.9841 16.6115L23.9721 12.6003L23.8591 12.8785Z" fill="#6CBBB0"/>
									</svg>
									<span><?php echo $clock; ?></span>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
					<div class="social_wrap">
							<?php if ($oct_techstore_data['ps_facebook_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_facebook_id']; ?>" title="Facebook" target="_blank">
									<svg width="12" height="23" viewBox="0 0 12 23" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M7.65708 12.3648H11.3793L11.9637 8.56055H7.65632V6.48134C7.65632 4.90099 8.16957 3.49962 9.63891 3.49962H12V0.179741C11.5852 0.123382 10.7078 0 9.04996 0C5.58819 0 3.55867 1.8393 3.55867 6.0297V8.56055H0V12.3648H3.55867V22.821C4.26344 22.9276 4.97729 23 5.71007 23C6.37245 23 7.01893 22.9391 7.65708 22.8522V12.3648Z" fill="white"/>
									</svg>
								</a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_instagram']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_instagram']; ?>" title="Instagram" target="_blank">
									<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M10 1.80723C12.6506 1.80723 13.0121 1.80723 14.0964 1.80723C15.0602 1.80723 15.5422 2.04819 15.9036 2.16868C16.3855 2.40964 16.747 2.53012 17.1084 2.89157C17.4699 3.25301 17.7108 3.61446 17.8313 4.09639C17.9518 4.45783 18.0723 4.93976 18.1928 5.90362C18.1928 6.98795 18.1928 7.22892 18.1928 10C18.1928 12.7711 18.1928 13.0121 18.1928 14.0964C18.1928 15.0602 17.9518 15.5422 17.8313 15.9036C17.5904 16.3855 17.4699 16.747 17.1084 17.1084C16.747 17.4699 16.3855 17.7108 15.9036 17.8313C15.5422 17.9518 15.0602 18.0723 14.0964 18.1928C13.0121 18.1928 12.7711 18.1928 10 18.1928C7.22892 18.1928 6.98795 18.1928 5.90362 18.1928C4.93976 18.1928 4.45783 17.9518 4.09639 17.8313C3.61446 17.5904 3.25301 17.4699 2.89157 17.1084C2.53012 16.747 2.28916 16.3855 2.16868 15.9036C2.04819 15.5422 1.92771 15.0602 1.80723 14.0964C1.80723 13.0121 1.80723 12.7711 1.80723 10C1.80723 7.22892 1.80723 6.98795 1.80723 5.90362C1.80723 4.93976 2.04819 4.45783 2.16868 4.09639C2.40964 3.61446 2.53012 3.25301 2.89157 2.89157C3.25301 2.53012 3.61446 2.28916 4.09639 2.16868C4.45783 2.04819 4.93976 1.92771 5.90362 1.80723C6.98795 1.80723 7.3494 1.80723 10 1.80723ZM10 0C7.22892 0 6.98795 0 5.90362 0C4.81928 0 4.09639 0.240965 3.49398 0.481928C2.89157 0.722892 2.28916 1.08434 1.68675 1.68675C1.08434 2.28916 0.843374 2.77109 0.481928 3.49398C0.240965 4.09639 0.120482 4.81928 0 5.90362C0 6.98795 0 7.3494 0 10C0 12.7711 0 13.0121 0 14.0964C0 15.1807 0.240965 15.9036 0.481928 16.506C0.722892 17.1084 1.08434 17.7108 1.68675 18.3133C2.28916 18.9157 2.77109 19.1566 3.49398 19.5181C4.09639 19.759 4.81928 19.8795 5.90362 20C6.98795 20 7.3494 20 10 20C12.6506 20 13.0121 20 14.0964 20C15.1807 20 15.9036 19.759 16.506 19.5181C17.1084 19.2771 17.7108 18.9157 18.3133 18.3133C18.9157 17.7108 19.1566 17.2289 19.5181 16.506C19.759 15.9036 19.8795 15.1807 20 14.0964C20 13.0121 20 12.6506 20 10C20 7.3494 20 6.98795 20 5.90362C20 4.81928 19.759 4.09639 19.5181 3.49398C19.2771 2.89157 18.9157 2.28916 18.3133 1.68675C17.7108 1.08434 17.2289 0.843374 16.506 0.481928C15.9036 0.240965 15.1807 0.120482 14.0964 0C13.0121 0 12.7711 0 10 0Z" fill="white"/>
										<path d="M10 4.81928C7.10843 4.81928 4.81928 7.10843 4.81928 10C4.81928 12.8916 7.10843 15.1807 10 15.1807C12.8916 15.1807 15.1807 12.8916 15.1807 10C15.1807 7.10843 12.8916 4.81928 10 4.81928ZM10 13.3735C8.19277 13.3735 6.62651 11.9277 6.62651 10C6.62651 8.19277 8.07229 6.62651 10 6.62651C11.8072 6.62651 13.3735 8.07229 13.3735 10C13.3735 11.8072 11.8072 13.3735 10 13.3735Z" fill="white"/>
										<path d="M15.3012 5.90362C15.9666 5.90362 16.506 5.3642 16.506 4.6988C16.506 4.03339 15.9666 3.49398 15.3012 3.49398C14.6358 3.49398 14.0964 4.03339 14.0964 4.6988C14.0964 5.3642 14.6358 5.90362 15.3012 5.90362Z" fill="white"/>
									</svg>
								</a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_vk_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_vk_id']; ?>" title="Vkonakte" target="_blank"><i class="fa fa-vk" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_gplus_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_gplus_id']; ?>" title="Google Plus" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_odnoklass_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_odnoklass_id']; ?>" title="Odnoklassniki" target="_blank"><i class="fa fa-odnoklassniki" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_twitter_username']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_twitter_username']; ?>" title="Twitter" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_vimeo_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_vimeo_id']; ?>" title="Vimeo" target="_blank"><i class="fa fa-vimeo" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_pinterest_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_pinterest_id']; ?>" title="Pinterest" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_flick_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_flick_id']; ?>" title="Flickr" target="_blank"><i class="fa fa-flickr" aria-hidden="true"></i></a>
							<?php } ?>
							<?php if ($oct_techstore_data['ps_youtube_id']) { ?>
								<a rel="nofollow" href="<?php echo $oct_techstore_data['ps_youtube_id']; ?>" title="Youtube" target="_blank">
									<svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M2.26249 15.0568C1.87023 14.9408 1.51232 14.7343 1.21896 14.4549C0.925599 14.1754 0.705403 13.8312 0.576947 13.4512C-0.157079 11.4874 -0.374569 3.28693 1.03911 1.66807C1.50961 1.14145 2.17389 0.817218 2.88777 0.765759C6.68024 0.36768 18.3975 0.420757 19.7432 0.898453C20.1217 1.01832 20.4675 1.22026 20.7547 1.48912C21.0419 1.75799 21.263 2.08681 21.4015 2.45096C22.2035 4.48117 22.2307 11.8589 21.2928 13.8095C21.044 14.3173 20.6275 14.7285 20.1102 14.9772C18.6965 15.6672 4.13833 15.6539 2.26249 15.0568ZM8.28422 11.2087L15.0808 7.75869L8.28422 4.28213V11.2087Z" fill="white"/>
									</svg>
								</a>
							<?php } ?>
						<div style="display: none !important;">
							<div id="ratingBadgeContainer"></div>
							<script src="https://apis.google.com/js/platform.js?onload=renderBadge" async defer></script>
							<script>
								window.renderBadge = function() {
									var ratingBadgeContainer = document.getElementById('ratingBadgeContainer');
									window.gapi.load('ratingbadge', function() {
										window.gapi.ratingbadge.render(ratingBadgeContainer, {"merchant_id": 115465335});
									});
								}

								// $(document).ready(function(){
								// 	setTimeout(renderBadge(), 1000);
								// });
							</script>

						</div>
					</div>
				</div>
				
				<div class="item">
					<?php if ($oct_techstore_data['foot_show_soclinks'] == 'on') { ?>
							<?php echo $oct_popup_subscribe; ?>

							
					<?php } ?>
				</div>
						
				<div class="item pc-hidden">
					<div class="copuright">
						<?php echo $oct_powered; ?>
					</div>
					<div class="payment-box">
						<?php if ($oct_techstore_data['ps_sberbank'] == 'on') { ?>
							<span class="sberbank"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_privat'] == 'on') { ?>
							<span class="privat24"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_yamoney'] == 'on') { ?>
							<span class="yandex-money"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_webmoney'] == 'on') { ?>
							<span class="webmoney"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_visa'] == 'on') { ?>
							<span class="visa"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_qiwi'] == 'on') { ?>
							<span class="qiwi"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_skrill'] == 'on') { ?>
							<span class="skrill"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_interkassa'] == 'on') { ?>
							<span class="interkassa"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_liqpay'] == 'on') { ?>
							<span class="liqpay"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_paypal'] == 'on') { ?>
							<span class="paypal"></span>
						<?php } ?>
						<?php if ($oct_techstore_data['ps_robokassa'] == 'on') { ?>
							<span class="robokassa"></span>
						<?php } ?>
						<?php echo (isset($oct_techstore_data['ps_mastercard']) && $oct_techstore_data['ps_mastercard'] == 'on') ? '<span class="mastercard"></span>' : ''; ?>
						<?php echo (isset($oct_techstore_data['ps_maestro']) && $oct_techstore_data['ps_maestro'] == 'on') ? '<span class="maestro"></span>' : ''; ?>
						<?php if ($ps_additional_icons) { ?>
							<?php foreach ($ps_additional_icons as $ps_additional_icon) { ?>
								<span class="custom-payment"><img src="<?php echo $ps_additional_icon['image']; ?>" alt=""></span>
							<?php } ?>
						<?php } ?>
					</div>
				</div>

		
	</div>
</footer>
<? /* VOICE SEARCH */ ?>
<style type="text/css">
    @-webkit-keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(43 55 67 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(43 55 67 / 60%)}
	to{box-shadow:0 0 0 0 #2b3743}
    }
    @keyframes voice-modal__preview--outer{
	0%{box-shadow:0 0 0 0 rgb(43 55 67 / 60%)}
	30%{box-shadow:0 0 0 12px rgb(43 55 67 / 60%)}
	to{box-shadow:0 0 0 0 rgb(43 55 67 / 60%)}
    }
    #voice_modal{
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	position: fixed;
	overflow: auto;
	margin: 0!important;
	background-color: rgba(0,0,0,.5);
	display: flex;
	align-items: center;
	overflow: hidden!important;
	z-index: 9999999 !important;
    }
    #voice_modal .wrap-modal{
	width: 288px;
	top: 50%;
	max-height: 100vh;
	overflow-y: auto;
	overflow-x: hidden;
	position: absolute;
	left: 50%;
	transform: translate(-50%, -50%);
	background: #fff;
	border-radius: 4px;
	box-shadow: 0 1px 3px rgb(0 0 0 / 30%);
	min-height: 150px;
	text-align: center;
	transition: padding-top .5s ease-out;
    }
    #voice_modal .wrap-modal .body .content{
	padding: 88px 24px 64px;       
	transition: padding-top .5s ease-out;
	height: 288px;
    }
    #voice_modal .wrap-modal .body.error_voice .voice-modal__icon{
	-webkit-animation: none;
	animation: none;
	border: 2px solid #f30;
	background-color: transparent;
	color:  #f30;
    }
    #voice_modal .wrap-modal .body.error_voice .content{
	padding: 51px 24px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error p{
	line-height: 1.5;
	color: rgba(0,0,0,.87);
	text-align: center;
	max-width: 181px;
	margin: 0 auto 10px;
    }
    #voice_modal .wrap-modal .body .voice-modal__error button{
	line-height: normal;
	font-size: 14px !important;
	color: #ffff;
	background: #2b3743 !important;
	font-size: 12px;
	cursor: pointer;
	background: 0 0;
	margin: 0 auto;
	padding: 10px 20px;
	border: 0 !important;
    }
    #voice_modal .wrap-modal p{
	font-size: 14px;
	margin-bottom: 0;
    }
    
    #voice_modal .voice-modal__icon{
	width: 64px;
	height: 64px;
	margin: 0 auto 25px;
	background: #2b3743;
	-webkit-animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	animation: voice-modal__preview--outer 1s ease-out infinite alternate;
	border-radius: 32px;
	position: relative;
	display: flex;
	align-items: center;
	box-shadow: 0 0 0 0 rgb(43 55 67 / 65%);
	justify-content: center;
	color: #fff;
	font-size: 21px;
    }
    #voice_modal .close_modals{
	position: absolute;
	right: 10px;
	width: 25px;
	height: 25px;
	top: 10px;
	font-size: 26px;
	cursor: pointer;
	background-size: 11px 11px;
	background-repeat: no-repeat;
	border: 1px solid #000;
	border-radius: 50px;
	text-align: center;
	background-position: center;
	opacity: .5;
	z-index: 10;
	background-color: #fff;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 0;
	color: #000;
    }
</style>
<div id="voice_modal" class="overlay_modal" style="display: none;">
    <div class="wrap-modal">
        <div class="body">
            <div class="content">
                <button class="close_modals">×</button>
                <div class="voice-modal__icon">
                    <i class="fa fa-microphone"></i>
				</div>
                <p class="voice-modal__say">Скажите что-нибудь</p>
                <p class="voice-modal__text-recognize"></p>
                <div class="voice-modal__error">
                    <p class="voice-modal__error-text">
                        Ничего не найдено. Произнесите текст еще раз
					</p> 
                    <button class="voice-modal__repeat">
                        Повторить
					</button>
				</div>
			</div>            
		</div>  
	</div>
</div>


<p id="back-top">
	<span class="wraper">
		<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7.97248 0.750776L7.9711 0.752113L0.422705 8.50827C-0.142771 9.08933 -0.140667 10.0292 0.42764 10.6076C0.995874 11.1859 1.91496 11.1836 2.48051 10.6026L7.54839 5.39509L7.54839 16.1269C7.54839 16.9468 8.19827 17.6113 9 17.6113C9.80173 17.6113 10.4516 16.9468 10.4516 16.1269L10.4516 5.39517L15.5195 10.6025C16.085 11.1836 17.0041 11.1858 17.5724 10.6075C18.1407 10.0291 18.1427 9.08918 17.5773 8.5082L10.0289 0.752037L10.0275 0.7507C9.46009 0.169346 8.53802 0.171202 7.97248 0.750776Z" fill="white"/>
		</svg>
	</span>
</p>

<script>
	$("#oct-search-button").on("click", function() {            
        var e = $("#mainsearch").val();
        if (e.length <= 0) return !1;
        e && (srchurl = "<?php echo $search; ?>?search=" + encodeURIComponent(e)), document.location = srchurl;
	});
	
	$("#oct-m-search-button").on("click", function() {
        
        var e = $("#mobilesearch").val();
        if (e.length <= 0) return !1;
        e && (srchurl = "<?php echo $search; ?>?search=" + encodeURIComponent(e)), document.location = srchurl;
	});
	
	$("#oct-m-search-button1").on("click", function() {
        
        var e = $("input[name='webfun_search1']").val();
        if (e.length <= 0) return !1;
        e && (srchurl = "<?php echo $search; ?>?search=" + encodeURIComponent(e)), document.location = srchurl;
	});
</script>


<?php if (NProgress instanceof Object){ ?>
	<? /* NPROGRESS */ ?>
	<script src="/catalog/view/theme/oct_techstore/js/nprogress/nprogress.js" async="async" defer></script>
	<script>
		window.addEventListener('beforeunload', function(event) {
		console.log('BeforeUnload fired');			
		if (NProgress instanceof Object){				
		NProgress.configure({ showSpinner: false });
		NProgress.start();
		NProgress.inc(0.1);
		setTimeout(function () {
		NProgress.inc(0.5);
		}, 100);
		setTimeout(function () {
		NProgress.done();
		$(".fade").removeClass("out");
		}, 1000);			
		}
		});
	</script>
	
<?php } ?>
						





</body>
</html>