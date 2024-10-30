// JavaScript Document

( function($) {

	$( function($) {
				

                IWPTW.iwptw_uninstall_button();
                
	});

	IWPTW = {

                iwptw_uninstall_button : function() {
                    var button = $('input[name="do"]');
                    var checkbox = $('#uninstall_iwptw_yes');
                    button.hide();
                    checkbox.attr( 'checked', '' ).click(function() {
                        var is_checked = checkbox.attr( 'checked' );
                        if ( is_checked )
                            button.fadeIn();
                        else
                            button.fadeOut();
                    })
                }
	}

})(jQuery);