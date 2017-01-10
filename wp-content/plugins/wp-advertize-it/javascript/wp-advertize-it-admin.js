/**
 * Wrapper function to safely use $
 */
var custom_uploader;
function wpaiAdminWrapper($) {
    var wpaiAdmin = {
        /**
         * Main entry point
         */
        init: function () {
            wpaiAdmin.registerAce();
            $('.media-button').click(function (e) {
                e.preventDefault();

                var image_url = $(this).siblings('.wpai-image-url');
                var image_description = $(this).siblings('.wpai-image-description');

                //If the uploader object has already been created, reopen the dialog
                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }

                //Extend the wp.media object
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: { text: 'Choose Image' },
                    library: { type: 'image' },
                    multiple: false,
                    frame: 'select'
                });

                //When a file is selected, grab the URL and set it as the text field's value
                custom_uploader.on('select', function () {
                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    image_url.val(attachment.url);
                    image_description.val(attachment.description);
                });

                //Open the uploader dialog
                custom_uploader.open();
            });
        },

        registerAce: function () {
            jQuery('.settings_page_wpai_settings textarea').each(function () {
                var editor = ace.edit(jQuery(this).attr('id')+'_div');
                var textarea = jQuery(this).hide();
                editor.setTheme("ace/theme/chrome");
                editor.getSession().setUseWrapMode(true);
                editor.getSession().setUseWorker(false);
                editor.getSession().setMode("ace/mode/html");
                editor.getSession().setValue(jQuery(this).val());
                editor.getSession().on('change', function(){
                    textarea.val(editor.getSession().getValue());
                });
            });
        }
    }; // end wpaiAdmin

    $(document).ready(wpaiAdmin.init);

} // end wpaiAdminWrapper()

wpaiAdminWrapper(jQuery);
