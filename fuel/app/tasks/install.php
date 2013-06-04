<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

/**
 * Robot example task
 *
 * Ruthlessly stolen from the beareded Canadian sexy symbol:
 *
 *		Derek Allard: http://derekallard.com/
 *
 * @package		Fuel
 * @version		1.0
 * @author		Phil Sturgeon
 */

class Install
{

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots
	 *
	 * or
	 *
	 * php oil r robots "Kill all Mice"
	 *
	 * @return string
	 */
	public static function run()
	{
		\Migrate::latest('default', 'app');
		\Migrate::latest('auth', 'package');

		$name = "index";
		$page = \Model_Page::forge();
		$page->name = $name;
		$page->parent_id = 0;
		$page->published = 0;
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->save();
		\Config::set("routes._root_", "/pages/load/".$page->clean_name);
		\Config::save("routes", "routes");
		\File::create(APPPATH."views/pages/load", $page->clean_name.".php", '<?php foreach($pages as $page) { echo ${$page}; }');

	}

	/**
	 * An example method that is here just to show the various uses of tasks.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots:protect
	 *
	 * @return string
	 */
	public static function with_admin($username, $email, $password)
	{
		self::run();

		// create a new user
		\Auth::create_user(
		    $username,
		    $password,
		    $email,
		    100
		);
	}
}

/* End of file tasks/robots.php */
