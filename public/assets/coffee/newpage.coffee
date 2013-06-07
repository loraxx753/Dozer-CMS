$("#newpage").on "click", (e)->
	e.preventDefault()
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load "/assets/snippets/newpage.html", () ->
		$('#myModal').modal()
		$('#newPageSave').on "click", (e) ->
			e.preventDefault()
			$.post "/admin/create/page",
				"name" : $("#newPageName").val()
				(data) ->
					window.location = "/"+data
