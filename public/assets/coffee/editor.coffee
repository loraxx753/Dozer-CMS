save_text = ($el) -> 
	content = $el.parent().prev()
	d = "disabled"
	if $el.hasClass(d)
		$el.text("Saved")
		$el.removeClass(d).removeAttr(d)
		$el.removeClass("btn-info").addClass("btn-success")
	else 
		$el.addClass(d).removeClass("btn-success").addClass("btn-info").attr(d, d);
		$el.text("Saving...")
		$.post "/admin/update/content", 
			name: $(content).attr "id"
			content: $(content).html()
			(data) ->
				save_text $el
$(".hallo_edit").click (e) -> 
	e.preventDefault()
	if $(this).hasClass("btn-primary")
		$(this).removeClass("btn-primary").addClass("btn-danger").text("Stop Editing")
		$(".editable").addClass("active");
		$(".editable").after('<p><button class="btn btn-success pull-right hallo_save" data-loading-text="Saving...">Save</button></p>')
		$('.editable').hallo
			plugins: 
				'halloformat': {}
				'halloblock': {}
				'hallojustify': {}
				'hallolists': {}
				'hallolink': {}
				'halloreundo': {}
				'halloimage': 
					upload: (data) -> 
						console.log "success"
					uploadUrl: "/admin/imageupload"
			editable: true
			toolbar: 'halloToolbarFixed'
		$("#page_content").on "click", ".hallo_save", "click", (e) ->
			e.preventDefault
			save_text $(this)
	else
		$(this).removeClass("btn-danger").addClass("btn-primary").text("Edit")
		$(".hallo_save").parent().remove()
		$(".editable").removeClass("active");
		jQuery('.editable').hallo
			editable: false
$("#publish_page").on "click", ->
	d = "disabled"
	$el = $(@);
	if page_is_published == true
		$el.addClass(d).attr(d, d);
		$el.text("Un-Publishing...")
		$.post "/admin/update/publish", 
			clean_name: current_page
			publish: 0
			(data) ->
				$el.text("Publish")
				$el.removeClass(d).removeAttr(d)
				$el.removeClass("btn-warning").addClass("btn-primary")
				window.page_is_published = false
	else
		$el.addClass(d).attr(d, d);
		$el.text("Publishing...")
		$.post "/admin/update/publish", 
			clean_name: current_page
			publish: 1
			(data) ->
				$el.removeClass("btn-primary").addClass("btn-warning")
				$el.text("Un-Publish")
				$el.removeClass(d).removeAttr(d)
				$el.removeClass("btn-primary").addClass("btn-warning")
				window.page_is_published = true
$("#add_content_block").on "click", ->
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load "/assets/snippets/contentblock.html", () ->
		$('#myModal').modal()
		$('#contentBlockSave').on "click", (e) ->
			e.preventDefault()
			$.post "/admin/create/block",
				"name" : $("#contentBlockName").val()
				"page" : window.current_page
				(data) ->
					$("#page_content").append(data);
$("#add_sub_page").on "click", ->
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load "/assets/snippets/subpage.html", () ->
		$('#myModal').modal()
		$('#newSubPageSave').on "click", (e) ->
			e.preventDefault()
			$.post "/admin/create/subpage",
				"name" : $("#newSubPageName").val()
				"parent" : window.current_page
				(data) ->
					window.location = "/"+data


