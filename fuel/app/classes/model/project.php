<?php
use Orm\Model;

class Model_Project extends Model
{
	protected static $_properties = array(
		'id',
		'name',
		'description',
		'category',
		'order',
		'image',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Name', 'required|max_length[255]');
		$val->add_field('description', 'Description', 'required|max_length[255]');
		$val->add_field('category', 'Category', 'required|valid_string[numeric]');
		$val->add_field('order', 'Order', 'required|valid_string[numeric]');

		return $val;
	}

	// in a Model_User which has one profile
	protected static $_has_one = array(
	    'category' => array(
	        'key_from' => 'category',
	        'model_to' => 'Model_Category',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	public function create_file($file)
	{
		$category = Model_Category::find($this->category);
		if(!is_dir(DOCROOT."assets/img/projects/".$category->name))
		{
			$old = umask(0);
			File::create_dir(DOCROOT."assets/img/projects", $category->name, 0777);
			chmod(DOCROOT."assets/img/projects/".$category->name, 777);
			File::create_dir(DOCROOT."assets/img/projects/".$category->name, "thumbs", 0777);
		}
		$esplode = explode(".", $file['name']);
		$extention = array_pop($esplode);
		$safeName = Inflector::friendly_title($this->name, "_", true).".".$extention;


		File::copy($file['tmp_name'], DOCROOT."assets/img/projects/".$category->name."/".$safeName);
		Image::load(DOCROOT."assets/img/projects/".$category->name."/".$safeName)
		    ->crop(0, 0, 800, 200)
		    ->save(DOCROOT."assets/img/projects/".$category->name."/".$safeName, 777);

		Image::load(DOCROOT."assets/img/projects/".$category->name."/".$safeName)
		    ->crop(0, 0, 100, 100)
		    ->rounded(10)
		    ->save(DOCROOT."assets/img/projects/".$category->name."/thumbs/".$safeName, 777);

		$this->image = $safeName;

		umask($old);

	}

	public function thumbnail() {
		return Asset::img("projects/".$this->category->name."/thumbs/".$this->image);
	}
	public function screenshot() {
		return Asset::img("projects/".$this->category->name."/".$this->image);
	}

}
