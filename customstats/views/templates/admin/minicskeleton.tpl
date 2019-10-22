<!-- minicskeleton admin page -->
	</br>
	<h2>Estadisticas Unificadas Para Productos de VentadeColchones.com</h2>
	<h5>Diseñado por Alejandro Medina</h5>
	
	<form method="POST" action="{$request_uri}">
		<p>
		ID Producto: <input type="number" name="id"> <input type="submit" name="btnid" value="Cargar estadisticas"> 
		<a class="btn" href="{$request_uri|escape:'htmlall':'UTF-8'}&idProd={$id_producto}" target="_self">Cargar propiedades producto</a>
		</p>
		<h3>ID seleccionado: {$id_producto}</h3>
		<h3>Valor recogido : {$aux}</h3>
		
	</form>
<!-- end minicskeleton admin page -->