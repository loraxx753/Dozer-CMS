<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Dozer;

class Model_Page extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'clean_name',
		'parent_id',
		'published',
		'url',
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

	protected static $_has_many = array(
		'page_contents',
		'sub_pages' => array(
			'key_from' => 'id',
			'model_to' => '\Dozer\Model_Page',
			'key_to' => 'parent_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		)
	);
}
