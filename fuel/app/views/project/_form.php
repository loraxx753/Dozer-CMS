<?php echo Form::open(array("enctype" => "multipart/form-data", "class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="control-group">
			<?php echo Form::label('Name', 'name', array('class'=>'control-label')); ?>

			<div class="controls">
				<?php echo Form::input('name', Input::post('name', isset($project) ? $project->name : ''), array('class' => 'span4', 'placeholder'=>'Name')); ?>

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
				<?php echo Form::select('category', Input::post('category', isset($project) ? $project->category : ''), $categories); ?>

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
		<div class="control-group">
			<label class='control-label'>&nbsp;</label>
			<div class='controls'>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>			</div>
		</div>
	</fieldset>
<?php echo Form::close(); ?>