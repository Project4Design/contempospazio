<?
$projects = new Projects();
if($opc=="add"){$li="Add";}elseif($opc=="edit"){$li="Edit";}elseif($opc=="ver"){$li="Ver";}else{$li="";}
?>

<section class="content-header">
  <h1> Projects </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Home</a></li>
    <li><a href="inicio.php?ver=projects"> Projects</a></li>
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
      <?if($_SESSION['nivel']=="A"){
      		if($project->status<4){
    	?>
	      	<a class="btn btn-flat btn-success" href="?ver=projects&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit information</a>
      		<button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Delete</button>
      <?	}
    		}
    	?>
	    </section><br>
  	</div>
  </div>
  <div class="row">
  	<div class="col-md-5">
  		<div class="box box-poison">
        <div class="box-body box-profile">
          <h3 class="profile-username text-center"><?=$project->title?></h3>
          <img id="project-main-photo" class="img-responsive pad" src="<?=Base::Img("images/thumbs/".$projects->gallery->getMain()->thumb)?>" alt="<?=$projects->gallery->getMain()->thumb?>" style="margin:0 auto">

          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Created</b> <span class="pull-right"><?=$project->created?></span>
            </li>
            <li class="list-group-item">
              <b>Status</b>
              <span class="pull-right">
              	<?if($project->status>1 && $_SESSION['nivel'] == 'A'){?>
              	 <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#changeStatusModal" data-opc="-1"><i class="fa fa-caret-left" aria-hidden="true"></i></button> 
              	<?}?>
              	<?=$projects->status($project->status)?>
              	<?if($project->status<4 && $_SESSION['nivel'] == 'A'){?>
              	 <button class="btn btn-default btn-xs" data-toggle="modal" data-target="#changeStatusModal" data-opc="1"><i class="fa fa-caret-right" aria-hidden="true"></i></button>
            		<?}?>
            	</span>
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
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Messages <i class="fa fa-inbox" aria-hidden="true"></i></a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">History <i class="fa fa-history" aria-hidden="true"></i> </a></li>
        </ul>
        <div class="tab-content">

          <!--=====================|| MESSAGES ||====================-->
          <div class="tab-pane active" id="tab_1">
          	<div class="box box-solid direct-chat direct-chat-poison" style="margin: 0">
              <!-- /.box-header -->
              <div class="box-body">
                <!-- Conversations are loaded here -->
                <div id="direct-chat-messages" class="direct-chat-messages">
                </div><!--/.direct-chat-messages-->
              </div><!-- /.box-body -->
              
              <div class="box-footer">
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
          	<div class="timeline-history">
	          	<ul id="timeline-logs" class="timeline timeline-inverse">
	              <!-- END timeline item -->
	              <li>
	                <i class="fa fa-clock-o bg-gray"></i>
	              </li>
	            </ul>
	          </div>
          </div><!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
  	</div>
  </div>
  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-solid">
	      <div class="box-header with-border">
	        <h3 class="box-title"> Items in this project</h3>
	        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
	      </div>
	      <div class="box-body">
	      	<table class="table table-striped">
		  			<thead>
		  				<tr>
		  					<th>#</th>
		  					<th>Category</th>
		  					<th>Name</th>
		  					<th>Quantity</th>
		  					<th>Action</th>
		  				</tr>
		  			</thead>
		  			<tbody>
	  				<?$i=1;
	  					foreach ($projects->items() as $k => $d){
	  						$data = $projects->checkItemStock($d);
	  				?>
	  					<tr>
  							<td class="text-center"><?=$i?></td>
  							<td><?=$d->category?></td>
  							<td><?=$d->name?></td>
  							<td><?=$data->stock?></td>
  							<td class="text-center"><?=$data->button?></td>
	  					</tr>
	  				<?
	  					$i++;
	  					}
	  				?>
		  			</tbody>
		  		</table>
	      </div>
	    </div><!--box-->

  		<div class="box box-solid">
	      <div class="box-header with-border">
	        <h3 class="box-title"> Templates in this project</h3>
	        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
	      </div>
	      <div class="box-body">
	      	<table class="table table-striped">
		  			<thead>
		  				<tr>
		  					<th>#</th>
		  					<th>Category</th>
		  					<th>Name</th>
		  					<th>Quantity</th>
		  					<th>Action</th>
		  				</tr>
		  			</thead>
		  			<tbody>
		  				<?$i=1;
		  					foreach ($projects->templates() as $tempID => $template){
		  				?>
		  				<tr>
								<td class="text-center"><?=$i?></td>
								<th class="text-center" colspan="4"><?=$template->name?></th>
		  				</tr>
			  				<?
			  					foreach ($template->items as $k => $d){
			  						$data = $projects->checkItemStock($d,$tempID);
			  				?>
			  					<tr>
		  							<td></td>
		  							<td><?=$d->category?></td>
		  							<td><?=$d->name?></td>
		  							<td><?=$data->stock?></td>
		  							<td class="text-center"><?=$data->button?></td>
			  					</tr>
			  				<?
			  					}	//foreach Items
			  					$i++;
		  					}//foreach Templates
			  				?>
		  			</tbody>
		  		</table>
	      </div>
	    </div><!--box-->
	  </div>
	  <div class="col-md-12">
	  	<div class="box box-solid">
	  		<div class="box-header">
	  			<h3 class="box-title">Gallery <i class="fa fa-photo" aria-hidden="true"></i> </h3>
	  			<div class="pull-right">
	  				<?if($project->status > 0 && $project->status < 5){?>
	  					<button class="btn btn-flat btn-sm btn-default" data-toggle="modal" data-target="#addPhotoModal"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Upload</button>
	  				<?}?>
	  			</div>
	  		</div>
	  		<div class="box-body" style="padding:0">
	  			<div class="nav-tabs-custom" style="margin:0">
		        <ul class="nav nav-tabs">
		          <li class="<?=$project->status == 1 ?'active':''?>"><a href="#tab_1_started" data-toggle="tab" aria-expanded="false">Started </a></li>
		          <li class="<?=$project->status == 2 ?'active':''?>"><a href="#tab_2_demolished" data-toggle="tab" aria-expanded="false">Demolished </a></li>
		          <li class="<?=$project->status == 3 ?'active':''?>"><a href="#tab_3_installed" data-toggle="tab" aria-expanded="false">Installed </a></li>
		          <li class="<?=$project->status == 4 ?'active':''?>"><a href="#tab_4_completed" data-toggle="tab" aria-expanded="false">Completed </a></li>
		        </ul>
		        <div class="tab-content">
		          <!--=====================|| Started ||====================-->
		          <div class="tab-pane <?=$project->status == 1 ?'active':''?>" id="tab_1_started">
		          	<div class="row">
			          	<div id="gallery-body-1" class="col-xs-12 col-md-12" style="padding:0">
										<?foreach($projects->gallery->all(1) AS $gallery){?>
											<div id="gallery-<?=$gallery->id_gallery?>" class="col-md-2 col-xs-12" style="margin-bottom: 5px">
												<div class="gallery-item <?=(!$gallery->main)?:'gallery-item-main'?>">
													<button type="button" title="Remove photo" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-danger btn-remove-gallery" data-action="remove_photo" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-times"></i></button>
													<button type="button" title="Set as main" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-warning btn-main-gallery" data-action="set_main" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-star"></i></button>
													<a href="<?=Base::Img("images/uploads/{$gallery->photo}")?>" data-fancybox="fancy-images-1">
														<img class="img-responsive" src="<?=Base::Img("images/thumbs/{$gallery->thumb}")?>" alt="<?=$gallery->thumb?>">
													</a>
												</div>
											</div>
										<?}?>
									</div>
								</div>
		          </div><!-- /.tab-pane -->
		          <!--=====================|| Demolished ||====================-->
		          <div class="tab-pane <?=$project->status == 2 ?'active':''?>" id="tab_2_demolished">
		          	<div class="row">
			          	<div id="gallery-body-2" class="col-xs-12 col-md-12" style="padding:0">
										<?foreach($projects->gallery->all(2) AS $gallery){?>
											<div id="gallery-<?=$gallery->id_gallery?>" class="col-md-2 col-xs-12" style="margin-bottom: 5px">
												<div class="gallery-item <?=(!$gallery->main)?:'gallery-item-main'?>">
													<button type="button" title="Remove photo" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-danger btn-remove-gallery" data-action="remove_photo" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-times"></i></button>
													<button type="button" title="Set as main" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-warning btn-main-gallery" data-action="set_main" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-star"></i></button>
													<a href="<?=Base::Img("images/uploads/{$gallery->photo}")?>" data-fancybox="fancy-images-2">
														<img class="img-responsive" src="<?=Base::Img("images/thumbs/{$gallery->thumb}")?>" alt="<?=$gallery->thumb?>">
													</a>
												</div>
											</div>
										<?}?>
									</div>
								</div>
		          </div><!-- /.tab-pane -->
		          <!--=====================|| Installed ||====================-->
		          <div class="tab-pane <?=$project->status == 3 ?'active':''?>" id="tab_3_installed">
		          	<div class="row">
			          	<div id="gallery-body-3" class="col-xs-12 col-md-12" style="padding:0">
										<?foreach($projects->gallery->all(3) AS $gallery){?>
											<div id="gallery-<?=$gallery->id_gallery?>" class="col-md-2 col-xs-12" style="margin-bottom: 5px">
												<div class="gallery-item <?=(!$gallery->main)?:'gallery-item-main'?>">
													<button type="button" title="Remove photo" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-danger btn-remove-gallery" data-action="remove_photo" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-times"></i></button>
													<button type="button" title="Set as main" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-warning btn-main-gallery" data-action="set_main" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-star"></i></button>
													<a href="<?=Base::Img("images/uploads/{$gallery->photo}")?>" data-fancybox="fancy-images-3">
														<img class="img-responsive" src="<?=Base::Img("images/thumbs/{$gallery->thumb}")?>" alt="<?=$gallery->thumb?>">
													</a>
												</div>
											</div>
										<?}?>
									</div>
								</div>
		          </div><!-- /.tab-pane -->
		          <!--=====================|| Completed ||====================-->
		          <div class="tab-pane <?=$project->status == 4 ?'active':''?>" id="tab_4_completed">
		          	<div class="row">
			          	<div id="gallery-body-4" class="col-xs-12 col-md-12" style="padding:0">
										<?foreach($projects->gallery->all(4) AS $gallery){?>
											<div id="gallery-<?=$gallery->id_gallery?>" class="col-md-2 col-xs-12" style="margin-bottom: 5px">
												<div class="gallery-item <?=(!$gallery->main)?:'gallery-item-main'?>">
													<button type="button" title="Remove photo" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-danger btn-remove-gallery" data-action="remove_photo" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-times"></i></button>
													<button type="button" title="Set as main" data-photo="<?=$gallery->id_gallery?>" class="btn btn-flat btn-warning btn-main-gallery" data-action="set_main" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-star"></i></button>
													<a href="<?=Base::Img("images/uploads/{$gallery->photo}")?>" data-fancybox="fancy-images-4">
														<img class="img-responsive" src="<?=Base::Img("images/thumbs/{$gallery->thumb}")?>" alt="<?=$gallery->thumb?>">
													</a>
												</div>
											</div>
										<?}?>
									</div>
								</div>
		          </div><!-- /.tab-pane -->
		        </div>
		        <!-- /.tab-content -->
		      </div>	
	  		</div>
	  		<div class="box-footer">
	  			<div class="col-xs-12 col-md-12 margin">
            <div class="alert alert-danger alert-gallery" style="display:none" role="alert">
        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span class="msj">An error has ocurred.</span>
        		</div>
					</div>
	  		</div>
	  	</div>
	  </div>
  </div>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content modal-danger">
        <form action="funciones/class.projects.php" method="POST">
          <input type="hidden" name="action" value="delete_project">
          <input type="hidden" name="project" value="<?=$id?>">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Delete Project</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">Are you sure you want to <b>delete</b> this Project?</h4>
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

  <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="funciones/class.projects.php" method="POST">
          <input type="hidden" name="action" value="add_item_stock">
          <input id="project" type="hidden" name="project" value="<?=$id?>">
          <input id="template" type="hidden" name="template" value="">
          <input id="item" type="hidden" name="item" value="">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Add Stock</h4>
          </div>
          <div class="modal-body">
          	<div class="row">
	          	<div class="col-md-12">
		            <h4 class="text-center">Add stock for this Item</h4>
		            <p class="text-center">The excess amount will be added to the inventory.</p>
		          </div>
		          <div class="col-md-6 col-md-offset-3">
		            <div class="form-group">
		            	<label for="stock">Stock: *</label>
		            	<input id="stock" class="form-control" type="number" name="stock" placeholder="Quantity" required>
		            </div>
							</div>
							<div class="col-md-12">
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
	          </div>
          </div>
          <div class="modal-footer">
            <button id="b-del" type="submit" class="btn btn-flat btn-success b-submit">Save</button>
            <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <div id="changeStatusModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      	<form id="formChangeStatus" action="funciones/class.projects.php" type="POST">
      		<input type="hidden" name="project" value="<?=$id?>">
      		<input type="hidden" name="action" value="changeStatus">
      		<input type="hidden" name="status" value="">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	          <h4 class="modal-title">Change status</h4>
	        </div>
	        <div class="modal-body">
	        	<div class="row">
	        		<div class="col-md-12">
	        			<h4 class="text-center">Are you sure you want to make this action?</h4>
	        		</div>
	        		<div class="col-md-12">
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
	        	</div>
	        </div>
	        <div class="modal-footer">
						<button type="button" class="btn btn-flat btn-primary b-submit">Save</button>
	          <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
	        </div>
      	</form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <div id="optionsPhotoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="optionsPhotoModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <h4  id="options-text" class="text-center"></h4>
        </div>
        <div class="modal-footer">
					<button type="button" xid="#" xaction="#" class="btn btn-flat options-gallery"></button>
          <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <div id="addPhotoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addPhotoModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form id="add-project-photos" action="funciones/class.projects_gallery.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add_project_photo">
          <input id="project" type="hidden" name="project" value="<?=$id?>">
    			<input id="dropzone-input" type="file" accept="image/jpeg,image/png" name="photos[]" multiple style="display:none">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Upload Photo</h4>
          </div>
          <div class="modal-body">
          	<div class="dropzone text-center">
          		<div class="dropzone-no-uploads">
          			<div class="dropzone-drag">
	          			<h2>
	          				Drag and drop photos here
	          			</h2>
	          			<p>Or</p>
	          		</div>

          			<button id="btn-select-files" class="btn btn-flat btn-default" type="button"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp;Select files</button>
          		</div>
	  					
	  					<div class="row dropzone-thumbs-container">
	  					</div><!--dropzone-thumbs-container-->
          	
          	</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <script type="text/javascript">
		var $form = $('#add-project-photos'),
				$photos = $('#dropzone-input'),
				uploadFilesList = null,
				project_id  = <?=$id?>,
				project_status = <?=$project->status?>,
				lastComment = 0,
				lastLog     = 0,
				workingComments = false,
				workingLogs = false,
				uploading   = false;

		//Create preview image thumbs markup in dropzone
		var thumbs =	function(id,thumb){
  			return	'<div class="col-md-2 col-sm-3 col-xs-6" style="margin-bottom:5px">'+
	  						'<div id="thumb-'+id+'" class="dropzone-thumbs thumb-uploading">'+
								'<img src="'+thumb+'">'+
	  						'<div class="dropzone-thumbs-progress">'+
		            '<div class="progress">'+
							  '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">'+
							  '<span class="progress-loaded"></span>'+
							  '</div></div></div></div></div>';
			};

		//Create image thumbs markup in gallery
		var gallery =	function(id,img,thumb){
				return  '<div id="gallery-'+id+'" class="col-md-2 col-xs-12" style="margin-bottom: 5px"><div class="gallery-item">'+
		      			'<button type="button" title="Remove photo" data-photo="'+id+'" class="btn btn-flat btn-danger btn-remove-gallery" data-action="remove_photo" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-times"></i></button>'+
		      			'<button type="button" title="Set as main" data-photo="'+id+'" class="btn btn-flat btn-warning btn-main-gallery" data-action="set_main" data-toggle="modal" data-target="#optionsPhotoModal"><i class="fa fa-star"></i></button>'+
								'<a href="'+img+'" data-fancybox="fancy-images">'+
		      			'<img class="img-responsive" src="'+thumb+'" alt="'+thumb+'">'+
		      			'</a>'+
								'</div></div>';
			};

  	//Check if browser support Drag and Drop uploads
		var isAdvancedUpload = function() {
		  var div = document.createElement('div');
		  return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
		}();

		//=========================================== Page fully load.
  	$(document).ready(function(){
  		//Get comments every 2seg
  		setInterval(getComments,2000);
  		//Get Logs every 5seg
  		setInterval(getLogs,5000);

  		$('#btn-select-files').click(function(){
  			$('#dropzone-input').click();
  		});

  		//Photos upload by input
  		$photos.change(function(){
  			uploadFilesList = this.files;
  			preview();
  		});

			//Check if browser support Drag and Drop
			if (isAdvancedUpload) {
			  $form.find('.dropzone').addClass('has-advanced-upload');

			  $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
			    e.preventDefault();
			    e.stopPropagation();
			  })
			  .on('dragover dragenter', function() {
			    $form.find('.dropzone').addClass('is-dragover');
			  })
			  .on('dragleave dragend drop', function() {
			    $form.find('.dropzone').removeClass('is-dragover');
			  })
			  .on('drop', function(e) {
			    uploadFilesList = e.originalEvent.dataTransfer.files;
			    preview();
  				//$form.trigger('submit');
			  });
			}else{

			}

			$('#changeStatusModal').on('show.bs.modal',function(event){
				var btn = $(event.relatedTarget);
				var opc = btn.data('opc');
				var modal = $(this);
				var status = project_status + (opc);

				modal.find('input[name="status"]').val(status);
			});

			//Add stock for Items or Templates
  		$('#addModal').on('show.bs.modal',function(event){
        var btn  = $(event.relatedTarget);
        var item = btn.data('item');
        var template = btn.data('template');

        var modal   = $(this);

        modal.find('#item').val(item);
        modal.find('#template').val(template);
      });
			
  		//Modal for 'Set photo as Main' or 'Delete photo'
  		//When modal opens, chance the options
  		$('#optionsPhotoModal').on('show.bs.modal',function(event){
        var btn    = $(event.relatedTarget),
        		photo  = btn.data('photo'),
        		action = btn.data('action'),
        		modal  = $(this),
        		submit = modal.find('.options-gallery');

        if(action === 'remove_photo'){
        	var text = 'Remove';
        	modal.find('.modal-title').text(text);
        	modal.find('#options-text').text('Are you sure you want to delete this photo?');
        	submit.removeClass('btn-warning').addClass('btn-danger');
        }else{
        	var text = 'Save';
        	modal.find('.modal-title').text('Set as main');
        	modal.find('#options-text').text('Set this photo as Main photo for the project?');
        	submit.removeClass('btn-danger').addClass('btn-warning');
        }

        submit.attr({'xid':photo,'xaction':action}).text(text);
      });

      //Send Ajax request to Delete photo or Set as Main
      $('.options-gallery').on('click',function(){
      	var btn    = $(this),
      	    id     = btn.attr('xid'),
      	    action = btn.attr('xaction'),
      	    alert = $('.alert-gallery');

      	$.ajax({
      		type: 'POST',
      		url: 'funciones/class.projects_gallery.php',
      		data: {
      			action: action,
      			id: id
      		},
      		dataType: 'json',
      		success: function(r){
      			if(r.response){
      				if(action === 'remove_photo'){
	      				$('#gallery-'+id).remove();

	      				if(r.data){
	      					$('.gallery-item-main').removeClass('gallery-item-main');
	      					$('#gallery-'+r.data.id_gallery+' .gallery-item').addClass('gallery-item-main');
	      					$('#project-main-photo').attr({'src':'images/thumbs/'+r.data.thumb,'alt':r.data.thumb});
	      				}
      				}else{
      					$('.gallery-item-main').removeClass('gallery-item-main');
      					$('#gallery-'+id+' .gallery-item').addClass('gallery-item-main');
      					$('#project-main-photo').attr({'src':'images/thumbs/'+r.data.thumb,'alt':r.data.thumb});
      				}
      				
      				alert.removeClass('alert-danger').addClass('alert-success');
      			}else{
      				alert.removeClass('alert-success').addClass('alert-danger');
      			}
    				alert.find('.msj').text(r.msj);
      		},
      		error: function(){
    				alert.removeClass('alert-success').addClass('alert-danger');
    				alert.find('.msj').text('An error has ocurred.');
      		},
      		complete: function(){
      			alert.show().delay(7000).hide('slow');
      			$('#optionsPhotoModal').modal('hide');
      		}
      	});
      });

			//Save new comments to database  		
  		$('#form-new-comment').submit(function(e){
  			e.preventDefault();

  			workingComments = true;

  			var form = $(this),
  					alert = form.find('.alert');

  			$.ajax({
  				type: 'POST',
  				cache: false,
  				url: 'funciones/class.projects_comments.php',
  				data: form.serialize(),
  				dataType: 'json',
  				success: function(r){
  					if(r.response){
  						form[0].reset();
  					}else{
  						alert.show().delay(5000).hide();
  					}
  				},
  				error: function(){
  					alert.show().delay(5000).hide();
  				},
  				complete: function(){
  					workingComments = false;
  				}
  			})
  		});

  		//Remove upload thumbs when modal close
  		$('#addPhotoModal').on('hide.bs.modal',function(){
  			$('.dropzone-thumbs-container').empty();
  		});

  		//Fancy box
  		$().fancybox({
  			selector: '[data-fancybox="fancy-images-1"],[data-fancybox="fancy-images-2"],[data-fancybox="fancy-images-3"],[data-fancybox="fancy-images-4"]',
  			loop: true,
			  buttons : [
		      'slideShow',
		      'fullScreen',
		      'thumbs',
		      'download',
	        'close'
	    	],
  		});
  		//=============================================

  	});//=============================================================================READY

		function uploadSinglePhoto(photo,i){
	    // ajax for modern browserss
		  var photoThumb = $('.dropzone .thumb-uploading').first(),
		  		next = (i+1),
		  		formData = new FormData();

		  formData.append('action','add_project_photo');
		  formData.append('project',project_id);
		  formData.append('status',project_status);
		  formData.append('photo',photo);

		  $.ajax({
		    url: $form.attr('action'),
		    type: $form.attr('method'),
		    data: formData,
		    dataType: 'json',
		    cache: false,
		    contentType: false,
		    processData: false,
        // Custom XMLHttpRequest
        xhr: function() {
          var myXhr = $.ajaxSettings.xhr();
          if (myXhr.upload) {
            // For handling the progress of the upload
            myXhr.upload.addEventListener('progress', function(e){
            	loaded = ((100*e.loaded)/e.total).toFixed(0);

              if(e.lengthComputable){
                photoThumb.find('.progress-bar')
	                .attr({
                		'aria-valuenow': loaded,
                    style: 'width:'+loaded+'%;'
	                });

                photoThumb.find('.progress-loaded').text(loaded+'%');

                if(e.loaded === e.total){
                	photoThumb.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-success');
                	photoThumb.find('.progress-loaded').text('Procesing...');
                }
              }
            }, false);
            //Error Listener
            myXhr.addEventListener('error', function (e) {
          		photoThumb.addClass('thumb-error');
            	photoThumb.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-danger');
            	photoThumb.find('.progress-loaded').text('Error');
            }, false);
          }
          return myXhr;
        },
		    success: function(r) {
		      if(r.response){
		      	thumb_img = gallery(r.data.id,r.data.photo,r.data.thumb);
		      	$('#gallery-body-'+project_status).append(thumb_img);
		      }else{
        		photoThumb.addClass('thumb-error');
          	photoThumb.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-danger');
          	photoThumb.find('.progress-loaded').text('Error');
		      }
		    },
		    error: function() {
      		photoThumb.addClass('thumb-error');
        	photoThumb.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-danger');
        	photoThumb.find('.progress-loaded').text('Error');
		    },
		    complete: function() {
		    	photoThumb.removeClass('thumb-uploading');
		    	uploading = false;
		    	uploadPhotos(next);
		    }
		  });
		}//uploadSinglePhoto

		function uploadPhotos(i = 0){
			if(!uploadFilesList[i] || uploading) return false;

			uploading = true;
			uploadSinglePhoto(uploadFilesList[i],i);
		}//uploadPhotos

		//Preview files when uploaded or Droped
	  function preview(){
	  	var thumbsCount = checkPreviewCount();
	  	$.each(uploadFilesList, function(i,file){
		    //Tippo de archivo
		    var type  = file.type;

		    if(file){
		      if(file.size<20000000){
		        if(type == 'image/jpeg' || type == 'image/png' || type == 'image/jpg'){
		          var reader = new FileReader();
		          reader.onload = function (e) {
		          	nextCount = (thumbsCount+(i+1));
		          	var thumb = thumbs(nextCount,e.target.result);
		          	$('.dropzone-thumbs-container').append(thumb);
		          	uploadPhotos();
		          }
		          reader.readAsDataURL(file);
		        }
		      }
		    }
		  })//EACH
	  }//Preview-----------------------------------------------------------------------------------

		//Check if there's any file uploaded in Dropzone
		function checkPreviewCount(){
			return $('.dropzone-thumbs-container .dropzone-thumbs').length;
		}

		//Get comments from database
  	function getComments(){
  		if(workingComments || uploading) return false;
  		workingComments = true;

  		$.ajax({
  			type: 'POST',
  			cache: false,
  			data: {action:'getComments',project:project_id,lastcomment:lastComment},
  			url: 'funciones/class.projects_comments.php',
  			dataType: 'json',
  			success: function(r){
  				if(r.new){
						$('#direct-chat-messages').append(r.comments);
						lastComment = r.last;
						scrollDown();
					}
  			},
  			complete: function(){
  				workingComments = false;
  			}
  		})
  	}//getComments

		//Get logs from database
  	function getLogs(){
  		if(workingLogs) return false;
  		workingLogs = true;

  		$.ajax({
  			type: 'POST',
  			cache: false,
  			data: {action:'getLogs',project:project_id,lastLog:lastLog},
  			url: 'funciones/class.projects_logs.php',
  			dataType: 'json',
  			success: function(r){
  				if(r.new){
						$('#timeline-logs').prepend(r.logs);
						lastLog = r.lastLog;
					}
  			},
  			complete: function(){
  				workingLogs = false;
  			}
  		})
  	}//getComments

  	//Scroll down the messages window when new comments appear
  	function scrollDown(){
  		var height = document.getElementById("direct-chat-messages").scrollHeight-150;
  		$('#direct-chat-messages').animate({
        scrollTop: height
      }, 1000);
  	}//scrollDown
  </script>

  <?
	break;
  case 'add':
  case 'edit':
  $inventory  = new Inventory();
  $categories = $inventory->consultaCategories();
  $project    = $projects->obtener($id);
  if($project){
	  $projectItems = $projects->items();
	  $itemsAdded   =  $projects->itemsArrayIds($projectItems);
  	$image = $projects->gallery->getMain()->thumb?:'x';	
  }else{
  	$image = '';
  	$itemsAdded = [];
  }
  ?>
    <div class="row">
    	<div class="col-md-9 col-sm-9 col-xs-12">
    		<div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Inventory Items</a></li>
              <li><a href="#tab_2" data-toggle="tab">Templates</a></li>
              <li class="pull-right">
              	<button class="btn btn-box-tool btn-template-toogle"><i class="fa fa-minus"></i></button>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              	<div class="row">
	  						<?foreach($categories AS $category){?>
	  							<div class="col-md-4">
	  								<h4><?=$category->icat_category?></h4>
	  								<ul class="list-group inventory-item-list">
		  								<?foreach($inventory->category->getItemsByCategory($category->id_category) AS $item){?>
		  									<li id="list-item-<?=$item->id_inventory?>" class="<?=in_array($item->id_inventory,$itemsAdded)?'bg-red disabled':''?> list-group-item">
					               	<span class="inventory-item-name"><?=$item->inv_name?> <?=" (<span class='inventory-item-stock'>{$item->inv_stock}</span> {$item->mea_unit})"?></span>

					               		<button xid="<?=$item->id_inventory?>" type="1" class="btn-link btn-box-tool btn-add-item"><i class="fa fa-plus"></i></button>
					               		
					              </li>
				              <?}?>
	  								</ul>
	  							</div>
	  						<?}?>
	  						</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
              	<div class="row">
              		<div class="col-md-4 col-md-offset-4 text-center">
              			<label for="search-template">Search template</label>
              			<input id="search-template"class="form-control" placeholder="Template name" type="text">
              		</div>  		
              	</div>
              	<hr>
			      		<div id="projects-template-list" class="row" style="margin:0">
		            </div>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
    		</div>
    		<div class="col-md-12">
    			<div id="box-projects" class="box box-poison">
			      <div class="box-header with-border">
			        <h3 class="box-title"><i class="fa fa-wrench"></i> <?=$id>0?'Edit':'New'?> Project</h3>
			      </div>
			      <div class="box-body">
			      	<form id="form-new-project" action="funciones/class.projects.php" method="POST" enctype="multipart/form-data">
			      		<input type="hidden" name="project" value="<?=$id?>">
			      		<input type="hidden" name="action" value="<?=$project?'edit_project':'add_project'?>">
			      		<input type="hidden" name="project-items" value="">
			      		<input id="project-templates" type="hidden" name="project-templates" value="">
			      		<div class="row">
			      			<div class="col-md-6">
			      				<div class="form-group">
					      			<label class="control-label" for="project-title">Project title: *</label>
					      			<input id="project-title" class="form-control" type="text" name="project-title" placeholder="Project title" value="<?=($project)?$project->title:''?>" required>
					      		</div>
			      			</div>
			      			<div class="col-md-6" style="border-left: 2px solid #eee;">
			      				<div class="form-group">
			      					<label for="file-img">Project image:</label>
                      <div class="imageUploadWidget">
                        <div class="imageArea">
                          <img id="project-img" src="<?=Base::Img('images/thumbs/'.$image);?>" alt="" prev="">
                          <img class="spinner-image" src="images/spinner.gif">
                        </div>
                        <div class="btnArea">
                        	<?if(!$project){?>
                          <input id="file-img" name="foto" accept="image/jpeg,image/png" type="file" >
                          <?}?>
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
			      						<?
			      							if($project){
				      							$i=1;
								  					foreach ($projectItems as $k => $d){
								  				?>
								  					<tr id="item-<?=$i?>" xid="<?=$d->item?>" type="1">
								  						<td class="text-center"><?=$i?></td>
								  						<td><?=$d->name?></td>
								  						<td>
								  							<div class="form-group">
								  								<input id="qty-<?=$d->item?>" class="form-control" placeholder="Qty" min="1" step="0.1" value="<?=$d->stock_needed?>" required="" type="number">
								  							</div>
								  						</td>
								  						<td class="text-center">
								  							<button row="item-<?=$id?>" class="btn-link btn-box-tool btn-delete-item" type="button">
								  								<i class="fa fa-times" aria-hidden="true" style="color:red"></i>
								  							</button>
								  						</td>
								  					</tr>
								  				<?
								  					$i++;
								  					}
							  					}
							  				?>
			      					</tbody>
			      				</table>
			      			</div>
			      			<div class="col-md-12">
			      				<h5><strong>Templates for this project:</strong></h5>
			      				<table class="table table-bordered table-hover table-condensed">
			      					<thead>
			      						<tr>
			      							<th width="10%">#</th>
			      							<th width="60%">Item</th>
			      							<th width="20%">Qty</th>
			      							<th width="10%">Action</th>
			      						</tr>
			      					</thead>			      					
			      					<tbody id="tbody-project-template-list">
			      						<?
			      							if($project){
				      							$i=1;
								  					foreach ($projects->templates() as $tempID => $template){
								  				?>
				      						<tr id="template-<?=$template->template.$i?>" class="active" xid="<?=$template->template?>" type="2">
														<td class="text-center"><?=$i?></td>
														<th class="text-center" colspan="2"><?=$template->name?></th>
														<td class="text-center">
															<button row="template-<?=$template->template.$i?>" class="btn-link btn-box-tool btn-delete-template" type="button">
																<i class="fa fa-times" aria-hidden="true" style="color:red"></i>
															</button>
														</td>
													</tr>
									  				<?
									  					foreach ($template->items as $k => $d){
									  				?>
															<tr class="template-<?=$template->template.$i?>">
																<td></td>
									    					<td><?=$d->name?></td>
									    					<td class="text-center"><?=$d->stock_needed?></td>
									    					<td></td>
									    				</tr>
									  				<?
									  					}	//foreach Items
									  				?>
							    				<?
								  					}//foreach Templates
								  					$i++;
								  				}
							  				?>
			      					</tbody>
			      				</table>
			      			</div>
			      			<div class="col-md-12">
	                  <div class="alert alert-dismissible" role="alert" style="display:none">
	                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
	                  </div>

	                  <div class="progress progress-sm active" style="display:none">
	                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
	                      <span class="sr-only">100% Complete</span>
	                    </div>
	                  </div>

			      				<div class="form-group">
			      					<button id="save-new-project" class="btn btn-flat btn-block btn-danger" type="submit" <?=$project?'':'disabled'?>><i class="fa fa-send" aria-hidden="true"></i> Save project</button>
			      				</div>
			      			</div>
			      		</div>
			      	</form>
			      </div>
			      <div class="overlay" style="display:none">
	            <i class="fa fa-refresh fa-spin"></i>
	          </div>
			    </div><!--box-->
    		</div>	        
      </div>

      <div class="col-md-3 col-sm-3 col-xs-12">
        <div id="box-template" class="box box-poison">
		      <div class="box-header with-border">
		        <h3 class="box-title">New Templates</h3>
		        <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
		      </div>
		      <div class="box-body">
	        	<form id="form-add-template" action="funciones/class.projects_templates.php" method="POST">
	        		<input type="hidden" name="action"  value="add_template">
	        		<input type="hidden" name="project-items">
	        		<div class="form-group">
        				<div class="input-group">
	                <input id="add-template-name" name="add-template-name" class="form-control" placeholder="Template" type="text" required>
	                <div class="input-group-btn">
	                  <button id="save-new-template" type="submit" class="btn btn-primary btn-flat" required disabled>Add</button>
	                </div>
	              </div><!-- /btn-group -->
	        		</div>
	        		<div class="alert alert-danger" style="display:none" role="alert">
	        			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj">An error has ocurred.</span>
	        		</div>
	        	</form>
		      </div>
		      <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
		    </div><!--box-->
      </div>
    </div>

    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="delModalTemplateForm" action="funciones/class.projects_templates.php" method="POST">
            <input type="hidden" name="action" value="delete_template">
            <input id="template" type="hidden" name="template" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Delete Template</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">Are you sure you want to <b>delete</b> this Template?</h4>
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
              <button id="b-del" type="submit" class="btn btn-flat btn-danger pull-left">Delete</button>
              <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    		$('#file-img').change(preview);

    		//Add items from inventory to the project
    		$('.inventory-item-list .btn-add-item').on('click',addProjectItem);
    		//Delete Items added to the project
    		$('#tbody-project-items-list').on('click','.btn-delete-item',deleteProjectItem);
    		//Save Project
    		$('#form-new-project').submit(save);
    		//Save Template
    		$('#form-add-template').submit(saveTemplate);
    		//Delete Template
    		$('#delModalTemplateForm').submit(deleteTemplate);

    		loadTemplates();

    		$('#projects-template-list').on('click','.btn-add-template',addProjectTemplate);
    		//Delete Templates added to project
    		$('#tbody-project-template-list').on('click','.btn-delete-template',deleteProjectTemplate);

    		$('#delModal').on('show.bs.modal',function (event){
    			var btn   = $(event.relatedTarget),
    					id    = btn.data('id'),
    					modal = $(this);

    			modal.find('#template').val(id);
    		});

    		//Show and hide Inventory and Template panel
    		$('.btn-template-toogle').on('click',function(){
    			$('.tab-content').slideToggle();
    			$(this).find('.fa').toggleClass('fa-minus fa-plus')
    		})

    		//Seach template
    		$('#search-template').on('keyup',function(){
    			var keyword = $(this).val(),
    					$templates = $('.list-template-container');

    			if(keyword.length > 1){
    				$templates.hide();
    				$.each($templates,function(i,element){
    					var text = $(element).find('b').text().toLowerCase()
    					if(text.indexOf(keyword) >= 0){
    						$(element).show()
    					}
    				})
    			}

    			//If no search term, show all templates
    			if(keyword.length == 0){
    				$templates.show();
    			}
    		})
    	});//Ready

    	function addProjectTemplate(){
    		var btn = $(this),
    				id  = btn.attr('xid'),
    				loading_temp = $('#box-template .overlay'),
    				loading_box = $('#box-projects .overlay'),
    				alert   = $('#box-projects .alert');

    		loading_box.show();
    		loading_temp.show();

    		$.ajax({
    			type: 'POST',
    			url: 'funciones/class.projects_templates.php',
    			data: {action:'getTemplate',template:id},
    			dataType: 'json',
    			success: function(r){
    				if(r.response){
    					$('#tbody-project-template-list').append(r.data);
    					fixTemplatesCount();
    				}else{
	            alert.removeClass('alert-success').addClass('alert-danger');
	            alert.find('#msj').text('An error has occurred.').show().delay(7000).hide('slow');
    				}
    			},
    			error: function(){
            alert.removeClass('alert-success').addClass('alert-danger');
            alert.find('#msj').text('An error has occurred.').show().delay(7000).hide('slow');
    			},
    			complete: function(){
    				loading_box.hide();
    				loading_temp.hide();
    			}
    		})
    	}

    	function addProjectItem(){
    		var parent = $(this).parent().parent(),
    				name   = parent.text().trim(),
    				//Inventory ID
    				xid    = $(this).attr('xid'), 
    				type   = $(this).attr('type'),
    				tbody  = $('#tbody-project-items-list'),
    				num    = $('#tbody-project-items-list tr[type="1"]').length+1,
    				stock  = parent.find('.inventory-item-stock').text().trim()*1;

    		var tr ='<tr id="item-'+num+'" xid="'+xid+'" type='+type+'><td class="text-center">'+num+'</td>';
    			tr  +='<td>'+name+'</td>';
    			tr  +='<td><div class="form-group"><input id="qty-'+xid+'" class="form-control" type="number" placeholder="Qty" min="1" step="0.1" value="1" required></div></td>';
    			tr  +='<td class="text-center"><button row="item-'+num+'" class="btn-link btn-box-tool btn-delete-item" type="button"><i class="fa fa-times" aria-hidden="true" style="color:red"></i></button></td>';
    			tr  +='</tr>';

    		parent.attr('class','bg-red disabled list-group-item');
    		$('#save-new-template,#save-new-project').prop('disabled',false);

    		tbody.append(tr);
    	}

    	function deleteProjectItem(){
    		var row = $(this).attr('row'),
    				xid = $('#'+row).attr('xid'),
    				li = $('.inventory-item-list').find('#list-item-'+xid);

    		li.attr('class','list-group-item');
    		$('#'+row).remove();
    		fixItemsCount();
    	}

    	function fixItemsCount(){
    		var items = $('#tbody-project-items-list tr[type="1"]');

    		$.each(items, function(k,v){
    			$(v).find('td').first().text(k+1);
    			$(v).attr('id','item-'+(k+1));
    			$(v).find('button').attr('row','item-'+(k+1));
    		});

	    	toggleSaveButtons();
    	}//FixItemsCount

    	function deleteProjectTemplate(){
    		var id = $(this).attr('row');
    		$('#'+id+',.'+id).remove();
    		fixTemplatesCount();
    	}//deleteProjectTemplate

    	function fixTemplatesCount(){
    		var templates = $('#tbody-project-template-list tr[type="2"]');

    		$.each(templates, function(k,v){
    			var xid   = $(this).attr('xid'),
  						id    = this.id,
  						newid = 'template-'+xid+(k+1);

    			$(v).find('td').first().text(k+1);
    			$(v).attr('id',newid);
    			$(v).find('button').attr('row',newid);
    			$('#tbody-project-template-list tr.'+id).attr('class',newid);
    		});

    		toggleSaveButtons();
    	}//fixTemplatesCount

    	function toggleSaveButtons(){
    		var templates = $('#tbody-project-template-list tr[type="2"]').length;
    		var items = $('#tbody-project-items-list tr[type="1"]').length;
    		//if there is at least 1 template or item added.. Activate the save buttons
    		var toggle = !(templates>0 || items>0);
    		$('#save-new-template,#save-new-project').prop('disabled',toggle);
    	}

    	//Almacenar los items en como texto en formato JSON en un campo hidden
    	//Para enviar por el formulario
    	//Esta funcion guarda para los Templates y para los Proyectos
			function storeItems(form){
				var items = [];

				$('#tbody-project-items-list tr[type="1"]').each(function(k,v){
					var tr   = $(this);//tr del item
					var id   = tr.attr('xid');//ID del Item (Inventory)
					var qty  = tr.find('#qty-'+id).val();//Cantidad
					var prod = {item:id,stock_needed:qty};

					items[k] = prod;
				});

				$('#'+form+' input[name="project-items"]').val(JSON.stringify(items));
			}

			//Almacenar los templates en como texto en formato JSON en un campo hidden
    	//Para enviar por el formulario
			function storeTemplates(){
				var templates = [];
				$('#tbody-project-template-list tr[type="2"]').each(function(k,v){
					var tr   = $(this);//tr del item
					var id   = tr.attr('xid');//ID del Item (Inventory)
					var prod = {id:id};

					templates[k] = prod;
				});
				$('#project-templates').val(JSON.stringify(templates));
			}

			//Save project
			function save(e){
        e.preventDefault();

        var id = this.id;

        storeItems(id);
        storeTemplates();

        var form = $(this),
        		url  = form.attr('action'),
        		formdata = new FormData(form[0]),
        		alert = form.find('.alert'),
        		progress = form.find('.progress'),
        		btn  = form.find('#save-new-project'),
        		errors = 0;

        btn.button('loading');

        $.each($('#tbody-project-items-list>tr input'),function(k,v){
        	if($(v).val() <= 0){
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
      }//Save project

      //Load templates
    	function loadTemplates(){
    		var loading = $('#box-template .overlay'),
    		    alert   = $('#form-add-template .alert'),
    		    itemList = $('#projects-template-list');

    		loading.show();

    		$.ajax({
    			type: 'POST',
    			url: 'funciones/class.projects_templates.php',
    			data: {action:'loadTemplates'},
    			dataType: 'json',
    			success: function(r){
    				if(r.response){
    					itemList.empty();
    					itemList.html(r.data);
    				}else{
    					alert.show().delay(7000).hide('slow');
    				}
    			},
    			error: function(){
    				alert.show().delay(7000).hide('slow');
    			},
    			complete: function(){
    				loading.hide();
    			}
    		})
    	}

      //Save Template
      function saveTemplate(e){
      	e.preventDefault();

      	var id = this.id;
     
        storeItems(id);

      	var form    = $(this),
      			alert   = form.find('.alert'),
      			loading = form.find('#box-template .overlay'),
      			url     = form.attr('action'),
      			btn     = form.find('input[type="submit"]');

      	btn.button('loading');
      	loading.show();

      	$.ajax({
      		type: 'POST',
      		cache: false,
      		url: url,
      		data: form.serialize(),
      		dataType: 'json',
      		success: function(r){
      			if(r.response){
      				form[0].reset();
      				loadTemplates();
      			}else{
      				alert.show().delay(7000).hide('slow');
      			}
      		},
      		error: function(){
      			alert.show().delay(7000).hide('slow');
      		},
      		complete: function(){
      			loading.hide();
      			btn.button('reset');
      		}
      	})
      }//SaveTemplate

      //Delete Template
    	function deleteTemplate(e){
    		e.preventDefault();
    		var form = $(this),
    				alert = form.find('.alert'),
    				loading = form.find('.progress');

    		loading.show();
    		alert.hide();

    		$.ajax({
    			type: 'POST',
    			url: 'funciones/class.projects_templates.php',
    			data: form.serialize(),
    			dataType: 'JSON',
    			success: function(r){
    				if(r.response){
              alert.removeClass('alert-danger').addClass('alert-success');
	    				loadTemplates();
	    			}else{
	    				alert.removeClass('alert-success').addClass('alert-danger');
	    			}

    				alert.find('#msj').text(r.msj);
    			},
    			error: function(){
    				alert.find('#msj').text('An error has ocurred.');
    				alert.removeClass('alert-success').addClass('alert-danger');
    			},
    			complete: function(){
    				loading.hide();
    				alert.show().delay(7000).hide('slow');
    			}
    		})
    	}//Delete Template

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
		    }else{ img.parent().parent().removeClass('has-error'); }
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