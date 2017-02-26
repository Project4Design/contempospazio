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
		$query = Query::run("SELECT * FROM configuracion WHERE id_config = 1 LIMIT 1");
		$return = (object)$query->fetch_array(MYSQLI_ASSOC);

		return $return;
	}

	public function get_labor(){
		$query = Query::run("SELECT config_regular_work,config_big_work FROM configuracion WHERE id_config = 1 LIMIT 1");
		$data = (object) $query->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	public function edit($tax,$earnings_cab,$rwork,$bwork,$discount,$delivery,$earnings_tops,$earnings_sinks,$shipment)
	{
		if($this->nivel=="A"){
			$query = Query::prun("UPDATE configuracion SET
															config_tax            = ?,
															config_earnings_cab   = ?,
															config_regular_work   = ?,
															config_big_work       = ?,
															config_discount       = ?,
															config_delivery       = ?,
															config_earnings_sinks = ?,
															config_earnings_tops  = ?,
															config_shipment       = ?
														WHERE id_config = ?",
														array("dddddddddi",$tax,$earnings_cab,$rwork,$bwork,$discount,$delivery,$earnings_sinks,$earnings_tops,$shipment,1));

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
			case '2':
				$return = 0;
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
				$earnings_cab = $_POST['earnings_cab'];
				$rwork     = $_POST['regular_work'];
				$bwork    = $_POST['big_work'];
				$discount = $_POST['discount'];
				$delivery = $_POST['delivery'];
				$earnings_sinks = $_POST['earnings_sinks'];
				$earnings_tops = $_POST['earnings_tops'];
				$shipment = $_POST['shipment'];

				$modelConfiguracion->edit($tax,$earnings_cab,$rwork,$bwork,$discount,$delivery,$earnings_tops,$earnings_sinks,$shipment);
			break;
		endswitch;
	endif;
endif;

?>