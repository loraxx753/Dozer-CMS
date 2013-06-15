new_modal = (url, callback) ->
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load url, () ->
		$('#myModal').modal()
		if callback
			callback()

update_page = ($el) ->
	content = $el.parent().prev()
	d = "disabled"
	if $el.hasClass(d)
		$el.text("Updated")
		$el.removeClass(d).removeAttr(d)
		$el.removeClass("btn-info").addClass("btn-primary")
	else 
		pages = new Array();
		$(".page_row").each ->
			page = {}
			page.id = $(@).data "id"
			page.parent_id = $(@).find(".parent_id").val()
			page.published = $(@).find(".published").val()
			pages[page.id] = page;
		$el.addClass(d).removeClass("btn-primary").addClass("btn-info").attr(d, d);
		$el.text("Updating...")
		$.post "/admin/update/pages", 
			"pages" : pages
			(data) ->
				update_page $el
				$("nav").html data.html
				$(".page_row").each ->
					id = $(@).data("id");
					$(@).find(".url").html '<a href="'+data.urls[id]+'">'+data.urls[id]+"</a>"
			"json"

$(".update_pages").on "click", (e) ->
	update_page $(@)

$(".admin_newpage").on "click", (e)->
	e.preventDefault()
	new_modal "/assets/snippets/newpage.html", ->
		$('#newPageSave').on "click", (e) ->
			e.preventDefault()
			$.post "/admin/create/page",
				"name" : $("#newPageName").val(),
				"parent_id" : -1
				(data) ->
					$.post "/assets/snippets/page_row.html", (text)->
						text = text
								.replace(/__page_id__/g, data.id)
								.replace(/__page_name__/g, data.name)
								.replace(/__page_url__/g, data.url)
						$text = $("<div></div>").append text
						$temp_select = $text.find ".parent_page_temp"
						for page in data.pages
							$real_select = $temp_select.clone();
							$real_select.removeClass "parent_page_temp"
							$real_select.val page.id
							$real_select.text page.name
							$text.find(".parent_page_temp").after $real_select
						$text.find(".parent_page_temp").remove()
						$(".page_table").find("tbody").append $text.html()
						$("#modal_dump").remove()
				"json"
$("#admin_newmodel").on "click", (e)->
	e.preventDefault()
	new_modal "/assets/snippets/newmodal.html", ->
		$("#add_field").on "click", (e) -> 
			e.preventDefault()
			$field = $(@).parent().prev().clone()
			$field.find("input[type=text]").val('');
			$field.find("select").val("default");
			$(@).parent().prev().after $field
		$("#newModelSave").on "click", (e)->
			e.preventDefault()
			$form = $(".model_form")
			args = []
			args.push $form.find("input[name=name]").val()
			$(".fields").each ->
				args.push $(@).find("input[type=text]").val()+":"+$(@).find("select").val()
			console.log args
			$.post "/admin/create/model", {"args" : args }, (data)->
				console.log(data);
