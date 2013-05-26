<?php
class Controller_Admin_Language extends Controller_Admin{

	public function action_index()
	{
		$data['languages'] = Model_Language::find()->order_by("name")->get();
		$this->template->title = "Languages";
		$this->template->content = View::forge('language/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('language');

		if ( ! $data['language'] = Model_Language::find($id))
		{
			Session::set_flash('error', 'Could not find language #'.$id);
			Response::redirect('/admin/language');
		}

		$this->template->title = "Language";
		$this->template->content = View::forge('language/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Language::validate('create');
			
			if ($val->run())
			{
				$language = Model_Language::forge(array(
					'name' => Input::post('name'),
				));

				if ($language and $language->save())
				{
					Session::set_flash('success', 'Added language #'.$language->id.'.');

					Response::redirect('/admin/language');
				}

				else
				{
					Session::set_flash('error', 'Could not save language.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Languages";
		$this->template->content = View::forge('language/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('language');

		if ( ! $language = Model_Language::find($id))
		{
			Session::set_flash('error', 'Could not find language #'.$id);
			Response::redirect('/admin/language');
		}

		$val = Model_Language::validate('edit');

		if ($val->run())
		{
			$language->name = Input::post('name');

			if ($language->save())
			{
				Session::set_flash('success', 'Updated language #' . $id);

				Response::redirect('/admin/language');
			}

			else
			{
				Session::set_flash('error', 'Could not update language #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$language->name = $val->validated('name');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('language', $language, false);
		}

		$this->template->title = "Languages";
		$this->template->content = View::forge('language/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('/admin/language');

		if ($language = Model_Language::find($id))
		{
			$language->delete();

			Session::set_flash('success', 'Deleted language #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete language #'.$id);
		}

		Response::redirect('/admin/language');

	}


}
