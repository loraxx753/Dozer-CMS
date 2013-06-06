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
		self::success("Getting submodules...");
		exec("git submodule init");
		exec("git submodule update");

		self::success("Updating composer...");
		exec("php composer.phar self-update");
		exec("php composer.phar update");


		$db_host = \Cli::prompt("What's your database's host (usually localhost)");
		$db_name = \Cli::prompt("What about your database's name?");
		$db_username = \Cli::prompt("Your username for the database?");
		$db_password = \Cli::prompt("Lastly, you're databases password?");

		self::success("Trying to connect to your database...");

		\File::copy(APPPATH."config/development/db_template.php", APPPATH."config/development/db.php");
		$file_content = \File::read(APPPATH."config/development/db.php", true);
		$file_content = str_replace("__db_host__", $db_host, $file_content);
		$file_content = str_replace("__db_name__", $db_name, $file_content);
		$file_content = str_replace("__db_username__", $db_username, $file_content);
		$file_content = str_replace("__db_password__", $db_password, $file_content);
		
		\File::update(APPPATH."config/development", 'db.php', $file_content);

		self::success("Building your database...");

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

		\Cli::write("Let's add an aministrator! This is going to be you, so we're going to need some information.");
		$admin_username = \Cli::prompt("First off, choose a username:");
		$admin_email = \Cli::prompt("Now give me your email address:");
		$admin_password = \Cli::prompt("Finally, make a password:");
		$admin_password2 = \Cli::prompt("Type it again to be sure:");
		while($admin_password != $admin_password2)
		{
			self::error("Whoops! Your passwords didn't match. Try again.");
			$admin_password = \Cli::prompt("Finally, make a password:");
			$admin_password2 = \Cli::prompt("Type it again to be sure:");
		}
		\Auth::create_user(
		    $admin_username,
		    $admin_password,
		    $admin_email,
		    100
		);

		self::success("You're all set! Have fun!");
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

	private static function success($text)
	{
		\Cli::write(\Cli::color($text, "green"));
	}

	private static function error($text)
	{
		\Cli::write(\Cli::color($text, "red"));
	}

}

/* End of file tasks/robots.php */
