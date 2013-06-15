<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */


Autoloader::add_classes(array(
	'Dozer\\Generate'				=> __DIR__.'/classes/generate.php',
	'Dozer\\Update'					=> __DIR__.'/classes/update.php',
	'Dozer\\Install'				=> __DIR__.'/classes/install.php',
	'Dozer\\Update_Page'			=> __DIR__.'/classes/update/page.php',

	/* Models */
	'Dozer\\Model_Page'				=> __DIR__.'/classes/model/page.php',
	'Dozer\\Model_Model'			=> __DIR__.'/classes/model/model.php',
	'Dozer\\Model_Page_Content'		=> __DIR__.'/classes/model/page/content.php',
));

/* End of file bootstrap.php */
