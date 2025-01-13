<?php

$extension_name = "Auto generator google dynamic and standard remarketing";

// Heading
$_['heading_title']    = $extension_name.' (V.7.1.0.0)';

//Generals
$_['text_module']  			= 'Modules';
$_['button_save']      		= 'Save';
$_['button_cancel']      	= 'Cancel';
$_['status']				= 'Status';
$_['active']				= 'Enabled';
$_['disabled']				= 'Disabled';
$_['choose_store']			= 'Choose store';
$_['text_success']			= 'Success: You have modified module '.$extension_name.'!';
$_['error_permission']    	= 'Warning: You do not have permission to modify module '.$extension_name.'!';
$_['apply_changes'] 		= 'Apply changes';
$_['text_image_manager']     = 'Image Manager';
$_['text_browse']            = 'Browse';
$_['text_clear']             = 'Clear';
$_['type']					= 'Type';
$_['code']					= 'Code';
$_['code_head']					= 'Code head';
$_['code_body']					= 'Code body';
$_['configuration']			= 'Configuration';
$_['additional_information']= 'Additional Information';
$_['text_none'] = ' - None -';

//Tabs
  	$_['tab_help']     = 'Support';
  	$_['tab_changelog']     = 'Changelog';	

//Tab Analytics
  	$_['tab_analytics']     = 'Analytics - Tag manager';
  	$_['analytics_help']	= 'Copy your Google analytics or Tag Manager code and paste here. This code will be placed just before close head tag.';
  	$_['analytics_help_body']	= 'If you use tag manager is possible that you need also a code just after open body tag, paste this code here.';
  	$_['analytics_ai_analytics']	= '<b>Google Analytics:</b> Google Analytics is a freemium web analytics service offered by Google that tracks and reports website traffic. Google launched the service in November 2005 after acquiring Urchin. Google Analytics is now the most widely used web analytics service on the Internet. <a target="_new" href="http://www.google.es/intl/en/analytics/standard/">More information</a>';
  	$_['analytics_ai_tag_manager']	= '<b>Google Tag Manager:</b> Google Tag Manager gives you the power to create and update tags for your website and mobile apps, any time you want, at the speed of your business. <a target="_new" href="https://www.google.com/tagmanager/">More information</a>';
//END Tab Analytics

//Tab Dynamic & standard remarketing
	$_['tab_remarketing']     = 'Dynamic and Standard Remarketing';
	$_['remarketing_dynamic']     = 'Dynamic';
	$_['remarketing_standard']     = 'Standard';
	$_['google_remarketing_code_help'] = 'Get your remarketing script from Tag <b>Details -> Setup</b> and place here.';
	$_['remarketing_id_preffix'] = 'Product ID prefix';

	$_['remarketing_id_preffix_help'] = '<b>NO REQUIRED</b><br><br>Google Merchant locales: <b>US UK AU DE FR NL IT ES</b><br><br>Example: <b>ecomm_prodid: es_59,</b>';
	$_['remarketing_id_suffix'] = 'Product ID sufix';
	$_['remarketing_id_suffix_help'] = '<b>NO REQUIRED</b><br><br>Google Merchant locales: <b>US UK AU DE FR NL IT ES</b><br><br>Example: <b>ecomm_prodid: 59_es,</b>';
	$_['remarketing_ai_general'] = 'Remarketing allows you to show ads to your past site visitors and customize those ads based on the section of your site people visited. With dynamic remarketing, you can take this a step further, and show your site visitors an ad with the specific product they viewed on your site. <a href="https://support.google.com/adwords/answer/3124536?hl=en" target="_new">More information</a>';
	$_['remarketing_ai_other_sites'] = '<b>Other sites compatibility:</b> If you code has <b>dynx_</b> params. These parameters are intended for websites that do not fit into one of the other vertical-specific areas on this page, but still want to provide information for dynamic remarketing.';
//END Tab Dynamic & standard remarketing


//Debug mode
  	$_['google_debug_legend'] = 'Debug mode';
	$_['general_configuration_legend'] = 'GMT Debug mode';
	$_['google_debug_mode_google_remarketing'] = 'Dynamic and standard remarketing';
  	$_['google_debug_mode_help'] = 'The process will be saved in <b>system/logs/gmt.log</b>. Enable it to know if is working.';
//END Debug mode
?>