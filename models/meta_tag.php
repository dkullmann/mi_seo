<?php
class MetaTag extends MiSeoAppModel {

	public $name = 'MetaTag';

	public $validate = array(
		'meta_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'compiled' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'http-equiv' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'content' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'lang' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'dir' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'scheme' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

	public $belongsTo = array(
		'MiSeo.Meta'
	);
}