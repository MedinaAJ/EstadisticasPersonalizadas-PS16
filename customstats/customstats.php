<?php 
/*
* minicskeleton - a module template for Prestashop v1.5+
* Copyright (C) 2013 S.C. Minic Studio S.R.L.
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('_PS_VERSION_'))
  exit;
 
class CustomStats extends Module
{
	// DB file
	const INSTALL_SQL_FILE = 'install.sql';

	public function __construct()
	{
		$this->name = 'customstats';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'Alejandro Medina';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7'); 
		// $this->dependencies = array('blockcart');

		parent::__construct();

		$this->displayName = $this->l('Custom Stats');
		$this->description = $this->l('Hecho para ventadecolchones con el fin de unificar las estadisticas de megaproduct e innova.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	/**
 	 * install
	 */
	public function install()
	{
		// Create DB tables - uncomment below to use the install.sql for database manipulation
		/*
		if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return false;
		else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
			return false;
		$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
		// Insert default template data
		$sql = str_replace('THE_FIRST_DEFAULT', serialize(array('width' => 1, 'height' => 1)), $sql);
		$sql = str_replace('FLY_IN_DEFAULT', serialize(array('width' => 1, 'height' => 1)), $sql);
		$sql = preg_split("/;\s*[\r\n]+/", trim($sql));

		foreach ($sql as $query)
			if (!Db::getInstance()->execute(trim($query)))
				return false;
		*/

		if (!parent::install() || 
			!$this->registerHook('displayHome') || 
			!$this->registerHook('displayHeader') || 
			!$this->registerHook('displayBackOfficeHeader') || 
			!$this->registerHook('displayAdminHomeQuickLinks'))
			return false;
		return true;
	}

	/**
 	 * uninstall
	 */
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	/**
 	 * admin page Code Here AMJ
	 */	
	 
	 /*
	 *		ESTADISTICAS
	 *
	 *	MEGAPRODUCT
	 *
	 *	SELECT SUM(mp.quantity) AS cantidad, _splitear(mp.attributes,"-") AS articulo FROM ps_orders o, ps_megaproductcart mp WHERE 
	 *	o.id_cart=mp.id_cart AND mp.id_product=104 AND o.current_state<>34 
	 *	AND o.date_add>'2019-10-01 00:00:00' AND o.date_add<'2019-12-31 23:59:59' GROUP BY _splitear(mp.attributes,"-") ;
	 *
	 *	POR DEFECTO
	 *
	 *	SELECT SUM(product_quantity),pa.name FROM ps_order_detail od,ps_orders o,ps_product_attribute_combination ppc,ps_attribute_lang pa  WHERE 
o.id_order=od.id_order AND ppc.id_product_attribute=od.product_attribute_id AND pa.id_attribute=ppc.id_attribute AND pa.id_lang=1 AND
 current_state<>34 AND product_id=33 AND o.date_add>='2018-01-01 00:00:00' AND o.date_add<='2018-12-31 23:59:59' GROUP BY pa.name
	 *	
	 */
	public function getContent()
	{
		
		$debug = 'Nada';
		$texto_boton = 'Siguiente Paso'; // Cambiar en el ultimo paso para indicar que hay que sacar estadisticas
		
		/*
		*	Partimos del ID del módulo para obtener estadisticas
		*/
		
		$estadisticas = array();
		$id_producto = 0;
		$id_atributo = 0;
		$selector_producto = array();
		$producto_existe = false;
		$estadisticas_calculadas = false;
		
		$fecha_inicio = '2010-01-01';
		$fecha_fin = '2030-01-01';
		
		/*
		*	Comprobamos si se han estipulado fechas
		*/
		
		if(!empty(Tools::getValue('fechaInicio'))){
			$fecha_inicio = Tools::getValue('fechaInicio');
		}
		
		if(!empty(Tools::getValue('fechaFin'))){
			$fecha_fin = Tools::getValue('fechaFin');
		}
		
		
		if(!empty(Tools::getValue('id'))){
			$id_producto = (int)Tools::getValue('id');
			
			/*
			*	Comprobamos si el producto existe
			*/
			
			$sql = "SELECT id_product FROM " . _DB_PREFIX_ . "product WHERE id_product = ".$id_producto.";";
			$check_existe = Db::getInstance()->executeS($sql);
			
			foreach($check_existe as $ss){ 
				foreach($ss as $pp){ 
					if(is_numeric($pp)){
						$producto_existe = true;
					}
				}
			}
			
			if($producto_existe){
				$debug = 'El producto buscado si existe';
			
				/*
				*	Comprobamos si es de megaproduct o de prestashop
				*/
				
				$sql = "SELECT id_product FROM " . _DB_PREFIX_ . "megaproduct WHERE id_product = ".$id_producto.";";
				$check_megaproduct = Db::getInstance()->executeS($sql);
				
				$is_mp = false;
				
				foreach($check_megaproduct as $ss){ 
					foreach($ss as $pp){ 
						if(is_numeric($pp)){
							$is_mp = true;
						}
					}
				}
				
				/*
				*	Obtenemos selectores y despues comprobamos la opcion del selector escogida
				*/
				
				if($is_mp){
					$tipo_producto = "Megaproduct";
					$selector_producto_string = $this->getMpSelector($id_producto);
					$selector_producto = explode("_", $selector_producto_string);
					$estadisticas = $this->isMegaproduct($id_producto, $fecha_inicio, $fecha_fin);
					$texto_boton = 'Sacar Estadisticas';
					$estadisticas_calculadas = true;
				}else{
					$tipo_producto = "Prestashop";
					$selector_producto_string = $this->getPsSelector($id_producto);
					$selector_producto = explode("_", $selector_producto_string);
					$estadisticas = $this->isPrestashop($id_producto, $fecha_inicio, $fecha_fin);
					$texto_boton = 'Sacar Estadisticas';
					$estadisticas_calculadas = true;
				}
			}else{
				$debug = 'No se ha encontrado ningun producto con ese ID';
				
				/*
				*	Se vuelve a asignar el ID 0 al producto para evitar que se vean los pasos posteriores del formulario
				*/
				
				$id_producto = 0;
			}
		}
		
		$valores_estadistica = '';
		$elementos_estadistica = '';
		if($estadisticas_calculadas){
			foreach($estadisticas as $elemento){ 
				$elemento_split = explode("--", $elemento);
				$valores_estadistica .= $elemento_split[0].",";
				$elementos_estadistica .= '"'.$elemento_split[1].'",';
			}
			
			$valores_estadistica = substr($valores_estadistica,0,strlen($valores_estadistica)-1);
			$elementos_estadistica = substr($elementos_estadistica,0,strlen($elementos_estadistica)-1);
		}
		
		/*
		*	Incluir fechas en el front y obtener el grupo seleccionado en el back, y ya sacar las cantidades
		*/
		$this->smarty->assign('request_uri', $_SERVER['REQUEST_URI']);
		$this->smarty->assign('id_producto', $id_producto);
		$this->smarty->assign('existe', $producto_existe);
		$this->smarty->assign('tipo_producto', $tipo_producto);
		$this->smarty->assign('selector_producto', $selector_producto); // Array con el contenido del selector
		$this->smarty->assign('texto_boton', $texto_boton);
		$this->smarty->assign('estadisticas', $estadisticas);
		$this->smarty->assign('valores_estadistica', $valores_estadistica);
		$this->smarty->assign('elementos_estadistica', $elementos_estadistica);
		$this->smarty->assign('estadisticas_calculadas', $estadisticas_calculadas);
		$this->smarty->assign('fecha_fin', $fecha_fin);
		$this->smarty->assign('fecha_inicio', $fecha_inicio);
		$this->smarty->assign('debug', $debug);
		return $this->display(__FILE__, 'views/templates/admin/minicskeleton.tpl');
	}
	
	private function getPsSelector($id_product){
		
		/*
		*	Obtenemos los selectores que trae por defecto Prestashop
		*/

		$sql = "SELECT pagl.name, pagl.id_attribute_group 
				FROM " . _DB_PREFIX_ . "attribute_group_lang pagl, " . _DB_PREFIX_ . "attribute pa, " . _DB_PREFIX_ . "product_attribute ppa, " . _DB_PREFIX_ . "product_attribute_combination ppac
				WHERE pagl.id_attribute_group = pa.id_attribute_group AND pa.id_attribute = ppac.id_attribute AND ppac.id_product_attribute = ppa.id_product_attribute AND ppa.id_product = ".$id_product."
				GROUP BY NAME;";
				
		$check_selectores = Db::getInstance()->executeS($sql);
		$resultado = '';
				
		foreach($check_selectores as $ss){ 
			foreach($ss as $pp){ 
				$resultado .= $pp.'-';
			}
			$resultado = substr($resultado,0,strlen($resultado)-1);
			$resultado .=  '_';
		}
		$resultado = substr($resultado,0,strlen($resultado)-1);
	
		return $resultado;
	}
	
	private function getMpSelector($id_product){
		
		/*
		*	Obtenemos los selectores que trae por defecto Prestashop
		*/

		$sql = "SELECT pagl.name, pagl.id_attribute_group
				FROM " . _DB_PREFIX_ . "attribute_group_lang pagl, " . _DB_PREFIX_ . "attribute pa, " . _DB_PREFIX_ . "megaproductaddgroup pmag 
				WHERE pagl.id_attribute_group = pa.id_attribute_group AND pmag.id_group = pagl.id_attribute_group AND pmag.id_product = ".$id_product."
				GROUP BY NAME;";
				
		$check_selectores = Db::getInstance()->executeS($sql);
		$resultado = '';
				
		foreach($check_selectores as $ss){ 
			foreach($ss as $pp){ 
				$resultado .= $pp.'-';
			}
			$resultado = substr($resultado,0,strlen($resultado)-1);
			$resultado .=  '_';
		}
		$resultado = substr($resultado,0,strlen($resultado)-1);
	
		return $resultado;
	}
	
	private function isMegaproduct($id_product, $fecha_inicio, $fecha_fin){
		echo '<script>';
		echo 'console.log("ES MEGAPRODUCT");';
		echo '</script>';
		
		$resultado_new = array();
		
		if(!empty(Tools::getValue('id_atributo'))){
			$resultado = array();
			
			$id_atributo = Tools::getValue('id_atributo');
			
			/*
			*	Obtenemos los ID's de los atributos que componen el grupo del que queremos obtener estadisticas
			*/
			
			$id_group = explode("-", $id_atributo)[1];
			$sql = "SELECT id_attribute FROM ps_attribute WHERE id_attribute_group=".$id_group." ORDER BY POSITION;";
			
			$check_consulta = Db::getInstance()->executeS($sql);
			$atributos_del_grupo = '';
			
			foreach($check_consulta as $ss){ 
				foreach($ss as $pp){ 
					$atributos_del_grupo .= $pp.'-';
				}
			}
			$atributos_del_grupo = substr($atributos_del_grupo,0,strlen($atributos_del_grupo)-1);
			$array_attributos_del_grupo = explode("-", $atributos_del_grupo);
			
			/*echo '<script>';
			echo 'console.log("Inicio:'.$sql.'");';
			echo '</script>';
			
			foreach($array_attributos_del_grupo as $uno){
				echo '<script>';
				echo 'console.log("e : '.$uno.'");';
				echo '</script>';
			}
				
			echo '<script>';
			echo 'console.log("Fin");';
			echo '</script>';
			*/
			
			/*
			*	Ahora ejecutamos la consulta de Belen que devuelve una tupla cantidad-atributos de configuracion de un producto
			*/
				
			$sql = "SELECT SUM(mp.quantity) AS cantidad, mp.attributes AS articulo 
FROM ps_orders o, ps_megaproductcart mp 
WHERE o.id_cart=mp.id_cart AND mp.id_product=".$id_product." AND o.current_state<>34 
AND o.date_add>'".$fecha_inicio." 00:00:00' AND o.date_add<'".$fecha_fin." 23:59:59' 
GROUP BY _splitear(mp.attributes,'-') ;";

			//echo $sql;
			
			$check_consulta = Db::getInstance()->executeS($sql);
			$resultado_str = '';
					
			foreach($check_consulta as $ss){ 
				foreach($ss as $pp){ 
					$resultado_str .= $pp.'--';
				}
				$resultado_str = substr($resultado_str,0,strlen($resultado_str)-2);
				$resultado_str .=  '_';
			}
			$resultado_str = substr($resultado_str,0,strlen($resultado_str)-1);
			$resultado = explode("_", $resultado_str);
			
			
			
			/*
			*	Resultado es lo que se va a devolver, pero hay que eliminar los elementos que no contengan atributos del grupo del que queremos estadisticas
			*/
			
			echo '<script>';
			echo 'console.log("Llegamos a la seccion debug");';
			echo '</script>'; 
			
			foreach($resultado as $elemento){ 
				$aux = explode("--", $elemento)[1];
				/*echo '<script>';
				echo 'console.log("elemento : '.$elemento.'");';
				echo 'console.log("elemento expliteado : '.$aux.'");';
				echo '</script>';*/
				$array_aux = explode("-", $aux);
				foreach($array_aux as $attr){
					if(in_array($attr, $array_attributos_del_grupo)){
						$attr_traducido = $this->traducirAtributo($attr);
						$nuevo_elemento = explode("--", $elemento)[0].'--'.$attr_traducido;
						array_push($resultado_new, $nuevo_elemento);
					}
				}
			}
			
			echo '<script>';
			echo 'console.log("Salimos de la seccion debug");';
			echo '</script>';
			/*
			*	Ahora tenemos que unificar las cantidades de las medidas
			*/
		}
		
		$resultado_new = $this->agruparAtributos($resultado_new);
		
		return $resultado_new;
	}
	
	private function agruparAtributos($resultado){
		
		$resultado_new = array();
		
		$diferentes_atributos = array();
		
		foreach($resultado as $elemento){
			$e_text = explode("--", $elemento)[1];
			if(!in_array($e_text, $diferentes_atributos)){
				array_push($diferentes_atributos, $e_text);
			}
		}
		
		foreach($diferentes_atributos as $atributo){
			$cantidad = 0;
			foreach($resultado as $elemento){
				$v_elem = explode("--", $elemento);
				if($v_elem[1] == $atributo){
					$cantidad = $cantidad + (int)$v_elem[0];
				}
			}
			
			array_push($resultado_new, $cantidad.'--'.$atributo);
		}
		
		return $resultado_new;
	}
	
	private function traducirAtributo($attr_id){
		$sql = "SELECT name FROM ps_attribute_lang WHERE id_attribute=".$attr_id." AND id_lang=1;";

		//echo $sql;
			
		$check_consulta = Db::getInstance()->executeS($sql);
		$resultado = 'error traduccion';
					
		foreach($check_consulta as $ss){ 
			foreach($ss as $pp){ 
				$resultado = $pp;
			}
		}
		
		return $resultado;
	}
	
	private function isPrestashop($id_product, $fecha_inicio, $fecha_fin){ 
		echo '<script>';
		echo 'console.log("ES PRESTASHOP");';
		echo '</script>';
		
		$resultado = array();
		
		if(!empty(Tools::getValue('id_atributo'))){
			$id_atributo = Tools::getValue('id_atributo');
			$sql = "SELECT SUM(product_quantity),pal.name FROM ps_order_detail od,ps_orders o,ps_product_attribute_combination ppc,ps_attribute_lang pal, ps_attribute pa  WHERE o.id_order=od.id_order AND ppc.id_product_attribute=od.product_attribute_id AND pal.id_attribute=ppc.id_attribute AND pal.id_lang=1 AND pa.id_attribute_group = ".explode("-", $id_atributo)[1]." AND pa.id_attribute = pal.id_attribute AND current_state<>34 AND product_id=".$id_product." AND o.date_add>='".$fecha_inicio." 00:00:00' AND o.date_add<='".$fecha_fin." 23:59:59' GROUP BY pal.name";
			
			echo '<script>';
			echo 'console.log("SQL: '.$sql.'");';
			echo '</script>';
			
			$check_consulta = Db::getInstance()->executeS($sql);
			$resultado_str = '';
					
			foreach($check_consulta as $ss){ 
				foreach($ss as $pp){ 
					$resultado_str .= $pp.'--';
				}
				$resultado_str = substr($resultado_str,0,strlen($resultado_str)-1);
				$resultado_str .=  '_';
			}
			$resultado_str = substr($resultado_str,0,strlen($resultado_str)-1);
			$resultado = explode("_", $resultado_str);
		}
		
		return $resultado;
	}

	// BACK OFFICE HOOKS

	/**
 	 * admin <head> Hook
	 */
	public function hookDisplayBackOfficeHeader()
	{
		// CSS
		$this->context->controller->addCSS($this->_path.'views/css/elusive-icons/elusive-webfont.css');
		// JS
		// $this->context->controller->addJS($this->_path.'views/js/js_file_name.js');	
	}

	/**
	 * Hook for back office dashboard
	 */
	public function hookDisplayAdminHomeQuickLinks()
	{	
		$this->context->smarty->assign('minicskeleton', $this->name);
	    return $this->display(__FILE__, 'views/templates/hooks/quick_links.tpl');    
	}
}

?>
