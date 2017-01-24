<?
$productos = new Productos();
if($opc=="add"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Productos </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=productos"> Productos</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    $gabi  = $productos->obtener($id);
    $items = $productos->items($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=productos"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Modificar Informacion</button>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-columns" aria-hidden="true"></i> Producto
            <small class="pull-right">Registrado: <?=$gabi->gabi_fecha_reg?></small>
          </h2>
        </div>
        <div id="areaForm" class="col-md-3">
          <span id="backup" class="hide"></span>
          <form id="fproducto" class="" action="funciones/class.productos.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="gabinete" value="<?=$id?>">

            <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$gabi->gabi_foto)?>" alt="<?=Base::Img("images/productos/".$gabi->gabi_foto)?>" prev="">

            <span id="descripcion"><?=$gabi->gabi_descripcion?></span>
          </form>
        </div>
        <div class="col-md-9">
          <h3>Items agregados a este producto</h3>
          <form id="fitems" class="" method="post" action="funciones/class.productos.php">
            <input id="action" type="hidden" name="action" value="add_item">
            <input id="gabinete" type="hidden" name="gabinete" value="<?=$id?>">
            <input id="id_gp" type="hidden" name="item" value="0">
            <table id="table-items" class="table table-bordered table-striped table-condensed">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Item</th>
                  <th>GS</th>
                  <th>MGC</th>
                  <th>RBS</th>
                  <th>ES & MS</th>
                  <th>WS</th>
                  <th>MIW</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tbody">
              </tbody>
              <tfoot>
                <tr>
                  <td></td>
                  <td>
                    <div class="form-group" style="margin:0">
                      <input id="gp_codigo" class="form-control" type="text" name="codigo" placeholder="Item" required>
                    </div>
                  </td>
                  <td><input id="gp_gs" class="form-control" type="text" name="gs" placeholder="0.00"></td>
                  <td><input id="gp_mgc" class="form-control" type="text" name="mgc" placeholder="0.00"></td>
                  <td><input id="gp_rbs" class="form-control" type="text" name="rbs" placeholder="0.00"></td>
                  <td><input id="gp_esms" class="form-control" type="text" name="esms" placeholder="0.00"></td>
                  <td><input id="gp_ws" class="form-control" type="text" name="ws" placeholder="0.00"></td>
                  <td><input id="gp_miw" class="form-control" type="text" name="miw" placeholder="0.00"></td>
                  <td class="text-center">
                    <button id="b-items" class="btn btn-sm btn-primary btn-flat" type="submit">
                      <i class="fa fa-save" aria-hidden="true"></i>
                    </button>
                  </td>
                </tr>
              </tfoot>
            </table>
            <div class="alert alert-dismissible" role="alert" style="display:none">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
            </div>

            <div class="progress progress-sm active" style="display:none">
              <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                <span class="sr-only">100% Complete</span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.productos.php">
            <input type="hidden" name="action" value="del_item">
            <input id="d-item" type="hidden" name="item" value="0">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Eliminar Item <b id="cod-item"></b></h4>
            </div>
            <div class="modal-body">
              <p>¿Esta seguro que desea eliminar este Item?</p>

              <div class="alert alert-dismissible" role="alert" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
              </div>

              <div class="progress progress-sm active" style="display:none">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                  <span class="sr-only">100% Complete</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
              <button id="b-del-item" type="submit" class="btn btn-outline">Eliminar</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">

      //Activar formulario
      function editForm(){
        //Quitar el evento activar formulario
        $('#b-activate,#img,#descripcion').off();
        //Quitar los eventos doble click a la imagen y descripcion

        $('#fproducto').addClass('on-edit glow');
        //Tomar la descripcion y crear el textarea
        var descripcion = $('#descripcion').html();
        $('#backup').text(descripcion);
        var textArea    = $("<textarea id='descripcion' class='form-control glow' name='descripcion' required/>");

        //Crear los contenedores de la imagen y el input
        var widget  = $('<div class="imageUploadWidget"/>');
        var imgArea = $('<div class="imageArea"/>');
        var spinner = $('<img class="spinner-image" src="images/spinner.gif">');
        var btnArea = $('<div class="btnArea"/>');
        var input   = $("<input id='file' name='foto' accept='image/jpeg,image/png' type='file'>");

        //Tomar la imagen
        var img   = $("#img");

        //Crear el para los grupos
        var group = $("<div class='form-group'>");

        //Botones del formulario
        var botones = $("<input id='b-edit-form' class='btn btn-primary btn-flat btn-sm' type='submit' value='Guardar'/><button id='fcancel' class='btn btn-flat btn-sm btn-default pull-right' type='button'>Cancelar</button>");

        //Agregar la descripcion al textArea y reemplazarlo por el span de la descripcion
        //Envolverlo en un div.form-group
        textArea.val(descripcion);
        $('#descripcion').replaceWith(textArea);
        textArea.wrap(group);

        //Meter la imagen dentro de los contenedores
        //Envolverlo en div.form-group
        $('#img').wrap(group).wrap(widget).wrap(imgArea).after(spinner);
        $('.imageUploadWidget').append(btnArea);
        btnArea.append(input);
        //Meter los botones del formulario en un grupo
        group.append(botones);
        //Agregar el grupo de botones al formulario
        $('#fproducto').append(group);

        setTimeout(function(){
          $('.glow').removeClass('glow');
        },1000);

        //Colocar el focus en el Texarea
        textArea.focus();

        //Asegnar el evento para guardar los cambios
        $('#b-edit-form').click(function(e){save(e)});

        //Asignar el evento para desactivar el formulario al boton cancelar
        $('#fcancel').click(disableForm);

        //Asignar evento al cargar una imagen
        $('#file').change(preview);
      }

      //------------------------------------------------------------------------------------------------

      //Desactivar formulario
      function disableForm(change){
        change = change?true:false;
        var form = "#fproducto";
        var descripcion = $('#backup').text();
        var span = $("<span id='descripcion'>");
        var img = $('#img');
        var alt = img.attr('alt');
        var prev = (img.attr('prev')=="")?alt:img.attr('prev');
        $('#fproductos input[type!="hidden"],#fproducto .form-group').remove();
        $(form).append(img,span);
        if(change){img.attr('src',prev);}
        span.html(descripcion);
        img.show();

        $('#b-activate').click(editForm);
        $('#img,#descripcion').dblclick(editForm);
      }
      //End of crazy shit

      $(document).ready(function(){
        //Asignar el evento de activar formulario
        $('#b-activate').click(editForm);
        $('#img,#descripcion').dblclick(editForm);
        //Cargar los Items
        setTimeout(loadItems,300);
        //

        $('#deleteModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var codigo = button.data('codigo')
          var id = button.data('id')

          var modal = $(this)
          modal.find('#cod-item').text(codigo);
          modal.find('#d-item').val(id);
        });

        //Guardar o Editar Item
        $('#b-items').click(function(e){
          e.preventDefault();
          var codigo = $('#gp_codigo').val().replace(/\s+/g, '');

          if(codigo==""){
            $('#gp_codigo').closest('.form-group').addClass('has-error');
            $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
            $('#fitems .alert #msj').text('Debe completar este campo.');
            $('#fitems .alert').show().delay(7000).hide('slow');
          }else{
            $('#gp_codigo').closest('.form-group').removeClass('has-error');
            $('#b-items').button('loading');
            $('#fitems .alert').hide('fast');
            $('#fitems .progress').show();

            $.ajax({
              type: 'POST',
              cache: false,
              url: 'funciones/class.productos.php',
              data: $('#fitems').serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  $('#fitems .alert').removeClass('alert-danger').addClass('alert-success');
                  $('#fitems')[0].reset();
                  loadItems();
                }else{
                  $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
                }

                $('#fitems .alert #msj').text(r.msj);
              },
              error: function(){
                $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
                $('#fitems .alert #msj').text('Ha ocurrido un error.');
              },
              complete: function(){
                $('#table-items tr').removeClass();
                $('#b-items').button('reset');
                $('#fitems .progress').hide();
                $('#fitems .alert').show().delay(7000).hide('slow');
              }
            });
          }
        });//Guardar/Editar Item-------------------------------------------------------------------------

        //Eliminar Item
        $('#b-del-item').click(function(e){
          e.preventDefault();
          $('#b-del-item').button('loading');
          $('#fdelete .alert').hide('fast');
          $('#fdelete .progress').show();

          $.ajax({
            type: 'POST',
            cache: false,
            url: 'funciones/class.productos.php',
            data: $('#fdelete').serialize(),
            dataType: 'json',
            success: function(r){
              if(r.response){
                $('#fdelete .alert').removeClass('alert-danger').addClass('alert-success');
                $('#fdelete')[0].reset();
                loadItems();
              }else{
                $('#fdelete .alert').removeClass('alert-success').addClass('alert-danger');
              }

              $('#fdelete .alert #msj').text(r.msj);
            },
            error: function(){
              $('#fdelete .alert').removeClass('alert-success').addClass('alert-danger');
              $('#fdelete .alert #msj').text('Ha ocurrido un error.');
            },
            complete: function(){
              $('#b-del-item').button('reset');
              $('#fdelete .progress').hide();
              $('#fdelete .alert').show().delay(4000).hide('slow');
            }
          });
        });//Eliminar---------------------------------------------------------------------------------------

      });//Ready------------------------------------------------------------------------------

      //Guardar cambios en el producto
      function save(e){
        e.preventDefault();
        var form = $('#fproducto');
        var formdata = new FormData(form[0]);
        var btn  = form.find('input[type="submit"]');

        var textArea = $('#descripcion').val().replace(/\s+/g, '');

        if(textArea==""){
          $('#descripcion').closest('.form-group').addClass('has-error');
          $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
          $('#fitems .alert #msj').text('Debe completar este campo.');
          $('#fitems .alert').show().delay(7000).hide('slow');
        }else{
          $('#descripcion').closest('.form-group').removeClass('has-error');
          btn.button('loading');
          $('#fitems .alert').hide('fast');
          $('#fitems .progress').show();

          $.ajax({
            type: 'POST',
            cache: false,
            url: 'funciones/class.productos.php',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(r){
              if(r.response){
                $('#fitems .alert').removeClass('alert-danger').addClass('alert-success');
                $('#backup').text($('#descripcion').val());
                disableForm(r.data);
              }else{
                $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
              }

              $('#fitems .alert #msj').text(r.msj);
            },
            error: function(){
              $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
              $('#fitems .alert #msj').text('Ha ocurrido un error.');
            },
            complete: function(){
              btn.button('reset');
              $('#fitems .progress').hide();
              $('#fitems .alert').show().delay(7000).hide('slow');
            }
          });
        }
      }//Save--------------------------------------------------------------------------------------

      //Cargar items en los campos para editar
      function edit(id){
        $('#table-items tr').removeClass();
        $.ajax({
          type: 'POST',
          cache: false,
          url: 'funciones/class.productos.php',
          data: {action:'get_item',id:id},
          dataType: 'json',
          success: function(r){
            $('#'+id).closest('tr').addClass('success');
            $('#action').val('edit_item');
            if(r.response){
              $.each(r.data,function(i,v){
                $('#'+i).val(v);
              });
            }else{
              $('#action').val('add_item');
              $('.alert').removeClass('alert-success').addClass('alert-danger');
              $('.alert #msj').text('Ah ocurrido un error.');
              $('.alert').show().delay(7000).hide('slow');
            }
          },
          error: function(){
            $('#action').val('add_item');
            $('.alert').removeClass('alert-success').addClass('alert-danger');
            $('.alert #msj').text('Ah ocurrido un error.');
            $('.alert').show().delay(7000).hide('slow');
          }
        })
      }//Editar-------------------------------------------------------------------------------

      //Cargar la tabla
      function loadItems(id){
        var id = $('#gabinete').val();

        $.ajax({
          type: 'POST',
          cache: false,
          url: 'funciones/class.productos.php',
          data: {action:'load',id:id},
          dataType: 'json',
          success: function(r){
            $('#tbody').html(r.data);
          },
          error: function(){
            $('.alert').removeClass('alert-success').addClass('alert-danger');
            $('.alert #msj').text('Ah ocurrido un error al cargar los elementos.');
            $('.alert').show().delay(7000).hide('slow');
          }
        })
      };//Cargar---------------------------------------------------------------------------------
    </script>

  <?
  break;
  case 'add':
  ?>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-columns"></i> Agregar producto</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form id="fproductos" class="" action="funciones/class.productos.php" method="post" enctype="multipart/form-data">
                  <input id="action" type="hidden" name="action" value="add">
                  <div class="form-group">
                    <div class="imageUploadWidget">
                      <div class="imageArea">
                        <img id="img" src="" alt="" prev="">
                        <img class="spinner-image" src="images/spinner.gif">
                      </div>
                      <div class="btnArea">
                        <input id='file' name='foto' accept='image/jpeg,image/png' type='file'>
                      </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="descripcion" class="control-label">Descripcion: *</label>
                    <textarea id="descripcion" class="form-control" name="descripcion" rows="2" required></textarea>
                  </div>

                  <div class="alert alert-dismissible" role="alert" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
                  </div>

                  <div class="progress progress-sm active" style="display:none">
                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                      <span class="sr-only">100% Complete</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <a class="btn btn-default btn-flat" href="?ver=productos"><i class="fa fa-reply" aria-hiden="true"></i> Volver</a>
                    <button id="bproductos" class="btn btn-flat btn-primary" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Registrar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $('#bproductos').click(function(e){
        e.preventDefault();
        var form = $('#fproductos');
        var formdata = new FormData(form[0]);
        var btn  = form.find('input[type="submit"]');

        var textArea = $('#descripcion').val().replace(/\s+/g, '');

        if(textArea==""){
          $('#descripcion').closest('.form-group').addClass('has-error');
          $('#fproductos .alert').removeClass('alert-success').addClass('alert-danger');
          $('#fproductos .alert #msj').text('Debe completar este campo.');
          $('#fproductos .alert').show().delay(7000).hide('slow');
        }else{
          $('#descripcion').closest('.form-group').removeClass('has-error');
          btn.button('loading');
          $('#fproductos .alert').hide('fast');
          $('#fproductos .progress').show();

          $.ajax({
            type: 'POST',
            cache: false,
            url: 'funciones/class.productos.php',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(r){
              if(r.response){
                $('#fproductos .alert').removeClass('alert-danger').addClass('alert-success');
                form[0].reset();
                window.location.replace(r.data);
              }else{
                $('#fproductos .alert').removeClass('alert-success').addClass('alert-danger');
              }
              $('#fproductos .alert #msj').text(r.msj);
            },
            error: function(){
              $('#fproductos .alert').removeClass('alert-success').addClass('alert-danger');
              $('#fproductos .alert #msj').text('Ha ocurrido un error.');
            },
            complete: function(){
              btn.button('reset');
              $('#fproductos .progress').hide();
              $('#fproductos .alert').show().delay(7000).hide('slow');
            }
          });
        }
      })//Save--------------------------------------------------------------------------------------
    </script>
  <?
  break;
  default:
    $gabi = $productos->consulta();
  ?>
    <div class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-columns" aria-hidden="true"></i> Productos registrados</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=productos&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Agregar producto</a>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <?
              $i=0;
              foreach ($gabi as $d) {
                $items = $productos->items($d->id_gabi);
            ?>
              <div class="row prod-box">
                <div class="col-md-2 prod-img">
                  <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->gabi_foto)?>" alt="<?=Base::Img("images/productos/".$d->gabi_foto)?>">
                </div>
                <div class="col-md-10 prod-content">
                  <div class="prod-info-text">
                    <span class="prod-title"><?=$d->gabi_descripcion?></span>
                    <span class="prod-extras">Registrado: <?=$d->gabi_fecha_reg?></span>
                    <span class="prod-extras">Items: <?=count($items)?></span>
                  </div>
                  <div class="prod-opc">
                    <a class="prod-link btn-primary btn-flat" href="?ver=productos&opc=ver&id=<?=$d->id_gabi?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>
            <?
              $i++;
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  <?
  break;
endswitch;
?>
</div>

<script type="text/javascript">

  $(document).ready(function(){

    //Asignar evento al cargar una imagen
    $('#file').change(preview);

  });

  //Preview IMG
  function preview(){
    //Id del input
    var input = this.id;
    //El archivo
    var file  = this.files[0];
    //Tippo de archivo
    var type  = file.type;
    //Contar errores
    var error = 0;
    //Imagen
    var img   = $('#img');
    //Imagen anterior
    var prev  = img.attr("src");
    //Imagen loading
    var load = $(".spinner-image");
    //Guardar imagen anterior
    img.attr('prev',prev);
    //Ocultar imagen
    img.hide();
    //Mostar cargando
    load.show();
    if(file){
      if(file.size<2000000){
        if(type == "image/jpeg" || type == "image/png" || type == "image/jpg"){
          var reader = new FileReader();
          reader.onload = function (e) {
            img.attr('src', e.target.result);
            load.hide();
          img.show('slow');
          }
          reader.readAsDataURL(file);
        }else{ $('#msj').html('Archivo no admitido.'); error++; }
      }else{ $('#msj').html('La imagen supera el tamaño permitido: 2MB.'); error++; }
    }

    if(error>0){
      img.parent().parent().addClass('has-error');
      $('#'+input).val('');
      $('.alert').removeClass('alert-success').addClass("alert-danger");
      $('.alert').show().delay(7000).hide('slow');
      load.hide();
    }else{ img.parent().parent().removeClass('error'); }
  }//Preview-----------------------------------------------------------------------------------
</script>
