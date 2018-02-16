<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class projects_comments{
	private $rh;
	private $user;
	private $nivel;
	private $log;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->log    = new Projects_logs();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
	}

	//Buscar todos los items en Project
	public function consulta($project,$lastcomment = 0)
	{
    $query =  Query::prun("SELECT pc.*,u.user_nombres,u.user_apellidos FROM projects_comments AS pc
																					INNER JOIN usuarios AS u ON u.id_user = pc.id_user
    																	WHERE pc.id_project = ? AND pc.id_comment > ?
    																	ORDER BY pc.id_comment ASC",['ii',$project,$lastcomment]);
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
			//Save event to logs
			$this->log->add($project,1,2);
  		$this->rh->setResponse(true);
  	}else{
  		$this->rh->setResponse(false,"An error has ocurred with the data.");
  	}

  	echo json_encode($this->rh);
  }

  public function getComments($project,$lastcomment)
  {
  	$comments = $this->consulta($project,$lastcomment);
  	$data = "";
  	$last = $lastcomment;
  	$new = count($comments)>0;
  	foreach($comments AS $comment){
  		$fecha = date('d M y H:i',strtotime($comment->created));
    	$align = ($comment->id_user == $this->user)?'right':'left';

    	$data .="<div id=\"chat-text-{$comment->id_comment}\" class=\"direct-chat-msg {$align}\">
                <div class=\"direct-chat-info clearfix\">
                  <span class=\"direct-chat-name pull-{$align}\">&nbsp;&nbsp;{$comment->user_nombres} {$comment->user_apellidos}</span>
                  <span class=\"direct-chat-timestamp pull-{$align}\">&nbsp;&nbsp;{$fecha}</span>
                </div>
                <!--<img src=\"\" alt=\"\">-->
                <div class=\"direct-chat-text\">
                  {$comment->comment}
                </div>
              </div>
              ";
      $last = $comment->id_comment;
		}

		echo json_encode(['new'=>$new,'comments'=>$data,'last'=>$last]);
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
		  case 'getComments':
		  	$project     = $_POST['project'];
		  	$lastcomment = $_POST['lastcomment'];

		  	$modelProjectComments->getComments($project,$lastcomment);
		  break;
		endswitch;
	endif;
endif;
?>