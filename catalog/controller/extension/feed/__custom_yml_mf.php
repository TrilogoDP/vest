<?php

define('YANDEX_YML_VERSION', '1.6.6.1');

class ControllerExtensionFeedCustomYmlMf extends Controller
{
    private $statusFile = DIR_CACHE . '/feedupdater.status';
    private $status;
    private $type;

    private $langprefix = '';

    private function link($route, $args = '')
    {
        $url = $this->url->link($route, $args);

        $result = $url;
        if ($this->langprefix) {
            $scheme = parse_url($url, PHP_URL_SCHEME);
            $path = parse_url($url, PHP_URL_PATH);
            $query = parse_url($url, PHP_URL_QUERY);

            $result = $scheme . '://' . rtrim($this->langprefix, '/') . '/' . ltrim($path, '/');

            if ($query) {
                $result .= '?' . $query;
            }
        }

        return htmlspecialchars_decode(trim($result));
    }

    private function checkIfCanDoPromoPrice($product)
    {
        if ($product['special']) {
            if ($product['special'] < $product['price']) {
                if (($product['price'] - ($product['price']/100)*15) > $product['special']) {
                    $promo_discount = (($product['special']/100)*1);
                    if ($promo_discount > 1) {
                        return round($product['special'] - $promo_discount);
                    } else {
                        return 1;
                    }
                }
            }
        }

        return false;
    }

    protected function strip_html_tags($text)
    {
        $text = preg_replace(
            array(
            // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before and after blocks
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0",
            ),
            $text
        );
        return strip_tags($text);
    }

    private function normalizeForGoogle($text)
    {
        $text = str_replace('&nbsp;', ' ', $text);
        $text = str_replace(' & ', ' and ', $text);
        $text = str_replace('&', ' and ', $text);
        $text = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $text);

        return trim($text);
    }

    public static function fixEncoding($string)
    {
        $string=str_replace("&lt;br&gt;", " ", $string);
        $string=str_replace("<br>", " ", $string);
        $string=str_replace("&amp;lt;", "&lt;", $string);
        $string=str_replace("&amp;gt;", "&gt;", $string);
        $string=str_replace("&amp;quot;", "&quot;", $string);
        $string=str_replace("&amp;amp;", "&amp;", $string);
        $string=str_replace("&amp;nbsp;", "&amp;&nbsp;", $string);
        $string=str_replace("&amp;&nbsp;", " ", $string);
        $string=str_replace("&nbsp;", " ", $string);
        $string=str_replace("&quot;", "\"", $string);
        $string=str_replace("&gt;", ">", $string);
        $string=str_replace("&lt;", "<", $string);
        $string=str_replace("&amp;", "&", $string);
        $string=str_replace("<br>", " ", $string);
        return $string;
    }

    private function clearDescriptionForGoogle($text)
    {
        $text= str_replace("
			", " ", str_replace("\t", " ", str_replace("\n", " ", str_replace("\r", " ", str_replace("\r\n", " ", htmlspecialchars($this->strip_html_tags(htmlspecialchars_decode($text, ENT_COMPAT)), ENT_COMPAT, 'UTF-8'))))));
        while (strpos($text, "  ") !== false) {
            $text=str_replace("  ", " ", $text);
        }
        $text=$this->fixEncoding($text);

        while ($this->startsWith($text, "&amp;nbsp;") || $this->endsWith($text, "&amp;nbsp;") || $this->startsWith($text, " ") || $this->endsWith($text, " ")) {
            $text = $this->clearDescription($text, "&amp;nbsp;");
            $text = $this->clearDescription($text, " ");
        }

        while (strpos($text, '  ') !== false) {
            $text=str_replace('  ', ' ', $text);
        }

        $text=trim($text);

        return $text;
    }

    private function clearDescription($string, $remove)
    {
        while ($this->startsWith($string, $remove)) {
            $string = substr($string, strlen($remove));
        }
        while ($this->endsWith($string, $remove)) {
            $string = substr($string, 0, strlen($string) - strlen($remove));
        }

        return $string;
    }

    private function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }

    private function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }
        return false;
    }

    private function formatProductAttributeGroupsForDescription($attribute_groups)
    {

        $result = '';
        foreach ($attribute_groups as $ag) {
            if (!empty($ag['attribute'])) {
                foreach ($ag['attribute'] as $attribute) {
                    $result .= $attribute['name'] . ' : ' . $attribute['text'] . '. ';
                }
            }
        }

        $result = trim($result);

        return $result;
    }

    private function formatProductAttributesForDescription($attributes)
    {

        $result = '';

        foreach ($attributes as $attribute) {
            $result .= $attribute['name'] . ' : ' . $attribute['text'] . '. ';
        }


        $result = trim($result);

        return $result;
    }

    private function formatProductOcFiltersForDescription($ocfilters)
    {

        $result = '';

        foreach ($ocfilters as $ocfilter) {
            $result .= $ocfilter['name'] . ' : ' . $ocfilter['value'] . '. ';
        }


        $result = trim($result);

        return $result;
    }

        //++++ Config section ++++
        //Из какого поля брать описание товара (description, meta_description)
    protected $DESCRIPTION_FIELD = 'description';
        //До какой длины укорачивать описание товара. 0 - не укорачивать
    protected $SHORTER_DESCRIPTION = 0;
        //Отдавать ли Яндексу оригиналы фотографий товаров. Если false - то всегда масштабировать
    protected $ORIGINAL_IMAGES = true;
        //---- Config section ----
    protected $CONFIG_PREFIX = 'yandex_yml_';

    protected $shop = array();
    protected $currencies = array();
    protected $categories = array();
    protected $offers = array();
        //protected $from_charset = 'utf-8';
    protected $eol = "\n";
    protected $yml = '';

    protected $color_options;
    protected $size_options;
    protected $size_units;
    protected $optioned_name;

    public function index()
    {

        die('MOVED TO CLI');

        $this->generateYml();
        $this->response->addHeader('Content-Type: application/xml');
        $this->response->setOutput($this->getYml());
    }

    private function echoLine($line)
    {
        $line = str_replace('<![CDATA[', '', $line);
        $line = str_replace(']]>', '', $line);
        echo $line . PHP_EOL;
    }

    private function echoSimple($line)
    {
        echo $line;
    }

    private function memoryUnits($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024, ($i=floor(log($size, 1024)))), 2).' '.$unit[$i];
    }

    private function getStatus()
    {

        if (!file_exists($this->statusFile)) {
            $this->setStatus('idle');
        }

        $this->status = file_get_contents($this->statusFile);
    }

    private function setType($type)
    {

        $this->type = $type;

        return $this;
    }

    private function setStatus($status)
    {
        file_put_contents($this->statusFile, $status);
    }

    public function cron()
    {

        if (!defined('OPENCART_CLI_MODE')) {
            die('CLI ONLY');
        }

        $this->getStatus();

        if ($this->status != 'idle') {
            $this->echoLine('now working, exit');
                //      return;
        }
        $this->setStatus('rozetka');
        $this->setType('rozetka');

        $this->echoLine('[TIME] Начали в ' . date('H:i:s'));
        $file = DIR_FEEDS . 'rozetka_hotline_ymlfeed.xml';

        $this->saveToFile($file);

        gc_collect_cycles();
        $this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
        $this->echoLine('[TIME] Закончили в ' . date('H:i:s'));

        $this->setStatus('idle');
    }

    public function cron_hotline_uk()
    {
        $this->config->set('config_language_id', 3);
        $this->config->set('config_language', 'uk-ua');

        $langmark = $this->config->get('asc_langmark');
                
        if ($langmark && isset($langmark['prefix']) && isset($langmark['prefix']['uk-ua'])) {
            $this->langprefix = trim($langmark['prefix']['uk-ua']);
        }

        $this->cron_hotline();
    }

    public function cron_hotline()
    {

        if (!defined('OPENCART_CLI_MODE')) {
            die('CLI ONLY');
        }

        $this->getStatus();

        if ($this->status != 'idle') {
            $this->echoLine('now working, exit');
            return;
        }

        $this->setStatus('hotline');
        $this->setType('hotline');

        $this->echoLine('[TIME] Начали в ' . date('H:i:s'));
        $file = DIR_FEEDS . 'hotline_ymlfeed.xml';

        $this->saveToFileHotline($file);

        gc_collect_cycles();
        $this->echoLine('[mem] Занято памяти ' . $this->memoryUnits(memory_get_usage(true)));
        $this->echoLine('[TIME] Закончили в ' . date('H:i:s'));

        $this->setStatus('idle');
    }

    public function saveToFile($filename)
    {
        $this->generateYml();
        $fp = fopen($filename, 'w+');
        $this->putYml($fp);
        fclose($fp);
    }

    public function saveToFileHotline($filename)
    {
        $this->generateYml($hotlineStandart = true);
        $fp = fopen($filename, 'w+');
        $this->putYml($fp);
        fclose($fp);
    }

    protected function generateYml($hotlineStandart = false)
    {
        if ($this->config->get($this->CONFIG_PREFIX.'status')) {
            if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
                $HTTP_SERVER = $this->config->get('config_ssl');
            } else {
                $HTTP_SERVER = $this->config->get('config_url');
            }
            if (!defined('HTTP_IMAGE')) {
                define('HTTP_IMAGE', $HTTP_SERVER . 'image/');
            }
            $this->load->model('export/yandex_yml');
            $this->load->model('localisation/currency');
            $this->load->model('tool/image');
            $this->load->model('catalog/product');

                // Магазин
            $this->setShop('name', $this->config->get('config_name'));
            $this->setShop('company', $this->config->get('config_owner'));
            $this->setShop('url', $HTTP_SERVER);
            $this->setShop('phone', $this->config->get('config_telephone'));
            $this->setShop('platform', 'Yandex.YML for OpenCart (ocStore)');
            $this->setShop('version', YANDEX_YML_VERSION);

                // Валюты
                // TODO: Добавить возможность настраивать проценты в админке.
            $offers_currency = $this->config->get($this->CONFIG_PREFIX.'currency');
            if (!$this->currency->has($offers_currency)) {
                exit();
            }

            $decimal_place = intval($this->currency->getDecimalPlace($offers_currency));

            $shop_currency = $this->config->get('config_currency');

            $this->setCurrency($offers_currency, 1);

            $currencies = $this->model_localisation_currency->getCurrencies();

            $supported_currencies = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAN', 'UAH');

            $currencies = array_intersect_key($currencies, array_flip($supported_currencies));

            foreach ($currencies as $currency) {
                if ($currency['code'] != $offers_currency && $currency['status'] == 1) {
                    $this->setCurrency($currency['code'], number_format(1/$this->currency->convert($currency['value'], $offers_currency, $shop_currency), 4, '.', ''));
                }
            }
                //Тип данных vendor.model или default
            $datamodel = $this->config->get($this->CONFIG_PREFIX.'datamodel');

                // Категории
            $categories = $this->model_export_yandex_yml->getCategory();

            foreach ($categories as $category) {
                $this->setCategory($category['name'], $category['category_id'], $category['parent_id']);
            }

                // Товарные предложения
                $in_stock_id = $this->config->get($this->CONFIG_PREFIX.'in_stock'); // id статуса товара "В наличии"
                $out_of_stock_id = $this->config->get($this->CONFIG_PREFIX.'out_of_stock'); // id статуса товара "Нет на складе"
                $vendor_required = ($datamodel == 'vendor_model'); // true - только товары у которых задан производитель, необходимо для 'vendor.model'
                
                $pickup = ($this->config->get($this->CONFIG_PREFIX.'pickup') ? 'true' : false);
                
            if ($this->config->get($this->CONFIG_PREFIX.'delivery_cost') != '') {
                $local_delivery_cost = intval($this->config->get($this->CONFIG_PREFIX.'delivery_cost'));
                $export_delivery_cost = true;
            } else {
                $export_delivery_cost = false;
            }
                
                $store = ($this->config->get($this->CONFIG_PREFIX.'store') ? 'true' : false);
                $unavailable = $this->config->get($this->CONFIG_PREFIX.'unavailable');
                
                $allowed_categories = $this->config->get($this->CONFIG_PREFIX.'categories');
                $allowed_manufacturers = $this->config->get($this->CONFIG_PREFIX.'manufacturers');
            $allowed_manufacturers='';
                $blacklist_type = $this->config->get($this->CONFIG_PREFIX.'blacklist_type');
                $blacklist = $this->config->get($this->CONFIG_PREFIX.'blacklist');
                $product_rel = $this->config->get($this->CONFIG_PREFIX.'product_rel');
                $product_accessory = $this->config->get($this->CONFIG_PREFIX.'product_accessory');
                
            if ($hotlineStandart) {
                $products = $this->getProduct($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_id, $vendor_required, $allowed_manufacturers, $product_rel || $product_accessory, false);
            } else {
                //OVERLOADING
                $products = $this->getProduct($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_id, $vendor_required, $allowed_manufacturers, $product_rel || $product_accessory, true);
                    
                //$products = $this->getProduct($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_id, $vendor_required, $allowed_manufacturers, $product_rel || $product_accessory, false);
            }
                
                $numpictures = $this->config->get($this->CONFIG_PREFIX.'numpictures');
            if ($numpictures > 1) {
                //++++ Дополнительные изображения товара ++++
                $product_images = $this->model_export_yandex_yml->getProductImages($numpictures - 1);
                //---- Дополнительные изображения товара ----
            }
                $all_attributes = $this->model_export_yandex_yml->getAttributes($this->config->get($this->CONFIG_PREFIX.'attributes'));
                $this->optioned_name = $this->config->get($this->CONFIG_PREFIX.'optioned_name');
                
                $yandex_yml_categ_mapping = unserialize($this->config->get($this->CONFIG_PREFIX.'categ_mapping'));
                
                $this->color_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'color_options'));
            $this->color_options[] = 14;
                $this->size_options = explode(',', $this->config->get($this->CONFIG_PREFIX.'size_options'));
                $this->size_units = $this->config->get($this->CONFIG_PREFIX.'size_units') ? unserialize($this->config->get($this->CONFIG_PREFIX.'size_units')) : array();
                
            foreach ($products as $product) {
                if (!$hotlineStandart) {
                    if ($product['product_id'] != 30307) {
                        //  continue;
                    }
                }

                $data = array();
                    
                $options = $this->model_export_yandex_yml->getProductOptions($this->color_options, $product['product_id']);
                //***** 1. Нет опций
                $data['id'] = $product['product_id'];
                $data['type'] = $datamodel;
                $data['available'] = (!$unavailable && ($product['quantity'] > 0 || $product['stock_status_id'] == $in_stock_id) ? 'true' : false);
                $data['stock_quantity'] = (int)$product['quantity'];
                    
                $data['options'] = $this->color_options;
                    
                // Параметры товарного предложения
                $data['url'] = $this->link('product/product', 'path=' . $this->getPath($product['category_id']) . '&product_id=' . $product['product_id']);
                if ($product['special'] && $product['special'] < $product['price']) {
                    $data['price'] = $product['special'];
                    $data['price_old'] = $product['price'];
                        
                    if ($promo = $this->checkIfCanDoPromoPrice($product)) {
                        $data['price_promo'] = $promo;
                    }
                } else {
                    $data['price'] = $product['price'];
                }
                    
                    
                $data['currencyId'] = str_replace("UAN", "UAH", $offers_currency);
                $data['categoryId'] = $product['category_id'];
                    
                $data['categoryId'] = $this->getProductCategory($product['product_id']);
                    
                if (isset($yandex_yml_categ_mapping[$product['category_id']]) && $yandex_yml_categ_mapping[$product['category_id']]) {
                    $data['market_category'] = $yandex_yml_categ_mapping[$product['category_id']];
                }
                $data['delivery'] = 'true';
                if ($export_delivery_cost) {
                    $data['local_delivery_cost'] = $local_delivery_cost;
                }
                if ($pickup) {
                    $data['pickup'] = $pickup;
                }
                if ($store) {
                    $data['store'] = $store;
                }
                    
                $data['name']       = $product['name'];
                $data['vendor']     = $product['manufacturer'];
                $data['vendorCode'] = $product['mpn'];
                $data['model']      = $product['model'];
                $data['code']       = $product['model'];


                if ($hotlineStandart) {
                    //MODEL = model_marketplace -> model
                    $data['model'] = $product['model_marketplace'];

                    if (!$data['model']) {
                        $data['model'] = $product['model'];
                    }


                    //CODE = mpn -> model_marketplace -> model
                    $data['code'] = $product['mpn'];

                    if (!$data['code']) {
                        $data['code'] = $data['model'];
                    }


                    //BARCODE = ean -> gtin
                    if ($product['ean']) {
                        $data['barcode'] = $product['ean'];
                    }
                        
                    if (!$data['barcode']) {
                        $data['barcode'] = $product['gtin'];
                    }
                }
                    
                $sales_notes = $this->config->get($this->CONFIG_PREFIX.'sales_notes');
                if ($sales_notes) {
                    $data['sales_notes'] = $sales_notes;
                }
                if ($numpictures > 0) {
                    if ($product['image']) {
                        $data['picture'] = array($this->prepareImage($product['image']));
                    }
                    //++++ Дополнительные изображения товара ++++
                    if (isset($product_images[$product['product_id']])) {
                        if (!isset($data['picture']) || !is_array($data['picture'])) {
                            $data['picture'] = array();
                        }
                        foreach ($product_images[$product['product_id']] as $image) {
                            $data['picture'][] = $this->prepareImage($image);
                        }
                    }
                    //---- Дополнительные изображения товара ----
                }
                    
                if ($product_rel && $product['rel']) {
                    $data['rec'] = $product['rel'];
                }
                if ($product_accessory && $product['rel']) {
                    $data['accessory'] = explode(',', $product['rel']);
                }
                    
                $data['param'] = array();
                $attributes = $this->model_export_yandex_yml->getProductAttributes($product['product_id']);
                    
                $attr_text = array();
                if (count($attributes) > 0) {
                    foreach ($attributes as $attr) {
                        if ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'adult')) {
                            $data['adult'] = 'true';
                        } elseif ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'manufacturer_warranty')) {
                            $data['manufacturer_warranty'] = 'true';
                        } elseif ($attr['attribute_id'] == $this->config->get($this->CONFIG_PREFIX.'country_of_origin')) {
                            $data['country_of_origin'] = $attr['text'];
                        }
                        /*elseif (isset($all_attributes[$attr['attribute_id']])) {

                        }*/
                            
                        $data['param'][] = $this->detectUnits(array(
                            'name'  => $attr['name'],
                            'value' => $this->changeColor($attr['text'])));
                            
                        $attr_text[] = $attr['name'].': '.$attr['text'];
                        if (strpos($attr['name'], "Гарант") !== false) {
                            $data['manufacturer_warranty'] = $attr['text'];//***
                        }
                    }
                }
                    
                $ocfilters = $this->model_catalog_product->getProductOcFilterActiveValues($product['product_id'], ', ');
                foreach ($ocfilters as $ocfilter) {
                    $data['param'][] = array(
                        'name' => $ocfilter['name'],
                        'value' => $ocfilter['value']
                    );
                }
                    
                $data['param'] = array_key_unique($data['param'], 'name');
                    
                if ($product['weight'] > 0) {
                    $data['param'][] = array('id'=>'WEIGHT', 'name'=>'Вес', 'value'=>$product['weight'], 'unit'=>$product['weight_unit']);
                }
                //---- Атрибуты товара ----
                    
                //++++ Описание товара ++++
                if ($this->config->get($this->CONFIG_PREFIX.'attr_vs_description') && false) {
                    $data['description'] = implode($attr_text, "\n");
                } else {
                    $product_description = strip_tags($product[$this->DESCRIPTION_FIELD]);
                    if ($this->SHORTER_DESCRIPTION > 0) {
                        $product_description = mb_substr($product_description, 0, $this->SHORTER_DESCRIPTION, 'UTF-8');
                    }
                    $data['description'] = $this->clearDescriptionForGoogle($product_description);
                }
        
                $href = $this->link('product/product', 'path=&product_id='.$product['product_id']);
                    
                //OVERLOAD DESCRIPION
                $data['description'] = '';
                if ($product['fake_description'] && mb_strlen(strip_tags($product['fake_description'])) > 10) {
                    $data['description'] = $product['fake_description'];
                }
                if (!$data['description'] || mb_strlen(strip_tags($data['description'])) < 10) {
                    $data['description'] = $data['name'];
                }
                if ($attributes) {
                    $data['description'] .= ' <p>' . $this->formatProductAttributesForDescription($attributes).'</p>';
                }
                if ($ocfilters) {
                    $data['description'] .= ' <p>' . $this->formatProductOcFiltersForDescription($ocfilters).'</p>';
                }
                    
                    
                $data['description'] = $this->clearDescriptionForGoogle($data['description']);
                    
                if ($product['minimum'] > 1) {
                    if (isset($data['sales_notes'])) {
                        $data['sales_notes'].= ', минимальное кол-во заказа: '.$product['minimum'];
                    } else {
                        $data['sales_notes'] = 'Минимальное кол-во заказа: '.$product['minimum'];
                    }
                }
                    
                if (!$this->setOptionedOffer($data, $product, $shop_currency, $offers_currency, $decimal_place)) {
                    if ($this->type == 'rozetka') {
                        $rozetka_overprice = $this->getRozetkaOverprice($data['categoryId']);

                        if ($rozetka_overprice) {
                            $data['price']          = $data['price'] + ($data['price']/100)*$rozetka_overprice;

                            if (isset($data['price_old'])) {
                                $data['price_old']      = $data['price_old'] + ($data['price_old']/100)*$rozetka_overprice;
                            }

                            if (isset($data['price_promo'])) {
                                $data['price_promo']    = $data['price_promo'] + ($data['price_promo']/100)*$rozetka_overprice;
                            }
                        }
                    }

                    $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                        
                    if (isset($data['price_old'])) {
                        $data['price_old'] = number_format($this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    }
                    if (isset($data['price_promo'])) {
                        $data['price_promo'] = number_format($this->currency->convert($this->tax->calculate($data['price_promo'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
                    }
                        
                    //  $data['price_promo'] = str_replace('.0000', '', $data['price_promo']);
                        
                    if ($data['price'] > 0) {
                        $this->setOffer($data);
                    }
                }
                //}
                //***** 2. Есть Опция
            }
                //$this->categories = array_filter($this->categories, array($this, "filterCategory"));
                return true;
        }
            return false;
    }
        
        /**
            * Создает много элементов offer товарных предложений для разных опций цвет и размер товара
        */
    protected function setOptionedOffer($data, $product, $shop_currency, $offers_currency, $decimal_place)
    {
        $offers_array = array();

        $coptions = array();
        if ($this->color_options) {
            $coptions = $this->model_export_yandex_yml->getProductOptions($this->color_options, $product['product_id']);
        }
        $soptions = array();
        if ($this->size_options) {
            $soptions = $this->model_export_yandex_yml->getProductOptions($this->size_options, $product['product_id']);
        }
        if (!count($coptions) && !count($soptions)) {
            return false;
        }
    //++++ Цвета x Размеры для магазинов одежды ++++
        if (count($coptions)) {
            foreach ($coptions as $option) {
                $data_arr['stock_quantity'] = (int)$option['quantity'];
            //Если в опциях кол-во равно 0, то в OpenCart эта опция не показывается совсем, хотя она может быть просто не быть в наличии
                if ($option['subtract'] && ($option['quantity'] <= 0)) {
                //continue;
                }



                $oct_images = $this->model_catalog_product->getProductImagesByOptionValueId($product['product_id'], array($option['option_value_id']));
                if ($option['o_v_image']) {
                    $data['picture'] = array($this->prepareImage($option['o_v_image']));
                }

                if ($oct_images) {
                    foreach ($oct_images as $oct_image) {
                        if ($oct_image['image'] != $option['o_v_image']) {
                            if (file_exists(DIR_IMAGE . $oct_image['image'])) {
                                $data['picture'][] = $this->prepareImage($oct_image['image']);
                            }
                        }
                    }
                }


                $data_arr = $data;
                if (($this->optioned_name == 'short') || ($this->optioned_name == 'long')) {
                    $data_arr['name'].= ', '.$option['option_name'].' '.$option['name'];
                } else {
                    $data_arr['name'].= ' '.$this->changeColor($option['name']);
                }

            //$data_arr['param'][] = array('name'=>'Цвет', 'value'=>$option['name']);
                $data_arr['param'][] = array('name'=>$option['option_name'], 'value'=>$this->changeColor($option['name']));
                $data_arr['group_id'] = $product['product_id'];
                $data_arr['option_value_id'] = $option['option_value_id'];
                $data_arr['available'] = ($option['quantity'] > 0);
                if ($option['price_prefix'] == '+') {
                    $data_arr['price']+= $option['price'];
                    if (isset($data_arr['price_old'])) {
                        $data_arr['price_old']+= $option['price'];
                    }
                } elseif ($option['price_prefix'] == '-') {
                    $data_arr['price']-= $option['price'];
                    if (isset($data_arr['price_old'])) {
                        $data_arr['price_old']-= $option['price'];
                    }
                } elseif ($option['price_prefix'] == '=') {
                    $data_arr['price'] = $option['price'];
                }
                //  $data_arr = $this->setOptionedWeight($data_arr, $option);
                $data_arr['url'] .= '?option_id='.$option['product_option_value_id'];


                if (strlen($option['sku'])) {
                    $data_arr['rec'] = $option['sku'];
                    $data_arr['option_sku'] = $option['sku'];
                }
                if (strlen($option['color_image'])) {
                    $data_arr['picture'] = array($this->prepareImage($option['color_image']));
                }

                $data_arr['stock_quantity'] = (int)$option['quantity'];

                $offers_array[] = $data_arr;
            }
        } else {
            $data['group_id'] = $product['product_id'];
            $offers_array[] = $data;
        }
    // Размеры
        foreach ($offers_array as $i => $data) {
            if ($this->type == 'rozetka') {
                $rozetka_overprice = $this->getRozetkaOverprice($data['categoryId']);

                if ($rozetka_overprice) {
                    $data['price']          = $data['price'] + ($data['price']/100)*$rozetka_overprice;

                    if (isset($data['price_old'])) {
                        $data['price_old']      = $data['price_old'] + ($data['price_old']/100)*$rozetka_overprice;
                    }

                    if (isset($data['price_promo'])) {
                        $data['price_promo']    = $data['price_promo'] + ($data['price_promo']/100)*$rozetka_overprice;
                    }
                }
            }

            $data['id'] = $data['group_id']
            .(isset($data['option_value_id']) ? str_pad($data['option_value_id'], 7, '0', STR_PAD_LEFT) : '');
            $data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');

            if (isset($data['price_old'])) {
                $data['price_old'] = number_format($this->currency->convert($this->tax->calculate($data['price_old'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
            }

            if (isset($data['price_promo'])) {
                $data['price_promo'] = number_format($this->currency->convert($this->tax->calculate($data['price_promo'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
            }

            if ($this->type == 'rozetka' /* && $data['code'] == '0500029994' */) {
                    $prefix = '';
                    $prefixed = false;

                if (count($exploded = explode('-', $data['model'])) == 2) {
                    if (strpos($data['code'], $exploded[1]) === 0) {
                        $prefix = $exploded[1];

                        $data['code'] = $prefix . $data['option_sku'];
                        $prefixed = true;
                    }
                }

                if (!$prefixed && $data['option_sku']) {
                    $data['code'] = $data['option_sku'];
                }
            }

            if ($data['price'] > 0) {
                $this->setOffer($data);
            }
        }
        return true;
    //---- Цвета x Размеры для магазинов одежды ----
    }
        
        /**
            * Меняет аттрибут веса товара в зависимости от опции
        */
    protected function setOptionedWeight($product, $option)
    {
        if (isset($option['weight']) && isset($option['weight_prefix'])) {
            foreach ($product['param'] as $i => $param) {
                if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
                    if ($option['weight_prefix'] == '+') {
                        $product['param'][$i]['value']+= $option['weight'];
                    } elseif ($option['weight_prefix'] == '-') {
                        $product['param'][$i]['value']-= $option['weight'];
                    }
                    break;
                }
            }
        }
        return $product;
    }

        /**
            * Подготовка данных о фотографии
        */
    protected function prepareImage($image)
    {
        if ((strpos($image, 'http://') === 0) || (strpos($image, 'https://') === 0)) {
            return $image;
        }
        if (is_file(DIR_IMAGE . $image)) {
            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $image);
            if ($width_orig < 600 || $height_orig < 600 || !$this->ORIGINAL_IMAGES) {
                return $this->model_tool_image->resize($image, $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
            } else {
                $parts = explode('/', $image);
                $new_url = implode('/', array_map('rawurlencode', $parts));
                return HTTP_IMAGE . $new_url;
            }
        }
        return false;
    }

        /**
            * Методы формирования YML
        */

        /**
            * Формирование массива для элемента shop описывающего магазин
            *
            * @param string $name - Название элемента
            * @param string $value - Значение элемента
        */
    protected function setShop($name, $value)
    {
        $allowed = array('name', 'company', 'url', 'phone', 'platform', 'version', 'agency', 'email');
        if (in_array($name, $allowed)) {
            $this->shop[$name] = $this->prepareField($value);
        }
    }

    protected function setCurrency($id, $rate = 'CBRF', $plus = 0)
    {
        $allow_id = array('RUR', 'RUB', 'USD', 'BYR', 'KZT', 'EUR', 'UAN', 'UAH');
        if (!in_array($id, $allow_id)) {
            return false;
        }
        $allow_rate = array('CBRF', 'NBU', 'NBK', 'CB');
        if (in_array($rate, $allow_rate)) {
            $plus = str_replace(',', '.', $plus);
            if (is_numeric($plus) && $plus > 0) {
                $this->currencies[] = array(
                    'id'=>$this->prepareField(strtoupper($id)),
                    'rate'=>$rate,
                    'plus'=>(float)$plus
                );
            } else {
                $this->currencies[] = array(
                    'id'=>$this->prepareField(strtoupper($id)),
                    'rate'=>$rate
                );
            }
        } else {
            $rate = str_replace(',', '.', $rate);
            if (!(is_numeric($rate) && $rate > 0)) {
                return false;
            }
            $this->currencies[] = array(
                'id'=>$this->prepareField(strtoupper($id)),
                'rate'=>(float)$rate
            );
        }

        return true;
    }

        /**
            * Категории товаров
            *
            * @param string $name - название рубрики
            * @param int $id - id рубрики
            * @param int $parent_id - id родительской рубрики
            * @return bool
        */
    protected function setCategory($name, $id, $parent_id = 0)
    {
        $id = (int)$id;
        if ($id < 1 || trim($name) == '') {
            return false;
        }

        if ((int)$parent_id > 0) {
            $this->categories[$id] = array(
                'id'=>$id,
                'parentId'=>(int)$parent_id,
                'name'=>$this->prepareField($name)
            );
        } else {
            $this->categories[$id] = array(
                'id'=>$id,
                'name'=>$this->prepareField($name)
            );
        }

        return true;
    }

        /**
            * Товарные предложения
            *
            * @param array $data - массив параметров товарного предложения
        */
    protected function setOffer($data)
    {
        if ($data['price'] <= $this->config->get($this->CONFIG_PREFIX.'pricefrom')) {
            return;
        }

        $offer = array();

        $attributes = array('id', 'type', 'available', 'bid', 'cbid', 'param', 'group_id', 'accessory');
        $attributes = array_intersect_key($data, array_flip($attributes));

        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'id':
                    $offer['id'] = $value;
                    break;
                case 'bid':
                case 'cbid':
                case 'group_id':
                    $value = (int)$value;
                    if ($value > 0) {
                            $offer[$key] = $value;
                    }
                    break;

                case 'type':
                    if (in_array($value, array('vendor.model', 'book', 'audiobook', 'artist.title', 'tour', 'ticket', 'event-ticket'))) {
                            $offer['type'] = $value;
                    }
                    break;

                case 'available':
                    $offer['available'] = ($value ? 'true' : 'false');
                    break;

                case 'param':
                    if (is_array($value)) {
                            $offer['param'] = $value;
                    }
                    break;

                case 'accessory':
                    if (is_array($value)) {
                            $offer['accessory'] = $value;
                    }
                    break;

                default:
                    break;
            }
        }

        $type = isset($offer['type']) ? $offer['type'] : '';

        $allowed_tags = array('url'=>0, 'buyurl'=>0, 'price'=>1, 'price_promo'=>0, 'stock_quantity'=>0, 'price_old'=>0, 'wprice'=>0, 'currencyId'=>1, 'xCategory'=>0, 'categoryId'=>1, 'market_category'=>0, 'picture'=>0, 'store'=>0, 'pickup'=>0, 'delivery'=>0, 'deliveryIncluded'=>0, 'local_delivery_cost'=>0, 'orderingTime'=>0);

        switch ($type) {
            case 'vendor.model':
                $allowed_tags = array_merge($allowed_tags, array('typePrefix'=>0, 'vendor'=>1, 'vendorCode'=>0, 'model'=>1, 'provider'=>0, 'tarifplan'=>0));
                break;

            case 'book':
                $allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'binding'=>0, 'page_extent'=>0, 'table_of_contents'=>0));
                break;

            case 'audiobook':
                $allowed_tags = array_merge($allowed_tags, array('author'=>0, 'name'=>1, 'publisher'=>0, 'series'=>0, 'year'=>0, 'ISBN'=>0, 'volume'=>0, 'part'=>0, 'language'=>0, 'table_of_contents'=>0, 'performed_by'=>0, 'performance_type'=>0, 'storage'=>0, 'format'=>0, 'recording_length'=>0));
                break;

            case 'artist.title':
                $allowed_tags = array_merge($allowed_tags, array('artist'=>0, 'title'=>1, 'year'=>0, 'media'=>0, 'starring'=>0, 'director'=>0, 'originalName'=>0, 'country'=>0));
                break;

            case 'tour':
                $allowed_tags = array_merge($allowed_tags, array('worldRegion'=>0, 'country'=>0, 'region'=>0, 'days'=>1, 'dataTour'=>0, 'name'=>1, 'hotel_stars'=>0, 'room'=>0, 'meal'=>0, 'included'=>1, 'transport'=>1, 'price_min'=>0, 'price_max'=>0, 'options'=>0));
                break;

            case 'event-ticket':
                $allowed_tags = array_merge($allowed_tags, array('name'=>1, 'place'=>1, 'hall'=>0, 'hall_part'=>0, 'date'=>1, 'is_premiere'=>0, 'is_kids'=>0));
                break;

            default:
                $allowed_tags = array_merge($allowed_tags, array('name'=>1, 'vendor'=>0, 'vendorCode'=>0));
                break;
        }

        $allowed_tags = array_merge($allowed_tags, array('aliases'=>0, 'additional'=>0, 'description'=>0, 'sales_notes'=>0, 'promo'=>0, 'manufacturer_warranty'=>0, 'country_of_origin'=>0, 'downloadable'=>0, 'adult'=>0, 'barcode'=>0, 'rec'=>0, 'code'=>0, 'model'=>0, 'manufacturer_warranty'=>0));

        $required_tags = array_filter($allowed_tags);

        if (sizeof(array_intersect_key($data, $required_tags)) != sizeof($required_tags)) {
            return;
        }

        $data = array_intersect_key($data, $allowed_tags);
    //      if (isset($data['tarifplan']) && !isset($data['provider'])) {
    //          unset($data['tarifplan']);
    //      }

        $allowed_tags = array_intersect_key($allowed_tags, $data);

    // Стандарт XML учитывает порядок следования элементов,
    // поэтому важно соблюдать его в соответствии с порядком описанным в DTD
        $offer['data'] = array();
        foreach ($allowed_tags as $key => $value) {
            if (!isset($data[$key])) {
                continue;
            }
            if (is_array($data[$key])) {
                foreach ($data[$key] as $i => $val) {
                    $offer['data'][$key][$i] = $this->prepareField($val);
                }
            } else {
                $offer['data'][$key] = $this->prepareField($data[$key]);
            }
        }

        $this->offers[] = $offer;
    }

        /**
            * Формирование YML файла
            *
            * @return string
        */
    protected function getColourName($colour)
    {
    }

    protected function getYml()
    {
        $yml  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
        $yml .= '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol;
        $yml .= '<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol;
        $yml .= '<shop>' . $this->eol;

    // информация о магазине
        $yml .= $this->array2Tag($this->shop);

    // валюты
        $yml .= '<currencies>' . $this->eol;
        foreach ($this->currencies as $currency) {
        //$currency = str_replace("UAN","UAH", $currency);
            $yml .= $this->getElement($currency, 'currency');
        }
        $yml .= '</currencies>' . $this->eol;

    // категории
        $yml .= '<categories>' . $this->eol;
        foreach ($this->categories as $category) {
        /*$array_tag = array(
            'id' => $category['id'],
            'name' => $category['name']
            );
            if(isset($category['parentId'])){
            $array_tag = array_merge($array_tag, array('parentId' => $category['parentId']));
            }
            $tags_cat = $this->array2Tag($array_tag);*/
            $category_name = $category['name'];
            unset($category['name'], $category['export']);
            $yml .= $this->getElement($category, 'category', $category_name);
        }
        $yml .= '</categories>' . $this->eol;

    // товарные предложения
        $yml .= '<offers>' . $this->eol;
        foreach ($this->offers as $offer) {
            $tags = $this->array2Tag($offer['data']);
            unset($offer['data']);
            if (isset($offer['param'])) {
                $tags .= $this->array2Param($offer['param']);
                unset($offer['param']);
            }
            if (isset($offer['accessory'])) {
                $tags .= $this->array2Accessory($offer['accessory']);
                unset($offer['accessory']);
            }
            $yml .= $this->getElement($offer, 'offer', $tags);
        }
        $yml .= '</offers>' . $this->eol;

        $yml .= '</shop>';
        $yml .= '</yml_catalog>';

        return $yml;
    }

        /**
            * Вывод YML в файл
            * @param $fp дескриптор файла
        */
    protected function putYml($fp)
    {
        fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol
            .'<!DOCTYPE yml_catalog SYSTEM "shops.dtd">' . $this->eol
            .'<yml_catalog date="' . date('Y-m-d H:i') . '">' . $this->eol
            .'<shop>' . $this->eol);

    // информация о магазине
        fwrite($fp, $this->array2Tag($this->shop));

    // валюты
        fwrite($fp, '<currencies>' . $this->eol);
        foreach ($this->currencies as $currency) {
            fwrite($fp, $this->getElement($currency, 'currency'));
        }
        fwrite($fp, '</currencies>' . $this->eol
    // категории
            .'<categories>' . $this->eol);
        foreach ($this->categories as $category) {
            $category_name = $category['name'];
            unset($category['name'], $category['export']);
            fwrite($fp, $this->getElement($category, 'category', $category_name));
        }
        fwrite($fp, '</categories>' . $this->eol
    // товарные предложения
            .'<offers>' . $this->eol);
        foreach ($this->offers as $offer) {
            $tags = $this->array2Tag($offer['data']);
            unset($offer['data']);
            if (isset($offer['param'])) {
                $tags .= $this->array2Param($offer['param']);
                unset($offer['param']);
            }
            if (isset($offer['accessory'])) {
                $tags .= $this->array2Accessory($offer['accessory']);
                unset($offer['accessory']);
            }
            fwrite($fp, $this->getElement($offer, 'offer', $tags));
        }
        fwrite($fp, '</offers>' . $this->eol
            .'</shop>'
            .'</yml_catalog>');
        return true;
    }


        /**
            * Фрмирование элемента
            *
            * @param array $attributes
            * @param string $element_name
            * @param string $element_value
            * @return string
        */
    protected function getElement($attributes, $element_name, $element_value = '')
    {
        $retval = '<' . $element_name . ' ';
        foreach ($attributes as $key => $value) {
            $retval .= $key . '="' . $value . '" ';
        }
        $retval .= $element_value ? '>' . $this->eol . $element_value . '</' . $element_name . '>' : '/>';
        $retval .= $this->eol;

        return $retval;
    }

        /**
            * Преобразование массива в теги
            *
            * @param array $tags
            * @return string
        */
    protected function array2Tag($tags)
    {
        $retval = '';
        foreach ($tags as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $val) {
                    $retval .= '<' . $key . '>' . $val . '</' . $key . '>' . $this->eol;
                }
            } else {
                $retval .= '<' . $key . '>' . $value . '</' . $key . '>' . $this->eol;
            }
        }

        return $retval;
    }

        /**
            * Преобразование массива в теги параметров
            *
            * @param array $params
            * @return string
        */
    protected function array2Param($params)
    {
        $retval = '';
        foreach ($params as $param) {
            $retval .= '<param name="' . $this->prepareField($param['name']);
            if (isset($param['unit'])) {
                $retval .= '" unit="' . $this->prepareField($param['unit']);
            }
            $retval .= '">' . $this->prepareField($param['value']) . '</param>' . $this->eol;
        }

        return $retval;
    }

        /**
            * Преобразование массива в теги accessory
            *
            * @param array $params
            * @return string
        */
    protected function array2Accessory($rels)
    {
        $retval = '';
        foreach ($rels as $rel) {
            $retval .= '<accessory offer="' . $rel . '"/>';
        }

        return $retval;
    }

        /**
            * Подготовка текстового поля в соответствии с требованиями Яндекса
            * Запрещаем любые html-тэги, стандарт XML не допускает использования в текстовых данных
            * непечатаемых символов с ASCII-кодами в диапазоне значений от 0 до 31 (за исключением
            * символов с кодами 9, 10, 13 - табуляция, перевод строки, возврат каретки). Также этот
            * стандарт требует обязательной замены некоторых символов на их символьные примитивы.
            * @param string $text
            * @return string
        */
    protected function prepareField($field)
    {
        $field = htmlspecialchars_decode($field);
        $field = strip_tags($field, "<br><a>");
        $from = array('&', '>', '<', '\'', '&nbsp;');//'"',
        $to = array('&amp;', '&gt;', '&lt;', '&apos;', ' ');//'&quot;',
        $field = str_replace($from, $to, $field);
    /**
        if ($this->from_charset != 'windows-1251') {
        $field = iconv($this->from_charset, 'windows-1251//IGNORE', $field);
        }
        **/
        $field = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $field);

        return trim($field);
    }

    protected function getPath($category_id, $current_path = '')
    {
        if (isset($this->categories[$category_id])) {
            $this->categories[$category_id]['export'] = 1;

            if (!$current_path) {
                $new_path = $this->categories[$category_id]['id'];
            } else {
                $new_path = $this->categories[$category_id]['id'] . '_' . $current_path;
            }

            if (isset($this->categories[$category_id]['parentId'])) {
                return $this->getPath($this->categories[$category_id]['parentId'], $new_path);
            } else {
                return $new_path;
            }
        }
    }

    function filterCategory($category)
    {
        return isset($category['export']);
    }

    public function getProduct($allowed_categories, $blacklist_type, $blacklist, $out_of_stock_id, $vendor_required = true, $allowed_manufacturers = '', $with_related = false, $excludeNotCustomYml = true)
    {
        $sql_blacklist = '';
        if ($blacklist) {
            $sql_blacklist = " AND ".($blacklist_type == 'black' ? "NOT" : "")."(p.product_id IN (" . $this->db->escape($blacklist) . "))";
        }
        $date_begin = date('Y-m-d H:i:s', (strtotime('-10 day', strtotime(date('Y-m-d H:i:s'))) ));//***mf
            
        $sql = "SELECT
			p.*, pd.name, pd.description, pd.fake_description, pd.meta_description, m.name AS manufacturer, p2c.category_id, IFNULL(pd2.price, p.price) AS price, ps.price AS special, wcd.unit AS weight_unit"
        . ($with_related ? ", GROUP_CONCAT(DISTINCT CAST(pr.related_id AS CHAR) SEPARATOR ',') AS rel " : "") . "
			FROM " . DB_PREFIX . "product p
			JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id)"// AND p2c.main_category = '1'
        . ($vendor_required ? '' : ' LEFT') . " JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
			LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
			LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())
			LEFT JOIN " . DB_PREFIX . "product_discount pd2 ON (p.product_id = pd2.product_id) AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND pd2.date_start < NOW() AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())
			LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (p.weight_class_id = wcd.weight_class_id) AND wcd.language_id='" . (int)$this->config->get('config_language_id') . "'"
        . ($with_related ? "LEFT JOIN " . DB_PREFIX . "product_related pr ON p.product_id = pr.product_id" : "") . "
			WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
            
        $this->echoLine('[RZTKHTLN] Отбираем товары, исключение по флагу custom_yml_mf = ' . (int)$excludeNotCustomYml);
            
        if ($excludeNotCustomYml) {
                $sql .= " AND p.custom_yml_mf = '1'";
        }
            
                $sql .= "AND p.status = 1 AND p.archive = '0' GROUP BY p.product_id ORDER BY product_id";
            
                $query = $this->db->query($sql);
            
                $this->echoLine('[RZTKHTLN] Всего товаров отобрали ' . $query->num_rows);
            
                return $query->rows;
    }

    public function getRozetkaOverprice($category_id)
    {
        $query = $this->db->query("SELECT rozetka_overprice FROM " . DB_PREFIX . "category WHERE category_id='" . (int)$category_id . "'");

        return $query->row['rozetka_overprice'];
    }
        
    public function getProductCategory($product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id='" . (int)$product_id . "' order by (main_category=1) DESC LIMIT 1");
            
        return $query->row['category_id'];
    }
        
        /**
            * Определение единиц измерения по содержимому
            *
            * @param array $attr array('name'=>'Вес', 'value'=>'100кг')
            * @return array array('name'=>'Вес', 'unit'=>'кг', 'value'=>'100')
        */
    protected function detectUnits($attr)
    {
    //$matches = array();
        $attr['name'] = trim(strip_tags($attr['name']));
        $attr['value'] = trim(strip_tags($attr['value']));
        if (preg_match('/\(([^\)]+)\)$/mi', $attr['name'], $matches)) {
            $attr['name'] = trim(str_replace('('.$matches[1].')', '', $attr['name']));
            $attr['unit'] = trim($matches[1]);
        }
        return $attr;
    }

    protected function changeColor($color)
    {
        $color = str_replace("Белый", "White", $color);
        $color = str_replace("Бежевый", "Beige", $color);
        $color = str_replace("Нежно розовый", "Light pink", $color);
        $color = str_replace("Зеленый", "Green", $color);
        $color = str_replace("Кофейный", "Coffee", $color);
        $color = str_replace("Бронзовий", "Bronze", $color);
        $color = str_replace("Коричневый", "Brown", $color);
        $color = str_replace("Синий", "Blue", $color);
        $color = str_replace("Различные цвета", "Multicolor", $color);
        $color = str_replace("Золотой", "Gold", $color);
        $color = str_replace("Черная с красным", "Black with red", $color);
        $color = str_replace("Темно-синий", "Dark blue", $color);
        $color = str_replace("Бирюзовый", "Turquoise", $color);
        $color = str_replace("Кораловый", "Coral", $color);
        $color = str_replace("Темно-серый", "Dark grey", $color);
        $color = str_replace("Серебряный", "Silver", $color);
        $color = str_replace("Кремовый", "Cream", $color);
        $color = str_replace("Прозрачный", "Transparent", $color);
        $color = str_replace("Фиолетовый", "Purple", $color);
        $color = str_replace("Желтый", "Yellow", $color);
        $color = str_replace("Черная с синим", "Black with blue", $color);
        $color = str_replace("черно-красный", "black and red", $color);
        $color = str_replace("Серый", "Gray", $color);
        $color = str_replace("Розовый", "Pink", $color);
        $color = str_replace("Оранжевый", "Orange", $color);
        $color = str_replace("Голубой", "Blue", $color);
        $color = str_replace("Красный", "Red", $color);
        $color = str_replace("Черный", "Black", $color);
        $color = str_replace("Стальной", "Steel", $color);
        $color = str_replace("черный с белым", "black and white", $color);
        $color = str_replace("золотой с белым", "gold with white", $color);
        $color = str_replace("оливковый", "olive", $color);
        $color = str_replace("темно-коричневый", "dark brown", $color);
        return $color;
    }
}
