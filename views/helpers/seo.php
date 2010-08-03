<?php
class SeoHelper extends AppHelper {

	public $name = 'Seo';

	public $helpers = array('Html');

	public $settings = array();

	protected $_defaultSettings = array(
		'autoRun' => false,
		'defaultTitle' => 'Default Title',
		'defaultDescription' => 'Default Description',
		'defaultKeywords' => 'Default Keywords',
	);

	protected $_title = '';

	protected $_description = '';

	protected $_keywords = array();

	protected $_linkTags = array();

	protected $_metaTags = array();

	protected $_tagAttributes = array(
		'content-type' => array('http-equiv'),
		'content-style-type' => array('http-equiv'),
		'expires' => array('http-equiv'),
		'refresh' => array('http-equiv'),
		'set-cookie' => array('http-equiv'),

		'author' => array('name'),
		'description' => array('name'),
		'distribution' => array('name'),
		'generator' => array('name'),
		'keywords' => array('name'),
		'progid' => array('name'),
		'rating' => array('name'),
		'resource-type' => array('name'),
		'revisit-after' => array('name'),
		'revised' => array('name'),
		'robots' => array('name'),
		'title' => array('name'),
		'_default_' => array('name', 'scheme'),
	);

	protected $_linkAttributes = array(
		'charset',
		'href',
		'hreflang',
		'type',
		'rel',
		'rev',
		'media',
		'target'
	);

	protected $_commonAttributes = array(
		'id',
		'class',
		'lang',
		'xml:lang',
		'dir'
	);

	public function __construct($options = array()) {
		$this->settings = array_merge($this->_defaultSettings, $options);
		parent::__construct($options);
	}

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
 * addKeyword method
 *
 * @param mixed $keyword null
 * @return void
 * @access public
 */
	public function addKeyword($keyword = null) {
		$this->_keywords[$keyword] = $keyword;
	}

/**
 * addKeywords method
 *
 * @param array $keywords array()
 * @param string $seperator '
 * @return void
 * @access public
 */
	public function addKeywords($keywords = array(), $seperator = ',') {
		if (!is_array($keywords)) {
			$keywords = explode($seperator, $keywords);
		}
		foreach($keywords as $keyword) {
			$this->addKeyword($keyword);
		}
	}

/**
 * description method
 *
 * Set the description, replace multiple whitespace with one space, and strip tags
 *
 * @param mixed $description null
 * @return void
 * @access public
 */
	public function description($description = null) {
		$this->_description = trim(preg_replace('@\s+@s', ' ', strip_tags($description)));
	}

/**
 * description method
 *
 * @param mixed $description null
 * @return void
 * @access public
 */
	public function descriptionTag($description = null) {
		if (!$description) {
			$description = $this->_defaultDescription($this->_description);
		}
		return $this->metaTag('description', $description);
	}

	public function headerTags() {
	}

	public function keywords($keywords = null) {
		if ($keywords) {
			$this->addKeywords($keywords);
			$keywords = $this->_keywords;
		}
	}

/**
 * keywords method
 *
 * @param mixed $keywords null
 * @return void
 * @access public
 */
	public function keywordsTag($keywords = null) {
		if ($keywords) {
			$this->keywords($keywords);
		}
		$keywords = $this->_defaultKeywords($this->_keywords);
		return $this->metaTag('keywords', implode($keywords, ', '));
	}

	public function link($name = 'alternate', $content = '', $attributes = array()) {
		if (empty($content) && empty($attributes) && !empty($this->_linkTags[$name])) {
			return $this->_linkTags[$name];
		}

		if (!empty($this->_tagAttributes[$name])) {
			$allowedAttrs = $this->_tagAttributes[$name];
		} else {
			$allowedAttrs = $this->_tagAttributes['_default_'];
		}

		$allowedAttrs = array_merge($allowedAttrs, $this->_commonAttributes, $this->_linkAttributes);
		$attributes = array_intersect_key($attributes, array_flip($allowedAttrs));

		if ($attributes) {
			$unique = crc32(serialize($attributes));
		} else {
			$unique = '';
		}
		return $this->_linkTags[$name][$content . $unique] = $attributes;
	}

/**
 * linkTag method
 *
 * @param string $name 'alternate'
 * @param string $content ''
 * @param array $attributes array()
 * @return void
 * @access public
 */
	public function linkTag($name = 'alternate', $content = '', $attributes = array()) {
		$attributes = $this->meta($name, $content, $attributes);
		$test = current($attributes);
		if (is_array($test)) {
			$return = '';
			foreach($attributes as $link) {
				$return .= $this->Html->tag('link', null, $link);
			}
			return $return;
		}
		return $this->Html->tag('link', null, $attributes);
	}

/**
 * meta method
 *
 * @param string $name 'title'
 * @param string $content ''
 * @param array $attributes array()
 * @return void
 * @access public
 */
	public function meta($name = 'title', $content = '', $attributes = array()) {
		if (empty($content) && !empty($this->_metaTags[$name])) {
			return current($this->_metaTags[$name]);
		}

		if (!empty($this->_tagAttributes[$name])) {
			$allowedAttrs = $this->_tagAttributes[$name];
		} else {
			$allowedAttrs = $this->_tagAttributes['_default_'];
		}
		$attributes['name'] = $name;
		$attributes['content'] = $content;

		$allowedAttrs = array_merge($allowedAttrs, $this->_commonAttributes);
		$allowedAttrs[] = 'content';
		$attributes = array_intersect_key($attributes, array_flip($allowedAttrs));

		return $this->_metaTags[$name][$content] = $attributes;
	}

/**
 * metaTag method
 *
 * @param string $name 'title'
 * @param string $content ''
 * @param array $attributes array()
 * @return void
 * @access public
 */
	public function metaTag($name = 'title', $content = '', $attributes = array()) {
		$attributes = $this->meta($name, $content, $attributes);
		return $this->Html->tag('meta', null, $attributes);
	}

/**
 * removeKeyword method
 *
 * @param mixed $keyword null
 * @return void
 * @access public
 */
	public function removeKeyword($keyword = null) {
		unset ($this->_keywords[$keyword]);
	}

	public function reset($settings = array()) {
		$this->settings = array_merge($this->_defaultSettings, $settings);
		$this->_title = $this->_description = '';
		$this->_keywords = $this->_linkTags = $this->_metaTags = array();
	}

/**
 * title method
 *
 * @param mixed $title null
 * @return void
 * @access public
 */
	public function title($title = null) {
		$this->_title = $title;
	}

/**
 * title method
 *
 * @param mixed $title null
 * @return void
 * @access public
 */
	public function titleTag($title = null) {
		if (!$title) {
			$title = $this->_defaultDescription($this->_title);
		}
		return $this->metaTag('title', $title);
	}

/**
 * defaultDescription method
 *
 * @param string $description ''
 * @return void
 * @access protected
 */
	protected function _defaultDescription($description = '') {
		if ($description) {
			return $description;
		}
		return 'default description';
	}

/**
 * defaultKeywords method
 *
 * @param string $keywords ''
 * @return void
 * @access protected
 */
	protected function _defaultKeywords($keywords = '') {
		if ($keywords) {
			return $keywords;
		}
		return 'default keywords';
	}

/**
 * defaultTitle method
 *
 * @param string $title ''
 * @return void
 * @access protected
 */
	protected function _defaultTitle($title = '') {
		if ($title) {
			return $title;
		}
		return 'default title';
	}

/**
 * writeMeta method
 *
 * @param mixed $output
 * @param string $tagAttributes ''
 * @return void
 * @access protected
 */
	protected function _writeMeta(&$output, $tagAttributes = '') {
	}
}