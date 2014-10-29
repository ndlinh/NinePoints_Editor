/**
 * Magento Widget Insert Plugin
 * @author: ndlinh
 */

/**
 * Add new ajax element to dialog
 */
CKEDITOR.tools.extend( CKEDITOR.ui.dialog, {
    ajax: function(dialog, elementDefinition, htmlList ) {

        dialog.contentId = CKEDITOR.tools.getNextId();
        var innerHTML = '<div id="' + dialog.contentId + '">Loadding</div>';
        CKEDITOR.ui.dialog.uiElement.call(this, dialog, elementDefinition, htmlList, 'span', null, null, innerHTML);
    }
});

CKEDITOR.ui.dialog.ajax.prototype = new CKEDITOR.ui.dialog.uiElement;

CKEDITOR.dialog.addUIElement( 'ajax', {
    build: function( dialog, elementDefinition, output ) {
        return new CKEDITOR.ui.dialog[ elementDefinition.type ]( dialog, elementDefinition, output );
    }
});

/**
 * Widget declaration
 */
CKEDITOR.plugins.add('magewidget', {
    lang: 'en,ja,vi',
    requires: 'widget',
    icons: 'widget,variable',
    init: function(editor) {
        //add dialog
        CKEDITOR.dialog.add( 'widgetDialog', this.path + 'dialogs/widget.js' );
        CKEDITOR.dialog.add( 'varDialog', this.path + 'dialogs/var.js' );
        editor.addCommand('widgetDialog', new CKEDITOR.dialogCommand('widgetDialog'));
        editor.addCommand('varDialog', new CKEDITOR.dialogCommand('varDialog'));


        //add toolbar button
        editor.ui.addButton('Widget', {
            label: editor.lang.magewidget.insertWidget,
            command: 'widgetDialog',
            toolbar: 'insert'
        });

        editor.ui.addButton('Variables', {
            label: editor.lang.magewidget.insertVar,
            command: 'varDialog',
            toolbar: 'insert',
            icon: 'variable'
        });

        //Add magento widget
        editor.widgets.add('magewidget', {
            dialog: 'widgetDialog',

            upcast: function(el) {
                if (el.attributes.id == undefined) {
                    return false;
                }
                var widgetCode = Base64.idDecode(el.attributes.id);

                return (el.name == 'img' && widgetCode.indexOf('{{widget') != -1);
            }
        });
    }
})