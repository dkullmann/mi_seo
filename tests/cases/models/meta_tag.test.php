<?php
App::import('Model', 'MiSeo.MetaTag');

class MetaTagTestCase extends CakeTestCase {

	function startTest() {
		$this->MetaTag = ClassRegistry::init('MiSeo.MetaTag');
	}

	function endTest() {
		unset($this->MetaTag);
		ClassRegistry::flush();
	}

}