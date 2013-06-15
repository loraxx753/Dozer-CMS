<?php

class Controller_Base extends Controller_Template
{

	public function before()
	{
		parent::before();

		$pages = \Dozer\Model_Page::query()
			->where("parent_id", "0")
			->where("name", "!=", "index")
				->related("sub_pages");
		if(!\Auth::member(100)) { 
			$pages->where("published", "1");
			try
			{
				$pages = $pages->get();
			}
			catch(Database_Exception $e)
			{
				\Config::set("routes._root_", "dozer/install");
				\Config::save("routes", "routes");
				\Response::redirect("/");

			}
			foreach($pages as $page)
			{
				foreach($page->sub_pages as $index => $subpage)
				{
					if($subpage->published == 0)
					{
						unset($page->sub_pages[$index]);
					}
				}
			}
		}
		else
		{
			$pages = $pages->get();
		}

		$this->template->pages = $pages;
		$inline = "var current_css='".((\Config::get("portfolio.bootswatch")) ? \Config::get("portfolio.bootswatch") : "default")."';";
		if(count(Uri::segments()))
		{
			$uri = Uri::segments();			
		}
		else
		{
			$uri = array();
		}
		$curPage = array_pop($uri);
		$inline .= " var current_page='".$curPage."';";
		\Casset::js_inline($inline);
		$this->template->currentPage = $curPage;
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
