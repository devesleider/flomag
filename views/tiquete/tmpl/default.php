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
/*
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_flota.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_flota' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}*/
?>
<?php var_dump($this->resultado);//if ($this->item) : ?>

    <div class="item_fields">
        <table class="table">
            
        </table>
    </div>
    
    <?php /*
else:
    echo JText::_('COM_FLOTA_ITEM_NOT_LOADED');
endif;*/
?>
