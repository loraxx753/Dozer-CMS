<?php

class Controller_Dozer extends Controller_Template
{
	public function action_install()
	{
		$this->template = null;
		if(Input::method() == "POST")
		{
			$options = Input::post();
			$error = \Dozer\Install::build($options);
			if($error)
			{
				echo json_encode(array("error" => $error));
			}
			else
			{
				echo json_encode(array("success" => true));
			}
		}
		else
		{
			echo \View::forge("dozer/install");
		}
	}
}
