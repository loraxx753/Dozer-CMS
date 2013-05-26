<?php

namespace Fuel\Migrations;

class Create_languages_projects
{
	public function up()
	{
		\DBUtil::create_table('languages_projects', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'language_id' => array('constraint' => 11, 'type' => 'int'),
			'project_id' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('languages_projects');
	}
}