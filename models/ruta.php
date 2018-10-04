<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

/**
 * Flota model.
 */
class FlotaModelRuta extends JModelItem
{

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_flota');

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_flota.edit.ruta.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_flota.edit.ruta.id', $id);
		}
		$this->setState('ruta.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();
		if (isset($params_array['item_id']))
		{
			$this->setState('ruta.id', $params_array['item_id']);
		}
		$this->setState('params', $params);
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param    integer    The id of the object to get.
	 *
	 * @return    mixed    Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('ruta.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			}
		}

		
		if ( isset($this->_item->created_by) ) {
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}

			if (isset($this->_item->origen) && $this->_item->origen != '') {
				if(is_object($this->_item->origen)){
					$this->_item->origen = JArrayHelper::fromObject($this->_item->origen);
				}
				$values = (is_array($this->_item->origen)) ? $this->_item->origen : explode(',',$this->_item->origen);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('municipio')
							->from('`#__flota_municipios`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->municipio;
					}
				}

			$this->_item->origen = !empty($textValue) ? implode(', ', $textValue) : $this->_item->origen;

			}

			if (isset($this->_item->destino) && $this->_item->destino != '') {
				if(is_object($this->_item->destino)){
					$this->_item->destino = JArrayHelper::fromObject($this->_item->destino);
				}
				$values = (is_array($this->_item->destino)) ? $this->_item->destino : explode(',',$this->_item->destino);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('municipio')
							->from('`#__flota_municipios`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->municipio;
					}
				}

			$this->_item->destino = !empty($textValue) ? implode(', ', $textValue) : $this->_item->destino;

			}

			if (isset($this->_item->tipo_servicio) && $this->_item->tipo_servicio != '') {
				if(is_object($this->_item->tipo_servicio)){
					$this->_item->tipo_servicio = JArrayHelper::fromObject($this->_item->tipo_servicio);
				}
				$values = (is_array($this->_item->tipo_servicio)) ? $this->_item->tipo_servicio : explode(',',$this->_item->tipo_servicio);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('nombre')
							->from('`#__flota_servicios`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->nombre;
					}
				}

			$this->_item->tipo_servicio = !empty($textValue) ? implode(', ', $textValue) : $this->_item->tipo_servicio;

			}

		return $this->_item;
	}

	public function getTable($type = 'Ruta', $prefix = 'FlotaTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_flota/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	public function getItemIdByAlias($alias)
	{
		$table = $this->getTable();

		$table->load(array( 'alias' => $alias ));

		return $table->id;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param    integer        The id of the row to check out.
	 *
	 * @return    boolean        True on success, false on failure.
	 * @since    1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('ruta.id');

		if ($id)
		{

			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param    integer        The id of the row to check out.
	 *
	 * @return    boolean        True on success, false on failure.
	 * @since    1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('ruta.id');

		if ($id)
		{

			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	public function getCategoryName($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	public function getRuta()
	{
		$app           = JFactory::getApplication()->input;
		$origen        = $app->getInt('origen');
		$destino       = $app->getInt('destino');

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'DISTINCT a.*'));
		$query->from('`#__flota_rutas` AS a');

		// Join over the foreign key 'origen'
		$query->select('#__flota_municipios_2139145.municipio AS  flota_municipios_municipio_2139145');
		$query->join('LEFT', '#__flota_municipios AS #__flota_municipios_2139145 ON #__flota_municipios_2139145.id = a.origen');
		// Join over the foreign key 'destino'
		$query->select('#__flota_municipios_2139146.municipio AS flota_municipios_municipio_2139146');
		$query->join('LEFT', '#__flota_municipios AS #__flota_municipios_2139146 ON #__flota_municipios_2139146.id = a.destino');
		// Join over the foreign key 'tipo_servicio'
		$query->select('#__flota_servicios_2139174.nombre AS flota_servicios_nombre_2139174, #__flota_servicios_2139174.id AS idservicio');
		$query->join('LEFT', '#__flota_servicios AS #__flota_servicios_2139174 ON #__flota_servicios_2139174.id = a.tipo_servicio');

		if(($origen != null)&& ($destino!=null) ){
			$query->where('( a.origen = '.(int)$origen.' ) AND (a.destino = '.(int)$destino.')');
		}
		$query->setLimit(1);
		$db->setQuery($query);
		$item = $db->loadObject();
		return $item;
	}

	

}
