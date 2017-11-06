<section class="content-header">
  <h1> Forbidden </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Forbidden</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="error-page">
    <h2 class="headline text-yellow" style="float:none;"> Forbidden</h2>

    <div class="error-content" style="margin:0">
      <h3><i class="fa fa-warning text-yellow"></i> Are you lost?.</h3>
      <p>
        You are not allowed to view this page.
        You will be redirected in a few seconds. Or
        You may <a href="index.php">return to Home</a>.
      </p>

    </div><!-- /.error-content -->
  </div><!-- /.error-page -->
</section><!-- /.content -->


<script type="text/javascript">
	function redirect_main(){
		location.replace('index.php');
	}

	setTimeout('redirect_main()',3000);
</script>