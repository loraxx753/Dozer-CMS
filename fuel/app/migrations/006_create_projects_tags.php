<?php

namespace Fuel\Migrations;

class Create_projects_tags
{
	public function up()
	{
		\DBUtil::create_table('projects_tags', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'tag_id' => array('constraint' => 11, 'type' => 'int'),
			'project_id' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('projects_tags');
	}
}