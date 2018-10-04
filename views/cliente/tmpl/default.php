<?php defined('_JEXEC') or die;
	$user = JFactory::getUser();
	if (!$user->guest) {
?>
<?php if ($this->item): ?>
	<div class="cliente-top"><a class="btn" href="<?php echo JRoute::_('index.php?option=com_flota&task=cliente.edit&id='.$this->item->id); ?>">Editar <img src="images/bg-editar.png"></a></div>
	<div class="cliente-info">
		<ul>
			<li class="nombre"><?php echo $this->item->nombre; ?></li>
			<li class="nombre"><?php echo $this->item->apellidos; ?></li>
			<li class="documento"><?php echo $this->item->documento; ?></li>
			<li class="fecha"><?php echo $this->item->fecha_nacimiento; ?></li>
			<li class="email"><?php echo $user->email; ?></li>
			<li class="telefono"><?php echo $this->item->telefono; ?></li>
			<li class="celular"><?php echo $this->item->celular; ?></li>
			<li class="direccion"><?php echo $this->item->direccion; ?></li>
			<li class="departamento"><?php echo $this->item->municipio; ?></li>

		</ul>
	</div>

    <?php
else:
    echo JText::_('ERROR: Datos no encontrados');
endif;
}
?>
