(function ( $ ) {
	"use strict";

    function toggle_options(value) {
        // Hide all optional options
        $('#wpnettermine_row_iframewidth, #wpnettermine_row_iframeheight, #wpnettermine_row_styles').hide();

        // Show options related to html-value
        if (value === 'html') {
            $('#wpnettermine_row_styles').show();
        }
        else if (value === 'iframe') {
            $('#wpnettermine_row_iframewidth, #wpnettermine_row_iframeheight').show();
        }
    }

    $(function () {

        // Init options
        toggle_options($('#wpnettermine_includetype').val());

        // Change options on change
        $('#wpnettermine_includetype').on('change.wpnettermine', function () {
            toggle_options( $(this).val() );
        });

	});

}(jQuery));