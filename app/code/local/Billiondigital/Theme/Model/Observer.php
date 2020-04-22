<?php
//

class Billiondigital_Theme_Model_Observer
{
    /**
     * controller_action_predispatch
     */
    public function initTemplateHelper(Varien_Event_Observer $observer)
    {
        Mage::unregister('templateHelper');
        Mage::register('templateHelper', Mage::helper('billiontheme'));
    }

    /**
     * cms_page_render
     */
    public function applyCmsPageTemplate(Varien_Event_Observer $observer)
    {
        $action = $observer->getEvent()->getControllerAction();

        $actionName = strtolower($action->getFullActionName());
        $action->getLayout()->getUpdate()->addHandle($actionName . '_after');
    }

    /**
     * controller_action_layout_render_before
     */
    public function applyMessagesTemplate(Varien_Event_Observer $observer)
    {
        $layout = Mage::app()->getLayout();

        if ($block = $layout->getMessagesBlock()) {
            $messages = $block->getMessageCollection();
            $layout->unsetBlock('messages');
            $layout->addBlock('billiontheme/messages', 'messages')->setMessages($messages);
        }

        if ($globalMessage = $layout->getBlock('global_messages')) {
            $messages = $globalMessage->getMessageCollection();
            $layout->unsetBlock('global_messages');
            $layout->addBlock('billiontheme/messages', 'global_messages')->setMessages($messages);
        }
    }

    public function applyCustomTemplate()
    {
        $template = Mage::registry('customTemplate');
        $layoutParam = Mage::app()->getRequest()->getParam('layout');
        if ($template && !$layoutParam) {
            Mage::app()->getLayout()->getBlock('root')->setTemplate($template);
        }
    }

    public function getCustomProductTemplate(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        $tpl = Mage::helper('billiontheme/template')->findTemplate(
            Mage::helper('billioncore')->getThemeName(),
            'product-overview',
            array(
                'products' => (int) $product->getId(),
                'categories' => (int) $product->getCategoryId()
            )
        );

        if ($tpl) {
            $fileName = sprintf(
                'designer/layouts/%s/template-%d.phtml',
                $tpl['type'], $tpl['id']
            );
            Mage::register('customTemplate', $fileName);
        }
    }

    public function getCustomCategoryTemplate(Varien_Event_Observer $observer)
    {
        $category = $observer->getEvent()->getCategory();

        $info = Mage::helper('billiontheme/template')->findTemplate(
            Mage::helper('billioncore')->getThemeName(),
            'products',
            array('categories' => (int) $category->getId())
        );

        if ($info) {
            $template = sprintf(
                'designer/layouts/%s/template-%d.phtml',
                $info->getType(), $info->getId()
            );
            Mage::register('customTemplate', $template);
        }
    }

    public function getCustomPageTemplate(Varien_Event_Observer $observer)
    {
        $page = $observer->getEvent()->getPage();
        $action = $observer->getEvent()->getControllerAction();
        $theme = Mage::helper('billioncore')->getThemeName();
        $info = null;
        $helper = Mage::helper('billiontheme/template');

        switch ($action->getFullActionName()) {
            case 'cms_index_index':
                $info = $helper->findTemplate($theme, 'home');
                break;
            case 'cms_index_noRoute':
                $info = $helper->findTemplate($theme, 'noRoute');
                break;
            case 'cms_page_view':
                $info = $helper->findTemplate(
                    $theme, 'page', array('pages' => (int) $page->getId())
                );
                break;
        }

        if ($info) {
            $template = sprintf(
                'designer/layouts/%s/template-%d.phtml',
                $info->getType(), $info->getId()
            );
            Mage::register('customTemplate', $template);
        }
    }

}

//