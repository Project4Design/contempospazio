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

	/*
		Get all the Projects
	*/
	public function consulta()
	{
    $query = Query::run("SELECT * FROM projects");
    $data  = [];

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

	/*
		Get all the information of 1 specific Projects
	*/
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


	/*
		Add Projects
	*/
  public function add($title,$foto,$items,$templates)
  {
		if($foto){
			$img = new img();
			$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/projects");
			$foto = $tmp->name;
		}else{ $foto = NULL; }

  	$query = Query::prun("INSERT INTO projects (id_user,title,photo,status) VALUES (?,?,?,?)",["issi",$this->user,$title,$foto,1]);

  	if($query->response){
  		//ID of the new project
  		$id = $query->id;
  		//An Array for the Items and the Templates of the project
			$content_items = $content_templates = [];
			//Errors
			$errors = 0;

  		$items     = json_decode($items);
  		$templates = json_decode($templates);

  		$inventory = new Inventory();

  		if(count($items)>0){
	  		//Go through the items
	  		foreach ($items as $key => $item){
	  			//Get the iformation of each item in the Inventory table
	  			$item_inv = $inventory->obtener($item->id);
	  			//If Item exist. Else, skip...
	  			if($item_inv){
	  				//Lo que queda en stock para Inventory.
	  				//Si lo que se necesita para el projecto es mayor lo disponible en inventario
	  				//Inventario se guarda en 0
	  				$item_stock = (($item_inv->inv_stock - $item->qty)<0)?0:($item_inv->inv_stock - $item->qty);

	  				//if the stock minus the quantity needed for the project is lower than 0.
	  				//The Stock for this project's item will be saved as all the stock available.
	  				//ELse, the stock for this project's item will be the qty needed. It means, there is enough Stock for this Item.
	  				$stock = (($item_inv->inv_stock - $item->qty)<0)?$item_inv->inv_stock:$item->qty;
	  				
	  				//Update the Stock for this items in the inventory
	  				$inventory->edit($item_inv->id_inventory,$item_inv->id_category,$item_inv->inv_name,$item_inv->id_measurement,$item_stock,false);

	  				//Save the item's information in the content array
	  				$content_items[] = ['id'           => $key,
	  														'item'         => $item_inv->id_inventory,
	  														'category'     => $item_inv->icat_category,
	  														'name'         => $item_inv->inv_name,
	  														'stock_needed' => $item->qty,
	  														'stock'        => $stock,
	  														'registered'   => date('Y-m-d h:i:s')];
	  			}//If
	  		}//Foreach

	  		//Convert the $content array in JSON to store it in the Database...
	  		$content_items = json_encode($content_items);
	  		//Store the items
	  		$query = Query::prun("INSERT INTO projects_items (id_project,type,content) VALUES (?,?,?)",['iis',$id,1,$content_items]);
	  		//If the items cannot be saved. Sum error.
	  		$errors += $query->response?0:1;
	  	}

	  	if(count($templates)>0){
	  		foreach ($templates as $template){

	  		}
	  		//Convert the $content array in JSON to store it in the Database...
	  		$content_templates = json_encode($content_templates);
	  		//Store the items
	  		$query = Query::prun("INSERT INTO projects_items (id_project,type,content) VALUES (?,?,?)",['iis',$id,2,$content_templates]);
	  		//If the items cannot be saved. Sum error.
	  		$errors += $query->response?0:1;
	  	}

	  	//if there is no erros return Succcess
	  	//Else, delete everything and return Error
  		if($errors === 0){
	  		$this->rh->setResponse(true,"Project added.");
	  		$this->rh->data = $id;
  		}else{
  			$this->delete($id,false);
  			$this->rh->setResponse(false,"An error has ocurred with the data.");
  		}
  	}else{
  		$this->rh->setResponse(false,"An error has ocurred with the data.");
  	}

  	echo json_encode($this->rh);
  }

	/*
		Edit the information of 1 specific project
	*/
  public function edit($id,$category,$name,$measurement,$stock)
  {
  	$query = Query::prun("SELECT id_project FROM projects WHERE id_project = $id");

  	if($query->result->num_rows>0){
	  	$query = Query::prun("UPDATE projects SET
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

	/*
		Delete 1 specific Project
	*/
  public function delete($id,$echo = true)
  {
  	if($this->nivel=="A"){
	  	$query = Query::prun("SELECT id_project FROM projects WHERE id_project = ? LIMIT 1",['i',$id]);

	  	if($query->result->num_rows>0){
	  		$query = Query::prun("DELETE FROM projects WHERE id_project = ? LIMIT 1",['i',$id]);

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

	  if($echo){
	  	echo json_encode($this->rh);
	  }else{
	  	return $this->rh;
	  }  	
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


	/*
		Get all the items of 1 specific Project
	*/
  public function items()
  {
  	$query = Query::run("SELECT content FROM projects_items WHERE id_project = $this->project_id AND type = 1 LIMIT 1");
  	$data  = (object)$query->fetch_array(MYSQLI_ASSOC);

		return $data = json_decode($data->content);
  }


  public function checkStock($needed,$stock)
  {
  	if($needed>$stock){
  		$data = "{$needed}/<span style='color:red'>{$stock}</span>";
  	}else{
  		$data = "{$needed}/{$stock}";
  	}

  	return $data;
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
	  		$templates = NULL;

	  		$modelProject->add($title,$foto,$items,$templates);
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