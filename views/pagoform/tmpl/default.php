<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$session = JFactory::getSession();
$tiquete = $session->get('tiquete');
if($this->respuesta==null):
	$this->params->get("param_name");
//WEBSERVICE
	
$app 			= JFactory::getApplication()->input;
$component 		= $app->get('option');
$params 		= JComponentHelper::getParams($component);

$soap_client = new soapclient($params->get('pagos_url_tc'),array('trace'=> true));
$header_part = '
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <wsse:UsernameToken>
            <wsse:Username>' . $params->get('pagos_usuario') . '</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
        </wsse:UsernameToken>
    </wsse:Security>
';
$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
$soap_header = new SoapHeader( $params->get('pagos_url_tc'), 'Security', $soap_var_header );
$soap_client->__setSoapHeaders($soap_header);
$resulta = $soap_client->obtenerFranquicias();

$lang = JFactory::getLanguage();
$lang->load('com_flota', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_flota/assets/js/form.js');

?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-tiquete').submit(function (event) {
				
			});

			
			jQuery('input:hidden.municipio').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('municipiohidden')){
					jQuery('#jform_municipio option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_municipio").trigger("liszt:updated");
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-tiquete').submit(function (event) {
				
			});

			
			jQuery('input:hidden.municipio').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('municipiohidden')){
					jQuery('#jform_municipio option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_municipio").trigger("liszt:updated");
		});
	}

	function checkUp() {
	    document.getElementById("btnpago").disabled = true;
	    return true;
	}
</script>
<div class="header-pagos"></div>
	<form id="form-tiquete" name="form-tiquete" autocomplete="off"  onsubmit="return checkUp();" action="<?php echo JRoute::_('index.php?option=com_flota'); ?>" method="post" class="form-horizontal">
		<input autocomplete="false" name="hidden" type="text" style="display:none;">
<div class="contenido-pagos">
	<div class="pagos-left">
		<div class="titulo-pagos">Ingresa los datos de la tarjeta</div>
		<div class="informacion-titular">
			<div class="header-pagos">INFORMACION DEL TITULAR DE LA TARJETA DE CRÉDITO</div>
			<input type="text" name="nombre_titular"  value="<?php echo (isset($tiquete['nombre_titular'])) ? $tiquete['nombre_titular'] : null;?>" title="Nombre del Titular" required placeholder="Nombre del Titular"  />
			<input type="text" name="apellidos_titular" value="<?php echo (isset($tiquete['apellidos_titular'])) ? $tiquete['apellidos_titular'] : null;?>" title="Apellidos del Titular" required placeholder="Apellidos"  />
			<input type="text" name="cedula_titular" value="<?php echo (isset($tiquete['cedula_titular'])) ? $tiquete['cedula_titular'] : null;?>" title="Número de Identificación del Titular" required placeholder="Cédula" />
			<input type="email" name="email_titular" value="<?php echo (isset($tiquete['email_titular'])) ? $tiquete['email_titular'] : null;?>" title="Email del Titular" required placeholder="Email" />								
			<input type="tel" name="telefono_titular" value="<?php echo (isset($tiquete['telefono_titular'])) ? $tiquete['telefono_titular'] : null;?>" title="Número de Teléfono" required placeholder="Teléfono" />
			<input type="text" name="direccion_titular"  value="<?php echo (isset($tiquete['direccion_titular'])) ? $tiquete['direccion_titular'] : null;?>" title="Dirección de Residencia" required placeholder="Dirección" />
			<input type="text" name="departamento_titular" value="<?php echo (isset($tiquete['departamento_titular'])) ? $tiquete['departamento_titular'] : null;?>" title="Departamento" required placeholder="Departamento" />
			<input type="text" name="ciudad_titular"  value="<?php echo (isset($tiquete['ciudad_titular'])) ? $tiquete['ciudad_titular'] : null;?>" title="Ciudad de residencia del Titular" required placeholder="Ciudad" />
		</div>
		<div class="informacion-tarjeta">
			<div>
				<h4><b><input type="radio" class="mpago" name="mpago" value="efectivo" required>Efectivo</b><br></h4>
				<h4><b><input type="radio" class="mpago" name="mpago" value="otro" requireds> Tarjeta de credito o PSE</b><br></h4>
			</div>
			<div class="metodo-pago">
				<img src="https://static.placetopay.com/redirect/images/providers/placetopay.svg" width="150" height="75">
				<img src="/images/logo-flota-magdalena.png" width="150" height="75">
			</div>
			<div>
				<a href="FAQ.pdf" target="_blank">
					<h5>Preguntas Frecuentes</h5>
				</a>
			</div>
		</div>
	</div>
	<div class="pagos-right">
		<div class="descripcion-pago-canasta">
			<h3>Descripción del Pago</h3>
			<p><strong>Viaje Ida: </strong><?php echo $this->ObRutaIda->origen;?> - <?php echo $this->ObRutaIda->destino;?></p>
			<p><strong>No de pasajeros: </strong> <?php echo $session->get('tiquete')['pasajeros'];?> - <strong>Sillas: </strong> <?php echo $session->get('tiquete')['sillas_ida'];?></p>
			<p><strong>Fecha: </strong> <?php echo $session->get('tiquete')['fecha_salida'];?></p>
			<p><strong>Hora: </strong> <?php echo $this->ObRutaIda->hora;?></p>
			<p>&nbsp;</p>
					
					<?php if($session->get('tiquete')['ruta_regreso']):?>
						<p><strong>Viaje 2:</strong> <?php echo $this->ObRutaReg->origen;?> - <?php echo $this->ObRutaReg->destino;?></p>
						<p><strong>No de pasajeros:</strong><?php echo $session->get('tiquete')['pasajeros'];?> - <strong>Sillas:</strong> <?php echo $session->get('tiquete')['sillas_regreso'];?></p>
						<p><strong>Fecha:</strong><?php echo $session->get('tiquete')['fecha_regreso'];?></p>
						<p><strong>Hora:</strong><?php echo $this->ObRutaReg->hora;?></p>
						<p>&nbsp;</p>
					<?php endif;?>
		</div>
		<div class="tiquete-canasta">
			<p align="center"><img src="images/mis-tiquetes.png" title="Tiquetes"></p>
			<p>Total a pagar</p>
			<p class="costo-canasta">$<?php echo number_format($session->get('tiquete')['total'],0,'','.');?></p>
		</div>
	</div>
</div>
<div class="contenido-pagos">
	<div class="pagos-left">
		<div class="buttons-pago">
			<a href="index.php?option=com_flota&view=tiqueteform">RETROCEDER</a>
			<input type="submit" class="button-pago" id="btnpago" name="submit" value="REALIZAR PAGO" />
		</div>
	</div>
</div>
		<!-- PNG IMAGE -->
<p style="background:url(https://h.online-metrix.net/fp/clear.png?org_id=k8vif92e&amp;session_id=cybersource_iatai<?php echo $session->get("idsesion"); ?>&amp;m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?org_id=k8vif92e&amp;session_id=cybersource_iatai<?php echo $session->get("idsesion"); ?>&amp;m=2" alt="" >
<!-- FLASH CODE -->
<object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?org_id=k8vif92e&amp;session_id=cybersource_iatai<?php echo $session->get("idsesion"); ?>" width="1" height="1" id="thm_fp">
<param name="movie" value="https://h.online-metrix.net/fp/fp.swf?org_id=k8vif92e&amp;session_id=cybersource_iatai<?php echo $session->get("idsesion"); ?>" />
<div></div>
</object> <!-- JAVASCRIPT -->
<script src="https://h.online-metrix.net/fp/check.js?org_id=k8vif92e&amp;session_id=cybersource_iatai<?php echo $session->get("idsesion"); ?>" type="text/javascript"></script>
		<input type="hidden" name="tipo_cuenta" value="C" /> 
		<input type="hidden" name="option" value="com_flota" />
		<input type="hidden" name="task" value="pagoform.save" />
		<?php echo JHtml::_('form.token'); ?>
	</form>


<?php else:?>
	<p style="text-align:justify;">Para mayor información o ayuda con tu compra por favor envíanos un correo a flotamagdalena.pagosonline@gmail.com  o comunícate a la línea 4287650 o al  310 2199353  de lunes a viernes de 7:00 a 6:00pm o los sábados de 8:30 a 1:00pm.</p><p>&nbsp;</p>
				<p style="text-align:justify;">Apreciado Cliente: El pasaje se hará efectivo según el día , hora y ruta señalados. En ningún caso habrá devoluciones de dinero. El pasajero puede transportar una valija con un peso máximo de 25 kg sin pagar costo adicional, si sobrepasa este peso debe cancelar un valor adicional y hacer el proceso de aforo. </p><p>&nbsp;</p>
				<p style="text-align:justify;">  A su correo registrado en la pagina web, le sera enviado un pdf con el respectivo tiquete generado por Flota Magdalena S.A , que confirmara su compra y con el que podrá realizar el abordaje. Cualquier novedad que presente;  puede contactarse a la linea 310-2199353 o al correo flotamagdalena.pagosonline@gmail.com</p>
				<p><strong>EL ENVIO DE SU TIQUETE DE ABORDAJE NO ES AUTOMATICO A LA COMPRA , ESTE SERA ENVIADO A SU DIRECCION DE CORREO REGISTRADA EN LA PAGINA WEB, A MAS TARDAR EL DIA ANTERIOR DE SU FECHA DE VIAJE .</strong></p><p>&nbsp;</p>
<div class="pagina-respuesta">
	<div class="pagina-respuesta-left">
		<div class="pagina-respuesta-left-header">Datos de la transacción</div>
		<div class="pagina-respuesta-left-content">
			<span class="row"><strong>Pago realizado a: </strong>Flota Magdalena</span>
			<span class="row"><strong>Total: </strong>$<?php  echo $this->respuesta->respuestaTransaccion->valor;?></span>
			<span class="row">
				<strong>Fecha de la transacción: </strong><?php  echo $this->respuesta->respuestaTransaccion->fechaProcesamiento;?>
			</span>
			<span class="row"><strong>Estado: </strong><?php  echo $this->respuesta->respuestaTransaccion->nombreEstado;?></span>
			<span class="row">
				<strong>Tipo de Tarjeta: </strong><?php  echo $this->respuesta->respuestaTransaccion->tarjetaRespuesta->nombreFranquicia;?>
			</span>
			<span class="row"><strong>Referencia: </strong><?php  echo $this->respuesta->respuestaTransaccion->referencia;?></span>
			<span class="row">
				<strong>ID Transacción: </strong><?php  echo $this->respuesta->respuestaTransaccion->idTransaccion;?>
			</span>
			<span class="row-sub"><strong>Detalle de la compra</strong></span>
			<span class="row"><strong>Viaje Ida</strong></span>
			<span class="row"><strong><?php echo $this->ObRutaIda->origen.' - '.$this->ObRutaIda->destino;?></strong></span>
			<span class="row"><strong>No de Pasajeros: </strong><?php  echo $tiquete['pasajeros'];?></span>
			<span class="row"><strong>Sillas: </strong><?php  echo $tiquete['sillas_ida'];?></span>
			<span class="row"><strong>Fecha: </strong><?php  echo $tiquete['fecha_salida'];?></span>
			<span class="row"><strong>Hora: </strong><?php  echo $this->ObRutaIda->hora;?></span>
			<?php if($tiquete['ruta_regreso']):?>
				<hr>
				<span class="row"><strong>Viaje Regreso</strong></span>
				<span class="row"><strong><?php echo $this->ObRutaReg->origen.' - '.$this->ObRutaReg->destino;?></strong>
				</span>
				<span class="row"><strong>No de Pasajeros: </strong><?php  echo $tiquete['pasajeros'];?></span>
				<span class="row"><strong>Sillas: </strong><?php  echo $tiquete['sillas_regreso'];?></span>
				<span class="row"><strong>Fecha: </strong><?php  echo $tiquete['fecha_regreso'];?></span>
				<span class="row"><strong>Hora: </strong><?php  echo $this->ObRutaReg->hora;?></span>
			<?php endif;?>

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
	<div class="clearfix"></div><br><br>
	<a href="index.php" align="center"  class="button-submit">TERMINAR</a>
</div>
<table class="datos-transaccion" width="100%" style="display:none;">
	<tr>
		<td><strong>Pago realizado a</strong></td>
		<td>Flota Magdalena</td>
	</tr>
	<tr>
		<td><strong>ID Transacción</strong></td>
		<td><?php  echo $this->respuesta->respuestaTransaccion->idTransaccion;?></td>
	</tr>
	<tr>
		<td><strong>Referencia</strong></td>
		<td><?php  echo $this->respuesta->respuestaTransaccion->referencia;?></td>
	</tr>
	<tr>
		<td><strong>Fecha de la Transacción</strong></td>
		<td><?php  echo $this->respuesta->respuestaTransaccion->fechaProcesamiento;?></td>
	</tr>
	<tr>
		<td><strong>Tipo de Tarjeta</strong></td>
		<td><?php  echo $this->respuesta->respuestaTransaccion->tarjetaRespuesta->nombreFranquicia;?></td>
	</tr>
	<tr>
		<td><strong>Estado</strong></td>
		<td><?php  echo $this->respuesta->respuestaTransaccion->nombreEstado;?></td>
	</tr>
	<tr>
		<td><strong>Total</strong></td>
		<td>$<?php  echo $this->respuesta->respuestaTransaccion->valor;?></td>
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
</table>
<?php endif;?>