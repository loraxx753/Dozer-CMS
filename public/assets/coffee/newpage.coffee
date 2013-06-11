$(".newpage").on "click", (e)->
	e.preventDefault()
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load "/assets/snippets/newpage.html", () ->
		$('#myModal').modal()
		$('#newPageSave').on "click", (e) ->
			e.preventDefault()
			$.post "/admin/create/page",
				"name" : $("#newPageName").val(),
				"parent_id" : 0
				(data) ->
					window.location = "/"+data.clean_name
