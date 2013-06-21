<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Dozer Setup Script</title>
<link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
<style>
	.center {
		text-align: center;
	}
	#parent_slider {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
	}
	.page {
		position: absolute;
		width: 100%;
		height: 100%;
	}
	.page:not(:first-child) {
		display: none;
	}
	.content {
		width: 100%;
		height: 500px;
		position: absolute;
		top: 25%;
		vertical-align: bottom;
	}
	form input.invalid {
		border : 2px solid red;
	}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
	function build_error(message)
	{
		return '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong> '+message+'</div><a class="close" data-dismiss="alert" href="#">&times;</a>';
	}
	function process(options, popup, element, callback)
	{
		var $form = $(element).closest("form");
		inputs = $form.find("input");
		valid = true;
		for(i=0;i < inputs.length; i++)
		{
			if(!inputs[i].checkValidity())
			{
				valid = false;
			}
		}
		if(!valid)
		{
			$form.find("input[type=submit]").click();
			return false;
		}

		$("#progressModal .text").text(popup);
		$('#progressModal').modal({
			'backdrop' : 'static',
			'keyboard' : 'false'
		});
		$('#progressModal').modal('show');
		$.post("/", options, function(data) {
			$('#progressModal').modal('hide');
			if(!data.success)
			{
				var error = build_error(data.error);
				$(".page:visible").children(".content").prepend(error);
				$(".alert").alert();
			}
			else
			{
				var $page = $(".page:visible");
				var $page2 = $page.next();
				// Hide the first page by sliding it to the left.
				$page.hide('slide', {direction : 'left'});
				// Then show the second by sliding it out from the right.
				$page2.show('slide', {direction : 'right'});
				if(callback)
				{
					callback();
				}
			}
		}, 'json');

	}

	$("form").on("submit", function(e) {
		e.preventDefault();
	});
	$("#admin_setup").on("click", function(e) {
		e.preventDefault();
		var options = {
			"action"	      : "admin_setup",
			"admin_username"  : $("#admin_username").val(),
			"admin_email"     : $("#admin_email").val(),
			"admin_password"  : $("#admin_password").val(),
			"admin_password2" : $("#admin_password2").val()
		};
		var popup = "Setting up administrator, so you can do things.";
		process(options, popup, this, function() {
			window.location="/admin";
		});
	});

	$("#submit_database").on("click", function(e) {
		var options = {
			"action"	  : "database_setup",
			"db_host"     : $("#db_host").val(),
			"db_name"     : $("#db_name").val(),
			"db_username" : $("#db_username").val(),
			"db_password" : $("#db_password").val()
		};
		var popup = "Setting up database, so things work.";
		process(options, popup, this);
	});

	$('.content .next').on("click", function(e) {
		e.preventDefault();
		var $page = $(this).closest(".page");
		var $page2 = $page.next();
		// Hide the first page by sliding it to the left.
		$page.hide('slide', {direction : 'left'});
		// Then show the second by sliding it out from the right.
		$page2.show('slide', {direction : 'right'});		
	});

});

</script>



</head>
<body>
<div class="row">
	<div id="parent_slider" class="center">
		<div class="page main">
			<div class="content">
				<p><img src="/assets/img/dozer/dozer_256.png" /></p>
				<h1>Welcome to Dozer</h1>
				<button class="btn btn-large btn-primary next">Get Started ></button>
			</div>
		</div>

		<div class="page">
			<div class="content database">
				<form method="post">
					<fieldset>
						<p><label for="db_host">Database Host: </label><input type="text" name="db_host" id="db_host" value="localhost" required></p>
						<p><label for="db_name">Database Name: </label><input type="text" name="db_name" id="db_name" placeholder="dozer" required></p>
						<p><label for="db_username">Database Username: </label><input type="text" name="db_username" id="db_username" placeholder="root" required></p>
						<p><label for="db_password">Database Password: </label><input type="password" name="db_password" id="db_password" placeholder="root" required></p>
					</fieldset>
					<p><button class="btn btn-success" id="submit_database">Set Database</button></p>
					<input type="submit" id="hidden_db_submit" style="display:none;" />
				</form>
			</div>
		</div>

		<div class="page administrator">
			<div class="content">
				<form method="post">
					<fieldset>
						<p><label for="admin_username">Administrator Username: </label><input type="text" name="admin_username" id="admin_username" placeholder="Username" required></p>
						<p><label for="admin_email">Administrator Email: </label><input type="email" name="admin_email" id="admin_email" placeholder="Email" required></p>
						<p><label for="admin_password">Administrator Password: </label><input type="password" name="admin_password" id="admin_password" placeholder="Password" required></p>
						<p><label for="admin_password2">Re-type Password: </label><input type="password" name="admin_password2" id="admin_password2" placeholder="Retype Password" required></p>
					</fieldset>
					<p><input class="btn btn-success" type="submit" id="admin_setup" value="Finish Up" /></p>
				</form>
			</div>
		</div>
		<div class="page success">
			<div class="content">
				<p>Everything is all setup, and we're re-directing you to your homepage.</p>
			</div>
		</div>
	</div>
<div id="progressModal" class="modal hide fade">
	<div class="modal-body">
		<p class="text">Seting up...</p>
		<div class="progress progress-striped active">
			<div class="bar" style="width: 100%; padding: 10px;"></div>
		</div>
	</div>
</div>

</body>
</html>
