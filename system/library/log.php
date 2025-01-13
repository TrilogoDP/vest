<?php
	class Log {
		private $handle;
		
		public function __construct($filename) {
			$this->handle = fopen(DIR_LOGS . $filename, 'a');
		}
		
		public function write($message) {
			fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
		}
		
		public function printsql($sql){
			
			require_once(DIR_SYSTEM . '/library/SqlFormatter.php');
			$sql = SqlFormatter::format($sql);
			
			$this->printr($sql);			
		}
		
		public function backtrace(){
			
			ob_start();
			debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			$trace = ob_get_contents();
			ob_end_clean(); 
			
			$this->printr($trace);
		}
		
		public function printr($message) {
			print '<pre>';
			print_r ($message);
			print '</pre>';
		}
		
		public function __destruct() {
			fclose($this->handle);
		}
	}		