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
		$parent_id = Input::post("parent_id");

		extract(\Dozer\Generate::page($name, $parent_id));

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
		$block = \Dozer\Generate::block($name, $pageName);
		
		echo "<div class='editable' id='".\Inflector::friendly_title($block->name)."_".$block->id."'>".$block->contents."</div>";
	}

	public function post_model()
	{
		$args = \Input::post("args");
		\Dozer\Generate::model($args);		
	}

	public function post_entry()
	{
		$name = "\Model_".\Inflector::singularize(ucwords(Input::post("name")));
		$fields = array();
		foreach (Input::post() as $post) {
			if(gettype($post) == "array")
			{
				$fields = array_merge($fields, $post);
			}
		}
		$model = new $name();
		foreach ($fields as $field) {
			$model->$field["name"] = $field["content"];
		}
		$model->updated_at = time();
		$model->save();

	}
}
