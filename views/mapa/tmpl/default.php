<?php defined('_JEXEC') or die;?>

<div class="agencias-formulario">
  <div class="col-xs-12">
    <label>Origen</label>
    <select name="origen" id="origen1">
      <option value="">Seleccione un Origen</option>
      <?php 
        foreach($this->origenes as $muni):
          $select = ($this->origen_df==$muni->id) ? 'selected="selected" ' : null; 

      ?>
        <option value="<?php echo $muni->id;?>" <?php echo $select?>><?php echo $muni->municipio;?></option>
      <?php endforeach;?>
    </select>
    <label>Destino</label>
    <div id="destinos_html">
      <select name="destino" id="destino1">
        <option value="">Seleccione un Destino</option>
          <?php 
            foreach($this->destinos as $muni):
              $select = ($this->destino_df==$muni->id) ? 'selected="selected" ' : null;
          ?>
          <option value="<?php echo $muni->id;?>" <?php echo $select?>><?php echo $muni->municipio;?></option>
        <?php endforeach;?>
      </select>
    </div>
    <label class="distancia">
      <img src="images/distancia.png">
      <span id="distancia">&nbsp;</span>
    </label>
    <label class="tiempo">
      <img src="images/tiempo.png">
      <span id="tiempo">&nbsp;</span>
    </label>
  </div>
</div>

<div class="mapa_rutas">
  <div id="map_ruta2">
    <img src="images/mapa-rutas.jpg" alt="">
  </div>
</div>
<script type="text/javascript">
  var xhr;
jQuery(document).ready(function () {
    jQuery('#origen1').change(function(){
        var origen = jQuery('#origen1').val();
        var destino = jQuery('#destino1').val();
        xhr = jQuery.ajax({
                url: 'index.php', 
                data: 'option=com_flota&view=mapa&format=raw&layout=municipios&origen='+origen, 
                success: function(data){
                    jQuery('#destinos_html').replaceWith(data);
                }
        });
    });
    <?php if(($this->origen_df!=0)&&($this->destino_df!=0)):?>
        var origen1  = <?php echo $this->origen_df?>;
        var destino1 = <?php echo $this->destino_df?>;
        xhr = jQuery.ajax({
                url: 'index.php', 
                data: 'option=com_flota&view=ruta&format=raw&origen='+origen1+'&destino='+destino1, 
                success: function(data){
                    jQuery('#map_ruta2').html(data);
                }
        });
    <?php endif;?>
});


</script>
