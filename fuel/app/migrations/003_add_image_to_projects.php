<?php

namespace Fuel\Migrations;

class Add_image_to_projects
{
	public function up()
	{
		\DBUtil::add_fields('projects', array(
			'image' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('projects', array(
			'image'

		));
	}
}