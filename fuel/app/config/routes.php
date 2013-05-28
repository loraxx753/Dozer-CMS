<?php
return array(
	'_root_'  => 'pages/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	
	'contact'     => '/pages/contact',
	'about'       => '/pages/about',
	'resume'      => '/pages/resume',
	'admin-login' => '/pages/login',
	'logout'      => '/pages/logout',
	
	'projects/sort/:sortables' => 'projects/index/$1',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);