<?php
class Controller_Admin_Update extends Controller_Admin
{
	public function before() {
		parent::before();
		$this->template = null;
	}

	public function post_publish() {
		$name = Input::post("clean_name");
		\Dozer\Update_Page::publish($name);
	}

	public function post_css() {
		$name = Input::post("name");
		\Dozer\Update::css($name);
	}

	// public function post_profile() {
	// 	foreach (Input::post("personal_info") as $key => $value) {
	// 		if($value)
	// 		{
	// 			\Config::set("portfolio.profile.".$key, $value);
	// 		}
	// 		else 
	// 		{
	// 			\Config::set("portfolio.profile.".$key, null);
	// 		}
	// 	}
	// 	foreach (Input::post("social_media") as $key => $value) {
	// 		if($value)
	// 		{
	// 			\Config::set("portfolio.profile.social-media.".$key.".username", $value);
	// 		}
	// 		else
	// 		{
	// 			\Config::set("portfolio.profile.social-media.".$key.".username", null);
	// 		}
	// 	}
	// 	\Config::save("portfolio", "portfolio");
	// }

	// public function post_change() {
	// 	$file = Input::file("picture");
	// 	$name = explode(".", $file['name']);
	// 	$extension = array_pop($name);
	// 	try
	// 	{
	// 		\File::copy($file['tmp_name'], DOCROOT."assets/img/profile.".$extension);
	// 	}
	// 	catch(Exception $e)
	// 	{
	// 		\File::rename(DOCROOT."assets/img/profile.".$extension, DOCROOT."assets/img/profile.".$extension."_old");
	// 		\File::copy($file['tmp_name'], DOCROOT."assets/img/profile.".$extension);
	// 		\File::delete(DOCROOT."assets/img/profile.".$extension."_old");
	// 	}
	// 	// \Response::redirect("/admin");
	// }

	public function post_content() {
		$name = Input::post("name");
		$content = Input::post("content");
		$exploded = explode("_", $name);
		$id = array_pop($exploded);
		\Dozer\Update::content($id, $content);
	}

	public function post_pages()
	{
		$pages = Input::post("pages");
		extract(\Dozer\Update::pages($pages));
		echo json_encode(
				array(
					"html" => View::forge("dozer/navigation", array("pages" => $top_pages, "currentPage" => ""), false)->render(), 
					"urls" => $urls
				)
			);

	}
}
