<?php

namespace Fuel\Migrations;

class Add_clean_name_to_pages
{
	public function up()
	{
		\DBUtil::add_fields('pages', array(
			'clean_name' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('pages', array(
			'clean_name'

		));
	}
}