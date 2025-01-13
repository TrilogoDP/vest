<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" data-type="simple">
	<!--<![endif]-->
	<head><!--mf-->		
		<link rel="manifest" href="/manifest.json?rand=<?php echo mt_rand(0, 1000); ?>">
		<script>
			function pushPWAEvent(eventCategory){
				window.dataLayer = window.dataLayer || [];	
				
				dataLayer.push({
					event: 'PWA',
					eventCategory: 'PWA',
					eventAction: eventCategory
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
				
				//displaying block and|or button
				if (localStorage.getItem('pwaaccepted') == 'true'){
					//Если установлено, то не трогать					
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
				if (isStandaloneAPP()){
					pushPWAEvent('pwapageview');
				}
			});
			
			/*Different check functions*/
			function isStandaloneAPP(){
				if (window.matchMedia('(display-mode: standalone)').matches) {
					console.log('[PWA] display-mode is standalone: display-mode');
					return true;
				}
				
				if (document.referrer.includes('android-app://')) {
					console.log('[PWA] display-mode is standalone: android-app/TWA');
					return true;
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
		
		
		
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?><?php echo $title; ?></title>
		<base href="<?php echo $base; ?>" lang="<?php echo rtrim($base_lang, '/'); ?>" />
		
		
		
		



<link rel="icon" type="image/png" href="/pwa/favicon-48x48.png?r=vTy261a" sizes="48x48" />
<link rel="icon" type="image/svg+xml" href="/pwa/favicon.svg?r=vTy261a" />
<link rel="shortcut icon" href="/pwa/favicon.ico?r=vTy261a" />
<link rel="apple-touch-icon" sizes="180x180" href="/pwa/apple-touch-icon.png?r=vTy261a" />
<meta name="apple-mobile-web-app-title" content="VEST" />
<meta name="theme-color" content="#6cbbb0">
<meta name="apple-mobile-web-app-title" content="VEST">
<meta name="application-name" content="VEST">
		
		
		
		<link rel="dns-prefetch" href="//ajax.googleapis.com">
		<link rel="dns-prefetch" href="//www.google.com">
		<link rel="dns-prefetch" href="//fonts.googleapis.com">
		<link rel="dns-prefetch" href="//www.googletagmanager.com">
		<link rel="dns-prefetch" href="//connect.facebook.net">
		
		<?php if ($minified_css_uri) { ?>
			<link rel="preload" href="<? echo $minified_css_uri; ?>" as="style" />	
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
		<meta property="og:title" content="<?php if (isset($_GET['page'])) { echo mb_ucfirst($text_page) . " " . ((int)$_GET['page']) . " - "; } ?><?php echo $title; ?>" />
		<meta property="og:type" content="website" />
		<?php if (isset($og_url)) { ?><meta property="og:url" content="<?php echo $og_url; ?>" /><?php } ?>
		<?php if (isset($og_image)) { ?>
			<meta property="og:image" content="<?php echo $og_image; ?>" />
			<?php } else { ?>
			<meta property="og:image" content="<?php echo $logo; ?>" />
		<?php } ?>
		<meta property="og:site_name" content="<?php echo $name; ?>" />
		<?php if ($robots) { ?>
			<meta name="robots" content="<?php echo $robots; ?>" />
		<?php } ?>
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/themes/fontawesome-stars.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
		
		
		<?php  foreach ($incompatible_scripts as $incompatible_script) { ?>
			<script src="<?php echo $incompatible_script; ?>"></script>
		<?php } ?>		
		
		<?php if ($oct_techstore_customjavascrip) { ?>
			<?php echo $oct_techstore_customjavascrip; ?>
		<?php } ?>
		
		<?php foreach ($analytics as $analytic) { ?>
			<?php echo $analytic; ?>
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
		<?php
			//***mf begin
			if($lang == 'ua'){
				$href_uk = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$href_ru = str_replace("https://vest.in.ua/ua/","https://vest.in.ua/", "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
				}else{
				$href_ru = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$href_uk = str_replace("https://vest.in.ua/", "https://vest.in.ua/ua/", "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			}
			if ($is_homepage){
				$href_uk = "https://vest.in.ua/ua";
				$href_ru = "https://vest.in.ua";			
			}
			//***mf end
		?>
		<link rel="alternate" hreflang="uk" href="<?php echo $href_uk ?>"/>
		<link rel="alternate" hreflang="ru" href="<?php echo $href_ru ?>"/>
		<? /*
			<link rel="alternate" hreflang="uk-UA" href="<?php echo $href_uk ?>"/>
			<link rel="alternate" hreflang="ru-UA" href="<?php echo $href_ru ?>"/>		
			<link rel="alternate" hreflang="x-default" href="<?php echo $href_uk ?>"/>
		*/ ?>
		<meta name="it-rating" content="it-rat-fa9ef9f8c2095cc94c5d33de60ee9bb3" />

		<!-- new style -->
		<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/stylesheet/style_root.css">
		<link rel="stylesheet" type="text/css" href="https://vest.in.ua/catalog/view/theme/oct_techstore/stylesheet/style_new_version.css">
		
		<?php if ($minified_css_uri) { ?>			
			<link href="<? echo $minified_css_uri; ?>" rel="stylesheet" media="screen" />
		<?php } ?>	
		
		<?php /* foreach ($styles as $style) { ?>
			<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } */ ?>
		
		<? /*
			<script src="//cdn.sendpulse.com/js/push/42a039035b2eb0a3d4824c848a544267_1.js" async defer></script>
		*/ ?>

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

				<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

		<script src="
		https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/jquery.magnific-popup.min.js
		"></script>
		<link href="
		https://cdn.jsdelivr.net/npm/magnific-popup@1.1.0/dist/magnific-popup.min.css
		" rel="stylesheet">


		<script src="catalog/view/theme/oct_techstore/js/new-main.js"></script>
		<style type="text/css">
			.alert.alert-danger.simplecheckout-warning-block{
			   	color: #BB4220;
    			font-weight: 500;
			}
			#footer_app{
				display: none !important;
			}
			#simplecheckout_form_0 .content{
				font-size: 16px;
				font-weight: 400;
				margin-bottom: 30px;
				margin-top: 25px;
			}
			#simplecheckout_form_0 .button.btn-primary.button_oc.btn.oct-button{
				padding: 14px 40px;
			    display: flex;
			    width: max-content;
			    align-items: center;
			    justify-content: space-between;
			    font-weight: 700;
			    font-size: 16px;
			    line-height: 26px;
			    color: #FFFFFF;
			    background: var(--color-green);
			    border-radius: 12px;
			    cursor: pointer;
			    border: 0;
			    margin-right: auto;
			    margin-bottom: 60px;
			}
.select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle
}

.select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 28px;
    user-select: none;
    -webkit-user-select: none
}

.select2-container .select2-selection--single .select2-selection__rendered {
    display: block;
    padding-left: 8px;
    padding-right: 20px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap
}

.select2-container .select2-selection--single .select2-selection__clear {
    position: relative
}

.select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
    padding-right: 8px;
    padding-left: 20px
}

.select2-container .select2-selection--multiple {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    min-height: 32px;
    user-select: none;
    -webkit-user-select: none
}

.select2-container .select2-selection--multiple .select2-selection__rendered {
    display: inline-block;
    overflow: hidden;
    padding-left: 8px;
    text-overflow: ellipsis;
    white-space: nowrap
}

.select2-container .select2-search--inline {
    float: left
}

.select2-container .select2-search--inline .select2-search__field {
    box-sizing: border-box;
    border: none;
    font-size: 100%;
    margin-top: 5px;
    padding: 0
}

.select2-container .select2-search--inline .select2-search__field::-webkit-search-cancel-button {
    -webkit-appearance: none
}

.select2-dropdown {
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    box-sizing: border-box;
    display: block;
    position: absolute;
    left: -100000px;
    width: 100%;
    z-index: 1051
}

.select2-results {
    display: block
}

.select2-results__options {
    list-style: none;
    margin: 0;
    padding: 0
}

.select2-results__option {
    padding: 6px;
    user-select: none;
    -webkit-user-select: none
}

.select2-results__option[aria-selected] {
    cursor: pointer
}

.select2-container--open .select2-dropdown {
    left: 0
}

.select2-container--open .select2-dropdown--above {
    border-bottom: none;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0
}

.select2-container--open .select2-dropdown--below {
    border-top: none;
    border-top-left-radius: 0;
    border-top-right-radius: 0
}

.select2-search--dropdown {
    display: block;
    padding: 4px
}

.select2-search--dropdown .select2-search__field {
    padding: 4px;
    width: 100%;
    box-sizing: border-box
}

.select2-search--dropdown .select2-search__field::-webkit-search-cancel-button {
    -webkit-appearance: none
}

.select2-search--dropdown.select2-search--hide {
    display: none
}

.select2-close-mask {
    border: 0;
    margin: 0;
    padding: 0;
    display: block;
    position: fixed;
    left: 0;
    top: 0;
    min-height: 100%;
    min-width: 100%;
    height: auto;
    width: auto;
    opacity: 0;
    z-index: 99;
    background-color: #fff;
    filter: alpha(opacity=0)
}

.select2-hidden-accessible {
    border: 0 !important;
    clip: rect(0 0 0 0) !important;
    height: 1px !important;
    margin: -1px !important;
    overflow: hidden !important;
    padding: 0 !important;
    position: absolute !important;
    width: 1px !important
}

.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #aaa;
    border-radius: 4px
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px
}

.select2-container--default .select2-selection--single .select2-selection__clear {
    cursor: pointer;
    float: right;
    font-weight: bold
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #999
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 26px;
    position: absolute;
    top: 1px;
    right: 1px;
    width: 20px
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #888 transparent transparent transparent;
    border-style: solid;
    border-width: 5px 4px 0 4px;
    height: 0;
    left: 50%;
    margin-left: -4px;
    margin-top: -2px;
    position: absolute;
    top: 50%;
    width: 0
}

.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__clear {
    float: left
}

.select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
    left: 1px;
    right: auto
}

.select2-container--default.select2-container--disabled .select2-selection--single {
    background-color: #eee;
    cursor: default
}

.select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__clear {
    display: none
}

.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #888 transparent;
    border-width: 0 4px 5px 4px
}

.select2-container--default .select2-selection--multiple {
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: text
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    box-sizing: border-box;
    list-style: none;
    margin: 0;
    padding: 0 5px;
    width: 100%
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    list-style: none
}

.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #999;
    margin-top: 5px;
    float: left
}

.select2-container--default .select2-selection--multiple .select2-selection__clear {
    cursor: pointer;
    float: right;
    font-weight: bold;
    margin-top: 5px;
    margin-right: 10px
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #e4e4e4;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #999;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #333
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice,.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__placeholder,.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-search--inline {
    float: right
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
    margin-left: 5px;
    margin-right: auto
}

.select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
    margin-left: 2px;
    margin-right: auto
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border: solid black 1px;
    outline: 0
}

.select2-container--default.select2-container--disabled .select2-selection--multiple {
    background-color: #eee;
    cursor: default
}

.select2-container--default.select2-container--disabled .select2-selection__choice__remove {
    display: none
}

.select2-container--default.select2-container--open.select2-container--above .select2-selection--single,.select2-container--default.select2-container--open.select2-container--above .select2-selection--multiple {
    border-top-left-radius: 0;
    border-top-right-radius: 0
}

.select2-container--default.select2-container--open.select2-container--below .select2-selection--single,.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #aaa
}

.select2-container--default .select2-search--inline .select2-search__field {
    background: transparent;
    border: none;
    outline: 0;
    box-shadow: none;
    -webkit-appearance: textfield
}

.select2-container--default .select2-results>.select2-results__options {
    max-height: 200px;
    overflow-y: auto
}

.select2-container--default .select2-results__option[role=group] {
    padding: 0
}

.select2-container--default .select2-results__option[aria-disabled=true] {
    color: #999
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #ddd
}

.select2-container--default .select2-results__option .select2-results__option {
    padding-left: 1em
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__group {
    padding-left: 0
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -1em;
    padding-left: 2em
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -2em;
    padding-left: 3em
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -3em;
    padding-left: 4em
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -4em;
    padding-left: 5em
}

.select2-container--default .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -5em;
    padding-left: 6em
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #5897fb;
    color: white
}

.select2-container--default .select2-results__group {
    cursor: default;
    display: block;
    padding: 6px
}

.select2-container--classic .select2-selection--single {
    background-color: #f7f7f7;
    border: 1px solid #aaa;
    border-radius: 4px;
    outline: 0;
    background-image: -webkit-linear-gradient(top, #fff 50%, #eee 100%);
    background-image: -o-linear-gradient(top, #fff 50%, #eee 100%);
    background-image: linear-gradient(to bottom, #fff 50%, #eee 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF', endColorstr='#FFEEEEEE', GradientType=0)
}

.select2-container--classic .select2-selection--single:focus {
    border: 1px solid #5897fb
}

.select2-container--classic .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px
}

.select2-container--classic .select2-selection--single .select2-selection__clear {
    cursor: pointer;
    float: right;
    font-weight: bold;
    margin-right: 10px
}

.select2-container--classic .select2-selection--single .select2-selection__placeholder {
    color: #999
}

.select2-container--classic .select2-selection--single .select2-selection__arrow {
    background-color: #ddd;
    border: none;
    border-left: 1px solid #aaa;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    height: 26px;
    position: absolute;
    top: 1px;
    right: 1px;
    width: 20px;
    background-image: -webkit-linear-gradient(top, #eee 50%, #ccc 100%);
    background-image: -o-linear-gradient(top, #eee 50%, #ccc 100%);
    background-image: linear-gradient(to bottom, #eee 50%, #ccc 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE', endColorstr='#FFCCCCCC', GradientType=0)
}

.select2-container--classic .select2-selection--single .select2-selection__arrow b {
    border-color: #888 transparent transparent transparent;
    border-style: solid;
    border-width: 5px 4px 0 4px;
    height: 0;
    left: 50%;
    margin-left: -4px;
    margin-top: -2px;
    position: absolute;
    top: 50%;
    width: 0
}

.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__clear {
    float: left
}

.select2-container--classic[dir="rtl"] .select2-selection--single .select2-selection__arrow {
    border: none;
    border-right: 1px solid #aaa;
    border-radius: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    left: 1px;
    right: auto
}

.select2-container--classic.select2-container--open .select2-selection--single {
    border: 1px solid #5897fb
}

.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow {
    background: transparent;
    border: none
}

.select2-container--classic.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent #888 transparent;
    border-width: 0 4px 5px 4px
}

.select2-container--classic.select2-container--open.select2-container--above .select2-selection--single {
    border-top: none;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    background-image: -webkit-linear-gradient(top, #fff 0%, #eee 50%);
    background-image: -o-linear-gradient(top, #fff 0%, #eee 50%);
    background-image: linear-gradient(to bottom, #fff 0%, #eee 50%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFFFF', endColorstr='#FFEEEEEE', GradientType=0)
}

.select2-container--classic.select2-container--open.select2-container--below .select2-selection--single {
    border-bottom: none;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    background-image: -webkit-linear-gradient(top, #eee 50%, #fff 100%);
    background-image: -o-linear-gradient(top, #eee 50%, #fff 100%);
    background-image: linear-gradient(to bottom, #eee 50%, #fff 100%);
    background-repeat: repeat-x;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFEEEEEE', endColorstr='#FFFFFFFF', GradientType=0)
}

.select2-container--classic .select2-selection--multiple {
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: text;
    outline: 0
}

.select2-container--classic .select2-selection--multiple:focus {
    border: 1px solid #5897fb
}

.select2-container--classic .select2-selection--multiple .select2-selection__rendered {
    list-style: none;
    margin: 0;
    padding: 0 5px
}

.select2-container--classic .select2-selection--multiple .select2-selection__clear {
    display: none
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice {
    background-color: #e4e4e4;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove {
    color: #888;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    margin-right: 2px
}

.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #555
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
    float: right
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice {
    margin-left: 5px;
    margin-right: auto
}

.select2-container--classic[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
    margin-left: 2px;
    margin-right: auto
}

.select2-container--classic.select2-container--open .select2-selection--multiple {
    border: 1px solid #5897fb
}

.select2-container--classic.select2-container--open.select2-container--above .select2-selection--multiple {
    border-top: none;
    border-top-left-radius: 0;
    border-top-right-radius: 0
}

.select2-container--classic.select2-container--open.select2-container--below .select2-selection--multiple {
    border-bottom: none;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0
}

.select2-container--classic .select2-search--dropdown .select2-search__field {
    border: 1px solid #aaa;
    outline: 0
}

.select2-container--classic .select2-search--inline .select2-search__field {
    outline: 0;
    box-shadow: none
}

.select2-container--classic .select2-dropdown {
    background-color: #fff;
    border: 1px solid transparent
}

.select2-container--classic .select2-dropdown--above {
    border-bottom: none
}

.select2-container--classic .select2-dropdown--below {
    border-top: none
}

.select2-container--classic .select2-results>.select2-results__options {
    max-height: 200px;
    overflow-y: auto
}

.select2-container--classic .select2-results__option[role=group] {
    padding: 0
}

.select2-container--classic .select2-results__option[aria-disabled=true] {
    color: grey
}

.select2-container--classic .select2-results__option--highlighted[aria-selected] {
    background-color: #3875d7;
    color: #fff
}

.select2-container--classic .select2-results__group {
    cursor: default;
    display: block;
    padding: 6px
}

.select2-container--classic.select2-container--open .select2-dropdown {
    border-color: #5897fb
}

/*!
 * Select2 Bootstrap Theme v0.1.0-beta.10 (https://select2.github.io/select2-bootstrap-theme)
 * Copyright 2015-2017 Florian Kissling and contributors (https://github.com/select2/select2-bootstrap-theme/graphs/contributors)
 * Licensed under MIT (https://github.com/select2/select2-bootstrap-theme/blob/master/LICENSE)
 */
.select2-container--bootstrap {
    display: block
}

.select2-container--bootstrap .select2-selection {
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #555;
    font-size: 14px;
    outline: 0
}

.select2-container--bootstrap .select2-selection.form-control {
    border-radius: 4px
}

.select2-container--bootstrap .select2-search--dropdown .select2-search__field {
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #555;
    font-size: 14px
}

.select2-container--bootstrap .select2-search__field {
    outline: 0
}

.select2-container--bootstrap .select2-search__field::-webkit-input-placeholder {
    color: #999
}

.select2-container--bootstrap .select2-search__field:-moz-placeholder {
    color: #999
}

.select2-container--bootstrap .select2-search__field::-moz-placeholder {
    color: #999;
    opacity: 1
}

.select2-container--bootstrap .select2-search__field:-ms-input-placeholder {
    color: #999
}

.select2-container--bootstrap .select2-results__option {
    padding: 6px 12px
}

.select2-container--bootstrap .select2-results__option[role=group] {
    padding: 0
}

.select2-container--bootstrap .select2-results__option[aria-disabled=true] {
    color: #777;
    cursor: not-allowed
}

.select2-container--bootstrap .select2-results__option[aria-selected=true] {
    background-color: #f5f5f5;
    color: #262626
}

.select2-container--bootstrap .select2-results__option--highlighted[aria-selected] {
    background-color: #337ab7;
    color: #fff
}

.select2-container--bootstrap .select2-results__option .select2-results__option {
    padding: 6px 12px
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__group {
    padding-left: 0
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -12px;
    padding-left: 24px
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -24px;
    padding-left: 36px
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -36px;
    padding-left: 48px
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -48px;
    padding-left: 60px
}

.select2-container--bootstrap .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option .select2-results__option {
    margin-left: -60px;
    padding-left: 72px
}

.select2-container--bootstrap .select2-results__group {
    color: #777;
    display: block;
    padding: 6px 12px;
    font-size: 12px;
    line-height: 1.42857143;
    white-space: nowrap
}

.select2-container--bootstrap.select2-container--focus .select2-selection,.select2-container--bootstrap.select2-container--open .select2-selection {
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    border-color: #66afe9
}

.select2-container--bootstrap.select2-container--open .select2-selection .select2-selection__arrow b {
    border-color: transparent transparent #999;
    border-width: 0 4px 4px
}

.select2-container--bootstrap.select2-container--open.select2-container--below .select2-selection {
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    border-bottom-color: transparent
}

.select2-container--bootstrap.select2-container--open.select2-container--above .select2-selection {
    border-top-right-radius: 0;
    border-top-left-radius: 0;
    border-top-color: transparent
}

.select2-container--bootstrap .select2-selection__clear {
    color: #999;
    cursor: pointer;
    float: right;
    font-weight: 700;
    margin-right: 10px
}

.select2-container--bootstrap .select2-selection__clear:hover {
    color: #333
}

.select2-container--bootstrap.select2-container--disabled .select2-selection {
    border-color: #ccc;
    -webkit-box-shadow: none;
    box-shadow: none
}

.select2-container--bootstrap.select2-container--disabled .select2-search__field,.select2-container--bootstrap.select2-container--disabled .select2-selection {
    cursor: not-allowed
}

.select2-container--bootstrap.select2-container--disabled .select2-selection,.select2-container--bootstrap.select2-container--disabled .select2-selection--multiple .select2-selection__choice {
    background-color: #eee
}

.select2-container--bootstrap.select2-container--disabled .select2-selection--multiple .select2-selection__choice__remove,.select2-container--bootstrap.select2-container--disabled .select2-selection__clear {
    display: none
}

.select2-container--bootstrap .select2-dropdown {
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    border-color: #66afe9;
    overflow-x: hidden;
    margin-top: -1px
}

.select2-container--bootstrap .select2-dropdown--above {
    -webkit-box-shadow: 0 -6px 12px rgba(0,0,0,.175);
    box-shadow: 0 -6px 12px rgba(0,0,0,.175);
    margin-top: 1px
}

.select2-container--bootstrap .select2-results>.select2-results__options {
    max-height: 200px;
    overflow-y: auto
}

.select2-container--bootstrap .select2-selection--single {
    height: 34px;
    line-height: 1.42857143;
    padding: 6px 24px 6px 12px
}

.select2-container--bootstrap .select2-selection--single .select2-selection__arrow {
    position: absolute;
    bottom: 0;
    right: 12px;
    top: 0;
    width: 4px
}

.select2-container--bootstrap .select2-selection--single .select2-selection__arrow b {
    border-color: #999 transparent transparent;
    border-style: solid;
    border-width: 4px 4px 0;
    height: 0;
    left: 0;
    margin-left: -4px;
    margin-top: -2px;
    position: absolute;
    top: 50%;
    width: 0
}

.select2-container--bootstrap .select2-selection--single .select2-selection__rendered {
    color: #555;
    padding: 0
}

.select2-container--bootstrap .select2-selection--single .select2-selection__placeholder {
    color: #999
}

.select2-container--bootstrap .select2-selection--multiple {
    min-height: 34px;
    padding: 0;
    height: auto
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__rendered {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    display: block;
    line-height: 1.42857143;
    list-style: none;
    margin: 0;
    overflow: hidden;
    padding: 0;
    width: 100%;
    text-overflow: ellipsis;
    white-space: nowrap
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__placeholder {
    color: #999;
    float: left;
    margin-top: 5px
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
    color: #555;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: default;
    float: left;
    margin: 5px 0 0 6px;
    padding: 0 6px
}

.select2-container--bootstrap .select2-selection--multiple .select2-search--inline .select2-search__field {
    background: 0 0;
    padding: 0 12px;
    height: 32px;
    line-height: 1.42857143;
    margin-top: 0;
    min-width: 5em
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
    color: #999;
    cursor: pointer;
    display: inline-block;
    font-weight: 700;
    margin-right: 3px
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #333
}

.select2-container--bootstrap .select2-selection--multiple .select2-selection__clear {
    margin-top: 6px
}
			#menu .parent-title-toggle, .m-panel-box, .megamenu-toggle-a, .mob-menu-ul, .mobile-container, .mobile-icons-box {
			    display: none !important;
			}

			.phones-top-box .phones-dropdown .dropdown-menu{
				width: 280px;
			}
			.buttons-top-box div a, 
			#menu-mobile,.mobile-category-header {
			    display: none
			}


			.header-simple {
		    margin-bottom: 30px;
			}
			#oct-bluring-box {
		    background: white;
			}
			.header-simple .wrap {
		    display: flex;
		    align-items: center;
		    padding: 15px 20px;
		    border-bottom: 2px solid #1faa93;
			}
			.header-simple .header-order__contact {
		    display: flex;
		    margin-left: auto;
		    align-items: center;
			}
			.header-simple .header-order__contact .header-order__contact-title {
		    font-weight: 500;
		    font-size: 14px;
		    margin-right: 30px;
			}

			.breadcrumb-box{
			opacity: 0;
			height: 0;
			padding: 0;
			}

			@media screen and (max-width: 992px) {
			.header-simple {
			margin-bottom: 10px;
			}
		    .header-simple .header__col-logo {
			margin-bottom: 0;
		    }
		    .header-simple .header-order__contact .header-order__contact-title{
			margin-right: 0
		    }
		    .header-simple .header-order__contact {
			flex-direction: column;
			align-items: flex-end;
		    }
		    .header-simple .header-order__contact > div {
			text-align: right;
		    }

		    header {
			padding-top: 0;
			box-shadow: none;
			}
			}
			@media screen and (max-width: 767px) {

		    .header-simple .header-order__contact-title {
			display: none;
		    }
			}
			@media screen and (max-width: 500px) {
		    .header-simple .header-order__contact-title {
			display: none;
		    }
			
		    .header-simple .header__col-logo {
			margin-bottom: 0;
		    }
		    .header-simple .header-order__contact .header-order__contact-tel {
			margin-left: 0;
		    }
		    .header-simple .wrap {
			flex-direction: row;
			align-items: center;
			justify-content: space-between;
			}
		    .header-simple .header-order__contact {
			align-items: center;
			width: auto;
			}
		    header {
			padding-top: 0 !important;
			}
			.header-simple .header__col-logo img{
			max-width: 100px;
			}
			.content-row{
			box-shadow: none !important;
			margin-top: 0 !important;
			}
			.header-simple {
			margin-bottom: 0;
			padding-bottom: 0;
			}
			
			}
			@media screen and (max-width: 992px) {
				.header-simple .header-order__contact > div .phones-dropdown.open .dropdown-menu{
				    gap: 15px;
				    display: flex;
				    flex-direction: column;
				    /*position: fixed;
				    left: 0;
				    top: 20%;
				    margin: auto;
				    right: 0;*/
				}
				.header-simple .header-order__contact > div .phones-dropdown .dropdown-backdrop{
					position: fixed;
					left: 0;
					top: 0;
					height: 100%;
					width: 100%;
					background: #00000061;
					z-index: 1;
				}
			}
		</style>
	</head>
	<body class="<?php echo $class; ?>">
		<div id="menu-mobile" class="m-panel-box">
			<div class="menu-mobile-header">
				<?php echo $oct_techstore_mmenu; ?>
				<?php
					$logo_mobile = 'image/catalog/newlogoru.png';
				?>
				<a class="mobile-menu-logo" href="<?php echo $home; ?>"><img src="<?php echo $logo_mobile; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
			</div>
			<div id="menu-mobile-box"></div>
			<div class="close-m-search">
				<a class="oct-button closempanel">×</a>
			</div>
		</div>
		
		<?php if ($oct_techstore_customcss) { ?>
			<style>
				<?php echo $oct_techstore_customcss; ?>
			</style>
		<?php } ?>
		
		<div id="info-mobile" class="m-panel-box">
			<div class="menu-mobile-header"><?php echo $oct_techstore_minfo; ?></div>
			<div class="close-m-search">
				<a class="oct-button closempanel">×</a>
			</div>
			<div id="info-mobile-box"></div>
		</div>
		<div class="oct-m-search m-panel-box" id="msrch">
			<div class="menu-mobile-header"><?php echo $oct_techstore_msearch; ?></div>
			<div id="oct-mobile-search-box">
				<div id="oct-mobile-search">
					<div class="input-group">
						<input type="text" id="mobilesearch" name="search" class="form-control" placeholder="<?php echo $oct_techstore_msearch; ?>">
						<span class="input-group-btn">
							<input type="button" id="oct-m-search-button" value="<?php echo $oct_techstore_msearchb; ?>" class="oct-button">
						</span>
					</div>
					<div class="oct-msearchresults" id="searchm">
						<div id="msearchresults"></div>
					</div>
				</div>
				<p>&nbsp;</p>
			</div>
			<div class="close-m-search">
				<a class="oct-button closempanel">×</a>
			</div>
		</div>
		
		<div id="oct-bluring-box">
			<header class="header-simple">
				<div class="container">
					<div class="wrap">
						<div class="header__col-logo">
							<div class="header__logo logo">
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
						</div>
						<div class="header-order__contact">
							<div class="header-order__contact-title">
								<?php echo $text_if_you_have_questions_call; ?>
							</div>
							<?php if ($oct_techstore_cont_phones) { ?>
								<!-- PHONE END-->
									<div class="phones-top-box">
										<?php if ($oct_techstore_cont_phones) { ?>
											<div class="phones-dropdown pr">
												<?php if (count($oct_techstore_cont_phones) >= 1) { ?>
													<a href="#" class="dropdown-toggle df aic" data-toggle="dropdown" aria-expanded="false" data-hover="dropdown" role="button">
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
							<?php } ?>
						</div>
					</div>
				</div>
			</header>																																	