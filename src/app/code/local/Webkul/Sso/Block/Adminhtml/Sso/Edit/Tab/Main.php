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
class Webkul_Sso_Block_Adminhtml_Sso_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        $model = Mage::helper('sso')->getSsoInstance();

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('sso_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('sso')->__('Sso Info')
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'class'    =>'validate-alpha-with-spaces',
            'label'    => Mage::helper('sso')->__('Name'),
            'title'    => Mage::helper('sso')->__('Name'),
            'required' => true,
        ));


        $fieldset->addField('client_id', 'text', array(
            'name'     => 'client_id',
            'label'    => Mage::helper('sso')->__('Client Id'),
            'title'    => Mage::helper('sso')->__('Client Id'),
            'disabled' => true,
            'style'    => 'background-color:#efefef'
        ));

        $fieldset->addField('secret_key', 'text', array(
            'name'     => 'secret_key',
            'label'    => Mage::helper('sso')->__('Secret Key'),
            'title'    => Mage::helper('sso')->__('Secret Key'),
            'disabled' => true,
            'style'    => 'background-color:#efefef'
        ));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return Mage::helper('sso')->__('SSO Info');
    }

    public function getTabTitle()
    {
        return Mage::helper('sso')->__('SSO Info');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
