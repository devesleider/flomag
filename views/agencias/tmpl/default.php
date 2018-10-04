<?php defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_flota');
$canEdit    = $user->authorise('core.edit', 'com_flota');
$canCheckin = $user->authorise('core.manage', 'com_flota');
$canChange  = $user->authorise('core.edit.state', 'com_flota');
$canDelete  = $user->authorise('core.delete', 'com_flota');
?>
<form action="<?php echo JRoute::_('index.php?option=com_flota&view=agencias'); ?>" method="post" name="adminForm" id="adminForm">
<div class="agencias-formulario">
	<div class="col-xs-12">
		<label>Ciudad</label>
		<select name="ciudad" id="ciudad">
			<option value="">Seleccione una Ciudad</option>
			<?php foreach($this->municipios as $muni):?>
				<option value="<?php echo $muni->id?>"><?php echo $muni->municipio;?></option>
			<?php endforeach;?>
		</select>
		<label>Oficina</label>
		<div id="agencias_html">
			<select name="agencia" id="agencia">
				<option value="">Seleccione una Ciudad</option>
			</select>
		</div>
	</div>
</div>

<div class="col-xs-12">
	<div class="agencias-mapa">
		<div id="agencias_mapa">
			<div class="colum-mapa">
				<div class="agencia-titulo">Bogotá - Terminal de Transportes Salitre</div>
				<div id="map"></div>
			</div>
			<div class="agencia-datos">
				<label>Teléfono</label>
				<span class="agencia-dato">3102104715</span>
				<label>Dirección</label>
				<span class="agencia-dato">Diagonal 23 # 69 –11 Terminal de Trasportes Salitre</span>
				<span class="agencia-enlace">
					<a href="bogota">CONOCER MÁS</a>
				</span>
			</div>
			<div class="clearfix"></div>
			<script>
				function initMap() {
			  		var map = new google.maps.Map(document.getElementById('map'), {
			    		zoom: 7,
			    		center: {lat: 4.654614, lng: -74.115172}
			  		});

			  		var image = '<?php echo JURI::base();?>images/marcador-agencias.png';
			  		var infowindow = new google.maps.InfoWindow();
			  		<?php  
			  		$i = 0;
			  		foreach ($this->items as $row): 
			  			$content_html  = '<p>'.$row->nombre.'</p>';
			  		    $content_html .= '<p>'.$row->telefono.'</p>'; 
			  		    $content_html .= '<p>'.$row->direccion.'</p>'; 
			  			$coordenadas   = explode(",",$row->coordenadas);
			  		?>
			  			var i = <?php echo $i;?>;
			  			var beachMarker = new google.maps.Marker({
				    		position: {lat: <?php echo $coordenadas[0];?>, lng: <?php echo $coordenadas[1];?>},
				    		map: map,
				    		icon: image
				  		});

				  		google.maps.event.addListener(beachMarker, 'click', (function(beachMarker, i) {
					        return function() {
					          infowindow.setContent('<?php echo $content_html;?>');
					          infowindow.open(map, beachMarker);
					        }
					      })(beachMarker, i));
			  		<?php $i++; endforeach;?>
			  		
				}
			    </script>
			    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvx0qnimyEpzZ5UZUSMXe7Iar0ewtrMsc&signed_in=true&callback=initMap">
			</script>
		</div>
	</div>
</div>
<div class="clearfix"></div>

<div class="agencias-listado">
	<?php foreach ($this->items as $i => $item) : ?>
	<div class="agencias-item">
		<h5><?php echo $item->nombre;?></h5>
		<ul>
			<li class="telefono"><?php echo $item->telefono;?></li>
			<li class="ubicacion"><?php echo $item->direccion;?></li>
		</ul>
	</div>
	<?php endforeach?>
</div>

<?php echo $this->pagination->getListFooter(); ?>

	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">
	var xhr;
jQuery(document).ready(function () {
    jQuery('#ciudad').change(function(){
        var ciudad = jQuery('#ciudad').val();
        xhr = jQuery.ajax({
                url: 'index.php', 
                data: 'option=com_flota&view=agencias&format=raw&ciudad='+ciudad, 
                success: function(data){
                    jQuery('#agencias_html').replaceWith(data);
                }
        });
    });

});
</script>