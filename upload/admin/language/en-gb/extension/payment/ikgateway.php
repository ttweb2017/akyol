<?php
/* Создано в компании www.ttweb.com
 * =================================================================
 * Ecomerce модуль OPENCART 2.3.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 2.3.x
 *  данный продукт не поддерживает программное обеспечение для других
 *  версий Opencart.
 * =================================================================
*/

$_['heading_title']               = 'Altyn Asyr Payment 1.0';

// Tab 
$_['tab_log']                     = 'Log';
$_['tab_general']                 = 'General';

// Text 
$_['text_payment']                = 'Payment';
$_['text_success']                = 'Module setting is updated!';
$_['text_ikgateway']              = '<a onclick="window.open(\'https://mpi.gov.tm/payment/merchants/online/payment_ru.html\');"><img src="view/image/payment/ecommerce.png" alt="AltynAsyr" title="AltynAsyr"/></a>';
$_['text_order_status_cart']      = 'Cart';
$_['text_ik_log_off']             = 'Disabled';
$_['text_ik_log_short']           = 'Partially (Only result of process)';
$_['text_ik_log_full']            = 'full (All requests)';
$_['text_edit']                   = 'Edit';
$_['text_enabled']                = 'Enabled';
$_['text_disabled']               = 'Disabled';
$_['text_yes']                    = 'Yes';
$_['text_no']                     = 'No';

$_['text_ik_parameters']          = 'Parameters for setting up Altyn Asyr payment';


// Entry
$_['entry_ik_log']                = 'Log:';
$_['entry_ik_log_help']           = 'Log request is saved in file:  system/logs/shoputils_ik.txt';
$_['entry_ik_shop_id']            = 'Altyn Asyr Login:';
$_['entry_ik_shop_id_help']       = '«ALTYNASYR» Login should be obtained from Halkbank. This is you online payment login!';
$_['entry_ik_sign_hash']          = 'Altyn Asyr Password:';
$_['entry_ik_sign_hash_help']     = '«ALTYNASYR» Password should be obtained from Halkbank. This is you online payment login!';

$_['entry_ik_currency']           = 'Currency of store:';
$_['entry_ik_currency_help']      = 'Currency that this payment accepts. Only Manat will be accepted';
$_['entry_log_file']              = 'Log file:';
$_['entry_log_file_help']         = 'Last %d line from log file.';
$_['entry_status']                = 'Status:';
$_['entry_order_status']          = 'Status after payment:';
$_['entry_geo_zone']              = 'Geo zone:';
$_['entry_sort_order']            = 'Sort order:';

$_['ikgateway_counter_text']      = 'Counter';
$_['ikgateway_counter_help']      = 'This is counter to change orderId. This is usefull when you have more than one store Example: your orderId is 5 --> counter will be added to it, then will registered in altyn asyr payment (5 + counter)';


// Error
$_['error_permission']            = 'You do not have permission to edit this module!';
$_['error_ik_shop_id']            = 'Please fill out the "Altyn Asyr Login" field!';
$_['error_ik_sign_hash']          = 'Please fill out the "Altyn Asyr Password" field!';

?>