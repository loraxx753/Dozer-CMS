<?php

namespace Fuel\Migrations;

class Add_url_to_pages
{
	public function up()
	{
		\DBUtil::add_fields('pages', array(
			'url' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('pages', array(
			'url'

		));
	}
}