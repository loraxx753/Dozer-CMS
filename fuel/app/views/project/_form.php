<?php echo Form::open(array("enctype" => "multipart/form-data", "class"=>"form-horizontal")); ?>
		<?php if(isset($project->image)) {  ?>
		<div class="control-group">
		<?php
			echo $project->screenshot(array("class" => "img-rouned img-polaroid"));
		?>
		</div>
		<?php } ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Name', 'name', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('name', Input::post('name', isset($project) ? $project->name : ''), array('class' => 'span4', 'placeholder'=>'Name')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Overview', 'overview', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('overview', Input::post('overview', isset($project) ? $project->name : ''), array('class' => 'span4', 'placeholder'=>'Overview')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Description', 'description', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::textarea('description', Input::post('description', isset($project) ? $project->description : ''), array('class' => 'span4', 'placeholder'=>'Description')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Category', 'category', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php 
				if(!isset($categories)) { ?>
					<a class="btn btn-success" href="/admin/category/create">Create A Category</a>
				<?php } else {
				echo Form::select('category', Input::post('category', isset($project) ? $project->category : ''), $categories); ?>
				<?php } ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Tags', 'tags', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php 
				if(!isset($tags)) { ?>
					<a class="btn btn-success" href="/admin/tag/create">Add A Tag</a>
				<?php } else {
					$selectedTags = array();
					if(isset($project->tags))
					{
						foreach($project->tags as $tag)
						{
							$selectedTags[] = $tag->id;
						}
					}
				echo Form::select('tag', Input::post('tag', isset($project) ? $selectedTags : ''), $tags, array("multiple" => "multiple")); ?>
				<?php } ?>
			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Image', 'order', array('class'=>'control-label')); 
			
				?>
			<div class="controls">
				<?php echo ((isset($project->image)) ? $project->thumbnail() : ''." ".Form::file('image')); ?>

			</div>
		</div>
		<div class="control-group">
			<?php echo Form::label('Link', 'link', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('link', Input::post('name', isset($project) ? $project->link : ''), array('class' => 'span4', 'placeholder'=>'http://www.example.com')); ?>

			</div>
		</div>

		<?php if(isset($categories)) { ?>
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>			</div>
		</div>
		<?php } else { ?>
		<p> You have to create a category first!</p>
		<?php }?>
	</fieldset>
<?php echo Form::close(); ?>