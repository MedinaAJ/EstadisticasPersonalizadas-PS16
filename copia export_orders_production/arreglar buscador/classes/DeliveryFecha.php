<?php
/*
* Copyright (C) 2012  Campos Development.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
* @author  	  Campos Development.
* @copyright  Copyright Campos Development 2012. All rights reserved.
* @license 	  GPLv2 License http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* @version 	  v1.0.0
*
*/
class DeliveryFecha extends ObjectModel
{
  /** @var string Name */
  public $delivery_date;
 
  public static $definition = array(
		'table' => 'deliverydate',
		'primary' => 'id_deliverydate',
		'multilang' => false,
		'fields' => array(
			'delivery_date' => 	array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
                        'id_order' => 	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			// Lang fields
		),
	);
 
  public function getFields()
    {
    parent::validateFields();
    $fields[ 'delivery_date' ] = pSQL( $this->delivery_date );
    return $fields;
    }
  
}
?>
