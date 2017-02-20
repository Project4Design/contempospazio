<?
$usuarios = new Usuarios();
$user = $usuarios->perfil();
?>
<section class="content-header">
  <h1>My Profile</h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
    <li class="active">Profile</li>
  </ol>
</section>
<div class="content">
  <section>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#Modal-password"><i class="fa fa-key" aria-hidden="true"></i> Change password</button>
    <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#Modal-perfil"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</button>
  </section>
  <section class="perfil">
    <div class="row">
      <div class="col-md-12">
        <h2 class="page-header" style="margin-top:0!important">
          <i class="fa fa-user" aria-hidden="true"></i> <?=$user->user_nombres?> <?=$user->user_apellidos?>
          <small class="pull-right">Registered: <?=Base::ConvertTS($user->user_fecha_reg)?></small>
        </h2>
      </div>

      <div class="col-md-12">
        <div class="col-md-4">
          <p><b>Name:</b> <?=$user->user_nombres?></p>
          <p><b>Last name:</b> <?=$user->user_apellidos?></p>
          <p><b>Email:</b> <?=$user->user_email?></p>
          <p><b>Phone:</b> <?=$user->user_telefono?></p>
        </div>

        <div class="col-md-4">
          <p><b>Level:</b> <?=($user->user_nivel=="A")?'Admin':'User'?></p>
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
        <h4 id="myModalLabel" class="modal-title">Change Password</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <form id="fr-newpass" action="funciones/class.usuarios.php" method="post">
              <input type="hidden" name="action" value="newpass">
              <div class="form-group">
                <label for="p1">Password: *</label>
                <input id="p1" class="form-control" type="password" name="p1" title="Contraseña actual." required>
              </div>
              <div class="form-group">
                <label for="p2">New password: *</label>
                <input id="p2" class="form-control" type="password" name="p2" title="Nueva contraseña." required>
              </div>
              <div class="form-group">
                <label for="p3">Rrepeat password: *</label>
                <input id="p3" class="form-control" type="password" name="p3" title="Repetir nueva contraseña." required>
              </div>

              <div class="progress" style="display:none">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                </div>
              </div>

              <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

              <button id="fr-b-newpass" class="btn btn-flat btn-success" data-loading-text="Guardando...">Save</button>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
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
        <h4 id="myModalLabel" class="modal-title">Edit information</h4>
      </div>

      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <form id="fr-registro" action="funciones/class.usuarios.php" method="post">
              <input type="hidden" name="action" value="edit">
              
              <div class="form-group">
                <label for="nombres">Name: *</label>
                <input id="nombre" class="form-control" type="text" name="nombres" title="Nombres"  value="<?=$user->user_nombres?>">
              </div>

              <div class="form-group">
                <label for="apellidos">Last name: *</label>
                <input id="apellidos" class="form-control" type="text" name="apellidos" title="Apellidos"  value="<?=$user->user_apellidos?>">
              </div>

              <div class="form-group">
                <label for="correo">Email: *</label>
                <input id="correo" class="form-control" type="text" name="email" title="Correo"  value="<?=$user->user_email?>">
              </div>

              <div class="form-group">
                <label for="telefono">Phone: *</label>
                <input id="telefono" class="form-control" type="text" name="telefono" title="Telefono"  value="<?=$user->user_telefono?>">
              </div>

              <div class="progress" style="display:none">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                </div>
              </div>

              <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

              <button id="fr-modificar" class="btn btn-flat btn-success b-submit" data-loading-text="Guardando...">Save</button>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div><!--modal body-->
    </div>
  </div>
</div>
<!--- fin modalmodificar -->
