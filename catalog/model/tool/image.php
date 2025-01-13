<?php
	class ModelToolImage extends Model {
		public function resize($filename, $width, $height, $gscl = false, $webp = true) {
			if (!is_file(DIR_IMAGE . $filename)) {
				if (is_file(DIR_IMAGE . 'no_image.jpg')) {
					$filename = 'no_image.jpg';
					} elseif (is_file(DIR_IMAGE . 'no_image.png')) {
					$filename = 'no_image.png';
					} else {
					return;
				}
			}
			
			$extension = pathinfo($filename, PATHINFO_EXTENSION);
			
			$image_old = $filename;
			$greyscale = $gscl?'grey':'';
			$image_new 		= 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . $greyscale . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;
			$image_new_webp = 'cachewebp/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . $greyscale . '-' . (int)$width . 'x' . (int)$height . '.' . 'webp';
			
			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
				list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);
				
				if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
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
					
					if ($greyscale){
						$image->greyscale();
					}
					
					$image->save(DIR_IMAGE . $image_new);
					} else {
					copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
				}
			}
			
			$gd = gd_info();
			if ($gd['WebP Support']) {
				if (!is_file(DIR_IMAGE . $image_new_webp) || (filectime(DIR_IMAGE . $image_new) > filectime(DIR_IMAGE . $image_new_webp))) {
					
					$path = '';
					
					$directories = explode('/', dirname($image_new_webp));
					
					foreach ($directories as $directory) {
						$path = $path . '/' . $directory;
						
						if (!is_dir(DIR_IMAGE . $path)) {
							@mkdir(DIR_IMAGE . $path, 0777);
						}
					}
					
					$image_webp = new Image(DIR_IMAGE . $image_old);
					$image_webp->resize($width, $height);
					
					if ($greyscale){
						$image_webp->greyscale();
					}
					
					
					$image_webp->save_webp(DIR_IMAGE . $image_new_webp);
				}
			}
			
			$imagepath_parts = explode('/', $image_new);
			$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));
			
			$uri = '';
			
			if (isset($this->request->server['REQUEST_URI'])) {
				$uri = $this->request->server['REQUEST_URI'];
			}
			
			if (stripos($uri, 'admin') === false) {
				if (isset($this->request->server['HTTP_ACCEPT']) && isset($this->request->server['HTTP_USER_AGENT'])) {
					if( strpos( $this->request->server['HTTP_ACCEPT'], 'image/webp' ) !== false ) {	
						$imagepath_parts = explode('/', $image_new_webp);
						$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));		
					}
				}
			}
			
			$new_image = str_replace('cache//', 'cache/', $new_image);
			$new_image = str_replace('cachewebp//', 'cachewebp/', $new_image);
			
			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				return $this->config->get('config_ssl') . 'image/' . $new_image;
				} else {
				return $this->config->get('config_url') . 'image/' . $new_image;
			}
		}
	}
