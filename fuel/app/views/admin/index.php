<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Projects</a></li>
    <li><a href="#tab2" data-toggle="tab">Categories</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
		<?=View::forge('project/index', array("projects" => $projects))->render()?>
    </div>
    <div class="tab-pane" id="tab2">
		<?=View::forge('category/index', array("categories" => $categories))->render()?>
    </div>
  </div>
</div>