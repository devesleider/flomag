<?php 

	$idTransaccion = $_GET['referencia']; 
	$session  = JFactory::getSession();
	$user     = JFactory::getUser();
	$tiquete  = $session->get('tiquete');
	$model_p  = $this->getModel('PagoForm', 'FlotaModel');

	$data 		= 	$model_p->getPago($idTransaccion);
	$requestId 	=	$data->id_transaccion;
	$model_pu = JModelLegacy::getInstance('Punto', 'FlotaModel');
	$formp2   = $model_p->getForm();
	$formpu   = $model_pu->getForm();

	$model_p  			 = $this->getModel('PagoForm', 'FlotaModel');
	$fecha_actual		 = date('Y-m-d H:i:s');
	$pagos 				 = $model_p->getPendientes();
	$login				= $model_p->getlogin();
	$app            = JFactory::getApplication()->input;
	$component 		= $app->get('option');
	$params 		= JComponentHelper::getParams($component);
	
	if (function_exists('random_bytes')) {
	$nonce = bin2hex(random_bytes(16));
	} elseif (function_exists('openssl_random_pseudo_bytes')) {
		$nonce = bin2hex(openssl_random_pseudo_bytes(16));
	} else {
		$nonce = mt_rand();
	}

	$secretKey 		= $model_p->gettrankey();
	$nonceBase64 	= base64_encode($nonce);


	foreach ($pagos as $item) {
		$seed = date('c');
		$tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));

		$request = [
		'auth' => [
			'login' => $login,
			'seed' => $seed,
			'nonce' => $nonceBase64,
			'tranKey' => $tranKey,
			],
		];

		$url = 'https://test.placetopay.com/redirection/api/session/'.$requestId;
	
	
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
		$result_Json = curl_exec($ch);
		//var_dump($tiquete);
		$result = json_decode($result_Json);

		$respuesta_transaccion 	   = $result;
		//OBTENGO LA RESPUESTA DE LA CREACION DE LA TRANSACCION
		$pagos['id_transaccion'] 		= $respuesta_transaccion->requestId;
		$pagos['estados_id']		 	= '2';
		$pagos['id']	            	= $data->id;
		$pagos['codigo_respuesta'] 		= $respuesta_transaccion->status->reason;
		$pagos['codigo_trazabilidad'] 	= $respuesta_transaccion->status->reason;
		$pagos['nombre_estado'] 		= $respuesta_transaccion->payment[0]->status->status;
		$pagos['fecha_procesamiento'] 	= $respuesta_transaccion->status->date;
		$pagos['mensaje'] 			  	= $respuesta_transaccion->payment[0]->status->message;
		$pagos['nombre_banco'] 			= "Defecto";
		$pagos['ip'] 			  		= $_SERVER['REMOTE_ADDR'];
		$pagos['referencia'] 			= $referencia;
		$formp2 						= $model_p->getForm();
		$Mopag2  						= $model_p->validate($formp2, $pagos);
		$PagSav   						= $model_p->save($Mopag2);
		print_r($PagSav); 

	}
?>