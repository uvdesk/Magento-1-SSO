<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Sso
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
class Webkul_Sso_Helper_Data extends Mage_Core_Helper_Data
{
    protected $_ssoInstance;
    
    public function getSsoInstance()
    {
        if (!$this->_ssoInstance) {
            $this->_ssoInstance = Mage::registry('sso');

            if (!$this->_ssoInstance) {
                Mage::throwException($this->__('Sso instance does not exist in Registry'));
            }
        }

        return $this->_ssoInstance;
    }
    
}