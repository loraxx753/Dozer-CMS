<?php $profile = \Config::get("portfolio.profile"); 
echo Form::open(array("enctype" => "multipart/form-data", "class"=>"form-horizontal", "action" => "/admin/update/change")); ?>
<?=($profile['picture']) ? Asset::img($profile['picture'] ,array("class" => "img-circle img-polaroid")) : '' ?>
<div class="control-group">
<?=Form::file('picture'); ?>
</div>
<div class="control-group">
		<?php echo Form::submit('change', 'Change', array('class' => 'btn btn-primary change-picture')); ?>	
</div>

<?php echo Form::close(); 
echo Form::open(array("enctype" => "multipart/form-data", "class"=>"form-horizontal")); ?>
	<fieldset class="personal-info">
		<h2>Personal Information</h2>
		<div class="control-group">
			<?php echo Form::label('Site Name', 'name', array('class'=>'profile-label')); ?>
				<?php echo Form::input('name', Input::post('name', isset($profile['name']) ? $profile['name'] : ''), array('class' => 'span4', 'placeholder'=>'Name')); ?>
		</div>
		<div class="control-group">
			<?php echo Form::label('Email', 'email', array('class'=>'profile-label')); ?>
				<?php echo Form::input('email', Input::post('name', isset($profile['email']) ? $profile['email'] : ''), array('class' => 'span4', 'placeholder'=>'Email')); ?>
		</div>
		<div class="control-group">
			<?php echo Form::label('Phone Number', 'phone', array('class'=>'profile-label')); ?>
				<?php echo Form::input('phone', Input::post('name', isset($profile['phone']) ? $profile['phone'] : ''), array('class' => 'span4', 'placeholder'=>'Phone Number')); ?>
		</div>
	</fieldset>
	<fieldset class="social-media">
		<h2>Social Media Links</h2>

		<?php foreach($social_media as $type => $info) { ?>
		<div class="control-group">
				<?=Asset::img("social_media/".$type.".png")?>
				<?php if(preg_match("/{%username}$/", $info['link'])) { ?>
					<?=preg_replace("/{%username}/", '', $info['link']).Form::input($type, Input::post($type, ($info['username']) ? $info['username'] : ''), array('class' => 'span4', 'placeholder'=>'username')); ?>
				<?php } else if(preg_match("/^{%username}/", $info['link'])) { ?>
					<?=Form::input($type, Input::post($type, ($info['username']) ? $info['username'] : ''), array('class' => 'span4', 'placeholder'=>'username')).preg_replace("/{%username}/", '', $info['link']); ?>
				<?php } else { ?>
					<?=Form::input($type, Input::post($type, ($info['username']) ? $info['username'] : ''), array('class' => 'span4', 'placeholder'=>'link')); ?>
				<?php } ?>
		</div>
		<?php } // end $social_media foreach ?>
	</fieldset>
	<div class="control-group">
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary update-profile')); ?>	
	</div>
<?php echo Form::close(); ?>