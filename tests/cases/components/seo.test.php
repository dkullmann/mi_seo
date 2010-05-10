<?php
App::import('Component', 'Seo');

class SeoComponentTestCase extends CakeTestCase {

	function startTest() {
		$this->Seo = new SeoComponent();
	}

	function endTest() {
		unset($this->Seo);
		ClassRegistry::flush();
	}
}