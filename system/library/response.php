<?php
	class Response {
		private $headers = array();
		private $level = 0;
		private $output;

	//Jet Cache vars
	private $sc_registry = Array();
	//End of Jet Cache vars
    
		

 	public function seocms_setRegistry($registry) {
		$this->sc_registry = $registry;
	}

 	public function seocms_getHeaders() {
		return $this->headers;
	}
 	public function seocms_getOutput() {
		return $this->output;
	}
    
		public function addHeader($header) {
			$this->headers[] = $header;
		}
		
		public function redirect($url, $status = 301) {
			
			/*
			if ($this->request->server['REMOTE_ADDR'] == '37.57.184.228'){
				ob_start();
				debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
				$trace = ob_get_contents();
				ob_end_clean(); 
				
				print '<pre>';
				print_r ($trace);
				print '</pre>';
				exit();
			}
			*/
			
			
			header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
			exit();
		}
		
		public function setCompression($level) {
			$this->level = $level;
		}
		
		public function getOutput() {
			return $this->output;
		}
		
		public function minifyOutput(){
			require DIR_SYSTEM . 'library/TinyHtmlMinifier.php';
			$options = array();
			$minifier = new TinyHtmlMinifier($options);		
			
			$this->setOutput = $minifier->minify($this->getOutput());
		}
		
		public function setOutput($output) {
			
			//	$output = str_ireplace('https://vest.in.ua', 'https://test.vest.in.ua', $output);
			
			$this->output = $output;
		}
		
		private function compress($data, $level = 0) {
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
				$encoding = 'gzip';
			}
			
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
				$encoding = 'x-gzip';
			}
			
			if (!isset($encoding) || ($level < -1 || $level > 9)) {
				return $data;
			}
			
			if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
				return $data;
			}
			
			if (headers_sent()) {
				return $data;
			}
			
			if (connection_status()) {
				return $data;
			}
			
			$this->addHeader('Content-Encoding: ' . $encoding);
			
			return gzencode($data, (int)$level);
		}
		
		public function output() {						

			if (is_callable(array($this->sc_registry, 'get')) && $this->output) {
            	if (defined('DIR_CATALOG')) {
            	} else {
	           		if (function_exists('agoo_cont')) {
		           		agoo_cont('record/pagination', $this->sc_registry);
						$this->output = $this->sc_registry->get('controller_record_pagination')->setPagination($this->output);
						unset($this->controller_record_pagintation);

	            		if ($this->sc_registry->get('config')->get('google_sitemap_blog_status')) {
		            		if (agoo_cont('record/google_sitemap_blog', $this->sc_registry)) {
		                		$this->output = $this->sc_registry->get('controller_record_google_sitemap_blog')->setSitemap($this->output);
		                	}
	                	}
                	}
                }
			}
    
			if ($this->output) {
				$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
				if (!headers_sent()) {
					foreach ($this->headers as $header) {
						header($header, true);
					}
				}
				
				echo $output;
			}
		}
	}