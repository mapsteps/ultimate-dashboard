/* Icon picker */

(function ($) {
  /**
   *
   * @returns {void}
   */
  $.fn.iconPicker = function () {
    /**
     * Icons
     *
     * @type Array
     */
    var icons = iconPickerIcons ? iconPickerIcons : [];

    return this.each(function () {
      var eventType =
        this.tagName.toLowerCase() === "button" ? "click" : "focus";

      $(this).on(eventType, function (e) {
        createPopup(this);
      });

      function createPopup(field) {
        var boxWidth = field.dataset.width;

        var popup = $(
            '<div class="icon-picker-container"> \
						<div class="icon-picker-control" /> \
						<ul class="icon-picker-list" /> \
					</div>'
          ),
          list = popup.find(".icon-picker-list");

        // if (boxWidth) popup.width(boxWidth);

        for (var i in icons) {
          list.append(
            '<li data-icon="' +
              icons[i] +
              '"><a href="#" title="' +
              icons[i] +
              '"><span class="' +
              icons[i] +
              '"></span></a></li>'
          );
        }

        $("a", list).click(function (e) {
          e.preventDefault();
          var title = $(this).attr("title");
          field.dataset.icon = title;

          if (field.tagName.toLowerCase() === "input") {
            var prevVal = field.value;
            field.value = "" + title;
            if (field.value !== prevVal)
              field.dispatchEvent(new Event("change"));
          }

          $(document).trigger("iconPicker:selected", [field, title]);

          field.blur();
          removePopup();
        });

        var control = popup.find(".icon-picker-control");

        control.prepend(
          '<a data-direction="back" href="#"> \
					<span class="dashicons dashicons-arrow-left-alt2"></span></a> \
					<span class="icon-picker--search-wrapper"><input type="text" class="" placeholder="Search" /></span> \
					<a data-direction="forward" href="#"><span class="dashicons dashicons-arrow-right-alt2"></span></a>'
        );

        $("a", control).click(function (e) {
          e.preventDefault();
          if ($(this).data("direction") === "back") {
            $("li:gt(" + (icons.length - 26) + ")", list).prependTo(list);
          } else {
            $("li:lt(25)", list).appendTo(list);
          }
        });

        popup.appendTo(field.parentNode).show();

        $("input", control).on("keyup", function (e) {
          var search = $(this).val();
          if (search === "") {
            $("li:lt(25)", list).show();
          } else {
            $("li", list).each(function () {
              if (
                $(this)
                  .data("icon")
                  .toLowerCase()
                  .indexOf(search.toLowerCase()) !== -1
              ) {
                $(this).show();
              } else {
                $(this).hide();
              }
            });
          }
        });

        $(document).bind("mouseup.icon-picker", function (e) {
          if (
            e.target !== field &&
            !popup.is(e.target) &&
            popup.has(e.target).length === 0
          ) {
            removePopup();
          }
        });
      }

      function removePopup() {
        $(".icon-picker-container").remove();
        $(document).unbind(".icon-picker");
      }
    });
  };

  $(function () {
    $(".icon-picker").iconPicker();
  });
})(jQuery);
