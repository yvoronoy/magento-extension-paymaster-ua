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
class Voronoy_Paymaster_Model_System_Config_Source_Encryption
{
    public function toOptionArray()
    {
        $encryptionMethods = array(
            array('value' => 'sha256', 'label' => 'sha256'),
            array('value' => 'md5', 'label' => 'md5'),
            array('value' => 'sha1', 'label' => 'sha1'),
        );

        return $encryptionMethods;
    }
}
