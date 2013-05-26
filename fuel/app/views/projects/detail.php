<?=$project->screenshot(array("class" => "img-polaroid img-rounded"))?>
<h2><?=$project->name?></h2>

<?=Markdown::parse($project->description)?>