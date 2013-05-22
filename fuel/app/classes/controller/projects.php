<?php

class Controller_Projects extends Controller_Base
{

	public function action_index()
	{
		$categories = \Model_Category::find()->order_by("name")->get();
		var_dump($categories);

		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Projects &raquo; Index';
		$this->template->content = View::forge('projects/index', $data);
	}

}
