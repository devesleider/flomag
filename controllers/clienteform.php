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
 * Cliente controller class.
 */
class FlotaControllerClienteForm extends FlotaController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_flota.edit.cliente.id');
		$editId     = $app->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_flota.edit.cliente.id', $editId);

		// Get the model.
		$model = $this->getModel('ClienteForm', 'FlotaModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=clienteform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 * @since    1.6
	 */
	public function save(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$user  = JFactory::getUser();
		$model = $this->getModel('ClienteForm', 'FlotaModel');

		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		$url_return = ($data['return']==1) ? JRoute::_("index.php?option=com_flota&view=rutas&layout=login", false) : JRoute::_("index.php?option=com_flota&view=clienteform", false);

		if($data['id']!=null){
			$url_return = JRoute::_("index.php?option=com_flota&view=cliente", false) ;
		}

		$data['fecha_nacimiento'] = $data['anio'].'-'.$data['mes'].'-'.$data['dia'];

		// Save the data in the session.
		$app->setUserState('com_flota.edit.cliente.data', $data, array());

		if(!$this->validarNombre($data['nombre']) || !$this->validarNombre($data['apellidos']) || ($data['nombre']=="") || ($data['apellidos']=="")){
			$this->setMessage("ERROR: Los Nombres  y Apellidos solo pueden contener letras y espacios", 'warning');
			$this->setRedirect($url_return);
			return;
		}	
			

		if(!$this->validarEmail($data['email']) || ($data['email']=="")){
			$this->setMessage("ERROR: Los Email no tienen un formato valido (nombre@tudominio.com)", 'warning');
			$this->setRedirect($url_return);
			return;
		}

		if(!$this->validarTelefono($data['telefono']) || ($data['telefono']=="") ){
			$this->setMessage("ERROR: Los Teléfonos solo pueden contener números", 'warning');
			$this->setRedirect($url_return);
			return;
		}


		if(!$this->validarDocumento($data['documento']) || ($data['documento']=="")){
			$this->setMessage("ERROR: La Cédula solo puede contener números", 'warning');
			$this->setRedirect($url_return);
			return;
		}

		if($data['id']==null){
			if($data['password'] == ""){
				$this->setMessage("ERROR: Debe ingresar una contraseña", 'warning');
				$this->setRedirect($url_return);
				return;
			}
			$password = $data['password'];
			//Adicion del usuario
			$data['usuario'] = $this->addUser($data['nombre'], $data['email'], $password, $data['email']);
			$prueba1 = $data['usuario'];
		}else{
			$data['usuario'] = $user->id;
		}



		
		
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form){
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false){
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++){
				if ($errors[ $i ] instanceof Exception){
					$app->enqueueMessage($errors[ $i ]->getMessage(), 'warning');
				}else{
					$app->enqueueMessage($errors[ $i ], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.cliente.id');
			$this->setRedirect($url_return);

			return false;
		}

		
		

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false){
			// Save the data in the session.
			$app->setUserState('com_flota.edit.cliente.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.cliente.id');
			$this->setMessage(JText::sprintf('Error al guardar', $model->getError()), 'warning');
			$this->setRedirect($url_return);

			return false;
		}

		// Check in the profile.
		if ($return){
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.cliente.id', null);

		// Redirect to the list screen.
		
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php' : $item->link);
		$this->setRedirect($url_return);

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.cliente.data', null);
		
		if(($data['id']==0)||($data['id']==null)){
			$this->setMessage(JText::_('El usuario ha sido creado exitosamente. Por favor, ingresa con tu Email y Contraseña'));
			$this->setRedirect(JRoute::_("index.php?option=com_flota&view=rutas&layout=login", false));
			return true;
		}else{
			
			$this->setMessage(JText::_('Su información ha sido modificada exitosamente.'));
		}
	}

	public function addUser($name, $username, $password, $email) {
      jimport('joomla.user.helper');

      $data = array(
          "name"=>$name,
          "username"=>$username,
          "password"=>$password,
          "password2"=>$password,
          "email"=>$email,
          "block"=>0,
          "groups"=>array("1","2")
      );

      $user = new JUser;
      //Write to database
      if(!$user->bind($data)) {
          throw new Exception("Could not bind data. Error: " . $user->getError());
      }
      if (!$user->save()) {
          throw new Exception("Could not save user. Error: " . $user->getError());
      }

	  return $user->id;
	}

	public function validarNombre($nombre = NULL) { 
	  $validos="abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ "; 
	        $validez=1; 
	        for ($i=0;$i<=strlen($nombre)-1;$i++) { 
	            if (strpos($validos,substr($nombre,$i,1))===false) {$validez=0;} 
	        } 
	        return $validez; 
	}

    public function ValidarEmail($email)
	{
	   if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE){
	      return false;
	   }else{
	      return true;
   		}
	}

	public function validarDocumento($doc){
		$validos="0123456789"; 
	        $validez=1; 
	        for ($i=0;$i<=strlen($doc)-1;$i++) { 
	            if (strpos($validos,substr($doc,$i,1))===false) {$validez=0;} 
	        } 
	        return $validez; 
	}

	public function validarTelefono($tel){
		$validos="0123456789"; 
	        $validez=1; 
	        for ($i=0;$i<=strlen($tel)-1;$i++) { 
	            if (strpos($validos,substr($tel,$i,1))===false) {$validez=0;} 
	        } 
	        return $validez; 
	}

	public function cancel()
	{

		$app = JFactory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_flota.edit.cliente.id');

		// Get the model.
		$model = $this->getModel('ClienteForm', 'FlotaModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=clientes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}

	public function remove()
	{

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('ClienteForm', 'FlotaModel');

		// Get the user data.
		$data       = array();
		$data['id'] = $app->input->getInt('id');

		// Check for errors.
		if (empty($data['id']))
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[ $i ] instanceof Exception)
				{
					$app->enqueueMessage($errors[ $i ]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[ $i ], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_flota.edit.cliente.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.cliente.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=cliente&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->delete($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.cliente.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.cliente.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=cliente&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.cliente.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=clientes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.cliente.data', null);
	}

}
