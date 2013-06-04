
<?php

class Model_Page extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'clean_name',
		'parent_id',
		'published',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	protected static $_table_name = 'pages';

	protected static $_has_many = array('page_contents');
}
