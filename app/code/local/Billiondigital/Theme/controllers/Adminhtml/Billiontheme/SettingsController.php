<?php
// 

class Billiondigital_Theme_Adminhtml_Billiontheme_SettingsController extends Billiondigital_Theme_Controller_AdminAction
{
    public function indexAction() {
        $this->loadLayout();
        Mage::register('designer_settings_theme', $this->currentTheme);
        $this->_addContent($this->getLayout()->createBlock('billiontheme/adminhtml_settings_edit'))
            ->_addLeft($this->getLayout()->createBlock('billiontheme/adminhtml_settings_tabs'));
        $this->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $menu = Mage::getSingleton('Billiondigital_Theme_Model_Settings_Menu');
            $menu->saveOptions($this->currentTheme, $data);

            $logo = Mage::getSingleton('Billiondigital_Theme_Model_Settings_Logo');
            $logo->saveOptions($this->currentTheme, $data);

            $slider = Mage::getSingleton('Billiondigital_Theme_Model_Settings_Slider');
            $slider->saveOptions($this->currentTheme, $data);
        }
        $this->adminRedirect('*/*/index', array('theme' => $this->currentTheme));
    }

    /* custom templates */

    public function updateTemplateInfoAction() {
        $info = json_decode($this->getRequest()->getParam('info', '{}'), true);

        $model = Mage::getModel('billiontheme/settings_template');
        $model->saveOptions($this->currentTheme, $info);

        $this->getResponse()->setBody(
            $this->coreHelper->jsonEncode(array('status' => 'OK'))
        );
    }
}

//