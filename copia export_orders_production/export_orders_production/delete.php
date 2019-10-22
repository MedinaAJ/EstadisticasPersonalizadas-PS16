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

include(dirname(__FILE__).'/../../config/config.inc.php');
   $mask = "download/*.csv";
   @array_map( "unlink", glob( $mask ) );
   $mask = "download/*.html";
   @array_map( "unlink", glob( $mask ) );
   $mask = "download/*.xml";
   @array_map( "unlink", glob( $mask ) );
   $mask = "download/*.xlsx";
   @array_map( "unlink", glob( $mask ) );   
   Tools::redirectAdmin($_SERVER['HTTP_REFERER'].'&tools=deleted');
?>