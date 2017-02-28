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
		$query = Query::run("SELECT COUNT(id_od) AS orders,SUM(od_qty) AS total,od_type AS type FROM orders_details GROUP BY type");

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
					$data[] = sprintf('{name: \'Tops\',y:%u}',$row->total/$row->orders);
				break;
				case 4:
					$data[] = sprintf('{name: \'Accessories\',y:%u}',$row->total);
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
                          UNION SELECT id_product,'sink','Sink',prod_name,prod_foto,SUM(od.od_qty)
													FROM products AS p
                        	  INNER JOIN orders_details AS od ON (od.od_id_product = p.id_product AND od.od_type = 2)
                          	GROUP BY p.id_product
                        	UNION SELECT id_product,'top','Top',prod_name,prod_foto,COUNT(od.id_od)
													FROM products AS p
                        	  INNER JOIN orders_details AS od ON (od.od_id_product = p.id_product AND od.od_type = 3)
                          	GROUP BY p.id_product
                        	UNION SELECT id_accessory,'acce','Accessory',acce_name,acce_foto,COUNT(od.od_qty)
													FROM accessories AS a
                        	  INNER JOIN orders_details AS od ON (od.od_id_product = a.id_accessory AND od.od_type = 4)
                          	GROUP BY a.id_accessory
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