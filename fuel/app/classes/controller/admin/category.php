<?php
class Controller_Admin_Category extends Controller_Admin{

	public function action_index()
	{
		$data['categories'] = Model_Category::find('all');
		$this->template->title = "Categories";
		$this->template->content = View::forge('category/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('category');

		if ( ! $data['category'] = Model_Category::find($id))
		{
			Session::set_flash('error', 'Could not find category #'.$id);
			Response::redirect('category');
		}

		$this->template->title = "Category";
		$this->template->content = View::forge('category/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Category::validate('create');
			
			if ($val->run())
			{
				$category = Model_Category::forge(array(
					'name' => Input::post('name'),
				));

				if ($category and $category->save())
				{

					$category->create_folder();

					Session::set_flash('success', 'Added category #'.$category->id.'.');

					Response::redirect('/admin/category');
				}

				else
				{
					Session::set_flash('error', 'Could not save category.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Categories";
		$this->template->content = View::forge('category/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('category');

		if ( ! $category = Model_Category::find($id))
		{
			Session::set_flash('error', 'Could not find category #'.$id);
			Response::redirect('category');
		}

		$val = Model_Category::validate('edit');

		if ($val->run())
		{
			$oldFolderName = $category->name;
			$category->name = Input::post('name');

			if ($category->save())
			{
				$category->edit_folder($oldFolderName);
				Session::set_flash('success', 'Updated category #' . $id);

				Response::redirect('/admin/category');
			}

			else
			{
				Session::set_flash('error', 'Could not update category #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$category->name = $val->validated('name');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('category', $category, false);
		}

		$this->template->title = "Categories";
		$this->template->content = View::forge('category/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('category');

		if ($category = Model_Category::find($id))
		{
			$category->delete();
			

			Session::set_flash('success', 'Deleted category #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete category #'.$id);
		}

		Response::redirect('/admin/category');

	}


}
