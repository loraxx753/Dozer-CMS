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
	body {
		width: 1024px;
		margin: 0 auto;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
	$("button#git_update").on("click", function(e) {
		e.preventDefault();
		$("#progressModal .text").text("Setting up everything. This might take a bit...");
		$('#progressModal').modal({
			'backdrop' : 'static',
			'keyboard' : 'false'
		});
		$('#progressModal').modal('show');
		$.post("index.php", {"action": "git_updates"}, function(data) {
			$("#progressModal").modal('hide');
			$("a[href=#collapseTwo]").click();
		});
	});
	$("#database_setup").on("click", function(e) {
		e.preventDefault();
		var options = {
			"action"      : "setup_database",
			"db_host"     : $("#db_host").val(),
			"db_name"     : $("#db_name").val(),
			"db_username" : $("#db_username").val(),
			"db_password" : $("#db_password").val()
		};
		$("#progressModal .text").text("Setting up database, so you don't have to.");
		$('#progressModal').modal({
			'backdrop' : 'static',
			'keyboard' : 'false'
		});
		$('#progressModal').modal('show');
		$.post("index.php", options, function(data) {
			$("#progressModal").modal('hide');
			$("a[href=#collapseThree]").click();
		});
	});
	$("#admin_setup").on("click", function(e) {
		e.preventDefault();
		var options = {
			"action"          : "setup_admin",
			"admin_username"  : $("#admin_username").val(),
			"admin_email"     : $("#admin_email").val(),
			"admin_password"  : $("#admin_password").val(),
			"admin_password2" : $("#admin_password2").val()
		};
		$("#progressModal .text").text("Setting up administrator, so you can do things.");
		$('#progressModal').modal({
			'backdrop' : 'static',
			'keyboard' : 'false'
		});
		$('#progressModal').modal('show');
		$.post("index.php", options, function(data) {
			$("#progressModal").modal('hide');
			window.location="/";
		});
	});

});

</script>



</head>
<body>
<div class="row">
	<div class="span12 center">
		<p><img src="/assets/img/dozer/dozer_256.png" /></p>
		<h1>Welcome to Dozer</h1>

		<div class="accordion" id="accordion1">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
						Welcome to Dozer
					</a>
				</div>
				<div id="collapseOne" class="accordion-body collapse in">
					<div class="accordion-inner">
						<p>To make things easier to initially upload, we've only uploaded "some" of the files you need. To get the rest of the nessicary files, click that big button below.</p>
						<button class="btn btn-big btn-primary" id="git_update">Start it up!</button>
					</div> <!-- end .accordion-inner -->
				</div> <!-- end #collapseOne -->
			</div><!-- end .accordion-group -->
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo">
						Database Information
					</a>
				</div>
				<div id="collapseTwo" class="accordion-body collapse">
					<div class="accordion-inner">
						<form method="post">
							<fieldset>
								<p><label for="db_host">Database Host: </label><input type="text" name="db_host" id="db_host" value="localhost"></p>
								<p><label for="db_name">Database Name: </label><input type="text" name="db_name" id="db_name" placeholder="dozer"></p>
								<p><label for="db_username">Database Username: </label><input type="text" name="db_username" id="db_username" placeholder="root"></p>
								<p><label for="db_password">Database Password: </label><input type="password" name="db_password" id="db_password" placeholder="root"></p>
							</fieldset>
							<p><input class="btn btn-success" id="database_setup" type="submit" value="Submit" /></p>
						</form>
					</div> <!-- end .accordion-inner -->
				</div> <!-- end #collapseTwo -->
			</div><!-- end .accordion-group -->
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseThree">
						Administrator Information
					</a>
				</div>
				<div id="collapseThree" class="accordion-body collapse">
					<div class="accordion-inner">
						<form method="post">
							<fieldset>
								<p><label for="admin_username">Administrator Username: </label><input type="text" name="admin_username" id="admin_username" placeholder="Username"></p>
								<p><label for="admin_email">Administrator Email: </label><input type="text" name="admin_email" id="admin_email" placeholder="Email"></p>
								<p><label for="admin_password">Administrator Password: </label><input type="password" name="admin_password" id="admin_password" placeholder="Password"></p>
								<p><label for="admin_password2">Re-type Password: </label><input type="password" name="admin_password2" id="admin_password2" placeholder="Retype Password"></p>
							</fieldset>
							<p><input class="btn btn-success" type="submit" id="admin_setup" value="Submit" /></p>
						</form>
					</div> <!-- end .accordion-inner -->
				</div> <!-- end #collapseThree -->
			</div><!-- end .accordion-group -->
		</div> <!-- end .accordion#accordion2 -->
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
