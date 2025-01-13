<?
	class ControllerHobotixRebuildImageIndex extends Controller {
		private $error = array();
		var $version = 1.1;
		var $tables_to_check = array(
		'banner_image' => 'image',
		'category' => 'image',
		'download' => 'filename',
		'manufacturer' => 'image',
		'option_value' => 'image',
		'product' => 'image',
		'product_image' => 'image',
		'setting' => 'value',
		'voucher_theme' => 'image',
		);
		
		private function getImagesFromHTML($html){
			//This will return the HTML source of the page as a string.
			$htmlString = $html;
			
			//Create a new DOMDocument object.
			$htmlDom = new DOMDocument;
			
			//Load the HTML string into our DOMDocument object.
			@$htmlDom->loadHTML($htmlString);
			
			//Extract all img elements / tags from the HTML.
			$imageTags = $htmlDom->getElementsByTagName('img');
			
			//Create an array to add extracted images to.
			$extractedImages = array();
			
			//Loop through the image tags that DOMDocument found.
			foreach($imageTags as $imageTag){
				
				//Get the src attribute of the image.
				$imgSrc = $imageTag->getAttribute('src');
				
				//Get the alt text of the image.
				$altText = $imageTag->getAttribute('alt');
				
				//Get the title text of the image, if it exists.
				$titleText = $imageTag->getAttribute('title');
				
				//Add the image details to our $extractedImages array.
				$extractedImages[] = array(
				'src' => $imgSrc,
				'alt' => $altText,
				'title' => $titleText
				);
			}
			
			return $extractedImages;
			
		}
		
		private function replaceSRC($src){
			$remove = array(
				'https://vest.in.ua/image//',
				'https://vest.in.ua/image/',			
			);
			
			echo $src . ' -> ';
		
			$src = str_replace($remove, '', $src);
			$src = ltrim($src, '/');
			
			echo $src . PHP_EOL;
			
			return $src;
		}
		
		
		public function rebuildIndex(){
			$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . 'needlessimage_view`');
			$sql = "CREATE TABLE `" . DB_PREFIX . "needlessimage_view` ( `image` LONGTEXT NOT NULL ) ENGINE = InnoDB;";
			$this->db->ncquery($sql);
			
			$sql = "INSERT INTO `" . DB_PREFIX . "needlessimage_view`";
			$parts = array();
			foreach ($this->tables_to_check as $name => $column) {
				$parts[] = 'SELECT DISTINCT `' . $column . '` image FROM `' . DB_PREFIX . $name . '` WHERE `' . $column . "` LIKE 'catalog/%'";
			}
			
			$sql .= implode(' UNION ', $parts);
			
			$this->db->ncquery($sql);
			
			echo 'Отбор закончен' . PHP_EOL;
			
			$query = $this->db->ncquery("SELECT description FROM `" . DB_PREFIX . "product_description` WHERE 1");
			
			foreach ($query->rows as $row){
				
				$images = $this->getImagesFromHTML(html_entity_decode($row['description']));
				
				foreach ($images as $image){
					$sql = "INSERT INTO `" . DB_PREFIX . "needlessimage_view` SET image = '" . $this->db->escape($this->replaceSRC($image['src'])) . "'";
				}
			
			}
		}
	}		