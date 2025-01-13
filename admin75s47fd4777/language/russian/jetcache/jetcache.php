<?php
$_['jetcache_version'] = '11.8';
$_['jetcache_model'] = 'Jet Cache';
$_['jetcache_model_code'] = 'jetcache';
$_['order_jetcache'] = '10';
$_['jetcache_model_settings'] = $_['heading_title'] = $_['jetcache_model'].' '.$_['jetcache_version'];
$_['heading_title'] = '<span style="color: #16A9DE; font-size: 16px; font-weight: 400;"><a href="https://opencartadmin.com" target="_blank" data-toggle="tooltip" title="" data-original-title="opencartadmin.com"><i class="fa fa-dot-circle-o" style="font-size:16px; margin-right: 6px;"></i></a>' . $_['heading_title'] . '</span>';
if (!defined('SC_VERSION')) define('SC_VERSION', (int)substr(str_replace('.','',VERSION), 0,2));

if (SC_VERSION > 22) {
	if (file_exists(DIR_APPLICATION. 'controller/module/jetcache.php')) {
		@unlink(DIR_APPLICATION. 'controller/module/jetcache.php');
	}
}
if (SC_VERSION < 22) {
	if (file_exists(DIR_APPLICATION. 'controller/extension/module/jetcache.php')) {
		@unlink(DIR_APPLICATION. 'controller/extension/module/jetcache.php');
	}
	$files_extension_module = glob(DIR_APPLICATION. 'controller/extension/module/*.*');
	if (!$files_extension_module && is_dir(DIR_APPLICATION. 'controller/extension/module')) {
    	rmdir(DIR_APPLICATION. 'controller/extension/module');
	}
}

$_['heading_dev'] = 'Разработчик <a href="mailto:admin@opencartadmin.com" target="_blank">opencartadmin.com</a><br>&copy; 2011-'.date('Y') .' All Rights Reserved';
$_['entry_tab_options'] = 'Настройки';
$_['entry_id'] = 'ID';

$_['text_status'] = 'Статус';
$_['text_mod_add_jetcache'] = $_['jetcache_model'].' модификатор установлен<br>';

$_['entry_url_jetcache'] = 'Страница настроек модуля';

$_['tab_options'] = 'Настройки';
$_['tab_pages'] = 'Страницы';
$_['tab_cont'] = 'Контроллеры';
$_['tab_access'] = 'Доступ';
$_['tab_exceptions'] = 'Исключения';
$_['tab_query'] = 'Запросы';
$_['tab_model'] = 'Модели';
$_['tab_minify'] = 'PageSpeed';
$_['tab_minify_html'] = 'HTML';
$_['tab_minify_css'] = 'CSS';
$_['tab_minify_js'] = 'JS';
$_['tab_lazy'] = 'Изображения';

$_['entry_lazy_status'] = 'Отложенная загрузка изображений <br>"Lazy Loading Images"';
$_['entry_lazy_status_help'] = 'Правила замены<br>
Формат:<br>
"токен для земены"|"токен на который заменяем"<br>
разделитель "перевод каретки" PHP_EOL, <br>между токенами |';

$_['entry_minify_html'] = 'Минификация HTML';
$_['entry_minify_html_status_help'] = 'Статус минификации HTML';

$_['entry_minify_html_ex_route'] = 'Исключения route';
$_['entry_minify_html_ex_route_help'] = 'Параметры <br>разделяются <br>переводом "каретки" <br><span class="jc-help"># - выключает</span>';

$_['entry_lazy_ex_route'] = 'Исключения route';
$_['entry_lazy_ex_route_help'] = 'Параметры <br>разделяются <br>переводом "каретки" <br><span class="jc-help"># - выключает</span>';


$_['entry_minify_css'] = 'Минификация CSS';
$_['entry_minify_css_status_help'] = 'Статус минификации CSS';

$_['entry_minify_js'] = 'Минификация JS';
$_['entry_minify_js_status_help'] = 'Статус минификации JS';




$_['tab_doc'] = 'Документация';
$_['tab_logs'] = 'Логи';
$_['tab_main'] = 'Главная';
$_['tab_clear'] = 'Очистка кеша';

$_['entry_db_status'] = 'Кешировать в БД<br><span class="jc-help">(не рекомендуется)</span>';
$_['entry_pages_db_status_help'] = 'Кешировать <br>страницы в БД<br><span class="jc-help">(не рекомендуется)</span>';
$_['entry_cont_db_status_help'] = 'Кешировать <br>контроллеры в БД<br><span class="jc-help">(не рекомендуется)</span>';
$_['entry_model_db_status_help'] = 'Кешировать методы <br>моделей в БД<br><span class="jc-help">(не рекомендуется)</span>';

$_['entry_add_rule'] = 'Добавить';
$_['entry_ex_route'] = 'Route';
$_['entry_ex_routes'] = 'Исключения Routes';

$_['entry_pages_status_help'] = 'Страницы будут <br>кешироваться в файловый кеш<br>если не включено кеширование в БД';
$_['entry_ex_routes_help'] = 'Исключения Route';

$_['entry_pages_forsage'] = 'Форсаж';
$_['entry_pages_forsage_help'] = 'При включении этой функции, будет включен <br>принудительный вызов страниц из кеша как можно раньше';



$_['entry_ex_pages'] = 'Исключения URI';
$_['entry_ex_page'] = 'URI';
$_['entry_ex_page_accord'] = 'Соотвествие';
$_['entry_ex_pages_help'] = 'REQUEST_URI исключения';

$_['entry_cont_status_help'] = 'Статус кеширования контроллеров';
$_['entry_add_conts_help'] = 'Контроллеры которые <br>надо кешировать';
$_['entry_add_conts'] = 'Контроллеры';
$_['entry_add_cont'] = 'Контроллер';

$_['entry_cache_mobile_detect'] = 'Определение мобильного устройства';
$_['entry_jetcache_info_status'] = 'Информационная панель <br><span class="jc-help">Показывается только администраторам</span><span class="jetcache-table-help-href">?</span>';

$_['entry_jetcache_info_demo_status'] = '<br>демо режим<br>(показывать всем)';


$_['entry_model_status_help'] = 'Включить / отключить <br>кеширование методов в моделях';
$_['entry_model_product_status'] = 'Модель catalog/product';
$_['entry_model_gettotalproducts_status'] = 'Метод getTotalProducts';
$_['entry_model_gettotalproducts_status_help'] = 'Кешировать метод <br>подсчета продуктов <br>в категориях';

$_['entry_seocms_jetcache_alter'] = 'Альтернативный метод <br>записи в кеш файл';
$_['entry_seocms_jetcache_gzip_level'] = 'Сжатие (gzip) данных в кеш <br><span class="jc-help">(0 - не сжимать, <br> 0 - быстрее, но занимает <br>больше места на диске)</span>';
$_['entry_seocms_jetcache_gzip_level_help'] = 'Не путать со сжатием HTML страницы <br>Это сжатие файла кеша';


$_['text_gettotalproducts_uri_status'] = 'Зависимость от URL';

$_['entry_ex_session'] = 'Исключенные параметры сессии';
$_['entry_ex_session_help'] = 'Параметры <br>разделяются <br>переводом "каретки" <br>PHP_EOL';

$_['entry_session_log'] = 'Протоколирование <br>переменных сессии <br>в лог файл';
$_['entry_cache_max_hache_folders_level'] = 'Уровень папок в кеше';
$_['entry_no_getpost'] = 'Не реагировать на <br>GET и SESSION';


$_['entry_query_status_help'] = 'Статус кеширования запросов <br>Имеет смысл кешировать запросы, <br>если есть медленные запросы более 0.01 c';
$_['entry_query_db_status_help'] = 'Кеширование запросов в БД <br>Простой запрос кеша из БД <br>будет быстрее сложного и медленного';
$_['entry_query_log_settings'] = 'Настройки лога запросов';
$_['entry_query_log_status'] = 'Статус лог файла запросов';
$_['entry_query_log_maxtime'] = 'Время с которого протоколировать запрос <br><span class="jc-help">(секунды, дробное через точку), пример: 0.1 <br>0.1 секунды, это  100 мс</span>';
$_['entry_query_log_file'] = 'Файл, лог медленных запросов, <br>имя файла в папке лог файлов opencart';
$_['entry_query_model_title'] = 'Запросы которые кешируются для классов и их методов';
$_['entry_query_model_help'] = 'Запросы которые будут кешироваться из моделей и методов <br>Класс модели, к примеру: ModelCatalogProduct <br>Метод модели, к примеру: getTotalProducts <br>Если поле Метод пустое, то будут кешироваться все методы модели';
$_['entry_query_model'] = 'Класс';
$_['entry_query_method'] = 'Метод';

$_['button_buildcache'] = 'Создать кеш';
$_['button_buildcache_abort'] = 'Отмена';
$_['message_buildcache_aborted'] = '<span style="color: red;">Отменено</span>';
$_['message_buildcache_complete'] = '<span style="color: green;">Выполнено успешно</span>';
$_['message_buildcache_processing'] = '<span style="color: #16A9DE;">Обработано</span>';
$_['message_buildcache_processing_complete'] = '<span style="color: #16A9DE;">Выполнено</span>';
$_['entry_jetcache_builcache_gen'] = 'Генерировать кеш';


$_['label_buildcache_with_products'] = 'С товарами';
$_['label_buildcache_with_lang'] = 'Все языки';

$_['label_buildcache_with_products_title_info'] = 'С товарами';
$_['label_buildcache_with_lang_title_info'] = 'Все языки';


$_['label_buildcache_with_products_data_content'] = 'Генерировать кеш со страницами товаров <br> Если большое количество товаров может занять много времени';
$_['label_buildcache_with_lang_data_content'] = 'Генерировать кеш для страниц всех языков <br>Еслм язык один или вы не используете языковые префиксы (поддомены) для каждого языка отмечать не надо';


$_['text_jetcache_success'] = 'Успешно';

$_['entry_log_file_unlink'] = 'Удалить файл';
$_['entry_log_file_view'] = 'Просмотреть файл';
$_['unlink_success'] = 'Успешно';
$_['unlink_unsuccess'] = 'Неудачно. Файл не найден';
$_['access_denided'] = 'Доступ закрыт';


$_['ocmod_jetcache_name'] = $_['jetcache_model'];
$_['ocmod_jetcache_name_15'] = $_['jetcache_model'].' 15';

$_['ocmod_jetcache_db_name'] = $_['jetcache_model'].' DB';
$_['ocmod_jetcache_db_mod'] = $_['jetcache_model_code'].'_db';
$_['ocmod_jetcache_db_html'] = $_['ocmod_jetcache_db_name'].' модификатор успешно установлен<br>';


$_['ocmod_jetcache_cat_name'] = $_['jetcache_model'].' Categories';
$_['ocmod_jetcache_cat_mod'] = $_['jetcache_model_code'].'_cat';
$_['ocmod_jetcache_cat_html'] = $_['ocmod_jetcache_cat_name'].' модификатор успешно установлен<br>';


$_['ocmod_jetcache_menu_name'] = $_['jetcache_model'] . ' Menu';
$_['ocmod_jetcache_menu_mod'] = $_['jetcache_model_code'] . '_menu';
$_['ocmod_jetcache_menu_html'] = $_['ocmod_jetcache_menu_name'] . ' модификатор успешно установлен<br>';

$_['ocmod_jetcache_image_name'] = $_['jetcache_model'].' Image';
$_['ocmod_jetcache_image_mod'] = $_['jetcache_model_code'].'_image';
$_['ocmod_jetcache_image_html'] = $_['ocmod_jetcache_image_name'].' модификатор успешно установлен<br>';

$_['ocmod_jetcache_mod'] = $_['jetcache_model_code'];
$_['ocmod_jetcache_mod_15'] = $_['jetcache_model_code'].'_15';


$_['ocmod_jetcache_author'] = 'opencartadmin.com';
$_['ocmod_jetcache_link'] = 'https://opencartadmin.com';
$_['jetcache_ocas'] = 'https://opencartadmin.com/index.php?route=record/ver';

$_['ocmod_jetcache_html'] = $_['ocmod_jetcache_name'].' модификатор успешно установлен<br>';


$_['entry_install_update'] = 'Установка / обновление';
$_['url_create_text'] = '<div style="text-align: center; text-decoration: none;">Установка и обновление<br>модификаторов, данных модуля<br>(выполняется при установке или обновлении модуля)</div>';

$_['text_refresh_ocmod_successfully'] = '<span style="color:green">Модификаторы успешно обновлены</span>';
$_['text_refresh_ocmod_success'] = 'Модификаторы успешно обновлены';

$_['text_refresh_ocmod_error'] = '<span style="color:red">Ошибка обновления модификаторов</span>';

$_['entry_model_help'] = 'Классы и методы моделей <br>для кеширования';
$_['entry_onefile'] = 'В один "файл"';
$_['entry_model_status'] = 'Кеширование моделей';
$_['entry_model_title'] = 'Классы и методы моделей для кеширования';
$_['entry_no_vars'] = 'Не реагировать на:<br> 1. GET параметры<br> 2. SESSION параметры<br> 3. URL адрес<br> 4. ROUTE';

$_['entry_ex_get'] = 'Исключенные параметры GET';
$_['entry_ex_get_help'] = 'Параметры <br>разделяются <br>переводом "каретки" <br>PHP_EOL';

$_['entry_lastmod_status'] = 'Статус заголовка Last-Modified';
$_['entry_lastmod_help'] = 'Last-Modified: '.gmdate('D, d M Y H:i:s \G\M\T').',filemtime(кеш файла)<br>HTTP/1.1 304 Not Modified <br><br>Если не работает попробуйте в файл <br>.htaccess добавить правило после <br>RewriteRule ^([^?]*) index.php?_route_=$1 [L,QSA]<br><br><span style="color: green;">RewriteRule ^(.*)$ $1 [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]</span><br>или
<br><span style="color: green;">RewriteRule .* - [E=HTTP_IF_MODIFIED_SINCE:%{HTTP:If-Modified-Since}]<br>RewriteRule .* - [E=HTTP_IF_NONE_MATCH:%{HTTP:If-None-Match}]</span><br><br>Если у вас связка nginx+php<br>отредактируйте config<br>
<br>
location ~ .php$<br>
{<br>
 …<br>
 if_modified_since off;<br>
<br>
 fastcgi_pass fcgi;<br>
 fastcgi_index index.php;<br>
 fastcgi_param SCRIPT_FILENAME /<путь > /web$fastcgi_script_name;<br>
 …<br>
 fastcgi_pass_header Last-Modified;<br>
 include fastcgi_params;<br>
}<br>
';

$_['entry_cachecontrol_status'] = 'Статус заголовка Cache-Control';
$_['entry_cachecontrol_help'] = 'Cache-Control:public, max-age=31536000';

$_['entry_expires_status'] = 'Статус заголовка Expires';
$_['entry_expires_help'] = 'Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 604800);

$_['ocmod_file_agoo_catalog_product_unlink_successfully'] = 'Файл старой версии <br>/catalog/model/agoo/catalog/product.php<br> успешно удален<br><br>';

/***************/

$_['entry_widget_status'] = "Статус";
$_['entry_cache_expire'] = 'Время жизни кеш файла <br>модуля (в секундах)';
$_['entry_cache_max_files'] = 'Максимальное количество файлов <br>в папке кеша модуля';
$_['entry_cache_maxfile_length'] = 'Максимальный размер <br>кеш файла модуля (в байтах)';
$_['entry_cache_auto_clear'] = 'Автоматическое очищение <br>всего кеша (в часах)';
$_['entry_tab_settings_cache'] = 'Кеш и модификаторы';
$_['entry_jetcache_ocmod_refresh'] = 'Обновить кеш <br><span class="sc-color-clearcache">модификаторов</span>';
$_['text_url_ocmod_refresh'] = 'Обновить';
$_['text_ocmod_refresh_success'] = 'Выполнено успешно';
$_['text_ocmod_refresh_fail'] = 'Не удалось обновить';
$_['entry_jetcache_cache_remove'] = 'Удалить кеш <br><span class="sc-color-clearcache">файлов</span>';
$_['text_url_cache_remove'] = 'Удалить кеш';
$_['text_cache_remove_success']	= 'Выполнено успешно';
$_['text_cache_remove_fail'] = 'Не удалось удалить';
$_['text_jetcache_about'] = 'О модуле';
$_['entry_jetcache_cache_image_remove'] 	= 'Удалить кеш <br><span class="sc-color-clearcache">изображений</span>';
$_['text_url_cache_image_remove'] 	= 'Удалить кеш';
$_['text_cache_image_remove_success']	= 'Выполнено успешно';
$_['text_cache_image_remove_fail'] 	= 'Не удалось удалить';
$_['entry_store'] = 'Магазины:';
$_['text_default_store'] = 'Основной магазин';
$_['text_loading_main']         = '<div style=&#92;\'color: #008000;&#92;\'>Загружается...<i class=&#92;\'fa fa-refresh fa-spin&#92;\'></i></div>';
$_['text_loading_main_without'] = '<div style="color: #008000">Загружается...<i class="fa fa-refresh fa-spin"></i></div>';

$_['text_faq'] = '';
$_['text_separator'] = ' > ';

$_['entry_add_category'] = 'Очистка кеша (полная)<br>при добавлении, изменении, <br>удалении категории';
$_['entry_add_category_help'] = 'При включенной настройке будет произведена полная очистка кеша';
$_['label_add_category'] = 'Очистка кеша при добавлении, изменении, удалении категории';
$_['label_add_category_content'] = 'При включенной настройке будет произведена полная очистка кеша';

$_['entry_add_product'] = 'Очистка кеша (полная)<br>при добавлении, удаления продукта';
$_['entry_add_product_help'] = 'При включенной настройке будет произведена полная очистка кеша';
$_['label_add_product'] = 'Очистка кеша при добавлении, удалении продукта';
$_['label_add_product_content'] = 'При включенной настройке будет произведена полная очистка кеша';

$_['entry_edit_product'] = 'Очистка кеша (полная)<br>при изменении продукта';
$_['entry_edit_product_help'] = 'При включенной настройке будет произведена полная очистка кеша';
$_['label_edit_product'] = 'Очистка кеша при изменении продукта';
$_['label_edit_product_content'] = 'При включенной настройке будет произведена полная очистка кеша';

$_['entry_edit_product_id'] = 'Очистка кеша (связанная) <br>при изменении продукта';
$_['label_edit_product_id_content'] = "При включенной настройке будет произведена очистка кеша <br>только связанных кеш файлов с продуктом <br><span style='color: red;'>Внимание! Немного замедляет работу при записи в кеш. <br>Из кеша также быстро</span>";
$_['entry_edit_product_id_help'] = $_['label_edit_product_id_content'];
$_['label_edit_product_id'] = 'Связанная очистка кеша при изменении продукта';


$_['entry_query_log_status_title'] = 'Статус&nbsp;лог&nbsp;файла&nbsp;запросов';
$_['entry_query_log_status_content'] = 'Не забудьте отключать после анализа запросов<br><span style="color: red;">Внимание! Статус запросов (таб Запросы) должен быть включен</span>';

$_['entry_jetcache_menu_status'] = 'Статус <i class="fa fa-dot-circle-o"></i> JC в меню';
$_['entry_jetcache_menu_order'] = 'Порядок пункта <i class="fa fa-dot-circle-o"></i> JC в меню, после "номера" <br>пункта в меню <br>номер:';

$_['text_status_on'] = 'включено';
$_['text_status_off'] = 'выключено';

$_['text_js_status_on'] = 'JC <span style="margin-left: 6px; color: #eeffee;"> '.$_['text_status_on'] .' <i class="fa fa-dot-circle-o"></i></span>';
$_['text_js_status_off'] = 'JC <span style="margin-left: 6px; color: #fccccc;"> '.$_['text_status_off'] .' </span>';

$_['text_ocmod_refresh'] = 'Обновить&nbsp;модификаторы';

$_['text_close'] = 'Закрыть';

$_['entry_session_log_file'] = 'Файл, лог сессии,<br>имя файла в папке лог файлов opencart';

$_['entry_session_log_settings'] = 'Настройка лога сессии';
$_['entry_session_log_settings_help'] = 'При включении вы можете протоколировать <br>переменные сессии и по надобности заносить их в исключения';
$_['entry_query_log_settings_help'] = 'При включении вы можете анализировать <br>запросы и по надобности заносить их в настройки<br>Внимание статус запросов должен быть включен';

$_['entry_model_original_status_help'] = 'Использовать оригинальный метод model класса Loader';

$_['entry_cont_log_settings'] = 'Настройки лога контроллеров';
$_['entry_cont_log_settings_help'] = 'При включении вы можете протоколировать <br>скорость выполнения контроллеров';
$_['entry_cont_log_status'] = 'Статус лог файла контроллеров';
$_['entry_cont_log_maxtime'] = 'Время с которого протоколировать контроллер <br><span class="jc-help">(секунды, дробное через точку), пример: 0.1 <br>0.1 секунды, это  100 мс</span>';
$_['entry_cont_log_file'] = 'Файл лог, скорости <br>выполнения контроллеров,<br>имя файла в папке лог файлов opencart';
$_['entry_cont_log_settings_help'] = 'При включении вы можете анализировать <br>скорость выполнения контроллеров';
$_['entry_cont_log_status_title'] = 'Статус&nbsp;лог&nbsp;файла&nbsp;контроллеров';
$_['entry_cont_log_status_content'] = 'При включении вы можете анализировать <br>скорость выполнения контроллеров, <br>чтобы занести их в кеширование контроллеров';

$_['tab_image_options'] = 'Настройки';
$_['tab_image_ex'] = 'Исключения';
$_['entry_image_status'] = 'Статус оптимизации изображений';
$_['entry_image_ex'] = 'Исключения';
$_['entry_image_status_help'] = 'При включенном статусе будет <br>производиться оптимизация изображений';
$_['entry_image_ex_help'] = 'Исключения';

$_['entry_image_status_error_text'] = 'Система не отвечает требованиям для оптимизации';

$_['entry_image_status_error'] = 'Статус системы';
$_['entry_mozjpeg'] = 'Оптимизация JPEG по алгоритму mozjpeg';
$_['entry_jpegoptim'] = 'Оптимизация JPEG по алгоритму jpegoptim';
$_['entry_optipng'] = 'Оптимизация PNG по алгоритму optipng';


$_['entry_image_status_error_must_text'] = 'Требования системы:<br><div style="text-align: left !important;">
Linux платформа (OC)<br>
Включенная функция php exec на стороне сервера <br>
или включенная функция php proc_open на стороне сервера <br>
Права на выполнения (0755) для файлов mozjpeg, jpegoptim и optipng <br>
Возможность запуска mozjpeg и optipng
</div>';

$_['entry_image_mozjpeg_status'] = 'Статус оптимизации изображений JPEG по алгоритму mozjpeg';
$_['entry_image_mozjpeg_optimize'] = 'Максимальная оптимизация (медленно)<br><div class="jetcache-table-help">ключ -optimize</div>';
$_['entry_image_mozjpeg_progressive'] = 'Прогрессивный алгоритм JPEG<br><div class="jetcache-table-help">ключ -progressive</div>';
$_['entry_mozjpeg_must'] = 'Требования:<br>Права на выполнения (0755) для файлов mozjpeg<br>Возможность запуска mozjpeg';
$_['entry_mozjpeg_text'] = 'mozjpeg';

$_['entry_image_jpegoptim_status'] = 'Статус оптимизации изображений JPEG по алгоритму jpegoptim';
$_['entry_image_jpegoptim_optimize'] = 'Форсированная оптимизация<br><div class="jetcache-table-help">ключ --force</div>';
$_['entry_image_jpegoptim_progressive'] = 'Прогрессивный алгоритм JPEG<br><div class="jetcache-table-help">ключ --all-progressive</div>';
$_['entry_jpegoptim_must'] = 'Требования:<br>Права на выполнения (0755) для файлов jpegoptim<br>Возможность запуска jpegoptim';
$_['entry_jpegoptim_text'] = 'jpegoptim';

$_['entry_image_jpegoptim_strip'] = 'Удалить EXIF и комментарии<br><div class="jetcache-table-help">ключ --strip-all --strip-iptc</div>';
$_['entry_image_jpegoptim_level'] = 'Уровень компрессии<br><span class="jc-help">Целое число 1-99, <br>без знака процента<br>Хотите без потери качества - <br>не заполняйте</span><br><div class="jetcache-table-help">указывать от 1 до 99, <br>если не заполнять - без потери качества</div>';
$_['entry_image_jpegoptim_size'] = 'Размер файла в процентах<br>от оригинального<br><span class="jc-help">Целое число 1-99, <br>без знака процента<br>Хотите без потери качества - <br>не заполняйте</span><br><div class="jetcache-table-help">указывать от 1 до 99, <br>если не заполнять - без потери качества</div>';


$_['entry_image_optipng_status'] = 'Статус оптимизации изображений PNG по алгоритму optipng';
$_['entry_optipng_must'] = 'Требования:<br>Права на выполнения (0755) для файлов optipng<br>Возможность запуска optipng';
$_['entry_optipng_optimize_level'] = 'Уровень оптимизации optipng<br><span class="jc-help">Рекомендуем 1 или 2</span><br><div class="jetcache-table-help">ключ –oX,<br>где о – сокр. от optimization, X – уровень сжатия (1-7), где 7 - максимальный, но медленный, 1 - быстрый, но минимальный</div>';
$_['entry_optipng_text'] = 'optipng';


$_['entry_features_system'] = 'Возможности системы (тест)';

$_['entry_system_linux_status'] = 'Linux платформа (ОС)';
$_['entry_system_exec_status'] = 'Включенная функция php exec';
$_['entry_system_proc_open'] = 'Включенная функция php proc_open';



$_['entry_system_mozjpeg_perms'] = 'Права на выполнение mozjpeg <span class="jc-help">(0755)</span>';
$_['entry_system_mozjpeg_exec'] = 'Возможность выполнения оптимизации mozjpeg';
$_['entry_system_image_mozjpeg_original'] = 'Оригинальное изображение JPEG';
$_['entry_system_image_mozjpeg_optimized'] = 'Оптимизированное изображение JPEG по алгоритму mozjpeg';

$_['entry_system_jpegoptim_perms'] = 'Права на выполнение jpegoptim <span class="jc-help">(0755)</span>';
$_['entry_system_jpegoptim_exec'] = 'Возможность выполнения оптимизации jpegoptim';
$_['entry_system_image_jpegoptim_original'] = 'Оригинальное изображение JPEG';
$_['entry_system_image_jpegoptim_optimized'] = 'Оптимизированное изображение JPEG по алгоритму jpegoptim';


$_['entry_system_optipng_perms'] = 'Права на выполнение optipng <span class="jc-help">(0755)</span>';
$_['entry_system_optipng_exec'] = 'Возможность выполнения оптимизации optipng';
$_['entry_system_image_optipng_original'] = 'Оригинальное изображение PNG';
$_['entry_system_image_optipng_optimized'] = 'Оптимизированное изображение PNG по алгоритму optipng';

$_['entry_system_yes'] = '<span class="jetcache-green">Соотвествует</span>';
$_['entry_system_no'] = '<span class="jetcache-red">Не соотвествует</span>';
$_['text_system_byte'] = '<span class="">байт</span>';

$_['error_image_exec'] = 'Функция php exec - выключена';
$_['error_image_proc_open'] = 'Функция php proc_open - выключена';
$_['error_image_linux'] = 'Не linux платформа';


$_['entry_ex_key'] = 'Очистка всего кеша через связанный параметр <br>очистки кеша opencart $this->cache->delete(\'параметр\');';
$_['text_ex_key'] = 'Параметры <br>разделяются <br>переводом "каретки" <br><span class="jc-help"># - выключает</span>';

$_['entry_header_categories_status'] = 'Кеширование категорий меню в header';
$_['entry_header_categories_status_help'] = 'Кеширование стандартного расчета категорий товаров <br>для меню в контроллере header.php';


$_['entry_tab_cont_categories'] = 'Категории';
$_['entry_tab_cont_ajax'] = 'Ajax';

$_['entry_cont_ajax_status'] = 'Статус ajax загрузки контроллеров';
$_['entry_cont_ajax_status_help'] = 'Ajax загрузка контроллеров<br>Рекомендуется не использовать <br>без особой надобности';

$_['entry_cont_ajax_route'] = 'Route контроллеров ajax загрузки';
$_['entry_cont_ajax_route_help'] = 'Route контроллеров ajax загрузки<br>Параметры разделяются <br>переводом "каретки" <br><span class="jc-help"># "хештег" первым символом в строке <br>выключает "строку" route</span>';

$_['entry_cont_ajax_header'] = 'Замена &lt;head&gt;...&lt;/head&gt; на загруженный из ajax';
$_['entry_cont_ajax_header_help'] = 'Включать <b>только</b> когда в ajax контроллерах <br>есть вызов $this->document->...<br>Возможно одномоментное мерцание';


$_['entry_ex_session_black_status'] = 'Параметры сессии (статус алгоритма "черный список")';
$_['entry_ex_session_black_status_help'] = 'Параметры сессии (статус алгоритма "черный список")';
$_['entry_ex_session_black'] = 'Параметры сессии ("черный список")';
$_['entry_ex_session_black_help'] = 'На какие параметры сессии реагировать кешу. <br>Параметры <br>разделяются <br>переводом "каретки" <br><span class="jc-help"># - выключает</span>';
