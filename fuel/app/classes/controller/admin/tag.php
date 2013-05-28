<?php
class Controller_Admin_Tag extends Controller_Admin{

	public function action_index()
	{
		$data['tags'] = Model_Tag::find()->order_by("name")->get();
		$this->template->title = "Tags";
		$this->template->content = View::forge('tag/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('tag');

		if ( ! $data['tag'] = Model_Tag::find($id))
		{
			Session::set_flash('error', 'Could not find tag #'.$id);
			Response::redirect('/admin/tag');
		}

		$this->template->title = "Tag";
		$this->template->content = View::forge('tag/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tag::validate('create');
			
			if ($val->run())
			{
				$tag = Model_Tag::forge(array(
					'name' => Input::post('name'),
				));

				if ($tag and $tag->save())
				{
					Session::set_flash('success', 'Added tag #'.$tag->id.'.');

					Response::redirect('/admin/tag');
				}

				else
				{
					Session::set_flash('error', 'Could not save tag.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tags";
		$this->template->content = View::forge('tag/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('tag');

		if ( ! $tag = Model_Tag::find($id))
		{
			Session::set_flash('error', 'Could not find tag #'.$id);
			Response::redirect('/admin/tag');
		}

		$val = Model_Tag::validate('edit');

		if ($val->run())
		{
			$tag->name = Input::post('name');

			if ($tag->save())
			{
				Session::set_flash('success', 'Updated tag #' . $id);

				Response::redirect('/admin/tag');
			}

			else
			{
				Session::set_flash('error', 'Could not update tag #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tag->name = $val->validated('name');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tag', $tag, false);
		}

		$this->template->title = "Tags";
		$this->template->content = View::forge('tag/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('/admin/tag');

		if ($tag = Model_Tag::find($id))
		{
			$tag->delete();

			Session::set_flash('success', 'Deleted tag #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete tag #'.$id);
		}

		Response::redirect('/admin/tag');

	}


}
