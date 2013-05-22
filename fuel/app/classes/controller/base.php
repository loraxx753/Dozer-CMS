<?php

class Controller_Base extends Controller_Template
{

	public function before()
	{
		parent::before();
		$this->template->pages = array(
			"about",
			"projects",
			"resume",
			"contact"
		);
		$this->template->currentPage = Uri::segment(1);
	}

}
