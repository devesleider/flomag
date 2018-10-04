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
 * Tiquete controller class.
 */
class FlotaControllerTiqueteForm extends FlotaController
{

	public function display($cachable = false, $urlparams = false){
		$view = $this->getView( 'TiqueteForm', 'html' );
		$view->setModel( $this->getModel( 'Ruta' ), true );
		$view->display();
	}
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_flota.edit.tiquete.id');
		$editId     = $app->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_flota.edit.tiquete.id', $editId);

		// Get the model.
		$model = $this->getModel('TiqueteForm', 'FlotaModel');

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
		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform&layout=edit', false));
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

		$model = $this->getModel('TiqueteForm', 'FlotaModel');
		$session =& JFactory::getSession();

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		$jinput = JFactory::getApplication()->input;
		$component 		= $jinput->get('option');
		$params 		= JComponentHelper::getParams($component);

		//PROCESAMIENTO DE LA INFORMACION PARA LATAI
		//informacion de compra
		$ruta = $jinput->post->get('ruta', '', 'int');
		$fecha_salida  = $jinput->post->get('fecha_salida', '', 'STRING');
		$fecha_regreso = $jinput->post->get('fecha_regreso', '', 'STRING');
		$precio = $jinput->post->get('precio', '', 'INT');
		$referencia    = time();

		//informacion personal
		$nombres = $jinput->post->get('nombres', '', 'STRING');
		$apellidos = $jinput->post->get('apellidos', '', 'STRING');
		$cedula = $jinput->post->get('cedula', '', 'STRING');
		$telefono = $jinput->post->get('telefono', '', 'STRING');
		$email = $jinput->post->get('email', '', 'STRING');
		$usuario = $jinput->post->get('usuario', '', 'USERNAME');
		$password = $jinput->post->get('password', '', 'STRING');
		
		//informacion titular
		$nombre_titular = $jinput->post->get('nombre_titular', '', 'STRING');
		$apellidos_titular = $jinput->post->get('apellidos_titular', '', 'STRING');
		$email_titular = $jinput->post->get('email_titular', '', 'STRING');
		$cedula_titular = $jinput->post->get('cedula_titular', '', 'STRING');
		$telefono_titular = $jinput->post->get('telefono_titular', '', 'STRING');
		$departamento_titular = $jinput->post->get('departamento_titular', '', 'STRING');
		$ciudad_titular = $jinput->post->get('ciudad_titular', '', 'STRING');
		$direccion_titular = $jinput->post->get('direccion_titular', '', 'STRING');

		//informacion tarjeta
		$franquicia = $jinput->post->get('franquicia', '', 'INT');
		$numero_tarjeta = $jinput->post->get('numero_tarjeta', '', 'STRING');
		$mes_vencimiento = $jinput->post->get('mes_vencimiento', '', 'STRING');
		$anio_vencimiento = $jinput->post->get('anio_vencimiento', '', 'INT');
		$tipo_cuenta = $jinput->post->get('tipo_cuenta', 'C', 'CMD');
		$codigo_tarjeta = $jinput->post->get('codigo_tarjeta', '', 'INT');

		 $session->set('fecha_salida', $fecha_salida);
		 $session->set('nombres', $nombres);
	     $session->set('apellidos',$apellidos);
	     $session->set('cedula',$cedula);
	     $session->set('telefono',$telefono);
	     $session->set('email', $email);
	     $session->set('usuario',$usuario);
	     $session->set('nombre_titular',$nombre_titular);
	     $session->set('apellidos_titular', $apellidos_titular);
	     $session->set('email_titular',$email_titular);
	     $session->set('cedula_titular',$cedula_titular);
	     $session->set('telefono_titular',$telefono_titular);
	     $session->set('departamento_titular', $departamento_titular);
	     $session->set('ciudad_titular',$ciudad_titular);
	     $session->set('direccion_titular',$direccion_titular);

		if($usuario == ""){
			$this->setMessage("ERROR: Debe ingresar un nombre de usuario", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($password == ""){
			$this->setMessage("ERROR: Debe ingresar una contraseña", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($numero_tarjeta == ""){
			$this->setMessage("ERROR: Debe ingresar un Número de Tarjeta en la información de pago", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($mes_vencimiento == ""){
			$this->setMessage("ERROR: Debe seleccionar un mes de vencimiento", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($anio_vencimiento == ""){
			$this->setMessage("ERROR: Debe seleccionar un año de vencimiento de su tarjeta", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($codigo_tarjeta == ""){
			$this->setMessage("ERROR: Debe ingresar un Codigo  de Verificación de su tarjeta valido", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($direccion_titular == ""){
			$this->setMessage("ERROR: Debe ingresar la Dirección del Titular", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if($departamento_titular == ""){
			$this->setMessage("ERROR: Debe ingresar el Departamento del Titular", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		//VALIDACIONES
		if(!$this->validarNombre($nombres) || !$this->validarNombre($apellidos) || !$this->validarNombre($nombre_titular) ||
		 !$this->validarNombre($apellidos_titular) || ($nombres=="") || ($apellidos=="") || ($nombre_titular=="") || ($apellidos_titular=="")){
			$this->setMessage("ERROR: Los Nombres  y Apellidos solo pueden contener letras y espacios", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}	
			

		if(!$this->validarEmail($email_titular) || !$this->validarEmail($email) || ($email_titular=="") || ($email=="")){
			$this->setMessage("ERROR: Los Email no tienen un formato valido (nombre@tudominio.com)", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if(!$this->validarTelefono($telefono) || !$this->validarTelefono($telefono_titular) || ($telefono=="") || ($telefono_titular=="")){
			$this->setMessage("ERROR: Los Teléfonos solo pueden contener números", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if(!$this->validarNombre($ciudad_titular) || ($ciudad_titular=="") || !$this->validarNombre($departamento_titular) || ($departamento_titular=="")){
			$this->setMessage("ERROR: El nombre de la Ciudad y el Departamento no puede contener caracteres especiales", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if(!$this->validarDocumento($cedula) || !$this->validarDocumento($cedula_titular) || ($cedula=="") || ($cedula_titular=="")){
			$this->setMessage("ERROR: La Cédula solo puede contener números", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		if( (  ($anio_vencimiento == date('Y')) && ($mes_vencimiento<=date('m'))  )    || ($anio_vencimiento<date('Y'))   ){
			$this->setMessage("ERROR: La Fecha de expiración de su Tarjeta de crédito debe ser superior a la fecha actual", 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
			return;
		}

		//WEBSERVICES
		$transaccion_aprobada = true;
		$correo_tarjetahabiente = '';

		$soap_client = new soapclient($params->get('pagos_url_tc'),array('trace'=> true));
$header_part = '
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <wsse:UsernameToken>
            <wsse:Username>' . $params->get('pagos_usuario') . '</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
        </wsse:UsernameToken>
    </wsse:Security>
';
		$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
		$soap_header = new SoapHeader( $params->get('pagos_url_tc'), 'Security', $soap_var_header );
		$soap_client->__setSoapHeaders($soap_header);

		$envio = array('informacionTransaccion'=>array(
			   	'Compra' =>array(
			   		'referencia' => $referencia,
			   		// 'descripcion' => 'prueba',//opcional
			   		'valor'=>$precio,
			   		'isoMoneda'=>'COP',
			   		'numeroCuotas'=>12,
			   		'iva'=>0,
			   		'baseDevolucionIva'=>0
			   	),
			   	'Cliente' => array(
			   		'nombre'=>$nombres,
			   		'apellido'=>$apellidos,
			   		'documento'=>$cedula,
			   		'email'=>$email,
			   		'telefono'=>$telefono
			   		// 'telefonoOficina'=>'39292929'//opcional
			   	),
			   	'TarjetaHabiente'=>array(
			   		'nombre'=>$nombre_titular,
			   		'apellido'=>$apellidos_titular,
			   		'email'=>$email_titular,
			   		'telefono'=>$telefono_titular,
			   		// 'documento'=>'65631068',//opcional
			   		'pais'=>'CO',
			   		'estadoProvincia'=>$departamento_titular,
			   		'ciudad'=>$ciudad_titular,
			   		'direccion'=>$direccion_titular,
			   		'codigoPostal'=>'5700'
			   	),
			   	'TarjetaCredito'=>array(
			   		'franquicia'=> $franquicia,
			   		'numeroTarjeta'=>$numero_tarjeta,
			   		'mesVencimiento'=>$mes_vencimiento,
			   		'anoVencimiento'=>$anio_vencimiento,
			   		'codigoSeguridad'=>$codigo_tarjeta,
			   		'tipoCuenta'=>$tipo_cuenta
			   	),
			   	'InformacionFraude'=>array(
			   		'ipComprador'=>$_SERVER['REMOTE_ADDR'],
			   		// 'hostComprador'=>'localhost',//opcional
			   		'cookie'=>$session->get('idsesion'),
			   		'userAgent'=>$_SERVER['HTTP_USER_AGENT'],
			   		'deviceFingerPrint'=>$session->get('idsesion')
			   	),
			   	'productos'=>array(
			   		'producto'=>array(
				   		'codigoItem'=>$ruta,
				   		'nombreItem'=>'Tiquete Viaje',
				   		'valorItem'=>$precio,
				   		'cantidadItem'=>1,
				   		'codigoCategoria'=>3,
				   		'nombreCategoria'=>'Gacela'
			   		)
			   	)
			   ));	

				

			try{
				$result = $soap_client->transaccion($envio);// Se imprime la respuesta del web service
				$respuesta_transaccion = $result->respuestaTransaccion;
				$view = $this->getView('TiqueteForm','html');
				$view->confirmacion = $result;
				$view->display();
				$session->clear('fecha_salida');
				$session->clear('nombres');
				$session->clear('apellidos');
				$session->clear('cedula');
				$session->clear('telefono');
				$session->clear('email');
				$session->clear('usuario');
				$session->clear('nombre_titular');
				$session->clear('apellidos_titular');
				$session->clear('email_titular');
				$session->clear('cedula_titular');
				$session->clear('telefono_titular');
				$session->clear('departamento_titular');
				$session->clear('ciudad_titular');
				$session->clear('direccion_titular');
				$session->clear('ruta');
				$session->clear('fecha_salida');
				$session->clear('fecha_regreso');
				$session->clear('tipo_viaje');



				return;
				}catch (Exception $e){

				$this->setMessage("ERROR en la Transacción: ".$e->getMessage(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
					return;
				}





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
			$app->setUserState('com_flota.edit.tiquete.data', $jform, array());

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.tiquete.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.tiquete.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.tiquete.id');
			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.tiquete.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_SAVED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=tiquetes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.tiquete.data', null);
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
		$editId = (int) $app->getUserState('com_flota.edit.tiquete.id');

		// Get the model.
		$model = $this->getModel('TiqueteForm', 'FlotaModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=tiquetes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}

	public function remove()
	{

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('TiqueteForm', 'FlotaModel');

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
			$app->setUserState('com_flota.edit.tiquete.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.tiquete.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiquete&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->delete($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.tiquete.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.tiquete.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiquete&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.tiquete.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=tiquetes' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.tiquete.data', null);
	}

}
