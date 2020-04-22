<?php
//

class Billiondigital_Themler_Adminhtml_ThemeController extends Mage_Core_Controller_Front_Action
{
    public $_publicActions = array('index');

    public function indexAction()
    {
        $autoLogin = $this->getRequest()->getParam('auto_login', '');
        $login = $this->getRequest()->getParam('login', '');
        $pwd = $this->getRequest()->getParam('pwd', '');
        $startup = $this->getRequest()->getParam('startup');
        $desktop = $this->getRequest()->getParam('desktop');
        $domain = $this->getRequest()->getParam('domain');
        $theme = $this->getRequest()->getParam('theme');
        $version = Billiondigital_Themler_Model_Manifest::getThemeVersion($theme);

        $redirectParams = array('theme' => $theme);

        if ($domain) {
            $redirectParams['domain'] = $domain;
        }

        if ($startup) {
            $redirectParams['startup'] = $startup;
        }

        if ($desktop) {
            $redirectParams['desktop'] = $desktop;
        }

        if ($version) {
            $redirectParams['ver'] = $version;
        }

        if ($login) {
            $redirectParams['login'] = $login;
        }

        if ($pwd) {
            $redirectParams['pwd'] = $pwd;
        }

        if ($autoLogin) {
            $redirectParams['auto_login'] = $autoLogin;
        }

        $adminUrl = Mage::helper('core/url')->addRequestParam(
            Mage::helper('adminhtml')->getUrl('adminhtml/billionthemler_theme/index'),
            $redirectParams
        );

        $this->getResponse()->setRedirect($adminUrl);
    }

}

//