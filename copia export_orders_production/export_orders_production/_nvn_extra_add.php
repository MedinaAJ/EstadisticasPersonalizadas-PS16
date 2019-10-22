<?php
/* ########################################################################### */
/* ----------------   NVN Export Orders PrestaShop extra add   --------------- */
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

class nvn_extra_add
{  
	public  $new_fname = '';
	public  $new_exp_path = '';
	

//************************************************************************************************************************
    function __construct($megapole,$fname,$df,$kni=null,$mline=null,$cfline=null,$ktyp=null)
//************************************************************************************************************************
{
   
   $this->new_fname = 'Extra_Add_Export-click-here.html'; //str_replace('.extra.add','.html',$fname);
   $this->new_exp_path = dirname(__FILE__)."/download/".$this->new_fname ;
 
   $fp = fopen($this->new_exp_path, 'a+');
   fwrite($fp,$this->xme());
   fclose($fp);
	
}  
//************************************************************************************************************************
    private  function xme()
    {return '<span style="font-weight: bold;color:red;">This is special export on demand. See example <a style="font-weight: bold;color:blue" href="http://netvianet.com/support-and-other-services/41-support-and-modification-for-nvn-export-orders-module.html" target="_blank"> here. </a>  Need a special export format? Contact me at: <a style="font-weight: bold;color:blue" href="http://netvianet.com/contact-us" target="_blank"> NetViaNet.com </a></span>'; }
  

//************************************************************************************************************************
    private function preprint($s, $return=false) //for testing only
//************************************************************************************************************************
    { 
        $x = "<pre>"; 
        $x .= print_r($s, 1); 
        $x .= "</pre>"; 
        if ($return) return $x; 
        else print $x; 
    }   
}
?>