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
class Webkul_Sso_Model_Sso extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {         
        $this->_init('sso/sso');
    }
}