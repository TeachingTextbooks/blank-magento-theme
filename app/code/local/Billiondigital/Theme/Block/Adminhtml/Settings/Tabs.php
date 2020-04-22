<?php
//

class Billiondigital_Theme_Block_Adminhtml_Settings_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define above
        $this->setTitle($this->__('Theme Settings'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('logo_section', array(
            'label'     => $this->__('Logo'),
            'title'     => $this->__('Logo'),
            'content'   => $this->getLayout()->createBlock('billiontheme/adminhtml_settings_tab_logo')->toHtml()
        ));

        $this->addTab('menu_section', array(
            'label'     => $this->__('Menu'),
            'title'     => $this->__('Menu'),
            'content'   => $this->getLayout()->createBlock('billiontheme/adminhtml_settings_tab_menu')->toHtml()
        ));

        $this->addTab('slider_section', array(
            'label'     => $this->__('Slider'),
            'title'     => $this->__('Slider'),
            'content'   => $this->getLayout()->createBlock('billiontheme/adminhtml_settings_tab_slider')->toHtml()
        ));

        $this->addTab('template_section', array(
            'label'     => $this->__('Template'),
            'title'     => $this->__('Template'),
            'content'   => $this->getLayout()
                                ->createBlock('billiontheme/adminhtml_settings_tab_template')
                                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}


//