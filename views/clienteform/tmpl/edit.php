<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_flota', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_flota/assets/js/form.js');

$dias       = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
$meses      = array("01","02","03","04","05","06","07","08","09","10","11","12");
$fecha_naci = explode('-',$this->item['fecha_nacimiento']);    
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-cliente').submit(function (event) {
				
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
			jQuery('#form-cliente').submit(function (event) {
				
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
</script>

<div class="cliente-edit front-end-edit">

	
	<div class="row">
		<div class="col-xs-12 col-sm-7">
			<div class="capa-registro">
				<form id="form-cliente" action="<?php echo JRoute::_('index.php?option=com_flota&task=cliente.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
				<div class="content-registro">
					<h2>Actualización de Datos</h2>
					<span class="sub-registro">Modifica tu información</span>
					<div class="campos-registro">
						<div class="campos-group">
							<select name="jform[tipo_documento]" id="tipo_documento" class="tipo_documento" required>
								<option value="">Tipo de documento</option>
								<option value="1" <?php if($this->item['tipo_documento']==1) echo "selected";?>>Cédula de Ciudadanía</option>
								<option value="2" <?php if($this->item['tipo_documento']==2) echo "selected";?>>Pasaporte</option>
							</select>
							<input type="text" name="jform[documento]" id="numero_documento" class="documento" required="" title="Número de Documento" placeholder="No de Documento" value="<?php echo $this->item['documento']; ?>" />
						</div>
						<input type="text" name="jform[nombre]" id="nombre" class="nombre" required placeholder="Nombres" value="<?php echo $this->item['nombre']; ?>" />
						<input type="text" name="jform[apellidos]" id="apellidos" class="nombre" placeholder="Apellidos" value="<?php echo $this->item['apellidos']; ?>" required />
						<div class="campos-group">
							<label>Fecha de Nacimiento</label>
							<select name="jform[dia]" id="dia" class="dia" required >
								<option value="">Dia</option>
								<?php 
									foreach($dias as $dia):
										$selected = ($fecha_naci[2]==$dia) ? "selected" : null;
								?>
								<option value="<?php echo $dia;?>" <?php echo $selected;?>><?php echo $dia;?></option>
								<?php endforeach;?>
							</select>
							<select name="jform[mes]" id="mes" class="mes" required >
								<option value="">Mes</option>
								<?php 
									foreach($meses as $mes):
										$selected = ($fecha_naci[1]==$mes) ? "selected" : null;
								?>
								<option value="<?php echo $mes;?>" <?php echo $selected;?>><?php echo $mes;?></option>
								<?php endforeach;?>
							</select>
							<select name="jform[anio]" id="anio" class="anio" required >
								<option value="">Año</option>
								<?php 
									$i = date('Y')-18;
									$limit = date('Y')-90;
									for($i; $i>=$limit; $i--){
										$selected = ($fecha_naci[0]==$i) ? "selected" : null;
								?>
								<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
								<?php }?>
							</select>
						</div>
						<div class="campos-group">
							<input type="tel" name="jform[telefono]" id="telefono" class="telefono" placeholder="Teléfono" value="<?php echo $this->item['telefono']; ?>" required />
							<input type="tel" name="jform[celular]" id="celular" class="celular" placeholder="Celular" value="<?php echo $this->item['celular']; ?>" required />
						</div>
						<input type="text" name="jform[direccion]" id="direccion" class="ubicacion" placeholder="Dirección" value="<?php echo $this->item['direccion']; ?>" />
						<input type="text" name="jform[municipio]" id="municipio" class="ubicacion" placeholder="Municipio" value="<?php echo $this->item['municipio']; ?>" required  />
						<input type="email" name="jform[email]" id="email" class="email" placeholder="Email" value="<?php echo $this->item['email']; ?>" required  />
						<div class="campos-group">
							<input type="checkbox" name="jform[boletin]" id="boletin" value="1" class="boletin" <?php echo ($this->item['boletin']==1) ? 'checked="checked"' : null?> />
							<span class="boletin">Deseo recibir información sobre servicios y promociones</span>
						</div>
						<div class="campos-group">
							<input type="submit" name="submit" value="MODIFICAR" class="button-submit-registro" />
						</div>
					</div>
				</div>
				<input type="hidden" name="jform[return]" value="2" />
				<input type="hidden" name="jform[id]" value="<?php echo $this->item['id']; ?>" />
			        <div class="clearfix"></div>
					<input type="hidden" name="option" value="com_flota" />
					<input type="hidden" name="task" value="clienteform.save" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
			</div>
		</div>
		<div class="col-xs-12 col-sm-5">
			<div class="beneficios-registro">
				<h4>BENEFICIOS QUE OBTIENES AL CREAR TU CUENTA</h4>
				<p>
					<ul>
						<li>Quedas inscrito en el Plan Viajero Preferencial y puedes consultar tus puntos acumulados.</li>
						<li>Ahorras tiempo realizando compras en línea con tarjeta de crédito o débito.</li>
						<li>Tienes un historial de tus viajes realizados.</li>
						<li>Participas de todas nuestras promociones.</li>
					</ul>
				</p>
			</div>
		</div>
	</div>
</div>
