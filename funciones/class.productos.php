<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Productos{
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
    $query = Query::run("SELECT * FROM gabinetes");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	//Obtener toda la informacion de un gabinete especifico
	public function obtener($gabinete)
	{
		$query = Query::prun("SELECT * FROM gabinetes WHERE id_gabi = ? LIMIT 1",array("i",$gabinete));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	//Agregar
	public function add($descripcion,$foto)
	{
		if($this->nivel){
			if($foto){
				$img = new img();
				$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
				$foto = $tmp->name;
			}else{ $foto = NULL; }

			$query = Query::prun("INSERT INTO gabinetes (gabi_descripcion,gabi_foto) values (?,?)",array("ss",$descripcion,$foto));

			if($query->response){
				$this->rh->setResponse(true,"Producto agregado correctamente.");
				$this->rh->data = "?ver=productos&opc=ver&id=".$query->id;
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error.");
			}
		}else{
			$this->rh->setResponse(false,"No puedes realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//add

	//Editar producto
	public function edit($gabinete,$descripcion,$foto)
	{
		if($this->nivel){
			$query = Query::prun("SELECT gabi_foto FROM gabinetes WHERE id_gabi = ?",array("i",$gabinete));

			if($query->result->num_rows>0){
				$old = $query->result->fetch_array(MYSQLI_ASSOC);
				$old = $old['gabi_foto'];

				if($foto){
					$img = new img();
					$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
					$foto = $tmp->name;
					//Cambiar foto
					$cambia = false;
				}else{ $foto = $old; $cambia=true; }

				$query = Query::prun("UPDATE gabinetes SET
																			gabi_descripcion = ?,
																			gabi_foto        = ?
																	WHERE id_gabi = ?",array("ssi",$descripcion,$foto,$gabinete));

				if($query->response){
					$this->rh->setResponse(true,"Cambios guardados correctamente.");
					if(!$cambia){unlink("../images/productos/".$old);}
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"Ha ocurrido un error.");
				}
			}else{
				$this->rh->setResponse(false,"Producto no encontrado.");
			}
		}else{
			$this->rh->setResponse(false,"No puedes realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//Edit

	//Obtener todos los Items de un gabinete especifico
	public function items($gabinete)
	{
    $query = Query::run("SELECT * FROM gabinetes_prod WHERE id_gabi = '$gabinete'");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	//Agregar un Item a un gabinete especifico
	public function add_item($gabinete,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw)
	{
		$query = Query::prun("SELECT id_gabi FROM gabinetes WHERE id_gabi = ? LIMIT 1",array("i",$gabinete));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE gp_codigo = ? LIMIT 1",array("s",$codigo));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Este codigo de Item ya existe.");
			}else{
				$query = Query::prun("INSERT INTO gabinetes_prod (id_gabi,gp_codigo,gp_gs,gp_mgc,gp_rbs,gp_esms,gp_ws,gp_miw)
																			VALUES (?,?,?,?,?,?,?,?)",array("isdddddd",$gabinete,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw));
				if($query->response){
					$this->rh->setResponse(true,"Item almacenado correctamente.");
				}else{
					$this->rh->setResponse(false,"Ha ocurrido un error.");
				}
			}
		}else{
			$this->rh->setResponse(false,"Articulo no encontrado.");
		}

		echo json_encode($this->rh);
	}//add_item

	public function edit_item($id,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw)
	{
		$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE id_gp = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE gp_codigo = ? AND id_gp != ? LIMIT 1",array("si",$codigo,$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Este codigo de Item ya existe.");
			}else{
				$query = Query::prun("UPDATE gabinetes_prod SET
																			gp_codigo = ?,
																			gp_gs     = ?,
																			gp_mgc    = ?,
																			gp_rbs    = ?,
																			gp_esms   = ?,
																			gp_ws     = ?,
																			gp_miw    = ?
															WHERE id_gp = ? LIMIT 1",array("sddddddi",$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw,$id));

				if($query->response){
					$this->rh->setResponse(true,"Cambios guardados correctamente.");
				}else{
					$this->rh->setResponse(false,"Ha ocurrido un error.");
				}	
			}
		}else{
			$this->rh->setResponse(false,"Item no encontrado.");
		}

		echo json_encode($this->rh);
	}

	//Consultar todo hacerca de un item especifico
	public function get_item($item)
	{
		$query = Query::prun("SELECT * FROM gabinetes_prod WHERE id_gp = ? LIMIT 1",array("i",$item));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		$this->rh->setResponse(true);
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}//get_item

	public function load($gabinete)
	{
		$query = Query::prun("SELECT * FROM gabinetes_prod WHERE id_gabi = ?",array("i",$gabinete));
		$data = "";

		if($query->result->num_rows>0){
			$i = 1;

			while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				if(!isset($row["gp_gs"]) || $row["gp_gs"]==0){$gs ="<span style=\"color:red\">N/A</span>"; }else{ $gs=$row["gp_gs"]; }
				if(!isset($row["gp_mgc"]) || $row["gp_mgc"]==0){$mgc ="<span style=\"color:red\">N/A</span>"; }else{ $mgc=$row["gp_mgc"]; }
				if(!isset($row["gp_rbs"]) || $row["gp_rbs"]==0){$rbs ="<span style=\"color:red\">N/A</span>"; }else{ $rbs=$row["gp_rbs"]; }
				if(!isset($row["gp_esms"]) || $row["gp_esms"]==0){$esms ="<span style=\"color:red\">N/A</span>"; }else{ $esms=$row["gp_esms"]; }
				if(!isset($row["gp_ws"]) || $row["gp_ws"]==0){$ws ="<span style=\"color:red\">N/A</span>"; }else{ $ws=$row["gp_ws"]; }
				if(!isset($row["gp_miw"]) || $row["gp_miw"]==0){$miw ="<span style=\"color:red\">N/A</span>"; }else{ $miw=$row["gp_miw"]; }
				$data .= "<tr>
                  <td class=\"text-center\">".$i."</td>
                  <td class=\"text-right\">".$row["gp_codigo"]."</td>
                  <td class=\"text-right\">".$gs."</td>
                  <td class=\"text-right\">".$mgc."</td>
                  <td class=\"text-right\">".$rbs."</td>
                  <td class=\"text-right\">".$esms."</td>
                  <td class=\"text-right\">".$ws."</td>
                  <td class=\"text-right\">".$miw."</td>
                  <td class=\"text-center\">
                  	<div class=\"btn-group\">
                      <button type=\"button\" class=\"btn btn-default btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        Action <span class=\"caret\"></span>
                      </button>
                      <ul class=\"dropdown-menu\" role=\"menu\">
                        <li>
                          <a id=\"".$row["id_gp"]."\" role=\"button\" onclick=\"edit(this.id)\">Editar</a>
                        </li>
                        <li role=\"separator\" class=\"divider\"></li>
                        <li>
                          <a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#deleteModal\" data-id=\"".$row["id_gp"]."\" data-codigo=\"".$row["gp_codigo"]."\" style=\"color:red\">
                            <b>Eliminar</b>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>";
        $i++;
			}
		}else{
			$data = "<tr><td class=\"text-center\" colspan=\"9\">No hay items para mostrar.</td></tr>";
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);

	}

	public function del_item($id)
	{
		$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE id_gp = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){
			$query = Query::prun("DELETE FROM gabinetes_prod WHERE id_gp = ?",array("i",$id));

			if($query->response){
				$this->rh->setResponse(true,"Item eliminado correctamente.");
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error.");
			}
		}else{
			$this->rh->setResponse(false,"Item no encontrado.");
		}

		echo json_encode($this->rh);
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}

}//Class Productos

$modelGabi = new Productos();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'add':
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$descripcion = $_POST['descripcion'];

				$modelGabi->add($descripcion,$foto);
			break;
			case 'edit':
				$gabinete    = $_POST['gabinete'];
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$descripcion = $_POST['descripcion'];

				$modelGabi->edit($gabinete,$descripcion,$foto);
			break;
			case 'add_item':
				$gabinete = $_POST['gabinete'];
				$codigo   = $_POST['codigo'];
				$gs       = $_POST['gs'];
				$mgc      = $_POST['mgc'];
				$rbs      = $_POST['rbs'];
				$esms     = $_POST['esms'];
				$ws       = $_POST['ws'];
				$miw      = $_POST['miw'];

				$modelGabi->add_item($gabinete,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
			break;
			case 'edit_item':
				$id     = $_POST['item'];
				$codigo = $_POST['codigo'];
				$gs     = $_POST['gs'];
				$mgc    = $_POST['mgc'];
				$rbs    = $_POST['rbs'];
				$esms   = $_POST['esms'];
				$ws     = $_POST['ws'];
				$miw    = $_POST['miw'];

				$modelGabi->edit_item($id,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
			break;
			case 'get_item':
				$id = $_POST["id"];

				$modelGabi->get_item($id);
			break;
			case 'load':
				$id = $_POST["id"];

				$modelGabi->load($id);
			break;
			case 'del_item':
				$id = $_POST["item"];

				$modelGabi->del_item($id);
			break;
		endswitch;
	endif;
endif;
?>