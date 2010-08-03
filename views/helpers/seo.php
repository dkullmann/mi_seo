<?php
/**
 * Seo Helper
 *
 * This helper is used to either explicitly or implicitly generate the meta tags for a page
 *
 * PHP version 5
 *
 * Copyright (c) 2010, Andy Dawson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright (c) 2010, Andy Dawson
 * @link          www.ad7six.com
 * @package       mi_seo
 * @subpackage    mi_seo.views.helpers
 * @since         v 1.0 (03-Aug-2010)
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * SeoHelper class
 *
 * @uses          AppHelper
 * @package       mi_seo
 * @subpackage    mi_seo.views.helpers
 */
class SeoHelper extends AppHelper {

/**
 * name property
 *
 * @var string 'Seo'
 * @access public
 */
	public $name = 'Seo';

/**
 * run time settings property
 *
 * @var array
 * @access public
 */
	public $settings = array();

/**
 * Helper defaults - can be overriden when loading the helper
 *
 * @var array
 * @access protected
 */
	protected $_defaultSettings = array(
		'autoRun' => false,
		'defaultTitle' => 'Default Title',
		'defaultDescription' => 'Default Description',
		'defaultKeywords' => 'Default Keywords',
	);

/**
 * The meta title for the current page
 *
 * @var string ''
 * @access protected
 */
	protected $_title = '';

/**
 * The meta description for the current page
 *
 * @var string ''
 * @access protected
 */
	protected $_description = '';

/**
 * an array of keywords for the current page
 *
 * @var array
 * @access protected
 */
	protected $_keywords = array();

/**
 * An indexed stack of 'meta' links
 *
 * @var array
 * @access protected
 */
	protected $_linkTags = array();

/**
 * An indexed stack of meta tag data
 *
 * @var array
 * @access protected
 */
	protected $_metaTags = array();

/**
 * which tags can have which attrbutes
 *
 * @var array
 * @access protected
 */
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

/**
 * attributes specific only to links
 *
 * @var array
 * @access protected
 */
	protected $_linkAttributes = array(
		'charset',
		'href',
		'hreflang',
		'type',
		'rel',
		'rev',
		'media',
		'target',
		'title'
	);

/**
 * attributes any html entity can have
 *
 * @var array
 * @access protected
 */
	protected $_commonAttributes = array(
		'id',
		'class',
		'lang',
		'xml:lang',
		'dir'
	);

/**
 * construct method
 *
 * @param array $options array()
 * @return void
 * @access public
 */
	public function __construct($options = array()) {
		$this->settings = array_merge($this->_defaultSettings, $options);
		parent::__construct($options);

		if (!empty($this->settings['title'])) {
			$this->title($this->settings['title']);
		}

		if (!empty($this->settings['description'])) {
			$this->description($this->settings['description']);
		}

		if (!empty($this->settings['keywords'])) {
			$this->keywords($this->settings['description']);
		}

		if (!empty($this->settings['meta'])) {
			foreach($this->settings['meta'] as $args) {
				call_user_func_array(array($this, 'meta'), $args);
			}
		}

		if (!empty($this->settings['links'])) {
			foreach($this->settings['links'] as $args) {
				call_user_func_array(array($this, 'link'), $args);
			}
		}
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

/**
 * headerTags method
 *
 * @return void
 * @access public
 */
	public function headerTags($seperator = "\n") {
		$return = array(
			$this->titleTag(),
			$this->descriptionTag(),
			$this->keywordsTag(),
		);
		foreach($this->_metaTags as $name) {
			$return[] = $this->_metaTag($name);
		}
		foreach($this->_linkTags as $_intermediate) {
			$return = array_merge($return, explode('$&&$', $this->_linkTag($name, '$&&$')));
		}
		return implode($return, $seperator);
	}

/**
 * keywords method
 *
 * @param mixed $keywords null
 * @return void
 * @access public
 */
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
		return $this->metaTag('keywords', implode($keywords, ','));
	}

/**
 * link method
 *
 * @param string $rel 'alternate'
 * @param string $href ''
 * @param array $attributes array()
 * @return void
 * @access public
 */
	public function link($rel = 'alternate', $href = '', $attributes = array()) {
		if (empty($href) && empty($attributes) && !empty($this->_linkTags[$rel])) {
			return $this->_linkTags[$rel];
		}

		if (!empty($this->_tagAttributes[$rel])) {
			$allowedAttrs = $this->_tagAttributes[$rel];
		} else {
			$allowedAttrs = $this->_tagAttributes['_default_'];
		}

		$allowedAttrs = array_merge($allowedAttrs, $this->_commonAttributes, $this->_linkAttributes);
		$attributes = array_intersect_key($attributes, array_flip($allowedAttrs));
		$attributes['rel'] = $rel;
		$attributes['href'] = $href;

		if ($attributes) {
			$unique = crc32(serialize($attributes));
		} else {
			$unique = '';
		}
		return $this->_linkTags[$rel][$href . $unique] = $attributes;
	}

/**
 * linkTag method
 *
 * @param string $rel 'alternate'
 * @param string $href ''
 * @param array $attributes array()
 * @return void
 * @access public
 */
	public function linkTag($rel = 'alternate', $href = '', $attributes = array(), $_seperator = "\n") {
		$attributes = $this->link($rel, $href, $attributes);
		$test = current($attributes);
		if (is_array($test)) {
			$return = '';
			foreach($attributes as $link) {
				$return .= sprintf('<link%s />', $this->_parseAttributes($link)) . $_seperator;
			}
			return $return;
		}
		return sprintf('<link%s />', $this->_parseAttributes($attributes));
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
		return sprintf('<meta%s />', $this->_parseAttributes($attributes));
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

/**
 * reset to uninitialized state
 *
 * @param array $settings array()
 * @return void
 * @access public
 */
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