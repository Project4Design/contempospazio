<?
require_once 'config/config.php';

$items     = json_decode('[{"id":"0","content":"Test"},{"id":"1","content":"TWO"}]');

foreach ($items as $k => $v) {
	echo var_dump($k)."<br>";
	echo var_dump($v)."<br>";
	echo $v->id."<br>";
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
	</script>
</body>
</html>