// Generated by CoffeeScript 1.6.2
(function() {
  $(".newpage").on("click", function(e) {
    e.preventDefault();
    $("body").append("<div id='modal_dump'></div>");
    return $("#modal_dump").load("/assets/snippets/newpage.html", function() {
      $('#myModal').modal();
      return $('#newPageSave').on("click", function(e) {
        e.preventDefault();
        return $.post("/admin/create/page", {
          "name": $("#newPageName").val(),
          "parent_id": 0
        }, function(data) {
          return window.location = "/" + data.clean_name;
        });
      });
    });
  });

}).call(this);
