<?

if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Funciones{
	private $rh;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
	}

}//Class Funciones

// Logica
$modelFunciones = new Funciones();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			default:
			break;
		endswitch;
	endif;
endif;

?>