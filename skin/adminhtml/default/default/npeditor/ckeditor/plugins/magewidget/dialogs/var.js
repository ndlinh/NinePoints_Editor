/**
 * Variable dialog
 */
CKEDITOR.dialog.add('varDialog', function(editor) {
    var url = editor.config.magentovar_url;
    return {
        title: editor.lang.magewidget.insertVar,
        minWidth: 400,
        minHeight: 400,
        buttons: [],
        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Settings',
                elements: [
                    {
                        id: 'widget_content',
                        type: 'ajax',
                        url: url
                    }
                ]
            }
        ],
        onShow: function() {
            var dialog = this;
            dialog.getElement().$.removeClassName('cke_reset_all');
            dialog.getElement().$.select('.cke_dialog_footer').first().hide();

            new Ajax.Request(url, {
                evalScripts: true,
                onComplete: function(response) {
                    try {
                        var data = JSON.parse(response.responseText);
                        var obj = $(dialog.contentId);
                        if (!obj) {
                            return;
                        }

                        var prepareVariableRow = function(varValue, varLabel) {
                            var value = (varValue).replace(/"/g, '&quot;').replace(/'/g, '\\&#39;');
                            var content = '<a href="#" onclick="insertVariable(\''+ value +'\', \'' + editor.element.$.id + '\');return false;">' + varLabel + '</a>';
                            return content;
                        }

                        var variablesContent = '<ul>';
                        data.each(function(variableGroup) {
                            if (variableGroup.label && variableGroup.value) {
                                this.variablesContent += '<li><b>' + variableGroup.label + '</b></li>';
                                (variableGroup.value).each(function(variable){
                                    if (variable.value && variable.label) {
                                        variablesContent += '<li style="padding-left: 20px;">' +
                                        prepareVariableRow(variable.value, variable.label) + '</li>';
                                    }
                                }.bind(this));
                            }
                        }.bind(this));
                        variablesContent += '</ul>';
                        obj.innerHTML = '';
                        obj.insert({bottom: variablesContent});

                    } catch (e) {
                        if (console) {
                            console.log(e);
                        }
                    }
                }
            });
        }
    }
});