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
require_once(Mage::getBaseDir('lib')."/Firebase/JWT/JWT.php");
class Webkul_Sso_SsoController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $clientId=$this->getRequest()->getParam("client_id");
        $model=Mage::getModel('sso/sso');
        $model->load($clientId, "client_id");
        if ($model->getId()) {
            $redirectUri=$this->getRequest()->getParam("redirect_uri");
            if ($redirectUri=="" || empty($redirectUri)) {
                Mage::getSingleton("core/session")->setSsoClientId(false);
                Mage::getSingleton("core/session")->addError("Invalid redirect URI");
            } else {
                Mage::getSingleton("core/session")->setSsoClientId($clientId);
                Mage::getSingleton("core/session")->setRedirectUri($redirectUri);
            }
        } else {
            Mage::getSingleton("core/session")->setSsoClientId(false);
            $redirectPath   = '/index.php';
        }
        if(!$redirectPath){
            $redirectPath   = '*/*/checked';
        }
        $this->_redirect($redirectPath);
    }

    public function checkedAction()
    {
        if (!Mage::getSingleton("core/session")->getSsoClientId()) {
            $redirectPath   = '*/*/index';
            $this->_redirect($redirectPath);
        }
        if (Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getSingleton("core/session")->getSsoClientId()!= false) {
            $redirectPath   = '*/*/decide';
            $this->_redirect($redirectPath);
        }
        $this->loadLayout()->renderLayout();
    }
    public function loginAction()
    {
        if (!Mage::getSingleton("core/session")->getSsoClientId()) {
            $redirectPath   = '*/*/index';
            $this->_redirect($redirectPath);
        }
        $data=$this->getRequest()->getParams();
        $email = $data['email'];
        $password = $data['password'];


        try {
            $check=Mage::getModel('customer/customer') ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                                                ->authenticate($email, $password);
            if ($check==1) {
                $customer=Mage::getModel('customer/customer');
                $customer->website_id = Mage::app()->getStore()->getWebsiteId();
                $customer->setStore(Mage::app()->getStore());
                $customer->loadByEmail($email);
                $session = Mage::getSingleton('customer/session');
                $session->setCustomer($customer);
            }
            $redirectPath   = '*/*/decide';
        } catch (Exception $e) {
            Mage::getSingleton("core/session")->addError("Invalid Email and/or Password");
            $redirectPath   = '*/*/checked';
        }
        $this->_redirect($redirectPath);
    }

    public function decideAction()
    {
        if (!Mage::getSingleton("core/session")->getSsoClientId()) {
            $redirectPath   = '*/*/index';
            $this->_redirect($redirectPath);
        }
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $redirectPath   = '*/*/checked';
            $this->_redirect($redirectPath);
        }
        $clientId=Mage::getSingleton("core/session")->getSsoClientId();
        $ssoModel = Mage::getModel('sso/sso');
        $ssoModel->load($clientId, "client_id");
        $client= $ssoModel->getName();
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        Mage::getSingleton("core/session")->addNotice($client." will receive your basic profile info.");
        $this->loadLayout()->renderLayout();
    }

    public function authAction()
    {
        if (!Mage::getSingleton("core/session")->getSsoClientId()) {
            $redirectPath   = '*/*/index';
            $this->_redirect($redirectPath);
        }
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $redirectPath   = '*/*/checked';
            $this->_redirect($redirectPath);
        }
        $model=Mage::getModel('sso/ssoauth');
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $model->setAuthCode($token);
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $model->setName($customer->getName());
        $model->setEmail($customer->getEmail());
        $ipAddress=Mage::helper('core/http')->getRemoteAddr();
        $model->setIpAddress($ipAddress);
        $model->save();
        $redirectPath   = Mage::getSingleton("core/session")->getRedirectUri();
        $redirectPath=parse_url($redirectPath, PHP_URL_SCHEME) === null ? "http://" . $redirectPath : $redirectPath;
        $redirectPath.="?auth_code=".$token."&client_id=".Mage::getSingleton("core/session")->getSsoClientId();
        Mage::getSingleton("core/session")->setSsoClientId(false);        
        Mage::app()->getResponse()->setRedirect($redirectPath)
                                    ->sendResponse();
    }

    public function cancelAction()
    {
        if (!Mage::getSingleton("core/session")->getSsoClientId()) {
            $redirectPath   = '*/*/index';
            $this->_redirect($redirectPath);
        }
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            $redirectPath   = '*/*/checked';
            $this->_redirect($redirectPath);
        }
        $clientId=Mage::getSingleton("core/session")->getSsoClientId();
        Mage::getSingleton("core/session")->setSsoClientId(false);
        $ssoModel = Mage::getModel('sso/sso');
        $ssoModel->load($clientId, "client_id");
        if ($ssoModel->getCancelUrl()!=null) {
            $cancelUrl=$ssoModel->getCancelUrl();
            $redirectPath   = $cancelUrl;
            $redirectPath=parse_url($redirectPath, PHP_URL_SCHEME) === null ? "http://" . $redirectPath : $redirectPath;
            Mage::app()->getResponse()->setRedirect($redirectPath)
                                    ->sendResponse();
        } else {
            $redirectPath   = '/index';
            $this->_redirect($redirectPath);
        }
    }

    public function responseAction()
    {
        $response = [];
        $this->jwt = new Firebase\JWT\JWT();
        $data = $this->getRequest()->getParams();
        $clientId = $data['client_id'];
        $ssoModel = Mage::getModel('sso/sso');
        $ssoModel->load($clientId, "client_id");
        if ($ssoModel->getId()) {
            $authCode = $data['auth_code'];
            $authModel = Mage::getModel('sso/ssoauth');
            $authModel->load($authCode, "auth_code");
            if ($authModel->getId()) {
                $secretKey=$ssoModel->getSecretKey();
                if ($secretKey="") {
                    $response['error'] = "Invalid key";
                } else {
                    $payload = array(
                                "exp" => strtotime("+2 minutes"),
                                "email" => $authModel->getEmail(),
                                "name" => $authModel->getName()
                            );
                    $jwt = $this->jwt->encode($payload, $secretKey);
                    $response['accessToken'] = $jwt;
                    $authModel->delete();
                }
            } else {
                $response['error'] = "Invalid authorization code provided";
            }
        } else {
            $response['error'] = "Invalid client id token provided";
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode(['response' => $response]));
    }

    public function verifyAction()
    {
        $response = [];
        $data = $this->getRequest()->getParams();
        $clientId = $data['client_id'];
        $ssoModel = Mage::getModel('sso/sso');
        $ssoModel->load($clientId, "client_id");
        if ($ssoModel->getId()) {
            $secretKey = $data['secret_key'];
            if ($secretKey == $ssoModel->getSecretKey()) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = "Invalid credentials";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Invalid credentials";
        }
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode(['response' => $response]));
    }
}
