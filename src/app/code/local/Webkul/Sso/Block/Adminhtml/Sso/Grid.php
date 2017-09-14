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
class Webkul_Sso_Block_Adminhtml_Sso_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sso_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel("sso/sso")->getResourceCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
            "header"    => $this->__("ID"),
            "align"     => "center",
            "width"     => "30px",
            "index"     => "id"
        ));

        $this->addColumn("name", array(
            "header"    => $this->__("Name"),
            "index"     => "name"
        ));

        $this->addColumn("url", array(
            "header"    => $this->__("Url"),
            "index"     => "url"
        ));

        $this->addColumn("email", array(
            "header"    => $this->__("Email"),
            "index"     => "email"
        ));

        $this->addColumn("client_id", array(
            "header"    => $this->__("Client Id"),
            "index"     => "client_id"
        ));

        $this->addColumn("secret_key", array(
            "header"    => $this->__("Secret Key"),
            "index"     => "secret_key"
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('sso_id');
        $this->getMassactionBlock()->addItem('delete', array(
                                                'label'=> Mage::helper('sso')->__('Delete'),
                                                'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
                                                'confirm' => Mage::helper('sso')->__('Are you sure?')
                                    ));
 
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl("*/*/grid", array("_current"=>true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }
}
