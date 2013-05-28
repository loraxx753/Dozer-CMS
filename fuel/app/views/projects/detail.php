<?=$project->screenshot(array("class" => "img-polaroid img-rounded"))?>
<h2><?=$project->name?></h2>

<?php if($project->link) { ?>
	<ul>
		<li>Link: <a href="<?=$project->link?>"><?=$project->link?></a></li>
	</ul>
<?php } ?>
<?=Markdown::parse($project->description)?>