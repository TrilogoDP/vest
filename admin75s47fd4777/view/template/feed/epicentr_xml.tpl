<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

   <div class="page-header">
      <div class="container-fluid">
         <div class="pull-right">

            <button type="button" onclick="show_install()" title="Настройки синхронизации" class="btn btn-success"><i class="fa fa-gear"></i></button>


            <a href="<?php echo $data_feed; ?>" target="_blank"><button type="button" data-toggle="tooltip" title="Перейти к XML прайсу" class="btn btn-success"><i class="fa fa-list-alt"></i></button></a>

            <button type="button"  data-toggle="tooltip" title="<?php echo $button_save_and_stay; ?>" onClick="saveAndStay()" class="btn btn-info"><i class="fa fa-save"></i></button>

            <button type="submit" form="form-epicentr_xml" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>


            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
         </div>
         <h1><?php echo $heading_title; ?></h1>
         <br>
         <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
         </ul>
      </div>
   </div>
   <div class="container-fluid">
      <?php if ($error_warning) { ?>
         <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
         </div>
      <?php } ?>
      <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
         <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <div class="panel panel-default">
         <div class="panel-heading">
               <div class="panel-title text-left col-sm-8 col-xs-8"><i class="fa fa-gear"></i> <?php echo $text_edit; ?></div>


            <? if($new){ ?>
               <div class=""><div class="licence no"><i class="fa fa-times"></i> <span class="hidden-xs">Есть обновления</span></div></div>
            <? } else {?>
               <div class=""><div class="licence yes"><i class="fa fa-check"></i> <span class="hidden-xs">Актуальная версия</span></div></div>
            <? } ?>


            <? if($l){ ?>
               <div class=""><div class="licence yes"><i class="fa fa-check"></i> <span class="hidden-xs">Лицензия</span></div></div>
            <? } else {?>
               <div class=""><div class="licence no"><i class="fa fa-times"></i> <span class="hidden-xs">Нет лицензии</span></div></div>
            <? } ?>
         </div>
         <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-epicentr_xml" class="form-horizontal">
               <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab-general" data-toggle="tab">Основные</a></li>
                  <li><a href="#tab-products" data-toggle="tab">Товары</a></li>
                  <li><a href="#tab-categories" data-toggle="tab">Категории</a></li>
                  <li><a href="#tab-options" data-toggle="tab">Опции</a></li>
                  <li><a href="#tab-attributes" data-toggle="tab">Характеристики</a></li>
                  <li class="hidden"><a href="#tab-fastnames" data-toggle="tab">Быстрое переименование</a></li>
                  <li class="hidden"><a href="#tab-cache" data-toggle="tab">Кеширование</a></li>

               </ul>
               <div class="tab-content">
                  <div class="tab-pane active" id="tab-general">
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_status" id="input-status" class="form-control">
                              <?php if ($epicentr_xml_status) { ?>
                              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                              <option value="0"><?php echo $text_disabled; ?></option>
                              <?php } else { ?>
                              <option value="1"><?php echo $text_enabled; ?></option>
                              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_shopname"><span data-toggle="tooltip" title="<?php echo $help_shopname; ?>"><?php echo $entry_shopname; ?></span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_shopname" value="<?php echo $epicentr_xml_shopname; ?>" placeholder="Не более 20 символов" id="input-epicentr_xml_shopname" class="form-control" maxlength="20"/>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_company"><span data-toggle="tooltip" title="<?php echo $help_company; ?>"><?php echo $entry_company; ?></span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_company" value="<?php echo $epicentr_xml_company; ?>" placeholder="Полное название компании" id="input-epicentr_xml_company" class="form-control" />
                        </div>
                     </div>
                     <div class="form-group hidden">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_telephone"><span data-toggle="tooltip" title="Если пусто, то будет выводиться основной номер из настроек магазина">Номер телефона</span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_telephone" value="<?php echo $epicentr_xml_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-epicentr_xml_telephone" class="form-control" />
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_country"><span data-toggle="tooltip" title="Это значение будет выставлено тем товарам, у которых в настройках категории и производителя не указана страна производителя">Страна производителя</span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_country" value="<?php echo $epicentr_xml_country; ?>" placeholder="Например: Украина" id="input-epicentr_xml_country" class="form-control" />
                        </div>
                     </div>


                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="epicentr_xml_lang-ru"><span data-toggle="tooltip" title="Язык выгрузки товаров">Какой язык русский?</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_language_ru" id="epicentr_xml_language_ua" class="form-control">
                              <option value="0" selected="selected">-Выбор-</option>
                              <?php foreach ($languages as $language) { ?>
                              <?php if ($language['language_id'] == $epicentr_xml_language_ru) { ?>
                              <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo  $language['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $language['language_id']; ?>"><?php echo  $language['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                           </select>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="epicentr_xml_lang-ua"><span data-toggle="tooltip" title="Язык выгрузки товаров">Какой язык украинский?</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_language_ua" id="epicentr_xml_language_ua" class="form-control">
                              <option value="0" selected="selected">-Выбор-</option>
                              <?php foreach ($languages as $language) { ?>
                              <?php if ($language['language_id'] == $epicentr_xml_language_ua) { ?>
                              <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo  $language['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $language['language_id']; ?>"><?php echo  $language['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                           </select>
                        </div>
                     </div>


                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_charset"><span data-toggle="tooltip" title="По умолчанию Windows-1251, но иногда нужно переключиться на UTF-8">Кодировка фида</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_charset" id="input-charset" class="form-control">
                              <?php if ($epicentr_xml_charset) { ?>
                              <option value="0">Windows-1251</option>
                              <option value="1" selected="selected">UTF-8</option>
                              <?php } else { ?>
                              <option value="0" selected="selected">Windows-1251</option>
                              <option value="1">UTF-8</option>
                              <?php } ?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-data-feed"><?php echo $entry_data_feed; ?></label>
                        <div class="col-sm-10">
                           <textarea rows="1" readonly id="input-data-feed" class="form-control" style="resize: none;"><?php echo $data_feed; ?></textarea>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-products">

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="epicentr_xml_currency"><span data-toggle="tooltip" title="Валюта должна быть UAH">Выберите валюту</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_currency" id="epicentr_xml_currency" class="form-control">
                              <?php foreach ($currencies as $currency) { ?>
                              <?php if ($currency['code'] == $epicentr_xml_currency) { ?>
                              <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $currency['code']; ?>"><?php echo '(' . $currency['code'] . ') ' . $currency['title']; ?></option>
                              <?php } ?>
                              <?php } ?>
                           </select>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_coeff"><span data-toggle="tooltip" title="1 - 100% , 1.1 - 110% , 0.8 - 80%">Наценка на товары (коэффициент), если у категории не указан своё значение</span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_coeff" value="<?php echo $epicentr_xml_coeff; ?>" placeholder="разделитель - точка" id="input-epicentr_xml_coeff" class="form-control" />
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_images"><span data-toggle="tooltip" title="Не более 14. Главное фото не учитывается, а значит выводить будет на 1 фото больше от введенного числа">Количество выводимых фото</span></label>
                        <div class="col-sm-10">
                           <input type="text" name="epicentr_xml_images" value="<?php echo $epicentr_xml_images; ?>" placeholder="Рекомендуется не более 15, иногда не более 10 фотографий на товар" id="input-epicentr_xml_coeff" class="form-control" />
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_zero"><span data-toggle="tooltip" title="Если на ваши фото наложен вадяной знак, тогда нужно выводить оригиналы">Ссылки на изображения</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_img" id="epicentr_xml_img" class="form-control">
                              <option value="1" <?php if ($epicentr_xml_img == 1) { ?> selected="selected" <?php } ?> >Ссылки на оригиналы</option>
                              <option value="0" <?php if ($epicentr_xml_img == 0) { ?> selected="selected" <?php } ?> >Ссылки на кеш (умолчания 600х500)</option>
                           </select>
                        </div>
                      </div>
                        <?php if($epicentr_xml_img == 1){ ?>
                        <style> .asen_img { display: none; } </style>
                        <?php } ?>
                        <div class="form-group asen_img">
                           <label class="col-sm-2 control-label">Размер кеша</label>
                           <div class="col-sm-5 no-padding">
                              
                                 <label class="col-sm-12 control-label text-left" for="input-epicentr_xml_width"><span data-toggle="tooltip" title="Если пусто, то ширина = 600px">Ширина</span></label>
                                 <div class="col-sm-12">
                                    <input type="text" name="epicentr_xml_width" value="<?php echo $epicentr_xml_width; ?>" placeholder="Только число" id="input-epicentr_xml_width" class="form-control" />
                                 </div>
                            
                           </div>
                           <div class="col-sm-5 no-padding">
                             
                                 <label class="col-sm-12 control-label text-left" for="input-epicentr_xml_height"><span data-toggle="tooltip" title="Если пусто, то высота = 500px">Высота</span></label>
                                 <div class="col-sm-12">
                                    <input type="text" name="epicentr_xml_height" value="<?php echo $epicentr_xml_height; ?>" placeholder="Только число" id="input-epicentr_xml_height" class="form-control" />
                                 </div>
                          
                           </div>
                        </div>
                    
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_zero">Вывод товаров с остатком = 0</label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_zero" id="epicentr_xml_zero" class="form-control">
                              <option value="1" <?php if ($epicentr_xml_zero == 1) { ?> selected="selected" <?php } ?> >Да</option>
                              <option value="0" <?php if ($epicentr_xml_zero == 0) { ?> selected="selected" <?php } ?>>Нет</option>
                           </select>
                        </div>
                     </div>
                     <?php if($epicentr_xml_zero == 0){ ?>
                     <style>
                        .asen_stats {
                        display: none;
                        }
                     </style>
                     <?php } ?>
                     <!--
                        <div class="form-group ">
                             <label class="col-sm-2 control-label" for="epicentr_xml_in_stock"><span data-toggle="tooltip" title="<?php echo $help_in_stock; ?>"><?php echo $entry_in_stock; ?></span></label>
                             <div class="col-sm-10">
                               <select name="epicentr_xml_in_stock" id="epicentr_xml_in_stock" class="form-control">
                           <?php foreach ($stock_statuses as $stock_status) { ?>
                                     <?php if ($stock_status['stock_status_id'] == $epicentr_xml_in_stock) { ?>
                                     <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                                     <?php } else { ?>
                                     <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                                     <?php } ?>
                                     <?php } ?>
                           </select>
                             </div>
                           </div>
                        -->
                     <div class="form-group asen_stats">
                        <label class="col-sm-2 control-label" for="epicentr_xml_out_of_stock"><span data-toggle="tooltip" title="<?php echo $help_out_of_stock; ?>"><?php echo $entry_out_of_stock; ?></span></label>
                        <div class="col-sm-10">
                           <div class="well well-sm" style="height:200px; overflow: auto;">
                              <?php foreach ($stock_statuses as $stock_status) { ?>
                              <div class="checkbox">
                                 <label>
                                 <?php if (in_array($stock_status['stock_status_id'], $epicentr_xml_out_of_stock)) { ?>
                                 <input type="checkbox" name="epicentr_xml_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" checked="checked" />
                                 <?php echo $stock_status['name']; ?>
                                 <?php } else { ?>
                                 <input type="checkbox" name="epicentr_xml_out_of_stock[]" value="<?php echo $stock_status['stock_status_id']; ?>" />
                                 <?php echo $stock_status['name']; ?>
                                 <?php } ?>
                                 </label>
                              </div>
                              <?php } ?>
                           </div>
                           <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                        </div>
                     </div>


                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml">Вывод описания</label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_descript" id="epicentr_xml_descript" class="form-control">
                              <option value="1" <?php if ($epicentr_xml_descript == 1) { ?> selected="selected" <?php } ?> >С закодированным HTML-кодом</option>
                              <option value="0" <?php if ($epicentr_xml_descript == 0) { ?> selected="selected" <?php } ?>>С валидным HTML-кодом</option>
                              <option value="2" <?php if ($epicentr_xml_descript == 2) { ?> selected="selected" <?php } ?>>С очисткой от HTML</option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-categories">

                     <div class="form-group col-sm-12">
                        <label class="col-sm-12 control-label text-left" for="input-epicentr_xml_zero"><span data-toggle="tooltip" title="Товары из категорий, товары с производителями или товары из категории с определенным производителем">Тип выгрузки товаров</span></label>
                        <div class="col-sm-12">
                           <select name="epicentr_xml_feedtype" id="epicentr_xml_feedtype" class="form-control">
                              <option value="0" <? if($epicentr_xml_feedtype == 0){ ?> selected="selected" <?}?> >По категориям</option>
                              <option value="1" <? if($epicentr_xml_feedtype == 1){ ?> selected="selected" <?}?> >По производителям</option>
                              <option value="2" <? if($epicentr_xml_feedtype == 2){ ?> selected="selected" <?}?> >По категориям + произвидителям (товары из X категорий с производителем Y)</option>
                              <option value="3" <? if($epicentr_xml_feedtype == 3){ ?> selected="selected" <?}?> >Выбранные товары</option>
                           </select>
                        </div>
                     </div>

                     <div class="form-group col-sm-6 col-xs-12">
                        <label class="col-sm-12 control-label text-left"><span data-toggle="tooltip" title="Выберите категории для выгрузки"><?php echo $entry_category; ?></span></label>
                        <div class="col-sm-12">
                           <div class="well well-sm" style="height:350px; overflow: auto;">
                              <?php foreach ($categories as $category) { ?>
                              <div class="checkbox">
                                 <label>
                                 <?php if (in_array($category['category_id'], $epicentr_xml_categories)) { ?>
                                 <input type="checkbox" name="epicentr_xml_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                                 <?php echo $category['name']; ?>
                                 <?php } else { ?>
                                 <input type="checkbox" name="epicentr_xml_categories[]" value="<?php echo $category['category_id']; ?>" />
                                 <?php echo $category['name']; ?>
                                 <?php } ?>
                                 </label>
                              </div>
                              <?php } ?>
                           </div>
                           <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                        </div>
                     </div>

                     <div class="form-group col-sm-6 col-xs-12">
                        <label class="col-sm-12 control-label text-left"><span data-toggle="tooltip" title="Выберите производителей для выгрузки их товаров">Производители</span></label>
                        <div class="col-sm-12">
                           <div class="well well-sm" style="height:350px; overflow: auto;">
                              <?php foreach ($manufacturers as $manufacturer) { ?>
                              <div class="checkbox">
                                 <label>
                                 <?php if (in_array($manufacturer['manufacturer_id'], $epicentr_xml_manufacturers)) { ?>
                                 <input type="checkbox" name="epicentr_xml_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                                 <?php echo $manufacturer['name']; ?>
                                 <?php } else { ?>
                                 <input type="checkbox" name="epicentr_xml_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                                 <?php echo $manufacturer['name']; ?>
                                 <?php } ?>
                                 </label>
                              </div>
                              <?php } ?>
                           </div>
                           <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                        </div>
                     </div>


              <div class="form-group col-xs-12">
                <label class="col-sm-12 text-left" for="input-products">Выбрать товары для выгрузки</label>
                <div class="col-sm-12">
                  <input type="text" name="epicentr_xml_product" value="" placeholder="Начните воодить название товара" id="input-epicentr_xml_products" class="form-control" />
                  <div id="product-epicentr_xml_products" class="well well-sm" style="height: 350px; overflow: auto;">
                    <?php foreach ($xml_products as $epicentr_xml_product) { ?>
                    <div id="product-epicentr_xml_products<?php echo $epicentr_xml_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $epicentr_xml_product['name']; ?>
                      <input type="hidden" name="epicentr_xml_products[]" value="<?php echo $epicentr_xml_product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>




                  </div>
                  <div class="tab-pane" id="tab-options">

                     <div class="form-group">
                        <label class="col-sm-12 control-label text-left" for="input-epicentr_xml_zero"><span data-toggle="tooltip" title="Если товар имеет опции, то ему будут созданы аналогичные товары с заменой цены/названия/количества">Разбивать товары по опциям?</span></label>
                        <div class="col-sm-12">
                           <select name="epicentr_xml_option" id="epicentr_xml_option" class="form-control">
                              <option value="1" <?php if ($epicentr_xml_option == 1) { ?> selected="selected" <?php } ?> >Да</option>
                              <option value="0" <?php if ($epicentr_xml_option == 0) { ?> selected="selected" <?php } ?> >Нет</option>
                           </select>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-12 control-label text-left" for="input-epicentr_xml_format"><span data-toggle="tooltip" title="Доступные элементы: {name}, {brand}, {model}, {option}, {sku}">Формат вывода названия товаров</span></label>
                        <div class="col-sm-12">
                           <input type="text" name="epicentr_xml_format" value="<?php echo $epicentr_xml_format; ?>" placeholder="Формат розетки {name} {brand} {model} {option} {sku}" id="input-epicentr_xml_format" class="form-control" />
                           <br>
                           <span>Если формат не указан, то будет выводиться просто название {name}</span>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-12 text-left" for="input-epicentr_xml">Вывод опций с нулевым остатком</label>
                        <div class="col-sm-12">
                           <select name="epicentr_xml_options_null" id="epicentr_xml_descript" class="form-control">
                              <option value="0" <?php if ($epicentr_xml_options_null == 0) { ?> selected="selected" <?php } ?>>Нет</option>
                              <option value="1" <?php if ($epicentr_xml_options_null == 1) { ?> selected="selected" <?php } ?> >Да</option>
                           </select>
                        </div>
                     </div>


                     <div class="form-group hidden">
                        <label class="col-sm-12 text-left" for="input-epicentr_xml">
                              <span data-toggle="tooltip" title="Выбирается один раз и навсегда. Необходимо сменить в зависимости от использования этой функции ранее или если розетка не находит старые опциональные товары">Формирование ID для опциональных товаров</span></label>
                        <div class="col-sm-12">
                           <select name="epicentr_xml_options_id_form" id="epicentr_xml_descript" class="form-control">
                              <option value="0" <?php if ($epicentr_xml_options_id_form == 0) { ?> selected="selected" <?php } ?>>option_value_id (начиная с версии 2.5)</option>
                              <option value="1" <?php if ($epicentr_xml_options_id_form == 1) { ?> selected="selected" <?php } ?> >product_option_value_id (до версии 2.4.2)</option>
                           </select>
                        </div>
                     </div>


                     <div class="form-group">
                        <label class="col-sm-12 control-label text-left">Опции, которые будут выгружаться. Допускаются опции типа 'select', 'checkbox' и 'radio'</label>
                        <div class="col-sm-12">
                           <div class="well well-sm" style="height:350px; overflow: auto;">
                              <?php foreach ($options as $option) { ?>
                              <div class="checkbox">
                                 <label>
                                 <?php if (in_array($option['option_id'], $epicentr_xml_options)) { ?>
                                 <input type="checkbox" name="epicentr_xml_options[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
                                 <?php echo $option['name']; ?>
                                 <?php } else { ?>
                                 <input type="checkbox" name="epicentr_xml_options[]" value="<?php echo $option['option_id']; ?>" />
                                 <?php echo $option['name']; ?>
                                 <?php } ?>
                                 </label>
                              </div>
                              <?php } ?>
                           </div>
                           <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a style="cursor:default;" onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-attributes">
                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-Epicentr">Конструкция выгрузки атрибутов</label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_attrib_type" id="epicentr_xml_attrib_type" class="form-control">
                              <option value="0" <? if($epicentr_xml_attrib_type == 0){ ?> selected="selected" <?}?> >Атрибут -> значение атрибута</option>
                              <option value="1" <? if($epicentr_xml_attrib_type == 1){ ?> selected="selected" <?}?> >Группа атрибута -> Атрибут</option>
                           </select>
                        </div>
                     </div>

                     <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-epicentr_xml_attribchange"><span data-toggle="tooltip" title="Производить замену по правилам указанным ниже? Заменяются только имена атрибутов">Замена атрибутов</span></label>
                        <div class="col-sm-10">
                           <select name="epicentr_xml_attribchange" id="epicentr_xml_attribchange" class="form-control">
                              <option value="1" <?php if ($epicentr_xml_attribchange == 1) { ?> selected="selected" <?php } ?> >Включена</option>
                              <option value="0" <?php if ($epicentr_xml_attribchange == 0) { ?> selected="selected" <?php } ?> >Выключена</option>
                           </select>
                        </div>
                     </div>


                     <div class="form-group">
                        <div class="col-sm-2">
                           <ul class="nav nav-pills nav-stacked" id="groups">
                              <li class="t-header">Группы характеристик</li>
                              <?php foreach ($attribute_groups as $group) { ?>
                              <li>
                                 <a href="#tab-group<?php echo $group['attribute_group_id']; ?>" data-toggle="tab">
                                 <?php echo $group['name']; ?>
                                 </a>
                              </li>
                              <?php } ?>
                           </ul>
                        </div>
                        <div class="col-sm-10">
                           <div class="tab-content">
                              <?php foreach ($attribute_groups as $group) { ?>
                              <div class="tab-pane" id="tab-group<?php echo $group['attribute_group_id']; ?>">
                                 <div class="row asen_attr_head">
                                    <div class="col-sm-5 t-header"> Оригинальное название </div>
                                    <div class="col-sm-5 t-header"> Альтернативное название </div>
                                    <div class="col-sm-1 t-header"> Замена </div>
                                    <div class="col-sm-1 t-header"> Вывод </div>
                                 </div>
                                 <?php foreach ($attributes as $attribute) {  ?>
                                 <?php if ($attribute['attribute_group_id'] == $group['attribute_group_id']) { 

                                    $alt_name    = $iattributes[$group['attribute_group_id']][$attribute['attribute_id']]['name'];
                                    $alt_status  = (int)$iattributes[$group['attribute_group_id']][$attribute['attribute_id']]['status'];
                                    $alt_active  = $iattributes[$group['attribute_group_id']][$attribute['attribute_id']]['active'];
                                    
                                    $checked_status = ($alt_status == 1) ? 'checked="checked"' : '' ; 
                                    $checked_active = ($alt_active == 1 || $alt_active == '') ? 'checked="checked"' : '' ; 
                                    ?>
                                 <div class="row asen_attr_row form-group">
                                    <div class="col-sm-5">
                                       <?php echo $attribute['name']; ?>
                                    </div>
                                    <div class="col-sm-5">
                                       <input type="text" name="epicentr_xml_attrib[<?php echo $group['attribute_group_id']; ?>][<?php echo $attribute['attribute_id']; ?>][name]"  class="form-control" value="<? echo $alt_name; ?>" />
                                    </div>

                                    <div class="col-sm-1 text-center">
                                       <input type="hidden" name="epicentr_xml_attrib[<?php echo $group['attribute_group_id']; ?>][<?php echo $attribute['attribute_id']; ?>][status]" value="0" />
                                       <input type="checkbox" name="epicentr_xml_attrib[<?php echo $group['attribute_group_id']; ?>][<?php echo $attribute['attribute_id']; ?>][status]" value="1" <? echo $checked_status; ?>/>
                                    </div>

                                    <div class="col-sm-1 text-center">
                                       <input type="hidden" name="epicentr_xml_attrib[<?php echo $group['attribute_group_id']; ?>][<?php echo $attribute['attribute_id']; ?>][active]" value="0" />
                                       <input type="checkbox" name="epicentr_xml_attrib[<?php echo $group['attribute_group_id']; ?>][<?php echo $attribute['attribute_id']; ?>][active]" value="1" <? echo $checked_active; ?>/>
                                    </div>


                                 </div>
                                 <?php } ?>
                                 <?php } ?>
                              </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>


                  <div class="tab-pane" id="tab-fastnames">


                     <div class="form-group">
                        <div class="col-sm-3">
                           <ul class="nav nav-pills nav-stacked" id="categories">
                              <li class="t-header">Категории</li>
                              <?php foreach ($categories as $category) { ?>
                              <li>
                                 <a href="#tab-category<?php echo $category['category_id']; ?>" id="<?php echo $category['category_id']; ?>" data-toggle="tab" class="load-products">
                                 <?php echo $category['name']; ?>
                                 </a>
                              </li>
                              <?php } ?>
                           </ul>
                        </div>
                        <div class="col-sm-9">
                           <div class="tab-content">
                                 <div class="alert alert-warning text-center">Выберите категорию для начала работы</div>
                           </div>
                     </div>
                  </div>

               </div>


            <div class="tab-pane" id="tab-cache">

                   тут будет кеширование

            </div>



            <div class="tab-pane" id="tab-install">

               <div class="asen_heading">Базовые установки модуля</div>
               <br>


               <!-- MENU -->
                  <div class="col-sm-3">
                     
                     <ul class="nav nav-pills nav-stacked" id="installs">
                        <li class="t-header">МЕНЮ</li>
                        <li> <a href="#tab-install-db"   data-toggle="tab"><i class="fa fa-tasks"></i> Проверка базы данных</a>   </li>
                        <li> <a href="#tab-install-cats" data-toggle="tab"><i class="fa fa-download"></i> Импорт дерева каталога</a> </li>
                     </ul>
                  </div>

               <!-- CONTENT -->
               <div class="col-sm-9 tab-content">
                  


               <div class="tab-pane" id="tab-install-db">

                  <div class="col-sm-12">
                     <label  class="col-sm-12 panel-heading" for="input-Epicentr">Проверка базы данных</label>

                     <? if(empty($epicentr_xml_shopname)){ ?>
                        <div class="btn col-sm-12 col-xs-12 btn-success" id="db_install" onClick="start_install_db();">Выполнить установку базы</div>
                     <? } else { ?>
                        <div class="btn col-sm-12 col-xs-12 btn-success" id="db_check"   onClick="start_checking_db();">Выполнить проверку базы</div>
                     <? } ?>


                     <div class="checked_status"></div>
                     <div class="btn col-sm-12 col-xs-12 btn-success" style="display: none;" id="db_correct" onClick="start_install_db();">Выполнить корректировку</div>
                  </div>
               </div>

               <div class="tab-pane" id="tab-install-cats">
                  <div class="col-sm-12">
                     <label  class="col-sm-12 panel-heading" for="input-Prom">Заполнение дерева категорий</label>

                        Для заполнения базы категорий с Епицентра выполните сдедующие действия:<br>
                        1. Скачайте вместе с модулем архив с дополнением<br>
                        2. Установите дополнение через OCMOD либо загрузите файлы архива на сервер вручную<br>
                        3. Перейдите по ссылке <a href="<? echo $install_link; ?>" target="_blank">установки</a>. После перехода должно быть уведомление об успешном импорте (или отображаться информация импорта)

                        <br><br><br>
                        После успешного импорта вы сможете пользоваться автоподостановочным поиском категорий в вклдаке модуля в меню редактирования категорий.<br> 
                        Для наглядности и простоты поиска необходимых названий категорий можно пользоваться таблицей с отображением всего дерева категорий Епицентра  <a href="<? echo $tree_link; ?>" target="_blank">по ссылке</a>

                  
                  </div>
               </div>


               </div>

            </div>


               <div class="form-group">
                     <div style="text-align:center;" class="col-sm-12">
                        Epicentr XML v<? echo $version; ?> By Alex Soloviov :: <a href='https://shop.ionline.su'>shop.ionline.su</a>
                     </div>
                  </div>
               </div>



            </form>
         </div>
      </div>
   </div>
   <script type="text/javascript">

         function saveAndStay(){

            $('#form-epicentr_xml').append('<input id="stayField" type="hidden" name="stay" value="yes" />');
            $('#form-epicentr_xml').submit(); 
         }
 
    
         function show_install(){

            $('.tab-pane').removeClass('active');
            $('#tab-install').addClass('active');
         }
        



         function start_install_db(){

            $('.checked_status').html('<div class="lds-dual-ring"></div>');
            var start = false;

            if(start == false){

               start = true;

                  $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/install_tables&token=<?php echo $token; ?>',
                          type: 'post',
                          success: function(data) {
                              start = false;
                              $('.checked_status').html(data);
                              $('#db_correct').hide();
                              $('#db_check').show()();

                          }

                      });
            } 

         }



         function start_checking_db(){

            $('.checked_status').html('<div class="lds-dual-ring"></div>');

            var start = false;

            if(start == false){

               start = true;

                  $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/check_tables&token=<?php echo $token; ?>',
                          type: 'post',
                          success: function(data) {
                              start = false;
                              $('.checked_status').html(data);
                              $('#db_check').hide();
                              $('#db_correct').show();
                          }

                      });
            } 

         }




// Товары для выгрузки
$('input[name=\'epicentr_xml_product\']').autocomplete({
   'source': function(request, response) {
      $.ajax({
         url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
         dataType: 'json',
         success: function(json) {
            response($.map(json, function(item) {
               return {
                  label: item['name'],
                  value: item['product_id']
               }
            }));
         }
      });
   },
   'select': function(item) {
      $('input[name=\'epicentr_xml_product\']').val('');

      $('#product-epicentr_xml_products' + item['value']).remove();

      $('#product-epicentr_xml_products').append('<div id="product-epicentr_xml_products' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="epicentr_xml_products[]" value="' + item['value'] + '" /></div>');
   }
});

$('#product-epicentr_xml_products').delegate('.fa-minus-circle', 'click', function() {
   $(this).parent().remove();
});



   function toggle_status(product_id){

           var el = $('body').find('.asen_status'+product_id);

           if(el.hasClass('fa-toggle-on')) {
             //off
             el.removeClass('fa-toggle-on').addClass('fa-toggle-off').css('color', 'red');

             //он вкючен значит нужно выключить
             var status = 0;

           } else {
             //on
             el.removeClass('fa-toggle-off').addClass('fa-toggle-on').css('color', 'green');

             //он выключен значит нужно включить
             var status = 1;
           }



       $.ajax({
         url: 'index.php?route=feed/epicentr_xml/saveEpicentrStatus&token=<?php echo $token; ?>',
         method: 'post',  
         data: {product_id: product_id, status: status},
         success: function(data) {
           console.log("ok="+product_id)
         }
       });

   }









      $(document).ready(function(){



         $(".alert-success").hide(0).delay(10).fadeIn(500)
         $(".alert-success").show(0).delay(1000).fadeOut(2000)


         $('#input-epicentr_password, #input-epicentr_login').on('change', function(){
             $('#login_test').show();
             $('.login_status').html('');
         })

      
        $('#epicentr_xml_img').on('change' , function(){
            if($(this).val() == 0) {
              $('.asen_img').show();
            } else {
              $('.asen_img').hide();
            }
        });
      
      
        $('#epicentr_xml_zero').on('change' , function(){
            if($(this).val() == 1) {
              $('.asen_stats').show();
            } else {
              $('.asen_stats').hide();
            }
        });
      


        $('.load-products').on('click' , function(){

            var category = $(this).attr('id');
            console.log(category);


            $.ajax({
               url: 'index.php?route=feed/epicentr_xml/getCategoryProducts&token=<?php echo $token; ?>',
               type: 'post',
               data: {category_id:category},
               dataType: 'json',

               success: function(json) {
                  

                  html  = '<div>    <div class="row asen_attr_head">\
                                    <div class="col-sm-4 t-header">Название категории</div>\
                                    <div class="col-sm-4 t-header">Связь/Замена</div>\
                                    <div class="col-sm-2 t-header">Наценка</div>\
                                    <div class="col-sm-1 t-header">Статус</div>\
                                    <div class="col-sm-1 t-header"><i class="fa fa-check"></i></div>\
                           </div></div>';
               
                  html += '<div class="row asen_attr_row form-group">';
                  html += '<div class="col-sm-4">'+ json['category']['name'] +'</div>';
                  html += '<div class="col-sm-4">\
                              <input type="text"  name="epicentr_category_name" data-id="'+ category +'" id="input-epicentr_category_name"  value="'+ json['category']['epicentr_name'] +'" class="form-control">\
                              <input type="hidden" name="epicentr_category_id" id="input-epicentr_category_name" value="'+ json['category']['epicentr_id'] +'">\
                           </div>';                           

                  html += '<div class="col-sm-2"><input type="text" placeholder="По умолчанию" name="coeff['+ category +']"  class="form-control new_cat_coeff" data-id="'+ category +'" value="'+ json['category']['epicentr_coeff']+'" /></div>';

                  if(json['category']['status'] == 1){
                     html += '<div class="text-center col-sm-1"><i class="fa fa-lg fa-power-off" style="color:green; padding-top:8px;"></i></div>';
                  } else {
                     html += '<div class="text-center col-sm-1"><i class="fa fa-lg fa-power-off" style="color:#ddd; padding-top:8px;"></i></div>';
                  }
                  

                  html += '<div class="text-center col-sm-1 cat-result'+ category +'"><i class="fa fa-check"></i></div></div>';

                  html += '<div>';


                           
                  if(json['products'].length > 0) {

                  html += '<div id="tab-category'+category+'">\
                                 <div class="row asen_attr_head">\
                                    <div class="col-sm-6 t-header">Название товара</div>\
                                    <div class="col-sm-2 t-header">Кол-во</div>\
                                    <div class="col-sm-2 t-header">PricePromo</div>\
                                    <div class="col-sm-1 t-header">Статус</div>\
                                    <div class="col-sm-1 t-header"><i class="fa fa-check"></i></div>\
                                 </div>';

                     $.each(json['products'], function(idx, obj) {


                        html += '<div class="row asen_attr_row form-group">';
                        html += '<div class="col-sm-6">\
                                    <input placeholder="'+obj['name'] +'" type="text" name="name['+ obj['product_id']+']"  class="form-control new_name" data-id="'+ obj['product_id']+'" value="'+ obj['epicentr_name']+'" />\
                                 </div>';

                        html += '<div class="col-sm-2"><input type="text" placeholder="'+obj['quantity'] +'" name="quantity['+ obj['product_id']+']"  class="form-control new_quantity" data-id="'+ obj['product_id']+'" value="'+ obj['epicentr_quantity']+'" /></div>';

                        html += '<div class="col-sm-2"><input type="text" placeholder="Цена '+obj['price'] +'" name="promo['+ obj['product_id']+']"  class="form-control new_promo" data-id="'+ obj['product_id']+'" value="'+ obj['epicentr_promo']+'" /></div>';


                        html += '<div class="col-sm-1" style="padding-top:8px;"><a class="dropdown-toggle" data-toggle="dropdown" onClick="toggle_status('+ obj['product_id']+');">';

                                if(obj['epicentr_status'] == 1){

                                    html += '<i class="fa fa-toggle-on fa-lg  asen_status'+ obj['product_id']+'" style="color:green"></i>';

                                } else { 
                                    
                                    html += '<i class="fa fa-toggle-off fa-lg asen_status'+ obj['product_id']+'" style="color:red"></i>';

                                }
                              
                        html += '</a></div>';


                        html += '<div class="text-center col-sm-1 result'+ obj['product_id']+'"><i class="fa fa-check"></i></div></div>';

                     });
                  } else {

                     html += '<div class="alert alert-warning text-center">В данной категории нет товаров</div>';
                  }

                  html += '</div>';




                  $('#tab-fastnames .tab-content').html(html);
               }
             }).done(function(){



        
               $('input[name=\'epicentr_category_name\']').autocomplete({
                 'source': function(request, response) {
                   $.ajax({
                     url: 'index.php?route=feed/epicentr_xml/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                     dataType: 'json',
                     success: function(json) {
                       response($.map(json, function(item) {
                         return {
                           label: item['name'],
                           value: item['category_id']
                         }
                       }));
                     }
                   });
                 },
                 'select': function(item) {
                   $('input[name=\'epicentr_category_name\']').val(item['label']);
                   $('input[name=\'epicentr_category_id\']').val(item['value']);



                     var name        = $(this).val();
                     var category_id = $(this).data('id');
                     var epicentr_id  = $('input[name=\'epicentr_category_id\']').val();


                     if(epicentr_id > 0) {

                         $.ajax({
                             url: 'index.php?route=feed/epicentr_xml/saveEpicentrCategory&token=<?php echo $token; ?>',
                             type: 'post',
                             data: {category_id:category_id, epicentr_name:name, epicentr_id:epicentr_id},
                             success: function(data) {

                              $('.cat-result'+category_id).show(400).delay(1000).hide(400);
                             }

                         });
                     }
                 }
               });


              $('.new_cat_coeff').blur(function(){

                  var coeff = $(this).val();
                  var id    = $(this).data('id');

                  if(id > 0) {

                      $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/saveEpicentrCatCoeff&token=<?php echo $token; ?>',
                          type: 'post',
                          data: {category_id:id, coeff:coeff},
                          success: function(data) {

                           $('.cat-result'+id).show(400).delay(1000).hide(400);
                          }

                      });
                  }

              });


              
              $('.new_name').blur(function(){

                  var name  = $(this).val();
                  var id    = $(this).data('id');

                  if(id > 0) {

                      $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/saveEpicentrName&token=<?php echo $token; ?>',
                          type: 'post',
                          data: {product_id:id, epicentr_name:name},
                          success: function(data) {

                           $('.result'+id).show(400).delay(1000).hide(400);
                          }

                      });
                  }

              });


              $('.new_promo').blur(function(){

                  var promo = $(this).val();
                  var id    = $(this).data('id');

                  if(id > 0) {

                      $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/saveEpicentrPromo&token=<?php echo $token; ?>',
                          type: 'post',
                          data: {product_id:id, promo:promo},
                          success: function(data) {

                           $('.result'+id).show(400).delay(1000).hide(400);
                          }

                      });
                  }

              });



            $('.new_quantity').blur(function(){

                  var quantity = $(this).val();
                  var id       = $(this).data('id');

                  if(id > 0) {

                      $.ajax({
                          url: 'index.php?route=feed/epicentr_xml/saveEpicentrQuantity&token=<?php echo $token; ?>',
                          type: 'post',
                          data: {product_id:id, quantity:quantity},
                          success: function(data) {

                           $('.result'+id).show(400).delay(1000).hide(400);
                          }

                      });
                  }

              });



            });

         });

      
});
   

</script>

<style>
      .text-left {
         text-align: left !important;
      }  
      div[class*="result"]{
         display:none;
         color:green; 
         font-size:20px;
      }
      .t-header{
         background: #eaeaea;
         margin-bottom: 10px;
         border-bottom: 1px solid #464444;
         line-height: 30px;
         text-align: center;
      }
      .panel-default .panel-heading {
         height: 40px;
         padding: 0 0 0 15px;
         line-height: 40px;
      }

      .licence {
          height: 40px;
          background: red;
          line-height: 40px;
          float: right;
          padding: 0 15px;
          color: white;
      }

      .no {
         background-color: red;
      }

      .yes {
         background-color: green;
      }

      .asen_heading{
          padding: 0;
          line-height: 40px;
          height: 40px;
          text-align: center;
          color: black;
          text-transform: uppercase;
          margin: 0;
          border-bottom: 1px solid #eee;
      }

      #tab-install {
         min-height: 300px;
      }

      .lds-dual-ring {
        display: inline-block;
        width: 64px;
        height: 64px;
      }
      .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 46px;
        height: 46px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #fff;
        border-color: #fff #8fbb6c #fff #8fbb6c;
        animation: lds-dual-ring 1.2s linear infinite;
      }
      @keyframes lds-dual-ring {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      .import_status {
         text-align: center;
      }

      .no-padding{
         padding: 0;
      }

      .checked_status table tr:nth-child(1){
         background: #dadada;
      }

   </style>
</div>
<?php echo $footer; ?>