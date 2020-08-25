jQuery(document).ready(function() {
    // ReadMore.init();
    Savvy.init();

    var menu_tree = jQuery('#menu_tree');
    if (typeof menu_tree == 'object')
        menu_tree.treed({openedClass:'glyphicon-chevron-down', closedClass:'glyphicon-chevron-right'});

});



Savvy = {
    init : function() {
        Savvy.initToTop();
        Savvy.initReadMore();
        Savvy.menuFix();
        Savvy.headerTop();

        jQuery(window).on('resize orientationchange', function() {
        	jQuery(".section.banner.main").css('height', '100%');
        });
        jQuery(window).on('load', function(){
            var  banner = jQuery(".section.banner.main");
            banner.css('height', String(0.5 * banner.width()) + 'px');
        });

        // search
        jQuery("#bs-navbar-collapse .search-top i").on('click', function() {
            /*
            jQuery("#bs-navbar-collapse .search-top .search-box").toggle('slide', {
                direction: 'right'
            }, 250, function() {
                jQuery('input', this).focus();
                
                jQuery('input', this).focusout(function() {
                	jQuery("#bs-navbar-collapse .search-top .search-box").hide();
                });
            });
            */
            
        	jQuery("#bs-navbar-collapse .search-top .search-box").toggle('slide', function() {
        		jQuery('input', this).focus();
        		jQuery('input', this).focusout(function() {
        			jQuery("#bs-navbar-collapse .search-top .search-box").hide();
                });
            });
            
        })
    },

    headerTop : function() {
    	jQuery(window).on('scroll', function() {
            // var scrollTop = jQuery(this).scrollTop();
            // jQuery('header.header').each(function() {
            //    var topDistance = jQuery(this).offset().top;
            // });
            var header = jQuery('header.header');
            if (typeof header == 'object') {
                if (header.offset().top < 1) {
                    header.removeClass('opaque');
                }
                else {
                    header.addClass('opaque')
                }

            }
        });
    },

    menuFix : function() {
        var nav = jQuery('.menu-header-menu.nav.navbar-nav');
        var subs = jQuery("ul.sub-menu", nav);
        nav.removeClass('hidden');
        jQuery(subs).each(function(a, el) {
            var li = jQuery(el.parentElement);
            //li.first('a').addClass('dropdown-toggle').attr('data-toggle', 'dropdown').attr('role', 'button');
            jQuery(el).addClass('dropdown-menu');
            li.addClass('dropdown');

            if (li.has('ul li').length > 0 && li.parents('ul').length > 1) {
                li.children('a').append('<i class="fa fa-ellipsis-h"></i>');
            }
        });
    },

    initReadMore : function() {
    	jQuery('.entry-content.readmore-yes').readmore({
            collapsedHeight: 500,
            moreLink: '<div style="display:block"><i class="fa fa-ellipsis-h"></i></div><a href="#" class="read-more-btn btn">Read more &nbsp<i class="fa fa-chevron-down"></i></a>',
            lessLink: '<a href="#" class="read-more-btn btn">Collapse &nbsp<i class="fa fa-chevron-up"></i></a>',
            blockCSS: 'display: inline-block; width: auto;',
            beforeToggle: function() {
            	jQuery(".fa.fa-ellipsis-h").parent().remove();
            }
        });
    },

    initToTop : function() {
        var offset = 300,
            //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
            offset_opacity = 1200,
            //duration of the top scrolling animation (in ms)
            scroll_top_duration = 700,
            //grab the "back to top" link
            back_to_top = jQuery('.cd-top');

        if (typeof back_to_top != 'object')
            return;

        //hide or show the "back to top" link
        jQuery(window).scroll(function(){
            ( jQuery(this).scrollTop() > offset ) ? back_to_top.addClass('cd-is-visible') : back_to_top.removeClass('cd-is-visible cd-fade-out');
            if( jQuery(this).scrollTop() > offset_opacity ) {
                back_to_top.addClass('cd-fade-out');
            }
        });

        //smooth scroll to top
        back_to_top.on('click', function(event){
            event.preventDefault();
            jQuery('body,html').animate({
                    scrollTop: 0 ,
                }, scroll_top_duration
            );
        });
    }
}

/**
 * menu tree used in the footer
 */
jQuery.fn.extend({
    treed: function (o) {

        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';

        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
                closedClass = o.closedClass;
            }
        };

        //initialize each of the top levels
        var tree = jQuery(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = jQuery(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = jQuery(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    jQuery(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
        	jQuery(this).on('click', function () {
            	jQuery(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
        	jQuery(this).on('click', function (e) {
        		jQuery(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
        	jQuery(this).on('click', function (e) {
        		jQuery(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});
