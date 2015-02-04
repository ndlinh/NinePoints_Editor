<script type="text/javascript">
    <?php if (Mage::helper('npeditor')->isEnableSyntaxHighLight()): ?>
    CMInstances = {};
    <?php endif ?>
    var extPlugins = 'codemirror';
    <?php if ($this->getEnableWidgets()): ?>
    extPlugins = 'widget,lineutils,magewidget,codemirror';
    <?php endif ?>
    function advEditorToggle(elementId) {
        if (CKEDITOR.instances[elementId]) {
            CKEDITOR.instances[elementId].destroy(true);

            return null;
        } else {
            CKEDITOR.replace(elementId, {
                extraAllowedContent: {
                    img: {
                        attributes: '!src, alt, title, id, template',
                        styles: 'height, width'
                    }
                },
                filebrowserImageBrowseUrl: '<?php echo Mage::helper('adminhtml')->getUrl('adminhtml/cms_wysiwyg_images/index', array('type' => 'image', 'node' => 'root')) ?>',
                codemirror: {
                    theme: '<?php echo Mage::helper('npeditor')->getEditorTheme() ?>'
                },
                height: 500,
                width: '99.5%',
                toolbarCanCollapse: true,
                language: '<?php echo Mage::helper('npeditor')->getLanguage() ?>',
                extraPlugins: extPlugins
            });
            
            //workaround for editor in hidden tab
            setTimeout(function() {
                CKEDITOR.instances[elementId].resize();
            }, 1000);

            return CKEDITOR.instances[elementId];
        }
    }

    function insertVariable(value, target) {
        if (!CKEDITOR.instances[target]) {
            return;
        }

        CKEDITOR.instances[target].insertText(value);
        CKEDITOR.dialog.getCurrent().hide()
    }
</script>
