<?
require_once 'config/config.php';
if(isset($_GET['ver'])){ $inicio = $_GET['ver']; }else{ $inicio = "index"; }
if(isset($_GET['opc'])){ $opc = $_GET['opc']; }else{ $opc = ""; }
if(isset($_GET['id'])){ $id = $_GET['id']; }else{ $id = 0; }

?>
<!DOCTYPE html>
<html lang="ES-es">
  <head>
    <title>Contempospazio</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Tell the browser to be responsive to screen width -->
    <link rel="icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/pico">

    <?=Base::Meta("viewport","width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no")?>

    <!-- Bootstrap 3.3.5 -->
    <?=Base::Css("includes/css/bootstrap.min.css")?>

    <?=Base::Css("includes/css/bootstrap-switch.css")?>

    <!-- Font Awesome -->
    <?=Base::Css("includes/css/font-awesome.min.css")?>
    <?=Base::Css("includes/css/glyphicons.css")?>
    <?=Base::Css("includes/css/styles.css")?>
    <?=Base::Css("plugins/daterangepicker/daterangepicker.css")?>

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <?=Base::Css("includes/css/_all-skins.min.css")?>
    <!-- Select 2 -->
    <?=Base::Css("plugins/select2/select2.min.css")?>
    <!-- Theme style -->
    <?=Base::Css("includes/css/AdminLTE.min.css")?>

    <!-- Datatable -->
    <?=Base::Css("includes/css/datatables.min.css")?>

    <?=Base::Js("includes/js/jquery-2.2.1.min.js")?>
    <?=Base::Js("includes/js/bootstrap.js")?>
    <?=Base::Js("includes/js/bootstrap-switch.min.js")?>

    <!-- Datatable -->
    <?=Base::Js("includes/js/datatables.min.js")?>

    <?=Base::Js("includes/js/highcharts.js")?>
    <!-- AdminLTE App -->
    <?=Base::Js("includes/js/app.min.js")?>

    <?=Base::Js("plugins/daterangepicker/moment.min.js")?>
    <?=Base::Js("plugins/daterangepicker/daterangepicker.js")?>

    <!-- Funciones -->
    <?=Base::Js("includes/js/funciones.js")?>

    <?=Base::Js("includes/js/jquery.easing.min.js")?>
    
    <?=Base::Js("plugins/select2/select2.min.js")?>
  </head>

  <body id="main-body" class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="inicio.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img class="img-responsive" src="images/logo.JPG" alt="Logo Contempospazio"/ style="height: 50px"></span>
          
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
            <b style="font-size:18px;">
              <img src="<?=Base::Img("images/logo.JPG")?>" alt="logo" width="20px">&nbsp;CONTEMPOSPAZIO
            </b>
          </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                  <span><?=$_SESSION['nombre']?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                    <p>
                      <?=$_SESSION['email']?><br>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="?ver=profile" class="btn btn-default btn-flat"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
                    </div>
                    <div class="pull-right">
                      <a id="b-logout" href="#" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENU</li>

            <li>
              <a href="inicio.php">
                <i class="fa fa-home" aria-hidden="true"></i>
                <span>Home</span>
              </a>
            </li>

            <li class="<?=($inicio=='cotizacion')?'active':''?>">
              <a href="?ver=quotation">
                <i class="fa fa-calculator" aria-hidden="true"></i>
                <span>Quotation</span>
              </a>
            </li>

            <li class="<?=($inicio=='orders')?'active':''?>">
              <a href="?ver=orders">
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                <span>Orders</span>
              </a>
            </li>

            <? if($_SESSION['nivel'] == "A"): ?>
            <li class="treeview <?=($inicio=="usuarios")?'active':'';?>">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Users</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu <?=($inicio=='usuarios')?'active':'';?>">
                <li><a href="?ver=users"><i class="fa fa-circle-o"></i>Users</a></li>
                <li><a href="?ver=users&opc=add"><i class="fa fa-circle-o"></i>Add User</a></li>
              </ul>
            </li>
            <? endif; ?>

            <? if($_SESSION['nivel'] == "A"): ?>
            <li class="treeview <?=($inicio=="clients")?'active':'';?>">
              <a href="#">
                <i class="fa fa-address-book-o"></i>
                <span>Clients</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu <?=($inicio=='clients')?'active':'';?>">
                <li><a href="?ver=clients"><i class="fa fa-circle-o"></i>Clients</a></li>
                <li><a href="?ver=clients&opc=add"><i class="fa fa-circle-o"></i>Add Client</a></li>
              </ul>
            </li>
            <? endif; ?>

            <li class="treeview <?=($inicio=="products")?'active':'';?>">
              <a href="#">
                <i class="fa fa-cubes"></i>
                <span>Products</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu <?=($inicio=='products')?'active':'';?>">
                <li><a href="?ver=products"><i class="fa fa-circle-o"></i>Products</a></li>
                <li><a href="?ver=products&opc=add"><i class="fa fa-circle-o"></i>Add product</a></li>
              </ul>
            </li>
<!--
            <li>
              <a href="#">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                <span>Materials</span>
              </a>
            </li>
-->
            <li class="<?=($inicio=='statistics')?'active':''?>">
              <a href="?ver=statistics">
                <i class="fa fa-area-chart" aria-hidden="true"></i>
                <span>Statistics</span>
              </a>
            </li>

            <li class="<?=($inicio=='configuration')?'active':''?>">
              <a href="?ver=configuration">
                <i class="fa fa-cogs" aria-hidden="true"></i>
                <span>Configuration</span>
              </a>
            </li>

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
      <!--Contenido TODO LO DE EL MEDIO -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
