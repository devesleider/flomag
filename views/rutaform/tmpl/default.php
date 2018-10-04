<?php
/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_flota', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/components/com_flota/assets/js/form.js');

/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-ruta').submit(function (event) {
				
			});

			
			jQuery('input:hidden.origen').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('origenhidden')){
					jQuery('#jform_origen option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_origen").trigger("liszt:updated");
			jQuery('input:hidden.destino').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('destinohidden')){
					jQuery('#jform_destino option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_destino").trigger("liszt:updated");
			jQuery('input:hidden.tipo_servicio').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('tipo_serviciohidden')){
					jQuery('#jform_tipo_servicio option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_tipo_servicio").trigger("liszt:updated");
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-ruta').submit(function (event) {
				
			});

			
			jQuery('input:hidden.origen').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('origenhidden')){
					jQuery('#jform_origen option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_origen").trigger("liszt:updated");
			jQuery('input:hidden.destino').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('destinohidden')){
					jQuery('#jform_destino option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_destino").trigger("liszt:updated");
			jQuery('input:hidden.tipo_servicio').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('tipo_serviciohidden')){
					jQuery('#jform_tipo_servicio option[value="' + jQuery(this).val() + '"]').attr('selected',true);
				}
			});
					jQuery("#jform_tipo_servicio").trigger("liszt:updated");
		});
	}
</script>

<div class="ruta-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h1>Add</h1>
	<?php endif; ?>

	<form id="form-ruta" action="<?php echo JRoute::_('index.php?option=com_flota&task=ruta.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('origen'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('origen'); ?></div>
	</div>
	<?php foreach((array)$this->item->origen as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="origen" name="jform[origenhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('destino'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('destino'); ?></div>
	</div>
	<?php foreach((array)$this->item->destino as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="destino" name="jform[destinohidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('distancia'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('distancia'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tiempo_horas'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tiempo_horas'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tiempo_minutos'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tiempo_minutos'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('precio'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('precio'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('fecha'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('fecha'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('hora'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('hora'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('comentarios'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('comentarios'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('tipo_servicio'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('tipo_servicio'); ?></div>
	</div>
	<?php foreach((array)$this->item->tipo_servicio as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="tipo_servicio" name="jform[tipo_serviciohidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
				<?php endif; ?>
				<a class="btn" href="<?php echo JRoute::_('index.php?option=com_flota&task=rutaform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_flota" />
		<input type="hidden" name="task" value="rutaform.save" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
