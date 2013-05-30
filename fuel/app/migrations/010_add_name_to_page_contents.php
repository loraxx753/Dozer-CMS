<?php

namespace Fuel\Migrations;

class Add_name_to_page_contents
{
	public function up()
	{
		\DBUtil::add_fields('page_contents', array(
			'name' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('page_contents', array(
			'name'

		));
	}
}