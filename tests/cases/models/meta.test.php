<?php
App::import('Model', 'MiSeo.Meta');

class MetaTestCase extends CakeTestCase {

	function startTest() {
		$this->Meta = ClassRegistry::init('MiSeo.Meta');
	}

	function endTest() {
		unset($this->Meta);
		ClassRegistry::flush();
	}
}