<?
$projects = new Projects();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Projects </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=project"> Project</a></li>
    <?if($li!=""){echo "<li class=\"active\">".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
	case 'ver':
	$project = $projects->obtener($id);
  ?>
  <div class="row">
  	<div class="col-md-12">
	    <section>
	      <a class="btn btn-flat btn-default" href="?ver=projects"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>
	      <a class="btn btn-flat btn-success" href="?ver=projects&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</a>
	      <?if($_SESSION['nivel']=="A"){?>
	      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>
	      <?}?>
	    </section><br>
  	</div>
  </div>
  <div class="row">
  	<div class="col-md-5">
  		<div class="box box-poison">
        <div class="box-body box-profile">
          <h3 class="profile-username text-center"><?=$project->title?></h3>
          <img class="img-responsive pad" src="<?=Base::Img("images/projects/".$project->photo)?>" alt="<?=Base::Img("images/projects/".$project->photo)?>" style="margin:0 auto">

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Created</b> <span class="pull-right"><?=$project->created?></span>
            </li>
            <li class="list-group-item">
              <b>Status</b> <span class="pull-right"><?=$projects->status($project->status)?></span>
            </li>
            <li class="list-group-item">
              <b>User</b> <span class="pull-right"><?=$project->user_nombres." ".$project->user_apellidos?></span>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
  	</div>
  	<div class="col-md-7">
  		<div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Messages</a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">History</a></li>
        </ul>
        <div class="tab-content">

          <!--=====================|| MESSAGES ||====================-->
          <div class="tab-pane active" id="tab_1">
          	<div class="box box-solid direct-chat direct-chat-poison" style="margin: 0">
              <!-- /.box-header -->
              <div class="box-body">
                <!-- Conversations are loaded here -->
                <div id="direct-chat-messages" class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  <?foreach($projects->comments() AS $comment){
                  	$align = ($comment->id_user == $_SESSION['id'])?'right':'left';
                  ?>
                  <div id="chat-text-<?=$comment->id_comment?>" class="direct-chat-msg <?=$align?>">
                    <div class="direct-chat-info clearfix">
                      <span class="direct-chat-name pull-<?=$align?>">&nbsp;&nbsp;<?=$comment->user_nombres." ".$comment->user_apellidos?></span>
                      <span class="direct-chat-timestamp pull-<?=$align?>">&nbsp;&nbsp;<?=date("d M y H:i",strtotime($comment->created))?></span>
                    </div>
                    <img src="" alt="">
                    <div class="direct-chat-text">
                      <?=$comment->comment?>
                    </div><!-- /.direct-chat-text -->
                  </div><!-- /.direct-chat-msg -->
									<?}?>
                </div><!--/.direct-chat-messages-->
                <!-- /.direct-chat-pane -->
              </div><!-- /.box-body -->
              
              <div class="box-footer" style="">
                <form id="form-new-comment" action="funciones/class.project_comments.php" method="POST">
                	<input type="hidden" name="project" value="<?=$project->id_project?>">
                	<input type="hidden" name="action" value="add_comment">
                  <div class="input-group">
                    <input name="comment" placeholder="Type Message ..." class="form-control" type="text">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-poison btn-flat">Send</button>
                    </span>
                  </div>
                  <div class="alert alert-danger" style="display:none" role="alert">
			        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj">An error has ocurred.</span>
			        		</div>
                </form>
              </div>
              <!-- /.box-footer-->
            </div>
          </div><!-- /.tab-pane -->
          <!--=====================|| HISTORY ||====================-->
          <div class="tab-pane" id="tab_2">
          	
          </div><!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
  	</div>
  </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    		scrollDown();

    		$('#form-new-comment').submit(function(e){
    			e.preventDefault();
    			var form = $(this);
    			var alert = form.find('.alert');

    			$.ajax({
    				type: 'POST',
    				cache: false,
    				url: 'funciones/class.projects_comments.php',
    				data: form.serialize(),
    				dataType: 'json',
    				success: function(r){
    					if(r.response){
    						form[0].reset();
    						$('.direct-chat-messages').append(r.data.comment);
    						scrollDown();
    					}else{
    						alert.show().delay(5000).hide();
    					}
    				},
    				error: function(){
    					alert.show().delay(5000).hide();
    				}
    			})
    		});
    	});

    	function scrollDown(){
    		
    		var height = document.getElementById("direct-chat-messages").scrollHeight-150;
    		$('#direct-chat-messages').animate({
            scrollTop: height
          }, 1000);
    	}
    </script>

  <?
	break;
  case 'add':
  case 'edit':
  $inventory  = new Inventory();
  $categories = $inventory->consultaCategories();
  ?>
    <div class="row">
    	<div class="col-md-9 col-sm-9 col-xs-12">
    		<div class="col-md-12">
    			<div class="box box-solid">
    				<div class="box-header with-border">
    					<h3 class="box-title">Inventory Items</h3>
			        <div class="box-tools pull-right">
	              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	            </div>
    				</div>
    				<div class="box-body">
  						<?foreach($categories AS $category){?>
  						<div class="row">
  							<div class="col-md-4">
  								<h4><?=$category->icat_category?></h4>
  								<ul id="inventory-item-list" class="list-group">
	  								<?foreach($inventory->category->getItemsByCategory($category->id_category) AS $items){?>
	  									<li id="list-item-<?=$items->id_inventory?>" class="list-group-item">
				               	<span class="inventory-item-name"><?=$items->inv_name?> <?=" (<span class='inventory-item-stock'>{$items->inv_stock}</span> {$items->mea_unit})"?></span>
				               	<span class="pull-right">
				               		<button xid="<?=$items->id_inventory?>" class="btn-link btn-box-tool btn-add-item"><i class="fa fa-plus"></i></button>
				               	</span>
				              </li>
			              <?}?>
  								</ul>
  							</div>
  						</div>
  						<?}?>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-12">
    			<div class="box box-poison">
			      <div class="box-header with-border">
			        <h3 class="box-title"><i class="fa fa-wrench"></i> New Project</h3>
			      </div>
			      <div class="box-body">
			      	<form id="form-new-project" action="funciones/class.projects.php" method="POST" enctype="multipart/form-data">
			      		<input type="hidden" name="action" value="add_project">
			      		<input id="project-items" type="hidden" name="project-items" value="">
			      		<div class="row">
			      			<div class="col-md-6">
			      				<div class="form-group">
					      			<label class="control-label" for="project-title">Project title: *</label>
					      			<input id="project-title" class="form-control" type="text" name="project-title" placeholder="Project title" required>
					      		</div>
			      			</div>
			      			<div class="col-md-6" style="border-left: 2px solid #eee;">
			      				<div class="form-group">
			      					<label for="file-img">Project image:</label>
                      <div class="imageUploadWidget">
                        <div class="imageArea">
                          <img id="project-img" src="" alt="" prev="">
                          <img class="spinner-image" src="images/spinner.gif">
                        </div>
                        <div class="btnArea">
                          <input id="file-img" name="foto" accept="image/jpeg,image/png" type="file">
                        </div>
                      </div>
                    </div>
			      			</div>
			      		</div>
			      		<div class="row">
			      			<div class="col-md-12">
			      				<h5><strong>Items for this project:</strong></h5>
			      				<table class="table table-bordered table-hover table-condensed">
			      					<thead>
			      						<tr>
			      							<th width="10%">#</th>
			      							<th width="60%">Item</th>
			      							<th width="20%">Qty</th>
			      							<th width="10%">Action</th>
			      						</tr>
			      					</thead>
			      					<tbody id="tbody-project-items-list">
			      					</tbody>			      					
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

			      				<div class="form-group"><button id="save-new-project" class="btn btn-flat btn-block btn-danger" type="submit" disabled><i class="fa fa-send" aria-hidden="true"></i> Save project</div>
			      			</div>
			      		</div>
			      	</form>
			      </div>
			    </div><!--box-->
    		</div>	        
      </div>

      <div class="col-md-3 col-sm-3 col-xs-12">
        <div class="box box-poison">
		      <div class="box-header with-border">
		        <h3 class="box-title">Templates</h3>
		        <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
		      </div>
		      <div class="box-body">
	      		<ul id="projects-template-list" class="list-group list-group-unbordered">

            	<?foreach([] AS $d){?>
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
	        	<form id="form-add-template" action="funciones/class.projects.php" method="POST">
	        		<input type="hidden" name="action" value="add_template">
	        		<div class="form-group">
        				<div class="input-group">
	                <input id="add-template-name" name="add-template-name" class="form-control" placeholder="Template" type="text">
	                <div class="input-group-btn">
	                  <button id="save-new-template" type="submit" class="btn btn-primary btn-flat b-submit" disabled>Add</button>
	                </div>
	              </div><!-- /btn-group -->
	        		</div>
	        		<div class="alert alert-danger" style="display:none" role="alert">
	        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
	        		</div>
	        	</form>
		      </div>
		    </div><!--box-->
      </div>
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    		$('#file-img').change(preview);

    		$('#inventory-item-list .btn-add-item').on('click',addProjectItem);
    		$('#tbody-project-items-list').on('click','.btn-delete-item',deleteProjectItem);
    		$('#form-new-project').submit(save);
    	
    	});//Ready

    	function addProjectItem(){
    		var parent = $(this).parent().parent();
    		var name  = parent.text().trim();
    		var xid   = $(this).attr('xid'); //Inventory ID
    		var tbody = $('#tbody-project-items-list');
    		var num   = $('#tbody-project-items-list tr').length+1;
    		var stock = parent.find('.inventory-item-stock').text().trim()*1;
    		var tr ='<tr id="item-'+num+'" xid="'+xid+'"><td class="text-center">'+num+'</td>';
    			tr  +='<td>'+name+'</td>';
    			tr  +='<td><div class="form-group"><div class="input-group"><span class="input-group-addon">'+stock+'&nbsp;/</span><input id="qty-'+xid+'" class="form-control" type="number" placeholder="Qty" min="0" value="1"></div></div></td>';
    			tr  +='<td class="text-center"><button row="item-'+num+'" class="btn-link btn-box-tool btn-delete-item" type="button"><i class="fa fa-times" aria-hidden="true" style="color:red"></i></button></td>';
    			tr  +='</tr>';

    		parent.attr('class','bg-red disabled list-group-item');
    		$('#save-new-template,#save-new-project').prop('disabled',false);

    		tbody.append(tr);
    	}

    	function deleteProjectItem(){
    		var row = $(this).attr('row');
    		var xid = $('#'+row).attr('xid');
    		var btn = $('#inventory-item-list').find('#list-item-'+xid);
    		btn.attr('class','list-group-item');
    		$('#'+row).remove();
    		fixCount();
    	}

    	function fixCount(){
    		var tr = $('#tbody-project-items-list tr');
    		if(tr.length>0){
	    		$.each(tr, function(k,v){
	    			$(v).find("td").first().text(k+1);
	    		});
	    	}else{
	    		$('#save-new-template,#save-new-project').prop('disabled',true);
	    	}
    	}

    	//Almacenar los items en como texto en formato JSON en un campo hidden
    	//Para enviar por el formulario
			function storeItems(){
				var products = [];
				$('#tbody-project-items-list tr').each(function(k,v){
					var tr   = $(this);//tr del item
					var id   = tr.attr('xid');//ID del Item (Inventory)
					var qty  = tr.find('#qty-'+id).val();//Cantidad
					var prod = {id:id,qty:qty};
					products[k] = prod;
				});

				$('#project-items').val(JSON.stringify(products));
			}

			//Save project
			function save(e){
        e.preventDefault();
        storeItems();
        var form = $(this);
        var id   = this.id;
        var url  = form.attr('action');
        var formdata = new FormData(form[0]);
        var alert = form.find('.alert');
        var progress = form.find('.progress');
        var btn  = form.find('input[type="submit"]');
        var errors = 0;

        $.each($('#tbody-project-items-list>tr input'),function(k,v){
        	if($(v).val()<=0){
        		$(v).closest('.form-group').addClass('has-error');
        		errors++;
        	}else{
        		$(v).closest('.form-group').removeClass('has-error');
        	}
        });

        if(errors>0){
          alert.removeClass('alert-success').addClass('alert-danger');
          alert.find('#msj').text('Quantities must be greater than 0.');
          alert.show().delay(7000).hide('slow');
        }else{
          btn.button('loading');
          alert.hide('fast');
          progress.show();

          $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: function(r){
              if(r.response){
                alert.removeClass('alert-danger').addClass('alert-success');
                form[0].reset();
                window.location.replace('?ver=projects&opc=ver&id='+r.data);
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
              progress.hide();
              alert.show().delay(7000).hide('slow');
            }
          });
        }
      }

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
		    var img   = $('#project-img');
		    //Imagen anterior
		    var prev  = img.attr('src');
		    //Imagen loading
		    var load = $('.spinner-image');
		    //Guardar imagen anterior
		    img.attr('prev',prev);
		    //Ocultar imagen
		    img.hide();
		    //Mostar cargando
		    load.show();
		    if(file){
		      if(file.size<2000000){
		        if(type == 'image/jpeg' || type == 'image/png' || type == 'image/jpg'){
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
		      $('.alert').removeClass('alert-success').addClass('alert-danger');
		      $('.alert').show().delay(7000).hide('slow');
		      load.hide();
		    }else{ img.parent().parent().removeClass('error'); }
		  }//Preview-----------------------------------------------------------------------------------
    </script>
  <?
  break;
  default:
  	$project = $projects->consulta();
  ?>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-purple"><i class="fa fa-wrench"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Projects</span>
            <span class="info-box-number"><?=count($project)?></span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
      </div>
    </div>
		
		<div class="row">
    	<div class="col-md-12">
    		<div class="box box-poison">
		      <div class="box-header with-border">
		        <h3 class="box-title"><i class="fa fa-wrench"></i> Projects</h3>
		        <div class="pull-right">
		          <a class="btn btn-flat btn-sm btn-success" href="?ver=projects&opc=add"><i class="fa fa-plus" aria-hidden="true"></i> Add Project</a>
		        </div>
		      </div>
		      <div class="box-body">
		        <table class="table data-table table-striped table-bordered table-hover">
		          <thead>
		            <tr>
		              <th class="text-center">#</th>
		              <th class="text-center">Title</th>
		              <th class="text-center">Status</th>
		              <th class="text-center">Registered</th>
		              <th class="text-center">Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          <? $i = 1;
		            foreach($project as $d) {
		          ?>
		            <tr>
		              <td class="text-center"><?=$i?></td>
		              <td class="text-center"><?=$d->title?></td>
		              <td class="text-center"><?=$projects->status($d->status)?></td>
		              <td class="text-center"><?=Base::removeTS($d->created)?></td>
		              <td class="text-center">
		                <a class="btn btn-flat btn-primary btn-sm" href="?ver=projects&opc=ver&id=<?=$d->id_project?>"><i class="fa fa-search"></i></a>
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