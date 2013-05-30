// Generated by CoffeeScript 1.6.2
(function() {
  var activeTypes, oldHref;

  Array.prototype.remove = function(from, to) {
    var rest;

    rest = this.slice((to || from) + 1 || this.length);
    if (from < 0) {
      this.length = this.length + from;
    } else {
      this.length = from;
    }
    return this.push.apply(this, rest);
  };

  activeTypes = [];

  oldHref = '';

  $(".tags li a").on("click", function(e) {
    var tagType;

    e.preventDefault();
    tagType = $(this).data('type');
    if (activeTypes.indexOf(tagType) < 0) {
      activeTypes[activeTypes.length] = tagType;
    } else {
      activeTypes.remove(activeTypes.indexOf(tagType));
    }
    $(".tags li a[data-type=" + tagType + "]").parent().toggleClass("active");
    if (activeTypes.length > 0) {
      return $(".tags").each(function() {
        var type, visible, _i, _len;

        visible = true;
        for (_i = 0, _len = activeTypes.length; _i < _len; _i++) {
          type = activeTypes[_i];
          if ($(this).children("li").children("a[data-type=" + type + "]").length === 0) {
            visible = false;
            $(this).closest(".project-row").slideUp();
          }
        }
        if (visible === true) {
          return $(this).closest(".project-row").slideDown();
        }
      });
    } else {
      return $(".tags").closest(".project-row").slideDown();
    }
  });

}).call(this);
