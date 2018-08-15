$(document).ready(function() {
    // ReadMore.init();
    Savvy.init();

    var menu_tree = $('#menu_tree');
    if (typeof menu_tree == 'object')
        menu_tree.treed({openedClass:'glyphicon-chevron-down', closedClass:'glyphicon-chevron-right'});

});



Savvy = {
    init : function() {
        Savvy.initToTop();
        Savvy.initReadMore();
        Savvy.menuFix();
        Savvy.headerTop();

        $(window).on('resize orientationchange', function() {
            $(".section.banner.main").css('height', '100%');
        });
        $(window).on('load', function(){
            var  banner = $(".section.banner.main");
            banner.css('height', String(0.5 * banner.width()) + 'px');
        });

        // search
        $("#bs-navbar-collapse .search-top i").on('click', function() {
            /*
            $("#bs-navbar-collapse .search-top .search-box").toggle('slide', {
                direction: 'right'
            }, 250, function() {
                $('input', this).focus();
                
                $('input', this).focusout(function() {
                	$("#bs-navbar-collapse .search-top .search-box").hide();
                });
            });
            */
            
            $("#bs-navbar-collapse .search-top .search-box").toggle('slide', function() {
                $('input', this).focus();
                $('input', this).focusout(function() {
                	$("#bs-navbar-collapse .search-top .search-box").hide();
                });
            });
            
        })
    },

    headerTop : function() {
        $(window).on('scroll', function() {
            // var scrollTop = $(this).scrollTop();
            // $('header.header').each(function() {
            //    var topDistance = $(this).offset().top;
            // });
            var header = $('header.header');
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
        var nav = $('#menu-header-menu.nav.navbar-nav');
        var subs = $("ul.sub-menu", nav);
        nav.removeClass('hidden');
        $(subs).each(function(a, el) {
            var li = $(el.parentElement);
            //li.first('a').addClass('dropdown-toggle').attr('data-toggle', 'dropdown').attr('role', 'button');
            $(el).addClass('dropdown-menu');
            li.addClass('dropdown');

            if (li.has('ul li').length > 0 && li.parents('ul').length > 1) {
                li.children('a').append('<i class="fa fa-ellipsis-h"></i>');
            }
        });
    },

    initReadMore : function() {
        $('.entry-content.readmore-yes').readmore({
            collapsedHeight: 500,
            moreLink: '<div style="display:block"><i class="fa fa-ellipsis-h"></i></div><a href="#" class="read-more-btn btn">Read more &nbsp<i class="fa fa-chevron-down"></i></a>',
            lessLink: '<a href="#" class="read-more-btn btn">Collapse &nbsp<i class="fa fa-chevron-up"></i></a>',
            blockCSS: 'display: inline-block; width: auto;',
            beforeToggle: function() {
                $(".fa.fa-ellipsis-h").parent().remove();
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
            back_to_top = $('.cd-top');

        if (typeof back_to_top != 'object')
            return;

        //hide or show the "back to top" link
        $(window).scroll(function(){
            ( $(this).scrollTop() > offset ) ? back_to_top.addClass('cd-is-visible') : back_to_top.removeClass('cd-is-visible cd-fade-out');
            if( $(this).scrollTop() > offset_opacity ) {
                back_to_top.addClass('cd-fade-out');
            }
        });

        //smooth scroll to top
        back_to_top.on('click', function(event){
            event.preventDefault();
            $('body,html').animate({
                    scrollTop: 0 ,
                }, scroll_top_duration
            );
        });
    }
}

/**
 * menu tree used in the footer
 */
$.fn.extend({
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
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});
