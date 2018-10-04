<?php defined('_JEXEC') or die;?>
<?php if($this->inicio==1):?>
  <div id="destinos_select">
      <select name="destino" id="destino" required="required">
        <option value="">Seleccione</option>
          <?php foreach($this->destinos as $muni):?>
          <option value="<?php echo $muni->id;?>"><?php echo $muni->municipio;?></option>
        <?php endforeach;?>
      </select>
    </div>
<?php else:?>
  <div id="destinos_html">
      <select name="destino" id="destino1">
        <option value="">Seleccione un Destino <?php echo $this->inicio?></option>
          <?php foreach($this->destinos as $muni):?>
          <option value="<?php echo $muni->id;?>"><?php echo $muni->municipio;?></option>
        <?php endforeach;?>
      </select>
    </div>
    <script type="text/javascript">
  jQuery('#destino1').change(function(){
        var origen = jQuery('#origen1').val();
        var destino = jQuery('#destino1').val();
        xhr = jQuery.ajax({
                url: 'index.php', 
                data: 'option=com_flota&view=ruta&format=raw&origen='+origen+'&destino='+destino, 
                success: function(data){
                    jQuery('#map_ruta2').html(data);
                }
        });
    });
</script>
<?php endif;?>