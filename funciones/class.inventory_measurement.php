<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Inventory_measurement{
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
  	$query = Query::run("SELECT * FROM inventory_measurement");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function add($measurement)
  {
  	$query = Query::prun("INSERT INTO inventory_measurement (mea_unit) VALUES (?)",["s",$measurement]);

  	if($query->response){
  		$this->rh->setResponse(true,'Measurement added.',true);
  	}else{
  		$this->rh->setResponse(false,'An error has ocurred.');
  	}

  	echo json_encode($this->rh);
  }

  public function edit($id,$measurement)
  {
  	$query = Query::prun("SELECT id_measurement FROM inventory_measurement WHERE id_measurement = ? LIMIT 1",["i",$id]);

  	if($query->result->num_rows>0){
			$query = Query::prun("UPDATE inventory_measurement SET
																		mea_unit = ?
																	WHERE id_measurement = ? LIMIT 1",['si',$measurement,$id]);
			if($query->response){
				$this->rh->setResponse(true,"Measurement edited.",true);
			}else{
				$this->rh->setResponse(false,"An error has ocurred.");
			}
  	}else{
  		$this->rh->setResponse(false,"Measurement not found.");
  	}

  	echo json_encode($this->rh);
  }

  public function delete($measurement)
  {
  	$query = Query::prun("SELECT COUNT(i.id_inventory)AS total, ic.id_measurement
  															FROM inventory_measurement AS ic
																LEFT JOIN inventory AS i ON i.id_measurement = ic.id_measurement
																WHERE ic.id_measurement = ?
																GROUP BY ic.id_measurement LIMIT 1",["i",$measurement]);

  	if($query->result->num_rows>0){
  		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
  		if($data->total === 0){
  			$query = Query::prun("DELETE FROM inventory_measurement WHERE id_measurement = ? LIMIT 1",['i',$measurement]);

  			if($query->response){
  				$this->rh->setResponse(true,"Measurement deleted.",true);
  			}else{
  				$this->rh->setResponse(false,"An error has ocurred.");
  			}
  		}else{
  			$this->rh->setResponse(false,"This measurement has items.");
  		}
  	}else{
  		$this->rh->setResponse(false,"Measurement not found.");
  	}

  	echo json_encode($this->rh);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================


}//Class Products

$modelInventoryMeasurement = new Inventory_measurement();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'add_measurement':
	  		$measurement = ucfirst($_POST['add-measurement-name']);
	  		$modelInventoryMeasurement->add($measurement);
	  	break;
	  	case 'edit_measurement':
	  		$id          = $_POST['id'];
	  		$measurement = ucfirst($_POST['unit-name']);
	  		$modelInventoryMeasurement->edit($id,$measurement);
	  	break;
	  	case 'delete_measurement':
	  		$id = $_POST['id'];

	  		$modelInventoryMeasurement->delete($id);
	  	break;
		endswitch;
	endif;
endif;
?>