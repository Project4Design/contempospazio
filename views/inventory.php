<?
$inventory = new Inventory();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Inventory </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=inventory"> Inventory</a></li>
    <?if($li!=""){echo "<li class=\"active\">".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
	case 'ver':
	$item = $inventory->obtener($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=inventory"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <a class="btn btn-flat btn-success" href="?ver=inventory&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</a>
      <?if($_SESSION['nivel']=="A"){?>
      <button class="btn btn-flat btn-primary" data-toggle="modal" data-target="#stockModal"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Stock</button>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-archive" aria-hidden="true"></i>
            <?=$item->inv_name?>
            <small class="pull-right">Registered: <?=Base::ConvertTS2($item->inv_fecha_reg)?></small>
            <span class="clearfix"></span>
          </h2>
        </div>
        <div class="col-md-4">
          <h4>Item details</h4>
          <p><b>Category:</b> <?=$item->icat_category?></p>
          <p><b>Name:</b> <?=$item->inv_name?></p>
          <p><b>Stock:</b> <?=$item->inv_stock." ({$item->mea_unit})"?></p>
        </div>
      </div>
    </section>

    <div id="stockModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="stockModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="stockModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="delete-user" class="col-md-8 col-md-offset-2" action="funciones/class.inventory.php" method="PSOT">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="stock">

								<center>
									<div class="form-group">
										<div class="btn-group" data-toggle="buttons">
										  <label class="btn btn-default btn-flat active">
										    <input type="radio" name="type" value="1" checked required> <i class="fa fa-plus" aria-hidden="true"></i> Increase Stock
										  </label>
										  <label class="btn btn-default btn-flat">
										    <input type="radio" name="type" value="2" required> <i class="fa fa-retweet" aria-hidden="true"></i> Replace Stock
										  </label>
										</div>
									</div>
								
									<fieldset id="increase">
										<h4 class="text-center">
											<i class="fa fa-plus" aria-hidden="true"></i> Increase Stock
										</h4>
										<p class="help-block"><b>This option will increase the current stock.</b></p>
									</fieldset>

									<fieldset id="replace" style="display:none">
										<h4 class="text-center">
											<i class="fa fa-retweet" aria-hidden="true"></i> Replace Stock
										</h4>
										<p class="text-danger"><b>This option will replace the current stock.</b></p>
									</fieldset>

									<div class="form-group">
										<label class="control-label" for="Stock">Stock: *</label>
										<input id="stock" class="form-control" type="number" name="stock" min="0" style="width:60px" required>
									</div>
								</center>

                <div class="form-group">
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                  <button id="b-eliminar" class="btn btn-flat btn-success b-submit" type="submit"><i class="fa fa-send" aria-hidden="true"></i> Save</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="delModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="funciones/class.inventory.php" method="POST">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Product</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this Item?</h4>
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
              <button id="b-del" type="submit" class="btn btn-flat btn-outline pull-left b-submit">Delete</button>
              <button type="button" class="btn btn-flat btn-outline" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    		$('input[name=type]').change(function(){
    			if(this.value == 1){
    				$('#increase').show();
    				$('#replace').hide();
    			}else{
    				$('#increase').hide();
    				$('#replace').show();
    			}
    		});
    	});
    </script>

  <?
	break;
  case 'add':
  case 'edit':
  	$item = $inventory->obtener($id);
  ?>
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-archive'?>"></i> <?=($id>0)?'Edit':'Add'?> Inventory</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form id="finventory" action="funciones/class.inventory.php" method="post" enctype="multipart/form-data">
                  <input id="action" type="hidden" name="action" value="<?=($id>0)?'edit':'add';?>">
                  <input type="hidden" name="id" value="<?=$id?>">
                  
                  <div class="form-group">
                    <label class="control-label" for="category">Category: *</label>
                    <select id="category" class="form-control" type="text" name="category" required>
                      <option value="">Select...</option>
                      <?foreach($inventory->consultaCategories() as $d){?>
												<option value="<?=$d->id_category?>" <?=($item)?($item->id_category==$d->id_category)?'selected':'':''?>><?=$d->icat_category?></option>
                      <?}?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="name">Name: *</label>
                    <input id="name" class="form-control" type="text" name="name" value="<?=($item)?$item->inv_name:''?>" required>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="measurement">Measurement unit: *</label>
                    <select id="measurement" class="form-control" type="text" name="measurement" required>
                      <option value="">Select...</option>
                      <?foreach($inventory->consultaMeasurements() as $d){?>
												<option value="<?=$d->id_measurement?>" <?=($item)?($item->id_measurement==$d->id_measurement)?'selected':'':''?>><?=$d->mea_unit?></option>
                      <?}?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="stock">Stock: *</label>
                    <input id="stock" class="form-control" type="number" name="stock" min="0" value="<?=($item)?$item->inv_stock:''?>" style="width:75px" required>
                  </div>

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
                    <a class="btn btn-default btn-flat" href="?ver=inventory"><i class="fa fa-reply" aria-hiden="true"></i> Back</a>
                    <button id="binventory" class="btn btn-flat btn-primary b-submit" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Save</button>
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
              <form id="faddOpc" class="col-md-8 col-md-offset-2" action="funciones/class.inventory.php" method="post">
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
            url: 'funciones/class.inventory.php',
            data: {action:consult},
            dataType: 'json',
            success:function(r){
              if(r.response){
                $('#faddOpc .categoria-list').html('');
                $('#faddOpc .categoria-list').append(r.data);
              }else{
                $('#faddOpc .categoria-list').html('');
                $('#faddOpc .categoria-list').html(r.msj);
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
              url: 'funciones/class.inventory.php',
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

      //==============================|| Registrar producto ||===============================================
        $('#finventory').submit(function(e){
          e.preventDefault();
          var form = $('#finventory');
          var btn   = form.find('button[type="submit"]');
          var alert = form.find('.alert');
          var bar   = form.find('.progress');

          alert.hide();
          bar.show();

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
              url: 'funciones/class.inventory.php',
              data: formdata,
              processData: false,
              contentType: false,
              dataType: 'json',
              success: function(r){
                if(r.response){
                  $('#finventory .alert').removeClass('alert-danger').addClass('alert-success');
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
        })//Registrar inventory=============================================================================
      });
    </script>
  <?
  break;
  default:
  ?>
    <div class="row">
    	<div class="col-md-12">
    		<div class="box box-primary">
		      <div class="box-header with-border">
		        <h3 class="box-title"><i class="fa fa-archive"></i> Inventory</h3>
		        <div class="pull-right">
		          <a class="btn btn-flat btn-sm btn-success" href="?ver=inventory&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Add Inventory</a>
		        </div>
		      </div>
		      <div class="box-body">
		        <table class="table data-table table-striped table-bordered table-hover">
		          <thead>
		            <tr>
		              <th class="text-center">#</th>
		              <th class="text-center">Category</th>
		              <th class="text-center">Name</th>
		              <th class="text-center">Stock</th>
		              <th class="text-center">Registered</th>
		              <th class="text-center">Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          <? $i = 1;
		            foreach($inventory->consulta() as $d) {
		          ?>
		            <tr>
		              <td class="text-center"><?=$i?></td>
		              <td class="text-center"><?=$d->icat_category?></td>
		              <td class="text-center"><?=$d->inv_name?></td>
		              <td class="text-center"><?=$d->inv_stock?></td>
		              <td class="text-center"><?=Base::removeTS($d->inv_fecha_reg)?></td>
		              <td class="text-center">
		                <a class="btn btn-flat btn-primary btn-sm" href="?ver=inventory&opc=ver&id=<?=$d->id_inventory?>"><i class="fa fa-search"></i></a>
		              </td>
		            </tr>
		          <?
		            $i++;
		            }
		          ?>        
		          </tbody>
		        </table>
		      </div>
		    </div><!--box-->
    	</div>
    </div><!--row-->
  <?
  break;
endswitch;
?>
</div>
