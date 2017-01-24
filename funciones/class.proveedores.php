<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Proveedores{
	private $rh;
	private $user;
	private $nivel;
	private $delete;
	private $fecha;
	private $hora;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->delete = array("(",")","-","/","_","-"," ","+");
		$this->fecha  = Base::Fecha();
		$this->hora   = Base::Hora();
	}

	public function consulta()
	{

    $query = Query::run("SELECT * FROM proveedores WHERE prov_eliminado = 0");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	public function perfil()
	{
    $query = Query::run("SELECT * FROM proveedores WHERE id_user = $this->user");

    if($query->num_rows >0){
    	$data = (object)$query->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

    return $data;
	}//perfil

	public function obtener($id)
	{

	    $query = Query::prun("SELECT * FROM proveedores WHERE id_prov = ?",array("i",$id));

	    if($query->result->num_rows >0){
	    	$data = (object)$query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

	    return $data;
	}//obtener

	public function add($nivel,$estado,$nombres,$apellidos,$email,$pass,$telefono)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT prov_email FROM proveedores WHERE prov_eliminado = ? AND prov_email = ? LIMIT 1",array("is","0",$email));

			if($query->result->num_rows>0){
	    	$this->rh->setResponse(false,"Correo ya registrado.");
			}else{
		  	$query = Query::prun("INSERT INTO proveedores (prov_nivel,prov_estado,prov_nombres,prov_apellidos,prov_email,prov_pass,prov_telefono,prov_fecha_reg,prov_hora_reg)
											VALUES(?,?,?,?,?,?,?,?,?)",
											array("sssssssssss",$nivel,$estado,$nombres,$apellidos,$email,$pass,$telefono,$this->fecha,$this->hora));
		  	if($query->response){
					$this->rh->setResponse(true,"Registro exitoso! <a href=\"?ver=proveedores&opc=ver&id={$query->id}\">Ver usuario</a>");
		  	}else{
					$this->rh->setResponse(false,"Oops! Ah ocurrido un error!");
		  	}
			}
		}else{
			$this->sh->setResponse(false,"No tienes permisos para realizar esta accion");
		}

		echo json_encode($this->rh);
		
	}//add

	public function edit_admin($id,$estado,$nivel,$nombres,$apellidos,$email,$telefono)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT prov_email FROM proveedores WHERE prov_eliminado = ? AND prov_email = ? AND id_user != ? LIMIT 1",array("isi","0",$email,$id));
		
			if($query->result->num_rows>0){
			  $this->rh->setResponse(false,"Ya existe un usuario registrado con este email.");
			}else{
		  	$query = Query::prun("UPDATE proveedores SET
		  												prov_nivel     = ?,
		  												prov_estado    = ?,
															prov_nombres   = ?,
															prov_apellidos = ?,
															prov_email     = ?,
															prov_telefono  = ?
														WHERE id_user = ? LIMIT 1",
														array("ssssssi",$nivel,$estado,$nombres,$apellidos,$email,$telefono,$id));
		  	if($query->response){
					$this->rh->setResponse(true,"Cambios guardados con exito!",true,"inicio.php?ver=proveedores&opc=ver&id=".$id);
			  }else{
			    $this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
			  }
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);

	}//Modificar admin

	public function edit($id,$nombres,$apellidos,$email,$telefono)
	{
		$query = Query::prun("SELECT prov_email FROM proveedores WHERE prov_eliminado = ? AND prov_email = ? AND id_user != ? LIMIT 1",array("isi","0",$email,$id));
	
		if($query->result->num_rows>0){
		  $this->rh->setResponse(false,"Ya existe un usuario registrado con este email.");
		}else{
	  	$query = Query::prun("UPDATE proveedores SET
														prov_nombres   = ?,
														prov_apellidos = ?,
														prov_email     = ?,
														prov_telefono  = ?
													WHERE id_user = ? LIMIT 1",
													array("ssssi",$nombres,$apellidos,$email,$telefono,$this->user));
	  	if($query->response){
				$this->rh->setResponse(true,"Cambios guardados con exito!",true,"inicio.php?ver=perfil");
		  }else{
		    $this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
		  }
		}
		echo json_encode($this->rh);

	}//Modificar usuario


	public function newpass($actual,$nueva){

		$query = Query::run("SELECT prov_pass FROM proveedores WHERE id_user = $this->user LIMIT 1");

		if($query->num_rows>0){
			$us = (object) $query->fetch_array(MYSQLI_ASSOC);

			if(password_verify($actual,$us->prov_pass)){
				$query = Query::prun("UPDATE proveedores SET prov_pass = ? WHERE id_user = ? LIMIT 1",array("si",$nueva,$this->user));

				if($query->response){
					$this->rh->setResponse(true,"Contraseña actualizada");
				}else{
					$this->rh->setResponse(false,"Ha ocurrido un error. Intente mas tarde");
				}
			}else{
				$this->rh->setResponse(false,"Contraseña incorrecta");
			}
		}else{
			$this->rh->setResponse(false,"Ha ocurrido un error");
		}

		echo json_encode($this->rh);
	}//newpass

	public function activar($id,$estado){

		$query = Query::prun("SELECT id_user FROM proveedores WHERE id_user = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){

			$query = Query::prun("UPDATE proveedores SET prov_estado = ? WHERE id_user = ? LIMIT 1",array("si",$estado,$id));

			if($query->response){
				if($estado == "A"){
					$a = "Activado";
					$r = 1;
					$b = "<button id=\"btn-activar\" class=\"btn btn-flat btn-danger\" data-toggle=\"modal\" data-target=\"#activarModal\" data-title=\"Desactivar\" data-val=\"I\"></i><i class=\"fa fa-close\" aria-hidden=\"true\">&nbsp;Desactivar</button>";
				}else{
					$a = "Desactivado";
					$b = "<button id=\"btn-activar\" class=\"btn btn-flat btn-success\" data-toggle=\"modal\" data-target=\"#activarModal\" data-title=\"Activar\" data-val=\"A\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Activar</button>";
					$r = 0;
				}
				$this->rh->setResponse(true,"El usuario ha sido <b>".$a."</b>");
				$this->rh->data = array("e"=>$r,"b"=>$b);
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
			}
		}else{
			$this->rh->setResponse(false,"Usuario no encontrado.");
		}

		echo json_encode($this->rh);
	}

	public function reestablecer($id,$nueva){

		if($this->nivel=="A"){

			$query = Query::prun("SELECT id_user FROM proveedores WHERE id_user = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				if($nueva){
					$pass = "";
					$hash = $nueva;
				}else{
					$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
					$pass  = substr( str_shuffle( $chars ),0,10);
					$hash  = password_hash($pass, PASSWORD_DEFAULT);
				}

				$done = Query::prun("UPDATE proveedores SET prov_pass = ? WHERE id_user = ? LIMIT 1",array("si",$hash,$id));

				if($done->response){
					$this->rh->setResponse(true,"La contraseña ha sido cambiada.");
					$this->rh->data = $pass;
				}else{
					$this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
				}
			}else{
				$this->rh->setResponse(false,"Usuario no encontrao.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);

	}//Reestablecer

	public function eliminar($usuario){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_user FROM proveedores WHERE id_user = ? AND prov_eliminado = ?",array("ii",$usuario,"0"));

			if($query->result->num_rows>0){
				$query = Query::prun("UPDATE proveedores SET prov_eliminado = ? WHERE id_user = ?",array("ii","1",$usuario));

				if($query->response){
					$this->rh->setResponse(false,"Usuario eliminado!",true,"inicio.php?ver=proveedores");		
				}else{
					$this->rh->setResponse(false,"Ah ocurrido un error.");	
				}

			}else{
				$this->rh->setResponse(false,"Usuario no encontrado");	
			}
		}else{
			$this->rh->setResponse(false,"No puedes realiza esta accion");
		}

		echo json_encode($this->rh);
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}

}//Class Proveedores

$modelUser = new Proveedores();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'add':
				$estado    = $_POST["estado"];
				$nivel     = $_POST["nivel"];
				$nombres   = ucwords(strtolower($_POST["nombres"]));
				$apellidos = ucwords(strtolower($_POST["apellidos"]));
				$email     = ucfirst(strtolower($_POST["email"]));
				$telefono  = $_POST["telefono"];
				$pass      = password_hash($_POST['pass'], PASSWORD_DEFAULT);

				$modelUser->add($nivel,$estado,$nombres,$apellidos,$email,$pass,$telefono);
			break;
				
			case 'edit_admin':
				$id        = $_POST["id"];
				$estado    = $_POST["estado"];
				$nivel     = $_POST["nivel"];
				$nombres   = ucwords(strtolower($_POST["nombres"]));
				$apellidos = ucwords(strtolower($_POST["apellidos"]));
				$email     = ucfirst(strtolower($_POST["email"]));
				$telefono  = $_POST["telefono"];
				
				$modelUser->edit_admin($id,$estado,$nivel,$nombres,$apellidos,$email,$telefono);
			break;

			case 'edit':
				$id        = $_POST["id"];
				$cedula    = $_POST["cedula"];
				$nombres   = ucwords(strtolower($_POST["nombres"]));
				$apellidos = ucwords(strtolower($_POST["apellidos"]));
				$sexo      = $_POST["sexo"];
				$email     = ucfirst(strtolower($_POST["email"]));
				$telefono  = $_POST["telefono"];
				
				$modelUser->edit($id,$nombres,$apellidos,$email,$telefono);
			break;

			case 'newpass':
				$actual = $_POST['p1'];
				$nueva  = $_POST['p2'];
				$nueva  = password_hash($nueva, PASSWORD_DEFAULT);

				$modelUser->newpass($actual,$nueva);
			break;

			case 'activar':
				$id     = $_POST['id'];
				$estado = $_POST['estado'];

				$modelUser->activar($id,$estado);
			break;

			case 'recuperar':
				$id = $_POST['id'];

				if(isset($_POST['filtro'])){
					$nueva = password_hash($_POST['p1'], PASSWORD_DEFAULT);
				}else{
					$nueva = NULL;
				}

				$modelUser->reestablecer($id,$nueva);
			break;
			case 'eliminar':
				$usuario = $_POST['id'];
				$modelUser->eliminar($usuario);
			break;

			default:
				$modelUser->fdefault();
			break;
		endswitch;
	endif;
endif;
?>