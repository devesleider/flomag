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

jimport('joomla.application.component.view');
require_once JPATH_SITE . '/components/com_flota/models/ruta.php';
/**
 * View to edit
 */
class FlotaViewTiqueteform extends JViewLegacy
{

	protected $state;
	protected $item;
	protected $form;
	protected $params;
	protected $canSave;
	public    $confirmacion = null;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{

		$model = JModelLegacy::getInstance('Rutas', 'FlotaModel'); 
		//$this->item2 = $model->getData(1);
		$session     = JFactory::getSession();
		$app = JFactory::getApplication();

		$tiquete = $session->get('tiquete');
		$this->ObRutaIda = $model->getRuta($tiquete['ruta_ida'], $tiquete['fecha_salida']);
		$this->ObRutaReg = $model->getRuta($tiquete['ruta_regreso'], $tiquete['fecha_regreso']);

		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state   = $this->get('State');
		$this->item    = $this->get('Data');
		$this->params  = $app->getParams('com_flota');
		$this->canSave = $this->get('CanSave');
		

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		

		$this->_prepareDocument();

		parent::display($tpl);
	}

	public function displayPrice($item,$fecha, $format=1){
        if($item->precio_especial>0){
            return  ($format==2) ? $item->precio_especial : '$'.number_format($item->precio_especial,0,"",".");
        }elseif((date("N", strtotime($fecha))>=5 )&&(date("N", strtotime($fecha))<=7)){
            return ($format==2) ? $item->precio_finsemana : '$'.number_format($item->precio_finsemana,0,"",".");
        }else{
            return ($format==2) ? $item->precio : '$'.number_format($item->precio,0,"",".");
        }
    }

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_FLOTA_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}
		//$this->document->setTitle($title);

	}

}
