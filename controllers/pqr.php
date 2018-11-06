<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Pago controller class.
 */
class FlotaControllerPagoForm extends FlotaController
{

	public function display($cachable = false, $urlparams = false){
		$view = $this->getView( 'Pqr', 'html' );
		$view->setModel( $this->getModel( 'Ruta' ), true );
		$view->display();
    }
    
}