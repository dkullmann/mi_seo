<?php
App::import('Helper', 'MiSeo.Seo');

class SeoHelperTestCase extends CakeTestCase {

	function startTest() {
		$this->Seo = new SeoHelper();
	}

	function endTest() {
		unset($this->Seo);
		ClassRegistry::flush();
	}
}