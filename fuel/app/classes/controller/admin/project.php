<?php
class Controller_Admin_Project extends Controller_Admin
{

	public function action_index()
	{	
		$data['projects'] = Model_Project::find()->related("category")->get();
		$this->template->title = "Projects";
		$this->template->content = View::forge('project/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('project');

		if ( ! $data['project'] = Model_Project::find()->where("id", $id)->get_one())
		{
			Session::set_flash('error', 'Could not find project #'.$id);
			Response::redirect('/admin/project');
		}

		$this->template->title = "Project";
		$this->template->content = View::forge('project/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Project::validate('create');
			
			if ($val->run())
			{
				$project = Model_Project::forge(array(
					'name' => Input::post('name'),
					'description' => Input::post('description'),
					'category' => Input::post('category'),
					'order' => Input::post('order'),
				));

				$project->create_file(Input::file('image'));

				if ($project and $project->save())
				{
					Session::set_flash('success', 'Added project #'.$project->id.'.');

					Response::redirect('/admin/project');
				}

				else
				{
					Session::set_flash('error', 'Could not save project.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$categories = Model_Category::find('all');
		foreach ($categories as $key => $value) {
			$data['categories'][$value->id] = $value->name;
		}

		$this->template->title = "Projects";
		$this->template->content = View::forge('project/create', $data);

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('project');

		if ( ! $project = Model_Project::find($id))
		{
			Session::set_flash('error', 'Could not find project #'.$id);
			Response::redirect('/admin/project');
		}

		$val = Model_Project::validate('edit');

		if ($val->run())
		{
			$project->name = Input::post('name');
			$project->description = Input::post('description');
			$project->category = Input::post('category');
			$project->order = Input::post('order');

			if ($project->save())
			{
				Session::set_flash('success', 'Updated project #' . $id);

				Response::redirect('/admin/project');
			}

			else
			{
				Session::set_flash('error', 'Could not update project #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$project->name = $val->validated('name');
				$project->description = $val->validated('description');
				$project->category = $val->validated('category');
				$project->order = $val->validated('order');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('project', $project, false);
		}

		$categories = Model_Category::find('all');
		foreach ($categories as $key => $value) {
			$data['categories'][$value->id] = $value->name;
		}

		$this->template->title = "Projects";
		$this->template->content = View::forge('project/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('project');

		if ($project = Model_Project::find($id))
		{
			$project->delete();

			Session::set_flash('success', 'Deleted project #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete project #'.$id);
		}

		Response::redirect('/admin/project');

	}


}
