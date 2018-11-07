<?php
	/**
	 * 
	 */
	class googleAut
	{
		protected $client;
		 
		public function __construct(Google_Client $googleClient = null)
		{
			$this->client = $googleClient;
			if ($this->client) {
				$this->client->setClientId('806836229686-7u2l0qaho040fhs4t8dutbc5obl4r40v.apps.googleusercontent.com');
				$this->client->setClientSecret('NlzOnq0-VOn-CCul5ulQo7Rz');
				$this->client->setRedirectUri('https://flotamagdalena.com/component/flota/tiqueteform');
				$this->client->setScopes('email');
			}

		}

		public function isLoggedIn(){
			return isset($_SESSION['access_token']);
		}

		public function getUrl(){
			return $this->client->createAuthUrl();
		}

		public function checkRedirectCode(){
			if (isset($_GET['code'])) {
				$this->client->authenticate($_GET['code']);
				$this->setToken($this->client->getAccessToken());
				$res = $this->getPayLoad();
				echo "<pre>";
				print_r($res);
				echo "<pre>";
				return true;
			}
			return false;
		}

		public function setToken($token){
			$_SESSION['access_token'] = $token;
			$this->client->setAccessToken($token);
		}

		public function logout(){
			unset($_SESSION['access_token']);
		}

		public function getPayLoad(){
			try{
				$payload = $this->client->getBasicProfile();
			}catch(Exception $e) {
    			$payload = 'Excepción capturada: '. $e->getMessage();

			}
			return $payload;
			
		}

	}
?>


