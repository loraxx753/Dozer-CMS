<?php

class Controller_Projects extends Controller_Base
{

	public function action_index()
	{
		$data = array();
		$data['categories'] = \Model_Category::find()->order_by("name")->get();

		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Projects &raquo; Index';
		$this->template->content = View::forge('projects/index', $data);
	}

}
