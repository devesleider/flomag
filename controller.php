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

jimport('joomla.application.component.controller');

class FlotaController extends JControllerLegacy {

    /**
     * Method to display a view.
     *
     * @param	boolean			$cachable	If true, the view output will be cached
     * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return	JController		This object to support chaining.
     * @since	1.5
     */
    public function display($cachable = false, $urlparams = false) {
        require_once JPATH_COMPONENT . '/helpers/flota.php';

        $viewname = JFactory::getApplication()->input->getCmd('view', 'rutas');
        $format   = JFactory::getApplication()->input->getCmd('format', 'html');
        $user     = JFactory::getUser();
      //  JFactory::getApplication()->input->set('view', $viewname);

        switch ($viewname){
            case 'agencias':
                $viewLayout = $this->input->get('layout', 'default');
                $view = $this->getView($viewname, $format, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
                $view->setModel($this->getModel('agencias'),true);
                $view->setModel($this->getModel('municipios')); 
                break;

            case 'mapa':
                $viewLayout = $this->input->get('layout', 'default');
                $view = $this->getView($viewname, $format, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));
                $view->setModel($this->getModel('municipios')); 
                $view->setModel($this->getModel('rutas')); 
                break;

            case 'rutas':
                $viewLayout = $this->input->get('layout', 'default');
                if(($viewLayout=="login")&& (!$user->guest)){
                    $this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
                }
                break;

            case 'pagoform':
                $viewLayout = $this->input->get('layout', 'default');
                if($user->guest){
                    $this->setRedirect('index.php');
                    return;
                }
                break;

        }
        parent::display($cachable, $urlparams);

        return $this;
    }

}
