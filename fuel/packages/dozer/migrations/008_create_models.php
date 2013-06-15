<?php

namespace Fuel\Migrations;

class Create_models
{
	public function up()
	{
		\DBUtil::create_table('models', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'fields' => array('type' => 'text'),
			'has_one' => array('type' => 'text'),
			'has_many' => array('type' => 'text'),
			'many_to_many' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('models');
	}
}