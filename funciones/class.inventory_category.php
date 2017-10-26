<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Inventory_category{
	private $rh;
	private $user;
	private $nivel;
	private $fecha;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
		$this->fecha  = Base::Fecha();
	}

  public function consulta()
  {
  	$query = Query::run("SELECT * FROM inventory_category");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================


}//Class Products

$modelInventoyCat = new Inventory_category();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
		endswitch;
	endif;
endif;
?>