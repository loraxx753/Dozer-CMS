<ul class="bootswatch">
<?php 
$folders = File::read_dir(DOCROOT."assets/css/bootswatch");

foreach($folders as $folder => $files) {
	if($folder != "img")
		echo "<li><a href='#' data-name='".str_replace("/", '', $folder)."'>".ucwords(str_replace("/", '', $folder))."</a></li>";
}
?>
</ul>