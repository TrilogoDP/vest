<?php

$_['heading_title']    			= "Комиссия или скидка для способов оплаты";
$_['heading_version']   		= "2.6.0";

$_['text_success']    			= 'Успешно: Вы изменили модуль Комиссия или скидка для способов оплаты!';

/* Tabs */

$_['tab_general']   			= "Настройки";

$_['tab_payments']   			= "Способы оплаты";
$_['help_payments']   			= "Минимум - минимальное значение для активации комисии или скидки; Максимум - максимальное значение, когда комиссия или скидка остаются активными; Значение - процент или фиксированное значение (например: 3%, -5%, 10, -20).";

/* General */

$_['entry_round']     			= "Округлить к";
$_['help_round']         		= "Комиссия или Скидка будут округлены до заданного значения (например, если установленное значение 10, то при сумме 271,52 будет округлено до 280).";

$_['entry_add_name']     		= "Добавить название оплаты в заголовок комиссии или скидки";
$_['help_add_name']   			= "Название выбранного способа оплаты будет добавлено в название скидки или комиссии в информации о заказе.";

$_['entry_add_value']     		= "Добавить значение комиссии или скидки в ее заголовок";
$_['help_add_value']   			= "Значение скидки или комиссии будут добавлены в название в информации о заказе.";

$_['entry_hide_total']     		= "Скрыть сумму налога или скидки";
$_['help_hide_total']     		= "Скрывает сумму комисии или скидка из списка и добавляет его значение к общему итогу.";

$_['entry_add_to']				= "Добавить сумму налога или скидки к";
$_['help_add_to']				= "Когда сумма скрыта, налог или скидка будут добавлены к сумме выбраного значения.";

$_['entry_add_info']     		= "Добавить информацию в название";
$_['help_add_info']   			= "Дополнительная информация о комиссии или скидке будет добавлена к названию выбранной суммы.";

$_['entry_inactive_fees'] 		= "Комиссия не применяется с";
$_['help_inactive_fees']  		= "Комиссия не применяется, если какой-либо из выбранных итогов активен в текущем заказе.";

$_['entry_inactive_discounts'] 	= "Скидка не применяется с";
$_['help_inactive_discounts']  	= "Скидка не применяется, если какой-либо из выбранных итогов активен в текущем заказе.";

/* Payments */

$_['entry_fees']   				= "Комиссии";
$_['help_fees']   				= "Список комиссий для способов оплаты. Вы можете добавить неограниченное количество элементов.";

$_['entry_discounts']   		= "Скидки";
$_['help_discounts']   			= "Список скидок для способов оплаты. Вы можете добавить неограниченное количество элементов.";

$_['entry_payment']   			= "-- Выберите способ --";
$_['entry_minimum']   			= "Минимум";
$_['entry_maximum']   			= "Максимум";
$_['entry_value']   			= "Значение";

$_['text_fees']   				= "Комиссия";
$_['text_discounts']   			= "Скидка";

/* Buttons */

$_['button_item_add'] 			= "Добавить";
$_['button_item_remove'] 		= "Удалить";

/* Errors */

$_['error_payments'] 			= "Ошибка: Нет включенных способов оплаты.";
$_['error_value'] 				= "Ошибка: %s значение %s должно быть числом либо оставьте пустым.";

/* Generic language strings */

$_['heading_latest']   			= "У вас последняя версия: %s";
$_['heading_future']   			= "Wow! You have version %s and it's from THE FUTURE!";
$_['heading_update']   			= "A new version available: %s.";

$_['entry_version']				= "Check Version";
$_['help_version']				= "Disable, if settings page loads too slow or connection errors displayed.";

$_['entry_customer_groups'] 	= "Группы клиентов";
$_['help_customer_groups'] 	 	= "Модуль будет работать только для выбранных групп (если пусто - все группы и гости).";

$_['entry_geo_zone']   			= "Геозоны";
$_['help_geo_zone']   			= "Модуль будет работать только для выбранных зон";

$_['entry_tax_class']  			= "Налог";
$_['help_tax_class']   			= "Налоговый класс, который будет применяться для модуля.";

$_['entry_status']     			= "Статус";
$_['help_status']   			= "Включить или отключить модуль";

$_['entry_sort_order'] 			= "Порядок";
$_['help_sort_order']   		= "Позиция в списке модулей этого типа.";

$_['text_edit_title']       	= "Редактирование %s";
$_['text_remove_all']       	= "Удалить все";
$_['text_none']   	    		= "--- Нет ---";

$_['text_extension']	 		= "Дополнения";
$_['text_total']    			= "Заказ";
$_['text_module']   	 		= "Модули";
$_['text_shipping']    			= "Доставка";
$_['text_payment']    			= "Оплата";
$_['text_feed']           	  	= 'Продвижение';

$_['button_apply']     		 	= "Применить";
$_['button_help']      			= "Помощь";

$_['text_content_top']    		= "Верх страницы";
$_['text_content_bottom'] 		= "Низ страницы";
$_['text_column_left']    		= "Левая колонка";
$_['text_column_right']   		= "Правая колонка";

$_['entry_module_layout']   	= "Макет:";
$_['entry_module_position'] 	= "Позиция:";
$_['entry_module_status']   	= "Статус:";
$_['entry_module_sort']    		= "Порядок:";

$_['message_success']     		= "Успешно: Вы изменили модуль %s!";

$_['error_permission']	 		= "Внимание: У вас нет прав редактировать %s!";
$_['error_version'] 			= "Impossible to get version information: no connection to server.";
$_['error_disabled'] 			= "Impossible to get version information: Version check is disabled.";
$_['error_fopen'] 				= "Impossible to get version information: allow_url_fopen option is disabled.";
$_['error_empty'] 				= "Ошибка: %s значение не должно быть пустым.";
$_['error_numerical'] 			= "Ошибка: %s значение должно быть числом.";
$_['error_percent'] 			= "Ошибка: %s значение должно быть числом или процентом.";
$_['error_positive'] 			= "Ошибка: %s значение должно быть 0 или больше.";
$_['error_date'] 				= "Ошибка: %s неправильный формат даты.";
$_['error_curl']      			= "cURL error: (%s) %s. Fix it (if necessary) and try to reinstall.";

?>