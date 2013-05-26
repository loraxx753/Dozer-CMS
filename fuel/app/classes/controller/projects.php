<?php

class Controller_Projects extends Controller_Base
{

	public function action_index()
	{
		$data = array();
		$data['categories'] = \Model_Category::find()
			->order_by("name")
			->related("projects")
				->order_by("projects.order")
			->get();

		$data["subnav"] = array('index'=> 'active' );
		$this->template->title = 'Projects &raquo; Index';
		$this->template->content = View::forge('projects/index', $data);
	}

	public function action_detail($safeTitle)
	{
		$title = Inflector::humanize($safeTitle);
		$data['project'] = Model_Project::find()
			->where(DB::expr('LOWER(name)'), DB::expr("LOWER('$safeTitle')"))
			->get_one();
		$this->template->title = 'Projects &raquo; Index';
		$this->template->content = View::forge('projects/detail', $data);
	}

}
