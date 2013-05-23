<?php echo Form::open(array("enctype" => "multipart/form-data", "class"=>"form-horizontal")); ?>

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
			<?php echo Form::label('Order', 'order', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('order', Input::post('order', isset($project) ? $project->order : ''), array('class' => 'span4', 'placeholder'=>'Order')); ?>

			</div>
		<div class="control-group">
			<?php echo Form::label('Image', 'order', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::file('image'); ?>

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