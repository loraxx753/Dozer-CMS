# Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = (from, to) ->
	rest = this.slice((to or from) + 1 or this.length)
	if from < 0 then this.length = this.length + from else this.length = from
	this.push.apply this, rest

activeTypes = []
oldHref = ''# tag filtering code
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
