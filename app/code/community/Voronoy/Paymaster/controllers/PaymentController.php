<?php

/**
 * Magento PayMaster Extension.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (c) 2015 by Yaroslav Voronoy (y.voronoy@gmail.com)
 * @license   http://www.gnu.org/licenses/
 */
class Voronoy_Paymaster_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function redirectAction()
    {
        $block = $this->getLayout()->createBlock('voronoy_paymaster/method_form', 'paymaster_redirect',
            array('template' => 'paymaster/redirect.phtml'));
        $this->getResponse()->setBody($block->toHtml());
    }

    public function resultAction()
    {
        $requestData = Mage::app()->getRequest()->getParams();
        $request = Mage::getModel('voronoy_paymaster/request');
        $request->setData($requestData);

        if ($request->getData(Voronoy_Paymaster_Model_Request::FIELD_NAME_PREREQUEST)) {
            $this->_preRequest($request);
        } else {
            $this->_paymentNotify($request);
        }
    }

    /**
     * @param Voronoy_Paymaster_Model_Request $request
     */
    protected function _paymentNotify($request)
    {
        $order = $request->getOrder();
        $order->getPayment()->getMethodInstance()->debugData($request->getData());
        try {
            if (!$request->validateHash($request->getData(Voronoy_Paymaster_Model_Request::FIELD_NAME_HASH))) {
                Mage::throwException(Mage::helper('voronoy_paymaster')->__('Invalid Hash'));
            }
            if ($order->canInvoice()) {
                $invoice = $order->prepareInvoice();
                $invoice->register();
                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                $transactionSave->save();

                $order->setStatus(Mage::helper('voronoy_paymaster')->getOrderState());
                $order->addStatusHistoryComment(
                    Mage::helper('voronoy_paymaster')->__('Payment confirmed by PayMaster'));
                $order->save();
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $this->getResponse()->setHeader('HTTP/1.1', '503 Service Unavailable')->sendResponse();
        }
    }

    /**
     * @param Voronoy_Paymaster_Model_Request $request
     */
    protected function _preRequest($request)
    {
        $order = $request->getOrder();
        $paymentMethod = $order->getPayment()->getMethodInstance();
        $paymentMethod->debugData($request->getData());
        if ($paymentMethod->validateRequest($request)) {
            echo 'YES';
            exit;
        }
    }

    public function successAction()
    {
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $this->_redirect('checkout/onepage/success', array('_secure' => true));
    }

    public function failAction()
    {
        $session = Mage::getSingleton('checkout/session');
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
            Mage::helper('voronoy_paymaster')->restoreQuote($quote);
        }

        $session->addError(Mage::helper('voronoy_paymaster')->__('Payment was fail. Please try again later.'));
        $this->_redirect('checkout/cart');
    }
}
