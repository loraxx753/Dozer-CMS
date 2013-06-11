<?php
class Controller_Admin_Update extends Controller_Admin
{
	public function before() {
		parent::before();
		$this->template = null;
	}

	public function post_publish() {
		$page = Model_Page::find()->where("clean_name", Input::post("clean_name"))->get_one();
		$page->published = (int)Input::post("publish");
		$page->save();
	}

	public function post_projects() {
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
	}

	public function post_css() {
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
	}

	public function post_profile() {
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
	}

	public function post_change() {
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
	}

	public function post_content() {
		$name = Input::post("name");
		$exploded = explode("_", $name);
		$id = array_pop($exploded);
		$content = Input::post("content");
		$pageContent = Model_Page_Content::find()
			->where("id", $id)
			->get_one();
		$pageContent->contents = $content;
		$pageContent->save();		
	}

	public function post_pages()
	{
		$pages = Input::post("pages");
		$models = Model_Page::find("all");
		foreach ($models as $model) {
			if(isset($pages[$model->id]['parent_id']))
			{
				Config::delete('routes.'.substr($model->url, 1));
				$model->parent_id = $pages[$model->id]['parent_id'];
				$parent_id = $model->parent_id;
				$parents = array();
				while($parent_id > 0)
				{
					$parent = Model_Page::find($parent_id);
					$parents[] = $parent->clean_name;
					$parent_id = $parent->parent_id;
				}
				$parent_string = "/".implode("/", array_reverse($parents));
				if(!is_dir(APPPATH."views/pages/load".$parent_string))
				{
					mkdir(APPPATH."views/pages/load".$parent_string);
				}
				\File::rename(APPPATH."views/pages/load".$model->url.".php", APPPATH."views/pages/load".$parent_string."/".$model->clean_name.".php");
				if($model->parent_id > 0)
				{
					$model->url = $parent_string."/".$model->clean_name;
				}
				else
				{
					$model->url = "/".$model->clean_name;					
				}


				\Config::set('routes.'.substr($model->url, 1), "/pages/load".$model->url);
				\Config::save("routes", "routes");
			}
			if(!empty($pages[$model->id]['published']))
			{
				$model->published = $pages[$model->id]['published'];
			}

			$model->save();

		}
	}
}
