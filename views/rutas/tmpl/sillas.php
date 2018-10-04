<?php defined('_JEXEC') or die;
	if(isset($this->tiquete['dispositivo']) && ($this->tiquete['dispositivo']==1)){
		$sillas[21] = array("5","6","2","3");
		$sillas[22] = array("18","17","16","15","14","13");
		$sillas[3] 	= array("20","19","16","15","12","11","8","7","4","3");
		$sillas[4] 	= array("18","17","14","13","10","9","6","5","2","1");
		$sillas[5] 	= array("20","19","16","15","12","11","8","7","4","3");
	}else{
		$sillas[21] = array("2","5","3","6");
		$sillas[22] = array("14","16","18","13","15","17");
		$sillas[3] 	= array("4","8","12","16","20","3","7","11","15","19");
		$sillas[4] 	= array("2","6","10","14","18","1","5","9","13","17");
		$sillas[5] 	= array("4","8","12","16","20","3","7","11","15","19");
	}
	$session 	= JFactory::getSession();
	$sillas_ida = (isset($this->tiquete['sillas_ida'])) ? explode(",", $this->tiquete['sillas_ida']) : array(); 
	$sillas_reg = (isset($this->tiquete['sillas_regreso'])) ? explode(",", $this->tiquete['sillas_regreso']) : array();
?>
<form action="<?php echo JRoute::_('index.php?option=com_flota&view=rutas&layout=login'); ?>" method="post" name="form-sillas" id="form-sillas" >
<div class="resultados-top">
	<div class="buttons-pago">
		<a href="index.php">< Nueva Ruta</a>
	</div>
</div>
<div class="col-xs-12 col-sm-8">
	<div class="subtitulo-viaje-ida">
		<div class="sub">VIAJE DE IDA</div>
		<div class="sub-border"></div>
		<div class="subruta">
			<span class="descripcion-ruta">
					<?php echo $this->ruta_ida->origen.' - '.$this->ruta_ida->destino;?>
				</span>
				<span class="fecha-ruta">
					<?php echo $this->tiquete['fecha_salida'];?>
				</span>
		</div>
	</div>
	<div class="disponibilidad-sillas">
		<div class="imagen-bus">
			<?php if($this->ruta_ida->tipo_servicio==2):?>
			<div class="bus-doble-premium-piso-1">
				<div class="sillas-doble-premium-izquierda">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"I",1) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-izquierda-2">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"I",2) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-derecha">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"D",1) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-derecha-2">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"D",2) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_ida->tipo_servicio==266):?>
			<div class="bus-doble-premium-piso-2">
				<div class="sillas-doble-premium-piso-2">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio."2"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_ida']) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>
			
			<?php if($this->ruta_ida->tipo_servicio==3):?>
			<div class="bus-pacific">
				<div class="sillas-pacific-izquierda">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $sp):
						foreach ($this->getSillas($this->tiquete) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;	
					?>
				</div>
				<div class="sillas-pacific-derecha">
					<?php  
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $sp):
						foreach ($this->getSillas($this->tiquete) as $sp):
							echo $this->displaySilla($sp,$sillas_ida,$this->tiquete);
						endforeach;	
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_ida->tipo_servicio==4):?>
			<div class="bus-premium">
				<div class="sillas-premium-izquierda">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"I",1) as $spf):
							echo $this->displaySilla($spf,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-premium-izquierda-2">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"I",2) as $spf):
							echo $this->displaySilla($spf,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-premium-derecha">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"D",1) as $spf):
							echo $this->displaySilla($spf,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-premium-derecha-2">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_ida'],"D",2) as $spf):
							echo $this->displaySilla($spf,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_ida->tipo_servicio==5):?>
			<div class="bus-plus">
				<div class="sillas-plus-izquierda">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spl):
						foreach ($this->getSillas($this->tiquete) as $spl):
							echo $this->displaySilla($spl,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-plus-derecha">
					<?php 
						//foreach($sillas[$this->ruta_ida->tipo_servicio] as $spl):
						foreach ($this->getSillas($this->tiquete) as $spl):
							echo $this->displaySilla($spl,$sillas_ida,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

		</div>
		<div class="descripcion-sillas">
			<ul>
				<li><img src="images/sillas-disponibles.png">Sillas disponibles</li>
				<li><img src="images/sillas-reservadas.png">Sillas reservadas</li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>


	<?php if($this->tiquete['tipo_viaje']==2):?>
	<div class="subtitulo-viaje-ida">
		<div class="sub">VIAJE DE REGRESO</div>
		<div class="sub-border"></div>
		<div class="subruta">
			<span class="descripcion-ruta"><?php echo $this->ruta_regreso->origen.' - '.$this->ruta_regreso->destino;?></span>
			<span class="fecha-ruta"><?php echo $this->tiquete['fecha_regreso'];?></span>
		</div>
	</div>
	<div class="disponibilidad-sillas">
		<div class="imagen-bus">
			<?php if($this->ruta_regreso->tipo_servicio==2):?>
			<div class="bus-doble-premium-piso-1">
				<div class="sillas-doble-premium-izquierda">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"I",1) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-izquierda-2">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"I",2) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-derecha">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"D",1) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-doble-premium-derecha-2">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio."1"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"D",2) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_regreso->tipo_servicio==992):?>
			<div class="bus-doble-premium-piso-2">
				<div class="sillas-doble-premium-piso-2">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio."2"] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],2) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>
			
			<?php if($this->ruta_regreso->tipo_servicio==3):?>
			<div class="bus-pacific">
				<div class="sillas-pacific-izquierda">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso']) as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-pacific-derecha">
					<?php  
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $sp):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"D") as $sp):
							echo $this->displaySillaReg($sp,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_regreso->tipo_servicio==4):?>
			<div class="bus-premium">
				<div class="sillas-premium-izquierda">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"I",1) as $spf):
							echo $this->displaySillaReg($spf,$sillas_reg,$this->tiquete);
						endforeach; 
					?>
				</div>
				<div class="sillas-premium-izquierda-2">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"I",2) as $spf):
							echo $this->displaySillaReg($spf,$sillas_reg,$this->tiquete);
						endforeach; 
					?>
				</div>
				<div class="sillas-premium-derecha">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'], "D",1) as $spf):
							echo $this->displaySillaReg($spf,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-premium-derecha-2">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spf):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'], "D",2) as $spf):
							echo $this->displaySillaReg($spf,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

			<?php if($this->ruta_regreso->tipo_servicio==5):?>
			<div class="bus-plus">
				<div class="sillas-plus-izquierda">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spl):
						foreach ($this->getSillas($this->tiquete['ruta_regreso']) as $spl):
							echo $this->displaySillaReg($spl,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
				<div class="sillas-plus-derecha">
					<?php 
						//foreach($sillas[$this->ruta_regreso->tipo_servicio] as $spl):
						foreach ($this->getSillas($this->tiquete['ruta_regreso'],"D") as $spl):
							echo $this->displaySillaReg($spl,$sillas_reg,$this->tiquete);
						endforeach;
					?>
				</div>
			</div>
			<?php endif;?>

		</div>
		<div class="descripcion-sillas">
			<ul>
				<li><img src="images/sillas-disponibles.png">Sillas disponibles</li>
				<li><img src="images/sillas-reservadas.png">Sillas reservadas</li>
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
	<?php endif;?>
</div>
<div class="col-xs-12 col-sm-4">
	<div class="sillas-informacion">
		<h3>Información de tu viaje</h3>
		<div class="sillas-informacion-content">
			<span class="silla-info-subtitle">Viaje de Ida</span>
			<span class="silla-info-row">
				<span class="silla-info-label">Origen:</span>
				<span class="silla-info-value"><?php echo $this->ruta_ida->origen;?></span>
			</span>
			<span class="silla-info-row">
				<span class="silla-info-label">Destino:</span>
				<span class="silla-info-value"><?php echo $this->ruta_ida->destino;?></span>
			</span>
			<span class="silla-info-row">
				<span class="silla-info-label">Horario:</span>
				<span class="silla-info-value"><?php echo date("g:i a",strtotime($this->ruta_ida->hora));?></span>
			</span>
			<span class="silla-info-row">
				<span class="silla-info-label">Servicio:</span>
				<span class="silla-info-value"><?php echo $this->ruta_ida->servicio;?></span>
			</span>
			<span class="silla-info-row">
				<span class="silla-info-label">Valor:</span>
				<span class="silla-info-value">$<?php echo number_format($this->tiquete['total_ida'],0,",",".") ?></span>
			</span>

			<?php if($this->tiquete['tipo_viaje']==2):?>
				<span class="silla-info-subtitle">Viaje de Regreso</span>
				<span class="silla-info-row">
					<span class="silla-info-label">Origen:</span>
					<span class="silla-info-value"><?php echo $this->ruta_regreso->origen;?></span>
				</span>
				<span class="silla-info-row">
					<span class="silla-info-label">Destino:</span>
					<span class="silla-info-value"><?php echo $this->ruta_regreso->destino;?></span>
				</span>
				<span class="silla-info-row">
					<span class="silla-info-label">Horario:</span>
					<span class="silla-info-value"><?php echo date("g:i a",strtotime($this->ruta_regreso->hora));?></span>
				</span>
				<span class="silla-info-row">
					<span class="silla-info-label">Servicio:</span>
					<span class="silla-info-value"><?php echo $this->ruta_regreso->servicio;?></span>
				</span>
				<span class="silla-info-row">
					<span class="silla-info-label">Valor:</span>
					<span class="silla-info-value">$<?php echo number_format($this->tiquete['total_reg'],0,",",".")?></span>
				</span>
			<?php endif;?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="buttons-pago">
	<a href="index.php?option=com_flota&view=rutas">RETROCEDER</a>
	<input type="button" name="button" class="button-pago" name="button" onclick="validateForm('form-sillas'); return false;" value="Registrar Datos" />
</div>
</form>

<script type="text/javascript">
function validateForm(formu){
	if (jQuery('input[type=checkbox].sillai:checked').length < <?php echo $this->tiquete['pasajeros']?>) {
        BootstrapDialog.show({
			title: 'Advertencia',
            message: 'Debe eligir <?php echo $this->tiquete['pasajeros']?> Sillas por viaje',
            buttons: [{label: 'Cerrar',action: function(dialogItself){dialogItself.close();}}]});
        return false;
    }

    <?php if($this->tiquete['tipo_viaje']==2):?>
    if (jQuery('input[type=checkbox].sillareg:checked').length < <?php echo $this->tiquete['pasajeros']?>) {
        BootstrapDialog.show({
			title: 'Advertencia',
            message: 'Debe eligir <?php echo $this->tiquete['pasajeros']?> Sillas por viaje',
            buttons: [{label: 'Cerrar',action: function(dialogItself){dialogItself.close();}}]});
        return false;
    }
    <?php endif;?>
    document.getElementById('form-sillas').submit();

}

jQuery('input[type=checkbox].sillai').on('change', function (e) {
    if (jQuery('input[type=checkbox].sillai:checked').length > <?php echo $this->tiquete['pasajeros']?>) {
        jQuery(this).prop('checked', false);
        BootstrapDialog.show({
			title: 'Advertencia',
            message: 'Ya eligio el número de sillas necesarias',
            buttons: [{label: 'Cerrar',action: function(dialogItself){dialogItself.close();}}]});
    }
});

jQuery('input[type=checkbox].sillareg').on('change', function (e) {
    if (jQuery('input[type=checkbox].sillareg:checked').length > <?php echo $this->tiquete['pasajeros']?>) {
        jQuery(this).prop('checked', false);
        BootstrapDialog.show({
			title: 'Advertencia',
            message: 'Ya eligio el número de sillas necesarias',
            buttons: [{label: 'Cerrar',action: function(dialogItself){dialogItself.close();}}]});
    }
});


</script>