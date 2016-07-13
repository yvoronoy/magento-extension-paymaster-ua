<?php

/**
 * Magento Paymaster Extension
 * Copyright (c) 2015 by Yaroslav Voronoy (y.voronoy@gmail.com).
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
 */
class Voronoy_Paymaster_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Ukrainian Hrivna currency code
     */
    const UAH_CURRENCY_CODE = 'UAH';

    /**
     * Restore Quote and Replace Current Quote.
     *
     * @param $quote
     */
    public function restoreQuote($quote)
    {
        if ($quote->getId()) {
            $quote->setIsActive(true);
            $quote->setReservedOrderId(null);
            $quote->save();
        }
        Mage::getSingleton('checkout/session')->replaceQuote($quote)->unsLastRealOrderId();
    }

    public function getGatewayUrl()
    {
        $gatewayUrl = Mage::getStoreConfig('payment/paymaster_general/gateway_url');
        $lang = Mage::getStoreConfig('payment/paymaster_general/lang');
        if ($lang) {
            $gatewayUrl = sprintf('%s%s/', $gatewayUrl, $lang, '/');
        }

        return $gatewayUrl;
    }

    public function getOrderState()
    {
        return Mage::getStoreConfig('payment/paymaster_general/order_status');
    }

    public function convertToDefaultCurrency($baseAmount)
    {
        $availableCodes = Mage::app()->getStore()->getAvailableCurrencyCodes();
        if (!in_array(self::UAH_CURRENCY_CODE, $availableCodes)) {
            return Mage::app()->getStore()->roundPrice($baseAmount);
        }

        $baseCurrency = Mage::app()->getStore()->getBaseCurrency();
        $uahAmount = $baseAmount;
        if ($baseCurrency->getCode() != self::UAH_CURRENCY_CODE) {
            $uahAmount = $baseCurrency->convert($baseAmount, self::UAH_CURRENCY_CODE);
        }

        $uahAmount = Mage::app()->getStore()->roundPrice($uahAmount);
        return $uahAmount;
    }
}
