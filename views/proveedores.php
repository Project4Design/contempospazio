<?
$proveedores   = new Proveedores();
if($opc=="add"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Proveedores </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=proveedores"> Proveedores</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    if($prov->user_estado == "A"){
      $color = "green";
      $estado = "Activo";
    }else{
      $color  = "red";
      $estado = "Inactivo";
    }
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=proveedores"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-flat btn-success" href="?ver=proveedores&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Modificar</a>
      <?if($_SESSION['id']!=$id){?>
        <span id="botones">
        <?if($prov->user_estado == "A"){?>
          <button id="btn-activar" class="btn btn-flat btn-danger" data-toggle="modal" data-target="#activarModal" data-title="Desactivar" data-val="I"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;Desactivar</button>
        <?}else{?>
          <button id="btn-activar" class="btn btn-flat btn-success" data-toggle="modal" data-target="#activarModal" data-title="Activar" data-val="A"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Activar</button>
        <?}?>
        </span>
      <?}?>
      <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#passModal"><i class="fa fa-key" aria-hidden="true"></i> Reesteblecer contraseña</button>
      <?if($_SESSION['id']!=$id){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-user-times" aria-hidden="true"></i> Eliminar</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-user" aria-hidden="true"></i> 
            <?=$prov->user_nombres?> <?=$prov->user_apellidos?>
            <small class="pull-right">Registrado: <?=Base::Convert($prov->user_fecha_reg)?></small>
          </h2>
        </div>
        <div class="col-md-4">
          <h4>Datos del usuario</h4>
          <p><b>Nombres:</b> <?=$prov->user_nombres?></p>
          <p><b>Apellidos:</b> <?=$prov->user_apellidos?></p>
          <p><b>Correo:</b> <?=$prov->user_email?></p>
          <p><b>Telefono:</b> <?=$prov->user_telefono?></p>
        </div>

        <div class="col-md-4">
          <h4>&nbsp;</h4>
          <p><b>Nivel:</b> <?=($prov->user_nivel=="A")?'Administrador':'Usuario'?></p>
          <p><b>Estado:</b> <span id="estado" style="color:<?=$color?>"><?=$estado?></span></p>
        </div>
      </div>
    </section>

    <div id="activarModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="activarModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="activarModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="activar-user" class="col-md-8 col-md-offset-2" action="funciones/class.proveedores.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="activar">
                <input id="estado-val" type="hidden" name="estado" value="">
                <div class="form-group">
                  <h4 class="text-center">Confirme que des&eacute;a <b><span id="modal-msj"></span></b> este usuario.</h4><br>
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="b-habilitar" class="btn btn-flat btn-primary" type="submit" data-loading-text="Cargando..." >Aceptar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="passModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="passModalLabel">Reestablecer contraseña</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="pass-user" class="col-md-8 col-md-offset-2" action="funciones/class.proveedores.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="recuperar">
                <p class="text-center">Reestablecer contraseña para este usuario.</p>

                <div class="form-group">
                  <label for="filtro">Establecer manualmente?: *</label>
                  <label class="radio-inline">
                    <input id="switch" class="switch" type="checkbox" name="filtro">
                  </label>
                </div>

                <fieldset id="aleatorio">
                  <p>Contaseña: <b><span id="password" style="padding:10px;background-color:#D6CACA;color:#D83636;"></span></b></p>
                  <p class="help-block">Tome nota de la contraseña una vez cambiada.</p>
                </fieldset>

                <fieldset id="manual" disabled style="display:none">
                  <div class="form-group">
                    <label for="p1" class="control-label">Contraseña nueva: *</label>
                    <input id="p1" class="form-control" type="password" name="p1" required>
                  </div>
                  <div class="form-group">
                    <label for="p2" class="control-label">Repetir contraseña: *</label>
                    <input id="p2" class="form-control" type="password" name="p2" required>
                  </div>
                </fieldset>

                <div class="progress" style="display:none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div>
                </div>

                <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

                <center>
                  <button id="b-recuperar" class="btn btn-flat btn-primary" type="submit" data-loading-text="Cargando..." >Aceptar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="delete-user" class="col-md-8 col-md-offset-2" action="funciones/class.proveedores.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="eliminar">
                <div class="form-group">
                  <h4 class="text-center">¿Esta seguro que des&eacute;a <b>Eliminar</b> este usuario?</h4><br>
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="b-eliminar" class="btn btn-flat btn-danger b-submit" type="submit" data-loading-text="Cargando..." >Eliminar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $(".switch").bootstrapSwitch({
          state: false,
          size: 'small',
          onText: 'Sí',
          offText: 'No',
          onSwitchChange: function(event,state){
            activate(event,state);
          }
        });
      });

      function activate(e,s){
        if(s){
          $('#manual').show();
          $('#manual').prop('disabled',false);
          $('#aleatorio').hide('fast');
        }else{
          $('#manual').hide('fast');
          $('#manual').prop('disabled',true);
          $('#aleatorio').show();
        }
      }
      $('#activarModal').on('show.bs.modal',function(event){
        var button = $(event.relatedTarget);
        var title = button.data('title');
        var val = button.data('val');
        var modal = $(this);

        modal.find('#estado-val').val(val);
        modal.find('#modal-msj').text(title);
        modal.find('.modal-title').text(title+" usuario");
        modal.find('.modal-body #estado').val(title);
      });

      $('#b-habilitar').click(function(e){
        e.preventDefault();
        $('#b-habilitar').button('loading');
        $('#activar-user .progress').show();
        $('#activar-user .alert').hide('fast');

        var form = $('#activar-user');
        var action = form.attr('action');

        $.ajax({
          type: 'POST',
          cache: false,
          url: action,
          data: form.serialize(),
          dataType: 'json',
          success: function(r){
            if(r.response){
              $('#activar-user .alert').removeClass('alert-danger').addClass('alert-success');
            }else{
              $('#activar-user .alert').removeClass('alert-success').addClass('alert-danger');
            }
            if(r.data != null){
              if(parseInt(r.data.e)){
                $('#estado').html('Activo').css('color','green');
              }else{
                $('#estado').html('Inactivo').css('color','red');
              }
              $('#botones').html('');
              $('#botones').html(r.data.b);
            }

            $('#activar-user .alert #msj').html(r.msj);
          },
          error: function(){
            $('#activar-user .alert').removeClass('alert-success').addClass('alert-danger');
            $('#activar-user .alert #msj').html('Ha ocurrido un error inesperado');
          },
          complete: function(){
            $('#b-habilitar').button('reset');
            $('#activar-user .progress').hide('fast');
            $('#activar-user .alert').show().delay(7000).hide('slow');
          }
        })
      });

      $('#b-recuperar').click(function(e){
        e.preventDefault();
        $('#pass-user .alert').hide('fast');
        $('#b-recuperar').button('loading');
        $('#pass-user .progress').show();

        var form = $('#pass-user');
        var action = form.attr('action');
        var pasa = false;
        if($('input[name=filtro]').is(':checked')){ 
          var switche = true;
        }else{ 
          var switche = false;
        }       
        
        var fields = $('#pass-user input:not(:disabled)').filter('[required]').length;
        $('#pass-user input:not(:disabled)').filter('[required]').each(function(){
          var val = $(this).val();
          if(val == ""){
            $(this).closest('.form-group').addClass('has-error');
          }
          else{
            $(this).closest('.form-group').removeClass('has-error');
            fields = fields-1;
          }
        });

        if(fields!=0){
          $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
          $('#pass-user .alert #msj').html('Debe completar todos los campos requeridos');
          $('#pass-user .progress').hide();
          $('#b-recuperar').button('reset');
          $('#pass-user .alert').show().delay(7000).hide('slow');
        }else{
          if(switche){
            var p1 = $('#p1').val();
            var p2 = $('#p2').val();
            if(p1===p2){
              pasa = true;
            }else{
              $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
              $('#pass-user .alert #msj').html('Las contraseñas no coinciden');
              $('#pass-user .progress').hide();
              $('#b-recuperar').button('reset');
              $('#pass-user .alert').show().delay(7000).hide('slow');
            }
          }else{
            pasa = true;
          }

          if(pasa){
            $.ajax({
              type: 'POST',
              cache: false,
              url: action,
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  $('#pass-user .alert').removeClass('alert-danger').addClass('alert-success');
                  $('#pass-user')[0].reset();
                }else{
                  $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
                }
                $('#password').html(r.data);
                $('#pass-user .alert #msj').html(r.msj);
              },
              error: function(){
                $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
                $('#pass-user .alert #msj').html('Ha ocurrido un error inesperado');
              },
              complete: function(){
                $('#b-recuperar').button('reset');
                $('#pass-user .progress').hide('fast');
                $('#pass-user .alert').show().delay(7000).hide('slow');
              }
            })
          }
        }
      });

    </script>


  <?
  break;
  case 'add':
  case 'edit':
    $prov = $proveedores->obtener($id);
    if($prov==NULL){ $id = 0; $action = "add"; }else{ ($_SESSION['nivel']=="A")?$action="edit_admin" : $action="edit"; }
  ?>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-truck"></i> <?=($id>0)?'Modificar':'Agregar'?> Proveedor</h3><br>
          </div>
          <div class="box-body">
            <form id="msform"  action="funciones/class.proveedores.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <input id="action" type="hidden" name="action" value="<?=$action?>">
                  <input id="id" type="hidden" name="id" value="<?=($id>0)?$id:'0';?>">
                  <!-- progressbar -->
                  <ul id="progressbar">
                    <li class="form-active">Informacion</li>
                    <li>Colores</li>
                    <li>Personal Details</li>
                  </ul>

                  <!-- fieldsets -->
                  <fieldset>
                    <h2 class="fs-title">Informacion del proveedor</h2>
                    <h3 class="fs-subtitle">PASO 1</h3>
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Proveedor" />
                    </div>
                    <div class="form-group">
                      <label for="telefono">Telefono</label>
                      <input id="telefono" class="form-control" type="text" name="telefono" placeholder="Telefono" />
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input class="form-control" type="text" name="email" placeholder="Email" />
                    </div>

                    <div class="form-group">
                      <label for="logo">Logo</label>
                      <input id="logo" class="form-control" type="file" name="logo" accept=""/>
                    </div>
                    <input type="button" name="next" class="next action-button" value="Next" />
                  </fieldset>

                  <fieldset>
                    <h2 class="fs-title">Agregar colores</h2>
                    <h3 class="fs-subtitle"></h3>
                    <input class="form-control" type="text" name="twitter" placeholder="Twitter" />
                    <input class="form-control" type="text" name="facebook" placeholder="Facebook" />
                    <input class="form-control" type="text" name="gplus" placeholder="Google Plus" />
                    <input type="button" name="previous" class="previous action-button" value="Previous" />
                    <input type="button" name="next" class="next action-button" value="Next" />
                  </fieldset>

                  <fieldset>
                    <h2 class="fs-title">Personal Details</h2>
                    <h3 class="fs-subtitle">We will never sell it</h3>
                    <input class="form-control"  type="text" name="fname" placeholder="First Name" />
                    <input class="form-control"  type="text" name="lname" placeholder="Last Name" />
                    <input class="form-control"  type="text" name="phone" placeholder="Phone" />
                    <textarea class="form-control" name="address" placeholder="Address"></textarea>
                    <input type="button" name="previous" class="previous action-button" value="Previous" />
                    <input type="submit" name="submit" class="submit action-button" value="Submit" />
                  </fieldset>
                  
                </div>
              </div>
            </form>    
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      //jQuery time
      var current_fs, next_fs, previous_fs; //fieldsets
      var left, opacity, scale; //fieldset properties which we will animate
      var animating; //flag to prevent quick multi-click glitches

      $(".next").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("form-active");
        
        //show the next fieldset
        next_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
          step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale current_fs down to 80%
            scale = 1 - (1 - now) * 0.2;
            //2. bring next_fs from the right(50%)
            left = (now * 50)+"%";
            //3. increase opacity of next_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({'transform': 'scale('+scale+')'});
            next_fs.css({'left': left, 'opacity': opacity});
          }, 
          duration: 400, 
          complete: function(){
            current_fs.hide();
            animating = false;
          }, 
          //this comes from the custom easing plugin
          easing: 'easeInOutBack'
        });
      });

      $(".previous").click(function(){
        if(animating) return false;
        animating = true;
        
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show(); 
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
          step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale previous_fs from 80% to 100%
            scale = 0.8 + (1 - now) * 0.2;
            //2. take current_fs to the right(50%) - from 0%
            left = ((1-now) * 50)+"%";
            //3. increase opacity of previous_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({'left': left});
            previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
          }, 
          duration: 400, 
          complete: function(){
            current_fs.hide();
            animating = false;
          }, 
          //this comes from the custom easing plugin
          easing: 'easeInOutBack'
        });
      });

      $(".submit").click(function(){
        return false;
      })

    </script>
  <?
  break;
  default:
    $prov = $proveedores->consulta();
  ?>
    <div class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Proveedores registrados</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=proveedores&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Agregar proveedor</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Apellido</th>
              <th class="text-center">Email</th>
              <th class="text-center">Nivel</th>
              <th class="text-center">Accion</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;
            foreach ($prov as $d) {
          ?>
            <tr>
              <td class="text-center"><?=$i?></td>
              <td><?=$d->user_nombres?></td>
              <td><?=$d->user_apellidos?></td>
              <td><?=$d->user_email?></td>
              <td class="text-center"><?=($d->user_nivel == "A")?'Administrador':'Colaborador'?></td>
              <td class="text-center">
                <a class="btn btn-flat btn-primary btn-sm" href="?ver=proveedores&opc=ver&id=<?=$d->id_user?>"><i class="fa fa-search"></i></a>
                <a class="btn btn-flat btn-success btn-sm" href="?ver=proveedores&opc=edit&id=<?=$d->id_user?>"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
          <?
            $i++;
            }
          ?>        
          </tbody>
        </table>
       </div>
    </div>
  <?
  break;
endswitch;
?>
</div>