<?
$clients = new Clients();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Profile";}else{$li="";}
?>

<section class="content-header">
  <h1> Clients </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=clients"> Clients</a></li>
    <?if($li!=""){echo "<li class=\"active\">".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    $orders = new Orders();
    $client = $clients->obtener($id);
    $order  = $orders->obtenerByClient($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=clients"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <a class="btn btn-flat btn-success" href="?ver=clients&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</a>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-address-book-o" aria-hidden="true"></i>
            <?=$client->client_name?>
            <small class="pull-right">Registered: <?=Base::ConvertTS2($client->client_fecha_reg)?></small>
          </h2>
        </div>
        <div class="col-md-4">
          <h4>Client details</h4>
          <p><b>#<?=$client->client_number?></b></p>
          <p><b>Name:</b> <?=$client->client_name?></p>
          <p><b>Phone:</b> <?=$client->client_phone?></p>
          <p><b>Email:</b> <?=$client->client_email?></p>
          <p><b>Address:</b> <?=$client->client_address?></p>
        </div>
        <div class="col-md-4">
          <h4>Contact information</h4>
          <p><b>Contact: </b><?=($client->client_contact)?$client->client_contact:'N/A';?></p>
        </div>
      </div>
    </section>

    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Orders</h3>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Project</th>
              <th class="text-center">Status</th>
              <th class="text-center">Address</th>
              <th class="text-center">Products</th>
              <th class="text-center">Total</th>
              <th class="text-center">Date</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;
            foreach($order as $d) {
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
    </div>

    <script type="text/javascript">
      $(document).ready(function(){
        $('.btn-print').click(function(){
          window.location.href = $(this).attr('xhref');
        });
      });
    </script>


  <?
  break;
  case 'add':
  case 'edit':
    $client = $clients->obtener($id);
    if($client==NULL){ $id = 0; $action = "add"; }else{ ($_SESSION['nivel']=="A")?$action="edit_admin" : $action="edit"; }
  ?>
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-user-plus'?>"></i> <?=($id>0)?'Edit':'Add'?> client</h3><br>
          </div>
          <div class="box-body">
            <form id="client-form" class="col-md-8 col-md-offset-2" action="funciones/class.clients.php">
              <input type="hidden" name="action" value="<?=($id>0)?'edit_client':'add_client';?>">
              <input type="hidden" name="id" value="<?=$id?>">
              
              <div class="form-group">
                <label class="control-label" for="client_name">Name: *</label>
                <input id="client_name" class="form-control" type="text" name="client_name" value="<?=($client)?$client->client_name:''?>" required/>
              </div>
              <div class="form-group">
                <label class="control-label" for="client_address">Address: *</label>
                <input id="client_address" class="form-control" type="text" name="client_address" value="<?=($client)?$client->client_address:''?>" required/>
              </div>
              <div class="form-group">
                <label class="control-label" for="client_email">Email: *</label>
                <input id="client_email" class="form-control" type="text" name="client_email" value="<?=($client)?$client->client_email:''?>" required/>
              </div>
              <div class="form-group">
                <label class="control-label" for="client_phone">Telephone: *</label>
                <input id="client_phone" class="form-control" type="text" name="client_phone" value="<?=($client)?$client->client_phone:''?>" required/>
              </div>
              <div class="form-group">
                <label class="control-label" for="client_contact">Contact: </label>
                <input id="client_contact" class="form-control" type="text" name="client_contact" value="<?=($client)?$client->client_contact:''?>"/>
              </div>

              <div class="form-group">
                <div class="progress" style="display:none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"> </div>
                </div>
              </div>
              <div class="alert alert-danger" role="alert" style="display:none">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
                <span id="msj"></span>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-danger btn-flat b-submit">Save &nbsp;<i class="fa fa-send" aria-hidden="true"></i></button>
              </div>    
            </form>
          </div>
        </div>
      </div>
    </div>
  <?
  break;
  default:
    $client = $clients->consulta();
  ?>
    <div class="row">
      <div class="col-md-3">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-address-book-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Clients</span>
            <span class="info-box-number"><?=count($client)?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div>
    </div>

    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-address-book-o"></i> Clients</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=clients&opc=add"><i class="fa fa-user-plus" aria-hidden="true"></i> Add client</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Number</th>
              <th class="text-center">Name</th>
              <th class="text-center">Phone</th>
              <th class="text-center">Email</th>
              <th class="text-center">Address</th>
              <th class="text-center">Accion</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;
            foreach ($client as $d) {
          ?>
            <tr>
              <td class="text-center"><?=$i?></td>
              <td><?=$d->client_number?></td>
              <td><?=$d->client_name?></td>
              <td><?=$d->client_phone?></td>
              <td><?=$d->client_email?></td>
              <td><?=$d->client_address?></td>
              <td class="text-center">
                <a class="btn btn-flat btn-primary btn-sm" href="?ver=clients&opc=ver&id=<?=$d->id_client?>"><i class="fa fa-search"></i></a>
                <a class="btn btn-flat btn-success btn-sm" href="?ver=clients&opc=edit&id=<?=$d->id_client?>"><i class="fa fa-pencil"></i></a>
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
