<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class projects_comments{
	private $rh;
	private $user;
	private $nivel;


	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	//Buscar todos los items en Project
	public function consulta($id)
	{
    $query =  Query::prun("SELECT pc.*,u.user_nombres,u.user_apellidos FROM projects_comments AS pc
																					INNER JOIN usuarios AS u ON u.id_user = pc.id_user
    																	WHERE pc.id_project = ?
    																	ORDER BY pc.created ASC",array("i",$id));
    $data  = [];

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
  }

  public function add($comment,$project)
  {
  	$query = Query::prun("INSERT INTO projects_comments (id_project,id_user,comment) VALUES (?,?,?)",["iis",$project,$this->user,$comment]);

  	if($query->response){
  		$fecha = date('d M y H:i',time());
  		$return = [];
  		$return['comment'] = "<div id=\"chat-text-{$query->id}\" class=\"direct-chat-msg right\">
                    <div class=\"direct-chat-info clearfix\">
                      <span class=\"direct-chat-name pull-right\">&nbsp;&nbsp;{$_SESSION['nombre']}</span>
                      <span class=\"direct-chat-timestamp pull-right\">&nbsp;&nbsp;{$fecha}</span>
                    </div>
                    <div class=\"direct-chat-text\">
                      {$comment}
                    </div>
                  </div>";
      $return['id'] = "chat-text-".$query->id;

  		$this->rh->setResponse(true);
  		$this->rh->data = $return;
  	}else{
  		$this->rh->setResponse(false,"An error has ocurred with the data.");
  	}

  	echo json_encode($this->rh);
  }

  
  public function delete($id)
  {
  	if($this->nivel=="A"){
	  	$query = Query::prun("SELECT id_project FROM project WHERE id_project = $id");

	  	if($query->result->num_rows>0){
	  		$query = Query::prun("DELETE FROM project WHERE id_project = $id");

	  		if($query->response){
	  			$this->rh->setResponse(true,"Item deleted.",true,"inicio.php?ver=project");
	  		}else{
	  			$this->rh->setResponse(false,"An error has ocurred.");
	  		}
	  	}else{
	  		$this->rh->setResponse(false,"Item not found.");
	  	}
	  }else{
	  	$this->sh->setResponse(false,"You don't have permission to make this accion.");
	  }

  	echo json_encode($this->rh);
  }

	//===================NULL RESPONSE ========
	public function fdefault(){
		echo json_encode($this->rh);
	}//=========================================x

}//Class Project

$modelProjectComments = new Projects_comments();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  case 'add_comment':
	  	$comment = $_POST['comment'];
	  	$project = $_POST['project'];

	  	$modelProjectComments->add($comment,$project);
	  break;
		endswitch;
	endif;
endif;
?>