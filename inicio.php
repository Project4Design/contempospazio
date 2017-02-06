<?
require_once 'header.php';
if(isset($_SESSION['id'])===false):
	require_once 'views/prohibido.php';
else:

	switch($inicio):
		case 'perfil':
			require_once 'views/perfil.php';
		break;
		case 'usuarios':
			if($_SESSION['nivel']=="A"):
				require_once 'views/usuarios.php';
			else:
				require_once 'views/prohibido.php';
			endif;
		break;
		case 'configuracion':
			require_once 'views/configuracion.php';
		break;
		case 'productos':
			require_once 'views/productos.php';
		break;
		case 'cotizacion':
			require_once 'views/cotizacion.php';
		break;
		default:
			$usuarios  = new Usuarios();
			//Total de usuarios
			$totaluser = count($usuarios->consulta());

			$productos = new Productos();
			//Total de productos
			$totalg = count($productos->consulta_gabinetes());
			$totalf = count($productos->consulta_fregaderos());
			$totalt = count($productos->consulta_topes());
		?>
	    <section class="content-header">
	      <h1 class="text-center">
	        CONTEMPOSPAZIO
	      </h1>
	      <ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	      </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">
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
		          <a href="?ver=usuarios" class="small-box-footer">
		            More info <i class="fa fa-arrow-circle-right"></i>
		          </a>
		        </div>
		      </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-red">
	            <div class="inner">
	              <h3><?=($totalg+$totalf+$totalt)?></h3>

	              <p>Products</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-columns"></i>
	            </div>
	            <a href="?ver=productos" class="small-box-footer">
	              More info <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-purple">
	            <div class="inner">
	              <h3>0</h3>

	              <p>Materials</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-cubes"></i>
	            </div>
	            <a href="#" class="small-box-footer">
	              More info <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>
	        <!-- /.col -->
		    </div>

	    </section>

		<?
		break;
	endswitch;//Switch($vista)
endif;
require_once 'footer.php';
?>
