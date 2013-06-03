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
		\Casset::js("profile.js");
		\Casset::js("change-css.js");
		$data['categories'] = Model_Category::find('all');
		$data['projects'] = Model_Project::find()->related("category")->get();
		$data['tags'] = Model_Tag::find()->get();
		$data['social_media'] = \Config::get("portfolio.profile.social-media");

		$this->template->title = 'Admin &raquo; Index';
		$this->template->content = View::forge('admin/index', $data, false);
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

				$movedProject->move_image_assets($oldFolderId, $movedProject);
				break;
			case "css":
				$name = Input::post("name");
				if($name != "reset")
				{
					\Config::set("portfolio.bootswatch", $name);
				}
				else
				{
					\Config::set("portfolio.bootswatch", null);
				}
				\Config::save("portfolio", "portfolio");
				break;
			case "profile":
				foreach (Input::post("personal_info") as $key => $value) {
					if($value)
					{
						\Config::set("portfolio.profile.".$key, $value);
					}
					else 
					{
						\Config::set("portfolio.profile.".$key, null);
					}
				}
				foreach (Input::post("social_media") as $key => $value) {
					if($value)
					{
						\Config::set("portfolio.profile.social-media.".$key.".username", $value);
					}
					else
					{
						\Config::set("portfolio.profile.social-media.".$key.".username", null);
					}
				}
				\Config::save("portfolio", "portfolio");
				break;
			case "change":
				$file = Input::file("picture");
				$name = explode(".", $file['name']);
				$extension = array_pop($name);
				try
				{
					\File::copy($file['tmp_name'], DOCROOT."assets/img/profile.".$extension);
				}
				catch(Exception $e)
				{
					\File::rename(DOCROOT."assets/img/profile.".$extension, DOCROOT."assets/img/profile.".$extension."_old");
					\File::copy($file['tmp_name'], DOCROOT."assets/img/profile.".$extension);
					\File::delete(DOCROOT."assets/img/profile.".$extension."_old");
				}
				// \Response::redirect("/admin");
				break;
			case "imageupload":
				var_dump(Input::post());
				break;
		}
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
