<?

require_once 'config/config.php';
require_once 'funciones/abeautifulsite/SimpleImage.php';

// include composer autoload
require 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

if(isset($_FILES['photo'])){
	// create an image manager instance with favored driver
	$manager = new ImageManager();

	// to finally create image instances
	$image = $manager->make($_FILES['photo']['tmp_name']);
	// resize image to fixed size

	$image->resize($width, $height, function ($constrain){
		$constrain->aspectRatio();
	});
/*
	// resize only the width of the image
	$image->resize(300, null);

	// resize only the height of the image
	$image->resize(null, 200);

	// resize the image to a width of 300 and constrain aspect ratio (auto height)
	$image->resize(300, null, function ($constraint) {
	    $constraint->aspectRatio();
	});

	// resize the image to a height of 200 and constrain aspect ratio (auto width)
	$image->resize(null, 200, function ($constraint) {
	    $constraint->aspectRatio();
	});
	*/
	$image->save('images/xxx.png');
	$image->destroy();
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