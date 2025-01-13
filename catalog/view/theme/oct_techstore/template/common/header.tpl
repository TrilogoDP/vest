<!DOCTYPE html>
<!--[if IE]><![endif]-->
	<!--[if IE 8 ]><html prefix="og: http://ogp.me/ns#" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
		<!--[if IE 9 ]><html prefix="og: http://ogp.me/ns#" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
			<!--[if (gt IE 9)|!(IE)]><!-->
				<html prefix="og: http://ogp.me/ns#" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" data-device-session="<?php echo $isMobile; ?>">
				<!--<![endif]-->
					<head>
							<link rel="manifest" href="/manifest.json?rand=<?php echo mt_rand(0, 1000); ?>">
						<script>
							function pushPWAEvent(eventAction){
								window.dataLayer = window.dataLayer || [];	

								dataLayer.push({
									event: 'PWA',
									eventCategory: 'PWA',
									eventAction: eventAction
								});
							}

							if ("serviceWorker" in navigator) {
								console.log("[PWA] ServiceWorker is in navigator, continue");
								if (navigator.serviceWorker.controller) {
									console.log("[PWA] active service worker found, no need to register");
								} else {
									navigator.serviceWorker
									.register("/sw.js", {scope: "/"})
									.then(function (reg) {
										console.log("[PWA] Service worker has been registered for scope: " + reg.scope);
									});
								}
							}  else {
								console.log("[PWA] ServiceWorker NOT in navigator, bad luck");		
							}

							let deferredPrompt = null;

							window.addEventListener('beforeinstallprompt', function(e) {			
								e.preventDefault(); 
								window.deferredPrompt = e;		
								pushPWAEvent('beforeinstallprompt');
								console.log('[PWA] VEST PWA APP beforeinstallprompt fired');			
								if (localStorage.getItem('pwaaccepted') == 'true'){					
								} else {
									showInstallFooterBlock();	
								}
							});


							document.addEventListener("DOMContentLoaded", () => {
								var pwaInstallButton = document.getElementById('download_app');
								pwaInstallButton.addEventListener('click', async () => {
									let promptEvent = window.deferredPrompt;
									if (promptEvent) {
										localStorage.removeItem('pwadeclined');
										localStorage.removeItem('pwaaccepted');

										promptEvent.prompt();
										console.log(promptEvent);
										promptEvent.userChoice.then(function(choiceResult){

											if (choiceResult.outcome === 'accepted') {
												pushPWAEvent('pwainstall');
												localStorage.setItem('pwaaccepted', 'true');								
												console.log('[PWA] VEST PWA APP is installed');							
											} else {
												pushPWAEvent('beforeinstallpromptdeclined');
												localStorage.setItem('pwadeclined', 'true');
												console.log('[PWA] VEST PWA APP is not installed');
											}

											promptEvent = null;

										});
									}
								});
							});


							window.addEventListener('appinstalled', function(e) {
								pushPWAEvent('pwainstall');				
							});			


							document.addEventListener("DOMContentLoaded", function() {
								if (isTWAApp()){
									console.log('[PWA] sending pwapageview event');
									pushPWAEvent('twapageview');
								}

								if (isStandaloneAPP()){
									console.log('[PWA] sending pwapageview event');
									pushPWAEvent('pwapageview');
								}
							});

							function isTWAApp(){								
								if (document.referrer.includes('android-app://')) {
									console.log('[PWA] display-mode is standalone: android-app/TWA');
									return true;
								}

								return false;
							}

							function isStandaloneAPP(){
								if (window.matchMedia('(display-mode: standalone)').matches) {
									console.log('[PWA] display-mode is standalone: display-mode');
									return true;
								}

								if (document.referrer.includes('android-app://')) {
									console.log('[PWA] display-mode is standalone: android-app/TWA');
									//return true;
								}

								if ('standalone' in navigator && window.navigator.standalone === true) {
									console.log('[PWA] display-mode is standalone:  window.navigator.standalone = true');
									return true;
								}

								console.log('[PWA] display-mode is not standalone');
								return false;
							}

							function showInstallFooterBlock(){
								document.getElementById('footer_app').style.display = 'block';
							}

							function localStorageSupported(){
								try {
									localStorage.setItem('lstest', 'lstest');
									localStorage.removeItem('lstest');
									return true;
								} catch(e) {
									return false;
								}
							}
						</script>

						<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=0" />
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<title><?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?><?php echo $title; ?></title>

						<?php if ($noindex) { ?>
						<!-- OCFilter Start -->
						<meta name="robots" content="noindex,nofollow" />
						<!-- OCFilter End -->
						<?php } ?>


						<base href="<?php echo $base; ?>" lang="<?php echo rtrim($base_lang, '/'); ?>" />

						


						<link rel="icon" type="image/png" href="/pwa/favicon-48x48.png?r=vTy261a" sizes="48x48" />
						<link rel="icon" type="image/svg+xml" href="/pwa/favicon.svg?r=vTy261a" />
						<link rel="shortcut icon" href="/pwa/favicon.ico?r=vTy261a" />
						<link rel="apple-touch-icon" sizes="180x180" href="/pwa/apple-touch-icon.png?r=vTy261a" />
						<meta name="apple-mobile-web-app-title" content="VEST" />
						<meta name="theme-color" content="#6cbbb0">
						<meta name="apple-mobile-web-app-title" content="VEST">
						<meta name="application-name" content="VEST">
						<meta name="theme-color" content="#6cbbb0">
						<meta name="apple-mobile-web-app-title" content="VEST">
						<meta name="application-name" content="VEST">





						<link rel="dns-prefetch" href="//ajax.googleapis.com">
						<link rel="dns-prefetch" href="//www.google.com">
						<link rel="dns-prefetch" href="//fonts.googleapis.com">
						<link rel="dns-prefetch" href="//www.googletagmanager.com">
						<link rel="dns-prefetch" href="//connect.facebook.net">

						

						<?php if ($description) { ?>
						<meta name="description" content="<?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?> <?php echo $description; ?>" />
						<?php } ?>

						<?php
							//***mf begin
							if($lang == 'ua'){
								$href_uk = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								$href_uk = str_replace('/ua/ua', '/ua', $href_uk); // Добавить эту строку
								$href_ru = str_replace('/ua', '', $href_uk);
							}else{
								$href_ru = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								$href_uk = str_replace("https://vest.in.ua/", "https://vest.in.ua/ua/", "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
								$href_uk = str_replace('/ua/ua', '/ua', $href_uk); // Добавить эту строку
								$href_ru = str_replace('/ua', '', $href_uk);
							}
							if ($is_homepage){
								$href_uk = "https://vest.in.ua/ua";
								$href_ru = "https://vest.in.ua";			
							}
							//***mf end
						?>

						<link rel="alternate" hreflang="uk" href="<?php echo $href_uk ?>"/>
						<link rel="alternate" hreflang="ru" href="<?php echo $href_ru ?>"/>



						<!-- new style -->
						<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/stylesheet/style_root.css">
						<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/stylesheet/style_new_version.css">
	
						<!-- fixes by Trilogo -->
						<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/stylesheet/fix.css">

						<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/themes/fontawesome-stars.css">
						<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

						<link href="
						https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css
						" rel="stylesheet">
						<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

						<?php foreach ($analytics as $analytic) { ?>
						<?php echo $analytic; ?>
						<?php } ?>

						<?php if ($description) { ?>
								<meta name="description" content="<?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?> <?php echo $description; ?>" />
						<?php } ?>

						<?php if ($keywords) { ?>
								<meta name="keywords" content= "<?php echo $keywords; ?>" />
						<?php } ?>

						<?php if ($canonical) { ?>
						<link rel="canonical" href="<?php echo $canonical; ?>" />
						<?php } ?>
						
						<?php if (isset($og_url)) { ?><meta property="og:url" content="<?php echo $og_url; ?>" /><?php } ?>
						
						<?php if (isset($og_image)) { ?>
								<meta property="og:image" content="<?php echo $og_image; ?>" />
						<?php } else { ?>
								<meta property="og:image" content="https://vest.in.ua/image/catalog/logo_130_56.png" />
						<?php } ?>
						
						<meta property="og:site_name" content="<?php echo $name; ?>" />
						<meta property="og:title" content="<?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?><?php echo $title; ?>" />
						<meta property="og:type" content="website" />

						<?php if ($robots) { ?>
								<meta name="robots" content="<?php echo $robots; ?>" />
						<?php } ?>

						<?php foreach ($links as $link) { ?>
								<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
						<?php } ?>

						<!-- Google Tag Manager -->
						<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
							new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
						j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
						'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
					})(window,document,'script','dataLayer','GTM-PD843HP');</script>
					<!-- End Google Tag Manager -->

					<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>

					<script src="catalog/view/javascript/ocfilter/nouislider.min.js"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.4.1/jquery-migrate.min.js" integrity="sha512-t0ovA8ZOiDuaNN5DaQQpMn37SqIwp6avyoFQoW49hOmEYSRf8mTCY2jZkEVizDT+IZ9x+VHTZPpcaMA5t2d2zQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

					<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js" integrity="sha512-gY25nC63ddE0LcLPhxUJGFxa2GoIyA5FLym4UJqHDEMHjp8RET6Zn/SHo1sltt3WuVtqfyxECP38/daUc/WVEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


					<script src="https://vest.in.ua/catalog/view/javascript/nprogress/nprogress.js" async="async" defer></script>			

					<script src="catalog/view/javascript/ocfilter/ocfilter.js"></script>	
					<script src="catalog/view/javascript/lazyload/jquery.lazyload.min.js"></script>

					<script src="catalog/view/javascript/simple.js"></script>
					<script src="catalog/view/javascript/simplecheckout.js"></script>
					<script src="catalog/view/javascript/simplepage.js"></script>
					<script src="catalog/view/javascript/ecommerce.functions.js"></script>

					<script src="
					https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/jquery.magnific-popup.min.js
					"></script>
					<link href="
					https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.min.css
					" rel="stylesheet">
					<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
					<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

					<script src="catalog/view/theme/oct_techstore/js/new-main.js"></script>

					<?php if($fbpixel_status){ ?>
					<!-- Facebook Pixel Code -->
					<script>
						!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
						n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
						n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
						t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
							document,'script','//connect.facebook.net/en_US/fbevents.js');

						fbq('init', '<?php echo $fbpixel_id; ?>');
						fbq('track', "PageView");
						<?php echo $pixel; ?>

					</script>
					<noscript><img height="1" width="1" style="display:none" alt="pixel facebook" 
						src="https://www.facebook.com/tr?id=<?php echo $fbpixel_id; ?>&ev=PageView&noscript=1"
						/></noscript>
						<!-- End Facebook Pixel Code -->
						<?php } ?>

						<?php echo $tc_og; ?>
					</head>	
					<body class="<?php echo $class; ?>">
						<?php if (!empty($UA_REDIRECTION_URI)) { ?>
							<?php if ($isMobile) { ?>
								<div id="ua-popup_bg"></div>
								<div id="ua-popup" class="ua-popup_m">
									<h3>Виберіть бажану мову сайту</h3>
									<div class="buttons-box">
										<div class="text-right">
											<a class="popup-button popup-button-wide close_popup_m" id="popup-button-link-ru-m" style="background-color: grey;">RU</a>
										</div>
										<div class="text-left">
											<a class="popup-button popup-button-wide ua_lang" id="popup-button-link-ua-m" href="<?php echo $UA_REDIRECTION_URI; ?>">UA</a>
										</div>
									</div>
								</div>
								<script>
									$(document).ready(function(){
										$('.ua-popup_m').css('display','block');
										$('#ua-popup_bg').css('display','block');
										$('.close_popup_m').on('click', function(){
											$('.ua-popup_m').css('display','none');
											$('#ua-popup_bg').css('display','none');
										});
									});

								</script>
							<?php } ?>
						<?php } ?>

						<header>

							<!-- TOP BANNER -->
							<?php if(isset($oct_information_bar_value) && !empty($oct_information_bar_value) && $oct_information_bar_value && $text_oct_information_bar) { ?>
							<div class="top-banner">
								<div style="background-color:<?php echo $oct_information_bar_background; ?>; color:<?php echo $oct_information_bar_color_text; ?>">
									<?php echo $text_oct_information_bar; ?>
								</div>
							</div>							
							<?php } ?>
							<!-- TOP BANNER END -->
							<div class="header-first-line">
								<div class="wrap df jcsb aic">
									<!-- LOGO -->
									<div class="logo">
										<?php if ($logo) { ?>
										<?php if($_SERVER['REQUEST_URI'] == "/index.php?route=common/home" OR $_SERVER['REQUEST_URI'] == "/") { ?>
										<!-- <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" width="135" height="50" alt="<?php echo $name; ?>" class="img-responsive" /> -->

										<a href="<?php echo $home; ?>">
											<svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											viewBox="0 0 256.2 76.5" width="180" style="enable-background:new 0 0 256.2 76.5;" xml:space="preserve">
											<g>
												<path d="M57.7,6.2c-0.3-1.3-1-2.5-1.8-3.4c-0.8-0.9-1.8-1.6-3-2.1C51.7,0.3,50.5,0,49.2,0c-1.7,0-3.3,0.5-4.7,1.5
												c-1.5,1-2.5,2.6-3.1,4.6c-2.1,7.2-4.3,14.8-6.6,22.4c-1.8,5.8-3.7,11.8-5.7,17.7c-1.9-5.9-3.8-11.7-5.5-17.5
												c-2.2-7.6-4.4-15.1-6.5-22.3c-0.7-2.1-1.8-3.7-3.3-4.8C12.4,0.5,10.7,0,9,0C7.8,0,6.5,0.3,5.3,0.8C4.1,1.3,3,2.1,2.2,3
												C1.3,4,0.6,5.2,0.3,6.5c-0.4,1.4-0.3,3,0.1,4.6c2.6,9.1,5.4,18.5,8.4,28c3,9.4,6.3,19.2,9.7,29.1c1.1,3.1,2.4,5.2,4.2,6.5
												c1.7,1.2,3.8,1.9,6.1,1.9c2.1,0,4.1-0.6,5.9-1.9c1.8-1.3,3.3-3.4,4.3-6.4c3.5-10,6.8-19.9,9.9-29.4c3.1-9.5,6-19,8.6-28.2
												C57.9,9.1,58,7.6,57.7,6.2z"/>
												<path d="M191.3,41.7c-1.6-2.9-4-5.3-7.4-7.2c-1.3-0.7-2.8-1.3-4.4-1.9c-1.6-0.5-3.3-1-5-1.5c-1.7-0.4-3.5-0.8-5.3-1.2
												c-1.7-0.3-3.4-0.7-5-1.1c-3.3-0.8-5.8-1.7-7.2-2.6c-0.4-0.3-1.6-1.1-1.6-3.7c0-2,0.8-3.5,2.5-4.6c1.9-1.2,4.7-1.9,8.4-1.9
												c1.5,0,2.8,0.1,3.9,0.2c1.1,0.2,2.1,0.3,3,0.5c1.5,0.3,2.7,0.6,3.6,1c1.3,0.5,2.6,0.7,4,0.7c2.8,0,5-0.9,6.6-2.7
												c1.5-1.7,2.3-3.6,2.3-5.6c0-1.7-0.5-3.3-1.6-4.7c-1.1-1.5-2.8-2.6-5-3.2c-2.8-0.9-5.7-1.5-8.7-1.8c-2.9-0.3-6-0.5-9.3-0.5
												c-3.8,0-7.5,0.5-10.9,1.6c-3.5,1-6.6,2.6-9.2,4.6c-2.6,2.1-4.8,4.6-6.3,7.7c-1.5,3-2.3,6.6-2.3,10.5c0,4.5,1,8.1,3,10.8
												c1.9,2.6,4.4,4.8,7.4,6.4c1.3,0.6,2.9,1.2,4.6,1.7c1.7,0.5,3.4,1,5.1,1.3c1.7,0.4,3.4,0.8,5.1,1.1c1.6,0.4,3.1,0.7,4.4,1
												c4.6,1.2,6.6,2.2,7.4,2.9c1,0.8,1.4,1.9,1.4,3.4c0,2.3-0.9,4-2.8,5.3c-2,1.4-5.5,2.1-10.4,2.1c-1.8,0-3.4-0.1-4.7-0.3
												c-1.4-0.2-2.6-0.4-3.7-0.6c-1.9-0.4-3.4-0.9-4.4-1.3c-1.5-0.6-3-0.9-4.5-0.9c-2.6,0-4.8,0.9-6.3,2.6c-1.4,1.6-2.1,3.5-2.1,5.7
												c0,1.6,0.5,3.2,1.5,4.6c1,1.5,2.7,2.6,4.8,3.4c3.2,1.1,6.5,1.8,9.9,2.2c3.3,0.4,7,0.6,10.8,0.6c4.3,0,8.3-0.5,12-1.5
												c3.7-1,7-2.6,9.8-4.6c2.8-2.1,5-4.7,6.6-7.9c1.6-3.1,2.4-6.8,2.4-10.9C193.6,47.9,192.8,44.6,191.3,41.7z"/>
												<path d="M253.9,2.5C252.3,0.8,250.3,0,248,0h-41.4c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1
												c1.6,1.7,3.5,2.5,5.8,2.5h12.3v50.5c0,2.4,0.8,4.5,2.4,6.2c1.6,1.7,3.6,2.6,6,2.6c2.3,0,4.3-0.9,6-2.6c1.6-1.7,2.4-3.8,2.4-6.2
												V17.2H248c2.3,0,4.2-0.8,5.8-2.5c1.6-1.7,2.4-3.7,2.4-6.1C256.2,6.2,255.4,4.2,253.9,2.5z"/>
											</g>
											<g>
												<path class="st0" d="M119.5,17c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
												<path class="st0" d="M119.5,46.7c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
												<path class="st0" d="M119.5,76.5c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
											</g>
										</svg>
									</a>
									<?php } else { ?>
									<a href="<?php echo $home; ?>">
										<!-- <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" width="135" height="50" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a> -->
										<svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
										viewBox="0 0 256.2 76.5"  width="180" style="enable-background:new 0 0 256.2 76.5;" xml:space="preserve">
										<g>
											<path d="M57.7,6.2c-0.3-1.3-1-2.5-1.8-3.4c-0.8-0.9-1.8-1.6-3-2.1C51.7,0.3,50.5,0,49.2,0c-1.7,0-3.3,0.5-4.7,1.5
											c-1.5,1-2.5,2.6-3.1,4.6c-2.1,7.2-4.3,14.8-6.6,22.4c-1.8,5.8-3.7,11.8-5.7,17.7c-1.9-5.9-3.8-11.7-5.5-17.5
											c-2.2-7.6-4.4-15.1-6.5-22.3c-0.7-2.1-1.8-3.7-3.3-4.8C12.4,0.5,10.7,0,9,0C7.8,0,6.5,0.3,5.3,0.8C4.1,1.3,3,2.1,2.2,3
											C1.3,4,0.6,5.2,0.3,6.5c-0.4,1.4-0.3,3,0.1,4.6c2.6,9.1,5.4,18.5,8.4,28c3,9.4,6.3,19.2,9.7,29.1c1.1,3.1,2.4,5.2,4.2,6.5
											c1.7,1.2,3.8,1.9,6.1,1.9c2.1,0,4.1-0.6,5.9-1.9c1.8-1.3,3.3-3.4,4.3-6.4c3.5-10,6.8-19.9,9.9-29.4c3.1-9.5,6-19,8.6-28.2
											C57.9,9.1,58,7.6,57.7,6.2z"/>
											<path d="M191.3,41.7c-1.6-2.9-4-5.3-7.4-7.2c-1.3-0.7-2.8-1.3-4.4-1.9c-1.6-0.5-3.3-1-5-1.5c-1.7-0.4-3.5-0.8-5.3-1.2
											c-1.7-0.3-3.4-0.7-5-1.1c-3.3-0.8-5.8-1.7-7.2-2.6c-0.4-0.3-1.6-1.1-1.6-3.7c0-2,0.8-3.5,2.5-4.6c1.9-1.2,4.7-1.9,8.4-1.9
											c1.5,0,2.8,0.1,3.9,0.2c1.1,0.2,2.1,0.3,3,0.5c1.5,0.3,2.7,0.6,3.6,1c1.3,0.5,2.6,0.7,4,0.7c2.8,0,5-0.9,6.6-2.7
											c1.5-1.7,2.3-3.6,2.3-5.6c0-1.7-0.5-3.3-1.6-4.7c-1.1-1.5-2.8-2.6-5-3.2c-2.8-0.9-5.7-1.5-8.7-1.8c-2.9-0.3-6-0.5-9.3-0.5
											c-3.8,0-7.5,0.5-10.9,1.6c-3.5,1-6.6,2.6-9.2,4.6c-2.6,2.1-4.8,4.6-6.3,7.7c-1.5,3-2.3,6.6-2.3,10.5c0,4.5,1,8.1,3,10.8
											c1.9,2.6,4.4,4.8,7.4,6.4c1.3,0.6,2.9,1.2,4.6,1.7c1.7,0.5,3.4,1,5.1,1.3c1.7,0.4,3.4,0.8,5.1,1.1c1.6,0.4,3.1,0.7,4.4,1
											c4.6,1.2,6.6,2.2,7.4,2.9c1,0.8,1.4,1.9,1.4,3.4c0,2.3-0.9,4-2.8,5.3c-2,1.4-5.5,2.1-10.4,2.1c-1.8,0-3.4-0.1-4.7-0.3
											c-1.4-0.2-2.6-0.4-3.7-0.6c-1.9-0.4-3.4-0.9-4.4-1.3c-1.5-0.6-3-0.9-4.5-0.9c-2.6,0-4.8,0.9-6.3,2.6c-1.4,1.6-2.1,3.5-2.1,5.7
											c0,1.6,0.5,3.2,1.5,4.6c1,1.5,2.7,2.6,4.8,3.4c3.2,1.1,6.5,1.8,9.9,2.2c3.3,0.4,7,0.6,10.8,0.6c4.3,0,8.3-0.5,12-1.5
											c3.7-1,7-2.6,9.8-4.6c2.8-2.1,5-4.7,6.6-7.9c1.6-3.1,2.4-6.8,2.4-10.9C193.6,47.9,192.8,44.6,191.3,41.7z"/>
											<path d="M253.9,2.5C252.3,0.8,250.3,0,248,0h-41.4c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1
											c1.6,1.7,3.5,2.5,5.8,2.5h12.3v50.5c0,2.4,0.8,4.5,2.4,6.2c1.6,1.7,3.6,2.6,6,2.6c2.3,0,4.3-0.9,6-2.6c1.6-1.7,2.4-3.8,2.4-6.2
											V17.2H248c2.3,0,4.2-0.8,5.8-2.5c1.6-1.7,2.4-3.7,2.4-6.1C256.2,6.2,255.4,4.2,253.9,2.5z"/>
										</g>
										<g>
											<path class="st0" d="M119.5,17c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
											H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
											<path class="st0" d="M119.5,46.7c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
											H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
											<path class="st0" d="M119.5,76.5c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
											H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
										</g>
									</svg>
								</a>
								<?php } ?>
								<?php } else { ?>
								<h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
								<?php } ?>
							</div>
							<!-- LOGO END-->
							<!-- HEADER NAVIGATION -->
							<ul class="header-fl-navigation">
								<li class="item"><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
								<li class="item"><a href="<?php echo $newproducts; ?>"><?php echo $text_newproducts; ?></a></li>
								<?php if ($oct_techstore_data['shownews'] == 'on') { ?>
								<li class="item"><a href="<?php echo $oct_techstore_news; ?>"><?php echo $text_news; ?></a></li>
								<?php } ?>
								<?php if ($oct_techstore_data['showcontacts'] == 'on') { ?>
								<li class="item"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
								<?php } ?>
							</ul>
							<!-- HEADER NAVIGATION END -->
							<!-- PHONE END-->
							<div class="phones-top-box">
								<?php if ($oct_techstore_cont_phones) { ?>
								<div class="phones-dropdown pr">
									<?php if (count($oct_techstore_cont_phones) >= 1) { ?>
									<a href="#" class="dropdown-toggle df aic" data-toggle="dropdown" aria-expanded="false" data-hover="dropdown">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M23.0001 10.9998C22.7348 10.9998 22.4805 10.8944 22.2929 10.7069C22.1054 10.5194 22.0001 10.265 22.0001 9.99981C21.9979 7.87872 21.1544 5.84512 19.6546 4.34529C18.1547 2.84546 16.1211 2.00192 14.0001 1.99981C13.7348 1.99981 13.4805 1.89445 13.2929 1.70691C13.1054 1.51938 13.0001 1.26502 13.0001 0.999807C13.0001 0.73459 13.1054 0.480236 13.2929 0.2927C13.4805 0.105163 13.7348 -0.00019346 14.0001 -0.00019346C16.6513 0.00271814 19.1932 1.05722 21.0679 2.93195C22.9426 4.80669 23.9971 7.34854 24.0001 9.99981C24.0001 10.265 23.8947 10.5194 23.7072 10.7069C23.5196 10.8944 23.2653 10.9998 23.0001 10.9998ZM20.0001 9.99981C20.0001 8.40851 19.3679 6.88238 18.2427 5.75717C17.1175 4.63195 15.5914 3.99981 14.0001 3.99981C13.7348 3.99981 13.4805 4.10516 13.2929 4.2927C13.1054 4.48024 13.0001 4.73459 13.0001 4.99981C13.0001 5.26502 13.1054 5.51938 13.2929 5.70691C13.4805 5.89445 13.7348 5.99981 14.0001 5.99981C15.0609 5.99981 16.0783 6.42123 16.8285 7.17138C17.5786 7.92152 18.0001 8.93894 18.0001 9.99981C18.0001 10.265 18.1054 10.5194 18.2929 10.7069C18.4805 10.8944 18.7348 10.9998 19.0001 10.9998C19.2653 10.9998 19.5196 10.8944 19.7072 10.7069C19.8947 10.5194 20.0001 10.265 20.0001 9.99981ZM22.1831 22.1638L23.0931 21.1148C23.6723 20.5337 23.9975 19.7467 23.9975 18.9263C23.9975 18.1059 23.6723 17.3189 23.0931 16.7378C23.0621 16.7068 20.6561 14.8558 20.6561 14.8558C20.0786 14.3061 19.3116 14.0001 18.5143 14.0012C17.7171 14.0023 16.951 14.3105 16.3751 14.8618L14.4691 16.4678C12.9132 15.8239 11.4999 14.879 10.3103 13.6873C9.12074 12.4957 8.17827 11.0807 7.53705 9.52381L9.13705 7.62381C9.68877 7.04798 9.99735 6.28168 9.99865 5.48421C9.99996 4.68674 9.69388 3.91943 9.14405 3.34181C9.14405 3.34181 7.29105 0.938807 7.26005 0.907807C6.6895 0.333555 5.91593 0.00682856 5.10648 -0.00177868C4.29702 -0.0103859 3.51668 0.299817 2.93405 0.861807L1.78405 1.86181C-5.00995 9.74381 9.62005 24.2608 17.7621 23.9998C18.5842 24.0046 19.399 23.8446 20.1584 23.5292C20.9177 23.2139 21.6061 22.7496 22.1831 22.1638Z" fill="#6CBBB0"/>
										</svg>
										<span><?php echo $oct_techstore_cont_phones[0]; ?></span> 
										<svg class="arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M11.8082 1.04845L11.4151 0.652642C11.2914 0.529031 11.1268 0.460933 10.9509 0.460933C10.7751 0.460933 10.6103 0.529031 10.4866 0.652642L6.00268 5.13678L1.51357 0.647666C1.39006 0.524055 1.22528 0.456055 1.04947 0.456055C0.873666 0.456055 0.708786 0.524055 0.585176 0.647666L0.192002 1.04104C-0.0640005 1.29684 -0.0640005 1.71353 0.192002 1.96934L5.53683 7.33338C5.66034 7.45689 5.82493 7.54392 6.00229 7.54392H6.00434C6.18025 7.54392 6.34483 7.45679 6.46835 7.33338L11.8082 1.98387C11.9319 1.86036 11.9998 1.6908 12 1.51499C12 1.33909 11.9319 1.17187 11.8082 1.04845Z" fill="#002C3E" fill-opacity="0.2"/>
										</svg>
									</a>
									<ul class="dropdown-menu">
										<?php foreach($oct_techstore_cont_phones as $element) { ?>
										<li>
											<a href="tel:+<?php echo preg_replace('/\D/', '', $element); ?>" class="df aic" onclick="window.location.href='tel:+<?php echo preg_replace('/\D/', '', $element); ?>';return false;">
												<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M23.0001 10.9998C22.7348 10.9998 22.4805 10.8944 22.2929 10.7069C22.1054 10.5194 22.0001 10.265 22.0001 9.99981C21.9979 7.87872 21.1544 5.84512 19.6546 4.34529C18.1547 2.84546 16.1211 2.00192 14.0001 1.99981C13.7348 1.99981 13.4805 1.89445 13.2929 1.70691C13.1054 1.51938 13.0001 1.26502 13.0001 0.999807C13.0001 0.73459 13.1054 0.480236 13.2929 0.2927C13.4805 0.105163 13.7348 -0.00019346 14.0001 -0.00019346C16.6513 0.00271814 19.1932 1.05722 21.0679 2.93195C22.9426 4.80669 23.9971 7.34854 24.0001 9.99981C24.0001 10.265 23.8947 10.5194 23.7072 10.7069C23.5196 10.8944 23.2653 10.9998 23.0001 10.9998ZM20.0001 9.99981C20.0001 8.40851 19.3679 6.88238 18.2427 5.75717C17.1175 4.63195 15.5914 3.99981 14.0001 3.99981C13.7348 3.99981 13.4805 4.10516 13.2929 4.2927C13.1054 4.48024 13.0001 4.73459 13.0001 4.99981C13.0001 5.26502 13.1054 5.51938 13.2929 5.70691C13.4805 5.89445 13.7348 5.99981 14.0001 5.99981C15.0609 5.99981 16.0783 6.42123 16.8285 7.17138C17.5786 7.92152 18.0001 8.93894 18.0001 9.99981C18.0001 10.265 18.1054 10.5194 18.2929 10.7069C18.4805 10.8944 18.7348 10.9998 19.0001 10.9998C19.2653 10.9998 19.5196 10.8944 19.7072 10.7069C19.8947 10.5194 20.0001 10.265 20.0001 9.99981ZM22.1831 22.1638L23.0931 21.1148C23.6723 20.5337 23.9975 19.7467 23.9975 18.9263C23.9975 18.1059 23.6723 17.3189 23.0931 16.7378C23.0621 16.7068 20.6561 14.8558 20.6561 14.8558C20.0786 14.3061 19.3116 14.0001 18.5143 14.0012C17.7171 14.0023 16.951 14.3105 16.3751 14.8618L14.4691 16.4678C12.9132 15.8239 11.4999 14.879 10.3103 13.6873C9.12074 12.4957 8.17827 11.0807 7.53705 9.52381L9.13705 7.62381C9.68877 7.04798 9.99735 6.28168 9.99865 5.48421C9.99996 4.68674 9.69388 3.91943 9.14405 3.34181C9.14405 3.34181 7.29105 0.938807 7.26005 0.907807C6.6895 0.333555 5.91593 0.00682856 5.10648 -0.00177868C4.29702 -0.0103859 3.51668 0.299817 2.93405 0.861807L1.78405 1.86181C-5.00995 9.74381 9.62005 24.2608 17.7621 23.9998C18.5842 24.0046 19.399 23.8446 20.1584 23.5292C20.9177 23.2139 21.6061 22.7496 22.1831 22.1638Z" fill="#6CBBB0"/>
												</svg>
												<?php echo $element; ?>
											</a>
										</li>
										<?php } ?>
									</ul>
									<?php } else { ?>
									<a href="#"><i class="fa fa-phone"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $oct_techstore_cont_phones[0]; ?></span> <i class="fa fa-caret-down"></i></a>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
							<!-- PHONE END-->
							
							 <?php if (!empty($UA_REDIRECTION_URI)) { ?> 
								 <?php if (!$isMobile) { ?> 
									<div id="ua-popup_pc_bg"></div>
									<div id="ua-popup_pc" class="ua-popup_pc" style="display: none;">
										<h3>Виберіть бажану мову сайту</h3>
										<div class="buttons-box">
											<div class="text-right">
												<a class="popup-button popup-button-wide close_popup" id="popup-button-link-ru-pc" style="background-color: grey;">RU</a>
											</div>
											<div class="text-left">
												<a class="popup-button popup-button-wide ua_lang" id="popup-button-link-ua-pc" href="<?php echo $UA_REDIRECTION_URI; ?>">UA</a>
											</div>
										</div>
									</div>
								
									<script>
										$(document).ready(function(){

											$('#popup-button-link-ru-pc').on('click', function(){
												$('.ua-popup_pc').css('display','none');
												$('#ua-popup_pc_bg').css('display','none');
												$('html').css('overflow-y','auto');
											});
											$('html').css('overflow-y','hidden');
											$('.ua-popup_pc').css('display','block');
											$('#ua-popup_pc_bg').css('display','block');
										});

									</script>
								 <?php } ?>
								<?php } ?>
							<?php echo $language; ?>
						</div>
					</div>
					<div class="header-second-line">
						<div class="wrap df aic">
							<?php if ($oct_megamenu) { ?>
							<?php echo $oct_megamenu; ?>
							<?php } ?>
							<div class="search-wrap">
								<?php echo $search; ?>
							</div>
							<div class="header-actions">

								<?php if ($logged) { ?>
								<div class="account-wrap">
									<a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="account-btn">
										<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 12C12.3137 12 15 9.31371 15 6C15 2.68629 12.3137 0 9 0C5.68629 0 3 2.68629 3 6C3 9.31371 5.68629 12 9 12Z" fill="white"/>
											<path d="M9 14C4.03172 14.0055 0.00553125 18.0317 0 23C0 23.5523 0.447703 24 0.999984 24H17C17.5522 24 18 23.5523 18 23C17.9945 18.0317 13.9683 14.0055 9 14Z" fill="white"/>
										</svg>
									</a>
									<ul class="dropdown-menu user-dropdown-menu" hidden>
										<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
										<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
										<!--li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li-->
										<!--li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li-->
										<li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
									</ul>
								</div>
								<?php } else { ?>
								<a class="account-btn" onclick="get_oct_popup_login();">
									<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M9 12C12.3137 12 15 9.31371 15 6C15 2.68629 12.3137 0 9 0C5.68629 0 3 2.68629 3 6C3 9.31371 5.68629 12 9 12Z" fill="white"/>
										<path d="M9 14C4.03172 14.0055 0.00553125 18.0317 0 23C0 23.5523 0.447703 24 0.999984 24H17C17.5522 24 18 23.5523 18 23C17.9945 18.0317 13.9683 14.0055 9 14Z" fill="white"/>
									</svg>
								</a>
								<?php } ?>	
								<a href="<?php echo $link_compare; ?>" class="compare-btn">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="white"/>
									</svg>

									<span id="oct-compare-quantity" class="compare-quantity <?php if( intval($total_compare) <= 0) { ?>hidden<?php } ?>"><?php echo $total_compare; ?></span>

									</a>
									<a href="<?php echo $link_wishlist; ?>" class="wishlist-btn">
										<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="white"/>
										</svg>
										<span id="oct-favorite-quantity" class="wishlist-quantity <?php if( intval($total_wishlist) <= 0) { ?>hidden<?php } ?>"><?php echo $total_wishlist; ?></span>
											
										</a>
										<?php echo $cart; ?>
									</div>								
								</div>
							</div>
						</header>
						<!--mobile-menu-->
						<div class="mobile-menu">
							<div class="wrap_menu">
								<div class="head">
									<div class="logo">
										<a href="<?php echo $home; ?>">
											<svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											viewBox="0 0 256.2 76.5" width="80" style="enable-background:new 0 0 256.2 76.5;" xml:space="preserve">
											<g>
												<path d="M57.7,6.2c-0.3-1.3-1-2.5-1.8-3.4c-0.8-0.9-1.8-1.6-3-2.1C51.7,0.3,50.5,0,49.2,0c-1.7,0-3.3,0.5-4.7,1.5
												c-1.5,1-2.5,2.6-3.1,4.6c-2.1,7.2-4.3,14.8-6.6,22.4c-1.8,5.8-3.7,11.8-5.7,17.7c-1.9-5.9-3.8-11.7-5.5-17.5
												c-2.2-7.6-4.4-15.1-6.5-22.3c-0.7-2.1-1.8-3.7-3.3-4.8C12.4,0.5,10.7,0,9,0C7.8,0,6.5,0.3,5.3,0.8C4.1,1.3,3,2.1,2.2,3
												C1.3,4,0.6,5.2,0.3,6.5c-0.4,1.4-0.3,3,0.1,4.6c2.6,9.1,5.4,18.5,8.4,28c3,9.4,6.3,19.2,9.7,29.1c1.1,3.1,2.4,5.2,4.2,6.5
												c1.7,1.2,3.8,1.9,6.1,1.9c2.1,0,4.1-0.6,5.9-1.9c1.8-1.3,3.3-3.4,4.3-6.4c3.5-10,6.8-19.9,9.9-29.4c3.1-9.5,6-19,8.6-28.2
												C57.9,9.1,58,7.6,57.7,6.2z" style="fill:#fff;"/>
												<path d="M191.3,41.7c-1.6-2.9-4-5.3-7.4-7.2c-1.3-0.7-2.8-1.3-4.4-1.9c-1.6-0.5-3.3-1-5-1.5c-1.7-0.4-3.5-0.8-5.3-1.2
												c-1.7-0.3-3.4-0.7-5-1.1c-3.3-0.8-5.8-1.7-7.2-2.6c-0.4-0.3-1.6-1.1-1.6-3.7c0-2,0.8-3.5,2.5-4.6c1.9-1.2,4.7-1.9,8.4-1.9
												c1.5,0,2.8,0.1,3.9,0.2c1.1,0.2,2.1,0.3,3,0.5c1.5,0.3,2.7,0.6,3.6,1c1.3,0.5,2.6,0.7,4,0.7c2.8,0,5-0.9,6.6-2.7
												c1.5-1.7,2.3-3.6,2.3-5.6c0-1.7-0.5-3.3-1.6-4.7c-1.1-1.5-2.8-2.6-5-3.2c-2.8-0.9-5.7-1.5-8.7-1.8c-2.9-0.3-6-0.5-9.3-0.5
												c-3.8,0-7.5,0.5-10.9,1.6c-3.5,1-6.6,2.6-9.2,4.6c-2.6,2.1-4.8,4.6-6.3,7.7c-1.5,3-2.3,6.6-2.3,10.5c0,4.5,1,8.1,3,10.8
												c1.9,2.6,4.4,4.8,7.4,6.4c1.3,0.6,2.9,1.2,4.6,1.7c1.7,0.5,3.4,1,5.1,1.3c1.7,0.4,3.4,0.8,5.1,1.1c1.6,0.4,3.1,0.7,4.4,1
												c4.6,1.2,6.6,2.2,7.4,2.9c1,0.8,1.4,1.9,1.4,3.4c0,2.3-0.9,4-2.8,5.3c-2,1.4-5.5,2.1-10.4,2.1c-1.8,0-3.4-0.1-4.7-0.3
												c-1.4-0.2-2.6-0.4-3.7-0.6c-1.9-0.4-3.4-0.9-4.4-1.3c-1.5-0.6-3-0.9-4.5-0.9c-2.6,0-4.8,0.9-6.3,2.6c-1.4,1.6-2.1,3.5-2.1,5.7
												c0,1.6,0.5,3.2,1.5,4.6c1,1.5,2.7,2.6,4.8,3.4c3.2,1.1,6.5,1.8,9.9,2.2c3.3,0.4,7,0.6,10.8,0.6c4.3,0,8.3-0.5,12-1.5
												c3.7-1,7-2.6,9.8-4.6c2.8-2.1,5-4.7,6.6-7.9c1.6-3.1,2.4-6.8,2.4-10.9C193.6,47.9,192.8,44.6,191.3,41.7z" style="fill:#fff;"/>
												<path d="M253.9,2.5C252.3,0.8,250.3,0,248,0h-41.4c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1
												c1.6,1.7,3.5,2.5,5.8,2.5h12.3v50.5c0,2.4,0.8,4.5,2.4,6.2c1.6,1.7,3.6,2.6,6,2.6c2.3,0,4.3-0.9,6-2.6c1.6-1.7,2.4-3.8,2.4-6.2
												V17.2H248c2.3,0,4.2-0.8,5.8-2.5c1.6-1.7,2.4-3.7,2.4-6.1C256.2,6.2,255.4,4.2,253.9,2.5z" style="fill:#fff;"/>
											</g>
											<g>
												<path class="st0" d="M119.5,17c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
												<path class="st0" d="M119.5,46.7c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
												<path class="st0" d="M119.5,76.5c1.5-0.4,2.8-1.1,3.9-2.3c1.6-1.7,2.4-3.7,2.4-6.1c0-2.4-0.8-4.4-2.4-6.1c-1.6-1.7-3.5-2.5-5.8-2.5
												H76.1c-2.3,0-4.2,0.8-5.8,2.5c-1.6,1.7-2.4,3.7-2.4,6.1c0,2.4,0.8,4.4,2.4,6.1c1.1,1.2,2.4,1.9,3.9,2.3H119.5z" style="fill:#49B9AE;"/>
											</g>
										</svg>
									</a>
								</div>
								<?php echo $language; ?>
							</div>
							<div class="content">
								<div class="account">
									<?php if ($logged) { ?>
									<div class="account-wrap">
										<a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="account-btn">
											<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M9 12C12.3137 12 15 9.31371 15 6C15 2.68629 12.3137 0 9 0C5.68629 0 3 2.68629 3 6C3 9.31371 5.68629 12 9 12Z" fill="white"/>
												<path d="M9 14C4.03172 14.0055 0.00553125 18.0317 0 23C0 23.5523 0.447703 24 0.999984 24H17C17.5522 24 18 23.5523 18 23C17.9945 18.0317 13.9683 14.0055 9 14Z" fill="white"/>
											</svg>
											<?php echo $text_account; ?>
										</a>
									</div>
									<?php } else { ?>
									<a class="account-btn" onclick="get_oct_popup_login();">
										<svg width="18" height="24" viewBox="0 0 18 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 12C12.3137 12 15 9.31371 15 6C15 2.68629 12.3137 0 9 0C5.68629 0 3 2.68629 3 6C3 9.31371 5.68629 12 9 12Z" fill="white"/>
											<path d="M9 14C4.03172 14.0055 0.00553125 18.0317 0 23C0 23.5523 0.447703 24 0.999984 24H17C17.5522 24 18 23.5523 18 23C17.9945 18.0317 13.9683 14.0055 9 14Z" fill="white"/>
										</svg>
										<?php echo $text_enter; ?>
									</a>
									<?php } ?>	

									<a href="<?php echo $link_compare; ?>" class="compare-btn">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M11.6594 0.0862219C11.2979 0.277117 11.3213 -0.0170584 11.2951 4.65583L11.2716 8.82953L5.87777 10.3327C2.91117 11.1594 0.436429 11.8523 0.378363 11.8725C0.224663 11.9261 0.0336272 12.2002 0.00586068 12.4071C-0.0480776 12.8091 0.277569 13.1806 0.683091 13.1797C0.818359 13.1794 1.43781 13.0296 2.25757 12.7991C3.00094 12.59 3.62784 12.4189 3.65078 12.4189C3.67367 12.4189 2.86501 13.7961 1.85369 15.4793L0.0149129 18.5397L0.0187121 18.9853C0.0236838 19.5709 0.138408 20.1143 0.387087 20.7304C1.49817 23.4828 4.71908 24.765 7.38115 23.5145C8.52112 22.979 9.3264 22.2029 9.86541 21.1201C10.18 20.4881 10.3391 19.9413 10.4089 19.2518C10.4548 18.7988 10.452 18.7033 10.3883 18.5511C10.3478 18.4542 9.41453 16.875 8.31452 15.0418C7.21446 13.2086 6.32687 11.6962 6.34216 11.6809C6.37307 11.65 17.2206 8.62733 17.2416 8.64379C17.2489 8.64951 16.4441 10.0053 15.453 11.6567C14.462 13.3081 13.6258 14.7358 13.595 14.8293C13.5197 15.0574 13.6091 15.912 13.7643 16.4479C14.2056 17.9717 15.3916 19.2877 16.8356 19.8558C17.8813 20.2673 19.1433 20.3354 20.2008 20.0374C20.7779 19.8749 21.5616 19.4672 22.0413 19.08C22.8944 18.3916 23.5315 17.4284 23.8182 16.3939C23.9342 15.9753 24.0322 15.1263 23.99 14.9048C23.9725 14.8124 23.0673 13.2546 21.9195 11.3416C20.7979 9.47205 19.8956 7.92805 19.9144 7.91047C19.9333 7.89283 20.7612 7.65395 21.7544 7.37962C22.7476 7.10528 23.6116 6.84338 23.6745 6.7976C24.2182 6.40207 24.032 5.60589 23.38 5.5384C23.2274 5.52264 22.0605 5.83079 18.0256 6.95252C15.188 7.74138 12.8293 8.39844 12.7842 8.41265C12.7065 8.43714 12.7021 8.22922 12.7021 4.49364C12.7021 0.632499 12.7001 0.544838 12.6077 0.363652C12.4347 0.0245446 12.011 -0.0994197 11.6594 0.0862219ZM20.4238 11.5848L22.0355 14.2716L20.4209 14.2839C19.5329 14.2907 18.0682 14.2907 17.1659 14.2839L15.5255 14.2716L17.1493 11.5654C18.0424 10.077 18.7818 8.86789 18.7926 8.87863C18.8033 8.88933 19.5373 10.1071 20.4238 11.5848ZM6.88229 15.389C7.76153 16.8549 8.48088 18.0633 8.48088 18.0742C8.48088 18.0852 7.01399 18.0942 5.22113 18.0942C3.42827 18.0942 1.96138 18.0817 1.96138 18.0666C1.96138 18.0365 5.08793 12.8092 5.16522 12.7101C5.19121 12.6767 5.2285 12.6662 5.2481 12.6866C5.26771 12.707 6.0031 13.9231 6.88229 15.389Z" fill="white"/>
										</svg>
										<span id="oct-compare-quantity" class="compare-quantity <?php if( intval($total_compare) <= 0) { ?>hidden<?php } ?>"><?php echo $total_compare; ?></span>
											
										</a>
										<a href="<?php echo $link_wishlist; ?>" class="wishlist-btn">
											<svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M19.9646 0C18.4894 0 17.1369 0.467469 15.9448 1.38945C14.8018 2.27336 14.0409 3.39919 13.5929 4.21784C13.1449 3.39913 12.384 2.27336 11.2411 1.38945C10.0489 0.467469 8.69644 0 7.22124 0C3.1045 0 0 3.36727 0 7.8326C0 12.6567 3.87308 15.9573 9.73641 20.9539C10.7321 21.8025 11.8607 22.7643 13.0337 23.7901C13.1883 23.9255 13.3869 24 13.5929 24C13.7989 24 13.9975 23.9255 14.1521 23.7901C15.3253 22.7642 16.4538 21.8024 17.4501 20.9534C23.3128 15.9573 27.1858 12.6567 27.1858 7.8326C27.1858 3.36727 24.0813 0 19.9646 0Z" fill="white"/>
											</svg>
											<span id="oct-favorite-quantity" class="wishlist-quantity <?php if( intval($total_wishlist) <= 0) { ?>hidden<?php } ?>"><?php echo $total_wishlist; ?></span>
											</a>
										</div>



										<?php if ($oct_megamenu) { ?>
										<?php echo $oct_megamenu; ?>
										<?php } ?>


										<ul>
											<li class="item">
												<a href="<?php echo $special; ?>">
													<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
														<g clip-path="url(#clip0_246_98126)">
															<path d="M2.37399 20.125C2.82902 20.3195 3.33211 20.3723 3.81761 20.2765C4.30312 20.1808 4.7485 19.941 5.09565 19.5883L9.99982 14.7108L14.904 19.5883C15.1327 19.8203 15.4051 20.0047 15.7054 20.1307C16.0058 20.2567 16.3282 20.3219 16.654 20.3225C16.9888 20.3215 17.3202 20.2544 17.629 20.125C18.0877 19.9393 18.4799 19.6197 18.7541 19.2077C19.0284 18.7958 19.1721 18.3107 19.1665 17.8158V4.66667C19.1652 3.562 18.7258 2.50296 17.9446 1.72185C17.1635 0.940735 16.1045 0.501323 14.9998 0.5L4.99982 0.5C3.89516 0.501323 2.83612 0.940735 2.055 1.72185C1.27389 2.50296 0.834476 3.562 0.833153 4.66667V17.8158C0.827775 18.3111 0.971967 18.7964 1.24687 19.2084C1.52178 19.6204 1.9146 19.9399 2.37399 20.125Z" fill="#6CBBB0"/>
														</g>
														<defs>
															<clipPath id="clip0_246_98126">
																<rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
															</clipPath>
														</defs>
													</svg>
													<?php echo $text_special; ?>
												</a>
											</li>
											<li class="has_children">
												<span class="btn-menu-children">
													<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
														<g clip-path="url(#clip0_246_98168)">
															<path d="M10.696 0.523414C9.26734 0.423448 7.83383 0.631535 6.49252 1.13359C5.15121 1.63565 3.93339 2.41997 2.92147 3.43348C1.90954 4.44698 1.12713 5.66602 0.627159 7.00812C0.127193 8.35021 -0.0786566 9.78404 0.0235385 11.2126C0.391039 16.5067 5.06771 20.5001 10.9019 20.5001H15.8327C16.9374 20.4988 17.9964 20.0593 18.7775 19.2782C19.5586 18.4971 19.9981 17.4381 19.9994 16.3334V10.7834C20.0305 8.21462 19.0886 5.72916 17.363 3.82606C15.6373 1.92295 13.2556 0.743112 10.696 0.523414ZM9.99937 4.66675C10.3309 4.66675 10.6488 4.79844 10.8833 5.03286C11.1177 5.26728 11.2494 5.58523 11.2494 5.91675C11.2494 6.24827 11.1177 6.56621 10.8833 6.80063C10.6488 7.03505 10.3309 7.16675 9.99937 7.16675C9.66785 7.16675 9.34991 7.03505 9.11549 6.80063C8.88107 6.56621 8.74937 6.24827 8.74937 5.91675C8.74937 5.58523 8.88107 5.26728 9.11549 5.03286C9.34991 4.79844 9.66785 4.66675 9.99937 4.66675ZM11.666 15.5001C11.666 15.7211 11.5782 15.9331 11.422 16.0893C11.2657 16.2456 11.0537 16.3334 10.8327 16.3334C10.6117 16.3334 10.3997 16.2456 10.2435 16.0893C10.0872 15.9331 9.99937 15.7211 9.99937 15.5001V10.5001H9.16604C8.94503 10.5001 8.73306 10.4123 8.57678 10.256C8.4205 10.0997 8.33271 9.88776 8.33271 9.66675C8.33271 9.44573 8.4205 9.23377 8.57678 9.07749C8.73306 8.92121 8.94503 8.83341 9.16604 8.83341H9.99937C10.4414 8.83341 10.8653 9.00901 11.1779 9.32157C11.4904 9.63413 11.666 10.0581 11.666 10.5001V15.5001Z" fill="#6CBBB0"/>
														</g>
														<defs>
															<clipPath id="clip0_246_98168">
																<rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
															</clipPath>
														</defs>
													</svg>
													<?php echo $text_customers; ?>
													<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: auto; fill: #000a0e;">
														<path d="M3.04845 0.191807L2.65264 0.584883C2.52903 0.708591 2.46093 0.873178 2.46093 1.04908C2.46093 1.22489 2.52903 1.38967 2.65264 1.51338L7.13678 5.99732L2.64767 10.4864C2.52406 10.6099 2.45605 10.7747 2.45605 10.9505C2.45605 11.1263 2.52406 11.2912 2.64767 11.4148L3.04104 11.808C3.29684 12.064 3.71353 12.064 3.96934 11.808L9.33338 6.46317C9.45689 6.33966 9.54392 6.17507 9.54392 5.99771V5.99566C9.54392 5.81975 9.45679 5.65517 9.33338 5.53165L3.98387 0.191807C3.86036 0.0680981 3.6908 0.00019455 3.51499 0C3.33909 0 3.17187 0.0680981 3.04845 0.191807Z" fill="#000a0e" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?> fill-opacity="0.2"/>
														</svg>
													</span>
													<div class="sub_menu">
														<button class="btn-back">
															<svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
																<g clip-path="url(#clip0_246_98539)">
																	<path d="M10.696 0.523414C9.26734 0.423448 7.83383 0.631535 6.49252 1.13359C5.15121 1.63565 3.93339 2.41997 2.92147 3.43348C1.90954 4.44698 1.12713 5.66602 0.627159 7.00812C0.127193 8.35021 -0.0786566 9.78404 0.0235385 11.2126C0.391039 16.5067 5.06771 20.5001 10.9019 20.5001H15.8327C16.9374 20.4988 17.9964 20.0593 18.7775 19.2782C19.5586 18.4971 19.9981 17.4381 19.9994 16.3334V10.7834C20.0305 8.21462 19.0886 5.72916 17.363 3.82606C15.6373 1.92295 13.2556 0.743112 10.696 0.523414ZM9.99937 4.66675C10.3309 4.66675 10.6488 4.79844 10.8833 5.03286C11.1177 5.26728 11.2494 5.58523 11.2494 5.91675C11.2494 6.24827 11.1177 6.56621 10.8833 6.80063C10.6488 7.03505 10.3309 7.16675 9.99937 7.16675C9.66785 7.16675 9.34991 7.03505 9.11549 6.80063C8.88107 6.56621 8.74937 6.24827 8.74937 5.91675C8.74937 5.58523 8.88107 5.26728 9.11549 5.03286C9.34991 4.79844 9.66785 4.66675 9.99937 4.66675ZM11.666 15.5001C11.666 15.7211 11.5782 15.9331 11.422 16.0893C11.2657 16.2456 11.0537 16.3334 10.8327 16.3334C10.6117 16.3334 10.3997 16.2456 10.2435 16.0893C10.0872 15.9331 9.99937 15.7211 9.99937 15.5001V10.5001H9.16604C8.94503 10.5001 8.73306 10.4123 8.57678 10.256C8.4205 10.0997 8.33271 9.88776 8.33271 9.66675C8.33271 9.44573 8.4205 9.23377 8.57678 9.07749C8.73306 8.92121 8.94503 8.83341 9.16604 8.83341H9.99937C10.4414 8.83341 10.8653 9.00901 11.1779 9.32157C11.4904 9.63413 11.666 10.0581 11.666 10.5001V15.5001Z" fill="#6CBBB0"/>
																</g>
																<defs>
																	<clipPath id="clip0_246_98539">
																		<rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
																	</clipPath>
																</defs>
															</svg>
															<span><?php echo $text_customers; ?></span>
															<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
																<path d="M8.95155 0.191807L9.34736 0.584883C9.47097 0.708591 9.53907 0.873178 9.53907 1.04908C9.53907 1.22489 9.47097 1.38967 9.34736 1.51338L4.86322 5.99732L9.35233 10.4864C9.47594 10.6099 9.54395 10.7747 9.54395 10.9505C9.54395 11.1263 9.47594 11.2912 9.35233 11.4148L8.95896 11.808C8.70316 12.064 8.28647 12.064 8.03066 11.808L2.66662 6.46317C2.54311 6.33966 2.45608 6.17507 2.45608 5.99771V5.99566C2.45608 5.81975 2.54321 5.65517 2.66662 5.53165L8.01613 0.191807C8.13964 0.0680981 8.3092 0.00019455 8.48501 0C8.66091 0 8.82813 0.0680981 8.95155 0.191807Z" fill="#000a0e" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?> fill-opacity="0.2"></path>
															</svg>
														</button>
														<ul>
															<li>
																<a href="<?php echo $static_information_1; ?>"><?php echo $text_payment; ?></a>
															</li>
															<li>
																<a href="<?php echo $static_information_2; ?>"><?php echo $text_return; ?></a>
															</li>
															<li>
																<a href="<?php echo $oct_techstore_news; ?>"><?php echo $text_blog; ?></a>
															</li>
														</ul>
													</div>
												</li>	
												<li class="mobile-cart">
													<a onclick="get_oct_popup_cart();">
														<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" clip-rule="evenodd" d="M0.185547 1.1949V2.3898L1.38352 2.40232L2.58149 2.41484L4.61616 6.7292C5.73524 9.10208 6.70116 11.153 6.76269 11.2867L6.87453 11.53L6.03517 12.9764C5.28799 14.264 5.18408 14.4689 5.0889 14.8428C4.8372 15.8311 5.04091 16.6529 5.69878 17.3036C5.84406 17.4473 6.08815 17.6319 6.24122 17.7138C6.81287 18.0199 6.37794 18.0044 14.4115 18.0051L21.7054 18.0058V16.8101V15.6144H14.7604C7.14538 15.6144 7.6078 15.6318 7.6078 15.3462C7.6078 15.2791 7.84409 14.7747 8.13296 14.2254L8.65807 13.2268L13.3029 13.2127L17.9476 13.1985L18.251 13.0943C18.7941 12.9076 19.1943 12.5863 19.4803 12.107C19.568 11.96 20.58 10.1306 21.7291 8.04166C22.8783 5.9527 23.8563 4.20135 23.9025 4.14978C24.1069 3.92166 24.1417 3.52876 23.9929 3.12926C23.8867 2.84417 23.681 2.62214 23.4093 2.49929C23.2318 2.41901 22.7748 2.41423 14.2112 2.40256L5.19991 2.39022L4.66499 1.19518L4.13008 9.37803e-05L2.15781 4.68901e-05L0.185547 0V1.1949ZM6.62643 19.3317C5.37113 19.7616 4.68572 21.1108 5.09044 22.3552C5.35167 23.1586 6.1374 23.8269 6.98862 23.9699C8.20265 24.1738 9.42853 23.3205 9.68799 22.091C9.77603 21.6737 9.77159 21.4617 9.66628 21.048C9.45169 20.2051 8.81702 19.5454 7.98442 19.2997C7.6085 19.1888 7.00183 19.2031 6.62643 19.3317ZM18.5767 19.3317C17.3214 19.7616 16.636 21.1108 17.0407 22.3552C17.302 23.1586 18.0877 23.8269 18.9389 23.9699C20.1529 24.1738 21.3788 23.3205 21.6383 22.091C21.7263 21.6737 21.7219 21.4617 21.6166 21.048C21.402 20.2051 20.7673 19.5454 19.9347 19.2997C19.5588 19.1888 18.9521 19.2031 18.5767 19.3317Z" fill="white"/>
														</svg>
														<span>
															<?php echo $text_cart; ?>
														</span>
														<span class="count"><?php echo $cart; ?></span>
													</a>
												</li>
												<li class="has_children">
													<span>

														<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
															<g clip-path="url(#clip0_246_98138)">
																<path d="M19.1669 9.66618C18.9459 9.66618 18.7339 9.57838 18.5777 9.4221C18.4214 9.26582 18.3336 9.05386 18.3336 8.83285C18.3318 7.06528 17.6289 5.37061 16.379 4.12075C15.1291 2.87089 13.4345 2.16795 11.6669 2.16618C11.4459 2.16618 11.2339 2.07838 11.0777 1.9221C10.9214 1.76582 10.8336 1.55386 10.8336 1.33285C10.8336 1.11183 10.9214 0.899871 11.0777 0.743591C11.2339 0.587311 11.4459 0.499513 11.6669 0.499513C13.8763 0.50194 15.9945 1.38069 17.5568 2.94297C19.1191 4.50525 19.9978 6.62345 20.0002 8.83285C20.0002 9.05386 19.9124 9.26582 19.7562 9.4221C19.5999 9.57838 19.3879 9.66618 19.1669 9.66618ZM16.6669 8.83285C16.6669 7.50676 16.1401 6.23499 15.2024 5.29731C14.2648 4.35963 12.993 3.83285 11.6669 3.83285C11.4459 3.83285 11.2339 3.92064 11.0777 4.07692C10.9214 4.2332 10.8336 4.44517 10.8336 4.66618C10.8336 4.88719 10.9214 5.09916 11.0777 5.25544C11.2339 5.41172 11.4459 5.49951 11.6669 5.49951C12.551 5.49951 13.3988 5.8507 14.0239 6.47582C14.6491 7.10095 15.0002 7.94879 15.0002 8.83285C15.0002 9.05386 15.088 9.26582 15.2443 9.4221C15.4006 9.57838 15.6126 9.66618 15.8336 9.66618C16.0546 9.66618 16.2666 9.57838 16.4228 9.4221C16.5791 9.26582 16.6669 9.05386 16.6669 8.83285ZM18.4861 18.9695L19.2444 18.0953C19.7271 17.6111 19.9981 16.9553 19.9981 16.2716C19.9981 15.5879 19.7271 14.9321 19.2444 14.4478C19.2186 14.422 17.2136 12.8795 17.2136 12.8795C16.7324 12.4215 16.0932 12.1664 15.4288 12.1673C14.7645 12.1683 14.126 12.4251 13.6461 12.8845L12.0577 14.2228C10.7612 13.6863 9.58348 12.8988 8.59215 11.9058C7.60082 10.9127 6.81543 9.73363 6.28108 8.43618L7.61441 6.85285C8.07417 6.37299 8.33133 5.73441 8.33241 5.06985C8.3335 4.40529 8.07844 3.76587 7.62025 3.28451C7.62025 3.28451 6.07608 1.28201 6.05025 1.25618C5.57479 0.777637 4.93015 0.505365 4.2556 0.498192C3.58106 0.49102 2.93077 0.749522 2.44525 1.21785L1.48691 2.05118C-4.17475 8.61951 8.01691 20.717 14.8019 20.4995C15.4871 20.5035 16.1661 20.3701 16.7988 20.1074C17.4316 19.8446 18.0053 19.4577 18.4861 18.9695Z" fill="#6CBBB0"/>
															</g>
															<defs>
																<clipPath id="clip0_246_98138">
																	<rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
																</clipPath>
															</defs>
														</svg>

														<?php echo $oct_techstore_cont_phones[0]; ?> 
														<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: auto; fill: #000a0e;">
															<path d="M3.04845 0.191807L2.65264 0.584883C2.52903 0.708591 2.46093 0.873178 2.46093 1.04908C2.46093 1.22489 2.52903 1.38967 2.65264 1.51338L7.13678 5.99732L2.64767 10.4864C2.52406 10.6099 2.45605 10.7747 2.45605 10.9505C2.45605 11.1263 2.52406 11.2912 2.64767 11.4148L3.04104 11.808C3.29684 12.064 3.71353 12.064 3.96934 11.808L9.33338 6.46317C9.45689 6.33966 9.54392 6.17507 9.54392 5.99771V5.99566C9.54392 5.81975 9.45679 5.65517 9.33338 5.53165L3.98387 0.191807C3.86036 0.0680981 3.6908 0.00019455 3.51499 0C3.33909 0 3.17187 0.0680981 3.04845 0.191807Z" fill="#000a0e" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>/>
															</svg>
														</span>
														<div class="sub_menu">
															<button class="btn-back">
																<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<g clip-path="url(#clip0_246_98138)">
																		<path d="M19.1669 9.66618C18.9459 9.66618 18.7339 9.57838 18.5777 9.4221C18.4214 9.26582 18.3336 9.05386 18.3336 8.83285C18.3318 7.06528 17.6289 5.37061 16.379 4.12075C15.1291 2.87089 13.4345 2.16795 11.6669 2.16618C11.4459 2.16618 11.2339 2.07838 11.0777 1.9221C10.9214 1.76582 10.8336 1.55386 10.8336 1.33285C10.8336 1.11183 10.9214 0.899871 11.0777 0.743591C11.2339 0.587311 11.4459 0.499513 11.6669 0.499513C13.8763 0.50194 15.9945 1.38069 17.5568 2.94297C19.1191 4.50525 19.9978 6.62345 20.0002 8.83285C20.0002 9.05386 19.9124 9.26582 19.7562 9.4221C19.5999 9.57838 19.3879 9.66618 19.1669 9.66618ZM16.6669 8.83285C16.6669 7.50676 16.1401 6.23499 15.2024 5.29731C14.2648 4.35963 12.993 3.83285 11.6669 3.83285C11.4459 3.83285 11.2339 3.92064 11.0777 4.07692C10.9214 4.2332 10.8336 4.44517 10.8336 4.66618C10.8336 4.88719 10.9214 5.09916 11.0777 5.25544C11.2339 5.41172 11.4459 5.49951 11.6669 5.49951C12.551 5.49951 13.3988 5.8507 14.0239 6.47582C14.6491 7.10095 15.0002 7.94879 15.0002 8.83285C15.0002 9.05386 15.088 9.26582 15.2443 9.4221C15.4006 9.57838 15.6126 9.66618 15.8336 9.66618C16.0546 9.66618 16.2666 9.57838 16.4228 9.4221C16.5791 9.26582 16.6669 9.05386 16.6669 8.83285ZM18.4861 18.9695L19.2444 18.0953C19.7271 17.6111 19.9981 16.9553 19.9981 16.2716C19.9981 15.5879 19.7271 14.9321 19.2444 14.4478C19.2186 14.422 17.2136 12.8795 17.2136 12.8795C16.7324 12.4215 16.0932 12.1664 15.4288 12.1673C14.7645 12.1683 14.126 12.4251 13.6461 12.8845L12.0577 14.2228C10.7612 13.6863 9.58348 12.8988 8.59215 11.9058C7.60082 10.9127 6.81543 9.73363 6.28108 8.43618L7.61441 6.85285C8.07417 6.37299 8.33133 5.73441 8.33241 5.06985C8.3335 4.40529 8.07844 3.76587 7.62025 3.28451C7.62025 3.28451 6.07608 1.28201 6.05025 1.25618C5.57479 0.777637 4.93015 0.505365 4.2556 0.498192C3.58106 0.49102 2.93077 0.749522 2.44525 1.21785L1.48691 2.05118C-4.17475 8.61951 8.01691 20.717 14.8019 20.4995C15.4871 20.5035 16.1661 20.3701 16.7988 20.1074C17.4316 19.8446 18.0053 19.4577 18.4861 18.9695Z" fill="#6CBBB0"/>
																	</g>
																	<defs>
																		<clipPath id="clip0_246_98138">
																			<rect width="20" height="20" fill="white" transform="translate(0 0.5)"/>
																		</clipPath>
																	</defs>
																</svg>
																<span><?php echo $text_return; ?></span>
																<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" style="fill: #000a0e;">
																	<path d="M8.95155 0.191807L9.34736 0.584883C9.47097 0.708591 9.53907 0.873178 9.53907 1.04908C9.53907 1.22489 9.47097 1.38967 9.34736 1.51338L4.86322 5.99732L9.35233 10.4864C9.47594 10.6099 9.54395 10.7747 9.54395 10.9505C9.54395 11.1263 9.47594 11.2912 9.35233 11.4148L8.95896 11.808C8.70316 12.064 8.28647 12.064 8.03066 11.808L2.66662 6.46317C2.54311 6.33966 2.45608 6.17507 2.45608 5.99771V5.99566C2.45608 5.81975 2.54321 5.65517 2.66662 5.53165L8.01613 0.191807C8.13964 0.0680981 8.3092 0.00019455 8.48501 0C8.66091 0 8.82813 0.0680981 8.95155 0.191807Z" fill="#000a0e" fill-opacity="0.2" <?php if ($isMobile) { ?>style="fill: #000a0e;"<?php } ?>></path>
																</svg>
															</button>
															<ul>
																<?php foreach($oct_techstore_cont_phones as $element) { ?>
																<li>
																	<a href="tel:+<?php echo preg_replace('/\D/', '', $element); ?>" class="df aic" onclick="window.location.href='tel:+<?php echo preg_replace('/\D/', '', $element); ?>';return false;">
																		<?php echo $element; ?>
																	</a>
																</li>
																<?php } ?>

															</ul>
														</div>
													</li>
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
													<?php if ($oct_techstore_data['showcontacts'] == 'on') { ?>
													<li class="item">
														<a href="<?php echo $contact; ?>">
															<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																<g clip-path="url(#clip0_246_96075)">
																	<path d="M12.0006 7.94545C12.8417 7.94545 13.5236 7.26356 13.5236 6.42243C13.5236 5.58131 12.8417 4.89941 12.0006 4.89941C11.1594 4.89941 10.4775 5.58131 10.4775 6.42243C10.4775 7.26356 11.1594 7.94545 12.0006 7.94545Z" fill="#6CBBB0"/>
																	<path d="M20.0001 0.000976562H4.00006C1.77636 0.0185547 -0.0132387 1.83323 7.37985e-05 4.05698V16.2269C-0.0132387 18.4508 1.77621 20.2658 4.00006 20.2839H6.92304L10.671 23.4839C11.4134 24.1624 12.5479 24.1724 13.302 23.507L17.147 20.2829H20C22.2239 20.2648 24.0133 18.4499 24 16.226V4.05698C24.0134 1.83323 22.2238 0.0185547 20.0001 0.000976562ZM12.0001 3.04298C16.6151 3.16499 16.6141 10.02 12.0001 10.143C7.38509 10.0199 7.38607 3.16795 12.0001 3.04298ZM16.2491 17.209C15.7139 17.3455 15.1694 17.0224 15.0329 16.4872C15.0323 16.4848 15.0317 16.4824 15.0311 16.48C14.5192 14.8057 12.747 13.8634 11.0728 14.3752C10.065 14.6834 9.27617 15.4722 8.96806 16.48C8.82832 17.0146 8.28167 17.3347 7.74706 17.195C7.21245 17.0552 6.89234 16.5086 7.03207 15.974C7.80532 13.2299 10.6566 11.6323 13.4007 12.4056C15.1301 12.8929 16.4817 14.2445 16.9691 15.974C17.1083 16.5134 16.7871 17.0643 16.2491 17.209Z" fill="#6CBBB0"/>
																</g>
																<defs>
																	<clipPath id="clip0_246_96075">
																		<rect width="24" height="24" fill="white"/>
																	</clipPath>
																</defs>
															</svg>
															<?php echo $text_contact; ?>
														</a>
													</li>
													<?php } ?>
												</ul>











											</div>
										</div>
									</div>
									<div class="menu-overlay"></div>
						<!--mobile-menu end-->