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

abstract class Voronoy_Paymaster_Model_Method_Abstract extends Mage_Payment_Model_Method_Abstract
{
    /**
     * Is initialize needed
     *
     * @var bool
     */
    protected $_isInitializeNeeded      = true;

    /**
     * Can we use payment in backend
     *
     * @var bool
     */
    protected $_canUseInternal          = false;

    /**
     * Can we use for Multi Shipping
     *
     * @var bool
     */
    protected $_canUseForMultishipping  = false;

    /**
     * @var Voronoy_Paymaster_Model_Request
     */
    protected $_request;

    /**
     * @var Voronoy_Paymaster_Model_Config
     */
    protected $_config;

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * @return Voronoy_Paymaster_Model_Config
     */
    public function getConfig()
    {
        if (!$this->_config) {
            $this->_config = Mage::getModel('voronoy_paymaster/config');
            $this->_config->setPaymentCode($this->getCode());

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
     * Get Redirect Url after Order Place
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('paymaster/payment/redirect', array('_secure' => true));
    }

    /**
     * Get Request
     *
     * @return Voronoy_Paymaster_Model_Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = Mage::getModel('voronoy_paymaster/request');
        }
        return $this->_request;
    }

    /**
     * Validate Request
     *
     * @param Voronoy_Paymaster_Model_Request $request
     *
     * @return bool
     */
    public function validateRequest($request)
    {
        $result = true;
        $merchantId = $request->getData(Voronoy_Paymaster_Model_Request::FIELD_NAME_MERCHANT_ID);
        if ($this->getConfig()->getMerchantId() != $merchantId) {
            $result = false;
        }
        $order = Mage::getModel('sales/order')->loadByIncrementId(
            $request->getData(Voronoy_Paymaster_Model_Request::FIELD_NAME_PAYMENT_NO));
        if (!$order->getId() || $order->isCanceled()) {
            $result = false;
        }
        if ($order->getGrandTotal()
            != $request->getData(Voronoy_Paymaster_Model_Request::FIELD_NAME_PAYMENT_AMOUNT)
        ) {
            $result = false;
        }

        return $result;
    }

    /**
     * Define if debugging is enabled
     *
     * @return bool
     */
    public function getDebugFlag()
    {
        return $this->getConfig()->getDebug();
    }
}
 