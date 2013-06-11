<h2>Admin Login</h2>
<form method="post">
        <fieldset>
                <div class="control-group">
                        <?php echo Form::label('Username or Email', 'username', array('class'=>'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::input('username', Input::post('username', isset($project) ? $project->name : ''), array('class' => 'span4', 'placeholder'=>'Username or Email')); ?>

                        </div>
                </div>
                <div class="control-group">
                        <?php echo Form::label('Password', 'password', array('class'=>'control-label')); ?>

                        <div class="controls">
                                <?php echo Form::password('password', '', array('class' => 'span4', 'placeholder'=>'Password')); ?>

                        </div>
                </div>
                <div class="control-group">
                        <label class='control-label'>&nbsp;</label>
                        <div class='controls'>
                                <?php echo Form::submit('submit', 'Login', array('class' => 'btn btn-primary')); ?>                     </div>
                </div>
</fieldset>
</form>