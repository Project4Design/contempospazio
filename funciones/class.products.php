<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Products{
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

	//Buscar todos los Cabinets
	public function consulta_gabinetes()
	{
    $query = Query::run("SELECT * FROM cabinets");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	//Consultar todos los Sinks or Tops
	public function consultaProduct($type)
	{
    $query = Query::run("SELECT * FROM products WHERE prod_type = $type");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//

	//Consultar todos los accesorios
	public function consulta_accessories()
	{
    $query = Query::run("SELECT * FROM accessories");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//Accesories

	//Obtener toda la informacion de un cabinet especifico
	public function obtener_gabi($cabinet)
	{
		$query = Query::prun("SELECT * FROM cabinets WHERE id_gabi = ? LIMIT 1",array("i",$cabinet));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	public function obtenerProd($product){
			$query = Query::prun("SELECT p.*,c.color_name,m.mate_name,s.shape_name FROM products AS p
															INNER JOIN materials AS m ON m.id_material = p.id_material
															INNER JOIN colors AS c ON c.id_color = p.id_color
															LEFT JOIN shapes AS s ON s.id_shape = p.id_shape
															WHERE p.id_product = ? LIMIT 1",array("i",$product));

		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	public function obtener_accessory($id){
		$query = Query::prun("SELECT * FROM accessories WHERE id_accessory = ?",array("i",$id));

		return (object) $query->result->fetch_array(MYSQLI_ASSOC);
	}

	//Agregar los products
	public function add($tipo,$prod,$foto)
	{

		if($this->nivel){
			if($foto){
				$img = new img();
				$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
				$foto = $tmp->name;
			}else{ $foto = NULL; }

			switch ($tipo) {
				case 1:
					$result = $this->addCabinet($prod->description,$foto);
				break;
				case 2:
					$result = $this->addProduct(2,$prod->color,$prod->material,$prod->shape,$prod->name,NULL,$prod->price,$foto);
				break;
				case 3:
					$result = $this->addProduct(3,$prod->color,$prod->material,NULL,$prod->name,$prod->manufacture,$prod->price,$foto);
				break;
				case 4:
					$result = $this->addAccessory($prod->name,$prod->price,$foto);
				break;
			}
			$this->rh->setResponse($result->response,$result->msj);
			$this->rh->data = $result->data;
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//add

	//Agregar cabinet
	public function addCabinet($description,$foto){
		$query = Query::prun("INSERT INTO cabinets (gabi_descripcion,gabi_foto) values (?,?)",array("ss",$description,$foto));

		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=products&opc=cabi&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}//=======================================================================================================================

	//Agregar Product
	public function addProduct($type,$color,$material,$shape,$name,$manufacture,$price,$foto){
		$query = Query::prun("SELECT prod_name FROM products WHERE prod_type = ? AND prod_name = ?",array("is",$type,$name));

		if($query->result->num_rows){
			$response = false;
			$msj = "There is another product already registered with the same name.";
			$data = NULL;
		}else{
			$query = Query::prun("INSERT INTO products (prod_type,id_color,id_material,id_shape,prod_name,prod_manufacture,prod_price,prod_foto) VALUES (?,?,?,?,?,?,?,?)",
																	array("iiiisdds",$type,$color,$material,$shape,$name,$manufacture,$price,$foto));
			if($query->response){
				$opc = ($type==2) ? 'sink' : 'top';
				$response = true;
				$msj = "Product added.";
				$data = "?ver=products&opc={$opc}&id=".$query->id;
			}else{
				$response = false;
				$msj = "An error has occcurred.";
				$data = NULL;
			}
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

	//Agregar Accessory
	public function addAccessory($name,$price,$foto){
		$query = Query::prun("INSERT INTO accessories (acce_name,acce_price,acce_foto) VALUES (?,?,?)",
																array("sds",$name,$price,$foto));
		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=products&opc=acce&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

	//Editar producto
	public function edit($cabinet,$descripcion,$foto)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT gabi_foto FROM cabinets WHERE id_gabi = ?",array("i",$cabinet));

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

				$query = Query::prun("UPDATE cabinets SET
																			gabi_descripcion = ?,
																			gabi_foto        = ?
																	WHERE id_gabi = ?",array("ssi",$descripcion,$foto,$cabinet));

				if($query->response){
					$this->rh->setResponse(true,"Changes has been saved.");
					if($cambia === false && $old != ""){unlink("../images/productos/".$old);}
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Product not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//Edit

	//Obtener todos los Items de un cabinet especifico
	public function items($cabinet)
	{
    $query = Query::run("SELECT * FROM cabinets_items WHERE id_gabi = '$cabinet'");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	//Agregar un Item a un cabinet especifico
	public function add_item($labor,$cabinet,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw)
	{
		$query = Query::prun("SELECT id_gabi FROM cabinets WHERE id_gabi = ? LIMIT 1",array("i",$cabinet));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM cabinets_items WHERE gp_codigo = ? LIMIT 1",array("s",$codigo));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"This item code already exist.");
			}else{
				$query = Query::prun("INSERT INTO cabinets_items (id_gabi,gp_labor,gp_codigo,gp_gs,gp_mgc,gp_rbs,gp_esms,gp_ws,gp_miw)
																			VALUES (?,?,?,?,?,?,?,?,?)",array("iisdddddd",$cabinet,$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw));
				if($query->response){
					$this->rh->setResponse(true,"Item added.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}
		}else{
			$this->rh->setResponse(false,"Product not found.");
		}

		echo json_encode($this->rh);
	}//add_item

	//==============================================|| Edit Methods ||==========================================================
	public function edit_item($id,$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw)
	{
		$query = Query::prun("SELECT id_gp FROM cabinets_items WHERE id_gp = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM cabinets_items WHERE gp_codigo = ? AND id_gp != ? LIMIT 1",array("si",$codigo,$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"This item already exist.");
			}else{
				$query = Query::prun("UPDATE cabinets_items SET
																			gp_labor  = ?,
																			gp_codigo = ?,
																			gp_gs     = ?,
																			gp_mgc    = ?,
																			gp_rbs    = ?,
																			gp_esms   = ?,
																			gp_ws     = ?,
																			gp_miw    = ?
															WHERE id_gp = ? LIMIT 1",array("isddddddi",$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw,$id));

				if($query->response){
					$this->rh->setResponse(true,"Changes has been saved.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}	
			}
		}else{
			$this->rh->setResponse(false,"Item not found.");
		}

		echo json_encode($this->rh);
	}

	//Edit Product
	public function editProduct($id,$color,$material,$shape,$name,$manufacture,$price,$foto)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT prod_foto FROM products WHERE id_product = ?",array("i",$id));

			if($query->result->num_rows>0){
				$old = $query->result->fetch_object();
				$old = $old->prod_foto;

				if($foto){
					$img  = new img();
					$tmp  = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
					$foto = $tmp->name;
					
					$cambia = false; //Cambiar foto en la vista
				}else{ $foto = $old; $cambia=true; }

				$query = Query::prun("UPDATE products SET
																		id_color         = ?,
																		id_material      = ?,
																		id_shape         = ?,
																		prod_name        = ?,
																		prod_manufacture = ?,
																		prod_price       = ?,
																		prod_foto        = ?
																	WHERE id_product = ?",
																	array("iiisddsi",$color,$material,$shape,$name,$manufacture,$price,$foto,$id));
				if($query->response){
					if(!$cambia && is_null($old) === false){unlink("../images/productos/".$old);}
					$this->rh->setResponse(true,"Changes has been saved.");
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Product not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//EditProduct

	//Edit_top
	public function editAccessory($id,$name,$price,$foto)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT acce_foto FROM accessories WHERE id_accessory = ?",array("i",$id));

			if($query->result->num_rows>0){
				$old = $query->result->fetch_array(MYSQLI_ASSOC);
				$old = $old['acce_foto'];

				if($foto){
					$img = new img();
					$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
					$foto = $tmp->name;
					
					$cambia = false; //Cambiar foto
				}else{ $foto = $old; $cambia=true; }

				$query = Query::prun("UPDATE accessories SET
																		acce_name  = ?,
																		acce_price = ?,
																		acce_foto  = ?
																	WHERE id_accessory = ?",
																	array("sdsi",$name,$price,$foto,$id));
				if($query->response){
					if(!$cambia && $old!=""){unlink("../images/productos/".$old);}
					$this->rh->setResponse(true,"Changes has been saved.");
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Accessory not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//Edit_accessories
	//==========================================================================================================================

	//Consultar todo hacerca de un item especifico
	public function get_item($item)
	{
		$query = Query::prun("SELECT * FROM cabinets_items WHERE id_gp = ? LIMIT 1",array("i",$item));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		$this->rh->setResponse(true);
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}//get_item

	public function load($cabinet)
	{
		$query = Query::prun("SELECT * FROM cabinets_items WHERE id_gabi = ?",array("i",$cabinet));
		$data = "";

		if($query->result->num_rows>0){
			$config = new Configuration();
			$i = 1;
			while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				if(!isset($row["gp_gs"]) || $row["gp_gs"]==0){$gs ="<span style=\"color:red\">N/A</span>"; }else{ $gs = Base::Format($row["gp_gs"],2,".",",");}
				if(!isset($row["gp_mgc"]) || $row["gp_mgc"]==0){$mgc ="<span style=\"color:red\">N/A</span>"; }else{ $mgc= Base::Format($row["gp_mgc"],2,".",",");}
				if(!isset($row["gp_rbs"]) || $row["gp_rbs"]==0){$rbs ="<span style=\"color:red\">N/A</span>"; }else{ $rbs= Base::Format($row["gp_rbs"],2,".",",");}
				if(!isset($row["gp_esms"]) || $row["gp_esms"]==0){$esms ="<span style=\"color:red\">N/A</span>"; }else{ $esms= Base::Format($row["gp_esms"],2,".",",");}
				if(!isset($row["gp_ws"]) || $row["gp_ws"]==0){$ws ="<span style=\"color:red\">N/A</span>"; }else{ $ws= Base::Format($row["gp_ws"],2,".",",");}
				if(!isset($row["gp_miw"]) || $row["gp_miw"]==0){$miw ="<span style=\"color:red\">N/A</span>"; }else{ $miw= Base::Format($row["gp_miw"],2,".",",");}
				$labor = Base::Format($config->labor($row['gp_labor']),2,".",",");
				$data .= "<tr>
                  <td class=\"text-center\">{$i}</td>
                  <td class=\"text-center\">{$labor}</td>
                  <td class=\"text-right\">{$row['gp_codigo']}</td>
                  <td class=\"text-right\">{$gs}</td>
                  <td class=\"text-right\">{$mgc}</td>
                  <td class=\"text-right\">{$rbs}</td>
                  <td class=\"text-right\">{$esms}</td>
                  <td class=\"text-right\">{$ws}</td>
                  <td class=\"text-right\">{$miw}</td>
                  <td class=\"text-center\">
                  	<div class=\"btn-group\">
                      <button type=\"button\" class=\"btn btn-default btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                      <ul class=\"dropdown-menu\" role=\"menu\">
                        <li><a id=\"{$row['id_gp']}\" role=\"button\" onclick=\"edit(this.id)\">Edit</a></li>
                        <li role=\"separator\" class=\"divider\"></li>
                        <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#deleteModal\" data-id=\"{$row['id_gp']}\" data-codigo=\"{$row['gp_codigo']}\" style=\"color:red\"><b>Delete</b></a></li>
                      </ul>
                    </div>
                  </td>
                </tr>";
        $i++;
			}
		}else{
			$data = "<tr><td class=\"text-center\" colspan=\"9\">There are no Items to show.</td></tr>";
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);

	}

	//==============================================|| DELETE METHODS ||===================================================
	public function del_cabi($id)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT gabi_foto FROM cabinets WHERE id_gabi = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod = (object)$query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM cabinets WHERE id_gabi = ?",array("i",$id));

				if($query->response){
					if(!is_null($prod->gabi_foto)){unlink("../images/productos/".$prod->gabi_foto);}
					Query::run("DELETE FROM cabinets_items WHERE id_gabi = $id");

					$this->rh->setResponse(true,"Cabinet deleted.",true,"inicio.php?ver=products");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Cabinet not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function del_item($id)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_gp FROM cabinets_items WHERE id_gp = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$query = Query::prun("DELETE FROM cabinets_items WHERE id_gp = ?",array("i",$id));

				if($query->response){
					$this->rh->setResponse(true,"Item deleted.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Item not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function delProduct($id){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT prod_foto FROM products WHERE id_product = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod  = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM products WHERE id_product = ? LIMIT 1",array("i",$id));

				if($query->response){
					if(!is_null($prod->prod_foto)){unlink("../images/productos/".$prod->prod_foto);}
					$this->rh->setResponse(true,"Product deleted.",true,"inicio.php?ver=products");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Product not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function del_accessory($id){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT acce_foto FROM accessories WHERE id_accessory = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod  = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM accessories WHERE id_accessory = ? LIMIT 1",array("i",$id));

				if($query->response){
					if(!is_null($prod->acce_foto)){unlink("../images/productos/".$prod->acce_foto);}
					$this->rh->setResponse(true,"Accessory deleted.",true,"inicio.php?ver=products");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Accessory not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}
	//=========================================================================================================================

	public function selectMaterials($type){
		$query = Query::run("SELECT * FROM materials WHERE mate_type = $type");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function selectColors($type){
		$query = Query::run("SELECT * FROM colors WHERE color_type = $type");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function selectShapes(){
		$query = Query::run("SELECT * FROM shapes");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function listMaterials($type){

		$materials = $this->selectMaterials($type);
		$data = "";

		if(count($materials)>0){
			foreach ($materials as $d) {
				$data.="
					<li>
				    {$d->mate_name}
				  </li>
				";
			}

			$this->rh->setResponse(true);
			$this->rh->data = $data;
		}else{
			$this->rh->setResponse(false,"There are no materials to show.");
		}

		echo json_encode($this->rh);
	}

	public function listColors($type){
		$colors = $this->selectColors($type);
		$data = "";

		if(count($colors)>0){
			foreach ($colors as $d) {
				$data.="
					<li>
				    {$d->color_name}
				  </li>
				";
			}

			$this->rh->setResponse(true);
			$this->rh->data = $data;
		}else{
			$this->rh->setResponse(false,"There are no colors to show.");
		}

		echo json_encode($this->rh);
	}

	public function listShapes(){
		$shapes = $this->selectShapes();
		$data = "";

		if(count($shapes)>0){
			foreach ($shapes as $d) {
				$data.="
					<li>
				    {$d->shape_name}
				  </li>
				";
			}

			$this->rh->setResponse(true);
			$this->rh->data = $data;
		}else{
			$this->rh->setResponse(false,"There are no shapes to show.");
		}

		echo json_encode($this->rh);
	}

	//Agregar color a Tops o Sinks
	public function addColor($type,$color,$load=false){
		
		if($this->nivel == "A"){

			$query = Query::prun("INSERT INTO colors (color_type,color_name) VALUES (?,?)",array("is",$type,$color));

			$select = "";

			if($query->response){
				//Si cargamos los colores para el modal de colores en agregar productos
				if(!$load){
					$option = "<option value=\"\">Select...</option>";
					$colores = $this->selectColors($type);

					$select = ($type == "2") ? "freg_color" : "tope_color";

					foreach ($colores as $d) {
						$option .= "<option value=\"{$d->id_color}\">{$d->color_name}</option>";
					}
				}

				$this->rh->setResponse(true,"Color added.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}

			if(!$load){$data = array("select"=>$select,"options"=>$option);}else{$data="";}
			
			$this->rh->data = $data;
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	//Agregar Material a Tops or Sinks
	public function addMaterial($type,$material,$load=false){

		if($this->nivel == "A"){
			$query = Query::prun("INSERT INTO materials  (mate_type,mate_name) VALUES (?,?)",array("is",$type,$material));
			$select = "";
				
			if($query->response){
				$option = "<option value=\"\">Select...</option>";
				if(!$load){
					$colors = $this->selectMaterials($type);

					$select = ($type == "2") ? "freg_material" : "tope_material";

					foreach ($colors as $d) {
						$option .= "<option value=\"{$d->id_material}\">{$d->mate_name}</option>";
					}
				}

				$this->rh->setResponse(true,"Material added.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}

			if(!$load){$data = array("select"=>$select,"options"=>$option);}else{$data="";}

			$this->rh->data = $data;

		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function addShape($shape,$load=false){

		if($this->nivel == "A"){
			$query = Query::prun("INSERT INTO shapes  (shape_name) VALUES (?)",array("s",$shape));
			$select = "";
				
			if($query->response){
				$option = "<option value=\"\">Select...</option>";
				if(!$load){
					$shapes = $this->selectShapes();

					$select = "sink_shape";

					foreach ($shapes as $d) {
						$option .= "<option value=\"{$d->id_shape}\">{$d->shape_name}</option>";
					}
				}

				$this->rh->setResponse(true,"Shape added.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}

			if(!$load){$data = array("select"=>$select,"options"=>$option);}else{$data="";}

			$this->rh->data = $data;

		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function search($type,$search){
		$array = "";
		switch ($type) {
			case "1":
				$query = Query::prun("SELECT g.*,gp.* FROM cabinets_items AS gp INNER JOIN cabinets AS g ON g.id_gabi = gp.id_gabi WHERE gp.gp_codigo = ? LIMIT 1",
														array("s",$search));

				if($query->result->num_rows>0){
					$labor = new Configuration();
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$tr ="
						<tr class=\"item-list\">
							<td index=\"3\">".Base::Format($prod->gp_gs,2,".",",")."</td>
							<td index=\"4\">".Base::Format($prod->gp_mgc,2,".",",")."</td>
							<td index=\"5\">".Base::Format($prod->gp_rbs,2,".",",")."</td>
							<td index=\"6\">".Base::Format($prod->gp_esms,2,".",",")."</td>
							<td index=\"7\">".Base::Format($prod->gp_ws,2,".",",")."</td>
							<td index=\"8\">".Base::Format($prod->gp_miw,2,".",",")."</td> 
					";
					$array = array("id"=>$prod->id_gabi,"id_item"=>$prod->id_gp,"type"=>$type,"item"=>$prod->gp_codigo,"labor"=>$labor->labor($prod->gp_labor),"desc"=>$prod->gabi_descripcion,"tr"=>$tr,"foto"=>$prod->gabi_foto);
					$this->rh->setResponse(true);
				}else{
					$this->rh->setResponse(false);
				}
			break;
			case "2":
				$query = Query::prun("SELECT p.*,c.color_name,m.mate_name,s.shape_name FROM products AS p
																INNER JOIN materials AS m ON m.id_material = p.id_material
																INNER JOIN colors AS c ON c.id_color = p.id_color
																INNER JOIN shapes AS s ON s.id_shape = p.id_shape
																WHERE p.prod_name = ? LIMIT 1",array("s",$search));
				if($query->result->num_rows>0){
					$prod = (object)$query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->prod_name,"other-shape"=>$prod->shape_name,"other-mat"=>$prod->mate_name,"other-color"=>$prod->color_name,"other-price"=>$prod->prod_price);
					$array = array("id"=>$prod->id_product,"type"=>$type,"s"=>$other,"foto"=>$prod->prod_foto);
					$this->rh->setResponse(true);
				}else{
					$this->rh->setResponse(false);
				}
			break;
			case "3":
				$query = Query::prun("SELECT p.*,c.color_name,m.mate_name FROM products AS p
																INNER JOIN materials AS m ON m.id_material = p.id_material
																INNER JOIN colors AS c ON c.id_color = p.id_color
																WHERE p.prod_name = ? LIMIT 1",array("s",$search));
				if($query->result->num_rows>0){
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->prod_name,"other-mat"=>$prod->mate_name,"other-color"=>$prod->color_name,"other-manu"=>$prod->prod_manufacture,"other-price"=>$prod->prod_price);
					$array = array("id"=>$prod->id_product,"type"=>$type,"s"=>$other,"foto"=>$prod->prod_foto);
					$this->rh->setResponse(true);
				}else{	
					$this->rh->setResponse(false);
				}
			break;
			case "4":
				$query = Query::prun("SELECT * FROM accessories AS a WHERE a.acce_name = ? LIMIT 1",array("s",$search));
				if($query->result->num_rows>0){
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->acce_name,"other-price"=>$prod->acce_price);
					$array = array("id"=>$prod->id_accessory,"type"=>$type,"s"=>$other,"foto"=>$prod->acce_foto);
					$this->rh->setResponse(true);
				}else{	
					$this->rh->setResponse(false);
				}
			break;
		}

		$this->rh->data = $array;

		echo json_encode($this->rh);
	}

	//Load Sink Colors in Products view
	function loadColors($type){
		$query = Query::run("SELECT c.*,COUNT(p.id_product) AS products FROM colors AS c LEFT JOIN products AS p ON p.id_color = c.id_color WHERE c.color_type = $type GROUP BY c.id_color");
		$data = "";
		
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class=\"text-center\">{$i}</td>
            <td id=\"c{$type}-{$row['id_color']}\">{$row['color_name']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_color']}\" class=\"editColor\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delColorModal\" data-id=\"{$row['id_color']}\" data-table=\"{$type}\" style=\"color:red\"><b>Delete</b></a></li>
                </ul>
              </div>
            </td>
          </tr>
				";
				$i++;
			}
		}else{
			$data .="<tr><td class=\"text-center\" colspan=\"4\">There are no colors to show</td></tr>" ;
		}

		$this->rh->data = $data;
		$this->rh->setResponse(true);

		echo json_encode($this->rh);
	}//loadColors

	//Load Materials in Products view
	function loadMaterials($type){
		$query = Query::run("SELECT m.*,COUNT(p.id_product) AS products FROM materials AS m LEFT JOIN products AS p ON p.id_material = m.id_material WHERE mate_type = $type GROUP BY m.id_material");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class=\"text-center\">{$i}</td>
            <td id=\"m{$type}-{$row['id_material']}\">{$row['mate_name']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_material']}\" class=\"editMaterial\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delMaterialModal\" data-id=\"{$row['id_material']}\" data-table=\"{$type}\" style=\"color:red\"><b>Delete</b></a></li>
                </ul>
              </div>
            </td>
          </tr>
				";
				$i++;
			}
		}else{
			$data .="<tr><td class=\"text-center\" colspan=\"4\">There are no materials to show</td></tr>" ;
		}

		$this->rh->data = $data;
		$this->rh->setResponse(true);

		echo json_encode($this->rh);
	}//Materials

	//Load Shape in Products view
	function loadShapes(){
		$query = Query::run("SELECT s.*,COUNT(p.id_product) AS products FROM shapes AS s LEFT JOIN products AS p ON p.id_shape = s.id_shape GROUP BY s.id_shape");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class=\"text-center\">{$i}</td>
            <td id=\"s-{$row['id_shape']}\">{$row['shape_name']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_shape']}\" class=\"editShape\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delShapeModal\" data-id=\"{$row['id_shape']}\" style=\"color:red\"><b>Delete</b></a></li>
                </ul>
              </div>
            </td>
          </tr>
				";
				$i++;
			}
		}else{
			$data .="<tr><td class=\"text-center\" colspan=\"4\">There are no shapes to show</td></tr>" ;
		}

		$this->rh->data = $data;
		$this->rh->setResponse(true);

		echo json_encode($this->rh);
	}//SinkShape

	//editColor
	public function editColor($id,$type,$color){

		if($this->nivel == "A"){
			$query = Query::prun("UPDATE colors SET color_name = ? WHERE color_type = ? AND id_color = ?",array("sii",$color,$type,$id));

			if($query->response){
				$this->rh->setResponse(true,"Changes saved.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		$this->rh->data = $type;

		echo json_encode($this->rh);
	}//Edit color

	//edit_material
	public function editMaterial($id,$type,$material){

		if($this->nivel== "A"){
			$query = Query::prun("UPDATE materials SET mate_name = ? WHERE mate_type = ? AND id_material = ?",array("sii",$material,$type,$id));

			if($query->response){
				$this->rh->setResponse(true,"Changes saved.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

			$this->rh->data = $type;

		echo json_encode($this->rh);
	}//Edit material

	//editShape
	public function editShape($id,$shape){

		if($this->nivel== "A"){
			$query = Query::prun("UPDATE shapes SET shape_name = ? WHERE id_shape = ?",array("si",$shape,$id));

			if($query->response){
				$this->rh->setResponse(true,"Changes saved.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//Edit shape

	public function delColor($id,$type){

		if($this->nivel == "A"){
			$query = Query::prun("SELECT id_product FROM products WHERE prod_type = ? AND id_color = ? LIMIT 1",array("ii",$type,$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"There are products with this color assigned to them.");
			}else{
				$query = Query::prun("DELETE FROM colors WHERE color_type = ? AND id_color = ? LIMIT 1",array("ii",$type,$id));

				if($query->response){
					$this->rh->setResponse(true,"Color deleted.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		$this->rh->data = $type;

		echo json_encode($this->rh);
	}//============================Del_color

	//del_material
	public function delMaterial($id,$type){
		if($this->nivel == "A"){

			$query = Query::prun("SELECT id_product FROM products WHERE prod_type = ? AND id_material = ? LIMIT 1",array("ii",$type,$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"There are products with this material assigned to them.");
			}else{
				$query = Query::prun("DELETE FROM materials WHERE mate_type = ? AND id_material = ? LIMIT 1",array("ii",$type,$id));

				if($query->response){
					$this->rh->setResponse(true,"Material deleted.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}

			$this->rh->data = $type;
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//============================Del_material

	//del_shape
	public function delShape($id){
		if($this->nivel == "A"){

			$query = Query::prun("SELECT id_product FROM products WHERE id_shape = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"There are products with this shape assigned to them.");
			}else{
				$query = Query::prun("DELETE FROM shapes WHERE id_shape = ? LIMIT 1",array("i",$id));

				if($query->response){
					$this->rh->setResponse(true,"Shape deleted.");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//============================Del_material

	public function cabinetColor($index){
		switch($index){
			case '3':$color="GS";break;
			case '4':$color="MGC";break;
			case '5':$color="RBS";break;
			case '6':$color="ES & MS";break;
			case '7':$color="WS";break;
			case '8':$color="MIW";break;
		}
		return $color;
	}

	public function lastAdded(){
		$query = Query::run("SELECT cabinets.id_gabi AS id,'cabi' AS opc,'Cabinet' AS product,gabi_descripcion AS name,gabi_foto AS foto,COUNT(ci.id_gp) AS cost, gabi_fecha_reg AS fecha
													FROM cabinets INNER JOIN cabinets_items AS ci ON ci.id_gabi = cabinets.id_gabi
													GROUP BY cabinets.id_gabi
												UNION SELECT id_product,'sink','Sink',prod_name,prod_foto,prod_price,prod_fecha_reg
													FROM products WHERE prod_type = 2 GROUP BY id_product
												UNION SELECT id_product,'top','Top',prod_name,prod_foto,prod_price,prod_fecha_reg
													FROM products WHERE prod_type = 3 GROUP BY id_product
												UNION SELECT id_accessory,'acce','Accessory',acce_name,acce_foto,acce_price,acce_fecha_reg
													FROM accessories
												ORDER BY fecha DESC
												LIMIT 10");
		$data = array();

		if($query->num_rows>0){
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data[] = (object)$row;
			}
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