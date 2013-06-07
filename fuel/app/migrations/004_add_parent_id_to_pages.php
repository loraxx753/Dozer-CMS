<?php

namespace Fuel\Migrations;

class Add_parent_id_to_pages
{
	public function up()
	{
		\DBUtil::add_fields('pages', array(
			'parent_id' => array('constraint' => 11, 'type' => 'int', 'default' => 0),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('pages', array(
			'parent_id'

		));
	}
}