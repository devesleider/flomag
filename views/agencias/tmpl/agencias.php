<?php defined('_JEXEC') or die;?>
<div id="agencias_html">
	<select name="agencia" id="agencia">
		<option value="">Seleccione una Agencia</option>
		<?php foreach($this->agencias as $ag):?>
			<option value="<?php echo $ag->id; ?>"><?php echo $ag->nombre; ?></option>
		<?php endforeach;?>
	</select>
</div>
<script type="text/javascript">
	var xhr;
jQuery(document).ready(function () {
    jQuery('#agencia').change(function(){
        var agencia = jQuery('#agencia').val();
        xhr = jQuery.ajax({
                url: 'index.php', 
                data: 'option=com_flota&view=agencia&format=raw&id='+agencia, 
                success: function(data){
                    jQuery('#agencias_mapa').replaceWith(data);
                }
        });
    });
});
</script>