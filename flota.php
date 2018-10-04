<?php
/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::register('FlotaFrontendHelper', JPATH_COMPONENT . '/helpers/flota.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Flota');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
