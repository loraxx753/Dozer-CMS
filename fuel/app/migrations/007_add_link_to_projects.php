<?php

namespace Fuel\Migrations;

class Add_link_to_projects
{
	public function up()
	{
		\DBUtil::add_fields('projects', array(
			'link' => array('type' => 'text', 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('projects', array(
			'link'
		));
	}
}