<?php
class ResponseHelper
{
	public $response = false;
	public $msj      = 'An unexpected error has occurred.';
	public $redirect = '';
	public $error    = 0;
	public $reload   = false;
	public $data     = null;
	
	public function setResponse($response, $msg = '', $reload = false, $redirect = '')
	{
		if(!$response && $msg == ''){ $this->msj = 'Unexpected error.';}
		else { $this->msj = $msg; }

		if($reload){ $this->reload = true; }
		if($redirect != '') { $this->redirect = BASE_URL.$redirect; }

		$this->response = $response;
	}
}