<?
require_once 'config/config.php';

echo base64_decode("OTgy");

?>

<!DOCTYPE html>
<html>
<head>
<?=Base::Css("includes/css/bootstrap.min.css")?>
<form action="test.php" method="post" enctype="multipart/form-data">
	<input type="file" name="foto">
	<input type="submit">
</form>
</head>
<body>
</body>
</html>