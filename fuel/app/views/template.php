<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php 
	if($current_css = \Config::get("portfolio.bootswatch")) { 
		echo Asset::css(array('bootswatch/'.$current_css.'/bootstrap.min.css'), array("class" => "bootstrap")); 	
		$options = array("class" => "custom_css", "disabled" => true);
	} else {
		echo Asset::css(array('bootstrap.css'), array("class" => "bootstrap")); 
		$options = array("class" => "custom_css");
	}

		echo Asset::css(array('fontawesome.css', 'hallo.css')); 
	?>
	<?php 
		echo Asset::css(array('styles.css'), $options); 
		echo Asset::css(array('base.css', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css')); 
	?>
</head>
<body>
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="/"><?=\Config::get("portfolio.profile.name")?></a>
			<ul class="nav">
				<?php foreach($pages as $page) { ?>
				<li<?=($currentPage == $page->name)?" class='active'":''?>><a href="/<?=$page->clean_name?>"><?=ucwords($page->name)?></a></li>
				<?php } ?>
				<?php if(\Auth::member(100)) { ?>
				<li><a href="#" id="newpage">New Page+</a></li>
				<?php } ?>
			</ul>
			<?php if(\Auth::member(100)) { ?>
			<ul class="nav pull-right">
				<li><a class="pull-right right-pipe" href="/admin">Admin Panel</a></li>
				<li><a class="pull-right" href="/logout">Logout</a></li>
			</ul>
			<?php } ?>
		</div>
	</div>
	<div class="container">
		<div class="span12">
<?php if (Session::get_flash('success')): ?>
			<div class="alert alert-success">
				<strong>Success</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
				</p>
			</div>
<?php endif; ?>
<?php if (Session::get_flash('error')): ?>
			<div class="alert alert-error">
				<strong>Error</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
				</p>
			</div>
<?php endif; ?>
		</div>
		<div class="span12">
		<?php if(isset($editable)) { ?>
		<p>
			<button class="btn btn-success" id="add_content_block">Add Content Block</button>
			<?php if(!Uri::segment(1)) { ?>
			<button class="btn btn-success">Add Subpage</button>
			<?php } ?>
			<button class="btn btn-primary hallo_edit">Edit</button>
			<?php if(!Uri::segment(1)) { ?>

			<?php if(isset($published)) { ?>
			<button class="btn btn-warning" id="publish_page">Un-Publish</button>
			<?php } else { ?>
			<button class="btn btn-primary" id="publish_page">Publish Page</button>
			<?php } ?>
			<?php } ?>
			<button class="btn btn-danger pull-right">Delete Page</button>
		</p>
		<?php } ?>
<div id="page_content">
<?php echo $content; ?>
</div>
		</div>
		<footer>
			<p class="muted">Copyright &copy; 2013 Kevin Baugh</p>
		</footer>
	</div>
	<?=Casset::render_js_inline();?>

	<?php echo Asset::js(array(
		'//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js',
		'bootstrap.js', 
		'html5sortable/jquery.sortable.js', 
		"jquery-markdown/markdown/Markdown.Converter.js",
		"jquery-markdown/markdown/Markdown.Sanitizer.js",
		"jquery-markdown/markdown/Markdown.Editor.js",
		"jquery-markdown/markdown/jquery.markdown.js"
	)); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script src="http://rangy.googlecode.com/svn/trunk/currentrelease/rangy-core.js"></script>
	<?php echo \Casset::render_js(); ?>
</body>
</html>
