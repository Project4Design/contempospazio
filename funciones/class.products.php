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

	//Consultar todos los fregaderos
	public function consulta_fregaderos()
	{
    $query = Query::run("SELECT * FROM fregaderos");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//Fregaderos

	//Consultar todos los topes
	public function consulta_topes()
	{
    $query = Query::run("SELECT * FROM topes");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//Topes

	//Consultar todos los topes
	public function consulta_accessories()
	{
    $query = Query::run("SELECT * FROM accessories");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//Topes

	//Obtener toda la informacion de un cabinet especifico
	public function obtener_gabi($cabinet)
	{
		$query = Query::prun("SELECT * FROM cabinets WHERE id_gabi = ? LIMIT 1",array("i",$cabinet));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	public function obtener_prod($tipo,$producto){
		if($tipo){
			$query = Query::prun("SELECT * FROM fregaderos AS f
															INNER JOIN fregaderos_materiales AS fm ON fm.id_fm = f.id_fm
															INNER JOIN fregaderos_colores AS fc ON fc.id_fc = f.id_fc
															WHERE f.id_fregadero = ? LIMIT 1",array("i",$producto));
		}else{
			$query = Query::prun("SELECT t.*,tc.*,tm.* FROM topes AS t
															INNER JOIN topes_materiales AS tm ON tm.id_tm = t.id_tm
															INNER JOIN topes_colores AS tc ON tc.id_tc = t.id_tc
															WHERE t.id_tope = ? LIMIT 1",array("i",$producto));
		}

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
					$result = $this->add_cabinet($prod->descripcion,$foto);
				break;
				case 2:
					$result = $this->add_sink($prod->color,$prod->material,$prod->name,$prod->forma,$prod->price,$foto);
				break;
				case 3:
					$result = $this->add_top($prod->color,$prod->material,$prod->name,$prod->manufacture,$prod->price,$foto);
				break;
				case 4:
					$result = $this->add_accessory($prod->name,$prod->price,$foto);
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
	public function add_cabinet($descripcion,$foto){
		$query = Query::prun("INSERT INTO cabinets (gabi_descripcion,gabi_foto) values (?,?)",array("ss",$descripcion,$foto));

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

	//Agregar Fregadero
	public function add_sink($color,$material,$name,$forma,$price,$foto){
		$query = Query::prun("INSERT INTO fregaderos (id_fc,id_fm,freg_nombre,freg_forma,freg_costo,freg_foto) VALUES (?,?,?,?,?,?)",
																array("iisids",$color,$material,$nombre,$forma,$price,$foto));
		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=products&opc=sink&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

	//Agregar Tope
	public function add_top($color,$material,$name,$manufacture,$price,$foto){
		$query = Query::prun("INSERT INTO topes (id_tc,id_tm,tope_nombre,tope_manufacture,tope_costo,tope_foto) VALUES (?,?,?,?,?,?)",
																array("iisdds",$color,$material,$name,$manufacture,$price,$foto));
		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=products&opc=top&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

	//Agregar Tope
	public function add_accessory($name,$price,$foto){
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
				$this->rh->setResponse(false,"Este codigo de Item ya existe.");
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

	//Edit_sink
	public function edit_sink($id,$name,$shape,$material,$color,$price,$foto)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT freg_foto FROM fregaderos WHERE id_fregadero = ?",array("i",$id));

			if($query->result->num_rows>0){
				$old = $query->result->fetch_array(MYSQLI_ASSOC);
				$old = $old['freg_foto'];

				if($foto){
					$img = new img();
					$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
					$foto = $tmp->name;
					
					$cambia = false; //Cambiar foto
				}else{ $foto = $old; $cambia=true; }

				$query = Query::prun("UPDATE fregaderos SET
																		id_fc       = ?,
																		id_fm       = ?,
																		freg_nombre = ?,
																		freg_forma  = ?,
																		freg_costo  = ?,
																		freg_foto   = ?
																	WHERE id_fregadero = ?",
																	array("iisidsi",$color,$material,$name,$shape,$price,$foto,$id));
				if($query->response){
					if(!$cambia){unlink("../images/productos/".$old);}
					$this->rh->setResponse(true,"Changes has been saved.");
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Sink not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}
	//Edit_top
	public function edit_top($id,$name,$material,$color,$manufacture,$price,$foto)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT tope_foto FROM topes WHERE id_tope = ?",array("i",$id));

			if($query->result->num_rows>0){
				$old = $query->result->fetch_array(MYSQLI_ASSOC);
				$old = $old['tope_foto'];

				if($foto){
					$img = new img();
					$tmp = $img->load($foto['foto']['tmp_name'],$foto['foto']['name'],"../images/productos");
					$foto = $tmp->name;
					
					$cambia = false; //Cambiar foto
				}else{ $foto = $old; $cambia=true; }

				$query = Query::prun("UPDATE topes SET
																		id_tc            = ?,
																		id_tm            = ?,
																		tope_nombre      = ?,
																		tope_manufacture = ?,
																		tope_costo       = ?,
																		tope_foto        = ?
																	WHERE id_tope      = ?",
																	array("iisddsi",$color,$material,$name,$manufacture,$price,$foto,$id));
				if($query->response){
					if(!$cambia){unlink("../images/productos/".$old);}
					$this->rh->setResponse(true,"Changes has been saved.");
					$this->rh->data = $cambia;
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Sink not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//Edit_top

	//Edit_top
	public function edit_accessory($id,$name,$price,$foto)
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
			$config = new Configuracion();
			$i = 1;
			while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				if(!isset($row["gp_gs"]) || $row["gp_gs"]==0){$gs ="<span style=\"color:red\">N/A</span>"; }else{ $gs=$row["gp_gs"]; }
				if(!isset($row["gp_mgc"]) || $row["gp_mgc"]==0){$mgc ="<span style=\"color:red\">N/A</span>"; }else{ $mgc=$row["gp_mgc"]; }
				if(!isset($row["gp_rbs"]) || $row["gp_rbs"]==0){$rbs ="<span style=\"color:red\">N/A</span>"; }else{ $rbs=$row["gp_rbs"]; }
				if(!isset($row["gp_esms"]) || $row["gp_esms"]==0){$esms ="<span style=\"color:red\">N/A</span>"; }else{ $esms=$row["gp_esms"]; }
				if(!isset($row["gp_ws"]) || $row["gp_ws"]==0){$ws ="<span style=\"color:red\">N/A</span>"; }else{ $ws=$row["gp_ws"]; }
				if(!isset($row["gp_miw"]) || $row["gp_miw"]==0){$miw ="<span style=\"color:red\">N/A</span>"; }else{ $miw=$row["gp_miw"]; }
				$labor = $config->labor($row['gp_labor']);
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
					if(!is_null($prod->gabi_foto)){unlink("../images/productos/".$prod->foto_foto);}
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

	public function del_top($id){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT tope_foto FROM topes WHERE id_tope = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod  = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM topes WHERE id_tope = ? LIMIT 1",array("i",$id));

				if($query->response){
					if(!is_null($prod->tope_foto)){unlink("../images/productos/".$prod->tope_foto);}
					$this->rh->setResponse(true,"Top deleted.",true,"inicio.php?ver=products");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Top not found.");	
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}

	public function del_sink($id){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT freg_foto FROM fregaderos WHERE id_fregadero = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod  = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM fregaderos WHERE id_fregadero = ? LIMIT 1",array("i",$id));

				if($query->response){
					if(!is_null($prod->freg_foto)){unlink("../images/productos/".$prod->freg_foto);}
					$this->rh->setResponse(true,"Sink deleted.",true,"inicio.php?ver=products");
				}else{
					$this->rh->setResponse(false,"An error has occcurred.");
				}
			}else{
				$this->rh->setResponse(false,"Sink not found.");	
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
					if(!is_null($prod->acce_foto)){unlink("../images/productos/".$prod->freg_foto);}
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

	public function topesColor(){
		$query = Query::run("SELECT * FROM topes_colores");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function topesMateriales(){
		$query = Query::run("SELECT * FROM topes_materiales");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function fregColor(){
		$query = Query::run("SELECT * FROM fregaderos_colores");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function fregMateriales(){
		$query = Query::run("SELECT * FROM fregaderos_materiales");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function tope_list_mat(){
		$materiales = $this->topesMateriales();
		$data = "";

		if(count($materiales)>0){
			foreach ($materiales as $d) {
				$data.="
					<li>
				    {$d->tm_nombre}
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

	public function tope_list_color(){
		$colores = $this->topesColor();
		$data = "";

		if(count($colores)>0){
			foreach ($colores as $d) {
				$data.="
					<li>
				    {$d->tc_nombre}
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

	public function freg_list_mat(){
		$materiales = $this->fregMateriales();
		$data = "";

		if(count($materiales)>0){
			foreach ($materiales as $d) {
				$data.="
					<li>
				    {$d->fm_nombre}
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

	public function freg_list_color(){
		$colores = $this->fregColor();
		$data = "";

		if(count($colores)>0){
			foreach ($colores as $d) {
				$data.="
					<li>
				    {$d->fc_nombre}
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

	//Agregar color a Topes o Fregaderos
	public function add_color($color,$table,$load=false){
		if($table=="1"){
			$sql = "INSERT INTO fregaderos_colores (fc_nombre) VALUES (?)";
			$select  = "freg_color";
		}else{
			$sql ="INSERT INTO topes_colores (tc_nombre) VALUES (?)";
			$select  = "tope_color";
		}

		$query = Query::prun($sql,array("s",$color));

		$option = "<option value=\"\">Select...</option>";

		if($query->response){
			//Si cargamos los colores para el modal de colores en agregar productos
			if(!$load){
				if($table=="1"){
					$colores = $this->fregColor();
					foreach ($colores as $d) {
						$option .= "<option value=\"{$d->id_fc}\">{$d->fc_nombre}</option>";
					}
				}else{
					$colores = $this->topesColor();
					foreach ($colores as $d) {
						$option .= "<option value=\"{$d->id_tc}\">{$d->tc_nombre}</option>";
					}
				}
			}//===================================================================

			$this->rh->setResponse(true,"Color added.");
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		if(!$load){$data = array("select"=>$select,"options"=>$option);}else{$data="";}
		
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	//Agregar Material a Toes o Fregaderos
	public function add_material($material,$table,$load=false){
		if($table=="1"){
			$sql = "INSERT INTO fregaderos_materiales (fm_nombre) VALUES (?)";
			$select  = "freg_material";
		}else{
			$sql ="INSERT INTO topes_materiales (tm_nombre) VALUES (?)";
			$select  = "tope_material";
		}

		$query = Query::prun($sql,array("s",$material));

		$option = "<option value=\"\">Select...</option>";

		if($query->response){
			if(!$load){
				if($table=="1"){
					$colores = $this->fregMateriales();
					foreach ($colores as $d) {
						$option .= "<option value=\"{$d->id_fm}\">{$d->fm_nombre}</option>";
					}
				}else{
					$colores = $this->topesMateriales();
					foreach ($colores as $d) {
						$option .= "<option value=\"{$d->id_tm}\">{$d->tm_nombre}</option>";
					}
				}
			}

			$this->rh->setResponse(true,"Material added.");
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		if(!$load){$data = array("select"=>$select,"options"=>$option);}else{$data="";}

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function shape($forma)
	{
    switch ($forma) {
      case '1': $forma = "Oval"; break;
      case '2': $forma = "Rectangular"; break;
      case '3': $forma = "Square"; break;
    }
    return $forma;
  }

	public function search($type,$search){
		$array = "";
		switch ($type) {
			case "1":
				$query = Query::prun("SELECT g.*,gp.* FROM cabinets_items AS gp INNER JOIN cabinets AS g ON g.id_gabi = gp.id_gabi WHERE gp.gp_codigo = ? LIMIT 1",
														array("s",$search));

				if($query->result->num_rows>0){
					$labor = new Configuracion();
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$tr ="
						<tr class=\"item-list\">
							<td index=\"3\">{$prod->gp_gs}</td>
							<td index=\"4\">{$prod->gp_mgc}</td>
							<td index=\"5\">{$prod->gp_rbs}</td>
							<td index=\"6\">{$prod->gp_esms}</td>
							<td index=\"7\">{$prod->gp_ws}</td>
							<td index=\"8\">{$prod->gp_miw}</td> 
					";
					$array = array("id"=>$prod->id_gabi,"id_item"=>$prod->id_gp,"type"=>$type,"item"=>$prod->gp_codigo,"labor"=>$labor->labor($prod->gp_labor),"desc"=>$prod->gabi_descripcion,"tr"=>$tr,"foto"=>$prod->gabi_foto);
					$this->rh->setResponse(true);
				}else{
					$this->rh->setResponse(false);
				}
			break;
			case "2":
				$query = Query::prun("SELECT f.*,fm.*,fc.* FROM fregaderos AS f
																INNER JOIN fregaderos_materiales AS fm ON fm.id_fm = f.id_fm
																INNER JOIN fregaderos_colores AS fc ON fc.id_fc = f.id_fc
																WHERE f.freg_nombre = ? LIMIT 1",array("s",$search));
				if($query->result->num_rows>0){
					$prod = (object)$query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->freg_nombre,"other-shape"=>$this->shape($prod->freg_forma),"other-mat"=>$prod->fm_nombre,"other-color"=>$prod->fc_nombre,"other-price"=>$prod->freg_costo);
					$array = array("id"=>$prod->id_fregadero,"type"=>$type,"s"=>$other,"foto"=>$prod->freg_foto);
					$this->rh->setResponse(true);
				}else{
					$this->rh->setResponse(false);
				}
			break;
			case "3":
				$query = Query::prun("SELECT t.*,tc.*,tm.* FROM topes AS t
																INNER JOIN topes_materiales AS tm ON tm.id_tm = t.id_tm
																INNER JOIN topes_colores AS tc ON tc.id_tc = t.id_tc
																WHERE t.tope_nombre = ? LIMIT 1",array("s",$search));
				if($query->result->num_rows>0){
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->tope_nombre,"other-mat"=>$prod->tm_nombre,"other-color"=>$prod->tc_nombre,"other-manu"=>$prod->tope_manufacture,"other-price"=>$prod->tope_costo);
					$array = array("id"=>$prod->id_tope,"type"=>$type,"s"=>$other,"foto"=>$prod->tope_foto);
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
	function sinkColors(){
		$query = Query::run("SELECT sc.*,COUNT(s.id_fregadero) AS products FROM fregaderos_colores AS sc LEFT JOIN fregaderos AS s ON s.id_fc = sc.id_fc GROUP BY sc.id_fc");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class\"text-center\">{$i}</td>
            <td id=\"sinkColor{$row['id_fc']}\">{$row['fc_nombre']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_fc']}\" class=\"editSinkColor\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delColorModal\" data-id=\"{$row['id_fc']}\" data-table=\"1\" style=\"color:red\"><b>Delete</b></a></li>
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
	}//SinkColors

	//Load Sink Colors in Products view
	function topColors(){
		$query = Query::run("SELECT tc.*,COUNT(t.id_tope) AS products FROM topes_colores AS tc LEFT JOIN topes AS t ON t.id_tc = tc.id_tc GROUP BY tc.id_tc");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class\"text-center\">{$i}</td>
            <td id=\"topColor{$row['id_tc']}\">{$row['tc_nombre']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_tc']}\" class=\"editTopColor\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delColorModal\" data-id=\"{$row['id_tc']}\" data-table=\"0\" style=\"color:red\"><b>Delete</b></a></li>
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
	}//TopColors

	//Load Sink Materials in Products view
	function sinkMaterials(){
		$query = Query::run("SELECT sm.*,COUNT(s.id_fregadero) AS products FROM fregaderos_materiales AS sm LEFT JOIN fregaderos AS s ON s.id_fm = sm.id_fm GROUP BY sm.id_fm");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class\"text-center\">{$i}</td>
            <td id=\"sinkMaterial{$row['id_fm']}\">{$row['fm_nombre']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_fm']}\" class=\"editSinkMaterial\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delMaterialModal\" data-id=\"{$row['id_fm']}\" data-table=\"1\" style=\"color:red\"><b>Delete</b></a></li>
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
	}//SinkMaterials

	//Load Top Materials in Products view
	function topMaterials(){
		$query = Query::run("SELECT tm.*,COUNT(t.id_tope) AS products FROM topes_materiales AS tm LEFT JOIN topes AS t ON t.id_tm = tm.id_tm GROUP BY tm.id_tm");
		$data = "";
		if($query->num_rows>0){
			$i = 1;
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data .= "
					<tr>
            <td class\"text-center\">{$i}</td>
            <td id=\"topMaterial{$row['id_tm']}\">{$row['tm_nombre']}</td>
            <td class=\"text-center\">{$row['products']}</td>
            <td class=\"text-center\">
              <div class=\"btn-group\">
                <button type=\"button\" class=\"btn btn-default btn-sm btn-flat dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Action <span class=\"caret\"></span></button>
                <ul class=\"dropdown-menu\" role=\"menu\">
                  <li><a id=\"{$row['id_tm']}\" class=\"editTopMaterial\" role=\"button\">Edit</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a type=\"button\" role=\"button\" data-toggle=\"modal\" data-target=\"#delMaterialModal\" data-id=\"{$row['id_tm']}\" data-table=\"0\" style=\"color:red\"><b>Delete</b></a></li>
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
	}//SinkColors

	//edit_color
	public function edit_color($id,$color,$table){
		if($table=="1"){
			$sql = "UPDATE fregaderos_colores SET fc_nombre = ? WHERE id_fc = ? LIMIT 1";
		}else{
			$sql ="UPDATE topes_colores SET tc_nombre = ? WHERE id_tc = ? LIMIT 1";
		}

		$query = Query::prun($sql,array("si",$color,$id));

		if($query->response){
			$this->rh->setResponse(true,"Changes saved.");
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		$this->rh->data = $table;

		echo json_encode($this->rh);
	}//Edit color

	//edit_material
	public function edit_material($id,$material,$table){
		if($table=="1"){
			$sql = "UPDATE fregaderos_materiales SET fm_nombre = ? WHERE id_fm = ? LIMIT 1";
		}else{
			$sql ="UPDATE topes_materiales SET tm_nombre = ? WHERE id_tm = ? LIMIT 1";
		}

		$query = Query::prun($sql,array("si",$material,$id));

		if($query->response){
			$this->rh->setResponse(true,"Changes saved.");
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		$this->rh->data = $table;

		echo json_encode($this->rh);
	}//Edit material

	public function del_color($id,$table){
		if($table=="1"){
			$sql    = "SELECT id_fregadero FROM fregaderos WHERE id_fc = ? LIMIT 1";
			$delSql = "DELETE FROM fregaderos_colores WHERE id_fc = ? LIMIT 1";
		}else{
			$sql    = "SELECT id_tope FROM topes WHERE id_tc = ? LIMIT 1";
			$delSql ="DELETE FROM topes_colores WHERE id_tc = ? LIMIT 1";
		}

		$query = Query::prun($sql,array("i",$id));

		if($query->result->num_rows>0){
			$this->rh->setResponse(false,"There are products with this color assigned to them.");
		}else{
			$query = Query::prun($delSql,array("i",$id));

			if($query->response){
				$this->rh->setResponse(true,"Color deleted.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}
		}

		$this->rh->data = $table;

		echo json_encode($this->rh);
	}//============================Del_color

	//del_material
	public function del_material($id,$table){
		if($table=="1"){
			$sql    = "SELECT id_fregadero FROM fregaderos WHERE id_fm = ? LIMIT 1";
			$delSql = "DELETE FROM fregaderos_materiales WHERE id_fm = ? LIMIT 1";
		}else{
			$sql    = "SELECT id_tope FROM topes WHERE id_tm = ? LIMIT 1";
			$delSql ="DELETE FROM topes_materiales WHERE id_tm = ? LIMIT 1";
		}

		$query = Query::prun($sql,array("i",$id));

		if($query->result->num_rows>0){
			$this->rh->setResponse(false,"There are products with this material assigned to them.");
		}else{
			$query = Query::prun($delSql,array("i",$id));

			if($query->response){
				$this->rh->setResponse(true,"Material deleted.");
			}else{
				$this->rh->setResponse(false,"An error has occcurred.");
			}
		}

		$this->rh->data = $table;

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
												UNION SELECT id_fregadero,'sink','Sink',freg_nombre,freg_foto,freg_costo,freg_fecha_reg
													FROM fregaderos
												UNION SELECT id_tope,'top','Top',tope_nombre,tope_foto,tope_costo,tope_fecha_reg
													FROM topes
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
						$prod->descripcion = $_POST['descripcion'];
					break;
					case 2:
					case 3:
						//Fregaderos o Topes
						$prod->forma       = isset($_POST['forma'])?$_POST['forma']:NULL; //Solo los fregaderos
						$prod->name      = $_POST['name'];
						$prod->material    = $_POST['material'];
						$prod->color       = $_POST['color'];
						$prod->manufacture = isset($_POST['manufacture'])?$_POST['manufacture']:NULL; //Solo los fregaderos
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
				$id       = $_POST['sink'];
				$foto     = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name     = $_POST['sink_name'];
				$shape    = $_POST['sink_shape'];
				$material = $_POST['sink_material'];
				$color    = $_POST['sink_color'];
				$price    = $_POST['sink_price'];

				$modelProducts->edit_sink($id,$name,$shape,$material,$color,$price,$foto);
			break;
			case 'edit_top':
				$id         = $_POST['top'];
				$foto       = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name       = $_POST['top_name'];
				$material   = $_POST['top_material'];
				$manufacure = $_POST['top_manufacure'];
				$color      = $_POST['top_color'];
				$price      = $_POST['top_price'];

				$modelProducts->edit_top($id,$name,$material,$color,$manufacture,$price,$foto);
			break;
			case 'edit_acce':
				$id    = $_POST['accessory'];
				$foto  = ($_FILES['foto']["name"])?$_FILES:NULL;
				$name  = $_POST['acce_name'];
				$price = $_POST['acce_price'];

				$modelProducts->edit_accessory($id,$name,$price,$foto);
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
				$modelProducts->del_top($id);
			break;
			case 'del_sink':
				$id = $_POST['sink'];
				$modelProducts->del_sink($id);
			break;
			case 'del_acce':
				$id = $_POST['accessory'];
				$modelProducts->del_accessory($id);
			break;
			case 'tope_list_mat':
				$modelProducts->tope_list_mat();
			break;
			case 'tope_list_color':
				$modelProducts->tope_list_color();
			break;
			case 'freg_list_mat':
				$modelProducts->freg_list_mat();
			break;
			case 'freg_list_color':
				$modelProducts->freg_list_color();
			break;
			case 'add_material':
				$material = ucfirst(strtolower($_POST['opc']));
				$table    = $_POST['table'];
				$load  = isset($_POST['load']);

				$modelProducts->add_material($material,$table,$load);
			break;
			case 'edit_material':
				$id    = $_POST['id'];
				$material = ucfirst(strtolower($_POST['opc']));
				$table = $_POST['table'];

				$modelProducts->edit_material($id,$material,$table);
			break;
			case 'del_material':
				$id    = $_POST['id'];
				$table = $_POST['table'];

				$modelProducts->del_material($id,$table);
			break;
			case 'add_color':
				$color = ucfirst(strtolower($_POST['opc']));
				$table = $_POST['table'];
				$load  = isset($_POST['load']);

				$modelProducts->add_color($color,$table,$load);
			break;
			case 'edit_color':
				$id    = $_POST['id'];
				$color = ucfirst(strtolower($_POST['opc']));
				$table = $_POST['table'];

				$modelProducts->edit_color($id,$color,$table);
			break;
			case 'del_color':
				$id    = $_POST['id'];
				$table = $_POST['table'];

				$modelProducts->del_color($id,$table);
			break;
			case 'search':
				$type   = $_POST['type'];
				$search = $_POST['search'];

				$modelProducts->search($type,$search);
			break;
			//Load Sink Colors in Products view
			case 'sinkColors':
				$modelProducts->sinkColors();
			break;
			case 'topColors':
				$modelProducts->topColors();
			break;
			//Load Colors in Products view
			case 'sinkMaterials':
				$modelProducts->sinkMaterials();
			break;
			case 'topMaterials':
				$modelProducts->topMaterials();
			break;
		endswitch;
	endif;
endif;
?>