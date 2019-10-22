<?php
/* ########################################################################### */
/* ----------------     NVN Export Orders PrestaShop module    --------------- */
/*                       Copyright 2013   Karel Falgenhauer                    */
/*                          http://www.netvianet.com/                          */
/*                           http://www.praotec.com/                           */
/*             Please do not change this text, remove the link,                */
/*          or remove all or any part of the creator copyright notice          */
/*                                                                             */
/*    Please also note that although you are allowed to make modifications     */
/*     for your own personal use, you may not distribute the original or       */
/*                 the modified code without permission.                       */
/*                                                                             */
/*     SELLING AND REDISTRIBUTION IS FORBIDDEN! DO NOT SHARE WITH OTHERS!      */
/*                  Download is allowed only from netvianet.com                */
/*                                                                             */
/*       This software is provided as is, without warranty of any kind.        */
/*           The author shall not be liable for damages of any kind.           */
/*               Use of this software indicates that you agree.                */
/*                                                                             */
/* ########################################################################### */

include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/../../init.php');
include(dirname(__FILE__) . '/export_orders_production.php'); 

if (substr(_COOKIE_KEY_, 34, 8) != Tools::getValue('token')){die;}
$act = Tools::getValue('act');
if($act <> 'set1' AND $act <> 'set2'){$act = null;}
//ini_set('max_execution_time', 90);

$my_export = new export_orders_redur();

$my_export->export($act);
echo "Export Orders PRODUCTION (CRON) OK";

?>