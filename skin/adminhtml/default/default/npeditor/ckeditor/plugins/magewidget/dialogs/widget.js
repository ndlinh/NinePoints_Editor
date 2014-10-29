/**
 * Widget dialog wrapper
 */
CKEDITOR.dialog.add('widgetDialog', function(editor) {
    var url = editor.config.magentowidget_url + 'widget_target_id/' + editor.element.getId() + '/';
    return {
        title: editor.lang.magewidget.insertWidget,
        minWidth: 950,
        minHeight: 480,
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        id: 'widget_content',
                        type: 'ajax',
                        url: url,
                        setup: function( widget ) {
                            this.element = widget.element;
                        },
                        commit: function( widget ) {

                        }
                    }
                ]
            }
        ],
        onOk: function() {
            if (wWidget) {
                wWidget.insertWidget(editor);
            }
        },
        onShow: function() {
            var dialog = this;
            new Ajax.Updater(this.contentId, url, {
                evalScripts: true,
                onComplete: function() {
                    dialog.getElement().$.removeClassName('cke_reset_all');
                    var el = $('insert_button');
                    if (el) el.remove();
                }
            });
        },
        onHide: function() {
            Windows.close("widget-chooser");
        }
    }
});