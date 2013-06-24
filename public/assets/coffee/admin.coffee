String.prototype.capitalize = () -> 
    @.charAt(0).toUpperCase() + this.slice(1)

new_modal = (url, callback) ->
	$("body").append "<div id='modal_dump'></div>"
	$("#modal_dump").load url, () ->
		$('#myModal').modal()
		if callback
			callback($("#myModal"))

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
$(".add_entry").on "click", (e) ->
	e.preventDefault()
	$this = $(@)
	id = $this.closest(".page_row").data("id")
	name = $this.closest("td").prevUntil(".model_name").last().prev().text()
	console.log $this.closest("td").prevUntil(".model_name").last().prev().text()
	fields = {}
	$this.closest("td").prevUntil(".field_list").prev().children("ul").children("li").each ->
		fields[$(@).text()] = $(@).data('type')
	new_modal "/assets/snippets/model_entry.html", ($modal)->
		text = $modal.html()
		text = text
			.replace(/__Model_Name__/g, name.capitalize())
		$modal.html(text)
		for element, type of fields
			$fieldClone = $(".model_"+type).clone();
			$fieldClone.removeClass("hidden")
			fieldtext = $fieldClone.html()
			fieldtext = fieldtext
				.replace(/__Column_Name__/g, element.capitalize())
				.replace(/__column_name__/g, element)
			$fieldClone.html(fieldtext)
			$(".model_field_form").append($fieldClone)
			if $fieldClone.hasClass("model_text")
				$fieldClone.children(".content").hallo({
					plugins: {
						'halloformat': {},
						'halloblock': {},
						'hallojustify': {},
						'hallolists': {},
						'hallolink': {},
						'halloreundo': {},
						'halloimage': {
							upload: (data) ->
								return console.log("success");
							,
							uploadUrl: "/admin/imageupload"
						}
					},
					editable: true,
					toolbar: 'halloToolbarFixed'
				});
		$("#modelEntrySave").on "click", (e)->
			e.preventDefault()
			obj = {}
			obj.name = name
			obj.texts = for model in $(".model_field_form .model_text")
				returned = {}
				returned.name = $(model).children("p").data("name")
				returned.content = $(model).find(".content").html()
				returned
			obj.ints = for model in $(".model_field_form .model_int")
				returned = {}
				returned.name = $(model).children("input").attr("id")
				returned.content = $(model).children("input").val()
				returned
			obj.strings = for model in $(".model_field_form .model_string")
				returned = {}
				returned.name = $(model).children("input").attr("id")
				returned.content = $(model).children("input").val()
				returned
			$.post "/admin/create/entry", obj, (data)->

