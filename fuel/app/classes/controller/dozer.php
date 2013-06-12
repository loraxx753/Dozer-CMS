<?php

class Controller_Dozer extends Controller_Template
{
	public function action_install()
	{
		$this->template = null;
		if(Input::method() == "POST")
		{
			$options = Input::post();
			$error = self::build($options);
			if($error)
			{
				echo $error;
			}
			else
			{
				echo "success";
			}
		}
		else
		{
			echo \View::forge("dozer/install");
		}
	}

	public function build($options)
	{
		if(!empty($options))
		{
			extract($options);
		}
		else
		{
			return false;
		}

		if($action == "database_setup")
		{
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

			try 
			{
				\Migrate::latest('default', 'app');
				\Migrate::latest('auth', 'package');
			}
			catch(Database_Exception $e)
			{
				return "It seems like your database isn't connecting. Better check those inputs.";

			}
		}
		else if($action == "admin_setup")
		{
			if($admin_password2 != $admin_password)
			{
				return "Password's don't match!";
			}
			try
			{
				// create a new user
				\Auth::create_user(
				    $admin_username,
				    $admin_email,
				    $admin_password,
				    100
				);
			}
			catch(SimpleUserUpdateException $e)
			{
				return $e->getMessage();
			}

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
				\Config::set("routes._root_", "/pages/load/main_page");
				\Config::save("routes", "routes");
		}
	}
}
