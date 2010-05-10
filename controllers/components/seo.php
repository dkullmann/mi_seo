<?php
/**
 * Short description for seo.php
 *
 * Long description for seo.php
 *
 * PHP versions 4 and 5
 *
 * Copyright (c) 2009, Andy Dawson
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2009, Andy Dawson
 * @link          www.ad7six.com
 * @package       mi
 * @subpackage    mi.controllers.components
 * @since         v 1.0
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'Router');

/**
 * SeoComponent class
 *
 * @uses          Object
 * @package       mi
 * @subpackage    mi.controllers.components
 */
class SeoComponent extends Object {

/**
 * components property
 *
 * @var array
 * @access public
 */
	var $components = array(
		'RequestHandler',
	);

/**
 * settings property
 *
 * @var array
 * @access public
 */
	var $settings = array(
		'autoRun' => true,
		'sortNamedParams' => true,
		'maxArgs' => null
	);

/**
 * getInstance method
 *
 * This component is/may be called statically by AppHelper::url, this method allows the component
 * instance to be found
 *
 * @param mixed $_this null
 * @return void
 * @access public
 */
	function &getInstance(&$_this = null) {
		static $instance = array();
		if (!$instance) {
			if (!$_this) {
				$_this = new SeoComponent();
				$_this->params['site'] = false;
			}
			$instance[0] =& $_this;
		}
		return $instance[0];
	}

/**
 * initialize method
 *
 * @param mixed $Controller
 * @return void
 * @access public
 */
	function initialize(&$Controller, $config = array()) {
		$this->settings = array_merge($this->settings, $config);
		$this->Controller =& $Controller;
		$this->params =& $Controller->params;
		$this->getInstance($this);
		if ($this->settings['autoRun'] && $Controller->name != 'CakeError') {
			Configure::write('Seo.settings', $this->settings);
			$this->check($this->settings['maxArgs']);
		}
	}

/**
 * beforeRedirect method
 *
 * @param mixed $Controller
 * @param mixed $url
 * @param mixed $status
 * @param mixed $exit
 * @return void
 * @access public
 */
	function beforeRedirect(&$Controller, $url, $status, $exit) {
		if ($this->settings['sortNamedParams']) {
			return $this->sortUrl($url);
		}
	}

/**
 * Verify that the current url matches the (first) Router definition and prevent duplicate urls existing to point at
 * the same content.
 * Disabled for requestAction and POST requests
 *
 * @return void
 * @access public
 */
	function check($maxArgs = null) {
		$C =& $this->Controller;
		if (isset($C->params['requested']) || $this->RequestHandler->isAjax() || $C->data) {
			return;
		}
		$here = '/' . trim(str_replace($C->webroot, '/', $C->here), '/');
		if ($maxArgs !== null) {
			if ($maxArgs) {
				list($url) = array_chunk($C->params['pass'], $maxArgs);
			} else {
				$url = array();
			}
			$url = $url + $C->params['named'];
		} else {
			$url = $C->passedArgs;
		}
		if ($this->settings['sortNamedParams']) {
			$url = $this->sortUrl($url);
		}
		$normalized = Router::normalize($url);
		if ($normalized !== $here) {
			if (Configure::read()) {
				$C->Session->setFlash('SEOComponent: Redirecting from "' . $here . '" to "' . $normalized . '"');
			}
			return $C->redirect($normalized, 301);
		}
	}

/**
 * sortUrl method
 *
 * Sort the named parameters in the url alphabetically. prevents two urls each containing the same named parameters in
 * different orders ('.../page:2/sort:id', '.../sort:id/page:2') from being considered different
 * Also called statically by AppHelper::url
 *
 * @param mixed $url
 * @return mixed $url
 * @access public
 */
	function sortUrl($url = null) {
		if (is_string($url)) {
			return $url;
		}
		if ($url) {
			$named = array();
			$skip = array('bare', 'action', 'controller', 'plugin', 'ext', '?', '#', 'prefix', Configure::read('Routing.admin'));
			$keys = array_values(array_diff(array_keys($url), $skip));
			foreach ($keys as $key) {
				if (!is_numeric($key)) {
					$named[$key] = $url[$key];
				}
			}
		} elseif (isset($this->Controller)) {
			$url = $this->Controller->passedArgs;
			$named = $this->Controller->params['named'];
		} elseif (isset($this->parms['pass'])) {
			$url = $this->params['pass'];
			$named = $this->params['named'];
		} else {
			return $url;
		}
		if (!$named) {
			return $url;
		}
		ksort($named);
		return am($named, $url);
	}

/**
 * url method
 *
 * @param mixed $url
 * @param bool $full false
 * @return void
 * @access public
 */
	function url($url, $full = false) {
		if (is_a($this, 'SeoComponent')) {
			$_this =& $this;
		} else {
			$_this =& SeoComponent::getInstance();
		}
		$domain = null;
		if (is_array($url)) {
			$url = $_this->sortUrl($url);
			if (array_key_exists('domain', $url)) {
				if (!strpos($url['domain'], '.')) {
					$site = Configure::read('Site.id');
					if ($url['domain'] == $site) {
						unset($url['domain']);
					} else {
						$url['domain'] = MiCache::data('Site', 'field', 'domain', array('id' => $url['domain']));
					}
				} elseif ($url['domain'] == Configure::read('Seo.maindomain')) {
					unset($url['domain']);
				}
				if (!empty($url['domain'])) {
					$domain = $url['domain'];
				}
				unset($url['domain']);
			}
		}
		$_url = Router::url($url);
		if (!$domain) {
			return $_url;
		}
		static $s = false;
		if ($s === false) {
			if (env('HTTPS')) {
				$s ='s';
			} else {
				$s = null;
			}
		}
		return 'http'.$s.'://'. $domain  . $_url;
	}
}