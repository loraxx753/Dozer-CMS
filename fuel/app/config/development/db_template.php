<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=__db_host__;dbname=__db_name__',
			'username'   => '__db_username__',
			'password'   => '__db_password__',
		),
	),
);
