<?
require_once 'config/config.php';

$x = array('a'=>'first', 'b'=>'second', 'c'=>'third');
foreach ($x as &$a) echo $a;
//unset($a);
foreach ($x as $a){};
echo "<pre>";
print_r($x);
echo "</pre>";


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