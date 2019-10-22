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


// todo delivery and invoice other - odstranovat CRLF pro csv

//************************************************************************************************************************
class export_orders_production extends Module
//************************************************************************************************************************
{
private $_html = '';
private static $existf = array();

    function __construct()
    {
        $this->name = 'export_orders_production';
        $this->tab =  'administration';
        $this->author = 'netvianet.com';
        $this->version = '1.8.3';//css  
        parent::__construct();
    
        $this->displayName = $this->l('NVN Export Orders');
        $this->description = $this->l('A module to export orders made on Your page.');
        $this->confirmUninstall = $this->l('If you uninstall, settings will be lost. Are you sure?');

    }

//************************************************************************************************************************
    public function install()
//************************************************************************************************************************
    {
    $ds = 'LASTIDORDER↔-1‡DATEFROM↔‡DATETO↔‡GROUPBY↔‡TOCOMMA↔0‡LZERO↔1‡CSVDELIMITER↔,‡ICONV↔‡SAVEAS↔csv‡ONEFILE↔0‡TITLES↔1‡EMAIL↔‡GSORT↔‡ORDERSTATUS↔‡FTPSERVER↔‡FTPFILE↔‡FTPUSER↔‡FTPPASS↔‡AUTODATEFR↔‡DATEFORMAT↔‡ONEORDER1FILE↔0‡MSHOP↔0‡INVOICE↔0‡ESPUMA↔0‡TAPIZADO↔0‡SOLDADURA↔0‡CORTESOFAS↔0‡CORTEFUNDA↔0‡COSTURA↔0‡PEGADO↔0‡EMPAQUETADO↔0‡EXPRESS↔0‡VENCIMIENTO↔0‡OSTFROM↔0‡OSTTO↔0‡OSTMAIL↔0';
    if (parent::install() == false OR 
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_FIELDSON1','') == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_FIELDSON2','') == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SPECFILD1','Multi-line field:CR~LFUse "Multi-line key" from buttons or empty.CR~LFDelivery Adress Example:CR~LFCompany: delivery_companyCR~LFdelivery_firstname delivery_lastnameCR~LFdelivery_address1CR~LFdelivery_address2CR~LFdelivery_postcode  delivery_cityCR~LF-----------------------------CR~LFPhone:delivery_mobilephone') == false  OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SPECFILD2','Multi-line field:CR~LFUse "Multi-line key" from buttons or empty.CR~LFDelivery Adress Example:CR~LFCompany: delivery_companyCR~LFdelivery_firstname delivery_lastnameCR~LFdelivery_address1CR~LFdelivery_address2CR~LFdelivery_postcode  delivery_cityCR~LF-----------------------------CR~LFPhone:delivery_mobilephone') == false  OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_CALCFILD1','delivery_firstname delivery_lastname‡product_weight * product_quantity‡‡‡‡‡‡') == false  OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_CALCFILD2','delivery_firstname delivery_lastname‡product_weight * product_quantity‡‡‡‡‡‡') == false  OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_REWRITE1','↔‡↔') == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_REWRITE2','↔‡↔') == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_USERXML1','&lt;?xml version="1.0" encoding="UTF-8"?&gt;CR~LF&lt;export_orders_production&gt;CR~LF↔‡↔&lt;order&gt;CR~LF&lt;Your_user_key1&gt;#order_id#&lt;/Your_user_key1&gt;CR~LF&lt;Your_user_key2&gt;&lt;![CDATA[#first_name# #last_name#]]&gt;&lt;/Your_user_key2&gt;CR~LF&lt;/order&gt;CR~LF↔‡↔&lt;/export_orders_production&gt;') == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_USERXML2','&lt;?xml version="1.0" encoding="UTF-8"?&gt;CR~LF&lt;export_orders_production&gt;CR~LF↔‡↔&lt;order&gt;CR~LF&lt;Your_user_key1&gt;#order_id#&lt;/Your_user_key1&gt;CR~LF&lt;Your_user_key2&gt;&lt;![CDATA[#first_name# #last_name#]]&gt;&lt;/Your_user_key2&gt;CR~LF&lt;/order&gt;CR~LF↔‡↔&lt;/export_orders_production&gt;') == false OR    
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING1',$ds) == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING2',$ds) == false OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SHIDE',1) == false  OR
    Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETX',0) == false
                                                                                        )
                return false;
   return true; 
    }
//************************************************************************************************************************
	public function uninstall()
//************************************************************************************************************************
    {
       if (!parent::uninstall() OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_FIELDSON1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_FIELDSON2') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SPECFILD1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SPECFILD2') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_CALCFILD1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_CALCFILD2') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_REWRITE1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_REWRITE2') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_USERXML1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_USERXML2') OR       
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SETTING1') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SETTING2') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SHIDE') OR
       !Configuration::deleteByName('EXPORT_ORDERS_PRODUCTION_SETX')
                                                                                       )
            return false;
   return true;   
    }  
//************************************************************************************************************************
 public function displayForm()
//************************************************************************************************************************
    {
    $url0=$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/export_orders_production/export_orders_production_cron.php?token=".substr(_COOKIE_KEY_, 34, 8);
    $url1=$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/export_orders_production/export_orders_production_cron.php?act=set1&token=".substr(_COOKIE_KEY_, 34, 8);
    $url2=$_SERVER['HTTP_HOST'].__PS_BASE_URI__."modules/export_orders_production/export_orders_production_cron.php?act=set2&token=".substr(_COOKIE_KEY_, 34, 8);
    if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){
     $jeon = Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON2');
     $mline = Configuration::get('EXPORT_ORDERS_PRODUCTION_SPECFILD2');
     $cfline = Configuration::get('EXPORT_ORDERS_PRODUCTION_CALCFILD2');
     $xmline = Configuration::get('EXPORT_ORDERS_PRODUCTION_USERXML2');
     $ds = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING2');
     }
    else{
     $jeon = Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON1');
     $mline = Configuration::get('EXPORT_ORDERS_PRODUCTION_SPECFILD1');
     $cfline = Configuration::get('EXPORT_ORDERS_PRODUCTION_CALCFILD1');
     $xmline = Configuration::get('EXPORT_ORDERS_PRODUCTION_USERXML1');
     $ds = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING1');
     }
    $cn = $this->nFields(); 
    $df = array();
    foreach (explode('‡', $ds) as $cLine) {
    list ($cKey, $cValue) = explode('↔', $cLine, 2);
    $df[$cKey] = $cValue;}
    $cf = explode('‡', $cfline);
    if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SHIDE')){$shide='block';}
    else{$shide='none';}
    $this->_html = '
<link rel="stylesheet" type="text/css" href="'.$this->_path.'nvn.css">    
<script type="text/javascript" language="JavaScript">
<!--   
function in_array(needle, haystack){
    var found = 0;
    for (var i=0, len=haystack.length;i<len;i++) {
        if (haystack[i] == needle) return i;
            found++;}
    return -1;
  }
function SsetT(button){
  var xbutton = document.getElementById(button.id);
  var bid = (xbutton.id).split("_"); 
  var fieldson = document.getElementById("gonfields");
  var arrAA = (fieldson.value).split(",");
  var jetam = in_array(bid[1], arrAA);
  if(jetam == -1){
    arrAA.push(bid[1]);
    fieldson.value = arrAA.join();
    document.getElementById(\'targetD\').appendChild(xbutton);}
   else{
    arrAA.splice(jetam,1);
    fieldson.value = arrAA.join();
    document.getElementById(\'sourceD\').appendChild(xbutton);}
  } 
function alterset() {
   var setingx = document.getElementById("altset").checked;
   document.getElementById("jsetingx").value = "jsetingxsend";   
   document.getElementById("export_orders_production").submit();    
   }
function showhide() {
   document.getElementById("jshidex").value = "jshidesend";
   document.getElementById("export_orders_production").submit();    
   }
function uncheckSec(id,checkbox) {
    var druhy = document.getElementById(id);
    if (checkbox.checked)
    {druhy.checked = false;}
   }   
// -->
</script>    
    
    <link href="' . _MODULE_DIR_ . 'export_orders_production/calendar/tcal.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="' . _MODULE_DIR_ . 'export_orders_production/calendar/tcal.js"></script>

    
<form id= "export_orders_production" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
<input type="hidden" id="gonfields" name="gonfields" value="'.$jeon.'" />
<input type="hidden" name="jsetingx" id="jsetingx" value=""/>
<input type="hidden" name="jshidex" id="jshidex" value=""/>
    <fieldset><legend>
     <img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('FIELDS:').'</legend>
    <div class="isdiv1">
        <div style="float:left; width:645px;"><label for="s">'.$this->l('Available Fields').'</label></div>
        <div style="float:left; width:235px;"><label for="s">'.$this->l('Fields for Export').'</label></div>
        <div class="sdiv" id="sourceD">
           '.$this->setFields(true).'
        </div>
        <div class="tdiv" id="targetD">
           '.$this->setFields(false).'
        </div>    
        <div style="float:left;margin-top:5px;" >
        <textarea rows="5" wrap="hard" style="width:220px;" title="'.$this->l('Multi-line field: use "Multi-line key" from buttons or empty. To add to export click &lt;').$cn[84][1].'&gt; button"  name="specfield">'.str_replace("CR~LF","\n",$mline).'</textarea>
        </div>
        <div style="float:left; width:100%; background-color:#FDFACC;"><label for="s">'.$this->l('Calculated Fields. Use: "Multi-line key" "Operator(+ -*/)" "Multi-line key"').'</label>
          <div style="float:left; width:100%;">
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[85][1].'&gt; button" name="calcfield1" value="'.($cf[0]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[86][1].'&gt; button" name="calcfield2" value="'.($cf[1]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[87][1].'&gt; button" name="calcfield3" value="'.($cf[2]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[88][1].'&gt; button" name="calcfield4" value="'.($cf[3]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[89][1].'&gt; button" name="calcfield5" value="'.($cf[4]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[90][1].'&gt; button" name="calcfield6" value="'.($cf[5]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[91][1].'&gt; button" name="calcfield7" value="'.($cf[6]).'"/>
            <input type="text" style="float:left; width:210px;" title="'.$this->l('To add to export click &lt;').$cn[92][1].'&gt; button" name="calcfield8" value="'.($cf[7]).'"/>
          </div>
        </div>  
        <span style="float:left; width:100%;">'.$this->l('Expample1: delivery_firstname delivery_lastname Expample2: product_weight * product_quantity'  ).'</span>
    </div>
    </fieldset>
   
<div  style="font-weight:bold;margin-top:3px;margin-bottom:5px;background:#ff0000;">
<label class = "checkme" style="color:white;"><input type="checkbox" id="shide" name="shide" onclick="javascript:showhide();" value="1" '.((Configuration::get('EXPORT_ORDERS_PRODUCTION_SHIDE')) ? ' checked=""': '').' />&nbsp;'.$this->l('SHOW ALL FILTERS AND OPTIONS').'</label>
<p class="clear" ></p>
</div>
<div id="showAll" style="display:'.$shide.';">
    
     <fieldset>
     <legend><img src="'.$this->_path.'logo.gif" /> '.$this->l('FILTERS').'</legend>
        <label for="s">'.$this->l('Last Exported Order ID').'</label>
        <div class="margin-form">
        <input type="text" name="lastid" value="'.($df['LASTIDORDER']).'"/>
        <span style="color:blue">'.$this->l('Set negative (eg -1), if You do not need use this filter.').'</span>
        <p class="clear">'.$this->l('Is automatically set to last Order ID after export. Set to 0, if You need export all old Orders.').'</p>
        </div>
        <label for="s">'.$this->l('Order date from:').'</label>
        <div class="margin-form">
        <input type="text" name="datefrom" class="tcal" value="'.($df['DATEFROM']).'"/>
        <span>'.$this->l('Select from picker or leave empty, if You do not need use this filter.').'</span>
        </div>
        <label for="s">'.$this->l('Order date to:').'</label>
        <div class="margin-form">
        <input type="text" name="dateto" class="tcal" value="'.($df['DATETO']).'"/>
        <span>'.$this->l('Select from picker or leave empty, if You do not need use this filter.').'</span>
        </div>
        <label for="s">'.$this->l('Automatic Day from to now:').'</label>
        <div class="margin-form">
        <input type="text" name="autodatefr" value="'.($df['AUTODATEFR']).'"/>
        <span>'.$this->l('Example: Set 1 for from yesterday to now, filter "Date from" "Date to" will be ignored, if not empty.').'</span>
        </div>
        <label for="s">'.$this->l('Order status:').'</label>
        <div class="margin-form">
        <select multiple="multiple" name="ordstatus[ ]" id="ostatus" style="width: 300px; height: 160px;">
        '.$this->getOrderState($df['ORDERSTATUS']).'</select><span style="color:red">'.$this->l(' Use CTRL + mouse for multiple select / unselect.').'</span>
        <br /><label class = "checkme"><input type="checkbox" name="invoice" value="1"'.(($df['INVOICE']) ? ' checked=""': '').'/> '.$this->l('Only Orders with Invoice').'</label><p class="clear" ></p>
		</div>
		<div class="margin-form">
		<label class = "checkme"><input type="checkbox" name="espuma" value="1"'.(($df['ESPUMA']) ? ' checked=""': '').'/> '.$this->l('Only Orders for foam ').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="tapizado" value="1"'.(($df['TAPIZADO']) ? ' checked=""': '').'/> '.$this->l('Only Orders for upholstered').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="soldadura" value="1"'.(($df['SOLDADURA']) ? ' checked=""': '').'/> '.$this->l('Only Orders for welding').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="cortesofas" value="1"'.(($df['CORTESOFAS']) ? ' checked=""': '').'/> '.$this->l('Only Orders for sofascourt').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="cortefunda" value="1"'.(($df['CORTEFUNDA']) ? ' checked=""': '').'/> '.$this->l('Only Orders for deepcut').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="costura" value="1"'.(($df['COSTURA']) ? ' checked=""': '').'/> '.$this->l('Only Orders for sewing').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="pegado" value="1"'.(($df['PEGADO']) ? ' checked=""': '').'/> '.$this->l('Only Orders for glued').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="empaquetado" value="1"'.(($df['EMPAQUETADO']) ? ' checked=""': '').'/> '.$this->l('Only Orders for packaging').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="express" value="1"'.(($df['EXPRESS']) ? ' checked=""': '').'/> '.$this->l('Only Orders for express').'</label><p class="clear" ></p>
		<label class = "checkme"><input type="checkbox" name="vencimiento" value="1"'.(($df['VENCIMIENTO']) ? ' checked=""': '').'/> '.$this->l('Only Orders for Vto').'</label><p class="clear" ></p>
        </div>
        <label for="s">'.$this->l('Group by:').'</label>
        <div class="margin-form">
        '.$this->getGroupBy($df['GROUPBY']).'<span style="color:blue">'.$this->l('Must be in "Field for Export", otherwise will be ignored.').'</span>
        <p style="color:red;">'.$this->l('Some Fields for Export are ignored if You select Group by!!! Sum Quantity, Sum Weight and Sum Price.').'</p>   
        </div>
        <label for="s">'.$this->l('Sort result by:').'</label>
        <div class="margin-form">
        '.$this->getSortBy($df['GSORT']).'<span style="color:blue">'.$this->l('Must be in "Field for Export", otherwise will be ignored.').'</span>
        </div>   
        <label for="s">'.$this->l('Shop name:').'</label>
        <div class="margin-form">
        '.$this->getShopName($df['MSHOP']).'<span>'.$this->l('For MultiStore support.').'</span>
        </div>   
    </fieldset>
    
    <fieldset>
    <legend><img src="'.$this->_path.'logo.gif" /> '.$this->l('OPTIONS:').'</legend>
        <label for="s">'.$this->l('Number format in export').'</label>
        <div class="margin-form">
        <label class = "checkme"><input type="checkbox" name="tocomma" value="1"'.(($df['TOCOMMA']) ? ' checked=""': '').'/> '.$this->l('Comma as separator in number').'</label>
        <label class = "checkme"><input type="checkbox" name="lzero" value="1"'.(($df['LZERO']) ? ' checked=""': '').'/> '.$this->l('Keep leading zeros of Phone, Postcode, etc. for MS Excel').'</label>
        <p class="clear" ></p>
        </div>
        <label for="s">'.$this->l('Date format in export').'</label>
        <div class="margin-form">
        <input type="text" name="dateformat" value="'.($df['DATEFORMAT']).'"/>
        <span>'.$this->l('Example: d/m/Y H:i:s Or empty for international standard notation.').'</span>
        </div>
        <label for="s">'.$this->l('CSV Delimiter').'</label>
        <div class="margin-form">
        <input type="text" name="delimiter" value="'.($df['CSVDELIMITER']).'"/>
        <span>'.$this->l('Usualy "," or ";" (Tip - use ";" if You use "Comma as separator in number")').'</span>
        </div>
        <label for="s">'.$this->l('Encode output to').'</label>
        <div class="margin-form">
        <input type="text" name="iconvx" value="'.($df['ICONV']).'"/>
        <span>'.$this->l('Set UTF-8 or leave empty for UTF-8, otherwise set Your charset as Windows-1250 or CP1250 for eastern Europe, Windows-1252 for western Europe, ASCII, ISO-8859-1 , CP1256 etc... ').'</span>
        </div>
        <label for="s">'.$this->l('Export as:').'</label>
        <div class="margin-form">
        '.$this->getSaveAs($df['SAVEAS']).'
        <span>'.$this->l('Export as Excel-2007.xlsx, CSV, HTML, XML or user defined XML. Use "Save as" for download *.html *.xml file. ').'</span>
        </div>
        <label for="s">'.$this->l('Append Orders into same file:').'</label>
        <div class="margin-form">
        <label class = "checkme"><input type="checkbox" name="onefile" id="onefile" onclick="javascript:uncheckSec(\'oneorder1file\',this);" value="1"'.(($df['ONEFILE']) ? ' checked=""': '').'/>
        '.$this->l('All exported Orders in current day will be append into 1 file.').'</label><p class="clear" ></p>
        </div>	
        <label for="s">'.$this->l('One order - one file:').'</label>
        <div class="margin-form">
        <label class = "checkme"><input type="checkbox" name="oneorder1file" id="oneorder1file" onclick="javascript:uncheckSec(\'onefile\',this);" value="1"'.(($df['ONEORDER1FILE']) ? ' checked=""': '').'/>
        '.$this->l('Each order into a new file.').'</label><p class="clear" ></p>
        </div>		
        <label for="s">'.$this->l('Put titles in the first row:').'</label>
        <div class="margin-form">
        <label class = "checkme"><input type="checkbox" name="titles" value="1"'.(($df['TITLES']) ? ' checked=""': '').'/>
        '.$this->l('Put columns names in the first CSV row.').'</label><p class="clear" ></p>
        </div>
        <label for="s">'.$this->l('Send Orders to e-mails:').'</label>
        <div class="margin-form">
        <input type="text" style="width:300px;" name="email" value="'.($df['EMAIL']).'"/>
        <p class="clear" >'.$this->l('Set e-mail(s), separated by "," where to send Orders or leave empty, if You do not need send Orders to e-mail.').'</p>
        </div>
        <label for="s">'.$this->l('Send Orders to FTP:').'</label>
        <div class="margin-form">
        <input type="text" style="width:130px;" name="ftpserver" value="'.($df['FTPSERVER']).'"/>&larr;<span>'.$this->l('FTP Server').' </span> 
        <input type="text" style="width:130px;" name="ftpfile" value="'.($df['FTPFILE']).'"/>&larr;<span>'.$this->l('FTP Path/File Name. Original Name is used if Path only.').' </span> 
        <p class="clear">'.$this->l('Example:Server "ftp12.myserver.net", Path/File: "www/export.csv" or "www/". Leave Server empty, if You do not need send Orders.').'</p>
        <input type="text" style="width:100px;" name="ftpuser" value="'.($df['FTPUSER']).'"/>&larr;<span>'.$this->l('FTP User name').' </span> 
        <input type="password" style="width:100px;" name="ftppass" value=""/>&larr;<span>'.$this->l('FTP Password. Fill only when changing.').' </span> 
        </div>
        <label for="s">'.$this->l('Change exported orders status:').'</label>
        <div class="margin-form">
        From: '.$this->getOrderStateFrom($df['OSTFROM']).'&nbsp;To: '.$this->getOrderStateTo($df['OSTTO']).' (Only for PS 1.5 and above)<br/>
        <label class = "checkme"><input type="checkbox" name="ostmail" value="1"'.(($df['OSTMAIL']) ? ' checked=""': '').'/>
        '.$this->l('Send email to the customer, if order status changes. (!May cause "maximum execution time exceeded" error on some servers!)').'</label><p class="clear" ></p>        
        <p class="clear" style="color:red;" >'.$this->l('Be careful with this feature! Exported orders status will be changed (after export) to selected status. Set empty "To:"  if you do not want use.').'</p>
        </div>
        <label for="s">'.$this->l('CRON Link:').'</label>
        <div class="margin-form">
        <p class="bold" style="color:#FFFFFF; background:#000000"> http://'.$url0.' </p>
        <p class="bold" style="color:#FFFFFF; background:#000000"> http://'.$url1.' </p>
        <p class="bold" style="color:#FFFFFF; background:#000000"> http://'.$url2.' </p>
        <p class="clear" >'.$this->l('CRON export as: Link1 = current setting, Link2 = Setting 1, Link3 = Setting 2(Alternative setting)').'</p>
        </div>
    </fieldset>
    <fieldset>
      <div style="float:left; position:absolute;width:200px" >
      <p class="bold" style="color:blue">Visit <a style=" color:green" href="http://netvianet.com" target="_blank"> www.NetViaNet.com </a> for check update and more PrestaShop modules.</p> 
      <p class="bold" style="color:black">Read <a style=" color:red" href="http://netvianet.com/content/6-faq" target="_blank"> FAQ </a> how to setup "User defined XML".</p>
      <a href="http://www.netvianet.com" target="_blank"><img style="float:left;" src="'.$this->_path.'nvn_l.png" /></a>
      </div>
      <legend><img src="'.$this->_path.'logo.gif" /> '.$this->l('Rewrite XML elements tag, CSV columns + buttons').'</legend>
      <div class="margin-form">
       '.$this->setRFields($xmline).'
      </div>
    </fieldset> 
        
</div>   
     
    <fieldset>
    <legend><img src="'.$this->_path.'logo.gif" /> '.$this->l('SAVE FIELDS, FILTERS, OPTIONS:').'</legend>
        <div class="margin-form">
        <span style="text-align: left;display: table-cell;min-width: 10px;padding: 5px; background:#FE5A5A;">
        <center><input type="submit" name="submitSetting" value="'.$this->l('Save Settings').'" class="button" /></center>
        </span>
        <span style="text-align: left;display: table-cell;min-width: 50px;padding: 5px; background: pink;">
        <center>
         <label style="color:black;"><input type="checkbox" id="altset" name="altset" onclick="javascript:alterset();" value="1" '.((Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')) ? ' checked=""': '').' />&nbsp;'.$this->l('Alternative settings').'</label>
        </center>
       </span>
       </div>
    </fieldset>
    <fieldset>
    <legend><img src="'.$this->_path.'logo.gif" /> '.$this->l('Export Orders').'</legend>
      <div class="margin-form">
      <span style="text-align: left;display: table-cell;min-width: 10px;padding: 5px; background:#00AAAA;">
      <center><input type="submit" name="submitExport" value="'.$this->l('Export Orders').'" class="button" /></center>
      </span>
      </div>
    </fieldset> 
</form>

   <form action="'. _PS_BASE_URL_.__PS_BASE_URI__.'modules/export_orders_production/delete.php"  method="post" target="_self">
      <div class="margin-form" style="background:#FFFEC8; text-align:right;">
      <p class="bold" style="color:red">For security reasons, delete the old exports files from server.</p> 
         <input type="submit" name="delete" value="'.$this->l('Delete old exports').'" class="button" />
      </div>
    </form>
    <form action="'. _PS_BASE_URL_.__PS_BASE_URI__.'modules/export_orders_production/download.php"  method="post" target="_blank">
      <span style="float:left;">Current version: '.$this->version.'</span><br />
         <div style="float:left;background:#C8CCFF;padding:5px;">'.$this->getNVNContent().' 
         <input type="hidden" style="width:150px;" name="voucher" value="export_orders_production_apC7963llfgD8AER1o4KI" />
         <input type="submit" value="'.$this->l('download latest version').'" class="button" />
      </div><p class="clear"></p>
    </form>';
    return $this->_html;
    }
    
 //------------------------------------------------------------------------------------------------------------------------
 	private function setRFields($xmline)
    {$res = $this->nFields();
     $userxml = explode('↔‡↔',$xmline);   
     $sout = '<div class="accordion">
	<div>
      <input id="panel1" type="checkbox" /><label for="panel1">XML elements tag</label><p class="clear" ></p>
      <article>
         <p class="clear" style="color:#ff0000;">Must be one word (A-Z _ 0-9) and must be unique. For detail XML Naming Rules read http://www.w3schools.com/xml/xml_elements.asp</p>
         <p class="clear" style="color:#ff0000;">DO NOT CHANGE RED. DO NOT CHANGE IF YOU USE extra.add Export as type. Gray are not in current DB.</p>';      
	         foreach ($res as $r){
             $x=explode(' AS ',$r[0]);
             $stl='';
             if($r[5]==0){$stl='background:#ff0000;';}
             if($r[6]==0){$stl='background:#5c5c5c;';}
             //if($r[5]==2){$stl='background:#ffd0d0;';}
             $sout .= '<input type="text" style="width:150px;'.$stl.'" title="csv+button text: '.$r[1].'" name="ixml[ ]" value="'.$x[1].'"/>'; 
         }
    $sout .= '
      </article>
    </div>
	<div>
      <input id="panel2" type="checkbox" /><label for="panel2">CSV columns + buttons names</label><p class="clear" ></p>
      <article>';
         foreach ($res as $r){
         $x=explode(' AS ',$r[0]);
         $sout .= '<input type="text" style="width:150px;" title="xml key: '.$x[1].'" name="icsv[ ]" value="'.$r[1].'"/>';   
         }
         $sout .= '
      </article>
    </div>
    <div>
      <input id="panel3" type="checkbox" /><label for="panel3">User defined XML, part 1 - header</label><p class="clear" ></p>
      <article>';
         $sout .= '<textarea rows="35" style="width:650px;" wrap="off" name="userxml1">'.str_replace("CR~LF","\n",$userxml[0]).'</textarea>   
      </article>
    </div>    
    <div>
      <input id="panel4" type="checkbox" /><label for="panel4">User defined XML, part 2 - table of contents</label><p class="clear" ></p>
      <article>';
         $sout .= '<textarea rows="35" style="width:650px;" wrap="off" name="userxml2">'.str_replace("CR~LF","\n",$userxml[1]).'</textarea>   
      </article>
    </div> 
    <div>
      <input id="panel5" type="checkbox" /><label for="panel5">User defined XML, part 3 - footer</label><p class="clear" ></p>
      <article>';
         $sout .= '<textarea rows="35" style="width:650px;" wrap="off" name="userxml3">'.str_replace("CR~LF","\n",$userxml[2]).'</textarea>   
      </article>
    </div>         
    </div>';
   return $sout;
    }
    
    private function getSaveAs($saveas)
    {   $sout = '';
        $res = $this->saveF();
          $sout .= '<select name="gsaveas">';
          foreach ($res as $row){
          if ($row == $saveas)
           {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
          else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
          }
          $sout .= '</select>';
    return $sout;
    } 	
    
 	private function getGroupBy($groupby)
    {   $sout = '';
        $res = $this->nFields();
          $sout .= '<select name="groupby">';
          $sout .= '<option value=""></option>';
          foreach ($res as $r){
           if($r[4]){
              $row = $r[2].'|'.$r[1];
              if ($row == $groupby)
               {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
              else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
           }
          }
          $sout .= '</select>';
    return $sout;
    }     
    
    private function getSortBy($sorton)
    {   $sout = '';
        $res = $this->nFields();
          $sout .= '<select name="gsort">';
          $sout .= '<option value=""></option>';
          foreach ($res as $r){
          $row = $r[2].'|'.$r[1];
          if ($row == $sorton)
           {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
          else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
          }
          $sout .= '</select>';
    return $sout;
    } 

    private function getShopName($mshop)
    {   $sout = '';
        $res = $this->nFields();
        $foo = array();
        if($res[100][6]){
        $foo = Db::getInstance()->ExecuteS("SELECT  `id_shop`,`name` FROM `"._DB_PREFIX_."shop`");
        } 
          $sout .= '<select name="mshop">';
          $sout .= '<option value="0|All">0|All</option>';
          foreach ($foo as $r){
          $row = $r['id_shop'].'|'.$r['name'];
          if ($row == $mshop)
           {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
          else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
          }
          $sout .= '</select>';
    return $sout;
    } 

    private function getOrderStateFrom($ostfrom)
    {   $sout = '';
        $lng = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $res = Db::getInstance()->ExecuteS("SELECT  `id_order_state`,`name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_lang` = ".$lng);
          $sout .= '<select name="ostfrom">';
          $sout .= '<option value="0|All">0|All</option>';
          foreach ($res as $r){
          $row = $r['id_order_state'].'|'.$r['name'];
          if ($row == $ostfrom)
           {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
          else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
          }
          $sout .= '</select>';
    return $sout;
    } 

    private function getOrderStateTo($ostto)
    {   $sout = '';
        $lng = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $res = Db::getInstance()->ExecuteS("SELECT  `id_order_state`,`name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_lang` = ".$lng);
          $sout .= '<select name="ostto">';
          $sout .= '<option value=""></option>';
          foreach ($res as $r){
          $row = $r['id_order_state'].'|'.$r['name'];
          if ($row == $ostto)
           {$sout .= '<option value="'.(string)($row).'" selected="selected">'.(string)($row).'</option>'; }
          else {$sout .= '<option value="'.(string)($row).'">'.(string)($row).'</option>';}
          }
          $sout .= '</select>';
    return $sout;
    } 
    
  	private function getOrderState($order)
    {   $lng = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $res = Db::getInstance()->ExecuteS("SELECT  `id_order_state`,`name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_lang` = ".$lng);
        $selord = explode(',',$order);
        $sout = '';
          foreach ($res as $row){
          $sel = '';
          if(in_array($row['id_order_state'], $selord)) {$sel = 'selected="selected"';};
           $sout .= '<option value="'.(string)($row['id_order_state']).'"'.$sel.'>'.(string)($row['name']).'</option>'; 
          }
    return $sout;
    }
 //------------------------------------------------------------------------------------------------------------------------
 	public function getNVNContent()
	{
        return @$this->file_get_contents_curl("http://www.netvianet.com/nvn_update/export_orders_production_ver.html");
    }    
//------------------------------------------------------------------------------------------------------------------------
    
//************************************************************************************************************************
 	public function setFields($source) 
//************************************************************************************************************************
	{   $verOn = $this->versionPS();
        $out=''; $fon = $this->nFields(); //$fon = $this->subval_sort($fon,1);
        if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){
         $jeon = explode(',',Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON2'));}
        else{$jeon = explode(',',Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON1'));}
        if($source){
            foreach($fon as $indx){
              //if(!in_array($indx[2],$jeon) AND ($indx[3]== 1 OR $indx[3] == $verOn)){
              if(!in_array($indx[2],$jeon) AND $indx[6]== 1){
                $n = explode(' AS ',$indx[0]);
                if($n[0]==''){$n[1]='';}
                $out .= '<input type="button" title="Multi-line key: '.$n[1].'" id="b_'.$indx[2].'" value="'.$indx[1].'" onClick="javascript:SsetT(this);" class="bbutton" />';
               }
            }
        }
        if(!$source){
          foreach($jeon as $i){
            if(!empty($i)){$i=(int)$i-1;
               $n = explode(' AS ',$fon[$i][0]);
               if($n[0]==''){$n[1]='';}
               $out .= '<input type="button" title="Multi-line key: '.$n[1].'" id="b_'.$fon[$i][2].'" value="'.$fon[$i][1].'" onClick="javascript:SsetT(this);" class="bbutton" />';
              }
          }  
        }
        return $out;
    }
//************************************************************************************************************************
 	public function getContent()
//************************************************************************************************************************
	{   global $cookie;
        $output = '<h2>'.$this->displayName.'</h2>';
        
        if (Tools::getValue('jsetingx')=="jsetingxsend"){
            Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETX', Tools::getValue('altset'));
            return $output.$this->displayForm();
        }
        if (Tools::getValue('jshidex')=="jshidesend"){ 
            Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SHIDE', Tools::getValue('shide')); 
            return $output.$this->displayForm(); 
        }
        if(Tools::isSubmit('submitSetting')){
        
            if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){ $d = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING2');}
            else{$d = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING1');}
            $df = array();
            foreach (explode('‡', $d) as $cLine) { list ($cKey, $cValue) = explode('↔', $cLine, 2); $df[$cKey] = $cValue;}
        
            if (!$delimiter = Tools::getValue('delimiter') OR empty($delimiter)){
                $output .= '<div class="alert error">'.$this->l('Please fill in the "CSV Delimiter" field.').'</div>';
                return $output.$this->displayForm();
            }
            $ds =  'LASTIDORDER↔'.(int)Tools::getValue('lastid');
            $ds .= '‡DATEFROM↔'.Tools::getValue('datefrom');
            $ds .= '‡DATETO↔'.Tools::getValue('dateto');
            $ds .= '‡GROUPBY↔'.Tools::getValue('groupby');
            $ds .= '‡TOCOMMA↔'.(int)Tools::getValue('tocomma');
            $ds .= '‡LZERO↔'.(int)Tools::getValue('lzero');
            $ds .= '‡CSVDELIMITER↔'.$delimiter;
            $ds .= '‡ICONV↔'.Tools::getValue('iconvx');
            $ds .= '‡SAVEAS↔'.Tools::getValue('gsaveas');
            $ds .= '‡ONEFILE↔'.(int)Tools::getValue('onefile');
            $ds .= '‡TITLES↔'.(int)Tools::getValue('titles');
            $ds .= '‡EMAIL↔'.Tools::getValue('email');
            $ds .= '‡GSORT↔'.Tools::getValue('gsort');
            $ds .= '‡MSHOP↔'.Tools::getValue('mshop');
            $ds .= '‡INVOICE↔'.Tools::getValue('invoice');
			$ds .= '‡ESPUMA↔'.Tools::getValue('espuma');
			$ds .= '‡TAPIZADO↔'.Tools::getValue('tapizado');
			$ds .= '‡SOLDADURA↔'.Tools::getValue('soldadura');
			$ds .= '‡CORTESOFAS↔'.Tools::getValue('cortesofas');
			$ds .= '‡CORTEFUNDA↔'.Tools::getValue('cortefunda');
			$ds .= '‡COSTURA↔'.Tools::getValue('costura');
			$ds .= '‡PEGADO↔'.Tools::getValue('pegado');
			$ds .= '‡EMPAQUETADO↔'.Tools::getValue('empaquetado');
			$ds .= '‡EXPRESS↔'.Tools::getValue('express');
			$ds .= '‡VENCIMIENTO↔'.Tools::getValue('vencimiento');
            $ds .= '‡OSTFROM↔'.Tools::getValue('ostfrom');
            $ds .= '‡OSTTO↔'.Tools::getValue('ostto');
            $ds .= '‡OSTMAIL↔'.Tools::getValue('ostmail');
            $ostat = array();
            if(Tools::getIsset('ordstatus')){ 
             foreach (Tools::getValue('ordstatus') as $selectedOption)
              {$ostat[] = (int)$selectedOption;}
            }
            $ds .= '‡ORDERSTATUS↔'.implode(',',$ostat);
            $ds .= '‡FTPSERVER↔'.trim(Tools::getValue('ftpserver'));
            $ds .= '‡FTPFILE↔'.trim(Tools::getValue('ftpfile'));
            $ds .= '‡FTPUSER↔'.trim(Tools::getValue('ftpuser'));
            $pp = Tools::getValue('ftppass');
            if(!empty($pp)){
              $ds .= '‡FTPPASS↔'.$this->encryptIt($pp);    
            }else {
              $ds .= '‡FTPPASS↔'.$df['FTPPASS'];//puvodni pass
            }
            $ds .= '‡AUTODATEFR↔'.Tools::getValue('autodatefr');
            $ds .= '‡DATEFORMAT↔'.Tools::getValue('dateformat');
            $ds .= '‡ONEORDER1FILE↔'.(int)Tools::getValue('oneorder1file');
            $xr='';
            foreach (Tools::getValue('ixml') as $rewritex){ 
                $rewritex = trim($rewritex);
                if(preg_match('/[^a-z_0-9]/i', $rewritex))
                {// not valid string
                  $xr .= '↔';  
                }else{
                  $xr .= $rewritex.'↔';   
                }
            }
            $xr = rtrim($xr,'↔');
            $xr .= '‡';
            foreach (Tools::getValue('icsv') as $rewritex){ 
                $xr .= trim($rewritex).'↔';   
            }
            $xr = rtrim($xr,'↔');
            if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){
             Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_REWRITE2',$xr);
             Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING2',$ds);}
            else{
             Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_REWRITE1',$xr);
             Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING1',$ds);}
            
            $searchId = (int)$cookie->id_employee;
            $result = Db::getInstance()->getRow("SELECT `id_profile` FROM `"._DB_PREFIX_."employee` WHERE `id_employee` = ".$searchId);
            // $result['id_profile'] = 1; // disable check admin 
            if ($result['id_profile'] <> 1)
            {
             $output .= '<div class="alert error">'.$this->l('Only employee id_profile = 1 (Administrator) may change the "Fields for Export" values.').'</div>';
            }
            else {
//PS 1.2.4 nema od.reduction_percent,od.reduction_amount AS reduction_amount,od.group_reduction AS group_reduction
                $uxml = str_replace(array("\r\n", "\n", "\r"),"CR~LF", Tools::getValue('userxml1').'↔‡↔'.Tools::getValue('userxml2').'↔‡↔'.Tools::getValue('userxml3'));
                $uxml = str_replace('<','&lt;',$uxml);$uxml = str_replace('>','&gt;',$uxml);
                if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){
                 Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_FIELDSON2', trim(Tools::getValue('gonfields'),','));
                 Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SPECFILD2',str_replace(array("\r\n", "\n", "\r")," ", Tools::getValue('specfield')));
                 Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_CALCFILD2',Tools::getValue('calcfield1').'‡'.Tools::getValue('calcfield2').'‡'.Tools::getValue('calcfield3').'‡'.Tools::getValue('calcfield4').'‡'.Tools::getValue('calcfield5').'‡'.Tools::getValue('calcfield6').'‡'.Tools::getValue('calcfield7').'‡'.Tools::getValue('calcfield8'));
                 Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_USERXML2',$uxml);
                 }
                else{
                Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_FIELDSON1', trim(Tools::getValue('gonfields'),','));
                Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SPECFILD1',str_replace(array("\r\n", "\n", "\r")," ", Tools::getValue('specfield')));
                Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_CALCFILD1',Tools::getValue('calcfield1').'‡'.Tools::getValue('calcfield2').'‡'.Tools::getValue('calcfield3').'‡'.Tools::getValue('calcfield4').'‡'.Tools::getValue('calcfield5').'‡'.Tools::getValue('calcfield6').'‡'.Tools::getValue('calcfield7').'‡'.Tools::getValue('calcfield8'));
                Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_USERXML1',$uxml);
                 }
           }
           $output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';      
        }
        elseif(Tools::isSubmit('submitExport')){
    	  $output .= $this->export(null);
        }
        elseif(Tools::getValue('tools')=='deleted'){  
           $output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Exports files was deleted').'</div>';
        }
        
        return $output.$this->displayForm();
    }
		
	//****************************************************************************************************************
	private function stripAccents($string){//**** Quitar acentos *****
	//****************************************************************************************************************
		$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
        $replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
        return utf8_encode(strtr(utf8_decode($string), utf8_decode($tofind), $replac));
	}
	
	public function export($act = null){
$out='';
$lng = (int)(Configuration::get('PS_LANG_DEFAULT'));
$verOn = $this->versionPS();
$fon = $this->nFields();
if($act == null){$set = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX');}
if($act == 'set1'){$set = 0;}
if($act == 'set2'){$set = 1;}
if($set){
	$jeon = explode(',',Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON2'));
	$mline = Configuration::get('EXPORT_ORDERS_PRODUCTION_SPECFILD2');
	$cfline = Configuration::get('EXPORT_ORDERS_PRODUCTION_CALCFILD2');
    $usxmline = Configuration::get('EXPORT_ORDERS_PRODUCTION_USERXML2');
	$ds = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING2');
}else{
    $jeon = explode(',',Configuration::get('EXPORT_ORDERS_PRODUCTION_FIELDSON1'));
    $mline = Configuration::get('EXPORT_ORDERS_PRODUCTION_SPECFILD1');
    $cfline = Configuration::get('EXPORT_ORDERS_PRODUCTION_CALCFILD1');
    $usxmline = Configuration::get('EXPORT_ORDERS_PRODUCTION_USERXML1');
    $ds = Configuration::get('EXPORT_ORDERS_PRODUCTION_SETTING1');
}
$usxmline=str_replace("CR~LF","\r\n",$usxmline);$usxmline=str_replace('&lt;','<',$usxmline);$usxmline=str_replace('&gt;','>',$usxmline);     
$userxml = explode('↔‡↔',$usxmline);    
$df = array();$ttl = array();
foreach (explode('‡', $ds) as $cLine) {
	list ($cKey, $cValue) = explode('↔', $cLine, 2);
    $df[$cKey] = $cValue;}
$ftppass =  $this->decryptIt($df['FTPPASS']); 
       
$fi=array();$kni=array();
if(!empty($jeon[0])){//pole do selectu
	foreach($jeon as $i){ 
		$fi[] = $fon[$i-1][0];
		$foo = explode(' AS ',$fon[$i-1][0]);
		$kni[] = $foo[1];
		if ($df['ICONV'] <> "" AND $df['ICONV'] <> "UTF-8"){ $ttl[] = iconv("UTF-8", $df['ICONV']."//TRANSLIT", $fon[$i-1][1]);}
		else {$ttl[] = $fon[$i-1][1];}
	}
}
$kni = array_flip($kni);
$ktyp = array();
foreach($fon as $k=>$v){//multiline a calc do selectu a typy
	$sear = explode(' AS ',$v[0]);
	$ktyp[$sear[1]] = $v[5];
	$pos = strpos($mline, $sear[1]);
	if ($pos !== false) {$fi[] = $v[0];} 
	$pos = strpos($cfline, $sear[1]);//udela se cely najednou jako multiline
	if ($pos !== false) {$fi[] = $v[0];}
} 
if(count($fi)){$dafi = array_fill_keys($fi,'');}
else{
	$out = '<div class="alert error">'.$this->l('Sorry, but no fields for export selected.').'</div>'; 
	return $out; } ;
$mshop = explode('|',$df['MSHOP']);     
$filterMshop = ''; 
$idshop = '';
if($fon[100][6]){$idshop = 'oo.id_shop,';}
if($mshop[0]){$filterMshop = ' AND id_shop = '.$mshop[0]; }
$filterInv = '';
if($df['INVOICE']){$filterInv = ' AND invoice_number > 0';}
$filterFr = '';
if(!empty($df['DATEFROM'])) {$filterFr = ' AND date_add >= "'.$df['DATEFROM'].' 00:00:00"';}
$filterTo = '';
if(!empty($df['DATETO'])) {$filterTo = ' AND date_add < "'.$df['DATETO'].' 23:59:59"';}
if($df['AUTODATEFR']<>''){
	$filterTo = '';
	$filterFr = ' AND date_add >= "'.date('Y-m-d',(strtotime ( '-'.$df['AUTODATEFR'].' day' , strtotime ( date('Y-m-d'))))).' 00:00:00"';    
}
$ordstatus = '';
if ($df['ORDERSTATUS'] <> '') { $ordstatus = ' WHERE id_order_state IN ('.$df['ORDERSTATUS'].')';}
$sq = 'SELECT tt.id_order
        FROM '._DB_PREFIX_.'order_history tt
        INNER JOIN
        (SELECT id_order, MAX(date_add) AS MaxDateTime
        FROM '._DB_PREFIX_.'order_history
        GROUP BY id_order) groupedtt ON tt.id_order = groupedtt.id_order AND tt.date_add = groupedtt.MaxDateTime '.$ordstatus; 
$filterInIds = '';
if($resId = Db::getInstance()->ExecuteS($sq)){
	$filterInIds = ' AND id_order IN(';
    foreach($resId as $Ides){
		$filterInIds .= $Ides['id_order'].',';  
    }
    $filterInIds = substr($filterInIds,0,strlen($filterInIds)-1).')';   
}
if ($df['ORDERSTATUS'] <> '' AND $filterInIds == ''){
	$out = '<div class="alert error">'.$this->l('Sorry, but there are NO NEW orders to export with this filters and options setting.').'</div>'; 
	return $out; } ;
$oneorder1file = '';
if ($df['ONEORDER1FILE']){$oneorder1file = ' LIMIT 1';}	 
$sq = 'SELECT id_order FROM '._DB_PREFIX_.'orders WHERE id_order  > '.$df['LASTIDORDER'].$filterFr.$filterTo.$filterMshop.$filterInv.$filterInIds.' ORDER BY id_order ASC'.$oneorder1file;
if(!$resId = Db::getInstance()->ExecuteS($sq)){
	$out = '<div class="alert error">'.$this->l('Sorry, but there are NO NEW orders to export with this filters and options setting.').'</div>'; 
return $out; } ;
		$tabf = $this->tFields();
		foreach($dafi as $a=>$l){
			$asname = explode(' AS ',$a);
			$dafi[$a] = $asname[1]; 
			$tbl = explode('.',$a);
			foreach($tabf as $tf=>$t){ 
				if($tf==$tbl[0] AND !in_array($a,$tabf[$tf])){$tabf[$tf][] = $a;}
			}
		}
		$dafi = array_flip($dafi);

		$megapole = array();
		foreach($resId as $Ides){ //all id_order for LASTIDORDER and DATEFROM DATETO  and ORDERSTATUS  filter
			foreach($dafi as $k=>$v){$dafi[$k]='';}
			$sq = 'SELECT '.$this->myFields($tabf,'oo').'oo.id_order,'.$idshop.'oo.id_customer,oo.id_address_delivery,oo.id_address_invoice,oo.id_currency,oo.id_carrier FROM '._DB_PREFIX_.'orders AS oo WHERE oo.id_order = '.$Ides['id_order'];
			//print('<br/>'.$sq);
			$resoo = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resoo);
			if($this->myFields($tabf,'sl')<>''){// aby se to nedelalo navic nikde jinde se to nepotrebuje
				$sq = 'SELECT oh.id_order_state FROM '._DB_PREFIX_.'order_history AS oh WHERE oh.id_order = '.$resoo['id_order'].' ORDER BY oh.date_add DESC';
				$resfoo = Db::getInstance()->getRow($sq);//getrow veme jen max date
				$sq = 'SELECT '.$this->myFields($tabf,'sl').'sl.id_order_state FROM '._DB_PREFIX_.'order_state_lang AS sl WHERE sl.id_order_state = '.$resfoo['id_order_state'].' AND sl.id_lang = '.$lng;
				$ressl = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$ressl); 
			}
			if($this->myFields($tabf,'sc')<>''){
				$sq = 'SELECT '.$this->myFields($tabf,'sc').' sc.id_order_history FROM '._DB_PREFIX_.'order_history AS sc WHERE sc.id_order = '.$resoo['id_order'].' AND (sc.id_order_state BETWEEN 25 AND 28 OR sc.id_order_state=15 OR sc.id_order_state=60) ORDER BY sc.date_add DESC';
				$ressc = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$ressc); 
			}	 
			if($this->myFields($tabf,'sh')<>''){
				$sq = 'SELECT '.$this->myFields($tabf,'sh').'sh.id_shop FROM '._DB_PREFIX_.'shop AS sh WHERE sh.id_shop = '.$resoo['id_shop'];
				$ressh = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$ressh);       
			}
			if($this->myFields($tabf,'vo')<>''){ 
				$sq = 'SELECT '.$this->myFields($tabf,'vo').'vo.id_order_cart_rule FROM '._DB_PREFIX_.'order_cart_rule AS vo WHERE vo.id_order = '.$resoo['id_order'];
				$resvo = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$resvo);
			}        
			if($verOn==2){$sq = 'SELECT '.$this->myFields($tabf,'cu').'cu.id_customer FROM '._DB_PREFIX_.'customer AS cu WHERE cu.id_customer = '.$resoo['id_customer'];}
			else{$sq = 'SELECT '.$this->myFields($tabf,'cu').'cu.id_customer,cu.id_default_group FROM '._DB_PREFIX_.'customer AS cu WHERE cu.id_customer = '.$resoo['id_customer'];}
			//print('<br/>'.$sq);
			$rescu = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$rescu);
			if($verOn==2){$sq = 'SELECT '.$this->myFields($tabf,'cg').'cg.id_customer,cg.id_group FROM '._DB_PREFIX_.'customer_group AS cg WHERE cg.id_customer = '.$rescu['id_customer'];}
			else{$sq = 'SELECT '.$this->myFields($tabf,'cg').'cg.id_customer,cg.id_group FROM '._DB_PREFIX_.'customer_group AS cg WHERE cg.id_customer = '.$rescu['id_customer'].' AND cg.id_group = '.$rescu['id_default_group'];}
			//print('<br/>'.$sq);
			$rescg = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$rescg);
			/*$sq = 'SELECT '.$this->myFields($tabf,'gl').'gl.id_group FROM '._DB_PREFIX_.'group_lang AS gl WHERE gl.id_group = '.$rescg['id_group'].' AND gl.id_lang = '.$lng;
			$resgl = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resgl);*/
			$sq = 'SELECT '.$this->myFields($tabf,'ad').'ad.id_country,ad.id_state FROM '._DB_PREFIX_.'address AS ad WHERE ad.id_address = '.$resoo['id_address_delivery'];
			$resad = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resad);
			//print('<br/>'.$sq);
			$sq = 'SELECT '.$this->myFields($tabf,'cd').'cd.id_country FROM '._DB_PREFIX_.'country_lang AS cd WHERE cd.id_country = '.$resad['id_country'].' AND cd.id_lang = '.$lng;
			$rescd = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$rescd);
			$sq = 'SELECT '.$this->myFields($tabf,'sd').'sd.id_state FROM '._DB_PREFIX_.'state AS sd WHERE sd.id_state = '.$resad['id_state'];
			$ressd = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$ressd);
			$sq = 'SELECT '.$this->myFields($tabf,'yd').'yd.id_country FROM '._DB_PREFIX_.'country AS yd WHERE yd.id_country = '.$resad['id_country'];
			$resyd = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resyd);
			$sq = 'SELECT '.$this->myFields($tabf,'ai').'ai.id_country,ai.id_state FROM '._DB_PREFIX_.'address AS ai WHERE ai.id_address = '.$resoo['id_address_invoice'];
			$resai = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resai);
			$sq = 'SELECT '.$this->myFields($tabf,'ci').'ci.id_country FROM '._DB_PREFIX_.'country_lang AS ci WHERE ci.id_country = '.$resai['id_country'].' AND ci.id_lang = '.$lng;
			$resci = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resci);
			$sq = 'SELECT '.$this->myFields($tabf,'si').'si.id_state FROM '._DB_PREFIX_.'state AS si WHERE si.id_state = '.$resai['id_state'];
			$ressi = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$ressi);
			$sq = 'SELECT '.$this->myFields($tabf,'yi').'yi.id_country FROM '._DB_PREFIX_.'country AS yi WHERE yi.id_country = '.$resad['id_country'];
			$resyi = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resyi);
			$sq = 'SELECT '.$this->myFields($tabf,'cr').'cr.id_currency FROM '._DB_PREFIX_.'currency AS cr WHERE cr.id_currency = '.$resoo['id_currency'];
			$rescr = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$rescr);
			$sq = 'SELECT '.$this->myFields($tabf,'ca').'ca.id_carrier FROM '._DB_PREFIX_.'carrier AS ca WHERE ca.id_carrier = '.$resoo['id_carrier'];
			$resca = Db::getInstance()->getRow($sq);
			$dafi=$this->toDafiRow($dafi,$resca);
       
			/*$sq = 'SELECT '.$this->myFields($tabf,'me').'me.id_message,me.message FROM '._DB_PREFIX_.'message AS me WHERE me.id_order = '.$resoo['id_order'];*/
	   
			$sq = 'SELECT ch.id_customer_thread FROM '._DB_PREFIX_.'customer_thread AS ch WHERE ch.id_order = '.$resoo['id_order'];
			$resch = Db::getInstance()->ExecuteS($sq);
			if (count($resch)>0){
				foreach($resch as $imp){
					$sq = 'SELECT '.$this->myFields($tabf,'me').'me.id_customer_message,me.message FROM '._DB_PREFIX_.'customer_message AS me WHERE me.id_customer_thread = '.$imp['id_customer_thread'];
					$resme = Db::getInstance()->ExecuteS($sq);
					$pom = array('message'=>'');
					foreach($resme as $ime){$pom['message'] .= $ime['message'].'. =>> ';}
					$pom['message'] = str_replace(chr(0x0A),'|',$pom['message']);$pom['message'] = str_replace(chr(0x0D),'|',$pom['message']);
					$pom['message'] = html_entity_decode($pom['message'],ENT_QUOTES,'UTF-8');
					$dafi=$this->toDafiRow($dafi,$pom);
				}
			}else{
				$pom='';
				$dafi=$this->toDafiRow($dafi,$pom);
			}
			if($verOn==4){ //from 1.4.6.2 to 1.4.9x only
				$sq = 'SELECT '.$this->myFields($tabf,'ot').'ot.id_order FROM '._DB_PREFIX_.'order_tax AS ot WHERE ot.id_order = '.$resoo['id_order'];
				$resot = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$resot);
			}
			
			$sq = 'SELECT '.$this->myFields($tabf,'oc').'oc.id_order  FROM '._DB_PREFIX_.'order_comment AS oc WHERE oc.id_order = '.$resoo['id_order'];
			$resoc = Db::getInstance()->getRow($sq);
         	$dafi=$this->toDafiRow($dafi,$resoc);
			
			$sq = 'SELECT '.$this->myFields($tabf,'od').'od.product_id,od.product_attribute_id,od.id_order_detail,od.id_order FROM '._DB_PREFIX_.'order_detail AS od WHERE od.id_order = '.$resoo['id_order'];	   
			$resod = Db::getInstance()->ExecuteS($sq);
			foreach($resod as $iod){
				$dafi=$this->toDafiRow($dafi,$iod);
				$sq = 'SELECT '.$this->myFields($tabf,'pr').'pr.id_manufacturer,pr.id_supplier FROM '._DB_PREFIX_.'product AS pr WHERE pr.id_product = '.$iod['product_id'];
				$respr = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$respr);
				$sq = 'SELECT '.$this->myFields($tabf,'mf').'mf.id_manufacturer FROM '._DB_PREFIX_.'manufacturer AS mf WHERE mf.id_manufacturer = '.$respr['id_manufacturer'];
				if(isset($respr['id_manufacturer'])){
					$resmf = Db::getInstance()->getRow($sq);
					$dafi=$this->toDafiRow($dafi,$resmf);
				}
				$sq = 'SELECT '.$this->myFields($tabf,'su').'su.id_supplier FROM '._DB_PREFIX_.'supplier AS su WHERE su.id_supplier = '.$respr['id_supplier'];
				if(isset($respr['id_supplier'])){
					$ressu = Db::getInstance()->getRow($sq);
					$dafi=$this->toDafiRow($dafi,$ressu);
				} 
				$sq = 'SELECT '.$this->myFields($tabf,'dt').'dt.id_tax  FROM '._DB_PREFIX_.'order_detail_tax AS dt WHERE dt.id_order_detail = '.$iod['id_order_detail'];
				$resdt = Db::getInstance()->getRow($sq);
				$dafi=$this->toDafiRow($dafi,$resdt); 
				$sq = 'SELECT '.$this->myFields($tabf,'tx').'tx.id_tax  FROM '._DB_PREFIX_.'tax AS tx WHERE tx.id_tax = '.$resdt['id_tax'];
				if(isset($resdt['id_tax'])){
					$restx = Db::getInstance()->getRow($sq);
					$dafi=$this->toDafiRow($dafi,$restx);
				} 
				//******************************************************************************
				//*********Código Añadido para descargar los artículos de megaproduct **********
				//******************************************************************************
				/*$sq='Select id_cart from ps_orders where ps_orders.id_order='.$iod['id_order'];
				$resnuevo=DB::getInstance()->getrow($sq);
				$sq='Select '.$this->myFields($tabf,'mp').'mp.id_megacart from '._DB_PREFIX_.'megaproductcart as mp where mp.id_cart='.$resnuevo['id_cart'].' and mp.id_product='.$iod['product_id'].' and mp.id_product_attribute='.$iod['product_attribute_id'];
				//$resmp=DB::getInstance()->getrow($sq);
				$resmp = Db::getInstance()->ExecuteS($sq);
				if (count($resmp)>0){
					foreach($resmp as $imp){
						$dafi=$this->toDafiRow($dafi,$imp);
						$megapole[]=$dafi; 
					}
				}else{
					$attr = array('attributes'=>'','length'=>'','width'=>'','height'=>'','quantity'=>'');
					$dafi=$this->toDafiRow($dafi,$attr);
					$megapole[]=$dafi; 
				}*/
				//Se ha cambiado el código para añadir las observaciones de los artículos a la parte de los atributos de megaproduct 
				$sq='Select id_cart from ps_orders where ps_orders.id_order='.$iod['id_order'];
				$resnuevo=DB::getInstance()->getrow($sq);
	  
				$sq='Select '.$this->myFields($tabf,'mp').'mp.id_megacart,(@rownum:=@rownum+1) AS posicion from (SELECT @rownum:=0) R, '._DB_PREFIX_.'megaproductcart as mp where mp.id_cart='.$resnuevo['id_cart'].' and mp.id_product='.$iod['product_id'].' and mp.id_product_attribute='.$iod['product_attribute_id'];
				$resmp = Db::getInstance()->ExecuteS($sq);
				if (count($resmp)>0){
					foreach($resmp as $imp){
						$sq = "SELECT ".$this->myFields($tabf,'cust')."a.id_product,a.id_customization,(@rownum:=@rownum+1) AS posicion FROM (SELECT @rownum:=0) R, "._DB_PREFIX_."customization a, "._DB_PREFIX_."customized_data cust, "._DB_PREFIX_."cart_product c WHERE a.id_customization=cust.id_customization AND a.id_cart=c.id_cart AND a.id_product=c.id_product AND c.id_cart='" .$resnuevo['id_cart']."' and a.id_product=".$iod['product_id'];
						
						$rescust = DB::getInstance()->ExecuteS($sq);
						if (count($rescust)>0){
							$attr = array('attributes'=>'','length'=>'','width'=>'','height'=>'','personalization'=>'','quantity'=>'');
							$attr['attributes'] .=$imp['attributes'].'-';
							foreach($rescust as $cust){
							
								if ($imp['posicion']==$cust['posicion']){
									$attr['attributes'] =$attr['attributes'] . $cust['customized_data'].' ';
								}
							}
							$attr['length'] .=$imp['length'];
							$attr['width'] .=$imp['width'];
							$attr['height'] .=$imp['height'];
							$attr['personalization'] .=$imp['personalization'];
							$attr['quantity'] .=$imp['quantity'];
						
							$dafi=$this->toDafiRow($dafi,$attr);
							$megapole[]=$dafi;
						}else{
							$dafi=$this->toDafiRow($dafi,$imp);
							$megapole[]=$dafi; 
						}
					}
				}else{
					$sq = "SELECT ".$this->myFields($tabf,'cust')."a.id_product,a.id_customization,(@rownum:=@rownum+1) AS posicion FROM (SELECT @rownum:=0) R, "._DB_PREFIX_."customization a, "._DB_PREFIX_."customized_data cust, "._DB_PREFIX_."cart_product c WHERE a.id_customization=cust.id_customization AND a.id_cart=c.id_cart AND a.id_product=c.id_product AND c.id_cart='" .$resnuevo['id_cart']."' and a.id_product=".$iod['product_id'];
					$rescust = DB::getInstance()->ExecuteS($sq);
					if (count($rescust)>0){
						$attr = array('attributes'=>'','length'=>'','width'=>'','height'=>'','personalization'=>'','quantity'=>'');
						foreach($rescust as $cust){
							$attr['attributes'] =$attr['attributes'] . $cust['customized_data'].' ';
						}
						$dafi=$this->toDafiRow($dafi,$attr);
						$megapole[]=$dafi;
					}else{
						$attr = array('attributes'=>'','length'=>'','width'=>'','height'=>'','personalization'=>'','quantity'=>'');
						$dafi=$this->toDafiRow($dafi,$attr);
						$megapole[]=$dafi; 
					}
				}
			 
				//******************************************************************************
				//******************************************************************************
				//******************************************************************************
				//$megapole[]=$dafi; 
			}
		}
				
		if(!empty($df['GROUPBY'])){
			$srt = explode('|',$df['GROUPBY']);
			$gkey = explode(' AS ',$fon[$srt[0]-1][0]);
			$qkey = explode(' AS ',$fon[51][0]);//product_quantity
			$pkey = explode(' AS ',$fon[52][0]);//product_price
			$wkey = explode(' AS ',$fon[53][0]);//product_weight
			if(!array_key_exists($gkey[1],$dafi)){
				$out = '<div class="alert error">'.$this->l('Sorry, group field for "Group by" is missing in "Fields for Export" ').'("'.$gkey[1].'")</div>'; 
				return $out; 
            }else{
				if(!array_key_exists($qkey[1],$dafi) OR !array_key_exists($pkey[1],$dafi) OR !array_key_exists($wkey[1],$dafi)){
					$out = '<div class="alert error">'.$this->l('NOTICE: All this fields must be in "Fields for Export" for correct totals: ').'("'.$qkey[1].'", "'.$pkey[1].'", "'.$wkey[1].'")</div>';   
				}
				$megapole = $this->subval_sort($megapole,$gkey[1]);
				$megapole = $this->group_by($megapole,$dafi,$gkey[1],$qkey[1],$pkey[1],$wkey[1]);
			}
        }   
		if(!empty($df['GSORT'])){
			$srt = explode('|',$df['GSORT']);
			$sortkey = explode(' AS ',$fon[$srt[0]-1][0]);
			if(array_key_exists($sortkey[1],$dafi)){ $megapole = $this->subval_sort($megapole,$sortkey[1]);}
		}
		//--------- save
		$mlkey = explode(' AS ', $fon[84][0]);//multilinekey
		$cf1key = explode(' AS ',$fon[85][0]);//caclfield1
		$cf2key = explode(' AS ',$fon[86][0]);//caclfield2
		$cf3key = explode(' AS ',$fon[87][0]);//caclfield3
		$cf4key = explode(' AS ',$fon[88][0]);//caclfield4
		$cf5key = explode(' AS ',$fon[89][0]);//caclfield5
		$cf6key = explode(' AS ',$fon[90][0]);//caclfield6
		$cf7key = explode(' AS ',$fon[91][0]);//caclfield7
		$cf8key = explode(' AS ',$fon[92][0]);//caclfield8
		if(array_key_exists($cf1key[1],$dafi) OR array_key_exists($cf2key[1],$dafi) OR array_key_exists($cf3key[1],$dafi) OR array_key_exists($cf4key[1],$dafi)  OR array_key_exists($cf5key[1],$dafi)  OR array_key_exists($cf6key[1],$dafi)  OR array_key_exists($cf7key[1],$dafi)  OR array_key_exists($cf8key[1],$dafi))
		{
			include('evalmath/evalmath.class.php');           
			$me = new EvalMath;$me->suppress_errors = true; 
		}

		$cl = 0;$lzp = "~ZERO~";$lzk = "ZE~RO";
		$ext = '.'.$df['SAVEAS'];
		$onef = $df['ONEFILE'] ? $ext : "_".time().$ext;
		$fname = "orders-".date("Y-m-d").$onef ;		
		$exp_path = dirname(__FILE__)."/download/".$fname ;
		$fe = file_exists($exp_path);
		if($fe AND $df['SAVEAS'] <> 'xlsx' AND $df['SAVEAS'] <> 'extra.add' AND $df['SAVEAS'] <> 'csv'){
			$fvelik = filesize($exp_path);
			$odebrat = 0;
			
			////final de archivo
			if($df['SAVEAS'] == 'html'){$odebrat = mb_strlen($this->ht3);}
			////
			
			if($df['SAVEAS'] == 'xml'){$odebrat = mb_strlen($this->xme);}
			if($df['SAVEAS'] == 'my.xml'){$odebrat = mb_strlen($userxml[2]);}
			$fdata = file_get_contents($exp_path, NULL, NULL, -1, $fvelik - $odebrat);
			file_put_contents($exp_path, $fdata);
		}

		if($df['SAVEAS'] <> 'xlsx' AND $df['SAVEAS'] <> 'extra.add'){$fp = fopen($exp_path, 'a+');}
    
		if ($df['TITLES'] AND !$fe AND $df['SAVEAS']=='csv'){ fwrite($fp, $this->csvstr($ttl,$df['CSVDELIMITER']));}
		
		///escribe incio de archivo y y cabecera de columnas ttl[]
		if ($df['TITLES'] AND !$fe AND $df['SAVEAS']=='html'){ 
			fwrite($fp, $this->ht1($df['ICONV']));
			fwrite($fp, $this->ht2a($ttl,$cl));$cl++;
		}
		///
		
		if (!$fe AND $df['SAVEAS']=='xml') { fwrite($fp, $this->hxml($df['ICONV']));}
		if (!$fe AND $df['SAVEAS']=='my.xml') { fwrite($fp, $userxml[0]);}		
		if($df['SAVEAS'] == 'xlsx'){
			include('xlsx/PHPExcel.php');
			$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
			//$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM; 
			if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod)) {
				//die($cacheMethod . " caching method is not available" . EOL);
			} 
			if(!$fe){//neexistuje
			
				$objxlsx = new PHPExcel;
				$objxlsx->getProperties()->setCreator('netvianet.com export orders module');
				$objxlsx->getProperties()->setLastModifiedBy('netvianet.com export orders module');
				$objxlsx->getProperties()->setTitle('export_orders_production');
				$objxlsx->removeSheetByIndex(0);
				$listxlsx = $objxlsx->createSheet();$listxlsx->setTitle('Todo');
		  		$objxlsx->setActiveSheetIndex(0);
		 				     
				$i=0;$xr=1;
				foreach($ttl as $k=>$v){
			 
					$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i+2,$xr,$v); 
					$objxlsx->getActiveSheet()->getStyleByColumnAndRow($i+2,$xr)->getFont()->setBold(true);
					$objxlsx->getActiveSheet()->getStyleByColumnAndRow($i+2,$xr)->getFont()->getColor()->setARGB('003030CF');
					$objxlsx->getActiveSheet()->getStyleByColumnAndRow($i+2,$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
					$objxlsx->getActiveSheet()->getStyleByColumnAndRow($i,$xr)->getFill()->getStartColor()->setARGB('00FBF4A4');
					$objxlsx->getActiveSheet()->getPageMargins()->setTop(0.25);
					$objxlsx->getActiveSheet()->getPageMargins()->setRight(0.05);
					$objxlsx->getActiveSheet()->getPageMargins()->setLeft(0.05);
					$objxlsx->getActiveSheet()->getPageMargins()->setBottom(0.25);
					$objxlsx->getActiveSheet()->getPageMargins()->setHeader(0);
					$objxlsx->getActiveSheet()->getPageMargins()->setFooter(0);
					$objxlsx->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objxlsx->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
			
					if($i==0){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(4);
					}elseif ($i==1){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(4);	 
					}elseif ($i==2){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(6);
					}elseif ($i==3){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(3);	 
					}elseif ($i==4){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(3); 
					}elseif ($i==5){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(3);
					}elseif ($i==6){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif($i==15){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(3);
					}else if($i==16){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(4);
					}elseif($i==8 or $i==9 or $i==10){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(4);
					}elseif($i==11){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif ($i==12){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(43);
					}elseif($i==13){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(2);	 
					}elseif($i==14){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(4);
					}elseif($i==17){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif($i==18){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif($i==19){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);	
					}elseif($i==20){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif($i==21){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(0);
					}elseif($i==7){
						$objxlsx->getActiveSheet()->getColumnDimensionByColumn($i)->setWidth(53);
					}
					$i++;
				}
				$objxlsx->getActiveSheet()->getColumnDimensionByColumn(22)->setWidth(0);
				$objxlsx->getActiveSheet()->getColumnDimensionByColumn(23)->setWidth(0);
				$objxlsx->getActiveSheet()->freezePaneByColumnAndRow(2, 2);
				$xr++;
			}else{
				include('xlsx/PHPExcel/IOFactory.php');       
				$inputFileType = 'Excel2007';
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				$objxlsx = $objReader->load($exp_path);
				$objxlsx->getSheet(0);
				$xr = $objxlsx->getActiveSheet()->getHighestRow()+1;
			}
		}
		if($df['SAVEAS'] == 'xlsx'){
		$objxlsx->getActiveSheet()->getStyle('H2:H4364')->getAlignment()->setWrapText(true); 
		$objxlsx->getActiveSheet()->getStyle('M2:M4364')->getAlignment()->setWrapText(true); 
		}
	
		$nofila=false;
	
		foreach($megapole as $mk=>$mv){
			$mi = $mline; $ki = $kni;
			foreach($ki as $kk=>$kv){
				$ki[$kk] = $mv[$kk];
				if ($df['SAVEAS']=='csv' AND is_numeric(trim($ki[$kk])) AND $ktyp[$kk] == 3){$ki[$kk] = $lzp.trim($ki[$kk]).$lzk;}
				if ($df['TOCOMMA'] AND is_numeric($ki[$kk])){$ki[$kk] = str_replace('.',',',$ki[$kk]);}
				if ($df['DATEFORMAT']<>'' AND $ktyp[$kk] == 2) {$ki[$kk] =  date($df['DATEFORMAT'], strtotime($ki[$kk]));}
			}
			$cfx = explode('‡',$cfline);
			if(array_key_exists($mlkey[1],$dafi)){
				foreach($mv as $k=>$v){
					if ($df['TOCOMMA'] AND is_numeric($v)){$v = str_replace('.',',',$v);}
					$mi = str_replace($k,$v,$mi);
				}
				$ki[$mlkey[1]]=$mi;
			}
			if(array_key_exists($cf1key[1],$dafi)){
				$foo = $cfx[0];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf1key[1]]=$fu;
			}
			if(array_key_exists($cf2key[1],$dafi)){
				$foo = $cfx[1];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf2key[1]]=$fu;
			}        
			if(array_key_exists($cf3key[1],$dafi)){
				$foo = $cfx[2];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf3key[1]]=$fu;
			}         
			if(array_key_exists($cf4key[1],$dafi)){
				$foo = $cfx[3];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf4key[1]]=$fu;
			}
			if(array_key_exists($cf5key[1],$dafi)){
				$foo = $cfx[4];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf5key[1]]=$fu;
			}        
			if(array_key_exists($cf6key[1],$dafi)){
				$foo = $cfx[5];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf6key[1]]=$fu;
			}        
			if(array_key_exists($cf7key[1],$dafi)){
				$foo = $cfx[6];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf7key[1]]=$fu;
			}        
			if(array_key_exists($cf8key[1],$dafi)){
				$foo = $cfx[7];
				foreach($mv as $k=>$v){$foo = str_replace($k,$v,$foo);}
				$fu = @$me->evaluate($foo);
				if((string)$fu == '' AND $foo <> ''){$fu = $foo;}
				if ($df['TOCOMMA'] AND is_numeric($fu)){$fu = str_replace('.',',',$fu);}
				$ki[$cf8key[1]]=$fu;
			}        
			if($df['SAVEAS']=='csv'){
				$tr = $this->csvstr($ki,$df['CSVDELIMITER']);
				$tr = str_replace("CR~LF","\n",$tr);
				($df['LZERO']) ? $tr = str_replace($lzp,'"',$tr) : $tr = str_replace($lzp,"",$tr);
				($df['LZERO']) ? $tr = str_replace($lzk,'"',$tr) : $tr = str_replace($lzk,"",$tr);   
			}elseif($df['SAVEAS']=='html'){
				
				///escribe cuerpo de documento
				$tr = $this->ht2($ki,$cl);$cl++;
				$tr = str_replace("CR~LF","<br />",$tr);   
				////
				
			}elseif($df['SAVEAS']=='xml'){
				$tr = $this->rxml($ki);
				$tr = str_replace("CR~LF","\r\n",$tr);   
			}elseif($df['SAVEAS']=='my.xml'){
				$tr = $this->myxml($ki,$userxml[1]);
				$tr = str_replace("CR~LF","\r\n",$tr);   
			}else{$tr='';}
			
			if ($df['ICONV'] <> "" AND $df['ICONV'] <> "UTF-8"){$tr = iconv("UTF-8", $df['ICONV']."//TRANSLIT", $tr);}
			if($df['SAVEAS'] <> 'xlsx' AND $df['SAVEAS'] <> 'extra.add'){fwrite($fp, $tr);}
    
			if($df['SAVEAS'] == 'xlsx'){
				$i=2;
				foreach($ki as $kk=>$kv){
		  			 
					if ($kk=='attributes'){
						$val=explode('-',$kv);
						//print('<br/>total: '.count($val));
						$kva='';
						foreach($val as $value){
							if ($value!=''){
								$sq='SELECT * FROM '._DB_PREFIX_.'megaproductaddattr_lang pal,'._DB_PREFIX_.'megaproductaddattr pa WHERE pal.id_addattr=pa.id_addattr AND id_attribute='.$value.' AND id_addgroup=1548 AND id_lang=1';
								$resat1=Db::getInstance()->getRow($sq);
								
								if($resat1['title']!=''){
									$kva=$kva.$resat1['title'].'-';
								}else{
									$sq='SELECT name FROM '._DB_PREFIX_.'attribute_lang AS al WHERE al.id_attribute='.$value.' and al.id_lang=1';
									$resat=Db::getInstance()->getRow($sq);
									if ($resat['name']!=''){
										$kva=$kva.$resat['name'].'-';
									}else{
										$kva=$kva.$value;
									}
																		
								}
							}else{$kva='';}
						}

						$kv=$objxlsx->getActiveSheet()->getCellByColumnAndRow(6, $xr)->getFormattedValue(). ' ' .$kva;

						
						//Añade el color según el equipo de futbol
						if(strpos($kv, 'Juventus')){ //Busca el nombre del equipo
							$kv = str_replace ('Juventus' , 'Color 1: polipiel negro, Color 2; polipiel blanco' , $kv); //Reemplaza la cadena buscada por una nueva cadena
						}else if(strpos($kv, 'Real Madrid')){
							$kv = str_replace ('Real Madrid' , 'Color 1: polipiel morado Color: 2 polipiel blanco' , $kv);
						}else if(strpos($kv, 'Barcelona')){
							$kv = str_replace ('Barcelona' , 'Color 1: polipiel azul, Color 2: polipiel rojo)' , $kv);
						}else if(strpos($kv, 'Atletico de Madrid')){
							$kv = str_replace ('Atletico de Madrid' , 'Color 1: polipiel rojo, Color 2: polipiel blanco' , $kv);
						}else if(strpos($kv, 'Betis')){
							$kv = str_replace ('Betis' , 'Color 1: polipiel verde, Color 2: polipiel blanco' , $kv);
						}else if(strpos($kv, 'Inter de Milan')){
							$kv = str_replace ('Inter de Milan' , 'Color 1: polipiel azul, Color 2: Negro' , $kv);
						}else if(strpos($kv, 'Milan')){
							$kv = str_replace ('Milan' , 'Color 1: polipiel negro, Color 2: polipiel rojo' , $kv);
						}else if(strpos($kv, 'Celta')){
							$kv = str_replace ('Celta' , 'Color 1: polipiel azul, Color 2: polipiel blanco' , $kv);
						}else if(strpos($kv, 'Atletico de Bilbao')){
							$kv = str_replace ('Atletico de Bilbao' , 'Color 1: polipiel rojo Color 2: polipiel blanco' , $kv);
						}else{
							
						}
						
						$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(6,$xr,$kv); 	
						;  	
						
					} 
					
					if ($kk=='width' OR $kk=='height' OR $kk=='length'){$kv=intval($kv);}
						
					if($kk=='personalization'){
						if ($kv!='' && base64_decode($kv)!='a:0:{}'){
							$kvv=base64_decode($kv);
							$val=explode(';',$kvv);
							$val1=explode(':',$val[1]);
							$grosor=$objxlsx->getActiveSheet()->getCellByColumnAndRow(8, $xr)->getFormattedValue();
							$ancho=$objxlsx->getActiveSheet()->getCellByColumnAndRow(9, $xr)->getFormattedValue();
							$largo=$objxlsx->getActiveSheet()->getCellByColumnAndRow(10, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(8,$xr,0);
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(9,$xr,0);
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(10,$xr,0);
							$kva=' A: ' . $ancho .' cm- B: ' . $largo . ' cm- C: ' . $grosor . ' cm- D: ' . $val1[2] . ' cm';
							$kv=$objxlsx->getActiveSheet()->getCellByColumnAndRow(6, $xr)->getFormattedValue(). ' '. $kva;
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(6,$xr,$kv);
							$kv=$objxlsx->getActiveSheet()->getCellByColumnAndRow(7, $xr)->getFormattedValue(). ' '. $kva;
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(7,$xr,$kv);
							$kv=$kva;
							
						}						
					}
										
					if ($kk=='order_status'){
						if ($kv=="Producción CS"){
							$kv="CS ";
						}elseif($kv=="Producción CR"){
							$kv="CR". $i . " ". $xr;
						}elseif($kv=="Producción SS"){
							$kv="SS";
						}elseif($kv=="Producción SR"){
							$kv="SR". $i . " ". $xr;
						}elseif($kv=="Incidencia Fábrica"){
							$kv="Inc.F";
						}elseif($kv=="Incidencia Agencia"){
							$kv="Inc.S";
						}elseif($kv=="Producción Recogida en Fábrica"){
							$kv = 'RECOGIDA EN FÁBRICA =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
							$kv="RF";
						}
					}
		   
					if ($kk=='carrier_name'){
						if ($kv=='SEUR' or $kv=='Envío Estándar'){
							$kv="S";
						}
						if ($kv=='SEUR - EXPRESS' or $kv=='Envío EXPRESS'){
							$kv="EX"; 
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00bdbdbd');
							//$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFont()->getColor()->setRGB('FFFFFF');
						}
						if ($kv=='Medios Propios Sofás.'){
							$kv="MP"; 
						}
						if ($kv=='Medios Propios.'){
							$kv="MP";
						}
						if ($kv=='SEUR con Subida al domicilio' or $kv=='Envío con Subida al domicilio'){
							$kv = 'MOZO. Enviar por Seur =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
							$kv="S";
						}
						if (strpos($kv, "Baleares")!==false){
							$kv = 'BALEARES =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
							$kv = "S";
						}
						if (strpos($kv, "Tamdis")!==false){
							$kv = 'Embalar para Agencia. Envío TAMDIS =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
							$kv = "MP";
						}
					}
					
					if ($kk=='delivery_state'){
						if (strpos($kv, "Girona")!==false){
							$kv = 'GIRONA. Enviar por Seur =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
						}
						/*if (strpos($kv, "Barcelona")!==false){
							$kv = 'BARCELONA. Enviar por Seur =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
						}*/
					}
					
					if ($kk=='payment'){
						if (strpos($kv, "Amazon")!==false){
							$kv = 'AMAZON =>> ' . $objxlsx->getActiveSheet()->getCellByColumnAndRow(12, $xr)->getFormattedValue();
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(12,$xr,$kv);
							$kv ='Amazon MarketPlace';
						}
					}
		   			
					/*if ($kk=='product_name'){
						$kvmdf = strtolower($this->stripAccents($kv));
			   
						if (strpos($kvmdf, "espuma")!==false or strpos($kvmdf, "viscoelastica a medida")!==false or strpos($kvmdf, "topper")!==false or strpos($kvmdf, "cama para perro")!==false or strpos($kvmdf, "colchon viscoelastico")!==false or strpos($kvmdf, "colchon cuna")!==false or strpos($kvmdf, "sofa palet")!==false or strpos($kvmdf, "colchon venus")!==false or strpos($kvmdf, "colchon laura")!==false or strpos($kvmdf, "viscolastica a medida")!==false or strpos($kvmdf, "colchon viscolastico")!==false or strpos($kvmdf, "visco dream")!==false or strpos($kvmdf, "viscolastica")!==false or strpos($kvmdf, "colchon viscoelastica")!==false or strpos($kvmdf, "sofa cama")!==false or strpos($kvmdf, "sofa-cama")!==false or strpos($kvmdf, "d.25")!==false or strpos($kvmdf, "d.23")!==false or strpos($kvmdf, "d.20")!==false or strpos($kvmdf, "hr30")!==false or strpos($kvmdf, "hr35")!==false or strpos($kvmdf, "hr 30")!==false or strpos($kvmdf, "hr 35")!==false or strpos($kvmdf, "palet")!==false or strpos($kvmdf, "cambiador")!==false or strpos($kvmdf, "plegable")!==false or strpos($kvmdf, "cojin de asiento")!==false or strpos($kvmdf, "asiento")!==false or strpos($kvmdf, "cojin viscoelastica")!==false){
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFont()->getColor()->setRGB('000000');
						}
			   
						if (strpos($kvmdf, "cabecero")!==false or strpos($kvmdf, "canape")!==false or strpos($kvmdf, "base")!==false or strpos($kvmdf, "baul")!==false){
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFont()->getColor()->setRGB('000000');
						}
				   
						if (strpos($kvmdf, "canape")!==false or strpos($kvmdf, "base")!==false){
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
							$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFont()->getColor()->setRGB('000000');
						}
						
						
				   
					}*/
							
					if ($kk=='date_add'){
						$date=$date=date_create($kv);
						$kv=date_format($date, 'd/m/Y H:i:s');
					}
					
					
					$advertencia = '';
		   
		   	   		if($df['ESPUMA'] or $df['TAPIZADO'] or $df['SOLDADURA'] or $df['CORTESOFAS'] or $df['CORTEFUNDA'] or $df['COSTURA'] or $df['PEGADO'] or $df['EMPAQUETADO']){		
						if ($kk=='attributes'){
																												
							if ($df['ESPUMA']){
								//echo 'espuma<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
						   
								if (strpos($kvmdf, "espuma")!==false or strpos($kvmdf, "viscoelastica a medida")!==false or strpos($kvmdf, "topper")!==false or strpos($kvmdf, "cama para perro")!==false or strpos($kvmdf, "deluxe")!==false or strpos($kvmdf, "colchon viscoelastico")!==false or strpos($kvmdf, "colchon cuna")!==false or strpos($kvmdf, "sofa palet")!==false or strpos($kvmdf, "colchon venus")!==false or strpos($kvmdf, "colchon laura")!==false or strpos($kvmdf, "a medida")!==false or strpos($kvmdf, "colchon viscolastico")!==false or strpos($kvmdf, "visco dream")!==false or strpos($kvmdf, "viscolastica")!==false or strpos($kvmdf, "colchon viscoelastica")!==false or strpos($kvmdf, "sofa cama")!==false or strpos($kvmdf, "sofa-cama")!==false or strpos($kvmdf, "d.25")!==false or strpos($kvmdf, "d.23")!==false or strpos($kvmdf, "d.20")!==false or strpos($kvmdf, "hr30")!==false or strpos($kvmdf, "hr35")!==false or strpos($kvmdf, "hr 30")!==false or strpos($kvmdf, "hr 35")!==false or strpos($kvmdf, "palet")!==false or strpos($kvmdf, "cambiador")!==false or strpos($kvmdf, "plegable")!==false or strpos($kvmdf, "cojin de asiento")!==false or strpos($kvmdf, "asiento")!==false or strpos($kvmdf, "cojin viscoelastica")!==false or strpos($kvmdf, "colchoneta")!==false or strpos($kvmdf, "cilindro")!==false or strpos($kvmdf, "redondo")!==false or strpos($kvmdf, "visco 5")!==false or strpos($kvmdf, "especial")!==false){
									
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia . $kv, PHPExcel_Cell_DataType::TYPE_STRING); 										
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
									
								}else{
									$nofila=true;
								}
							}
							if($df['TAPIZADO']){
								//echo 'tapizado<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
							   
								if (strpos($kvmdf, "cabecero")!==false or strpos($kvmdf, "canape")!==false or strpos($kvmdf, "base")!==false or strpos($kvmdf, "baul")!==false or strpos($kvmdf, "monaco")!==false or strpos($kvmdf, "tapa")!==false){
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['SOLDADURA']){
								//echo 'soldadura<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								   
								if (strpos($kvmdf, "canape")!==false or strpos($kvmdf, "base")!==false or strpos($kvmdf,"patas a medida")!==false or strpos($kvmdf, "tapa")!==false){
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['CORTESOFAS']){
								//echo 'cortesofas<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								
								if (strpos($kvmdf, "sofas")!==false or strpos($kvmdf, "cojin")!==false or strpos($kvmdf, "puf")!==false or strpos($kvmdf, "puff")!==false or strpos($kvmdf, "pouf")!==false){	
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['CORTEFUNDA']){
								//echo 'cortefunda<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								
								if (strpos($kvmdf, "esparta")!==false or strpos($kvmdf, "loneta")!==false or strpos($kvmdf, "tela")!==false or strpos($kvmdf, "rollo")!==false or strpos($kvmdf, "chenilla")!==false or strpos($kvmdf, "plegable")!==false or strpos($kvmdf, "palet")!==false or strpos($kvmdf, "deluxe")!==false or strpos($kvmdf, "variado")!==false or strpos($kvmdf, "tela chocolate")!==false or strpos($kvmdf, "tela cuadros")!==false or strpos($kvmdf, "contract")!==false or strpos($kvmdf, "plus")!==false or strpos($kvmdf, "lino")!==false or strpos($kvmdf, "pachtwork")!==false or strpos($kvmdf, "patchwork")!==false or strpos($kvmdf, "impermeable")!==false or strpos($kvmdf, "rustika")!==false or strpos($kvmdf, "exterior")!==false or strpos($kvmdf, "Puff Cama")!==false or strpos($kvmdf, "polipiel normal")!==false or strpos($kvmdf, "premium")!==false){	
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['COSTURA']){
								//echo 'costura<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								   
								if (strpos($kvmdf, "cabecero")!==false or strpos($kvmdf, "canape")!==false or strpos($kvmdf, "baul")!==false or strpos($kvmdf, "monaco")!==false or strpos($kvmdf, "base")!==false or strpos($kvmdf, "colchon")!==false or strpos($kvmdf, "funda")!==false or strpos($kvmdf, "strecht")!==false or strpos($kvmdf, "topper")!==false or strpos($kvmdf, "powerloom")!==false or strpos($kvmdf, "polipiel")!==false or strpos($kvmdf, "perro")!==false or strpos($kvmdf, "cuna")!==false or strpos($kvmdf, "cambiadores")!==false or strpos($kvmdf, "harrier")!==false or strpos($kvmdf, "loneta")!==false or strpos($kvmdf, "tela")!==false or strpos($kvmdf, "premium")!==false){	
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['PEGADO']){
								//echo 'pegado<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								   
								if (strpos($kvmdf, "visco")!==false or strpos($kvmdf, "viscoelastica")!==false or strpos($kvmdf, "extreme")!==false or strpos($kvmdf, "pegado")!==false or strpos($kvmdf, "10+5")!==false or strpos($kvmdf, "laura")!==false or strpos($kvmdf, "venus")!==false or strpos($kvmdf, "extreme")!==false){	
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
							if($df['EMPAQUETADO']){
								//echo 'empaquetado<br/>';
								$kvmdf = strtolower($this->stripAccents($kv));
								   
								if (strpos($kvmdf, "articulada")!==false or strpos($kvmdf, "geriatrica")!==false or strpos($kvmdf, "barandilla")!==false or strpos($kvmdf, "incorporador")!==false or strpos($kvmdf, "somier")!==false or strpos($kvmdf, "motor")!==false or strpos($kvmdf, "copos")!==false or strpos($kvmdf, "picado")!==false or strpos($kvmdf, "snow")!==false or strpos($kvmdf, "almohada")!==false or strpos($kvmdf, "topper")!==false or strpos($kvmdf, "plancha")!==false or strpos($kvmdf, "espuma")!==false or strpos($kvmdf, "cama de perro")!==false or strpos($kvmdf, "plegable")!==false or strpos($kvmdf, "viscoelastica")!==false){	
									$kv = str_replace("CR~LF","\n",$kv);
									if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
										$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
									}else{
										$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
									}
								}else{
									$nofila=true;
								}
							}
						}else{
							
							$kv = str_replace("CR~LF","\n",$kv);
							if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
								$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
							}else{
								$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
							}
						} 
					}elseif($df['EXPRESS']){
						if ($kk=='carrier_name'){
							$kvmdf = strtolower($this->stripAccents($kv));
							if (strpos($kvmdf, "ex")!==false){
								
								$kv = str_replace("CR~LF","\n",$kv);
								if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
									$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
								}else{
									$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
								}
							}else{
									$nofila=true;
							}
						}else{
							$kv = str_replace("CR~LF","\n",$kv);
							if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
								$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
							}else{
								$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
							}
						}
					}elseif($df['VENCIMIENTO']){
						if($kk=='message'){	
							$kvmdf = strtolower($this->stripAccents($kv));
							if (strpos($kvmdf, "vto")!==false){	
							
								$kv = str_replace("CR~LF","\n",$kv);
								if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
									$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
								}else{
									$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
								}
							}else{
								$nofila=true;
							}
						}else{
							$kv = str_replace("CR~LF","\n",$kv);
							if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
								$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
							}else{
								$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
							}
						}	
					}else{
						if ($kk=='attributes'){
							$kvmdf = str_replace(' ','',strtolower($this->stripAccents($kv)));
								$sq = "SELECT ppl.name, psa.quantity FROM ps_product_lang ppl,ps_product pp, ps_stock_available psa WHERE ppl.id_product=pp.id_product AND pp.id_product=psa.id_product AND ppl.id_lang=1 AND REPLACE(REPLACE(ppl.name,' ',''),'€','?') LIKE '%". $kvmdf ."%' AND psa.quantity>=0 AND pp.id_category_default=108 ORDER BY ppl.id_product desc";
								//echo $sq . '</br>';
								$resadv = Db::getInstance()->ExecuteS($sq);
								if (count($resadv)>0){
									$advertencia='ARTÍCULO HECHO. MIRAR STOCK. ';
								}else{
									$advertencia='';
								}
						}
						if ($kk=='order_id'){
							if ($ultimo==$kv){
								$ultimo=$kv;
							}else{
								if ($xr!=2){
									$objxlsx->getActiveSheet()->getRowDimension($xr)->setRowHeight(2);
									$objxlsx->getActiveSheet()->getStyle('C'.$xr.':Q'.$xr)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');
									$xr=$xr+1;
								}
								$ultimo=$kv;
							}
						}
												
						$kv = str_replace("CR~LF","\n",$kv);
						if(is_numeric($ki[$kk]) AND $ktyp[$kk] == 3){
							$objxlsx->getActiveSheet()->setCellValueExplicitByColumnAndRow($i,$xr,$advertencia .$kv, PHPExcel_Cell_DataType::TYPE_STRING); 
						}else{
							$objxlsx->getActiveSheet()->setCellValueByColumnAndRow($i,$xr,$advertencia .$kv); 
						}
					}
		            $i++;
				}
				if ($nofila==false){
					$xr++; 
				}else{
					$nofila=false; 
				}  
			} 
		}
		if($df['SAVEAS'] == 'xlsx'){
		$fil= $xr-1;
		$objxlsx->getActiveSheet()->getStyle('A1:Q'.$fil)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objxlsx->getActiveSheet()->getHeaderFooter()->setOddHeader('&C&H Producción de pedidos');
		$objxlsx->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B Fecha de impresión: '.date('d-m-Y'). ' ' .date('H').':'.date('i').':'.date('s')); 
		}
		
		///escribe final de archivo
		if($df['SAVEAS']=='html'){fwrite($fp,$this->ht3);} 
		///
		
		if($df['SAVEAS']=='xml'){fwrite($fp,$this->xme);} 
		if($df['SAVEAS']=='my.xml'){fwrite($fp,$userxml[2]);}     
		if($df['SAVEAS'] == 'xlsx'){
			$objWriter = new PHPExcel_Writer_Excel2007($objxlsx);
			$objWriter->save($exp_path);
			$objxlsx->disconnectWorksheets();
			unset($objxlsx);
		}
		if($df['SAVEAS'] == 'extra.add'){
			if(file_exists(dirname(__FILE__).'/nvn_extra_add.php')){
				include('nvn_extra_add.php');  
			}else{include('_nvn_extra_add.php');}  
			
			$objExtraAdd = new nvn_extra_add($megapole,$fname,$df,$kni,$mline,$cfline,$ktyp);
			$exp_path = $objExtraAdd->new_exp_path;
			$fname = $objExtraAdd->new_fname;
		}
		if(file_exists($exp_path)){
			if((int)$df['LASTIDORDER'] > -1){
				$df['LASTIDORDER']= $Ides['id_order'];
				$ds='';foreach($df as $k=>$v){$ds .= $k.'?'.$v.'‡';} $ds = rtrim($ds,'‡'); 
				if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING2',$ds);}
				else{Configuration::updateValue('EXPORT_ORDERS_PRODUCTION_SETTING1',$ds);}
			}
    		$out .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('EXPORT OK').'" />'.$this->l('Download here:')
				.'<div style="background:#99ff33; width:800px; height:20px; padding:5px; text-align:left;">
                   <a style=" color:#002200"  
                      href="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/export_orders_production/download/'.$fname.'" target="_blank">'.$fname
                   .'</a>
				 </div>
                </div>';
			if(!empty($df['EMAIL'])){
				require("attach_mailer/attach_mailer_class.php");//Validate::isEmail($mail)
				$imail= array_map('trim',explode(',',$df['EMAIL']));
				$shname = Configuration::get('PS_SHOP_NAME');
				$shmail =  Configuration::get('PS_SHOP_EMAIL');
				foreach($imail as $xmail){
					$mmail = new attach_mailer($shname,$shmail, $xmail, "", "", "EXPORT ORDERS NVN CRON");
					$mmail->text_body = "New export orders in Attachment"; 
					$mmail->add_attach_file(dirname(__FILE__).'/download/'.$fname);
					$mmail->process_mail();
					$out .='<p style="color:red;font-weight:bold;text-decoration: underline">'.$xmail.' - '.$mmail->get_msg_str().'</p>';
				}
			}
			if(!empty($df['FTPSERVER'])){
				$conn_id = ftp_connect($df['FTPSERVER']);
				$ftpfi = $df['FTPFILE'];
				if(substr($df['FTPFILE'], -1) == '/'){$ftpfi = $df['FTPFILE'].$fname;}
				$login_result = ftp_login($conn_id, $df['FTPUSER'], $ftppass);
				if (ftp_put($conn_id, $ftpfi, $exp_path, FTP_BINARY)) {
					$out .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Successfully uploaded to FTP').'</div>'; 
				} else {
					$out = '<div class="alert error">'.$this->l('Sorry, there was a problem while uploading file to FTP.').'</div>';
				}
				ftp_close($conn_id);
			} 
			if($df['OSTTO'] AND method_exists('Order','getCurrentOrderState')){$this->changeState($resId, $df['OSTFROM'],$df['OSTTO'],$df['OSTMAIL']);}
		}else {
			$out = '<div class="alert error">'.$this->l('Sorry, but something is wrong with output file: ').'</div>'. $exp_path;  
		}
		if($df['SAVEAS'] <> 'xlsx' AND $df['SAVEAS'] <> 'extra.add'){fclose($fp);}
		return $out ;
		
    }

	//************************************************************************************************************************
	private function  group_by($pole,$row,$gkey,$qkey,$pkey,$wkey)
	//************************************************************************************************************************
    {  $sameid = -1;//!array_key_exists($qkey[1],$dafi) OR !array_key_exists($pkey[1],$dafi) OR !array_key_exists($wkey[1],$dafi)
       $q=false;$p=false;$w=false;
       if(array_key_exists($qkey,$row)){$q=true;}
       if(array_key_exists($pkey,$row)){$p=true;}
       if(array_key_exists($wkey,$row)){$w=true;}
       foreach($pole as $k=>$v){
        if($sameid !== $v[$gkey]){
          if($sameid !== -1){$ven[] = $row;}//zapis
          foreach($row as $r=>$n){$row[$r]=$v[$r];}//novy
          $sameid = $v[$gkey];   
        }else{//stejny id
          if($q){$row[$qkey]=$row[$qkey]+$v[$qkey];}//sum quant
          if($q AND $p){$row[$pkey]=$row[$pkey]+$v[$pkey]*$v[$qkey];}//sum price
          if($q AND $w){$row[$wkey]=$row[$wkey]+$v[$wkey]*$v[$qkey];} //sum weight
          foreach($row as $r=>$n){
               if($r<>$qkey AND $r<>$pkey AND $r<>$wkey){
                if($row[$r]<>$v[$r]){//neopakujici mazu
                  $row[$r]= $this->l('! - absurdity - !');   
                 }   
               }
           }
       }
       }
    $ven[] = $row;   
    return $ven; 
    }
//************************************************************************************************************************
    public function changeState($ordIds, $ordSFT, $ordSTT, $smail)    
//************************************************************************************************************************
{   $ordSF = explode('|',$ordSFT);
    $ordST = explode('|',$ordSTT);
    foreach($ordIds as $ido){
     $order = new Order($ido['id_order']); 
     $order_state = new OrderState($ordST[0]);//ordS z formu
     $current_order_state = $order->getCurrentOrderState();
     if ($current_order_state->id != $order_state->id AND ($current_order_state->id == $ordSF[0] OR $ordSF[0] == 0) )
      {
			$history = new OrderHistory();
			$history->id_order = $order->id;
			$history->id_employee = (int)$this->context->employee->id;
			$use_existings_payment = false;
			if (!$order->hasInvoice())
				$use_existings_payment = true;
			$history->changeIdOrderState((int)$order_state->id, $order, $use_existings_payment);
			$carrier = new Carrier($order->id_carrier, $order->id_lang);
			$templateVars = array();
			if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number)
				$templateVars = array('{followup}' => str_replace('@', $order->shipping_number, $carrier->url));
			// Save all changes
            if($smail){
    			if ($history->addWithemail(true, $templateVars)) 
    			{
    				if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
    				{
    					foreach ($order->getProducts() as $product)
    					{
    						if (StockAvailable::dependsOnStock($product['product_id']))
    							StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
    					}
    				}
    			}
    			$this->errors[] = Tools::displayError('An error occurred while changing order status, or we were unable to send an email to the customer.');
            }else
            {
    			if ($history->add()) //  Because it is a automatic, I will not send email
    			{
    				if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
    				{
    					foreach ($order->getProducts() as $product)
    					{
    						if (StockAvailable::dependsOnStock($product['product_id']))
    							StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
    					}
    				}
    			}
    			$this->errors[] = Tools::displayError('An error occurred while changing order status.');
            }
      }
    }
}
   
//************************************************************************************************************************
    private function csvstr($row, $delim)    
//************************************************************************************************************************
   {$outstream = fopen("php://temp", 'r+');
    fputcsv($outstream, $row, $delim);
    rewind($outstream);
    $csv = fgets($outstream);
    fclose($outstream);
    $csv = str_replace(chr(0x0A),chr(0x0D).chr(0x0A),$csv);
    return $csv;
   }

//************************************************************************************************************************
 	private function versionPS()
//************************************************************************************************************************
    { // ver 1 = all, 5 = 1.5x, 4 = from 1.4.6.2 to 1.4.9x
      $version_mask = explode('.', _PS_VERSION_);
      if( $version_mask[0]  < 1){return 0;} 
      if( $version_mask[1] == 5){return 5;}
      if( $version_mask[1] == 2){return 2;}
      if( $version_mask[1] == 4 AND $version_mask[2] > 5){return 4;}
    }
//************************************************************************************************************************
 	private function toDafiRow($dafi,$resXX)
//************************************************************************************************************************
    { 
    if(!$resXX){return $dafi;}
    foreach ($dafi as $di=>$i){
       if(array_key_exists($di,$resXX)){$dafi[$di] = $resXX[$di];}
      }
      return $dafi;
    }
//************************************************************************************************************************
 	private function myFields($fiha,$tbl)
//************************************************************************************************************************
    { 
      $f = '';
      foreach ($fiha[$tbl] as $i){
       $f .= $i.',' ; 
      }
      return $f; 
    }
    
//************************************************************************************************************************
    private function subval_sort($a,$subkey) 
//************************************************************************************************************************
   {
    foreach($a as $k=>$v) {$b[$k] = strtolower($v[$subkey]);}
	asort($b);
	foreach($b as $key=>$val) {$c[] = $a[$key];}
	return $c;
   }    
    
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
    private function encryptIt( $q ) {
    $cryptKey  = 'r4fe1bAEbMe2FTM4k7iKT9';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );}
    private function decryptIt( $q ) {
    $cryptKey  = 'r4fe1bAEbMe2FTM4k7iKT9';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );}    
    
//************************************************************************************************************************
	private function file_get_contents_curl($url) 
//************************************************************************************************************************
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
    $data = curl_exec($ch);
    curl_close($ch);
    if(substr($data,0,9)=='<!DOCTYPE'){$data='';}//to jsem rozhodne nechtel
    return $data;
}
//************************************************************************************************************************
    private function hxml($chu)
//************************************************************************************************************************
    {if (empty($chu)){ $chu = "UTF-8";}
     return '<?xml version="1.0" encoding="'.$chu.'"?>'."\r\n".'<export_orders_production>';
    }
//************************************************************************************************************************
 	private function rxml(array $row)
//************************************************************************************************************************
    {  
      $ht = 'CR~LF<order>CR~LF';
      foreach ($row as $k=>$v) {
           $ht .= '    <'.$k.'><![CDATA['.(string)$v.']]></'.$k.'>CR~LF';
        }
       $ht .= '</order>CR~LF';
       return $ht;
    }
//************************************************************************************************************************
 	private function myxml(array $row,$tblcontents)
//************************************************************************************************************************
    {  
      $ht = $tblcontents;
      foreach ($row as $k=>$v) {
           $ht = str_replace('#'.$k.'#',(string)$v,$ht);
        }
       return $ht;
    }    
//************************************************************************************************************************
    private function ht1($chu)
//************************************************************************************************************************
    {if (empty($chu)){ $chu = "UTF-8";}
return 
'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta content="text/html; charset='.$chu.'" http-equiv="content-type">
<title></title>
</head>
<body>
<table border="1" cellpadding="0" cellspacing="0">';
    }
//************************************************************************************************************************
 	private function ht2a(array $row, $i)
//************************************************************************************************************************
    {   (($i%2)==0) ?  $ht = '<tr bgcolor = "#cafafa">':$ht = '<tr bgcolor = "#fbcaca">' ;
        if($i==0){$ht = '<tr bgcolor = "#fafacb">';}
        foreach ($row as $itm) {
			$width='';
			if ($itm=='Nombre de producto'){
				$width='width="0px"';
			}
           $ht .= '<td '.$width.'>'.(string)$itm.'</td>';
        }
       $ht .= '</tr>';
       return $ht;
    }	
//************************************************************************************************************************
 	private function ht2(array $row, $i)
//************************************************************************************************************************
    {   (($i%2)==0) ?  $ht = '<tr bgcolor = "#cafafa">':$ht = '<tr bgcolor = "#fbcaca">' ;
        if($i==0){$ht = '<tr bgcolor = "#fafacb">';}
        //foreach ($row as $itm) {
		foreach($row as $kk=>$kv){
           //$ht .= '<td nowrap>'.(string)$itm.'</td>';
		   echo 'kk:'. $kk. '<br/>';
		   if ($kk=='product_name'){
				$ht .= '<td width="0px">'.(string)$kv.'</td>';
				echo 'kv:'. $kv. '<br/>';
				$product=$kv;
			}elseif ($kk=='attributes'){
				$val=explode('-',$kv);
				$kva='';
				foreach($val as $value){
					if ($value!=''){
						$sq='SELECT * FROM '._DB_PREFIX_.'megaproductaddattr_lang pal,'._DB_PREFIX_.'megaproductaddattr pa WHERE pal.id_addattr=pa.id_addattr AND id_attribute='.$value.' AND id_addgroup=1548 AND id_lang=1';
						$resat1=Db::getInstance()->getRow($sq);
						if($resat1['title']!=''){
							$kva=$kva.$resat1['title'].'-';
						}else{
							$sq='SELECT name FROM '._DB_PREFIX_.'attribute_lang AS al WHERE al.id_attribute='.$value.' and al.id_lang=1';
							$resat=Db::getInstance()->getRow($sq);
							if ($resat['name']!=''){
								$kva=$kva.$resat['name'].'-';
							}else{
								$kva=$kva.$value;
							}
						}
					}else{$kva='';}
				}
				//$kv=$objxlsx->getActiveSheet()->getCellByColumnAndRow(4, $xr)->getFormattedValue(). ' ' .$kva;	
				//$objxlsx->getActiveSheet()->setCellValueByColumnAndRow(4,$xr,$kv); 	
				$ht .= '<td width="155px">'.(string)$product.' '.$kva.'</td>';
				;  	
			}else{		   
				$ht .= '<td>'.(string)$kv.'</td>';
			}
        }
       $ht .= '</tr>';
       return $ht;
    }
 private $ht3='</table></body></html>';
 private $xme='</export_orders_production>';
 private function saveF()
    { return array('xlsx','csv','html','xml','my.xml','extra.add');}
//************************************************************************************************************************
 private function tFields()
//************************************************************************************************************************

    { return array(
         'oo'=>array(),'cu'=>array(),'cg'=>array(),'gl'=>array(),'ad'=>array(),'cd'=>array(),
         'sd'=>array(),'ai'=>array(),'ci'=>array(),'si'=>array(),'cr'=>array(),'me'=>array(),
         'od'=>array(),'pr'=>array(),'mf'=>array(),'su'=>array(),'ot'=>array(),'yd'=>array(),
         'yi'=>array(),'ca'=>array(),'dt'=>array(),'tx'=>array(),'sl'=>array(),'sh'=>array(),
         'vo'=>array(),'mp'=>array(),'sc'=>array(),'cust'=>array(),'oc'=>array()
    );
    }  
//************************************************************************************************************************
 private function getFields()
//************************************************************************************************************************
   {if (count(self::$existf)){return;};//nacteno
    //error_reporting(E_ERROR | E_PARSE); // some tables may not exist
    $je = Db::getInstance()->ExecuteS("SHOW TABLES FROM `"._DB_NAME_."`");
    $tindb = array();
    foreach($je as $k1=>$v1){
       foreach($v1 as $k=>$v){ 
          $tindb[] = $v;
       }   
    }
    if(in_array(_DB_PREFIX_."orders",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."orders");
    foreach($result as $res){self::$existf[] = 'oo.'.$res['Field']; }
    }
    if(in_array(_DB_PREFIX_."customer",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."customer");
    foreach($result as $res){self::$existf[] = 'cu.'.$res['Field']; }
    }
    if(in_array(_DB_PREFIX_."customer_group",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."customer_group");
    foreach($result as $res){self::$existf[] = 'cg.'.$res['Field']; }
    }
    if(in_array(_DB_PREFIX_."group_lang",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."group_lang");
    foreach($result as $res){self::$existf[] = 'gl.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."address",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."address");
    foreach($result as $res){self::$existf[] = 'ad.'.$res['Field']; self::$existf[] = 'ai.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."country_lang",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."country_lang");
    foreach($result as $res){self::$existf[] = 'cd.'.$res['Field']; self::$existf[] = 'ci.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."state",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."state");
    foreach($result as $res){self::$existf[] = 'sd.'.$res['Field']; self::$existf[] = 'si.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."country",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."country");
    foreach($result as $res){self::$existf[] = 'yd.'.$res['Field']; self::$existf[] = 'yi.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."currency",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."currency");
    foreach($result as $res){self::$existf[] = 'cr.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."carrier",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."carrier");
    foreach($result as $res){self::$existf[] = 'ca.'.$res['Field']; }    
    }
    /*if(in_array(_DB_PREFIX_."message",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."message");
    foreach($result as $res){self::$existf[] = 'me.'.$res['Field']; }    
    }*/
	if(in_array(_DB_PREFIX_."customer_message",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."customer_message");
    foreach($result as $res){self::$existf[] = 'me.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."order_tax",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_tax");
    foreach($result as $res){self::$existf[] = 'ot.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."order_detail",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_detail");
    foreach($result as $res){self::$existf[] = 'od.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."product",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."product");
    foreach($result as $res){self::$existf[] = 'pr.'.$res['Field']; }    
    }
    if(in_array(_DB_PREFIX_."manufacturer",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."manufacturer");
    foreach($result as $res){self::$existf[] = 'mf.'.$res['Field']; }  
    }
    if(in_array(_DB_PREFIX_."supplier",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."supplier");
    foreach($result as $res){self::$existf[] = 'su.'.$res['Field']; }             
    }
    if(in_array(_DB_PREFIX_."order_detail_tax",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_detail_tax");
    foreach($result as $res){self::$existf[] = 'dt.'.$res['Field']; }             
    }
    if(in_array(_DB_PREFIX_."tax",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."tax");
    foreach($result as $res){self::$existf[] = 'tx.'.$res['Field']; }             
    }
    if(in_array(_DB_PREFIX_."order_state_lang",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_state_lang");
    foreach($result as $res){self::$existf[] = 'sl.'.$res['Field']; }             
    }  
    if(in_array(_DB_PREFIX_."shop",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."shop");
    foreach($result as $res){self::$existf[] = 'sh.'.$res['Field']; }             
    }      
    if(in_array(_DB_PREFIX_."order_cart_rule",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_cart_rule");
    foreach($result as $res){self::$existf[] = 'vo.'.$res['Field']; }             
    }      
	//******************************************************************************
	//******Código añadido para descargar los artículos de megaproduct**************
	//******************************************************************************
	if(in_array(_DB_PREFIX_."megaproductcart",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."megaproductcart");
    foreach($result as $res){self::$existf[] = 'mp.'.$res['Field']; }    
    }  
	//******************************************************************************
	//******************************************************************************
	//****************************************************************************** 
	//
	//*************************************************************************************
	//********Código añadido para obtener el transportista a travçes del estado************
	//*************************************************************************************
	if(in_array(_DB_PREFIX_."order_history",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_history");
    foreach($result as $res){self::$existf[] = 'sc.'.$res['Field']; }    
    } 
	//******************************************************************************
	//******************************************************************************
	//******************************************************************************  
	//
	//*************************************************************************************
	//********Código añadido para obtener el transportista a travçes del estado************
	//*************************************************************************************
	if(in_array(_DB_PREFIX_."customized_data",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."customized_data");
    foreach($result as $res){self::$existf[] = 'cust.'.$res['Field']; }    
    } 
	//******************************************************************************
	//******************************************************************************
	//****************************************************************************** 
	if(in_array(_DB_PREFIX_."order_comment",$tindb)){
    $result = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `"._DB_NAME_."`."._DB_PREFIX_."order_comment");
    foreach($result as $res){self::$existf[] = 'oc.'.$res['Field']; }    
    }  
    //error_reporting(-1);
    // $this->preprint(self::$existf);
}    
//************************************************************************************************************************
 private function nFields() // ver 1 = all, 5 = 1.5x, 4 = from 1.4.6.2 to 1.4.9x, // rw allowed 0-no, 1-text or num,2-date,3-num as text
//************************************************************************************************************************
    { $nf = array(
    // BDfield  AS XML                                                     csv / public name             id/ver./grpb/rw allowed/inDB
    array('oo.id_order AS order_id',                                       $this->l('Order No'),                1,1,1,0,0), 
    array('oo.payment AS payment',                                         $this->l('Payment'),                 2,1,0,1,0), 
    array('oo.recyclable AS recycled_packaging',                           $this->l('Recycled Packaging'),      3,1,0,1,0), 
    array('oo.gift AS gift_wrapping',                                      $this->l('Gift Wrapping'),           4,1,0,1,0), 
    array('oo.gift_message AS gift_message',                               $this->l('Gift Message'),            5,1,0,1,0), 
    array('oo.shipping_number AS shipping_number',                         $this->l('Shipping Number'),         6,1,0,1,0), 
    array('oo.total_discounts AS total_discounts',                         $this->l('Total Discounts'),         7,1,0,1,0), 
    array('oo.total_paid AS total_paid',                                   $this->l('Total Paid'),              8,1,0,1,0), 
    array('oo.total_paid_real AS total_paid_real',                         $this->l('Total Paid Real'),         9,1,0,1,0), 
    array('oo.total_products AS total_products',                           $this->l('Total Products'),         10,1,0,1,0), 
    array('oo.total_shipping AS total_shipping',                           $this->l('Total Shipping'),         11,1,0,1,0), 
    array('oo.total_wrapping AS total_wrapping',                           $this->l('Total Wrapping'),         12,1,0,1,0), 
    array('oo.invoice_number AS invoice_number',                           $this->l('Invoice No'),             13,1,0,1,0), 
    array('oo.delivery_number AS delivery_number',                         $this->l('Delivery No'),            14,1,0,1,0), 
    array('oo.invoice_date AS invoice_date',                               $this->l('Invoice Date'),           15,1,0,2,0), 
    array('oo.delivery_date AS delivery_date',                             $this->l('Delivery Date'),          16,1,0,2,0), 
    array('oo.valid AS valid',                                             $this->l('Validity'),               17,1,0,1,0), 
    array('oo.date_add AS date_add',                                       $this->l('Date Added'),             18,1,0,2,0), 
    array('oo.date_upd AS date_upd',                                       $this->l('Date Updated'),           19,1,0,2,0), 
    array('cu.id_customer AS id_customer',                                 $this->l('Customer No'),            20,1,1,1,0), 
    array('cu.firstname AS first_name',                                    $this->l('First Name'),             21,1,0,1,0), 
    array('cu.lastname AS last_name',                                      $this->l('Last Name'),              22,1,0,1,0), 
    array('cu.email AS email',                                             $this->l('Email'),                  23,1,0,1,0),
	array('cu.note AS note_private',									   $this->l('Note'),				   24,1,1,1,0), 
    //array('cg.id_group AS id_customer_group',                              $this->l('Id Customer Group'),      24,1,1,1,0), 
    array('gl.name AS customer_group_name',                                $this->l('Customer Group Name'),    25,1,0,1,0), 
    array('ad.company AS delivery_company',                                $this->l('Delivery Company'),       26,1,0,1,0), 
    array('ad.firstname AS delivery_firstname',                            $this->l('Delivery Firstname'),     27,1,0,1,0), 
    array('ad.lastname AS delivery_lastname',                              $this->l('Delivery Lastname'),      28,1,0,1,0), 
    array('ad.address1 AS delivery_address1',                              $this->l('Delivery Address1'),      29,1,0,1,0), 
    array('ad.address2 AS delivery_address2',                              $this->l('Delivery Address2'),      30,1,0,1,0), 
    array('ad.postcode AS delivery_postcode',                              $this->l('Delivery Postcode'),      31,1,0,3,0), 
    array('ad.city AS delivery_city',                                      $this->l('Delivery City'),          32,1,0,1,0), 
    array('ad.phone AS delivery_phone',                                    $this->l('Delivery Phone'),         33,1,0,3,0), 
    array('ad.phone_mobile AS delivery_mobilephone',                       $this->l('Delivery PhoneMobile'),   34,1,0,3,0), 
    array('cd.name AS delivery_country',                                   $this->l('Delivery Country'),       35,1,0,1,0), 
    array('sd.name AS delivery_state',                                     $this->l('Delivery State'),         36,1,0,1,0), 
    array('ai.company AS invoice_company',                                 $this->l('Invoice Company'),        37,1,0,1,0), 
    array('ai.firstname AS invoice_firstname',                             $this->l('Invoice Firstname'),      38,1,0,1,0), 
    array('ai.lastname AS invoice_lastname',                               $this->l('Invoice Lastname'),       39,1,0,1,0), 
    array('ai.address1 AS invoice_address1',                               $this->l('Invoice Address1'),       40,1,0,1,0), 
    array('ai.address2 AS invoice_address2',                               $this->l('Invoice Address2'),       41,1,0,1,0), 
    array('ai.postcode AS invoice_postcode',                               $this->l('Invoice Postcode'),       42,1,0,3,0), 
    array('ai.city AS invoice_city',                                       $this->l('Invoice City'),           43,1,0,1,0), 
    array('ai.phone AS invoice_phone',                                     $this->l('Invoice Phone'),          44,1,0,3,0), 
    array('ai.phone_mobile AS invoice_mobilephone',                        $this->l('Invoice PhoneMobile'),    45,1,0,3,0), 
    array('ci.name AS invoice_country',                                    $this->l('Invoice Country'),        46,1,0,1,0), 
    array('si.name AS invoice_state',                                      $this->l('Invoice State'),          47,1,0,1,0), 
    array('cr.iso_code AS iso_code',                                       $this->l('Currency'),               48,1,0,1,0), 
    array('od.product_id AS product_id',                                   $this->l('Product ID'),             49,1,1,1,0), 
    array('od.product_name AS product_name',                               $this->l('Product Name'),           50,1,1,1,0), 
    array('od.product_reference AS product_reference',                     $this->l('Product Reference'),      51,1,0,3,0), 
    array('od.product_quantity AS product_quantity',                       $this->l('Product Qty'),            52,1,0,1,0), 
    array('od.product_price AS product_price',                             $this->l('Product Price'),          53,1,0,1,0), 
    array('od.product_weight AS product_weight',                           $this->l('Weight'),                 54,1,0,1,0), 
    array('od.product_attribute_id AS product_attribute_id',               $this->l('Product attribute id'),   55,1,0,1,0), 
    array('od.tax_rate AS tax_rate',                                       $this->l('Tax Rate'),               56,1,0,1,0), 
    array('od.total_price_tax_incl AS total_price_tax_incl',               $this->l('Total price tax incl'),   57,5,0,1,0), 
    array('od.total_price_tax_excl AS total_price_tax_excl',               $this->l('Total price tax excl'),   58,5,0,1,0), 
    array('od.reduction_percent AS reduction_percent',                     $this->l('Reduction percent'),      59,1,0,1,0), 
    array('od.reduction_amount AS reduction_amount',                       $this->l('Reduction amount'),       60,1,0,1,0), 
    array('od.group_reduction AS group_reduction',                         $this->l('Group reduction'),        61,1,0,1,0), 
    array('od.product_quantity_discount AS product_quantity_discount',     $this->l('Quantity discount'),      62,1,0,1,0), 
    array('pr.wholesale_price AS wholesale_price',                         $this->l('Wholesale Price'),        63,1,0,1,0), 
    array('pr.id_manufacturer AS id_manufacturer',                         $this->l('ID Manufacturer'),        64,1,1,1,0), 
    array('mf.name AS manufacturer_name',                                  $this->l('Manufacturer Name'),      65,1,0,1,0), 
    array('od.product_supplier_reference AS product_supplier_reference',   $this->l('Supplier reference'),     66,1,0,3,0), 
    array('pr.id_supplier AS id_supplier',                                 $this->l('ID Supplier'),            67,1,1,1,0), 
    array('su.name AS supplier_name',                                      $this->l('Supplier Name'),          68,1,0,1,0), 
    array('me.message AS message',                                         $this->l('Customer Message'),       69,1,0,0,0), 
    array('ot.tax_name AS taxname',                                        $this->l('Tax Name'),               70,4,0,1,0), 
    array('ot.tax_rate AS taxrate',                                        $this->l('Tax Rate'),               71,4,0,1,0), 
    array('ot.amount AS taxamount',                                        $this->l('Tax Amount'),             72,4,0,1,0),
    array('ad.vat_number AS delivery_vat_number',                          $this->l('Delivery VAT number'),    73,1,0,3,0),
    array('ad.dni AS delivery_dni',                                        $this->l('Delivery DNI'),           74,1,0,3,0),
    array('ai.vat_number AS invoice_vat_number',                           $this->l('Invoice VAT number'),     75,1,0,3,0),
    array('ai.dni AS invoice_dni',                                         $this->l('Invoice DNI'),            76,1,0,3,0),
    array('od.product_ean13 AS product_ean13',                             $this->l('Product EAN13'),          77,1,0,3,0), 
    array('od.product_upc AS product_upc',                                 $this->l('Product UPC'),            78,1,0,3,0), 
    array('yd.iso_code AS country_delivery_iso_code',                      $this->l('Delivery Country code'),  79,1,0,1,0), 
    array('yi.iso_code AS country_invoice_iso_code',                       $this->l('Invoice Country code'),   80,1,0,1,0),
    array('ad.other AS delivery_other',                                    $this->l('Delivery other'),         81,1,0,3,0), 
    array('ai.other AS invoice_other',                                     $this->l('Invoice other'),          82,1,0,3,0),
    array('ca.name AS carrier_name',                                       $this->l('Carrier Name'),           83,1,0,1,0),
    array('ca.external_module_name AS carrier_external_module',            $this->l('Carrier external module'),84,1,0,1,0),
    array(' AS multiline',                                                 $this->l('Multi-line Field'),       85,1,0,1,1),
    array(' AS calcfield1',                                                $this->l('Calculated Field1'),      86,1,0,1,1),
    array(' AS calcfield2',                                                $this->l('Calculated Field2'),      87,1,0,1,1),
    array(' AS calcfield3',                                                $this->l('Calculated Field3'),      88,1,0,1,1),
    array(' AS calcfield4',                                                $this->l('Calculated Field4'),      89,1,0,1,1),
    array(' AS calcfield5',                                                $this->l('Calculated Field5'),      90,1,0,1,1),
    array(' AS calcfield6',                                                $this->l('Calculated Field6'),      91,1,0,1,1),
    array(' AS calcfield7',                                                $this->l('Calculated Field7'),      92,1,0,1,1),
    array(' AS calcfield8',                                                $this->l('Calculated Field8'),      93,1,0,1,1),
    array('od.ecotax AS eco_tax',                                          $this->l('Ecotax'),                 94,1,0,1,0),
    array('od.ecotax_tax_rate AS ecotax_tax_rate',                         $this->l('Ecotax tax rate'),        95,1,0,1,0),
    array('dt.unit_amount AS tax_unit_amount',                             $this->l('Tax unit amount'),        96,1,0,1,0),
    array('dt.total_amount AS tax_total_amount',                           $this->l('Tax total amount'),       97,1,0,1,0),
    array('tx.rate AS tax_tax_rate',                                       $this->l('Tax-tax rate'),           98,1,0,1,0),
    array('sl.name AS order_status',                                       $this->l('Order status'),           99,1,0,1,0),
	array('oo.reference AS order_reference',                               $this->l('Order reference'),       100,1,0,1,0),
    array('sh.name AS shop_name',                                          $this->l('Shop name'),             101,5,0,1,0),
    array('vo.name AS voucher_name',                                       $this->l('Voucher name'),          102,5,0,1,0),
    array('vo.value AS voucher_value',                                     $this->l('Voucher value'),         103,5,0,1,0),
    array('vo.value_tax_excl AS voucher_value_tax_excl',                   $this->l('Voucher value tax excl'),104,5,0,1,0),
    array('ca.id_carrier AS id_carrier',                                   $this->l('Carrier ID'),            105,1,0,1,0),
	//******************************************************************************
	//********Código añadido para descargar los artículos de megaproduct************
	//******************************************************************************
	array('mp.width AS width',				   				   $this->l('Ancho megaproduct'),	  106,1,1,1,0),
	array('mp.height AS height',				   			   $this->l('Largo megaproduct'),	  107,1,1,1,0),
	array('mp.length AS length',				   			   $this->l('Grosor megaproduct'),	  108,1,1,1,0),
	array('mp.quantity AS quantity',							$this->l('Cantidad megaproduct'),	109,1,1,1,0),
	array('mp.attributes AS attributes',						$this->l('Atributos'),				110,1,1,1,0),
	array('mp.personalization AS personalization',				$this->l('Personalizacion mega'),		115,1,0,1,0),
	//******************************************************************************
	//******************************************************************************
	//******************************************************************************
	//
	//*************************************************************************************
	//********Código añadido para obtener el transportista a travçes del estado************
	//*************************************************************************************
	array('sc.id_order_state AS carrier_status',						$this->l('Carrier by state'),		111,1,0,1,0),
	array('sc.date_add AS date_status',									$this->l('Date Status'),		112,1,0,1,0),
	//******************************************************************************
	//******************************************************************************
	//******************************************************************************
	//
	//*************************************************************************************
	//********Código añadido para obtener el transportista a travçes del estado************
	//*************************************************************************************
	array('cust.value AS customized_data',						$this->l('Personalizacion prod.'),		113,1,0,1,0),
	//******************************************************************************
	//******************************************************************************
	//******************************************************************************
	array('oc.comment AS order_comment',						$this->l('Order commnet'),				114,1,0,1,0)	
	
    );
     if(Configuration::get('EXPORT_ORDERS_PRODUCTION_SETX')){
     $rf = Configuration::get('EXPORT_ORDERS_PRODUCTION_REWRITE2'); }
    else{
     $rf = Configuration::get('EXPORT_ORDERS_PRODUCTION_REWRITE1'); }
    
    $erf=explode('‡',$rf);
    $xf=explode('↔',$erf[0]);
    $cf=explode('↔',$erf[1]);
    $i=0;
    foreach($xf as $r){
             if((string)$r<>''){
             $foo = explode(' AS ',$nf[$i][0]);
              $nf[$i][0] = $foo[0].' AS '.$r;   
             }
    $i++;}
    $i=0;
    foreach($cf as $r){
             if((string)$r<>''){
              $nf[$i][1] = $r;   
             }
    $i++;}
    $this->getFields();
    $i = 0;
    foreach($nf as $ni){
        $foo = explode(' AS ',$nf[$i][0]);
        if(count(self::$existf)){
          if(in_array(trim($foo[0]),self::$existf)){$nf[$i][6] = 1;}
        }else{
          if($nf[$i][3] == 1 OR $nf[$i][3] == 5){$nf[$i][6] = 1;} // manually selected PS version 1 = all, 5 = PS 1.5.x
        }  
    $i++;}
    return $nf;
    }    
    
	
	
}
