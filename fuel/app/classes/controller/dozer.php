<?php

class Controller_Dozer extends Controller_Template
{
	public function action_install()
	{
		$this->template = null;
		if(Input::method() == "POST")
		{
			if(isset($_POST['action']))
			{
				if($_POST['action'] == "setup_database")
				{
					$options = array(
						"db_host" => $_POST['db_host'],
						"db_name" => $_POST['db_name'],
						"db_username" => $_POST['db_username'],
						"db_password" => $_POST['db_password'],
					);
					self::build($options);
				}

				if($_POST['action'] == "setup_admin")
				{
					$options = array(
						"admin_username" => $_POST['admin_username'],
						"admin_email" => $_POST['admin_email'],
						"admin_password" => $_POST['admin_password'],
					);
					echo self::create_admin($options['admin_username'], $options['admin_email'], $options['admin_password']);
					\Config::set("routes._root_", "/pages/load/main_page");
					\Config::save("routes", "routes");
				}
			}
		}
		else
		{
			echo \View::forge("dozer/install");
		}
	}

	public function build($options = array())
	{
		if(empty($options))
		{
			$db_host = \Cli::prompt("What's your database's host (usually localhost)");
			$db_name = \Cli::prompt("What about your database's name?");
			$db_username = \Cli::prompt("Your username for the database?");
			$db_password = \Cli::prompt("Lastly, you're databases password?");			
		}
		else
		{
			extract($options);
		}
		if(is_file(APPPATH."config/development/db.php"))
		{
			\File::delete(APPPATH."config/development/db.php");
		}

		\File::copy(APPPATH."config/development/db_template.php", APPPATH."config/development/db.php");
		$file_content = \File::read(APPPATH."config/development/db.php", true);
		$file_content = str_replace("__db_host__", $db_host, $file_content);
		$file_content = str_replace("__db_name__", $db_name, $file_content);
		$file_content = str_replace("__db_username__", $db_username, $file_content);
		$file_content = str_replace("__db_password__", $db_password, $file_content);
		
		\File::update(APPPATH."config/development", 'db.php', $file_content);

		\Migrate::latest('default', 'app');
		\Migrate::latest('auth', 'package');

		$name = "Main Page";
		$page = \Model_Page::forge();
		$page->name = $name;
		$page->parent_id = -1;
		$page->published = 1;
		$page->url = "/";
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->save();

		if(!is_file(APPPATH."views/pages/load/".$page->clean_name.".php"))
		{
			\File::create(APPPATH."views/pages/load", $page->clean_name.".php", '<?php foreach($blocks as $block) { echo ${$block}; }');
		}
	}

	public static function create_admin($username, $email, $password)
	{

		// create a new user
		\Auth::create_user(
		    $username,
		    $password,
		    $email,
		    100
		);
	}


}
