
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
