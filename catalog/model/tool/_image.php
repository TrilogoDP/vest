<?php
	class ModelToolImage extends Model {
		public function resize($filename, $width, $height, $quality = 70, $webp = false) {	
			
			if (!$quality || $quality = 'default'){
				$quality = 70;
			}
			
			if (!is_file(DIR_IMAGE . $filename)) {
				if (is_file(DIR_IMAGE . 'no_image.jpg')) {
					$filename = 'no_image.jpg';
					} elseif (is_file(DIR_IMAGE . 'no_image.png')) {
					$filename = 'no_image.png';
					} else {
					return;
				}
			}
			
			if ($webp) {
				$extension = 'webp';
				} else {
				$extension = pathinfo($filename, PATHINFO_EXTENSION);
			}
			
			$image_old = $filename;
			$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
			
			if ($quality != 70) {		
				$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . 'q' . (int)$quality . '.' . $extension;
				} else {
				$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
			}		
			
			//FAST DIRTY FIX FOR DOUBLESLASH
			$image_new = str_replace('cache//', 'cache/', $image_new);			
			
			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
				list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				
				if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_WEBP))) {
					return DIR_IMAGE . $image_old;
				}
				
				$path = '';
				
				$directories = explode('/', dirname($image_new));
				
				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;
					
					if (!is_dir(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}
				
				if ($width_orig != $width || $height_orig != $height) {
					$image = new Image(DIR_IMAGE . $image_old);
					$image->resize($width, $height);										
					
					if ($webp) {									
						$image->savewebp(DIR_IMAGE . $image_new);									
						} else {
						$image->save(DIR_IMAGE . $image_new);	
					}									
					
					} else {
					
					if ($webp) {
						$image = new Image(DIR_IMAGE . $image_old);
						$image->savewebp(DIR_IMAGE . $image_new);
						} else {
						copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
					}
				}
			}
			
			$imagepath_parts = explode('/', $image_new);
			$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));
			
			//FAST DIRTY FIX FOR DOUBLESLASH
			$new_image = str_replace('cache//', 'cache/', $new_image);
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				return $this->config->get('config_ssl') . 'image/' . $new_image;
				} else {
				return $this->config->get('config_url') . 'image/' . $new_image;
			}
		}
		
		public function getMime($file){
			$info = pathinfo($file);			
			$extension = strtolower($info['extension']);
			
			if ($extension == 'jpeg' || $extension == 'jpg') {
				return 'image/jpeg';
				} elseif ($extension == 'png') {
				return 'image/png';
				} elseif ($extension == 'gif') {
				return 'image/gif';
				} elseif ($extension == 'webp') {
				return 'image/webp';
			}
			
			return '';
			
		}
		
		public function resize_webp($filename, $width, $height, $quality = 70, $do_not_resize = false, $standard_scale = false){
			return $this->resize($filename, $width, $height, $quality, $webp = true);
		}
	}
