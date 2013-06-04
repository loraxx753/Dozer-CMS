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
}
