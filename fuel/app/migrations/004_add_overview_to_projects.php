<?php

namespace Fuel\Migrations;

class Add_overview_to_projects
{
	public function up()
	{
		\DBUtil::add_fields('projects', array(
			'overview' => array('type' => 'text'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('projects', array(
			'overview'

		));
	}
}