<?php
/**
 * Created by NinePoints Co., Ltd
 * User: ndlinh
 * Date: 2013-12-22
 *
 * Copyright ©2013 NinePoints Co., Ltd. All Rights Reserved.
 */
class NinePoints_Editor_Model_Source_Codemirror_Theme
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'default', 'label' => Mage::helper('adminhtml')->__('default')),
            array('value' => '3024-day', 'label' => Mage::helper('adminhtml')->__('3024-day')),
            array('value' => '3024-night', 'label' => Mage::helper('adminhtml')->__('3024-night')),
            array('value' => 'ambiance', 'label' => Mage::helper('adminhtml')->__('ambiance')),
            array('value' => 'ambiance-mobile', 'label' => Mage::helper('adminhtml')->__('ambiance-mobile')),
            array('value' => 'blackboard', 'label' => Mage::helper('adminhtml')->__('blackboard')),
            array('value' => 'cobalt', 'label' => Mage::helper('adminhtml')->__('cobalt')),
            array('value' => 'elegant', 'label' => Mage::helper('adminhtml')->__('elegant')),
            array('value' => 'erlang-dark', 'label' => Mage::helper('adminhtml')->__('erlang-dark')),
            array('value' => 'mbo', 'label' => Mage::helper('adminhtml')->__('mbo')),
            array('value' => 'midnight', 'label' => Mage::helper('adminhtml')->__('midnight')),
            array('value' => 'monokai', 'label' => Mage::helper('adminhtml')->__('monokai')),
            array('value' => 'neat', 'label' => Mage::helper('adminhtml')->__('neat')),
            array('value' => 'night', 'label' => Mage::helper('adminhtml')->__('night')),
            array('value' => 'paraiso-light', 'label' => Mage::helper('adminhtml')->__('paraiso-light')),
            array('value' => 'rubyblue', 'label' => Mage::helper('adminhtml')->__('rubyblue')),
            array('value' => 'solarized', 'label' => Mage::helper('adminhtml')->__('solarized')),
            array('value' => 'the-matrix', 'label' => Mage::helper('adminhtml')->__('the-matrix')),
            array('value' => 'tomorrow-night-eighties', 'label' => Mage::helper('adminhtml')->__('tomorrow-night-eighties')),
            array('value' => 'twilight', 'label' => Mage::helper('adminhtml')->__('twilight')),
            array('value' => 'vibrant-ink', 'label' => Mage::helper('adminhtml')->__('vibrant-ink')),
            array('value' => 'xq-dark', 'label' => Mage::helper('adminhtml')->__('xq-dark')),
            array('value' => 'xq-light', 'label' => Mage::helper('adminhtml')->__('xq-light')),
        );
    }
}