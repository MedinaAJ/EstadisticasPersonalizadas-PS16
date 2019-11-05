</br>
<!-- minicskeleton admin page -->
<link rel="stylesheet" type="text/css" href="https://ventadecolchones.com/modules/customstats/views/css/Chart.css">
<script src="https://ventadecolchones.com/modules/customstats/views/js/Chart.js"></script>
<div class="panel" style="border-radius: 17px; background: #ffff;">
	<div class="panel-heading" style="border-bottom: solid 1px #585A69;">
		<i class="icon-signal"></i>
		<span style="font-size: 14px; text-transform: uppercase;">Estadisticas Unificadas Para Productos de VentadeColchones.com</span>
	</div>
	
	<form method="POST" id="id_producto" action="{$request_uri}">
		<p>Fechas inicio</p>
		<input type="date" name="fechaInicio" value="{$fecha_inicio}"> 
		<p>Fechas fin</p>
		<input type="date" name="fechaFin" value="{$fecha_fin}"> 
		<input type="submit" name="btnid" value="{$texto_boton}"> 
		{if $id_producto == 0}
			<p>ID Producto: <input id="id_product" type="number" name="id" required ></p>
		{else}	
			<p>Id Producto: <input id="id_product" type="number" name="id" value="{$id_producto}" required ></p>
			{if $existe != 1}
				<p>El producto al que quieres acceder no existe</p>
			{else}
				<p>El producto es de tipo: {$tipo_producto}</p>
				<select id="atributos_producto" name="id_atributo">
					{foreach item=atributo from=$selector_producto}
						<option value="{$atributo}">{$atributo}</option>
					{/foreach}
				</select>
				{if $estadisticas_calculadas == 1}	
					{*<div class="chart-wrap horizontal"> <!-- quitar el estilo "horizontal" para visualizar verticalmente -->
					  <div class="title">Grafico con estadisticas del producto ID: {$id_producto} y atributo: {$atributo}</div>
					 
					  <div class="grid" style="margin-left: 330px;">
						  {assign var="max_value" value=0}
						  {foreach item=elemento from=$estadisticas}
								{assign var="elemento_split" value="--"|explode:$elemento}
								<div class="bar" style="--bar-value:{$elemento_split[0]}%;" data-name="{$elemento_split[1]}" title="{$elemento}"></div>
								{if $elemento_split[0] > $max_value}
									{assign var="max_value" value=$elemento_split[0]}
								{/if}
						  {/foreach}
					  </div>
					</div>
					*}
					<table class="table table-striped">
					  <thead>
						<tr>
						  <th scope="col">Cantidad</th>
						  <th scope="col">Atributo</th>
						</tr>
					  </thead>
					  <tbody>
						  {foreach item=elemento from=$estadisticas}
								{assign var="elemento_split" value="--"|explode:$elemento}
								<tr>
									<td>{$elemento_split[0]}</td>
									<td>{$elemento_split[1]}</td>
								</tr>
						  {/foreach}
					  </tbody>
					</table>
					
				{else}
					<h2>No hay estadisticas disponibles para este producto</h2>
				{/if}
			{/if}
		{/if}
		
		<h5>Dise√±ado por Alejandro Medina</h5>
	</form>
</div>
	{if $estadisticas_calculadas == 1}	
		<canvas id="densityChart" width="600" height="400"></canvas>
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
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)',
		'rgba(255, 96, 68, 1)'
	  ],
	  borderColor: [
		'rgba(0, 99, 132, 1)',
		'rgba(30, 99, 132, 1)',
		'rgba(60, 99, 132, 1)',
		'rgba(90, 99, 132, 1)',
		'rgba(120, 99, 132, 1)',
		'rgba(150, 99, 132, 1)',
		'rgba(180, 99, 132, 1)',
		'rgba(210, 99, 132, 1)',
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

{/if}

<!-- end minicskeleton admin page -->
{*
<style>
    .chart-wrap {
        --chart-width:420px;
        --grid-color:#aaa;
        --bar-color:#F16335;
        --bar-thickness:40px;
        --bar-rounded: 3px;
        --bar-spacing:10px;
 
        font-family:sans-serif;
        width:var(--chart-width);
    }
 
    .chart-wrap .title{
        font-weight:bold;
        padding:1.8em 0;
        text-align:center;
        white-space:nowrap;
    }
 
    /* cuando definimos el gr·fico en horizontal, lo giramos 90 grados */
    .chart-wrap.horizontal .grid{
        transform:rotate(-90deg);
    }
 
    .chart-wrap.horizontal .bar::after{
        /* giramos las letras para horizontal*/
        transform: rotate(45deg);
        padding-top:0px;
        display: block;
    }
 
    .chart-wrap .grid{
        margin-left:50px;
        position:relative;
        padding:5px 0 5px 0;
        height:100%;
        width:100%;
        border-left:2px solid var(--grid-color);
    }
 
    /* posicionamos el % del gr·fico*/
    .chart-wrap .grid::before{
        font-size:0.8em;
        font-weight:bold;
        content:'0%'; /* VALOR MINIMO */
        position:absolute;
        left:-0.5em;
        top:-1.5em;
    }
    .chart-wrap .grid::after{
        font-size:0.8em;
        font-weight:bold;
        content:'{$max_value}%'; /* VALOR MAXIMO */
        position:absolute;
        right:-1.5em;
        top:-1.5em;
    }
 
    /* giramos las valores de 0% y 100% para horizontal */
    .chart-wrap.horizontal .grid::before, .chart-wrap.horizontal .grid::after {
        transform: rotate(90deg);
    }
 
    .chart-wrap .bar {
        width: var(--bar-value);
        height:var(--bar-thickness);
        margin:var(--bar-spacing) 0;
        background-color:var(--bar-color);
        border-radius:0 var(--bar-rounded) var(--bar-rounded) 0;
    }
 
    .chart-wrap .bar:hover{
        opacity:0.7;
    }
 
    .chart-wrap .bar::after{
        content:attr(data-name);
        margin-left:100%;
        padding:10px;
        display:inline-block;
        white-space:nowrap;
    }
 
</style>*}