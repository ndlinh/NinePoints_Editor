<?php
/**
 * Created by NinePoints Co., LTD
 * User: ndlinh
 * Date:
 *
 * Copyright © NinePoints Co., LTD. All Rights Reserved.
 */
class NinePoints_Editor_Block_Adminhtml_Form_Element_Ckeditor extends Varien_Data_Form_Element_Editor
{

    public function getElementHtml()
    {
        $js = $this->_getOverrideJs();
        $js .= '
            <script type="text/javascript">
            //<![CDATA[
                openEditorPopup = function(url, name, specs, parent) {
                    if ((typeof popups == "undefined") || popups[name] == undefined || popups[name].closed) {
                        if (typeof popups == "undefined") {
                            popups = new Array();
                        }
                        var opener = (parent != undefined ? parent : window);
                        popups[name] = opener.open(url, name, specs);
                    } else {
                        popups[name].focus();
                    }
                    return popups[name];
                }

                closeEditorPopup = function(name) {
                    if ((typeof popups != "undefined") && popups[name] != undefined && !popups[name].closed) {
                        popups[name].close();
                    }
                }
            //]]>
            </script>';

        if($this->isEnabled())
        {
            // add Firebug notice translations
            $warn = 'Firebug is known to make the WYSIWYG editor slow unless it is turned off or configured properly.';
            $this->getConfig()->addData(array(
                'firebug_warning_title'  => $this->translate('Warning'),
                'firebug_warning_text'   => $this->translate($warn),
                'firebug_warning_anchor' => $this->translate('Hide'),
            ));

            $translatedString = array(
                'Insert Image...' => $this->translate('Insert Image...'),
                'Insert Media...' => $this->translate('Insert Media...'),
                'Insert File...'  => $this->translate('Insert File...')
            );

            $jsSetupObject = 'wysiwyg' . $this->getHtmlId();

            $forceLoad = '';
            if (!$this->isHidden()) {
                if ($this->getForceLoad()) {
                    $forceLoad = $jsSetupObject . '.setup("exact");';
                } else {
                    $forceLoad = 'Event.observe(window, "load", '
                        . $jsSetupObject . '.setup.bind(' . $jsSetupObject . ', "exact"));';
                }
            }

            $html = $this->_getButtonsHtml()
                . '<textarea name="' . $this->getName() . '" title="' . $this->getTitle()
                . '" id="' . $this->getHtmlId() . '"'
                . ' class="textarea ' . $this->getClass() . '" '
                . $this->serialize($this->getHtmlAttributes()) . ' >' . $this->getEscapedValue() . '</textarea>'
                . $js . '
                <script type="text/javascript">
                //<![CDATA[
                    if ("undefined" != typeof(Translator)) {
                        Translator.add(' . Zend_Json::encode($translatedString) . ');
                    }'
                . $jsSetupObject . ' = new tinyMceWysiwygSetup("' . $this->getHtmlId() . '", '
                . Zend_Json::encode($this->getConfig()).');'
                . $forceLoad.'
                    editorFormValidationHandler = ' . $jsSetupObject . '.onFormValidation.bind(' . $jsSetupObject . ');
                    Event.observe("toggle' . $this->getHtmlId() . '", "click", '
                . $jsSetupObject . '.toggle.bind('.$jsSetupObject.'));
                    varienGlobalEvents.attachEventHandler("formSubmit", editorFormValidationHandler);
                    varienGlobalEvents.attachEventHandler("tinymceBeforeSetContent", '
                . $jsSetupObject . '.beforeSetContent.bind(' . $jsSetupObject . '));
                    varienGlobalEvents.attachEventHandler("tinymceSaveContent", '
                . $jsSetupObject . '.saveContent.bind(' . $jsSetupObject . '));
                    varienGlobalEvents.clearEventHandlers("open_browser_callback");
                    varienGlobalEvents.attachEventHandler("open_browser_callback", '
                . $jsSetupObject . '.openFileBrowser.bind(' . $jsSetupObject . '));
                //]]>
                </script>';

            $html = $this->_wrapIntoContainer($html);
            $html .= $this->getAfterElementHtml();
            return $html;
        } else {
            return parent::getElementHtml();
        }
    }

    protected function _getOverrideJs()
    {
        if (!Mage::helper('npeditor')->isUseCkEditor()) {
            return '';
        }

        $variableUrl = Mage::helper('npeditor')->getVariablesWysiwygActionUrl();
        $js = <<<JAVACRIPT
<script type="text/javascript">
    tinyMceWysiwygSetup.prototype.turnOn = function() {
        advEditorToggle(this.id);
        CKEDITOR.instances[this.id].config.magentowidget_url = this.config.widget_window_url;
        CKEDITOR.instances[this.id].config.magentovar_url = '{$variableUrl}';
        CKEDITOR.instances[this.id].updateElement();
        this.getPluginButtons().each(function(e) {
            e.hide();
        });
    }

    tinyMceWysiwygSetup.prototype.turnOff = function() {
        if (CKEDITOR.instances[this.id]) {
            CKEDITOR.instances[this.id].updateElement();
        }
        advEditorToggle(this.id);
        this.getPluginButtons().each(function(e) {
            e.show();
        });
    }

    tinyMceWysiwygSetup.prototype.onFormValidation = function() {
        if (CKEDITOR.instances[this.id]) {
            CKEDITOR.instances[this.id].updateElement();
        }
    }

    tinyMceWysiwygSetup.prototype.initialize = function(htmlId, config)
    {
        this.id = htmlId;
        this.config = config;
        varienGlobalEvents.attachEventHandler('tinymceChange', this.onChangeContent.bind(this));
        this.notifyFirebug();
    }

    tinyMceWysiwygSetup.prototype.toggle = function() {
        if (CKEDITOR.instances[this.id]) {
            this.turnOff();
            return true;
        } else {
            this.turnOn();
            this.bindEvents();
            return false;
        }
    }

    tinyMceWysiwygSetup.prototype.setup = function(mode) {
        advEditorToggle(this.id);
        CKEDITOR.instances[this.id].config.magentowidget_url = this.config.widget_window_url;
        CKEDITOR.instances[this.id].config.magentovar_url = '{$variableUrl}';
        this.bindEvents();
    }

    tinyMceWysiwygSetup.prototype.bindEvents = function() {
        var editor = CKEDITOR.instances[this.id];

        editor.on('getData', function(evt) {
            varienGlobalEvents.fireEvent('tinymceSaveContent', evt);
        });

        editor.on('setData', function(evt) {
            evt.content = evt.data.dataValue;
            varienGlobalEvents.fireEvent('tinymceBeforeSetContent', evt);
        });
    }

    tinyMceWysiwygSetup.prototype.beforeSetContent = function(o) {
        if (this.config.add_widgets) {
            o.data.dataValue = this.encodeWidgets(o.data.dataValue);
            o.data.dataValue = this.encodeDirectives(o.data.dataValue);
        } else if (this.config.add_directives) {
            o.data.dataValue = this.encodeDirectives(o.data.dataValue);
        }
    }

    tinyMceWysiwygSetup.prototype.saveContent = function(o) {
        if (this.config.add_widgets) {
            o.data.dataValue = this.decodeWidgets(o.data.dataValue);
            o.data.dataValue = this.decodeDirectives(o.data.dataValue);
        } else if (this.config.add_directives) {
            o.data.dataValue = this.decodeDirectives(o.data.dataValue);
        }
    }

    /**
     * Override Widgets
     */
    WysiwygWidget.Widget.prototype.wysiwygExists = function() {
        return (CKEDITOR.instances[this.widgetTargetId] != null);
    }

    WysiwygWidget.Widget.prototype.insertWidget = function(editor) {

        widgetOptionsForm = new varienForm(this.formEl);
        if(widgetOptionsForm.validator && widgetOptionsForm.validator.validate() || !widgetOptionsForm.validator){
            var formElements = [];
            var i = 0;
            Form.getElements($(this.formEl)).each(function(e) {
                if(!e.hasClassName('skip-submit')) {
                    formElements[i] = e;
                    i++;
                }
            });

            // Add as_is flag to parameters if wysiwyg editor doesn't exist
            var params = Form.serializeElements(formElements);
            if (!this.wysiwygExists()) {
                params = params + '&as_is=1';
            }

            new Ajax.Request($(this.formEl).action,
                {
                    parameters: params,
                    onComplete: function(transport) {
                        try {
                            widgetTools.onAjaxSuccess(transport);

                            if (editor != undefined) {
                                var tag = CKEDITOR.dom.element.createFromHtml(transport.responseText);
                                editor.insertElement(tag);
                                editor.widgets.initOn(tag, 'magewidget');
                            } else {
                                Windows.close("widget_window");
                                var textarea = document.getElementById(this.widgetTargetId);
                                updateElementAtCursor(textarea, transport.responseText);
                            }
                        } catch(e) {
                            alert(e.message);
                        }
                    }.bind(this)
                });
        }
    }

    WysiwygWidget.Widget.prototype.getWysiwygNode = function() {
        var selection = CKEDITOR.instances[this.widgetTargetId].getSelection();
        if (selection && selection.getType() == CKEDITOR.SELECTION_ELEMENT) {
            return selection.getStartElement().getFirst();
        }

        return null;
    }

    WysiwygWidget.Widget.prototype.initOptionValues = function() {

        if (!this.wysiwygExists()) {
            return false;
        }

        var e = this.getWysiwygNode();
        if (e != undefined && e.getId()) {
            var widgetCode = Base64.idDecode(e.getId());
            if (widgetCode.indexOf('{{widget') != -1) {
                this.optionValues = new Hash({});
                widgetCode.gsub(/([a-z0-9\_]+)\s*\=\s*[\"]{1}([^\"]+)[\"]{1}/i, function(match){
                    if (match[1] == 'type') {
                        this.widgetEl.value = match[2];
                    } else {
                        this.optionValues.set(match[1], match[2]);
                    }
                }.bind(this));

                this.loadOptions();
            }
        }
    }
</script>
JAVACRIPT;

        return $js;
    }
}
