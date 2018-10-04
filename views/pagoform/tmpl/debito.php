<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$session = JFactory::getSession();
$tiquete = $session->get('tiquete');
try {
	//WEBSERVICE
	$app 			= JFactory::getApplication()->input;
	$component 		= $app->get('option');
	$params 		= JComponentHelper::getParams($component);

	$soap_client = new soapclient($params->get('pagos_url_td'),array('trace'=> true));
	$header_part = '
			    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
			        <wsse:UsernameToken>
			            <wsse:Username>' . $params->get('pagos_usuario') . '</wsse:Username>
			            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
			        </wsse:UsernameToken>
			    </wsse:Security>
			';
	$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
	$soap_header = new SoapHeader( $params->get('pagos_url_td'), 'Security', $soap_var_header );
	$soap_client->__setSoapHeaders($soap_header);
	$resulta = $soap_client->obtenerBancos();
} catch (Exception $e) {
	echo '<p>&nbsp;</p><p>No se pudo crear la transacción, por favor intente mas tarde o comuniquese nuestras líneas de atención al cliente al Teléfono: (1)5674567 </p><p> Celular: +573102199353 o al correo electrónico flotamagdalena.pagosonline@gmail.com</p><p>&nbsp;</p>';
	echo '<p>'.$e->getMessage().'</p><p>&nbsp;</p>';
	echo '<div class="buttons-pago"><a href="index.php?option=com_flota&view=pagoform" class="button">Intentar de Nuevo</a></div>';
	return;
}


$lang = JFactory::getLanguage();
$lang->load('com_flota', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_flota/assets/js/form.js');

?>
<script type="text/javascript">
	function checkUp() {
	    document.getElementById("btnpago").disabled = true;
	    return true;
	}
</script>
<div class="header-pagos"></div>
	<form id="form-tiquete" name="form-tiquete" onsubmit="return checkUp();" action="<?php echo JRoute::_('index.php?option=com_flota'); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
<div class="contenido-pagos">
	<div class="pagos-left">
		<div class="titulo-pagos">Datos del titular de la cuenta</div>
		<div class="informacion-titular">
			<select name="tipo_persona" id="tipo_persona" required="required">
				<option value="">Seleccione el Tipo de Persona</option>
				<option value="Natural">Natural</option>
				<option value="Juridica">Juridica</option>
			</select>
			<select name="banco" id="banco" required="required">
				<option value="">Seleccione un Banco</option>
				<?php foreach($resulta->respuestaObtenerBancos as $fra):?>
				<option value="<?php echo $fra->idBanco;?>"><?php echo $fra->nombreBanco?></option>
			<?php endforeach;?>
			</select>
			<input type="text" name="nombre_titular"  value="<?php echo (isset($tiquete['nombre_titular'])) ? $tiquete['nombre_titular'] : null;?>" title="Nombre del Titular" required placeholder="Nombre del Titular"  />
			<input type="text" name="apellidos_titular" value="<?php echo (isset($tiquete['apellidos_titular'])) ? $tiquete['apellidos_titular'] : null;?>" title="Apellidos del Titular" required placeholder="Apellidos"  />
			<select name="tipo_documento" id="tipo_documento">
				<option value="CC">Cédula de Ciudadania</option>
				<option value="PP">Pasaporte</option>
			</select>
			<input type="text" name="cedula_titular" value="<?php echo (isset($tiquete['cedula_titular'])) ? $tiquete['cedula_titular'] : null;?>" title="Número de Identificación del Titular" required placeholder="No de documento" />
			<input type="email" name="email_titular" value="<?php echo (isset($tiquete['email_titular'])) ? $tiquete['email_titular'] : null;?>" title="Email del Titular" required placeholder="Email" />								
			<input type="tel" name="telefono_titular" value="<?php echo (isset($tiquete['telefono_titular'])) ? $tiquete['telefono_titular'] : null;?>" title="Número de Teléfono" required placeholder="Teléfono" />
			<input type="text" name="direccion_titular"  value="<?php echo (isset($tiquete['direccion_titular'])) ? $tiquete['direccion_titular'] : null;?>" title="Dirección de Residencia" required placeholder="Dirección" />
			<input type="text" name="departamento_titular" value="<?php echo (isset($tiquete['departamento_titular'])) ? $tiquete['departamento_titular'] : null;?>" title="Departamento" required placeholder="Departamento" />
			<input type="text" name="ciudad_titular"  value="<?php echo (isset($tiquete['ciudad_titular'])) ? $tiquete['ciudad_titular'] : null;?>" title="Ciudad de residencia del Titular" required placeholder="Ciudad" />
		</div>
	</div>
	<div class="pagos-right">
		<div class="descripcion-pago-canasta">
			<h3>Descripción del Pago</h3>
			<p><strong>Viaje Ida: </strong><?php echo $this->ObRutaIda->origen;?> - <?php echo $this->ObRutaIda->destino;?></p>
					
					<?php if($session->get('tiquete')['ruta_regreso']):?>
						<p><strong>Viaje Regreso:</strong> <?php echo $this->ObRutaReg->origen;?> - <?php echo $this->ObRutaReg->destino;?></p>
						<p>&nbsp;</p>
					<?php endif;?>
		</div>
		<div class="tiquete-canasta">
			<p align="center"><img src="images/mis-tiquetes.png" title="Tiquetes"></p>
			<p>Total a pagar</p>
			<p class="costo-canasta">$<?php echo number_format($session->get('tiquete')['total'],0,'','.');?></p>
		</div>
		<div class="logo-pse">
			<img src="images/logo-pse.png">
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
<div class="modal fade" id="confirm-pse" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      	<img src="images/gears.gif" width="50" align="center"><p></p>
      		<p>Este proceso puede tardar varios segundos</p>
          <p align="center"><strong>Por favor espere</strong>...</p>                     
      </div>    
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
		<input type="hidden" name="option" value="com_flota" />
		<input type="hidden" name="task" value="pagoform.save" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
	<script type="text/javascript">
	jQuery("form").on('submit', function(){
   		jQuery('#confirm-pse').modal('show');
	})
</script>