<?php
//

class Billiondigital_Theme_Block_Adminhtml_Settings_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->addButton('theme_back', array(
            'label' => $this->__('Back'),
            'onclick' => "setLocation('" . $this->getUrl('adminhtml/billiontheme_list/index') . "')",
            'class' => 'back'
        ), -1, -1, 'header');

        $this->_removeButton('back');

        $this->_objectId = 'id';
        $this->_blockGroup = 'billiontheme';
        $this->_controller = 'adminhtml_settings';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', $this->__('Save'));

        $this->_formScripts[] = '
            function enableSliderChange(id) {
                var selected = jQuery("#slider_enabled_" + id).find("option:selected");
                var enabled = selected.val() === "1"
                jQuery("#slider_header_" + id).prop("disabled", !enabled);
                jQuery("#slider_category_" + id).prop("disabled", !enabled);
                jQuery("#slider_count_" + id).prop("disabled", !enabled);
                jQuery("#slider_source_" + id).prop("disabled", !enabled);
                jQuery("#slider_products_" + id).prop("disabled", !enabled);
            }

            function changeSliderDataSource(id) {
                var sourceFields = {
                    ' . Mage::helper('billiontheme/product')->CATEGORY . ': [
                        "category"
                    ],
                    ' . Mage::helper('billiontheme/product')->BESTSELLERS . ': [
                        "products"
                    ]
                };

                // hide all
                for (var i in sourceFields) {
                    if (Array.isArray(sourceFields[i])) {
                        _changeVisible(sourceFields[i], "hide", id);
                    }
                }

                var selected = jQuery("#slider_source_" + id).find("option:selected").val();
                // show selected
                if (sourceFields[selected] && Array.isArray(sourceFields[selected])) {
                    _changeVisible(sourceFields[selected], "show", id);
                }
            }

            function _changeVisible(fields, action, id) {
                fields.forEach(function (field) {
                    jQuery("#slider_" + field + "_" + id).parents("tr")[action]();
                });
            }
            
            function changeMegaMenu(id) {
                var menuEnabled = jQuery("#megamenu_" + id).find("option:selected").val();
                var widthValue = jQuery("#megawidth_" + id).find("option:selected").val();
                
                jQuery("#megawidth_" + id).parents("tr").hide();
                jQuery("#megawidthvalue_" + id).parents("tr").hide();
                jQuery("#megaxs_" + id).parents("tr").hide();
                jQuery("#megasm_" + id).parents("tr").hide();
                jQuery("#megamd_" + id).parents("tr").hide();
                jQuery("#megalg_" + id).parents("tr").hide();
                
                if (menuEnabled === "' . Billiondigital_Theme_Model_Settings_Menu::MEGA_AUTO . '") {
                    jQuery("#megawidth_" + id).parents("tr").show();
                    jQuery("#megaxs_" + id).parents("tr").show();
                    jQuery("#megasm_" + id).parents("tr").show();
                    jQuery("#megamd_" + id).parents("tr").show();
                    jQuery("#megalg_" + id).parents("tr").show();
                                    
                    if (widthValue === "' . Billiondigital_Theme_Model_Settings_Menu::MEGA_CUSTOM_WIDTH . '") {
                        jQuery("#megawidthvalue_" + id).parents("tr").show();
                    }
                }
            }
            
            function changeMegaMenuWidth(id) {
                var menuEnabled = jQuery("#megamenu_" + id).find("option:selected").val();
                var widthValue = jQuery("#megawidth_" + id).find("option:selected").val();
                jQuery("#megawidthvalue_" + id).parents("tr").hide();
                
                if ((menuEnabled === "' . Billiondigital_Theme_Model_Settings_Menu::MEGA_AUTO . '") &&
                        widthValue === "' . Billiondigital_Theme_Model_Settings_Menu::MEGA_CUSTOM_WIDTH . '") {
                        
                    jQuery("#megawidthvalue_" + id).parents("tr").show();
                }
            }
        ';
    }

    public function getHeaderText()
    {
        return Mage::registry('designer_settings_theme') . ' ' . $this->__('theme');
    }
}

//