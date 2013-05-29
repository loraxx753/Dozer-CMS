# Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = (from, to) ->
	rest = this.slice((to or from) + 1 or this.length)
	if from < 0 then this.length = this.length + from else this.length = from
	this.push.apply this, rest

activeTypes = []
oldHref = ''

# Sort code
$(".sortable").sortable 
	connectWith: ".connected", 
	items: ':not(.disabled)',
	forcePlaceholderSize: true

$('.sortable').sortable().bind 'sortupdate', (e, ui)->
	cat = $(ui.item).closest(".connected");
	catId = cat.data("id")
	cat.find(".empty-category").remove()
	projId = $(ui.item).data("id");
	index = $(ui.item).index();
	$.post "admin/update/projects", {category: catId, newIndex: index, id : projId}, (data)->

$(".sortable").on "dragstart", ->
	par = $(@).closest ".connected"
	if par.children().length is 1
		par.append '<div class="row empty-category"><div class="span12"><p>There are no projects in this category</p></div></div>'
$(".sortable").on "dragover", ->
	$(@).addClass "test"

# tag filtering code
$(".tags li a").on "click", (e)->
	e.preventDefault();
	tagType = $(@).data('type')
	if activeTypes.indexOf(tagType) < 0 then activeTypes[activeTypes.length] = tagType
	else activeTypes.remove activeTypes.indexOf(tagType)
	$(".tags li a[data-type="+tagType+"]").parent().toggleClass "active"
	if activeTypes.length > 0
		$(".tags").each ->
			visible = true
			for type in activeTypes
				if $(@).children("li").children("a[data-type="+type+"]").length is 0
					visible = false
					$(@).closest(".project-row").slideUp()
			if visible is true then $(@).closest(".project-row").slideDown()
	else
		$(".tags").closest(".project-row").slideDown()

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

# Save profile code

$(".update-profile").click (e)->
	e.preventDefault();
	inputs = {}
	inputs.personal_info = {}
	inputs.social_media = {}
	$(".personal-info").find("input").each ->
		if $(@).val() != ''
			inputs.personal_info[$(@).attr("name")] = $(@).val()
		else
			inputs.personal_info[$(@).attr("name")] = null
	$(".social-media").find("input").each ->
		if $(@).val() != ''
			inputs.social_media[$(@).attr("name")] = $(@).val()
		else
			inputs.social_media[$(@).attr("name")] = null
	$.post("admin/update/profile", inputs);
