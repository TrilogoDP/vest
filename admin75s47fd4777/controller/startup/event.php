<?php
class ControllerStartupEvent extends Controller {
	public function index() {
		// Add events from the DB
		$this->load->model('extension/event');
		
		$results = $this->model_extension_event->getEvents();
		
		foreach ($results as $result) {
			if ((substr($result['trigger'], 0, 17) == 'admin75s47fd4777/') && $result['status']) {
				$this->event->register(substr($result['trigger'], 17), new Action($result['action']));
			}
		}		
	}
}