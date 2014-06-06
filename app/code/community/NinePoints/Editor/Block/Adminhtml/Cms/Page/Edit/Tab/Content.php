<?php
/**
 * Created by NinePoints Co., LTD
 * User: ndlinh
 * Date:
 *
 * Copyright © NinePoints Co., LTD. All Rights Reserved.
 */
class NinePoints_Editor_Block_Adminhtml_Cms_Page_Edit_Tab_Content extends Mage_Adminhtml_Block_Cms_Page_Edit_Tab_Content
{
    protected function _prepareForm()
    {
        /** @var $model Mage_Cms_Model_Page */
        $model = Mage::registry('cms_page');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }


        $form = new Varien_Data_Form();


        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('content_fieldset', array('legend'=>Mage::helper('cms')->__('Content'),'class'=>'fieldset-wide'));
        $fieldset->addType('ckeditor',  Mage::getConfig()->getBlockClassName('npeditor/adminhtml_form_element_ckeditor'));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        $fieldset->addField('content_heading', 'text', array(
            'name'      => 'content_heading',
            'label'     => Mage::helper('cms')->__('Content Heading'),
            'title'     => Mage::helper('cms')->__('Content Heading'),
            'disabled'  => $isElementDisabled
        ));

        $contentField = $fieldset->addField('content', 'ckeditor', array(
            'name'      => 'content',
            'style'     => 'height:36em;',
            'required'  => true,
            'disabled'  => $isElementDisabled,
            'config'    => $wysiwygConfig
        ));

        // Setting custom renderer for content field to remove label column
        $renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_fieldset_element')
            ->setTemplate('cms/page/edit/form/renderer/content.phtml');
        $contentField->setRenderer($renderer);

        $form->setValues($model->getData());
        $this->setForm($form);

        Mage::dispatchEvent('adminhtml_cms_page_edit_tab_content_prepare_form', array('form' => $form));

        //return parent::_prepareForm();
    }
}