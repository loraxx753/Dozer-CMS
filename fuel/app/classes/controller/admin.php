<?php

class Controller_Admin extends Controller_Base
{

	public function before() 
	{
		parent::before();
		\Casset::enable_js("hallo");
		\Casset::js("admin.js");
		if (!\Auth::member(100)) throw new HttpNotFoundException;
	}

	public function action_index()
	{
		\Casset::js("profile.js");
		\Casset::js("change-css.js");
		$data['pages'] = \Dozer\Model_Page::find()
			->where("clean_name", "!=", "index")
			->get();
		$models = \Dozer\Model_Model::find('all');
		foreach($models as $model)
		{
			$fields = explode(", ", $model->fields);
			$model->fields = array();
			foreach ($fields as $field) {
				list($name, $type) = explode(":", $field);
				$model->fields[$name] = $type;
			}
		}
		$data['models'] = $models;

		$data['data'] = $data;
		$data['social_media'] = \Config::get("portfolio.profile.social-media");

		$this->template->title = 'Admin &raquo; Index';
		$this->template->content = View::forge('admin/index', $data, false);
	}


	public function action_imageupload() {
		if(Input::method() == "POST")
		{
			$file = Input::file("userfile");
			$esplode = explode(".", $file['name']);
			$extention = array_pop($esplode);

			if(Input::post("caption"))
			{
				$safeName = Inflector::friendly_title(Input::post("caption"), "_", true).".".$extention;
			}
			else
			{
				$safeName = Inflector::friendly_title($esplode[0], "_", true).".".$extention;			
			}


			File::copy($file['tmp_name'], DOCROOT."assets/img/user/".$safeName);
		}


		die();
	}


	public function post_suggestsions()
	{
		$result = array(array('url' => "/assets/img/user/181494_10200638286972823_1949414501_n.jpg", 'tag' => 'all'));
		echo json_encode($result);
	}
}
