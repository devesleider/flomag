<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
try {
?>
<div class="pagina-respuesta">
	<div class="pagina-respuesta-left">
		<div class="pagina-respuesta-left-header">Datos de la transacción</div>
		<div class="pagina-respuesta-left-content">
			<div class="conf-content">
				<?php if($this->item->nombre_estado=="PENDIENTE"):?>
					<p align="center">Por favor verificar si el débito fue realizado en el Banco</p>
					<p>&nbsp;</p>
				<?php endif;?>
				<p><strong>Nit: </strong> 9005249126</p>
				<p><strong>Razón social: </strong> ALIANZA NACIONAL DE CAPITALES SAS</p>
				<p><strong>Total: </strong> $<?php  echo number_format($this->item->valor,0,'','.');?></p>
				<p><strong>Fecha de Transacción: </strong> <?php  echo $this->item->fecha_transaccion;?></p>
				<p><strong>Estado: </strong> <?php echo $this->item->nombre_estado;?></p>
				<p><strong>Banco: </strong> <?php echo $this->item->nombre_banco;?></p>
				<p><strong>Número de Factura: </strong> <?php echo $this->item->id_transaccion;?></p>
				<p><strong>Referencia: </strong> <?php echo $this->item->id;?></p>
				<p><strong>Código Único de Seguimiento: </strong> <?php echo $this->item->codigo_trazabilidad;?></p>
				<p><strong>Dirección IP: </strong> <?php echo $this->item->ip;?></p>
				<p>&nbsp;</p>
				<p class="conf-subt">Descripción</p>
				<?php 
				if($this->rutas):
					$i = 1;
					foreach($this->rutas as $ruta):
						$sillas = $this->getSillas($ruta->id);
						$sillas_r = "";
						foreach($sillas as $row){
							$sillas_r .= $row->silla."-";
						}
				?>
					<p><strong>Viaje <?php echo $i;?></strong></p>
					<p><strong><?php echo $ruta->origen;?> - <?php echo $ruta->destino;?></strong></p>
					<p><strong>No de pasajeros: </strong> <?php echo count($sillas);?></p>
					<p><strong>Sillas: </strong> <?php echo $sillas_r;?></p>
					<p><strong>Fecha: </strong> <?php echo $ruta->fecha;?></p>
					<p><strong>Hora: </strong> <?php echo $ruta->hora;?></p>
					<p>&nbsp;</p>
				<?php $i++; endforeach; endif;?>
				
			
			</div>
		</div>
	</div>
	<div class="pagina-respuesta-right">
		<div class="pagina-respuesta-right-imprimir">
			<h3>¡Imprimir!</h3>
			<div class="pagina-respuesta-right-imprimir-content">
				Verifica que la transacción haya sido aprobada. En el momento de viajar presenta impreso el tiquete que recibirás en tu correo con los detalles de tu viaje.
			</div>
			<div class="pagina-respuesta-right-imprimir-button">
				<a href="javascript:window.print(); void 0;" onclik="window.print()">IMPRIMIR</a>
			</div>
		</div>
		<div class="pagina-respuesta-right-content">
			<h3>Datos de contacto</h3>
			<div class="pagina-respuesta-right-contacto-content">
				<p>Para consultar datos del estado de la transacción puedes contactarnos a traves de los siguientes metodos:</p>
				<p>flotamagdalena.pagosonline@gmail.com<br>Teléfono: (1) 4287650 Bogotá<br>Celular: (57) 310 2199353</p>
			</div>
		</div>
	</div>
</div>
<?php //$session->clear("tiquete");?>
<table class="datos-transaccion" align="center" width="100%" style="display:none;">

	<tr>
		<td><strong>Nit</strong></td><td>9005249126</td>
	</tr>
	<tr>
		<td><strong>Razón Social</strong></td><td>ALIANZA NACIONAL DE CAPITALES SAS</td>
	</tr>
	<tr>
		<td><strong>Número de Factura</strong></td>
		<td><?php  echo $this->item->id_transaccion;?></td>
	</tr>
	<tr>
		<td><strong>Referencia</strong></td>
		<td><?php  echo $this->item->id;?></td>
	</tr>
	<tr>
		<td><strong>Código Único de Seguimiento</strong></td>
		<td><?php echo $this->item->codigo_trazabilidad;?></td>
	</tr>
	<tr>
		<td><strong>Fecha de la Transacción</strong></td>
		<td><?php  echo $this->item->fecha_transaccion;?></td>
	</tr>
	<tr>
		<td><strong>Estado</strong></td>
		<td><?php  echo $this->item->nombre_estado;?></td>
	</tr>
	<tr>
		<td><strong>Dirección IP</strong></td>
		<td><?php echo $this->item->ip;?></td>
	</tr>
	<tr>
		<td><strong>Total</strong></td>
		<td>$<?php  echo number_format($this->item->valor,0,'','.');?></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><strong>DETALLES DE LA COMPRA</strong></td>
	</tr>
	<?php 
		if($this->rutas):
			$i = 1;
			foreach($this->rutas as $ruta):
				$sillas = $this->getSillas($ruta->id);
				$sillas_r = "";
				foreach($sillas as $row){
					$sillas_r .= $row->silla."-";
				}
		?>
	<tr>
		<td colspan="2" ><strong>Viaje <?php echo $i;?></strong></td>
	</tr>
	<tr>
		<td><strong>Ruta</strong></td>
		<td><?php echo $ruta->origen;?> - <?php echo $ruta->destino;?></td>
	</tr>
	<tr>
		<td><strong>No de Pasajeros</strong></td>
		<td><?php echo count($sillas);?></td>
	</tr>
	<tr>
		<td><strong>Sillas</strong></td>
		<td><?php  echo $sillas_r;?></td>
	</tr>
	<tr>
		<td><strong>Fecha</strong></td>
		<td><?php echo $ruta->fecha;?></td>
	</tr>
	<tr>
		<td><strong>Hora</strong></td>
		<td><?php echo $ruta->hora;?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<?php $i++; endforeach; endif;?>
	
</table>
<?php } catch (Exception $e) {
	echo '<h2>Error en la Transacción</h2>';
	echo $e->getMessage();
	//var_dump($tiquete);
	if(empty($tiquete))
		echo "<p></p><p><strong>No existe transacción en proceso. Revise su Historial de Pagos</strong></p>";	
}?>