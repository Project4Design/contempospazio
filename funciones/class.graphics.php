<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Graphics{
	//Orders from le last 7 days
	public function lastDaysOrders(){
		$data   = (object) array();
		$hoy    = Base::Fecha();
		$orders = $dias = array();

		for($i = 6; $i >= 0; $i--){
			$day = strtotime ('-' . $i . ' day', strtotime($hoy));
			$fecha = date('Y-m-d',$day);

			//Orders per day
			$o_x_dia = Query::run("SELECT COUNT(id_order) AS orders FROM orders WHERE order_fecha_reg LIKE '%$fecha%'");
			$row = $o_x_dia->fetch_array(MYSQLI_ASSOC);
			$orders[] = $row["orders"];
			if($i>0){
				$days[] = "'".date ('l',$day)."'";
			}
		}//For

		$days[] = "'".date('l',strtotime($hoy))."'";

		$data->days   = $days;
		$data->orders = $orders;

		return $data;
	}

	//Amount of products by type
	public function productsType(){
		$data = array();
		$query = Query::run("SELECT COUNT(id_gabi) AS total,1 AS type FROM cabinets UNION SELECT COUNT(id_fregadero),2 FROM fregaderos UNION SELECT COUNT(id_tope),3 FROM topes");

		while($row = $query->fetch_array(MYSQLI_ASSOC)){
			$row = (object)$row;
			switch ($row->type) {
				case 1:
					$data[] = sprintf('{name: \'Cabinets\',y:%u}',$row->total);
				break;
				case 2:
					$data[] = sprintf('{name: \'Sinks\',y:%u}',$row->total);
				break;
				case 3:
					$data[] = sprintf('{name: \'Tops\',y:%u}',$row->total);
				break;
			}
		}

		return $data;
	}

	//Best selling products
	public function bestSelling(){
		$query = Query::run("SELECT c.id_gabi AS id,'cabi' AS opc,'Cabinet' AS product,gabi_descripcion AS name,gabi_foto AS foto, SUM(od.od_qty) AS sells
													FROM cabinets AS c
														INNER JOIN orders_details AS od ON (od.od_id_product = c.id_gabi AND od.od_type = 1)
                      	    GROUP BY c.id_gabi
                          UNION SELECT id_fregadero,'sink','Sink',freg_nombre,freg_foto,SUM(od.od_qty)
													FROM fregaderos AS f
                        	  INNER JOIN orders_details AS od ON (od.od_id_product = f.id_fregadero AND od.od_type = 2)
                          	GROUP BY f.id_fregadero
                        	UNION SELECT id_tope,'top','Top',tope_nombre,tope_foto,COUNT(od.id_od)
													FROM topes AS t
                        	  INNER JOIN orders_details AS od ON (od.od_id_product = t.id_tope AND od.od_type = 3)
                          	GROUP BY t.id_tope
													ORDER BY sells DESC
													LIMIT 10");	
		$data = array();

		if($query->num_rows>0){
			while($row = $query->fetch_array(MYSQLI_ASSOC)){
				$data[] = (object)$row;
			}
		}
		return $data;
	}
}



?>