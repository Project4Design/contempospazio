<?
$products = new Products();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Products </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=products"> Products</a></li>
    <?if($li!=""){echo "<li class=\"active\">".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'cabi':
    $gabi  = $products->obtener_gabi($id);
    $items = $products->items($id);
    
    $configuration = new Configuracion();
    $labor         = $configuration->get_labor();
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=products"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
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
          <div class="clearfix"></div>
        </div>
        <div id="areaForm" class="col-md-3">
          <span id="backup" class="hide"></span>
          <form id="fproducto" class="" action="funciones/class.products.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="gabinete" value="<?=$id?>">

            <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$gabi->gabi_foto)?>" alt="<?=Base::Img("images/productos/".$gabi->gabi_foto)?>" prev="">

            <span id="descripcion"><?=$gabi->gabi_descripcion?></span>
          </form>
        </div>
        <div class="col-md-9">
          <div class="table-responsive"
            <h3>Items added to this product</h3>
            <form id="fitems" class="" method="post" action="funciones/class.products.php">
              <input id="action" type="hidden" name="action" value="add_item">
              <input id="gabinete" type="hidden" name="gabinete" value="<?=$id?>">
              <input id="id_gp" type="hidden" name="item" value="0">
              <table id="table-items" class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Labor</th>
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
                        <select id="gp_labor" class="form-control" type="text" name="labor" required>
                          <option value="2">-</option>
                          <option value="0"><?=$labor->config_regular_work?></option>
                          <option value="1"><?=$labor->config_big_work?></option>
                        </select>
                      </div>
                    </td>
                    <td>
                      <div class="form-group" style="margin:0">
                        <input id="gp_codigo" class="form-control" type="text" name="codigo" placeholder="Item" required>
                      </div>
                    </td>
                    <td><input id="gp_gs" class="form-control" type="text" name="gs" placeholder="0.00" maxlength="3"></td>
                    <td><input id="gp_mgc" class="form-control" type="text" name="mgc" placeholder="0.00" maxlength="3"></td>
                    <td><input id="gp_rbs" class="form-control" type="text" name="rbs" placeholder="0.00" maxlength="3"></td>
                    <td><input id="gp_esms" class="form-control" type="text" name="esms" placeholder="0.00" maxlength="3"></td>
                    <td><input id="gp_ws" class="form-control" type="text" name="ws" placeholder="0.00" maxlength="3"></td>
                    <td><input id="gp_miw" class="form-control" type="text" name="miw" placeholder="0.00" maxlength="3"></td>
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
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.products.php">
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
          <form id="fdel" action="funciones/class.products.php">
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
        $('#fproducts input[type!="hidden"],#fproducto .form-group').remove();
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
          //var codigo = $('#gp_codigo').val().replace(/\s+/g, '');
          var form = $('#fitems');

          var fields = form.find('input,select').filter('[required]').length;
          form.find('input,select').filter('[required]').each(function(){
            var val   = $(this).val();
            if(val == ""){
              $(this).closest('.form-group').addClass('has-error');
            }
            else{
              $(this).closest('.form-group').removeClass('has-error');
              fields = fields-1;
            }
          });
          
          if(fields!=0){
            $('#fitems .alert').removeClass('alert-success').addClass('alert-danger');
            $('#fitems .alert #msj').text('You must complete all required fields.');
            $('#fitems .progress').hide();
            $('#b-items').button('reset');
            $('#fitems .alert').show().delay(7000).hide('slow');
          }else{
            $.ajax({
              type: 'POST',
              cache: false,
              url: 'funciones/class.products.php',
              data: form.serialize(),
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
            url: 'funciones/class.products.php',
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
            url: 'funciones/class.products.php',
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
          url: 'funciones/class.products.php',
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
              $('.alert #msj').text('An error has ocurred.');
              $('.alert').show().delay(7000).hide('slow');
            }
          },
          error: function(){
            $('#action').val('add_item');$('#id_gp').val("0");
            $('.alert').removeClass('alert-success').addClass('alert-danger');
            $('.alert #msj').text('An error has ocurred.');
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
          url: 'funciones/class.products.php',
          data: {action:'load',id:id},
          dataType: 'json',
          success: function(r){
            $('#tbody').html(r.data);
          },
          error: function(){
            $('.alert').removeClass('alert-success').addClass('alert-danger');
            $('.alert #msj').text('An error has ocurred trying to load the items.');
            $('.alert').show().delay(7000).hide('slow');
          }
        })
      };//Cargar---------------------------------------------------------------------------------
    </script>

  <?
  break;
  case 'sink':
    $tipo = ($opc=="sink");
    $prod = $products->obtener_prod($tipo,$id);
    $forma = $products->shape($prod->freg_forma);
    $fregMaterial = $products->fregMateriales();
    $fregColor    = $products->fregColor();    
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=products"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Information</button>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times"></i> Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-tint" aria-hidden="true"></i> Sink
            <small class="pull-right">Registered: <?=$prod->freg_fecha_reg?></small>
          </h2>
          <div class="clearfix"></div>
        </div>
        <div class="col-md-12">
          <form id="fregEdit" class="form-horizontal" method="post" action="funciones/class.products.php">
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
                <p><b>Price:</b> $<span id="price"><?=Base::Format($prod->freg_costo,2,".",",")?></span></p>
              </div>
              <div id="inputArea" style="display:none">
                <div class="form-group">
                  <label class="col-md-4 control-label" for="sink_name">Name: *</label>
                  <div class="col-md-8">
                    <input id="sink_name" class="form-control" type="text" name="sink_name" value="<?=$prod->freg_nombre?>" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label" for="sink_shape">Shape: *</label>
                  <div class="col-md-6">
                    <select id="sink_shape" class="form-control" name="sink_shape" required>
                      <option value="1" <?=($prod->freg_forma=="1")?'selected':'';?>>Oval</option>
                      <option value="2" <?=($prod->freg_forma=="2")?'selected':'';?>>Rectangular</option>
                      <option value="3" <?=($prod->freg_forma=="3")?'selected':'';?>>Square</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label" for="sink_material">Material: *</label>
                    <div class="col-md-6">
                    <select id="sink_material" class="form-control" name="sink_material" required>
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
                  <label class="col-md-4 control-label" for="sink_color">Color: *</label>
                  <div class="col-md-6">
                    <select id="sink_color" class="form-control" name="sink_color" required>
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
                  <label class="col-md-4 control-label" for="sink_price">Price:*</label>
                  <div class="col-md-6">
                    <input id="sink_price" class="form-control" type="text" name="sink_price" value="<?=$prod->freg_costo?>" required/>
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
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.products.php">
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
              url: 'funciones/class.products.php',
              data: formdata,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(r){
                if(r.response){
                  alert.removeClass('alert-danger').addClass('alert-success');
                  $('#name').text($('#sink_name').val());
                  $('#shape').text($('#sink_shape option[value='+$("#sink_shape").val()+']').text());
                  $('#material').text($('#sink_material option[value='+$("#sink_material").val()+']').text());
                  $('#color').text($('#sink_color option[value='+$("#sink_color").val()+']').text());
                  $('#price').text($('#sink_price').val());
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
  case 'top':
    $tipo = ($opc=="sink");
    $prod = $products->obtener_prod($tipo,$id);
    //Topes
    $topesMaterial = $products->topesMateriales();
    $topesColor    = $products->topesColor();
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=products"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
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
          <div class="clearfix"></div>
        </div>
        <div class="col-md-12">
          <form id="topEdit" class="form-horizontal" method="post" action="funciones/class.products.php">
            <input id="action" type="hidden" name="action" value="edit_top">
            <input type="hidden" name="top" value="<?=$id?>">
            <div id="areaForm" class="col-md-3">
              <div class="form-group">
                <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$prod->tope_foto)?>" alt="<?=Base::Img("images/productos/".$prod->tope_foto)?>" prev="">
              </div>
            </div>
            <div class="col-md-4">
              <div id="infoArea">
                <h3 id="name"><?=$prod->tope_nombre?></h3>
                <p><b>Material:</b> <span id="material"><?=$prod->tm_nombre?></span></p>
                <p><b>Color:</b> <span id="color"><?=$prod->tc_nombre?></span></p>
                <p><b>Manufacturer:</b> $<span id="manu"><?=Base::Format($prod->tope_manufacture,2,".",",")?></span></p>
                <p><b>Price:</b> $<span id="price"><?=Base::Format($prod->tope_costo,2,".",",")?></span></p>
              </div>
              <div id="inputArea" style="display:none">
                <div class="form-group">
                  <label class="col-md-5 control-label" for="top_name">Name: *</label>
                  <div class="col-md-7">
                    <input id="top_name" class="form-control" type="text" name="top_name" value="<?=$prod->tope_nombre?>" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-5 control-label" for="top_material">Material: *</label>
                  <div class="col-md-7">
                    <select id="top_material" class="form-control" name="top_material" required>
                      <option value="">Select...</option>
                      <?
                        foreach ($topesMaterial as $d) {
                          $selected = ($prod->id_tm==$d->id_tm)?'selected':'';
                      ?>
                        <option value="<?=$d->id_tm?>" <?=$selected?>><?=$d->tm_nombre?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-5 control-label" for="top_color">Color: *</label>
                  <div class="col-md-7">
                    <select id="top_color" class="form-control" name="top_color" required>
                      <option value="">Select...</option>
                      <?
                        foreach ($topesColor as $d) {
                          $selected = ($prod->id_tc==$d->id_tc)?'selected':'';
                      ?>
                        <option value="<?=$d->id_tc?>" <?=$selected?>><?=$d->tc_nombre?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-5 control-label" for="top_manufacture">Manufacture: *</label>
                  <div class="col-md-7">
                    <input id="top_manufacture" class="form-control" type="number" name="top_manufacture" value="<?=$prod->tope_manufacture?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-5 control-label" for="top_price">Price: *</label>
                  <div class="col-md-7">
                    <input id="top_price" class="form-control" type="number" name="top_price" value="<?=$prod->tope_costo?>" required>
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
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.products.php">
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
      $(document).ready(function(){
        $('#b-activate').click(editForm);
        $('#img,#infoArea').dblclick(editForm);
        $('#fcancel').click(disableForm);

        $('#topEdit').submit(function(e){
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
              url: 'funciones/class.products.php',
              data: formdata,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(r){
                if(r.response){
                  alert.removeClass('alert-danger').addClass('alert-success');
                  $('#name').text($('#top_name').val());
                  $('#material').text($('#top_material option[value='+$("#top_material").val()+']').text());
                  $('#color').text($('#top_color option[value='+$("#top_color").val()+']').text());
                  $('#price').text($('#top_price').val());
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
  case 'acce':
    $prod = $products->obtener_accessory($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=products"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <button id="b-activate" class="btn btn-flat btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</button>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-puzzle-piece" aria-hidden="true"></i> Accessory
            <small class="pull-right">Registered: <?=$prod->acce_fecha_reg?></small>
          </h2>
          <div class="clearfix"></div>
        </div>
        <div class="col-md-12">
          <form id="acceEdit" class="form-horizontal" method="post" action="funciones/class.products.php">
            <input id="action" type="hidden" name="action" value="edit_acce">
            <input type="hidden" name="accessory" value="<?=$id?>">
            <div id="areaForm" class="col-md-3">
              <div class="form-group">
                <img id="img" class="img-responsive" src="<?=Base::Img("images/productos/".$prod->acce_foto)?>" alt="<?=Base::Img("images/productos/".$prod->acce_foto)?>" prev="">
              </div>
            </div>
            <div class="col-md-4" style="margin-left: 10px">
              <div id="infoArea">
                <h3 id="name"><?=$prod->acce_name?></h3>
                <p><b>Price:</b> $<span id="price"><?=Base::Format($prod->acce_price,2,".",",")?></span></p>
              </div>
              <div id="inputArea" style="display:none">
                <div class="form-group">
                  <label class="col-md-5 control-label" for="acce_name">Name: *</label>
                  <div class="col-md-7">
                    <input id="acce_name" class="form-control" type="text" name="acce_name" value="<?=$prod->acce_name?>" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-5 control-label" for="acce_price">Price: *</label>
                  <div class="col-md-7">
                    <input id="acce_price" class="form-control" type="number" name="acce_price" value="<?=$prod->acce_price?>" required>
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
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.products.php">
            <input type="hidden" name="action" value="del_acce">
            <input id="accessory" type="hidden" name="accessory" value="<?=$id?>">
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

        $('#acceEdit').submit(function(e){
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
              url: 'funciones/class.products.php',
              data: formdata,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(r){
                if(r.response){
                  alert.removeClass('alert-danger').addClass('alert-success');
                  $('#name').text($('#acce_name').val());
                  $('#price').text($('#acce_price').val());
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
  case 'add':
    //Topes
    $topesMaterial = $products->topesMateriales();
    $topesColor    = $products->topesColor();
    //Fregaderos
    $fregMaterial = $products->fregMateriales();
    $fregColor    = $products->fregColor();
  ?>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-cubes"></i> Add product</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form id="fproducts" action="funciones/class.products.php" method="post" enctype="multipart/form-data">
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
                        <option value="4">Accessories</option>
                      </select>
                    </div>
                    <hr>
                  </fieldset>

                  <fieldset id="fieldsCabinets"  style="display:none" disabled>
                    <div class="form-group">
                      <label for="descripcion" class="control-label">Description: *</label>
                      <textarea id="descripcion" class="form-control" name="descripcion" rows="2" required></textarea>
                    </div>
                  </fieldset>

                  <fieldset id="fieldsTops" style="display:none" disabled>
                    <div class="form-group">
                      <label class="control-label" for="tope_name">Name: *</label>
                      <input id="tope_name" class="form-control" type="text" name="name" required>
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
                      <label class="control-label" for="tope_manufacture">Manufacture: *</label>
                      <input id="tope_manufacture" class="form-control" type="number" name="manufacture" placeholder="0.00" required>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="tope_price">Price: *</label>
                      <input id="tope_price" class="form-control" type="number" name="price" placeholder="0.00" required>
                    </div>
                  </fieldset>

                  <fieldset id="fieldsSinks" style="display:none" disabled>
                    <div class="form-group">
                      <label class="control-label" for="freg_name">Name: *</label>
                      <input id="freg_name" class="form-control" type="text" name="name" required>
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
                      <input id="freg_costo" class="form-control" type="number" name="price" placeholder="0.00" required>
                    </div>
                  </fieldset>

                  <fieldset id="fieldsAccessories" style="display:none" disabled>
                    <div class="form-group">
                      <label class="control-label" for="acc_name">Name: *</label>
                      <input id="acc_name" class="form-control" type="text" name="name" required>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="acc_costo">Price: *</label>
                      <input id="prod_costo" class="form-control" type="number" name="price" placeholder="0.00" required>
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
                    <a class="btn btn-default btn-flat" href="?ver=products"><i class="fa fa-reply" aria-hiden="true"></i> Back</a>
                    <button id="bproducts" class="btn btn-flat btn-primary" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Save</button>
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
              <form id="faddOpc" class="col-md-8 col-md-offset-2" action="funciones/class.products.php" method="post">
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
            url: 'funciones/class.products.php',
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
              url: 'funciones/class.products.php',
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
              $('#fieldsSinks,#fieldsTops,#fieldsAccessories').prop('disabled',true).hide();
              $('#fieldsCabinets').prop('disabled',false).show('slow');
            break;
            case "2":
              $('#fieldsCabinets,#fieldsTops,#fieldsAccessories').prop('disabled',true).hide();
              $('#fieldsSinks').prop('disabled',false).show('slow');
            break;
            case "3":
              $('#fieldsSinks,#fieldsCabinets,#fieldsAccessories').prop('disabled',true).hide();
              $('#fieldsTops').prop('disabled',false).show('slow');
            break;
            case "4":
              $('#fieldsSinks,#fieldsCabinets,#fieldsTops').prop('disabled',true).hide();
              $('#fieldsAccessories').prop('disabled',false).show('slow');
            break;
            default:
              $('#fieldsTops,#fieldsSinks,#fieldsCabinets,#fieldsAccessories').prop('disabled',true).hide();
            break;
          }
        });

      //==============================|| Registrar producto ||===============================================
        $('#fproducts').submit(function(e){
          e.preventDefault();
          var form = $('#fproducts');
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
              url: 'funciones/class.products.php',
              data: formdata,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(r){
                if(r.response){
                  $('#fproducts .alert').removeClass('alert-danger').addClass('alert-success');
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
    $gabi  = $products->consulta_gabinetes();
    $sinks = $products->consulta_fregaderos();
    $tops  = $products->consulta_topes();
    $accessories = $products->consulta_accessories();
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
              <span class="info-box-number"><?=count($sinks)?></span>
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
              <span class="info-box-number"><?=count($tops)?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </a>
      </div>
      <!-- /.col -->  

      <div class="col-md-3 col-sm-6 col-xs-12">
        <a class="product-link" href="#accessories" style="color:#000">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-puzzle-piece"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Accessories</span>
              <span class="info-box-number"><?=count($accessories)?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </a>
      </div>
      <!-- /.col -->  
    </div>


    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#products" data-toggle="tab" aria-expanded="true">Products</a></li>
        <li class=""><a href="#colors" data-toggle="tab" aria-expanded="false">Colors</a></li>
        <li class=""><a href="#materials" data-toggle="tab" aria-expanded="false">Materials</a></li>
        <li class="pull-right"><a href="?ver=products&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Add product</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="products">
          <!--==================================================================================================================
          =========================================|| PRODUCTS || ==============================================================
          ====================================================================================================================-->
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
                      $items = $products->items($d->id_gabi);
                  ?>
                    <div class="row prod-box">
                      <a href="?ver=products&opc=cabi&id=<?=$d->id_gabi?>">
                        <div class="col-md-2 col-sm-3 col-xs-3 prod-img">
                          <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->gabi_foto)?>" alt="<?=Base::Img("images/productos/".$d->gabi_foto)?>">
                        </div>
                      </a>
                      <div class="col-md-10 col-sm-9 col-xs-9 prod-content">
                        <div class="prod-info-text">
                          <span class="prod-title"><?=$d->gabi_descripcion?></span>
                          <span class="prod-extras">Registered: <?=Base::removeTS($d->gabi_fecha_reg)?></span>
                          <span class="prod-extras">Items: <?=count($items)?></span>
                        </div>
                        <div class="prod-opc">
                          <a class="prod-link btn-primary btn-flat" href="?ver=products&opc=cabi&id=<?=$d->id_gabi?>"><i class="fa fa-search" aria-hidden="true"></i></a>
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
                  if(count($sinks)>0){
                    $i=0;
                    foreach ($sinks as $d) {
                  ?>
                    <div class="row prod-box">
                      <a href="?ver=products&opc=sink&id=<?=$d->id_fregadero?>">
                      <div class="col-md-2 col-sm-3 col-xs-3 prod-img">
                        <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->freg_foto)?>" alt="<?=Base::Img("images/productos/".$d->freg_foto)?>">
                      </div>
                      </a>
                      <div class="col-md-10 col-sm-9 col-xs-9 prod-content">
                        <div class="prod-info-text">
                          <span class="prod-title"><?=$d->freg_nombre?></span>
                          <span class="prod-extras">
                            Price: $<?=Base::Format($d->freg_costo,2,".",",")?><br>
                            Registered: <?=Base::removeTS($d->freg_fecha_reg)?>
                          </span>
                        </div>
                        <div class="prod-opc">
                          <a class="prod-link btn-primary btn-flat" href="?ver=products&opc=sink&id=<?=$d->id_fregadero?>"><i class="fa fa-search" aria-hidden="true"></i></a>
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
                  if(count($tops)>0){
                    $i=0;
                    foreach ($tops as $d) {
                  ?>
                    <div class="row prod-box">
                      <a href="?ver=products&opc=top&id=<?=$d->id_tope?>">
                        <div class="col-md-2 col-sm-3 col-xs-3 prod-img">
                          <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->tope_foto)?>" alt="<?=Base::Img("images/productos/".$d->tope_foto)?>">
                        
                        </div>
                      </a>
                      <div class="col-md-10 col-sm-9 col-xs-9 prod-content">
                        <div class="prod-info-text">
                          <span class="prod-title"><?=$d->tope_nombre?></span>
                          <span class="prod-extras">
                            Price: $<?=Base::Format($d->tope_costo,2,".",",")?><br>
                            Registered: <?=Base::removeTS($d->tope_fecha_reg)?>
                          </span>
                        </div>
                        <div class="prod-opc">
                          <a class="prod-link btn-primary btn-flat" href="?ver=products&opc=top&id=<?=$d->id_tope?>"><i class="fa fa-search" aria-hidden="true"></i></a>
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

          <div id="accessories" class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Accesories</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <?
                  if(count($accessories)>0){
                    $i=0;
                    foreach ($accessories as $d) {
                  ?>
                    <div class="row prod-box">
                      <a href="?ver=products&opc=acce&id=<?=$d->id_tope?>">
                        <div class="col-md-2 col-sm-3 col-xs-3 prod-img">
                          <img class="img-responsive" src="<?=Base::Img("images/productos/".$d->acce_foto)?>" alt="<?=Base::Img("images/productos/".$d->acce_foto)?>">
                        </div>
                      </a>
                      <div class="col-md-10 col-sm-9 col-xs-9 prod-content">
                        <div class="prod-info-text">
                          <span class="prod-title"><?=$d->acce_name?></span>
                          <span class="prod-extras">
                            Price: $<?=Base::Format($d->acce_price,2,".",",")?><br>
                            Registered: <?=Base::removeTS($d->acce_fecha_reg)?>
                          </span>
                        </div>
                        <div class="prod-opc">
                          <a class="prod-link btn-primary btn-flat" href="?ver=products&opc=acce&id=<?=$d->id_accessory?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                        </div>
                      </div>
                    </div>
                  <?
                    $i++;
                    }
                  }else{
                  ?>
                    <p class="text-center">There are no Accessories to show.</p>
                  <?
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <!--==================================================================================================================
          =========================================|| PRODUCTS || ==============================================================
          ====================================================================================================================-->
        </div>

        <!-- /.tab-pane -->
        <div class="tab-pane" id="colors">
          <!--==================================================================================================================
          =========================================|| COLORS || ================================================================
          ====================================================================================================================-->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-tint" aria-hidden="true"></i> Sink colors</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div id="sinksArea" class="row">
                <div class="col-md-10 col-md-offset-1">
                  <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <div class="col-md-4 col-sm-12">
                  <form id="addSinkColor"  action="#" method="post">
                    <input id="sinkColorAtion" type="hidden" name="action" value="add_color">
                    <input id="sinkColor" type="hidden" name="id" value="0">
                    <input type="hidden" name="table" value="1">
                    <input type="hidden" name="load" value="false">
                    <div class="form-group">
                      <label class="control-label">Color: *</label>
                      <input id="sinkColorVal" type="text" class="form-control" name="opc" required>
                    </div>
                    <div class="form-group">
                      <div class="progress" style="display:none">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-sm btn-success btn-flat" type="submit">Save <i class="fa fa-send" aria-hidden="true"></i></button>
                    </div>
                  </form>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                  <table id="tSinkColors" class="table table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Color</th>
                        <th>Products</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tbSinkColors">
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-minus" aria-hidden="true"></i> Top colors</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div id="topsArea" class="row">
                <div class="col-md-10 col-md-offset-1">
                  <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <div class="col-md-4">
                  <form id="addTopColor"  action="#" method="post">
                    <input id="topColorAtion" type="hidden" name="action" value="add_color">
                    <input id="topColor" type="hidden" name="id" value="0">
                    <input type="hidden" name="table" value="0">
                    <input type="hidden" name="load" value="false">
                    <div class="form-group">
                      <label class="control-label">Color: *</label>
                      <input id="topColorVal" type="text" class="form-control" name="opc" required>
                    </div>
                    <div class="form-group">
                      <div class="progress" style="display:none">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-sm btn-success btn-flat" type="submit">Save <i class="fa fa-send" aria-hidden="true"></i></button>
                    </div>
                  </form>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                  <table id="tTopColors" class="table table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Color</th>
                        <th>Products</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tbTopColors">
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->          

          <!--==================================================================================================================
          =========================================|| COLORS || ==============================================================
          ====================================================================================================================-->
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="materials">
          <!--==================================================================================================================
          =========================================|| MATERIALS || =============================================================
          ====================================================================================================================-->

          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-tint" aria-hidden="true"></i> Sink materials</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div id="sinksMaterialArea" class="row">
                <div class="col-md-10 col-md-offset-1">
                  <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <div class="col-md-4">
                  <form id="addSinkMaterial"  action="#" method="post">
                    <input id="sinkMaterialAtion" type="hidden" name="action" value="add_material">
                    <input id="sinkMaterial" type="hidden" name="id" value="0">
                    <input type="hidden" name="table" value="1">
                    <input type="hidden" name="load" value="false">
                    <div class="form-group">
                      <label class="control-label">Material: *</label>
                      <input id="sinkMaterialVal" type="text" class="form-control" name="opc" required>
                    </div>
                    <div class="form-group">
                      <div class="progress" style="display:none">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-sm btn-success btn-flat" type="submit">Save <i class="fa fa-send" aria-hidden="true"></i></button>
                    </div>
                  </form>
                </div>
                <div class="col-md-8">
                  <table id="tSinkMaterials" class="table table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Products</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tbSinkMaterials">
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->

          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-minus" aria-hidden="true"></i> Top materials</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
              <div id="topsMaterialArea" class="row">
                <div class="col-md-10 col-md-offset-1">
                  <div class="alert" role="alert" style="display:none"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <div class="col-md-4">
                  <form id="addTopMaterial"  action="#" method="post">
                    <input id="topMaterialAtion" type="hidden" name="action" value="add_material">
                    <input id="topMaterial" type="hidden" name="id" value="0">
                    <input type="hidden" name="table" value="0">
                    <input type="hidden" name="load" value="false">
                    <div class="form-group">
                      <label class="control-label">Material: *</label>
                      <input id="topMaterialVal" type="text" class="form-control" name="opc" required>
                    </div>
                    <div class="form-group">
                      <div class="progress" style="display:none">
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-sm btn-success btn-flat" type="submit">Save <i class="fa fa-send" aria-hidden="true"></i></button>
                    </div>
                  </form>
                </div>
                <div class="col-md-8">
                  <table id="tTopMaterials" class="table table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Material</th>
                        <th>Products</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="tbTopMaterials">
                    </tbody>
                  </table>
                </div>
              </div>
            </div><!-- /.box-body -->
          </div><!-- /.box -->          

          <!--==================================================================================================================
          =========================================|| MATERIALS || =============================================================
          ====================================================================================================================-->
        </div>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
  
  <!--=====================================|| MODAL DELETE ||============================================================-->
    <div id="delColorModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="delColorModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delColor" action="funciones/class.products.php">
            <input type="hidden" name="action" value="del_color">
            <input id="delColorTable" type="hidden" name="table" value="">
            <input id="delColor" type="hidden" name="id" value="0">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Color</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this color?</h4>
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
              <button id="b-del" type="submit" class="btn btn-outline pull-left">Delete</button>
              <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--=====================================|| MODAL DELETE ||============================================================-->

    <!--=================================|| MODAL DELETE MATERIAL ||============================================================-->
    <div id="delMaterialModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="delMaterialModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delMaterial" action="funciones/class.products.php">
            <input type="hidden" name="action" value="del_material">
            <input id="delMaterialTable" type="hidden" name="table" value="">
            <input id="delMaterial" type="hidden" name="id" value="0">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Material</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this material?</h4>
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
              <button id="b-del" type="submit" class="btn btn-outline pull-left">Delete</button>
              <button type="button" class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--=====================================|| MODAL DELETE ||============================================================-->

    

    <!--Scroll top link-->
    <p class="scrolltop"><a href="#main-body"> <i class="fa fa-chevron-up"></i></a></p>

    <script type="text/javascript">
      $(document).ready(function(){

        //addSinkColor
        $('#addSinkColor').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find("button");
          var alert = $('#sinksArea .alert');
          var bar   = form.find('.progress');
          var color = form.find('input[name="opc"]');

          bar.show();
          btn.button('loading');

          if(color.val().replace(/\s+/g, '')!=""){
            $.ajax({
              type: 'post',
              cache:false,
              url: 'funciones/class.products.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  form[0].reset();
                  $('#sinkColorAtion').val('add_color');
                  $('#sinkColor').val(0);
                  alert.removeClass('alert-danger').addClass('alert-success');
                  loadSinkColors();
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Han error has ocurred.');
              },
              complete: function(){
                alert.show().delay(7000).hide('slow');
                bar.hide();
                btn.button('reset');
              }
            })
          }else{
            alert.find('#msj').text('You must enter a color.');
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.show().delay(7000).hide('slow');
            bar.hide();
            btn.button('reset');
          }
        });//==========================================addSinkColor

        //addTopColor
        $('#addTopColor').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find("button");
          var alert = $('#topsArea .alert');
          var bar   = form.find('.progress');
          var color = form.find('input[name="opc"]');

          bar.show();
          btn.button('loading');

          if(color.val().replace(/\s+/g, '')!=""){
            $.ajax({
              type: 'post',
              cache:false,
              url: 'funciones/class.products.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  form[0].reset();
                  $('#topColorAtion').val('add_color');
                  $('#topColor').val(0);
                  alert.removeClass('alert-danger').addClass('alert-success');
                  loadTopColors();
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Han error has ocurred.');
              },
              complete: function(){
                alert.show().delay(7000).hide('slow');
                bar.hide();
                btn.button('reset');
              }
            })
          }else{
            alert.find('#msj').text('You must enter a color.');
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.show().delay(7000).hide('slow');
            bar.hide();
            btn.button('reset');
          }
        });//==========================================addTopColor

        //addSinkMaterial
        $('#addSinkMaterial').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find("button");
          var alert = $('#sinksMaterialArea .alert');
          var bar   = form.find('.progress');
          var color = form.find('input[name="opc"]');

          bar.show();
          btn.button('loading');

          if(color.val().replace(/\s+/g, '')!=""){
            $.ajax({
              type: 'post',
              cache:false,
              url: 'funciones/class.products.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  form[0].reset();
                  $('#sinkMaterialAtion').val('add_material');
                  $('#sinkMaterial').val(0);
                  alert.removeClass('alert-danger').addClass('alert-success');
                  loadSinkMaterials();
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Han error has ocurred.');
              },
              complete: function(){
                alert.show().delay(7000).hide('slow');
                bar.hide();
                btn.button('reset');
              }
            })
          }else{
            alert.find('#msj').text('You must enter a Material.');
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.show().delay(7000).hide('slow');
            bar.hide();
            btn.button('reset');
          }
        });//==========================================addSinkMaterial

        //addTopMaterial
        $('#addTopMaterial').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find("button");
          var alert = $('#topsMaterialArea .alert');
          var bar   = form.find('.progress');
          var mate  = form.find('input[name="opc"]');

          bar.show();
          btn.button('loading');

          if(mate.val().replace(/\s+/g, '')!=""){
            $.ajax({
              type: 'post',
              cache:false,
              url: 'funciones/class.products.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  form[0].reset();
                  $('#topMaterialAtion').val('add_material');
                  $('#topMaterial').val(0);
                  alert.removeClass('alert-danger').addClass('alert-success');
                  loadTopMaterials();
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Han error has ocurred.');
              },
              complete: function(){
                alert.show().delay(7000).hide('slow');
                bar.hide();
                btn.button('reset');
              }
            })
          }else{
            alert.find('#msj').text('You must enter a material.');
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.show().delay(7000).hide('slow');
            bar.hide();
            btn.button('reset');
          }
        });//==========================================addTopMaterial

        $('#delColorModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var table  = button.data('table');
          var color  = button.data('id');

          var modal = $(this);
          modal.find('#delColorTable').val(table);
          modal.find('#delColor').val(color);
        });

        $('#delMaterialModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget)
          var table  = button.data('table');
          var color  = button.data('id');

          var modal = $(this);
          modal.find('#delMaterialTable').val(table);
          modal.find('#delMaterial').val(color);
        });

        //Eliminar colores
        $('#delColor').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find('button[type="submit"]');
          var alert = form.find('.alert');
          var bar   = form.find('.progress');

          bar.show();
          btn.button('loading');

          $.ajax({
            type: 'post',
            cache:false,
            url: 'funciones/class.products.php',
            data: form.serialize(),
            dataType: 'json',
            success: function(r){
              if(r.response){
                form[0].reset();
                $('#delColor').val(0);
                alert.removeClass('alert-danger').addClass('alert-success');
                if(r.data=="1"){
                  loadSinkColors();
                }else{
                  loadTopColors();
                }
              }else{
                alert.removeClass('alert-success').addClass('alert-danger');
              }
              alert.find('#msj').text(r.msj);
            },
            error: function(){
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('Han error has ocurred.');
            },
            complete: function(){
              alert.show().delay(7000).hide('slow');
              bar.hide();
              btn.button('reset');
            }
          })
        });//===========================================delColor

        //Eliminar materials
        $('#delMaterial').submit(function(e){
          e.preventDefault();
          var form  = $(this);
          var btn   = form.find('button[type="submit"]');
          var alert = form.find('.alert');
          var bar   = form.find('.progress');

          bar.show();
          btn.button('loading');

          $.ajax({
            type: 'post',
            cache:false,
            url: 'funciones/class.products.php',
            data: form.serialize(),
            dataType: 'json',
            success: function(r){
              if(r.response){
                form[0].reset();
                $('#delColor').val(0);
                alert.removeClass('alert-danger').addClass('alert-success');
                if(r.data=="1"){
                  loadSinkMaterials();
                }else{
                  loadTopMaterials();
                }
              }else{
                alert.removeClass('alert-success').addClass('alert-danger');
              }
              alert.find('#msj').text(r.msj);
            },
            error: function(){
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('Han error has ocurred.');
            },
            complete: function(){
              alert.show().delay(7000).hide('slow');
              bar.hide();
              btn.button('reset');
            }
          })
        });//===========================================delColor

        //Scroll top button
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
        });//====================================================================

        //Load colors
        $('#sinksArea').on('click','.editSinkColor',editSinkColor);
        $('#topsArea').on('click','.editTopColor',editTopColor);
        
        loadSinkColors();
        loadTopColors();

        //Load materiales
        $('#sinksMaterialArea').on('click','.editSinkMaterial',editSinkMaterial);
        $('#topsMaterialArea').on('click','.editTopMaterial',editTopMaterial);
        loadSinkMaterials();
        loadTopMaterials();
      });//Ready
  

      //Sink Colores
      function loadSinkColors(){
        var alert = $('#sinksArea .alert');
        $.ajax({
          type: 'post',
          cache: false,
          url: 'funciones/class.products.php',
          data: {action:'sinkColors'},
          dataType: 'json',
          success: function(r){
            if(r){
              $('#tSinkColors').DataTable().destroy();
              $('#tbSinkColors').empty();
              $('#tbSinkColors').append(r.data);
              DTable('#tSinkColors');
            }else{
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has ocurred.');
              alert.show().delay(7000).hide('slow');
            }
          },
          error: function(){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('An error has ocurred.');
            alert.show().delay(7000).hide('slow');
          }
        })
      }//============================================

      //Top Colores
      function loadTopColors(){
        var alert = $('#topsArea .alert');
        $.ajax({
          type: 'post',
          cache: false,
          url: 'funciones/class.products.php',
          data: {action:'topColors'},
          dataType: 'json',
          success: function(r){
            if(r){
              $('#tTopColors').DataTable().destroy();
              $('#tbTopColors').empty();
              $('#tbTopColors').append(r.data);
              DTable('#tTopColors');
            }else{
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has ocurred.');
              alert.show().delay(7000).hide('slow');
            }
          },
          error: function(){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('An error has ocurred.');
            alert.show().delay(7000).hide('slow');
          }
        })
      }//============================================

      //Sink Materials
      function loadSinkMaterials(){
        var alert = $('#sinksMaterialArea .alert');
        $.ajax({
          type: 'post',
          cache: false,
          url: 'funciones/class.products.php',
          data: {action:'sinkMaterials'},
          dataType: 'json',
          success: function(r){
            if(r){
              $('#tSinkMaterials').DataTable().destroy();
              $('#tbSinkMaterials').empty();
              $('#tbSinkMaterials').append(r.data);
              DTable('#tSinkMaterials');
            }else{
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has ocurred.');
              alert.show().delay(7000).hide('slow');
            }
          },
          error: function(){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('An error has ocurred.');
            alert.show().delay(7000).hide('slow');
          }
        })
      }//============================================

      //Top Materilas
      function loadTopMaterials(){
        var alert = $('#topsMaterialArea .alert');
        $.ajax({
          type: 'post',
          cache: false,
          url: 'funciones/class.products.php',
          data: {action:'topMaterials'},
          dataType: 'json',
          success: function(r){
            if(r){
              $('#tTopMaterials').DataTable().destroy();
              $('#tbTopMaterials').empty();
              $('#tbTopMaterials').append(r.data);
              DTable('#tTopMaterials');
            }else{
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has ocurred.');
              alert.show().delay(7000).hide('slow');
            }
          },
          error: function(){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('An error has ocurred.');
            alert.show().delay(7000).hide('slow');
          }
        })
      }//============================================

      function editSinkColor(){
        var id = this.id;
        var color = $.trim($('#sinkColor'+id).text());
        $('#sinkColorAtion').val('edit_color');
        $('#sinkColor').val(this.id);
        $('#sinkColorVal').val(color);
      }

      function editTopColor(){
        var id = this.id;
        var color = $.trim($('#topColor'+id).text());
        $('#topColorAtion').val('edit_color');
        $('#topColor').val(this.id);
        $('#topColorVal').val(color);
      }

      function editSinkMaterial(){
        var id = this.id;
        var mate = $.trim($('#sinkMaterial'+id).text());
        $('#sinkMaterialAtion').val('edit_material');
        $('#sinkMaterial').val(this.id);
        $('#sinkMaterialVal').val(mate);
      }

      function editTopMaterial(){
        var id = this.id;
        var mate = $.trim($('#topMaterial'+id).text());
        $('#topMaterialAtion').val('edit_material');
        $('#topMaterial').val(this.id);
        $('#topMaterialVal').val(mate);
      }

      function DTable(table){
        $(table).DataTable({
        });
      }
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
