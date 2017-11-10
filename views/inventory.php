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
              <form id="delete-user" class="col-md-8 col-md-offset-2" action="funciones/class.inventory.php" method="POST">
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
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
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
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-archive'?>"></i> <?=($id>0)?'Edit':'Add'?> Inventory</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form id="finventory" action="funciones/class.inventory.php" method="post" enctype="multipart/form-data">
                  <input id="action" type="hidden" name="action" value="<?=($id>0)?'edit_inventory':'add_inventory';?>">
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
                    <input id="name" class="form-control" type="text" name="name" value="<?=($item)?htmlspecialchars($item->inv_name):''?>" required>
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
                    <input id="stock" class="form-control" type="number" name="stock" min="0" step="0.1" value="<?=($item)?$item->inv_stock:''?>" style="width:75px" required>
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
  <?
  break;
  default:
  	$items = $inventory->consulta();
  	$itemsByCategory = $inventory->itemsByCategory();
  	$measurement = $inventory->consultaMeasurements();
  ?>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-archive"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Items</span>
            <span class="info-box-number"><?=count($items)?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div>
    </div>
		
		<div class="row">
    	<div class="col-md-3">
    		<div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Categories</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Measurements</a></li>
          </ul>
          <div class="tab-content">
          	<!--=====================|| CATEGORIES ||==========================-->
            <div class="tab-pane active" id="tab_1">
	            <ul id="inventory-category-list" class="list-group list-group-unbordered">

	            	<?foreach($itemsByCategory AS $d){?>
	              <li class="list-group-item">
	               	<b class="list-unit-name"><?=$d->icat_category?></b> (<?=$d->total?>)
									
									<?if($_SESSION['nivel']=="A"){?>
	                <span class="pull-right">
		                <button class="btn-link btn-box-tool" data-id="<?=$d->id_category?>" data-url="category" data-toggle="modal" data-target="#editUnitModal"><i class="fa fa-edit"></i></button>
		                <button class="btn-link btn-box-tool" data-id="<?=$d->id_category?>" data-url="category" data-toggle="modal" data-target="#delModal"><i class="fa fa-times"></i></button>
	              	</span>
	              	<?}?>
	              </li>
	              <?}?>

	            </ul>
		        	<form id="form-add-category" action="funciones/class.inventory_category.php" method="POST">
		        		<input type="hidden" name="action" value="add_category">
		        		<div class="form-group">
	        				<div class="input-group">
		                <input id="add-category-name" name="add-category-name" class="form-control" placeholder="Category" type="text">
		                <div class="input-group-btn">
		                  <button id="save-new-category" type="submit" class="btn btn-primary btn-flat b-submit">Add</button>
		                </div>
		              </div><!-- /btn-group -->
		        		</div>
		        		<div class="alert alert-danger" style="display:none" role="alert">
		        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
		        		</div>
		        	</form>
            </div><!-- /.tab-pane -->

            <!--=====================|| MEASUREMENT ||====================-->
            <div class="tab-pane" id="tab_2">
            	<ul id="inventory-measurement-list" class="list-group list-group-unbordered">

	            	<?foreach($measurement AS $d){?>
	              <li class="list-group-item">
	               	<b class="list-unit-name"><?=$d->mea_unit?></b>
									
									<?if($_SESSION['nivel']=="A"){?>
	                <span class="pull-right">
		                <button class="btn-link btn-box-tool" data-id="<?=$d->id_measurement?>" data-url="measurement" data-toggle="modal" data-target="#editUnitModal"><i class="fa fa-edit"></i></button>
		                <button class="btn-link btn-box-tool" data-id="<?=$d->id_measurement?>" data-url="measurement" data-toggle="modal" data-target="#delModal"><i class="fa fa-times"></i></button>
	              	</span>
	              	<?}?>
	              </li>
	              <?}?>

	            </ul>
		        	<form id="form-add-measurement" action="funciones/class.inventory_measurement.php" method="POST">
		        		<input type="hidden" name="action" value="add_measurement">
		        		<div class="form-group">
	        				<div class="input-group">
		                <input id="add-measurement-name" name="add-measurement-name" class="form-control" placeholder="Measurement" type="text">
		                <div class="input-group-btn">
		                  <button id="save-new-measurement" type="submit" class="btn btn-primary btn-flat b-submit">Add</button>
		                </div>
		              </div><!-- /btn-group -->
		        		</div>
		        		<div class="alert alert-danger" style="display:none" role="alert">
		        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj">An error has ocurred.</span>
		        		</div>
		        	</form>
            </div><!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
    	</div>

    	<div class="col-md-9">
    		<div class="box box-info">
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
		            foreach($items as $d) {
		          ?>
		            <tr>
		              <td class="text-center"><?=$i?></td>
		              <td class="text-center"><?=$d->icat_category?></td>
		              <td class="text-center"><?=$d->inv_name?></td>
		              <td class="text-center"><?=$d->inv_stock." (".$d->mea_unit.")" ?></td>
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

    <div class="row">
    	<div class="col-md-4">
    		<div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Items by category</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="chart-responsive">
                  <div id="inventoryChart" style="height:200px"></div>
                </div>
              </div>
            </div><!-- /.row -->
          </div><!-- /.box-body -->
        </div>
    	</div>
    </div>

    <div id="delModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="form-delete-unit" action="#" method="POST">
            <input id="delete-unit-action" type="hidden" name="action" value="delete">
            <input id="delete-unit-id" type="hidden" name="id" value="<?=$id?>">
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
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <div id="editUnitModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editUnitModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="editUnitModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="form-edit-unit" class="col-md-6 col-md-offset-3" action="#" method="POST">
                <input id="edit-unit-action" type="hidden" name="action" value="">
                <input id="edit-unit-id" type="hidden" name="id" value="<?=$id?>">
									<div class="form-group">
										<label class="control-label" for="edit-unit-name">Name: *</label>
										<input id="edit-unit-name" class="form-control" type="text" name="unit-name" required>
									</div>

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

    <script type="text/javascript">
    	$(document).ready(function(){

    		//Delete Category/Measurement
    		$('#delModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget);
          var id   = button.data('id');
          var xurl = button.data('url');

          var modal = $(this);
          modal.find('#form-delete-unit').attr('action','funciones/class.inventory_'+xurl+'.php');
          modal.find('#delete-unit-action').val('delete_'+xurl);
          modal.find('#delete-unit-id').val(id);
        });

        //Edit Category/Measurement
    		$('#editUnitModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget);
          var id    = button.data('id');
          var xurl  = button.data('url');
          var title = button.parent().parent().find('.list-unit-name').text().trim();

          var modal = $(this);
          modal.find('#editUnitModalLabel').text(title);
          modal.find('#form-edit-unit').attr('action','funciones/class.inventory_'+xurl+'.php');
          modal.find('#edit-unit-action').val('edit_'+xurl);
          modal.find('#edit-unit-id').val(id);
          modal.find('#edit-unit-name').val(title);
        });

        //Chart
    		$('#inventoryChart').highcharts({
		      chart: {
		        type: 'pie'
		      },
		      legend: {
		        align: 'right',
		        verticalAlign: 'middle',
		        layout: 'vertical',
		        labelFormatter: function () {
            return this.name+' ('+this.y+')';
        		}
			    },
		      title: null,
		      tooltip:{pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Qty: {point.y}'},
		      plotOptions: {
		        pie: {
		          allowPointSelect: true,
		          cursor: 'pointer',
		          dataLabels: {
		            enabled: false
		          },
		          showInLegend: true
		        }
		      },
		      series: [{
		      	name:'Couta',
		      	colorByPoint:true,
		      	data: [<?=join($inventory->convertToChart($itemsByCategory),",")?>]
		      }]
		    });//------------------

			});//ready
    </script>
  <?
  break;
endswitch;
?>
</div>