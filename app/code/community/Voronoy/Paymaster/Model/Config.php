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
class Voronoy_Paymaster_Model_Config extends Varien_Object
{
    /**
     * Path Merchant Id.
     */
    const XML_PATH_MERCHANT_ID = 'payment/paymaster_general/merchant_id';

    /**
     * Path Secret Key.
     */
    const XML_PATH_MERCHANT_SECRET_KEY = 'payment/paymaster_general/secret_key';

    /**
     * Path Encryption Method.
     */
    const XML_PATH_ENCRYPTION_METHOD = 'payment/paymaster_general/encryption_method';

    /**
     * Path Order Status.
     */
    const XML_PATH_ORDER_STATUS = 'payment/paymaster_general/order_status';

    /**
     * Path Test Mode.
     */
    const XML_PATH_DEBUG_MODE = 'payment/paymaster_general/debug';

    /**
     * Payment Code.
     *
     * @var string
     */
    protected $_paymentCode;

    /**
     * @param string $paymentCode
     */
    public function setPaymentCode($paymentCode)
    {
        $this->_paymentCode = $paymentCode;
    }

    /**
     * Get Merchant Id.
     *
     * @return string
     */
    public function getMerchantId()
    {
        return Mage::getStoreConfig(self::XML_PATH_MERCHANT_ID);
    }

    /**
     * Get Merchant Id.
     *
     * @return string
     */
    public function getDebug()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEBUG_MODE);
    }

    /**
     * Get Merchant Secret Key.
     *
     * @return string
     */
    public function getMerchantSecretKey()
    {
        return Mage::getStoreConfig(self::XML_PATH_MERCHANT_SECRET_KEY);
    }

    /**
     * Get Encryption Method.
     *
     * @return string
     */
    public function getEncryptionMethod()
    {
        return Mage::getStoreConfig(self::XML_PATH_ENCRYPTION_METHOD);
    }

    /**
     * Get Order Status after Order Place.
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return Mage::getStoreConfig(self::XML_PATH_ORDER_STATUS);
    }

    public function getTitle()
    {
        $path = sprintf('payment/%s/title', $this->_paymentCode);

        return Mage::getStoreConfig($path);
    }
}
