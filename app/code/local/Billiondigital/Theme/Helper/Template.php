<?php

// 

class Billiondigital_Theme_Helper_Template extends Mage_Core_Helper_Abstract
{
    private $_allTemplates = array();

    public function getAllTemplatesInfo($themeName)
    {
        if (!$this->_allTemplates && $themeName) {
            $templates = array();
            $templatesDir = $this->_getTemplatesDir($themeName);

            if (($configFile = $templatesDir . '/config.phtml') && file_exists($configFile)) {
                Mage::unregister('disableThemlerParams');
                Mage::register('disableThemlerParams', true);
                ob_start();
                include $configFile;
                ob_end_clean();
                Mage::unregister('disableThemlerParams');
            }

            $settings = Mage::getModel('billiontheme/settings_template')
                ->readOptions($themeName)
                ->getTemplate();

            foreach ($templates as $id => $tpl) {
                if (!empty($settings[$id])) {
                    $tpl = array_merge($tpl, $settings[$id]);
                }
                $tpl['id'] = $id;
                $tpl['active'] = !empty($tpl['active']);
                $this->_allTemplates[$id] = $tpl;
            }
        }

        return $this->_allTemplates;
    }

    public function findTemplate($themeName, $type, $searchData = array())
    {
        $templateInfo = null;
        $activeTemplate = null;
        $data = $this->getAllTemplatesInfo($themeName);
        $searchResult = array();

        if ($data) {
            foreach ($data as $id => $info) {
                $info = new Varien_Object($info);
                if ($info->getType() !== $type || !$this->_validateTemplate($themeName, $info)) continue;

                if ($info->getActive()) {
                    $activeTemplate = $info;
                }

                foreach (explode(',', $info->getSource()) as $source) {
                    if (empty($searchResult[$source])) {
                        $searchResult[$source] = array();
                    }

                    if (!empty($searchData[$source]) &&
                            ($entities = $info->getData($source)) &&
                            in_array($searchData[$source], $entities)) {

                        $searchResult[$source][] = $info;
                    }
                }
            }

            foreach ($searchResult as $source => $items) {
                if (null !== ($templateInfo = array_shift($items))) {
                    break;
                }
            }

            if (!$templateInfo) {
                $templateInfo = $activeTemplate;
            }
        }

        return $templateInfo;
    }

    private function _validateTemplate($themeName, $info) {
        $templatesDir = $this->_getTemplatesDir($themeName);

        $template = sprintf(
            $templatesDir . '/%s/template-%d.phtml',
            $info->getType(), $info->getId()
        );

        return file_exists($template);
    }

    private function _getTemplatesDir($themeName) {
        $path = Mage::helper('billioncore/path');

        if (Mage::helper('core')->isModuleEnabled('Billiondigital_Themler') &&
            (Mage::helper('billionthemler')->isPreview() ||
                Mage::app()->getRequest()->getControllerName() === 'billionthemler_theme')) {

            $templatesDir = $path->previewAppDir($themeName) . '/template/';
        } else {
            $templatesDir = $path->themeAppDir($themeName) . '/template/';
        }
        return $templatesDir . 'designer/layouts';
    }

}

//