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
		'overview',
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

	protected static $_many_many = array('languages');

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Name', 'required|max_length[255]');
		$val->add_field('description', 'Description', 'required|max_length[255]');
		$val->add_field('category', 'Category', 'required|valid_string[numeric]');

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

	public function move_image_assets($oldFolderId, $project)
	{
		$newFolderName = Model_Category::find($project->category)->name;
		$oldFolderName = Model_Category::find($oldFolderId)->name;

		$project->create_category_folders($project->category);
		File::rename(
			DOCROOT."assets/img/projects/".$oldFolderName."/".$project->image, 
			DOCROOT."assets/img/projects/".$newFolderName."/".$project->image
		);
		File::rename(
			DOCROOT."assets/img/projects/".$oldFolderName."/thumbs/".$project->image, 
			DOCROOT."assets/img/projects/".$newFolderName."/thumbs/".$project->image
		);


	}

	public function create_category_folders($folderNumber = null)
	{
		if(!$folderNumber) {
			$category = Model_Category::find($this->category);
		}
		else
		{
			$category = Model_Category::find($folderNumber);			
		}
		$old = umask(0);
		if(!is_dir(DOCROOT."assets/img/projects/".$category->name))
		{
			File::create_dir(DOCROOT."assets/img/projects/", $category->name, 0777);
			chmod(DOCROOT."assets/img/projects/".$category->name, 777);
			File::create_dir(DOCROOT."assets/img/projects/".$category->name, "thumbs", 0777);
		}
		umask($old);
	}
	public function create_file($file)
	{

		$category = Model_Category::find($this->category);			
		$old = umask(0);

		$this->create_category_folders();

		$esplode = explode(".", $file['name']);
		$extention = array_pop($esplode);
		$safeName = Inflector::friendly_title($this->name, "_", true).".".$extention;

		chmod($file['tmp_name'], 777);
		File::copy($file['tmp_name'], DOCROOT."assets/img/projects/".$category->name."/".$safeName);
		chmod(DOCROOT."assets/img/projects/".$category->name."/".$safeName, 0777);
		Image::load(DOCROOT."assets/img/projects/".$category->name."/".$safeName)
		    ->crop(0, 0, 800, 200)
		    ->save(DOCROOT."assets/img/projects/".$category->name."/".$safeName, 0777);

		Image::load(DOCROOT."assets/img/projects/".$category->name."/".$safeName)
		    ->crop(0, 0, 100, 100)
		    ->rounded(10)
		    ->save(DOCROOT."assets/img/projects/".$category->name."/thumbs/".$safeName, 0777);

		$this->image = $safeName;

		umask($old);
	}

	public function thumbnail($extra = array()) {
		if(is_object($this->category))
		{
			$cat = $this->category->name;
		}
		else
		{
			$cat = Model_Category::find($this->category)->name;
		}
		return Asset::img("projects/".$cat."/thumbs/".$this->image, $extra);
	}
	public function screenshot($extra = array()) {
		if(is_object($this->category))
		{
			$cat = $this->category->name;
		}
		else
		{
			$cat = Model_Category::find($this->category)->name;
		}
		return Asset::img("projects/".$cat."/".$this->image, $extra);
	}

}
