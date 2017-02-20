<?
$configuration = new Configuracion();
$config = $configuration->consulta();
?>
<section class="content-header">
  <h1 class="text-center">
    CONTEMPOSPAZIO
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
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
			      <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
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
		                  	<option value="1">Cabinets</option>
		                  	<option value="2">Sinks</option>
		                  	<option value="3">Tops</option>
		                  </select>
		                	
		  								<input id="search" class="form-control" type="text" name="search" placeholder="Search">
				  					</div>
				  					<div class="form-group">
				  						<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-search"></i></button>
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
							          <p id="shape"><b>Forma:</b> <span id="other-shape"></span></p>
							          <p><b>Meterial:</b> <span id="other-mat">-</span></p>
							          <p><b>Color:</b> <span id="other-color">-</span></p>
							          <p><b>Price:</b> $<span id="other-price">-</span></p>
							          <p id="manufact"><b>Manufacturer:</b> $<span id="other-manu"><?=$config->config_manufacturer?></span></p>
			              	</div>
			              	<div id="box-gabi" style="display:none">
			              		<span id="id-item" class="hide"></span>
				              	<p><b>Item: </b> <span id="gabi-item"></span></p>
				              	<p><b>Labor: </b> $<span id="gabi-labor"></span></p>
				              	<p><b>Description: </b> <span id="gabi-desc"></span></p>
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
			                		<input id="qty" type="numer" class="form-control numeros" name="qty" style="width:45px" maxlength="2"/>
			                		<button id="b-add" class="btn btn-flat btn-success" type="submit" disabled><i class="fa fa-plus"></i></button>
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

				  		<div class="col-md-12">
				  			<div class="table-responsive">
							    <table class="table table-striped">
							    	<thead>
							    		<tr>
							    			<th width="5%">&nbsp;</th>
							    			<th width="5%">#</th>
							    			<th>Product</th>
							    			<th>Description</th>
							    			<th>Item #</th>
							    			<th>Price</th>
							    			<th>Discount</th>
							    			<th>Price disc.</th>
							    			<th width="5%">Quantity</th>
							    			<th>Subtotal</th>
							    		</tr>
							    	</thead>
							    	<tbody id="tbody-list">
							    		<tr><td>&nbsp;</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
							    	</tbody>
							    </table>
							   </div>
						  </div><!--Table-responsive-->
						  <div class="col-md-8 col-md-offset-4">
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
			                	<td>$<span id="c-sub">0.00</span></td>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="s-sub">0.00</span></td>
			                	<th style="text-align:right !important;">Subtotal:</th>
			                	<td>$<span id="t-sub">0.00</span></td>
			              	</tr>
			              	<tr>
				                <th style="text-align:right !important">Tax (<?=$config->config_tax?>%):</th>
				                <td>$<span id="taxes">0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th style="text-align:right !important">Manufacturer:</th>
				                <td>$<span id="manufacturer">0.00</span></td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Delivery (<?=$config->config_delivery?>%):
				                </th>
				                <td>$<span id="delivery">0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Labor:
				                </th>
				                <td>$<span id="labor">0.00</span></td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				                <th>&nbsp;</th>
				                <td>&nbsp;</td>
				              </tr>
				              <tr>
				              <tr>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_cab?>%):</th>
				                <td>$<span id="c-earnings">0.00</span></td>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_sinks?>%):</th>
				                <td>$<span id="s-earnings">0.00</span></td>
				                <th style="text-align:right !important">Earnings (<?=$config->config_earnings_tops?>%):</th>
				                <td>$<span id="t-earnings">0.00</span></td>
				              </tr>
				              <tr>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="c-total">0.00</span></td>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="s-total">0.00</span></td>
				                <th style="text-align:right !important">Total:</th>
				                <td>$<span id="t-total">0.00</span></td>
				              </tr>
				            </tbody>
				            <tfoot>
				              <tr>
				                <th colspan="5" style="text-align:right !important">General Subtotal:</th>
				                <td>$<span id="g-sub">0.00</span></td>
				              </tr>
				              <tr>
				                <th colspan="5" style="text-align:right !important">Shipping (<?=$config->config_shipment?>%):</th>
				                <td>$<span id="shipping">0.00</span></td>
				              </tr>
				              <tr>
				                <th colspan="5" style="text-align:right !important">Grand total:</th>
				                <td>$<span id="g-total">0.00</span></td>
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
											<input id="number" class="form-control" type="text" name="number"/>
										</div>
										<div class="col-md-2">
											<button id="s-number" class="btn btn-flat btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
										</div>
									</div>
									<hr>
      						<div class="form-group">
										<label class="col-md-3 control-label" for="client">Project: *</label>
										<div class="col-md-9">
											<input class="form-control" type="text" name="project" required/>
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
										<label class="col-md-3 control-label" for="client_address2">Address: </label>
										<div class="col-md-9">
											<input id="client_address2" class="form-control" type="text" name="client_address2"/>
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
        			<br>
							
            </div><!--Step 3-->
						<!--==========================================================================================================-->
					</div><!--row-->
			  </div><!-- /.box-body -->
			  <div class="box-footer">
			    
			  </div><!-- box-footer -->
			</div><!-- /.box -->
		</div>
    <!-- /.col -->
  </div>
</section>

<script type="text/javascript">
	$(document).ready(function(){
		//Filas en la table de productos cotizados
		rows = 0;
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
		//Descuento
		discount = <?=$config->config_discount?>;
		//Descuento
		delivery = <?=$config->config_delivery?>;
		//Manufacturer
		manufacturer = <?=$config->config_manufacturer?>;

		//Activar la seccion correspondiente al cabiar el tipo de busqueda
		$('#type').change(function(){
			var val = $(this).val();
			$('#search').val('');
			$('#other-price,#other-color,#other-mat,#other-shape,#other-name,#gabi-desc,#gabi-item,#gabi-labor,#p-type').text(' - ');
			$('#foto').attr('src','images/no-image.png');
			$('#tbody-item').empty();$('#tbody-item').append("<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
			clean();
			switch(val){
				case "1":$('#search').focus();$('#box-gabi').show('slow');$('#box-other').hide();$('#search').attr('placeholder','Item #');$('#b-add').prop('disabled',false);break;
				case "2":$('#search').focus();$('#box-gabi').hide();$('#shape').show();$('#manufact').hide();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
				case "3":$('#search').focus();$('#box-gabi').hide();$('#shape').hide();$('#manufact').show();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
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

			if(type=="0"){
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
								}else if(r.data.type=="2"||r.data.type=="3"){
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
			var type  = $('#p-type').text();
			var qty   = $('#qty').val();
			//Si la cantidad a agregar es mayor a 0.
			if(qty>0){
				if(type=="1"){
					//Si hay un item activo para agregar.
					if(td>0){addRow(id,type,qty);
					}else{alert.find('#msj').text('You must select a item first.');alert.show().delay(7000).hide('slow');}
				}else if(type=="2"||type=="3"){addRow(id,type,qty);
				}else{alert.find('#msj').text('You must look for a product first.');alert.show().delay(7000).hide('slow');}
			}else{alert.find('#msj').text('Quantity must be greater than 0.');alert.show().delay(7000).hide('slow');}
		});
		//Al hacer click sobre un item, resaltarlo.
		$('#tbody-item').on('click','.item-list td',function(){var item  = $(this);var price = item.text();$('#box-product .alert').hide();$('#tbody-item td').removeClass('success item-active');item.addClass('success item-active');$('#qty').focus();});
		//Al hacer click sobre la papelera, eliminar esa fila.
		$('#tbody-list').on('click','td .delRow',delRow);


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
		        if(val==""){
		          $(this).closest('.form-group').addClass('has-error');
		        }else{
		          $(this).closest('.form-group').removeClass('has-error');
		          error = error-1;
		        }
		      });

          if(error!==0){
            bar.hide('fast');
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
					        $('ul.setup-panel li:eq(2)').removeClass('disabled');
					        $('ul.setup-panel li a[href="#step-3"]').trigger('click');
                  alert.removeClass('alert-danger').addClass('alert-success');
                }else{
                  alert.removeClass('alert-success').addClass('alert-danger');  
                }
                alert.find('#msj').text(r.msj);
              },
              error: function(){
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.find('#msj').text('Ha ocurrido un error');
              },
              complete: function(){
                btn.button('reset');
                bar.hide('fast');
                alert.show().delay(7000).hide('slow');
              }
            })
          }
        });
		//=======================================================================================================
		//======================================================================================================

		$('#s-number').click(function(e){
			e.preventDefault();
			var btn = $(this);
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
						$('#client-form input[name*="cli_"]:visible').prop('readonly',false);
						alert.removeClass('alert-success').addClass('alert-danger');
						alert.find('#msj').text('Client # not found.');
					}
				},error: function(){
					$('#client_number').val(0);
					$('#client-form input[name*="cli_"]:visible').prop('readonly',false);
					alert.removeClass('alert-success').addClass('alert-danger');
					alert.find('#msj').text('An error has occurred.');
				},complete: function(){
					btn.button('reset');
					alert.show().delay(7000).hide('slow');
				}
			})
		});
	});//Ready

	//Agregar un producto a la cotizacion.		
	function addRow(id,type,qty){
		$('#qty').blur();
		//Si no hay productos cotizados, borra la fila de muestra.
		if(rows==0){$('#tbody-list').empty();}
		//Aumenta contador global de filas.
		rows++;
		//Activamos el boton para continuar al paso 2
		$('#activate-step-2').prop('disabled',false);

		if(type=="1"){
			var id_gp = $('#id-item').text();
			var item  = $('#gabi-item').text();
			var labor = $('#gabi-labor').text();
			var desc  = $('#gabi-desc').text();
			var cost  = $('#tbody-item td.item-active').text();
			var index = $('#tbody-item td.item-active').attr('index');
			var price = Math.ceil((cost*discount)/100);
			var sub   = (price*qty);
			$('#tbody-list').append('<tr><td><button class="btn btn-flat btn-sm btn-danger delRow" type="button"><i class="fa fa-trash"></i></button></td><td>'+rows+'</td><td>-</td><td>'+desc+'</td><td>'+item+'</td><td>$'+cost+'</td><td>'+discount+'%</td><td>$'+price+'</td><td>'+qty+'</td><td>$<span pid="'+id+'" item="'+id_gp+'" index="'+index+'" ptype="1" labor="'+labor+'" qty="'+qty+'">'+sub+'</td>');
		}else if(type=="2"||type=="3"){
			if(type=="2"){var shape = '<b>Shape:</b> '+$('#other-shape').text()+" | ";}else{var shape = "";}
			var name  = $('#other-name').text();
			var color = $('#other-color').text();
			var price = $('#other-price').text();
			var mat   = $('#other-mat').text();
			var sub   = (price*qty);
			var ft    = (type=="3")?'(ft^2)':'';
			var desc  = shape+"<b>Mat.:</b> "+mat+" | <b>Color</b>: "+color;
			$('#tbody-list').append('<tr><td><button class="btn btn-flat btn-sm btn-danger delRow" type="button"><i class="fa fa-trash"></i></button></td><td>'+rows+'</td><td>'+name+'</td><td>'+desc+'</td><td>-</td><td>$'+price+'</td><td>-</td><td>-</td><td>'+qty+ft+'</td><td>$<span pid="'+id+'" ptype="'+type+'" qty="'+qty+'">'+sub+'</td>');
		}
		clean();//Limpiamos campos.		
		total();//Calculamos el total.
	}//==============================================================================================================

	//Eliminar un producto cotizado.
	function delRow(){
		rows--;//Disminuimos la variable global de filas.
		$(this).closest('tr').remove();//Eliminamos fila.

		//Si no hay productos en la cotizacion, colocar fila de muestra y los costos en 0.
		if(rows==0){
			//Si no hay productos agregados. Desactivamos el boton para continuar
			$('#activate-step-2').prop('disabled',true);
			$('#tbody-list').append('<tr><td>&nbsp;</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>');
			$('#t-sub,#t-tax,#t-shipping,#t-total');
		}else{
			//Acomodar el numero de las filas.
			var i = 1; $('#tbody-list tr').each(function(){ $(this).find('td').eq(1).text(i); i++;});
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
		var g_sub   = 0; //General subtotal

		var c_earnings = 0; //Cabinerts Earnings
		var s_earnings = 0; //Sinks Earnings
		var t_earnings = 0; //Tops Earnings

		var c_total  = 0; //Cabinets Total
		var s_total  = 0; //Sinks Total
		var t_total  = 0; //Tops Total
		var grand_total = 0;//Grand total

		//Calcular el subtotal.
		$('#tbody-list tr').each(function(){
			var span = $(this).find('td span');//Span con todo lo datos
			
			var tr_sub = (span.text()*1);//Subtotal del tr
			var type   = span.attr('ptype');//Tipo de producto
			var qty    = span.attr('qty');//Cantidad
			var lab    = span.attr('labor');//Labor
			switch(type){
				case '1':
					labor += (qty*lab); //Labor
					c_sub += tr_sub; //Cabinets subtotal
				break;
				case '2':
					s_sub += tr_sub; //Sinks Subtotal
				break;
				case '3':
					man   += manufacturer*qty; //Manufacturer
					t_sub += tr_sub; //Tops subtotal
				break;
			}
		});

		//Redondear hacia arriba.
		taxes = Math.ceil(((c_sub*tax)/100)); //Taxes for cabinets
		deliv = Math.ceil(((c_sub+taxes)*delivery)/100); //Delivery for cabinets
		c_earnings = Math.ceil(((c_sub+taxes+deliv+labor)*e_cab)/100); //Cabinets Earnings
		c_total = c_sub + taxes + deliv + labor + c_earnings;//Total Cabinets

		
		s_earnings = Math.ceil((s_sub*e_sinks)/100);//Sinks Earnings
		s_total = s_earnings + s_sub;//Total Sinks

		
		t_earnings = Math.ceil(((t_sub+man)*e_tops)/100);//Tops Earnings
		t_total = t_earnings + man + t_sub;//Total Tops
		
		g_sub = Math.ceil(c_total+s_total+t_total);//General sub-total
		
		ship  = Math.ceil((g_sub*shipping)/100);//Shipment to client
		grand_total = Math.ceil(c_total+s_total+t_total+ship);//Grand total

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

		//Totales 
		$('#g-sub').text(addCommas(g_sub));
		$('#shipping').text(addCommas(ship));
		$('#g-total').text(addCommas(grand_total));
	}//=============================================================================================================

	function storeProducts(){
		var products = {};
		var i = 0;
		$('#tbody-list tr').each(function(){
			var span = $(this).find('td span');//Span con todo lo datos
			var id   = span.attr('pid');//Tipo de producto
			var type = span.attr('ptype');//Tipo de producto
			var qty  = span.attr('qty');//Cantidad
			var item = 0;
			var index = null;
			if(type=="1"){
				item = span.attr('item');
				index = span.attr('index');
			}
			var prod = {"type":type,"id":id,"item":item,"index":index,"qty":qty};
			products[i] = prod;
			i++;
		});

		$('#products').val(JSON.stringify(products));
	}
</script>