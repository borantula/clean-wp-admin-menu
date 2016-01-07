(function( $ ) {
	'use strict';



    var menusAreHidden = true;
    $(function() {

        /**
         * When the toggle extra item clicked show/hide menu items
         * Also trigger the wp-window-resized event for left menu
         */
        $('#toplevel_page_toggle_extra a').click(function(e){
            e.preventDefault();
            $('.menu-top.clean-wp-menu__valid-item').toggleClass('hidden');

            $(document).trigger('wp-window-resized');

        });

        /**
         * Little hack for some of the submenus declared after the admin_menu hook
         * If it should be open but hidden, remove the hidden class
         */
        $('#adminmenu .wp-menu-open.hidden').removeClass('hidden');
    });

})( jQuery );
