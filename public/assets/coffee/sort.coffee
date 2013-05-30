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
