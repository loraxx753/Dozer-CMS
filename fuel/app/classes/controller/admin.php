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
		$data['languages'] = Model_Language::find()->get();

		$this->template->title = 'Admin &raquo; Index';
		$this->template->content = View::forge('admin/index', $data);
	}

	public function post_update($type)
	{
		$this->template = null;
		switch($type)
		{
			case "projects":
				$movedProject = Model_Project::find()->where("id", Input::post("id"))->get_one();
				$projects = Model_Project::find()
					->where("category", Input::post("category"))
					->where("order", ">", Input::post("newIndex"))
					->get();
				

				foreach($projects as $project)
				{
					if($project->order == (int)Input::post("newIndex")+1)
						$project->order--;
					else
						$project->order++;
					$project->save();
				}
				$oldFolderId = $movedProject->category;

				$movedProject->category = Input::post("category");
				$movedProject->order = (int)Input::post("newIndex")+1;
				$movedProject->save();
				var_dump($movedProject);

				$movedProject->move_image_assets($oldFolderId, $movedProject);
		}
	}

}
