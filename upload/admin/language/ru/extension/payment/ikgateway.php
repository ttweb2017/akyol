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

$_['heading_title']               = 'Оплата Алтын Асыр 1.0';

// Tab 
$_['tab_log']                     = 'Журнал';
$_['tab_general']                 = 'Обший';

// Text 
$_['text_payment']                = 'Оплата';
$_['text_success']                = 'Настройки модуля обновлены!';
$_['text_ikgateway']              = '<a onclick="window.open(\'https://mpi.gov.tm/payment/merchants/online/payment_ru.html\');"><img src="view/image/payment/ecommerce.png" alt="AltynAsyr" title="AltynAsyr"/></a>';
$_['text_order_status_cart']      = 'Корзина';
$_['text_ik_log_off']             = 'Выключен';
$_['text_ik_log_short']           = 'Частичный (Только результары операций)';
$_['text_ik_log_full']            = 'Полный (Все запросы)';
$_['text_edit']                   = 'Редактирование';
$_['text_enabled']                = 'Включить';
$_['text_disabled']               = 'Выключить';
$_['text_yes']                    = 'Да';
$_['text_no']                     = 'Нет';

$_['text_ik_parameters']          = 'Параметры для настройки приема платежей через Алтын Асыр';


// Entry
$_['entry_ik_log']                = 'Журнал:';
$_['entry_ik_log_help']           = 'Журнал запросов от Интеркассы сохраняется в файле: system/logs/shoputils_ik.txt';
$_['entry_ik_shop_id']            = 'Алтын Асыр Логин:';
$_['entry_ik_shop_id_help']       = '«АЛТЫНАСЫР» Логин нужно получат в Халкбанке. Это ваш логин для онлайн оплаты!';
$_['entry_ik_sign_hash']          = 'Алтын Асыр Парол:';
$_['entry_ik_sign_hash_help']     = '«АЛТЫНАСЫР» Парол нужно получат в Халкбанке. Это ваш логин для онлайн оплаты!';

$_['entry_ik_currency']           = 'Валюта магазина:';
$_['entry_ik_currency_help']      = 'Валюта, в которой магазин передает сумму платежа на платежный шлюз Алтын Асыр';
$_['entry_log_file']              = 'Файл Журнала:';
$_['entry_log_file_help']         = 'Последние %d строк из файла журнала.';
$_['entry_status']                = 'Статус:';
$_['entry_order_status']          = 'Статус заказа после оплаты:';
$_['entry_geo_zone']              = 'Географическая зона:';
$_['entry_sort_order']            = 'Порядок сортировки:';

$_['ikgateway_counter_text']      = 'Счетчик';
$_['ikgateway_counter_help']      = 'Это счетчик помогает поменят заказ номер в банке. Полезно использыват когда у вас есть магазина болше одного! Пример: заказ номер + счетчик (5 + 1)';


// Error
$_['error_permission']            = 'У Вас нет прав для управления этим модулем!';
$_['error_ik_shop_id']            = 'Необходимо заполнить поле "Алтын Асыр Логин"!';
$_['error_ik_sign_hash']          = 'Необходимо заполнить поле "Алтын Асыр Парол"!';

?>