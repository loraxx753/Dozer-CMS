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
 * Dozer\Generate Class
 *
 * @package		Fuel
 * @subpackage	Dozer
 * @category	Package
 * @author		Kevin Baugh
 */
class Generate
{
	public static $create_folders = array();
	public static $create_files = array();

	private static $_default_constraints = array(
		'varchar' => 255,
		'char' => 255,
		'int' => 11
	);

	public static function model($args)
	{

		$singular = \Inflector::singularize(\Str::lower(array_shift($args)));

		$plural = \Inflector::pluralize($singular);

		$filename = trim(str_replace(array('_', '-'), DS, $singular), DS);

		$filepath = APPPATH.'classes'.DS.'model'.DS.$filename.'.php';

		// Uppercase each part of the class name and remove hyphens
		$class_name = \Inflector::classify(str_replace(array('\\', '/'), '_', $singular), false);

		// Turn foo:string into "id", "foo",
		$properties = implode(",\n\t\t", array_map(function($field) {

			// Only take valid fields
			if (($field = strstr($field, ':', true)))
			{
				return "'".$field."'";
			}

		}, $args));

		// Make sure an id is present
		strpos($properties, "'id'") === false and $properties = "'id',\n\t\t".$properties.',';

		$contents = '';


		$contents .= <<<CONTENTS

protected static \$_table_name = '{$plural}';

CONTENTS;

		$model = '';

		$model .= <<<MODEL

<?php

class Model_{$class_name} extends \Orm\Model
{
{$contents}
}

MODEL;
		// Build the model
		static::create($filepath, $model, 'model');

		$build and static::build();
	}

	public static function build()
	{
		foreach (static::$create_folders as $folder)
		{
			is_dir($folder) or mkdir($folder, 0755, TRUE);
		}

		foreach (static::$create_files as $file)
		{
			\Cli::write("\tCreating {$file['type']}: {$file['path']}", 'green');

			if ( ! $handle = @fopen($file['path'], 'w+'))
			{
				throw new Exception('Cannot open file: '. $file['path']);
			}

			$result = @fwrite($handle, $file['contents']);

			// Write $somecontent to our opened file.
			if ($result === false)
			{
				throw new Exception('Cannot write to file: '. $file['path']);
			}

			@fclose($handle);

			@chmod($file['path'], 0666);
		}

		return $result;
	}

	public static function create($filepath, $contents, $type = 'file')
	{
		$directory = dirname($filepath);
		is_dir($directory) or static::$create_folders[] = $directory;

		static::$create_files[] = array(
			'path' => $filepath,
			'contents' => $contents,
			'type' => $type
		);
	}

	public static function page($name, $parent_id)
	{
		$page = \Dozer\Model_Page::forge();
		$page->name = $name;
		$page->parent_id = $parent_id;
		$page->published = 0;
		$page->clean_name = \Inflector::friendly_title($name, "_", true);
		$page->url = "/$page->clean_name";
		$page->save();
		\Config::set("routes.".$page->clean_name, "/pages/load/".$page->clean_name);
		\Config::save("routes", "routes");
		\File::create(APPPATH."views/pages/load", $page->clean_name.".php", '<?php foreach($blocks as $block) { echo ${$block}; }');
		$parent_pages = \Dozer\Model_Page::query()
			->where(array("clean_name", "!=", "main_page"))
			->where(array("id", "!=", $page->id))
			->get();
		$pages = array();
		foreach ($parent_pages as $parent_page) {
			$pages[] = array("id" => $parent_page->id, "name"=>$parent_page->name);
		}

		return array(
			"page" => $page,
			"pages" => $pages
		);
	}

	public static function block($name, $pageName)
	{
		$page = \Dozer\Model_Page::find()->where("clean_name", $pageName)->get_one();
		$block = \Dozer\Model_Page_Content::forge();
		$block->page_id = $page->id;
		$block->name = $name;
		$block->contents = "<p>".$block->name."</p>";
		$block->save();

		return $block;
	}

}