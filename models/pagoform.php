<?php defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Flota model.
 */
class FlotaModelPagoForm extends JModelForm
{

	var $_item = null;

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
			$id = JFactory::getApplication()->getUserState('com_flota.edit.pago.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_flota.edit.pago.id', $id);
		}
		$this->setState('pago.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();
		if (isset($params_array['item_id']))
		{
			$this->setState('pago.id', $params_array['item_id']);
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
	public function &getData($id = null){
		if ($this->_item === null){
			$this->_item = false;

			if (empty($id)){
				$id = $this->getState('pago.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table !== false && $table->load($id)){
				$user = JFactory::getUser();
				$id   = $table->id;

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			}
		}

		return $this->_item;
	}

	public function getTable($type = 'Pago', $prefix = 'FlotaTable', $config = array())
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
		$id = (!empty($id)) ? $id : (int) $this->getState('pago.id');

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
		$id = (!empty($id)) ? $id : (int) $this->getState('pago.id');

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

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML
	 *
	 * @param    array   $data     An optional array of data for the form to interogate.
	 * @param    boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return    JForm    A JForm object on success, false on failure
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_flota.pago', 'pagoform', array(
			'control'   => 'jform',
			'load_data' => $loadData
		));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form.
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_flota.edit.pago.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param    array        The form data.
	 *
	 * @return    mixed        The user id on success, false on failure.
	 * @since    1.6
	 */
	public function save($data)
	{
		$id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('pago.id');
		$user  = JFactory::getUser();
		if ($id)
		{
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'com_flota.cliente.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_flota.cliente.'.$id);
			if ($user->authorise('core.edit.state', 'com_flota.cliente.'.$id) !== true && $state == 1)
			{ //The user cannot edit the state of the item.
				$data['state'] = 0;
			}
		}
		$table = $this->getTable();
		if ($table->save($data) === true)
		{
			return $table->id;
		}
		else
		{
			print_r("no se pudo");
			return false;
		}

	}

	public function delete($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('pago.id');
		if (JFactory::getUser()->authorise('core.delete', 'com_flota.cliente.'.$id) !== true)
		{
			throw new Exception(403, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		$table = $this->getTable();
		if ($table->delete($data['id']) === true)
		{
			return $id;
		}
		else
		{
			return false;
		}
	}

	public function getlogin(){
		return "6dd490faf9cb87a9862245da41170ff2";
	}

	public function gettrankey(){
		return "024h1IlD";
	}

	public function getCanSave(){
		$table = $this->getTable();
		return $table !== false;
	}

	public function getPagos($id = null){
		$table = $this->getTable();
		return $table->getPagos($id);
	}

	public function getPendientes($id = null){
		$table = $this->getTable();
		return $table->getPendientes($id);
	}

	public function getPago($pid=null){
		if($pid!=null){
			$table = $this->getTable();
			return $table->getPago($pid);
		}
		return null;
	}

	public function getDescuento($bono=null, $fecha=null){
		if($bono!=null && $fecha!=null){
			$table = $this->getTable();
			return $table->getDescuento($bono,$fecha);
		}
		return null;

	}


	

}