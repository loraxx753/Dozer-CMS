
<?php foreach($categories as $category) { ?>
<h2><?=$category->name?></h2>
<hr />
<div class="row">
	<div class="span12">
		<?php if(!$category->projects) { ?>
		<p>There are no projects in this category</p>



		<?php }
		else { 
			foreach ($category->projects as $project) { ?>
		<div class="row">
			<div class="span2">
				<?=$project->thumbnail()?>
			</div>
			<div class="span4">
				<h3><?=$project->name?></h3>
			</div>
			<div class="span6">
				<?=$project->overview?></p>
			</div>
		</div>
		<hr />
			<?php } // end foreach 
		} // end else?>
	</div>
</div>



<?php } ?>