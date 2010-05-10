<?php
class MetaFixture extends CakeTestFixture {

	public $name = 'Meta';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'foreign_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 36),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'url_fuzzy_match' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'compiled_tags' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $records = array(
		array(
			'id' => '4be822e8-de50-479d-8c7b-0d93f5b517c5',
			'model' => 'Post',
			'foreign_id' => '1',
			'url' => '/posts/view/1/slug',
			'url_fuzzy_match' => 0,
			'compiled_tags' => '<meta name="title" content="A title" /><meta name="description" content="Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum" /><meta name="keywords" content="Lorem ipsum,dolor,sit,amet,aliquet,feugiat,Convallis,morbi,fringilla,gravida,phasellus,feugiat,dapibus,velit,nunc,pulvinar,eget,sollicitudin,venenatis,cum" />',
			'created' => '2010-05-10 17:14:48',
			'modified' => '2010-05-10 17:14:48'
		),
	);
}