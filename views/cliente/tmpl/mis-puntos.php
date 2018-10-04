<?php defined('_JEXEC') or die;
	$user = JFactory::getUser();
	if (!$user->guest) {
		$puntos = $redimidos = $vencidos = 0;
		foreach($this->puntos as $row):
			switch($row->estado){
				case 0:{
					$vencidos += $row->puntos; 
					break;
				}
				case 1:{
					$puntos += $row->puntos;
					break;
				}
				case 2:{
					$redimidos += $row->puntos;
					break;
				}
				default:{
					$puntos += $row->puntos;
					break;
				}
			}
		endforeach;
?>
<div class="puntos-content">
	<div class="row">
		<div class="col-xs-12 col-sm-7">
			<p>Recibe todos los beneficios que Flota Magdalena tiene para ti.</p>
			<p><h2>Acumula Puntos</h2></p>
			<p>Por cada trayecto, acumulas en puntos el 10% de los kilometros que viajaste que luego podras redimir por pasajes.</p>
			<p>Los puntos tienen una vigencia de un año.</p>
		</div>
		<div class="col-xs-12 col-sm-5">
			<img src="images/puntos-viajero-preferencial.jpg">
		</div>
	</div>
</div>

<div class="puntos-description">
	<h1>Resumen <span>Puntos</span></h1>
	<div class="mis-puntos">
		<div class="mis-puntos-box">
			<div class="thead">Acumulados</div>
			<div class="tbody"><?php echo $puntos;?></div>
		</div>
		<div class="mis-puntos-box">
			<div class="thead">Vencidos</div>
			<div class="tbody"><?php echo $vencidos;?></div>
		</div>
		<div class="mis-puntos-box">
			<div class="thead">Redimidos</div>
			<div class="tbody"><?php echo $redimidos;?></div>
		</div>
		<div class="mis-puntos-box">
			<div class="thead">Disponibles</div>
			<div class="tbody redimidos"><span class=""><?php echo ($puntos-$vencidos-$redimidos);?></span> </div>
		</div>
	</div>
</div>
<?php }?>