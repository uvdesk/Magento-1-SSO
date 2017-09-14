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
class Webkul_Sso_Block_Adminhtml_Sso extends Mage_Adminhtml_Block_Widget_Grid_Container
{	
	public function __construct() {
	        $this->_controller = "adminhtml_sso";
	        $this->_blockGroup = "sso";
	        $this->_headerText = $this->__("SSO Management");
	        $this->_addButtonLabel = $this->__("Add Sso");
	        parent::__construct();
	    }
}