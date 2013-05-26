<?php
class Controller_Admin_Project extends Controller_Admin
{

	public function action_index()
	{	
		$data = array();
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
				$count = Model_Project::find()->where("category", Input::post("category"))->count();
				$project = Model_Project::forge(array(
					'name' => Input::post('name'),
					'description' => Input::post('description'),
					'category' => Input::post('category'),
					'order' => $count++,
					'overview' => Input::post('overview'),
				));


				foreach (Input::post("language") as $languageId) {
					$project->languages[] = Model_Language::find($languageId);
				}

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
		$languages = Model_Language::find('all');
		$data = array();
		foreach ($categories as $key => $value) {
			$data['categories'][$value->id] = $value->name;
		}
		foreach ($languages as $key => $value) {
			$data['languages'][$value->id] = $value->name;
		}

		$data['options'] = $data;

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
			if($project->category != Input::post('category'))
			{
				$project->create_category_folders(Input::post('category'));
				File::rename(
					DOCROOT."assets/img/projects/".Model_Category::find($project->category)->name."/".$project->image, 
					DOCROOT."assets/img/projects/".Model_Category::find(Input::post('category'))->name."/".$project->image
				);
				File::rename(
					DOCROOT."assets/img/projects/".Model_Category::find($project->category)->name."/thumbs/".$project->image, 
					DOCROOT."assets/img/projects/".Model_Category::find(Input::post('category'))->name."/thumbs/".$project->image
				);
			}
			$project->name = Input::post('name');
			$project->description = Input::post('description');
			$project->category = Input::post('category');
			$project->order = Input::post('order');
			$project->overview = Input::post('overview');
			$project->languages = array();
			foreach (Input::post("language") as $languageId) {
				$project->languages[] = Model_Language::find($languageId);
			}

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
		$languages = Model_Language::find('all');
		foreach ($categories as $key => $value) {
			$data['categories'][$value->id] = $value->name;
		}
		foreach ($languages as $key => $value) {
			$data['languages'][$value->id] = $value->name;
		}

		$data['options'] = $data;


		$this->template->title = "Projects";
		$this->template->content = View::forge('project/edit', $data);

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
