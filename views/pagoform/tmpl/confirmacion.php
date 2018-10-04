<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$session = JFactory::getSession();
$tiquete = $session->get('tiquete');
	
	$app 			= JFactory::getApplication()->input;
	$component 		= $app->get('option');
	$params 		= JComponentHelper::getParams($component);

	$soap_client = new soapclient($params->get('pagos_url_td'),array('trace'=> true));
	$header_part = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        		<wsse:UsernameToken>
	            		<wsse:Username>' .$params->get('pagos_usuario') . '</wsse:Username>
	            		<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
	        		</wsse:UsernameToken>
	    			</wsse:Security>';
$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
$soap_header     = new SoapHeader( $params->get('pagos_url_td'), 'Security', $soap_var_header );
$soap_client->__setSoapHeaders($soap_header);
$envio = array('informacionConsulta'=>array('idTransaccion' => $tiquete['id_transaccion'],'referencia' => $tiquete['referencia']));
try {
	$result = $soap_client->consultarTransaccionPSE($envio);// Se imprime la respuesta del web service
	$respuesta_transaccion = $result->respuestaTransaccionPSE;
?>
<div class="pagina-respuesta">
	<div class="pagina-respuesta-left">
		<div class="pagina-respuesta-left-header">Datos de la transacción</div>
		<div class="pagina-respuesta-left-content">
			<div class="conf-content">
				<p style="text-align:justify;">Para mayor información o ayuda con tu compra por favor envíanos un correo a flotamagdalena.pagosonline@gmail.com  o comunícate a la línea 4287650 o al  310 2199353  de lunes a viernes de 7:00 a 6:00pm o los sábados de 8:30 a 1:00pm.</p><p>&nbsp;</p>
				<p style="text-align:justify;">Apreciado Cliente: El pasaje se hará efectivo según el día , hora y ruta señalados. En ningún caso habrá devoluciones de dinero. El pasajero puede transportar una valija con un peso máximo de 25 kg sin pagar costo adicional, si sobrepasa este peso debe cancelar un valor adicional y hacer el proceso de aforo. </p><p>&nbsp;</p>
				<p style="text-align:justify;">  A su correo registrado en la pagina web, le sera enviado un pdf con el respectivo tiquete generado por Flota Magdalena S.A , que confirmara su compra y con el que podrá realizar el abordaje. Cualquier novedad que presente;  puede contactarse a la linea 310-2199353 o al correo flotamagdalena.pagosonline@gmail.com</p>
				<p><strong>EL ENVIO DE SU TIQUETE DE ABORDAJE NO ES AUTOMATICO A LA COMPRA , ESTE SERA ENVIADO A SU DIRECCION DE CORREO REGISTRADA EN LA PAGINA WEB, A MAS TARDAR EL DIA ANTERIOR DE SU FECHA DE VIAJE .</strong></p>
				<p>&nbsp;</p>
				<?php if($respuesta_transaccion->nombreEstado=="PENDIENTE"):?>
					<p align="center">Por favor verificar si el débito fue realizado en el Banco</p>
					<p>&nbsp;</p>
				<?php endif;?>
				<?php if($respuesta_transaccion->nombreEstado=="APROBADA"):?>
					<p align="center">En este momento su Factura #<?php echo $respuesta_transaccion->idTransaccion;?> ha finalizado
						su proceso de pago y cuya transacción se encuentra APROBADA en su entidad financiera. Si desea mayor información 
						sobre el estado de su operación puede comunicarse a nuestras lineas de atención al cliente Teléfono: (1)5674567 - Celular: +57310-2199353
						 o enviar un correo electronico a flotamagdalena.pagosonline@gmail.com y preguntar por el estado de su transacción # <?php echo $respuesta_transaccion->codigoTrazabilidad;?></p>
					<p>&nbsp;</p>
				<?php endif;?>
				<p>&nbsp;</p>
				<p><strong>Nit: </strong> 9005249126</p>
				<p><strong>Razón social: </strong> ALIANZA NACIONAL DE CAPITALES SAS</p>
				<p><strong>Total: </strong> $<?php  echo number_format($respuesta_transaccion->valor,0,'','.');?></p>
				<p><strong>Fecha de Transacción: </strong> <?php  echo $respuesta_transaccion->fechaProcesamiento;?></p>
				<p><strong>Estado: </strong> <?php echo $respuesta_transaccion->nombreEstado;?></p>
				<p><strong>Banco: </strong> <?php echo $respuesta_transaccion->bancoPSERespuesta->nombre;?></p>
				<p><strong>Número de Factura: </strong> <?php echo $respuesta_transaccion->idTransaccion;?></p>
				<p><strong>Referencia: </strong> <?php echo $respuesta_transaccion->referencia;?></p>
				<p><strong>Código Único de Seguimiento: </strong> <?php echo $respuesta_transaccion->codigoTrazabilidad;?></p>
				<p><strong>Dirección IP: </strong> <?php echo $tiquete['direccion_ip'];?></p>
				<p>&nbsp;</p>
				<p class="conf-subt">Descripción</p>
				<p><strong>Viaje 1</strong></p>
				<p><strong><?php echo $this->ObRutaIda->origen;?> - <?php echo $this->ObRutaIda->destino;?></strong></p>
				<p><strong>No de pasajeros: </strong> <?php echo $tiquete['pasajeros'];?></p>
				<p><strong>Sillas: </strong> <?php echo $tiquete['sillas_ida'];?></p>
				<p><strong>Fecha: </strong> <?php echo $tiquete['fecha_salida'];?></p>
				<p><strong>Hora: </strong> <?php echo $this->ObRutaIda->hora;?></p>
				<p>&nbsp;</p>
				
				<?php if($tiquete['ruta_regreso']):?>
					<p><strong>Viaje 2</strong></p>
					<p><strong><?php echo $this->ObRutaReg->origen;?> - <?php echo $this->ObRutaReg->destino;?></strong></p>
					<p><strong>No de pasajeros:</strong><?php echo $tiquete['pasajeros'];?></p>
					<p><strong>Sillas:</strong><?php echo $tiquete['sillas_regreso'];?></p>
					<p><strong>Fecha:</strong><?php echo $tiquete['fecha_regreso'];?></p>
					<p><strong>Hora:</strong><?php echo $this->ObRutaReg->hora;?></p>
					<p>&nbsp;</p>
				<?php endif;?>
			
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
<?php $session->clear("tiquete");?>
<table class="datos-transaccion" align="center" width="100%" style="display:none;">

	<tr>
		<td><strong>Nit</strong></td><td>9005249126</td>
	</tr>
	<tr>
		<td><strong>Razón Social</strong></td><td>ALIANZA NACIONAL DE CAPITALES SAS</td>
	</tr>
	<tr>
		<td><strong>Número de Factura</strong></td>
		<td><?php  echo $respuesta_transaccion->idTransaccion;?></td>
	</tr>
	<tr>
		<td><strong>Referencia</strong></td>
		<td><?php  echo $respuesta_transaccion->referencia;?></td>
	</tr>
	<tr>
		<td><strong>Código Único de Seguimiento</strong></td>
		<td><?php echo $respuesta_transaccion->codigoTrazabilidad;?></td>
	</tr>
	<tr>
		<td><strong>Fecha de la Transacción</strong></td>
		<td><?php  echo $respuesta_transaccion->fechaProcesamiento;?></td>
	</tr>
	<tr>
		<td><strong>Estado</strong></td>
		<td><?php  echo $respuesta_transaccion->nombreEstado;?></td>
	</tr>
	<tr>
		<td><strong>Dirección IP</strong></td>
		<td><?php echo $tiquete['direccion_ip'];?></td>
	</tr>
	<tr>
		<td><strong>Total</strong></td>
		<td>$<?php  echo $respuesta_transaccion->valor;?></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><strong>DETALLES DE LA COMPRA</strong></td>
	</tr>
	<tr>
		<td colspan="2" ><strong>Viaje Ida</strong></td>
	</tr>
	<tr>
		<td><strong>Ruta</strong></td>
		<td><?php echo $this->ObRutaIda->origen.' - '.$this->ObRutaIda->destino;?></td>
	</tr>
	<tr>
		<td><strong>No de Pasajeros</strong></td>
		<td><?php  echo $tiquete['pasajeros'];?></td>
	</tr>
	<tr>
		<td><strong>Sillas</strong></td>
		<td><?php  echo $tiquete['sillas_ida'];?></td>
	</tr>
	<tr>
		<td><strong>Fecha</strong></td>
		<td><?php  echo $tiquete['fecha_salida'];?></td>
	</tr>
	<tr>
		<td><strong>Hora</strong></td>
		<td><?php  echo $this->ObRutaIda->hora;?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<?php if($tiquete['ruta_regreso']):?>
		<tr>
			<td colspan="2" ><strong>Viaje Ida</strong></td>
		</tr>
		<tr>
			<td><strong>Ruta</strong></td>
			<td><?php echo $this->ObRutaReg->origen.' - '.$this->ObRutaReg->destino;?></td>
		</tr>
		<tr>
			<td><strong>No de Pasajeros</strong></td>
			<td><?php  echo $tiquete['pasajeros'];?></td>
		</tr>
		<tr>
			<td><strong>Sillas</strong></td>
			<td><?php  echo $tiquete['sillas_regreso'];?></td>
		</tr>
		<tr>
			<td><strong>Fecha</strong></td>
			<td><?php  echo $tiquete['fecha_regreso'];?></td>
		</tr>
		<tr>
			<td><strong>Hora</strong></td>
			<td><?php  echo $this->ObRutaReg->hora;?></td>
		</tr>
	<?php endif;?>
</table>
<?php } catch (Exception $e) {
	echo '<h2>Error en la Transacción</h2>';
	echo $e->getMessage();
	echo '<p>No se pudo crear la transacción, por favor intente más tarde o comuníquese con nuestra línea de atención al cliente Teléfono: (1)5674567 - Celular: +573102199353 o al correo electrónico flotamagdalena.pagosonline@gmail.com</p>';
	//var_dump($tiquete);
	if(empty($tiquete))
		echo "<p></p><p><strong>No existe transacción en proceso. Revise su Historial de Pagos</strong></p>";	
}?>