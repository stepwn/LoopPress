(function() {
    tinymce.create('tinymce.plugins.LoopPress', {
        init: function(editor, url) {
            editor.addButton('looppress', {
                title: 'Token Gate Settings',
                icon:'lock',
                onclick: function() {
                    document.getElementById('looppress_editorModal').style.display='flex';
                    sendShortcodeToIframe(document.getElementById('looppress_visual_editor'),'[looppress schedule={}');
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('looppress_button_script', tinymce.plugins.LoopPress);
})();
