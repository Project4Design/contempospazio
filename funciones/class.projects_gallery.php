<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Projects_gallery{
	private $rh;
	private $user;
	private $nivel;
	private $fecha;
	private $project_id;
	private $photo;
	private $logs;

	public function __CONSTRUCT($project = NULL)
	{
		$this->rh         = new ResponseHelper();
		$this->logs       = new projects_logs();
		$this->user       = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel      = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
		$this->project_id = $project ? $project : NULL;
		$this->photo      = (object)['photo'=>null,'thumb'=>null];
	}

	public function obtener($id)
	{
		$query = Query::prun('SELECT * FROM projects_gallery WHERE id_gallery = ? LIMIT 1',['i',$id]);

		if($query->result->num_rows>0){
			$data = (object)$query->result->fetch_array(MYSQLI_ASSOC);
			$this->project_id = $data->id_project;
		}else{
			$data =  NULL;	
		}

		return $data;
	}

	//Get all photos from 1 project
	public function all($status)
	{
		$query = Query::prun('SELECT * FROM projects_gallery WHERE id_project = ? AND status = ?',['ii',$this->project_id,$status]);

		$data = [];

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}

	public function getMain()
	{
		$query = Query::prun('SELECT photo,thumb FROM projects_gallery WHERE id_project = ? AND main = ? LIMIT 1',['ii',$this->project_id,1]);

		$this->photo = ($query->result->num_rows>0) ? (object) $query->result->fetch_array(MYSQLI_ASSOC) : $this->photo;

		return $this->photo;
	}

  public function addPhoto($project,$status,$photo)
  {
  	$this->project_id= $project;

		if($photo){
			$img = new img();
			$tmp = $img->load($photo['photo'],true);

			//Check is there is a main photo in the project. Else, add this photo, as main photo
			$main = $this->getMain();

			$main = $main->photo ? '0':1;

			$query = Query::prun('INSERT INTO projects_gallery (id_project,id_user,status,photo,thumb,main) VALUES (?,?,?,?,?,?)',['iiissi',$project,$this->user,$status,$tmp->name,$tmp->thumb,$main]);

			if($query->response){
				//Log this action
				$this->logs->add($project,1,3);
				$this->rh->setResponse(true,'Image saved.');
				$this->rh->data = ['id'=>$query->id,'photo'=>'images/uploads/'.$tmp->name,'thumb'=>'images/thumbs/'.$tmp->thumb];
			}else{
				unlink('../images/thumbs/'.$tmp->thumb);
				unlink('../images/uploads/'.$tmp->name);
				$this->rh->setResponse(false,'Image could not be saved.');
			}
		}else{
			$this->rh->setResponse(false,'No image loaded.');
		}

		echo json_encode($this->rh);
  }

  public function removePhoto($id)
  {
  	if($this->nivel == 'A'){
	  	$photo = $this->obtener($id);

	  	if($photo){
	  		$query = Query::prun('DELETE FROM projects_gallery where id_gallery = ? LIMIT 1',['i',$id]);

	  		if($query){

	  			//Get the main photo
	  			$main = $this->getMain();
	  			//If there is NOT a main photo
	  			if(!$main->photo){
	  				$this->rh->data = $this->setFirstAsMain();
	  			}

					//Log this action
					$this->logs->add($this->project_id,3,3);

					unlink('../images/thumbs/'.$photo->thumb);
					unlink('../images/uploads/'.$photo->photo);

					$this->rh->setResponse(true,'Photo removed.');
	  		}else{
	  			$this->rh->setResponse(false,'An error has occurred.');
	  		}
	  	}else{
				$this->rh->setResponse(false,'Photo not found.');
	  	}
	  }else{
	  	$this->rh->setResponse(false,"You don't have permission to make this action.");
	  }
  	echo json_encode($this->rh);
  }

  public function removeAll()
  {
		$query = Query::prun('SELECT * FROM projects_gallery WHERE id_project = ?',['i',$this->project_id]);

    while($photo = $query->result->fetch_array(MYSQLI_ASSOC)){
			unlink('../images/thumbs/'.$photo['thumb']);
			unlink('../images/uploads/'.$photo['photo']);
    }

    return true;
  }

  public function setMain($id)
  {
  	$photo = $this->obtener($id);

  	if($photo){
  		//Set all other pics of the project as main = 0
  		$query = Query::prun('UPDATE projects_gallery SET
  																								main = ?
  																								WHERE id_project = ?'
  																								,['ii',0,$photo->id_project]);

  		//Set as main = 1
  		$query = Query::prun('UPDATE projects_gallery SET main = ? WHERE id_gallery = ? LIMIT 1',['ii',1,$photo->id_gallery]);

  		if($query->response){
				//Log this action
				$this->logs->add($this->project_id,4,3);

  			$this->rh->setResponse(true,'Photo set as main photo.');
  		}else{
  			$this->rh->setResponse(false,'An error has ocurred.');
  		}

  		$this->rh->data = $photo;
  	}else{
  		$this->rh->setResponse(false,'Photo not found.');
  	}

  	echo json_encode($this->rh);
  }

  //If the Main photo is removed. Set the firts photo in DB as Main
  protected function setFirstAsMain()
  {
  	//Get the first photo of the project
  	$query = Query::prun('SELECT id_gallery,thumb FROM projects_gallery WHERE id_project = ? LIMIT 1',['i',$this->project_id]);

		$photo = ($query->result->num_rows>0) ? (object)$query->result->fetch_array(MYSQLI_ASSOC) : NULL;

		if($photo){
  		$query = Query::prun('UPDATE projects_gallery SET
  																								main = ?
  																								WHERE id_gallery = ? LIMIT 1'
  																								,['ii',1,$photo->id_gallery]);

			//Log this action
			$this->logs->add($this->project_id,4,3);

  		return $photo;
		}

		return NULL;
  }

}//Class Project

$modelProjectGallery = new Projects_gallery();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
	  	case 'add_project_photo':
	  		$project = $_POST['project'];
	  		$status  = $_POST['status'];
	  		$photo   = ($_FILES['photo']['name'])?$_FILES:NULL;

	  		$modelProjectGallery->addPhoto($project,$status,$photo);
	  	break;
	  	case 'set_main':
	  		$id = $_POST['id'];

	  		$modelProjectGallery->setMain($id);
	  	break;
	  	case 'remove_photo':
	  		$id = $_POST['id'];

	  		$modelProjectGallery->removePhoto($id);
	  	break;
		endswitch;
	endif;
endif;
?>