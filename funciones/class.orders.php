<?
if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Orders{
	private $rh;
	private $nivel;

	public function __CONSTRUCT()
	{
		$this->rh    = new ResponseHelper();
		$this->nivel = isset($_SESSION['nivel'])?$_SESSION['nivel']:"X";
	}

	public function consulta()
	{
		$query = Query::run("SELECT o.*,c.client_name,c.client_phone,SUM(od.od_qty) AS products FROM orders AS o
															INNER JOIN orders_details AS od ON od.id_order = o.id_order
															INNER JOIN clients AS c ON c.client_number = o.client_number
															GROUP BY o.id_order");
		$data = array();
		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function latestOrders(){
		$query = Query::run("SELECT o.*,c.client_name,c.client_phone,SUM(od.od_qty) AS products FROM orders AS o
															INNER JOIN orders_details AS od ON od.id_order = o.id_order
															INNER JOIN clients AS c ON c.client_number = o.client_number
															GROUP BY o.id_order
															ORDER BY o.id_order DESC");
		$data = array();
		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object)$row;
		}

		return $data;
	}

	public function obtener($id){
		$query = Query::prun("SELECT o.*,c.* FROM orders AS o
															INNER JOIN clients AS c ON c.client_number = o.client_number WHERE o.id_order = ?",array("i",$id));

		if($query->result->num_rows>0){
			$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

		return $data;
	}

	public function obtenerProd($id){
		$query = Query::prun("SELECT * FROM orders_details WHERE id_order = ?",array("i",$id));

		$data = array();
		if($query->result->num_rows>0){
			while($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				$data[] = (object) $row;
			}
		}else{
			$data = NULL;
		}

		return $data;
	}

	public function obtenerByClient($id){
		$query = Query::prun("SELECT o.*,c.client_name,c.client_phone,SUM(od.od_qty) AS products FROM orders AS o
															INNER JOIN orders_details AS od ON od.id_order = o.id_order
															INNER JOIN clients AS c ON c.client_number = o.client_number
															WHERE c.id_client = ?
															GROUP BY o.id_order",
															array("i",$id));
		$data = array();

		if($query->result->num_rows>0){
			while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				$data[] = (object) $row;
			}
		}

		return $data;
	}

	public function setStarted($order){
		$data = (object)array();

		if($this->nivel == "A"){
			$query = Query::prun("SELECT order_status FROM orders WHERE id_order = ?",array("i",$order));

			if($query->result->num_rows>0){
				$o = (object)$query->result->fetch_array(MYSQLI_ASSOC);
				if($o->order_status=="Standby"){
					$query = Query::prun("UPDATE orders SET order_status = ?,order_status_reason = ? WHERE id_order = ?",array("ssi",'Started',NULL,$order));
					if($query->response){
						$data->status = "<span class=\"label label-primary\">Started</span>";
						$data->btn = "
							<button class=\"btn btn-flat btn-warning\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Standby\"><i class=\"fa fa-pause\" aria-hidden=\"true\"></i> Standby</button>
			        <button class=\"btn btn-flat btn-success\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Completed\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Complete</button>
			        <button class=\"btn btn-flat btn-danger\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Canceled\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Cancel</button>
						";

						$this->rh->setResponse(true,"The order was marked as Started!");
					}else{
						$this->rh->setResponse(false,"An error has ocurred.");
					}
				}else{
					$this->rh->setResponse(false,"This order is not on Standby.");
				}
			}else{
				$this->rh->setResponse(false,"Order not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this accion.");
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function setCompleted($order){
		$data = (object)array();

		if($this->nivel == "A"){
			$query = Query::prun("SELECT order_status FROM orders WHERE id_order = ?",array("i",$order));

			if($query->result->num_rows>0){
				$o = (object)$query->result->fetch_array(MYSQLI_ASSOC);

				if($o->order_status!="Canceled"){
					$query = Query::prun("UPDATE orders SET order_status = ?,order_status_reason = ? WHERE id_order = ?",array("ssi",'Completed',NULL,$order));

					if($query->response){
						$data->status = "<span class=\"label label-success\">Completed</span>";
						$data->btn = NULL;

						$this->rh->setResponse(true,"The order is completed!");
					}else{
						$this->rh->setResponse(false,"An error has ocurred.");
					}
				}else{
					$this->rh->setResponse(false,"This order is canceled.");
				}
			}else{
				$this->rh->setResponse(false,"Order not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this accion.");
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function setStandby($order,$reason){
		$data = (object)array();

		if($this->nivel == "A"){
			$query = Query::prun("SELECT order_status FROM orders WHERE id_order = ?",array("i",$order));
			
			if($query->result->num_rows>0){
				$o = (object)$query->result->fetch_array(MYSQLI_ASSOC);

				if($o->order_status=="Started"){
					$query = Query::prun("UPDATE orders SET order_status = ?,order_status_reason = ? WHERE id_order = ?",array("ssi",'Standby',$reason,$order));
					
					if($query->response){
						$data->status = "<span class=\"label label-warning\">Standby</span>";
						$data->reason = $reason;
						$data->btn = "
							<button class=\"btn btn-flat btn-primary\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Started\"><i class=\"fa fa-play\" aria-hidden=\"true\"></i> Start</button>
			        <button class=\"btn btn-flat btn-success\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Completed\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Complete</button>
			        <button class=\"btn btn-flat btn-danger\" data-toggle=\"modal\" data-target=\"#statusModal\" data-title=\"Canceled\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Cancel</button>
						";

						$this->rh->setResponse(true,"The order was marked as Standby!");
					}else{
						$this->rh->setResponse(false,"An error has ocurred.");
					}
				}else{
					$this->rh->setResponse(false,"This order is already completed.");	
				}
			}else{
				$this->rh->setResponse(false,"Order not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this accion.");
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function setCanceled($order,$reason){
		$data = (object)array();
		if($this->nivel == "A"){
			$query = Query::prun("SELECT order_status FROM orders WHERE id_order = ?",array("i",$order));

			if($query->result->num_rows>0){
				$o = (object)$query->result->fetch_array(MYSQLI_ASSOC);
				if($o->order_status!="Completed"){
					$query = Query::prun("UPDATE orders SET order_status = ?,order_status_reason = ? WHERE id_order = ?",array("ssi",'Canceled',$reason,$order));
					
					if($query->response){
						$data->status = "<span class=\"label label-danger\">Canceled</span>";
						$data->reason = $reason;
						$data->btn = NULL;

						$this->rh->setResponse(true,"The order was marked as Canceled!");
					}else{
						$this->rh->setResponse(false,"An error has ocurred.");
					}
				}else{
					$this->rh->setResponse(false,"This order is already completed.");	
				}
			}else{
				$this->rh->setResponse(false,"Order not found.");
			}
		}else{
			$this->rh->setResponse(false,"You don't have permission to make this accion.");
		}

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}

	public function revenues(){
		$query = Query::run("SELECT SUM(order_earnings) AS total FROM orders WHERE order_status = 'Completed'");
		$d = (object)$query->fetch_array(MYSQLI_ASSOC);

		$data = ($d->total>0)?$d->total:0;

		return Base::Format($data,2,".",",");
	}

}//Class Configuracion

// Logica
$modelOrders = new Orders();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'Started':
				$order = $_POST['order'];

				$modelOrders->setStarted($order);
			break;
			case 'Completed':
				$order = $_POST['order'];

				$modelOrders->setCompleted($order);
			break;
			case 'Standby':
				$order  = $_POST['order'];
				$reason = $_POST['reason'];

				$modelOrders->setStandby($order,$reason);
			break;
			case 'Canceled':
				$order  = $_POST['order'];
				$reason = $_POST['reason'];

				$modelOrders->setCanceled($order,$reason);
			break;
		endswitch;
	endif;
endif;

?>