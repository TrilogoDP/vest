<?php
class Cache {
	private $adaptor;
	private $isMobile = 0;
	private $isTablet = 0;

	public function __construct($adaptor, $expire = 3600, $registry = false) {
		$class = 'Cache\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($expire);
		} else {
			throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
		}
		
		if ($registry){
			$this->isMobile = $registry->get('isMobile');
			$this->isTablet = $registry->get('isTablet');
		}
	}
	
	public function get($key) {	
		if (defined('DEV_ENVIRONMENT')){
			return false;
		}

		if ($this->isMobile){
			$key .= 'ism';
		}
		
		if ($this->isTablet){
			$key .= 'ist';
		}
	
		return $this->adaptor->get($key);
	}
	
	public function flush() {
		return $this->adaptor->flush();
	}

	public function set($key, $value) {
		if (defined('DEV_ENVIRONMENT')){
			return false;
		}
		
		if ($this->isMobile){
			$key .= 'ism';
		}
		
		if ($this->isTablet){
			$key .= 'ist';
		}
	
	
		return $this->adaptor->set($key, $value);
	}

	public function delete($key) {
		return $this->adaptor->delete($key);
	}
}
