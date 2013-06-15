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
 * Dozer\Update_Page Class
 *
 * @package		Fuel
 * @subpackage	Dozer
 * @category	Package
 * @author		Kevin Baugh
 */
class Update_Page
{
	public static function publish($clean_name)
		$page = \Dozer\Model_Page::find()->where("clean_name", $clean_name)->get_one();
		$page->published = (int)Input::post("publish");
		$page->save();
	}

	public static function content($id, $content)
	{
		$pageContent = \Dozer\Model_Page_Content::find()
			->where("id", $id)
			->get_one();
		$pageContent->contents = $content;
		$pageContent->save();
	}

}