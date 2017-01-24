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
		case 'proveedores':
			require_once 'views/proveedores.php';
		break;
		case 'productos':
			require_once 'views/productos.php';
		break;
		default:
			$usuarios  = new Usuarios();
			//Total de usuarios
			$totaluser = count($usuarios->consulta());

			$productos = new Productos();
			//Total de productos
			$totalp    = count($productos->consulta());
		?>
	    <section class="content-header">
	      <h1 class="text-center">
	        CONTEMPOSPAZIO
	      </h1>
	      <ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
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

		            <p>Usuarios</p>
		          </div>
		          <div class="icon">
		            <i class="fa fa-user-plus"></i>
		          </div>
		          <a href="?ver=usuarios" class="small-box-footer">
		            Mas informacion <i class="fa fa-arrow-circle-right"></i>
		          </a>
		        </div>
		      </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-red">
	            <div class="inner">
	              <h3><?=$totalp?></h3>

	              <p>Productos</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-columns"></i>
	            </div>
	            <a href="?ver=productos" class="small-box-footer">
	              Mas informacion <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>

					<div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-purple">
	            <div class="inner">
	              <h3>0</h3>

	              <p>Materiales</p>
	            </div>
	            <div class="icon">
	              <i class="fa fa-cubes"></i>
	            </div>
	            <a href="#" class="small-box-footer">
	              Mas informacion <i class="fa fa-arrow-circle-right"></i>
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
