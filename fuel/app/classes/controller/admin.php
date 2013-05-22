<?php

class Controller_Admin extends Controller_Base
{

	public function action_index()
	{
		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Admin &raquo; Index';
		$this->template->content = View::forge('admin/index', $data);
	}

}
