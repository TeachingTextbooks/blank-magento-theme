<?php

class Billiondigital_Themler_Helper_Template extends Mage_Core_Helper_Abstract
{
    public function getList($themeName, $dir = '')
    {
        $templates = array();
        $templatesPreview = array();

        $path = Mage::helper('billioncore/path');
        $templatesDir = $path->previewAppDir($themeName) . '/template/';
        $layoutsDir = $templatesDir . 'designer/layouts';

        if (($configFile = $layoutsDir . '/config.preview.phtml') && file_exists($configFile)) {
            Mage::unregister('disableThemlerParams');
            Mage::register('disableThemlerParams', true);
            ob_start();
            include $configFile;
            ob_end_clean();
            Mage::unregister('disableThemlerParams');
        }

        foreach (Mage::helper('billiontheme/template')->getAllTemplatesInfo($themeName, $dir) as $id => $info) {
            $templates[$info['name']] = Mage::helper('core/url')->addRequestParam(
                $templatesPreview[$id]['url'],
                array('layout' => $info['name'])
            );
        }

        return $templates;
    }
}