<?php
// Heading
$_['heading_title']          = 'After Purchase Review Invitation';

// Text
$_['text_extension']         = 'Extensions';
$_['text_success']           = 'Success: You have modified module after purchase review invitation!';
$_['text_edit']        		 = 'Edit After Purchase Review Invitation';

// Tabs
$_['tab_general']            = 'General'; 
$_['tab_mail']               = 'Mail'; 

// Entry
$_['entry_cron_password']    = 'Cron password';
$_['entry_start_date']       = 'Start Date';
$_['entry_days_after']       = 'Days after';
$_['entry_allowed_statuses'] = 'Allowed Order Status';
$_['entry_allow_unsubscribe']= 'Allow unsubscribe?';
$_['entry_log_to_admin']     = 'Send Log Summary to Admin';
$_['entry_use_html_email']   = 'Send Review Invitation with <a href="http://www.oc-extensions.com/HTML-Email">HTML Email Extension</a>';
$_['entry_mail_subject']     = 'Mail subject';
$_['entry_mail_message']     = 'Mail message';
$_['entry_mail_log_subject'] = 'Admin Summnary Mail - subject';
$_['entry_mail_log_message'] = 'Admin Summnary Mail - message';

// Help
$_['help_customized']        = 'For customized mail subject / message you can use {firstname} {lastname} {store_name} {store_email} {store_telephone} {purchased_products_table} {unsubscribe_link}<br />Check email_example.txt from zip archive purchased from oc-extensions.com';
$_['help_start_date']        = '(Optional) send review mail invitation ONLY for orders added after Start Date.';
$_['help_days_after']        = '(Optional) send invitation only for orders older than x days';
$_['help_allowed_statuses']  = 'send invitation for customers that have orders wit specified status<br />Recomended: Complete';
$_['help_allow_unsubscribe'] = 'if option is enabled and customer unsubscribe, then extension can\'t send anymore review invitations to that customer';
$_['help_html_email']        = 'If HTML Email Extension is not installed on your store then is used default html mail (like in old versions of this extensions)';
$_['help_mail_log_message']  = '{recipients_list} - list of customers that received email';


// Error
$_['error_permission']       = 'Warning: You do not have permission to modify module after purchase review invitation!';
$_['error_in_tab']           = 'Please check again. We found errors in tab %s';
$_['error_cron_password']    = 'Error: Cron password - required!';
$_['error_allowed_statuses'] = 'Error: Allowed Statuses - required!';
$_['error_mail_subject']     = 'Error: Mail subject is required!';
$_['error_mail_message']     = 'Error: Mail message is required (at least 15 characters)!';
$_['error_mail_message_unsubscribe'] = 'Remove UNSUBSCRIBE LINK from mail message when \'Allow unsubscribe\' option is Disabled';
$_['error_mail_log_subject'] = 'Error: Mail Log subject is required!';
$_['error_mail_log_message'] = 'Error: Mail Log message is required (at least 15 characters)!';
$_['error_html_email_not_installed'] = 'Review Invitation Emails can\'t be sent with <a href="http://www.oc-extensions.com/HTML-Email">HTML Email Extension</a> because this extension is not available on your store. Please set option to Disabled!';

?>