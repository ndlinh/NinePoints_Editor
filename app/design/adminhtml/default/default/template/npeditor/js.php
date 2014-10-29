<script type="text/javascript">
    <?php if (Mage::helper('npeditor')->isEnableSyntaxHighLight()): ?>
    CMInstances = {};
    <?php endif ?>
    var extPlugins = '';
    <?php if ($this->getEnableWidgets()): ?>
    extPlugins = 'widget,lineutils,magewidget';
    <?php endif ?>
    function advEditorToggle(elementId) {
        if (CKEDITOR.instances[elementId]) {
            CKEDITOR.instances[elementId].destroy(true);
            <?php if (Mage::helper('npeditor')->isEnableSyntaxHighLight()): ?>
            CMInstances[elementId] =  CodeMirror.fromTextArea(document.getElementById('page_content'), {
                mode: "htmlmixed"
            });
            <?php endif ?>

            return null;
        } else {
            <?php if (Mage::helper('npeditor')->isEnableSyntaxHighLight()): ?>
            if (CMInstances[elementId] != undefined) {
                CMInstances[elementId].toTextArea();
            }
            <?php endif ?>
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
<?php if (Mage::helper('npeditor')->isCodeMirrorRequired()): ?>
<script type="text/javascript" src="<?php echo $this->getSkinUrl('npeditor/ckeditor/plugins/codemirror/js/codemirror.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->getSkinUrl('npeditor/ckeditor/plugins/codemirror/js/codemirror.mode.htmlmixed.min.js') ?>"></script>
<?php endif ?>
