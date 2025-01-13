<?php

$_['heading_title']    			= "Payment Fee or Discount";
$_['heading_version']   		= "2.6.0";

$_['text_success']    			= 'Success: You have modified Payment Fee or Discount!';

/* Tabs */

$_['tab_general']   			= "General";

$_['tab_payments']   			= "Payments";
$_['help_payments']   			= "Minimum - minimum subtotal value to activate fee or discount; Maximum - maximum subtotal value, when fee or discount stays active; Value - percent or fixed value (e.g.: 3%, -5%, 10, -20).";

/* General */

$_['entry_round']     			= "Round By";
$_['help_round']         		= "Fee or Discount will be rounded to given value (e.g: if set to 10 - 271.52 will be rounded to 280).";

$_['entry_add_name']     		= "Add Name to Title";
$_['help_add_name']   			= "Selected payment method's name will be added to title.";

$_['entry_add_value']     		= "Add Value to Title";
$_['help_add_value']   			= "Selected payment method's fee or discount value will be added to title.";

$_['entry_hide_total']     		= "Hide Total";
$_['help_hide_total']     		= "Hides Payment Fee or Discount total from the list and adds its value to selected total.";

$_['entry_add_to']				= "Total to Add";
$_['help_add_to']				= "When Hide Total is enabled, fee or discount value will be added to selected total.";

$_['entry_add_info']     		= "Add Info to Total";
$_['help_add_info']   			= "Additional information about fee or discount will be added to selected total's title.";

$_['entry_inactive_fees'] 		= "Fee Inactive With";
$_['help_inactive_fees']  		= "Fee will not be applied, if any of the selected totals are active in current order.";

$_['entry_inactive_discounts'] 	= "Discount Inactive With";
$_['help_inactive_discounts']  	= "Discount will not be applied, if any of the selected totals are active in current order.";

/* Payments */

$_['entry_fees']   				= "Fees";
$_['help_fees']   				= "List of fees for installed payment methods. You can add unlimited number of items.";

$_['entry_discounts']   		= "Discounts";
$_['help_discounts']   			= "List of discounts for installed payment methods. You can add unlimited number of items.";

$_['entry_payment']   			= "-- Select payment --";
$_['entry_minimum']   			= "Minimum";
$_['entry_maximum']   			= "Maximum";
$_['entry_value']   			= "Value";

$_['text_fees']   				= "Fee";
$_['text_discounts']   			= "Discount";

/* Buttons */

$_['button_item_add'] 			= "Add item";
$_['button_item_remove'] 		= "Remove item";

/* Errors */

$_['error_payments'] 			= "Error: No payment methods installed.";
$_['error_value'] 				= "Error: %s value for %s should be numerical or empty.";

/* Generic language strings */

$_['heading_latest']   			= "You have the latest version: %s";
$_['heading_future']   			= "Wow! You have version %s and it's from THE FUTURE!";
$_['heading_update']   			= "A new version available: %s. Click <a href='http://thekrotek.com/profile/my-orders' title='Download new version' target='_blank'>here</a> to download.";

$_['entry_version']				= "Check Version";
$_['help_version']				= "Disable, if settings page loads too slow or connection errors displayed.";

$_['entry_customer_groups'] 	= "Customer Groups";
$_['help_customer_groups'] 	 	= "Extension will work for selected groups only (empty - all groups and guests).";

$_['entry_geo_zone']   			= "Geo Zone";
$_['help_geo_zone']   			= "Extension will work for selected geo zone only.";

$_['entry_tax_class']  			= "Tax Class";
$_['help_tax_class']   			= "Tax class, which will be applied for this extension";

$_['entry_status']     			= "Status";
$_['help_status']   			= "Enable or disable this extension";

$_['entry_sort_order'] 			= "Sort Order";
$_['help_sort_order']   		= "Position in the list of extensions of the same type.";

$_['text_edit_title']       	= "Edit %s";
$_['text_remove_all']       	= "Remove all";
$_['text_none']   	    		= "--- None ---";

$_['text_extension']	 		= "Extensions";
$_['text_total']    			= "Total";
$_['text_module']   	 		= "Modules";
$_['text_shipping']    			= "Shipping";
$_['text_payment']    			= "Payment";
$_['text_feed']           	  	= 'Feeds';

$_['button_apply']     		 	= "Apply";
$_['button_help']      			= "Help";

$_['text_content_top']    		= "Content Top";
$_['text_content_bottom'] 		= "Content Bottom";
$_['text_column_left']    		= "Column Left";
$_['text_column_right']   		= "Column Right";

$_['entry_module_layout']   	= "Layout:";
$_['entry_module_position'] 	= "Position:";
$_['entry_module_status']   	= "Status:";
$_['entry_module_sort']    		= "Sort Order:";

$_['message_success']     		= "Success: You have modified %s!";

$_['error_permission']	 		= "Warning: You do not have permission to modify %s!";
$_['error_version'] 			= "Impossible to get version information: no connection to server.";
$_['error_disabled'] 			= "Impossible to get version information: Version check is disabled.";
$_['error_fopen'] 				= "Impossible to get version information: allow_url_fopen option is disabled.";
$_['error_empty'] 				= "Error: %s value can't be empty.";
$_['error_numerical'] 			= "Error: %s value should be numerical.";
$_['error_percent'] 			= "Error: %s value should be numerical or in percent.";
$_['error_positive'] 			= "Error: %s value should be zero or more.";
$_['error_date'] 				= "Error: %s has wrong date format.";
$_['error_curl']      			= "cURL error: (%s) %s. Fix it (if necessary) and try to reinstall.";

?>