<?

require_once 'config/config.php';
$a = ['id'=>1,'x'=>222,'c'=>[]];
$c = ['item1'=>['id'=>'ascasc','name'=>'2wwd','sme'=>'casc'],'item2'=>['id'=>'ascasc','name'=>'2wwd','sme'=>'casc'],'item3'=>['id'=>'ascasc','name'=>'2wwd','sme'=>'casc']];
$a['c'] = $c;
echo "<pre>";
var_dump($a);
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