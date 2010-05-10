<?php
class SeoHelper extends AppHelper {

/**
 * afterLayout method
 *
 * Currently nothing - inject/rewrite head
 *
 * @return void
 * @access public
 */
	public function afterLayout() {
		return;
		$View = ClassRegistry::getObject('view');
		$meta = $this->meta();
		$this->_writeMeta($View->output, $meta);
	}

/**
 * meta method
 *
 * Returns the meta tags for the current page
 *
 * @return void
 * @access public
 */
	public function meta() {
	}

/**
 * writeMeta method
 *
 * @param mixed $output
 * @param string $metaTags ''
 * @return void
 * @access protected
 */
	protected function _writeMeta(&$output, $metaTags = '') {
	}
}