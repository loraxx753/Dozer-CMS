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
		$page->parent_id = 0;
		$page->published = 0;
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->save();
		\Config::set("routes.".$page->clean_name, "/pages/load/".$page->clean_name);
		\Config::save("routes", "routes");
		\File::create(APPPATH."views/pages/load", $page->clean_name.".php", '<?php foreach($pages as $page) { echo ${$page}; }');
		echo $page->clean_name;
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
