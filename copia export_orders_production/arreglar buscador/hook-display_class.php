<?php
/**
 * hook-display_class.php file defines controller which manage hooks sequentially
 */

class BT_GRHookDisplay extends BT_GRHookBase
{
	/**
	 * @var string $sHookType : define hook type
	 */
	protected $sHookType = null;

	/**
	 * @var bool $bAlreadyExecute : define if one hook is already executed
	 */
	protected static $bAlreadyExecute = false;

	/**
	 * Magic Method __construct assigns few information about hook
	 *
	 * @param string
	 */
	public function __construct($sHookType)
	{
		// set hook type
		$this->sHookType = $sHookType;
	}

	/**
	 * Magic Method __destruct
	 *
	 * @category hook collection
	 *
	 */
	public function __destruct()
	{
		//unset($this);
	}

	/**
	 * run() method execute hook
	 *
	 * @param array $aParams
	 * @return array
	 */
	public function run(array $aParams = null)
	{
		// set variables
		$aDisplayHook = array();

		switch ($this->sHookType) {
			case 'orderConfirmation' : // use case - order confirmation page
			case 'footer' : // use case - display footer
				$aDisplayHook = call_user_func_array(array($this, '_display' . ucfirst($this->sHookType)), array($aParams));
				break;
			default :
				break;
		}

		return $aDisplayHook;
	}

	/**
	 * _displayOrderConfirmation() method display order confirmation page's content
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function _displayOrderConfirmation(array $aParams = null)
	{
		// detect if PS 1.6
		if (!empty(GRemarketing::$bCompare16)) {
			$bOrderConfPage = GRemarketing::$aConfiguration[_GR_MODULE_NAME . '_TAG_ON_ORDER_PAGE'];

			if (empty($bOrderConfPage) && !empty(GRemarketing::$bCompare16)) {
				self::$bAlreadyExecute = true;
			}
		}

		$aContent = $this->_displayFooter(array('sPageType' => 'purchase'));

		self::$bAlreadyExecute = true;

		return $aContent;
	}


	/**
	 * _displayFooter() method display footer
	 *
	 * @param array $aParams
	 * @return array
	 */
	private function _displayFooter(array $aParams = null)
	{
		// set
		$aAssign = array();

		// detect if one hook already has been executed like order confirmation hook
		if (empty(self::$bAlreadyExecute)) {
			$aAssign['iGoogleId'] = (int)GRemarketing::$aConfiguration[_GR_MODULE_NAME . '_REMARKETING_ID'];
			$aAssign['sGoogleJsInclude'] = BT_GRModuleTools::getTemplatePath(_GR_PATH_TPL_NAME . _GR_TPL_GOOGLE_JAVASCRIPT);
			$aAssign['bPS16'] = false;

			// set output
			$aOutPut = array();

			// check the 1.6.0.8 version
			if (version_compare(_PS_VERSION_, '1.6.0.8', '>=')
				&& Configuration::get('PS_JS_DEFER') == false
			) {
				GRemarketing::$bCompare16 = false;
			}

			// if dynamic activated
			if (!empty(GRemarketing::$aConfiguration[_GR_MODULE_NAME . '_REMARKETING_DYNAMIC'])
				&& !empty($aAssign['iGoogleId'])
			) {
				// include base class of dynamic tags
				require_once(_GR_PATH_LIB_DYN_TAGS . 'base-dynamic-tags_class.php');

				// force page type => use case for order confirmation page with PayPal order
				// detect page
				$sPageType = (!empty($aParams['sPageType'])? $aParams['sPageType'] : BT_GRModuleTools::detectCurrentPage());

				// detect if GMC PRO is installed
				$oGMCPro = BT_GRModuleTools::isInstalled('gmerchantcenterpro', array(), true);
				$oGMC = BT_GRModuleTools::isInstalled('gmerchantcenter', array(), true);

				// set prefix module name for Google
				$sPrefixGmcName = (!empty($oGMCPro)? 'GMCP' : 'GMERCHANTCENTER');

				// set dyn tags params
				$aDynTags = array(
					'iProductId'    => Tools::getvalue('id_product'),
					'iCategoryId'   => Tools::getvalue('id_category'),
					'bUseTax'       => (Configuration::get('PS_TAX') == 1? true: false),
					'bOneProduct'   => (Configuration::get($sPrefixGmcName . '_P_COMBOS') == 1? true : false),
					'oGMC'          => (!empty($oGMCPro)? $oGMCPro : $oGMC),
					'sPrefixName'   => (!empty($oGMCPro)? 'GMCP' : 'GMC'),
					'sGmcPrefix'    => Configuration::get($sPrefixGmcName . '_ID_PREFIX'),
					'sGooglePrefix' => GRemarketing::$aConfiguration[_GR_MODULE_NAME . '_GOOGLE_PREFIX'],
					'iCartId'       => (Tools::getvalue('id_cart') != false? Tools::getvalue('id_cart') : (!empty(GRemarketing::$oCookie->id_cart)? GRemarketing::$oCookie->id_cart : null)),
				);
				unset($oGMCPro);
				unset($oGMC);
				unset($sPrefixGmcName);

				try {
					// get current dynamic tags
					$oTagsCtrl = BT_BaseDynTags::get($sPageType, $aDynTags);

					$oTagsCtrl->set();

					// assign customized tags
					if ($oTagsCtrl->bValid) {
						$aAssign['aDynTags'] = $oTagsCtrl->display();
						$aAssign['sCR']      = "\n";

						// add JS Definition for PS 1.6 according to their defer Inline JS function
						if (!empty(GRemarketing::$bCompare16) && !empty($aAssign['aDynTags'])) {
							foreach ($aAssign['aDynTags'] as $iIndex => $aParams) {
								$aOutPut[$aParams['label']] = str_replace('\'', '', $aParams['value']);
							}

							// add google tags params
							Media::addJsDef(array('google_tag_params' => $aOutPut));
						}
					}
				}
				catch (Exception $e) {
					//@TODO: nothing if exception is caught
				}
			}
			// add JS inline for PS 1.6
			if (!empty(GRemarketing::$bCompare16) && !empty($aAssign['iGoogleId'])) {
				// set other google tags
				Media::addJsDef(array('google_conversion_id' => $aAssign['iGoogleId']));
				Media::addJsDef(array('google_remarketing_only' => true));
				Media::addJsDef(array('google_custom_params' => $aOutPut));

				$aAssign['bPS16'] = true;
			}
		}
		else {
			$aAssign['iGoogleId'] = 0;
		}

		return array('tpl' => _GR_TPL_HOOK_PATH . _GR_TPL_FOOTER, 'assign' => $aAssign);
	}
}