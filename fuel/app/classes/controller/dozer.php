<?php

class Controller_Dozer extends Controller_Template
{
	public function index()
	{
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
					Fuel\Tasks\Install::run($options);
				}

				if($_POST['action'] == "setup_admin")
				{
					$options = array(
						"admin_username" => $_POST['admin_username'],
						"admin_email" => $_POST['admin_email'],
						"admin_password" => $_POST['admin_password'],
					);
					Fuel\Tasks\Install::create_admin($options['admin_username'], $options['admin_email'], $options['admin_passwordr']);
					\Config::set("routes._root_", "/pages/load/index");
					\Config::save("routes", "routes");
				}
			}
		}
		else
		{
			echo \View::forge("dozer/welcome");
		}
	}

}
