<?php

class Controller_Admin extends Controller_Base
{

	public function before() 
	{
		parent::before();
		if (!\Auth::member(100)) throw new HttpNotFoundException;
	}

	public function action_index()
	{
		$data['categories'] = Model_Category::find('all');
		$data['projects'] = Model_Project::find()->related("category")->get();

		$this->template->title = 'Admin &raquo; Index';
		$this->template->content = View::forge('admin/index', $data);
	}

}
