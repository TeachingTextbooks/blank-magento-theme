<?php
//

class Billiondigital_Theme_Model_Settings_Menu extends Varien_Object
{
    const MEGA_AUTO = 0;
    const MEGA_OFF = 1;

    const MEGA_DEFAULT_WIDTH = 0;
    const MEGA_SHEET_WIDTH = 1;
    const MEGA_CUSTOM_WIDTH = 2;

    public function readOptions($themeName) {
        /** @var $helper Billiondigital_Core_Helper_Data */
        $helper = Mage::helper('billioncore');
        $this->setData('hmenu', $helper->getConfigValue("designer/settings/$themeName/hmenu", ''));
        $this->setData('vmenu', $helper->getConfigValue("designer/settings/$themeName/vmenu", ''));
        $this->setData('navigation_home', $helper->getConfigValue("designer/settings/$themeName/navigation_home", ''));
        $this->setData('navigation_home_text', $helper->getConfigValue("designer/settings/$themeName/navigation_home_text", 'Home'));

        $megaMenu = json_decode($helper->getConfigValue("designer/settings/$themeName/megamenu", '[]'), true);

        foreach ($megaMenu as $id => $data) {
            $this->setData('megamenu_' . $id, $data['status']);
            $this->setData('megawidth_' . $id, $data['width']);
            $this->setData('megacustomwidth_' . $id, $data['customWidth']);
            $this->setData('megalg_' . $id, $data['lg']);
            $this->setData('megamd_' . $id, $data['md']);
            $this->setData('megasm_' . $id, $data['sm']);
            $this->setData('megaxs_' . $id, $data['xs']);
        }
    }

    public function saveOptions($themeName, $data) {
        if (isset($data['hmenu'])) {
            Mage::getConfig()->saveConfig("designer/settings/$themeName/hmenu", $data['hmenu']);
        }
        if (isset($data['vmenu'])) {
            Mage::getConfig()->saveConfig("designer/settings/$themeName/vmenu", $data['vmenu']);
        }
        if (isset($data['navigation_home'])) {
            Mage::getConfig()->saveConfig("designer/settings/$themeName/navigation_home", $data['navigation_home']);
        }
        if (isset($data['navigation_home_text'])) {
            Mage::getConfig()->saveConfig("designer/settings/$themeName/navigation_home_text", $data['navigation_home_text']);
        }

        $megaMenu = array();
        foreach ($data as $prop => $value) {
            if (strpos($prop, 'megamenu_') === 0) {
                $itemId = str_replace('megamenu_', '', $prop);

                $width = empty($data['megawidth_' . $itemId]) ? self::MEGA_DEFAULT_WIDTH : $data['megawidth_' . $itemId];
                $customWidth = empty($data['megawidthvalue_' . $itemId]) ? '' : $data['megawidthvalue_' . $itemId];
                $lg = empty($data['megalg_' . $itemId]) ? '' : $data['megalg_' . $itemId];
                $md = empty($data['megamd_' . $itemId]) ? '' : $data['megamd_' . $itemId];
                $sm = empty($data['megasm_' . $itemId]) ? '' : $data['megasm_' . $itemId];
                $xs = empty($data['megaxs_' . $itemId]) ? '' : $data['megaxs_' . $itemId];

                $megaMenu[$itemId] = array(
                    'status' => $value,
                    'width' => $width,
                    'customWidth' => $customWidth,
                    'lg' => $lg,
                    'md' => $md,
                    'sm' => $sm,
                    'xs' => $xs
                );
            }
        }

        Mage::getConfig()->saveConfig("designer/settings/$themeName/megamenu", json_encode($megaMenu));
    }
}

//