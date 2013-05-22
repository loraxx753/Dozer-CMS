<?php

class Content 
{

	public static function load($filename)
	{
		$content = File::read(APPPATH."/content/".$filename.".md", true);
		return \Markdown::parse($content);
	}

}
