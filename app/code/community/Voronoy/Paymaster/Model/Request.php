<?php
/**
 * Magento PayMaster Extension
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

class Voronoy_Paymaster_Model_Request extends Varien_Object
{
    const FIELD_NAME_MERCHANT_ID    = 'LMI_MERCHANT_ID';
    const FIELD_NAME_PAYMENT_AMOUNT = 'LMI_PAYMENT_AMOUNT';
    const FIELD_NAME_PAYMENT_NO     = 'LMI_PAYMENT_NO';
    const FIELD_NAME_PAYMENT_DESC   = 'LMI_PAYMENT_DESC';
    const FIELD_NAME_SUCCESS_URL    = 'LMI_SUCCESS_URL';
    const FIELD_NAME_FAIL_URL       = 'LMI_FAIL_URL';
    const FIELD_NAME_PAYMENT_SYSTEM = 'LMI_PAYMENT_SYSTEM';
    const FIELD_NAME_HASH           = 'LMI_HASH';
    const FIELD_NAME_PAYER_EMAIL    = 'LMI_PAYER_EMAIL';
    const FIELD_NAME_PREREQUEST     = 'LMI_PREREQUEST';

    /**
     * Order
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Paymaster Config
     *
     * @var Voronoy_Paymaster_Model_Config
     */
    protected $_config;

    /**
     * Get Payment Method
     *
     * @var Voronoy_Paymaster_Model_Method_Abstract
     */
    protected $_paymentMethod;

    /**
     * Prepare Redirect Form Request
     */
    public function prepareRequest()
    {
        $requestData = array(
            self::FIELD_NAME_MERCHANT_ID    => $this->getConfig()->getMerchantId(),
            self::FIELD_NAME_PAYMENT_AMOUNT => sprintf("%0.2f", $this->getOrder()->getTotalDue()),
            self::FIELD_NAME_PAYMENT_NO     => $this->getOrder()->getIncrementId(),
            self::FIELD_NAME_PAYMENT_DESC   => '',
            self::FIELD_NAME_SUCCESS_URL    => Mage::getUrl('paymaster/payment/success'),
            self::FIELD_NAME_FAIL_URL       => Mage::getUrl('paymaster/payment/fail'),
            self::FIELD_NAME_PAYER_EMAIL    => $this->getOrder()->getCustomerEmail(),
            self::FIELD_NAME_HASH           => $this->getSignOfRequest(),
        );
        if ($this->getPaymentMethod()->getPaymentSystemId()) {
            $requestData[self::FIELD_NAME_PAYMENT_SYSTEM] = $this->getPaymentMethod()->getPaymentSystemId();
        }
        $this->addData($requestData);
    }

    public function getSignOfRequest()
    {
        $hash = sprintf("%s%s%0.2f%s", $this->getConfig()->getMerchantId(), $this->getOrder()->getIncrementId(),
            $this->getOrder()->getTotalDue(), $this->getConfig()->getMerchantSecretKey());
        return strtoupper(hash($this->getConfig()->getEncryptionMethod(), $hash));
    }

    /**
     * @return Voronoy_Paymaster_Model_Config
     */
    public function getConfig()
    {
        if (!$this->_config) {
            $this->_config = Mage::getModel('voronoy_paymaster/config');
            $this->_config->setPaymentCode($this->getPaymentMethod()->getCode());

        }
        return $this->_config;
    }

    /**
     * @param Voronoy_Paymaster_Model_Config $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * Get Payment Method Object
     *
     * @return Voronoy_Paymaster_Model_Method_Abstract
     */
    public function getPaymentMethod()
    {
        if (!$this->_paymentMethod) {
            $this->_paymentMethod = $this->getOrder()->getPayment()->getMethodInstance();
        }
        return $this->_paymentMethod;
    }

    /**
     * @param Voronoy_Paymaster_Model_Method_Abstract $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->_paymentMethod = $paymentMethod;
    }

    /**
     * Get Order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (!$this->_order) {
            $orderIncrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            if ($this->getData(self::FIELD_NAME_PAYMENT_NO)) {
                $orderIncrementId = $this->getData(self::FIELD_NAME_PAYMENT_NO);
            }
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
            if (!$order->getId()) {
                Mage::throwException(sprintf('Invalid Order ID: %s', $orderIncrementId));
            }
            $this->_order = $order;
        }
        return $this->_order;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    public function setOrder($order)
    {
        $this->_order = $order;
    }

    /**
     * Validate Hash String
     *
     * @param $hash
     *
     * @return bool
     */
    public function validateHash($hash)
    {
        $sign = $this->getConfig()->getMerchantId() . $this->getOrder()->getIncrementId()
            . $this->getData('LMI_SYS_PAYMENT_ID') . $this->getData('LMI_SYS_PAYMENT_DATE')
            . $this->getData('LMI_PAYMENT_AMOUNT') . $this->getData('LMI_PAID_AMOUNT')
            . $this->getData('LMI_PAYMENT_SYSTEM') . $this->getData('LMI_MODE')
            . $this->getConfig()->getMerchantSecretKey();

        $sign = strtoupper(hash($this->getConfig()->getEncryptionMethod(), $sign));
        if ($hash == $sign) {
            return true;
        }

        return false;
    }
}
