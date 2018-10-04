<?php defined('_JEXEC') or die; 
	$dias = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
	$meses = array("01","02","03","04","05","06","07","08","09","10","11","12");
	$session = JFactory::getSession();
?>
<div class="col-xs-12 col-md-7">
	<div class="capa-login">
		<h2>¿Ya tienes cuenta?</h2>
		<div class="capa-form-login">
			<div class="header-login">Inicia Sesión</div>
			<div class="content-login">
				<form name="login" id="login" action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">
					<input required type="text" name="username" id="user" class="user-login" placeholder="E-mail" required />
					<input required type="password" name="password" id="password" class="user-pass" placeholder="Contraseña" required />
					<input type="submit" name="submit" value="CONTINUAR" class="user-button" />
					<input type="hidden" name="return" value="<?php echo base64_encode("index.php?option=com_flota&view=tiqueteform"); ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
			</div>
		</div>
	</div>
	<div class="capa-registro">
		<form action="<?php echo JRoute::_('index.php?option=com_flota&task=clienteform.save'); ?>" method="post">
		<div class="content-registro">
			<h2>¿Aún no has creado tu cuenta?</h2>
			<span class="sub-registro">Ingresa tus datos a continuación</span>
			<div class="campos-registro">
				<div class="campos-group">
					<select name="jform[tipo_documento]" id="tipo_documento" class="tipo_documento" required>
						<option value="">Tipo de documento</option>
						<option value="1" <?php if($this->item['tipo_documento']==1) echo "selected";?>>Cédula de Ciudadanía</option>
						<option value="2" <?php if($this->item['tipo_documento']==2) echo "selected";?>>Pasaporte</option>
					</select>
					<input type="text" name="jform[documento]" id="numero_documento" class="documento" required="" placeholder="No de Documento" value="<?php echo $this->item['documento']; ?>" />
				</div>
				<input type="text" name="jform[nombre]" id="nombre" class="nombre" required placeholder="Nombres" value="<?php echo $this->item['nombre']; ?>" />
				<input type="text" name="jform[apellidos]" id="apellidos" class="nombre" placeholder="Apellidos" value="<?php echo $this->item['apellidos']; ?>" required />
				<div class="campos-group">
					<label>Fecha de Nacimiento</label>
					<select name="jform[dia]" id="dia" class="dia" required>
						<option value="">Dia</option>
						<?php foreach($dias as $dia):
								$selected = ($this->item['dia']==$dia) ? "selected" : null;
						?>
						<option value="<?php echo $dia;?>" <?php echo $selected;?>><?php echo $dia;?></option>
						<?php endforeach;?>
					</select>
					<select name="jform[mes]" id="mes" class="mes" required>
						<option value="">Mes</option>
						<?php foreach($meses as $mes):
								$selected = ($this->item['mes']==$mes) ? "selected" : null;
						?>
						<option value="<?php echo $mes;?>" <?php echo $selected;?>><?php echo $mes;?></option>
						<?php endforeach;?>
					</select>
					<select name="jform[anio]" id="anio" class="anio" required>
						<option value="">Año</option>
						<?php 
							$i = date('Y')-18;
							$limit = date('Y')-90;
							for($i; $i>=$limit; $i--){
								$selected = ($this->item['anio']==$i) ? "selected" : null;
						?>
						<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?></option>
						<?php }?>
					</select>
				</div>
				<div class="campos-group">
					<input type="tel" name="jform[telefono]" id="telefono" class="telefono" required placeholder="Teléfono" value="<?php echo $this->item['telefono']; ?>" />
					<input type="tel" name="jform[celular]" id="celular" class="celular"  placeholder="Celular" value="<?php echo $this->item['celular']; ?>" />
				</div>
				<input type="text" name="jform[direccion]" id="direccion" class="ubicacion" placeholder="Dirección" value="<?php echo $this->item['direccion']; ?>" />
				<input type="text" name="jform[municipio]" id="municipio" required class="ubicacion" placeholder="Municipio" value="<?php echo $this->item['municipio']; ?>" />
				<input type="email" name="jform[email]" id="email" class="email" required placeholder="Email" value="<?php echo $this->item['email']; ?>" />
				<input type="password" name="jform[password]" id="password" class="password" placeholder="Clave" required />
				<input type="password" name="jform[password2]" id="password2" class="password" placeholder="Repetir Clave" required />
				<div class="campos-group">
					<input type="checkbox" name="jform[boletin]" id="boletin" value="1" class="boletin" />
					<span class="boletin">Deseo recibir información sobre servicios y promociones</span>
				</div>
				<div class="campos-group">
					<input type="submit" name="submit" value="CREAR CUENTA Y CONTINUAR" class="button-submit-registro" />
				</div>
			</div>
		</div>
		<input type="hidden" name="jform[return]" value="1" />
		<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>
<div class="col-xs-12 col-md-5">
	<div class="aviso-viajero">
		<p><img src="images/logo-viajero-preferencial.png"><p>
		<p>Después de crear tu cuenta por cada trayecto que viajas, acumulas puntos que luego podrás redimir por pasajes.</p>
		<p>Los puntos tienen vigencia de un año.</p>
	</div>
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
