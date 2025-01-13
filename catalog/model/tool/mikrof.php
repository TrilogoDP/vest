<?php
class ModelToolMikrof extends Model {
	public function formatStringdisplay($string) {
		$string = preg_replace('~<style(.*?)</style>~Usi', "", htmlspecialchars_decode($string));
		return $string; 	
	}
}
