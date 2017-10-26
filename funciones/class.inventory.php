<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Inventory{
	private $rh;
	private $user;
	private $nivel;
	private $fecha;
	private $category;
	private $measurement;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->category = new Inventory_category();
		$this->measurement = new Inventory_measurement();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	//Buscar todos los items en Inventory
	public function consulta()
	{
    $query = Query::run("SELECT * FROM inventory AS i
    													INNER JOIN inventory_category AS ic ON ic.id_category = i.id_category
    													INNER JOIN inventory_measurement AS im ON im.id_measurement = i.id_measurement");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function consultaCategories()
  {
  	return $this->category->consulta();
  }

  public function consultaMeasurements()
  {
  	return $this->measurement->consulta();
  }

	public function obtener($id)
	{
		$query = Query::prun("SELECT * FROM inventory AS i
    													INNER JOIN inventory_category AS ic ON ic.id_category = i.id_category
    													INNER JOIN inventory_measurement AS im ON im.id_measurement = i.id_measurement
    													WHERE id_inventory = ? LIMIT 1",array("i",$id));
		if($query->result->num_rows>0){
			$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

		return $data;
	}

  public function add($category,$name,$measurement,$stock)
  {
  	$query = Query::prun("INSERT INTO inventory (id_category,id_measurement,inv_name,inv_stock)
  													VALUES (?,?,?,?)",array("iisi",$category,$measurement,$name,$stock));

  	if($query->response){
  		$this->rh->setResponse(true,"Item added to the Inventory. <a href='?ver=inventory&opc=ver&id={$query->id}'>See Item</a>");
  	}else{
  		$this->rh->setResponse(false,"An error has ocurred with the data.");
  	}

  	echo json_encode($this->rh);
  }

  public function edit($id,$category,$name,$measurement,$stock)
  {
  	$query = Query::prun("SELECT id_inventory FROM inventory WHERE id_inventory = $id");

  	if($query->result->num_rows>0){
	  	$query = Query::prun("UPDATE inventory SET
	  																				id_category    = ?,
	  																				id_measurement = ?,
	  																				inv_name       = ?,
	  																				inv_stock      = ?
	  													WHERE id_inventory = ?",array("iisii",$category,$measurement,$name,$stock,$id));

	  	if($query->response){
	  		$this->rh->setResponse(true,"Item added to the Inventory.",true,"inicio.php?ver=inventory&opc=ver&id={$id}");
	  	}else{
	  		$this->rh->setResponse(false,"An error has ocurred with the data.");
	  	}
	  }else{
	  	$this->rh->setResponse(false,"Item not found.");
	  }

  	echo json_encode($this->rh);
  }

  //Increase current stock
  public function restock($id,$stock)
  {
  	$query = Query::prun("SELECT id_inventory FROM inventory WHERE id_inventory = $id");

  	if($query->result->num_rows>0){
  		$query = Query::prun("UPDATE inventory SET
  																					inv_stock = inv_stock + $stock
  													WHERE id_inventory = $id");

  		if($query->response){
  			$this->rh->setResponse(true,"Stock updated.",true,"inicio.php?ver=inventory&opc=ver&id={$id}");
  		}else{
  			$this->rh->setResponse(false,"An error has ocurred.");
  		}
  	}else{
  		$this->rh->setResponse(false,"Item not found.");
  	}

  	echo json_encode($this->rh);
  }

  //Replace current stock
  public function replace($id,$stock)
  {
  	$query = Query::prun("SELECT id_inventory FROM inventory WHERE id_inventory = $id");

  	if($query->result->num_rows>0){
  		$query = Query::prun("UPDATE inventory SET
  																							inv_stock = $stock
  													WHERE id_inventory = $id");

  		if($query->response){
  			$this->rh->setResponse(true,"Stock updated.",true,"inicio.php?ver=inventory&opc=ver&id={$id}");
  		}else{
  			$this->rh->setResponse(false,"An error has ocurred.");
  		}
  	}else{
  		$this->rh->setResponse(false,"Item not found.");
  	}

  	echo json_encode($this->rh);
  }

  public function delete($id)
  {
  	if($this->nivel=="A"){
	  	$query = Query::prun("SELECT id_inventory FROM inventory WHERE id_inventory = $id");

	  	if($query->result->num_rows>0){
	  		$query = Query::prun("DELETE FROM inventory WHERE id_inventory = $id");

	  		if($query->response){
	  			$this->rh->setResponse(true,"Item deleted.",true,"inicio.php?ver=inventory");
	  		}else{
	  			$this->rh->setResponse(false,"An error has ocurred.");
	  		}
	  	}else{
	  		$this->rh->setResponse(false,"Item not found.");
	  	}
	  }else{
	  	$this->sh->setResponse(false,"You don't have permission to make this accion.");
	  }

  	echo json_encode($this->rh);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================x


}//Class Inventory

$modelInventory = new Inventory();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'add':
	  		$category    = $_POST['category'];
	  		$name        = $_POST['name'];
	  		$measurement = $_POST['measurement'];
	  		$stock       = $_POST['stock'];

	  		$modelInventory->add($category,$name,$measurement,$stock);
	  	break;
	  	case 'edit':
	  		$id          = $_POST['id'];
	  		$category    = $_POST['category'];
	  		$name        = $_POST['name'];
	  		$measurement = $_POST['measurement'];
	  		$stock       = $_POST['stock'];

	  		$modelInventory->edit($id,$category,$name,$measurement,$stock);
	  	break;
	  	case 'stock':
	  		$id    = $_POST['id'];
	  		$stock = $_POST['stock'];
	  		if($_POST['type'] == 1){
	  			$modelInventory->restock($id,$stock);
	  		}else{
					$modelInventory->replace($id,$stock);
	  		}
	  	break;
	  	case 'delete':
	  		$modelInventory->delete($_POST['id']);
	  	break;
		endswitch;
	endif;
endif;
?>