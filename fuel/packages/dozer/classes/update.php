<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Dozer;

/**
 * Dozer\Update Class
 *
 * @package		Fuel
 * @subpackage	Dozer
 * @category	Package
 * @author		Kevin Baugh
 */
class Update
{
	public static function css($name)
	{
		if($name != "reset")
		{
			\Config::set("portfolio.bootswatch", $name);
		}
		else
		{
			\Config::set("portfolio.bootswatch", null);
		}
		\Config::save("portfolio", "portfolio");		
	}

	public static function pages()
	{
		$models = \Dozer\Model_Page::find("all");
		foreach ($models as $model) {
			if(isset($pages[$model->id]['parent_id']))
			{
				Config::delete('routes.'.substr($model->url, 1));
				$model->parent_id = $pages[$model->id]['parent_id'];
				$parent_id = $model->parent_id;
				$parents = array();
				while($parent_id > 0)
				{
					$parent = \Dozer\Model_Page::find($parent_id);
					$parents[] = $parent->clean_name;
					$parent_id = $parent->parent_id;
				}
				$parent_string = "/".implode("/", array_reverse($parents));
				if(!is_dir(APPPATH."views/pages/load".$parent_string))
				{
					mkdir(APPPATH."views/pages/load".$parent_string);
				}
				\File::rename(APPPATH."views/pages/load".$model->url.".php", APPPATH."views/pages/load".$parent_string."/".$model->clean_name.".php");
				if($model->parent_id > 0)
				{
					$model->url = $parent_string."/".$model->clean_name;
				}
				else
				{
					$model->url = "/".$model->clean_name;					
				}


				\Config::set('routes.'.substr($model->url, 1), "/pages/load".$model->url);
				\Config::save("routes", "routes");
			}
			if(isset($pages[$model->id]['published']))
			{
				$model->published = $pages[$model->id]['published'];
			}

			$model->save();
		}
		$new_pages = \Dozer\Model_Page::query()
			->where("name", "!=", "index")
				->related("sub_pages")
			->get();
		$urls = array();
		$top_pages = array();
		foreach ($new_pages as $new_page) {
			$urls[$new_page->id] = $new_page->url;
			if($new_page->parent_id == 0)
			{
				$top_pages[] = $new_page;
			}
		}

		return array(
			"top_pages" => $top_pages,
			"urls" => $urls
		);
	}
	
}