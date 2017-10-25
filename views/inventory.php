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
  case 'add':
    $categorys = $inventory->selectCategorys();
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
                <form id="finventory" action="funciones/class.inventory.php" method="post" enctype="multipart/form-data">
                  <input id="action" type="hidden" name="action" value="add">
                  <div class="form-group">
                    <label class="control-label" for="category">Category:</label>
                    <select id="category" class="form-control" type="text" name="category" required>
                      <option value="">Select...</option>
                      <?foreach($categorys as $d){?>
												<option value="<?=$d->id_inv_cat?>"><?=$d->icat_category?></option>
                      <?}	?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="name">Name: *</label>
                    <input id="name" class="form-control" type="text" name="name" required>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="stock">Stock: *</label>
                    <input id="stock" class="form-control" type="number" name="stock" required>
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
                    <button id="binventory" class="btn btn-flat btn-primary" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Save</button>
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
    		<div class="box box-danger">
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
		              <th class="text-center">Date</th>
		              <th class="text-center">Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          <? $i = 1;
		            foreach($inventory as $d) {
		              switch ($d->order_status){
		                case 'Started':$status = "<span class=\"label label-primary\">Started</span>";break;
		                case 'Completed':$status = "<span class=\"label label-success\">Completed</span>";break;
		                case 'Standby':$status = "<span class=\"label label-warning\">Standby</span>";break;
		                case 'Canceled':$status = "<span class=\"label label-danger\">Canceled</span>";break;
		              }
		          ?>
		            <tr>
		              <td class="text-center"><?=$d->order_order?></td>
		              <td><?=$d->order_project?></td>
		              <td class="text-center"><?=$status?></td>
		              <td><?=($d->order_address)?$d->order_address:'N/A';?></td>
		              <td class="text-center"><?=$d->products?></td>
		              <td class="text-right">$<?=Base::Format($d->order_total,2,".",",")?></td>
		              <td class="text-center"><?=Base::removeTS($d->order_fecha_reg)?></td>
		              <td class="text-center">
		                <a class="btn btn-flat btn-primary btn-sm" href="?ver=orders&opc=ver&id=<?=$d->id_order?>"><i class="fa fa-search"></i></a>
		                <button class="btn btn-sm btn-flat btn-danger btn-print" xhref="reportes/orders.php?action=order&order=<?=$d->id_order?>" type="button"><i class="fa fa-print"></i></button>
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
