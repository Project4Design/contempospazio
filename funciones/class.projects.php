<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Projects{
	private $rh;
	private $user;
	private $nivel;
	private $fecha;
	public  $gallery;
	private $project_id = NULL;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : 'X';
	}

	/*
		Get all the Projects
	*/
	public function consulta()
	{
    $query = Query::run('SELECT * FROM projects');
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
		$query = Query::prun('SELECT p.*,u.user_nombres,u.user_apellidos FROM projects AS p
																	INNER JOIN usuarios AS u ON u.id_user = p.id_user
    															WHERE p.id_project = ? LIMIT 1',['i',$id]);

		if($query->result->num_rows>0){
			$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
			$this->project_id = $id;
			$this->gallery  = new projects_gallery($this->project_id);
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
  	$query = Query::prun('INSERT INTO projects (id_user,title,status) VALUES (?,?,?)',['isi',$this->user,$title,1]);

  	if($query->response){
  		//ID of the new project
  		$id = $query->id;
  		//An Array for the Items and the Templates of the project
			$content = ['items' => [],'templates' =>[] ];
			//Errors
			$errors = 0;

			//Save image
			if($foto){
				$img = new img();
				$tmp = $img->load($foto['foto'],true);

				$query == Query::prun('INSERT INTO projects_gallery (id_project,id_user,photo,thumb,main) VALUES (?,?,?,?,?)',['iissi',$id,$this->user,$tmp->name,$tmp->thumb,1]);
			}

  		$items = json_decode($items);

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
	  				$inventory->replace($item_inv->id_inventory,$item_stock,false);

	  				//Save the item's information in the content array
	  				$content['items']['id_'.($key+1)] = ['id' => ($key+1),
	  														'item'         => $item_inv->id_inventory,
	  														'category'     => $item_inv->icat_category,
	  														'name'         => $item_inv->inv_name,
	  														'stock_needed' => $item->qty,
	  														'stock'        => $stock,
	  														'registered'   => date('Y-m-d h:i:s')];
	  			}//If
	  		}//Foreach
	  	}

  		$templates = json_decode($templates);

	  	if(count($templates)>0){

	  		$projects_templates = new Projects_templates();

	  		foreach ($templates as $tempKey => $template){
	  			//Template in DB
	  			$temp = $projects_templates->obtener($template->id);
	  			//If Template exist. Else, skip...
	  			if($temp){
	  				//Items in template
	  				$temp_items = json_decode($temp->content);
	  				//Template content
	  				$array_template = ['name'=>$temp->name,'items'=>[]];

	  				//Items in Template
	  				foreach ($temp_items as $key => $item){
			  			//Get the iformation of each item in the Inventory table
			  			$item_inv = $inventory->obtener($item->item);
			  			//If Item exist. Else, skip...
			  			if($item_inv){
			  				//Lo que queda en stock para Inventory.
			  				//Si lo que se necesita para el projecto es mayor lo disponible en inventario
			  				//Inventario se guarda en 0
			  				$item_stock = (($item_inv->inv_stock - $item->stock_needed)<0)?0:($item_inv->inv_stock - $item->stock_needed);

			  				//if the stock minus the quantity needed for the project is lower than 0.
			  				//The Stock for this project's item will be saved as all the stock available.
			  				//ELse, the stock for this project's item will be the qty needed. It means, there is enough Stock for this Item.
			  				$stock = (($item_inv->inv_stock - $item->stock_needed)<0)?$item_inv->inv_stock:$item->stock_needed;
			  				
			  				//Update the Stock for this item in the inventory
			  				$inventory->replace($item_inv->id_inventory,$item_stock,false);

			  				//Save the item's information in the content array for the template
			  				$array_template['items']['id_'.($key+1)] = ['id' => ($key+1),
								  														'item'         => $item_inv->id_inventory,
								  														'category'     => $item_inv->icat_category,
								  														'name'         => $item_inv->inv_name,
								  														'stock_needed' => $item->stock_needed,
								  														'stock'        => $stock,
								  														'registered'   => date('Y-m-d h:i:s')];
			  			}//If item exits
			  		}//Foreach Items in Template

			  		$content['templates']['id_'.($tempKey+1)] = $array_template;
	  			}//If template exist
	  		}//foreach Templates
	  	}
  		//Convert the $content array in JSON to store it in the Database...
  		$content = json_encode($content);
  		//Store the items
  		$query = Query::prun('INSERT INTO projects_items (id_project,content) VALUES (?,?)',['is',$id,$content]);

  		if($query->response){
	  		$this->rh->setResponse(true,'Project added.',true,'?ver=projects&opc=ver&id='.$id);
	  		$this->rh->data = $id;
  		}else{
  			$this->delete($id,false);
  			$this->rh->setResponse(false,'An error has ocurred with the data.');
  		}
  	}else{
  		$this->rh->setResponse(false,'An error has ocurred with the data.');
  	}

  	echo json_encode($this->rh);
  }

	/*
		Edit the information of 1 specific project
	*/
  public function edit($id,$title,$foto,$items,$templates)
  {
  	$query = Query::prun('SELECT id_project FROM projects WHERE id_project = ? LIMIT 1',['i',$id]);

  	if($query->result->num_rows>0){
  		/*
  			EDIT
  		*/

	  	if($query->response){
	  		$this->rh->setResponse(true,'Item added to the Project.',true,'inicio.php?ver=project&opc=ver&id='.$id);
	  	}else{
	  		$this->rh->setResponse(false,'An error has ocurred with the data.');
	  	}
	  }else{
	  	$this->rh->setResponse(false,'Item not found.');
	  }

  	echo json_encode($this->rh);
  }

	/*
		Delete 1 specific Project
	*/
  public function delete($project,$echo = true)
  {
  	if($this->nivel=='A'){
	  	$query = Query::prun('SELECT id_project FROM projects WHERE id_project = ? LIMIT 1',['i',$project]);

	  	if($query->result->num_rows>0){
	  		$query = Query::prun('DELETE FROM projects WHERE id_project = ? LIMIT 1',['i',$project]);

	  		if($query->response){
	  			$this->rh->setResponse(true,'Project deleted.',true,'inicio.php?ver=projects');
	  		}else{
	  			$this->rh->setResponse(false,'An error has ocurred.');
	  		}
	  	}else{
	  		$this->rh->setResponse(false,'Item not found.');
	  	}
	  }else{
	  	$this->sh->setResponse(false,'You don\'t have permission to make this accion.');
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
		if($status == 1){
			return '<span class="label label-info">Started</span>';
		}

		if($status == 2){
			return '<span class="label label-warning">Demolished</span>';
		}

		if($status == 3){
			return '<span class="label label-primary">Installed</span>';
		}

		if($status == 4){
			return '<span class="label label-success">Completed</span>';
		}

		if($status == 5){
			return '<span class="label label-danger">Canceled</span>';
		}

		return '<span class="label label-default">Error</span>';
  }

	/*
		Get all the items of 1 specific Project
	*/
  public function items()
  {
  	$query = Query::run("SELECT content FROM projects_items WHERE id_project = $this->project_id LIMIT 1");
  	$data  = (object)$query->fetch_array(MYSQLI_ASSOC);
  	$content = json_decode($data->content);
		return $content->items;
  }

	/*
		Get all the templates of 1 specific Project
	*/
  public function templates()
  {
  	$query = Query::run("SELECT content FROM projects_items WHERE id_project = $this->project_id LIMIT 1");
  	$data  = (object)$query->fetch_array(MYSQLI_ASSOC);
  	$content = json_decode($data->content);
		return $content->templates;
  }


  public function checkItemStock($item,$template = '')
  {
  	if($item->stock_needed>$item->stock){
  		$data   = "{$item->stock_needed}/<span style='color:red'>{$item->stock}</span>";
  		$button = "<button type=\"button\" data-item=\"{$item->id}\" data-template=\"{$template}\" class=\"btn btn-success btn-sm btn-flat\" data-toggle=\"modal\" data-target=\"#addModal\"><i class=\"fa fa-plus-square\" aria-hidden=\"true\"></i></button>";
  	}else{
  		$data   = "{$item->stock_needed}/{$item->stock}";
  		$button = "";
  	}

  	return (object)['stock'=>$data,'button'=>$button];
  }

  /*
		Update the project Items and Templates (Content).
  */
  public function update_project_content($project,$content){
  	$query = Query::prun('UPDATE projects_items SET
  																						content = ?
  																				WHERE id_project = ?',
  																				['si',$content,$project]);
  }

  public function add_item_stock($project,$template = NULL,$item,$newStock){
  	$query = Query::prun('SELECT * FROM projects_items WHERE id_project = ? LIMIT 1',['i',$project]);

  	if($query->result->num_rows){
  		$info = (object) $query->result->fetch_array(MYSQLI_ASSOC);
  		//Decode the items as arrays
  		$content   = json_decode($info->content,true);
  		$items     = $content['items'];
  		$templates = $content['templates'];
  		
  		//If there is no template specified, go for the individual items
  		if($template){
  			//Search the template by its ID
  			//If does not exists, return Error
  			if(array_key_exists($template,$templates)){
  				//Search the item by its ID
	  			//If does not exists, return Error
	  			if(array_key_exists('id_'.$item,$templates[$template]['items'])){
	  				//Stock needed for the project
						$stock_needed = $templates[$template]['items']['id_'.$item]['stock_needed'];
						//Stock available
						$stock = $templates[$template]['items']['id_'.$item]['stock'];

						//If the new Stock to be added + the actual Stock is greater than the 
						//stock needed for the project. The exceeded amount will be added
						//to the Inventory of that Item.
						if(($stock + $newStock) > $stock_needed){
							$item_stock = $newStock - ($stock_needed - $stock);
							$stock = $stock_needed;
							$templates[$template]['items']['id_'.$item]['stock'] = $stock;

							$inventory = new Inventory();
							//ID of the Item
							$item_id = $templates[$template]['items']['id_'.$item]['item'];

							$inventory->restock($item_id,$item_stock,false);
						}else{
							$templates[$template]['items']['id_'.$item]['stock'] = $stock + $newStock;
						}//

						//Update the content of the project
						$content = json_encode(['items'=>$items,'templates'=>$templates]);
						$this->update_project_content($project,$content);

						//Set the response as true.
						$this->rh->setResponse(true,'Stock added.',true);
					}else{
						$this->rh->setResponse(false,'Item not found.');
					}//If item exists
  			}else{
  				$this->rh->setResponse(false,'Template not found.');
  			}
  		}else{
  			//Search the item by its ID
  			//If does not exists, return Error
  			if(array_key_exists('id_'.$item,$items)){
  				//Stock needed for the project
					$stock_needed = $items['id_'.$item]['stock_needed'];
					//Stock available
					$stock = $items['id_'.$item]['stock'];

					//If the new Stock to be added + the actual Stock is greater than the 
					//stock needed for the project. The exceeded amount will be added
					//to the Inventory of that Item.
					if(($stock + $newStock) > $stock_needed){
						$item_stock = $newStock - ($stock_needed - $stock);
						$stock = $stock_needed;
						$items['id_'.$item]['stock'] = $stock;

						$inventory = new Inventory();
						//ID of the Item
						$item_id = $items['id_'.$item]['item'];

						$inventory->restock($item_id,$item_stock,false);
					}else{
						$items['id_'.$item]['stock'] = $stock + $newStock;
					}//

					//Update the content of the project
					$content = json_encode(['items'=>$items,'templates'=>$templates]);
					$this->update_project_content($project,$content);

					//Set the response as true.
					$this->rh->setResponse(true,'Stock added.',true);
				}else{
					$this->rh->setResponse(false,'Item not found.');
				}//If item exists
			}//If template
  	}else{
  		$this->rh->setResponse(false,'Project not found.');
  	}//If num_rows->0

  	echo json_encode($this->rh);
  }//add_item_stock

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
	  		$templates = $_POST['project-templates'];

	  		$modelProject->add($title,$foto,$items,$templates);
	  	break;
	  	case 'edit_project':
	  		$id          = $_POST['id'];
	  		$title = ucfirst($_POST['project-title']);
	  		$foto  = ($_FILES['foto']['name'])?$_FILES:NULL;
	  		$items = $_POST['project-items'];
	  		$templates = $_POST['project-templates'];

	  		$modelProject->edit($id,$title,$foto,$items,$templates);
	  	break;
	  	case 'delete_project':
	  		$modelProject->delete($_POST['project']);
	  	break;
	  	case 'add_item_stock':
	  		$project  = $_POST['project'];
	  		$template = $_POST['template'];
	  		$item     = $_POST['item'];
	  		$newStock    = $_POST['stock'];

	  		$modelProject->add_item_stock($project,$template,$item,$newStock);
	  	break;
	  	case 'add_project_photo':
	  		$project = $_POST['project'];
	  		$photo  = ($_FILES['photo']['name'])?$_FILES:NULL;

	  		$modelProject->add_photo($project,$photo);
	  	break;
	  	default:
	  	$modelProject->fdefault();
	  	break;
		endswitch;
	endif;
endif;
?>