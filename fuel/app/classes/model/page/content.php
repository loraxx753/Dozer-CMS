
<?php

class Model_Page_Content extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'page_id',
		'name',
		'contents',
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
	protected static $_table_name = 'page_contents';
	
}
