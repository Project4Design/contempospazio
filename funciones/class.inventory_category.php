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
    $data  = [];

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function obtener($id)
  {
		$query = Query::prun("SELECT * FROM inventory_category WHERE id_category = ? LIMIT 1",array('i',$id));
		$data  = ($query->result->num_rows>0)?(object)$query->result->fetch_array(MYSQLI_ASSOC):NULL;

		return $data;
  }

  public function getItemsByCategory($id)
  {
  	$query = Query::prun("SELECT * FROM inventory WHERE id_category = ?",['i',$id]);
  	$data = [];

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }
    return $data;
  }

  public function add($category)
  {
  	$query = Query::prun("INSERT INTO inventory_category (icat_category) VALUES (?)",["s",$category]);

  	if($query->response){
  		$this->rh->setResponse(true,'Category added.',true);
  	}else{
  		$this->rh->setResponse(false,'An error has ocurred.');
  	}

  	echo json_encode($this->rh);
  }

  public function edit($id,$category)
  {
  	$query = Query::prun("SELECT id_category FROM inventory_category WHERE id_category = ? LIMIT 1",["i",$id]);

  	if($query->result->num_rows>0){
			$query = Query::prun("UPDATE inventory_category SET
																		icat_category = ?
																	WHERE id_category = ? LIMIT 1",['si',$category,$id]);
			if($query->response){
				$this->rh->setResponse(true,"Category edited.",true);
			}else{
				$this->rh->setResponse(false,"An error has ocurred.");
			}
  	}else{
  		$this->rh->setResponse(false,"Category not found.");
  	}

  	echo json_encode($this->rh);
  }

  public function delete($category)
  {
  	$query = Query::prun("SELECT COUNT(i.id_inventory)AS total, ic.id_category
  															FROM inventory_category AS ic
																LEFT JOIN inventory AS i ON i.id_category = ic.id_category
																WHERE ic.id_category = ?
																GROUP BY ic.id_category LIMIT 1",["i",$category]);

  	if($query->result->num_rows>0){
  		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
  		if($data->total === 0){
  			$query = Query::prun("DELETE FROM inventory_category WHERE id_category = ? LIMIT 1",['i',$category]);

  			if($query->response){
  				$this->rh->setResponse(true,"Category deleted.",true);
  			}else{
  				$this->rh->setResponse(false,"An error has ocurred.");
  			}
  		}else{
  			$this->rh->setResponse(false,"This category has items.");
  		}
  	}else{
  		$this->rh->setResponse(false,"Category not found.");
  	}

  	echo json_encode($this->rh);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================


}//Class Products

$modelInventoryCategory = new Inventory_category();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'add_category':
	  		$category = ucfirst($_POST['add-category-name']);
	  		$modelInventoryCategory->add($category);
	  	break;
	  	case 'edit_category':
	  		$id       = $_POST['id'];
	  		$category = ucfirst($_POST['unit-name']);
	  		$modelInventoryCategory->edit($id,$category);
	  	break;
	  	case 'delete_category':
	  		$id = $_POST['id'];

	  		$modelInventoryCategory->delete($id);
	  	break;
		endswitch;
	endif;
endif;
?>