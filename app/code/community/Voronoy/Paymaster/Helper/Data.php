<?php
/**
 * Magento Paymaster Extension
 * Copyright (c) 2015 by Yaroslav Voronoy (y.voronoy@gmail.com)
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
     * Restore Quote and Replace Current Quote
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
        return Mage::getStoreConfig('payment/paymaster_general/gateway_url');
    }
}