<?
$configuration = new Configuration();
$config = $configuration->consulta();
$clients = new Clients();
$list    = $clients->list_clients();
?>
<section class="content-header">
  <h1> Quotation </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li class="active">Quotation</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Info boxes -->
  <div class="row">
		<div class="col-md-12">
	  	<div class="box">
			  <div class="box-header with-border">
			    <h3 class="box-title">Quotation</h3>
			    <div class="box-tools pull-right">
			      <i class="fa fa-calculator" aria-hidden="true"></i>
			    </div><!-- /.box-tools -->
			  </div><!-- /.box-header -->
			  <div class="box-body">
			  	<div class="row">
            <div class="col-xs-12">
              <ul class="nav nav-pills nav-justified thumbnail setup-panel" style="margin-bottom:0">
                <li class="active">
                  <a href="#step-1">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Add products</p>
                  </a>
                </li>
                <li class="disabled">
                  <a href="#step-2">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Client information</p>
                  </a>
                </li>
                <li class="disabled">
                  <a href="#step-3">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Details</p>
                  </a>
                </li>
              </ul><!--Ul nav-nav-pills -->
            </div>
          </div><!--row-->
			  	<div class="row">
			  		<div id="step-1" class="setup-content">
			  			<br>
				  		<div class="col-md-12">
				  			<div class="col-md-12">
				  				<form id="fsearch" action="#" class="form-inline">
				  					<input type="hidden" name="action" value="search">
				  					<div class="form-group">
				  						<label for="search" class="control-label">Search: </label>
		                  
		                  <select id="type" type="text" class="form-control" name="type">
		                  	<option value="0">Type</option>
		                  	<option value="1" selected>Cabinets</option>
		                  	<option value="2">Sinks</option>
		                  	<option value="3">Tops</option>
		                  	<option value="4">Accessories</option>
		                  </select>
		                	
		  								<input id="search" class="form-control" type="text" name="search" placeholder="Item #">
				  					</div>
				  					<div class="form-group">
				  						<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-search"></i></button>
				  						<button class="btn btn-flat btn-default" type="button" data-toggle="modal" data-target="#productsAddressBookModal" title="Products list"><i class="fa fa-server"></i></button>
				  					</div>
				  				</form>
				  			</div>
				  			<div class="col-md-12 box-product">
				  				<div class="row">
					  				<div class="col-md-3">
			                <div style="padding:10px">
			                	<img id="foto" class="img-responsive" src="<?=Base::Img("images/productos/")?>" alt="<?=Base::Img("images/productos/")?>" prev="">
			                </div>
			              </div>
			              <div class="col-md-9">
		              		<span id="p-type" class="hide"></span>
		              		<span id="p-id" class="hide"></span>
			              	<div id="box-other" style="display:none">
							          <h3><span id="other-name">-</span></h3>
							          <p id="shape"><b>Shape:</b> <span id="other-shape"></span></p>
							          <p id="material"><b>Material:</b> <span id="other-mat">-</span></p>
							          <p id="color"><b>Color:</b> <span id="other-color">-</span></p>
							          <p><b>Price:</b> $<span id="other-price">-</span></p>
							          <p id="manufact"><b>Manufacturer:</b> $<span id="other-manu">-</span></p>
			              	</div>
			              	<div id="box-gabi">
			              		<span id="id-item" class="hide"></span>
				              	<p><b>Item: </b> <span id="gabi-item">-</span></p>
				              	<p><b>Labor: </b> $<span id="gabi-labor">-</span></p>
				              	<p><b>Description: </b> <span id="gabi-desc">-</span></p>
			                	<table id="table-items" class="table table-bordered table-condensed">
						              <thead>
						                <tr class="active">
						                  <th>GS</th>
						                  <th>MGC</th>
						                  <th>RBS</th>
						                  <th>ES & MS</th>
						                  <th>WS</th>
						                  <th>MIW</th>
						                </tr>
						              </thead>
						              <tbody id="tbody-item">
						              	<tr>
						              		<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
						              	</tr>
						              </tbody>
						            </table>
						          </div><!--box-gabi-->
		                </div><!--col-md-9-->
		              </div><!--row-->
		              <div class="row">
		                <div class="col-md-4 col-md-offset-5">
		                	<form id="faddRow" class="form-inline" action="#">
			                	<div class="form-group">
			                		<label class="control-label">Qty:</label>
			                		<input id="qty" type="number" class="form-control numeros" name="qty" style="width:45px" maxlength="2"/>
			                		<button id="b-add" class="btn btn-flat btn-success" type="submit"><i class="fa fa-plus"></i></button>
			                	</div>
			                </form>
		                </div>
		              </div><br>
		              <div class="alert alert-danger alert-dismissible" role="alert" style="display:none">
	                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
	                </div>
				  			</div><!--co-md-12 box-product-->
				  		</div>

				  		<div class="col-md-12 quotation-list" style="background:#ECF0F5;padding:5px">
						  </div><!--Table-responsive-->
						  <div class="col-md-10 col-md-offset-2" style="margin-top:10px">
			          <div class="table-responsive">
			            <table class="table table-bordered">
			            	<thead>
			            		<tr>
			            			<th colspan="2">Cabinets (Units): <span id="c-qty">0</span></th>
			            			<th colspan="2">Sinks (Units): <span id="s-qty">0</span></th>
			            			<th colspan="2">Tops (ft^2): <span id="t-qty">0</span></th>
			            			<th colspan="2">Accessories (Units): <span id="a-qty">0</span></th>
			            		</tr>
			            	</thead>
			              <tbody>
			              	<tr>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="c-sub" class='pull-right'>0.00</span></td>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="s-sub" class='pull-right'>0.00</span></td>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="t-sub" class='pull-right'>0.00</span></td>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="a-sub" class='pull-right'>0.00</span></td>
			              	</tr>
			              	<tr>
				                <th style="text-align:right !important">Tax (<?=$config->config_tax?>%):</th>
				                <td>$<span id="taxes" class='pull-right'>0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th style="text-align:right !important">Manufacturer:</th>
				                <td>$<span id="manufacturer" class='pull-right'>0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Delivery (<?=$config->config_delivery?>%):</th>
				                <td>$<span id="delivery" class='pull-right'>0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Labor:</th>
				                <td>$<span id="labor" class='pull-right'>0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_cab?>%):</th>
				                <td>$<span id="c-earnings" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_sinks?>%):</th>
				                <td>$<span id="s-earnings" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_tops?>%):</th>
				                <td>$<span id="t-earnings" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_acce?>%):</th>
				                <td>$<span id="a-earnings" class='pull-right'>0.00</span></td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="c-total" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="s-total" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="t-total" class='pull-right'>0.00</span></td>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="a-total" class='pull-right'>0.00</span></td>
				              </tr>
				            </tbody>
				            <tfoot>
				              <tr>
				                <th colspan="7" style="text-align:right !important">General Subtotal:</th>
				                <td>$<span id="g-sub" class='pull-right'>0.00</span></td>
				              </tr>
				              <tr>
				                <th colspan="7" style="text-align:right !important">Shipping (<?=$config->config_shipment?>%):</th>
				                <td>$<span id="shipping" class='pull-right'>0.00</span></td>
				              </tr>
				              <tr>
				                <th colspan="7" style="text-align:right !important">Grand total:</th>
				                <td>$<span id="g-total" class='pull-right'>0.00</span></td>
				              </tr>
				            </tfoot>
				          </table>
			          </div>
	        		</div>
              <div class="col-md-12 text-center">
                <button id="activate-step-2" class="btn btn-primary btn-flat" type="button" disabled>Next &nbsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></button>
              </div>
        		</div><!--Step 1-->

        		<!--======================================================================================================
																									STEP2 
        		==========================================================================================================-->
        		<div id="step-2" class="setup-content">
        			<br/>
        			<div clasS="col-md-6 col-md-offset-3">
        				<form id="client-form" class="form-horizontal" action="funciones/class.quotation.php">
        					<input type="hidden" name="action" value="add_quo">
        					<input id="products" type="hidden" name="products">
        					<input id="client_number" type="hidden" name="client_number" value="0">
									<div class="form-group">
										<label class="col-md-3 control-label" for="number">Client number: </label>
										<div class="col-md-7">
											<select id="number" class="form-control" type="text" name="number">
												<option value=""></option>
												<?
													foreach ($list as $d){
												?>
													<option value="<?=$d->client_number?>"><?=$d->client_number." | ".$d->client_name?></option>
												<?
													}
												?>
											</select>
										</div>
										<div class="col-md-2">
											<button id="s-reset" class="btn btn-flat btn-default" type="button" title="Clear selection"><i class="fa fa-refresh" aria-hidden="true"></i></button>
										</div>
									</div>
									<hr>
      						<div class="form-group">
										<label class="col-md-3 control-label" for="project_name">Project: *</label>
										<div class="col-md-9">
											<input id="project_name" class="form-control" type="text" name="project_name" required/>
										</div>
									</div>
									<hr>
									<div class="form-group">
										<label class="col-md-3 control-label" for="client_name">Client: *</label>
										<div class="col-md-9">
											<input id="client_name" class="form-control" type="text" name="client_name" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="client_address">Address: *</label>
										<div class="col-md-9">
											<input id="client_address" class="form-control" type="text" name="client_address" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="client_email">Email: *</label>
										<div class="col-md-9">
											<input id="client_email" class="form-control" type="text" name="client_email" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="client_phone">Telephone: *</label>
										<div class="col-md-9">
											<input id="client_phone" class="form-control" type="text" name="client_phone" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="client_contact">Contact: *</label>
										<div class="col-md-9">
											<input id="client_contact" class="form-control" type="text" name="client_contact" required/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="project_address">Project address: </label>
										<div class="col-md-9">
											<input id="project_address" class="form-control" type="text" name="project_address"/>
										</div>
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
									<div class="col-md-3 col-md-offset-3">
                  	<button id="activate-step-3" type="submit" class="btn btn-danger btn-flat">Save &nbsp;<i class="fa fa-send" aria-hidden="true"></i></button>
                  </div>
        				</form>
        			</div>
            </div><!--Step 2-->

            <!--======================================================================================================
																									STEP3 
        		==========================================================================================================-->
        		<div id="step-3" class="setup-content">

							<div class="col-md-12">
								<h3 class="text-center"><span id="order-project_name"></span></h3>
                <br>
							</div>
							<div class="col-md-10 col-md-offset-1">
								<div class="alert alert-success" role="alert" style="display:none">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
                	<span id="msj"></span>
                </div>
							</div>
							<div class="col-md-4 col-md-offset-2">
								<h4>Client details</h4>
								#<span id="order-cnumber"></span>
								<p>Name: <span id="order-name"></span></p>
								<p>Phone: <span id="order-phone"></span></p>
								<p>Address: <br><span id="order-address"></span></p>
								<p>Project address: <br><span id="order-project_address"></span></p>
							</div>
							<div class="col-md-3 col-md-offset-1">
								<h4>Order details</h4>
								<table class="table">
									<tbody>
										<tr>
											<td class="text-right" style="width:50%">Cabinets (Units):</td>
											<td class="text-left"><span id="order-cabinets"></span></td>
										</tr>
										<tr>
											<td class="text-right">Sinks (Units):</td>
											<td class="text-left"><span id="order-sinks"></span></td>
										</tr>
										<tr>
											<td class="text-right">Tops (ft^2):</td>
											<td class="text-left"><span id="order-tops"></span></td>
										</tr>
										<tr>
											<td class="text-right">Accessories (Units):</td>
											<td class="text-left"><span id="order-accessories"></span></td>
										</tr>
									</tbody>
									<tfoot>
										<tr style="font-size:150%;color:#DD4B39;font-weight:600">
											<td class="text-right">Total:</td>
											<td class="text-left">$<span id="order-total"></span></td>
										</tr>
									</tfoot>
								</table>
	        		</div>
	        		<div class="col-md-12">
	        			<center><br>
									<a id="o-link" class="btn btn-flat btn-danger" href="#">Order details</a>
								</center>
							</div>
            </div><!--Step 3-->
						<!--==========================================================================================================-->
					</div><!--row-->
			  </div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
    <!-- /.col -->
  </div>
</section>

<!--============================|| Libreta de productos ||==================================-->
<div id="productsAddressBookModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="productsAddressBookModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="productsAddressBookModalLabel">Products - <span id="productsAddressBookTitle"></span></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
          	<table id="table-products-address-book" class="table table-bordered table-condensed" style="width:100%">
          		<thead>
          			<tr>
          				<th>#</th>
          				<th>#</th>
          				<th>Product</th>
          				<th>Action</th>
          			</tr>
          		</thead>
          		<tbody id="tbody-products-address-book">
          			<tr>
          				<td></td>
          				<td></td>
          				<td></td>
          				<td></td>
          			</tr>
          		</tbody>
          	</table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--======================================================================================================-->

<script type="text/javascript">
	$(document).ready(function(){
		//Cantidad cajas de productos agregadas .box
		rows = 0;
		//Cantidad totales de cada producto
		c_qty = 0; s_qty = 0; t_qty = 0; a_qty = 0;
		//Variable de impuestos
		tax = <?=$config->config_tax?>;
		//Variable de % de envio al cliente
		shipping = <?=$config->config_shipment?>;
		//Ganacias
		e_cab = <?=$config->config_earnings_cab?>;
		//Ganacias
		e_sinks = <?=$config->config_earnings_sinks?>;
		//Ganacias
		e_tops = <?=$config->config_earnings_tops?>;
		//Ganacias
		e_acce = <?=$config->config_earnings_acce?>;
		//Delivery
		delivery = <?=$config->config_delivery?>;

		$("#number").select2({
      placeholder: 'Client number'
    });

    $('#productsAddressBookModal').on('show.bs.modal', function(event){
    	var modal = $(this);
			var type  = $("#type").val();
			var title = $("#type option[value="+type+"]").text();

			loadProductsAddressBook(type);

			modal.find('#productsAddressBookTitle').text(title);
    });

    //Add search term for the selected product and trigger the search
    $('#tbody-products-address-book').on('click','.btn-add-term', function(e){
	  	var term = $(this).parent().parent().find('.search-term-data').text().trim();
	  	$('#productsAddressBookModal').modal('hide');
	  	$('#search').val(term);
	  	$('#fsearch').submit();
    });

		//Activar la seccion correspondiente al cabiar el tipo de busqueda
		$('#type').change(function(){
			var val = $(this).val();
			$('#search').val('');
			$('#other-price,#other-color,#other-mat,#other-shape,#other-name,#other-manu,#gabi-desc,#gabi-item,#gabi-labor,#p-type').text(' - ');
			$('#foto').attr('src','images/no-image.png');
			$('#tbody-item').empty();$('#tbody-item').append("<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
			clean();
			switch(val){
				case "1":$('#search').focus();$('#box-gabi').show('slow');$('#box-other').hide();$('#search').attr('placeholder','Item #');$('#b-add').prop('disabled',false);break;
				case "2":$('#search').focus();$('#box-gabi').hide();$('#shape').show();$('#manufact').hide();$('#color').show();$('#material').show();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
				case "3":$('#search').focus();$('#box-gabi').hide();$('#shape').hide();$('#manufact').show();$('#color').show();$('#material').show();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
				case "4":$('#search').focus();$('#box-gabi').hide();$('#shape').hide();$('#manufact').hide();$('#color').hide();$('#material').hide();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
				default:$('#b-add').prop('disabled',true);$('#box-gabi,#box-other').hide();$('#search').attr('placeholder','Search');break;
			}
		});

		//Busqueda del producto
		$('#fsearch').submit(function(e){
			e.preventDefault();
			var form  = $(this);
			var btn   = form.find('button[type=submit]');
			var alert = $('.box-product .alert');
			var term  = $('#search').val();
			var type  = $('#type').val();

			if(type==0){
				alert.find('#msj').text('You must select a type.');alert.show().delay(7000).hide('slow');
			}else{
				if(term==""){
					alert.find('#msj').text('You must enter a search term.');alert.show().delay(7000).hide('slow');
				}else{
					$.ajax({
						type: 'post',
						cache: false,
						url: 'funciones/class.products.php',
						data: form.serialize(),
						dataType: 'json',
						success: function(r){
							if(r.response){
								$('#search').val('');$('#p-type').text(r.data.type);$('#p-id').text(r.data.id);
								if(r.data.type=="1"){
									//Asignar los datos a los campos de gabinete.
									$('#id-item').text(r.data.id_item);
									$('#gabi-item').text(r.data.item);
									$('#gabi-labor').text(r.data.labor);
									$('#gabi-desc').text(r.data.desc);
									$('#tbody-item').empty(); $('#tbody-item').append(r.data.tr);
								}else if(r.data.type=="2"||r.data.type=="3"||r.data.type=="4"){
									$('#qty').focus();
									//Asignar los datos a los campos Other.
									$.each(r.data.s,function(i,v){$("#"+i).text(v);});
								}
								if(r.data.foto==null){$('#foto').attr('src','images/no-image.png');}else{$('#foto').attr('src','images/productos/'+r.data.foto);}
							}else{
								$('#type').change();
								$('#tbody-item').empty();$('#tbody-item').append("<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
								$('#foto').attr('src','images/no-image.png');
								$('#p-type').text("");
								alert.find('#msj').text('Product not found.');
								alert.show().delay(7000).hide('slow');
							}
						},
						error: function(){
							alert.find('#msj').text('An error occurred.');
							alert.show().delay(7000).hide('slow');
						}
					});
				}
			}
		});

		//Agregar un producto a la tabla de cotizacion.
		$('#faddRow').submit(function(e){
			e.preventDefault();
			var alert = $('.box-product .alert');
			var td    = $('#tbody-item td.item-active').length;
			var id    = $('#p-id').text();
			var type  = $('#p-type').text()*1;
			var qty   = $('#qty').val();
			//Si la cantidad a agregar es mayor a 0.
			if(qty>0){
				if(type==1){
					//Si hay un item activo para agregar.
					if(td>0){addRow(id,type,qty);
					}else{alert.find('#msj').text('You must select a item first.');alert.show().delay(7000).hide('slow');}
				}else if(type==2||type==3||type==4){addRow(id,type,qty);
				}else{alert.find('#msj').text('You must look for a product first.');alert.show().delay(7000).hide('slow');}
			}else{alert.find('#msj').text('Quantity must be greater than 0.');alert.show().delay(7000).hide('slow');}
		});
		//Al hacer click sobre un item, resaltarlo.
		$('#tbody-item').on('click','.item-list td',function(){
			var item  = $(this);
			var price = (item.text()*1);
			if(price>0){
				$('#box-product .alert').hide();
				$('#tbody-item td').removeClass('success item-active');
				item.addClass('success item-active');
				$('#qty').focus();
			}else{
				$('#tbody-item td').removeClass('success item-active');
				$('.box-product .alert').find('#msj').text('Item no valid!');
				$('.box-product .alert').show().delay(3000).hide('slow');
			}
		});
		//Al hacer click sobre la papelera, eliminar esa fila.
		$('.quotation-list').on('click','.delBox',delBox);
		//=====================================================================================================
		//=====================================|| ACTIVAR PASOS ||=============================================

		var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');allWells.hide();
        navListItems.click(function(e)
        {
          e.preventDefault();
          var $target = $($(this).attr('href')),
          $item = $(this).closest('li');          
          if (!$item.hasClass('disabled')){navListItems.closest('li').removeClass('active');$item.addClass('active');allWells.hide('slow');$target.show('slow');}
        });
        
        $('ul.setup-panel li.active a').trigger('click');
        
        $('#activate-step-2').on('click', function(e){
	        $('ul.setup-panel li:eq(1)').removeClass('disabled');
	        $('ul.setup-panel li a[href="#step-2"]').trigger('click');
        });
        $('a[href="#step-2"]').on('click', function(e) {
        	storeProducts();
        });

        $('#activate-step-3').click(function(e){
          e.preventDefault();
          var btn   = $(this);
          var form  = $('#client-form');
          var bar   = form.find('.progress');
          var alert = form.find('.alert');

          btn.button('loading');
          alert.hide('fast');
          bar.show('fast');

			    var error = $('#step-2 input').filter('[required]').length;
		      $('#step-2 input').filter('[required]').each(function(){
		        var val = $(this).val();
		        var regex = $(this).attr('pattern');
		        if(val == ""){
			        $(this).closest('.form-group').addClass('has-error');
			      }else{
			        if(val.match(regex)){
			          $(this).closest('.form-group').removeClass('has-error');
			          error--;
			        }else{
			          $(this).closest('.form-group').addClass('has-error');
			        }
			      }
		      });

          if(error!==0){
            bar.hide('fast');
            alert.removeClass('alert-success').addClass('alert-danger');
          	alert.find('#msj').text('You must complete all required fields.');
          	alert.show().delay(7000).hide('slow');
          	btn.button('reset');
          }else{
            $.ajax({
              type: 'post',
              cache: false,
              url: 'funciones/class.quotation.php',
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                	$.each(r.data,function(k,v){ $('#order-'+k).text(v); });
                  $('#o-link').attr('href','inicio.php?ver=orders&opc=ver&id='+r.data.link);
                	$('ul.setup-panel li:eq(0),ul.setup-panel li:eq(1)').addClass('disabled');
					        $('ul.setup-panel li:eq(2)').removeClass('disabled');
					        $('ul.setup-panel li a[href="#step-3"]').trigger('click');
					        $('#step-3 .alert #msj').text(r.msj);
                  $('#step-3 .alert').show().delay(7000).hide('slow');
                }else{
                	bar.hide('fast');
                  alert.removeClass('alert-success').addClass('alert-danger');
                	alert.find('#msj').text(r.msj);
                	btn.button('reset');
                	alert.show().delay(7000).hide('slow');
                }
              },
              error: function(){
                bar.hide('fast');
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Ha ocurrido un error');
                btn.button('reset');
                alert.show().delay(7000).hide('slow');
              }
            })
          }
        });
		//=======================================================================================================
		//======================================================================================================

		$('#number').change(function(){
			var cli = $('#number').val();
			var alert = $('#client-form .alert');
			$.ajax({
				type: 'post',
				cache: false,
				url: 'funciones/class.clients.php',
				data: {action:'get_client',cli:cli},
				dataType: 'json',
				success: function(r){
					if(r.response===true){
						alert.removeClass('alert-danger').addClass('alert-success');
						alert.find('#msj').text('Client is already registered.');
						$.each(r.data,function(k,v){
							$('#'+k).val(v);
						});
						$('#client-form input[name*="client_"]:visible').prop('readonly',true);
					}else{
						$('#client_number').val(0);
						$('#client-form input[name*="client_"]:visible').prop('readonly',false);
						alert.removeClass('alert-success').addClass('alert-danger');
						alert.find('#msj').text('Client # not found.');
					}
				},error: function(){
					$('#client_number').val(0);
					$('#client-form input[name*="client_"]:visible').prop('readonly',false);
					alert.removeClass('alert-success').addClass('alert-danger');
					alert.find('#msj').text('An error has occurred.');
				}
			})
		});

		$('#s-reset').click(function(){
			$('#client_number').val(0);
			$('#number').val('');
			$('#client-form input[name*="client_"]:visible').prop('readonly',false).val('');
		})

	});//==========================Ready

	//Agregar un producto a la cotizacion.		
	function addRow(id,type,qty){
		$('#qty').blur();
		//Si no hay productos cotizados, borra la fila de muestra.
		if(rows==0){$('#tbody-list').empty();}
		//Aumenta contador global de filas.
		rows++;
		//Activamos el boton para continuar al paso 2
		$('#activate-step-2').prop('disabled',false);

		if(type == 1){
			var id_gp = $('#id-item').text();
			var item  = $('#gabi-item').text();
			var labor = $('#gabi-labor').text();
			var desc  = $('#gabi-desc').text();
			var price = $('#tbody-item td.item-active').text();
			var index = $('#tbody-item td.item-active').attr('index');
			var sub   = (price*qty);
			var color = cabinetColor(index);
			c_qty += (qty*1);
			var box ='<div class="box box-danger" pid="'+id+'" item="'+id_gp+'" index="'+index+'" ptype="1" labor="'+labor+'" qty="'+qty+'"><div class="box-header with-border"><h3 class="box-title"><span class="row-num">'+rows+'</span> | Cabinet</h3><div class="box-tools pull-right"><button class="btn btn-sm btn-flat btn-danger delBox" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
			box +='<div class="box-body">';
			box +='<div class="col-md-5">'+desc+' | <b>Color:</b> '+color+'</div>';
			box +='<div class="col-md-2"><p><b>Item #:</b><span class="pull-right">'+item+'</span></p></div>';
			box +='<div class="col-md-2"><p><b>Price:</b><span class="pull-right">$'+price+'</span></p></div>';
			box +='<div class="col-md-1"><p><b>Qty:</b><span class="pull-right">'+qty+'</span></p></div>';
			box +='<div class="col-md-2"><p><b>Subtotal:</b><span class="pull-right subotal">$<span class="subtotal">'+sub+'</span></span></p></div></div></div>';
			$('.quotation-list').append(box);
		}else if(type==2||type==3||type==4){
			if(type==2){
				s_qty += (qty*1);
				var desc  = '<b>Shape:</b> '+$('#other-shape').text()+" | "+"<b>Mat.:</b> "+$('#other-mat').text()+" | <b>Color</b>: "+$('#other-color').text();
			}
			if(type==3){
				t_qty = (qty*1);
				var desc  = "<b>Mat.:</b> "+$('#other-mat').text()+" | <b>Color</b>: "+$('#other-color').text();
			}
			if(type==4){
				a_qty = (qty*1);
				var desc  = "-";
			}

			var name  = $('#other-name').text();
			var price = $('#other-price').text();
			var manu  = $('#other-manu').text();
			var sub   = (price*qty);
			var ft    = (type==3)?'(ft^2)':'';
			var box ='<div class="box box-danger" pid="'+id+'" ptype="'+type+'" qty="'+qty+'" manu="'+manu+'"><div class="box-header with-border"><h3 class="box-title"><span class="row-num">'+rows+'</span> | '+name+'</h3><div class="box-tools pull-right"><button class="btn btn-sm btn-flat btn-danger delBox" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
			box +='<div class="box-body">';
			box += '<div class="col-md-5"><b>Description:</b> <span class="pull-right">'+desc+'</span></div>';
			box +='<div class="col-md-2"><p><b>Item #:</b><span class="pull-right">-</span></p></div>';
			box +='<div class="col-md-2"><p><b>Price:</b><span class="pull-right">$'+price+'</span></p></div>';
			box +='<div class="col-md-1"><p><b>Qty:</b><span class="pull-right">'+qty+ft+'</span></p></div>';
			box +='<div class="col-md-2"><p><b>Subtotal:</b><span class="pull-right">$<span class="subtotal">'+sub+'</span></span></p></div></div></div>';
			$('.quotation-list').append(box);
		}
		clean();//Limpiamos campos.		
		total();//Calculamos el total.
	}//==============================================================================================================

	function delBox(){
		rows--;//Disminuimos la variable global de filas.
		var box  = $(this).closest('.box');
		var del  = (box.attr('qty')*1);
		var type = (box.attr('ptype')*1);
		switch(type){
			case 1: c_qty -= del; break;
			case 2: s_qty -= del; break;
			case 3: t_qty -= del; break;
			case 4: a_qty -= del; break;
		}

		box.remove();//Eliminamos el producto.
		if(rows==0){
			//Si no hay productos agregados. Desactivamos el boton para continuar
			$('#activate-step-2').prop('disabled',true);
		}else{
			var i = 1; $('.quotation-list .box').each(function(){ $(this).find('.row-num').text(i); i++;});
		}
		//Calcular el total.
		total();
	}//==============================================================================================================

	//Limpiar campos y seleccion luego de agregar un producto a la cotizacion.
	function clean(){$('#tbody-item td').removeClass('success item-active');$('#qty').val('');}
	//====================================================================================================================

	//Calcular el total.
	function total(){
		var taxes = 0; //Taxes - Cabinets
		var deliv = 0; //Delivery - Cabinets
		var labor = 0; //Labor cost - Cabinets
		var ship  = 0; //Client shipment
		var man   = 0; //Manufacturer - Tops

		var c_sub   = 0; //Cabinets subtotal
		var s_sub   = 0; //Sinks Subtotal
		var t_sub   = 0; //Tops subtotal
		var a_sub   = 0; //Accessories subtotal
		var g_sub   = 0; //General subtotal

		var c_earnings = 0; //Cabinerts Earnings
		var s_earnings = 0; //Sinks Earnings
		var t_earnings = 0; //Tops Earnings
		var a_earnings = 0; //Accessories Earnings

		var c_total  = 0; //Cabinets Total
		var s_total  = 0; //Sinks Total
		var t_total  = 0; //Tops Total
		var a_total  = 0; //Accessories Total
		var grand_total = 0;//Grand total

		$('#c-qty').text(c_qty);
		$('#s-qty').text(s_qty);
		$('#t-qty').text(t_qty);
		$('#a-qty').text(a_qty);

		//Calcular el subtotal.
		$('.quotation-list .box').each(function(){
			var box = $(this);//Span con todo lo datos
			
			var box_sub = (box.find('.subtotal').text()*1);//Subtotal del tr
			var type   = box.attr('ptype');//Tipo de producto
			var qty    = box.attr('qty');//Cantidad
			var lab    = box.attr('labor');//Labor
			var manu   = box.attr('manu');//Manufacturer

			switch(type){
				case '1':
					labor += (qty*lab); //Labor
					c_sub += box_sub; //Cabinets subtotal
				break;
				case '2':
					s_sub += box_sub; //Sinks Subtotal
				break;
				case '3':
					man   += manu*qty; //Manufacturer
					t_sub += box_sub; //Tops subtotal
				break;
				case '4':
					a_sub += box_sub; //Accessories subtotal
				break;
			}
		});

		//Redondear hacia arriba.
		taxes = Math.ceil(((c_sub*tax)/100)); //Taxes for cabinets
		deliv = Math.ceil(((c_sub+taxes)*delivery)/100); //Delivery for cabinets
		c_earnings = Math.ceil(((c_sub+taxes+deliv+labor)*e_cab)/100); //Cabinets Earnings
		c_total = Math.ceil(c_sub + taxes + deliv + labor + c_earnings);//Total Cabinets

		
		s_earnings = Math.ceil((s_sub*e_sinks)/100);//Sinks Earnings
		s_total = Math.ceil(s_earnings + s_sub);//Total Sinks

		
		t_earnings = Math.ceil(((t_sub+man)*e_tops)/100);//Tops Earnings
		t_total = Math.ceil(t_earnings + man + t_sub);//Total Tops

		a_earnings = Math.ceil((a_sub*e_acce)/100);//Accessories Earnings
		a_total = Math.ceil(a_earnings + a_sub);//Total Tops
		
		g_sub = Math.ceil(c_total+s_total+t_total+a_total);//General sub-total
		
		ship  = Math.ceil((g_sub*shipping)/100);//Shipment to client
		grand_total = Math.ceil(c_total+s_total+t_total+a_total+ship);//Grand total

		//Asignar valores.
		//Cabinets
		$('#c-sub').text(addCommas(c_sub));
		$('#taxes').text(addCommas(taxes));
		$('#delivery').text(addCommas(deliv));
		$('#labor').text(addCommas(labor));
		$('#c-earnings').text(addCommas(c_earnings));
		$('#c-total').text(addCommas(c_total));

		//Sinks
		$('#s-sub').text(addCommas(s_sub));
		$('#s-earnings').text(addCommas(s_earnings));
		$('#s-total').text(addCommas(s_total));

		//Tops
		$('#t-sub').text(addCommas(t_sub));
		$('#manufacturer').text(addCommas(man));
		$('#t-earnings').text(addCommas(t_earnings));
		$('#t-total').text(addCommas(t_total));

		//Accessories
		$('#a-sub').text(addCommas(a_sub));
		$('#a-earnings').text(addCommas(a_earnings));
		$('#a-total').text(addCommas(a_total));

		//Totales 
		$('#g-sub').text(addCommas(g_sub));
		$('#shipping').text(addCommas(ship));
		$('#g-total,#res-total').text(addCommas(grand_total));
	}//=============================================================================================================

	function storeProducts(){
		var products = {};
		var i = 0;
		$('.quotation-list .box').each(function(){
			var box  = $(this);//Span con todo lo datos
			var id   = box.attr('pid');//Tipo de producto
			var type = box.attr('ptype');//Tipo de producto
			var qty  = box.attr('qty');//Cantidad
			var item = 0;
			var index = null;
			if(type=="1"){
				item = box.attr('item');
				index = box.attr('index');
			}
			var prod = {"type":type,"id":id,"item":item,"index":index,"qty":qty};
			products[i] = prod;
			i++;
		});

		$('#products').val(JSON.stringify(products));
	}

	//Obtener el color seleccionado basado en el index
	function cabinetColor(index){
		var color = "";
		switch(index){
			case '3':color = "GS";break;
			case '4':color = "MGC";break;
			case '5':color = "RBS";break;
			case '6':color = "ES & MS";break;
			case '7':color = "WS";break;
			case '8':color = "MIW";break;
		}
		return color;
	}

	//Cargar libreta de productos
	function loadProductsAddressBook(type){
		$.ajax({
			type: 'POST',
			cache: false,
			url: 'funciones/class.products.php',
			data: {action:'getProductsAddressBook',type:type},
			dataType: 'json',
			success: function(r){
				if(r.response){
	        $('#table-products-address-book').DataTable().destroy();
	        $('#tbody-products-address-book').empty();
	        $('#tbody-products-address-book').append(r.data);
        	builProductTable(type);
				}else{
					
				}
			},
			error: function(r){
				console.log("error");
			},
			complete: function(r){
			}
		});
	}

	function builProductTable(type){
		if(type==1){
			$('#table-products-address-book').DataTable({
				responsive:true,
		    "aaSorting": [],
	      "columnDefs": [{"visible":false, "targets":0}],
	      "order": [[ 0, 'asc' ]],
	      "displayLength": 25,
	      "drawCallback": function(settings){
	        var api = this.api();
	        var rows = api.rows( {page:'current'} ).nodes();
	        var last=null;
	 
	        api.column(0,{page:'current'}).data().each( function( group, i ){
	          if(last !== group){
	            $(rows).eq(i).before('<tr class="text-center group bg-grey"><td colspan="4"><b>'+group+'</b></td></tr>');
	            last = group;
	          }
	        });
	      }
	    });
		}else{
			$('#table-products-address-book').DataTable({
		    "paging": true,
		    responsive:true,
		    "searching": true,
		    "ordering": true,
		    "aaSorting": [],
	      "columnDefs": [{"visible":false, "targets":0}],
		  });
		}    
  }
</script>