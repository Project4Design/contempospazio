<?
$orders = new Orders();
if($opc=="add"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Order";}else{$li="";}
?>

<section class="content-header">
  <h1> Orders </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=orders"> Orders</a></li>
    <?if($li!=""){echo "<li clas=\"active\">".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
  	$order = $orders->obtener($id);
  	$prod  = $orders->obtenerProd($id);

    switch ($order->order_status){
      case 'Started':
        $display ="display:none";
        $status = "<span class=\"label label-primary\">Started</span>";
      break;
      case 'Completed':
        $display ="display:none";
        $status = "<span class=\"label label-success\">Completed</span>";
      break;
      case 'Standby':
        $display = "";
        $status = "<span class=\"label label-warning\">Standby</span>";
      break;
      case 'Canceled':
        $display ="";
        $status = "<span class=\"label label-danger\">Canceled</span>";
      break;
    }
  ?>
  	<section>
  		<a class="btn btn-flat btn-default" href="?ver=orders"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
      <span id="botones">
      <?
      if($order->order_status=="Standby"){
      ?>
        <button class="btn btn-flat btn-primary" data-toggle="modal" data-target="#statusModal" data-title="Started"><i class="fa fa-play" aria-hidden="true"></i> Start</button>
      <?}
      if($order->order_status=="Started"){
      ?>
        <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#statusModal" data-title="Standby"><i class="fa fa-pause" aria-hidden="true"></i> Standby</button>
      <?}
      if($order->order_status!="Completed"&&$order->order_status!="Canceled"){
      ?>
        <button class="btn btn-flat btn-success" data-toggle="modal" data-target="#statusModal" data-title="Completed"><i class="fa fa-check" aria-hidden="true"></i> Complete</button>
      <?}
      if($order->order_status!="Completed"&&$order->order_status!="Canceled"){
      ?>
        <button class="btn btn-flat btn-poison" data-toggle="modal" data-target="#statusModal" data-title="Canceled"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
      <?
      }
      ?>
      </span>
      <button class="btn btn-flat btn-danger btn-print" xhref="reportes/orders.php?action=order&order=<?=$id?>"><i class="fa fa-print"></i> Print</button>
  	</section>
    <section class="invoice" style="margin:10px 0 0 0;">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-file-text-o"></i> <?=$order->order_project?>
            <small class="pull-right">Date: <?=Base::ConvertTS2($order->order_fecha_reg)?></small>
            <span class="clearfix"></span>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-md-4 col-sm-12 invoice-col">
          <h4>Client details <small><a href="?ver=clients&opc=ver&id=<?=$order->id_client?>" target="_blank">(See client)</a></small></h4>
          # <?=$order->client_number?><br>
          <address>
            <strong><?=$order->client_name?></strong><br>
            <?=$order->client_address?><br>
            Phone: <?=$order->client_phone?><br>
            Email: <?=$order->client_email?>
          </address>
          
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-12 invoice-col">
          <h4>Contact information</h4>
          Contact: <?=($order->client_contact)?$order->client_contact:'N/A';?><br>
          Address:
          <address>
            <?=($order->order_address)?$order->order_address:'N/A';?><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-12 invoice-col">
          <h4>Order details</h4>
          <b>Order ID:</b> <?=$order->order_order?><br>
          <b>Status: </b> <span id="status"><?=$status?></span><br>
          
          <span id="sreason" style="<?=$display;?>">
          <b>Reason: </b><span id="status-reason"><?=$order->order_status_reason?></span><br>
          </span><br>
          <b>Operational costs:</b> $<?=Base::Format(($order->order_subtotal-$order->order_earnings),2,".",",")?><br>
          <b>Revenues:</b> $<?=Base::Format($order->order_earnings,2,".",",")?> (<span style="color:#009551"><?=round(($order->order_earnings*100)/($order->order_subtotal-$order->order_earnings),2)?>%</span>)
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive"><br>
          <table class="table table-striped">
            <thead>
            <tr>
		    			<th>#</th>
		    			<th>Product</th>
		    			<th>Description</th>
		    			<th>Item #</th>
		    			<th>Price</th>
		    			<th>Discount</th>
		    			<th>Price disc.</th>
		    			<th>Quantity</th>
		    			<th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
          	<?
          		$i=1; $manufacturer = 0; $labor = 0;$c_sub=0;$s_sub=0;$t_sub=0;
          		foreach ($prod as $d){          			
          			
          			switch($d->od_type){
          				case '1':
          					$product="Cabinet";
          					$item = $d->od_item;
          					$discount = $d->od_discount."%";
          					$pdisc = $d->od_price - (($d->od_price*$d->od_discount)/100);
          					$pdisc = "$".Base::Format(ceil($d->od_price - $pdisc),2,".",",");
          					$labor += $d->od_labor*$d->od_qty;
          					$qty = $d->od_qty;
          					$c_sub += $d->od_subtotal;
          				break;
          				case '2':
          					$product="Sink";
          					$item = "-";
          					$discount = "-";
          					$pdisc = "-";
          					$qty = $d->od_qty;
          					$s_sub += $d->od_subtotal;
          				break;
          				case '3':
          					$product="Top";
          					$item = "-";
          					$discount = "-";
          					$pdisc = "-";
          					$qty = $d->od_qty."(ft^2)";
          					$manufacturer += $order->order_manufacturer*$d->od_qty;
          					$t_sub += $d->od_subtotal;
        					break;
          			}
          	?>
	          	<tr>
								<td class="text-center"><?=$i?></td>
			    			<td class="text-center"><?=$product?></td>
			    			<td class="text-center"><?=$d->od_description?></td>
			    			<td class="text-center"><?=$d->od_item?></td>
			    			<td class="text-right">$<?=Base::Format($d->od_price,2,".",",")?></td>
			    			<td class="text-center"><?=$discount?></td>
			    			<td class="text-right"><?=$pdisc?></td>
			    			<td class="text-center"><?=$qty?></td>
			    			<td class="text-right">$<?=Base::Format($d->od_subtotal,2,".",",")?></th>
		    			</tr>
          	<?
          		$i++;
          		}
          	?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- /.col -->
        <div class="col-md-6 col-md-offset-6 col-xs-12">
          <!--<p class="lead">&nbsp;</p>--><br>
					<?
        		$taxes = ceil(($c_sub*$order->order_tax)/100);
        		$delivery = ceil((($c_sub +  $taxes)*$order->order_delivery)/100);
        		$c_e = (($c_sub+$taxes+$delivery+$labor)*$order->order_earnings_cab)/100;
        		$c_total = $c_e + $c_sub + $taxes + $delivery + $labor;

        		$s_e = ceil(($s_sub*$order->order_earnings_sinks)/100);
        		$s_total = $s_sub + $s_e;

        		$t_e = ceil((($t_sub + $manufacturer)*$order->order_earnings_tops)/100);
        		$t_total = $t_e + $t_sub + $manufacturer;
					?>
          <div class="table-responsive">
            <table class="table table-bordered">
            	<thead>
            		<tr>
            			<th colspan="2">Cabinets</th>
            			<th colspan="2">Sinks</th>
            			<th colspan="2">Tops</th>
            		</tr>
            	</thead>
              <tbody>
              	<tr>
                	<th style="text-align:right !important;">Subtotal:</th>
                	<td class="text-right">$<?=Base::Format($c_sub,2,".",",")?></td>
                	<th style="text-align:right !important;">Subtotal:</th>
                	<td class="text-right">$<?=Base::Format($s_sub,2,".",",")?></td>
                	<th style="text-align:right !important;">Subtotal:</th>
                	<td class="text-right">$<?=Base::Format($t_sub,2,".",",")?></td>
              	</tr>
              	<tr>
	                <th style="text-align:right !important">Tax (<?=$order->order_tax?>%):</th>
	                <td class="text-right">$<?=Base::Format($taxes,2,".",",")?></td>
	                <th>&nbsp;</th>
	                <td>&nbsp;</td>
	                <th style="text-align:right !important">Manufacturer:</th>
	                <td class="text-right">$<?=Base::Format($manufacturer,2,".",",")?></td>
	              </tr>
	              <tr>
	                <th style="text-align:right !important">Delivery (<?=$order->order_delivery?>%):
	                </th>
	                <td class="text-right">$<?=Base::Format($delivery,2,".",",")?></td>
	                <th>&nbsp;</th>
	                <td>&nbsp;</td>
	                <th>&nbsp;</th>
	                <td>&nbsp;</td>
	              </tr>
	              <tr>
	                <th style="text-align:right !important">Labor:
	                </th>
	                <td class="text-right">$<?=Base::Format($labor,2,".",",")?></td>
	                <th>&nbsp;</th>
	                <td>&nbsp;</td>
	                <th>&nbsp;</th>
	                <td>&nbsp;</td>
	              </tr>
	              <tr>
	              <tr>
	                <th style="text-align:right !important">Earnings (<?=$order->order_earnings_cab?>%):</th>
	                <td class="text-right">$<?=Base::Format($c_e,2,".",",")?></td>
	                <th style="text-align:right !important">Earnings (<?=$order->order_earnings_sinks?>%):</th>
	                <td class="text-right">$<?=Base::Format($s_e,2,".",",")?></td>
	                <th style="text-align:right !important">Earnings (<?=$order->order_earnings_tops?>%):</th>
	                <td class="text-right">$<?=Base::Format($t_e,2,".",",")?></td>
	              </tr>
	              <tr>
	                <th style="text-align:right !important">Total:</th>
	                <td class="text-right">$<?=Base::Format($c_total,2,".",",")?></td>
	                <th style="text-align:right !important">Total:</th>
	                <td class="text-right">$<?=Base::Format($s_total,2,".",",")?></td>
	                <th style="text-align:right !important">Total:</th>
	                <td class="text-right">$<?=Base::Format($t_total,2,".",",")?></td>
	              </tr>
	            </tbody>
	            <tfoot>
	              <tr>
	                <th colspan="5" style="text-align:right !important">General Subtotal:</th>
	                <td class="text-right">$<?=Base::Format($order->order_subtotal,2,".",",")?></td>
	              </tr>
	              <tr>
	                <th colspan="5" style="text-align:right !important">Shipping (<?=$order->order_shipping?>%):</th>
	                <td class="text-right">$<?=Base::Format(ceil(($order->order_subtotal*$order->order_shipping)/100),2,".",",")?></td>
	              </tr>
	              <tr>
	                <th colspan="5" style="text-align:right !important">Grand total:</th>
	                <td class="text-right">$<?=Base::Format($order->order_total,2,".",",")?></td>
	              </tr>
	            </tfoot>
	          </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
		</section>


    <div id="statusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="statusModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="order-status" class="col-md-8 col-md-offset-2" action="funciones/class.orders.php" method="post">
                <input type="hidden" name="order" value="<?=$id?>">
                <input id="action" type="hidden" name="action">
                <h4 class="text-center">Are you sure you want to mark this order as <b id="mark-status"></b>?</h4><br>

                  
                <div id="area-reason" style="display:none">
                  <div class="form-group">
                    <label class="control-label">Reason:</label>
                    <input id="reason" class="form-control" type="text" name="reason" maxlength="100">
                  </div>
                </div>

                <div class="form-group">
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="b-satus" class="btn btn-flat btn-primary" type="submit">Save</button>
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
      $('#statusModal').on('show.bs.modal',function(event){
        var button = $(event.relatedTarget);
        var title = button.data('title');
        var modal = $(this);

        if(title=="Canceled"||title=="Standby"){
          $('#area-reason').show();
        }else{
          $('#area-reason').hide();
          $('#reason').val('');
        }

        modal.find('#action').val(title);
        modal.find('#mark-status').text(title);
        modal.find('.modal-title').text("Mark as "+title);
        modal.find('.modal-body #estado').val(title);
      });

      $('#order-status').submit(function(e){
        e.preventDefault();
        var form  = $(this);
        var btn   = form.find('button[type="submit"]');
        var alert = form.find('.alert');
        var bar   = form.find('.progress');
        var reason = $('#reason').val();
        var val   = $('#action').val();
        access    = true;
        bar.show();
        btn.button('loading');

        if(val=="Standby"||val=="Canceled"){
          if(reason==""){
            access = false;
          }
        }

        if(access){
          $.ajax({
            type: 'post',
            url : 'funciones/class.orders.php',
            data: form.serialize(),
            dataType: 'json',
            success: function(r){
              if(r.response){
                $('#botones,#status').empty();
                $('#botones').append(r.data.btn);
                $('#status').append(r.data.status);
                if(val=="Standby"||val=="Canceled"){
                  $('#sreason').show();
                  $('#status-reason').text(r.data.reason);
                }else{
                  $('#sreason').hide();
                }
                alert.removeClass('alert-danger').addClass('alert-success');
              }else{
                alert.removeClass('alert-success').addClass('alert-danger');
              }

              alert.find('#msj').text(r.msj);
            },
            error: function(){
              alert.removeClass('alert-success').addClass('alert-danger');
              alert.find('#msj').text('An error has ocurred.');
            },
            complete: function(){
              bar.hide();
              alert.show().delay(7000).hide('slow');
              btn.button('reset');
            }
          })
        }else{
          bar.hide();
          alert.removeClass('alert-success').addClass('alert-danger');
          alert.find('#msj').text('You must enter a reason.');
          alert.show().delay(7000).hide('slow');
          btn.button('reset');
        }
      });
    });
  </script>
  <?
  break;
  case 'add':
  case 'edit':
  ?>
  <?
  break;
  default:
    $order = $orders->consulta();
    $revenues = $orders->revenues();
  ?>

	  <div class="row">
	    <div class="col-md-3 col-sm-6 col-xs-12">
	      <div class="info-box">
	        <span class="info-box-icon bg-red"><i class="fa fa-file-text-o"></i></span>
	        <div class="info-box-content">
	          <span class="info-box-text">Orders</span>
	          <span class="info-box-number"><?=count($order)?></span>
	        </div><!-- /.info-box-content -->
	      </div><!-- /.info-box -->
	    </div>
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-usd"></i></span>
          <div class="info-box-content">
            <div class="description-block border-right">
              <h5 class="description-header">$<?=$revenues?></h5>
              <span class="description-text">TOTAL REVENUE</span>
            </div>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div>
	  </div>

    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Orders</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=quotation"><i class="fa fa-plus" aria-hidden="true"></i> New quotation</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Project</th>
              <th class="text-center">Status</th>
              <th class="text-center">Client</th>
              <th class="text-center">Telephone</th>
              <th class="text-center">Products</th>
              <th class="text-center">Total</th>
              <th class="text-center">Date</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;$started=0;$completed=0;$standby=0;$cancel=0;
            foreach ($order as $d) {
              switch ($d->order_status){
                case 'Started':$status = "<span class=\"label label-primary\">Started</span>";$started++;break;
                case 'Completed':$status = "<span class=\"label label-success\">Completed</span>";$completed++;break;
                case 'Standby':$status = "<span class=\"label label-warning\">Standby</span>";$standby++;break;
                case 'Canceled':$status = "<span class=\"label label-danger\">Canceled</span>";$cancel++;break;
              }
          ?>
            <tr>
              <td class="text-center"><?=$d->order_order?></td>
              <td><?=$d->order_project?></td>
              <td class="text-center"><?=$status?></td>
              <td><?=$d->client_name?></td>
              <td><?=$d->client_phone?></td>
              <td class="text-center"><?=$d->products?></td>
              <td class="text-right">$<?=Base::Format($d->order_total,2,".",",")?></td>
              <td class="text-center"><?=Base::removeTS($d->order_fecha_reg)?></td>
              <td class="text-center">
                <a class="btn btn-flat btn-primary btn-sm" href="?ver=orders&opc=ver&id=<?=$d->id_order?>"><i class="fa fa-search"></i></a>
                <a class="btn btn-sm btn-flat btn-danger btn-print" href="reportes/orders.php?action=order&order=<?=$d->id_order?>" type="button"><i class="fa fa-print"></i></a>
              </td>
            </tr>
          <?
            $i++;
            }
          ?>        
          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <div class="row">
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h5 class="description-header"><?=$started?></h5>
              <span class="description-text"><span class="label label-primary">STARTED</span></span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h5 class="description-header"><?=$completed?></h5>
              <span class="description-text"><span class="label label-success">COMPLETED</span></span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <h5 class="description-header"><?=$standby?></h5>
              <span class="description-text"><span class="label label-warning">STANDBY</span></span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              <h5 class="description-header"><?=$cancel?></h5>
              <span class="description-text"><span class="label label-danger">CANCEL</span></span>
            </div>
            <!-- /.description-block -->
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
    $('.btn-print').click(function(){
      console.log("xhef");
      window.location.href = $(this).attr('xhref');
    });
  });
</script>