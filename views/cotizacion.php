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
		              	<div id="box-other" style="display:none">
						          <h3><span id="other-name">-</span></h3>
						          <p id="shape"><b>Forma:</b> <span id="other-shape"></span></p>
						          <p><b>Meterial:</b> <span id="other-mat">-</span></p>
						          <p><b>Color:</b> <span id="other-color">-</span></p>
						          <p><b>Costo:</b> $<span id="other-price">-</span></p>
		              	</div>
		              	<div id="box-gabi" style="display:none">
			              	<p><b>Item: </b> <span id="gabi-item"></span></p>
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
					              	<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
					              </tbody>
					            </table>
					          </div>
	                </div>
	              </div>
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
			  			</div>
			  		</div><!--box-product-->
			  		<div class="col-md-12 table-responsive">
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
					  </div><!--Table-responsive-->
					  <div class="col-md-4 col-md-offset-8">
		          <div class="table-responsive">
		            <table class="table">
		              <tbody>
		              	<tr>
		                	<th style="text-align:right !important;width:50%">Subtotal:</th>
		                	<td>$<span id="t-sub">0.00</span></td>
		              	</tr>
		              	<tr>
			                <th style="text-align:right !important">Tax (<?=$config->config_tax?>%):</th>
			                <td>$<span id="t-tax">0.00</span></td>
			              </tr>
			              <tr>
			                <th style="text-align:right !important">Delivery (<?=$config->config_delivery?>%):
			                <br><small style="font-weight:normal">Only for cabinets.</small>
			                </th>
			                <td>$<span id="t-deliv">0.00</span></td>
			              </tr>
			              <tr>
			                <th style="text-align:right !important">Labor:
			                <br><small style="font-weight:normal">Only for cabinets.</small>
			                </th>
			                <td>$<span id="t-labor">0.00</span></td>
			              </tr>
			              <tr>
			                <th style="text-align:right !important">Shipping (<?=$config->config_shipment?>%):</th>
			                <td>$<span id="t-shipping">0.00</span></td>
			              </tr>
			              <tr>
			              <tr>
			                <th style="text-align:right !important">Earnings (<?=$config->config_earnings?>%):</th>
			                <td>$<span id="t-earnings">0.00</span></td>
			              </tr>
			              <tr>
			                <th style="text-align:right !important">Total:</th>
			                <td>$<span id="t-total">0.00</span></td>
			              </tr>
			            </tbody>
			          </table>
		          </div>
        		</div>
					</div>
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
		earnings = <?=$config->config_earnings?>;
		//Trabajo regular
		regular = <?=$config->config_regular_work?>;
		//Trabajo grande
		big = <?=$config->config_big_work?>;
		//Descuento
		discount = <?=$config->config_discount?>;
		//Descuento
		delivery = <?=$config->config_delivery?>;


		//Activar la seccion correspondiente al cabiar el tipo de busqueda
		$('#type').change(function(){
			var val = $(this).val();
			$('#search').val('');
			$('#other-price,#other-color,#other-mat,#other-shape,#other-name,#gabi-desc,#gabi-item,#p-type').text(' - ');
			$('#foto').attr('src','images/no-image.png');
			$('#tbody-item').empty();$('#tbody-item').append("<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>");
			clean();
			switch(val){
				case "1":$('#search').focus();$('#box-gabi').show('slow');$('#box-other').hide();$('#search').attr('placeholder','Item #');$('#b-add').prop('disabled',false);break;
				case "2":$('#search').focus();$('#box-gabi').hide();$('#shape').show();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
				case "3":$('#search').focus();$('#box-gabi').hide();$('#shape').hide();$('#box-other').show('slow');$('#search').attr('placeholder','Name');$('#b-add').prop('disabled',false);break;
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
						url: 'funciones/class.productos.php',
						data: form.serialize(),
						dataType: 'json',
						success: function(r){
							if(r.response){
								$('#search').val('');$('#p-type').text(r.data.type);
								if(r.data.type=="1"){
									//Asignar los datos a los campos de gabinete.
									$('#gabi-item').text(r.data.item);
									$('#gabi-desc').text(r.data.desc);
									$('#tbody-item').empty(); $('#tbody-item').append(r.data.tr);
								}else if(r.data.type=="2"||r.data.type=="3"){
									$('#qty').focus();
									//Asignar los datos a los campos Other.
									$.each(r.data.s,function(i,v){$("#"+i).text(v);});
								}
								if(r.data.foto==null){$('#foto').attr('src','images/no-image.png');}else{$('#foto').attr('src','images/productos/'+r.data.foto);}
							}else{
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
			var type  = $('#p-type').text();
			var qty   = $('#qty').val();

			//Si la cantidad a agregar es mayor a 0.
			if(qty>0){
				if(type=="1"){
					//Si hay un item activo para agregar.
					if(td>0){
						addRow(type,qty);
					}else{
						alert.find('#msj').text('You must select a item first.');
						alert.show().delay(7000).hide('slow');
					}
				}else if(type=="2"||type=="3"){
					addRow(type,qty);
				}else{
					alert.find('#msj').text('You must look for a product first.');
					alert.show().delay(7000).hide('slow');
				}
			}else{
				alert.find('#msj').text('Quantity must be greater than 0.');
				alert.show().delay(7000).hide('slow');
			}
		});
		//Al hacer click sobre un item, resaltarlo.
		$('#tbody-item').on('click','.item-list td',function(){var item  = $(this);var price = item.text();$('#box-product .alert').hide();$('#tbody-item td').removeClass('success item-active');item.addClass('success item-active');$('#qty').focus();});
		//Al hacer click sobre la papelera, eliminar esa fila.
		$('#tbody-list').on('click','td .delRow',delRow);
	});

	//Agregar un producto a la cotizacion.		
	function addRow(type,qty){
		$('#qty').blur();
		//Si no hay productos cotizados, borra la fila de muestra.
		if(rows==0){$('#tbody-list').empty();}
		//Aumenta contador global de filas.
		rows++;

		if(type=="1"){
			var item  = $('#gabi-item').text();
			var desc  = $('#gabi-desc').text();
			var cost  = $('#tbody-item td.item-active').text();
			var price = Math.ceil((cost*discount)/100);
			var sub   = (price*qty);
			$('#tbody-list').append('<tr><td><button class="btn btn-flat btn-sm btn-danger delRow" type="button"><i class="fa fa-trash"></i></button></td><td>'+(rows)+'</td><td>-</td><td>'+desc+'</td><td>'+item+'</td><td>$'+cost+'</td><td>'+discount+'%</td><td>$'+price+'</td><td>'+qty+'</td><td>$<span ptype="1">'+sub+'</td>');
		}else if(type=="2"||type=="3"){
			if(type=="2"){var shape = '<b>Shape:</b> '+$('#other-shape').text()+" | ";}else{var shape = "";}
			var name  = $('#other-name').text();
			var color = $('#other-color').text();
			var price = $('#other-price').text();
			var mat   = $('#other-mat').text();
			var sub   = (price*qty);
			if(type=="3"){qty = qty+'(ft^2)';}
			var desc  = shape+"<b>Mat.:</b> "+mat+" | <b>Color</b>: "+color;
			$('#tbody-list').append('<tr><td><button class="btn btn-flat btn-sm btn-danger delRow" type="button"><i class="fa fa-trash"></i></button></td><td>'+(rows)+'</td><td>'+name+'</td><td>'+desc+'</td><td>-</td><td>$'+price+'</td><td>-</td><td>-</td><td>'+qty+'</td><td>$<span ptype="0">'+sub+'</td>');
		}
		//Limiamos campos.
		clean();
		//Calculamos el total.
		total();
	}

	//Eliminar un producto cotizado.
	function delRow(){
		//Disminuimos la variable global de filas.
		rows--;
		//Eliminamos fila.
		$(this).closest('tr').remove();

		//Si no hay productos en la cotizacion, colocar fila de muestra y los costos en 0.
		if(rows==0){
			$('#tbody-list').append('<tr><td>&nbsp;</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>');
			$('#t-sub,#t-tax,#t-shipping,#t-total');
		}else{
			//Acomodar el numero de las filas.
			var i = 1;
			$('#tbody-list tr').each(function(){ $(this).find('td').eq(1).text(i); i++;});
			//Calcular el total.
		}
		total();
	}

	//Limpiar campos y seleccion luego de agregar un producto a la cotizacion.
	function clean(){
		$('#tbody-item td').removeClass('success item-active');
		$('#qty').val('');
	}

	//Calcular el total.
	function total(){
		var sub   = 0;
		var taxes = 0;
		var deliv = 0;
		var labor = 0;
		var tship = 0;
		var earns = 0;
		var total = 0;

		//Calcular el subtotal.
		$('#tbody-list tr').each(function(){
			var x = $(this).find('td span');
			var y = (x.text()*1);
			var type = x.attr('ptype');
			if(type=="1"){ deliv += ((y*delivery)/100); labor+=50; }
			sub += y;
		});

		//Redondear hacia arriba.
		taxes = Math.ceil(((sub*tax)/100));
		deliv = Math.ceil(deliv);
		tship = Math.ceil((((sub+taxes+deliv+labor)*shipping)/100));
		earns = Math.ceil(((sub+taxes+deliv+labor+tship)*earnings)/100);
		total = Math.ceil((sub+taxes+deliv+labor+tship+earns));

		//Asignar valores.
		$('#t-sub').text(addCommas(sub));
		$('#t-tax').text(addCommas(taxes));
		$('#t-deliv').text(addCommas(deliv));
		$('#t-labor').text(addCommas(labor));
		$('#t-shipping').text(addCommas(tship));
		$('#t-earnings').text(addCommas(earns));
		$('#t-total').text(addCommas(total));
	}

</script>