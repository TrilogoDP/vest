<?php
class Url {
	private $url;
	private $ssl;

	// SEO CMS vars
	private $sc_registry = Array();
	// End of SEO CMS vars
    
	private $rewrite = array();

	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
	

 	// SEO CMS function
 	public function seocms_setRegistry($registry) {
		$this->sc_registry = $registry;
	}
	// End of SEO CMS function
    
	public function addRewrite($rewrite) {
		// SEO CMS code
		if (is_callable(array($this->sc_registry, 'get'))) {
			$this->sc_registry->get('controller_record_url')->before($rewrite);
		}
		// End of SEO CMS code
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {

        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');
    
        if ($route == 'checkout/checkout' && $get_route != 'checkout/checkout') {
          $route = 'checkout/oct_fastorder';
        }

        //if ($route == 'checkout/cart' && $get_route != 'checkout/cart') {
        //  $connection = 'SSL';
        //  $route = 'checkout/oct_fastorder';
        //}
      
		if ($this->ssl && $secure) {
			$url = $this->ssl . 'index.php?route=' . $route;
		} else {
			$url = $this->url . 'index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
		

		// SEO CMS code
		if (is_callable(array($this->sc_registry, 'get'))) {
			$url = $this->sc_registry->get('controller_record_url')->after($url);
		}
		//End of SEO CMS code
    
		return $url; 
	}
}