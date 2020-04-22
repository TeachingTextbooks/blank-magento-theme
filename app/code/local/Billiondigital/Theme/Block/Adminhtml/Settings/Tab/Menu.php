<?php
//

class Billiondigital_Theme_Block_Adminhtml_Settings_Tab_Menu extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fsh = $form->addFieldset('form_horizontal', array('legend' => $this->__('Horizontal')));

        $fsh->addField('hmenu', 'select', array(
            'label' => $this->__('View'),
            'name' => 'hmenu',
            'value' => '0',
            'values' => array(
                '0' => $this->__('All'),
                '1' => $this->__('Current')
            ),
            'tabindex' => 1
        ));

        $fsh->addField('navigation_home', 'select', array(
            'label' => $this->__('Show Home'),
            'name' => 'navigation_home',
            'value' => '0',
            'values' => array(
                '1' => $this->__('Yes'),
                '0' => $this->__('No')
            ),
            'tabindex' => 2
        ));

        $fsh->addField('navigation_home_text', 'text', array(
            'label' => Mage::helper('billiontheme')->__('Home Text'),
            'name' => 'navigation_home_text',
            'value' => 'Home',
            'tabindex' => 3
        ));

        $fsv = $form->addFieldset('form_vertical', array('legend' => $this->__('Vertical')));

        $fsv->addField('vmenu', 'select', array(
            'label' => $this->__('View'),
            'name' => 'vmenu',
            'value' => '0',
            'values' => array(
                '0' => $this->__('All'),
                '1' => $this->__('Current'),
                '2' => $this->__('None')
            ),
            'tabindex' => 4
        ));

        $fsmega = $form->addFieldset('form_megamenu', array('legend' => $this->__('Mega Menu')));

        $menuItems = $this->_getFirstLevelMenuItems();

        $index = 5;
        foreach ($menuItems as $item) {
            $fsmega->addField('megamenu_' . $item['value'], 'select', array(
                'label' => $item['label'],
                'name' => 'megamenu_' . $item['value'],
                'value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_AUTO,
                'values' => array(
                    array('value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_AUTO, 'label'  => 'Enable'),
                    array('value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_OFF,  'label'  => 'Disable')
                ),
                'after_element_html' => '<br><small>Available for 3 levels menu</small><script>jQuery(function () { changeMegaMenu(' . $item['value'] . '); })</script>',
                'onchange' => 'changeMegaMenu(' . $item['value'] . ')',
                'tabindex' => $index++
            ));

            $fsmega->addField('megawidth_' . $item['value'], 'select', array(
                'label' => '',
                'name' => 'megawidth_' . $item['value'],
                'value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_DEFAULT_WIDTH,
                'values' => array(
                    array('value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_DEFAULT_WIDTH, 'label' => 'Default Width'),
                    array('value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_SHEET_WIDTH,   'label' => 'Sheet Width'),
                    array('value' => Billiondigital_Theme_Model_Settings_Menu::MEGA_CUSTOM_WIDTH,  'label' => 'Custom Width')
                ),
                'after_element_html' => '<script>jQuery(function () { changeMegaMenuWidth(' . $item['value'] . '); })</script>',
                'onchange' => 'changeMegaMenuWidth(' . $item['value'] . ')',
                'tabindex' => $index++
            ));

            $fsmega->addField('megawidthvalue_' . $item['value'], 'text', array(
                'label' => '',
                'name' => 'megawidthvalue_' . $item['value'],
                'value' => '',
                'after_element_html' => '<br><small>Width value</small>',
                'tabindex' => $index++
            ));

            $fsmega->addField('megalg_' . $item['value'], 'select', array(
                'label' => '',
                'name' => 'megalg_' . $item['value'],
                'values' => array(
                    '' => '',
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '2' => '6',
                    '1' => '12'
                ),
                'after_element_html' => '<br><small>Desktops</small>',
                'tabindex' => $index++
            ));
            $fsmega->addField('megamd_' . $item['value'], 'select', array(
                'label' => '',
                'name' => 'megamd_' . $item['value'],
                'values' => array(
                    '' => '',
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '2' => '6',
                    '1' => '12'
                ),
                'after_element_html' => '<br><small>Laptops</small>',
                'tabindex' => $index++
            ));
            $fsmega->addField('megasm_' . $item['value'], 'select', array(
                'label' => '',
                'name' => 'megasm_' . $item['value'],
                'values' => array(
                    '' => '',
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '2' => '6',
                    '1' => '12'
                ),
                'after_element_html' => '<br><small>Tablets</small>',
                'tabindex' => $index++
            ));
            $fsmega->addField('megaxs_' . $item['value'], 'select', array(
                'label' => '',
                'name' => 'megaxs_' . $item['value'],
                'values' => array(
                    '' => '',
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '2' => '6',
                    '1' => '12'
                ),
                'after_element_html' => '<br><small>Phones</small>',
                'tabindex' => $index++
            ));
        }

        $model = Mage::getSingleton('Billiondigital_Theme_Model_Settings_Menu');
        $model->readOptions(Mage::registry('designer_settings_theme'));

        $form->setValues($model->toArray());

        return parent::_prepareForm();
    }

    private function _getFirstLevelMenuItems() {
        $_categories = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', '1')
            ->addAttributeToFilter('include_in_menu', '1')
            ->addAttributeToFilter('level', array('eq' => 2))
            ->addAttributeToSort('position')
            ->load();

        $result = array();

        foreach ($_categories as $category) {
            $result[] = array(
                'value' => $category->getId(),
                'label' => $category->getName()
            );
        }

        return $result;
    }
}
//