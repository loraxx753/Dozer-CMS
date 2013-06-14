<?php
class Controller_Admin_Create extends Controller_Admin
{
	public function before() {
		parent::before();
		$this->template = null;
	}

	public function post_page() 
	{
		$name = Input::post("name");
		$page = Model_Page::forge();
		$page->name = $name;
		$page->parent_id = Input::post("parent_id");
		$page->published = 0;
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->url = "/$page->clean_name";
		$page->save();
		\Config::set("routes.".$page->clean_name, "/pages/load/".$page->clean_name);
		\Config::save("routes", "routes");
		\File::create(APPPATH."views/pages/load", $page->clean_name.".php", '<?php foreach($blocks as $block) { echo ${$block}; }');
		$parent_pages = \Model_Page::query()
			->where(array("clean_name", "!=", "main_page"))
			->where(array("id", "!=", $page->id))
			->get();
		$pages = array();
		foreach ($parent_pages as $parent_page) {
			$pages[] = array("id" => $parent_page->id, "name"=>$parent_page->name);
		}
		echo json_encode(array(
			"id" => $page->id,
			"name" => $page->name,
			"parent_id" => $page->parent_id,
			"published" => $page->published,
			"clean_name" => $page->clean_name,
			"url" => $page->url,
			"pages" => $pages
		));
	}

	public function post_block()
	{
		$name = Input::post("name");
		$pageName = Input::post("page");
		$page = Model_Page::find()->where("clean_name", $pageName)->get_one();
		$block = Model_Page_Content::forge();
		$block->page_id = $page->id;
		$block->name = $name;
		$block->contents = "<p>".$block->name."</p>";
		$block->save();
		echo "<div class='editable' id='".\Inflector::friendly_title($block->name)."_".$block->id."'>".$block->contents."</div>";
	}

	public function post_subpage()
	{
		$name = Input::post("name");
		$parentName = Input::post("parent");
		$parent = Model_Page::find()->where("clean_name", $parentName)->get_one();
		$page = Model_Page::forge();
		$page->name = $name;
		$page->parent_id = $parent->id;
		$page->published = 0;
		$page->url = "/$parent->clean_name/$page->clean_name";
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->save();
		\Config::set("routes.".$parent->clean_name."/".$page->clean_name, "/pages/load/".$parent->clean_name."/".$page->clean_name);
		\Config::save("routes", "routes");
		if(!is_dir(APPPATH."views/pages/load/".$parent->clean_name))
		{
			\File::create_dir(APPPATH."views/pages/load/", $parent->clean_name);
		}
		\File::create(APPPATH."views/pages/load/".$parent->clean_name, $page->clean_name.".php", '<?php foreach($pages as $page) { echo ${$page}; }');
		echo $parent->clean_name."/".$page->clean_name;		
	}
}
