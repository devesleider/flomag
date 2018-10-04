<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
defined('_JEXEC') or die;

class FlotaFrontendHelper
{
	

	/**
	 * Get an instance of the named model
	 *
	 * @param string $name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_flota/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_flota/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'FlotaModel');
		}

		return $model;
	}
}
