/*!
 impleCode Product Scripts v1.0.0
 Manages product related scripts
 (c) 2021 impleCode - https://implecode.com

 */
ic_define_hooks();

jQuery(document).ready(function ($) {
    ic_define_hooks();
    reponsive_product_catalog();
    initialize_ic_tabs();
    setTimeout(modern_grid_font_size, 0);
    jQuery.ic.addAction('ic_change_responsive', function () {
        redefine_ic_tabs();
    });
    $(window).on('resize', function () {
        if ($(document.activeElement).attr('type') === undefined) {
            reponsive_product_catalog();
            //redefine_ic_tabs();
            setTimeout(modern_grid_font_size, 0);
            ic_apply_magnifier();
        }
    });

    if (typeof colorbox === 'object' && $('.a-product-image').length) {
        $('.a-product-image').colorbox(product_object.lightbox_settings);
    }

    ic_apply_magnifier();

    jQuery('body').on('change', '.ic_self_submit', function () {
        var self_submit_form = jQuery(this).closest('form');
        if (self_submit_form.find('[type="submit"]').length === 0 || self_submit_form.find('[type="submit"]').not(":visible").length > 0) {
            self_submit_form.submit();
        }
    });

    jQuery('.dismiss-empty-bar').on('click', function (e) {
        e.preventDefault();
        var data = {
            'action': 'hide_empty_bar_message'
        };
        jQuery.post(product_object.ajaxurl, data, function () {
            jQuery('div.product-sort-bar').hide('slow');
        });
    });

    jQuery('.al-box').on('click', '.notice-dismiss', function () {
        var container = jQuery(this).closest('.al-box');
        container.css('opacity', '0.5');
        var hash = container.data('hash');
        if (hash !== undefined) {
            var data = {
                'action': 'ic_ajax_hide_message',
                'hash': hash
            };
            jQuery.post(product_object.ajaxurl, data, function () {
                container.hide('slow');
            });
        }
    });

    ic_responsive_filters_bar();
    $('body').on('reload', '.product-sort-bar', function () {
        // $( ".product-size-filter-container.toReload" ).trigger( "reload" );
    });

    $.ic.addAction('ic_change_responsive', function (open) {
        ic_responsive_filters_bar(open);
    });
    $.ic.addAction('ic_self_submit', function () {
        reponsive_product_catalog();
        //ic_responsive_filters_bar();
        $('.product-size-filter-container.toReload').trigger('reload');

    });
    $.ic.addAction('ic_self_submit_before', function () {
        jQuery('.responsive-filters').hide();
    });
    jQuery('body').on('click', '.responsive-filters-button', function () {
        if (jQuery('.responsive-filters').is(':visible')) {
            jQuery('.responsive-filters').hide();
        } else {
            jQuery('.responsive-filters').show();
            if (jQuery('.responsive-filters-section').length === 1) {
                jQuery('.responsive-filters-section-content').show();
                jQuery('.responsive-filters-section-title').addClass("open");
            }
            jQuery('.responsive-filters .responsive-filters-section-content').find(".filter-active").each(function () {
                jQuery(this).closest('.responsive-filters-section-content').show();
                jQuery(this).closest('.responsive-filters-section-title').addClass("open");
            });
            ic_adjust_responsive_filters_height();
        }
    });

    jQuery("body").on("click", function (e) {
        var container = jQuery(".responsive-filters-button, .responsive-filters");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            jQuery(".responsive-filters").hide();
        }
    });

    jQuery("body").on('click', '.responsive-filters-section-title', function () {
        jQuery(".responsive-filters-section-content").hide();
        if (!jQuery(this).hasClass("open")) {
            jQuery(".responsive-filters-section-title").removeClass("open");
            jQuery(this).addClass("open");
            jQuery(this).next(".responsive-filters-section-content").show();
        } else {
            jQuery(".responsive-filters-section-title").removeClass("open");
        }
        ic_adjust_responsive_filters_height();
    });
    jQuery(".ic-icon-url.ic-show-content").on("click", function (e) {
        e.preventDefault();
        var hidden_content = jQuery(this).closest(".ic-bar-icon").find(".ic-icon-hidden-content");
        jQuery(".ic-icon-url").hide();
        hidden_content.slideToggle(300);

    });
    jQuery(document).on("mouseup", function (e) {
        var container = jQuery(".ic-icon-url.ic-show-content, .ic-icon-hidden-content");
        var close_icon = jQuery(".ic-popup-close");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            ic_close_icons_hidden_content();
        } else if (close_icon.is(e.target)) {
            ic_close_icons_hidden_content();
        }
    });
});

function ic_adjust_responsive_filters_height() {
    var responsive_filters_height = jQuery(".responsive-filters").outerHeight();
    var product_list_height = jQuery(".product-list").outerHeight();
    if (responsive_filters_height > product_list_height) {
        jQuery(".responsive-filters .responsive-filters-section-content").css("max-height", product_list_height + "px");
        jQuery(".responsive-filters .responsive-filters-section-content").css("overflow", "auto");
    }
}

function ic_close_icons_hidden_content() {
    var hidden_content = jQuery(".ic-catalog-bar");
    hidden_content.each(function () {
        var bar = jQuery(this).find(".ic-bar-icon");
        var barHidden = bar.find(".ic-icon-hidden-content");
        if (barHidden.length > 0) {
            barHidden.slideUp(300, function () {
                bar.find(".ic-icon-url").show();
            });
        } else {
            bar.find(".ic-icon-url").show();
        }
    });
}

function ic_apply_magnifier() {
    if (typeof jQuery(".ic_magnifier").elevateZoom !== "function") {
        return;
    }
    if (jQuery(".ic_magnifier").length && !jQuery(".responsive .ic_magnifier").length && !jQuery(".ic_magnifier.magnigier_on").length) {
        var zoom_pos = 11;
        if (jQuery(".boxed .ic_magnifier").length) {
            zoom_pos = 1;
        }
        jQuery(".ic_magnifier").elevateZoom({cursor: 'pointer', responsive: true, zoomWindowPosition: zoom_pos});
        jQuery(".ic_magnifier").addClass('magnigier_on');
    } else if (jQuery(".ic_magnifier.magnigier_on").length && jQuery(".responsive .ic_magnifier").length) {
        var zoom = jQuery('.ic_magnifier.magnigier_on').data('elevateZoom');
        if (!zoom !== undefined) {
            zoom.changeState('disable');
        }
    } else if (jQuery(".ic_magnifier.magnigier_on").length && !jQuery(".responsive .ic_magnifier").length) {
        var zoom = jQuery('.ic_magnifier.magnigier_on').data('elevateZoom');
        if (!zoom !== undefined) {
            zoom.changeState('enable');
        }
    }
}

function ic_define_hooks() {
    if (jQuery.ic === undefined) {
        jQuery.ic = {
            /**
             * Implement a WordPress-link Hook System for Javascript
             * TODO: Change 'tag' to 'args', allow number (priority), string (tag), object (priority+tag)
             */
            hooks: {action: {}, filter: {}},
            addAction: function (action, callable, tag) {
                jQuery.ic.addHook('action', action, callable, tag);
            },
            addFilter: function (action, callable, tag) {
                jQuery.ic.addHook('filter', action, callable, tag);
            },
            doAction: function (action, args) {
                jQuery.ic.doHook('action', action, null, args);
            },
            applyFilters: function (action, value, args) {
                return jQuery.ic.doHook('filter', action, value, args);
            },
            removeAction: function (action, tag) {
                jQuery.ic.removeHook('action', action, tag);
            },
            removeFilter: function (action, tag) {
                jQuery.ic.removeHook('filter', action, tag);
            },
            addHook: function (hookType, action, callable, tag) {
                if (undefined == jQuery.ic.hooks[hookType][action]) {
                    jQuery.ic.hooks[hookType][action] = [];
                }
                var hooks = jQuery.ic.hooks[hookType][action];
                if (undefined == tag) {
                    tag = action + '_' + hooks.length;
                }
                jQuery.ic.hooks[hookType][action].push({tag: tag, callable: callable});
            },
            doHook: function (hookType, action, value, args) {
                if (undefined != jQuery.ic.hooks[hookType][action]) {
                    var hooks = jQuery.ic.hooks[hookType][action];
                    for (var i = 0; i < hooks.length; i++) {
                        if ('action' == hookType) {
                            hooks[i].callable(args);
                        } else {
                            value = hooks[i].callable(value, args);
                        }
                    }
                }
                if ('filter' == hookType) {
                    return value;
                }
            },
            removeHook: function (hookType, action, tag) {
                if (undefined != jQuery.ic.hooks[hookType][action]) {
                    var hooks = jQuery.ic.hooks[hookType][action];
                    for (var i = hooks.length - 1; i >= 0; i--) {
                        if (undefined == tag || tag == hooks[i].tag)
                            hooks.splice(i, 1);
                    }
                }
            }
        };
    }
}

function ic_switch_popstate_tabs(state) {
    var hash = 'product_description';
    if (window.location.hash !== "") {
        hash = window.location.hash;
        hash = hash.replace("_tab", "").replace("#", "");
        var current_tab = jQuery(".boxed .after-product-details h3[data-tab_id=" + hash + "]");
        ic_enter_tab(hash, current_tab);
        //current_tab.trigger( "click" );
    } else {
        var current_tab = jQuery(".boxed .after-product-details h3:first-of-type");
        if (!current_tab.hasClass('active')) {
            set_default_ic_tab();
            history.replaceState("", document.title, window.location.pathname + window.location.search);
        }
        //location.reload();
    }
}

function initialize_ic_tabs() {
    if (jQuery(".boxed").length) {
        jQuery(window).on('popstate', ic_switch_popstate_tabs);
        if (jQuery(".boxed").hasClass("responsive")) {
            ic_accordion();
        } else if (jQuery(".boxed").length) {
            ic_tabs();
        }
        jQuery(document).trigger("ic_tabs_initialized");
    }
}

function redefine_ic_tabs() {
    if (jQuery(".boxed .after-product-details").hasClass("ic_accordion_container")) {
        jQuery(".boxed .after-product-details > div").each(function () {
            var accordion_container = jQuery(this).find(".ic_accordion_content_container");
            //var content_html = accordion_container.html();
            //accordion_container.remove();
            accordion_container.show();
            accordion_container.removeClass("ic_accordion_content_container");
            //jQuery( this ).append( content_html );
        });
        jQuery(".after-product-details").removeClass("ic_accordion_container");
    } else if (jQuery(".boxed .after-product-details").hasClass("ic_tabs_container")) {
        jQuery(".boxed .after-product-details .ic_tabs h3").each(function () {
            var a = jQuery(this).find("a");
            jQuery(this).prepend(a.text());
            a.remove();
            jQuery(this).addClass("catalog-header");
            var tab_id = jQuery(this).data("tab_id");
            jQuery(".boxed .after-product-details #" + tab_id).prepend(jQuery(this));
        });
        jQuery(".boxed .after-product-details > div").removeClass("ic_tab_content").css("display", "");
        jQuery(".boxed .after-product-details .ic_tabs").remove();
        jQuery(".after-product-details").removeClass("ic_tabs_container");
    }
    initialize_ic_tabs();
}

function ic_accordion() {
    jQuery(".boxed .after-product-details > div").each(function () {
        jQuery(this).children().wrapAll("<div class='ic_accordion_content_container' />");
        jQuery(this).find(".catalog-header").prependTo(jQuery(this));
    });
    ic_accordion_initial_hide();
    if (window.location.hash !== "") {
        var hash = window.location.hash.replace("_tab", "").replace("#", "");

        var current_tab = jQuery(".boxed .after-product-details > #" + hash + " > .catalog-header");
        if (current_tab.length) {
            current_tab.addClass('open');
            jQuery(".boxed .after-product-details > #" + hash + " .ic_accordion_content_container").show();
        } else {
            ic_open_default_accordion();
        }
    } else {
        ic_open_default_accordion();
    }
    jQuery(".boxed.responsive .after-product-details .catalog-header").unbind("click");
    jQuery(".boxed.responsive .after-product-details .catalog-header").click(function () {
        //var current_offset = jQuery( this ).position().top;
        //ic_accordion_initial_hide();

        if (jQuery(this).hasClass("open")) {
            history.pushState({}, document.title, window.location.pathname);
            jQuery(this).removeClass("open");
            jQuery(this).next(".ic_accordion_content_container").hide();
        } else {
            var hidden_height = 0;
            var max_top = 0;
            jQuery(".boxed.responsive .after-product-details .catalog-header.open").each(function () {
                var to_hide = jQuery(this).next(".ic_accordion_content_container");
                hidden_height = hidden_height + jQuery(this).next(".ic_accordion_content_container").height();
                var current_top = jQuery(this)[0].getBoundingClientRect().top;
                if (current_top > max_top) {
                    max_top = current_top;
                }
                to_hide.hide();
                jQuery(this).removeClass("open");
            });
            //var current_offset = jQuery( this ).offset().top;
            var current_scroll = jQuery(window).scrollTop();
            var current_offset = jQuery(this)[0].getBoundingClientRect().top;
            var clicked_tab_id = jQuery(this).parent("div").attr("id");
            window.location.hash = clicked_tab_id + "_tab";
            //jQuery( ".boxed .after-product-details > div .catalog-header" ).removeClass( "open" );
            jQuery(this).parent("div").children().show();
            jQuery(this).addClass("open");
        }
        if ((current_offset < hidden_height && max_top < current_offset) || !is_element_visible(jQuery(this))) {
            var page = jQuery("html");
            /*
             page.on( "scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove", function () {
             page.stop();
             } );
             */
            page.animate({
                scrollTop: current_scroll - hidden_height
            }, 0, function () {
                //page.off( "scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove" );
            });
        }
    });
    jQuery(".boxed .after-product-details").addClass("ic_accordion_container");
}

function ic_open_default_accordion() {
    jQuery(".boxed .after-product-details > div:first-child .ic_accordion_content_container").show();
    jQuery(".boxed .after-product-details > div:first-child .catalog-header").addClass("open");
}

function ic_accordion_initial_hide() {
    jQuery(".boxed.responsive .after-product-details > div").each(function () {
        jQuery(this).find(".ic_accordion_content_container").hide();
        jQuery(this).find(".catalog-header").show();
    });
}

function ic_tabs() {
    if (!jQuery(".boxed .after-product-details").hasClass("ic_tabs_container")) {
        var tabs = "<div class='ic_tabs'>";
        jQuery(".boxed .after-product-details > div").each(function () {
            var ic_tab_content = jQuery(this);
            var ic_tab_id = ic_tab_content.attr("id");
            ic_tab_content.addClass("ic_tab_content");
            var h = ic_tab_content.find("> h3.catalog-header");
            if (h.length) {
                tabs = tabs + "<h3 data-tab_id='" + ic_tab_id + "'><a href='#" + ic_tab_id + "_tab'>" + h.html() + "</a></h3>";
                h.remove();
            }
        });
        tabs = tabs + "</div>";
        jQuery(".boxed .after-product-details").prepend(tabs);
        if (window.location.hash !== "") {
            var hash = window.location.hash.replace("_tab", "").replace("#", "");
            var current_tab = jQuery(".boxed .after-product-details .ic_tabs > h3[data-tab_id='" + hash + "']");
            if (current_tab.length) {
                ic_enter_tab(hash, current_tab);
            } else {
                set_default_ic_tab();
            }
        } else {
            set_default_ic_tab();
        }
        jQuery(".boxed .after-product-details .ic_tabs > h3").unbind("click");
        jQuery(".boxed .after-product-details .ic_tabs > h3").click(function (e) {
            e.preventDefault();
            var ic_tab_id = jQuery(this).data("tab_id");
            ic_enter_tab(ic_tab_id, jQuery(this));
        });
        jQuery(".boxed .after-product-details").addClass("ic_tabs_container");
    }
}

function ic_enter_tab(ic_tab_id, object) {
    if (object.length && !object.hasClass("active")) {
        var hash = ic_tab_id + "_tab";
        if (window.location.hash !== '#' + hash) {
            window.location.hash = hash;
        }
        jQuery(".boxed .after-product-details .ic_tab_content.active").hide();
        jQuery("#" + ic_tab_id).show();
        jQuery(".boxed .after-product-details *").removeClass("active");
        jQuery("#" + ic_tab_id).addClass("active");
        object.addClass("active");
    }
}

function set_default_ic_tab() {
    jQuery(".boxed .after-product-details .ic_tabs > h3").removeClass("active");
    jQuery(".boxed .after-product-details > .ic_tab_content").removeClass("active").hide();
    jQuery(".boxed .after-product-details .ic_tabs > h3:first-child").addClass("active");
    jQuery(".boxed .after-product-details > .ic_tab_content:first").addClass("active").show();
}

function is_element_visible(element) {
    if (element.length === 0) {
        return false;
    }
    var top_of_element = element.offset().top;
    var top_of_screen = jQuery(window).scrollTop();
    if (top_of_screen < top_of_element) {
        return true;
    } else {
        return false;
    }
}

function reponsive_product_catalog() {
    // var list_width = jQuery( ".product-list" ).width();
    // var product_page_width = jQuery( "article.al_product" ).width();
    //if ( list_width < 600 ) {
    //    jQuery( ".product-list" ).addClass( "responsive" );
    // }
    // else {
    //     jQuery( ".product-list" ).removeClass( "responsive" );
    // }
    var body_width = jQuery("body").width();

    if (body_width < 1000 /* && !jQuery( ".al_product,.product-list" ).hasClass( "responsive" ) */) {
        jQuery(".al_product, .product-list").addClass("responsive");
        jQuery.ic.doAction("ic_change_responsive", "0");
    } else if (body_width >= 1000 /* && jQuery( ".al_product,.product-list" ).hasClass( "responsive" ) */) {
        jQuery(".al_product, .product-list").removeClass("responsive");
        jQuery.ic.doAction("ic_change_responsive", "1");
    }
}

function modern_grid_font_size() {
    var max_width = 0;
    jQuery(".modern-grid-element").each(function () {
            var this_width = jQuery(this).width();
            if (this_width > max_width) {
                max_width = this_width;
            }
        }
    );
    var fontSize = max_width * 0.08;
    if (fontSize < 16 && fontSize !== 0) {
        console.log(fontSize);
        jQuery(".modern-grid-element h3").css('font-size', fontSize);
        jQuery(".modern-grid-element .product-price").css('font-size', fontSize);
        fontSize = fontSize * 0.8;
        jQuery(".modern-grid-element .product-attributes table").css('font-size', fontSize);
    } else {
        jQuery(".modern-grid-element h3").css('font-size', '');
        jQuery(".modern-grid-element .product-price").css('font-size', '');
        jQuery(".modern-grid-element .product-attributes table").css('font-size', '');
    }
}

function ic_defaultFor(arg, val) {
    return typeof arg !== 'undefined' ? arg : val;
}

function ic_responsive_filters_bar(open) {
    open = typeof open !== 'undefined' ? open : "1";
    if (open == '0' || jQuery(".product-list").hasClass('responsive') || jQuery(".product-list").hasClass('grouped-filters')) {
        if (jQuery(".responsive-filters").length === 0) {
            var responsive_filters = jQuery(".responsive-filters").html();
            if (responsive_filters === undefined && jQuery("#product_filters_bar .filter-widget").length > 0) {
                if (jQuery('#product_filters_bar .filter-widget:not(.widget_product_categories):not(.product_search)').length > 0) {
                    responsive_filters = '<div class="responsive-filters" style="display: none">';
                    jQuery('#product_filters_bar .filter-widget').each(function () {
                        if (!jQuery(this).hasClass("product_search") && !jQuery(this).hasClass("widget_product_categories")) {
                            var label = jQuery(this).find("[data-ic_responsive_label]").data('ic_responsive_label');
                            if (!label) {
                                label = jQuery(this).find(".filter-label").text();
                            }
                            if (!label) {
                                label = jQuery(this).find("select option:first-child").text();
                            }
                            if (label) {
                                responsive_filters += '<div class="responsive-filters-section">';
                                responsive_filters += '<div class="responsive-filters-section-title">' + label + '</div>';
                                responsive_filters += '<div class="responsive-filters-section-content">' + jQuery(this).html() + '</div>';
                                responsive_filters += '</div>';
                                jQuery(this).hide();
                            } else {
                                jQuery(this).show();
                            }
                        } else {
                            jQuery(this).show();
                        }
                    });
                    responsive_filters += '</div>';
                    var responsive_filters_button = '<div class="responsive-filters-button button ' + product_object.design_schemes + '">' + product_object.filter_button_label + '</div>';
                    jQuery("#product_filters_bar .clear-both").before(responsive_filters_button);
                    jQuery("#product_filters_bar .clear-both").before(responsive_filters);
                }
            }
        }
    } else {
        jQuery(".responsive-filters").hide();
        //jQuery( ".responsive-filters-button" ).hide();
        jQuery("#product_filters_bar .filter-widget").show();
    }
}

function ic_disable_body() {
    jQuery('body').addClass('ic-disabled-body');
}

function ic_enable_body() {
    jQuery('body').removeClass('ic-disabled-body');
}

function ic_disable_container(container) {
    container.addClass('ic-disabled-container');
}