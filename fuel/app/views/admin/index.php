<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Pages</a></li>
    <li><a href="#tab2" data-toggle="tab">Models</a></li>
    <li><a href="#tab3" data-toggle="tab">CSS</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
    <?=View::forge('admin/pages', $data)->render()?>
    </div>
    <div class="tab-pane" id="tab2">
    <?=View::forge('admin/models', $data)->render()?>
    </div>
    <div class="tab-pane" id="tab3">
    <?=View::forge('admin/css')->render()?>
    </div>
  </div>
</div>