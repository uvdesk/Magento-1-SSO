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
class Webkul_Sso_Block_Adminhtml_Sso_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "sso";
        $this->_controller = "adminhtml_sso";
        $fieldid = $this->getRequest()->getParam("id");
        $this->_updateButton('save', 'label', Mage::helper('sso')->__('Save SSO'));
        $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
        $this->_updateButton("delete", "label", $this->__("Delete SSO"));
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        $model = Mage::helper('sso')->getSsoInstance();
        if ($model->getId()) {
            $this->_removeButton('reset');
            return Mage::helper('sso')->__("Edit SSO ", $this->escapeHtml($model->getName()));
        } else {
            return Mage::helper('sso')->__('New SSO');
        }
    }
}
