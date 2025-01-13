<?php
	class DB {
		private $adaptor;
		private $doNotCache = array(
		'Cart',
		'Customer', 
		'ModelSaleOrder', 
		'ModelAccountOrder',
		'ModelCheckoutOrder',
		'ModelAccountCustomer',
		'ModelExtensionTotalCoupon',
		'ControllerCommonCart',
		'ControllerCheckoutCart',
		'ControllerCheckoutOctFastorder',
		'ControllerCheckoutCheckout',
		'ControllerAccountCustomer',
		'ControllerAccountAccount',
		'ControllerAccountAddress',
		'ControllerAccountEdit',
		'ControllerAccountOrder',
		'ControllerExtensionTotalShipping',
		'ControllerExtensionTotalCoupon',
		'ModelAccountWishlist',
		'ExtensionFeed',
		'ControllerExtensionFeedGoogleSitemap',
		'ControllerExtensionModuleOctPopupLogin',
		'ControllerExtensionModuleOctPopupPurchase',
		'ControllerHobotixSMS',
		'ControllerHobotixHoboprice',	
		);
		
		private $doNotCacheTables = array(
		'oc_cart',
		'oc_customer',
		'oc_address',
		'oc_order',
		'oc_api',
		'oc_api_session',
		'oc_coupon'
		);
		
		public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL, $registry = false) {
			$class = 'DB\\' . $adaptor;
			
			if (class_exists($class)) {
				
				$class_reflection = new ReflectionClass($class);
				$constructor = $class_reflection->getConstructor();
				
				if ($constructor->getNumberOfParameters() == 6){				
					$this->adaptor = new $class($hostname, $username, $password, $database, $port, $registry);				
					} else {
					$this->adaptor = new $class($hostname, $username, $password, $database, $port);			
				}			
				} else {
				throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
			}
		}
		
		public function query_old($sql, $params = array()) {
			return $this->adaptor->query($sql, $params);
		}
		
		public function query($sql, $params = array()) {
			
			$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS && !DEBUG_BACKTRACE_PROVIDE_OBJECT);
			
			foreach ($stack as $call){
				if (isset($call['class']) && in_array($call['class'],$this->doNotCache)){			
					return $this->ncquery($sql, $params);
				}
			}
			
			foreach ($this->doNotCacheTables as $table){
				if (strpos($sql, $table)){		
					return $this->ncquery($sql, $params);
				}
			}						
			
			return $this->adaptor->query($sql, $params);
		}
		
		public function ncquery($sql, $params = array()) {
			if (method_exists($this->adaptor, 'ncquery')){
				return $this->adaptor->ncquery($sql, $params);
				} else {
				return $this->adaptor->query($sql, $params);
			}
		}
		
		public function escape($value) {
			return $this->adaptor->escape($value);
		}
		
		public function countAffected() {
			return $this->adaptor->countAffected();
		}
		
		public function getLastId() {
			return $this->adaptor->getLastId();
		}
		
		public function connected() {
			return $this->adaptor->connected();
		}
	}			