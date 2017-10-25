<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Inventory{
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

	//Buscar todos los items en Inventory
	public function consulta()
	{
    $query = Query::run("SELECT * FROM inventory");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function selectCategorys()
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

$modelProducts = new Products();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'add':
				$prod = (object) array();
				$foto = ($_FILES['foto']["name"])?$_FILES:NULL;
				$tipo = $_POST['tipo'];
				switch ($tipo){
					case 1:
						//Gabinetes
						$prod->description = $_POST['descripcion'];
					break;
					case 2:
					case 3:
						//Sinks o Tops
						$prod->shape       = isset($_POST['forma'])?$_POST['forma']:NULL; //Sinks only
						$prod->name        = $_POST['name'];
						$prod->material    = $_POST['material'];
						$prod->color       = $_POST['color'];
						$prod->manufacture = isset($_POST['manufacture'])?$_POST['manufacture']:NULL; //Tops only
						$prod->price       = $_POST['price'];
					break;
					case 4:
						$prod->name  = $_POST['name'];
						$prod->price = $_POST['price'];
					break;
				}

				$modelProducts->add($tipo,$prod,$foto);
			break;
			case 'edit':
				$cabinet     = $_POST['gabinete'];
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$descripcion = $_POST['descripcion'];

				$modelProducts->edit($cabinet,$descripcion,$foto);
			break;
			case 'edit_sink':
				$id          = $_POST['sink'];
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name        = $_POST['sink_name'];
				$shape       = $_POST['sink_shape'];
				$material    = $_POST['sink_material'];
				$color       = $_POST['sink_color'];
				$price       = $_POST['sink_price'];
				$manufacture = NULL;

				$modelProducts->editProduct($id,$color,$material,$shape,$name,$manufacture,$price,$foto);
			break;
			case 'edit_top':
				$id          = $_POST['top'];
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name        = $_POST['top_name'];
				$material    = $_POST['top_material'];
				$manufacture = $_POST['top_manufacture'];
				$color       = $_POST['top_color'];
				$price       = $_POST['top_price'];
				$shape       = NULL;

				$modelProducts->editProduct($id,$color,$material,$shape,$name,$manufacture,$price,$foto);
			break;
			case 'edit_acce':
				$id    = $_POST['accessory'];
				$foto  = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name  = $_POST['acce_name'];
				$price = $_POST['acce_price'];

				$modelProducts->editAccessory($id,$name,$price,$foto);
			break;
			case 'add_item':
				$labor   = $_POST['labor'];
				$cabinet = $_POST['gabinete'];
				$codigo   = $_POST['codigo'];
				$gs       = $_POST['gs'];
				$mgc      = $_POST['mgc'];
				$rbs      = $_POST['rbs'];
				$esms     = $_POST['esms'];
				$ws       = $_POST['ws'];
				$miw      = $_POST['miw'];

				$modelProducts->add_item($labor,$cabinet,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
			break;
			case 'edit_item':
				$id     = $_POST['item'];
				$labor  = $_POST['labor'];
				$codigo = $_POST['codigo'];
				$gs     = $_POST['gs'];
				$mgc    = $_POST['mgc'];
				$rbs    = $_POST['rbs'];
				$esms   = $_POST['esms'];
				$ws     = $_POST['ws'];
				$miw    = $_POST['miw'];

				$modelProducts->edit_item($id,$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
			break;
			case 'get_item':
				$id = $_POST["id"];

				$modelProducts->get_item($id);
			break;
			case 'load':
				$id = $_POST["id"];

				$modelProducts->load($id);
			break;
			case 'del_cabi':
				$id = $_POST["cabi"];
				$modelProducts->del_cabi($id);
			break;
			case 'del_item':
				$id = $_POST["item"];
				$modelProducts->del_item($id);
			break;
			case 'del_top':
				$id = $_POST['top'];
				$modelProducts->delProduct($id);
			break;
			case 'del_sink':
				$id = $_POST['sink'];
				$modelProducts->delProduct($id);
			break;
			case 'del_acce':
				$id = $_POST['accessory'];
				$modelProducts->del_accessory($id);
			break;
			case 'tope_list_mat':
				$modelProducts->listMaterials(3);
			break;
			case 'tope_list_color':
				$modelProducts->listColors(3);
			break;
			case 'freg_list_mat':
				$modelProducts->listMaterials(2);
			break;
			case 'freg_list_color':
				$modelProducts->listColors(2);
			break;
			case 'freg_list_shape':
				$modelProducts->listShapes();
			break;
			case 'add_material':
				$material = ucfirst(strtolower($_POST['opc']));
				$type     = $_POST['table'];
				$load     = isset($_POST['load']);

				$modelProducts->addMaterial($type,$material,$load);
			break;
			case 'edit_material':
				$id       = $_POST['id'];
				$material = ucfirst(strtolower($_POST['opc']));
				$type     = $_POST['table'];

				$modelProducts->editMaterial($id,$type,$material);
			break;
			case 'del_material':
				$id   = $_POST['id'];
				$type = $_POST['table'];

				$modelProducts->delMaterial($id,$type);
			break;
			case 'add_color':
				$color = ucfirst(strtolower($_POST['opc']));
				$type  = $_POST['table'];
				$load  = isset($_POST['load']);

				$modelProducts->addColor($type,$color,$load);
			break;
			case 'edit_color':
				$id    = $_POST['id'];
				$color = ucfirst(strtolower($_POST['opc']));
				$type  = $_POST['table'];

				$modelProducts->editColor($id,$type,$color);
			break;
			case 'del_color':
				$id   = $_POST['id'];
				$type = $_POST['table'];

				$modelProducts->delColor($id,$type);
			break;
			case 'add_shape':
				$shape = ucfirst(strtolower($_POST['opc']));
				$load  = isset($_POST['load']);

				$modelProducts->addShape($shape,$load);
			break;
			case 'edit_shape':
				$id    = $_POST['id'];
				$shape = ucfirst(strtolower($_POST['opc']));

				$modelProducts->editShape($id,$shape);
			break;
			case 'del_shape':
				$id    = $_POST['id'];

				$modelProducts->delShape($id);
			break;
			case 'search':
				$type   = $_POST['type'];
				$search = $_POST['search'];

				$modelProducts->search($type,$search);
			break;
			//Load Sink Colors in Products view
			case 'sinkColors':
				$modelProducts->loadColors(2);
			break;
			case 'topColors':
				$modelProducts->loadColors(3);
			break;
			//Load Colors in Products view
			case 'sinkMaterials':
				$modelProducts->loadMaterials(2);
			break;
			case 'topMaterials':
				$modelProducts->loadMaterials(3);
			break;
			case 'shapes':
				$modelProducts->loadShapes();
			break;
		endswitch;
	endif;
endif;
?>