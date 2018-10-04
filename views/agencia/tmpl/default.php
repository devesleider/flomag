<?php defined('_JEXEC') or die;?>
<div id="agencias_mapa">
	<div class="colum-mapa">
		<div class="agencia-titulo"><?php echo $this->item->ciudad;?> - <?php echo $this->item->nombre;?></div>
		<div id="map"></div>
	</div>
	<div class="agencia-datos">
			<?php if($this->item->imagen!=""):?>
				<span class="agencia-imagen"><img src="<?php echo JURI::base().'images/agencias/'.$this->item->imagen;?>"></span>
			<?php endif;?>
			<label>Teléfono</label>
			<span class="agencia-dato"><?php echo $this->item->telefono;?></span>
			<label>Dirección</label>
			<span class="agencia-dato"><?php echo $this->item->direccion;?></span>
			<?php if($this->item->enlace!=""):?>
			<span class="agencia-enlace">
				<a href="<?php echo $this->item->enlace;?>">CONOCER MÁS</a>
			</span>
		<?php endif;?>
	</div>
	<?php $coordenadas = explode(",",$this->item->coordenadas);?>
	<script>
		function initMap() {
	  		var map = new google.maps.Map(document.getElementById('map'), {
	    		zoom: 12,
	    		center: {lat: <?php echo $coordenadas[0];?>, lng: <?php echo $coordenadas[1];?>}
	  		});

	  		var image = 'images/marcador-agencias.png';
	  		var beachMarker = new google.maps.Marker({
	    		position: {lat: <?php echo $coordenadas[0];?>, lng: <?php echo $coordenadas[1];?>},
	    		map: map,
	    		icon: image
	  		});
		}
	    </script>
	    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvx0qnimyEpzZ5UZUSMXe7Iar0ewtrMsc&signed_in=true&callback=initMap">
	</script>
</div>
