<?php defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once JPATH_SITE . '/components/com_flota/models/ruta.php';

class FlotaViewPagoform extends JViewLegacy
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

		$model    = JModelLegacy::getInstance('Ruta', 'FlotaModel'); 
		$model2   = JModelLegacy::getInstance('Ruta', 'FlotaModel'); 
		$session  = JFactory::getSession();
		$app      = JFactory::getApplication();
		$tiquete  = $session->get('tiquete');
		$layout   = $app->input->get('layout');
		if($app->input->post->get('metodo_pago', '', 'INT')){
			$tiquete['metodo_pago'] = $this->metodo_pago = $app->input->post->get('metodo_pago', '', 'INT');
			$session->set('tiquete', $tiquete);
		}else{
			$this->metodo_pago = $tiquete['metodo_pago'];
		}
		
		$this->ObRutaIda = $model->getData($tiquete['ruta_ida']);
		$this->ObRutaReg = $model2->getData($tiquete['ruta_regreso']);
		if($this->metodo_pago==2 && ($layout!="confirmacion")){
			$this->setLayout('debito');
		}
		$this->respuesta = $this->confirmacion;
		if($this->respuesta==null){
			$this->origen        	= $tiquete['origen'];
	        $this->destino      	= $tiquete['destino'];
	        $this->fecha_salida     = $tiquete['fecha_salida'];
	        $this->fecha_regreso    = $tiquete['fecha_regreso'];
	        $this->tipo_viaje       = $tiquete['tipo_viaje'];
	        $session->set("idsesion", time());
    	}

		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->state   = $this->get('State');
		$this->item    = $this->get('Data');
		$this->params  = $app->getParams('com_flota');
		$this->canSave = $this->get('CanSave');
		

		if (count($errors = $this->get('Errors'))){
			throw new Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();
		parent::display($tpl);
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

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

}
