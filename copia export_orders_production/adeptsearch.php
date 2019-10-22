<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Arthur Cai
 * @copyright 2018 Adeptmind
 * @license   LICENSE
 */

include_once _PS_MODULE_DIR_ . 'adeptsearch/classes/util.php';
include_once _PS_MODULE_DIR_ . 'adeptsearch/classes/product_util.php';
include_once _PS_MODULE_DIR_ . 'adeptsearch/classes/shop.php';
include_once _PS_MODULE_DIR_ . 'adeptsearch/sql/AdeptSearchRescrapeQueue.php';

class AdeptSearch extends Module
{
    const CLASS_NAME = 'AdeptSearch';
    const UI_DOMAIN = 'shopify-app-ui.adeptmind.ai';

    private $hooks = array(
        'actionProductDelete',
        'actionProductSave',
        'displayBackOfficeHeader',
        'displayDashboardToolbarTopMenu',
        'displayMobileTopSiteMap',
        'displayHeader',
        'displayHome',
        'displayNav',
        'displayNav2',
        'displaySearch',
        'header',
    );

    public function __construct()
    {
        $this->name = 'adeptsearch';
        $this->author = 'Adeptmind';
        $this->version = "3.6.0"; // Keep double quote for pattern matching on release
        $this->description = 'Adeptmind is the first deep learning and natural language search solution on Prestashop.';
        $this->tab = 'search_filter';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Adeptmind AI Search and Filters');

        $this->ps_versions_compliancy = array(
            'min' => '1.6.0.0',
            'max' => _PS_VERSION_
        );
        $this->module_key = 'b9ff2a32bd59e81618f9a00840fc2510';
    }

    public function install()
    {
        Configuration::updateValue(
            'ADEPTMIND_SHOP_TOKEN',
            'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJwbGF0Zm9ybSIsInN1YiI6IjZkOGVkOGM3LTQwMT'.
            'ctNDA2NC04YTQzLWI5ZjI1ZTNhNDFkYSIsImlhdCI6MTU0NTA2ODkzNywiZXhwIjoxNTc2NjI2NTM3LCJpc3MiO'.
            'iJodHRwczovL3BsYXRmb3JtLWF1dGguYWRlcHRtaW5kLmFpIn0.NY5Jd267M_4A_xrBElgyQN71UXItVMv6iKJV'.
            'Az0Knew4qB3-bBP0DCrWuRQJuhBQZdT4Vj6teaQiw8yakZTHeUPJaebTa7K3P-RsYiR49EExyaYk96t0QbMVPwk'.
            'Y5XSKln2NZg2i69KNpFBskygwqel88r8v84GkISkLYuXfSUdRyPLQg7HxSbGjMMW97w4pCJiF5Aru4Bi0wfIwTD'.
            'f-2TeISekG0Ripl3BSJqgnGvhCbbx9mfmje2gB4S1Cf5rO_IGwG6hboGWAzewkGvvmja2Is-urBLvdOyVfjPGhK'.
            'V-p486c7M4-RKfJ1Akal3oYekBCdCq-p9dlhjmVUvqWZA'
        );

        return AdeptShop::install($this->getInstallData()) &&
            AdeptSearchRescrapeQueue::install() &&
            parent::install() &&
            $this->registerControllers() &&
            !$this->registerHooks();
    }

    public function uninstall()
    {
        try {
            AdeptUtil::callPlatformManagerDelete('/shops/'.Configuration::get('ADEPTMIND_SHOP_UUID'));
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_UNINSTALL_FAILED', $e);
        }
        Configuration::deleteByName('ADEPTMIND_SHOP_PUBLIC_TOKEN');
        Configuration::deleteByName('ADEPTMIND_SHOP_TOKEN');
        Configuration::deleteByName('ADEPTMIND_SHOP_UUID');
        Configuration::deleteByName('ADEPTMIND_IMPORT_PROCESS_ID');
        $id_tab = (int) Tab::getIdFromClassName('AdeptInstall');
        if ($id_tab) {
            (new Tab($id_tab))->delete();
        }

        return parent::uninstall() && AdeptSearchRescrapeQueue::uninstall();
    }

    public function disable()
    {
        $result = parent::disable();
        try {
            AdeptUtil::callPlatformManagerPost(
                '/shops/'.Configuration::get('ADEPTMIND_SHOP_UUID').'/toggle',
                array('is_active' => false)
            );
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DISABLE_FAILED', $e);
        }
        return $result;
    }

    public function enable()
    {
        if (!parent::enable()) {
            return false;
        }
        try {
            AdeptUtil::callPlatformManagerPost(
                '/shops/'.Configuration::get('ADEPTMIND_SHOP_UUID').'/toggle',
                array('is_active' => true)
            );
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_ENABLE_FAILED', $e);
        }
        return true;
    }

    protected function getInstallData()
    {
        $owner_name = $this->context->employee->firstname . ' ' . $this->context->employee->lastname;
        $owner_email = $this->context->cookie->email;
        return array(
            'shop' => _PS_BASE_URL_,
            'module_version' => $this->version,
            'db_version' => Db::getInstance()->getVersion(),
            'platform' => 'prestashop',
            'name' => AdeptUtil::getSlug(Configuration::get('PS_SHOP_NAME')),
            'owner' => $owner_name,
            'email' => $owner_email,
            'language' => $this->context->language->iso_code,
            'rescrape_link' => $this->getRescrapeLink(),
            'total_product_count' => AdeptProductUtil::getTotalProductCount(),
            'country' => $this->context->country->iso_code,
            'city' => Configuration::get('PS_SHOP_CITY'),
        );
    }

    protected function getRescrapeLink()
    {
        $pattern = '/^172\.16\.|^192\.168\.|^10\.|^127\.|^localhost|\.local$/';
        if (isset($_SERVER['REMOTE_ADDR']) === false ||
            in_array(Tools::getRemoteAddr(), array('127.0.0.1', '::1')) ||
            preg_match($pattern, Configuration::get('PS_SHOP_DOMAIN'))
        ) {
            return null;
        }
        $link = $this->context->link->getModuleLink('adeptsearch', 'install');
        return $link;
    }

    private function registerControllers()
    {
        $install_tab = new Tab();
        $install_tab->active = 1;
        $install_tab->class_name = 'AdeptInstall';
        $install_tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $install_tab->name[$lang['id_lang']] = $this->l('AdeptInstall');
        }
        $install_tab->id_parent = -1;
        $install_tab->module = $this->name;
        $install_tab->add();
        return true;
    }

    private function registerHooks()
    {
        $is_failure = false;

        foreach ($this->hooks as $hook) {
            if ($is_failure) {
                break;
            }

            $is_failure = !$this->registerHook($hook);
        }
        return $is_failure;
    }

    public function sendCallback()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        ob_start();
        header('Connection: close');
        header('Content-Length: '.ob_get_length());
        header('Content-Type: application/json');
        echo '{}';
        ob_end_flush();
        ob_flush();
        flush();
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

    public function hookActionProductDelete($params)
    {
        try {
            AdeptProductUtil::updateProduct($params['product'], true);
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DELETE_HOOK_FAILED', $e);
        }
    }

    public function hookActionProductSave($params)
    {
        try {
            AdeptProductUtil::updateProduct($params['product']);
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_UPDATE_HOOK_FAILED', $e);
        }
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        try {
            if (Tools::getValue('configure') !== $this->name) {
                return;
            }
            $this->context->controller->addCss(
                array(
                    $this->_path.'views/css/styles.css',
                    $this->_path.'views/css/toastify.css'
                )
            );
            $this->context->controller->addJs(
                array(
                    $this->_path.'views/js/platform-adapter-v1.0.0.js',
                    $this->_path.'views/js/toastify.js',
                    $this->_path.'views/js/admin.js'
                )
            );
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DISPLAY_BACKOFFICE_HEADER_FAILED', $e);
        }
    }

    public function hookDisplayHeader($params)
    {
        try {
            $shop = AdeptShop::getShop();
            $isActive = isset($shop['is_active']) && $shop['is_active'];
            if ($isActive && isset($shop['js_path'])) {
                if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                    $this->context->controller->registerJavascript(
                        'adept-js',
                        $shop['js_path'],
                        array('server' => 'remote')
                    );
                } else {
                    $this->context->controller->addJS($shop['js_path']);
                }
                Media::addJsDef(array(
                    'ADEPT_SHOP_PUBLIC_TOKEN' => Configuration::get('ADEPTMIND_SHOP_PUBLIC_TOKEN'),
                ));
            }
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DISPLAY_HEADER_FAILED', $e);
        }
    }

    public function hookDisplayMobileTopSiteMap($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-mobile-top');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayNav($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-nav');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayNav1($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-nav');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayNav2($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-nav');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayTop($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-top');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayLeftColumn($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-left-column');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplayRightColumn($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-right-column');
        return $this->searchBoxDisplayHook($params);
    }

    public function hookDisplaySearch($params)
    {
        $this->context->smarty->assign('positionClass', 'adept-search-box__display-search');
        return $this->searchBoxDisplayHook($params);
    }

    private function searchBoxDisplayHook()
    {
        try {
            $shop = AdeptShop::getShop();
            if ($shop['is_active']) {
                $customizations = AdeptShop::getCustomizations();
                $this->context->smarty->assign('placeholderText', 'Search');
                foreach ($customizations as $key => $value) {
                    $this->context->smarty->assign(AdeptUtil::snakeToCamelCase($key), $value);
                }
                $this->context->smarty->assign(
                    'SEARCH_URL',
                    $this->context->link->getModuleLink('adeptsearch', 'query')
                );
                return $this->display(__FILE__, 'searchbox.tpl');
            }
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DISPLAY_HOOK_FAILED', $e);
        }
    }

    public function validateFields()
    {
    }

    public function formatFields()
    {
        return json_encode($this->fields);
    }

    private function updateToken()
    {
        try {
            $new_token = AdeptUtil::callPlatformManagerGet(
                '/getShopToken',
                array('shop' => _PS_BASE_URL_)
            );
            Configuration::updateValue('ADEPTMIND_SHOP_TOKEN', $new_token);
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_FETCH_TOKEN_FAILED', $e);
        }
    }

    public function getContent()
    {
        try {
            $this->updateToken();
            $iframe_src = sprintf('https://%s/?shop=%s', self::UI_DOMAIN, _PS_BASE_URL_);
            $install_script_url = $this->context->link->getAdminLink('AdeptInstall');
            $this->context->smarty->assign(
                array(
                    'ADEPT_IFRAME_SRC' => $iframe_src,
                )
            );
            Media::addJsDef(array(
                'ADEPT_INSTALL_SCRIPT_URL' => $install_script_url,
                'ADEPT_SHOP_TOKEN' => Configuration::get('ADEPTMIND_SHOP_TOKEN'),
            ));
            $this->context->smarty->assign('ADEPT_BASE_URI', __PS_BASE_URI__);
            return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        } catch (Exception $e) {
            AdeptShop::reportException('PRESTASHOP_DISPLAY_BACKOFFICE_FAILED', $e);
        }
    }
}
