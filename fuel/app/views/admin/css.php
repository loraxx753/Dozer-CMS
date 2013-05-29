<ul class="bootswatch">
<?php $current_css = \Config::get("portfolio.bootswatch")?>
<li<?=($current_css)?'':" class='active'"?>><a href="#" data-name="reset"><?=($current_css)?'':"<strong>"?>Reset<?=($current_css)?'':"</strong>"?></a></li>
<?php 
$folders = File::read_dir(DOCROOT."assets/css/bootswatch");
foreach($folders as $folder => $files) {
	$cleanFolder = str_replace("/", '', $folder);
	if(strtolower($folder) != "img/")
		echo "<li".(($current_css == $cleanFolder)?" class='active'":'')."><a href='#' data-name='".str_replace("/", '', $folder)."'>".(($current_css == $cleanFolder)?"<strong>":'').ucwords($cleanFolder).(($current_css == $cleanFolder)?"</strong>":'')."</a></li>";
}

?>
</ul>

<button id="save-css" class="btn btn btn-primary">Save</button>