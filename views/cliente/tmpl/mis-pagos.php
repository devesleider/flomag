<?php defined('_JEXEC') or die;
	$metodos = array("0"=>"SIN DEFINIR","1"=>"Tarjeta de Crédito","2"=>"Tarjeta Débito","3"=>"Puntos");
	$user = JFactory::getUser();
	if (!$user->guest) {
		$i=2;
?>
<div class="viajes">
	<p><strong>CUS</strong> = Codigo Unico de Seguimiento de la transacción en PSE</p>
	<table class="version-ds">
		<thead>
			<td>Referencia</td>
			<td>No Factura</td>
			<td>CUS</td>
			<td>Valor</td>
			<td>Fecha de Transacción</td>
			<td>Estado</td>
			<td>Método de Pago</td>
			<td></td>
		</thead>
		<?php 
		foreach($this->pagos as $item):
			$class = (($i++%2)==0) ? 'i' : 'p';
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $item->id; ?></td>
			<td><?php echo $item->id_transaccion; ?></td>
			<td><?php echo $item->codigo_trazabilidad; ?></td>
			<td>$<?php echo number_format($item->valor);?></td>
			<td><?php echo $item->fecha_transaccion; ?></td>
			<td><?php echo $item->nombre_estado; ?></td>
			<td><?php echo $metodos[$item->metodo_pago]; ?></td>
			<td><a href="index.php?option=com_flota&view=cliente&layout=pago&pid=<?php echo $item->id;?>">Ver Información</a></td>			
		</tr>
	<?php endforeach;?>
	</table>
	<?php foreach($this->pagos as $item):?>
	<table class="version-mb">
		<tr>
			<td class="thead">Referencia</td>
			<td class="tbody"><?php echo $item->id; ?></td>
		</tr>
		<tr>
			<td class="thead">No Factura</td>
			<td class="tbody"><?php echo $item->id_transaccion; ?></td>
		</tr>
		<tr>
			<td class="thead">CUS</td>
			<td class="tbody"><?php echo $item->codigo_trazabilidad; ?></td>
		</tr>
		<tr>
			<td class="thead">Valor</td>
			<td class="tbody">$<?php echo number_format($item->valor);?></td>
		</tr>
		<tr>
			<td class="thead">Fecha de Transacción</td>
			<td class="tbody"><?php echo $item->fecha_transaccion; ?></td>
		</tr>
		<tr>
			<td class="thead">Estado</td>
			<td class="tbody"><?php echo $item->nombre_estado; ?></td>
		</tr>
		<tr>
			<td class="thead">Métodos Pago</td>
			<td class="tbody"><?php echo $metodos[$item->metodo_pago]; ?></td>
		</tr>
		<tr>
			<td class="tbody" colspan="2">
				<a href="index.php?option=com_flota&view=cliente&layout=pago&pid=<?php echo $item->id;?>">Ver Información</a>
			</td>
		</tr>
	</table>
	<?php endforeach;?>
</div>
<?php }?>