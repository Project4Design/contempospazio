<?
require_once "../config/config.php";


class Sesiones{
	private $rh;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
	}

	public function login($email,$pass){
		$result = Query::prun("SELECT id_user,user_nivel,user_estado,user_nombres,user_apellidos,user_email,user_pass FROM usuarios WHERE user_email = ? AND user_eliminado = ? LIMIT 1",array("si",$email,"0"));

		if($result->result->num_rows>0){
			$user = (object)$result->result->fetch_array(MYSQLI_ASSOC);
			if(password_verify($pass,$user->user_pass)){
				if($user->user_estado == "A"){

					$_SESSION['id']     = $user->id_user;
					$_SESSION['nombre'] = $user->user_nombres." ".$user->user_apellidos;
					$_SESSION['email']  = $user->user_email;
					$_SESSION['nivel']  = $user->user_nivel;


					$this->rh->setResponse(true,"Loggin in",true,"inicio.php");
				}else{
					$this->rh->setResponse(false,"Account deactivated. Call the administrator.");
				}
			}else{
				$this->rh->setResponse(false,"User and password does not match.");
			}
		}else{
			$this->rh->setResponse(false,"User and password does not match.");
		}
		echo json_encode($this->rh);
	}//Login

	public function logout(){
    session_unset();
	  session_destroy();

    $this->rh->setResponse(true);
    $this->rh->redirect = "index.php";
    echo json_encode($this->rh);
	}//log-out
}//Class Funciones

// Logica
$modelSesiones = new Sesiones();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'login':
				$email = $_POST['email'];
				$pass = $_POST['password'];

				$modelSesiones->Login($email,$pass);
			break;
				
			case 'logout':
				$modelSesiones->logout();
			break;
		endswitch;
	endif;
endif;
?>