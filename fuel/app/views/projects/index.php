	<?php foreach($categories as $category) { ?>
	<h2><?=$category->name?></h2>
	<hr />
	<div class="row connected sortable" data-id="<?=$category->id?>">
			<?php if(!$category->projects) { ?>
		<div class="row empty-category disabled">
			<div class="span12">
				<p>There are no projects in this category</p>
			</div>
		</div>
			<?php }
			else { 
				foreach ($category->projects as $project) { ?>
		<div class="row project-row" data-id="<?=$project->id?>">
			<div class="span12">
				<div class="media">
				  <a class="pull-left" href="/projects/detail/<?=Inflector::friendly_title($project->name,'-',true)?>">
				    <?=$project->thumbnail(array("class" => "media-object"))?>
				  </a>
				  <div class="media-body">
				    <h4 class="media-heading"><?=$project->name?></h4>
					<p><?=$project->overview?></p>
				  </div>
					<ul class="nav nav-pills tags">
				  	<?php foreach ($project->tags as $tag) { ?>
					  <li><a href="#" data-type="<?=$tag->name?>"><?=$tag->name?></a></li>
				  	<?php } ?>
					</ul>
				</div>
			</div>
		</div>
				<?php } // end foreach 
			} // end else?>
</div>


<?php } //end foreach ?>