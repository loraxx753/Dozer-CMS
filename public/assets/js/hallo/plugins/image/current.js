// Generated by CoffeeScript 1.6.2
(function() {
  (function(jQuery) {
    return jQuery.widget('IKS.halloimagecurrent', {
      options: {
        imageWidget: null,
        startPlace: '',
        draggables: [],
        maxWidth: 400,
        maxHeight: 200
      },
      _create: function() {
        this.element.html('<div>\
        <div class="activeImageContainer">\
          <div class="rotationWrapper">\
            <div class="hintArrow"></div>\
              <img src="" class="activeImage" />\
            </div>\
            <img src="" class="activeImage activeImageBg" />\
          </div>\
        </div>');
        this.element.hide();
        return this._prepareDnD();
      },
      _init: function() {
        var editable, widget;

        editable = jQuery(this.options.editable.element);
        widget = this;
        jQuery('img', editable).each(function(index, elem) {
          return widget._initDraggable(elem, editable);
        });
        return jQuery('p', editable).each(function(index, elem) {
          if (jQuery(elem).data('jquery_droppable_initialized')) {
            return;
          }
          jQuery(elem).droppable({
            tolerance: 'pointer',
            drop: function(event, ui) {
              return widget._handleDropEvent(event, ui);
            },
            over: function(event, ui) {
              return widget._handleOverEvent(event, ui);
            },
            out: function(event, ui) {
              return widget._handleLeaveEvent(event, ui);
            }
          });
          return jQuery(elem).data('jquery_droppable_initialized', true);
        });
      },
      _prepareDnD: function() {
        var editable, overlayMiddleConfig, widget;

        widget = this;
        editable = jQuery(this.options.editable.element);
        this.options.offset = editable.offset();
        this.options.third = parseFloat(editable.width() / 3);
        overlayMiddleConfig = {
          width: this.options.third,
          height: editable.height()
        };
        this.overlay = {
          big: jQuery("<div/>").addClass("bigOverlay").css({
            width: this.options.third * 2,
            height: editable.height()
          }),
          left: jQuery("<div/>").addClass("smallOverlay smallOverlayLeft"),
          right: jQuery("<div/>").addClass("smallOverlay smallOverlayRight")
        };
        this.overlay.left.css(overlayMiddleConfig);
        this.overlay.right.css(overlayMiddleConfig).css("left", this.options.third * 2);
        editable.on('halloactivated', function() {
          return widget._enableDragging();
        });
        return editable.on('hallodeactivated', function() {
          return widget._disableDragging();
        });
      },
      setImage: function(image) {
        if (!image) {
          return;
        }
        this.element.show();
        jQuery('.activeImage', this.element).attr('src', image.url);
        if (image.label) {
          jQuery('input', this.element).val(image.label);
        }
        return this._initImage(jQuery(this.options.editable.element));
      },
      _delayAction: function(functionToCall, delay) {
        var timer;

        timer = clearTimeout(timer);
        if (!timer) {
          return timer = setTimeout(functionToCall, delay);
        }
      },
      _calcDropPosition: function(offset, event) {
        var position, rightTreshold;

        position = offset.left + this.options.third;
        rightTreshold = offset.left + this.options.third * 2;
        if (event.pageX >= position && event.pageX <= rightTreshold) {
          return 'middle';
        } else if (event.pageX < position) {
          return 'left';
        } else if (event.pageX > rightTreshold) {
          return 'right';
        }
      },
      _createInsertElement: function(image, tmp) {
        var imageInsert, tmpImg;

        imageInsert = jQuery('<img>');
        tmpImg = new Image();
        jQuery(tmpImg).on('load', function() {});
        tmpImg.src = image.src;
        imageInsert.attr({
          src: tmpImg.src,
          alt: !tmp ? jQuery(image).attr('alt') : void 0,
          "class": tmp ? 'halloTmp' : 'imageInText'
        });
        imageInsert.show();
        return imageInsert;
      },
      _createLineFeedbackElement: function() {
        return jQuery('<div/>').addClass('halloTmpLine');
      },
      _removeFeedbackElements: function() {
        this.overlay.big.remove();
        this.overlay.left.remove();
        this.overlay.right.remove();
        return jQuery('.halloTmp, .halloTmpLine', this.options.editable.element).remove();
      },
      _removeCustomHelper: function() {
        return jQuery('.customHelper').remove();
      },
      _showOverlay: function(position) {
        var eHeight, editable;

        editable = jQuery(this.options.editable.element);
        eHeight = editable.height();
        eHeight += parseFloat(editable.css('paddingTop'));
        eHeight += parseFloat(editable.css('paddingBottom'));
        this.overlay.big.css({
          height: eHeight
        });
        this.overlay.left.css({
          height: eHeight
        });
        this.overlay.right.css({
          height: eHeight
        });
        switch (position) {
          case 'left':
            this.overlay.big.addClass("bigOverlayLeft");
            this.overlay.big.removeClass("bigOverlayRight");
            this.overlay.big.css({
              left: this.options.third
            });
            this.overlay.big.show();
            this.overlay.left.hide();
            return this.overlay.right.hide();
          case 'middle':
            this.overlay.big.removeClass("bigOverlayLeft bigOverlayRight");
            this.overlay.big.hide();
            this.overlay.left.show();
            return this.overlay.right.show();
          case 'right':
            this.overlay.big.addClass("bigOverlayRight");
            this.overlay.big.removeClass("bigOverlayLeft");
            this.overlay.big.css({
              left: 0
            });
            this.overlay.big.show();
            this.overlay.left.hide();
            return this.overlay.right.hide();
        }
      },
      _checkOrigin: function(event) {
        if (jQuery(event.target).parents("[contenteditable]").length !== 0) {
          return true;
        }
        return false;
      },
      _createFeedback: function(image, position) {
        var el;

        if (position === 'middle') {
          return this._createLineFeedbackElement();
        }
        el = this._createInsertElement(image, true);
        return el.addClass("inlineImage-" + position);
      },
      _handleOverEvent: function(event, ui) {
        var editable, postPone, widget;

        widget = this;
        editable = jQuery(this.options.editable);
        postPone = function() {
          var position, target;

          window.waitWithTrash = clearTimeout(window.waitWithTrash);
          position = widget._calcDropPosition(widget.options.offset, event);
          jQuery('.trashcan', ui.helper).remove();
          editable[0].element.append(widget.overlay.big);
          editable[0].element.append(widget.overlay.left);
          editable[0].element.append(widget.overlay.right);
          widget._removeFeedbackElements();
          target = jQuery(event.target);
          target.prepend(widget._createFeedback(ui.draggable[0], position));
          if (position === 'middle') {
            target.prepend(widget._createFeedback(ui.draggable[0], 'right'));
            jQuery('.halloTmp', event.target).hide();
          } else {
            target.prepend(widget._createFeedback(ui.draggable[0], 'middle'));
            jQuery('.halloTmpLine', event.target).hide();
          }
          return widget._showOverlay(position);
        };
        return setTimeout(postPone, 5);
      },
      _handleDragEvent: function(event, ui) {
        var position, tmpFeedbackLR, tmpFeedbackMiddle;

        position = this._calcDropPosition(this.options.offset, event);
        if (position === this._lastPositionDrag) {
          return;
        }
        this._lastPositionDrag = position;
        tmpFeedbackLR = jQuery('.halloTmp', this.options.editable.element);
        tmpFeedbackMiddle = jQuery('.halloTmpLine', this.options.editable.element);
        if (position === 'middle') {
          tmpFeedbackMiddle.show();
          tmpFeedbackLR.hide();
        } else {
          tmpFeedbackMiddle.hide();
          tmpFeedbackLR.removeClass('inlineImage-left inlineImage-right');
          tmpFeedbackLR.addClass("inlineImage-" + position);
          tmpFeedbackLR.show();
        }
        return this._showOverlay(position);
      },
      _handleLeaveEvent: function(event, ui) {
        var func;

        func = function() {
          if (!jQuery('div.trashcan', ui.helper).length) {
            jQuery(ui.helper).append(jQuery('<div class="trashcan"></div>'));
            return jQuery('.bigOverlay, .smallOverlay').remove();
          }
        };
        window.waitWithTrash = setTimeout(func, 200);
        return this._removeFeedbackElements();
      },
      _handleStartEvent: function(event, ui) {
        var internalDrop;

        internalDrop = this._checkOrigin(event);
        if (internalDrop) {
          jQuery(event.target).remove();
        }
        jQuery(document).trigger('startPreventSave');
        return this.options.startPlace = jQuery(event.target);
      },
      _handleStopEvent: function(event, ui) {
        var internalDrop;

        internalDrop = this._checkOrigin(event);
        if (internalDrop) {
          jQuery(event.target).remove();
        } else {
          jQuery(this.options.editable.element).trigger('change');
        }
        this.overlay.big.hide();
        this.overlay.left.hide();
        this.overlay.right.hide();
        return jQuery(document).trigger('stopPreventSave');
      },
      _handleDropEvent: function(event, ui) {
        var classes, editable, imageInsert, internalDrop, left, position;

        editable = jQuery(this.options.editable.element);
        internalDrop = this._checkOrigin(event);
        position = this._calcDropPosition(this.options.offset, event);
        this._removeFeedbackElements();
        this._removeCustomHelper();
        imageInsert = this._createInsertElement(ui.draggable[0], false);
        classes = 'inlineImage-middle inlineImage-left inlineImage-right';
        if (position === 'middle') {
          imageInsert.show();
          imageInsert.removeClass(classes);
          left = editable.width();
          left += parseFloat(editable.css('paddingLeft'));
          left += parseFloat(editable.css('paddingRight'));
          left -= imageInsert.attr('width');
          imageInsert.addClass("inlineImage-" + position).css({
            position: 'relative',
            left: left / 2
          });
          imageInsert.insertBefore(jQuery(event.target));
        } else {
          imageInsert.removeClass(classes);
          imageInsert.addClass("inlineImage-" + position);
          imageInsert.css('display', 'block');
          jQuery(event.target).prepend(imageInsert);
        }
        this.overlay.big.hide();
        this.overlay.left.hide();
        this.overlay.right.hide();
        editable.trigger('change');
        return this._initImage(editable);
      },
      _createHelper: function(event) {
        return jQuery('<div>').css({
          backgroundImage: "url(" + (jQuery(event.currentTarget).attr('src')) + ")"
        }).addClass('customHelper').appendTo('body');
      },
      _initDraggable: function(elem, editable) {
        var widget;

        widget = this;
        if (!elem.jquery_draggable_initialized) {
          elem.jquery_draggable_initialized = true;
          jQuery(elem).draggable({
            cursor: 'move',
            helper: function(event) {
              return widget._createHelper(event);
            },
            drag: function(event, ui) {
              return widget._handleDragEvent(event, ui);
            },
            start: function(event, ui) {
              return widget._handleStartEvent(event, ui);
            },
            stop: function(event, ui) {
              return widget._handleStopEvent(event, ui);
            },
            disabled: !editable.hasClass('inEditMode'),
            cursorAt: {
              top: 50,
              left: 50
            }
          });
        }
        return widget.options.draggables.push(elem);
      },
      _initImage: function(editable) {
        var widget;

        widget = this;
        return jQuery('.rotationWrapper img', this.options.dialog).each(function(index, elem) {
          return widget._initDraggable(elem, editable);
        });
      },
      _enableDragging: function() {
        return jQuery.each(this.options.draggables, function(index, d) {
          return jQuery(d).draggable('option', 'disabled', false);
        });
      },
      _disableDragging: function() {
        return jQuery.each(this.options.draggables, function(index, d) {
          return jQuery(d).draggable('option', 'disabled', true);
        });
      }
    });
  })(jQuery);

}).call(this);
