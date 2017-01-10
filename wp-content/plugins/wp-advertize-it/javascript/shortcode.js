(function () {
    tinymce.create('tinymce.plugins.wpai', {
        init: function (editor, url) {

            editor.addButton('showad', {
                title: 'Ad Block',
                cmd: 'showad',
                image: url + '/../images/dollar.png'
            });

            editor.addCommand('showad', function () {
                // Open window
                editor.windowManager.open({
                        title: 'WP Advertize It',
                        url: ajaxurl + '?action=get_ad_list',
                        buttons: [
                            {
                                text: "Insert",
                                onclick: function (e) {
                                    editor.insertContent('[showad block=' + jQuery('.mce-container-body iframe').contents().find('#ad-block-select').val() + ']');
                                    editor.windowManager.close();
                                }
                            },
                            {
                                text: "Cancel",
                                onclick: 'close'
                            }
                        ],
                        height: 60,
                        inline: 1
                    },
                    {
                        plugin_url: url
                    });
            });
        }
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add('wpai', tinymce.plugins.wpai);
})();