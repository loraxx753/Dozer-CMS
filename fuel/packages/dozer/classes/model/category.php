<?php
use Orm\Model;

class Model_Category extends Model
{
	protected static $_properties = array(
		'id',
		'name',
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

		return $val;
	}

	protected static $_has_many = array(
	    'projects' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Project',
	        'key_to' => 'category',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
	
	public function create_folder()
	{	

		$old = umask(0);
		if(!is_dir(DOCROOT."assets/img/projects/".$this->name))
		{
			mkdir(DOCROOT."assets/img/projects/".$this->name);
			mkdir(DOCROOT."assets/img/projects/".$this->name."/thumbs");
		}
		umask($old);
	}

	public function edit_folder($oldFolderName)
	{	

		$old = umask(0);
		if(is_dir(DOCROOT."assets/img/projects/".$oldFolderName))
		{
			rename(DOCROOT."assets/img/projects/".$oldFolderName, DOCROOT."assets/img/projects/".$this->name);
		}
		umask($old);
	}

	public function delete($cascade = null, $use_transaction = false)
	{
		parent::delete($cascade, $use_transaction);
		File::delete_dir(DOCROOT."assets/img/projects/".$this->name);
	}

}
