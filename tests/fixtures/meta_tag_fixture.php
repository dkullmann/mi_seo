<?php
class MetaTagFixture extends CakeTestFixture {

	public $name = 'MetaTag';

	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'meta_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'compiled' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'http-equiv' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 30),
		'content' => array('type' => 'string', 'null' => false, 'default' => NULL),
		'lang' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 5),
		'dir' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 3),
		'scheme' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	public $records = array(
		array(
			'id' => '4be8212b-46d8-4f2a-99c5-0909f5b517c5',
			'meta_id' => '4be822e8-de50-479d-8c7b-0d93f5b517c5',
			'compiled' => '<meta name="title" content="A title" />',
			'name' => 'title',
			'http-equiv' => '',
			'content' => 'A title',
			'lang' => 'en_EN',
			'dir' => '',
			'scheme' => '',
			'created' => '2010-05-10 17:07:23',
			'modified' => '2010-05-10 17:07:23'
		),
		array(
			'id' => '5be8212b-46d8-4f2a-99c5-0909f5b517c5',
			'meta_id' => '4be822e8-de50-479d-8c7b-0d93f5b517c5',
			'compiled' => '<meta name="title" content="A title" />',
			'name' => 'description',
			'http-equiv' => '',
			'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum',
			'lang' => 'en_EN',
			'dir' => '',
			'scheme' => '',
			'created' => '2010-05-10 17:07:23',
			'modified' => '2010-05-10 17:07:23'
		),
		array(
			'id' => '6be8212b-46d8-4f2a-99c5-0909f5b517c5',
			'meta_id' => '4be822e8-de50-479d-8c7b-0d93f5b517c5',
			'compiled' => '<meta name="title" content="A title" />',
			'name' => 'keywords',
			'http-equiv' => '',
			'content' => 'Lorem,ipsum,dolor,sit,amet,aliquet,feugiat,Convallis,morbi,fringilla,gravida,phasellus,feugiat,dapibus,velit,nunc,pulvinar,eget,sollicitudin,venenatis,cum',
			'lang' => 'en_EN',
			'dir' => '',
			'scheme' => '',
			'created' => '2010-05-10 17:07:23',
			'modified' => '2010-05-10 17:07:23'
		),
	);
}