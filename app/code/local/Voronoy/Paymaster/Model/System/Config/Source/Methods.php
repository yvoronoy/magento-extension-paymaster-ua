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

class Voronoy_Paymaster_Model_System_Config_Source_Methods
{
    public function toOptionArray()
    {
        $encryptionMethods = array(
            array('value' => '1',  'label' => 'WebMoney'),
            array('value' => '12', 'label' => 'EasyPay'),
            array('value' => '15', 'label' => 'NSMEP'),
            array('value' => '17', 'label' => 'WebMoney Terminal'),
            array('value' => '21', 'label' => 'PayMaster Card (Visa, MasterCard)'),
            array('value' => '20', 'label' => 'Privat 24'),
            array('value' => '19', 'label' => 'LiqPay'),
            array('value' => '23', 'label' => 'Kyivstar'),
            array('value' => '2',  'label' => 'WebMoney Mobile Payments'),
            array('value' => '18', 'label' => 'Test Payment Method'),
        );

        return $encryptionMethods;
    }
}