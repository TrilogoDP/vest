<?php
class Document {
	private $title;

	// SEO CMS vars
	private $sc_og_image;
	private $sc_og_description;
	private $sc_og_title;
	private $sc_og_type;
	private $sc_og_url;
	private $sc_robots;
	private $sc_hreflang = array();
	private $sc_removelinks = array();
	private $canonical;
	private $description;
	private $keywords;
	private $noindex = false;

	private $links = array();
	private $styles = array();
	private $scripts = array();
	private $og_image;
	private $robots;
	public $pixel;

	private $tc_og;


	public function setTc_og($data){$this->tc_og = $data;}
	public function getTc_og(){return $this->tc_og;}

	public function setPixel($pixel) {
		$this->pixel = $pixel;
	}
	public function getPixel() {
		return $this->pixel;
	}	

	public function setNoindex($state = false) {
		$this->noindex = $state;
	}

	public function isNoindex() {
		return $this->noindex;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	public function getKeywords() {
		return $this->keywords;
	}

	public function addLink($href, $rel) {
		if ($rel == 'canonical'){
			$this->setCanonical($href);
		} else {
			$this->links[$href] = array(
				'href' => $href,
				'rel'  => $rel
			);
		}
	}

	public function deleteLink($rel) {
		foreach ($this->links as $href => $link) {
			if ($link['rel'] == $rel) {
				unset($this->links[$href]);
			}
		}
	}

	public function setSCRobots($str) {
		$this->sc_robots = $str;
	}
	public function getSCRobots() {
		return $this->sc_robots;
	}
	public function setSCHreflang($hreflang = array()) {
		$this->sc_hreflang = $hreflang;
	}
	public function getSCHreflang() {
		return $this->sc_hreflang;
	}
	public function setSCOgImage($image) {
		$this->sc_og_image = $image;
	}
	public function getSCOgImage() {
		return $this->sc_og_image;
	}
	public function setSCOgType($og_type) {
		$this->sc_og_type = $og_type;
	}
	public function getSCOgType() {
		return $this->sc_og_type;
	}
	public function setSCOgTitle($title) {
		$this->sc_og_title = $title;
	}
	public function getSCOgTitle() {
		return $this->sc_og_title;
	}
	public function setSCOgDescription($description) {
		$this->sc_og_description = $description;
	}
	public function getSCOgDescription() {
		return $this->sc_og_description;
	}
	public function setSCOgUrl($url) {
		$this->sc_og_url = $url;
	}
	public function getSCOgUrl() {
		return $this->sc_og_url;
	}
	public function removeSCLink($href) {
		$this->sc_removelinks[$href] = $href;
	}

	public function getLinks() {

		if (is_array($this->links) && !empty($this->links)) {
			foreach ($this->links as $links => $linksarray) {
				if (isset($this->sc_removelinks) && !empty($this->sc_removelinks) && isset($this->sc_removelinks[$links])) {
					unset($this->links[$links]);
				}
			}
		}

		return $this->links;
	}

	public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
		$this->styles[$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}

	public function getStyles() {
		return $this->styles;
	}

	public function addScript($href, $postion = 'header') {
		$this->scripts[$postion][$href] = $href;
	}

	public function getScripts($postion = 'header') {
		if (isset($this->scripts[$postion])) {
			return $this->scripts[$postion];
		} else {
			return array();
		}
	}

	public function setRobots($robots) {
		$this->robots = $robots;
	}

	public function getRobots() {
		return $this->robots;
	}

	public function setCanonical($canonical) {
		$this->canonical = $canonical;
	}

	public function getCanonical() {
		return $this->canonical;
	}

	public function setOgImage($image) {
		$this->og_image = $image;
	}
	public function getOgImage() {
		return $this->og_image;
	}
}
