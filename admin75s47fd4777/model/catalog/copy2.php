<?php

class ModelCatalogCopy2 extends Controller
{


	private $skuToCopy 	= 'PL-';
	private $skuNew 	= 'GM-';

	private $manufacturerIdNew = 603;

	private $descriptionNew = [
		'1' => '<div><span style="font-size: 12px;">Matte – разновидность гидрогелевой пленки повышенного комфорта.</span></div><div><span style="font-size: 12px;">Основное ее отличие от глянцевой противоударной пленки заключается именно в слое с матовым покрытием.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;">Многие люди выбирают эту пленку, поскольку она <b>имеет ряд преимуществ</b>:</span></div><div><span style="font-size: 12px;">- полностью покрывает экран</span></div><div><span style="font-size: 12px;">- отсутствие бликов</span></div><div><span style="font-size: 12px;">- не остаются следы от пальцев</span></div><div><span style="font-size: 12px;">- абсолютно прозрачная и практически не видна на экране</span></div><div><span style="font-size: 12px;">- отлично защищает от повреждений, царапин и порезов.</span></div><div><span style="font-size: 12px;">- не влияет на чувствительность</span></div><div><span style="font-size: 12px;">- имеет эффект самовосстановления – мелкие царапины исчезают на солнце</span></div><div><span style="font-size: 12px;">- благодаря матовому покрытию смартфон выглядит очень привлекательно и изысканно.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;">Если сравнивать срок службы защитного стекла и пленки, то следует отдать должное именно пленке, поскольку <b>средний срок ее службы составляет 1 год</b>.</span></div><div><span style="font-size: 12px;">Вырезаем индивидуально под каждый заказ.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;"><i><b>Можно ли поклеить противоударную гидрогелевую пленку iNobi Matte самостоятельно?</b></i></span></div><div><span style="font-size: 12px;">Да конечно. Клеится она совсем просто. По бокам пленка защищена защитными слоями. Сам процесс интуитивно прост и не требует особых навыков. При этом само положение пленки можно менять несколько раз, пока все будет идеально.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;"><b>Важно!</b></span></div><div><span style="font-size: 12px;">Не пытайтесь вытеснить все пузырьки воздуха, находящиеся под пленкой. Маленькие пузырьки исчезнут самостоятельно в течение 24 часов.</span></div><div><span style="font-size: 12px;">Ваше решение купить противоударную гидрогелевую пленку iNobi Matte однозначно правильно и оправдано, ведь вы получаете надежную защиту экрана смартфона, комфорт использования и сможете сохранить гаджет в отличном состоянии в течение длительного промежутка времени.</span></div>',

		'3'	=> '<div><span style="font-size: 12px;">Matte - різновид гідрогелевої плівки підвищеного комфорту.</span></div><div>Основна її відмінність від глянцевої протиударної плівки полягає саме у шарі з матовим покриттям.<br></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;">Багато людей обирають цю плівку, оскільки вона <b>має ряд переваг</b>:</span></div><div><span style="font-size: 12px;">- повністю покриває екран</span></div><div><span style="font-size: 12px;">- відсутність відблисків</span></div><div><span style="font-size: 12px;">- не залишаються сліди від пальців</span></div><div><span style="font-size: 12px;">- абсолютно прозора та практично не помітна на екрані</span></div><div><span style="font-size: 12px;">- відмінно захищає від пошкоджень, подряпин та порізів</span></div><div><span style="font-size: 12px;">- не впливає на чутливість</span></div><div><span style="font-size: 12px;">- має ефект самовідновлення - дрібні подряпини зникають на сонці</span></div><div><span style="font-size: 12px;">- завдяки матовому покриттю смартфон має дуже привабливий та вишуканий вигляд.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;">Якщо порівнювати термін служби захисного скла та плівки, то тут варто віддати належне саме плівці, оскільки <b>середній термін її служби становить 1 рік</b>.</span></div><div><span style="font-size: 12px;">Вирізаємо індивідуально під кожне замовлення.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;"><b><i>Чи можна поклеїти протиударну гідрогелеву плівку iNobi Matte самостійно?</i></b></span></div><div><span style="font-size: 12px;">Так, звичайно. Клеїться вона дуже просто. З обох боків плівка захищена захисними шарами. Сам процес інтуїтивно простий і не вимагає спеціальних навичок. При цьому, саме положення плівки можна міняти кілька разів, аж поки все буде ідеально.</span></div><div><span style="font-size: 12px;"><br></span></div><div><span style="font-size: 12px;"><b>Важливо!</b></span></div><div><span style="font-size: 12px;">Не намагайтесь витіснити всі пухирці повітря, які знаходяться під плівкою. Маленькі бульбашки зникнуть самостійно протягом 24 год.</span></div><div><span style="font-size: 12px;">Ваше рішення купити протиударну гідрогелеву плівку iNobi Matte однозначно правильне та виправдане, адже ви отримуєте надійний захист екрана смартфона, комфорт використання та зможете зберегти гаджет у відмінному стані протягом тривалого проміжку часу.</span></div>'
	];

	private $fakeDescriptionNew = [
		'1' => '<p><span style="font-size: 12px;">Гидрогелевая пленка iNobi Matte является любимой среди людей, которым важен внешний вид гаджета и комфорт использования. К ее особенностям относят следующие:</span></p><p><span style="font-size: 12px;">- прочная, надежная, хорошо защищающая экран от различных повреждений</span></p><p><span style="font-size: 12px;">- имеет матовое покрытие</span></p><p><span style="font-size: 12px;">- на ней не остаются следы от пальцев и воды</span></p><p><span style="font-size: 12px;">- прозрачная, покрывающая весь экран</span></p><p><span style="font-size: 12px;">- отсутствие бликов.</span></p><p><span style="font-size: 12px;">При бережном отношении служит в среднем 1 год.</span></p><p><span style="font-size: 12px;">Клеится легко и просто. Вырезаем индивидуально под каждый заказ.</span></p>',
		'3'	=> '<p><span style="font-size: 12px;">Гідрогелева плівка iNobi Matte є улюбленою серед людей, яким важливий зовнішній вигляд гаджета та комфорт використання. До її особливостей відносять такі:</span></p><p><span style="font-size: 12px;">- міцна, надійна, добре захищає екран від різноманітних пошкоджень</span></p><p><span style="font-size: 12px;">- має матове покриття</span></p><p><span style="font-size: 12px;">- на ній не залишаються сліди від пальців та води</span></p><p><span style="font-size: 12px;">- прозора, покриває весь екран</span></p><p><span style="font-size: 12px;">- відсутність відблисків.</span></p><p><span style="font-size: 12px;">При бережному ставленні служить в середньому 1 рік.</span></p><p><span style="font-size: 12px;">Клеїться легко та просто. Вирізаємо індивідуально під кожне замовлення.</span></p>'
	];

	private $imagesNew = [
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-0.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-1.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-2.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-3.jpg',		
	];

	private $imagesNew2 = [
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-ekran/gidrogeleva-plivka-na-ekran-universal-photo-0.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-1.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-2.jpg',
		'/data/vse_dlya_telepfoniv/plenki/hydrogel/plenka-na-panel/gidrogel-film-inobi-matte-universal-photo-3.jpg',		
	];

	private $specialAttributeNew = [
		'1' => 'Матовая, олеофобное покрытие, клеевой слой по всей поверхности, авторегенерация, точное соответствие модели',
		'3' => 'Матова, олеофобне покриття, клейовий шар по всій поверхні, авторегенерація, точна відповідність моделі'
	];

	private $specialAttributeNew2 = [
		'1' => 'Матовая',
		'3' => 'Матова'
	];

	private function refactorInobiMatte($new_product_id, $sku, $imagesNew){
		$this->load->model('catalog/product');
		$this->load->model('module/seogen');

			//Кількість
		$this->db->query("UPDATE oc_product SET quantity = 9999 WHERE product_id = '" . $new_product_id . "'");

				//Ціну міняємо на 299грн
		$this->db->query("UPDATE oc_product SET copied_from2 = '" . $row['product_id'] . "' WHERE product_id = '" . $new_product_id . "'");

				//Артикул залишаємо той самий міняємо лише PL на PM
		$this->db->query("UPDATE oc_product SET sku = '" . str_replace($this->skuToCopy, $this->skuNew, $sku) . "' WHERE product_id = '" . (int)$new_product_id . "'");

				//Ціну міняємо на 299грн
		$this->db->query("UPDATE oc_product SET price = 199 WHERE product_id = '" . $new_product_id . "'");

				//Виробника вибрати iNobi
		$this->db->query("UPDATE oc_product SET model = 'iNobi Matte' WHERE product_id = '" . $new_product_id . "'");

				//Виробника вибрати iNobi
		$this->db->query("UPDATE oc_product SET manufacturer_id = '" . (int)$this->manufacturerIdNew . "' WHERE product_id = '" . $new_product_id . "'");

				//Відео прибираємо
		$this->db->query("UPDATE oc_product SET youtube_single = '' WHERE product_id = '" . $new_product_id . "'");

				//Типа очищать скидки имеешь в виду?
		$this->db->query("DELETE FROM oc_product_special WHERE product_id = '" . $new_product_id . "'");

				//В назві міняємо "Противоударная гидрогелевая пленка Hydrogel Film" на "Гидрогелевая пленка iNobi Privacy Matte"
		foreach (['name', 'meta_h1', 'meta_description', 'meta_title'] as $field){
			$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, 'Противоударная гидрогелевая пленка Hydrogel Film', 'Гидрогелевая пленка iNobi Matte') WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
			$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, 'Протиударна гідрогелева плівка Hydrogel Film', 'Гідрогелева плівка iNobi Matte') WHERE product_id = '" . $new_product_id . "' AND language_id = 3");


				//В назві міняємо ", Transparent" на " (Антишпион)" (зверни увагу, що тут є пробіл)
			$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, ', Transparent', ', Матовая') WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
			$this->db->query("UPDATE oc_product_description SET `" . $field . "` = REPLACE(`" . $field . "`, ', Transparent', ', Матова') WHERE product_id = '" . $new_product_id . "' AND language_id = 3");
		}

				//Опис весь міняємо на той що в файлі, що сюди прикріпив. Опис маркетплейсу так само.
		$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($this->descriptionNew[1])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
		$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($this->descriptionNew[3])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 3");
		$this->db->query("UPDATE oc_product_description SET fake_description = '" . $this->db->escape(nl2br($this->fakeDescriptionNew[1])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
		$this->db->query("UPDATE oc_product_description SET fake_description = '" . $this->db->escape(nl2br($this->fakeDescriptionNew[3])) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 3");

		$description_1 = $this->db->query("SELECT name, description FROM oc_product_description WHERE product_id = '" . $new_product_id . "' AND language_id = 1")->row;
		$description_1 = ('<div><span style="font-size: 12px;"><b>' . $description_1['name'] . '</b></span></div>' . $description_1['description']);

		$description_3 = $this->db->query("SELECT name, description FROM oc_product_description WHERE product_id = '" . $new_product_id . "' AND language_id = 3")->row;
		$description_3 = ('<div><span style="font-size: 12px;"><b>' . $description_3['name'] . '</b></span></div>' . $description_3['description']);

		$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($description_1)) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 1");
		$this->db->query("UPDATE oc_product_description SET description = '" . $this->db->escape(($description_3)) . "' WHERE product_id = '" . $new_product_id . "' AND language_id = 3");

				//Замінити фото на ті що я додаю. Фото з заготовкою має бути перше.
		$this->db->query("UPDATE oc_product SET image = '" . $this->db->escape($imagesNew[0]) . "' WHERE product_id = '" . $new_product_id . "'");
		$this->db->query("DELETE FROM oc_product_image WHERE product_id = '" . (int)$new_product_id . "'");

		for ($i=1; $i<=count($imagesNew)-1; $i++){
			$this->db->query("INSERT INTO oc_product_image SET product_id = '" . (int)$new_product_id . "', image = '" . $this->db->escape($imagesNew[$i]) . "', video_in_product = '', sort_order = '". (int)$i ."'");
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


	public function copyInobiMatte($product_id){
		$this->load->model('catalog/product');
		$this->load->model('module/seogen');

		$row = $this->db->query("SELECT p.product_id, p.sku, pd.name FROM oc_product p LEFT JOIN oc_product_description pd ON (pd.product_id = p.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = 3")->row;

		if ($row) {
			if ( strpos($row['sku'], $this->skuToCopy) !== false ){

				$new_product_id = $this->model_catalog_product->copyProduct($row['product_id']);	

				if (mb_stripos($row['name'], 'панель')){
					$this->refactorInobiMatte($new_product_id, $row['sku'], $this->imagesNew);
				} else {
					$this->refactorInobiMatte($new_product_id, $row['sku'], $this->imagesNew2);
				}			
			}
		}
	}
}