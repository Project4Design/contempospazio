<?
if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Configuracion{
	private $rh;
	private $nivel;

	public function __CONSTRUCT()
	{
		$this->rh    = new ResponseHelper();
		$this->nivel = isset($_SESSION['nivel'])?$_SESSION['nivel']:"X";
	}

	public function consulta()
	{
		$query = Query::run("SELECT * FROM configuracion");
		$return = (object)$query->fetch_array(MYSQLI_ASSOC);

		return $return;
	}

	public function get_labor(){
		$query = Query::run("SELECT config_regular_work,config_big_work FROM configuracion WHERE id_config = 1");
		$data = (object) $query->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	public function edit($tax,$earnings,$rwork,$bwork,$discount,$delivery,$shipment)
	{
		if($this->nivel=="A"){
			$query = Query::prun("UPDATE configuracion SET
															config_tax          = ?,
															config_earnings     = ?,
															config_regular_work = ?,
															config_big_work     = ?,
															config_discount     = ?,
															config_delivery     = ?,
															config_shipment     = ?
														WHERE id_config = ?",
														array("sssssssi",$tax,$earnings,$rwork,$bwork,$discount,$delivery,$shipment,1));

			if($query->response){
				$this->rh->setResponse("mod","CHanges has been saved.");
			}else{
				$this->rh->setResponse(false,"An error has occurred.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function labor($val)
	{
		$labor = $this->get_labor();
		switch ($val){
			case '0':
				$return = $labor->config_regular_work;	
			break;
			case '1':
				$return = $labor->config_big_work;
			break;
		}

		return $return;
	}
}//Class Configuracion

// Logica
$modelConfiguracion = new Configuracion();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'configuracion':
				$tax      = $_POST['tax'];
				$earnings = $_POST['earnings'];
				$rwork     = $_POST['regular_work'];
				$bwork    = $_POST['big_work'];
				$discount = $_POST['discount'];
				$delivery = $_POST['delivery'];
				$shipment = $_POST['shipment'];

				$modelConfiguracion->edit($tax,$earnings,$rwork,$bwork,$discount,$delivery,$shipment);
			break;
		endswitch;
	endif;
endif;

?>