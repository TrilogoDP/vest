<?php
	class ControllerHobotixKievDigital extends Controller {
		private $ymlFile = 'https://parser-powerplant.online/download/PowerPlant_price.yml';
		//private $ymlFile = 'https://vest.in.ua/suppliers/test.yml';
		
		private $supplerPrefix = '04';
		private $supplerCode = 'kievdigital';
		
		private $categoriesArray = array();
		
		private $urlify;
		private $xml2array;	
		private $directory;	
		
		private $thisLineIsParamValue = array(
		'Series',
		'MacBook',
		'href',
		);
		
	private $validAttributes = array('Вага', 'Макс. довжина', 'Мін. довжина', 'Місткість', 'Довжина кабелю', 'Тип акумулятора', 'Напруга', 'Розміри акумулятора', 'Кількість осередків', 'Виробник', 'Гарантія', 'Можна використовувати замість', 'Сумісний з', 'Вага', 'Розмір в упаковці', 'Вага в упаковці', 'Колір', 'Розмір акумулятора', 'Вигляд', 'Вихідна напруга', 'Вхідна напруга', 'Вихідний струм', 'Споживана потужність', 'Рознімання', 'Комплектація', 'Сумісні партноміри', 'Підходить до моделей', 'Розмір', 'Довжина кабелю', 'Вхідна напруга ', 'Розміри', 'Адаптери сумісних моделей', 'Вихідне', "Роз'єм", 'Вхід', 'Вихід', "Максимальний обсяг карт пам'яті", 'Кількість портів', 'Швидкість передачі даних', 'Живлення', 'Специфікація USB', 'Сумісність', 'Індикація', 'Кабель блоку живлення', 'Матеріал', 'Підтримуючі носії', 'Сумісний з такими пристроями', 'Акумулятор можна використовувати замість', 'Потужність', 'Акумулятор можна використовувати замість оригінального', 'Сумісні зарядні пристрої', 'Сумісний з оригінальними акумуляторами', 'Кріплення', 'Робоча напруга', 'Колірна температура', 'Сила світла', 'Кількість світлодіодів', 'Кут освітлення', 'Коефіцієнт передачі кольору', 'Габарити', 'Без елементів живлення', 'Комплект постачання', 'Місткість акумулятора', 'Діапазон регулювання світла', 'Вбудований акумулятор', 'Індекс передачі кольору', 'Кількість СІД', 'Яскравість', 'Джерело живлення', 'Час перезаряду', 'Розміри (см)', 'Сумісні камери', 'Тип спалаху', 'Тип елементів живлення', 'Наявність дисплея', 'Тривалість спалаху', 'Тривалість спалаху (FP-режим)', 'Поворотна головка', 'Кут повороту вгору', 'Кут повороту по горизонталі', 'Кількість ламп в одному спалаху', 'Кількість елементів живлення', 'Мін. кількість спрацьовувань від одного комплекту батарей', 'Макс. кількість спрацьовувань від одного комплекту батарей', 'Кількість спалахів в комплекті', 'Довжина', 'Ширина', 'Висота', 'Керуючий блок в комплекті', 'Ручне регулювання потужності', 'Підтримка режиму ADI-TTL', 'Підтримка режиму S-TTL', 'Підтримка режиму P-TTL', 'Підтримка режиму i-TTL', 'Підтримка режиму D-TTL', 'Підтримка режиму TTL', 'Поворотна головка', 'Синхронізація', 'Підходить', 'Тип', 'Інтерфейс підключення', 'Управління потужністю', 'Вертикальний кут повороту', 'Горизонтальний кут обертання', 'Витримка', 'Кількість спрацьовувань', 'Схемотехніка', 'Зовнішній інтерфейси', 'Підтримка режиму M', 'Підтримка режиму S1', 'Підтримка режиму S2', 'Підтримка режиму RPT', 'Упаковування', 'Підходить до акумуляторів', "Роз'єм ", "Роз'єм 2", "Роз'єм 3", "Роз'єм 4", 'Колір кабелю', "Сумісні з наступними комп'ютерами", 'Тип USB', 'Підтримуваний USB', 'Підтримуваний Ethernet', 'Інтерфейс', 'Максимальна швидкість передачі', 'Тип батареї', 'Додатково', 'Система захисту', 'Час повної зарядки', 'Особливості', 'Система захисту', 'Час повної зарядки', 'Вхід micro USB', 'Вихід USB 1', 'Вихід USB 2', 'LED дисплей', 'Час зарядки', 'Розміри в упаковці', 'Робоча температура', 'Загальна потужність', 'Сонячна панель', 'Вага без упаковування', 'Сумісність акумуляторів', 'Підходить для', 'Розміри (ШхВхД)', 'Кількість каналів', 'Типорозмір акумуляторів', 'Сила струму при зарядці', 'Функції', 'Сумісні акумулятори', 'Ресурс друку', 'Сумісний бренд', 'Сумісні моделі', 'Модель', 'Розмір панелі', 'Роздільна здатність', 'LCD дисплей', 'Активна область', 'Контрастність', 'Час реакції', 'Кут огляду', 'Колірна гама', 'Кількість кольорів', 'Технологія пера', 'Роздільна здатність пера', 'Рівні тиску', 'Точність', 'Висота зчитування пера', 'Швидкість відгуку', 'Розпізнавання нахилу', 'Сенсорна панель', 'Цифрове перо', 'Відеоінтерфейс', 'Кількість клавіш', 'Регульована підставка', 'Підтримка ОС', 'Антивідблискуюче скло', 'Використання енергії', 'Робоча температура та вологість', 'Температура та вологість зберігання', 'ЦП', 'Основна плата', 'ОЗП', 'Графіка', 'Сховище', "Тип бездротового зв'язку", 'Bluetooth', 'Динаміки', 'Порти', 'Розпізнавання нахилу', 'Підставка ', 'Операційна система', 'Кількість розеток', 'Максимальна сила струму', 'Перетин провідників', 'Матеріал провідника', 'Вхідні розетки', 'Вимикач', 'Температура експлуатації', 'Максимальна сумарна потужність навантаження', 'Тип проводу', 'Матеріал корпусу', 'Клас захисту', 'Вологість повітря', 'Кількість ламп', 'Тип лампи', 'Тип цоколя', 'Монтаж', 'Максимальна потужність навантаження', 'Тип кабелю', 'Кількість USB портів', 'Тип вхід', 'Тип вихід', 'Максимальна вихідна напруга', 'Максимальний струм', 'Перетин дроту', 'Номінальна вхідна напруга', 'Вихідна потужність (Макс)', 'Вхідна напруга DC', 'Вихідна напруга DC', 'USB', 'Функції захисту', 'Plug&amp;Play', 'Plug&amp;Play', 'Plug&Play', 'Феритові кільця', 'Підтримувана роздільна здатність', 'Підтримка 3D зображення', 'Макс. швидкість передачі даних', 'Співвідношення сигнал/шум', 'Працює зі смартфонами', 'Використання', 'Комплектація', 'Максимальне навантаження', 'Plug and Play підключення', 'Універсальний для iOS та Android', 'Вес', 'Макс. длинна', 'Мин. длинна', 'Емкость', 'Длина кабеля', 'Тип аккумулятора', 'Напряжение', 'Размеры аккумулятора', 'Количество ячеек', 'Производитель', 'Гарантия', 'Можно использовать вместо', 'Совместим с', 'Вес', 'Размер в упаковке', 'Вес в упаковке', 'Цвет', 'Размер аккумулятора', 'Вид', 'Выходное напряжение', 'Входное напряжение', 'Выходной ток', 'Потребляемая мощность', 'Разъемы', 'Комплектация', 'Cовместимые партномера', 'Подходит к моделям', 'Размер', 'Длина кабеля', 'Вход. Напряжение', 'Pазмеры', 'Адаптеры совместимых моделей', 'Выходное', 'Разъем', 'Вход', 'Выход', 'Максимальный объем карт памяти', 'Количество портов', 'Скорость передачи данных', 'Питание', 'Спецификация USB', 'Совместимость', 'Индикация', 'Кабель блока питания', 'Материал', 'Поддерживаемые носители', 'Совместим с такими устройствами', 'Аккумулятор можно использовать вместо', 'Мощность', 'Аккумулятор можно использовать вместо оригинального', 'Совместимые зарядные устройства', 'Совместим с оригинальными аккумуляторами', 'Крепление', 'Рабочее напряжение', 'Цветовая температура', 'Сила света', 'Количество светодиодов', 'Угол освещения', 'Коэффициент цветопередачи', 'Габариты', 'Вес без элементов питания', 'Комплект поставки', 'Ёмкость аккумулятора', 'Диапазон регулировки света', 'Встроенный аккумулятор', 'Индекс цветопередачи', 'Количество СИД', 'Яркость', 'Источник питания', 'Время перезаряда', 'Размеры (см)', 'Совместимые камеры', 'Тип вспышки', 'Тип элементов питания', 'Наличие дисплея', 'Длительность вспышки', 'Длительность вспышки (FP-режим)', 'Поворотная головка', 'Угол поворота вверх', 'Угол поворота по горизонтали', 'Количество ламп в одной вспышке', 'Количество элементов питания', 'Мин. число срабатываний от одного комплекта батарей', 'Макс. число срабатываний от одного комплекта батарей', 'Количество вспышек в комплекте', 'Длина', 'Ширина', 'Высота', 'Управляющий блок в комплекте', 'Ручная регулировка мощности', 'Поддержка режима ADI-TTL', 'Поддержка режима S-TTL', 'Поддержка режима P-TTL', 'Поддержка режима i-TTL', 'Поддержка режима D-TTL', 'Поддержка режима TTL', 'Поворотная головка', 'Синхронизация', 'Подходит', 'Тип', 'Интерфейс подключение', 'Управление мощностью', 'Вертикальный угол поворота' , 'Горизонтальный угол вращения', 'Выдержка', 'Количество срабатываний', 'Схемотехника', 'Внешний интерфейсы', 'Поддержка режима M', 'Поддержка режима S1', 'Поддержка режима S2', 'Поддержка режима RPT', 'Упаковка', 'Подходит к аккумуляторам', 'Разъем 1', 'Разъем 2', 'Разъем 3', 'Разъем 4', 'Цвет кабеля', 'Совместим со следующими компьютерами', 'Тип USB', 'Поддерживаемый USB', 'Поддерживаемый Ethernet', 'Интерфейс', 'Максимальная скорость передачи', 'Тип батареи', 'Дополнительно', 'Система защиты', 'Время полной зарядки', 'Особенности', 'Система защиты', 'Время полной зарядки', 'Вход micro USB', 'Выход USB 1', 'Выход USB 2', 'LED дисплей', 'Время зарядки', 'Размеры в упаковке', 'Рабочая температура', 'Общая мощность', 'Солнечная панель', 'Вес без упаковки', 'Совместимость аккумуляторов', 'Подходит для', 'Размеры (ШхВхГ)', 'Количество каналов', 'Типоразмер аккумуляторов', 'Сила тока при зарядке', 'Функции', 'Совместимые аккумуляторы', 'Ресурс печати', 'Совместимый бренд', 'Совместимые модели', 'Модель', 'Размер панели', 'Разрешение', 'LCD дисплей', 'Активная область', 'Контрастность', 'Время реакции', 'Угол обзора', 'Цветовая гамма', 'Количество цветов', 'Технология пера', 'Разрешение пера', 'Уровни давления', 'Точность', 'Высота считывания пера', 'Скорость отклика', 'Распознавания наклона', 'Сенсорная панель', 'Цифровое перо', 'Видеоинтерфейс', 'Количество клавиш', 'Регулируемая подставка', 'Поддержка ОС', 'Антибликовое стекло', 'Потребление энергии', 'Рабочая температура и влажность', 'Температура и влажность хранения', 'ЦП', 'Основная плата', 'ОЗУ', 'Графика', 'Хранилище', 'Тип беспроводной связи', 'Bluetooth', 'Динамики', 'Порты', 'Распознавание наклона', 'Подставка', 'Операционная система', 'Количество розеток', 'Максимальная сила тока', 'Сечение проводников', 'Материал проводника', 'Входные розетки', 'Выключатель', 'Температура эксплуатации', 'Максимальная суммарная мощность нагрузки', 'Тип провода', 'Материал корпуса', 'Класс защиты', 'Влажность воздуха', 'Количество ламп', 'Тип лампы', 'Тип цоколя', 'Монтаж', 'Максимальная мощность нагрузки', 'Тип кабеля', 'Количество USB портов', 'Тип вход', 'Тип выход', 'Максимальное выходное напряжение', 'Максимальный ток', 'Сечение провода', 'Номинальное входное напряжение', 'Выходная мощность (макс)', 'Входное напряжение DC', 'Выходное напряжение DC', 'USB', 'Функции защиты', 'Plug&amp;amp;Play', 'Plug&amp;Play', 'Plug&Play', 'Ферритовые кольца', 'Поддерживаемое разрешение', 'Поддержка 3D изображения','Макс. скорость передачи данных', 'Соотношение сигнал/шум', 'Работает со смартфонами', 'Использование','Комплектация', 'Максимальная нагрузка', 'Plug and Play подключение', 'Универсален для iOS и Android');
		
		
		private $paramEndedByBR = array('Гарантия', 'Длина кабеля', 'Длина', 'Гарантія', 'Довжина кабелю', 'Довжина');		
		private $paramFailOfTwoPointsByBR = array('Длина', 'Макс. скорость передачи данных', 'Довжина', 'Макс. швидкість передачі даних');
		
		private function echoLine($line){
			$line = str_replace('<![CDATA[', '', $line);
			$line = str_replace(']]>', '', $line);
			echo $line . PHP_EOL;			
		}
		
		function array_to_xml($data, &$xml) {
			
			foreach($data as $key => $value) {
				if (is_array($value)) {
					if (!is_numeric($key)) {
						$subnode = $xml->addChild(preg_replace('/\d/', '', $key));
						$this->array_to_xml($value, $subnode);
					}
				}
				else {
					$xml->addChild($key, $value);
				}
			}
			
			return $xml;
		}
		
		private function echoSimple($line){
			echo $line;			
		}
		
		private function memoryUnits($size)
		{
			$unit=array('b','kb','mb','gb','tb','pb');
			return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		}
		
		public static function parseYMLParamArray($params){
			$result = array();
			
			//если параметр один
			if (isset($params['@value'])){
				$result[] = array(
				'name' 	=> trim($params['@attributes']['name']),
				'value' => trim($params['@value'])
				);
			}
			
			foreach ($params as $param){							
				if (isset($param['@attributes']) && isset($param['@attributes']['name']) && isset($param['@value'])){					
					$result[] = array(
					'name' 	=> trim($param['@attributes']['name']),
					'value' => trim($param['@value'])
					);
				}
			}
			
			return $result;
		}
		
		public static function getParamValue($data, $param){		
			
			if (isset($data["@attributes"][$param])){
				return $data["@attributes"][$param];
			}
			
			return false;
		}
		
		public static function checkCDATA($data){
			
			if (isset($data["@cdata"])){
				return $data["@cdata"];
				} else {
				return $data;
			}
		}
		
		public static function getItemValue($data){
			
			if (isset($data["@value"])){
				return $data["@value"];
			}
			
			return false;
			
		}
		
		public static function getParamValueName($data, $param){		
			foreach ($data as $param_value){
				if ($param_value["@attributes"]["name"] == $param){
					return $param_value['@value'];
				}				
			}
			
			return false;
		}
		
		
		private function replaceBR($string){
			$string = trim(str_replace('<br />', '' , $string));
			$string = trim(str_replace('<br>', '' , $string));
			$string = trim(str_replace('<br >', '' , $string));
			$string = trim(str_replace('<br/>', '' , $string));
			
			return $string;
		}
		
		private function isOneLineParam($string){
			
			foreach ($this->paramEndedByBR as $param){
				
				if (strpos($string, $param) !== false){
					return true;
				}
				
			}
			
			return false;
		}
		
		private function checkStringAsParamValue($string){
			
			if (strpos($string, '<img') !== false){
				return true;
			}
			
			if (strpos($string, 'https://www.youtube.com') !== false){
				return true;
			}
			
			if (strpos($string, 'https://youtu.be/') !== false){
				return true;
			}
			
			if (strpos($string, '<iframe') !== false){
				return true;
			}
			
			if (strpos($string, '<strong') !== false){
				return true;
			}
			
			if (strpos($string, 'Основными особенностями') !== false){
				return true;
			}

			if (strpos($string, 'Основними особливостями') !== false){
				return true;
			}
			
			return false;
			
		}
		
		private function checkStringCanBeParamName($string){			

			if (mb_strlen($string) > 200){
				return false;
			}

			foreach ($this->thisLineIsParamValue as $line){				
				if (strpos($string, $line) !== false){
					return false;
				}
			}
			
			return true;
		}
		
		private function checkStringCanBeAttributeName($string){

			if (mb_strlen($string) > 200){
				return false;
			}

			foreach ($this->validAttributes as $line){				
				if (strpos($string, $line) !== false){
					return true;
				}				
			}
			
			return false;
		}
		
		private function tryToRemoveShitFromDescription($string){
			
			foreach ($this->paramFailOfTwoPointsByBR as $param){
				
				$string = str_replace($param . ' ', $param . ':', $string);
				$string = str_replace($param . '::', $param . ':', $string);
				$string = str_replace($param . ': :', $param . ':', $string);
				
			}
			
			return $string;
			
		}
		
		public function tryToParseParams($string, $name){
			$result = array();
			
			$exploded = explode('Название:', $string);

			if (empty($exploded[1])){
				$exploded = explode('Назва:', $string);
			}

			if (empty($exploded[1])){
				$exploded = explode('Найменування:', $string);
			}
			
			if (empty($exploded[1])){
				$exploded = explode('арактеристики:', $string);
			}

			if (empty($exploded[1])){
				$exploded = explode('сновные характеристики:', $string);
			}

			if (empty($exploded[1])){
				$exploded = explode('сновні характеристики:', $string);
			}
			
			if (!empty($exploded[count($exploded) - 1])){
				$param_string = $exploded[count($exploded) - 1];
				} else {
				$param_string = $string;
			}
			
			if (!empty($param_string)){
				$param_string = $this->tryToRemoveShitFromDescription($param_string);
				
				$explodedByEOL = explode(PHP_EOL, $param_string);
				
				foreach ($explodedByEOL as $explodedbyEOLLine){
					//Это картинка или ифрейм
					if ($this->checkStringAsParamValue($explodedbyEOLLine)){
						//skipping
						} else {
						
						if ($this->checkStringCanBeAttributeName($explodedbyEOLLine) && strpos($explodedbyEOLLine, ':') !== false){
							$explodedbyTwoPoints = explode(':', $explodedbyEOLLine);
							$paramName = $this->replaceBR($explodedbyTwoPoints[0]);

							$paramName = trim($paramName);
							$paramName = str_replace('- ', '', $paramName);
							$paramName = rtrim($paramName, '-');
							$paramName = trim($paramName);

							$paramValue = $this->replaceBR($explodedbyTwoPoints[1]);
							
							if (empty($result[$paramName]) && trim($paramName)){
								$result[$paramName] = $paramValue;
							}
							
							$this->echoLine('PARAM: ' . $paramName);
							$this->echoLine('VALUE: ' . $paramValue);
							
							} else {						
							
							$value = $this->replaceBR($explodedbyEOLLine);
							
						//	$this->echoLine('СТРОКИ: ' . $value);
							
							if (!$this->isOneLineParam($paramName)){
								if (trim($paramName) && trim($value)){
									if (trim($result[$paramName])){
										$result[$paramName] .= ', ' . $value;
										} else {									
										$result[$paramName] = $value;
									}
								}
								} else {
								if (empty($result[$paramName]) && trim($paramName)){
									$result[$paramName] = $paramValue;
								}
							}
						}
						
						if ($paramName && !empty($result[$paramName])){
							$result[$paramName] = trim($result[$paramName]);
							$result[$paramName] = trim($result[$paramName], ',');
							$result[$paramName] = trim($result[$paramName], ' , ');
						}
					}
					
				}
				
			}

			return $result;
		}
		
		private function validateAllowedCategory($offer){
			
			$configAllowedCategories = explode(PHP_EOL, $this->config->get('config_kievdigital_categories'));
			$allowedCategories = array();
			$allowedCategoriesIDS = array();			
			
			foreach ($configAllowedCategories as $cAC){
				$cAC = trim($cAC);
				if ($cAC && !empty($this->categoriesArray[$cAC])){				
					$allowedCategories[$this->categoriesArray[$cAC]] = $cAC;
				}
			}
			
			if (!empty($allowedCategories[$offer['categoryId']])){
				return true;
			}
			
			return false;
			
		}
		
		private function tryToGetYoutubeVideoFromDescription($text){
			$result = '';
			
			preg_match('~(?:https?://)?(?:www.)?(?:youtube.com|youtu.be)/(?:watch\?v=)?([^\s]+)~', $text, $matches);
			if (!empty($matches[1])){
				$result = str_replace('<br', '', $matches[1]);
				$result = str_replace('embed/', '', $result);
				$result = str_replace('?autohide=1', '', $result);
				$result = str_replace('"', '', $result);
			}
			
			return $result;
		}
		
		public function cron(){
			
			ini_set('memory_limit','2G');
			$this->config->set('config_language_id', 1);
			
			$this->load->model('catalog/product');
			$this->load->model('hobotix/hoboprice');
			
			if (!defined('OPENCART_CLI_MODE')){
				die('CLI ONLY');
			}
			
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'XML2Array2.php');
			require_once( DIR_SYSTEM . 'library/hobotix/helpers/' . 'Array2XML.php');
			require_once( DIR_SYSTEM . 'library/urlify.php');		
			
			if ($ymlContents = file_get_contents($this->ymlFile)){
				$this->echoLine('[XML] Загрузили XML ');	
				} else {
				$this->echoLine('[XML] Не получилось загрузить XML ');	
				die();
			}

			$ymlContents = iconv('Windows-1251', 'UTF-8', $ymlContents);
			$ymlContents = str_replace('encoding="windows-1251"', 'encoding="UTF-8"', $ymlContents);
			
			$xmlFile = DIR_SUPPLIERS . 'kievdigital.original.xml';
			$ymlContents = str_replace('BLACK&DECKER', 'BLACK AND DECKER', $ymlContents);

			file_put_contents($xmlFile, $ymlContents);
			
			try {
				$yml = LaLit\XML2Array::createArray($ymlContents);
				$this->echoLine('[YML] Загрузили XML в массив');	
				} catch (Exception $e){
				$this->echoLine('[YML] Ошибка разбора XML. ' . $e->getMessage());
				die ();
			}
			
			$xml = array('item' => array());
			
			$this->load->model('hobotix/hoboparser');
			$searchReplaceArray = $this->model_hobotix_hoboparser->init();
			
			$attributesArray = array();
			$categoriesArray = array();
			
			$existentProducts = array();
			$existentOptions = array();
			
			foreach ($yml["yml_catalog"]["shop"]["categories"]["category"] as $category){
				$categoriesArray[$this->getItemValue($category)] = $this->getParamValue($category, 'id');
				$categoriesArrayReverse[$this->getParamValue($category, 'id')] = $this->getItemValue($category);;
			}
			
			$this->categoriesArray = $categoriesArray;
			
			foreach ($yml["yml_catalog"]["shop"]["offers"]["offer"] as $offer){		
				if ($offer['vendorCode'] != 'PB930685'){
					//continue;
				}	
				
				if (!$this->validateAllowedCategory($offer)){					
					//IGNORE CATEGORIES LIST
					if (!$this->config->get('config_kievdigital_use_all_categories')){
						$this->echoLine('[skip] Категория не подходит');
						continue;
					}					
				}
				
				$this->echoLine('[i] Товар в категории ' . $offer['categoryId'] . ', ' . $categoriesArrayReverse[$offer['categoryId']]);
				
				if (!$this->model_catalog_product->checkIfSKUWasDeleted($offer["vendorCode"]) && !$this->model_catalog_product->checkIfSKUWasDeleted($this->supplerPrefix . $offer["vendorCode"])){
					
					$params = $this->tryToParseParams($this->checkCDATA($offer['description']), $offer["name"]);
					
					$currentAttributesArray = array();
					if (is_array($params)){
						foreach ($params as $key => $value){
							if ($key && $value){
								$attributesArray[$key] = $value;
								if (isset($searchReplaceArray[$key])){								
									$currentAttributesArray[$searchReplaceArray[$key]] = $value;
									} else {
									$currentAttributesArray[$key] = $value;
								}
							}
						}						
					}
					
					
					
					$reparsedParams = array();
					foreach ($currentAttributesArray as $key => $value){
						
						$reparsedParams[] = array(
						"@attributes" => array('name' => $key),
						"@value" 	  => $value
						);
						
					}
					
					$images = array('image' => array());
					
					if (is_array($offer['picture'])){
						foreach ($offer['picture'] as $image){
							if ($image != ''){
								$images['image'][] = $image;
							}
						}
						} else {
						if ($offer["picture"] != ''){
							$images['image'][] = $offer['picture'];						
						}
					}
					
					unset($image);
					$tmp = array('image' => array());
					foreach ($images['image'] as &$image){												
						mkdir($dir = (DIR_IMAGE . 'catalog/' . $this->supplerCode . '/tmp/'), 0755, true);
						$local_file = str_replace('img', '', mb_strtolower(basename($image)));
						$local_img = $dir . $local_file;
													
						$this->echoLine($image . ' -> ' . $local_img);

						if (file_exists($local_img) && filesize($local_img) == 0){
							unlink($local_img);
						}

						if (!file_exists($local_img) || filesize($local_img) == 0){							
							$img = file_get_contents(mb_strtolower($image));
							file_put_contents($local_img, $img);
						}
						$tmp['image'][] = 'https://vest.in.ua/image/catalog/' . $this->supplerCode . '/tmp/' . $local_file;	
					}					
					
					$images = $tmp;
					
					$available = false;
					if ($this->getParamValue($offer, 'available') == 'true'){
						$available = true;
					}
					
					$quantity = ($available)?3:0;
					if ($product_id = $this->model_catalog_product->findProductBySKU($this->supplerPrefix . $offer["vendorCode"])){
						
						$product = $this->model_catalog_product->getExplicitProduct($product_id);
						
						if ($available){
							$quantity = 3 + $product['stock'];
							} else {
							$quantity = $product['stock'];
						}						
					}
					
					$offer["description"] = $this->checkCDATA($offer["description"]);
					$offer["description"] = strip_tags($offer["description"], '<p><a><br><br /><br/><iframe>');
					
					$youtube = $this->tryToGetYoutubeVideoFromDescription($offer["description"]);
					
					
					$xml['item'][] = array(
					'name' 				=> trim($offer["name"]),
					'sku' 				=> trim($offer["vendorCode"]),
					'category' 			=> 'KievDigital',
					'brand' 			=> trim($offer["vendor"]),
					'cost'				=> $offer["cost_price"],
					'price'				=> !empty($product['dnup'])?$product['price']:$offer["price"],
					'quantity' 			=> $quantity,
					'images' 			=> $images,
					'youtube' 			=> $youtube,	
					'ean'				=> $offer["barcode"]?$offer["barcode"]:'',
					'description'		=> array('@cdata' => !empty($offer["description"])?$offer["description"]:''),
					'param'				=> $reparsedParams?$reparsedParams:'',					
					);	
					
					//MKING SUPPLER PREFIX
					if ($product_id){						
						//UPDATE QUERIES
						$this->echoLine('[KievDigital] Нашли товар ' . $product_id . ' - ' . $offer["vendorCode"]);
						
						$existentProducts[] = $product_id;
						
						//EAN
						$quantity = ($available)?3:0;
						$this->db->query("UPDATE " . DB_PREFIX . "product SET supplier = '" . (int)$quantity . "' WHERE product_id = '" . (int)$product_id . "'");
						$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = stock + supplier WHERE product_id = '" . (int)$product_id . "'");
						
						if (!$product['dnup']){
							$this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$offer["price"] . "' WHERE product_id = '" . (int)$product_id . "'");
						}
						
						if (!$product['youtube_single'] && $youtube){
							$this->db->query("UPDATE " . DB_PREFIX . "product SET youtube_single = '" . $this->db->escape($youtube) . "' WHERE product_id = '" . (int)$product_id . "'");
						}
						
						$history = array(
						'product_id' 	=> $product_id,
						'suppler_code' 	=> $this->supplerCode,
						'price'			=> $offer["cost_price"]
						);
						
						$this->model_hobotix_hoboprice->addPrice($history);
						
						} else {
						$this->echoLine('[KievDigital] Не нашли товар ' . $offer["vendorCode"]);
					}
					
					} else {
					$this->echoLine('[KievDigital] Пропускаем товар ' . $offer["vendorCode"]);				
				}
				
			}		
			
			$unExistent = $this->model_hobotix_hoboparser->disableUnexsistentProducts($existentProducts, $existentOptions, $this->supplerPrefix);
			
			mkdir(DIR_SUPPLIERS . $this->supplerCode, 0755, true);
			
			$unexistentProductsString = '';
			foreach ($unExistent['unexistentProducts'] as $key => $value){
				$unexistentProductsString .= $value . PHP_EOL;
			}
			
			$unexistentProductsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kiegdigital.unexistentproducts.csv';
			file_put_contents($unexistentProductsFile, $unexistentProductsString);
			
			$unexistentOptionsString = '';
			foreach ($unExistent['unexistentOptions'] as $key => $value){
				$unexistentOptionsString .= $value . PHP_EOL;
			}
			
			$unexistentOptionsFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kiegdigital.unexistentoptions.csv';
			file_put_contents($unexistentOptionsFile, $unexistentOptionsString);
			
			$attributesString = '';
			foreach ($attributesArray as $key => $value){
				$attributesString .= $key . ';' . $value . PHP_EOL;
			}
			
			$attributesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kievdigital.attributes.csv';
			file_put_contents($attributesFile, $attributesString);
			
			
			
			$categoriesString = '';
			foreach ($categoriesArray as $key => $value){
				$categoriesString .= $key . ';' . $value . PHP_EOL;
			}
			
			$categoriesFile = DIR_SUPPLIERS . $this->supplerCode . '/' . 'kievdigital.categories.csv';
			file_put_contents($categoriesFile, $categoriesString);
			
			
			$xmlString = LaLit\Array2XML::createXML('kievdigital', $xml)->saveXML();;					
			$xmlFile = DIR_SUPPLIERS . 'kievdigital.converted.xml';
			
			//			var_dump($xmlString);
			file_put_contents($xmlFile, $xmlString);
			
		}
	}																	