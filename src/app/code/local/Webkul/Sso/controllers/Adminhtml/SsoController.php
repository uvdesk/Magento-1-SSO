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
class Webkul_Sso_Adminhtml_SsoController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/sso/sso');
    }
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("sso")->_addBreadcrumb($this->__("Sso Manager"), $this->__("Sso Manager"));
        $this->getLayout()->getBlock("head")->setTitle($this->__("SSO Management"));
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('SSO'))
             ->_title($this->__('Manage SSO'));

        $model = Mage::getModel('sso/sso');

        $ssoId = $this->getRequest()->getParam('id');
        if ($ssoId) {
            $model->load($ssoId);

            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('sso')->__('SSO does not exist.')
                );
                return $this->_redirect('*/*/');
            }
            $this->_title($model->getTitle());
            $breadCrumb = Mage::helper('sso')->__('Edit SSO');
        } else {
            $this->_title(Mage::helper('sso')->__('New SSO'));
            $breadCrumb = Mage::helper('sso')->__('New SSO');
        }

        $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('sso', $model);

        $this->renderLayout();
    }

    public function saveAction()
    {
        $redirectPath   = '*/*';
        $redirectParams = array();

        $data = $this->getRequest()->getPost();
        if ($data) {
            $model = Mage::getModel('sso/sso');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            } else {
                $clientId=md5(uniqid(rand(), true));
                $data['client_id'] = $clientId;
                $secretKey=md5(uniqid(rand(), true));
                $data['secret_key'] = $secretKey;
            }
            $model->addData($data);
            
            try {
                $model->save();

                $this->_getSession()->addSuccess(
                    Mage::helper('sso')->__('The SSO has been saved.')
                );

                if ($this->getRequest()->getParam('back')) {
                    $redirectPath   = '*/*/edit';
                    $redirectParams = array('id' => $model->getId());
                }
            } catch (Mage_Core_Exception $e) {
                $hasError = true;
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $hasError = true;
                $this->_getSession()->addException($e,
                    Mage::helper('sso')->__('An error occurred while saving the SSO.')
                );
            }

            if ($hasError) {
                $this->_getSession()->setFormData($data);
                $redirectPath   = '*/*/edit';
                $redirectParams = array('id' => $this->getRequest()->getParam('id'));
            }
        }

        $this->_redirect($redirectPath, $redirectParams);
    }

    public function deleteAction()
    {
        $itemId = $this->getRequest()->getParam('id');
        if ($itemId) {
            try {
                $model = Mage::getModel('sso/sso');
                $model->load($itemId);
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('sso')->__('Unable to find SSO.'));
                }
                $model->delete();

                $this->_getSession()->addSuccess(
                    Mage::helper('sso')->__('The SSO has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('sso')->__('An error occurred while deleting the SSO.')
                );
            }
        }

        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $ssoIds = $this->getRequest()->getParam('sso_id');
        if (!is_array($ssoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('sso')->__('Please select sso(es).'));
        } else {
            try {
                $ssoModel = Mage::getModel('sso/sso');
                foreach ($ssoIds as $ssoId) {
                    $ssoModel->load($ssoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('sso')->__('Total of %d record(s) were deleted.', count($ssoIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
 
        $this->_redirect('*/*/index');
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
