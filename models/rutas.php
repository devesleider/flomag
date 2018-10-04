<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Flota records.
 */
class FlotaModelRutas extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'origen', 'a.origen',
                'destino', 'a.destino',
                'distancia', 'a.distancia',
                'tiempo_horas', 'a.tiempo_horas',
                'tiempo_minutos', 'a.tiempo_minutos',
                'precio', 'a.precio',
                'fecha', 'a.fecha',
                'hora', 'a.hora',
                'comentarios', 'a.comentarios',
                'tipo_servicio', 'a.tipo_servicio',

			);
		}
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{

		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->getUserStateFromRequest('limitstart', 'limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array( 'ASC', 'DESC', '' )))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[ count($orderingParts) - 1 ]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array( 'ASC', 'DESC', '' )))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');
		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');
		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
{
	$list['ordering'] = 'ordering';
}

if (empty($list['direction']))
{
	$list['direction'] = 'asc';
}

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}
		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}

	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		$app           = JFactory::getApplication()->input;
		$origen        = $app->getInt('origen');
		$destino       = $app->getInt('destino');
		$tipo_viaje    = $app->getInt('tipo_viaje');
		$fecha_salida  = $app->getCmd('fecha_salida');
		$fecha_regreso = $app->getCmd('fecha_regreso');

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'DISTINCT a.*'));
		$query->from('`#__flota_rutas` AS a');

		
	    // Join over the users for the checked out user.
	    $query->select('uc.name AS editor');
	    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
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

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.hora LIKE '.$search.' )');
			}
		}

		

		//Filtering origen
		$filter_origen = $this->state->get("filter.origen");
		if ($filter_origen) {
			$query->where("a.origen = '".$db->escape($filter_origen)."'");
		}

		//Filtering destino
		$filter_destino = $this->state->get("filter.destino");
		if ($filter_destino) {
			$query->where("a.destino = '".$db->escape($filter_destino)."'");
		}

		//Filtering fecha

		//Checking "_dateformat"
		$filter_fecha_from = $this->state->get("filter.fecha_from_dateformat");
		if ($filter_fecha_from && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_fecha_from) && date_create($filter_fecha_from) ) {
			$query->where("a.fecha >= '".$db->escape($filter_fecha_from)."'");
		}
		$filter_fecha_to = $this->state->get("filter.fecha_to_dateformat");
		if ($filter_fecha_to && preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $filter_fecha_to) && date_create($filter_fecha_to) ) {
			$query->where("a.fecha <= '".$db->escape($filter_fecha_to)."'");
		}

		//Filtering tipo_servicio
		$filter_tipo_servicio = $this->state->get("filter.tipo_servicio");
		if ($filter_tipo_servicio) {
			$query->where("a.tipo_servicio = '".$db->escape($filter_tipo_servicio)."'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		foreach($items as $item){
	

			if (isset($item->origen) && $item->origen != '') {
				if(is_object($item->origen)){
					$item->origen = JArrayHelper::fromObject($item->origen);
				}
				$values = (is_array($item->origen)) ? $item->origen : explode(',',$item->origen);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('municipio'))
							->from('`#__flota_municipios`')
							->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->municipio;
					}
				}

			$item->origen = !empty($textValue) ? implode(', ', $textValue) : $item->origen;

			}

			if (isset($item->destino) && $item->destino != '') {
				if(is_object($item->destino)){
					$item->destino = JArrayHelper::fromObject($item->destino);
				}
				$values = (is_array($item->destino)) ? $item->destino : explode(',',$item->destino);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('municipio'))
							->from('`#__flota_municipios`')
							->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->municipio;
					}
				}

			$item->destino = !empty($textValue) ? implode(', ', $textValue) : $item->destino;

			}

			if (isset($item->tipo_servicio) && $item->tipo_servicio != '') {
				if(is_object($item->tipo_servicio)){
					$item->tipo_servicio = JArrayHelper::fromObject($item->tipo_servicio);
				}
				$values = (is_array($item->tipo_servicio)) ? $item->tipo_servicio : explode(',',$item->tipo_servicio);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select($db->quoteName('nombre'))
							->from('`#__flota_servicios`')
							->where($db->quoteName('id') . ' = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->nombre;
					}
				}

			$item->tipo_servicio = !empty($textValue) ? implode(', ', $textValue) : $item->tipo_servicio;

			}
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;
		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[ $key ]  = '';
				$error_dateformat = true;
			}
		}
		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_FLOTA_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param string Contains the date to be checked
	 *
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

	public function getMunicipios(){
        $app           = JFactory::getApplication()->input;
		$origen        = $app->getInt('origen');

        $db    = $this->getDbo();
        $query = $db->getQuery(true);
        
        $query->select('m.id, m.municipio, m.origen, m.destino');
        $query->from('`#__flota_municipios` AS m');

        $query->select('r.id AS idruta');
	    $query->join('LEFT', '#__flota_rutas AS r ON m.id=r.destino');
        
        if($origen!=null)
            $query->where('r.origen = '.(int)$origen);

        $query->group('r.destino');
        $query->order('m.municipio');

        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
    }

    public function getMunicipio($mun=null){
    	if($mun!=null){
    		$db    = $this->getDbo();
        	$query = $db->getQuery(true);
        	$query->select('m.id, m.municipio');
        	$query->from('`#__flota_municipios` AS m');
        	$query->where('m.id = '.(int)$mun);
        	$db->setQuery($query);
        	$item = $db->loadObject();
        	return $item;
    	}
    }

    protected function getRutas($origen, $destino, $fecha){
    	$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.distancia, a.tiempo_horas, a.tiempo_minutos, a.precio, a.precio_finsemana, a.hora, a.tipo_servicio, s.id id_servicio, s.nombre servicio, s.link link_servicio, al.precio precio_especial, al.id id_alta ');
		$query->from(' #__flota_rutas AS a');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		$query->join('LEFT', '#__flota_municipios AS m1 ON m1.id = a.origen');
		$query->join('LEFT', '#__flota_municipios AS m2 ON m2.id = a.destino');
		$query->join('LEFT', '#__flota_servicios AS s ON s.id = a.tipo_servicio');
		$query->join('LEFT', '#__flota_tiquetes AS t ON a.id = t.ruta');
		$query->join('LEFT', '#__flota_altas AS al ON a.id = al.rutas_id AND (al.fecha_inicial <= "'.$fecha.'" AND al.fecha_final >= "'.$fecha.'")');
		$query->where('( a.origen = '.(int)$origen.' ) AND (a.destino = '.(int)$destino.') ');
		$query->group('a.id');
		$db->setQuery($query);
        return $db->loadObjectList();
    }

    public function getSillasRuta($ruta){
    	$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('s.sillas_izquierda, s.sillas_derecha,s.sillas_izquierda2, s.sillas_derecha2, s.sillas_disponibles');
		$query->from(' #__flota_servicios AS s');
		$query->join('LEFT', '#__flota_rutas AS r ON s.id=r.tipo_servicio');
		$query->where('r.id = '.(int)$ruta);
		$db->setQuery($query);
        return $db->loadObject();
    }

    public function getCountSillasDisponibles($ruta, $fecha){
    	if(($fecha!=null)&&($ruta!=null)){
	    	$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*) as sillas');
			$query->from(' #__flota_sillas AS s');
			$query->join('LEFT', '#__flota_tiquetes AS t ON s.tiquetes_id = t.id ');
			$query->join('LEFT', '#__flota_pagos AS p ON p.id=t.pagos_id ');
			$query->where('t.fecha = "'.$fecha.'" AND t.ruta = '.(int)$ruta.' AND p.estados_id = 1');
			$db->setQuery($query);
	        return $db->loadObject();
    	}
    	return 0;
    }

    public function getSillasOcupadas($ruta, $fecha){
    	if(($fecha!=null)&&($ruta!=null)){
    		$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('s.silla');
			$query->from(' #__flota_sillas AS s');
			$query->join('LEFT','#__flota_tiquetes AS ft ON s.tiquetes_id = ft.id ');
			$query->join('LEFT','#__flota_pagos AS fp ON ft.pagos_id = fp.id ');
			$query->where('ft.fecha = "'.$fecha.'" AND ft.ruta = '.(int)$ruta.' AND fp.estados_id IN (1,4,5,6,7)');
			$db->setQuery($query);
			$results = $db->loadObjectList();
			$sillas  = array();
			foreach ($results as $value) {
				$sillas[] = $value->silla;
			}
			$lista = implode(",",$sillas);
		    return $lista;
    	}
    	return null;
    }

    public function getSillasBloqueadas($ruta, $fecha){
    	if(($fecha!=null)&&($ruta!=null)){
    		$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('b.sillas');
			$query->from(' #__flota_bloqueos AS b');
			$query->where(' b.rutas_id = '.(int)$ruta.'  AND b.fecha = "'.$fecha.'"');
			$db->setQuery($query);
		    return $db->loadObject();
    	}
    	return null;
    }

    public function getCountSillasBloqueadas($ruta,$fecha){
    	if(($fecha!=null)&&($ruta!=null)){
    		$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*) as sillas');
			$query->from(' #__flota_bloqueos AS b');
			$query->where(' b.rutas_id = '.(int)$ruta.'  AND b.fecha = "'.$fecha.'"');
			$db->setQuery($query);
	        return $db->loadObject();
    	}
    	return 0;
    }

    public function getRutasDisponibles($regreso=1,$info){
    	$app = JFactory::getApplication()->input;
		if($regreso==1){
			$this->origenr  = $info['origen'];
			$this->destinor = $info['destino'];
			$this->fechar   = $info['fecha_salida'];
		}else{
			$this->origenr  = $info['destino'];
			$this->destinor = $info['origen'];
			$this->fechar   = $info['fecha_regreso'];
		}
		$resultados = $this->getRutas($this->origenr, $this->destinor, $this->fechar);

		return $resultados;
    }

    public function getRuta($id = null, $fecha = null){
    	if($id != null){
	    	$db    = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('r.id, r.origen, r.destino, r.precio, r.precio_finsemana, r.hora, r.tipo_servicio, s.id idservicio, s.nombre servicio, m1.municipio origen, m2.municipio destino, al.precio precio_especial, al.id id_alta');
			$query->from(' #__flota_rutas AS r');
			$query->join('LEFT', '#__flota_servicios AS s ON s.id=r.tipo_servicio');
			$query->join('LEFT', '#__flota_municipios AS m1 ON m1.id = r.origen');
			$query->join('LEFT', '#__flota_municipios AS m2 ON m2.id = r.destino');
			$query->join('LEFT', '#__flota_altas AS al ON r.id = al.rutas_id AND (al.fecha_inicial <= "'.$fecha.'" AND al.fecha_final >= "'.$fecha.'")');
			$query->where('r.id = '.(int)$id);
			$db->setQuery($query);
	        return $db->loadObject();
    	}
    	return null;

    }

}
