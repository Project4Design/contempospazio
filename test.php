<?

require_once 'config/config.php';
require_once 'funciones/abeautifulsite/SimpleImage.php';

if(isset($_FILES['photo'])){
	$img   = new abeautifulsite\SimpleImage($_FILES['photo']['tmp_name']);
	//$tmp = $img->load($_FILES['photo'],true)
	$name = sha1(mt_rand().time());
	$name = $name.".png"; #.$ext;
	$thumbname = $name."_thumb.png"; #.$ext;

	$width = $img->get_width();
	$height = $img->get_height();

	if($width > $height ){
		$height = $width;
	}else{
		$width = $height;
	}

	$img->best_fit($width,$height);

	$final = new abeautifulsite\SimpleImage(null, $width, $height, '#fff');

	$o = $final->overlay($img, 'center', 1,0,0);

	$final->save('images/'.$name);
	$final = NULL;
	$o->thumbnail(300);
	$o->save('images/'.$thumbname);

	$o = NULL;
}
?>

<!DOCTYPE html>
<html>
<head>
<?=Base::Css("includes/css/bootstrap.min.css")?>
<?=Base::Js("includes/js/jquery-2.2.1.min.js")?>
</head>
<body>
	<form action="" method="POST" enctype="multipart/form-data">
		<input type="file" name="photo">
		<input type="submit">
	</form>
	<script type="text/javascript">
		$(document).ready(function(){
		});
	</script>
</body>
</html>