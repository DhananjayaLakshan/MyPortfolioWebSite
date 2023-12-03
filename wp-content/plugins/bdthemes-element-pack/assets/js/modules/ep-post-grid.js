(function ($, elementor) {
  "use strict";
  $(window).on("elementor/frontend/init", function () {
    var ModuleHandler = elementorModules.frontend.handlers.Base,
      PostGrid;

    PostGrid = ModuleHandler.extend({
      bindEvents: function () {
        this.run();
      },

      getDefaultSettings: function () {
        return {};
      },

      onElementChange: debounce(function (prop) {
        if (prop.indexOf("post_grid") !== -1) {
          this.run();
        }
      }, 400),

      settings: function (key) {
        return this.getElementSettings("post_grid_" + key);
      },

      run: function () {
        var options = this.getDefaultSettings();
        var content = this.settings("ajax_loadmore");

        // console.log(content);
        var element = this.findElement(".elementor-widget-container").get(0);
        if (jQuery(this.$element).hasClass("elementor-section")) {
          element = this.$element.get(0);
        }
        var $container = this.$element.find(".bdt-post-grid");
        if (!$container.length) {
          return;
        }
        if (content === undefined) {
          return;
        }
        var settingsLoadmore = this.settings("show_loadmore");
        var settingsInfiniteScroll = this.settings("show_infinite_scroll");

        var loadButtonContainer = this.$element.find(".bdt-loadmore-container");
        var grid = $container.find(".bdt-grid");
        var loadButton = loadButtonContainer.find(".bdt-loadmore");
        var loading = false;
        var settings = $container.data("settings");
        var readMore = $container.data("settings-button");
        // var page = 1;
        var currentItemCount = settings.posts_per_page;

        var loadMorePosts = function () {
          var dataSettings = {
            action: "ep_loadmore_posts",
            settings: settings,
            readMore: readMore,
            per_page: settings.ajax_item_load,
            offset: currentItemCount,
          };
          jQuery.ajax({
            url: window.ElementPackConfig.ajaxurl,
            type: "post",
            data: dataSettings,
            // beforeSend: function () {
            // 	$('.bdt-loader').remove();
            //   $($container).append('<div class="bdt-loader" bdt-spinner></div>');
            // },
            success: function (response) {
              $(grid).append(response.markup);
              currentItemCount += settings.ajax_item_load;
              loading = false;
              if (settingsLoadmore === "yes") {
                loadButton.html("Load More");
              }

              if ($(response.markup).length < settings.ajax_item_load) {
                loadButton.hide();
                loadButtonContainer.hide();
              }
            },
            // complete: function () {
            //   $('.bdt-loader').remove();
            //   console.log("complete");
            // },
          });
        };

        if (settingsLoadmore === "yes") {
          $(loadButton).on("click", function () {
            if (!loading) {
              loading = true;
              loadButton.html("loading...");
              loadMorePosts();
            }
          });
        }

        if (settingsInfiniteScroll === "yes") {
          $(window).scroll(function () {
            if (
              $(window).scrollTop() ==
              $(document).height() - $(window).height()
            ) {
              $(".bdt-loadmore").css("display", "block");
              loadMorePosts();
            } else {
              return;
            }
          });
        }
      },
    });

    elementorFrontend.hooks.addAction(
      "frontend/element_ready/bdt-post-grid.default",
      function ($scope) {
        elementorFrontend.elementsHandler.addHandler(PostGrid, {
          $element: $scope,
        });
      }
    );
  });
})(jQuery, window.elementorFrontend);
