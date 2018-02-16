<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Projects_logs{
	private $rh;
	private $user;
	private $project_id;
	private $lastLog = 0;

	public function __CONSTRUCT($project = NULL)
	{
		$this->rh         = new ResponseHelper();
		$this->user       = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->project_id = $project;
	}

	protected function consulta($project,$date)
	{
		$data = '';
		$query = Query::prun('SELECT l.*,CAST(registered AS TIME) AS registered,u.id_user,u.user_nombres,u.user_apellidos
  																															FROM projects_logs AS l
  																															INNER JOIN usuarios AS u ON u.id_user = l.id_user
  																												 			WHERE (l.id_project = ? AND registered BETWEEN ? AND ? + INTERVAL 1 DAY)
  																												 			ORDER BY registered DESC',
  																												 			['iss',$project,$date,$date]);

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data .= $this->buildLog((object)$registro);
    }

    return $data;
  }

  protected function getAfterId($project,$lastLog)
  {
		$data = '';
		$query = Query::prun('SELECT l.*,CAST(registered AS TIME) AS registered,u.id_user,u.user_nombres,u.user_apellidos
  																															FROM projects_logs AS l
  																															INNER JOIN usuarios AS u ON u.id_user = l.id_user
  																												 			WHERE (l.id_project = ? AND l.id_log > ?)
  																												 			ORDER BY registered DESC',
  																												 			['ii',$project,$lastLog]);

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data .= $this->buildLog((object)$registro);
    }

    return $data;
  }

  public function add($project,$action,$type,$content = NULL)
  {
  	$query = Query::prun('INSERT INTO projects_logs (id_project,id_user,action,type,content) VALUES (?,?,?,?,?)',['iiiis',$project,$this->user,$action,$type,$content]);
  }

  //
  protected function getDateBlocks($project)
  {
  	$query = Query::prun('SELECT DISTINCT(CAST(registered AS DATE)) AS registered FROM projects_logs
  																																								WHERE id_project = ?
  																																								ORDER BY registered DESC',
  																																								['i',$project]);

  	$data = [];

  	while ($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
  		$data[] = $registro['registered'];
  	}

  	return $data;
  }

  public function getLogs($project,$lastLog)
  {
  	$data = '';
  	$this->lastLog = $lastLog;
  	if($lastLog == 0){
			$dates = $this->getDateBlocks($project);
				foreach ($dates as $date){
					$data .= '<li class="time-label"><span class="bg-red">'.$date.'</span><li>';
  				$data .= $this->consulta($project,$date);
  			}
  	}else{
  		$data = $this->getAfterId($project,$lastLog);
  	}
  	$new = $this->lastLog > $lastLog;

		echo json_encode(['new'=>$new,'logs'=>$data,'lastLog'=>$this->lastLog]);
  }

  //Short message in the History Tab
  protected function buildLog($log)
  {
  	$action  = $this->logAction($log->action);
  	$message = $this->logMessage($log->type);
  	$icon    = $this->logIcon($log->type);
		$this->lastLog = $log->id_log;

  	$data = '<li>
                <i class="fa fa-'.$icon.' bg-purple"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> '.$log->registered.'</span>
                  <h3 class="timeline-header"><a href="?ver=usuarios&opc=ver&id='.$log->id_user.'">'.$log->user_nombres.' '.$log->user_apellidos.'</a> '.$action.' <b>'.$message.'</b></h3>';
    if($log->content){
    	$data .= '<div class="timeline-body">'.$log->content.'</div>';
    }
    
    $data .= '</div></li>';

  	return $data;
  }

  protected function logAction($action)
  {
  	if($action == 1){
  		return 'Added a new';
  	}

  	if($action == 2){
  		return 'Updated ';
  	} 

  	if($action == 3){
  		return 'Removed a';
  	} 

  	if($action == 4){
  		return 'Set a new main ';
  	} 
  }

  protected function logMessage($type)
  {
  	if($type == 1){
  		return 'Information';
  	}

  	if($type == 2){
			return 'Comment';
  	}

  	if($type == 3){
			return 'Photo';
  	}

  	if($type == 4){
  		return 'Item/Template';
  	}

  	if($type == 5){
  		return 'Item\'s stock';
  	}
  }

  protected function logIcon($type)
  {
  	//Modifiy information
  	if($type == 1){
  		return 'info';
  	}
  	//Addded a comment
  	if($type == 2){
			return 'comments-o';
  	}
  	//Added, deleted, or set a photo as main
  	if($type == 3){
			return 'camera';
  	}
  	//Added items to inventory
  	if($type == 4){
  		return 'tasks';
  	}
  	//Change status up
  	if($type == 5){
  		return 'level-up';
  	}
  	//Change status down
  	if($type == 6){
  		return 'level-down';
  	}

  }

}//Class Project_logs

$modelProjectLogs = new projects_logs();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'getLogs':
	  		$project = $_POST['project'];
	  		$lastLog = $_POST['lastLog'];

	  		$modelProjectLogs->getLogs($project,$lastLog);
	  	break;
		endswitch;
	endif;
endif;
?>