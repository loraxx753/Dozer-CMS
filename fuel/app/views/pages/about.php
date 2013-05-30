<?php if(\Auth::member(100)) { ?>
<p><button class="btn btn-primary hallo_edit">Edit</button></p>
<?php } ?>
<?=Asset::img("profile.jpg",array("class" => "img-circle img-polaroid pull-left right-margin-out"))?>
<div class="editable">
<?=$about_me?>
</div>
