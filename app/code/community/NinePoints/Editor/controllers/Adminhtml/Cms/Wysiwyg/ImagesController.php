<?php
/**
 * Created by NinePoints Co., Ltd
 * User: ndlinh
 * Date: 2013-12-19
 *
 * Copyright ©2013 NinePoints Co., Ltd. All Rights Reserved.
 */
require_once 'Mage/Adminhtml/controllers/Cms/Wysiwyg/ImagesController.php';

class NinePoints_Editor_Adminhtml_Cms_Wysiwyg_ImagesController
    extends Mage_Adminhtml_Cms_Wysiwyg_ImagesController {

    public function indexAction()
    {
        $storeId = (int) $this->getRequest()->getParam('store');

        try {
            Mage::helper('cms/wysiwyg_images')->getCurrentPath();
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        if ($this->getRequest()->getParam('CKEditor') != '') {
            $this->_initAction();
            $update = $this->getLayout()->getUpdate();
            $update->addHandle('default');
            $this->addActionLayoutHandles();
            $update->addHandle('npeditor');

            $block = $this->getLayout()->getBlock('wysiwyg_images.js');
            if ($block) {
                $block->setStoreId($storeId);
            }

            $this->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();
        } else {
            $this->_initAction()->loadLayout('overlay_popup');
            $block = $this->getLayout()->getBlock('wysiwyg_images.js');
            if ($block) {
                $block->setStoreId($storeId);
            }
        }

        $this->renderLayout();
    }
}