<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Usuarios{
	private $rh;
	private $user;
	private $nivel;
	private $delete;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
		$this->delete = array("(",")","-","/","_","-"," ","+");
	}

	public function consulta()
	{
    $query = Query::run("SELECT * FROM usuarios WHERE user_eliminado = 0");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	public function perfil()
	{
    $query = Query::run("SELECT * FROM usuarios WHERE id_user = $this->user");

    if($query->num_rows >0){
    	$data = (object)$query->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

    return $data;
	}//perfil

	public function obtener($id)
	{
	    $query = Query::prun("SELECT * FROM usuarios WHERE id_user = ?",array("i",$id));

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
			$query = Query::prun("SELECT user_email FROM usuarios WHERE user_eliminado = ? AND user_email = ? LIMIT 1",array("is","0",$email));

			if($query->result->num_rows>0){
	    	$this->rh->setResponse(false,"Email already registered.");
			}else{
		  	$query = Query::prun("INSERT INTO usuarios (user_nivel,user_estado,user_nombres,user_apellidos,user_email,user_pass,user_telefono)
											VALUES(?,?,?,?,?,?,?)",
											array("sssssss",$nivel,$estado,$nombres,$apellidos,$email,$pass,$telefono));
		  	if($query->response){
					$this->rh->setResponse(true,"User registered! <a href=\"?ver=users&opc=ver&id={$query->id}\">See details</a>");
		  	}else{
					$this->rh->setResponse(false,"An error has ocurred.");
		  	}
			}
		}else{
			$this->sh->setResponse(false,"You don't have permission to make this accion.");
		}

		echo json_encode($this->rh);

	}//add

	public function edit_admin($id,$estado,$nivel,$nombres,$apellidos,$email,$telefono)
	{
		if($this->nivel=="A"){
			$query = Query::prun("SELECT user_email FROM usuarios WHERE user_eliminado = ? AND user_email = ? AND id_user != ? LIMIT 1",array("isi","0",$email,$id));

			if($query->result->num_rows>0){
			  $this->rh->setResponse(false,"Email already registered.");
			}else{
		  	$query = Query::prun("UPDATE usuarios SET
		  												user_nivel     = ?,
		  												user_estado    = ?,
															user_nombres   = ?,
															user_apellidos = ?,
															user_email     = ?,
															user_telefono  = ?
														WHERE id_user = ? LIMIT 1",
														array("ssssssi",$nivel,$estado,$nombres,$apellidos,$email,$telefono,$id));
		  	if($query->response){
					$this->rh->setResponse(true,"Changes have been saved.",true,"inicio.php?ver=users&opc=ver&id=".$id);
			  }else{
			    $this->rh->setResponse(false,"An error has ocurred.");
			  }
			}
		}else{
			$this->sh->setResponse(false,"You don't have permission to make this accion.");
		}

		echo json_encode($this->rh);

	}//Modificar admin

	public function edit($nombres,$apellidos,$email,$telefono)
	{
		$query = Query::prun("SELECT user_email FROM usuarios WHERE user_eliminado = ? AND user_email = ? AND id_user != ? LIMIT 1",array("isi","0",$email,$this->user));

		if($query->result->num_rows>0){
		  $this->rh->setResponse(false,"Ya existe un usuario registrado con este email.");
		}else{
	  	$query = Query::prun("UPDATE usuarios SET
														user_nombres   = ?,
														user_apellidos = ?,
														user_email     = ?,
														user_telefono  = ?
													WHERE id_user = ? LIMIT 1",
													array("ssssi",$nombres,$apellidos,$email,$telefono,$this->user));
	  	if($query->response){
				$this->rh->setResponse(true,"Changes has been saved!",true,"inicio.php?ver=profile");
		  }else{
		    $this->rh->setResponse(false,"An error has ocurred.");
		  }
		}
		echo json_encode($this->rh);

	}//Modificar usuario


	public function newpass($actual,$nueva){

		$query = Query::run("SELECT user_pass FROM usuarios WHERE id_user = $this->user LIMIT 1");

		if($query->num_rows>0){
			$us = (object) $query->fetch_array(MYSQLI_ASSOC);

			if(password_verify($actual,$us->user_pass)){
				$query = Query::prun("UPDATE usuarios SET user_pass = ? WHERE id_user = ? LIMIT 1",array("si",$nueva,$this->user));

				if($query->response){
					$this->rh->setResponse(true,"Password changed.");
				}else{
					$this->rh->setResponse(false,"An error has ocurred.");
				}
			}else{
				$this->rh->setResponse(false,"Wrong password.");
			}
		}else{
			$this->rh->setResponse(false,"User not found.");
		}

		echo json_encode($this->rh);
	}//newpass

	public function activar($id,$estado){

		$query = Query::prun("SELECT id_user FROM usuarios WHERE id_user = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){

			$query = Query::prun("UPDATE usuarios SET user_estado = ? WHERE id_user = ? LIMIT 1",array("si",$estado,$id));

			if($query->response){
				if($estado == "A"){
					$a = "Enabled";
					$r = 1;
					$b = "<button id=\"btn-activar\" class=\"btn btn-flat btn-poison\" data-toggle=\"modal\" data-target=\"#activarModal\" data-title=\"Disable\" data-val=\"I\"></i><i class=\"fa fa-close\" aria-hidden=\"true\">&nbsp;Desable</button>";
				}else{
					$a = "Disabled";
					$b = "<button id=\"btn-activar\" class=\"btn btn-flat btn-poison\" data-toggle=\"modal\" data-target=\"#activarModal\" data-title=\"Enable\" data-val=\"A\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>&nbsp;Enable</button>";
					$r = 0;
				}
				$this->rh->setResponse(true,"The user has been <b>".$a."</b>");
				$this->rh->data = array("e"=>$r,"b"=>$b);
			}else{
				$this->rh->setResponse(false,"An error has ocurred.");
			}
		}else{
			$this->rh->setResponse(false,"User not found.");
		}

		echo json_encode($this->rh);
	}

	public function reestablecer($id,$nueva){

		if($this->nivel=="A"){

			$query = Query::prun("SELECT id_user FROM usuarios WHERE id_user = ? LIMIT 1",array("i",$id));

			if($query->result->num_rows>0){
				if($nueva){
					$pass = "";
					$hash = $nueva;
				}else{
					$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
					$pass  = substr( str_shuffle( $chars ),0,10);
					$hash  = password_hash($pass, PASSWORD_DEFAULT);
				}

				$done = Query::prun("UPDATE usuarios SET user_pass = ? WHERE id_user = ? LIMIT 1",array("si",$hash,$id));

				if($done->response){
					$this->rh->setResponse(true,"Password has been changed.");
					$this->rh->data = $pass;
				}else{
					$this->rh->setResponse(false,"An error has ocurred.");
				}
			}else{
				$this->rh->setResponse(false,"User not found.");
			}
		}else{
			$this->sh->setResponse(false,"You don't have permission to make this accion.");
		}

		echo json_encode($this->rh);

	}//Reestablecer

	public function eliminar($usuario){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_user FROM usuarios WHERE id_user = ? AND user_eliminado = ?",array("ii",$usuario,"0"));

			if($query->result->num_rows>0){
				$query = Query::prun("UPDATE usuarios SET user_eliminado = ? WHERE id_user = ?",array("ii","1",$usuario));

				if($query->response){
					$this->rh->setResponse(false,"User deleted.",true,"inicio.php?ver=users");
				}else{
					$this->rh->setResponse(false,"An error has ocurred.");
				}

			}else{
				$this->rh->setResponse(false,"User not found.");
			}
		}else{
			$this->sh->setResponse(false,"You don't have permission to make this accion.");
		}

		echo json_encode($this->rh);
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}

}//Class Usuarios

$modelUser = new Usuarios();

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
				$nombres   = ucwords(strtolower($_POST["nombres"]));
				$apellidos = ucwords(strtolower($_POST["apellidos"]));
				$email     = ucfirst(strtolower($_POST["email"]));
				$telefono  = $_POST["telefono"];

				$modelUser->edit($nombres,$apellidos,$email,$telefono);
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
