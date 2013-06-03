<?php

class Controller_Pages extends Controller_Base
{

	public function action_index()
	{
		$this->template->title = 'Pages &raquo; Index';
		$this->template->content = View::forge('pages/index');
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
			if(\Auth::member(100))
			{
				$data[$friendly_title] = Markdown::parse($content->contents);
			}
			else
			{
				$data[$friendly_title] = Markdown::parse($content->contents);
			}
		}

		if (\Auth::member(100)) \Casset::enable_js("hallo");

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
