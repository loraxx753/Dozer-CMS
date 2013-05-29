<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Projects</a></li>
    <li><a href="#tab2" data-toggle="tab">Categories</a></li>
    <li><a href="#tab3" data-toggle="tab">Tags</a></li>
    <li><a href="#tab4" data-toggle="tab">CSS</a></li>
    <li><a href="#tab5" data-toggle="tab">Profile</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
	<?=View::forge('project/index', array("projects" => $projects))->render()?>
    </div>
    <div class="tab-pane" id="tab2">
    <?=View::forge('category/index', array("categories" => $categories))->render()?>
    </div>
    <div class="tab-pane" id="tab3">
    <?=View::forge('tag/index', array("tags" => $tags))->render()?>
    </div>
    <div class="tab-pane" id="tab4">
    <?=View::forge('admin/css')->render()?>
    </div>
    <div class="tab-pane" id="tab5">
    <?=View::forge('admin/profile', array("social_media" => $social_media))->render()?>
    </div>
  </div>
</div>