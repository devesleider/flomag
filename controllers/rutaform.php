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
 * Ruta controller class.
 */
class FlotaControllerRutaForm extends FlotaController
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
		$previousId = (int) $app->getUserState('com_flota.edit.ruta.id');
		$editId     = $app->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_flota.edit.ruta.id', $editId);

		// Get the model.
		$model = $this->getModel('RutaForm', 'FlotaModel');

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
		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=rutaform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 * @since    1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('RutaForm', 'FlotaModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
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

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_flota.edit.ruta.data', $jform, array());

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.ruta.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=rutaform&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.ruta.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.ruta.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=rutaform&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.ruta.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_SAVED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=rutas' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.ruta.data', null);
	}

	public function cancel()
	{

		$app = JFactory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_flota.edit.ruta.id');

		// Get the model.
		$model = $this->getModel('RutaForm', 'FlotaModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=rutas' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}

	public function remove()
	{

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('RutaForm', 'FlotaModel');

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
			$app->setUserState('com_flota.edit.ruta.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.ruta.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=ruta&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->delete($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.ruta.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.ruta.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=ruta&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.ruta.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=rutas' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.ruta.data', null);
	}

}
