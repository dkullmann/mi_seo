<?php
class Meta extends MiSeoAppModel {

	public $name = 'Meta';

/**
 * useTable property
 *
 * Avoid inflector problems
 *
 * @var string 'metas'
 * @access public
 */
	public $useTable = 'metas';

	public $actsAs = array(
		'MiEnums.Enum' => array(
			'fields' => array('url_fuzzy_match')
		)
	);

	public $validate = array(
		'url_fuzzy_match' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
	);

	public $hasMany = array(
		'MiSeo.MetaTag' => array(
		)
	);
}