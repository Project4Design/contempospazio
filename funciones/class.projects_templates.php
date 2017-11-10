<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Projects_templates{
	private $rh;
	private $user;
	private $nivel;


	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	public function consulta()
	{
    $query =  Query::run("SELECT * FROM projects_templates");
    $data  = [];

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function obtener($template){
  	$query = Query::prun("SELECT * FROM projects_templates WHERE id_pt = ? LIMIT 1",['i',$template]);

		$data = ($query->result->num_rows>0)?(object) $query->result->fetch_array(MYSQLI_ASSOC):NULL;

		return $data;
  }

  public function add($name,$items)
  {
  	$content = $this->itemsToJson(json_decode($items));

  	if($content){
	  	$query = Query::prun("INSERT INTO projects_templates (id_user,name,content) VALUES (?,?,?)",["iss",$this->user,$name,$content]);

	  	if($query->response){
	  		$data = "<li class='list-group-item'>
	               	<b class='list-unit-name'>{$name}</b>";
								if($this->nivel=="A"){
	              	$data .="<span class='pull-right'>
		                <button class='btn-link btn-box-tool' data-id='{$query->id}' data-toggle='modal' data-target='#delModal'><i class='fa fa-times'></i></button>
	              	</span>";
	              }
	      $data .= "</li>";

	  		$this->rh->setResponse(true);
	  		$this->rh->data = $data;
	  	}else{
	  		$this->rh->setResponse(false);
	  	}
	  }else{
	  	$this->rh->setResponse(false);
	  }

  	echo json_encode($this->rh);
  }

  
  public function delete($template)
  {
  	if($this->nivel=="A"){
	  	$query = Query::prun("SELECT id_pt FROM projects_templates WHERE id_pt = ? LIMIT 1",['i',$template]);

	  	if($query->result->num_rows>0){
	  		$query = Query::prun("DELETE FROM projects_templates WHERE id_pt = ? LIMIT 1",['i',$template]);

	  		if($query->response){
	  			$this->rh->setResponse(true,"Template deleted.",true);
	  		}else{
	  			$this->rh->setResponse(false,"An error has ocurred.");
	  		}
	  	}else{
	  		$this->rh->setResponse(false,"Template not found.");
	  	}
	  }else{
	  	$this->sh->setResponse(false,"You don't have permission to make this accion.");
	  }

  	echo json_encode($this->rh);
  }


  public function itemsToJson($items)
  {
  	$inventory = new Inventory();
  	//Go through the items
		foreach ($items as $key => $item){
			//Get the iformation of each item in the Inventory table
			$item_inv = $inventory->obtener($item->id);
			//If Item exist, continue. Else, cancel...
			if($item_inv){
				//Save the item's information in the content array
				$data[] = ['id'            => $key,
										'item'         => $item_inv->id_inventory,
										'stock_needed' => $item->qty
									];
			}else{
				return false;
			}
		}//Foreach

		return json_encode($data);
  }

  //Load templates in Project's View
  public function load(){
  	$data = "";
  	foreach ($this->consulta() AS $template){
  		$data .= "<li class='list-group-item'>
	               	<button xid='{$template->id_pt}' class='btn-link btn-add-template'><b>{$template->name}</b></button>";
								if($this->nivel=="A"){
	              	$data .="<span class='pull-right'>
		                <button class='btn-link btn-box-tool' data-id='{$template->id_pt}' data-toggle='modal' data-target='#delModal'><i class='fa fa-times'></i></button>
	              	</span>";
	              }
      $data .= "</li>";
  	}

  	$this->rh->setResponse(true);
  	$this->rh->data = $data;

  	echo json_encode($this->rh);
  }//Load

  public function getTemplate($id){
  	$template = $this->obtener($id);
  	$data = "";
  	
  	if($template){
  		$inventory = new Inventory();

  		$items = json_decode($template->content);
	  	//Go through the items
			$data .="<tr id=\"\" class=\"active\" type='2'><th class=\"text-center\" colspan=\"3\">{$template->name}</th>
							<td class=\"text-center\"><button row=\"\" class=\"btn-link btn-box-tool btn-delete-template\" type=\"button\"><i class=\"fa fa-times\" aria-hidden=\"true\" style=\"color:red\"></i></button></td>
							</tr>";
			foreach ($items as $key => $item){
				//Get the iformation of each item in the Inventory table
				$item_inv = $inventory->obtener($item->item);
				$data .="<tr class=\"\" id=\"\" xid=\"{$item->item}\"><td class=\"text-center\">-</td>
    			<td>{$item_inv->inv_name}</td>
    			<td><div class=\"form-group\"><input id=\"qty-{$item->item}\" class=\"form-control\" type=\"number\" placeholder=\"Qty\" min=\"1\" value=\"1\" required></div></td>
    			<td class=\"text-center\">-</td>
    			</tr>";
  		}

  		$this->rh->setResponse(true);
  		$this->rh->data = $data;
  	}else{
  		$this->rh->setResponse(false);
  	}

  	echo json_encode($this->rh);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================x

}//Class Project

$modelProjectTemplates = new Projects_templates();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'loadTemplates':
	  		$modelProjectTemplates->load();
	  	break;
		  case 'add_template':
		  	$name  = $_POST['add-template-name'];
		  	$items = $_POST['project-items'];

		  	$modelProjectTemplates->add($name,$items);
		  break;
		  case 'delete_template':
		  	$template = $_POST['template'];

		  	$modelProjectTemplates->delete($template);
		  break;
		  case 'getTemplate':
		  	$template = $_POST['template'];

		  	$modelProjectTemplates->getTemplate($template);
		  break;
		endswitch;
	endif;
endif;
?>