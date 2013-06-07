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

	// public function action_index()
	// {
	// 	if (\Auth::member(100)) \Casset::enable_js("editor");
	// 	$this->template->title = 'Pages &raquo; Index';
	// 	$this->template->content = View::forge('pages/index');
	// }

	public function action_load($clean_name) {
		$currentPage = Model_Page::query();
		if($this->param("subpage")) 
		{
			$parent_page = $clean_name;
			$clean_name = $this->param('subpage');
			$parent = Model_Page::query()->where("clean_name", $parent_page)->get_one();
			$currentPage->where("parent_id", $parent->id);
		}
		$data = array();
		$currentPage
			->where("clean_name", $clean_name)
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
		$this->template->title = ($currentPage->name == "index") ? "Kevin Baugh" : $currentPage->name;
		$segments = Uri::segments();
		$segments = (count($segments)) ? $segments : array('index');
		$this->template->content = View::forge('pages/load/'.implode("/", $segments), $data, false);
	}

	public function action_about()
	{
		$page = Model_Page::find()
			->where("name", "about")
			->related("page_contents")
			->get_one();
		foreach($page->page_contents as $content)
		{
			$friendly_title = \Inflector::friendly_title($content->name, "_", true);

			$data[$friendly_title] = "<div class='editable' id='".\Inflector::friendly_title($content->name)."_".$content->id."'>".$content->contents."</div>";
		}

		if (\Auth::member(100)) \Casset::enable_js("editor");

		$this->template->title = 'Pages &raquo; About';
		$this->template->content = View::forge('pages/about', $data, false);
	}
	public function action_resume()
	{
		$this->template->title = 'Pages &raquo; About';
		$data['resume'] = \Content::load("/resume/resume");
		$this->template->content = View::forge('pages/resume', $data, false);
	}

	public function action_contact()
	{
		$data['social_media'] = '';
		$social_media = \Config::get("portfolio.profile.social-media");
		foreach ($social_media as $type => $info) {
			if($info['username'])
			{
				$link = '';
				if($info['link'])
				{
					$link = preg_replace("/{%username}/", $info['username'], $info['link']);
				}
				else
				{
					$link = $info['username'];
				}
				$data['social_media'] .= "<a href='$link'>".Asset::img("social_media/".$type.".png", array("alt" => $info['name']))."</a>";
			}
		}
		$this->template->title = 'Pages &raquo; Contact';
		$this->template->content = View::forge('pages/contact', $data, false);
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
