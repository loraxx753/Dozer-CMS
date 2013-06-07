<?php

namespace Fuel\Migrations;

class Create_page_contents
{
	public function up()
	{
		\DBUtil::create_table('page_contents', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'page_id' => array('constraint' => 11, 'type' => 'int'),
			'contents' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('page_contents');
	}
}