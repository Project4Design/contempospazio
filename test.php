<?
require_once 'config/config.php';
$view = "views". DS . "statistics.php";
if(is_readable($view)){
echo "si";
}else{
echo "no";
}
?>

<!DOCTYPE html>
<html>
<head>
<?=Base::Css("includes/css/bootstrap.min.css")?>
<?=Base::Js("includes/js/jquery-2.2.1.min.js")?>
</head>
<body>

	<script type="text/javascript">
		$(document).ready(function(){
		});


		function addArray(){

		/*
			var products = {"type":1,"id":1,"qty":4};
			var products2 = {"type":5,"id":5,"qty":5};
			var array = {};
			array[0] = products;
			array[1] = products2;
			console.log(array);
			$('#products').val(JSON.stringify(array));
			var  x = $('#products').val();
			console.log(x);
			/*console.log(x);
			$.each(x,function(k,v){
				console.log(this);
			});*/
		}
	</script>
</body>
</html>