<?php defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$iservicios = array("1"=>"logo-gacela-premium.png","2"=>"logo-gacela-duplex-premium.png",
					"3"=>"logo-gacela-vip.png","4"=>"logo-gacela-premium.png","5"=>"logo-gacela-puerta.png");
$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_flota');
$canEdit    = $user->authorise('core.edit', 'com_flota');
$canCheckin = $user->authorise('core.manage', 'com_flota');
$canChange  = $user->authorise('core.edit.state', 'com_flota');
$canDelete  = $user->authorise('core.delete', 'com_flota');
$fecha_actual = strtotime(date('Y-m-d'));
$fecha_sal    = strtotime($this->tiquete['fecha_salida']);
$prueba = $this->params->get("pagos_url_tc");
if($fecha_sal>$fecha_actual){
?>
<div class="resultados-top">
	<div class="buttons-pago">
		<a href="index.php">< Nueva Ruta</a>
	</div>
</div>
<div class="resultados-header">
	<div class="pasos">
		<span>Pago</span>
		<span>Selección de viaje</span>
		<span>Búsqueda</span>
		
	</div>
</div>
<div class="rutas-desktop">
	<form action="<?php echo JRoute::_('index.php?option=com_flota&view=rutas&layout=sillas'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="resultados-rutas">
		<div class="fechas" style="display:none">
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span class="active">18/01/2015</span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
		</div>
		<div class="clearfix"></div>
		<div class="resultado-ruta">
			<div class="viaje">
				<span class="subtitulo">viaje de ida</span>
				<span class="borde"></span>
				<span class="descripcion-ruta">
					<?php echo $this->municipio_origen->municipio.' - '.$this->municipio_destino->municipio;?>
				</span>
				<span class="fecha-ruta">
					<?php echo $this->tiquete['fecha_salida'];?>
				</span>
			</div>
			<div class="rutas">
				<table cellspacing="0" class="listado-rutas">
					<tr>
						<th>Hora de Salida</th>
						<th>Servicio</th>
						<th>Ruta</th>
						<th>Tarifa Web</th>
					</tr>
					<?php 
						foreach ($this->resultados_ida as $i => $item) : 
							$total_sillas = 6 - $this->getCantidadSillas($item->id, $this->tiquete['fecha_salida']);
							$blocked = (($total_sillas==0) || ($total_sillas < $this->tiquete['pasajeros'])) ? "disabled" : null; 
							$checked = (isset($this->tiquete['ruta_ida'])&&($item->id==$this->tiquete['ruta_ida'])) ? 'checked' : null;
					?>
					
					
						<tr id="<?php echo $item->id;?>" class="<?php echo $checked.' '.$blocked;?>">
							<td><input type="radio" <?php echo $blocked.' '.$checked;?> required title="Hora de tu viaje" name="tiquete_ida" value="<?php echo $item->id;?>"><?php echo date("g:i a",strtotime($item->hora)); ?></td>
							<td>
								<a href="<?php echo $item->link_servicio; ?>" target="blank">
									<img src="images/<?php echo $iservicios[$item->id_servicio]; ?>" alt="<?php echo $item->servicio; ?>" />
								</a>
							</td>
							<td>
								<a href="index.php?option=com_flota&view=mapa&ori=<?php echo $this->tiquete['origen'];?>&des=<?php echo $this->tiquete['destino'];?>"  target="blank">Ver Ruta</a>
							</td>
							<td>
								<p><?php echo $this->displayPrice($item,$this->tiquete['fecha_salida']);?></p>
								<?php if(($total_sillas<4)||($blocked=="disabled")):?>
								<p class="total_sillas"><?php echo $total_sillas;?> Sillas disponibles</p>
								<?php endif;?>
							</td>
						</tr>
					<?php endforeach?>
				</table>
			</div>
		</div>
	</div>
	<?php if($this->tiquete['tipo_viaje']==2):?>
		<div class="resultados-rutas">
			<div class="fechas" style="display:none">
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span class="active">18/01/2015</span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
			</div>
			<div class="clearfix"></div>
			<div class="resultado-ruta">
				<div class="viaje">
					<span class="subtitulo">viaje de regreso</span>
					<span class="borde"></span>
					<span class="descripcion-ruta">
						<?php echo $this->municipio_destino->municipio.' - '.$this->municipio_origen->municipio;?>
					</span>
					<span class="fecha-ruta">
						<?php echo $this->tiquete['fecha_regreso'];?>
					</span>
				</div>
				<div class="rutas">
					<table cellspacing="0" class="listado-rutas">
						<tr>
							<th>Hora de Salida</th>
							<th>Servicio</th>
							<th>Ruta</th>
							<th>Tarifa Web</th>
						</tr>
						<?php 
							foreach ($this->resultados_regreso as $i => $item) : 
								$total_sillas = 6 - $this->getCantidadSillas($item->id, $this->tiquete['fecha_regreso']);
							    $blocked = (($total_sillas==0) || ($total_sillas < $this->tiquete['pasajeros'])) ? "disabled" : null; 
								$checked = ((isset($this->tiquete['ruta_regreso']))&&($item->id==$this->tiquete['ruta_regreso'])) ? 'checked' : null;
						?>
						
						
							<tr id="<?php echo $item->id;?>" class="<?php echo $checked;?>">
								<td><input type="radio" <?php echo $blocked.' '.$checked;?> required title="Hora de tu viaje" name="tiquete_regreso" value="<?php echo $item->id;?>"><?php echo date("g:i a",strtotime($item->hora)); ?></td>
								<td>
									<a href="<?php echo $item->link_servicio; ?>" target="blank">
										<img src="images/<?php echo $iservicios[$item->id_servicio]; ?>" alt="<?php echo $item->servicio; ?>" />
									</a>
								</td>
								<td>
									<a href="index.php?option=com_flota&view=mapa&ori=<?php echo $this->tiquete['destino'];?>&des=<?php echo $this->tiquete['origen'];?>"  target="blank">Ver Ruta</a>
								</td>
								<td>
									<p><?php echo $this->displayPrice($item,$this->tiquete['fecha_regreso']);?></p>
									<?php if(($total_sillas<4)||($blocked=="disabled")):?>
									<p class="total_sillas"><?php echo $total_sillas;?> Sillas disponibles</p>
									<?php endif;?>
									
								</td>
							</tr>
						<?php endforeach?>
					</table>
				</div>
			</div>
		</div>
	<?php endif;?>
	<div class="buttons-pago">
		<a href="index.php">Retroceder</a>
		<input type="submit" class="button-pago" name="submit" value="Seleccionar Sillas" />
	</div>
	<?php if ($canCreate): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_flota&task=rutaform.edit&id=0', false, 2); ?>"
			class="btn btn-success btn-small"><i
				class="icon-plus"></i> <?php echo JText::_('COM_FLOTA_ADD_ITEM'); ?></a>
	<?php endif; ?>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>
<div class="rutas-mobile" style="display:none">
	<form action="<?php echo JRoute::_('index.php?option=com_flota&view=rutas&layout=login'); ?>" method="post" name="adminForm" id="adminForm">
	<div class="resultados-rutas">
		<div class="fechas" style="display:none">
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span class="active">18/01/2015</span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
			<span><a href="#">18/01/2015</a></span>
		</div>
		<div class="clearfix"></div>
		<div class="resultado-ruta">
			<div class="viaje">
				<span class="subtitulo">viaje de ida</span>
				<span class="borde"></span>
				<span class="descripcion-ruta">
					<?php echo $this->municipio_origen->municipio.' - '.$this->municipio_destino->municipio;?>
				</span>
				<span class="fecha-ruta">
					<?php echo $this->tiquete['fecha_salida'];?>
				</span>
			</div>
			<div class="rutas">

					<?php 
						foreach ($this->resultados_ida as $i => $item) : 
							$total_sillas = 6 - $this->getCantidadSillas($item->id, $this->tiquete['fecha_salida']);
							$blocked = (($total_sillas==0) || ($total_sillas < $this->tiquete['pasajeros'])) ? "disabled" : null; 
							$checked = (isset($this->tiquete['ruta_ida'])&&($item->id==$this->tiquete['ruta_ida'])) ? 'checked' : null;
					?>
					<table cellspacing="0" id="maria" class="maria listado-rutas <?php echo $checked;?>">
						<tr>
							<th>Hora de Salida</th>
							<td><input type="radio" <?php echo $blocked.' '.$checked;?> required title="Hora de tu viaje" name="tiquete_ida" value="<?php echo $item->id;?>"><?php echo date("g:i a",strtotime($item->hora)); ?></td>
						</tr>
						<tr>
							<th>Servicio</th>
							<td>
								<a href="<?php echo $item->link_servicio; ?>" target="blank">
									<img src="images/<?php echo $iservicios[$item->id_servicio]; ?>" alt="<?php echo $item->servicio; ?>" />
								</a>
							</td>
						</tr>
						<tr>
							<th>Ruta</th>
							<td><td>
								<a href="index.php?option=com_flota&view=mapa&ori=<?php echo $this->tiquete['origen'];?>&des=<?php echo $this->tiquete['destino'];?>"  target="blank">Ver Ruta</a>
							</td></td>
						</tr>
						<tr>
							<th>Tarifa Web</th>
							<td>
								<p><?php echo $this->displayPrice($item,$this->tiquete['fecha_salida']);?></p>
								<?php if(($total_sillas<4)||($blocked=="disabled")):?>
								<p class="total_sillas"><?php echo $total_sillas;?> Sillas disponibles</p>
								<?php endif;?>
							</td>
						</tr>
					</table>
					<?php endforeach?>
			</div>
		</div>
	</div>
	<?php if($this->tiquete['tipo_viaje']==2):?>
		<div class="resultados-rutas">
			<div class="fechas" style="display:none">
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span class="active">18/01/2015</span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
				<span><a href="#">18/01/2015</a></span>
			</div>
			<div class="clearfix"></div>
			<div class="resultado-ruta">
				<div class="viaje">
					<span class="subtitulo">viaje de regreso</span>
					<span class="borde"></span>
					<span class="descripcion-ruta">
						<?php echo $this->municipio_destino->municipio.' - '.$this->municipio_origen->municipio;?>
					</span>
					<span class="fecha-ruta">
						<?php echo $this->tiquete['fecha_regreso'];?>
					</span>
				</div>
				<div class="rutas">
						<?php 
							foreach ($this->resultados_regreso as $i => $item) : 
								$total_sillas = 6 - $this->getCantidadSillas($item->id, $this->tiquete['fecha_regreso']);
							    $blocked = (($total_sillas==0) || ($total_sillas < $this->tiquete['pasajeros'])) ? "disabled" : null; 
								$checked = ((isset($this->tiquete['ruta_regreso']))&&($item->id==$this->tiquete['ruta_regreso'])) ? 'checked' : null;
						?>
						<table cellspacing="0" id="maria" class="maria listado-rutas <?php echo $checked;?>">
							<tr>
								<th>Hora de Salida</th>
								<td><input type="radio" <?php echo $blocked.' '.$checked;?> required title="Hora de tu viaje" name="tiquete_regreso" value="<?php echo $item->id;?>"><?php echo date("g:i a",strtotime($item->hora)); ?></td>
							</tr>
							<tr>
								<th>Servicio</th>
								<td>
									<a href="<?php echo $item->link_servicio; ?>" target="blank">
										<img src="images/<?php echo $iservicios[$item->id_servicio]; ?>" alt="<?php echo $item->servicio; ?>" />
									</a>
								</td>
							</tr>
							<tr>
								<th>Ruta</th>
								<td>
									<td>
								<a href="index.php?option=com_flota&view=mapa&ori=<?php echo $this->tiquete['destino'];?>&des=<?php echo $this->tiquete['origen'];?>"  target="blank">Ver Ruta</a>
							</td>
								</td>
							</tr>
							<tr>
								<th>Tarifa Web</th>
								<td>
									<p><?php echo $this->displayPrice($item,$this->tiquete['fecha_regreso']);?></p>
									<?php if(($total_sillas<4)||($blocked=="disabled")):?>
									<p class="total_sillas"><?php echo $total_sillas;?> Sillas disponibles</p>
									<?php endif;?>
								</td>
							</tr>
						</table>
						<?php endforeach?>
				</div>
			</div>
		</div>
	<?php endif;?>
	<div class="buttons-pago">
		<a href="index.php">Retroceder</a>
		<input type="submit" class="button-pago" name="submit" value="Seleccionar Sillas" />
	</div>
	<?php if ($canCreate): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_flota&task=rutaform.edit&id=0', false, 2); ?>"
			class="btn btn-success btn-small"><i
				class="icon-plus"></i> <?php echo JText::_('COM_FLOTA_ADD_ITEM'); ?></a>
	<?php endif; ?>
	<input type="hidden" name="dispositivo" value="1" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>


<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {
		var item_id = jQuery(this).attr('data-item-id');
		<?php if($canDelete): ?>
		if (confirm("<?php echo JText::_('COM_FLOTA_DELETE_MESSAGE'); ?>")) {
			window.location.href = '<?php echo JRoute::_('index.php?option=com_flota&task=rutaform.remove&id=', false, 2) ?>' + item_id;
		}
		<?php endif; ?>
	}
</script>
<?php }else{?>
Para encontrar Rutas disponibles debe seleccionar una fecha superior a la actual
<div class="clearfix"></div><br><br>
<a href="index.php" class="button-submit">VOLVER</a>
<?php }?>
<style>
.resultados-rutas .resultado-ruta .rutas table.listado-rutas tr.disabled td {
    background-color: bisque;
}
</style>


