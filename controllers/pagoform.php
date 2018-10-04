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
 * Pago controller class.
 */
class FlotaControllerPagoForm extends FlotaController
{

	public function display($cachable = false, $urlparams = false){
		$view = $this->getView( 'PagoForm', 'html' );
		$view->setModel( $this->getModel( 'Ruta' ), true );
		$view->display();
	}
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since    1.6
	 */
	public function edit(){
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_flota.edit.pago.id');
		$editId     = $app->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_flota.edit.pago.id', $editId);

		// Get the model.
		$model = $this->getModel('PagoForm', 'FlotaModel');

		// Check out the item
		if ($editId){
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId){
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 * @since    1.6
	 */
	public function save(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$seed = date('c');
        if (function_exists('random_bytes')) {
        $nonce = bin2hex(random_bytes(16));
      } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $nonce = bin2hex(openssl_random_pseudo_bytes(16));
      } else {
        $nonce = mt_rand();
      }
      
      $secretKey = '024h1IlD';
      
      $nonceBase64 = base64_encode($nonce);
      
      $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));
      
      $request = [
        'auth' => [
        'login' => '6dd490faf9cb87a9862245da41170ff2',
        'seed' => date('c'),
        'nonce' => $nonceBase64,
        'tranKey' => $tranKey,
        ],
      
       'buyer' => [
           'name' => 'John',
           'surname' => 'Doe',
           'email' => 'john.doe@example.com',
           'address' => [
               'city' => 'Bogotá',
               'street' => 'Calle 14 # 13b - 03'
           ]
       ],
       'payment' => [
           'reference' => '587548758',
           'description' => 'Testing payment',
           'amount' => [
                'currency' => 'COP',
                'total' => 10000
            ]
      
      ],
      
        'expiration' => date('c', strtotime('+2 days')),
        'returnUrl' => 'http://example.com/response?reference=',
        'ipAddress' => '127.0.0.1',
        'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
      ];
      
      //return $request;
      
      
      $url = 'https://test.placetopay.com/redirection/api/session';
      
      
      //Se inicia. el objeto CUrl
      $ch = curl_init($url);
      
      //creamos el json a partir del arreglo
      $jsonDataEncoded = json_encode($request);
      
      
      //Indicamos que nuestra petición sera Post
      curl_setopt($ch, CURLOPT_POST, 1);
      
      //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      
      //Adjuntamos el json a nuestra petición
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
      
      
        //Agregar los encabezados del contenido
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'User-Agent: cUrl Testing'));
      
      //Ejecutamos la petición
      $result = curl_exec($ch);
      $res= json_decode($result);
      header('Location: '.$res->processUrl);
      //print_r($res->processUrl); 

		$app       = JFactory::getApplication()->input;
		$model_p   = $this->getModel('PagoForm', 'FlotaModel');
		$model_p2  = $this->getModel('PagoForm', 'FlotaModel');
		$model_t   = JModelLegacy::getInstance('TiqueteForm', 'FlotaModel'); 
		$model_c   = JModelLegacy::getInstance('ClienteForm', 'FlotaModel'); 
		$titular   = JModelLegacy::getInstance('Titular', 'FlotaModel');
		$model_s   = JModelLegacy::getInstance('Silla', 'FlotaModel'); 
		$model_pu  = JModelLegacy::getInstance('Punto', 'FlotaModel');
		$model_r   = JModelLegacy::getInstance('Ruta', 'FlotaModel');
		$model_r2  = JModelLegacy::getInstance('Ruta', 'FlotaModel');  
		$session   = JFactory::getSession();
		$user      = JFactory::getUser();
		$cliente   = $model_c->getCliente($user->id); 
		$tiquete   = $session->get('tiquete');
		$formt     = $model_t->getForm();
		$forms     = $model_s->getForm();
		$formp     = $model_p->getForm();
		$formpu    = $model_pu->getForm();
		$pagos 	   = $model_p2->getPendientes($user->id);
		$theme 	   = ($tiquete["metodo_pago"]==2) ? '&layout=debito' : null;
		if(count($pagos)>0){
			$this->setMessage("En este momento su Orden #".$pagos[0]->id." presenta un proceso de pago cuya transacción se encuentra PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde para verificar si su pago fue confirmado de forma exitosa. Si desea mayor información sobre el estado actual de su operación puede comunicarse nuestras líneas de atención al cliente (1)5674567 - Celular: +573102199353 o al correo electrónico flotamagdalena.pagosonline@gmail.com y preguntar por el estado de la transacción: #".$pagos[0]->codigo_trazabilidad, 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
			return;
		}

		$puntos_totales   = 0;
		$descripcion_pago = '';

		#INFORMACION DE LAS SILLAS
		$sillas_ida     = explode(",", $tiquete['sillas_ida']);
		$sillas_regreso = explode(",", $tiquete['sillas_regreso']);

		#REGISTRO DEL PAGO
		$pago['id']	         = '';
   		$pago['clientes_id'] = $user->id;
		$pago['valor']       = $tiquete['total'];
		$pago['fecha']   	 = date('Y-m-d H:i:s');
		$pago['estados_id']  = 0;
		$pago['metodo_pago'] = $tiquete['metodo_pago'];
		$pago['subtotal']	 = $tiquete['subtotal'];
		$pago['bonos_id']	 = $tiquete['bonos_id'];
		$pago['descuento']   = $tiquete['descuento'];
		$pago['cupon']		 = $tiquete['cupon'];			

 		$Mopag 			 	 = $model_p->validate($formp, $pago);
		$PagSav 		 	 = $model_p->save($Mopag);

		#REGISTRO DE TIQUETE IDA
		$ObTiq['fecha'] 			= $tiquete['fecha_salida'];
		$ObTiq['ruta'] 				= $tiquete['ruta_ida'];
		$ObTiq['fecha_creacion'] 	= date('Y-m-d H:i:s');
		$ObTiq['usuario_creacion'] 	= $user->id;
		$ObTiq['clientes_id'] 		= $cliente['id'];
		$ObTiq['pagos_id'] 		    = $PagSav;
		$ObRuta		     			= $model_r->getData($tiquete['ruta_ida']);
		$puntos_totales				+= round($ObRuta->distancia/10);
		$descripcion_pago           .= 'Viaje Ida: '.$ObRuta->origen.' - '.$ObRuta->destino;
		
		$MoTiq  = $model_t->validate($formt, $ObTiq);
		$TiqSav = $model_t->save($MoTiq);

		#REGISTRO TIQUETE DE REGRESO SI EXISTE
		if($tiquete['ruta_regreso']!=null){
			$ObTiq2['fecha'] 			= $tiquete['fecha_regreso'];
			$ObTiq2['ruta'] 		    = $tiquete['ruta_regreso'];
			$ObTiq2['fecha_creacion'] 	= date('Y-m-d H:i:s');
			$ObTiq2['usuario_creacion'] = $user->id;
			$ObTiq2['clientes_id'] 		= $cliente['id'];
			$ObTiq2['pagos_id'] 		= $PagSav;
			
			$MoTiq2  		 = $model_t->validate($formt, $ObTiq2);
			$TiqSav2 		 = $model_t->save($MoTiq2);
			$ObRuta		     = $model_r2->getData($tiquete['ruta_regreso']);
			$puntos_totales	+= round($ObRuta->distancia/10);

			$descripcion_pago           .= ' Viaje Regreso: '.$ObRuta->origen.' - '.$ObRuta->destino;
		}

		//REGISTRO DE SILLA
		for($i=0; $i<$tiquete['pasajeros']; $i++){
			#SILLAS DE IDA
			$ObSilla['silla']       = $sillas_ida[$i];
			$ObSilla['tiquetes_id'] = $TiqSav;
 			$MoSill  				= $model_s->validate($forms, $ObSilla);
			$SillSav 				= $model_s->save($MoSill);

			#SILLAS DE REGRESO
			if($tiquete['ruta_regreso']!= null){
				$ObSilla2['silla']       = $sillas_regreso[$i];
				$ObSilla2['tiquetes_id'] = $TiqSav2;
	 			$MoSill2  				 = $model_s->validate($forms, $ObSilla2);
				$SillSav2 				 = $model_s->save($MoSill2);
			}
		}		

		$form_titular = $titular->getForm();
		//if (!$form){
	//		throw new Exception($model_p->getError(), 500);
//		}

		if (!$form_titular){
			throw new Exception($titular->getError(), 500);
		}

		
		
		$jinput = JFactory::getApplication()->input;

		//PROCESAMIENTO DE LA INFORMACION PARA LATAI
		//informacion de compra
		$referencia    = $PagSav;

		//informacion titular
			$nombre_titular 		= $info_titular['nombres'] = $tiquete["nombre_titular"] = $jinput->post->get('nombre_titular', '', 'STRING');
			$apellidos_titular 		= $info_titular['apellidos'] = $tiquete["apellidos_titular"] = $jinput->post->get('apellidos_titular', '', 'STRING');
			$email_titular			= $info_titular['email'] = $tiquete["email_titular"] = $jinput->post->get('email_titular', '', 'STRING');
			$cedula_titular 		= $info_titular['documento'] = $tiquete["cedula_titular"] = $jinput->post->get('cedula_titular', '', 'STRING');
			$tipo_documento_titular = $info_titular['tipo_documento'] = $tiquete["tipo_documento"] = $jinput->post->get('tipo_documento', '', 'STRING');
			$telefono_titular 		= $info_titular['telefono'] = $tiquete["telefono_titular"] = $jinput->post->get('telefono_titular', '', 'STRING');
			$departamento_titular 	= $info_titular['departamento'] = $tiquete["departamento_titular"] = $jinput->post->get('departamento_titular', '', 'STRING');
			$ciudad_titular 		= $info_titular['municipio'] = $tiquete["ciudad_titular"] = $jinput->post->get('ciudad_titular', '', 'STRING');
			$direccion_titular 		= $info_titular['direccion'] = $tiquete["direccion_titular"] = $jinput->post->get('direccion_titular', '', 'STRING');
			$info_titular['clientes_id'] = $cliente['id'];

			$session->set('tiquete', $tiquete);

			if($direccion_titular == ""){
				$this->setMessage("ERROR: Debe ingresar la Dirección del Titular", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

			if($departamento_titular == ""){
				$this->setMessage("ERROR: Debe ingresar el Departamento del Titular", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

			//VALIDACIONES
			if(!$this->validarNombre($nombre_titular) || !$this->validarNombre($apellidos_titular) || ($nombre_titular=="") || ($apellidos_titular=="")){
				$this->setMessage("ERROR: Los Nombres  y Apellidos solo pueden contener letras y espacios", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}	
				

			if(!$this->validarEmail($email_titular)  || ($email_titular=="")){
				$this->setMessage("ERROR: Los Email no tienen un formato valido (nombre@tudominio.com)", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

			if(!$this->validarTelefono($telefono_titular) || ($telefono_titular=="")){
				$this->setMessage("ERROR: Los Teléfonos solo pueden contener números", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

			if(!$this->validarNombre($ciudad_titular) || ($ciudad_titular=="") || !$this->validarNombre($departamento_titular) || ($departamento_titular=="")){
				$this->setMessage("ERROR: El nombre de la Ciudad y el Departamento no puede contener caracteres especiales", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

			if(!$this->validarDocumento($cedula_titular) || ($cedula_titular=="")){
				$this->setMessage("ERROR: La Cédula solo puede contener números", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
				return;
			}

		//SE GUARDAN LOS DATOS DEL TITULAR
		$ObTitular       = $titular->validate($form_titular, $info_titular);
		$return_titular  = $titular->save($ObTitular);

		if($tiquete['metodo_pago']==1){
			
			//informacion tarjeta
			$franquicia = $jinput->post->get('franquicia', '', 'INT');
			$numero_tarjeta = $jinput->post->get('numero_tarjeta', '', 'STRING');
			$mes_vencimiento = $jinput->post->get('mes_vencimiento', '', 'STRING');
			$anio_vencimiento = $jinput->post->get('anio_vencimiento', '', 'INT');
			$tipo_cuenta = $jinput->post->get('tipo_cuenta', 'C', 'CMD');
			$codigo_tarjeta = $jinput->post->get('codigo_tarjeta', '', 'INT');	

			if($numero_tarjeta == ""){
				$this->setMessage("ERROR: Debe ingresar un Número de Tarjeta en la información de pago", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform', false));
				return;
			}

			if($mes_vencimiento == ""){
				$this->setMessage("ERROR: Debe seleccionar un mes de vencimiento", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform', false));
				return;
			}

			if($anio_vencimiento == ""){
				$this->setMessage("ERROR: Debe seleccionar un año de vencimiento de su tarjeta", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform', false));
				return;
			}

			if($codigo_tarjeta == ""){
				$this->setMessage("ERROR: Debe ingresar un Codigo  de Verificación de su tarjeta valido", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform', false));
				return;
			}


			if( (  ($anio_vencimiento == date('Y')) && ($mes_vencimiento<=date('m'))  )    || ($anio_vencimiento<date('Y'))   ){
				$this->setMessage("ERROR: La Fecha de expiración de su Tarjeta de crédito debe ser superior a la fecha actual", 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform', false));
				return;
			}

			//WEBSERVICES
		

			$envio = array('informacionTransaccion'=>array(
				   	'Compra' =>array(
				   		'referencia' => $referencia,
				   		// 'descripcion' => 'prueba',//opcional
				   		'valor'=>$tiquete['total'],
				   		'isoMoneda'=>'COP',
				   		'numeroCuotas'=>12,
				   		'iva'=>0,
				   		'baseDevolucionIva'=>0
				   	),
				   	'Cliente' => array(
				   		'nombre'=>$cliente['nombre'],
				   		'apellido'=>$cliente['apellidos'],
				   		'documento'=>$cliente['documento'],
				   		'email'=>$user->email,
				   		'telefono'=>$cliente['telefono']
				   	),
				   	'TarjetaHabiente'=>array(
				   		'nombre'=>$nombre_titular,
				   		'apellido'=>$apellidos_titular,
				   		'email'=>$email_titular,
				   		'telefono'=>$telefono_titular,
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
				   		'ipComprador'=> $_SERVER['REMOTE_ADDR'],
				   		// 'hostComprador'=>'localhost',//opcional
				   		'cookie'=>$session->get('idsesion'),
				   		'userAgent'=>$_SERVER['HTTP_USER_AGENT'],
				   		'deviceFingerPrint'=>$session->get('idsesion')
				   	),
				   	'productos'=>array(
				   		'producto'=>array(
					   		'codigoItem'=>$PagSav,
					   		'nombreItem'=>'Tiquete Viaje',
					   		'valorItem'=>$tiquete['total'],
					   		'cantidadItem'=>1,
					   		'codigoCategoria'=>3,
					   		'nombreCategoria'=>'Gacela'
				   		)
				   	)
				   ));
		}else{
			$tipo_documento_titular = $jinput->post->get('tipo_documento', '', 'STRING');
			$banco = $jinput->post->get('banco', '', 'STRING');
			$component = $app->get('option');
			$params    = JComponentHelper::getParams($component);
			//$usuario_web_service 		= 'icapi';
			//$pass_web_service 	 		= 'qVPiCfJ8';
			//$wsdl 						= "https://secure.allegraplatform.com/GatewayIatai/PSE?WSDL";

			$soap_client = new soapclient($params->get('pagos_url_td'),array('trace'=> true));
			$header_part = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        		<wsse:UsernameToken>
	            		<wsse:Username>' . $params->get('pagos_usuario') . '</wsse:Username>
	            		<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
	        		</wsse:UsernameToken>
	    			</wsse:Security>';
			$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
			$soap_header     = new SoapHeader($params->get('pagos_url_td'), 'Security', $soap_var_header );
			$soap_client->__setSoapHeaders($soap_header);

			$envio = array('transaccionPSE'=>array(
				   	'CompraPSE' =>array(
				   		'referencia' => $referencia,
				   		 'descripcion' => substr($descripcion_pago, 0, 80),
				   		'valor'=>$tiquete['total'],
				   		'isoMoneda'=>'COP',
				   		'numeroCuotas'=>12,
				   		'iva'=>0,
				   		'baseDevolucionIva'=>0,
				   		'idBanco' => $banco,
				   		'urlRespuesta' => $params->get('pagos_respuesta')
				   	),
				   	'TitularCuentaPSE'=>array(
				   		'nombre'		=>$nombre_titular,
				   		'apellido'		=>$apellidos_titular,
				   		'email'			=>$email_titular,
				   		'telefono'		=>$telefono_titular,
				   		'documento' 	=> $cedula_titular,
				   		'pais'			=>'CO',
				   		'estadoProvincia'=>$departamento_titular,
				   		'ciudad'		=>$ciudad_titular,
				   		'direccion'		=>$direccion_titular,
				   		'codigoPostal'	=>'5700',
				   		'tipoDocumento' => $tipo_documento_titular,
				   		'usuarioNatural' => true
				   	),
				   	'InformacionFraude'=>array(
				   		'ipComprador'  => $_SERVER['REMOTE_ADDR'],
				   		// 'hostComprador'=>'localhost',//opcional
				   		'cookie'			=>$session->get('idsesion'),
				   		'userAgent'			=>$_SERVER['HTTP_USER_AGENT'],
				   		'deviceFingerPrint'	=>$session->get('idsesion')
				   	),
				   	'productos'=>array(
				   		'producto'=>array(
					   		'codigoItem'=>'codio454565',//$PagSav,
					   		'nombreItem'=>'Tiquete Viaje',
					   		'valorItem'=>'200700',//$tiquete['total'],
					   		'cantidadItem'=>1,
					   		'codigoCategoria'=>3,
					   		'nombreCategoria'=>'Gacela'
				   		)
				   	)
				   ));
		}
		try{

			if($tiquete['metodo_pago']==1){
				$result = $soap_client->transaccion($envio);
				$respuesta_transaccion = $result->respuestaTransaccion;

				$pagos['id_transaccion'] 		= $respuesta_transaccion->idTransaccion;
				$pagos['estados_id']		 	= $respuesta_transaccion->idEstado;
				$pagos['nombre_estado'] 		= $respuesta_transaccion->nombreEstado;
				$pagos['id']	            	= $respuesta_transaccion->referencia;
				$pagos['codigo_respuesta'] 		= $respuesta_transaccion->codigoRespuesta;
				$pagos['codigo_autorizacion'] 	= $respuesta_transaccion->codigoAutorizacion;
				$pagos['fecha_procesamiento'] 	= $respuesta_transaccion->fechaProcesamiento;
				$pagos['mensaje'] 			  	= $respuesta_transaccion->mensaje;
				$pagos['ip'] 			  		= $_SERVER['REMOTE_ADDR'];

				$formp2 = $model_p->getForm();
				$Mopag2 = $model_p->validate($formp2, $pagos);
				$PagSav = $model_p->save($Mopag2);

				if($respuesta_transaccion->idEstado==1){
					$puntos['clientes_id'] = $user->id;
					$puntos['puntos']	   = $puntos_totales;
					$puntos['estado']	   = 1;
					$puntos['fecha']	   = date('Y-m-d');
					$Mopun 	= $model_pu->validate($formpu, $puntos);
					$PunSav = $model_pu->save($Mopun);
				}

			}elseif($tiquete['metodo_pago']==2){
				$model_p  				   = $this->getModel('PagoForm', 'FlotaModel');
				$formp2   				   = $model_p->getForm();
				$result 				   = $soap_client->crearTransaccionPSE($envio);
				$respuesta_transaccion 	   = $result->respuestaTransaccionPSE;
				$tiquete['id_transaccion'] = $respuesta_transaccion->idTransaccion;
				$tiquete['referencia']     = $respuesta_transaccion->referencia;
				$tiquete['direccion_ip']   = $_SERVER['REMOTE_ADDR'];
				$tiquete['puntos_totales'] = $puntos_totales;
				$session->set('tiquete', $tiquete);

				//OBTENGO LA RESPUESTA DE LA CREACION DE LA TRANSACCION
				$pagos['id_transaccion'] 		= $respuesta_transaccion->idTransaccion;
				$pagos['estados_id']		 	= $respuesta_transaccion->idEstado;
				$pagos['id']	            	= $respuesta_transaccion->referencia;
				$pagos['codigo_respuesta'] 		= $respuesta_transaccion->codigoRespuesta;
				$pagos['codigo_trazabilidad'] 	= $respuesta_transaccion->codigoTrazabilidad;
				$pagos['nombre_estado'] 		= $respuesta_transaccion->nombreEstado;
				$pagos['fecha_procesamiento'] 	= $respuesta_transaccion->fechaProcesamiento;
				$pagos['mensaje'] 			  	= $respuesta_transaccion->mensaje;
				$pagos['nombre_banco'] 			= $respuesta_transaccion->bancoPSERespuesta->nombre;
				$pagos['ip'] 			  		= $respuesta_transaccion->ip;
				$Mopag2  						= $model_p->validate($formp2, $pagos);
				$PagSav   						= $model_p->save($Mopag2);

				//echo '<pre>';
				//echo print_r($respuesta_transaccion,true);
				//echo '</pre>';
				
				if(!$respuesta_transaccion->bancoPSERespuesta->url){
					$this->setMessage("No se pudo crear la transacción, por favor intente mas tarde o comuniquese nuestras líneas de atención al cliente al Teléfono: (1)5674567 - Celular: +573216134945 o al correo electrónico reservas@flotamagdalena.com", 'warning');
					$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform&layout=debito', false));
					return;
				}
				if($respuesta_transaccion->nombreEstado=="RECHAZADA" || $respuesta_transaccion->nombreEstado == "FALLIDA"){
					if($respuesta_transaccion->codigoRespuesta=="FAIL_EXCEEDEDLIMIT"){
						$this->setMessage("El monto de la transacción excede los limites establecidos en PSE para la empresa, por favor comuníquese con nuestras líneas de atención al cliente al Teléfono: (1)5674567 - Celular: +573102199353 o al correo electrónico flotamagdalena.pagosonline@gmail.com", 'warning');
						$this->setRedirect(JRoute::_('index.php?option=com_flota&task=pagoform.confirmacion', false));
						return;
					}else{
						$this->setMessage("No se pudo crear la transacción, por favor intente mas tarde o comuniquese nuestras líneas de atención al cliente al Teléfono: (1)5674567 - Celular: +573102199353 o al correo electrónico flotamagdalena.pagosonline@gmail.com", 'warning');
						$this->setRedirect(JRoute::_('index.php?option=com_flota&task=pagoform.confirmacion', false));
						return;
					}
				}
				$this->setRedirect($respuesta_transaccion->bancoPSERespuesta->url);
			}
			
			#DATOS PARA ENVIAR NOTIFICACION A LA EMPRESA
			$email['cliente']       = $ObTiq['clientes_id'];
			$email['pago']	    	= $ObTiq['pagos_id'];
			$email['tiquete_ida']   = $TiqSav;
			$email['tiquete_reg']   = $TiqSav2;
			$this->notificarEmpresa($email);

			$view = $this->getView('PagoForm','html');
			$view->confirmacion = $result;
			$view->display();
			return;
		}catch (Exception $e){
			$this->setMessage("ERROR en la Transacción: ".$e->getMessage(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform'.$theme, false));
			return;
		}
	}

	public function cupon(){
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app      = JFactory::getApplication()->input;
		$session  = JFactory::getSession();
		$user     = JFactory::getUser();
		$tiquete  = $session->get('tiquete');
		$model_p  = $this->getModel('PagoForm', 'FlotaModel');
		$descuento_ida = 0;
		$descuento_regreso = 0;
		$meses = array("Sin mes","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

		$bono     = $app->post->get('cupon', '', 'STRING');
		$ObBonoSalida    = $model_p->getDescuento($bono, $tiquete['fecha_salida']);
		$ObBonoRegreso   = ($tiquete['tipo_viaje']==2) ? $model_p->getDescuento($bono, $tiquete['fecha_regreso']) : null;
		
		if($ObBonoSalida && $this->validarAlta($tiquete['ruta_ida'],$tiquete['fecha_salida'])){
			$descuento_ida  = ($tiquete['total_ida']*$ObBonoSalida->descuento)/100;
		}

		if($ObBonoRegreso && $this->validarAlta($tiquete['ruta_regreso'],$tiquete['fecha_regreso'])){
			$descuento_regreso = ($tiquete['total_reg']*$ObBonoRegreso->descuento)/100;		
		}

		if(($descuento_ida>0)||($descuento_regreso>0)){
			$tiquete['total'] 		= $tiquete['subtotal'] - $descuento_ida - $descuento_regreso;
			$tiquete['bonos_id'] 	= $ObBonoSalida->id;
			$tiquete['descuento'] 	= $descuento_ida + $descuento_regreso;
			$tiquete['cupon']	  	= $bono;
			$session->set('tiquete', $tiquete);
			$fecha_inicial  = date('j', strtotime($ObBonoSalida->fecha_inicial)).' '.$meses[date('n', strtotime($ObBonoSalida->fecha_inicial))].', '.date('Y', strtotime($ObBonoSalida->fecha_inicial));
			$fecha_final    = date('j', strtotime($ObBonoSalida->fecha_final)).' '.$meses[date('n', strtotime($ObBonoSalida->fecha_final))].', '.date('Y', strtotime($ObBonoSalida->fecha_final));
			$this->setMessage('El Cupón  "'.$bono.'" te ha aplicado un descuento exitosamente del '.$ObBonoSalida->descuento.'% en cada viaje que realices entre el '.$fecha_inicial.' y el '.$fecha_final);
		}else{
			$this->setMessage('El Cupón  "'.$bono.'" no es valido');
		}
		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=tiqueteform', false));
		return;
	}

	public function validarAlta($id, $fecha){
		$model_r   = JModelLegacy::getInstance('Rutas', 'FlotaModel');
		$ObRuta	= $model_r->getRuta($id, $fecha);

		if(($ObRuta->precio_especial>0)||(date("N", strtotime($fecha))>=5)){
            return  true;
        }
        return false;
	}

	public function confirmacion(){
		$session  = JFactory::getSession();
		$user     = JFactory::getUser();
		$tiquete  = $session->get('tiquete');

		$model_p  = $this->getModel('PagoForm', 'FlotaModel');
		$model_pu = JModelLegacy::getInstance('Punto', 'FlotaModel');
		$formp2   = $model_p->getForm();
		$formpu   = $model_pu->getForm();

		$app       = JFactory::getApplication()->input;
		$component 		= $app->get('option');
		$params 		= JComponentHelper::getParams($component);

		$soap_client 		 = new soapclient($params->get('pagos_url_td'),array('trace'=> true));
		$header_part 		 = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        		<wsse:UsernameToken>
	            		<wsse:Username>' .$params->get('pagos_usuario') . '</wsse:Username>
	            		<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
	        		</wsse:UsernameToken>
	    			</wsse:Security>';
		$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
		$soap_header     = new SoapHeader($params->get('pagos_url_td'), 'Security', $soap_var_header );
		$soap_client->__setSoapHeaders($soap_header);
		//var_dump($tiquete);
		try {
			$envio 	= array('informacionConsulta'=>array('idTransaccion' => $tiquete['id_transaccion'],'referencia' => $tiquete['referencia']));
			$result = $soap_client->consultarTransaccionPSE($envio);
			$respuesta_transaccion = $result->respuestaTransaccionPSE;
			//echo '<pre>';
			//var_dump($respuesta_transaccion);
			//echo '</pre>';

			$pagos['id_transaccion'] 		= $respuesta_transaccion->idTransaccion;
			$pagos['estados_id']		 	= $respuesta_transaccion->idEstado;
			$pagos['id']	            	= $respuesta_transaccion->referencia;
			$pagos['codigo_respuesta'] 		= $respuesta_transaccion->codigoRespuesta;
			$pagos['codigo_autorizacion'] 	= $respuesta_transaccion->codigoAutorizacion;
			$pagos['codigo_trazabilidad'] 	= $respuesta_transaccion->codigoTrazabilidad;
			$pagos['nombre_estado'] 		= $respuesta_transaccion->nombreEstado;
			$pagos['fecha_procesamiento'] 	= $respuesta_transaccion->fechaProcesamiento;
			$pagos['mensaje'] 			  	= $respuesta_transaccion->mensaje;
			$pagos['nombre_banco'] 			= $respuesta_transaccion->bancoPSERespuesta->nombre;
				$pagos['ip'] 			  	= $_SERVER['REMOTE_ADDR'];

			//echo '<pre>';
			//var_dump($pagos);
			//echo '</pre>';
			$Mopag2   = $model_p->validate($formp2, $pagos);
			$PagSav   = $model_p->save($Mopag2);

			if($respuesta_transaccion->idEstado==1){
				$puntos['clientes_id'] = $user->id;
				$puntos['puntos']	   = $tiquete['puntos_totales'];
				$puntos['estado']	   = 1;
				$puntos['fecha']	   = date('Y-m-d');
				$Mopun 	= $model_pu->validate($formpu, $puntos);
				$PunSav = $model_pu->save($Mopun);
			}
		} catch (Exception $e) {
			$this->setMessage("ERROR en la Transacción: ".$e->getMessage(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform&layout=confirmacion', false));
			return;
		}

		$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pagoform&layout=confirmacion', false));
		return;
	}

	public function actualizar(){
		$model_p  			 = $this->getModel('PagoForm', 'FlotaModel');
		$fecha_actual		 = date('Y-m-d H:i:s');
		$pagos 				 = $model_p->getPendientes();

		$app            = JFactory::getApplication()->input;
		$component 		= $app->get('option');
		$params 		= JComponentHelper::getParams($component);

		$soap_client 		 = new soapclient($params->get('pagos_url_td'),array('trace'=> true));
		$header_part 		 = '<wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	        		<wsse:UsernameToken>
	            		<wsse:Username>' . $params->get('pagos_usuario') . '</wsse:Username>
	            		<wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $params->get('pagos_key') . '</wsse:Password>
	        		</wsse:UsernameToken>
	    			</wsse:Security>';
		$soap_var_header = new SoapVar( $header_part, XSD_ANYXML, null, null, null );
		$soap_header     = new SoapHeader($params->get('pagos_url_td'), 'Security', $soap_var_header );
		$soap_client->__setSoapHeaders($soap_header);
		foreach ($pagos as $item) {
			$envio 	= array('informacionConsulta'=>array('idTransaccion' => $item->id_transaccion,'referencia' => $item->id));
			$result = $soap_client->consultarTransaccionPSE($envio);
			$respuesta_transaccion = $result->respuestaTransaccionPSE;

			$model_p  = $this->getModel('PagoForm', 'FlotaModel');
			$formp2   = $model_p->getForm();
			$minutos  = ceil((strtotime($fecha_actual) - strtotime($item->fecha)) / 60);
			//if($minutos <= 21){
				$pagos['id']	            	= $respuesta_transaccion->referencia;
				$pagos['estados_id']		 	= $respuesta_transaccion->idEstado;
				$pagos['codigo_respuesta'] 		= $respuesta_transaccion->codigoRespuesta;
				$pagos['fecha_procesamiento'] 	= $respuesta_transaccion->fechaProcesamiento;
				$pagos['mensaje'] 			  	= $respuesta_transaccion->mensaje;
				$pagos['nombre_estado'] 		= $respuesta_transaccion->nombreEstado;
			//}else{
			//	$pagos['id']	            	= $respuesta_transaccion->referencia;
			//	$pagos['estados_id']		 	= 3;
			//	$pagos['codigo_respuesta'] 		= 'NOT_AUTHORIZED';
			//	$pagos['nombre_estado'] 		= 'RECHAZADA';
			//}

			
			
			//$pagos['codigo_autorizacion'] 	= ($respuesta_transaccion->codigoAutorizacion) ? $respuesta_transaccion->codigoAutorizacion : null;

			$Mopag2   = $model_p->validate($formp2, $pagos);
			$PagSav   = $model_p->save($Mopag2);

			#DATOS PARA ENVIAR NOTIFICACION A LA EMPRESA
			$email['pago']	    	= $pagos['id'];
			$this->notificarActualizacion($email);
		}
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
		$editId = (int) $app->getUserState('com_flota.edit.pago.id');

		// Get the model.
		$model = $this->getModel('PagoForm', 'FlotaModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=pagos' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}

	public function notificarEmpresa($datos = array()){
		#PARAMETROS BASICOS
		$app         = JFactory::getApplication()->input;
		
		$model_p   = $this->getModel('PagoForm', 'FlotaModel');
		$model_t   = JModelLegacy::getInstance('TiqueteForm', 'FlotaModel'); 
		$model_t2   = JModelLegacy::getInstance('TiqueteForm', 'FlotaModel'); 
		$model_c   = JModelLegacy::getInstance('ClienteForm', 'FlotaModel'); 
		$model_c2   = JModelLegacy::getInstance('ClienteForm', 'FlotaModel'); 
		$model_s   = JModelLegacy::getInstance('Silla', 'FlotaModel'); 
		$model_r   = JModelLegacy::getInstance('Ruta', 'FlotaModel');
		$model_s2   = JModelLegacy::getInstance('Silla', 'FlotaModel'); 
		$model_r2   = JModelLegacy::getInstance('Ruta', 'FlotaModel');

		$ObCliente   = $model_c->getData($datos["cliente"]);
		$ObCliente2  = $model_c2->getCliente($ObCliente->usuario);
		$ObPago      = $model_p->getPago($datos["pago"]);
		$ObTiquete   = $model_t->getData($datos["tiquete_ida"]);
		$ObSillas   = $model_s->getSillas($datos["tiquete_ida"]);
		$ObTiquete2 = $model_t2->getData($datos["tiquete_reg"]);
		$ObSillas2  = $model_s2->getSillas($datos["tiquete_reg"]);
		$ObRutaIda  = $model_r->getData($ObTiquete->ruta);
		$ObRutaReg  = $model_r2->getData($ObTiquete2->ruta);

		$metodos_pago = array("1"=>"Tarjeta de Crédito","2"=>"Tarjeta Débito","3"=>"Puntos Flota");

		foreach ($ObSillas as $value) {
			$sillas1[] = $value->silla;
		}

		foreach ($ObSillas2 as $value) {
			$sillas2[] = $value->silla;
		}

		$component 	 = $app->get('option');
		$params 	 = JComponentHelper::getParams($component);
		$destino     = explode(",", $params->get('email_notificacion'));
		$recipients  = $destino; 
		$asunto      = ' Reserva Tiquetes [Pago #'.$ObPago->id_transaccion.']  ';
		$asunto2     = ' Información de tu Tiquete [Pago #'.$ObPago->id_transaccion.']  ';
		$mensaje     = '';

		$mensaje .= '<table align="center" cellspacing="0" cellpadding="5" width="500px" style="border:solid 1px #ccc">';
		$mensaje .= '<tr><td colspan="2" align="center" style="background-color: #eb5d1f;color:#fff;">DATOS DE LA TRANSACCION</td></tr>';
		$mensaje .= '<tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Nit</strong></td><td>9005249126</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Razón Social</strong></td><td>ALIANZA NACIONAL DE CAPITALES SAS</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Número de Factura</strong></td>';
		$mensaje .= '<td>'.$ObPago->id_transaccion.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Referencia</strong></td>';
		$mensaje .= '<td>'. $ObPago->id.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Código Único de Seguimiento</strong></td>';
		$mensaje .= '<td>'.$ObPago->codigo_trazabilidad.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Fecha de la Transacción</strong></td>';
		$mensaje .= '<td>'.$ObPago->fecha_transaccion.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Metodo de Pago</strong></td>';
		$mensaje .= '<td>'.$metodos_pago[$ObPago->metodo_pago].'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Estado</strong></td>';
		$mensaje .= '<td>'.$ObPago->nombre_estado.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Dirección IP</strong></td>';
		$mensaje .= '<td>'.$ObPago->ip.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Total</strong></td>';
		$mensaje .= '<td>$'.$ObPago->valor.'</td>';
		$mensaje .= '</tr>';
		$mensaje .= '</table>';
		
		$mensaje .= '<table align="center"  cellspacing="0" cellpadding="5" width="500px" style="border:solid 1px #ccc; margin-top:10px;">';
		$mensaje .= '<tr>';
		$mensaje .= '<td colspan="2" align="center"  style="background-color: #eb5d1f;color:#fff;"><strong>DETALLES DE LA COMPRA</strong></td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td colspan="2"  style="font-size: 13px;text-align: left;"><strong>Viaje Ida</strong></td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Ruta</strong></td>';
		$mensaje .= '<td>'.$ObRutaIda->origen.' - '.$ObRutaIda->destino.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>No de Pasajeros</strong></td>';
		$mensaje .= '<td>'. count($sillas1).'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Sillas</strong></td>';
		$mensaje .= '<td>'.implode(",", $sillas1).'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Fecha</strong></td>';
		$mensaje .= '<td>'.$ObTiquete->fecha.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Hora</strong></td>';
		$mensaje .= '<td>'.$ObRutaIda->hora.'</td>';
		$mensaje .= '</tr>';
		
		
		if($ObTiquete2->fecha!=null):
			$mensaje .= '<tr>';
			$mensaje .= '<td colspan="2" ><strong>Viaje Regreso</strong></td>';
			$mensaje .= '</tr><tr>';
			$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Ruta</strong></td>';
			$mensaje .= '<td>'.$ObRutaReg->origen.' - '.$ObRutaReg->destino.'</td>';
			$mensaje .= '</tr><tr>';
			$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>No de Pasajeros</strong></td>';
			$mensaje .= '<td>'. count($sillas2).'</td>';
			$mensaje .= '</tr><tr>';
			$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Sillas</strong></td>';
			$mensaje .= '<td>'.implode(",", $sillas2).'</td>';
			$mensaje .= '</tr><tr>';
			$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Fecha</strong></td>';
			$mensaje .= '<td>'.$ObTiquete2->fecha.'</td>';
			$mensaje .= '</tr><tr>';
			$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Hora</strong></td>';
			$mensaje .= '<td>'.$ObRutaReg->hora.'</td>';
			$mensaje .= '</tr>';
		 endif;

		 $mensaje .= '</table>';

		$mensaje .= '<table align="center" cellspacing="0" cellpadding="5" width="500px" style="border:solid 1px #ccc">';
		$mensaje .= '<tr><td colspan="2" align="center" style="background-color: #eb5d1f;color:#fff;">DATOS DEL CLIENTE</td></tr>';
		$mensaje .= '<tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Nombres</strong></td>';
		$mensaje .= '<td>'.$ObCliente->nombre.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Apellidos</strong></td>';
		$mensaje .= '<td>'.$ObCliente->apellidos.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Teléfono</strong></td>';
		$mensaje .= '<td>'.$ObCliente->telefono.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Dirección</strong></td>';
		$mensaje .= '<td>'.$ObCliente->direccion.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>No de Documento</strong></td>';
		$mensaje .= '<td>'.$ObCliente->documento.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Celular</strong></td>';
		$mensaje .= '<td>'.$ObCliente->celular.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Email</strong></td>';
		$mensaje .= '<td>'.$ObCliente2['email'].'</td>';
		$mensaje .= '</tr>';
		$mensaje .= '</table>';
			

		#ENVIO DE MENSAJE
		$mail    = JFactory::getMailer();
		$mail->addRecipient($recipients);
		$mail->setSubject($asunto);
		$mail->setBody($mensaje);
		$mail->IsHTML(True);
		$mail->setBody(nl2br($mensaje));
		$sent = $mail->Send();

		$mail2    = JFactory::getMailer();
		$mail2->addRecipient($ObCliente2['email']);
		$mail2->setSubject($asunto2);
		$mail2->setBody($mensaje);
		$mail2->IsHTML(True);
		$mail2->setBody(nl2br($mensaje));
		$sent2 = $mail->Send();
	}

	public function notificarActualizacion($datos = array()){
		#PARAMETROS BASICOS
		$app         = JFactory::getApplication()->input;
		
		$model_p   = $this->getModel('PagoForm', 'FlotaModel');
		$ObPago     = $model_p->getPago($datos["pago"]);

		$component 	 = $app->get('option');
		$params 	 = JComponentHelper::getParams($component);
		$destino     = explode(",", $params->get('email_notificacion'));
		$recipients  = $destino; 
		$asunto      = ' Actualización Estado [Pago #'.$ObPago->id_transaccion.']  ';
		$mensaje     = '';

		$mensaje .= '<table align="center" cellspacing="0" cellpadding="5" width="500px" style="border:solid 1px #ccc">';
		$mensaje .= '<tr><td colspan="2" align="center" style="background-color: #eb5d1f;color:#fff;">DATOS DE LA TRANSACCION</td></tr>';
		$mensaje .= '<tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;width:200px;"><strong>Nit</strong></td><td>9005249126</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Razón Social</strong></td><td>ALIANZA NACIONAL DE CAPITALES SAS</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Número de Factura</strong></td>';
		$mensaje .= '<td>'.$ObPago->id_transaccion.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Referencia</strong></td>';
		$mensaje .= '<td>'. $ObPago->id.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Código Único de Seguimiento</strong></td>';
		$mensaje .= '<td>'.$ObPago->codigo_trazabilidad.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Fecha de la Transacción</strong></td>';
		$mensaje .= '<td>'.$ObPago->fecha_transaccion.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Estado</strong></td>';
		$mensaje .= '<td>'.$ObPago->nombre_estado.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Dirección IP</strong></td>';
		$mensaje .= '<td>'.$ObPago->ip.'</td>';
		$mensaje .= '</tr><tr>';
		$mensaje .= '<td style="font-size: 13px;text-align: left;"><strong>Total</strong></td>';
		$mensaje .= '<td>$'.$ObPago->valor.'</td>';
		$mensaje .= '</tr>';
		$mensaje .= '</table>';			

		#ENVIO DE MENSAJE
		$mail    = JFactory::getMailer();
		$mail->addRecipient($recipients);
		$mail->setSubject($asunto);
		$mail->setBody($mensaje);
		$mail->IsHTML(True);
		$mail->setBody(nl2br($mensaje));
		$sent = $mail->Send();
	}

	public function remove()
	{

		// Initialise variables.
		$app   = JFactory::getApplication();
		$model = $this->getModel('PagoForm', 'FlotaModel');

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
			$app->setUserState('com_flota.edit.pago.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.pago.id');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pago&layout=edit&id=' . $id, false));

			return false;
		}

		// Attempt to save the data.
		$return = $model->delete($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_flota.edit.pago.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_flota.edit.pago.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_flota&view=pago&layout=edit&id=' . $id, false));

			return false;
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_flota.edit.pago.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_FLOTA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_flota&view=pagos' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_flota.edit.pago.data', null);
	}

}