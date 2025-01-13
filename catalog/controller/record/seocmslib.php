<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2018 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerRecordSeocmslib extends Controller
{
	public $theme_folder;


	public function __construct($registry) {
		parent::__construct($registry);
		if (SC_VERSION > 15) {
			$this->load->model('extension/module');
		}
		$this->theme_folder = $this->get_theme_folder();
	}

	public function cont($cont) {
		if (!defined('DIR_CATALOG')) {
			$dir_catalog = DIR_APPLICATION;
		} else {
			$dir_catalog = DIR_CATALOG;
		}
       	$file  = $dir_catalog . 'controller/' . $cont . '.php';
       	$original_file = $file;
		if (function_exists('modification')) {
        	$file = modification($file);
        }

		if (class_exists('VQMod',false)) {
			if (VQMod::$directorySeparator) {
				if (strpos($file,'vq2-')!==FALSE) {
				} else {
					if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
						//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
						//exit;
					}
					if ($original_file != $file) {
						$file = VQMod::modCheck($file, $original_file);
					} else {
						$file = VQMod::modCheck($original_file);
					}
				}
			}
		}

		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
           return true;
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$this->registry = $this->cont_loading($cont, $file);
             	return true;
            } else {
				return false;
			}
		}
	}

	private function cont_loading($cont, $file) {
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
	}

	public function get_theme_folder() {
		if (SC_VERSION > 21 && !$this->config->get('config_template') || $this->config->get('config_template') == '') {
			return $theme_folder = $this->config->get($this->config->get('config_theme').'_directory');
		} else {
			return $this->config->get('config_template');
		}
	}

	public function template($name = '') {
		if ($name != '') {
			if (SC_VERSION > 23) {
				$ext = '.twig';
			} else {
				$ext = '.tpl';
			}

			if (file_exists(DIR_TEMPLATE . $this->get_theme_folder() . '/template/' . $name . $ext)) {

				if (SC_VERSION > 23) {
					$template = $this->get_theme_folder() . '/template/' . $name;
				} else {
					if (SC_VERSION < 23) {
						$template = $this->get_theme_folder() . '/template/' . $name . $ext;
					} else {
						$template = $name . $ext;
					}
				}

			} else {
				if (SC_VERSION > 23) {
					$template = 'default/template/' . $name;
				} else {
					if (SC_VERSION < 23) {
						$template = 'default/template/' . $name . $ext;
					} else {
						$template = $name . $ext;
					}
				}
			}
          	return $template;
		} else {
			return '';
		}
	}

	public function resizeme($filename, $width, $height, $type = true, $copy = true) {
 		if (!class_exists('PhpThumbFactory')) {
			require_once(DIR_SYSTEM . 'library/image/ThumbLib.php');
		}

        $http_image = getHttpImage($this);

		if ($type) {
			$asaptive_path = 'adaptive/';
		} else {
			$asaptive_path = '';
		}

        $dir_image = DIR_IMAGE;
        $ok = false;

		if (!file_exists($dir_image . $filename) || !is_file($dir_image . $filename)) {
			return false;
		}

		$info      = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/'.$asaptive_path . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists($dir_image . $new_image) || (filemtime($dir_image . $old_image) > filemtime($dir_image . $new_image))) {
			$path        = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists($dir_image . $path)) {
					@mkdir($dir_image . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize($dir_image . $old_image);
			if (($width_orig != $width || $height_orig != $height) || !$copy) {
		  		//********* code *************
				if ($type) {
					$thumb = PhpThumbFactory::create($dir_image . $old_image, Array('resizeUp'=> true));
					$ok = $thumb->adaptiveResize($width, $height)->save($dir_image . $new_image);
				} else {
					//$ok = $thumb->resize($width, $height)->save($dir_image . $new_image);
                    // opencart standart
					$image = new Image($dir_image . $old_image);
					$image->resize($width, $height);
					$image->save($dir_image . $new_image);
					$ok = true;
				}
		        //********* code *************
			} else {
				$ok =  copy($dir_image . $old_image, $dir_image . $new_image);
			}

			if ($ok) {
				 return $http_image.$new_image;
			}  else {
				 return '';
			}
		} else {
			 return $http_image.$new_image;
		}

    }

	public function resizeavatar($filename, $filename_original, $width, $height, $type = true, $copy = true) {
 		if (!class_exists('PhpThumbFactory')) {
			require_once(DIR_SYSTEM . 'library/image/ThumbLib.php');
		}
        $http_image = getHttpImage($this);
        $ok = false;
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return $ok;
		}

		$info      = pathinfo($filename_original);
		$extension = $info['extension'];
		$old_image = $filename;
        $new_image = $filename_original;

			if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
				$path        = '';
				$directories = explode('/', dirname(str_replace('../', '', $new_image)));
				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;
					if (!file_exists(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}

				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);
				if (($width_orig != $width || $height_orig != $height) || !$copy) {
		  			//********* code *************
		  			$thumb = PhpThumbFactory::create(DIR_IMAGE . $old_image, Array('resizeUp'=> true));
					if ($type)
						$ok = $thumb->adaptiveResize($width, $height)->save(DIR_IMAGE . $new_image);
					else
						$ok = $thumb->resize($width, $height)->save(DIR_IMAGE . $new_image);
		            //********* code *************
				} else {
					copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
					$ok = true;
				}

			 if ($ok)
			 return $http_image.$new_image;
			 else
			 return '';
		}

    }

	public function getLayoutIdSeocms($route) {

        $layout_id = false;

		$this->load->model('design/bloglayout');

		if ($route == 'record/blog' && isset($this->request->get['blog_id'])) {
			$path = explode('_', (string) $this->request->get['blog_id']);
			$layout_id = $this->model_design_bloglayout->getBlogLayoutId(end($path));
		}
		if ($route == 'record/record' && isset($this->request->get['record_id'])) {
			$layout_id = $this->model_design_bloglayout->getRecordLayoutId($this->request->get['record_id']);
		}
        if ($layout_id) {
        	return $layout_id;
        } else {
	        $sql = "SELECT * FROM " . DB_PREFIX . "layout_route WHERE '" . $this->db->escape($route) . "' LIKE CONCAT(route, '%') AND `route`<>'' AND store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY route DESC LIMIT 1";
            $query = $this->db->query($sql);
			if ($query->num_rows) {
				return $query->row['layout_id'];
			} else {
				return false;
			}
        }
	}

	public function sc_getLayout($layout_id) {
		if ($this->registry->get('sc_layout_id')) {
			$layout_id = $this->registry->get('sc_layout_id');
		}
		return $layout_id;
	}


	public function sc_getLayoutModules($layout_id, $position, $data) {
           		$blog_module = $this->config->get('blog_module');
	            $modules = $blog_module;
                if ($this->registry->get('sc_layout_id')) {
                	$layout_id = $this->registry->get('sc_layout_id');
                }
	       		$flag_layout = false;
				$request_url = ltrim($this->request->server['REQUEST_URI'], '/');
				if ($modules && is_array($modules)) {
					foreach ($modules as $num => $module) {
						if (isset($module['layout_id']) && is_array($module['layout_id'])) {
							if (is_array($module['layout_id']) || !isset($module['layout_id'])) {

								if (isset($module['url']) && trim($module['url']) != '') {
									if (isset($module['url_template'])) {
										$url_status = $module['url_template'];
									} else {
										$url_status = false;
									}
									if ($url_status == 1) {
										$pos = utf8_strpos($request_url, trim($module['url']));
										if ($pos === false) {
										} else {
											$modules[$num]['layout_id'] = $layout_id;
										}
									} else {
										if (trim($module['url']) == $request_url) {
											$modules[$num]['layout_id'] = $layout_id;
										}
									}
								} else {
									if ($module['status'] > 0) {
										if ($layout_id) {
											foreach ($module['layout_id'] as $key => $value) {
												if ($value == $layout_id) {
													$modules[$num]['layout_id'] = $layout_id;
												}
											}
										}
									}
								}
							}
						}
					}
				}
	            $this->config->set('blog_module', $modules);
	            $blog_module = $modules;



            if (isset($position)) {
            	$position  = $position;
            } else {
            	$position  = false;
            }

            if (is_array($blog_module) && !empty($blog_module)) {
	            foreach ($blog_module as $num => $val) {
	            	if (isset($val['position'])) {
		            	if ($position == $val['position']) {
		            	  	if (!is_array($val['layout_id'])) {
		            	     	if ($layout_id == $val['layout_id'] && $val['status']) {
			                       $val['code'] = 'blog';
			                       $this->config->set('blog_status', true);
			                       $this->registry->set('blog_position', $val['position'] );
			            	   	   $data[] = $val;
		            	   		}
		            	  	}
		            	}
	            	}
	            }
            }


     	if (is_array($data)) usort($data, 'commd');

     	return $data;

	}




/*
в config php

  	после
  	public function get($key) {

    вставить
		if ($key == 'blog_module') {
			if (isset($this->data[$key])) {
				$modules = $this->data[$key];
			} else  {
				$modules = null;
			}
            if ($modules != null && isset($this->data['seocmslib'])) {
            	$modules = $this->data['seocmslib']->config_15($modules);
            	return $modules;
            }
        }

*/
	public function config_15($modules) {

			$settings_general = $this->registry->get('config_ascp_settings');
			/*
			if (isset($settings_general['layout_url_status'])) {
				$url_status = $settings_general['layout_url_status'];
			} else {
				$url_status = false;
			}
			*/
			unset($settings_general);
            $request_url = ltrim($this->request->server['REQUEST_URI'], '/');
			$layout_id = $this->registry->get('agoo_layout_id');

			if (!$layout_id) {
				$layout_id = $this->getLayoutId_15();
			}

			if ($modules && is_array($modules)) {
				foreach ($modules as $num => $module) {
					if (isset($module['layout_id']) && is_array($module['layout_id'])) {
						if (is_array($module['layout_id']) || !isset($module['layout_id'])) {

							if (isset($module['url']) && trim($module['url']) != '') {
								if (isset($module['url_template'])) {
									$url_status = $module['url_template'];
								} else {
									$url_status = false;
								}
								if ($url_status == "1") {
									$pos = utf8_strpos($request_url, trim($module['url']));
									if ($pos === false) {
									} else {
										$modules[$num]['layout_id'] = $layout_id;
									}
								} else {
									if (trim($module['url']) == $request_url) {
										$modules[$num]['layout_id'] = $layout_id;
									}
								}
							} else {
								if ($module['status'] > 0) {
									if ($layout_id) {
										foreach ($module['layout_id'] as $key => $value) {
											if ($value == $layout_id) {
												$modules[$num]['layout_id'] = $layout_id;
											}
										}
									}
								}
							}
						}
					}
				}
			}

		return $modules;
	}

	private function getLayoutId_15($route = '') {
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/information');

        $layout_id = 0;

		if ($route == '') {
			if (isset($this->request->get['route'])) {
				$route = (string) $this->request->get['route'];
			} else {
				$route = 'common/home';
			}
		}

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$path      = explode('_', (string) $this->request->get['path']);
			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}
		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}
		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}
		if ($route == 'record/blog' && isset($this->request->get['blog_id'])) {
			$path      = explode('_', (string) $this->request->get['blog_id']);
			$layout_id = $this->model_design_bloglayout->getBlogLayoutId(end($path));
		}
		if ($route == 'record/record' && isset($this->request->get['record_id'])) {
			$layout_id = $this->model_design_bloglayout->getRecordLayoutId($this->request->get['record_id']);
		}
		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}
		if ($route == '' && $layout_id) {
			$this->registry->set('agoo_layout_id', $layout_id);
		}

		return $layout_id;
	}

	public function getRouteByLayoutId($layout_id) {
   		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_route WHERE layout_id='" . (int)$layout_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY route ASC LIMIT 1");
        if ($query->num_rows) {
			return $query->row['route'];
		} else {
			return false;
		}

	}

	public function model($model) {

	    $dir_application = DIR_APPLICATION;

		$model = str_replace('../', '', (string) $model);
		$file  = $dir_application . 'model/agoo/' . $model . '.php';

        if (!file_exists($file)) {
			$file = $dir_application . 'model/' . $model . '.php';
			if (!file_exists($file)) {
				if (defined('DIR_CATALOG')) {
					$dir_application = DIR_CATALOG;
				}
			}
		}

        $file  = $dir_application . 'model/agoo/' . $model . '.php';
        $original_file = $file;

  		if (function_exists('modification')) {
        	$file = modification($file);
        }
		if (class_exists('VQMod',false)) {
			if (VQMod::$directorySeparator) {
				if (strpos($file,'vq2-')!==FALSE) {
				} else {
					if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
						//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
						//exit;
					}
					if ($original_file != $file) {
						$file = VQMod::modCheck($file, $original_file);
					} else {
						$file = VQMod::modCheck($original_file);
					}
				}
			}
		}

		$class = 'agooModel' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		if (!file_exists($file)) {
			$file  = $dir_application . 'model/' . $model . '.php';
			$original_file = $file;

			if (function_exists('modification')) {
        		$file = modification($file);
        	}
			if (class_exists('VQMod',false)) {
				if (VQMod::$directorySeparator) {
					if (strpos($file,'vq2-')!==FALSE) {
					} else {
						if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
							//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
							//exit;
						}
						if ($original_file != $file) {
							$file = VQMod::modCheck($file, $original_file);
						} else {
							$file = VQMod::modCheck($original_file);
						}
					}
				}
			}

			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		}
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		}
	}


}