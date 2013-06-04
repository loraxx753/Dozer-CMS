<?php

class Controller_Base extends Controller_Template
{

	public function before()
	{
		parent::before();
		// $this->template->pages = array(
		// 	"about",
		// 	"projects",
		// 	"resume",
		// 	"contact"
		// );
		$pages = Model_Page::find()
			->where("parent_id", 0)
			->where("name", "!=", "index");
		if(!\Auth::member(100)) { 
			$pages->where("published", true);
		}

		$this->template->pages = $pages->get();
		$inline = "var current_css='".((\Config::get("portfolio.bootswatch")) ? \Config::get("portfolio.bootswatch") : "default")."';";
		$inline .= " var current_page='".Uri::segment(1)."';";
		\Casset::js_inline($inline);
		$this->template->currentPage = Uri::segment(1);
		if(\Auth::member(100))
		{ 
			\Casset::js('newpage.js');
			if(Uri::segment(1) != "admin")
			{
				$this->template->editable = true;
			}
		}
	}

}
