<?php
//

class Billiondigital_Theme_Model_Settings_Template extends Varien_Object
{
    public function readOptions($themeName) {
        /** @var $helper Billiondigital_Core_Helper_Data */
        $helper = Mage::helper('billioncore');
        $this->setData('template', json_decode($helper->getConfigValue("designer/settings/$themeName/template", '{}'), true));
        return $this;
    }

    public function saveOptions($themeName, $saveData = array()) {
        Mage::getConfig()->saveConfig("designer/settings/$themeName/template", json_encode($saveData));
    }
}

//