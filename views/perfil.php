<?
$usuarios = new Usuarios();
$user = $usuarios->perfil();
?>
<section class="content-header">
  <h1>Perfil</h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=ordenes"> Perfil</a></li>
  </ol>
</section>
<div class="content">
  <section>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#Modal-password">Cambiar contraseña</button>
    <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#Modal-perfil">Modificar datos</button>
  </section>
  <section class="perfil">
    <div class="row">
      <div class="col-md-12">
        <h2 class="page-header" style="margin-top:0!important">
          <i class="fa fa-user" aria-hidden="true"></i> <?=$user->user_nombres?> <?=$user->user_apellidos?>
          <small class="pull-right">Registrado: <?=Base::ConvertTS($user->user_fecha_reg)?></small>
        </h2>
      </div>

      <div class="col-md-12">
        <div class="col-md-4">
          <h4>Datos del usuario</h4>
          <p><b>Nombres:</b> <?=$user->user_nombres?></p>
          <p><b>Apellidos:</b> <?=$user->user_apellidos?></p>
          <p><b>Correo:</b> <?=$user->user_email?></p>
          <p><b>Telefono:</b> <?=$user->user_telefono?></p>
        </div>

        <div class="col-md-4">
          <h4>&nbsp;</h4>
          <p><b>Nivel:</b> <?=($user->user_nivel=="A")?'Administrador':'Usuario'?></p>
          <p><b>Estado de la cuenta:</b> <?=($user->user_estado=="A")?'Activa':'Inactiva'?></p>
        </div>
      </div>
    </div><!--row-->
  </section>
</div>


<!-- Modal de modificar -->
<div class="modal fade" id="Modal-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 id="myModalLabel" class="modal-title"><b>Cambiar contraseña</b></h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <form id="fr-newpass" action="funciones/class.usuarios.php" method="post">
              <input type="hidden" name="action" value="newpass">
              <div class="form-group">
                <label for="p1">Contraseña actual: *</label>
                <input id="p1" class="form-control" type="password" name="p1" title="Contraseña actual." required>
              </div>
              <div class="form-group">
                <label for="p2">Nueva contraseña: *</label>
                <input id="p2" class="form-control" type="password" name="p2" title="Nueva contraseña." required>
              </div>
              <div class="form-group">
                <label for="p3">Repetir contraseña: *</label>
                <input id="p3" class="form-control" type="password" name="p3" title="Repetir nueva contraseña." required>
              </div>

              <p class="help-block" style="color:red">* Campos obligatorios</p>

              <div class="progress" style="display:none">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                </div>
              </div>

              <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

              <button id="fr-b-newpass" class="btn btn-flat btn-success" data-loading-text="Guardando...">Cambiar contraseña</button>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
            </form>
          </div>
        </div>
      </div><!--modal body-->
    </div>
  </div>
</div>
<!--- fin cambiar contraseña -->

<!-- Modal de modificar -->
<div class="modal fade" id="Modal-perfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 id="myModalLabel" class="modal-title"><b>Cambiar contraseña</b></h5>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <form id="fr-registro" action="funciones/class.usuarios.php" method="post">
              <input type="hidden" name="action" value="edit">
              
              <div class="form-group">
                <label for="nombres">Nombres: *</label>
                <input id="nombre" class="form-control" type="text" name="nombres" title="Nombres"  value="<?=$user->user_nombres?>">
              </div>

              <div class="form-group">
                <label for="apellidos">Apellidos: *</label>
                <input id="apellidos" class="form-control" type="text" name="apellidos" title="Apellidos"  value="<?=$user->user_apellidos?>">
              </div>

              <div class="form-group">
                <label for="correo">Correo: *</label>
                <input id="correo" class="form-control" type="text" name="email" title="Correo"  value="<?=$user->user_email?>">
              </div>

              <div class="form-group">
                <label for="telefono">Telefono: *</label>
                <input id="telefono" class="form-control" type="text" name="telefono" title="Telefono"  value="<?=$user->user_telefono?>">
              </div>

              <div class="progress" style="display:none">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                </div>
              </div>

              <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

              <button id="fr-modificar" class="btn btn-flat btn-success b-submit" data-loading-text="Guardando...">Modificar</button>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
            </form>
          </div>
        </div>
      </div><!--modal body-->
    </div>
  </div>
</div>
<!--- fin modalmodificar -->
