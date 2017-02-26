<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Quotation{
	private $rh;
	private $user;
	private $nivel;
	private $clients;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
		$this->clients = new Clients();
	}

	public function add($number,$project,$products,$client,$address,$email,$phone,$contact,$address2){
		$products = json_decode($products);//Transformar los productos en un array para extaer sus datos
		$data = (object)array();//Datos para cargar en el Paso 3

		//Registrar el cliente
		if($number==="0"){
			$response = $this->clients->add($client,$address,$email,$phone,$contact);
			$msj = $response->msj; //En caso de error, aqui estara el mensaje
			$number = $response->data; //Numero del cliente registrado
			if($response->response){
				$cli = $this->clients->get_client($number,false);	
			}
		}else{
			$cli = $this->clients->get_client($number,false);
			$msj = "";
		}

		if($number!="0"){
			
			$configuration = new Configuracion();
			$config = $configuration->consulta();
			//Variables
			$tax = $config->config_tax;//Variable de impuestos
			$shipping = $config->config_shipment;//Variable de % de envio al cliente
			$earnings_cab = $config->config_earnings_cab;//Ganacias
			$earnings_sinks = $config->config_earnings_sinks;//Ganacias
			$earnings_tops = $config->config_earnings_tops;//Ganacias
			$regular = $config->config_regular_work;//Trabajo regular
			$big = $config->config_big_work;//Trabajo grande
			$discount = $config->config_discount;//Descuento
			$delivery = $config->config_delivery;//Descuento
			//Totales
			$error=0;//Errores

			$c_labor=0;$t_man=0;$taxes=0;$deliv=0;$c_sub=0;$s_sub=0;$t_sub=0;$c_total=0;$s_total=0;$t_total=0;$ship=0;$subtotal=0;$total=0;$c_earnings=0;$s_earnings=0;$t_earnings=0;$earnings=0;$c_unit=0;$s_unit=0;$t_unit=0;

			//Numero de orden
			$order = Base::Complete($this->last_order()+1);

			$query = Query::prun("INSERT INTO orders (order_order,order_status,client_number,order_project,order_address,order_tax,order_delivery,order_earnings_cab,order_earnings_sinks,order_earnings_tops,order_shipping)
																VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
															array("sssssddddddd",$order,'Started',$number,$project,$address2,$tax,$delivery,$earnings_cab,$earnings_sinks,$earnings_tops,$shipping));
			if($query->response){
				$id_order = $query->id;
				
				//Buscar productos
				if(count($products)>0){
					foreach ($products as $prod){
						switch ($prod->type){
							case '1':
								$productos = new Products();
								$query = Query::prun("SELECT c.gabi_descripcion,ci.gp_labor,ci.gp_codigo,ci.gp_gs,ci.gp_mgc,ci.gp_rbs,ci.gp_esms,ci.gp_ws,ci.gp_miw,c.id_gabi FROM cabinets AS c INNER JOIN cabinets_items AS ci ON ci.id_gabi = c.id_gabi WHERE ci.id_gp = ?",array("i",$prod->item));
								if($query->result->num_rows>0){
									$cabi = $query->result->fetch_array(MYSQLI_NUM);
									$id_prod = $cabi[9];
									$description = $cabi[0]." | <b>Color: </b>".$productos->cabinetColor($prod->index);
									$item   = $cabi[2];
									$pdisc  = (($cabi[$prod->index]*$discount)/100);
									$price  = $cabi[$prod->index]; //Precio original
									$price2 = ceil($price-$pdisc); //Precio para el calculo del total
									$labor  = $configuration->labor($cabi[1]);
									$c_labor += ($prod->qty*$labor);
									$ta     = $tax;
									$disc   = $discount;
									$man    = NULL;
									$sub    = $price2 * $prod->qty;
									$c_unit += $prod->qty; //Cantidad de cabinets - Paso 3

									$c_sub += $sub; //Subtotal for cabinets
								}	
							break;
							case '2':
								$productos = new Products();
								$query = Query::prun("SELECT f.*,fm.*,fc.* FROM fregaderos AS f
																	INNER JOIN fregaderos_materiales AS fm ON fm.id_fm = f.id_fm
																	INNER JOIN fregaderos_colores AS fc ON fc.id_fc = f.id_fc
																	WHERE f.id_fregadero = ? LIMIT 1",array("i",$prod->id));
								if($query->result->num_rows>0){
									$sink = (object) $query->result->fetch_array(MYSQLI_ASSOC);
									$id_prod = $sink->id_fregadero;
									$description = "<b>shape:</b> ".$productos->shape($sink->freg_forma)." | <b>Mat.:</b> ".$sink->fm_nombre." | <b>Color:</b> ".$sink->fc_nombre;
									$item  = NULL;
									$price = $sink->freg_costo;
									$labor = NULL;
									$ta    = NULL;
									$disc  = NULL;
									$man   = NULL;
									$sub   = $price*$prod->qty;
									$s_unit += $prod->qty; //Cantidad de sinks - Paso 3

									$s_sub += $sub;
								}
							break;
							case '3':
								$query = Query::prun("SELECT t.*,tc.*,tm.* FROM topes AS t
																	INNER JOIN topes_materiales AS tm ON tm.id_tm = t.id_tm
																	INNER JOIN topes_colores AS tc ON tc.id_tc = t.id_tc
																	WHERE t.id_tope = ? LIMIT 1",array("i",$prod->id));
								if($query->result->num_rows>0){
									$top = (object) $query->result->fetch_array(MYSQLI_ASSOC);
									$id_prod = $top->id_tope;
									$description = "<b>Mat.:</b> ".$top->tm_nombre." | <b>Color:</b> ".$top->tc_nombre;
									$item  = NULL;
									$price = $top->tope_costo;
									$labor = NULL;
									$ta    = NULL;
									$disc  = NULL;
									$man   = $top->tope_manufacture;
									$man2  = $man * $prod->qty;
									$sub   = $price * $prod->qty;
									$t_unit += $prod->qty; //Cantidad de tops - Paso 3
									
									$t_man += $man2;
									$t_sub += $sub;
								}
							break;
						}//Switch
						//Guardar los productos del order
						$inv_det = Query::prun("INSERT INTO orders_details (id_order,od_id_product,od_type,od_description,od_item,od_price,od_discount,od_qty,od_subtotal,od_labor,od_tax,od_manufacturer)
																			VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
																			array("iiissddidddd",$id_order,$id_prod,$prod->type,$description,$item,$price,$disc,$prod->qty,$sub,$labor,$ta,$man));
						if(!$inv_det->response){
							$error++;
						}
					}//Foreach


					$taxes += ceil(($c_sub*$tax)/100); //Taxes for cabinets
					$deliv += ceil((($c_sub+$taxes)*$delivery)/100); //Delivery for cabinets
					$c_earnings = ceil((($c_sub+$taxes+$deliv+$c_labor)*$earnings_cab)/100); //Cabinets Earnings
					$c_total += $c_sub + $taxes + $deliv + $c_labor + $c_earnings;//Total Cabinets


					$s_earnings = ceil(($s_sub*$earnings_sinks)/100);//Sinks Earnings
					$s_total = $s_earnings + $s_sub;//Total Sinks

					$t_earnings = ceil((($t_sub+$t_man)*$earnings_tops)/100);//Tops Earnings
					$t_total = $t_earnings + $t_man + $t_sub;//Total Tops

					$earnings = $c_earnings + $s_earnings + $t_earnings; 
					$subtotal = ceil($c_total + $s_total + $t_total);//General subtotal
		
					$ship  = ceil(($subtotal * $shipping)/100);//Shipment to client
					$total = ceil($c_total + $s_total + $t_total + $ship);//Grand total

					$query = Query::prun("UPDATE orders SET order_earnings = ?, order_subtotal = ?, order_total = ? WHERE id_order = ?",array("dddi",$earnings,$subtotal,$total,$id_order));
					if($query->response){
						//Asignar variables a mostrar en el paso 3
						$data->cnumber = $cli->client_number;$data->name = $cli->client_name;$data->phone = $cli->client_phone;$data->address = $cli->client_address;
						$data->project = $project;$data->address2 = $address2;
						$data->cabinets = $c_unit;$data->sinks = $s_unit;$data->tops = $t_unit;
						$data->link = $id_order;
						$data->total = $total;

						$this->rh->data = $data;
						$this->rh->setResponse(true,"Order created.");
	 				}else{
						$this->rh->setResponse(false,"An error has ocurred with the Order");
					}
				}//End if count
				else{
					Query::run("DELETE FROM clients WHERE client_number = '$number'");
					Query::run("DELETE FROM orders WHERE id_order = $id_order");
					$this->rh->setResponse(false,"There are no products added.");
				}
			}else{
				//Error saving Order
				Query::run("DELETE FROM clients WHERE client_number = $number");
				$this->rh->setResponse(false,"An error has ocurred with the Order");
			}
		}else{
			$this->rh->setResponse(false,$msj);
		}

		echo json_encode($this->rh);
	}

	public function last_order(){
		$query = Query::run("SELECT order_order FROM orders ORDER BY order_order DESC LIMIT 1");
		if($query->num_rows>0){
			$order = (object)$query->fetch_array(MYSQLI_ASSOC);
			$data = $order->order_order;
		}else{
			$data = 0;
		}

		return $data;
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}

}//Quotation

$modelQuotation = new Quotation();

if(Base::isAjax()):
	if(isset($_POST['action'])):
		switch ($_POST['action']):
			case 'add_quo':
				$project  = $_POST['project'];
				$products = $_POST['products'];
				$number   = $_POST['client_number'];
				$client   = ucfirst(strtolower($_POST['client_name']));
				$address  = $_POST['client_address'];
				$email    = $_POST['client_email'];
				$phone    = $_POST['client_phone'];
				$contact  = $_POST['client_contact'];
				$address2 = ($_POST['client_address2']!="")?$_POST['client_address2']:NULL;
				
				$modelQuotation->add($number,$project,$products,$client,$address,$email,$phone,$contact,$address2);
			break;
		endswitch;
	endif;
endif;



?>