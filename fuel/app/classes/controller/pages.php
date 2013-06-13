<?php

class Controller_Pages extends Controller_Base
{
	public function before()
	{
		parent::before();
		if(\Auth::member(100)) { 
			$this->template->content_edit = true;
		}
	}

	public function action_load($clean_name) {
		$currentPage = Model_Page::query();
		$data = array();
		$currentPage
			->where("url", "/".Uri::string())
			->related("page_contents");
		$currentPage = $currentPage->get_one();
		\Casset::js_inline("var page_is_published=".(($currentPage->published) ? "true" : "false").";");
		$data['blocks'] = array();
		foreach($currentPage->page_contents as $content)
		{
			$friendly_title = \Inflector::friendly_title($content->name, "_", true);

			$data[$friendly_title] = "<div class='editable' id='".\Inflector::friendly_title($content->name)."_".$content->id."'>".$content->contents."</div>";
			$data['blocks'][] = $friendly_title;
		}

		if (\Auth::member(100)) \Casset::enable_js("editor");

		if($currentPage->published == "1")
		{
			$this->template->published = true;
		}
		$this->template->title = ($currentPage->name == "main_page") ? "Kevin Baugh" : $currentPage->name;
		$segments = Uri::segments();
		$segments = (count($segments)) ? $segments : array('main_page');
		$this->template->content = View::forge('pages/load/'.implode("/", $segments), $data, false);
	}


	public function action_login()
	{
		if (Input::method() == 'POST')
		{
			if (Auth::login(Input::post("username"), Input::post("password")))
			{
			    Response::redirect("/admin");
			}
			else
			{
				Session::set_flash("error", array("Username or password invalid."));
			}
		}
		$this->template->title = 'Pages &raquo; Contact';
		$this->template->content = View::forge('pages/login');
	}

	public function action_logout()
	{
		\Auth::logout();
		Response::redirect("/admin-login");
		Session::set_flash("success", array("You have successfully logged out!"));
	}

}
