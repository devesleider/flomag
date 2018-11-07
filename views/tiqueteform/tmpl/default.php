<?php defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$session = JFactory::getSession();
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_flota', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_flota/assets/js/form.js');

$precio_ida = $this->displayPrice($this->ObRutaIda,$session->get('tiquete')['fecha_salida'],2);#($this->ObRutaIda->precio_especial > 0) ? $this->ObRutaIda->precio_especial : $this->ObRutaIda->precio;
if($this->ObRutaReg)
	$precio_reg = $this->displayPrice($this->ObRutaReg,$session->get('tiquete')['fecha_regreso'],2);#($this->ObRutaReg->precio_especial > 0) ? $this->ObRutaReg->precio_especial : $this->ObRutaReg->precio;
else
	$precio_reg = 0;
$subtotal  = $precio_ida*$session->get('tiquete')['pasajeros'];
$subtotal  = ($this->ObRutaReg) ? (($precio_reg*$session->get('tiquete')['pasajeros']) + $subtotal) : $subtotal;
$codigo    = (isset($session->get('tiquete')['cupon'])) ? $session->get('tiquete')['cupon'] : null;
$total     = ($session->get('tiquete')['subtotal']>$session->get('tiquete')['total']) ? $session->get('tiquete')['total'] : 0;
#var_dump($session->get('tiquete'));
#var_dump($this->ObRutaReg);
?>

<div class="header-pagos"></div>
<div class="content-tiquete">
	<div class="left_tiquete">
		<div class="text-verifica">Verifica la información de tu compra</div>
		<div class="tiquete-ida">
			<div class="top-tiquete">
				<span class="subtitulo">viaje de ida</span>
				<span class="borde"></span>
			</div>
			<div class="contenido-datos-tiquete">
				<div class="contenido-left">
					<span class="ruta"></span>
					<span class="fechas">
						<strong>Fecha: </strong><?php echo $session->get('tiquete')['fecha_salida'];?>
						&nbsp;&nbsp;
						<strong>Hora: </strong><?php echo $this->ObRutaIda->hora;?>
					</span>
					<span class="ubicacion">
						<strong>No de Pasajeros:</strong> 
						<?php echo $session->get('tiquete')['pasajeros'];?>
						<strong>Silla: </strong> <?php echo $session->get('tiquete')['sillas_ida'];?>
					</span>
				</div>
				<div class="contenido-right">
					$<?php echo number_format($precio_ida*$session->get('tiquete')['pasajeros'],0,'','.');?>
				</div>
			</div>
		</div>
		<?php if($session->get('tiquete')['ruta_regreso']):?>
		<div class="tiquete-regreso">
			<div class="top-tiquete">
				<span class="subtitulo">viaje de regreso</span>
				<span class="borde"></span>
			</div>
			<div class="contenido-datos-tiquete">
				<div class="contenido-left">
					<span class="ruta"><?php ?></span>
					<span class="fechas">
						<strong>Fecha: </strong><?php echo $session->get('tiquete')['fecha_regreso'];?>
						<strong>Hora: </strong><?php echo $this->ObRutaReg->hora;?>
					</span>
					<span class="ubicacion">
						<strong>No de Pasajeros:</strong>
						<?php echo $session->get('tiquete')['pasajeros'];?>
						<strong>Silla: </strong> <?php echo $session->get('tiquete')['sillas_regreso'];?>
					</span>
				</div>
				<div class="contenido-right">
					$<?php echo number_format($precio_reg*$session->get('tiquete')['pasajeros'],0,'','.');?>
				</div>
			</div>
		</div>
		<?php endif;?>
		<div class="tiquete-ida">
			<div class="top-tiquete">
				<span class="subtitulo">Cupón de Descuento</span>
				<span class="borde"></span>
				<form action="<?php echo JRoute::_('index.php?option=com_flota'); ?>" method="post" name="form-cupon" id="form-cupon" >
					<span class="input-cupon"><input type="text" name="cupon" id="cupon" placeholder="Ingresa el Código" value="<?php echo $codigo;?>"></span>
					<span class="button-cupon"><input type="submit" name="submit" value="Validar Cupón"></span>
					<input type="hidden" name="option" value="com_flota" />
					<input type="hidden" name="task" value="pagoform.cupon" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
			</div>
		</div>
	</div>
	<form action="<?php echo JRoute::_('index.php?option=com_flota&view=pagoform'); ?>" method="post" name="form-confirmar" id="form-confirmar" >
	<div class="right_tiquete">
		<div class="tiquete-canasta">
			<p align="center"><img src="images/mis-tiquetes.png" title="Tiquetes"></p>
			<p>Total de tu compra</p>
			<p class="costo-canasta <?php echo ($total>0) ? 'cupon-promocion' : ''?>">$<?php echo number_format($subtotal,0,'','.');?></p>
			<?php if($total>0):?>
				<p class="costo-porcentaje">Descuento: -$<?php echo number_format($session->get('tiquete')['descuento']);?></p>
				<p class="costo-subtitulo">Total a Pagar</p>
				<p class="costo-canasta-promo">$<?php echo number_format($total,0,'','.');?></p>
			<?php endif;?>
		</div>
		<div class="terminos">
			<p>
				<input type="checkbox" name="terminos" id="terminos" required value="1" />
				Acepto lo siguiente
			</p>
			<p><a href="#">Términos de servicio</a></p>
		</div>
	</div>
</div>
<div class="content-tiquete">
	<div class="left_tiquete">
		<div class="buttons-pago">
			<a href="index.php?option=com_flota&view=rutas&layout=sillas">RETROCEDER</a>
			<input type="submit" name="submit" class="button-pago" value="CONTINUAR CON EL PAGO" />
		</div>
	</div>
</div>
<form>