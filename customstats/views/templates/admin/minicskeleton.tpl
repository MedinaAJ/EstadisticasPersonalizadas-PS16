</br>
<!-- minicskeleton admin page -->
<link rel="stylesheet" type="text/css" href="https://ventadecolchones.com/modules/customstats/views/css/Chart.css">
<link rel="stylesheet" type="text/css" href="https://ventadecolchones.com/modules/customstats/views/css/bootstrap.css">
<script src="https://ventadecolchones.com/modules/customstats/views/js/Chart.js"></script>
<div class="panel" style="border-radius: 17px; background: #ffff;">
	<div class="panel-heading" style="border-bottom: solid 1px #585A69;">
		<i class="icon-signal"></i>
		<span style="font-size: 14px; text-transform: uppercase;">Estadisticas Unificadas Para Productos de VentadeColchones.com</span>
	</div>
	
	<form method="POST" id="id_producto" action="{$request_uri}">
		<div class="row">
		  <div class="col-md-6">
			{if $id_producto == 0}
				<div class="row" style="padding: 15px; margin-left: 50px;">
					<p><strong>ID Producto</strong></p> 
					<input class="form-control" style="max-width: 60%;" id="id_product" type="number" name="id" required >
				</div>
			{else}	
				<div class="row" style="padding: 15px; margin-left: 50px;">
					<p><strong>ID Producto</strong></p> 
					<input class="form-control" style="max-width: 60%;" id="id_product" type="number" name="id" value="{$id_producto}" required >
				</div>
				{if $existe == 1}
				<div class="row" style="padding: 15px; margin-left: 50px;">
					<p><strong>Atributos del producto</strong></p> 
					<select class="form-control" style="max-width: 60%;" id="atributos_producto" name="id_atributo">
						{foreach item=atributo from=$selector_producto}
							<option value="{$atributo}">{$atributo}</option>
						{/foreach}
					</select>
				</div>
				<div class="row" style="padding: 15px; margin-left: 50px;">
					<p>El producto es de tipo: <strong>{$tipo_producto}</strong></p>
				</div>
				{/if}
			{/if}
		  </div>
		  <div class="col-md-6">
			<div class="row" style="padding: 20px;">
				<p><strong>Fechas Inicio</strong></p>
				<input class="form-control" style="max-width: 60%;" type="date" name="fechaInicio" value="{$fecha_inicio}"> 
			</div>
			<div class="row" style="padding: 20px;">
				<p><strong>Fechas Fin</strong></p>
				<input class="form-control" style="max-width: 60%;" type="date" name="fechaFin" value="{$fecha_fin}"> 
			</div>
			<div class="row">
				<label class="switch" style="width: 61px; margin-left: 21px;">
				  <input type="checkbox" id="checkCompare" onclick="prepararComparacion()" name="isCompare">
				  <span class="slider round"></span>
				</label>
				<p style="
					font-size: 20px;
					margin-top: 2px;
				">&nbsp&nbspComparar</p>
			</div>
			
			
			
			<div id="fechasCompare" class="row" style="display: none;">
				<p>Checkbox is CHECKED!</p>
				
				<div class="row" style="padding: 20px;">
					<p><strong>Fechas Inicio Comparada</strong></p>
					<input class="form-control" style="max-width: 60%;" type="date" name="fechaInicioC" value="{$fecha_inicio_comp}"> 
				</div>
				<div class="row" style="padding: 20px;">
					<p><strong>Fechas Fin Comparada</strong></p>
					<input class="form-control" style="max-width: 60%;" type="date" name="fechaFinC" value="{$fecha_fin_comp}"> 
				</div>
			</div>
			
			
			
			<div class="row" style="padding: 20px;">
				{if $id_producto != 0}	
					{if $existe != 1}
						<h3>El producto al que quieres acceder no existe</h3>
					{else}
						{if $estadisticas_calculadas == 1}	
							<div class="alert alert-success" role="alert">
							  <h4 class="alert-heading">Existen estadisticas!</h4>
							  <p>Si cambias de atributo vuelve a pulsar el boton.</p>
							</div>
						{else}
							<div class="alert alert-danger" role="alert">
							  <h4 class="alert-heading">No hay estadisticas disponibles...</h4>
							  <p>No hay estadisticas disponibles para este producto.</p>
							  <hr>
							  <p>Cambie el ID para buscar estadisticas de otro producto.</p>
							</div>
						{/if}
					{/if}
				{/if}
			</div>
		  </div>
		</div>
		
		<input class="btn btn-primary btn-lg" type="submit" name="btnid" value="{$texto_boton}" style="padding: 10px 30px 10px 30px;color: #fff;background: #ff6044;margin-left: 64px;margin-top: 10px;font-size: 17px;"> 
		<h5 style="float: right;">Diseñado por Alejandro Medina</h5>
	</form>
</div>
	{if $estadisticas_calculadas == 1}	
	<div class="panel" style="border-radius: 17px; background: #ffff;">
		<div class="panel-heading" style="border-bottom: solid 1px #585A69;">
			<i class="icon-ok"></i>
			<span style="font-size: 14px; text-transform: uppercase;">resultados</span>
		</div>
		<table class="table table-striped" style="margin: auto; margin-top: 29px;">
					  <thead>
						<tr>
						  <th scope="col" style="text-align: right;">Cantidad</th>
						  <th scope="col">Atributo</th>
						</tr>
					  </thead>
					  <tbody>
						  {foreach item=elemento from=$estadisticas}
								{assign var="elemento_split" value="--"|explode:$elemento}
								<tr>
									<td style="text-align: right;">{$elemento_split[0]}</td>
									<td>{$elemento_split[1]}</td>
								</tr>
						  {/foreach}
					  </tbody>
		</table>
	
		<div style="margin:auto; width: 85%; margin-top: 29px;">
			<canvas id="densityChart" width="600" height="400"></canvas>
		</div>
	</div>
	{/if}
	
	
	<p>DEBUG: {$debug}</p>
	


{if $estadisticas_calculadas == 1}	

	<script>

	var densityCanvas = document.getElementById("densityChart");

	Chart.defaults.global.defaultFontFamily = "Lato";
	Chart.defaults.global.defaultFontSize = 18;

	var densityData = {
	  label: 'Unidades vendidas',
	  data: [{$valores_estadistica}],
	  backgroundColor: [
	  {foreach item=elemento from=$estadisticas}
		'rgba(255, 96, 68, 1)',
	  {/foreach}
		'rgba(255, 96, 68, 1)'
	  ],
	  borderColor: [
		{foreach item=elemento from=$estadisticas}
		  'rgba(210, 99, 132, 1)',
		{/foreach}
		'rgba(240, 99, 132, 1)'
	  ],
	  borderWidth: 2,
	  hoverBorderWidth: 0
	};

	var chartOptions = {
	  scales: {
		yAxes: [{
		  barPercentage: 0.5
		}]
	  },
	  elements: {
		rectangle: {
		  borderSkipped: 'left',
		}
	  }
	};

	var barChart = new Chart(densityCanvas, {
	  type: 'horizontalBar',
	  data: {
		labels: [{$elementos_estadistica}],
		datasets: [densityData],
	  },
	  options: chartOptions
	});

	</script>

<style>
table {
  border-collapse: collapse;
  width: 60% !important;
}

th, td {
  text-align: left;
  padding: 8px;
}

th {
  background-color: #4CAF50;
  color: white;
}
</style>

{/if}
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #ff6044;
}

input:focus + .slider {
  box-shadow: 0 0 1px #ff6044;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

<script>
	function prepararComparacion() {
	  // Get the checkbox
	  var checkBox = document.getElementById("checkCompare");
	  // Get the output text
	  var text = document.getElementById("fechasCompare");

	  // If the checkbox is checked, display the output text
	  if (checkBox.checked == true){
		text.style.display = "block";
	  } else {
		text.style.display = "none";
	  }
	}
</script>

<!-- end minicskeleton admin page -->
