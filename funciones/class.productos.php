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

	//Buscar todos los gabinetes
	public function consulta_gabinetes()
	{
    $query = Query::run("SELECT * FROM gabinetes");
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

	//Obtener toda la informacion de un gabinete especifico
	public function obtener_gabi($gabinete)
	{
		$query = Query::prun("SELECT * FROM gabinetes WHERE id_gabi = ? LIMIT 1",array("i",$gabinete));
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

	//Agregar los productos
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
					$result = $this->add_gabinete($prod->descripcion,$foto);
				break;
				case 2:
					$result = $this->add_fregadero($prod->color,$prod->material,$prod->nombre,$prod->forma,$prod->costo,$foto);
				break;
				case 3:
					$result = $this->add_tope($prod->color,$prod->material,$prod->nombre,$prod->costo,$foto);
				break;
			}
			$this->rh->setResponse($result->response,$result->msj);
			$this->rh->data = $result->data;
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this action.");
		}

		echo json_encode($this->rh);
	}//add

	//Agregar gabinete
	public function add_gabinete($descripcion,$foto){
		$query = Query::prun("INSERT INTO gabinetes (gabi_descripcion,gabi_foto) values (?,?)",array("ss",$descripcion,$foto));

		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=productos&opc=gabi&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}//=======================================================================================================================

	//Agregar Fregadero
	public function add_fregadero($color,$material,$nombre,$forma,$costo,$foto){
		$query = Query::prun("INSERT INTO fregaderos (id_fc,id_fm,freg_nombre,freg_forma,freg_costo,freg_foto) VALUES (?,?,?,?,?,?)",
																array("iisids",$color,$material,$nombre,$forma,$costo,$foto));
		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=productos&opc=freg&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

	//Agregar Tope
	public function add_tope($color,$material,$nombre,$costo,$foto){
		$query = Query::prun("INSERT INTO topes (id_tc,id_tm,tope_nombre,tope_costo,tope_foto) VALUES (?,?,?,?,?)",
																array("iisds",$color,$material,$nombre,$costo,$foto));
		if($query->response){
			$response = true;
			$msj = "Product added.";
			$data = "?ver=productos&opc=tope&id=".$query->id;
		}else{
			$response=false;
			$msj = "An error has occcurred.";
			$data = NULL;
		}

		return (object)array("response"=>$response,"msj"=>$msj,"data"=>$data);
	}

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
					$this->rh->setResponse(true,"Changes has been saved.");
					if(!$cambia){unlink("../images/productos/".$old);}
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
	public function add_item($labor,$gabinete,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw)
	{
		$query = Query::prun("SELECT id_gabi FROM gabinetes WHERE id_gabi = ? LIMIT 1",array("i",$gabinete));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE gp_codigo = ? LIMIT 1",array("s",$codigo));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Este codigo de Item ya existe.");
			}else{
				$query = Query::prun("INSERT INTO gabinetes_prod (id_gabi,gp_labor,gp_codigo,gp_gs,gp_mgc,gp_rbs,gp_esms,gp_ws,gp_miw)
																			VALUES (?,?,?,?,?,?,?,?,?)",array("iisdddddd",$gabinete,$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw));
				if($query->response){
					$this->rh->setResponse(true,"Item deleted.");
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
		$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE id_gp = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){
			$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE gp_codigo = ? AND id_gp != ? LIMIT 1",array("si",$codigo,$id));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Este codigo de Item ya existe.");
			}else{
				$query = Query::prun("UPDATE gabinetes_prod SET
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
			$this->rh->setResponse(false,"Item no encontrado.");
		}

		echo json_encode($this->rh);
	}

	//Edit_sink
	public function edit_sink($id,$name,$shape,$material,$color,$price)
	{
		if($this->nivel="A"){
			$query = Query::prun("SELECT id_fregadero FROM fregaderos WHERE id_fregadero = ?",array("i",$id));

			if($query->result->num_rows>0){
				$query = Query::prun("UPDATE fregaderos SET
																		id_fc       = ?,
																		id_fm       = ?,
																		freg_nombre = ?,
																		freg_forma = ?,
																		freg_costo = ?
																	WHERE id_fregadero = ?",
																	array("iisidi",$color,$material,$name,$shape,$price,$id));
				if($query->response){
					$this->rh->setResponse(true,"Changes has been saved.");
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
	//==========================================================================================================================

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
			$query = Query::prun("SELECT gabi_foto FROM gabinetes WHERE id_gabi = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$prod = (object)$query->result->fetch_array(MYSQLI_ASSOC);
				$query = Query::prun("DELETE FROM gabinetes WHERE id_gabi = ?",array("i",$id));

				if($query->response){
					if(!is_null($prod->gabi_foto)){unlink("../images/productos/".$prod->foto_foto);}
					Query::run("DELETE FROM gabinetes_prod WHERE id_gabi = $id");

					$this->rh->setResponse(true,"Cabinet deleted.",true,"inicio.php?ver=productos");
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
			$query = Query::prun("SELECT id_gp FROM gabinetes_prod WHERE id_gp = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				$query = Query::prun("DELETE FROM gabinetes_prod WHERE id_gp = ?",array("i",$id));

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
					$this->rh->setResponse(true,"Top deleted.",true,"inicio.php?ver=productos");
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
					$this->rh->setResponse(true,"Sink deleted.",true,"inicio.php?ver=productos");
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
	public function add_color($color,$table){
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
			$this->rh->setResponse(true,"Color added.");

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
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		$data = array("select"=>$select,"options"=>$option);
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	//Agregar Material a Toes o Fregaderos
	public function add_material($material,$table){
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
			$this->rh->setResponse(true,"Material added.");

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
		}else{
			$this->rh->setResponse(false,"An error has occcurred.");
		}

		$data = array("select"=>$select,"options"=>$option);
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function shape($forma)
	{
    switch ($forma) {
      case '1': $forma = "Ovalado"; break;
      case '2': $forma = "Rectangular"; break;
      case '3': $forma = "Cuadrado"; break;
    }
    return $forma;
  }

	public function search($type,$search){
		$array = "";
		switch ($type) {
			case "1":
				$query = Query::prun("SELECT g.*,gp.* FROM gabinetes_prod AS gp INNER JOIN gabinetes AS g ON g.id_gabi = gp.id_gabi WHERE gp.gp_codigo = ?",
														array("s",$search));

				if($query->result->num_rows>0){
					$labor = new Configuracion();
					$prod = (object) $query->result->fetch_array(MYSQLI_ASSOC);
					$tr ="
						<tr class=\"item-list\">
							<td>{$prod->gp_gs}</td>
							<td>{$prod->gp_mgc}</td>
							<td>{$prod->gp_rbs}</td>
							<td>{$prod->gp_esms}</td>
							<td>{$prod->gp_ws}</td>
							<td>{$prod->gp_miw}</td> 
					";
					$array = array("type"=>$type,"item"=>$prod->gp_codigo,"labor"=>$labor->labor($prod->gp_labor),"desc"=>$prod->gabi_descripcion,"tr"=>$tr,"foto"=>$prod->gabi_foto);
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
					$array = array("type"=>$type,"s"=>$other,"foto"=>$prod->freg_foto);
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
					$prod = (object)$query->result->fetch_array(MYSQLI_ASSOC);
					$other = array("other-name"=>$prod->tope_nombre,"other-mat"=>$prod->tm_nombre,"other-color"=>$prod->tc_nombre,"other-price"=>$prod->tope_costo);
					$array = array("type"=>$type,"s"=>$other,"foto"=>$prod->tope_foto);
					$this->rh->setResponse(true);
				}else{	
					$this->rh->setResponse(false);
				}
			break;
		}

		$this->rh->data = $array;

		echo json_encode($this->rh);
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}

}//Class Productos

$modelProductos = new Productos();

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
						$prod->forma    = isset($_POST['forma'])?$_POST['forma']:NULL; //Solo los fregaderos
						$prod->nombre   = $_POST['nombre'];
						$prod->material = $_POST['material'];
						$prod->color    = $_POST['color'];
						$prod->costo    = $_POST['costo'];
					break;
				}

				$modelProductos->add($tipo,$prod,$foto);
			break;
			case 'edit':
				$gabinete    = $_POST['gabinete'];
				$foto        = ($_FILES['foto']["name"])?$_FILES:NULL;
				$descripcion = $_POST['descripcion'];

				$modelProductos->edit($gabinete,$descripcion,$foto);
			break;
			case 'edit_sink':
				$id       = $_POST['sink'];
				$name     = $_POST['freg_name'];
				$shape    = $_POST['freg_shape'];
				$material = $_POST['freg_material'];
				$color    = $_POST['freg_color'];
				$price    = $_POST['freg_price'];

				$modelProductos->edit_sink($id,$name,$shape,$material,$color,$price);
			break;
			case 'add_item':
				$labor   = $_POST['labor'];
				$gabinete = $_POST['gabinete'];
				$codigo   = $_POST['codigo'];
				$gs       = $_POST['gs'];
				$mgc      = $_POST['mgc'];
				$rbs      = $_POST['rbs'];
				$esms     = $_POST['esms'];
				$ws       = $_POST['ws'];
				$miw      = $_POST['miw'];

				$modelProductos->add_item($labor,$gabinete,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
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

				$modelProductos->edit_item($id,$labor,$codigo,$gs,$mgc,$rbs,$esms,$ws,$miw);
			break;
			case 'get_item':
				$id = $_POST["id"];

				$modelProductos->get_item($id);
			break;
			case 'load':
				$id = $_POST["id"];

				$modelProductos->load($id);
			break;
			case 'del_cabi':
				$id = $_POST["cabi"];

				$modelProductos->del_cabi($id);
			break;
			case 'del_item':
				$id = $_POST["item"];

				$modelProductos->del_item($id);
			break;
			case 'del_top':
				$id = $_POST['top'];

				$modelProductos->del_top($id);
			break;
			case 'del_sink':
				$id = $_POST['sink'];

				$modelProductos->del_sink($id);
			break;
			case 'tope_list_mat':
				$modelProductos->tope_list_mat();
			break;
			case 'tope_list_color':
				$modelProductos->tope_list_color();
			break;
			case 'freg_list_mat':
				$modelProductos->freg_list_mat();
			break;
			case 'freg_list_color':
				$modelProductos->freg_list_color();
			break;
			case 'add_material':
				$material = ucfirst(strtolower($_POST['opc']));
				$table    = $_POST['table'];

				$modelProductos->add_material($material,$table);
			break;
			case 'add_color':
				$color = ucfirst(strtolower($_POST['opc']));
				$table = $_POST['table'];

				$modelProductos->add_color($color,$table);
			break;
			case 'search':
				$type   = $_POST['type'];
				$search = $_POST['search'];

				$modelProductos->search($type,$search);
			break;
			default:
				$modelProductos->fdefault();
			break;
		endswitch;
	endif;
endif;
?>