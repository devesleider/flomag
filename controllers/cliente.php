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
class FlotaControllerCliente extends FlotaController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_flota.edit.cliente.id');
        $editId = $app->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_flota.edit.cliente.id', $editId);

        // Get the model.
        $model = $this->getModel('Cliente', 'FlotaModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId && $previousId !== $editId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_flota&view=clienteform&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function publish() {
        // Initialise variables.
        $app = JFactory::getApplication();

        //Checking if the user can remove object
        $user = JFactory::getUser();
        if ($user->authorise('core.edit', 'com_flota') || $user->authorise('core.edit.state', 'com_flota')) {
            $model = $this->getModel('Cliente', 'FlotaModel');

            // Get the user data.
            $id = $app->input->getInt('id');
            $state = $app->input->getInt('state');

            // Attempt to save the data.
            $return = $model->publish($id, $state);

            // Check for errors.
            if ($return === false) {
                $this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
            }

            // Clear the profile id from the session.
            $app->setUserState('com_flota.edit.cliente.id', null);

            // Flush the data from the session.
            $app->setUserState('com_flota.edit.cliente.data', null);

            // Redirect to the list screen.
            $this->setMessage(JText::_('COM_FLOTA_ITEM_SAVED_SUCCESSFULLY'));
            $menu = & JSite::getMenu();
            $item = $menu->getActive();
            if (!$item) {
                // If there isn't any menu item active, redirect to list view
                $this->setRedirect(JRoute::_('index.php?option=com_flota&view=clientes', false));
            } else {
                $this->setRedirect(JRoute::_($item->link . $menuitemid, false));
            }
        } else {
            throw new Exception(500);
        }
    }

    public function remove() {

        // Initialise variables.
        $app = JFactory::getApplication();

        //Checking if the user can remove object
        $user = JFactory::getUser();
        if ($user->authorise('core.delete', 'com_flota')) {
            $model = $this->getModel('Cliente', 'FlotaModel');

            // Get the user data.
            $id = $app->input->getInt('id', 0);

            // Attempt to save the data.
            $return = $model->delete($id);


            // Check for errors.
            if ($return === false) {
                $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            } else {
                // Check in the profile.
                if ($return) {
                    $model->checkin($return);
                }

                // Clear the profile id from the session.
                $app->setUserState('com_flota.edit.cliente.id', null);

                // Flush the data from the session.
                $app->setUserState('com_flota.edit.cliente.data', null);

                $this->setMessage(JText::_('COM_FLOTA_ITEM_DELETED_SUCCESSFULLY'));
            }

            // Redirect to the list screen.
            $menu = & JSite::getMenu();
            $item = $menu->getActive();
            $this->setRedirect(JRoute::_($item->link, false));
        } else {
            throw new Exception(500);
        }
    }

}
