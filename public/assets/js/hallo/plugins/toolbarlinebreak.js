// Generated by CoffeeScript 1.6.2
(function() {
  (function(jQuery) {
    return jQuery.widget("Liip.hallotoolbarlinebreak", {
      options: {
        editable: null,
        uuid: "",
        breakAfter: []
      },
      populateToolbar: function(toolbar) {
        var buttonRow, buttonset, buttonsets, queuedButtonsets, row, rowcounter, _i, _j, _len, _len1, _ref;

        buttonsets = jQuery('.ui-buttonset', toolbar);
        queuedButtonsets = jQuery();
        rowcounter = 0;
        _ref = this.options.breakAfter;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          row = _ref[_i];
          rowcounter++;
          buttonRow = "<div          class=\"halloButtonrow halloButtonrow-" + rowcounter + "\" />";
          for (_j = 0, _len1 = buttonsets.length; _j < _len1; _j++) {
            buttonset = buttonsets[_j];
            queuedButtonsets = jQuery(queuedButtonsets).add(jQuery(buttonset));
            if (jQuery(buttonset).hasClass(row)) {
              queuedButtonsets.wrapAll(buttonRow);
              buttonsets = buttonsets.not(queuedButtonsets);
              queuedButtonsets = jQuery();
              break;
            }
          }
        }
        if (buttonsets.length > 0) {
          rowcounter++;
          buttonRow = "<div          class=\"halloButtonrow halloButtonrow-" + rowcounter + "\" />";
          return buttonsets.wrapAll(buttonRow);
        }
      }
    });
  })(jQuery);

}).call(this);
