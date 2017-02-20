<?
require_once 'header.php';
if(isset($_SESSION['id'])===false):
	require_once 'views/forbidden.php';
else:

	switch($inicio):
		case 'profile':
			require Base::LoadView($inicio);
		break;
		case 'users':
			require Base::LoadView($inicio,"A");
		break;
		case 'clients':
			require Base::LoadView($inicio);
		break;
		case 'configuration':
			require Base::LoadView($inicio);
		break;
		case 'products':
			require Base::LoadView($inicio);
		break;
		case 'quotation':
			require Base::LoadView($inicio);
		break;
		case 'orders':
			require Base::LoadView($inicio);
		break;
		case 'statistics':
			require Base::LoadView($inicio);
		break;
		case 'index':
			$usuarios  = new Usuarios();
			//Total de usuarios
			$totaluser = count($usuarios->consulta());
			$products = new Products();
			//Total de productos
			$totalg  = count($products->consulta_gabinetes());
			$totalf  = count($products->consulta_fregaderos());
			$totalt  = count($products->consulta_topes());
			$product = $products->lastAdded();

			$clients = new CLients();
			$client  = count($clients->consulta());
			$orders  = new Orders();
			$order   = $orders->latestOrders();

		?>
	    <section class="content-header">
	      <h1 class="text-center">
	        CONTEMPOSPAZIO
	      </h1>
	      <ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard" aria-hidden="true"></i> Home</a></li>
	      </ol>
	    </section>

	    <!-- Main content -->
	    <div class="content">
	      <!-- Info boxes -->
		    <div class="row">
		      <div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-yellow">
		          <div class="inner">
		            <h3><?=$totaluser?></h3>

		            <p>Users</p>
		          </div>
		          <div class="icon">
		            <i class="fa fa-user-plus"></i>
		          </div>
		          <a href="?ver=users" class="small-box-footer">
		            More info <i class="fa fa-arrow-circle-right"></i>
		          </a>
		        </div>
		      </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-purple">
	            <div class="inner">
	              <h3><?=count($orders->consulta())?></h3>

	              <p>Orders</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-file-text-o"></i>
	            </div>
	            <a href="?ver=orders" class="small-box-footer">
	              More info <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>
	        <!-- /.col -->

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-red">
	            <div class="inner">
	              <h3><?=($totalg+$totalf+$totalt)?></h3>

	              <p>Products</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-cubes"></i>
	            </div>
	            <a href="?ver=products" class="small-box-footer">
	              More info <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-green">
	            <div class="inner">
	              <h3><?=$client?></h3>

	              <p>Clients</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-address-book-o"></i>
	            </div>
	            <a href="?ver=clients" class="small-box-footer">
	              More info <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>
		    </div><!--row-->

		    <div class="row">
		    	<div class="col-md-8">
			    	<div class="box box-poison">
				      <div class="box-header with-border">
				        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Latest orders</h3>
				      </div>
				      <div class="box-body">
				        <table class="table table-basic table-striped table-bordered table-hover">
				          <thead>
				            <tr>
				              <th class="text-center">#</th>
				              <th class="text-center">Project</th>
				              <th class="text-center">Client</th>
				              <th class="text-center">Telephone</th>
				              <th class="text-center">Total</th>
				              <th class="text-center">Date</th>
				              <th class="text-center">Action</th>
				            </tr>
				          </thead>
				          <tbody>
				          <? $i = 1;
				            foreach ($order as $d) {
				          ?>
				            <tr>
				              <td class="text-center"><?=$d->order_order?></td>
				              <td><?=$d->order_project?></td>
				              <td><?=$d->client_name?></td>
				              <td><?=$d->client_phone?></td>
				              <td class="text-right">$<?=Base::Format($d->order_total,2,".",",")?></td>
				              <td class="text-center"><?=Base::removeTS($d->order_fecha_reg)?></td>
				              <td class="text-center">
				                <a class="btn btn-flat btn-primary btn-sm" href="?ver=orders&opc=ver&id=<?=$d->id_order?>"><i class="fa fa-search"></i></a>
				              </td>
				            </tr>
				          <?
				            $i++;
				            }
				          ?>        
				          </tbody>
				        </table>
				      </div>
				      <div class="box-footer">

				      	<a class="btn btn-flat btn-default pull-right" href="?ver=orders">View All Orders</a>
				      </div>
				    </div>
				  </div>
				  <div class="col-md-4">
				  	<div class="box box-danger">
				      <div class="box-header with-border">
				        <h3 class="box-title"><i class="fa fa-cubes"></i> Recently Added Products</h3>
				      </div>
				      <div class="box-body">
	              <ul class="products-list product-list-in-box">
	              <?
	              	foreach($product as $d) {
	              		if($d->opc=="cabi"){ $cost = $d->cost." Items"; }else{ $cost = "$".Base::Format($d->cost,2,".",","); }
	              ?>
	                <li class="item">
	                  <div class="product-img">
	                    <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->foto)?>" alt="<?=Base::Img("images/productos/".$d->foto)?>">
	                  </div>
	                  <div class="product-info">
	                    <a href="?ver=products&opc=<?=$d->opc?>&id=<?=$d->id?>" class="product-title"><?=$d->product?><span class="label label-danger pull-right"><?=$cost?></span></a>
                      <span class="product-description">
                        <?=$d->name?>
                      </span>
	                  </div>
	                </li>
	                <?}?>
	              </ul>
	            </div>
				      <div class="box-footer text-center">
				      	<a href="?ver=products">View All Products</a>
				      </div>
				    </div>
				  </div>
		    </div>
	    </div>
		<?
		break;
		default:
			require Base::LoadView($inicio);
		break;
	endswitch;//Switch($vista)
endif;
require_once 'footer.php';
?>
