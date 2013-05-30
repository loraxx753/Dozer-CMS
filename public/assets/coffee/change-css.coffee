# Change css code

$(".bootswatch a").click (e)->
	e.preventDefault()
	folder = $(@).data "name"
	$currentActive = $(".bootswatch .active")
	$currentActive.removeClass("active").children("a").html($currentActive.children("a").text())
	$(@).parent().addClass("active").children("a").html("<strong>"+$(".bootswatch .active").text()+"</strong>")
	current_css = $(@).text().toLowerCase();
	console.log folder
	if folder != "reset"
		oldHref = $(".bootstrap").attr("href")
		broken = oldHref.split("css");
		newHref = broken[0]+"css/bootswatch/"+folder+"/bootstrap.min.css";
		$(".bootstrap").attr("href", newHref);
		$(".custom_css").attr("disabled", true);
	else
		$(".bootstrap").attr("href", "/assets/css/bootstrap.css");
		$(".custom_css").removeAttr("disabled");
$("#save-css").click (e)->
	e.preventDefault();
	folder = $(".bootswatch .active").children("a").data "name"
	$.post "admin/update/css", {"name" : folder }, (data)->
		alert "updated";
