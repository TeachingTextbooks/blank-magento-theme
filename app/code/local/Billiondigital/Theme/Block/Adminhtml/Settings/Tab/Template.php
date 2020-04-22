<?php
//

class Billiondigital_Theme_Block_Adminhtml_Settings_Tab_Template extends Mage_Adminhtml_Block_Template
{
    private $_products;
    private $_categories;
    private $_pages;
    private $_templatesByType;

    protected function _getProducts()
    {
        if (!$this->_products) {
            $this->_products = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToSort('created_at', 'DESC')
                ->load();
        }

        return $this->_products;
    }

    protected function _getCategories()
    {
        if (!$this->_categories) {
            $this->_categories = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToSort('created_at', 'DESC')
                ->load();
        }

        return $this->_categories;
    }

    protected function _getPages()
    {
        if (!$this->_pages) {
            $this->_pages = array();

            $pages = Mage::getModel('cms/page')
                ->getCollection()
                ->load();


            foreach ($pages as $page) {
                $this->_pages[] = new Varien_Object(array(
                    'id' => $page->getPageId(),
                    'name' => $page->getTitle()
                ));
            }
        }

        return $this->_pages;
    }


    protected function _getTemplatesByType()
    {
        if (!$this->_templatesByType) {
            $theme = Mage::registry('designer_settings_theme');
            $templates = Mage::helper('billiontheme/template')->getAllTemplatesInfo($theme);

            $templatesByType = array();

            foreach ($templates as $id => $info) {
                if (!isset($templatesByType[$info['type']])) {
                    $templatesByType[$info['type']] = array();
                }
                $templatesByType[$info['type']][] = $info;
            }

            $this->_templatesByType = $templatesByType;
        }

        return $this->_templatesByType;
    }

    protected function _toHtml()
    {
        $this->setTemplate('billiontheme/settings/template.phtml');

        return parent::_toHtml();
    }
}

//