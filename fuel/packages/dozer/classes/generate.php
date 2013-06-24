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
		array_push($args, "created_at:int");
		array_push($args, "updated_at:int");


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

		$contents = <<<CONTENTS
	protected static \$_properties = array(
		$properties
	);
CONTENTS;


		$contents .= <<<CONTENTS

	protected static \$_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false
		),
	);
CONTENTS;


		$contents .= <<<CONTENTS

	protected static \$_table_name = '{$plural}';

CONTENTS;

		$contents .= <<<CONTENTS

	protected static \$_has_many = array(
	);

	protected static \$_has_one = array(
	);

	protected static \$_belongs_to = array(
	);

	protected static \$_many_to_many = array(
	);
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
		static::build();

		//Create the migration and update the database
		self::migration($plural, $args);
		\Migrate::latest();


		$model = Model_Model::forge();
		
		array_pop($args);
		array_pop($args);

		$model->fields = implode(", ", $args);
		$model->name = $singular;
		$model->has_one = '';
		$model->many_to_many = '';
		$model->has_many = '';

		$model->save();
	}

	public static function migration($migration_name, $args)
	{	

		$subjects = array(false, $migration_name);

		// We always pass in fields to a migration, so lets sort them out here.
		$fields = array();
		foreach ($args as $field)
		{
			$field_array = array();

			// Each paramater for a field is seperated by the : character
			$parts = explode(":", $field);

			// We must have the 'name:type' if nothing else!
			if (count($parts) >= 2)
			{
				$field_array['name'] = array_shift($parts);
				foreach ($parts as $part_i => $part)
				{
					preg_match('/([a-z0-9_-]+)(?:\[([0-9a-z\,\s]+)\])?/i', $part, $part_matches);
					array_shift($part_matches);

					if (count($part_matches) < 1)
					{
						// Move onto the next part, something is wrong here...
						continue;
					}

					$option_name = ''; // This is the name of the option to be passed to the action in a field
					$option = $part_matches;

					// The first option always has to be the field type
					if ($part_i == 0)
					{
						$option_name = 'type';
						$type = $option[0];
						if ($type === 'string')
						{
							$type = 'varchar';
						}
						else if ($type === 'integer')
						{
							$type = 'int';
						}

						if ( ! in_array($type, array('text', 'blob', 'datetime', 'date', 'timestamp', 'time')))
						{
							if ( ! isset($option[1]) || $option[1] == NULL)
							{
								if (isset(self::$_default_constraints[$type]))
								{
									$field_array['constraint'] = self::$_default_constraints[$type];
								}
							}
							else
							{
								// should support field_name:enum[value1,value2]
								if ($type === 'enum')
								{
									$values = explode(',', $option[1]);
									$option[1] = '"'.implode('","', $values).'"';

									$field_array['constraint'] = $option[1];
								}
								// should support field_name:decimal[10,2]
								elseif (in_array($type, array('decimal', 'float')))
								{
									$field_array['constraint'] = $option[1];
								}
								else
								{
									$field_array['constraint'] = (int) $option[1];
								}

							}
						}
						$option = $type;
					}
					else
					{
						// This allows you to put any number of :option or :option[val] into your field and these will...
						// ... always be passed through to the action making it really easy to add extra options for a field
						$option_name = array_shift($option);
						if (count($option) > 0)
						{
							$option = $option[0];
						}
						else
						{
							$option = true;
						}
					}

					// deal with some special cases
					switch ($option_name)
					{
						case 'auto_increment':
						case 'null':
						case 'unsigned':
							$option = (bool) $option;
							break;
					}

					$field_array[$option_name] = $option;

				}
				$fields[] = $field_array;
			}
			else
			{
				// Invalid field passed in
				continue;
			}


		}

		\Package::load("oil");
		// Call the magic action which returns an array($up, $down) for the migration
		$migration = call_user_func("\Oil\Generate_Migration_Actions::create", $subjects, $fields);

		// Build the migration
		list($up, $down)=$migration;

		$migration_name = ucfirst(strtolower($migration_name));

		$migration = <<<MIGRATION
<?php

namespace Fuel\Migrations;

class Create_{$migration_name}
{
	public function up()
	{
{$up}
	}

	public function down()
	{
{$down}
	}
}
MIGRATION;

		$number = isset($number) ? $number : static::_find_migration_number();
		$filepath = APPPATH . 'migrations/'.$number.'_create_' . strtolower($migration_name) . '.php';

		static::create($filepath, $migration, 'migration');

		static::build();
	}

	// Helper methods

	private static function _find_migration_number()
	{
		$glob = glob(APPPATH .'migrations/*_*.php');
		list($last) = explode('_', basename(end($glob)));

		return str_pad($last + 1, 3, '0', STR_PAD_LEFT);
	}

	public static function build()
	{
		foreach (static::$create_folders as $folder)
		{
			is_dir($folder) or mkdir($folder, 0755, TRUE);
		}

		foreach (static::$create_files as $file)
		{
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