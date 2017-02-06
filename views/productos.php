<?
$productos = new Productos();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Products </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=productos"> Products</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'gabi':
    $gabi  = $productos->obtener_gabi($id);
    $items = $productos->items($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=productos"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Information</button>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal">Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-columns" aria-hidden="true"></i> Cabinet
            <small class="pull-right">Registered: <?=$gabi->gabi_fecha_reg?></small>
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
          <h3>Items added to this product</h3>
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
              <h4 class="modal-title">Delete Item <b id="cod-item"></b></h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this item?</p>

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
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
              <button id="b-del-item" type="submit" class="btn btn-outline">Delete</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div id="delModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdel" action="funciones/class.productos.php">
            <input type="hidden" name="action" value="del_cabi">
            <input id="cabi" type="hidden" name="cabi" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this product?</h4>
              <p class="text-center">All Items added to this product will also be deleted.</p>
              <p class="text-center">This action cannot be undone.</p>

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
              <button id="b-del" type="submit" class="btn btn-outline pull-left b-submit">Delete</button>
              <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
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
        var botones = $("<input id='b-edit-form' class='btn btn-primary btn-flat btn-sm' type='submit' value='Save'/><button id='fcancel' class='btn btn-flat btn-sm btn-default pull-right' type='button'>Cancel</button>");

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
            $('#fitems .alert #msj').text('You must complete all fields.');
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
                  $('#action').val('add_item');$('#id_gp').val("0");
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
                $('#fitems .alert #msj').text('An error has occurred.');
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
                $('#action').val('add_item');
                $('#fdelete')[0].reset();
                loadItems();
              }else{
                $('#fdelete .alert').removeClass('alert-success').addClass('alert-danger');
              }

              $('#fdelete .alert #msj').text(r.msj);
            },
            error: function(){
              $('#fdelete .alert').removeClass('alert-success').addClass('alert-danger');
              $('#fdelete .alert #msj').text('An error has occurred.');
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
          $('#fitems .alert #msj').text('You must complete all fields.');
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
              $('#fitems .alert #msj').text('An error has occurred.');
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
              $('#action').val('add_item');$('#id_gp').val("0");
              $('.alert').removeClass('alert-success').addClass('alert-danger');
              $('.alert #msj').text('Ah ocurrido un error.');
              $('.alert').show().delay(7000).hide('slow');
            }
          },
          error: function(){
            $('#action').val('add_item');$('#id_gp').val("0");
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
  case 'freg':
    $tipo = ($opc=="freg");
    $prod = $productos->obtener_prod($tipo,$id);
    $forma = $productos->shape($prod->freg_forma);
    $fregMaterial = $productos->fregMateriales();
    $fregColor    = $productos->fregColor();    
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=productos"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Information</button>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-tint" aria-hidden="true"></i> Sink
            <small class="pull-right">Registered: <?=$prod->freg_fecha_reg?></small>
          </h2>
        </div>
        <form id="fregEdit" class="form-horizontal" method="post" action="funciones/class.productos.php">
          <input id="action" type="hidden" name="action" value="edit_sink">
          <input type="hidden" name="sink" value="<?=$id?>">
          <div id="areaForm" class="col-md-3">
            <div class="form-group">
              <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$prod->freg_foto)?>" alt="<?=Base::Img("images/productos/".$prod->freg_foto)?>" prev="">
            </div>
          </div>
          <div class="col-md-4">
            <div id="infoArea">
              <h3 id="name"><?=$prod->freg_nombre?></h3>
              <p><b>Shape:</b> <span id="shape"><?=$forma?></span></p>
              <p><b>Material:</b> <span id="material"><?=$prod->fm_nombre?></span></p>
              <p><b>Color:</b> <span id="color"><?=$prod->fc_nombre?></span></p>
              <p><b>Price:</b> $ <span id="price"><?=$prod->freg_costo?></span></p>
            </div>
            <div id="inputArea" style="display:none">
              <div class="form-group">
                <label class="col-md-4 control-label" for="freg_name">Name: *</label>
                <div class="col-md-8">
                  <input id="freg_name" class="form-control" type="text" name="freg_name" value="<?=$prod->freg_nombre?>" required/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label" for="freg_shape">Shape: *</label>
                <div class="col-md-6">
                  <select id="freg_shape" class="form-control" name="freg_shape" required>
                    <option value="1" <?=($prod->freg_forma=="1")?'selected':'';?>>Oval</option>
                    <option value="2" <?=($prod->freg_forma=="2")?'selected':'';?>>Rectangular</option>
                    <option value="3" <?=($prod->freg_forma=="3")?'selected':'';?>>Square</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label" for="freg_material">Material: *</label>
                  <div class="col-md-6">
                  <select id="freg_material" class="form-control" name="freg_material" required>
                    <?
                      foreach ($fregMaterial as $d) {
                        $selected = ($prod->id_fm==$d->id_fm)?'selected':'';
                    ?>
                      <option value="<?=$d->id_fm?>" <?=$selected?>><?=$d->fm_nombre?></option>
                    <?
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label" for="freg_color">Color: *</label>
                <div class="col-md-6">
                  <select id="freg_color" class="form-control" name="freg_color" required>
                    <?
                      foreach ($fregColor as $d){
                        $selected = ($prod->id_fc==$d->id_fc)?'selected':'';
                    ?>
                      <option value="<?=$d->id_fc?>" <?=$selected?>><?=$d->fc_nombre?></option>
                    <?
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label" for="freg_price">Price:*</label>
                <div class="col-md-6">
                  <input id="freg_price" class="form-control" type="text" name="freg_price" value="<?=$prod->freg_costo?>" required/>
                </div>
              </div>
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

            <div id="btn-form-group" class="form-group" style="display:none">
              <button id='b-edit-form' class='btn btn-primary btn-flat btn-sm' type='submit'>Save</button>
              <button id='fcancel' class='btn btn-flat btn-sm btn-default pull-right' type='button'>Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.productos.php">
            <input type="hidden" name="action" value="del_sink">
            <input id="sink" type="hidden" name="sink" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this product?</h4>
              <p class="text-center">This action cannot be undone.</p>

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
              <button id="b-del" type="submit" class="btn btn-outline pull-left b-submit">Delete</button>
              <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#b-activate').click(editForm);
        $('#img,#infoArea').dblclick(editForm);
        $('#fcancel').click(disableForm);

        $('#fregEdit').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var formdata = new FormData(form[0]);
          var btn   = form.find('button[type=submit]');
          var alert = form.find(".alert");
          var bar   = form.find(".progress");

          var fields = form.find('input,select').filter('[required]').length;
          form.find('input,select').filter('[required]').each(function(){
            var regex = $(this).attr('pattern');
            var val   = $(this).val();
            if(val == ""){
              $(this).closest('.form-group').addClass('has-error');
            }
            else{
              if(val.match(regex)){
                $(this).closest('.form-group').removeClass('has-error');
                fields = fields-1;
              }else{
                $(this).closest('.form-group').addClass('has-error');
              }
            }
          });

          if(fields!=0){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('You must complete all required fields.');
            bar.hide();
            btn.button('reset');
            alert.show().delay(7000).hide('slow');
          }else{
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
                alert.removeClass('alert-danger').addClass('alert-success');
                $('#name').text($('#freg_name').val());
                $('#shape').text($('#freg_shape option[value='+$("#freg_shape").val()+']').text());
                $('#material').text($('#freg_material option[value='+$("#freg_material").val()+']').text());
                $('#color').text($('#freg_color option[value='+$("#freg_color").val()+']').text());
                $('#price').text($('#freg_price').val());
                disableForm(r.data);
              }else{
                alert.removeClass('alert-success').addClass('alert-danger');
              }
              alert.find('#msj').text(r.msj);
            },
            error: function(){
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has occurred.');
            },
            complete: function(){
              btn.button('reset');
              bar.hide();
              alert.show().delay(7000).hide('slow');
            }
          });
          }
        });
      });
      function editForm(){
        //Quitar el evento activar formulario
        $('#b-activate,#img,#infoArea').off();
        //Quitar los eventos doble click a la imagen y descripcion

        //Crear los contenedores de la imagen y el input
        var widget  = $('<div class="imageUploadWidget"/>');
        var imgArea = $('<div class="imageArea"/>');
        var spinner = $('<img class="spinner-image" src="images/spinner.gif">');
        var btnArea = $('<div class="btnArea"/>');
        var input   = $("<input id='file' name='foto' accept='image/jpeg,image/png' type='file'>");

        //Tomar la imagen
        var img   = $("#img");

        //Meter la imagen dentro de los contenedores
        //Envolverlo en div.form-group
        $('#img').wrap(widget).wrap(imgArea).after(spinner);
        $('.imageUploadWidget').append(btnArea);
        btnArea.append(input);

        $('#infoArea').hide();
        $('#btn-form-group,#inputArea').show();

        //Asignar evento al cargar una imagen
        $('#file').change(preview);
      }

      //Desactivar formulario
      function disableForm(change){
        change = change?true:false;
        var img = $('#img');
        var alt = img.attr('alt');
        var prev = (img.attr('prev')=="")?alt:img.attr('prev');
        $('.imageUploadWidget').remove();
        $('#areaForm .form-group').append(img);
        if(change){img.attr('src',prev);}
        $('#infoArea').show();
        $('#btn-form-group,#inputArea').hide();
        $('#inputArea .form-group,#areaForm .form-control').removeClass('has-error');
        $('#b-activate').click(editForm);
        $('#img,#infoArea').dblclick(editForm);
      }
    </script>
  <?
  break;
  case 'tope':
    $tipo = ($opc=="freg");
    $prod = $productos->obtener_prod($tipo,$id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=productos"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</button>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-minus" aria-hidden="true"></i> Top
            <small class="pull-right">Registered: <?=$prod->tope_fecha_reg?></small>
          </h2>
        </div>
        <form id="fitems" class="" method="post" action="funciones/class.productos.php">
          <input id="action" type="hidden" name="action" value="edit_tope">
          <input id="id" type="hidden" name="gabinete" value="<?=$id?>">

          <div id="areaForm" class="col-md-3">
            <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$prod->tope_foto)?>" alt="<?=Base::Img("images/productos/".$prod->tope_foto)?>" prev="">
          </div>
          <div class="col-md-6">
            <div id="infoArea">
              <h3><?=$prod->tope_nombre?></h3>
              <p><b>Material:</b> <?=$prod->tm_nombre?></p>
              <p><b>Color:</b> <?=$prod->tc_nombre?></p>
              <p><b>Price:</b> $ <?=$prod->tope_costo?></p>
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
          </div>
        </form>
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.productos.php">
            <input type="hidden" name="action" value="del_top">
            <input id="top" type="hidden" name="top" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this product?</h4>
              <p class="text-center">This action cannot be undone.</p>

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
              <button id="b-del" type="submit" class="btn btn-outline pull-left b-submit">Delete</button>
              <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
    </script>
<?
  break;
  case 'add':
    //Topes
    $topesMaterial = $productos->topesMateriales();
    $topesColor    = $productos->topesColor();
    //Fregaderos
    $fregMaterial = $productos->fregMateriales();
    $fregColor    = $productos->fregColor();
  ?>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-columns"></i> Add product</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form id="fproductos" action="funciones/class.productos.php" method="post" enctype="multipart/form-data">
                  <input id="action" type="hidden" name="action" value="add">
                  <fieldset>
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
                      <label class="control-label" for="tipo">Type:</label>
                      <select id="tipo" class="form-control" type="text" name="tipo" required>
                        <option value="">Select...</option>
                        <option value="1">Cabinets</option>
                        <option value="2">Sinks</option>
                        <option value="3">Tops</option>
                      </select>
                    </div>
                    <hr>
                  </fieldset>

                  <fieldset id="fieldsGabinetes"  style="display:none" disabled>
                    <div class="form-group">
                      <label for="descripcion" class="control-label">Description: *</label>
                      <textarea id="descripcion" class="form-control" name="descripcion" rows="2" required></textarea>
                    </div>
                  </fieldset>
                  <fieldset id="fieldsTopes" style="display:none" disabled>
                    <div class="form-group">
                      <label class="control-label" for="tope_nombre">Name: *</label>
                      <input id="tope_nombre" class="form-control" type="text" name="nombre" required>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="tope_material">Material: *</label>
                      <select id="tope_material" class="form-control" name="material" required>
                        <option value="">Select...</option>
                        <?
                          foreach ($topesMaterial as $d) {
                        ?>
                          <option value="<?=$d->id_tm?>"><?=$d->tm_nombre?></option>
                        <?
                          }
                        ?>
                      </select>
                      <button class="btn btn-flat btn-link" type="button" data-toggle="modal" data-target="#addModal" data-title="Tops - Materials" data-action="add_material" data-table="0" data-consult="tope_list_mat" data-label="Material:">Add material</button>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="tope_color">Color: *</label>
                      <select id="tope_color" class="form-control" name="color" required>
                        <option value="">Select...</option>
                        <?
                          foreach ($topesColor as $d) {
                        ?>
                          <option value="<?=$d->id_tc?>"><?=$d->tc_nombre?></option>
                        <?
                          }
                        ?>
                      </select>
                      <button class="btn btn-flat btn-link" type="button" data-toggle="modal" data-target="#addModal" data-title="Tops - Colors" data-action="add_color" data-table="0" data-consult="tope_list_color" data-label="Color:">Add color</button>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="tope_costo">Price: *</label>
                      <input id="tope_costo" class="form-control" type="number" name="costo" placeholder="0.00" required>
                    </div>
                  </fieldset>

                  <fieldset id="fieldsFregaderos" style="display:none" disabled>
                    <div class="form-group">
                      <label class="control-label" for="freg_nombre">Name: *</label>
                      <input id="freg_nombre" class="form-control" type="text" name="nombre" required>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="freg_forma">Shape: *</label>
                      <select id="freg_forma" class="form-control" name="forma" required>
                        <option value="">Seleccione...</option>
                        <option value="1">Oval</option>
                        <option value="2">Rectangular</option>
                        <option value="3">Square</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="freg_material">Material: *</label>
                      <select id="freg_material" class="form-control" name="material" required>
                        <option value="">Select...</option>
                        <?
                          foreach ($fregMaterial as $d) {
                        ?>
                          <option value="<?=$d->id_fm?>"><?=$d->fm_nombre?></option>
                        <?
                          }
                        ?>
                      </select>
                      <button class="btn btn-flat btn-link" type="button" data-toggle="modal" data-target="#addModal" data-title="Sinks - Materials" data-action="add_material" data-table="1" data-consult="freg_list_mat" data-label="Material:">Add material</button>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="freg_color">Color: *</label>
                      <select id="freg_color" class="form-control" name="color" required>
                        <option value="">Select...</option>
                        <?
                          foreach ($fregColor as $d) {
                        ?>
                          <option value="<?=$d->id_fc?>"><?=$d->fc_nombre?></option>
                        <?
                          }
                        ?>
                      </select>
                      <button class="btn btn-flat btn-link" type="button" data-toggle="modal" data-target="#addModal" data-title="Sinks - Colors" data-action="add_color" data-table="1" data-consult="freg_list_color" data-label="Color:">Add color</button>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="freg_costo">Price: *</label>
                      <input id="freg_costo" class="form-control" type="number" name="costo" placeholder="0.00" required>
                    </div>
                  </fieldset>

                  

                  <div class="alert alert-dismissible" role="alert" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
                  </div><br><br>

                  <div class="progress progress-sm active" style="display:none">
                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                      <span class="sr-only">100% Complete</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <a class="btn btn-default btn-flat" href="?ver=productos"><i class="fa fa-reply" aria-hiden="true"></i> Back</a>
                    <button id="bproductos" class="btn btn-flat btn-primary" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="addModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="faddOpc" class="col-md-8 col-md-offset-2" action="funciones/class.productos.php" method="post">
                <input id="opcAction" type="hidden" name="action" value="">
                <input id="opcTale" type="hidden" name="table">
                <div class="form-group">
                  <ul class="categoria-list">
                  </ul>
                </div>
                <div class="form-group">
                  <label for="opc" class="control-label">Add <span id="opcLabel"></span></label>
                  <input id="opc" class="form-control" type="text" name="opc" />
                </div>
                <div class="form-group">
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="bAddOpc" class="btn btn-flat btn-primary" type="submit" data-loading-text="Cargando..." >Add</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        //Mostrar Materiales-Colores
        $('#addModal').on('show.bs.modal',function(event){
          var btn  = $(event.relatedTarget);
          var title   = btn.data('title');
          var action  = btn.data('action');
          var consult = btn.data('consult');
          var label   = btn.data('label');
          var table   = btn.data('table');
          var modal   = $(this);

          $.ajax({
            type: 'post',
            cache: false,
            url: 'funciones/class.productos.php',
            data: {action:consult},
            dataType: 'json',
            success:function(r){
              if(r.response){
                $('#faddOpc .categoria-list').html('');
                $('#faddOpc .categoria-list').append(r.data);
              }else{
              }
            },
            error: function(){
            },
            complete: function(){

            }
          })

          modal.find('#opcAction').val(action);
          modal.find('#opcTale').val(table);
          modal.find('#opcLabel').text(label);
          modal.find('.modal-title').text(title);
        });
        //========================================================

        $('#bAddOpc').click(function(e){
          e.preventDefault();
          var form = $('#faddOpc');
          var btn   = $(this);
          var alert = form.find('.alert');
          var bar   = form.find('.progress');
          var cat   = $('#opc');

          alert.hide();
          bar.show();
          btn.button('loading');

          if(cat.val()==""){
            cat.closest('.form-group').addClass('has-error');
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('You must complete all required fields.');
            alert.show().delay(7000).hide('slow');
            bar.hide();
            btn.button('reset');
          }else{
            $.ajax({
              type: 'post',
              cache: false,
              url: 'funciones/class.productos.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  alert.removeClass('alert-danger').addClass('alert-success');
                  cat.val('');
                  $('#'+r.data.select).empty();
                  $('#'+r.data.select).append(r.data.options);
                  setTimeout(function(){
                    $('#addModal').modal('hide');
                  },2000);
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('An error has occurred.');
              },
              complete: function(){
                bar.hide();
                alert.show().delay(7000).hide('slow');
                btn.button('reset');
              }
            })
          }
        });

        $('#tipo').change(function(){
          var type = $(this).val();
          console.log(type);
          switch(type){
            case "1":
              $('#fieldsFregaderos,#fieldsTopes').prop('disabled',true).hide();
              $('#fieldsGabinetes').prop('disabled',false).show('slow');
            break;
            case "2":
              $('#fieldsGabinetes,#fieldsTopes').prop('disabled',true).hide();
              $('#fieldsFregaderos').prop('disabled',false).show('slow');
            break;
            case "3":
              $('#fieldsFregaderos,#fieldsGabinetes').prop('disabled',true).hide();
              $('#fieldsTopes').prop('disabled',false).show('slow');
            break;
            default:
              $('#fieldsTopes,#fieldsFregaderos,#fieldsGabinetes').prop('disabled',true).hide();
            break;
          }
        });

      //==============================|| Registrar producto ||===============================================
        $('#fproductos').submit(function(e){
          e.preventDefault();
          var form = $('#fproductos');
          var formdata = new FormData(form[0]);
          var btn   = form.find('button[type="submit"]');
          var alert = form.find('.alert');
          var bar   = form.find('.progress');

          alert.hide();
          bar.show();

          var textArea = $('#descripcion').val().replace(/\s+/g, '');

          var fields = form.find('fieldset:not([disabled]) input,fieldset:not([disabled]) select,fieldset:not([disabled]) textarea').filter('[required]').length;
          form.find('fieldset:not([disabled]) input,fieldset:not([disabled]) select,fieldset:not([disabled]) textarea').filter('[required]').each(function(){
            var regex = $(this).attr('pattern');
            var val   = $(this).val();
            if(val == ""){
              $(this).closest('.form-group').addClass('has-error');
            }
            else{
              if(val.match(regex)){
                $(this).closest('.form-group').removeClass('has-error');
                fields = fields-1;
              }else{
                $(this).closest('.form-group').addClass('has-error');
              }
            }
          });

          if(fields!=0){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('You must complete all required fields.');
            btn.button('reset');
            alert.show().delay(7000).hide('slow');
            bar.hide();
          }else{
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
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('An error has occurred.');
              },
              complete: function(){
                btn.button('reset');
                bar.hide();
                alert.show().delay(7000).hide('slow');
              }
            });
          }
        })//Registrar producto=============================================================================
      });
    </script>
  <?
  break;
  default:
    $gabi = $productos->consulta_gabinetes();
    $freg = $productos->consulta_fregaderos();
    $tope = $productos->consulta_topes();
  ?>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <a class="product-link" href="#gabinetes" style="color:#000">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-columns"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Cabinets</span>
              <span class="info-box-number"><?=count($gabi)?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </a>
      </div>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <a class="product-link" href="#fregaderos" style="color:#000">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-tint"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sinks</span>
              <span class="info-box-number"><?=count($freg)?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </a>
      </div>
      <!-- /.col -->

      <div class="col-md-3 col-sm-6 col-xs-12">
        <a class="product-link" href="#topes" style="color:#000">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-minus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Tops</span>
              <span class="info-box-number"><?=count($tope)?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </a>
      </div>
      <!-- /.col -->  
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="pull-left">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=productos&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Add product</a>
        </div>
      </div><br><br>
    </div>

    <div id="gabinetes" class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-columns" aria-hidden="true"></i> Cabinets</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <?
            if(count($gabi)>0){
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
                    <span class="prod-extras">Registered: <?=$d->gabi_fecha_reg?></span>
                    <span class="prod-extras">Items: <?=count($items)?></span>
                  </div>
                  <div class="prod-opc">
                    <a class="prod-link btn-primary btn-flat" href="?ver=productos&opc=gabi&id=<?=$d->id_gabi?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>
            <?
              $i++;
              }
            }else{
            ?>
              <p class="text-center">There are no Cabinets to show.</p>
            <?
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    <div id="fregaderos" class="box box-danger color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tint" aria-hidden="true"></i> Sinks</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <?
            if(count($freg)>0){
              $i=0;
              foreach ($freg as $d) {
            ?>
              <div class="row prod-box">
                <div class="col-md-2 prod-img">
                  <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->freg_foto)?>" alt="<?=Base::Img("images/productos/".$d->freg_foto)?>">
                </div>
                <div class="col-md-10 prod-content">
                  <div class="prod-info-text">
                    <span class="prod-title"><?=$d->freg_nombre?></span>
                    <span class="prod-extras">Registered: <?=$d->freg_fecha_reg?></span>
                  </div>
                  <div class="prod-opc">
                    <a class="prod-link btn-primary btn-flat" href="?ver=productos&opc=freg&id=<?=$d->id_fregadero?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>
            <?
              $i++;
              }
            }else{
            ?>
              <p class="text-center">There are no Sinks to show.</p>
            <?
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    <div id="topes" class="box box-primary color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-minus" aria-hidden="true"></i> Tops</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-10 col-md-offset-1">
            <?
            if(count($tope)>0){
              $i=0;
              foreach ($tope as $d) {
            ?>
              <div class="row prod-box">
                <div class="col-md-2 prod-img">
                  <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->tope_foto)?>" alt="<?=Base::Img("images/productos/".$d->tope_foto)?>">
                </div>
                <div class="col-md-10 prod-content">
                  <div class="prod-info-text">
                    <span class="prod-title"><?=$d->tope_nombre?></span>
                    <span class="prod-extras">Registered: <?=$d->tope_fecha_reg?></span>
                  </div>
                  <div class="prod-opc">
                    <a class="prod-link btn-primary btn-flat" href="?ver=productos&opc=tope&id=<?=$d->id_tope?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>
            <?
              $i++;
              }
            }else{
            ?>
              <p class="text-center">There are no Tops to show.</p>
            <?
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!--Scroll top link-->
    <p class="scrolltop"><a href="#main-body"> <i class="fa fa-chevron-up"></i></a></p>

    <script type="text/javascript">
      $(document).ready(function(){
        var $root = $('html, body');

        $(window).scroll(function(){
          if($(this).scrollTop() > 250) {
            $('.scrolltop a').fadeIn(300);
          }else{
            $('.scrolltop a').fadeOut(300);
          }
        });
        $('a.product-link,.scrolltop a').click(function() {
          $root.animate({
            scrollTop: $( $.attr(this, 'href') ).offset().top
          }, 500);
          return false;
        });
      });
    </script>
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
