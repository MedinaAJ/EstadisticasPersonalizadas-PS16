<!-- minicskeleton admin page -->
	</br>
	<h2>Estadisticas Unificadas Para Productos de VentadeColchones.com</h2>
	<h5>Dise√±ado por Alejandro Medina</h5>
	
	<form method="POST" id="id_producto" action="{$request_uri}">
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
					<div class="chart-wrap horizontal"> <!-- quitar el estilo "horizontal" para visualizar verticalmente -->
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
						{*<tr>
						  <th scope="row">1</th>
						  <td>Mark</td>
						  <td>Otto</td>
						  <td>@mdo</td>
						</tr>
						<tr>
						  <th scope="row">2</th>
						  <td>Jacob</td>
						  <td>Thornton</td>
						  <td>@fat</td>
						</tr>
						<tr>
						  <th scope="row">3</th>
						  <td>Larry</td>
						  <td>the Bird</td>
						  <td>@twitter</td>
						</tr> *}
					  </tbody>
					</table>
					
				{else}
					<h2>No hay estadisticas disponibles para este producto</h2>
				{/if}
			{/if}
		{/if}
		
		
	</form>
	
	<p>DEBUG: {$debug}</p>

<!-- end minicskeleton admin page -->

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
 
</style>