<?php defined('_JEXEC') or die;
	$user = JFactory::getUser();
	if (!$user->guest) {
		$i=2;
?>
<div class="viajes">
	<table>
		<thead>
			<td>Codigo</td>
			<td>Estado</td>
			<td>Origen</td>
			<td>Destino</td>
			<td>Fecha del Viaje</td>
			<td>Hora</td>
			<td>Sillas</td>
			<td>Precio</td>
		</thead>
		<?php foreach($this->tiquetes as $item):
				$class = (($i++%2)==0) ? 'i' : 'p';
		?>
		<tr class="<?php echo $class; ?>">
			<td><?php echo $item->id; ?></td>
			<td><?php echo $item->estado; ?></td>
			<td><?php echo $item->origen; ?></td>
			<td><?php echo $item->destino; ?></td>
			<td><?php echo $item->fecha; ?></td>
			<td><?php echo $item->hora; ?></td>
			<td><?php 
				$sillas = $this->getSillas($item->id);
				if($sillas):
					foreach($sillas as $row):
						echo '<span class="silla_escogida">'.$row->silla."</span>";
					endforeach;	
				endif;
				?>
			</td>
			<td>$<?php echo number_format($item->precio); ?></td>
			
		</tr>
	<?php endforeach;?>
	</table>
</div>
<?php }?>