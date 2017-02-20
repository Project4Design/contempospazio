<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Clients{
	private $rh;
	private $user;
	private $nivel;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	public function consulta(){
		$query = Query::run("SELECT * FROM clients");
		$data = array();
		if($query->num_rows>0){
			while ($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data[] = (object)$row;
			}
		}else{
			$data = NULL;
		}

		return $data;
	}

	public function obtener($id){
		$query = Query::prun("SELECT * FROM clients WHERE id_client = ? LIMIT 1",array("i",$id));
		if($query->result->num_rows>0){
			$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

		return $data;
	}

	public function add($name,$address,$email,$phone,$contact,$ajax = false){

		$query = Query::prun("SELECT client_number FROM clients WHERE client_email = ?",array("s",$email));

		if($query->result->num_rows>0){
			$client = (object) $query->result->fetch_array(MYSQLI_ASSOC);
			$number = $client->client_number;
			$this->rh->setResponse(false,"Email already registered.");
		}else{
			$number = $this->new_number($email);
			$query = Query::prun("INSERT INTO clients (client_number,client_name,client_address,client_email,client_phone,client_contact)
																					VALUES (?,?,?,?,?,?)",array("ssssss",$number,$name,$address,$email,$phone,$contact));
			if($query->response){
				$this->rh->setResponse(true,"Client registered. <a href=\"inicio.php?ver=clients&opc=ver&id={$query->id}\">See client</a>");
			}else{
				$number = 0;
				$this->rh->setResponse(false,"An error has ocurred with the client.");
			}
		}

		$this->rh->data = $number;

		if($ajax){
			echo json_encode($this->rh);
		}else{
			return $this->rh;
		}
	}

	public function edit($client,$name,$address,$email,$phone,$contact){
		$query = Query::prun("SELECT id_client FROM clients WHERE client_email = ? AND id_client != ?",array("si",$email,$client));

		if($query->result->num_rows>0){
			$this->rh->setResponse(false,"This email is already registered with another client.");
		}else{
			$query = Query::prun("UPDATE clients SET
																	client_name    = ?,
																	client_address = ?,
																	client_email   = ?,
																	client_phone   = ?,
																	client_contact = ?
																WHERE id_client = ?",
																array("sssssi",$name,$address,$email,$phone,$contact,$client));

			if($query->response){
				$this->rh->setResponse(true,"Changes has been saved.",true,"inicio.php?ver=clients&opc=ver&id=".$client);
			}else{
				$this->rh->setResponse(false,"An error has ocurred.");
			}
		}

		echo json_encode($this->rh);
	}

	public function new_number($email){
		$repeat = true;
		$letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		$email = str_replace($letters,"",md5($email));
		while($repeat){
			$number  = "CL".substr( str_shuffle( mt_rand().time().$email ),0,5);
			$query = Query::run("SELECT id_client FROM clients WHERE client_number = '$number'");
			if($query->num_rows == 0){
				$repeat = false;
			}
		}
		
		return $number;
	}

	public function get_client($number,$ajax = true){
		$query = Query::prun("SELECT client_number,client_name,client_address,client_email,client_phone,client_contact FROM clients WHERE client_number = ?",array("s",$number));

		if($query->result->num_rows>0){
			$this->rh->setResponse(true);
			$this->rh->data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$this->rh->data = "No";
			$this->rh->setResponse(false);
		}

		if($ajax){
			echo json_encode($this->rh);	
		}else{
			return $this->rh->data;
		}
		
	}

}//CLients

$modelClients = new Clients();

if(Base::isAjax()):
	if(isset($_POST['action'])):
		switch ($_POST['action']):
			case 'add_client':
				$name    = $_POST['client_name'];
				$address = $_POST['client_address'];
				$email   = $_POST['client_email'];
				$phone   = $_POST['client_phone'];
				$contact = $_POST['client_contact'];

			$modelClients->add($name,$address,$email,$phone,$contact,true);
			break;
			case 'edit_client':
				$client  = $_POST['id'];
				$name    = $_POST['client_name'];
				$address = $_POST['client_address'];
				$email   = $_POST['client_email'];
				$phone   = $_POST['client_phone'];
				$contact = $_POST['client_contact'];

			$modelClients->edit($client,$name,$address,$email,$phone,$contact);
			break;
			case 'get_client':
				$number = $_POST['cli'];

				$modelClients->get_client($number);
			break;
		endswitch;
	endif;
endif;