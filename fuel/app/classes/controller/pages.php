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
		$this->template->title = 'Pages &raquo; About';
		$data['about_me'] = \Content::load("/about/about_me");
		$this->template->content = View::forge('pages/about', $data, false);
	}
	public function action_resume()
	{
		$this->template->title = 'Pages &raquo; About';
		$this->template->content = View::forge('pages/resume');
	}

	public function action_contact()
	{
		$this->template->title = 'Pages &raquo; Contact';
		$this->template->content = View::forge('pages/contact');
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
