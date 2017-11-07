<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Projects{
	private $rh;
	private $user;
	private $nivel;
	private $fecha;
	private $comments;
	private $project_id = NULL;

	public function __CONSTRUCT()
	{
		$this->rh       = new ResponseHelper();
		$this->comments = new projects_comments();
		$this->user     = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel    = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	//Buscar todos los items en Project
	public function consulta()
	{
    $query = Query::run("SELECT * FROM projects");
    $data  = [];

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

	public function obtener($id)
	{
		$query = Query::prun("SELECT p.*,u.user_nombres,u.user_apellidos FROM projects AS p
																	INNER JOIN usuarios AS u ON u.id_user = p.id_user
    															WHERE p.id_project = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){
			$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
			$this->project_id = $id;
		}else{
			$data = NULL;
		}

		return $data;
	}

  public function add($title,$foto,$items)
  {
		if($foto){
			$img = new img();
			$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/projects");
			$foto = $tmp->name;
		}else{ $foto = NULL; }

  	$query = Query::prun("INSERT INTO projects (id_user,title,photo,status) VALUES (?,?,?,?)",["issi",$this->user,$title,$foto,1]);

  	if($query->response){
  		$id = $query->id;
  		$inventory = new Inventory();
  		foreach (json_decode($items) as $d){
  			$item = $inventory->obtener($d->id);

  			if($item){
  				//Lo que queda en stock para Inventory.
  				//Si lo que se necesita para el projecto es mayor lo disponible en inventario
  				//Inventario se guarda en 0
  				$item_stock = (($item->inv_stock - $d->qty)<0)?0:($item->inv_stock - $d->qty);

  				$stock = (($item->inv_stock - $d->qty)<0)?$item->inv_stock:$d->qty;
  				
  				$query = Query::prun("INSERT INTO projects_inventory (id_project,type,inventory_name,stock_needed,in_stock) VALUES (?,?,?,?,?)",
  																									["iisii",$id,1,$item->inv_name,$d->qty,$stock]);

  				$inventory->edit($item->id_inventory,$item->id_category,$item->inv_name,$item->id_measurement,$item_stock,false);
  			}
  		}
  		$this->rh->setResponse(true,"Project added.");
  		$this->rh->data = $id;
  	}else{
  		$this->rh->setResponse(false,"An error has ocurred with the data.");
  	}

  	echo json_encode($this->rh);
  }

  public function edit($id,$category,$name,$measurement,$stock)
  {
  	$query = Query::prun("SELECT id_project FROM project WHERE id_project = $id");

  	if($query->result->num_rows>0){
	  	$query = Query::prun("UPDATE project SET
	  																				id_category    = ?,
	  																				id_measurement = ?,
	  																				inv_name       = ?,
	  																				inv_stock      = ?
	  													WHERE id_project = ?",array("iisii",$category,$measurement,$name,$stock,$id));

	  	if($query->response){
	  		$this->rh->setResponse(true,"Item added to the Project.",true,"inicio.php?ver=project&opc=ver&id={$id}");
	  	}else{
	  		$this->rh->setResponse(false,"An error has ocurred with the data.");
	  	}
	  }else{
	  	$this->rh->setResponse(false,"Item not found.");
	  }

  	echo json_encode($this->rh);
  }

  public function delete($id)
  {
  	if($this->nivel=="A"){
	  	$query = Query::prun("SELECT id_project FROM project WHERE id_project = $id");

	  	if($query->result->num_rows>0){
	  		$query = Query::prun("DELETE FROM project WHERE id_project = $id");

	  		if($query->response){
	  			$this->rh->setResponse(true,"Item deleted.",true,"inicio.php?ver=project");
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

  //Status of the project
  public function status($status)
  {
  	switch ($status) {
  		case 1:
  			$label = '<span class="label label-info">Started</span>';
  		break;
  		case 2:
  			$label = '<span class="label label-warning">Demolished</span>';
  		break;
  		case 3:
  			$label = '<span class="label label-primary">Installed</span>';
  		break;
  		case 4:
  			$label = '<span class="label label-success">Completed</span>';
  		break;
  		case 5:
  			$label = '<span class="label label-danger">Canceled</span>';
  		break;
  		default:
  			$label = '<span class="label label-default">Error</span>';
  		break;
  	}

  	return $label;
  }

  //Conexion con comentarios
  public function comments()
  {
  	return $this->comments->consulta($this->project_id);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================x

}//Class Project

$modelProject = new Projects();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'add_project':
	  		$title = ucfirst($_POST['project-title']);
	  		$foto  = ($_FILES['foto']['name'])?$_FILES:NULL;
	  		$items = $_POST['project-items'];

	  		$modelProject->add($title,$foto,$items);
	  	break;
	  	case 'edit_project':
	  		$id          = $_POST['id'];
	  		$category    = $_POST['category'];
	  		$name        = $_POST['name'];
	  		$measurement = $_POST['measurement'];
	  		$stock       = $_POST['stock'];

	  		$modelProject->edit($id,$category,$name,$measurement,$stock);
	  	break;
	  	case 'delete':
	  		$modelProject->delete($_POST['id']);
	  	break;
		endswitch;
	endif;
endif;
?>