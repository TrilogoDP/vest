<?php

class ModelCatalogCopy extends Controller
{


	private $skuToCopy 	= 'PL-';
	private $skuNew 	= 'PM-';

	private $manufacturerIdNew = 603;

	private $descriptionNew = [
		'1' => '<p>Уже сотни краш-тестов и проверок доказали, что гидрогелевая пленка – одно из лучших средств для защиты экрана смартфона.<br>
		Однако эта модель гидрогелевой плёнки отличается от обычной. Она имеет дополнительное покрытие, которое обеспечивает эффект приватности и скрывает содержание, если угол обзора непрямой.<br>
		Соответственно, все, что вы будете видеть на экране своего телефона, для глаз других людей будет скрытым.<br><b>
		</b></p><p><b>Кроме этой важной характеристики упомянем и другие:</b><br>
		- пленка абсолютно прозрачна и не влияет на чувствительность и цветопередачу изображений<br>
		- тонкая и практически не заметная на экране<br>
		- отлично защищает от царапин, порезов и других повреждений<br>
		- свойство для регенерации – на солнце мелкие царапины затягиваются самостоятельно<br>
		- антибликовый эффект.<br>
		</p><p><b><i>Срок службы составляет в среднем 1 год.</i></b><br>
		</p><p><b>Как же поклеить пленку?</b><br>
		С этой задачей справится даже ребенок. Клеится пленка очень просто, при этом, в отличие от стекла, можно менять её положение, пока все будет идеально. После этого следует снять защитный слой.<br>
		</p><p><b>Важно!</b><br>
		Не пытайтесь вытеснить все пузырьки воздуха, находящиеся под пленкой. Маленькие пузыри исчезнут самостоятельно в течение 24 часов.<br>
		Конечно рекомендуем купить гидрогелевую пленку с эффектом приватности, ведь здесь сочетается отличная защита экрана смартфона, а также можно реализовать свое желание не делиться с посторонними людьми личной информацией.<br>
		</p>',
		'3'	=> '<p>Уже сотні краш-тестів та перевірок довели, що гідрогелева плівка одна з кращих засобів для захисту екрана смартфона.<br>
		Однак ця модель гідрогелевої плівки відрізняється від звичайної. Вона має додаткове покриття, яке забезпечує ефект приватності та приховує зміст, якщо кут огляду є непрямим. <br>
		Відповідно, все що ви будете бачити на екрані свого телефону, для очей інших людей буде прихованим.<br>
		</p><p><b>Окрім цієї важливої характеристики згадаємо також інші:</b><br>
		- плівка абсолютно прозора й не впливає на чутливість та кольоровіддачу зображень<br>
		- тонка і практично не помітна на екрані<br>
		- відмінно захищає від подряпин, порізів та інших пошкоджень<br>
		- властивість для регенерації - на сонці дрібні подряпини затягуються самостійно<br>
		- ефект антивідблиску.<br>
		</p><p><b><i>Термін служби в середньому становить 1 рік.<br>
		</i></b></p><p><b>Як же поклеїти плівку?</b><br>
		З цим завданням впорається навіть дитина. Клеїться плівка дуже просто, при цьому, на відміну від скла, можна міняти її положення, аж поки все буде ідеально. Після цього потрібно зняти захисний шар. <br>
		</p><p><b>Важливо!</b><br>
		Не намагайтесь витіснити всі пухирці повітря, які знаходяться під плівкою. Маленькі бульбашки зникнуть самостійно протягом 24год.<br>
		Звичайно рекомендуємо купити гідрогелеву плівку з ефектом приватності, адже тут поєднаний відмінний захист екрана смартфона та можна реалізувати своє бажання не ділитись з посторонніми людьми особистою інформацією.</p>'
	];

	private $fakeDescriptionNew = [
		'1' => 'Privacy Matte – противоударная гидрогелевая пленка с эффектом приватности. Она не только отлично защищает дисплей смартфона от царапин и повреждений, а также скрывает любую информацию на нем, если угол обзора непрямой. Это означает, что никто, кроме вас, не будет видеть, что именно изображено на экране. К другим особенностям пленки относим прочность, надежность, простота в поклейке, длительный срок службы и свойство самовосстановления (мелкие царапины самостоятельно затягиваются на солнце).',
		'3'	=> 'Privacy Matte - протиударна гідрогелева плівка з ефектом приватності. Вона не тільки відмінно захищає дисплей смартфона від подряпин та пошкоджень, а також приховує будь-яку інформацію на ньому, якщо кут огляду непрямий. Це означає, що ніхто, окрім вас, не буде бачити що саме зображено на екрані. До інших особливостей плівки відносимо міцність, надійність, простота у поклейці, довготривалий термін служби та властивість до самовідновлення (дрібні подряпини самостійно затягуються на сонці).'
	];

	private $imagesNew = [
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogeleva-plivka-privacy-matte-universal-photo-0.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogeleva-plivka-privacy-matte-universal-photo-1.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogeleva-plivka-privacy-matte-universal-photo-2.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogeleva-plivka-privacy-matte-universal-photo-3.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogeleva-plivka-privacy-matte-universal-photo-4.jpg'
	];

	private $specialAttributeNew = [
		'1' => 'Затемнение экрана при просмотре под углом, олеофобное покрытие, клеевой слой по всей поверхности, авторегенерация, точное соответствие модели',
		'3' => 'Затемнення екрану під час перегляду під кутом, олеофобне покриття, клейовий шар по всій поверхні, авторегенерація, точна відповідність моделі'
	];

	private $specialAttributeNew2 = [
		'1' => 'Матовая',
		'3' => 'Матова'
	];


	public function copyPrivacyMatte($product_id){
		$this->load->model('catalog/product');
		$this->load->model('module/seogen');

		$row = $this->db->query("SELECT product_id, sku FROM oc_product WHERE product_id = '" . (int)$product_id . "'")->row;

		if ($row) {
			if ( strpos($row['sku'], $this->skuToCopy) !== false ){

				$new_product_id = $this->model_catalog_product->copyProduct($row['product_id']);
				echoLine('COPY: ' . $new_product_id);

				//Кількість
				$this->db->query("UPDATE oc_product SET quantity = 9999 WHERE product_id = '" . $new_product_id . "'");

				//Ціну міняємо на 299грн
				$this->db->query("UPDATE oc_product SET copied_from = '" . $row['product_id'] . "' WHERE product_id = '" . $new_product_id . "'");

				//Артикул залишаємо той самий міняємо лише PL на PM
				$this->db->query("UPDATE oc_product SET sku = '" . str_replace($this->skuToCopy, $this->skuNew, $row['sku']) . "' WHERE product_id = '" . (int)$new_product_id . "'");

				//Ціну міняємо на 299грн
				$this->db->query("UPDATE oc_product SET price = 299 WHERE product_id = '" . $new_product_id . "'");

				//Виробника вибрати iNobi
				$this->db->query("UPDATE oc_product SET model = 'Privacy Matte' WHERE product_id = '" . $new_product_id . "'");

				//Виробника вибрати iNobi
				$this->db->query("UPDATE oc_product SET manufacturer_id = '" . (int)$this->manufacturerIdNew . "' WHERE product_id = '" . $new_product_id . "'");

				//Відео прибираємо
				$this->db->query("UPDATE oc_product SET youtube_single = '' WHERE product_id = '" . $new_product_id . "'");

				//Типа очищать скидки имеешь в виду?
				$this->db->query("DELETE FROM oc_product_special WHERE product_id = '" . $new_product_id . "'");

				//В назві міняємо "Противоударная гидрогелевая пленка Hydrogel Film" на "Гидрогелевая пленка iNobi Privacy Matte"
				foreach (['name', 'meta_h1', 'meta_description', 'meta_title'] as $field){
					$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, 'Противоударная гидрогелевая пленка Hydrogel Film', 'Гидрогелевая пленка iNobi Privacy Matte') WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
					$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, 'Протиударна гідрогелева плівка Hydrogel Film', 'Гідрогелева плівка iNobi Privacy Matte') WHERE product_id = '" . $new_product_id . "' AND language_id = 3");


				//В назві міняємо ", Transparent" на " (Антишпион)" (зверни увагу, що тут є пробіл)
					$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, ', Transparent', ' (Антишпион)') WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
					$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, ', Transparent', ' (Антишпигун)') WHERE product_id = '" . $new_product_id . "' AND language_id = 3");
				}

				//Опис весь міняємо на той що в файлі, що сюди прикріпив. Опис маркетплейсу так само.
				$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($this->descriptionNew[1])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
				$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($this->descriptionNew[3])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 3");
				$this->db->query("UPDATE oc_product_description SET fake_description = '" . $this->db->escape(nl2br($this->fakeDescriptionNew[1])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
				$this->db->query("UPDATE oc_product_description SET fake_description = '" . $this->db->escape(nl2br($this->fakeDescriptionNew[3])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 3");

				//Замінити фото на ті що я додаю. Фото з заготовкою має бути перше.
				$this->db->query("UPDATE oc_product SET image = '" . $this->db->escape($this->imagesNew[0]) . "' WHERE product_id = '" . $new_product_id . "'");
				$this->db->query("DELETE FROM oc_product_image WHERE product_id = '" . (int)$new_product_id . "'");

				for ($i=1; $i<=count($this->imagesNew)-1; $i++){
					$this->db->query("INSERT INTO oc_product_image SET product_id = '" . (int)$new_product_id . "', image = '" . $this->db->escape($this->imagesNew[$i]) . "', video_in_product = '', sort_order = '". (int)$i ."'");
				}

				//В атрибутах потрібно в "Особенности" замінити текст 
				$this->db->query("UPDATE oc_product_attribute SET text = '" . $this->db->escape($this->specialAttributeNew[1]) . "' WHERE product_id = '" . $new_product_id . "' AND attribute_id = 81 AND language_id = 1");
				$this->db->query("UPDATE oc_product_attribute SET text = '" . $this->db->escape($this->specialAttributeNew[3]) . "' WHERE product_id = '" . $new_product_id . "' AND attribute_id = 81 AND language_id = 3");

				//В атрибутах потрібно в "Особенности" замінити текст 
				$this->db->query("UPDATE oc_product_attribute SET text = '" . $this->db->escape($this->specialAttributeNew2[1]) . "' WHERE product_id = '" . $new_product_id . "' AND attribute_id = 80 AND language_id = 1");
				$this->db->query("UPDATE oc_product_attribute SET text = '" . $this->db->escape($this->specialAttributeNew2[3]) . "' WHERE product_id = '" . $new_product_id . "' AND attribute_id = 80 AND language_id = 3");

				//ще одне, якщо є можливість то постав трігер розетка "да"
				$this->db->query("UPDATE oc_product SET custom_yml_mf = '1' WHERE product_id = '" . $new_product_id . "'");


				//SEOGEN
				$this->model_module_seogen->urlifyProduct($new_product_id);				
			}
		}
	}
}